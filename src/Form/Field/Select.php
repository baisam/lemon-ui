<?php
/**
 * Select.php
 * User: realeff
 * Date: 17-11-14
 */

namespace BaiSam\UI\Form\Field;


use BaiSam\UI\Form\Field;
use BaiSam\UI\Form\Traits\Options;

class Select extends Field
{
    use Options {
        options as protected baseOptions;
    }

    /**
     * @var bool
     */
    protected $multiple = false;

    /**
     * @var int
     */
    protected $size = 0;

    /**
     * Default option.
     *
     * @var string|null
     */
    protected $choice = null;

    /**
     * Allow multiple for the select.
     *
     * @param boolean $multiple
     * @return $this
     */
    public function multiple($multiple = true)
    {
        $this->multiple = $multiple;

        return $this;
    }

    /**
     * Set the size to specify the number of select to display.
     *
     * @param int $size
     *
     * @return $this
     */
    public function size($size)
    {
        $this->size = $size;
        if ($size > 1) {
            $this->multiple();
        }

        return $this;
    }

    /**
     * 设置选择文本
     *
     * @param string $choice
     * @return $this
     */
    public function choice($choice = '--请选择--')
    {
        $this->choice = $choice;

        return $this;
    }

    /**
     * Set or get the field options.
     *
     * @param array|\Closure $options
     *
     * @return $this
     */
    public function options($options = [])
    {
        //TODO 客户端异步加载数据项

        return $this->baseOptions($options);
    }

    protected function formatAttributes()
    {
        if ($this->multiple) {
            $this->attribute('multiple', 'multiple');
        }
        if ($this->size > 1) {
            $this->attribute('size', $this->size);
        }

        return parent::formatAttributes();
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
            'multiple'      => $this->multiple,
            'size'          => $this->size,
            'choice'        => $this->choice,
            'options'       => $this->buildOptions()
        ]);
    }
}