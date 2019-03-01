<?php
/**
 * Captcha.php
 * BaiSam admin
 *
 * Created by realeff on 2018/05/15.
 * Copyright ©2018 Jiangxi baisam information technology co., LTD. All rights reserved.
 */


namespace BaiSam\UI\Form\Field;


use BaiSam\UI\Form\Field;

class Captcha extends Field
{

    protected $ignored = true;

    /**
     * @var string
     */
    protected $config = 'default';

    /**
     * Captcha constructor.
     *
     * @param string $column
     * @param string|null $label
     * @param string $config
     * @throws \Exception
     */
    public function __construct(string $column, string $label = null,  string $config = null)
    {
        if (!class_exists('Mews\Captcha\Captcha')) {
            throw new \Exception('To use captcha field, please install [mews/captcha] first.');
        }

        parent::__construct($column, $label, $config);
    }

    /**
     * @param string $config
     * @param null $value
     * @return $this
     */
    public function config($config, $value = null)
    {
        $this->config = $config;

        return $this;
    }
}