<?php
/**
 * Builder.php
 * User: realeff
 * Date: 17-11-12
 */

namespace BaiSam\UI\Grid;


use Closure;
use BaiSam\UI\Element;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;

class Builder extends Element implements Renderable
{
    /**
     * @var \BaiSam\UI\Grid\Helper
     */
    protected $helper;

    /**
     * Grid title.
     *
     * @var string
     */
    protected $title;

    /**
     * 主键
     * @var string
     */
    protected $majorKey = 'id';

    /**
     * 数据源
     * @var array
     */
    protected $data = null;

    /**
     * 表头信息
     * @var array
     */
    private $header;

    /**
     * 列信息
     * @var Collection
     */
    protected $columns;

    /**
     * 行信息
     * @var Collection
     */
    protected $rows;

    /**
     * 合并列信息
     * @var array
     */
    protected $complexCols = [];

    /**
     * 可见列信息
     * @var array
     */
    protected $visibleCols;

    /**
     * 固定列名或列索引
     *
     * @var null|string|int
     */
    protected $fixedColumn = null;

    /**
     * 每页显示记录数
     * @var int
     */
    protected $perPage = 20;

    /**
     * 记录总数
     * @var int
     */
    protected $total = null;

    /**
     * 没有记录时显示的内容
     * @var string
     */
    protected $emptyString = null;

    /**
     * 过滤项
     * @var Collection
     */
    protected $filters;

    /**
     * 工具条
     *
     * @var array
     */
    protected $toolbar;

    /**
     * 使用分页
     * @var bool
     */
    protected $usePagination = true;

    /**
     * 分页器
     * @var Paginator
     */
    protected $pager;

    /**
     * Config for specify field.
     *
     * @var array
     */
    protected $config = [];

    /**
     * 标记已经被编译
     *
     * @var bool
     */
    protected $builded = false;

    /**
     * @var string
     */
    protected $type = 'grid';

    /**
     * View for form to render.
     *
     * @var string
     */
    protected $view = 'ui::grid';

    /**
     * Builder constructor.
     *
     * @param string $id
     * @param array $config
     */
    public function __construct(string $id, array $config = null)
    {
        parent::__construct($id);

        $this->columns = new Collection();
        $this->rows = new Collection();

        if ($config) {
            $this->config = $config;
        }

        // default toolbar
        $this->toolbar();

        // make form helper
        $this->helper = app('grid.helper');
    }

    /**
     * Set label for the grid.
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
     * 设置主键名
     * @param string $key
     * @return $this
     */
    public function setKeyName($key)
    {
        $this->majorKey = $key;

        return $this;
    }

    /**
     * 为表格设置列信息
     *
     * @param string $name 列名|字段名
     * @param string $title 列标题
     * @return Column
     */
    public function column($name, $title = null)
    {
        $column = new Column($name, $title);
        // 初始化权重
        $column->weight(count($this->columns));

        $this->columns->push($column);

        return $column;
    }

    /**
     * 将两个以上的列合并为一个列输出
     *
     * @param string $name 合成列名称
     * @param string $title 合成列标题
     * @param string $column 合成列名
     * @param string ...$columns 合成列名
     * @return $this
     */
    public function complex($name, $title, $column, ...$columns)
    {
        if (is_array($column)) {
            $columns = $column;
        }
        else {
            array_unshift($columns, $column);
        }

        $this->complexCols[$name] = [
            'name'      => $name,
            'title'     => title_case($title),
            'columns'   => $columns
        ];

        return $this;
    }

    /**
     * 将列设置为隐藏列
     * @param string ...$columns
     * @return $this
     */
    public function hidden(...$columns)
    {
        if ( is_array($columns[0]) ) {
            $columns = $columns[0];
        }

        foreach ($columns as $col) {
            $this->columns->map(function (Column $column, $i) use ($col) {
                if ((is_numeric($col) && $i == $col) || $column->getName() === $col) {
                    $column->hidden();
                }
            });
        }

        return $this;
    }

    /**
     * 设置表格固定列
     *
     * @param string|int $column
     * @return $this
     */
    public function fixation($column)
    {
        $this->fixedColumn = $column;

        return $this;
    }

    /**
     * 设置表格数据源
     *
     * @param array|Arrayable|Eloquent $data
     * @return $this
     */
    public function setData($data)
    {
        //TODO 支持Array,Model
        $this->data = $data;

        return $this;
    }

    /**
     * 设置每页显示数据项
     * @param int $perPage
     * @return $this
     */
    public function setPerPage($perPage)
    {
        $this->perPage = $perPage;

        return $this;
    }

    /**
     * 设置表格数据分页
     *
     * @param int $total
     * @param int $perPage
     * @return $this;
     */
    public function pagination($total, $perPage = 20)
    {
        $this->total = $total;
        $this->setPerPage($perPage);

        return $this;
    }

    /**
     * 关闭分页
     * @return $this
     */
    public function disablePagination()
    {
        $this->usePagination = false;

        return $this;
    }

    /**
     * 将列设置为可排序的列
     * @param string ...$columns
     * @return $this
     */
    public function sort(...$columns)
    {
        if ( is_array($columns[0]) ) {
            $columns = $columns[0];
        }

        foreach ($columns as $col) {
            $this->columns->map(function (Column $column, $i) use ($col) {
                if ((is_numeric($col) && $i == $col) || $column->getName() === $col) {
                    $column->sortable();
                }
            });
        }

        return $this;
    }

    /**
     * 设置过滤器
     * @param string $name
     * @param string $label
     * @return Filter
     */
    public function filter($name, $label)
    {
        if (!isset($this->filters)) {
            $this->filters = new Collection();
        }

        $filter = new Filter($name, $label);
        $filter->weight(count($this->filters));

        $this->filters->push($filter);

        return $filter;
    }

    /**
     * 获取并初始化工具条
     *
     * @param string $name
     * @param Closure|null $callback
     * @return Toolbar
     */
    public function toolbar($name = 'left', Closure $callback = null)
    {
        if (!isset($this->toolbar[$name])) {
            $this->toolbar[$name] = new Toolbar($name);
        }

        if ($callback) {
            call_user_func($callback, $this->toolbar[$name]);
        }

        return $this->toolbar[$name];
    }

    /**
     * @return array
     */
    protected function buildToolbar()
    {
        $toolbars = [];
        /**
         * @var Toolbar $toolbar
         */
        foreach ($this->toolbar as $name => $toolbar) {
            $toolbar->setPrefix($this->formatId());

            //TODO 将筛选条件缓存至Toolbar

            $toolbars[$name] = $toolbar->render($this, $this->columns);
        }

        return $toolbars;
    }

    /**
     * 设置没有记录时显示的内容
     * @param string $str
     * @return $this
     */
    public function setEmptyString($str)
    {
        $this->emptyString = $str;

        return $this;
    }

    /**
     * @return int
     */
    public function total()
    {
        return $this->total;
    }

    /**
     * Set the grid options.
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
            array_set($this->config, (string)$name, $value);
        }

        return $this;
    }

    protected function buildConfig()
    {
        $config = $this->config;

        foreach ($config as $index => $cfg) {
            if (is_callable($cfg)) {
                //TODO 将函数返回值转换为javascript function，提取为Config对象
            }
        }

        return $config;
    }

    /**
     * Get the view variables of this element.
     *
     * @return array
     */
    protected function variables()
    {
        if (isset($this->filters)) {
            $this->filters->each(function ($filter) {
                $filter->field()->setPrefix([$this->formatId()]);
            });
        }

        return array_merge(parent::variables(), [
            'title'         => $this->title,
            'filters'       => $this->filters,
            'toolbar'       => $this->buildToolbar(),
            'headers'       => $this->header,
            'rows'          => $this->rows,
            'pager'         => $this->pager,
            'emptyString'   => $this->emptyString,
            'config'        => $this->buildConfig(),
            'visColumns'    => $this->visibleCols,
            'fixedColumn'   => $this->fixedColumn,
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
        return 'grid';
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

        if ($this->fixedColumn) {
            $this->helper->getResource()
                ->requireResource('grid.fixed');
        }

        // 开始编译
        $this->build();

        return $this->toHtml();
    }

    protected function build()
    {
        if ($this->builded) {
            return;
        }

        //TODO 编译过滤器

        //初始化默认列
        $this->buildDefColumns();

        //TODO 初始化选项

        // 编译列信息
        $this->buildColumns();

        // 分页器
        $pager = null;

        // 准备数据项
        $data = null;
        if ($this->data instanceof LengthAwarePaginator) {
            $pager = $this->data;
            $data = $pager->getCollection();
            $this->total = $pager->total();
            $this->setPerPage($pager->perPage());
        }
        else if ($this->data instanceof Eloquent || $this->data instanceof \Illuminate\Database\Eloquent\Builder) {
            if ($this->usePagination) {
                $pager = $this->data->paginate($this->perPage);

                $data = $pager->items();
                $this->total = $pager->total();
            }
            else {
                $data = $this->data->get();
                $this->total = $data ? count($data) : 0;
            }
        }
        else {
            $data = $this->data instanceof Collection ? $this->data : collect($this->data);

            if (is_null($this->total)) {
                $this->total = count($data);
            }
        }

        // 设置分页器
        if ($this->usePagination && !empty($data)) {
            if (empty($pager)) {
                $pager = new LengthAwarePaginator($data, $this->total, $this->perPage, Paginator::resolveCurrentPage(), [
                    'path' => Paginator::resolveCurrentPath(),
                ]);
            }

            $this->pager = $pager;
        }

        // 编译行列数据
        $this->buildRows($data);


        $this->builded = true;
    }

    protected function buildDefColumns()
    {
        $toolbar = $this->toolbar();
        if ($toolbar->needSelectRow()) {
            $rowSelector = new RowSelector($this->getId().'_ids');
            $this->columns->push($rowSelector);
            $nullable = new Nullable($this->getId().'_null');
            $this->columns->push($nullable);
        }
    }

    protected function buildColumns()
    {
        /**
         * @var Column $column
         */
        $columns = $this->columns->sortBy(function (Column $column) {
            return $column->weight();
        });

        // 收集合并列名
        $complexColumns = array_column($this->complexCols, 'columns', 'name');
        $complexColumnNames = array_collapse($complexColumns);
        $complexColumnFirsts = array_map(function ($columns) {return reset($columns); }, $complexColumns);

        $_columns = [];
        $hidden = [];
        $header = [];
        foreach ($columns as $column) {
            $name = $column->getName();

            // 优先将不可见列压入列表
            if ( ! $column->isVisible() ) {
                $hidden[$name] = $column;
                continue;
            }

            // 将未合并列及已定义列名的合并列按权重加到表头
            if (in_array($name, $complexColumnFirsts)) {
                collect($complexColumnFirsts)->filter(function ($val) use($name) {
                    return $val == $name;
                })->each(function ($name, $key) use($columns, &$header, &$_columns) {
                    $complexColumns = $columns->filter(function (Column $column) use($key) {
                        return in_array($column->getName(), $this->complexCols[$key]['columns']) && $column->isVisible();
                    });

                    // 合并列
                    $this->complexCols[$key]['columns'] = $complexColumns;

                    $header[$key] = $this->complexCols[$key];
                    $complexColumns->each(function(Column $column) use(&$_columns) {
                        $_columns[] = $column;
                    });
                });

                continue;
            }
            if (in_array($name, $complexColumnNames)) {
                continue;
            }

            $header[$name] = $column;
            $_columns[] = $column;
        }

        foreach (array_keys($this->complexCols) as $key) {
            if (!isset($header[$key])) {
                // 将未定义列名的合并列追加到表头
                $complexColumns = $columns->filter(function (Column $column) use($key) {
                    return in_array($column->getName(), $this->complexCols[$key]['columns']) && $column->isVisible();
                });

                // 合并列
                $this->complexCols[$key]['columns'] = $complexColumns;

                $header[$key] = $this->complexCols[$key];
                $complexColumns->each(function(Column $column) use($_columns) {
                    $_columns[] = $column;
                });
            }
        }

        // 可视列名
        $visibleColumns = [];
        $fixedColumn = $this->fixedColumn;
        foreach($header as $colName => $column) {
            if(is_array($column) && isset($column['columns'])) {
                foreach($column['columns'] as $_column) {
                    $visibleColumns[] = $_column;

                    if (is_string($fixedColumn) && $_column->getName() === $fixedColumn) {
                        $fixedColumn = count($visibleColumns);
                    }
                }
            }
            else {
                $visibleColumns[] = $column;
            }

            if (is_string($fixedColumn) && $colName === $fixedColumn) {
                $fixedColumn = count($visibleColumns);
            }
        }

        // 追加隐藏列
        if (count($hidden) > 0) {
            foreach ($hidden as $name => $column) {
                // 避免错误覆盖
                if (isset($header[$name])) {
                    $header[$name . count($header)] = $column;
                }
                else {
                    $header[$name] = $column;
                }

                $_columns[] = $column;
            }
        }

        $header = [$header];
        if (count($this->complexCols) > 0) {
            $header[] = array_collapse(array_column($this->complexCols, 'columns'));
        }

        $this->header = $header;
        $this->columns = new Collection($_columns);
        $this->visibleCols = $visibleColumns;
        $this->fixedColumn = $fixedColumn;
    }

    protected function buildRows($data)
    {
        $data = $data instanceof Collection ? $data : Collection::make($data);

        $this->rows = $data->map(function ($data) {
            $row = new Row($this->columns);

            $row->setMajorKeyName($this->majorKey);
            $row->fill($data, $this);

            return $row;
        });

        // 解决特殊情况下生成空行
        if ($this->emptyString === '###NEW###') {
            $row = new Row($this->columns);

            $row->setMajorKeyName($this->majorKey);
            $row->fill([$this->majorKey => '__new_'], $this);

            $this->rows->push($row);
        }

        //TODO 允许设置每行执行一个回调函数
    }

}