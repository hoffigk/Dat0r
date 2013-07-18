<?php

namespace Dat0r\CodeGen\Schema;

use Dat0r;

class FieldDefinition extends Dat0r\Object
{
    protected $name;

    protected $description;

    protected $type;

    protected $options;

    public function getName()
    {
        return $this->name;
    }

    public function getType()
    {
        return $this->type;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function getOptions()
    {
        return $this->options;
    }
}
