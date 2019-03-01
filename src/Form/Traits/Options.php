<?php
/**
 * Options.php
 * BaiSam admin
 *
 * Created by realeff on 2018/05/06.
 * Copyright ©2018 Jiangxi baisam information technology co., LTD. All rights reserved.
 */


namespace BaiSam\UI\Form\Traits;


use Illuminate\Contracts\Support\Arrayable;

trait Options
{
    /**
     * @var bool
     */
    protected $single = false;

    /**
     * Options for specify elements.
     *
     * @var array
     */
    protected $options = [];

    /**
     * Set the field options.
     *
     * @param array|\Closure $options
     *
     * @return $this
     */
    public function options($options = [])
    {
        if ($options instanceof Arrayable) {
            $options = $options->toArray();
        }

        if (is_callable($options)) {
            $this->options = $this->options ? call_user_func($options, $this->options) : $options;
        } else {
            $this->options = (array) $options;
        }

        $this->single = false;

        return $this;
    }

    /**
     * Set the field option.
     *
     * @param string|int $key
     * @param string $label
     * @return $this
     */
    public function option($key, $label = null)
    {
        $this->options[$key] = $label ?: $key;

        $this->single = true;

        return $this;
    }

    protected function buildOptions()
    {
        if (is_callable($this->options)) {
            $this->options = call_user_func($this->options, []);
        }

        return $this->options;
    }

}