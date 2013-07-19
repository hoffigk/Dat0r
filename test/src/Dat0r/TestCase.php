<?php

namespace Dat0r\Tests;

use Faker;

abstract class TestCase extends \PHPUnit_Framework_TestCase
{
    protected static $faker;

    public static function setUpBeforeClass()
    {
        self::$faker = Faker\Factory::create();
    }
}
