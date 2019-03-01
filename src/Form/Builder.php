<?php
/**
 * Builder.php
 * User: realeff
 * Date: 17-11-12
 */

namespace BaiSam\UI\Form;

use Closure;
use BaiSam\UI\Element;
use Illuminate\Support\Collection;
use Illuminate\Contracts\Support\Renderable;

/**
 * Class Builder
 *
 * @method Field\Button         button($column, $label = '')
 * @method Field\Submit         submit($column, $label = '')
 * @method Field\Reset          reset($column, $label = '')
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
class Builder extends Element implements Renderable
{
    /**
     * @var \BaiSam\UI\Form\Helper
     */
    protected $helper;

    /**
     * Form title.
     *
     * @var string
     */
    protected $title;

    /**
     * Form action
     *
     * @var string
     */
    protected $action;

    /**
     * Form method
     *
     * @var string
     */
    protected $method;

    /**
     * Form fields
     *
     * @var Collection
     */
    protected $fields;

    /**
     * @var string
     */
    protected $type = 'form';

    /**
     * View for form to render.
     *
     * @var string
     */
    protected $view = 'ui::form';

    /**
     * Builder constructor.
     *
     * @param string $action
     * @param string $method
     */
    public function __construct($action, $method = 'POST')
    {
        $this->action($action);
        $this->method($method);

        $this->fields = new Collection();

        // Make form helper
        $this->helper = app('form.helper');
        // Load the styles.
        $this->loadStyles();
    }

    /**
     * Set new action for the form.
     *
     * @param string $action
     *
     * @return $this
     */
    public function action($action)
    {
        $this->action = $action;

        return $this;
    }

    /**
     * Set new method for the form.
     *
     * @param string $method
     *
     * @return $this
     */
    public function method($method)
    {
        $this->method = $method;

        return $this;
    }

    /**
     * Set id for the form.
     *
     * @param string $id
     *
     * @return $this
     */
    public function setId($id)
    {
        $this->id = snake_case($id);

        return $this;
    }

    /**
     * Set label for the form.
     *
     * @param string $title
     *
     * @return $this
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get fields for the form.
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
     * @param boolean $group Only find group.
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
     * Push the element for the form.
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
     * Get or create group
     *
     * @param $name
     * @param Closure|array|null $callback
     *
     * @return Group
     *
     * @throws \InvalidArgumentException
     */
    public function group($name, $callback = null)
    {
        // 查找指定name的group
        $group = $this->find($name, true);
        if (false === $group) {
            // 未找到该分组，新建group，并将group放入fields.
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

    /**
     * Determine if form fields has files.
     *
     * @return boolean
     */
    public function hasFile()
    {
        $file = $this->fields()->first(function ($field) {
            if ($field instanceof Group) {
                return $field->hasFile();
            }

            return $field instanceof Field\File;
        });

        return $file ? true : false;
    }

    /**
     * @param mixed $content
     * @return $this
     */
    public function content($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Build the content.
     *
     * @return null|string
     */
    protected function buildContent()
    {
        $content = null;

        if (isset($this->content)) {
            ob_start();

            foreach (array_wrap($this->content) as $item) {
                if ($item instanceof Renderable) {
                    echo $item->render();
                }
                else {
                    echo e($item);
                }
            }

            $content = ob_get_clean();
        }

        return $content;
    }

    /**
     * Get the view variables of this element.
     *
     * @return array
     */
    protected function variables()
    {
        $method = strtoupper($this->method);
        $fields = $hiddenFields = [];

        // 如果method不是GET及POST,则增加_method
        if ( ! in_array($method, ['GET', 'POST']) ) {
            $hiddenFields['_method'] = method_field($method);
            $method = 'POST';
        }

        // 必需先设置前置ID
        $this->fields->each(function ($field) {
            // Set the field prefix to be form.
            $field->setPrefix([$this->formatId()]);
        });

        // 必须先编译Content内容块
        $content = $this->buildContent();

        foreach ($this->fields->sortBy(function ($field) {
            return $field->weight();
        }, SORT_NUMERIC) as $field) {
            if ($field instanceof Field && $field->isRender()) {
                continue;
            }

            if ($field instanceof Field\Hidden) {
                $hiddenFields[$field->column()] = $field;
            }
            else {
                $fields[$field->column()] = $field;
            }
        }

        return array_merge(parent::variables(), [
            'action'        => $this->action,
            'method'        => $method,
            'fields'        => $fields,
            'hiddenFields'  => $hiddenFields,
            'title'         => $this->title,
            'content'       => $content,
            '_resource_name'=> $this->getResourceName()
        ]);
    }

    /**
     * Get name for the form resource.
     *
     * @return string
     */
    protected function getResourceName()
    {
        return 'form';
    }

    /**
     * Get the evaluated contents of the object.
     *
     * @return string
     */
    public function render()
    {
        // 加载引用资源(js,css)及style scoped/Javascript内容
        $this->helper->getResource()
            ->requireResource($this->getResourceName());

        $this->attribute('accept-charset', 'UTF-8');
        if ( $this->hasFile() ) {
            $this->attribute('enctype',  'multipart/form-data');
        }

        //TODO pjax-container

        return $this->toHtml();
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