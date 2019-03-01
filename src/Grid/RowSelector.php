<?php
/**
 * RowSelecter.php
 * BaiSam admin
 *
 * Created by realeff on 2018/11/01.
 * Copyright ©2018 Jiangxi baisam information technology co., LTD. All rights reserved.
 */


namespace BaiSam\UI\Grid;


use Illuminate\Support\HtmlString;

class RowSelector extends Column
{
    /**
     * 默认权重
     * @var int
     */
    protected $weight = -999999;

    protected $type = 'rowSelect';

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
        $html = <<<EOT
<input type="checkbox" name="{$this->name}_all" />
EOT;

        return new HtmlString($html);
    }

    /**
     * @param Row $row
     * @param Builder $builder
     * @return string
     */
    public function render(Row $row, Builder $builder)
    {
        $html = <<<EOT
<input type="checkbox" name="{$this->name}[]" value="{$row->getMajorKey()}" />
EOT;

        return new HtmlString($html);
    }

}