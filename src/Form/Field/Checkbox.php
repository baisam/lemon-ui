<?php
/**
 * Checkbox.php
 * User: realeff
 * Date: 17-11-14
 */

namespace BaiSam\UI\Form\Field;


use BaiSam\UI\Form\Field;
use BaiSam\UI\Form\Traits\Options;
use BaiSam\UI\UIRepository;

class Checkbox extends Field
{
    use Options;

    protected $inline = false;

    protected $circle = false;

    protected $color;

    /**
     * Draw inline checkboxes.
     *
     * @return $this
     */
    public function inline()
    {
        $this->inline = true;

        return $this;
    }

    /**
     * Draw stacked checkboxes.
     *
     * @return $this
     */
    public function stacked()
    {
        $this->inline = false;

        return $this;
    }

    /**
     * Draw circle checkboxes.
     *
     * @return $this
     */
    public function roundness()
    {
        $this->circle = true;

        return $this;
    }

    /**
     * Draw diamond checkboxes.
     *
     * @return $this
     */
    public function diamond()
    {
        $this->circle = false;

        return $this;
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
        if ($this->inline) {
            $this->addClass($this->getStyle(UIRepository::STYLE_INLINE, 'checkbox-inline'));
        }
        if ($this->circle) {
            $this->addClass($this->getStyle(UIRepository::STYLE_CIRCLE, 'checkbox-circle'));
        }
        if (isset($this->color)) {
            $this->addClass($this->getStyle('color.'. $this->color, $this->color));
        }

        return parent::formatClass();
    }

    public function value()
    {
        $value = $this->value ?: $this->original();

        if (is_scalar($value)) {
            return $value;
        }
        else if(isset($value)) {
            $data = collect($value);
            if (is_scalar($data->first())) {
                return $data->toArray();
            }
        }

        return null;
    }

    protected function variables()
    {
        return array_merge(parent::variables(), [
            'inline'        => $this->inline,
            'circle'        => $this->circle,
            'options'       => $this->buildOptions(),
            'single'        => $this->single
        ]);
    }
}