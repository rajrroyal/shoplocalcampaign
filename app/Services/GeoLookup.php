<?php

namespace App\Services;

class GeoLookup
{
    public function lookupAddress(string $street, string $city, string $region, string $country)
    {
        $coordinates = (object) [
            'latitude' => null,
            'longitude' => null
        ];

        // @TODO geolookup address

        return $coordinates;
    }
}
