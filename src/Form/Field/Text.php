<?php
/**
 * Textbox.php
 * User: realeff
 * Date: 17-11-14
 */

namespace BaiSam\UI\Form\Field;


use BaiSam\UI\Form\Field;
use BaiSam\UI\UIRepository;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Support\Arr;

class Text extends Field
{
    protected $input = 'text';

    protected $size;

    protected $maxlength = 0;

    protected $prepend = '';

    protected $append = '';

    protected $view = 'ui::form.input';

    public function prepend($html)
    {
        $this->prepend = $html;

        return $this;
    }

    public function append($html)
    {
        $this->append = $html;

        return $this;
    }

    public function maxLength($length)
    {
        $this->maxlength = $length;

        return $this;
    }

    /**
     * Set size for the text.
     *
     * @param string $size
     *
     * @return $this
     */
    public function size($size = UIRepository::STYLE_DEFAULT)
    {
        $this->size = $size;

        return $this;
    }

    protected function formatClass()
    {
        if (isset($this->size)) {
            $this->addClass($this->getStyle('size.'. $this->size, $this->size));
        }

        return parent::formatClass();
    }

    protected function getStyle($key, $default = null)
    {
        if ($style = parent::getStyle($key)) {
            return $style;
        }

        return Arr::get($this->styles, 'input.'. $key, $default);
    }

    protected function loadResources()
    {
        // 引用表单资源
        $this->helper
            ->getResource()
            ->requireResource('input');

        parent::loadResources();
    }

    protected function formatAttributes()
    {
        // Set max length for text attribute.
        if ($this->maxlength > 0) {
            $this->attribute('maxlength', $this->maxlength);
        }

        return parent::formatAttributes();
    }

    protected function variables()
    {
        foreach (['prepend', 'append'] as $key) {
            $html = $this->$key;
            if ($html instanceof Htmlable) {
                $this->variables[$key] = $html->toHtml();
            }
            else if ($html instanceof Renderable) {
                $this->variables[$key] = $html->render();
            }
            else {
                $this->variables[$key] = e($html);
            }
        }

        return array_merge(parent::variables(), [
            'input'             => $this->input,
            'maxlength'         => $this->maxlength
        ]);
    }
}