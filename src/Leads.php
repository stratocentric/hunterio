<?php

namespace Messerli90\Hunterio;

use Illuminate\Support\Facades\Http;
use Messerli90\Hunterio\Exceptions\InvalidRequestException;
use Messerli90\Hunterio\Interfaces\EndpointInterface;


class Leads extends HunterClient
{
    /**
     * Domain name from which you want to find the email addresses
     *
     * @var string
     */
    public $lead_id;

    protected $filter_array = [];

    protected $filter_options = [
        'leads_list_id',    // Only returns the leads belonging to this list.
        'email',    // Filters the leads by email.
        'first_name',    // Filters the leads by first name.
        'last_name',    // Filters the leads by last name.
        'position',    // Filters the leads by position.
        'company',    // Filters the leads by company.
        'industry',    // Filters the leads by industry.
        'website',    // Filters the leads by website.
        'country_code',    // Filters the leads by country. The country code is defined in the ISO 3166-1 alpha-2 standard.
        'company_size',    // Filters the leads by company size.
        'source',    // Filters the leads by source.
        'twitter',    // Filters the leads by Twitter handle.
        'linkedin_url',    // Filters the leads by LinkedIn URL.
        'phone_number',    // Filters the leads by phone number.
        'sync_status',    // Only returns the leads matching this synchronization status. It can be one of the following values: pending, error, success.
        'sending_status',    // Only returns the leads matching this sending status. It can be one of the following values: clicked, opened, sent, pending, error, bounced, unsubscribed, replied.
        'last_activity_at',    // Only returns the leads matching this last activity. It can be one of the following values: * (any value) or ~ (unset).
        'query' // Query all fields
    ];

    protected $lead_fields = [
        'email', //	REQUIRED - The email address of the lead.
        'first_name',    //	The first name of the leads.
        'last_name', //	The last name of the lead.
        'position',  //	The job title of the lead.
        'company',   //	The name of the company the lead is working in.
        'company_industry',  //	The sector of the company. It can be any value, but we recommend using one of the following: Animal, Art and Entertainment, Automotive, Beauty and Fitness, Books and Litterature, Education and Career, Finance, Food and Drink, Game, Health, Hobby and Leisure, Home and Garden, Industry, Internet and Telecom, Law and Government, News, Real Estate, Science, Shopping, Sport, Technology or Travel.
        'company_size',  //	The size of the company the lead is working in.
        'confidence_score',  //	Estimation of the probability the email address returned is correct, between 0 and 100. In Hunter's products, the confidence score is the score returned by the Email Finder.
        'website',   //	The domain name of the company.
        'country_code',  //	The country of the lead. The country code is defined in the ISO 3166-1 alpha-2 standard.
        'linkedin_url',  //	The address of the public profile on LinkedIn.
        'phone_number',  //	The phone number of the lead.
        'twitter',   //	The Twitter handle of the lead.
        'notes', //	Some personal notes about the lead.
        'source',    //	The source where the lead has been found.
        'leads_list_id'
    ];

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

        $this->endpoint = 'leads';
    }

    /**
     * Sets domain to search
     *
     * @param string $list_id
     * @return Leads
     */
    public function lead(string $lead_id = null, array $data = null): self
    {
        if ($lead_id) {
            $this->lead_id = $lead_id;
            $this->endpoint .= '/' . $this->lead_id;
        }

        if ($data) {
            $body_data = array_intersect_key($data, array_flip($this->lead_fields));
            $this->body = $body_data;
        }

        return $this;
    }



    /**
     * Set max number of emails to return. Max 100
     *
     * @param int $limit
     * @return Leads
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
     * @return Leads
     */
    public function offset(int $offset): self
    {
        $this->offset = $offset;

        return $this;
    }


    public function filter(array $filter_array = []): self
    {
        foreach ($this->filter_options as $option) {
            if (isset($filter_array[$option])) {
                $this->query_params[$option] = $filter_array[$option];
            }
        }

        return $this;
    }

    public function make()
    {

        $this->query_params = array_merge(
            $this->query_params,
            [
                'limit' => $this->limit ?? null,
                'offset' => $this->offset ?? null,
                'api_key' => $this->api_key ?? null
            ]
        );

        return $this->query_params;
    }


    public function save()
    {
        if ($this->lead_id) {
            return $this->update();
        } else {
            return $this->create();
        }
    }
}
