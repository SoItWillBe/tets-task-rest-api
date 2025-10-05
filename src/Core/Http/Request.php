<?php

namespace App\Core\Http;

class Request
{
    public string $method;

    public string $uri;

    public array $get;

    public array $post;

    public array $files;

    public array $cookies;

    public array $headers;

    public ?array $json;

    public function __construct()
    {
        $this->method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
        $this->uri = $_SERVER['REQUEST_URI'] ?? '/';
        $this->get = $_GET;
        $this->post = $_POST;
        $this->files = $_FILES;
        $this->cookies = $_COOKIE;
        $this->headers = getallheaders();
        $this->json = $this->parseJson();
    }

    private function parseJson(): ?array
    {
        $raw = file_get_contents('php://input');
        if (!$raw) return null;
        $data = json_decode($raw, true);
        return is_array($data) ? $data : null;
    }
}