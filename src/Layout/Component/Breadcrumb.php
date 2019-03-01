<?php
/**
 * Breadcrumb.php
 * BaiSam admin
 *
 * Created by realeff on 2018/05/25.
 * Copyright ©2018 Jiangxi baisam information technology co., LTD. All rights reserved.
 */


namespace BaiSam\UI\Layout\Component;


use BaiSam\UI\Element;
use Illuminate\Support\Collection;

/**
 * 路径导航
 *
 * @package BaiSam\UI\Layout\Component
 */
class Breadcrumb extends Element
{
    /**
     * Breadcrumb label.
     *
     * @var string
     */
    protected $label;

    /**
     * @var Collection
     */
    protected $items;

    /**
     * View for element to render.
     *
     * @var string
     */
    protected $view = 'ui::partials.breadcrumb';

    /**
     * Breadcrumb constructor.
     *
     * @param string $id
     */
    public function __construct(string $id)
    {
        parent::__construct($id);

        $this->items = new Collection();
    }

    /**
     * Set the label for breadcrumb.
     *
     * @param string $label
     * @return $this
     */
    public function setLabel($label)
    {
        $this->label = $label;

        return $this;
    }

    /**
     * @param mixed $item
     * @return $this
     */
    public function push($item)
    {
        $this->items->push($item);

        return $this;
    }

    /**
     * @param string $title
     * @param string $url
     *
     * @return Link
     */
    public function link($title, $url)
    {
        $link = new Link($title, $url);

        $this->push($link);

        return $link;
    }

    /**
     * @return Collection
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
            'label'         => $this->label,
            'items'         => $this->buildItems()
        ]);
    }
}