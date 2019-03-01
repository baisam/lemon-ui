<?php
/**
 * Column.php
 * BaiSam admin
 *
 * Created by realeff on 2018/05/25.
 * Copyright ©2018 Jiangxi baisam information technology co., LTD. All rights reserved.
 */


namespace BaiSam\UI\Layout;


use Closure;
use BaiSam\UI\Element;
use Illuminate\Contracts\Support\Renderable;

/**
 * Class Column
 *
 * @package BaiSam\UI\Layout
 */
class Column extends Element
{
    /**
     * @var array
     */
    protected $content = [];

    /**
     * @var Row[]
     */
    protected $rows = [];

    /**
     * Column constructor.
     *
     * @param string $id
     * @param mixed|null $content
     */
    public function __construct(string $id, $content = null)
    {
        parent::__construct($id);

        if (isset($content)) {
            $this->append($content);
        }
    }

    /**
     * Append content to column.
     *
     * @param mixed $content
     *
     * @return $this
     */
    public function append($content)
    {
        $this->content[] = $content;

        return $this;
    }

    /**
     * Add a row.
     *
     * @param mixed $content
     *
     * @return \BaiSam\UI\Layout\Row
     */
    public function row($content = null)
    {
        if (is_int($content) && isset($this->rows[$content])) {
            return $this->rows[$content];
        }

        $id = 'row_'. count($this->rows);

        if ($content instanceof Closure) {
            $row = new Row($id);
            call_user_func($content, $row);
        }
        else {
            $row = new Row($id, $content);
        }

        $this->addRow($row);

        return $row;
    }

    /**
     * Add a row for layout.
     *
     * @param string $id
     * @param Row $row
     */
    protected function addRow(Row $row)
    {
        $this->append($row);

        $this->rows[] = $row;
    }

    /**
     * @return false|string
     */
    public function toHtml()
    {
        ob_start();

        $id = null;
        if (isset($this->prefix)) {
            $id = $this->formatId();
            foreach ($this->content as $item) {
                if ($item instanceof Row) {
                    // Set prefix for row.
                    $item->setPrefix($id);
                }
            }
        }
        $class = $this->formatClass();
        $attribute = $this->formatAttributes();

        echo "<div";
        if (isset($id)) {
            echo ' id="'. $id .'"';
        }
        echo " class=\"col {$class}\" {$attribute}>";

        foreach ($this->content as $content) {
            if ($content instanceof Renderable) {
                echo $content->render();
            } else {
                echo e($content);
            }
        }

        echo '</div>';

        $html = ob_get_clean();

        return $html;
    }
}