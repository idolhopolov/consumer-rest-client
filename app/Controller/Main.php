<?php

namespace Merce\Consumer\RestClient\Controller;

use Merce\RestClient\HttpPlug\src\HttpPlugController;
use Merce\RestClient\HttpPlug\src\Support\EHttpMethod;
use Merce\RestClient\HttpPlug\src\Core\Middleware\Impl\AuthMiddleware;
use Merce\RestClient\HttpPlug\src\Core\Client\Impl\Curl\CurlHttpClient;
use Merce\RestClient\HttpPlug\src\Core\Builder\Request\Impl\RequestBuilder;
use Merce\RestClient\AuthTokenPlug\src\DTO\BasicAuthToken\BasicAuthTokenCredentialData;
use Merce\RestClient\HttpPlug\src\DTO\Middleware\Collection\Impl\ArrayMiddlewareCollection;
use Merce\RestClient\AuthTokenPlug\src\Core\TokenController\BasicAuthToken\ManualBasicAuthTokenController;

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
            'handler' => new ArrayMiddlewareCollection(new AuthMiddleware(new ManualBasicAuthTokenController(new BasicAuthTokenCredentialData('postman', 'password')))),
        ];

        $controller = new HttpPlugController(...$args);
        $request = (new RequestBuilder())
            ->setUri('https://postman-echo.com/basic-auth')
            ->setMethod(EHttpMethod::GET)
            ->getRequest();

        $response = $controller->get($request);
        var_dump($response->getBody()->getContents());
    }

    private function httpPostUsage(): void
    {

        $args = [
            'client' => new CurlHttpClient(),
        ];

        $request = (new RequestBuilder())
            ->setUri('https://dummyjson.com/products/add')
            ->setMethod(EHttpMethod::POST)
            ->setBody(json_encode(
                (object)[
                    'title' => 'keyboard',
                ]
            ))->getRequest();

        $controller = new HttpPlugController(...$args);
        $response = $controller->post($request);

        var_dump(json_decode($response->getBody()->getContents()));
    }

    private function httpGetUsage(): void
    {

        $args = [
            'client' => new CurlHttpClient(),
        ];
        $controller = new HttpPlugController(...$args);
        $request = (new RequestBuilder())
            ->setUri('https://dummyjson.com/products')
            ->setMethod(EHttpMethod::GET)
            ->getRequest();

        $response = $controller->get($request);

        print '<pre>';
        var_dump(json_decode($response->getBody()->getContents()));
        print '</pre>';
    }
}