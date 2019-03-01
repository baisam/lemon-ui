<?php
/**
 * Toolbar.php
 * BaiSam admin
 *
 * Created by realeff on 2018/10/31.
 * Copyright ©2018 Jiangxi baisam information technology co., LTD. All rights reserved.
 */


namespace BaiSam\UI\Grid;


use Closure;
use ArrayAccess;
use ArrayIterator;
use Countable;
use IteratorAggregate;
use Traversable;
use OutOfBoundsException;
use BaiSam\UI\Grid\Actions\ManyActions;
use Illuminate\Contracts\Support\Arrayable;

/**
 * Class Toolbar
 *
 * @method \BaiSam\UI\Grid\Actions\FlashAction      flash(...$keys)
 *
 * @package BaiSam\UI\Grid
 */
class Toolbar extends ManyActions implements ArrayAccess, Arrayable, Countable, IteratorAggregate
{
    /**
     * @var string
     */
    protected $type = 'toolbar';

    /**
     * Toolbar constructor.
     *
     * @param string $id
     * @param callable|null $callback
     */
    public function __construct(string $id, callable $callback = null)
    {
        $this->supportActionNames[] = 'flash';

        parent::__construct($id, $callback);
    }

    /**
     * @param Closure $callback
     * @return $this
     */
    public function actions(Closure $callback)
    {
        call_user_func($callback, $this);

        return $this;
    }

    /**
     * @return $this
     */
    public function separator()
    {
        $this->items[] = '##_SEPARATOR_##';

        return $this;
    }

    /**
     * @param string $name
     * @param Closure|null $callback
     * @return ManyActions
     */
    public function many($name, Closure $callback = null)
    {
        $actions = new ManyActions($name, $callback);

        $this->push($actions);

        return $actions;
    }


    /**
     * Get the instance as an array.
     *
     * @return array
     */
    public function toArray()
    {
        return $this->buildItems();
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
        return new ArrayIterator($this->buildItems());
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
        return isset($this->items[$offset]);
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
        $item = $this->items[$offset];
        if ($item instanceof Action) {
            return $item->render(...$this->params);
        }

        return $item;
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
    public function count()
    {
        return count($this->items);
    }
}