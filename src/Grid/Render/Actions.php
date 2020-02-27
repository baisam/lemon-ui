<?php
/**
 * Actions.php
 * BaiSam admin
 *
 * Created by realeff on 2018/10/28.
 * Copyright ©2018 Jiangxi baisam information technology co., LTD. All rights reserved.
 */


namespace BaiSam\UI\Grid\Render;


use Closure;
use BadMethodCallException;
use BaiSam\UI\Grid\Action;
use BaiSam\UI\Grid\Render as AbstractRender;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

/**
 * Class Actions
 *
 * @method \BaiSam\UI\Grid\Actions\LinkAction       link($name, $label)
 * @method \BaiSam\UI\Grid\Actions\ButtonAction     button($name, $label, array $config = [])
 * @method \BaiSam\UI\Grid\Actions\DropdownAction   dropdown($name, $label, $callback = null)
 *
 * @package BaiSam\UI\Grid\Render
 */
class Actions extends AbstractRender
{
    /**
     * @var Collection
     */
    protected $actions;

    // 默认仅支持ButtonAction,DropdownAction,LinkAction
    protected $supportActionNames = ['link', 'button', 'dropdown'];

    /**
     * Actions constructor.
     *
     * @param string $name
     * @param Closure|null $callback
     */
    public function __construct($name, Closure $callback = null)
    {
        parent::__construct($name);

        $this->actions = new Collection();

        if ($callback) {
            call_user_func($callback, $this);
        }
    }

    /**
     * @param Action $item
     * @return $this
     */
    public function push(Action $item)
    {
        $this->actions->push($item);

        return $this;
    }

    /**
     * @param mixed $value
     * @param \BaiSam\UI\Grid\Row $row
     * @param \BaiSam\UI\Grid\Builder $builder
     * @return string
     */
    public function render($value, $row, $builder)
    {
        $majorKey = $row->getMajorKey();
        $actions = $this->actions->map(function ($action) use($majorKey, $row, $builder) {
            return $action->render($majorKey, $row, $builder);
        })->filter();

        return view('ui::grid.actions', ['content' => $value, 'actions' => $actions]);
    }

    public function __call($name, $arguments)
    {
        if (in_array($name, $this->supportActionNames)) {
            $name = Str::studly($name);
            $class = "BaiSam\\UI\\Grid\\Actions\\{$name}Action";
            $action = new $class(...$arguments);

            $this->push($action);

            return $action;
        }
        else {
            throw new BadMethodCallException($name .' does not exist');
        }
    }
}