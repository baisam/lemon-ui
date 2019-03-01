<?php
/**
 * Navigation.php.
 * User: feng
 * Date: 2018/5/28
 */

namespace BaiSam\UI\Layout\Component;

use Countable;
use BaiSam\UI\Element;
use BaiSam\Contracts\Sortable;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\HtmlString;

/**
 * 导航tabs,pills
 *
 * @package BaiSam\UI\Layout\Component
 */
class Navigation extends Element implements Countable
{
    const STYLE_TABS = 'tabs';
    const STYLE_PILLS = 'pills';
    const STYLE_STACKED = 'stacked';
    const STYLE_JUSTIFIED = 'justified';

    /**
     * 导航列表
     * @var array
     */
    protected $items = [];

    /**
     * 修饰导航外观
     *
     * @var string|null
     */
    protected $embellish = null;

    /**
     * 堆叠导航
     * @var boolean
     */
    protected $stacked = false;

    /**
     * 两端对齐
     * @var boolean
     */
    protected $justified = false;

    /**
     * View for element to render.
     *
     * @var string
     */
    protected $view = 'ui::partials.navigation';

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
     * @param mixed $item
     * @param int $index
     * @return $this
     */
    public function insert($item, $index = 0)
    {
        array_splice($this->items, $index, 0, [$item]);

        return $this;
    }

    /**
     * @param int $index
     * @return $this
     */
    public function forget($index)
    {
        unset($this->items[$index]);

        return $this;
    }

    /**
     * @return array
     */
    public function items()
    {
        return $this->items;
    }

    /**
     * Count elements of an object
     *
     * @link https://php.net/manual/en/countable.count.php
     * @return int The custom count as an integer.
     * </p>
     * <p>
     * The return value is cast to an integer.
     * @since 5.1.0
     */
    public function count()
    {
        return count($this->items);
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
     *
     * @param string $title
     * @param array $items
     *
     * @return DropDown
     */
    public function dropdown($title, array $items)
    {
        $dropdown = new DropDown($title, $items);

        $this->push($dropdown);

        return $dropdown;
    }

    /**
     * @return $this
     */
    public function separator()
    {
        $this->push('##_SEPARATOR_##');

        return $this;
    }

    public function tabs()
    {
        $this->embellish = self::STYLE_TABS;

        return $this;
    }

    public function pills()
    {
        $this->embellish = self::STYLE_PILLS;

        return $this;
    }

    /**
     * 堆叠导航
     * @return $this
     */
    public function stack()
    {
        $this->stacked = true;

        return $this;
    }

    /**
     * 两端对齐
     * @return $this
     */
    public function justify()
    {
        $this->justified = true;

        return $this;
    }

    /**
     * 获取导航样式
     *
     * @param string $key
     * @param string|null $default
     * @return string
     */
    protected function getStyle($key, $default = null) {
        return array_get($this->styles, 'nav.'.$key, $default);
    }

    /**
     * Format the element of class attributes.
     *
     * @return string
     */
    protected function formatClass()
    {
        if (isset($this->embellish)) {
            $this->addClass($this->getStyle($this->embellish, ''));
        }

        if ($this->stacked) {
            $this->addClass($this->getStyle(self::STYLE_STACKED, ''));
        }
        if ($this->justified) {
            $this->addClass($this->getStyle(self::STYLE_JUSTIFIED, ''));
        }

        return parent::formatClass();
    }

    /**
     * Build the items.
     *
     * @return Collection
     */
    protected function buildItems()
    {
        return collect($this->items)->sortBy(function ($item, $index) {
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
            'stacked'       => $this->stacked,
            'justified'     => $this->justified,
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