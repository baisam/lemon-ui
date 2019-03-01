<?php
/**
 * Select.php
 * BaiSam admin
 *
 * Created by realeff on 2018/10/28.
 * Copyright ©2018 Jiangxi baisam information technology co., LTD. All rights reserved.
 */


namespace BaiSam\UI\Grid\Actions;


use BaiSam\UI\Element;
use BaiSam\UI\Form\Field\Select;
use BaiSam\UI\Form\Field\Submit;
use BaiSam\UI\Form\Traits\Options;
use BaiSam\UI\UIRepository;
use BaiSam\UI\Grid\Action;
use BaiSam\UI\Grid\Traits\ActionRender;
use Illuminate\Support\Facades\Request;

class FilterAction extends Element implements Action
{
    use Options, ActionRender;

    protected $name;

    protected $filters = [];

    /**
     * 操作按钮
     */
    protected $button;

    /**
     * 默认颜色
     *
     * @var string
     */
    protected $color = UIRepository::STYLE_DEFAULT;

    /**
     * @var string
     */
    protected $type = 'filter';

    /**
     * @var string
     */
    protected $view = 'ui::grid.actions.filter';

    /**
     * FilterAction constructor.
     *
     * @param string $name
     * @param string $label
     * @param null $options
     */
    public function __construct($name, $label, $options = null)
    {
        parent::__construct($name);

        $this->name  = $name;

        if (isset($options)) {
            $this->filter($name, $label, $options);
        }
        else {
            $this->filter($name, $label, function () {
                return $this->options;
            });
        }
    }

    public function needSelectRow()
    {
        return false;
    }

    /**
     * 增加筛选条件
     *
     * @param string $name
     * @param string $label
     * @param mixed $options
     * @return $this
     */
    public function filter($name, $label, $options)
    {
        $this->filters[] = [$name, $label, $options];

        return $this;
    }

    protected function buildFilters()
    {
        $helper = app('form.helper');
        $color  = $helper->getConfig('styles.button.color.'. $this->color, $this->color);

        $filters = [];
        foreach ($this->filters as list($name, $label, $options)) {
            $select = new Select($name, $label);
            $select->setView('ui::grid.actions.select');
            $select->setPrefix($this->prefix);
            $select->addClass($this->classes);
            $select->addClass($color);

            if (is_callable($options)) {
                $options = call_user_func_array($options, array_merge($this->params, [$select]));
            }

            $select->options($options);

            $select->setValue(Request::get($name));

            $filters[] = $select;
        }

        return $filters;
    }

    /**
     * @param string $name
     * @param string $label
     * @return Submit
     */
    public function button($name, $label)
    {
        $button = new Submit($name, $label);

        $this->button = $button->color($this->color);

        return $button;
    }

    protected function buildButton()
    {
        $button = $this->button;
        if (empty($button)) {
            $button = new Submit($this->name .'filter_action', '筛选');
            $button->color($this->color);
        }

        $button->setPrefix($this->prefix);

        return $button;
    }

    protected function variables()
    {
        return array_merge(parent::variables(), [
            'name'              => $this->name,
            'filters'           => $this->buildFilters(),
            'button'            => $this->buildButton()
        ]);
    }

}