<?php
/**
 * Field.php
 * User: realeff
 * Date: 17-11-13
 */

namespace BaiSam\UI\Form;


use BaiSam\UI\Element;
use BaiSam\Contracts\Sortable;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class Field extends Element implements Sortable
{
    /**
     * Field label.
     *
     * @var string
     */
    protected $label;

    /**
     * Column name.
     *
     * @var string
     */
    protected $column;

    /**
     * Field value.
     *
     * @var mixed
     */
    protected $value = null;

    /**
     * Field original value.
     *
     * @var mixed
     */
    protected $original = null;

    /**
     * Field default value.
     *
     * @var mixed
     */
    protected $default = null;

    /**
     * Placeholder for this field.
     *
     * @var string
     */
    protected $placeholder = '';

    /**
     * Ignore the field.
     *
     * @var boolean
     */
    protected $ignored = false;

    /**
     * This field is required.
     *
     * @var boolean
     */
    protected $required = false;

    // TODO rules 规则设置

    /**
     * The validate rules for this field.
     *
     * @var array
     */
    protected $rules = [];

    /**
     * The help description for this field.
     *
     * @var array
     */
    protected $help = null;

    /**
     * The weight for this field.
     *
     * @var float
     */
    protected $weight = null;

    /**
     * The disabled for this field.
     *
     * @var null
     */
    protected $disabled = null;

    /**
     * The readonly for this field.
     *
     * @var null
     */
    protected $readonly = null;

    /**
     * Config for specify field.
     *
     * @var array
     */
    protected $config = [];

    /**
     * This field has been render.
     *
     * @var boolean
     */
    protected $rendered = false;

    /**
     * View for field to render.
     *
     * @var string
     */
    protected $view = 'ui::form';

    /**
     * @var \BaiSam\UI\Form\Helper
     */
    protected $helper;

    /**
     * Field constructor.
     *
     * @param string $column
     * @param string|null $label
     * @param array|string|null $config
     */
    function __construct(string $column, string $label = null, array $config = null)
    {
        parent::__construct($column);

        $this->label = isset($label) ? e($label) : Str::title($column);

        $this->column = $column;

        if ($config) {
            $this->config = $config;
        }

        // make form helper
        $this->helper = app('form.helper');
    }

    /**
     * Get label of the field.
     *
     * @return string
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * Format the field name.
     *
     * @return string
     */
    protected function formatName()
    {
        if (is_null($this->prefix)) {
            return $this->column;
        }

        if (is_array($this->prefix)) {
            return empty($this->prefix[1]) ? $this->column : $this->prefix[1] .'['. $this->column .']';
        }

        return $this->prefix .'['. $this->column .']';
    }

    /**
     * Get the column definition name of the field.
     *
     * @return string
     */
    public function column()
    {
        return $this->column;
    }

    protected function formatColumn()
    {
        return strtr($this->formatName(), array('[' => '.', ']' => ''));
    }

    /**
     * @param mixed $value
     *
     * @return $this
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * @param mixed $default
     *
     * @return $this
     */
    public function setDefault($default)
    {
        $this->default = $default;

        return $this;
    }

    /**
     * @param mixed $original
     * @return $this
     */
    public function setOriginal($original)
    {
        $this->original = $original;

        return $this;
    }

    /**
     * @return mixed
     */
    public function value()
    {
        $value = $this->value ?: $this->original();

        return is_scalar($value) ? $value : null;
    }

    /**
     * @return mixed
     */
    public function original()
    {
        return isset($this->original) ? $this->original : $this->default;
    }

    /**
     * @param boolean $required
     *
     * @return $this
     */
    public function required($required = true)
    {
        $this->required = $required;

        return $this;
    }

    /**
     * @return boolean
     */
    public function isRequired()
    {
        return $this->required;
    }

    /**
     * @return boolean
     */
    public function ignored()
    {
        return $this->ignored;
    }

    // TODO required配置rule校验数据是否必填项
    // TODO ignored配置是否忽略此字段

    protected function formatRules()
    {
        // TODO 配置解析规则并编译校验规则
        return $this->rules;
    }

    /**
     * @param string $placeholder
     * @return $this
     */
    public function setPlaceholder($placeholder)
    {
        $this->placeholder = $placeholder;

        return $this;
    }

    /**
     * Set help content for the field.
     *
     * @param string $help
     *
     * @return $this
     */
    public function setHelp($text, $icon = null)
    {
        $this->help = ['text' => $text];
        if (isset($icon)) {
            $this->help['icon'] = $icon;
        }

        return $this;
    }

    /**
     * Get help content for the field.
     *
     * @return array|null
     */
    public function help()
    {
        return $this->help;
    }

    /**
     * Set or get the weight for the field.
     *
     * @param float $weight
     *
     * @return $this|float
     */
    public function weight($weight = null)
    {
        if (is_null($weight)) {
            return $this->weight;
        }

        $this->weight = $weight;

        return $this;
    }

    /**
     * Set the disabled for the field.
     *
     * @param boolean $disabled
     * @return $this
     */
    public function disabled($disabled = true)
    {
        $this->disabled = $disabled;

        return $this;
    }

    /**
     * Set the readonly for the field.
     *
     * @param boolean $readonly
     * @return $this
     */
    public function readonly($readonly = true)
    {
        $this->readonly = $readonly;

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

        return $errors->has($this->formatColumn());
    }

    public function getError()
    {
        $errors = session()->get('errors');
        if (empty($errors)) {
            return null;
        }

        return $errors->first($this->formatColumn());
    }

    /**
     * Load the resources required for this field.
     *
     * @return void
     */
    protected function loadResources()
    {
        // 引用表单资源
        $this->helper
            ->getResource()
            ->requireResource($this->getResourceName());
    }

    /**
     * Get name for the field resource.
     *
     * @return string
     */
    protected function getResourceName()
    {
        return 'form.'. $this->type;
    }

    /**
     * Set the field options.
     *
     * @param string|array $name
     * @param mixed|\Closure|null $value
     *
     * @return $this
     */
    public function config($name, $value = null)
    {
        if (is_array($name)) {
            $this->config = array_merge($this->config, $name);
        }
        else {
            Arr::set($this->config, (string)$name, $value);
        }

        return $this;
    }

    protected function buildConfig()
    {
        if (!is_array($this->config)) {
            return $this->config;
        }

        $config = $this->config;

        foreach ($config as $index => $cfg) {
            if (is_callable($cfg)) {
                //TODO 将函数返回值转换为javascript function，提取为Config对象
            }
        }

        return $config;
    }

    protected function formatAttributes()
    {
        if ($this->disabled) {
            $this->attribute('disabled', '');
        }

        if ($this->readonly) {
            $this->attribute('readonly', '');
        }

        return parent::formatAttributes();
    }

    /**
     * Get the view variables of this element.
     *
     * @return array
     */
    protected function variables()
    {
        $value = null;
        if (! $this->ignored) {
            $value = old($this->formatColumn());
            if (is_null($value)) {
                $value = $this->value();
            }
        }

        return array_merge(parent::variables(), [
            'type'          => $this->type,
            'label'         => $this->getLabel(),
            'column'        => $this->column,
            'key'           => $this->formatColumn(),
            'name'          => $this->formatName(),
            'value'         => $value,
            'required'      => $this->required,
            'rules'         => $this->formatRules(),
            'help'          => $this->help,
            'placeholder'   => $this->placeholder,
            'config'        => $this->buildConfig(),
            '_resource_name'=> $this->getResourceName()
        ]);
    }

    /**
     * Get view of this field.
     *
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    protected function view(array $data)
    {
        if ('ui::form' == $this->view) {
            return view($this->view .'.'. $this->type, $data);
        }

        return view($this->view, $data);
    }

    /**
     * Ignore render.
     *
     * @param boolean $render
     * @return $this
     */
    public function ignoreRender($render = true)
    {
        $this->rendered = $render;

        return $this;
    }

    /**
     * Has it been rendered.
     *
     * @return boolean
     */
    public function isRender()
    {
        return $this->rendered;
    }

    /**
     * @return string
     * @throws \Throwable
     */
    public function toHtml()
    {
        $this->rendered = true;

        // 加载引用资源(js,css)及style scoped/Javascript内容
        $this->loadResources();

        return parent::toHtml();
    }

}