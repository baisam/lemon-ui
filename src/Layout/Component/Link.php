<?php
/**
 * Link.php
 * BaiSam admin
 *
 * Created by realeff on 2018/06/02.
 * Copyright ©2018 Jiangxi baisam information technology co., LTD. All rights reserved.
 */


namespace BaiSam\UI\Layout\Component;


use BaiSam\UI\Element;
use Illuminate\Support\Facades\URL;

class Link extends Element
{
    /**
     * @var string
     */
    protected $title;

    /**
     * @var string
     */
    protected $url;

    /**
     * @var array
     */
    protected $arguments;

    /**
     * @var string
     */
    protected $icon;

    /**
     * View for element to render.
     *
     * @var string
     */
    protected $view = 'ui::partials.link';

    /**
     * @var string
     */
    protected $type = 'link';

    /**
     * NavLink constructor.
     *
     * @param string $text
     * @param string $url
     * @param array $arguments
     */
    public function __construct($text, $url, array $arguments = [])
    {
        if (is_string($text)) {
            $this->title = trim($text);
            $this->content = str_replace(' ', '&nbsp;', $text);
        }
        else {
            $this->content = $text;
        }

        $this->url = $url;
        $this->arguments = $arguments;

        // Load the styles.
        $this->loadStyles();
    }

    /**
     * Set the id for link.
     *
     * @param string $id
     *
     * @return $this
     */
    public function setId($id)
    {
        $this->id = snake_case($id);

        return $this;
    }

    /**
     * Set the title.
     *
     * @param string $title
     * @return $this
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get the title.
     *
     * @return string
     */
    public function title()
    {
        return $this->title;
    }

    /**
     * Get the url.
     *
     * @return string
     */
    public function url()
    {
        return $this->formatUrl();
    }

    /**
     * Set icon for the menu.
     *
     * @param string $icon
     * @return $this
     */
    public function icon($icon)
    {
        $this->icon = $icon;

        return $this;
    }

    /**
     * Set target.
     *
     * @param string $target
     * @return $this
     */
    public function target($target = '_blank')
    {
        $this->attribute('target', $target);

        return $this;
    }

    /**
     *
     * @return boolean
     */
    public function isActive()
    {
        return URL::full() === $this->formatUrl();
    }

    /**
     * @return string
     */
    protected function formatUrl()
    {
        if (empty($this->url)) {
            return '';
        }

        if (is_callable($this->url)) {
            return call_user_func($this->url, $this->arguments);
        }

        return url($this->url, $this->arguments);
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
            'title'         => $this->title,
            'url'           => $this->formatUrl()
        ]);
    }
}