<?php
/**
 * LinkAction.php
 * BaiSam admin
 *
 * Created by realeff on 2018/10/28.
 * Copyright ©2018 Jiangxi baisam information technology co., LTD. All rights reserved.
 */


namespace BaiSam\UI\Grid\Actions;


use BaiSam\UI\Grid\Action;
use BaiSam\UI\UIRepository;
use BaiSam\UI\Layout\Component\Link;
use BaiSam\UI\Grid\Traits\ActionRender;

class LinkAction extends Link implements Action
{
    use ActionRender;

    /**
     * Action 名称
     *
     * @var string
     */
    protected $name;

    /**
     * 默认颜色
     *
     * @var string
     */
    protected $color = UIRepository::STYLE_DEFAULT;

    protected $view = 'ui::grid.actions.link';

    /**
     * LinkAction constructor.
     *
     * @param string $name
     * @param string $label
     */
    public function __construct($name, $label)
    {
        $this->id    = snake_case($name);
        $this->name = $name;

        parent::__construct($label, null);
    }

    public function needSelectRow()
    {
        return false;
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

    protected function formatClass()
    {
        $this->addClass(array_get($this->styles, 'button.color.'. $this->color, $this->color));

        return parent::formatClass();
    }

    /**
     * @param null $url
     * @return $this|string
     */
    public function url($url = null)
    {
        if (is_null($url)) {
            return $this->formatUrl();
        }

        $this->url = $url;

        return $this;
    }

    /**
     * 设置参数
     *
     * @param callable|string $name
     * @param mixed|null $value
     * @return $this
     */
    public function args($name, $value = null)
    {
        if (is_callable($name)) {
            // 支持callback返回参数
            $this->arguments = $name;
        }
        else if (is_array($name)) {
            $this->arguments = array_merge($this->arguments, $name);
        }
        else {
            $this->arguments[$name] = $value;
        }

        return $this;
    }

    protected function formatUrl()
    {
        if (is_callable($this->arguments)) {
            $arguments = call_user_func_array($this->arguments, $this->params);
        }
        else {
            $arguments = $this->arguments;
        }

        if (empty($this->url)) {
            // 没有设置url，则使用name作为路由名称
            return route($this->name, $arguments);
        }

        if (is_callable($this->url)) {
            $url = call_user_func_array($this->url, $this->params);
        }
        else {
            $url = $this->url;
        }

        return url($url, $arguments);
    }
}