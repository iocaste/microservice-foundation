<?php

namespace Iocaste\Microservice\Foundation\Feature\Client;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Request as SymfonyRequest;
use \GuzzleHttp\Client;

/**
 * Class HttpClient
 */
class HttpClient
{
    /**
     * @var string
     */
    protected $baseUrl;

    /**
     * The last response returned by the application.
     *
     * @var \Illuminate\Http\Response
     */
    protected $response;

    /**
     * The current URL being viewed.
     *
     * @var string
     */
    protected $currentUri;

    /**
     * @param string $baseUrl
     *
     * @return $this
     */
    public function setBaseUrl(string $baseUrl)
    {
        $this->baseUrl = $baseUrl;

        return $this;
    }

    /**
     * @param bool $toJson
     * @param bool $toAssociative
     *
     * @return mixed
     */
    public function getResponse($toJson = false, $toAssociative = false)
    {
        return $toJson ? json_decode($this->response, $toAssociative) : $this->response;
    }

    /**
     * Visit the given URI with a JSON request.
     *
     * @param $method
     * @param $uri
     * @param array $data
     * @param array $headers
     * @param bool $async
     *
     * @return $this
     */
    public function json($method, $uri, array $data = [], array $headers = [], $async = false)
    {
        $content = json_encode($data);

        $headers = array_merge([
            'CONTENT_LENGTH' => mb_strlen($content, '8bit'),
            'CONTENT_TYPE' => 'application/json',
            'Accept' => 'application/json',
        ], $headers);

        $this->call($method, $uri, $data, [], [], $this->transformHeadersToServerVars($headers), $content, $async);

        return $this;
    }

    /**
     * Visit the given URI with a GET request.
     *
     * @param $uri
     * @param array $headers
     *
     * @return $this
     */
    public function get($uri, array $headers = [])
    {
        $server = $this->transformHeadersToServerVars($headers);

        $this->call('GET', $uri, [], [], [], $server);

        return $this;
    }

    /**
     * Visit the given URI with a POST request.
     *
     * @param  string  $uri
     * @param  array  $data
     * @param  array  $headers
     * @return $this
     */
    public function post($uri, array $data = [], array $headers = [])
    {
        $server = $this->transformHeadersToServerVars($headers);

        $this->call('POST', $uri, $data, [], [], $server);

        return $this;
    }

    /**
     * Visit the given URI with a PUT request.
     *
     * @param  string  $uri
     * @param  array  $data
     * @param  array  $headers
     * @return $this
     */
    public function put($uri, array $data = [], array $headers = [])
    {
        $server = $this->transformHeadersToServerVars($headers);

        $this->call('PUT', $uri, $data, [], [], $server);

        return $this;
    }

    /**
     * Visit the given URI with a PATCH request.
     *
     * @param  string  $uri
     * @param  array  $data
     * @param  array  $headers
     * @return $this
     */
    public function patch($uri, array $data = [], array $headers = [])
    {
        $server = $this->transformHeadersToServerVars($headers);

        $this->call('PATCH', $uri, $data, [], [], $server);

        return $this;
    }

    /**
     * Visit the given URI with a DELETE request.
     *
     * @param  string  $uri
     * @param  array  $data
     * @param  array  $headers
     * @return $this
     */
    public function delete($uri, array $data = [], array $headers = [])
    {
        $server = $this->transformHeadersToServerVars($headers);

        $this->call('DELETE', $uri, $data, [], [], $server);

        return $this;
    }

    /**
     * Send the given request through the application.
     * This method allows you to fully customize the entire Request object.
     *
     * @param Request $request
     * @param bool $async
     *
     * @return \Psr\Http\Message\StreamInterface
     */
    public function send(Request $request, $async = false): \Psr\Http\Message\StreamInterface
    {
        $this->currentUri = $request->fullUrl();
        $options = [];

        if ($request->method() === 'POST') {
            $options['json'] = $request->request->all();
        }

        $client = new Client();
        $requestType = $async ? 'requestAsync' : 'request';

        $response = $client->{$requestType}($request->method(), $this->currentUri, $options);

        return $response->getBody();
    }

    /**
     * Call the given URI and return the Response.
     *
     * @param  string  $method
     * @param  string  $uri
     * @param  array   $parameters
     * @param  array   $cookies
     * @param  array   $files
     * @param  array   $server
     * @param  string  $content
     * @param  bool    $async
     *
     * @return mixed
     */
    public function call($method, $uri, $parameters = [], $cookies = [], $files = [], $server = [], $content = null, $async = false)
    {
        $this->currentUri = $this->prepareUrlForRequest($uri);

        $symfonyRequest = SymfonyRequest::create(
            $this->currentUri,
            $method,
            $parameters,
            $cookies,
            $files,
            $server,
            $content
        );

        return $this->response = $this->send(
            Request::createFromBase($symfonyRequest),
            $async
        );
    }

    /**
     * Turn the given URI into a fully qualified URL.
     *
     * @param string $uri
     * @return string
     */
    protected function prepareUrlForRequest($uri): string
    {
        if (Str::startsWith($uri, '/')) {
            $uri = substr($uri, 1);
        }

        if (! Str::startsWith($uri, 'http')) {
            $uri = $this->baseUrl . '/' . $uri;
        }

        return trim($uri, '/');
    }

    /**
     * Transform headers array to array of $_SERVER vars with HTTP_* format.
     *
     * @param array $headers
     * @return array
     */
    protected function transformHeadersToServerVars(array $headers): array
    {
        $server = [];
        $prefix = 'HTTP_';

        foreach ($headers as $name => $value) {
            $name = str_replace(strtoupper($name), '-', '_');

            if ($name !== 'CONTENT_TYPE' && ! starts_with($name, $prefix)) {
                $name = $prefix.$name;
            }

            $server[$name] = $value;
        }

        return $server;
    }
}
