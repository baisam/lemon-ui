<?php
/**
 * Row.php.
 * User: feng
 * Date: 2018/6/7
 */

namespace BaiSam\UI\Grid;

use ArrayAccess;
use ArrayIterator;
use Countable;
use IteratorAggregate;
use Traversable;
use OutOfBoundsException;
use Illuminate\Support\Collection;
use Illuminate\Contracts\Support\Arrayable;

class Row implements ArrayAccess, Arrayable, Countable, IteratorAggregate
{
    /**
     * @var string
     */
    protected $majorKey = 'id';

    /**
     * @var Collection
     */
    protected $columns;

    /**
     * @var Collection
     */
    protected $cells;

    /**
     * @var mixed
     */
    protected $data = null;

    /**
     * Row constructor.
     *
     * @param Collection $columns
     */
    public function __construct(Collection $columns)
    {
        $this->columns = $columns;
    }

    /**
     * 获取指定的数据
     * @param string $name
     * @return mixed
     */
    public function __get($name)
    {
        return data_get($this->data, $name);
    }

    /**
     * 设置主键名
     * @param string $key
     * @return $this
     */
    public function setMajorKeyName($key)
    {
        $this->majorKey = $key;

        return $this;
    }

    /**
     * 获取主键
     * @return mixed
     */
    public function getMajorKey()
    {
        return $this->__get($this->majorKey);
    }

    /**
     * 获取列信息
     * @return Collection
     */
    public function columns()
    {
        return $this->columns;
    }

    /**
     * 获取单元格
     * @return Collection
     */
    public function cells()
    {
        return $this->cells;
    }

    /**
     * 填充数据
     * @param mixed $data
     * @param Builder $builder
     * @return $this
     */
    public function fill($data, Builder $builder)
    {
        $this->data = $data;

        // 将数据填充到单元
        $this->cells = collect($this->columns)->map(function ($column) use ($builder) {
            $cell = new Cell($column);

            $cell->build($this, $builder);

            return $cell;
        });

        return $this;
    }

    /**
     * 获取数据源
     * @return mixed
     */
    public function rawData()
    {
        return $this->data;
    }

    /**
     * Get the instance as an array.
     *
     * @return array
     */
    public function toArray()
    {
        return $this->cells->toArray();
    }

    /**
     * Retrieve an external iterator
     *
     * @link https://php.net/manual/en/iteratoraggregate.getiterator.php
     * @return Traversable An instance of an object implementing <b>Iterator</b> or
     * <b>Traversable</b>
     * @since 5.0.0
     */
    public function getIterator()
    {
        return new ArrayIterator($this->cells->all());
    }

    /**
     * Whether a offset exists
     *
     * @link https://php.net/manual/en/arrayaccess.offsetexists.php
     * @param mixed $offset <p>
     * An offset to check for.
     * </p>
     * @return boolean true on success or false on failure.
     * </p>
     * <p>
     * The return value will be casted to boolean if non-boolean was returned.
     * @since 5.0.0
     */
    public function offsetExists($offset)
    {
        if (is_numeric($offset)) {
            return $this->cells->offsetExists($offset);
        }
        else {
            $cell = $this->cells->first(function ($column) use($offset) {
                return $column->getName() === $offset;
            });

            return !is_null($cell);
        }
    }

    /**
     * Offset to retrieve
     *
     * @link https://php.net/manual/en/arrayaccess.offsetget.php
     * @param mixed $offset <p>
     * The offset to retrieve.
     * </p>
     * @return mixed Can return all value types.
     * @since 5.0.0
     */
    public function offsetGet($offset)
    {
        if (is_numeric($offset)) {
            return $this->cells->offsetGet($offset);
        }
        else {
            return $this->cells->first(function ($column) use($offset) {
                return $column->getName() === $offset;
            });
        }
    }

    /**
     * Offset to set
     *
     * @link https://php.net/manual/en/arrayaccess.offsetset.php
     * @param mixed $offset <p>
     * The offset to assign the value to.
     * </p>
     * @param mixed $value <p>
     * The value to set.
     * </p>
     * @return void
     * @since 5.0.0
     */
    public function offsetSet($offset, $value)
    {
        throw new OutOfBoundsException();
    }

    /**
     * Offset to unset
     *
     * @link https://php.net/manual/en/arrayaccess.offsetunset.php
     * @param mixed $offset <p>
     * The offset to unset.
     * </p>
     * @return void
     * @since 5.0.0
     */
    public function offsetUnset($offset)
    {
        throw new OutOfBoundsException();
    }

    /**
     * Count elements of an object
     *
     * @link https://php.net/manual/en/countable.count.php
     * @return int The custom count as an integer.
     * </p>
     * <p>
     * The return value is cast to an integer.
     * @since 5.1.0
     */
    public function count(){
        return $this->cells->count();
    }
}