<?php

namespace Lens\Bundle\KiyohBundle\Inviter;

class RequestContent
{
    /**
     * API key (token) to authorize the request.
     */
    public $hash;

    /**
     * ID for the location for which the invite should be sent.
     */
    public $location_id;

    /**
     * 99 (The tenant ID number for Klantenvertellen is "99").
     */
    public $tenantId;

    /**
     * Email address that should receive the invite.
     */
    public $invite_email;

    /**
     * Number of days (integer) after which the email should be sent. 0 is
     * immediately.
     */
    public $delay;

    /**
     * Name fields to personalize the invite.
     */
    public $first_name;
    public $last_name;

    /**
     * Language the invite email is sent, "nl" for Dutch (case sensitive).
     */
    public $language;

    /**
     * Internal reference code which can be used for administration purposes
     * (the reference code is visible in invite history, review exports and
     * XML feed).
     */
    public $ref_code;

    public function __construct(
        array $options,
        string $email,
        string $name = null,
        string $reference = null
    ) {
        $type = $options['invites']['request_type'];

        $this->location_id = $options['location_id'];
        $this->invite_email = $email;
        $this->delay = $options['invites']['delay'];

        if (Inviter::TYPE_URL === $type) {
            $this->hash = $options['invites']['api_key'];
        }

        $this->tenantId = $options['tenant_id'];

        $names = explode(' ', $name);
        $firstName = array_shift($names);
        $this->first_name = empty($firstName) ? null : $firstName;
        $this->last_name = count($names) ? implode(' ', $names) : null;

        $this->language = $options['language'];
        $this->ref_code = $reference;
    }
}
