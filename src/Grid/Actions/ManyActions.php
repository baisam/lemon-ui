<?php
/**
 * ManyAction.php
 * BaiSam admin
 *
 * Created by realeff on 2018/10/28.
 * Copyright ©2018 Jiangxi baisam information technology co., LTD. All rights reserved.
 */


namespace BaiSam\UI\Grid\Actions;


use BadMethodCallException;
use BaiSam\UI\Element;
use BaiSam\UI\Grid\Action;
use BaiSam\UI\Grid\Traits\ActionRender;

/**
 * Class ManyActions
 *
 * @method \BaiSam\UI\Grid\Actions\BulkAction       bulk($name, $label)
 * @method \BaiSam\UI\Grid\Actions\LinkAction       link($name, $label)
 * @method \BaiSam\UI\Grid\Actions\ButtonAction     button($name, $label, array $config = [])
 * @method \BaiSam\UI\Grid\Actions\SubmitAction     submit($name, $label, array $config = [])
 * @method \BaiSam\UI\Grid\Actions\DropdownAction   dropdown($name, $label, $callback = null)
 * @method \BaiSam\UI\Grid\Actions\FilterAction     filter($name, $label, $options = null)
 * @method \BaiSam\UI\Grid\Actions\SearchAction     search($name, $label)
 * @method \BaiSam\UI\Grid\Actions\CheckboxAction   checkbox($name, $label)
 * @method \BaiSam\UI\Grid\Actions\RadioAction      radio($name, $label)
 * @method \BaiSam\UI\Grid\Actions\DateRangeAction  daterange($name, $label = null, array $config = [])
 *
 * @package BaiSam\UI\Grid\Actions
 */
class ManyActions extends Element implements Action
{
    use ActionRender;

    /**
     * @var array
     */
    protected $items = [];

    /**
     * 默认支持的Action
     * @var array
     */
    protected $supportActionNames = ['bulk', 'button', 'dropdown', 'filter', 'link', 'search', 'submit', 'checkbox', 'radio', 'daterange'];

    /**
     * @var string
     */
    protected $type = 'many';

    /**
     * @var string
     */
    protected $view = 'ui::grid.actions.many';

    /**
     * ManyActions constructor.
     *
     * @param string $id
     * @param callable|null $callback
     */
    public function __construct($id, callable $callback = null)
    {
        parent::__construct($id);

        if ($callback) {
            call_user_func($callback, $this);
        }
    }

    public function needSelectRow()
    {
        foreach ($this->items as $item) {
            if ($item instanceof Action && $item->needSelectRow()) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param mixed $item
     * @return $this
     */
    public function push(Action $item)
    {
        $this->items[] = $item;

        return $this;
    }

    protected function buildItems()
    {
        $items = [];
        foreach ($this->items as $item) {
            if ($item instanceof Element) {
                $item->setPrefix([$this->formatId()]);
            }

            if ($item instanceof Action) {
                $items[] = $item->render(...$this->params);
            } else {
                $items[] = $item;
            }
        }

        return $items;
    }

    protected function variables()
    {
        $this->variables['items'] = $this->buildItems();

        return parent::variables();
    }

    public function __call($name, $arguments)
    {
        if (in_array($name, $this->supportActionNames)) {
            $name = studly_case($name);
            $class = "BaiSam\\UI\\Grid\\Actions\\{$name}Action";
            $action = new $class(...$arguments);

            $this->push($action);

            return $action;
        }

        return parent::__call($name, $arguments);
    }

}