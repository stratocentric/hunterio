<?php

namespace Messerli90\Hunterio;

use Illuminate\Support\Facades\Http;
use Messerli90\Hunterio\Exceptions\InvalidRequestException;
use Messerli90\Hunterio\Interfaces\EndpointInterface;

// OK - List all your leads lists
// OK - Retrieve one of your leads lists
// OK - Create a new leads list
// OK - Update an existing leads list
// OK - Delete an existing leads list

class LeadsLists extends HunterClient
{
    /**
     * Domain name from which you want to find the email addresses
     *
     * @var string
     */
    public $list_id;

    /**
     * Specifies the max number of email addresses to return
     *
     * @var int
     */
    public $limit = 20;

    /**
     * Specifies the number of email addresses to skip
     *
     * @var int
     */
    public $offset = 0;

    public function __construct(string $api_key = null)
    {
        parent::__construct($api_key);

        $this->endpoint = 'leads_lists';
    }

    /**
     * Sets domain to search
     *
     * @param string $list_id
     * @return LeadsLists
     */
    public function list(string $list_id): self
    {
        $this->list_id = $list_id;
        $this->endpoint .= '/' . $this->list_id;

        return $this;
    }

    /**
     * Set max number of emails to return. Max 100
     *
     * @param int $limit
     * @return LeadsLists
     */
    public function limit(int $limit): self
    {
        $this->limit = $limit <= 100 ? $limit : 20;

        return $this;
    }

    /**
     * Set the number of email addresses to skip
     *
     * @param int $offset
     * @return LeadsLists
     */
    public function offset(int $offset): self
    {
        $this->offset = $offset;

        return $this;
    }

    public function make()
    {


        $this->query_params = [
            'limit' => $this->limit ?? null,
            'offset' => $this->offset ?? null,
            'api_key' => $this->api_key ?? null
        ];

        return $this->query_params;
    }

    public function create(string $name = null)
    {
        if (!$name) {
            throw new InvalidRequestException('Name must be specified');
        }
        $this->body = ['name' => $name];
        return parent::create();
    }

    public function update(string $name = null)
    {
        if (!$name) {
            throw new InvalidRequestException('Name must be specified');
        }
        $this->body = ['name' => $name];
        return parent::update();
    }

    public function delete(string $list_id = null)
    {
        if ($list_id) {
            $this->list($list_id);
        }

        if (!$this->list_id) {
            throw new InvalidRequestException('Name must be specified');
        }
        return parent::delete();
    }
}
