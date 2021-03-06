<?php

namespace Dat0r\CodeGen\Parser;

use Dat0r\CodeGen\Schema;

class AggregateDefinitionXpathParser extends ModuleDefinitionXpathParser
{
    public function parseXpath(\DOMXPath $xpath, array $options = array())
    {
        $aggregate_set = Schema\ModuleDefinitionSet::create();

        $node_list = $xpath->query('//aggregate_definition', $options['context']);

        foreach ($node_list as $element) {
            $aggregate_set->add(
                Schema\AggregateDefinition::create(
                    $this->parseModuleDefinition($xpath, $element)
                )
            );
        }

        return $aggregate_set;
    }
}
