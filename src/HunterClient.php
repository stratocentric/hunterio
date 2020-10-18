<?php

namespace Messerli90\Hunterio;

use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Messerli90\Hunterio\Exceptions\AuthorizationException;
use Messerli90\Hunterio\Exceptions\InvalidRequestException;
use Messerli90\Hunterio\Exceptions\UsageException;
use Messerli90\Hunterio\Interfaces\EndpointInterface;

class HunterClient implements EndpointInterface
{
    /** @var string */
    protected $api_key;

    protected $body;

    /** @var string */
    protected $base_url = "https://api.hunter.io/v2";

    /** @var string */
    public $endpoint = "";

    /** @var array */
    public $query_params = [];

    /**
     * @param string|null $api_key
     * @throws AuthorizationException
     */
    public function __construct(string $api_key = null)
    {
        if (empty($api_key)) {
            throw new AuthorizationException('API key required');
        }
        $this->api_key = $api_key;
    }

    /**
     *
     * @param mixed $attr
     * @return mixed
     */
    public function __get($attr)
    {
        return $this->$attr;
    }

    public function make()
    {
        return array_filter($this->query_params, function ($q) {
            return isset($q);
        });
    }

    public function body()
    {

        return $this->body;
    }

    protected function buildUrl($query_params = true)
    {
        if ($query_params) {
            return "{$this->base_url}/{$this->endpoint}";
        } else {
            return "{$this->base_url}/{$this->endpoint}?api_key=" . $this->api_key;
        }
    }

    public function get()
    {
        $response = Http::get($this->buildUrl(), $this->make());

        if ($response->ok()) {
            return $response->json();
        } else {
            return $this->handleErrors($response);
        }
    }

    public function create(string $name = null)
    {
        $response = Http::post($this->buildUrl(false), $this->body());
        if ($response->status() === 201) {
            return $response->json();
        } else {
            return $this->handleErrors($response);
        }
    }

    public function update(string $name = null)
    {
        $response = Http::put($this->buildUrl(false), $this->body());

        if ($response->status() === 204) {
            return $response->json();
        } else {
            return $this->handleErrors($response);
        }
    }

    public function delete()
    {
        $this->limit = null;
        $this->offset = null;
        $response = Http::delete($this->buildUrl(), $this->make());

        if ($response->status() === 204) {
            return $response->json();
        } else {
            return $this->handleErrors($response);
        }
    }

    protected function handleErrors(Response $response)
    {
        $message = $response->json()['errors'][0]['details'];
        if ($response->status() === 401) {
            // No valid API key was provided.
            throw new AuthorizationException($message);
        } else if (in_array($response->status(), [403, 429])) {
            // Thrown when `usage limit` or `rate limit` is reached
            // Upgrade your plan if necessary.
            throw new UsageException($message);
        } else {
            // Your request was not valid.
            throw new InvalidRequestException($message);
        }
    }
}
