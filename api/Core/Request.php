<?php

namespace HM\Core\KC;

class Request
{
    private array $data = [];
    private string $method;
    private array $query;
    private string $path;
    private string $scheme;
    private string $hostname;
    private array $params = [];

    public function __construct()
    {
        $this->data     = $_POST;
        $this->query    = $_GET;
        $this->hostname = trim($_SERVER['HTTP_HOST'], '/');
        $this->method   = $_SERVER['REQUEST_METHOD'];
        $this->path     = trim($_SERVER['REQUEST_URI'], '/');
        $this->scheme   = $_SERVER['REQUEST_SCHEME'];
    }

    public function method(): string
    {
        return strtolower($this->method);
    }
    public function hostname(): string
    {
        return strtolower(sprintf('%s.%s', $this->scheme, $this->hostname));
    }
    public function scheme(): string
    {
        return strtolower($this->scheme);
    }

    public function url(): string
    {
        return trim(sprintf('%s://%s/%s', $this->scheme, $this->hostname, $this->path), '/');
    }

    public function all(): array
    {
        return $this->data;
    }
    public function path($withQueryString = true): string
    {
        if ($withQueryString || strpos($this->path, '?') === false)
            return $this->path;

        $parts = explode('?', $this->path);

        return $parts[0];
    }

    public function query($key = null, $default = null): array
    {
        return isset($key) ? $this->query[$key] ?? $default : $this->query;
    }

    public function setParam(string $key, $value = null) :void
    {
        $this->params[$key] = $value;
    }

    public function removeParams() :void
    {
        $this->params = [];
    }

    public function params() :array
    {
        return $this->params;
    }

    public function param(string $key, $default = null) :?string
    {
        return $this->params[$key] ?? $default;
    }
}