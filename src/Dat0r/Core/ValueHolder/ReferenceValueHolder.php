<?php

namespace Dat0r\Core\ValueHolder;

use Dat0r\Core\Error;
use Dat0r\Core\Field\IField;
use Dat0r\Core\Field\ReferenceField;

/**
 * Default IValueHolder implementation used for reference (id) value containment.
 *
 * @copyright BerlinOnline Stadtportal GmbH & Co. KG
 * @author Thorsten Schmitt-Rink <tschmittrink@gmail.com>
 */
class ReferenceValueHolder extends ValueHolder
{
    /**
     * Tells whether a spefic IValueHolder instance's value is considered greater than
     * the value of an other given IValueHolder.
     *
     * @param IValueHolder $other
     *
     * @return boolean
     */
    public function isGreaterThan(IValueHolder $other)
    {
        $lefthand_value = $this->getValue();
        $righthand_value = $other->getValue();

        return (!empty($lefthand_value) && empty($righthand_value));
    }

    /**
     * Tells whether a spefic IValueHolder instance's value is considered less than
     * the value of an other given IValueHolder.
     *
     * @param IValueHolder $other
     *
     * @return boolean
     */
    public function isLessThan(IValueHolder $other)
    {
        $lefthand_value = $this->getValue();
        $righthand_value = $other->getValue();

        return (empty($lefthand_value) && !empty($righthand_value));
    }

    /**
     * Tells whether a spefic IValueHolder instance's value is considered equal to
     * the value of an other given IValueHolder.
     *
     * @param IValueHolder $other
     *
     * @return boolean
     */
    public function isEqualTo(IValueHolder $other)
    {
        return $this->getValue() === $other->getValue();
    }

    /**
     * Sets the value holder's (int) value.
     *
     * @param string $value
     */
    public function setValue($value)
    {
        parent::setValue($value);
    }

    /**
     * Contructs a new ReferenceValueHolder instance from a given value.
     *
     * @param IField $field
     * @param mixed $value
     */
    protected function __construct(IField $field, $value = null)
    {
        if (!($field instanceof ReferenceField)) {
            throw new Error\BadValueException(
                "Only instances of ReferenceField my be associated with ReferenceValueHolder."
            );
        }

        parent::__construct($field, $value);
    }
}
