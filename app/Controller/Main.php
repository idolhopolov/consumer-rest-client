<?php

namespace Merce\Consumer\RestClient\Controller;

use Merce\RestClient\HttpPlug\src\HttpPlugController;
use Merce\RestClient\HttpPlug\src\Client\Impl\Curl\CurlHttpClient;
use Merce\RestClient\HttpPlug\src\Middleware\Impl\BasicAuthMiddleware;
use Merce\RestClient\HttpPlug\src\MiddlewareContainer\Impl\StackMiddlewareHandler;

class Main
{

    public function index(): void
    {

        $this->basicAuthUsage();
        $this->httpPostUsage();
        $this->httpGetUsage();
    }

    private function basicAuthUsage(): void
    {

        $args = [
            'client'  => new CurlHttpClient(),
            'handler' => new StackMiddlewareHandler(new BasicAuthMiddleware('postman', 'password')),
        ];
        $controller = new HttpPlugController(...$args);
        $response = $controller->get('https://postman-echo.com/basic-auth');
        var_dump($response->getBody()->getContents());
    }

    private function httpPostUsage(): void
    {

        $args = [
            'client' => new CurlHttpClient(),
        ];
        $controller = new HttpPlugController(...$args);
        $response = $controller->post(
            'https://dummyjson.com/products/add',
            ['Content-Type' => 'application/json'],
            body: json_encode(
                (object)[
                    'title' => 'keyboard',
                ]
            )
        );
        var_dump(json_decode($response->getBody()->getContents()));
    }

    private function httpGetUsage(): void
    {

        $args = [
            'client' => new CurlHttpClient(),
        ];
        $controller = new HttpPlugController(...$args);
        $response = $controller->get("https://dummyjson.com/products");
        var_dump(json_decode($response->getBody()->getContents()));
    }
}