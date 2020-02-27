<?php
/**
 * Builder.php
 * User: realeff
 * Date: 17-11-12
 */

namespace BaiSam\UI\Layout;


use Closure;
use BaiSam\UI\Element;
use BaiSam\UI\Layout\Component\Sidebar;
use BaiSam\UI\Layout\Component\Navbar;
use BaiSam\UI\Layout\Component\Navigation;
use BaiSam\UI\Layout\Component\Breadcrumb;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Http\Response;
use Illuminate\Container\Container;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Contracts\Support\Responsable;

/**
 * 页面布局类
 *
 * @package BaiSam\UI\Layout
 */
class Builder extends Element implements Renderable, Responsable
{
    /**
     * Layout the name.
     *
     * @var string
     */
    protected $name;

    /**
     * The title.
     *
     * @var string
     */
    protected $title = '';

    /**
     * @var string
     */
    protected $description;

    /**
     * @var string
     */
    protected $help;

    /**
     * @var array
     */
    protected $meta = [];

    /**
     * Laravel application
     *
     * @var Container
     */
    protected $app;

    /**
     * @var Content
     */
    protected $content;

    /**
     * @var Row[]
     */
    protected $rows = [];

    /**
     * @var Sidebar[]
     */
    protected $sidebar = [];

    /**
     * @var Navbar[]
     */
    protected $navbar = [];

    /**
     * @var Navigation
     */
    protected $navigation;

    /**
     * @var Breadcrumb
     */
    protected $breadcrumb;

    /**
     * @var array
     */
    protected $footer = [];

    /**
     * Configuration information for the layout.
     *
     * @var array
     */
    protected $config = [];

    /**
     * The render for the layout.
     * @var bool
     */
    protected $rendered = false;

    /**
     * @var string 
     */
    protected $type = 'layout';

    /**
     * View for layout to render.
     *
     * @var string
     */
    protected $view = null;


    /**
     * Builder constructor.
     *
     * @param Container $container
     * @param string $name
     */
    public function __construct(Container $container, string $name = null)
    {
        if (isset($name)) {
            parent::__construct($name);

            $this->name = $name;
            $this->title = Str::title($name);

            // 加载布局配置
            $this->config = config('ui.layouts.'. $name, []);
        }

        $this->app = $container;
        $this->content = new Content();

        if (!empty($this->config)) {
            // 设置渲染模板
            $this->view = Arr::get($this->config, 'view', null);
            // 加载默认内容
            $this->loadDefaultNavbar();
            $this->loadDefaultSidebar();
            $this->loadDefaultFooter();
        }
    }

    /**
     * Get the layout name.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set the title.
     *
     * @param string $title
     * @return $this
     */
    public function title($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Set the sub title.
     *
     * @param string $help
     * @return $this
     */
    public function help($help)
    {
        $this->help = $help;

        return $this;
    }

    /**
     * Set the description
     *
     * @param string $description
     * @return $this
     */
    public function description($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Add the meta.
     *
     * @param string $name
     * @param mixed $content
     * @return $this
     */
    public function addMeta($name, $content)
    {
        $this->meta[$name] = $content;

        return $this;
    }

    /**
     * Set the content for layout.
     *
     * @param mixed|null $content
     * @return Content
     */
    public function content($content = null)
    {
        if (isset($content)) {
            $this->content->assign($content);
        }

        return $this->content;
    }

    /**
     * If the row have exists, the row is set as new content.
     *
     * @return Content
     */
    protected function getContent()
    {
        //提取内容部分
        if ($this->hasRows()) {
            $this->content->assign($this->rows);
        }

        return $this->content;
    }

    /**
     * Add a row.
     *
     * @param mixed $content
     * @return \BaiSam\UI\Layout\Row
     */
    public function row($content = null)
    {
        if (is_int($content) && isset($this->rows[$content])) {
            return $this->rows[$content];
        }

        // Generate the row id.
        $id = 'row_'. count($this->rows);

        if ($content instanceof Closure) {
            $row = new Row($id);
            call_user_func($content, $row);
        }
        else {
            $row = new Row($id, $content);
        }

        $this->addRow($row);

        return $row;
    }

    /**
     * Add a row for layout.
     *
     * @param Row $row
     */
    protected function addRow(Row $row)
    {
        if (isset($this->id)) {
            // Set prefix for row.
            $row->setPrefix($this->formatId());
        }

        $this->rows[] = $row;
    }

    /**
     * Have the row.
     *
     * @return boolean
     */
    public function hasRows()
    {
        return !empty($this->rows);
    }

    /**
     * Get all rows.
     *
     * @return Row[]
     */
    public function rows()
    {
        return $this->rows;
    }

    /**
     * Empty the layout.
     */
    public function empty()
    {
        $this->rows = [];
        $this->content = new Content();
        $this->breadcrumb = null;
        $this->navigation = null;
    }

    /**
     * 加载默认导航栏内容
     */
    protected function loadDefaultNavbar()
    {
        $keys = array_keys(Arr::get($this->config, 'navbar', []));
        foreach ($keys as $key) {
            $this->navbar($key);
        }
    }

    /**
     * 加载默认侧边栏内容
     */
    protected function loadDefaultSidebar()
    {
        $keys = array_keys(Arr::get($this->config, 'sidebar', []));
        foreach ($keys as $key) {
            $this->sidebar($key);
        }
    }

    /**
     * 加载默认页尾内容
     */
    protected function loadDefaultFooter()
    {
        foreach (Arr::get($this->config, 'footer', []) as $key => $item) {
            $item = $this->app->make($item);
            $this->footer($key, $item);
        }
    }

    /**
     * @param string $name
     * @return Sidebar
     */
    public function sidebar($name)
    {
        if (!isset($this->sidebar[$name])) {
            $sidebar = new Sidebar($name);
            $this->sidebar[$name] = $sidebar;

            $items = Arr::get($this->config, 'sidebar.'. $name, []);
            foreach ($items as $item) {
                //TODO 待优化,实际使用时进行make
                $item = $this->app->make($item);
                $sidebar->append($item);
            }
        }

        return $this->sidebar[$name];
    }

    /**
     * @param string $name
     * @return Navbar
     */
    public function navbar($name)
    {
        if (!isset($this->navbar[$name])) {
            $navbar = new Navbar($name);
            $this->navbar[$name] = $navbar;

            $items = Arr::get($this->config, 'navbar.'. $name, []);
            foreach ($items as $key => $item) {
                if (in_array($item, ['navigation', 'form'])) {
                    call_user_func([$navbar, $item], $key);
                }
                else {
                    $item = $this->app->make($item);
                    $navbar->put($key, $item);
                }
            }
        }

        return $this->navbar[$name];
    }

    /**
     * @param Navigation|string|null $navigation
     * @return Navigation
     */
    public function navigation($navigation = null)
    {
        if ($navigation instanceof Navigation) {
            $this->navigation = $navigation;
        }
        else if (is_string($navigation)) {
            $this->navigation = new Navigation($navigation);
        }

        return $this->navigation;
    }

    /**
     * @param mixed $breadcrumb
     * @return Breadcrumb
     */
    public function breadcrumb($breadcrumb = null)
    {
        if ($breadcrumb instanceof Breadcrumb) {
            $this->breadcrumb = $breadcrumb;
        }
        else if (is_string($breadcrumb)) {
            $this->breadcrumb = new Breadcrumb($breadcrumb);
        }

        return $this->breadcrumb;
    }

    public function message()
    {
        //TODO 消息
    }

    public function footer($name, $content)
    {
        $this->footer[$name] = $content;

        return $this;
    }

    public function flushState()
    {
        if ($this->rendered) {
            $this->title = '';
            $this->description = null;
            $this->help = null;
            $this->meta = [];
            $this->breadcrumb = null;
            $this->navigation = null;
            $this->content = new Content();
            $this->rows = [];
            $this->rendered = false;
        }
    }

    //TODO sidebar/navbar/menu/rows/columns/message/component/widgets/form/block/breadcrumb

    /**
     * @return string
     */
    protected function formatMeta()
    {
        $items = [];
        if (!isset($this->meta['description']) && !empty($this->description)) {
            $items[] = '<meta name="description" content="'. e($this->description) .'" />';
        }

        foreach ($this->meta as $name => $meta) {
            $items[] = '<meta name="'. e($name) .'" content="'. e($meta) .'" />';
        }

        return implode("\n", $items);
    }

    /**
     * @return array
     */
    protected function buildFooter()
    {
        $footer = $this->footer;

        foreach ($footer as $key => $item) {
            if ($item instanceof Renderable) {
                $footer[$key] = $item->render();
            }
        }

        return $footer;
    }

    protected function variables()
    {
        return array_merge(parent::variables(), [
            'name'          => $this->name,
            'title'         => $this->title,
            'meta'          => $this->formatMeta(),
            'content'       => $this->getContent(),
            'sidebar'       => $this->sidebar,
            'navbar'        => $this->navbar,
            'footer'        => $this->buildFooter()
        ]);
    }

    /**
     * Get view of this field.
     *
     * @param array $data
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    protected function view(array $data)
    {
        if (empty($this->view)) {
            // 没有定义页面总局,则直接输出内容.
            return $data['content'];
        }

        return view($this->view, $data);
    }

    /**
     * Get the evaluated contents of the object.
     *
     * @return string
     */
    public function render()
    {
        $this->rendered = true;

        // 内容帮助
        if (isset($this->help)) {
            $this->content->help($this->help);
        }

        return $this->toHtml();
    }

    /**
     * Create an HTTP response that represents the object.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function toResponse($request)
    {
        // 如果不是弹窗,则显示页面导航等内容.
        if ($request->header('X-PJAX-Dialog') != true) {
            // 内容标题
            $this->content->header('title', $this->title);
            // 内容描述
            if (isset($this->description)) {
                $this->content->header('subtitle', $this->description);
            }

            if (isset($this->breadcrumb)) {
                $this->content->breadcrumb = $this->breadcrumb;
            }
            if (isset($this->navigation)) {
                $this->content->navigation = $this->navigation;
            }
        }

        // 如果是AJAX请求,则使用pjax模板渲染.
        if ($request->pjax()) {
            $this->view = 'ui::layouts.pjax';
        }

        return new Response($this);
    }

}