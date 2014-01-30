<?php

namespace Dat0r\CodeGen\ClassBuilder\Reference;

use Dat0r\CodeGen\ClassBuilder\Common\DocumentClassBuilder as CommonDocumentClassBuilder;

class DocumentClassBuilder extends CommonDocumentClassBuilder
{
    protected function getPackage()
    {
        return $this->module_schema->getPackage() . '\\Reference';
    }
}