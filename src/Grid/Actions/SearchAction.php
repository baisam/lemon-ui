<?php
/**
 * SearchAction.php
 * BaiSam admin
 *
 * Created by realeff on 2018/10/28.
 * Copyright ©2018 Jiangxi baisam information technology co., LTD. All rights reserved.
 */


namespace BaiSam\UI\Grid\Actions;


use BaiSam\UI\Element;
use BaiSam\UI\Form\Field\Submit;
use BaiSam\UI\Form\Traits\Options;
use BaiSam\UI\Grid\Action;
use BaiSam\UI\Grid\Traits\ActionRender;
use BaiSam\UI\UIRepository;
use Illuminate\Support\Facades\Request;

class SearchAction extends Element implements Action
{
    use Options, ActionRender;

    protected $name;

    protected $label;

    protected $placeholder = '';

    /**
     * 操作按钮
     */
    protected $button;

    /**
     * @var string
     */
    protected $type = 'search';

    /**
     * @var string
     */
    protected $view = 'ui::grid.actions.search';

    /**
     * SearchAction constructor.
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
        return false;
    }

    /**
     * @param string $placeholder
     * @return $this
     */
    public function setPlaceholder($placeholder)
    {
        $this->placeholder = $placeholder;

        return $this;
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
            $button = new Submit($this->name .'_action');
            $button->icon('search')->color(UIRepository::STYLE_DEFAULT);
        }

        $button->setPrefix($this->prefix);
        $button->addClass($this->classes);

        return $button;
    }

    protected function variables()
    {
        return array_merge(parent::variables(), [
            'name'              => $this->name,
            'value'             => Request::get($this->name),
            'label'             => $this->label,
            'placeholder'       => $this->placeholder,
            'options'           => $this->buildOptions(),
            'button'            => $this->buildButton()
        ]);
    }
}