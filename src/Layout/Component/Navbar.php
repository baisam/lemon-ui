<?php
/**
 * Navbar.php
 * BaiSam admin
 *
 * Created by realeff on 2018/05/25.
 * Copyright ©2018 Jiangxi baisam information technology co., LTD. All rights reserved.
 */


namespace BaiSam\UI\Layout\Component;

use Closure;
use BaiSam\UI\HtmlTag;
use BaiSam\UI\Element;
use BaiSam\UI\Form\Field\Button;
use BaiSam\UI\Form\Builder as Form;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\View;

/**
 * 导航栏
 *
 * @package BaiSam\UI\Layout\Component
 */
class Navbar extends Element
{
    const STYLE_TOP         = 'top';
    const STYLE_BOTTOM      = 'bottom';
    const STYLE_NAVIGATION  = 'navigation';
    const STYLE_FORM        = 'form';
    const STYLE_TEXT        = 'text';
    const STYLE_BUTTON      = 'button';

    /**
     * Navbar the name.
     *
     * @var string
     */
    protected $name;

    /**
     * @var Collection
     */
    protected $items;

    /**
     * @var string
     */
    protected $fixed;

    /**
     * View for element to render.
     *
     * @var string
     */
    protected $view = 'ui::layouts.navbar';

    /**
     * Navbar constructor.
     *
     * @param string $name
     */
    public function __construct(string $name)
    {
        parent::__construct($name);

        $this->name = $name;
        $this->items = new Collection();
    }

    /**
     * Add a navbar item.
     *
     * @param string $name
     * @param mixed $item
     *
     * @return $this
     */
    public function put($name, $item)
    {
        $this->items->put($name, $item);

        return $this;
    }

    /**
     * @param string $name
     * @param Closure $callable
     *
     * @return Navigation
     * @throws \InvalidArgumentException
     */
    public function navigation($name, Closure $callable = null)
    {
        $navigation = isset($this->items[$name]) ? $this->items[$name] : null;
        if (is_null($navigation)) {
            $navigation = new Navigation($name);
            $navigation->addClass($this->getStyle(self::STYLE_NAVIGATION, ''));

            $this->put($name, $navigation);
        }
        else if (! ($navigation instanceof Navigation)) {
            throw new \InvalidArgumentException('navigation type error.');
        }

        if (isset($callable)) {
            call_user_func($callable, $navigation);
        }

        return $navigation;
    }

    /**
     * @param string $name
     * @param Closure $callable
     *
     * @return Form
     * @throws \InvalidArgumentException
     */
    public function form($name, Closure $callable = null)
    {
        $form = isset($this->items[$name]) ? $this->items[$name] : null;
        if (is_null($form)) {
            $form = new Form('');
            $form->setId($name);
            $form->setView('ui::partials.form');
            $form->addClass($this->getStyle(self::STYLE_FORM, ''));

            $this->put($name, $form);
        }
        else if (! ($form instanceof Form)) {
            throw new \InvalidArgumentException('form type error.');
        }

        if (isset($callable)) {
            call_user_func($callable, $form);
        }

        return $form;
    }

    /**
     * @param string $name
     * @param string $content
     * @return HtmlTag
     */
    public function text($name, $content)
    {
        $text = isset($this->items[$name]) ? $this->items[$name] : null;
        if (is_null($text)) {
            $text = new HtmlTag('p', $content);
            $text->addClass($this->getStyle(self::STYLE_TEXT, ''));

            $this->put($name, $text);
        }
        else if (! ($text instanceof HtmlTag)) {
            throw new \InvalidArgumentException('text type error.');
        }

        return $text;
    }

    /**
     * @param string $name
     * @param string $label
     * @return Button
     */
    public function button($name, $label)
    {
        $button = isset($this->items[$name]) ? $this->items[$name] : null;
        if (is_null($button)) {
            $button = new Button($name, $label);
            $button->addClass($this->getStyle(self::STYLE_BUTTON, ''));

            $this->put($name, $button);
        }
        else if (! ($button instanceof Button)) {
            throw new \InvalidArgumentException('button type error.');
        }

        return $button;
    }

    /**
     * Set fixed position.
     *
     * @param string $fixed
     *
     * @return $this
     */
    public function fixed($fixed = self::STYLE_TOP)
    {
        if (in_array($fixed, [self::STYLE_TOP, self::STYLE_BOTTOM])) {
            $this->fixed = $fixed;
        }

        return $this;
    }

    /**
     * Format the element attributes.
     *
     * @return string
     */
    protected function formatClass()
    {
        if (isset($this->fixed)) {
            $this->addClass($this->getStyle($this->fixed, ''));
        }

        return parent::formatClass();
    }

    /**
     * Build the items.
     *
     * @return Collection
     */
    protected function buildItems()
    {
        $this->items->each(function ($item) {
            if ($item instanceof Element) {
                $item->setPrefix($this->formatId());
            }
        });

        return $this->items;
    }

    /**
     * Get the view variables of this element.
     *
     * @return array
     */
    protected function variables()
    {
        return array_merge(parent::variables(), [
            'name'          => $this->name,
            'items'         => $this->buildItems()
        ]);
    }

    /**
     * Get view of this field.
     *
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    protected function view(array $data)
    {
        if ('ui::layouts.navbar' == $this->view) {
            if (View::exists($this->view .'-'. $this->name)) {
                $this->view = $this->view .'-'. $this->name;
            }
        }

        return view($this->view, $data);
    }

}