<?php
/**
 * ManagesResources.php
 * BaiSam admin
 *
 * Created by realeff on 2018/05/13.
 * Copyright ©2018 Jiangxi baisam information technology co., LTD. All rights reserved.
 */


namespace BaiSam\UI;

use InvalidArgumentException;

trait ManagesResources
{
    /**
     * The stack of in-progress style sections.
     *
     * @var array
     */
    protected $styleStack = [];

    /**
     * The stack of in-progress script sections.
     *
     * @var array
     */
    protected $scriptStack = [];

    /**
     * Start injecting into a style handle.
     *
     * @param  string  $handle
     * @param  string  $tag
     * @return void
     */
    public function startStyle($handle, $tag = null)
    {
        if (ob_start()) {
            $this->styleStack[] = [$handle, $tag];
        }

        // Require required resource.
        $this->requireResource($handle);
    }

    /**
     * Stop injecting into a style handle.
     *
     * @return string
     * @throws \InvalidArgumentException
     */
    public function stopStyle()
    {
        if (empty($this->styleStack)) {
            throw new InvalidArgumentException('Cannot end a style stack without first starting one.');
        }

        return tap(array_pop($this->styleStack), function ($last) {
            $this->inlineStyle($last[0], ob_get_clean(), $last[1]);
        });
    }

    /**
     * Start injecting into a script handle.
     *
     * @param  string  $handle
     * @param  string  $tag
     * @param  boolean  $prepend
     * @return void
     */
    public function startScript($handle, $tag = null, $prepend = false)
    {
        if (ob_start()) {
            $this->scriptStack[] = [$handle, $tag, $prepend];
        }

        // Require required resource.
        $this->requireResource($handle);
    }

    /**
     * Stop injecting into a script handle.
     *
     * @return string
     * @throws InvalidArgumentException
     */
    public function stopScript()
    {
        if (empty($this->scriptStack)) {
            throw new InvalidArgumentException('Cannot end a script stack without first starting one.');
        }

        return tap(array_pop($this->scriptStack), function ($last) {
            $this->inlineScript($last[0], ob_get_clean(), $last[1], $last[2]);
        });
    }

    /**
     * Flush stack.
     */
    protected function flushStack()
    {
        $this->styleStack = [];
        $this->scriptStack = [];
    }
}