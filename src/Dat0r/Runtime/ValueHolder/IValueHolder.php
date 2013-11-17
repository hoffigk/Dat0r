<?php

namespace Dat0r\Runtime\ValueHolder;

use Dat0r\Runtime\Field\IField;

/**
 * @todo Explain what valid holders are and what they are supposed to do.
 */
interface IValueHolder
{
    /**
     * Creates a new IValueHolder instance for the given value.
     *
     * @param IField $field
     *
     * @return IValueHolder
     */
    public static function create(IField $field);

    /**
     * Returns the value holder's aggregated value.
     *
     * @return mixed
     */
    public function getValue();

    /**
     * Sets the value holder's value.
     *
     * @param string $value
     *
     * @return IResult
     */
    public function setValue($value);

    /**
     * Tells if a value holder has a value.
     *
     * @return boolean
     */
    public function hasValue();

    /**
     * Tells if a value holder has no value.
     *
     * @return boolean
     */
    public function isValueNull();

    /**
     * Tells whether a given IValueHolder is considered being less than a given other IValueHolder.
     *
     * @param mixed $other
     *
     * @return boolean
     */
    public function isValueGreaterThan($righthand_value);

    /**
     * Tells whether a given IValueHolder is considered being less than a given other IValueHolder.
     *
     * @param mixed $other
     *
     * @return boolean
     */
    public function isValueLessThan($righthand_value);

    /**
     * Tells whether a given IValueHolder is considered being equal to a given other IValueHolder.
     *
     * @param mixed $other
     *
     * @return boolean
     */
    public function isValueEqualTo($righthand_value);
}