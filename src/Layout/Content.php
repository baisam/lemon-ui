<?php
/**
 * Content.php
 * BaiSam admin
 *
 * Created by realeff on 2018/05/25.
 * Copyright ©2018 Jiangxi baisam information technology co., LTD. All rights reserved.
 */


namespace BaiSam\UI\Layout;


use BaiSam\UI\Element;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Support\MessageBag;

/**
 * Class Content
 *
 * @package BaiSam\UI\Layout
 */
class Content extends Element implements Renderable
{
    /**
     * Content header.
     *
     * @var array
     */
    protected $header;

    /**
     * Content help.
     *
     * @var string|array
     */
    protected $help;

    /**
     * @var string
     */
    protected $type = 'content';

    /**
     * View for content to render.
     *
     * @var string
     */
    protected $view = 'ui::content';

    /**
     * Content constructor.
     *
     * @param mixed $content
     */
    public function __construct($content = null)
    {
        $this->content = $content;

        // Load the styles.
        $this->loadStyles();
    }

    /**
     * Set id for the content.
     *
     * @param string $id
     *
     * @return $this
     */
    public function setId($id)
    {
        $this->id = Str::snake($id);

        return $this;
    }

    /**
     * Set header of content.
     *
     * @param string $name
     * @param mixed $header
     *
     * @return $this
     */
    public function header($name, $header)
    {
        if (!isset($this->header)) {
            $this->header = [];
        }

        $this->header[$name] = $header;

        return $this;
    }

    /**
     * Assign the content.
     *
     * @param mixed $content
     *
     * @return $this
     */
    public function assign($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Add help to content.
     *
     * @param string|array $help
     * @return $this
     */
    public function help($help)
    {
        $this->help = $help;

        return $this;
    }

    protected function getHelp()
    {
        if (isset($this->help)) {
            return Arr::wrap($this->help);
        }

        return null;
    }

    /**
     * Set error message for content.
     *
     * @param string $title
     * @param string $message
     *
     * @return $this
     */
    public function withError($title = '', $message = '')
    {
        $error = new MessageBag(compact('title', 'message'));

        session()->flash('error', $error);

        return $this;
    }

    /**
     * Build the content.
     *
     * @return null|string
     */
    protected function build()
    {
        $content = null;

        if (isset($this->content)) {
            ob_start();

            foreach (Arr::wrap($this->content) as $item) {
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
     * Get the view variables of this content.
     *
     * @return array
     */
    protected function variables()
    {
        return array_merge(parent::variables(), [
            'header'        => $this->header,
            'content'       => $this->build(),
            'help'          => $this->getHelp()
        ]);
    }

    /**
     * @param array $data
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|string
     */
    protected function view(array $data)
    {
        if ($this->content instanceof \BaiSam\Contracts\Content) {
            return $this->content->view($data);
        }

        return view($this->view, $data);
    }

    /**
     * Get the evaluated contents of the object.
     *
     * @return string
     */
    public function render()
    {
        return $this->toHtml();
    }

    public function __toString()
    {
        return $this->render();
    }
}