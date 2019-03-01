<?php
/**
 * Suite.php
 * BaiSam admin
 *
 * Created by realeff on 2018/05/20.
 * Copyright ©2018 Jiangxi baisam information technology co., LTD. All rights reserved.
 */


namespace BaiSam\UI\Form;


interface Suite
{
    /**
     * Get accessories.
     *
     * @return array
     */
    public function getAccessories();

    /**
     * Find accessory.
     *
     * @param string $name
     *
     * @return Field
     */
    public function find($name);

    /**
     * Has File.
     *
     * @return boolean
     */
    public function hasFile();
}