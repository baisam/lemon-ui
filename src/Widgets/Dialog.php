<?php
/**
 * Dialog.php
 * BaiSam huixin
 *
 * Created by realeff on 2018/11/12.
 * Copyright ©2018 Jiangxi baisam information technology co., LTD. All rights reserved.
 */


namespace BaiSam\UI\Widgets;


use Closure;
use BaiSam\UI\Element;
use BaiSam\UI\UIRepository;

class Dialog extends Element
{
    /**
     * The title.
     *
     * @var string
     */
    protected $title;

    /**
     * @var string
     */
    protected $url;

    /**
     * The size.
     *
     * @var string
     */
    protected $size;

    /**
     * The color.
     *
     * @var string
     */
    protected $color;

    /**
     * 按钮
     * @var array
     */
    protected $buttons = [];

    /**
     * 事件
     * @var array
     */
    protected $events = [];

    /**
     * @var string
     */
    protected $args;

    /**
     * Display dialog.
     *
     * @var bool
     */
    protected $show = 'js';

    /**
     * @var string
     */
    protected $type = 'dialog';

    /**
     * @var string
     */
    protected $view = 'ui::widget.dialog';

    /**
     * Dialog constructor.
     *
     * @param string $id
     * @param string $title
     * @param string $content
     */
    public function __construct(string $id, $title = null, $content = null)
    {
        parent::__construct($id);

        $this->title = $title;
        $this->content = $content;
    }

    /**
     * Set title.
     *
     * @param string $title
     * @return $this
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    public function url($url)
    {
        $this->url = $url;

        if (!isset($this->content)) {
            $this->content = '加载中...';
        }

        return $this;
    }

    /**
     * 提示消息框
     *
     * @param string $message
     * @return $this
     */
    public function alert($message)
    {
        $this->type = 'alert';
        $this->content = $message;

        return $this;
    }

    /**
     * 确认消息框
     *
     * @param string $message
     * @return $this
     */
    public function confirm($message)
    {
        $this->type = 'confirm';
        $this->content = $message;

        return $this;
    }

    /**
     * @param string $content
     * @param string|\BaiSam\UI\Form\Field\Button $button
     * @param string|null $btn_label
     * @return $this
     */
    public function dialog($content, $button = null, $btn_label = null)
    {
        $this->type = 'dialog';
        $this->content = $content;
        if (is_array($button)) {
            $this->buttons = $button;
        }
        else if($button) {
            $this->buttons = isset($btn_label) ? [$button => $btn_label] : ['ok' => $button];
        }

        return $this;
    }

    /**
     * Set color.
     *
     * @param string $color
     * @return $this
     */
    public function color($color = UIRepository::STYLE_COLOR_PRIMARY)
    {
        $this->color = $color;

        return $this;
    }

    /**
     * Set size.
     *
     * @param string $size
     * @return $this
     */
    public function size($size = UIRepository::STYLE_SIZE_SMALL)
    {
        $this->size = $size;

        return $this;
    }

    protected function formatColor()
    {
        if (isset($this->color)) {
            return $this->getStyle('color.'. $this->color, $this->color);
        }

        return null;
    }

    protected function formatSize()
    {
        if (isset($this->size)) {
            return $this->getStyle('size.'. $this->size, $this->size);
        }

        return null;
    }

    protected function formatUrl()
    {
        if (empty($this->url)) {
            return null;
        }

        return url($this->url);
    }

    /**
     * 客户端响应事件
     * @param string $event
     * @param string $callback
     * @return $this
     */
    public function on($event, $callback)
    {
        //close,ok,loaded
        $this->events[$event] = $callback;

        return $this;
    }

    /**
     * 当弹窗显示时客户端响应事件
     *
     * @param string $callback
     * @return $this
     */
    public function onShow($callback)
    {
        return $this->on('show', $callback);
    }

    /**
     * 当弹窗隐藏时客户端响应事件
     *
     * @param string $callback
     * @return $this
     */
    public function onHide($callback)
    {
        return $this->on('hide', $callback);
    }

    /**
     * 客户端参数
     * @param string ...$args
     * @return $this
     */
    public function args(...$args)
    {
        $this->args = implode(', ', $args);

        return $this;
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
     * 立即显示
     *
     * @return $this
     */
    public function show($show = true)
    {
        $this->show = $show;

        return $this;
    }

    /**
     * 返回显示Javascript.
     *
     * @return $this
     */
    public function showJs()
    {
        $this->show = 'js';

        return $this;
    }

    protected function variables()
    {
        // 计算迭代次数
        if (isset($this->variables['iteration'])) {
            $this->variables['iteration']++;
        }
        else {
            $this->variables['iteration'] = 1;
        }

        return array_merge(parent::variables(), [
            'title'         => $this->title,
            'url'           => $this->formatUrl(),
            'color'         => $this->formatColor(),
            'size'          => $this->formatSize(),
            'buttons'       => $this->buttons,
            'events'        => $this->buildEvents(),
            'args'          => $this->args,
            'show'          => $this->show
        ]);
    }
}