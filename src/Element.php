<?php
/**
 * Element.php
 * User: realeff
 * Date: 17-11-13
 */

namespace BaiSam\UI;

use BadMethodCallException;
use Illuminate\Contracts\Support\Renderable;

class Element extends HtmlTag
{
    /**
     * Element id.
     *
     * @var string
     */
    protected $id;

    /**
     * @var array
     */
    protected $styles;

    /**
     * View for element to render.
     *
     * @var string
     */
    protected $view = 'ui::default';

    /**
     * Variables of element.
     *
     * @var array
     */
    protected $variables = [];

    /**
     * Prefix of element.
     *
     * @var string|array
     */
    protected $prefix = null;

    /**
     * Element constructor.
     *
     * @param string $id
     * @param mixed $content
     */
    function __construct(string $id, $content = null)
    {
        if (!isset($this->type)) {
            $this->type = strtolower(class_basename($this));
        }

        $this->id    = snake_case($id);
        $this->content = $content;

        // Load config the styles.
        $this->loadStyles();
    }

    protected function loadStyles()
    {
        $this->styles = config('ui.styles', []);
    }

    /**
     * Set variable for the element view.
     *
     * @param string $name
     * @param mixed $value
     */
    function __set($name, $value)
    {
        $this->variables[$name] = $value;
    }

    /**
     * Dynamically retrieve the value of a variable.
     *
     * @param string $key
     * @return mixed
     */
    public function __get($key)
    {
        if (isset($this->variables[$key])) {
            return $this->variables[$key];
        }
    }

    /**
     * Dynamically check if a variable is set.
     *
     * @param string $key
     * @return bool
     */
    public function __isset($key)
    {
        return isset($this->variables[$key]);
    }

    /**
     * Unset variable for the element.
     *
     * @param string $name
     */
    public function __unset($name)
    {
        unset($this->variables[$name]);
    }

    /**
     * Get id for the element.
     *
     * @return string
     */
    public function getId()
    {
        return $this->formatId();
    }

    /**
     * Set id prefix of the element.
     *
     * @param string|array $prefix
     *
     * @return $this
     */
    protected function setPrefix($prefix)
    {
        $this->prefix = $prefix;

        return $this;
    }

    /**
     * Format the element id.
     *
     * @return string
     */
    protected function formatId()
    {
        if (is_null($this->prefix)) {
            return $this->id;
        }

        if (is_array($this->prefix)) {
            return empty($this->prefix[0]) ? $this->id : snake_case($this->prefix[0]) .'_'. $this->id;
        }

        return snake_case($this->prefix) .'_'. $this->id;
    }

    /**
     * Add the class if the callback is truthy.
     *
     * @param boolean|callable $callback
     * @param string $class
     * @return $this
     */
    public function whenClass($callback, $class)
    {
        if (is_bool($callback)) {
            $callback and $this->addClass($class);
        }
        else if ($callback($this)) {
            $this->addClass($class);
        }

        return $this;
    }

    /**
     * @return string
     */
    protected function formatClass()
    {
        $styles = array_get($this->styles, 'custom', []);
        $classes = [];
        foreach ($this->classes as $class) {
            $classes[] = $styles[$class] ?? $class;
        }

        return implode(' ', array_unique($classes));
    }

    /**
     * @param string $key
     * @param mixed $default
     * @return string|array
     */
    protected function getStyle($key, $default = null)
    {
        if (empty($this->type)) {
            return $default;
        }

        $styles = array_get($this->styles, $this->type, []);
        if (isset($styles[0]) && is_string($styles[0])) {
            $styles = array_merge(array_get($this->styles, array_pull($styles, 0), []), $styles);
        }

        return array_get($styles, strtolower($key), $default);
    }

    /**
     * Set view for this element.
     *
     * @param string $view
     *
     * @return $this
     */
    public function setView($view)
    {
        $this->view = $view;

        return $this;
    }

    /**
     * Get view of this element.
     *
     * @param array $data view data
     *
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    protected function view(array $data)
    {
        return view($this->view, $data);
    }

    /**
     * Set the variable.
     *
     * @param string $name
     * @param mixed $value
     * @return $this
     */
    public function variable($name, $value)
    {
        $this->variables[$name] = $value;

        return $this;
    }

    /**
     * Get the view variables of this element.
     *
     * @return array
     */
    protected function variables()
    {
        return array_merge($this->variables, [
            'id'         => $this->formatId(),
            'type'       => $this->type,
            'content'    => $this->content,
            'class'      => $this->formatClass(),
            'attributes' => $this->formatAttributes()
        ]);
    }

    /**
     * Get content as a string of HTML.
     *
     * @return string
     * @throws \Throwable
     */
    public function toHtml()
    {
        //TODO 缓存view解析加速

        $data = $this->variables();
        $html = $this->view($data);

        return $html instanceof Renderable ? $html->render() : (string)$html;
    }

    /**
     * @param string $name
     * @param $arguments
     * @return $this
     */
    public function __call($name, $arguments)
    {
        if (substr($name, 0, 5) === 'class') {
            $name = substr($name, 5);
            if ($name && isset($arguments[0])) {
                $this->whenClass($arguments[0], kebab_case($name));
            }

            return $this;
        }

        throw new BadMethodCallException('The '. $name .' method does not exist');
    }
}