<?php
/**
 * DropDown.phpp
 * BaiSam admin
 *
 * Created by realeff on 2018/06/05.
 * Copyright ©2018 Jiangxi baisam information technology co., LTD. All rights reserved.
 */


namespace BaiSam\UI\Layout\Component;


use BaiSam\UI\Element;

class DropDown extends Element
{
    /**
     * @var string
     */
    protected $icon;

    /**
     * @var string
     */
    protected $title;

    /**
     * 下拉列表
     *
     * @var array
     */
    protected $items = [];

    /**
     * View for element to render.
     *
     * @var string
     */
    protected $view = 'ui::partials.dropdown';

    /**
     * @var string
     */
    protected $type = 'dropdown';

    /**
     * DropDown constructor.
     * @param string $title
     * @param array|mixed|null $items
     */
    public function __construct($title, $items = null)
    {
        $this->title = $title;
        if (isset($items)) {
            $this->items = array_wrap($items);
        }

        // Load the styles.
        $this->loadStyles();
    }

    /**
     * Set the id for dropdown.
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
     * Get the title.
     *
     * @return string
     */
    public function title()
    {
        return $this->title;
    }

    /**
     * @param string $icon
     * @return $this
     */
    public function icon($icon)
    {
        $this->icon = $icon;

        return $this;
    }

    /**
     * @param mixed $item
     *
     * @return $this
     */
    public function push($item)
    {
        $this->items[] = $item;

        return $this;
    }

    /**
     * @return $this
     */
    public function separator()
    {
        $this->push('##_SEPARATOR_##');

        return $this;
    }

    /**
     * Build the items.
     *
     * @return array
     */
    protected function buildItems()
    {
        return $this->items;
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
            'items'         => $this->buildItems()
        ]);
    }
}