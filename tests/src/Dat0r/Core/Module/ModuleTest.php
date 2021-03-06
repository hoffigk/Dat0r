<?php

namespace Dat0r\Tests\Core\Module;

use Dat0r\Tests\Core\BaseTest;
use Dat0r\Core\Module\IModule;
use Dat0r\Core\Field;

class ModuleTest extends BaseTest
{
    public function testCreateRootModule()
    {
        $module = RootModule::getInstance();

        $this->assertEquals('Article', $module->getName());
        $this->assertEquals(11, $module->getFields()->getSize());
    }

    public function testCreateAggegateModule()
    {
        $module = AggregateModule::getInstance();

        $this->assertEquals(2, $module->getFields()->getSize());
        $this->assertEquals('Paragraph', $module->getName());
    }

    /**
     * @dataProvider provideModuleInstances
     */
    public function testForConsistentFrozenState(IModule $module)
    {
        $fields = $module->getFields();

        $this->assertTrue($module->isFrozen());
        $this->assertTrue($fields->isFrozen());

        foreach ($fields as $field) {
            $this->assertTrue($field->isFrozen());
        }
    }

    /**
     * @dataProvider provideModuleInstances
     */
    public function testGetFieldMethod(IModule $module)
    {
        $this->assertInstanceOf('Dat0r\\Core\\Field\\TextField', $module->getField('headline'));
        $this->assertInstanceOf('Dat0r\\Core\\Field\\IntegerField', $module->getField('clickCount'));
    }

    /**
     * @dataProvider provideModuleInstances
     */
    public function testGetFieldsMethodPlain(IModule $module)
    {
        $fields = $module->getFields();

        $this->assertInstanceOf('Dat0r\\Core\\Field\\FieldCollection', $fields);
        $this->assertEquals(11, $fields->getSize());

        $this->assertInstanceOf('Dat0r\\Core\\Field\\TextField', $fields->get('headline'));
        $this->assertInstanceOf('Dat0r\\Core\\Field\\TextField', $fields->get('content'));
        $this->assertInstanceOf('Dat0r\\Core\\Field\\IntegerField', $fields->get('clickCount'));
        $this->assertInstanceOf('Dat0r\\Core\\Field\\TextField', $fields->get('author'));
        $this->assertInstanceOf('Dat0r\\Core\\Field\\TextField', $fields->get('email'));
        $this->assertInstanceOf('Dat0r\\Core\\Field\\TextCollectionField', $fields->get('keywords'));
        $this->assertInstanceOf('Dat0r\\Core\\Field\\BooleanField', $fields->get('enabled'));
        $this->assertInstanceOf('Dat0r\\Core\\Field\\IntegerCollectionField', $fields->get('images'));
        $this->assertInstanceOf('Dat0r\\Core\\Field\\KeyValueField', $fields->get('meta'));
        $this->assertInstanceOf('Dat0r\\Core\\Field\\AggregateField', $fields->get('paragraph'));
        $this->assertInstanceOf('Dat0r\\Core\\Field\\ReferenceField', $fields->get('references'));
    }

    /**
     * @dataProvider provideModuleInstances
     */
    public function testGetFieldsMethodFiltered(IModule $module)
    {
        $fields = $module->getFields(array('headline', 'clickCount'));

        $this->assertInstanceOf('Dat0r\\Core\\Field\\FieldCollection', $fields);
        $this->assertEquals(2, $fields->getSize());

        $this->assertInstanceOf('Dat0r\\Core\\Field\\TextField', $fields->get('headline'));
        $this->assertInstanceOf('Dat0r\\Core\\Field\\IntegerField', $fields->get('clickCount'));
    }

    /**
     * @dataProvider provideModuleInstances
     */
    public function testCreateDocumentMethod(IModule $module)
    {
        $document = $module->createDocument();
        $this->assertInstanceOf('Dat0r\\Core\\Document\\Document', $document);
    }

    /**
     * @dataProvider provideModuleInstances
     * @expectedException Dat0r\Core\Module\InvalidFieldException
     */
    public function testInvalidFieldException(IModule $module)
    {
        $module->getField('foobar-field-does-not-exist'); // @codeCoverageIgnoreStart
    } // @codeCoverageIgnoreEnd

    /**
     * @expectedException Dat0r\Core\Error\InvalidImplementorException
     */
    public function testInvalidDocumentImplementorException()
    {
        $module = InvalidRootModule::getInstance();
        $module->createDocument(); // @codeCoverageIgnoreStart
    } // @codeCoverageIgnoreEnd

    /**
     * @codeCoverageIgnore
     */
    public static function provideModuleInstances()
    {
        return array(array(RootModule::getInstance()));
    }
}
