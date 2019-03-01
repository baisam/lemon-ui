<?php
/**
 * NullableColumn.php
 * BaiSam huixin
 *
 * Created by realeff on 2018/11/16.
 * Copyright ©2018 Jiangxi baisam information technology co., LTD. All rights reserved.
 */


namespace BaiSam\UI\Grid;


class Nullable extends Column
{
    /**
     * 默认权重
     * @var int
     */
    protected $weight = -999998;

    protected $type = 'nullable';

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return HtmlString
     */
    public function getTitle()
    {
        return null;
    }

    /**
     * @param Row $row
     * @param Builder $builder
     * @return string
     */
    public function render(Row $row, Builder $builder)
    {
        return '';
    }
}