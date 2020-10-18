<?php

namespace Messerli90\Hunterio;

class Hunter extends HunterClient
{
    public function account()
    {
        $this->endpoint = 'account';
        $this->query_params = [
            'api_key' => $this->api_key ?? null
        ];
        return $this->get();
    }

    public function domainSearch($domain = null)
    {
        if (!$domain) {
            return new DomainSearch($this->api_key);
        }
        return (new DomainSearch($this->api_key))->domain($domain)->get();
    }

    public function emailCount($domain = null)
    {
        if (!$domain) {
            return new EmailCount($this->api_key);
        }
        return (new EmailCount($this->api_key))->domain($domain)->get();
    }

    public function emailFinder($domain = null)
    {
        if (!$domain) {
            return new EmailFinder($this->api_key);
        }
        return (new EmailFinder($this->api_key))->domain($domain);
    }

    public function leadsLists($list_id = null)
    {
        if (!$list_id) {
            return new LeadsLists($this->api_key);
        }
        return (new LeadsLists($this->api_key))->list($list_id)->get();
    }

    public function leads($lead_id = null)
    {
        if (!$lead_id) {
            return new Leads($this->api_key);
        }
        return (new Leads($this->api_key))->lead($lead_id)->get();
    }

    public function campaigns($id = null)
    {
        if (!$id) {
            return new Campaigns($this->api_key);
        }
        return (new Campaigns($this->api_key))->campaign($id);
    }

    /**
     * @deprecated v1.1.0
     */
    public function emailVerifier()
    {
        return new EmailVerifier($this->api_key);
    }

    public function verifyEmail($email)
    {
        return (new EmailVerifier($this->api_key))->verify($email);
    }
}
