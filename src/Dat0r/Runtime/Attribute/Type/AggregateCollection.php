<?php

namespace Dat0r\Runtime\Attribute\Type;

use Dat0r\Runtime\Attribute\Attribute;
use Dat0r\Runtime\Document\DocumentList;
use Dat0r\Runtime\Attribute\Validator\Rule\RuleList;
use Dat0r\Runtime\Attribute\Validator\Rule\Type\AggregateRule;
use Dat0r\Common\Error\RuntimeException;
use Dat0r\Common\Error\InvalidTypeException;

/**
 * AggregateCollection allows to nest multiple types below a defined attribute_name.
 * Pass in the 'OPTION_MODULES' option to define the types you would like to nest.
 * The corresponding value-structure is organized as a collection of documents.
 *
 * Supported options: OPTION_MODULES
 */
class AggregateCollection extends Attribute
{
    /**
     * Option that holds an array of supported aggregate-type names.
     */
    const OPTION_MODULES = 'types';

    /**
     * An array holding the aggregate-type instances supported by a specific aggregate-attribute instance.
     *
     * @var array
     */
    protected $aggregated_types = null;

    /**
     * Constructs a new aggregate attribute instance.
     *
     * @param string $name
     * @param array $options
     */
    public function __construct($name, array $options = array())
    {
        parent::__construct($name, $options);

        foreach ($this->getAggregates() as $aggregate_type) {
            foreach ($aggregate_type->getAttributes() as $attribute) {
                $attribute->setParent($this);
            }
        }
    }

    /**
     * Returns an aggregate-attribute instance's default value.
     *
     * @return mixed
     */
    public function getDefaultValue()
    {
        return DocumentList::create();
    }

    /**
     * Returns the aggregate-types as an array.
     *
     * @return array
     */
    public function getAggregates()
    {
        if (!$this->aggregated_types) {
            $this->aggregated_types = array();
            foreach ($this->getOption(self::OPTION_MODULES) as $aggregate_type) {
                $this->aggregated_types[] = new $aggregate_type();
            }
        }

        return $this->aggregated_types;
    }

    public function getAggregateByPrefix($prefix)
    {
        foreach ($this->getAggregates() as $type) {
            if ($type->getPrefix() === $prefix) {
                return $type;
            }
        }

        return null;
    }

    public function getAggregateByName($name)
    {
        foreach ($this->getAggregates() as $type) {
            if ($type->getName() === $name) {
                return $type;
            }
        }

        return null;
    }

    /**
     * Return a list of rules used to validate a specific attribute instance's value.
     *
     * @return RuleList
     */
    protected function buildValidationRules()
    {
        $rules = new RuleList();
        $rules->push(
            new AggregateRule(
                'valid-data',
                array('aggregate_types' => $this->getAggregates())
            )
        );

        return $rules;
    }
}
