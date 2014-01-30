<?php

namespace Dat0r\CodeGen\Parser\ModuleSchema;

use Dat0r\Common\Object;
use Dat0r\CodeGen\Parser\IParser;
use Dat0r\Common\Error\InvalidTypeException;
use DOMXPath;
use DOMElement;

abstract class XpathParser extends Object implements IParser
{
    abstract protected function parseXpath(DOMXPath $xpath, DOMElement $context);

    public function parse($xpath, array $options = array())
    {
        if (!($xpath instanceof DOMXPath)) {
            throw new InvalidTypeException(
                "Invalid source type: " . @get_class($xpath) . " given to " . __METHOD__ .
                ', expected instance of DOMXPath.'
            );
        }
        if (!isset($options['context'])) {
            throw new RuntimeException("Missing option 'context' given to " . __METHOD__);
        }
        if (!($options['context'] instanceof DOMElement)) {
            throw new InvalidTypeException(
                "Invalid: " . @get_class($xpath) . " given to " . __METHOD__ .
                ', expected instance of DOMElement.'
            );
        }
        return $this->parseXPath($xpath, $options['context']);
    }

    public static function literalize($value)
    {
        if (!is_string($value)) {
            return $value;
        }

        $value = trim($value);
        if ($value == '') {
            return null;
        }

        $lc_value = strtolower($value);
        if ($lc_value === 'on' || $lc_value === 'yes' || $lc_value === 'true') {
            return true;
        } elseif ($lc_value === 'off' || $lc_value === 'no' || $lc_value === 'false') {
            return false;
        }

        if (preg_match('/^[0-9]+$/', $value)) {
            return (int)$value;
        }

        return $value;
    }

    protected function parseDescription(DOMXPath $xpath, DOMElement $element)
    {
        return array_map(
            function ($line) {
                return trim($line);
            },
            preg_split('/$\R?^/m', trim($element->nodeValue))
        );
    }

    protected function parseOptions(DOMXPath $xpath, DOMElement $element)
    {
        $parser = OptionDefinitionXpathParser::create();

        return $parser->parseXpath($xpath, $element);
    }
}