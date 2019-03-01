<?php
/**
 * DataSheet.php
 * BaiSam huixin
 *
 * Created by realeff on 2019/01/09.
 * Copyright ©2018 Jiangxi baisam information technology co., LTD. All rights reserved.
 */


namespace BaiSam\UI\Form\Field;


use BaiSam\UI\Form\Field;
use BaiSam\UI\Form\Suite;
use BaiSam\UI\Grid\Builder as Grid;
use BaiSam\UI\Grid\Render\Actions;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Support\Collection;

/**
 * Class DataSheet
 *
 * @package BaiSam\UI\Form\Field
 */
class DataSheet extends Field implements Suite
{
    /**
     * 主键
     * @var string
     */
    protected $majorKey = 'id';

    /**
     * @var array
     */
    protected $fields = [];

    /**
     * @var Grid
     */
    protected $grid;

    /**
     * @var boolean
     */
    protected $sort = false;

    /**
     * @var string
     */
    protected $view = 'ui::form.datasheet';

    /**
     * 设置主键名
     * @param string $key
     * @return $this
     */
    public function setKeyName($key)
    {
        $this->majorKey = $key;

        return $this;
    }

    /**
     * @param array|callable $items
     * @return $this
     */
    public function items($items)
    {
        if (is_callable($items)) {
            $items = call_user_func($items, $this);
        }

        collect($items)->each(function ($field) {
            if ($field instanceof Field) {
                $this->push($field);
            }
            else if (is_array($field) && count($field) > 1) {
                list($column, $label) = $field;
                $field = new Text($column, $label);
                $this->push($field);
            }
        });

        return $this;
    }

    /**
     * @param callable|string|null $column
     * @param string|null $label
     * @return $this
     */
    public function item($column, $label = null)
    {
        $field = null;
        if (is_callable($column)) {
            $field = call_user_func($column, $this);
        }
        else if (isset($label)) {
            $field = new Text($column, $label);
        }

        if ($field instanceof Field) {
            $this->push($field);
        }

        return $this;
    }

    /**
     * @param Field $field
     * @return $this
     */
    public function push(Field $field)
    {
        // 忽略默认表单渲染
        $field->ignoreRender();

        $this->fields[$field->column()] = $field;

        return $this;
    }

    /**
     * @param $name
     * @return $this
     */
    public function forget($name)
    {
        unset($this->fields[$name]);

        return $this;
    }

    /**
     * Get accessories.
     *
     * @return array
     */
    public function getAccessories()
    {
        return $this->fields;
    }

    /**
     * Find accessory.
     *
     * @param string $name
     *
     * @return Field
     */
    public function find($name)
    {
        return array_first($this->fields, function($field) use($name) {
            return $field->column() === $name;
        });
    }

    /**
     * Has File.
     *
     * @return boolean
     */
    public function hasFile()
    {
        $file = array_first($this->fields, function ($field) {
            if ($field instanceof Suite) {
                return $field->hasFile();
            }

            return $field instanceof Field\File;
        });

        return $file ? true : false;
    }

    /**
     * @param boolean $sort
     * @return $this
     */
    public function sort($sort = true)
    {
        $this->sort = $sort;

        return $this;
    }

    /**
     * @return Collection|mixed|null
     */
    public function value()
    {
        $value = $this->value ?: $this->original();

        if ($value instanceof Collection ||
            $value instanceof LengthAwarePaginator ||
            $value instanceof Eloquent ||
            $value instanceof \Illuminate\Database\Eloquent\Builder) {
            return $value;
        }
        else {
            $data = collect($value);
            if (is_scalar($data->first())) {
                return null;
            }

            return $data;
        }
    }

    /**
     * Build the data sheet.
     *
     * @return Grid
     */
    protected function buildGrid()
    {
        $this->grid = new Grid($this->formatId(), $this->buildConfig());
        // 设置主键
        $this->grid->setKeyName($this->majorKey);
        // 设置模板
        $this->grid->setView($this->view);
        // 禁止分页
        $this->grid->disablePagination();

        $this->grid->variables = $this->variables;
        $this->grid->help = $this->help;
        $this->grid->placeholder = $this->placeholder;
        $this->grid->classes = $this->classes;
        $this->grid->attributes = $this->attributes;

        $fields = array_merge([
            $this->majorKey => new Hidden($this->majorKey, '序号')
        ], $this->fields);

        /**
         * @var Field $field
         */
        foreach ($fields as $field) {
            if ($this->readonly) {
                $field->readonly($this->readonly);
            }
            if ($this->disabled) {
                $field->disabled($this->disabled);
            }

            $this->grid->column($field->column(), $field->getLabel())
                ->format(function ($value, $row) use ($field) {
                    $newField = clone $field;

                    $prefixId = $this->grid->getId() .'_row_'. $row->getMajorKey();
                    $prefixName = $this->formatName() .'['. $row->getMajorKey() .']';
                    $newField->setPrefix([$prefixId, $prefixName]);
                    $newField->setOriginal($value);

                    return $newField;
                });
        }

        if (! $this->readonly && ! $this->disabled) {
            $this->grid->setEmptyString('###NEW###');

            $this->grid->column('op', '操作')->weight(999)
                ->actions(function (Actions $actions) {
                    // 向下增加行
                    $actions->button('insert', '')
                        ->icon($this->getStyle('icon.plus', 'plus'));
                    // 删除行
                    $actions->button('delete', '')
                        ->icon($this->getStyle('icon.minus', 'minus'));

                    if ($this->sort) {
                        // 上移
                        $actions->button('up', '')
                            ->icon($this->getStyle('icon.up', 'arrow-up'));
                        // 下移
                        $actions->button('down', '')
                            ->icon($this->getStyle('icon.down', 'arrow-down'));
                    }
                });
        }

        $this->grid->fields = $fields;
        $this->grid->setData(old($this->formatColumn(), $this->value()));

        return $this->grid;
    }

    /**
     * @return string
     */
    public function toHtml()
    {
        $this->rendered = true;

        // 编译表格
        $this->buildGrid();

        // 加载引用资源(js,css)及style scoped/Javascript内容
        $this->loadResources();

        return $this->grid->render();
    }

}