<?php
/**
 * FlashAction.php
 * BaiSam huixin
 *
 * Created by realeff on 2019/01/16.
 * Copyright ©2018 Jiangxi baisam information technology co., LTD. All rights reserved.
 */


namespace BaiSam\UI\Grid\Actions;


use BaiSam\UI\Grid\Action;
use BaiSam\UI\Grid\Traits\ActionRender;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Request;

class FlashAction implements Htmlable, Action
{
    use ActionRender;

    protected $keys;

    /**
     * FlashAction constructor.
     *
     * @param string ...$keys
     */
    public function __construct(...$keys)
    {
        $keys = Arr::flatten($keys);
        // 排除保留字
        $keys = array_diff($keys, ['_method', '_token', 'op']);

        $this->keys = $keys;
    }

    public function needSelectRow()
    {
        return false;
    }

    protected function buildItems($key, $value)
    {
        // 检查级数或对象
        $type = 1;
        if (isset($value[0]) && isset($value[count($value) -1])) {
            $type = 0;
        }

        $items = [];
        foreach ($value as $index => $item) {
            if (is_array($item)) {
                $items = array_merge($items, $this->buildItems($key ."[{$index}]", $item));
            }
            else {
                $items[] = ['name' => $key .($type ? "[{$index}]" : '[]'), 'value' => $item];
            }
        }

        return $items;
    }

    public function toHtml()
    {
        $items = [];
        foreach ($this->keys as $key) {
            $value = Request::get($key);

            if (is_array($value)) {
                foreach ($this->buildItems($key, $value) as $item) {
                    $items[] = <<<EOT
<input type="hidden" name="{$item['name']}" value="{$item['value']}" />
EOT;
                }
            }
            else {
                $items[] = <<<EOT
<input type="hidden" name="{$key}" value="{$value}" />
EOT;
            }
        }

        return implode("\n", $items);
    }

}