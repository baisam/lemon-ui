<?php
/**
 * Group.php
 * User: realeff
 * Date: 17-11-14
 */

namespace BaiSam\UI\Form;

use Closure;
use BaiSam\UI\Element;
use BaiSam\Contracts\Sortable;
use Illuminate\Support\Collection;

/**
 * Class Group
 *
 * @method Field\Button         button($column, $label = '')
 * @method Field\Label          label($column, $label = '')
 * @method Field\Captcha        captcha($column, $label = '')
 * @method Field\Checkbox       checkbox($column, $label = '')
 * @method Field\Color          color($column, $label = '')
 * @method Field\Currency       currency($column, $label = '')
 * @method Field\Date           date($column, $label = '')
 * @method Field\DateRange      daterange($column, $label = '')
 * @method Field\Decimal        decimal($column, $label = '')
 * @method Field\Editor         editor($column, $label = '')
 * @method Field\Email          email($column, $label = '')
 * @method Field\File           file($column, $label = '')
 * @method Field\Html           html($column, $label = '')
 * @method Field\Image          image($column, $label = '')
 * @method Field\Ip             ip($column, $label = '')
 * @method Field\Number         number($column, $label = '')
 * @method Field\Password       password($column, $label = '')
 * @method Field\Phone          phone($column, $label = '')
 * @method Field\Radio          radio($column, $label = '')
 * @method Field\Select         select($column, $label = '')
 * @method Field\Slider         slider($column, $label = '')
 * @method Field\Switcher       switcher($column, $label = '')
 * @method Field\Tags           tags($column, $label = '')
 * @method Field\Text           text($column, $label = '')
 * @method Field\Textarea       textarea($column, $label = '')
 * @method Field\Url            url($column, $label = '')
 * @method Field\Hidden         hidden($column)
 * @method Field\DataSheet      datasheet($column, $label = '')
 *
 * @package BaiSam\UI\Form
 */
class Group extends Element implements Sortable
{
    /**
     * @var \BaiSam\UI\Form\Helper
     */
    protected $helper;

    /**
     * Column label.
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
     * Form fields
     *
     * @var Collection
     */
    protected $fields;

    /**
     * The weight for this field.
     *
     * @var float
     */
    protected $weight = null;

    /**
     * Render form group template view name.
     *
     * @var string
     */
    protected $view = 'ui::form.group';

    /**
     * Group constructor.
     *
     * @param string $column
     * @param string|null $label
     */
    function __construct($column, $label = null)
    {
        parent::__construct($column);

        $this->setLabel($label);

        $this->column = $column;

        $this->fields = new Collection();

        // make form helper
        $this->helper = app('form.helper');
    }

    public function setLabel($label)
    {
        $this->label = isset($label) ? e($label) : null;

        return $this;
    }

    /**
     * Get label of the group.
     *
     * @return string
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * Get the column definition name of the group.
     *
     * @return string
     */
    public function column()
    {
        return $this->column;
    }

    /**
     * Get fields for the group.
     *
     * @return Collection
     */
    public function fields()
    {
        return $this->fields;
    }

    /**
     * Get specify field.
     *
     * @param string $name
     *
     * @return Field|null
     *
     * @throws \InvalidArgumentException
     */
    public function field($name)
    {
        if ($field = $this->find($name)) {
            if ($field instanceof Field) {
                return $field;
            }

            throw new \InvalidArgumentException('field type error.');
        }

        return null;
    }

    /**
     * Determine if an field exists in the form by name, if exists return the field.
     *
     * @param string $name
     * @param boolean $group
     *
     * @return boolean|Field|Group
     */
    public function find($name, $group = false)
    {
        $field = null;
        if ($this->fields->has($name)) {
            $field = $this->fields->get($name);
        }
        else if (! $group) {
            $field = $this->fields->first(function ($field) use($name) {
                if ($field instanceof Suite) {
                    return $field->find($name) !== null;
                }

                return false;
            });

            if (isset($field)) {
                $field = $field->find($name);
            }
        }

        if (isset($field)) {
            if ($group) {
                return $field instanceof Group ? $field : false;
            }

            return $field;
        }

        return false;
    }

    /**
     * Push the element for the group.
     *
     * @param Field $field
     *
     * @return $this
     */
    public function push(Field $field)
    {
        // Set the field to fields
        $this->fields->put($field->column(), $field);

        // Set the field weight.
        $this->setWeight($field);

        return $this;
    }

    protected function setWeight($field)
    {
        if (is_null($field->weight())) {
            $field->weight(count($this->fields));
        }
    }

    /**
     * Remove an field from the fields by name.
     *
     * @param string $name
     * @return $this
     */
    public function forget($name)
    {
        $this->fields->forget($name);

        return $this;
    }

    /**
     *
     * @param string $name
     * @param Closure|array|null $callback
     *
     * @return Group
     *
     * @throws \InvalidArgumentException
     */
    public function group($name, $callback = null)
    {
        $group = $this->find($name, true);
        if (false === $group) {
            $group = new Group($name);
            $this->fields->put($name, $group);
            // Set the group weight.
            $this->setWeight($group);
        }
        else if (!($group instanceof Group)) {
            throw new \InvalidArgumentException('group type error.');
        }

        if (is_null($callback)) {
            return $group;
        }

        if (is_callable($callback)) {
            call_user_func($callback, $group);
        }
        else if (is_array($callback)) {
            foreach ($callback as $field) {
                if ($field instanceof Field) {
                    $group->push($field);
                }
            }
        }

        return $group;
    }

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
     * @param string $prefix
     * @return $this
     */
    public function setPrefix($prefix)
    {
        parent::setPrefix($prefix);

        foreach ($this->fields as $field) {
            $field->setPrefix([$this->formatId(), $this->formatName()]);
        }

        return $this;
    }

    /**
     * Determine if form fields has files.
     *
     * @return boolean
     */
    public function hasFile()
    {
        $file = $this->fields()->first(function (Field $field) {
            if ($field instanceof Suite || $field instanceof Group) {
                return $field->hasFile();
            }

            return $field instanceof Field\File;
        });

        return $file ? true : false;
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
     * Get the view variables of this group.
     *
     * @return array
     */
    protected function variables()
    {
        $fields = $hiddenFields = [];
        foreach ($this->fields->sortBy(function ($field) {
            return $field->weight();
        }) as $field) {
            if ($field instanceof Field && $field->isRender()) {
                continue;
            }

            if ($field instanceof Field\Hidden) {
                $hiddenFields[] = $field;
            }
            else {
                $fields[] = $field;
            }
        }

        return array_merge(parent::variables(), [
            'label'         => $this->getLabel(),
            'fields'        => $fields,
            'hiddenFields'  => $hiddenFields
        ]);
    }

    /**
     * @param string $name method
     * @param $arguments
     *
     * @return Field|$this
     */
    function __call($name, $arguments)
    {
        // 查找指定的字段类型
        if ($class = $this->helper->findFieldClass($name)) {
            // 实例化新字段
            $field = new $class(...$arguments);

            $this->push($field);

            return $field;
        }

        return parent::__call($name, $arguments);
    }
}