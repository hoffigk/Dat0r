<?php

namespace Dat0r\Tests\Common;

use Dat0r\Tests\TestCase;
use Dat0r\Common\Options;

class OptionTest extends TestCase
{
    public function testCreate()
    {
        $data = $this->getRandomScalarValues();
        $options = new Options($data);

        $this->assertInstanceOf('\\Dat0r\\Common\\Options', $options);
        $this->assertEquals($data['one'], $options->get('one'));
        $this->assertEquals($data['two'], $options->get('two'));
        $this->assertEquals($data['three'], $options->get('three'));
    }

    public function testToArray()
    {
        $data = $this->getRandomScalarValues();
        $options = new Options($data);

        $this->assertEquals($data, $options->toArray());
    }

    public function testGetDefaultValues()
    {
        $options = new Options(
            array(
                'nil' => null,
                'null_as_string' => '0',
                'null_as_int' =>0
            )
        );

        $this->assertEquals('default', $options->get('non_existant', 'default'));
        $this->assertFalse($options->has('non_existant'));

        $this->assertEquals(null, $options->get('nil', 'default'));
        $this->assertTrue($options->has('nil'));
        $this->assertFalse($options->has('NIL'));

        $this->assertEquals('0', $options->get('null_as_string', 'default'));
        $this->assertTrue($options->has('null_as_string'));
        $this->assertFalse($options->has('Null_as_string'));

        $this->assertEquals(0, $options->get('null_as_int', 'default'));
        $this->assertTrue($options->has('null_as_int'));
        $this->assertFalse($options->has('Null_as_int'));
    }

    public function testSetValue()
    {
        $options = new Options(array('foo' => 'trololo'));
        $options->set('foo', 'bar');
        $options->set('nil', null);

        $this->assertEquals('bar', $options->get('foo', 'default'));
        $this->assertEquals(null, $options->get('nil', 'default'));
    }

    public function testGetKeys()
    {
        $options = new Options(array('foo' => 'trololo'));
        $options->set('foo', 'bar');
        $options->set('nil', null);

        $this->assertEquals(array('foo', 'nil'), $options->getKeys());
    }

    public function testClearValues()
    {
        $options = new Options(array('foo' => 'bar'));
        $options->clear();

        $this->assertEquals(array(), $options->toArray());
    }

    protected function getRandomScalarValues()
    {
        return array(
            'one' => self::$faker->word(),
            'two' => self::$faker->randomNumber(0, 999),
            'three' => self::$faker->boolean()
        );
    }
}