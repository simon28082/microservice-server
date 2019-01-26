<?php

namespace CrCms\Microservice\Server\Tests;


use CrCms\Microservice\Server\Http\Request;
use PHPUnit\Framework\TestCase;

class KernelTest extends TestCase
{
    use ApplicationTrait;

    public function testKernel()
    {
        $kernel = new Kernel(static::$app);

        $kernel->request(new Request(\Illuminate\Http\Request::capture()));
    }
}