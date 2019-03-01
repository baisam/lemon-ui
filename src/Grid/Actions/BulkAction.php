<?php
/**
 * BulkAction.php
 * BaiSam admin
 *
 * Created by realeff on 2018/10/28.
 * Copyright ©2018 Jiangxi baisam information technology co., LTD. All rights reserved.
 */


namespace BaiSam\UI\Grid\Actions;


use BaiSam\UI\Element;
use BaiSam\UI\UIRepository;
use BaiSam\UI\Form\Traits\Options;
use BaiSam\UI\Grid\Action;
use BaiSam\UI\Grid\Traits\ActionRender;
use Illuminate\Support\Facades\Request;

class BulkAction extends Element implements Action
{
    use Options, ActionRender;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $label;

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
    protected $type = 'bulk';

    /**
     * @var string
     */
    protected $view = 'ui::grid.actions.bulk';


    /**
     * BulkAction constructor.
     *
     * @param string $name
     * @param string $label
     */
    public function __construct($name, $label)
    {
        parent::__construct($name);

        $this->name  = $name;
        $this->label = $label;
    }

    public function needSelectRow()
    {
        return true;
    }

    /**
     * Set color for the bulk.
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
        $this->addClass(array_get($this->styles, 'button.color.'. $this->color, $this->color));

        return parent::formatClass();
    }

    /**
     * @param string $name
     * @param string $label
     * @return SubmitAction
     */
    public function button($name, $label)
    {
        $button = new SubmitAction($name, $label);

        $this->button = $button;

        return $button;
    }

    protected function buildButton()
    {
        $button = $this->button;
        if (empty($button)) {
            $button = new SubmitAction($this->name .'_apply', '应用');
        }

        $button->setPrefix($this->prefix);
        $button->attribute('select-rows', true);

        return $button->render(...$this->params);
    }

    protected function variables()
    {
        return array_merge(parent::variables(), [
            'name'              => $this->name,
            'value'             => Request::get($this->name),
            'label'             => $this->label,
            'options'           => $this->buildOptions(),
            'button'            => $this->buildButton()
        ]);
    }
}