<?php

namespace OAuth2;

class AccessToken
{
    public $accessToken;
    public $expires;
    public $refreshToken;
    public $uid;

    /**
     * Sets the token, expiry, etc values.
     *
     * @param array $options token options
     * @return void
     */
    public function __construct(array $options = null)
    {
        if (!isset($options['access_token']))
        {
            throw new \InvalidArgumentException(
                'Required option not passed: access_token in '.print_r($options, true)
            );
        }

        $this->accessToken = $options['access_token'];

        if (!empty($options['uid']))
        {
            $this->uid = $options['uid'];
        }

        if (!empty($options['refresh_token']))
        {
            $this->refreshToken = $options['refresh_token'];
        }

        // We need to know when the token expires. Show preference to
        // 'expires_in' since it is defined in RFC6749 Section 5.1.
        // Defer to 'expires' if it is provided instead.
        if (!empty($options['expires_in']))
        {
            $this->expires = time() + ((int) $options['expires_in']);
        }
        elseif (!empty($options['expires']))
        {
            // Some providers supply the seconds until expiration rather than
            // the exact timestamp. Take a best guess at which we received.
            $expires = $options['expires'];
            $expiresInFuture = $expires > time();
            $this->expires = $expiresInFuture ? $expires : time() + ((int) $expires);
        }
    }

    /**
     * Returns the token key.
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->accessToken;
    }
}
