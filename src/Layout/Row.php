<?php
/**
 * Row.php
 * BaiSam admin
 *
 * Created by realeff on 2018/05/25.
 * Copyright ©2018 Jiangxi baisam information technology co., LTD. All rights reserved.
 */


namespace BaiSam\UI\Layout;


use Closure;
use BaiSam\UI\Element;

/**
 * Class Row
 *
 * @package BaiSam\UI\Layout
 */
class Row extends Element
{
    /**
     * @var Column[]
     */
    protected $columns = [];

    /**
     * Row constructor.
     *
     * @param string $id
     * @param mixed $content
     */
    public function __construct(string $id, $content = null)
    {
        parent::__construct($id);

        if (isset($content)) {
            $this->column($content);
        }
    }

    /**
     * Add a column.
     *
     * @param mixed $content
     *
     * @return Column
     */
    public function column($content = null)
    {
        if (is_int($content) && isset($this->columns[$content])) {
            return $this->columns[$content];
        }

        // Generate the column id.
        $id = 'col_'. count($this->columns);

        if ($content instanceof Closure) {
            $column = new Column($id);
            call_user_func($content, $column);
        }
        else {
            $column = new Column($id, $content);
        }

        $this->addColumn($column);

        return $column;
    }

    /**
     * Add a column for row.
     *
     * @param Column $column
     */
    protected function addColumn(Column $column)
    {
        $this->columns[] = $column;
    }

    /**
     * @return string
     */
    public function toHtml()
    {
        $id = '';
        if (isset($this->prefix)) {
            $id = ' id="'. $this->formatId() .'"';
            foreach ($this->columns as $column) {
                // Set prefix for column.
                $column->setPrefix($this->formatId());
            }
        }

        $class = $this->formatClass();
        $attribute = $this->formatAttributes();
        $content = implode("\n", $this->columns);

        return <<<EOT
<div{$id} class="row {$class}" {$attribute}>{$content}</div>
EOT;
    }
}