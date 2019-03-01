<?php
/**
 * Textarea.php
 * User: realeff
 * Date: 17-11-14
 */

namespace BaiSam\UI\Form\Field;


use BaiSam\UI\Form\Field;

class Textarea extends Field
{
    /**
     * Default rows of textarea.
     *
     * @var int
     */
    protected $rows = 5;

    protected $maxlength = 255;

    /**
     * Set rows of textarea.
     *
     * @param int $rows
     *
     * @return $this
     */
    public function rows($rows = 5)
    {
        $this->rows = $rows;

        return $this;
    }

    public function maxLength($maxLength)
    {
        $this->maxlength = $maxLength;

        return $this;
    }

    protected function formatAttributes()
    {
        if ($this->maxlength > 0) {
            $this->attribute('maxlength', $this->maxlength);
        }

        return parent::formatAttributes();
    }

    protected function variables()
    {
        return array_merge(parent::variables(), [
            'maxlength'     => $this->maxlength,
            'rows'          => $this->rows
        ]);
    }
}