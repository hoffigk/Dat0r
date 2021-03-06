<?php

namespace Dat0r\Core\Validator;

/**
 * Default implementation for validators that validate uuids (v4).
 *
 * @copyright BerlinOnline Stadtportal GmbH & Co. KG
 * @author Thorsten Schmitt-Rink <tschmittrink@gmail.com>
 */
class UuidValidator extends TextValidator
{
    /**
     * Validates a given value thereby considering the state of the field
     * that a specific validator instance is related to.
     *
     * @param mixed $value
     *
     * @return boolean
     */
    public function validate($value)
    {
        $is_valid = parent::validate($value);

        if (true === $is_valid) {
            $is_valid = (1 === preg_match(
                '/^\{?[0-9a-f]{8}\-?[0-9a-f]{4}\-?[0-9a-f]{4}\-?'.
                '[0-9a-f]{4}\-?[0-9a-f]{12}\}?$/i',
                $value
            ));
        }

        return $is_valid;
    }
}
