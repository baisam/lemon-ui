<?php
/**
 * DropdownAction.php
 * BaiSam admin
 *
 * Created by realeff on 2018/10/28.
 * Copyright ©2018 Jiangxi baisam information technology co., LTD. All rights reserved.
 */


namespace BaiSam\UI\Grid\Actions;


use Closure;
use BaiSam\UI\Element;
use BaiSam\UI\Grid\Action;
use BaiSam\UI\UIRepository;
use BaiSam\UI\Grid\Traits\ActionRender;
use BaiSam\UI\Layout\Component\DropDown;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class DropdownAction extends DropDown implements Action
{
    use ActionRender;

    /**
     * 默认颜色
     *
     * @var string
     */
    protected $color = UIRepository::STYLE_DEFAULT;

    protected $view = 'ui::grid.actions.dropdown';

    /**
     * DropdownAction constructor.
     *
     * @param $name
     * @param string $label
     * @param Closure|null $callback
     */
    public function __construct($name, string $label, Closure $callback = null)
    {
        parent::__construct($label);

        $this->id    = Str::snake($name);

        if ($callback) {
            call_user_func($callback, $this);
        }
    }

    public function needSelectRow()
    {
        return false;
    }

    /**
     * Set color for the dropdown.
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
        $this->addClass(Arr::get($this->styles, 'button.color.'. $this->color, $this->color));

        return parent::formatClass();
    }

    /**
     * 创建链接选项
     *
     * @param string $name
     * @param string $label
     * @return LinkAction
     */
    public function link($name, $label)
    {
        $link = new LinkAction($name, $label);

        $this->push($link);

        return $link;
    }

    /**
     * 创建按钮选项
     *
     * @param string $name
     * @param string $label
     * @return ButtonAction
     */
    public function button($name, $label)
    {
        //TODO
        $button = new ButtonAction($name, $label);

        $this->push($button);

        return $button;
    }

    /**
     * @return array
     */
    protected function buildItems()
    {
        $items = $this->items;
        foreach ($items as $key => $item) {
            if ($item instanceof Element) {
                $item->setPrefix([$this->formatId()]);
            }

            if ($item instanceof Action) {
                $items[$key] = $item->render(...$this->params);
            }
            else if ($item instanceof Renderable) {
                $items[$key] = $item->render();
            }
        }

        return $items;
    }

}