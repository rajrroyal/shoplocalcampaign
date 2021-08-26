<?php

namespace App\Services\Utilities;


use Jenssegers\Agent\Facades\Agent;

class EnvironmentService
{
    /**
     * Return the IP address of the current request
     *
     * @return string IP Address
     */
    public function remoteAddr()
    {
        return $this->coalesceServerVariables([
            "HTTP_X_FORWARDED_FOR", // Amazon AWS
            "REMOTE_ADDR" // Default
        ]);
    }

    /**
     * Return the user agent for the current request
     *
     * @return string|null
     */
    public function httpUserAgent()
    {
        return $this->coalesceServerVariables([
            "HTTP_USER_AGENT" // Default
        ]);
    }

    public function country()
    {
        return request()->server('HTTP_CLOUDFRONT_VIEWER_COUNTRY');
    }

    public function languages()
    {
        return Agent::languages();
    }

    public function browser()
    {
        return Agent::browser();
    }

    /**
     * Return whether or not the default caching driver
     * supports tags
     *
     * @return boolean
     */
    public function cacheSupportsTags()
    {
        $driver = config('cache.default','');
        return in_array($driver, ['memcached', 'redis']);
    }

    public function isCLI()
    {
        return (strpos(php_sapi_name(), 'cli') !== false);
    }

    // ---------------------------------------------------------------------

    /**
     * Return the first server variable from the list of keys provided
     * that returns a non-null value
     *
     * @param  array  $keys
     * @return string|null
     */
    protected function coalesceServerVariables(array $keys)
    {
        foreach($keys as $key) {
            $value = request()->server($key);
            if (strlen($value)) {
                return $value;
            }
        }

        return null;
    }
}
