<?php
/**
 * Html.php
 * BaiSam admin
 *
 * Created by realeff on 2018/06/02.
 * Copyright ©2018 Jiangxi baisam information technology co., LTD. All rights reserved.
 */


namespace BaiSam\UI;


use Illuminate\Contracts\Support\Htmlable;

class HtmlTag implements Htmlable
{
    /**
     * Html type.
     *
     * @var string
     */
    protected $type;

    /**
     * Html content.
     *
     * @var string
     */
    protected $content;

    /**
     * Html classes.
     *
     * @var array
     */
    protected $classes = [];

    /**
     * Html attributes.
     *
     * @var array
     */
    protected $attributes = [];

    /**
     * Html constructor.
     *
     * @param string $type
     * @param string $content
     */
    public function __construct($type, $content = null)
    {
        $this->type = $type;
        $this->content = $content;
    }

    /**
     * Get the element type.
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Add the element class.
     *
     * @param string|array $class
     *
     * @return $this
     */
    public function addClass($class)
    {
        if ($class) {
            $this->classes = array_merge($this->classes, (array) $class);

            $this->classes = array_unique($this->classes);
        }

        return $this;
    }

    /**
     * Remove element class.
     *
     * @param array|string $class
     *
     * @return $this
     */
    public function removeClass($class)
    {
        $delClass = [];

        if (is_string($class) || is_array($class)) {
            $delClass = (array) $class;
        }

        foreach ($delClass as $del) {
            if (($key = array_search($del, $this->classes))) {
                unset($this->classes[$key]);
            }
        }

        return $this;
    }

    /**
     * Checks if the specified class exists.
     *
     * @param string $class
     * @return boolean
     */
    public function hasClass($class)
    {
        return in_array($class, $this->classes);
    }

    /**
     * Add html attributes to elements.
     *
     * @param array|string $name
     * @param mixed        $value
     *
     * @return $this|mixed|null
     */
    public function attribute($name, $value = null)
    {
        // Get name of attribute
        if (is_string($name) && is_null($value)) {
            return $this->attributes[$name] ?? null;
        }

        if (is_array($name)) {
            // Merge attributes
            $this->attributes = array_merge($this->attributes, $name);
        } else {
            // Set attribute
            $this->attributes[$name] = (string) $value;
        }

        return $this;
    }

    /**
     * Format the html attributes.
     *
     * @return string
     */
    protected function formatClass()
    {
        return implode(' ', array_unique($this->classes));
    }

    /**
     * Format the html attributes.
     *
     * @return string
     */
    protected function formatAttributes()
    {
        $attributes = [];

        foreach ($this->attributes as $name => $value) {
            if (empty($value)) {
                $attributes[] = $name;
            }
            else {
                $attributes[] = $name.'="'.e($value).'"';
            }
        }

        return implode(' ', $attributes);
    }

    /**
     * Get content as a string of HTML.
     *
     * @return string
     */
    public function toHtml()
    {
        $class = $this->formatClass();
        if ($class) {
            $class = ' class="'. $class .'"';
        }
        $attributes = $this->formatAttributes();
        $content = e($this->content);

        return <<<EOT
<{$this->type}{$class} {$attributes}>{$content}</{$this->type}>
EOT;
    }

    /**
     * Convert to html string.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->toHtml();
    }
}