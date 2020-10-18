<?php

namespace Messerli90\Hunterio;

use Illuminate\Support\Facades\Http;
use Messerli90\Hunterio\Exceptions\InvalidRequestException;
use Messerli90\Hunterio\Interfaces\EndpointInterface;

class Campaigns extends HunterClient
{
    /**
     * Domain name from which you want to find the email addresses
     *
     * @var string
     */
    public $campaign_id;
    public $emails = [];

    public function __construct(string $api_key = null)
    {
        parent::__construct($api_key);

        $this->endpoint = 'campaigns';
    }

    /**
     * Sets domain to search
     *
     * @param string $list_id
     * @return Campaigns
     */
    public function campaign(string $campaign_id): self
    {
        $this->campaign_id = $campaign_id;
        $this->endpoint = 'campaigns/' . $this->campaign_id . '/recipients';

        return $this;
    }

    /**
     * Sets domain to search
     *
     * @param string $list_id
     * @return Campaigns
     */
    public function addEmails(array $emails)
    {
        $this->emails = $emails;
        $this->body = ['emails' => $this->emails];

        return parent::create();
    }


    public function make()
    {


        $this->query_params = [
            'api_key' => $this->api_key ?? null
        ];

        return $this->query_params;
    }
}
