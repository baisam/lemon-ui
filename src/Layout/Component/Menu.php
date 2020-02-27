<?php
/**
 * Menu.php
 * BaiSam admin
 *
 * Created by realeff on 2018/05/25.
 * Copyright ©2018 Jiangxi baisam information technology co., LTD. All rights reserved.
 */


namespace BaiSam\UI\Layout\Component;

use BaiSam\Contracts\Sortable;
use BaiSam\UI\Element;
use Illuminate\Support\Facades\URL;

/**
 * 菜单
 *
 * @package BaiSam\UI\Layout\Component
 */
class Menu extends Element implements Sortable
{
    /**
     * Menu label.
     *
     * @var string
     */
    protected $label;

    /**
     * @var string
     */
    protected $icon;

    /**
     * @var string
     */
    protected $badge;

    /**
     * @var string
     */
    protected $url;

    /**
     * @var array
     */
    protected $params = [];

    /**
     * @var boolean
     */
    protected $secure = null;

    /**
     * @var int
     */
    protected $level = 0;

    /**
     * @var array
     */
    protected $items = [];

    /**
     * @var int
     */
    protected $weight = 0;

    /**
     * View for element to render.
     *
     * @var string
     */
    protected $view = 'ui::partials.menu-item';

    public function __construct(string $id, string $label = null, $params = [], $secure = null)
    {
        parent::__construct($id);

        $this->label = $label;
        $this->params = $params;
        $this->secure = $secure;
    }

    /**
     * Get the id for the menu.
     *
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $label
     * @return $this
     */
    public function setLabel($label)
    {
        $this->label = $label;

        return $this;
    }

    /**
     * Set the icon for menu.
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
     * Set the badge.
     *
     * @param string $badge
     * @return $this
     */
    public function badge($badge)
    {
        $this->badge = $badge;

        return $this;
    }

    /**
     * Set the url.
     *
     * @param string $url
     * @param array|null $params
     * @param boolean|null $secure
     * @return $this
     */
    public function url($url, $params = [], $secure = null)
    {
        $this->url = $url;
        $this->params = $params;
        $this->secure = $secure;

        return $this;
    }

    /**
     * Set level.
     *
     * @param int $level
     * @return $this
     */
    protected function level($level)
    {
        $this->level = $level;

        return $this;
    }

    public function isActive()
    {
        return URL::current() === $this->formatUrl();
    }

    /**
     * @param float|null $weight
     *
     * @return $this|int
     */
    public function weight($weight = null)
    {
        if (is_null($weight)) {
            return $this->weight;
        }

        $this->weight = $weight;

        return $this;
    }

    /**
     * Add children menu.
     *
     * @param string $id
     * @param string $label
     * @param string|null $url
     * @param string|null $icon
     * @param string|null $badge
     * @return Menu
     */
    public function add($id, $label, $url = null, $icon = null, $badge = null)
    {
        $menu = new static($id, $label);
        $menu->url($url)
            ->level($this->level+1)
            ->icon($icon)
            ->badge($badge)
            ->weight( count($this->items) );

        $this->items[] = $menu;

        return $menu;
    }

    /**
     * Remove an item from the menu by index.
     *
     * @param int $index
     * @return $this
     */
    public function forget($index)
    {
        unset($this->items[$index]);

        return $this;
    }

    /**
     * @return $this
     */
    public function separator()
    {
        $this->items[] = '##_SEPARATOR_##';

        return $this;
    }

    /**
     * @return boolean
     */
    public function hasChildren()
    {
        return count($this->items) > 0;
    }

    /**
     * @return array
     */
    public function children()
    {
        return $this->items;
    }

    /**
     * @return string
     */
    public function formatUrl()
    {
        if (empty($this->url)) {
            return '';
        }

        return url($this->url, $this->params, $this->secure);
    }

    /**
     * Build the items.
     *
     * @return Collection
     */
    protected function buildItems()
    {
        return collect($this->items)->sortBy(function ($item, $index) {
            // Set the prefix.
            $item->setPrefix($this->formatId());

            if ($item instanceof Sortable) {
                return $item->weight();
            }

            return $index;
        });
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
            'icon'          => $this->icon,
            'badge'         => $this->badge,
            'url'           => $this->formatUrl(),
            'level'         => $this->level,
            'items'         => $this->buildItems()
        ]);
    }

    public function __clone()
    {
        foreach ($this->items as $index => $item) {
            if (!is_scalar($item))
            $this->items[$index] = clone $item;
        }
    }
}