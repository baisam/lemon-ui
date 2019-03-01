<?php
/**
 * CheckboxAction.php
 * BaiSam huixin
 *
 * Created by realeff on 2018/11/16.
 * Copyright ©2018 Jiangxi baisam information technology co., LTD. All rights reserved.
 */


namespace BaiSam\UI\Grid\Actions;


use BaiSam\UI\Element;
use BaiSam\UI\Form\Traits\Options;
use BaiSam\UI\Grid\Action;
use BaiSam\UI\Grid\Traits\ActionRender;
use BaiSam\UI\UIRepository;
use Illuminate\Support\Facades\Request;

class CheckboxAction extends Element implements Action
{
    use Options, ActionRender;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var array
     */
    protected $config = [];

    /**
     * @var string
     */
    protected $color = UIRepository::STYLE_DEFAULT;

    protected $view = 'ui::grid.actions.checkbox';

    /**
     * @var string
     */
    protected $type = 'checkbox';

    /**
     * CheckboxAction constructor.
     *
     * @param string $name
     * @param string|null $label
     * @param array $config
     */
    public function __construct(string $name, string $label = null, array $config = [])
    {
        parent::__construct($name, $label);

        $this->name = $name;

        if ($config) {
            $this->config = $config;
        }
    }

    /**
     * @return boolean
     */
    public function needSelectRow()
    {
        return false;
    }

    /**
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

    protected function variables()
    {
        return array_merge(parent::variables(), [
            'name'          => $this->name,
            'value'         => Request::get($this->name),
            'options'       => $this->buildOptions(),
            'single'        => $this->single,
            'config'        => $this->config
        ]);
    }
}