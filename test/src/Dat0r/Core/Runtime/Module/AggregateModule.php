<?php

namespace Dat0r\Tests\Core\Runtime\Module;

use Dat0r\Core\Runtime\Module;

class AggregateModule extends Module\AggregateModule
{
    protected function getDocumentImplementor()
    {
        return 'Dat0r\Tests\Core\Runtime\Document\DocumentTestProxy';
    }
}