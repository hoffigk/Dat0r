<?php

namespace Dat0r\CodeGen\Schema;

use Dat0r\Generic;

class OptionDefinitionList extends Generic\ObjectList
{
    public static function create(array $items = array())
    {
        $item_implementor = sprintf('\\%s\\OptionDefinition', __NAMESPACE__);

        return parent::create(array(
            self::ITEM_IMPLEMENTOR => $item_implementor,
            self::ITEMS => $items
        ));
    }

    public function toArray()
    {
        $data = array();

        foreach ($this->option_definitions as $option)
        {
            $name = $option->getName();
            $value = $option->getValue();
            $next_value = $value;

            if ($value instanceof OptionDefinitionList)
            {
                $next_value = $value->toArray();
            }

            $next_value = $next_value ? $next_value : $option->getDefault();

            if ($name)
            {
                $data[$name] = $next_value;
            }
            else
            {
                $data[] = $next_value;
            }
        }

        return $data;
    }
}
