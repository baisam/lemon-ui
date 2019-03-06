<?php
/**
 * DateRangeAction.php
 * BaiSam admin
 *
 * Created on 2019/03/04.
 * Copyright ©2019 Jiangxi baisam information technology co., LTD. All rights reserved.
 */

namespace BaiSam\UI\Grid\Actions;


use BaiSam\UI\Form\Field\DateRange;
use BaiSam\UI\Form\Field\Submit;
use BaiSam\UI\Grid\Action;
use BaiSam\UI\Grid\Traits\ActionRender;
use BaiSam\UI\UIRepository;
use Illuminate\Support\Facades\Request;

class DaterangeAction extends DateRange implements Action
{
    use ActionRender;

    /**
     * @var string
     */
    protected $color = UIRepository::STYLE_DEFAULT;

    /**
     * 操作按钮
     */
    protected $button;


    protected $view = 'ui::grid.actions.daterange';

    public function needSelectRow()
    {
        return false;
    }

    /**
     * @param string $name
     * @param string $label
     * @return Submit
     */
    public function button($name, $label = null)
    {
        $button = new Submit($name, $label);

        $this->button = $button->color(UIRepository::STYLE_DEFAULT);

        return $button;
    }

    protected function buildButton()
    {
        $button = $this->button;
        if (empty($button)) {
            $button = new Submit($this->column . '_action');
            $button->icon('search')->color(UIRepository::STYLE_DEFAULT);
        }

        $button->setPrefix($this->prefix);
        $button->addClass($this->classes);

        return $button;
    }

    protected function variables()
    {
        return array_merge(parent::variables(), [
            'value' => $this->setValue(Request::get($this->column)),
            'button' => $this->buildButton()
        ]);
    }
}