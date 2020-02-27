<?php
/**
 * Box.php
 * BaiSam huixin
 *
 * Created by realeff on 2018/11/12.
 * Copyright ©2018 Jiangxi baisam information technology co., LTD. All rights reserved.
 */


namespace BaiSam\UI\Widgets;


use BaiSam\UI\Element;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Support\Str;

class Box extends Element implements Renderable
{
    protected $title;

    protected $icon;

    protected $type = 'box';

    protected $view = 'ui::widget.box';

    /**
     * Box constructor.
     *
     * @param string $title
     * @param mixed|null $content
     */
    public function __construct(string $title, $content = null)
    {
        $this->title($title);
        $this->content($content);

        // Load the styles.
        $this->loadStyles();
    }

    /**
     * Set box title.
     *
     * @param string $title
     * @return $this
     */
    public function title($title)
    {
        $this->title = Str::title($title);

        return $this;
    }


    /**
     * Set box content.
     *
     * @param string $content
     *
     * @return $this
     */
    public function content($content)
    {
        $this->content = $content;

        return $this;
    }

    public function icon($icon)
    {
        $this->icon = $icon;

        return $this;
    }

    protected function variables()
    {
        return array_merge(parent::variables(), [
            'title'         => $this->title,
            'icon'          => $this->icon
        ]);
    }

    /**
     * Get the evaluated contents of the object.
     *
     * @return string
     */
    public function render()
    {
        return $this->toHtml();
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->render();
    }
}