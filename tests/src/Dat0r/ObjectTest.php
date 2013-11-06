<?php

namespace Dat0r\Tests;

use Dat0r\Tests\Fixtures;

// @todo:
// - test IObject as nested value (recursively)
// - test empty objects
class ObjectTest extends TestCase
{
    public function testCreate()
    {
        $object_data = $this->getRandomScalarValues();
        $test_object = Fixtures\TestObject::create($object_data);

        $this->assertInstanceOf('\\Dat0r\\Type\\IObject', $test_object);
        $this->assertInstanceOf('\\Dat0r\\Tests\\Fixtures\\TestObject', $test_object);
        $this->assertEquals($object_data['property_one'], $test_object->getPropertyOne());
        $this->assertEquals($object_data['property_two'], $test_object->getPropertyTwo());
        $this->assertEquals($object_data['property_three'], $test_object->getPropertyThree());
    }

    public function testToArray()
    {
        $object_data = $this->getRandomScalarValues();
        $test_object = Fixtures\TestObject::create($object_data);

        $this->assertEquals($object_data, $test_object->toArray());
    }

    protected function getRandomScalarValues()
    {
        return array(
            'property_one' => self::$faker->word(23),
            'property_two' => self::$faker->randomNumber(0, 500),
            'property_three' => self::$faker->boolean()
        );
    }
}
