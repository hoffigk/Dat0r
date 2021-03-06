<?php

namespace Dat0r\Core\ValueHolder;

use Dat0r\Core\Error;
use Dat0r\Core\Field\IField;
use Dat0r\Core\Field\UuidField;

/**
 * Default IValueHolder implementation used for uuid value containment.
 *
 * @copyright BerlinOnline Stadtportal GmbH & Co. KG
 * @author Thorsten Schmitt-Rink <tschmittrink@gmail.com>
 */
class UuidValueHolder extends TextValueHolder
{
    /**
     * Contructs a new TextValueHolder instance from a given value.
     *
     * @param IField $field
     * @param mixed $value
     */
    protected function __construct(IField $field, $value = null)
    {
        if (!($field instanceof UuidField)) {
            throw new Error\BadValueException(
                "Only instances of UuidField my be associated with UuidValueHolder."
            );
        }

        parent::__construct($field, $value);
    }
}
