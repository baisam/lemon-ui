<?php
/**
 * SuiteTrait.php
 * BaiSam admin
 *
 * Created by realeff on 2018/05/21.
 * Copyright ©2018 Jiangxi baisam information technology co., LTD. All rights reserved.
 */


namespace BaiSam\UI\Form\Traits;


use Illuminate\Support\Arr;

trait SuiteTrait
{
    /**
     * @return array
     */
    abstract public function getAccessories();

    /**
     * @param string $name
     * @return \BaiSam\UI\Form\Field
     */
    public function find($name)
    {
        return Arr::first($this->getAccessories(), function($field) use($name) {
            return $field->column() === $name;
        });
    }

    /**
     * @return boolean
     */
    public function hasFile()
    {
        return false;
    }

    /**
     * @param string $prefix
     * @param null $name
     * @return $this
     */
    public function setPrefix($prefix)
    {
        parent::setPrefix($prefix);

        foreach ($this->getAccessories() as $field) {
            $field->setPrefix([$this->formatId(), $this->formatName()]);
        }

        return $this;
    }

    /**
     * @return bool
     */
    public function hasError()
    {
        $errors = session()->get('errors');
        if (empty($errors)) {
            return false;
        }

        foreach ($this->getAccessories() as $field) {
            if ($field->hasError()) {
                return true;
            }
        }

        return false;
    }

    public function getError()
    {
        $errors = session()->get('errors');
        if (empty($errors)) {
            return null;
        }


        foreach ($this->getAccessories() as $field) {
            if ($error = $field->getError()) {
                return $error;
            }
        }

        return null;
    }

    /**
     * @param array $value
     * @return $this
     */
    public function setValue($value)
    {
        foreach ($this->getAccessories() as $field) {
            $field->setValue(Arr::get($value, $field->column()));
        }

        $this->value = $value;

        return $this;
    }

    /**
     * @param array $default
     * @return $this
     */
    public function setDefault($default)
    {
        foreach ($this->getAccessories() as $field) {
            $field->setDefault(Arr::get($default, $field->column()));
        }

        $this->default = $default;

        return $this;
    }

    /**
     * @param array $original
     * @return $this
     */
    public function setOriginal($original)
    {
        foreach ($this->getAccessories() as $field) {
            $field->setOriginal(Arr::get($original, $field->column()));
        }

        $this->original = $original;

        return $this;
    }

    /**
     * @return array
     */
    public function original()
    {
        if (isset($this->original)) {
            return $this->original;
        }

        $values = [];
        foreach ($this->getAccessories() as $field) {
            $values[$field->column()] = $field->original();
        }
        return $values;
    }

    /**
     * @return array
     */
    public function value()
    {
        return $this->value ?: $this->original();
    }
}