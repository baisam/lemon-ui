<?php
/**
 * Image.php
 * BaiSam admin
 *
 * Created by realeff on 2018/05/20.
 * Copyright ©2018 Jiangxi baisam information technology co., LTD. All rights reserved.
 */


namespace BaiSam\UI\Form\Field;


class Image extends File
{

    public function __construct(string $column, string $label = null, array $config = null)
    {
        if (is_null($config)) {
            $config = 'image';
        }
        else if (is_array($config) && !isset($config['upload'])) {
            $config['upload'] = 'image';
        }

        parent::__construct($column, $label, $config);
    }

    //TODO 生成缩略图
    //TODO 图片裁剪（宽度*高度）
    //TODO 图片压缩
}