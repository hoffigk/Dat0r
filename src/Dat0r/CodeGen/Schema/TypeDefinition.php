<?php

namespace Dat0r\CodeGen\Schema;

use Dat0r\Common\Object;

class TypeDefinition extends Object
{
    protected $name;

    protected $implementor;

    protected $document_implementor;

    protected $description;

    protected $options;

    protected $attributes;

    public function __construct(array $state = array())
    {
        $this->attributes = new AttributeDefinitionList();
        $this->options = new OptionDefinitionList();

        parent::__construct($state);
    }

    public function getName()
    {
        return $this->name;
    }

    public function getImplementor()
    {
        return $this->implementor;
    }

    public function getDocumentImplementor()
    {
        return $this->document_implementor;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function getOptions()
    {
        return $this->options;
    }

    public function getAttributes()
    {
        return $this->attributes;
    }
}
