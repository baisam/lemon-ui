<?php
/**
 * Created by PhpStorm.
 * User: Peak
 * Date: 2018/11/14
 * Time: 23:10
 */

namespace BaiSam\UI\Grid\Render;

use BaiSam\UI\Grid\Render as AbstractRender;

class Gender extends AbstractRender
{
    protected $map = [1 => '男', 2 => '女', 0 => '未知'];


    public function __construct(string $name, array $_map = null)
    {
        if (isset($_map)) {
            $this->map = $_map;
        }
        parent::__construct($name);
    }

    public function render($value, $row, $builder)
    {
        // 调用模板输出 ui::grid.gender
        $data = [
            //'id' => $builder->getId() .'_row_' . $row->getMajorKey() . '_' . $this->name,
            'name' => $this->name,
            'value' => $value,
            'map' => $this->map
        ];

        return view('ui::grid.gender', $data);
    }

}