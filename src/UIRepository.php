<?php
/**
 * Created by admin.
 * User: realeff
 * Date: 18-1-15
 * Time: 上午8:14
 */

namespace BaiSam\UI;

use ArrayAccess;

/**
 * Class ResourceManager
 *
 * 注册跟UI有关的view模板及前端文件（javascript/styles），编译并输出前端相关内容
 * 提供UI操作有关的公共方法
 *
 * @package BaiSam\UI
 */
class UIRepository implements ArrayAccess
{
    use ManagesResources;

    const STYLE_DEFAULT       = 'default';
    const STYLE_INLINE        = 'inline';
    const STYLE_CIRCLE        = 'circle';

    const STYLE_SIZE_LARGE    = 'large';
    const STYLE_SIZE_SMALL    = 'small';
    const STYLE_SIZE_MINI     = 'mini';
    const STYLE_SIZE_TINY     = 'tiny';

    const STYLE_COLOR_PRIMARY = 'primary';
    const STYLE_COLOR_SUCCESS = 'success';
    const STYLE_COLOR_INFO    = 'info';
    const STYLE_COLOR_WARNING = 'warning';
    const STYLE_COLOR_DANGER  = 'danger';
    const STYLE_COLOR_WHITE   = 'white';
    const STYLE_COLOR_LINK    = 'link';

    /**
     * Laravel application
     *
     * @var \Illuminate\Foundation\Application
     */
    protected $app;

    /**
     * @var string
     */
    protected $baseUrl = '';

    /**
     * @var array
     */
    protected $styles = [];

    /**
     * @var array
     */
    protected $scripts = [];

    /**
     * Layout resources.
     *
     * @var array
     */
    protected $resources = [
        'jquery'        => ['js/jquery.js', 'scope' => 'header'],
        'jquery.min'    => ['js/jquery.min.js', 'scope' => 'header'],
        'bootstrap'     => ['css/bootstrap.css', 'js/bootstrap.js', 'scope' => 'header'],
        'bootstrap.min' => ['css/bootstrap.min.css', 'js/bootstrap.min.js', 'scope' => 'header']
    ];

    /**
     * @var bool
     */
    protected $mini = false;

    /**
     * @var
     */
    protected $uuid;

    /**
     * Create a new confide instance.
     *
     * @param \Illuminate\Foundation\Application $app
     * @param array $resources
     *
     * @return void
     */
    public function __construct($app, $resources)
    {
        $this->app = $app;

        if ($resources instanceof self) {
            $this->baseUrl = $resources->baseUrl;
            $this->resources = $resources->resources instanceof self ? $resources->resources : $resources;
        }
        else if (is_array($resources)) {
            $this->resources = array_merge($this->resources, (array)$resources);
        }

        //TODO 生成唯一资源编号
        $this->uuid = uniqid();
        $this->mini = $this->app->environment() == 'production';

        // Share resource.
        $this->shareToView();
    }

    /**
     * Set base url for the repository.
     *
     * @param string $baseUrl
     *
     * @return void
     */
    public function setBaseUrl($baseUrl)
    {
        $this->baseUrl = rtrim($baseUrl, '/');
    }

    /**
     * Register the resources.
     *
     * @param string $name
     * @param string|array $resources
     */
    public function register($name, $resources)
    {
        $this->resources[$name] = (array)$resources;

        // Rebuild resource.
        if (isset($this->styles[$name]) || isset($this->scripts[$name])) {
            unset($this->styles[$name], $this->scripts[$name]);

            $this->requireResource($name);
        }
    }

    /**
     * Register the resource, if the resource already exists, skip.
     *
     * @param string $name
     * @param string|array $resources
     */
    public function registerIf($name, $resources)
    {
        if (!isset($this->resources[$name])) {
            $this->register($name, $resources);
        }
    }

    /**
     * Remove the resource
     *
     * @param string $name
     *
     * @return void
     */
    public function remove($name)
    {
        if (isset($this->resources[$name])) {
            unset($this->resources[$name]);

            //TODO Remove build resource.
            //TODO Remove all depend resources.
        }
    }

    /**
     * Share the resource to view, and set uuid.
     */
    protected function shareToView()
    {
        // Share the resource.
        $this->app->make('view')
            ->share('__resource', $this);

        // Set the resource id for the current request.
        $this->app->make('request')
            ->attributes->set('__resource_uuid', $this->uuid);
    }

    /**
     * Get resource instance.
     *
     * @return \BaiSam\UI\UIRepository
     */
    public function getInstance()
    {
        $request = $this->app->make('request');

        if ($request->attributes->get('__resource_uuid', 0) === 0) {
            $instance = new static($this->app, $this);
            $this->app->instance('resources', $instance);
            // Flush require.
            $this->flush();
        }

        return $this->app->make('resources');
    }

    /**
     * Flush resources.
     *
     * @return void
     */
    public function flush()
    {
        $this->styles = [];
        $this->scripts = [];

        $this->flushStack();
    }

    /**
     * Require the resource
     *
     * @param string $name
     *
     * @return $this
     */
    public function requireResource($name)
    {
        if (empty($name) || isset($this->styles[$name]) || isset($this->scripts[$name]) ) {
            return;
        }

        if ( isset($this->resources[$name]) ) {
            //TODO 如果加载依赖关系已经缓存，则直接从缓存还原。
            //TODO 根据依赖关系，调整优先加载顺序.
            foreach ($this->resources[$name] as $scope => $resource) {
                // Load depend resource or mini resource, example: jquery or jquery.min.
                if ( $this->mini && isset($this->resources[$resource.'.min']) ) {
                    $this->requireResource($name, $resource.'.min');
                }
                else if ( isset($this->resources[$resource]) ) {
                    $this->requireResource($resource);
                }
                else {
                    // Load css or js...
                    $this->resource($name, $resource, $scope);
                }
            }
        }

        return $this;
    }

    /**
     * Load the resource.
     *
     * @param string $name
     * @param string $path
     *
     * @return void
     */
    protected function resource($name, $path, $scope)
    {
        // Set the use scope of the resource.
        if ( $scope === 'scope' ) {
            if (!isset($this->styles[$name])) {
                $this->styles[$name] = array();
            }
            if (!isset($this->scripts[$name])) {
                $this->scripts[$name] = array();
            }

            $this->styles[$name][$scope] = (string)$path;
            $this->scripts[$name][$scope] = (string)$path;
            return;
        }

        $parse = parse_url($path);
        if ( empty($parse['path']) ) {
            return;
        }

        if ( empty($parse['host']) && $path[0] != '/' ) {
            $path = $this->baseUrl .'/'. $path;
        }

        $type = pathinfo($parse['path'], PATHINFO_EXTENSION);
        switch ($type) {
            case 'css':
                $this->enqueueStyle($name, $path);
                break;
            case 'js':
                $this->enqueueScript($name, $path);
                break;
            default:
                // Init styles and scripts.
                if ( !isset($this->styles[$name]) ) {
                    $this->styles[$name] = array();
                }
                if ( !isset($this->scripts[$name]) ) {
                    $this->scripts[$name] = array('before' => array());
                }
                break;
        }
    }

    /**
     * Enqueue a CSS stylesheet.
     *
     * @param string $handle
     * @param string $href
     * @param string|boolean $ver
     *
     * @return $this
     */
    public function enqueueStyle($handle, $href, $ver = false)
    {
        if ( !isset($this->styles[$handle]) ) {
            $this->styles[$handle] = array();
        }
        if ( !isset($this->styles[$handle]['href']) ) {
            $this->styles[$handle]['href'] = array();
        }

        // 追加版本号
        if ( $ver ) {
            $href = $href .(strpos($href, '?') !== false ? '?' : '&').'ver='.$ver;
        }

        $this->styles[$handle]['href'][] = $href;

        return $this;
    }

    /**
     * Add extra CSS styles to a registered stylesheet.
     *
     * @param string $handle
     * @param string $style
     * @param string $tag
     *
     * @return $this
     */
    public function inlineStyle($handle, $style, $tag = null)
    {
        if ( !isset($this->styles[$handle]) ) {
            $this->styles[$handle] = array();
        }
        if ( !isset($this->styles[$handle]['style']) ) {
            $this->styles[$handle]['style'] = array();
        }

        if ( false !== stripos( $style, '</style>' ) ) {
            $style = trim( preg_replace( '#<style[^>]*>(.*)</style>#is', '$1', $style ) );
        }

        if ( isset($tag) ) {
            $this->styles[$handle]['style'][$tag] = $style;
        }
        else {
            $this->styles[$handle]['style'][] = $style;
        }

        return $this;
    }

    /**
     * Enqueue a script.
     *
     * @param string $handle
     * @param string $src
     * @param boolean|string $ver
     *
     * @return $this
     */
    public function enqueueScript($handle, $src, $ver = false)
    {
        if ( !isset($this->scripts[$handle]) ) {
            $this->scripts[$handle] = array();
        }
        if ( !isset($this->scripts[$handle]['src']) ) {
            $this->scripts[$handle]['src'] = array();
        }

        // 追加版本号
        if ( $ver ) {
            $src = $src .(strpos($src, '?') !== false ? '?' : '&').'ver='.$ver;
        }

        $this->scripts[$handle]['src'][] = $src;

        return $this;
    }

    /**
     * Adds extra code to a registered script.
     *
     * @param string $handle
     * @param string $script
     * @param string $tag
     * @param boolean $prepend
     *
     * @return $this
     */
    public function inlineScript($handle, $script, $tag = null, $prepend = false)
    {
        $position = $prepend ? 'before' : 'after';

        if ( !isset($this->scripts[$handle]) ) {
            $this->scripts[$handle] = array();
        }
        if ( !isset($this->scripts[$handle][$position]) ) {
            $this->scripts[$handle][$position] = array();
        }

        if ( false !== stripos( $script, '</script>' ) ) {
            $script = trim( preg_replace( '#<script[^>]*>(.*)</script>#is', '$1', $script ) );
        }

        if ( isset($tag) ) {
            $this->scripts[$handle][$position][$tag] = $script;
        }
        else {
            $this->scripts[$handle][$position][] = $script;
        }

        return $this;
    }

    public function styles($return = false, $position = null, $force = false)
    {
        //$cache_id = 'style_'. md5(implode(' ', array_keys($this->styles)));
        //if (!$output = cache($cache_id)) {
        ob_start();

        foreach ($this->styles as $handle => $style) {
            // 检查position渲染指定范围的样式
            if ( isset($position) && (!isset($style['scope']) || $style['scope'] !== $position) ) {
                continue;
            }

            // 默认过滤已经渲染过的样式
            if ( !$force && isset($style['render']) ) {
                continue;
            }

            //TODO 过滤重复项

            if ( isset($style['href']) ) {
                collect($style['href'])->each(function ($src) {
                    echo "<link rel='stylesheet' href='".asset($src)."'>\n";
                });
            }

            if ( isset($style['style']) ) {
                echo "<style type='text/css'>\n";
                echo implode("\n", $style['style']);
                echo "</style>\n";
            }

            // 标识已经渲染
            $style['render'] = true;
        }

        $output = ob_get_clean();

        // Cache styles
        //    cache([$cache_id => $output], 3600);
        //}

        if ($return)
            return $output;

        echo $output;
    }

    public function scripts($position = null, $return = false, $force = false)
    {
        //$cache_id = $position .'_script_'. md5(implode(' ', array_keys($this->scripts)));
        //if (!$output = cache($cache_id)) {
        ob_start();

        foreach ($this->scripts as $handle => $script) {
            // 检查position渲染指定范围的脚本
            if (isset($position) && (!isset($script['scope']) || $script['scope'] !== $position)) {
                continue;
            }

            // 默认过滤已经渲染过的脚本
            if (!$force && isset($script['render'])) {
                continue;
            }

            // 渲染脚本
            $this->script($handle);

            // 标识已经渲染
            $script['render'] = true;
        }

        $output = ob_get_clean();

        // Cache scripts
        //   cache([$cache_id => $output], 3600);
        //}

        if ( $return )
            return $output;

        echo $output;
    }

    public function script($handle, $return = false) {
        if ( !isset($this->scripts[$handle]) ) {
            return;
        }

        ob_start();

        //TODO 过滤重复项

        // before script
        $this->extraScript($handle, 'before', true);

        if (isset($this->scripts[$handle]['src'])) {
            collect($this->scripts[$handle]['src'])->each(function ($src) {
                echo "<script type='text/javascript' src='".asset($src)."'></script>\n";
            });
        }

        // after script
        $this->extraScript($handle, 'after', true);

        $output = ob_get_clean();

        if ( $return )
            return $output;

        echo $output;
    }

    /**
     * Prints extra scripts of a registered script.
     *
     * @param string $handle The script's registered handle.
     * @param string $position
     * @param bool   $echo   Optional. Whether to echo the extra script instead of just returning it.
     *                       Default true.
     * @return bool|string|void Void if no data exists, extra scripts if `$echo` is true, true otherwise.
     */
    protected function extraScript($handle, $position = null, $echo = false) {
        if ( !isset($this->scripts[$handle]) || !isset($this->scripts[$handle][$position]) )
            return;
        if ( !$output = implode(";\n", $this->scripts[$handle][$position]) )
            return;

        if ( !$echo )
            return $output;

        echo "<script type='text/javascript'>\n"; // CDATA and type='text/javascript' is not needed for HTML 5
        echo "/* <![CDATA[ */\n";
        echo "$output;";
        echo "/* ]]> */\n";
        echo "</script>\n";

        return true;
    }

    /**
     * Determine if an resource exists at an offset.
     *
     * @param  mixed  $key
     * @return bool
     */
    public function offsetExists($key)
    {
        return array_key_exists($key, $this->resources);
    }

    /**
     * Get an item at a given offset.
     *
     * @param  mixed  $key
     * @return mixed
     */
    public function offsetGet($key)
    {
        return $this->resources[$key];
    }

    /**
     * Set the resource at a given offset.
     *
     * @param  mixed  $key
     * @param  mixed  $value
     * @return void
     * @throws \InvalidArgumentException
     */
    public function offsetSet($key, $value)
    {
        if (is_null($key)) {
            throw new InvalidArgumentException('The resource name must be defined.');
        } else {
            $this->resources[$key] = $value;
        }
    }

    /**
     * Unset the resource at a given offset.
     *
     * @param  string  $key
     * @return void
     */
    public function offsetUnset($key)
    {
        unset($this->resources[$key]);
    }

}