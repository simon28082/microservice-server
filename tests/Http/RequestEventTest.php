<?php

/**
 * @author simon <crcms@crcms.cn>
 * @datetime 2019-02-22 23:04
 *
 * @link http://crcms.cn/
 *
 * @copyright Copyright &copy; 2019 Rights Reserved CRCMS
 */

namespace CrCms\Microservice\Server\Tests\Http  ;

use CrCms\Microservice\Server\Contracts\KernelContract;
use CrCms\Microservice\Server\Http\Events\RequestEvent;
use CrCms\Microservice\Server\Http\Server;
use CrCms\Microservice\Server\Tests\ApplicationTrait;
use CrCms\Server\Drivers\Laravel\Laravel;
use PHPUnit\Framework\TestCase;
use Swoole\Http\Request;
use Swoole\Http\Response;

class RequestEventTest extends TestCase
{
    use ApplicationTrait;

    /**
     * @var Laravel
     */
    public static $laravel;

    public function setUp()
    {
        // TODO: Change the autogenerated stub
        parent::setUp();

        static::$laravel = new Laravel(static::$app);
        $kernel = \Mockery::mock(KernelContract::class);
        $kernel->shouldReceive('terminate');
        static::$laravel->getBaseContainer()->instance(KernelContract::class,$kernel);
        static::$laravel->close();
    }

    public function testRequestEvent()
    {

        $server = new Server(static::$app->make('config')->get('swoole'),static::$laravel);

        //
        $swooleRequest = \Mockery::mock(Request::class);
        $swooleRequest->server = [
            'request_method' => 'OPTIONS'
        ];
        $swooleRequest->shouldReceive('rawContent')->andReturn('123');

        //
        $swooleResponse = \Mockery::mock(Response::class);//new Response();
        $swooleResponse->shouldReceive('status')->andReturn(200);
        $swooleResponse->shouldReceive('header')->andReturn(['content-type'=>'text/plain']);
        $swooleResponse->shouldReceive('end')->andReturn('end');

        /* @var \Mockery\MockInterface $kernel */
        $kernel = static::$laravel->getBaseContainer()->make(KernelContract::class);
        $kernel->shouldReceive('handle')->andReturn(
            (new \CrCms\Microservice\Server\Http\Response())->setData(['x'=>1])->setPackData('abc')
        );

        $app1 = $server->getApplication();

        $event = new RequestEvent($server,$swooleRequest,$swooleResponse);

        $event->handle();


        $app2 = $server->getApplication();

        $this->assertNotEquals(spl_object_hash($app1),spl_object_hash($app2));

    }

}