<?php

namespace CrCms\Microservice\Server\Tests;

use Illuminate\Contracts\Container\Container;

trait ApplicationTrait
{
    /**
     * @var Container
     */
    public static $app;

    public static function setUpBeforeClass()
    {
        // TODO: Change the autogenerated stub
        parent::setUpBeforeClass();
        static::$app = app();
    }

    public static function tearDownAfterClass()
    {
        // TODO: Change the autogenerated stub
        parent::tearDownAfterClass();

        static::$app = null;
    }
}
