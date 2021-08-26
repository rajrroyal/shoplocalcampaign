<?php

namespace App\Helpers;

class UrlHelper
{
    /**
     * Add a default protocol to specified address if it lacks one
     *
     * @param  string  $url
     * @return string
     */
    public function prepareUrl(string $url)
    {
        if ($parts = parse_url($url)) {
            if (! isset($parts['scheme'])) {
                $url = 'https://' . $url;
            }
        }

        return $url;
    }
}
