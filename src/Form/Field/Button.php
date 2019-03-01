<?php
/**
 * Button.php
 * User: realeff
 * Date: 17-11-14
 */

namespace BaiSam\UI\Form\Field;


use Closure;
use BaiSam\UI\Form\Field;
use BaiSam\UI\UIRepository;

class Button extends Field
{

    protected $size;

    protected $color;

    /**
     * @var string
     */
    protected $icon;

    /**
     * 事件
     * @var array
     */
    protected $events = [];

    /**
     * Ignore the field.
     *
     * @var boolean
     */
    protected $ignored = true;

    protected $view = 'ui::form.button';

    /**
     * Button constructor.
     *
     * @param string $column
     * @param string|null $label
     * @param array $config
     */
    public function __construct(string $column, string $label = null, array $config = [])
    {
        parent::__construct($column, '', $config);

        $this->content = $label;
    }

    /**
     * Set size for the button.
     *
     * @param string $size
     *
     * @return $this
     */
    public function size($size = UIRepository::STYLE_DEFAULT)
    {
        $this->size = $size;

        return $this;
    }

    /**
     * Set color for the button.
     *
     * @param string $color
     *
     * @return $this
     */
    public function color($color = UIRepository::STYLE_DEFAULT)
    {
        $this->color = $color;

        return $this;
    }

    /**
     * Set icon for the button.
     *
     * @param string $icon
     * @return $this
     */
    public function icon($icon)
    {
        $this->icon = $icon;

        return $this;
    }

    protected function formatClass()
    {
        if (isset($this->size)) {
            $this->addClass($this->getStyle('size.'. $this->size, $this->size));
        }
        if (isset($this->color)) {
            $this->addClass($this->getStyle('color.'. $this->color, $this->color));
        }

        return parent::formatClass();
    }

    /**
     * 客户端响应事件
     * @param string $event
     * @param string $callback
     * @return $this
     */
    public function on($event, $callback)
    {
        $this->events[$event] = $callback;

        return $this;
    }

    /**
     * 当点击按钮时客户端响应事件
     *
     * @param string $callback
     * @return $this
     */
    public function onClick($callback)
    {
        return $this->on('click', $callback);
    }

    protected function buildEvents()
    {
        $events = $this->events;

        foreach ($events as $name => $event) {
            if ($event instanceof Closure) {
                $events[$name] = call_user_func($event);
            }
        }

        return $events;
    }

    /**
     * Get the view variables of this element.
     *
     * @return array
     */
    protected function variables()
    {
        return array_merge(parent::variables(), [
            'icon'          => $this->icon,
            'events'        => $this->buildEvents()
        ]);
    }

}