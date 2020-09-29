<?php


namespace App\Hook;

use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class GeolocationHook
{

    public function getZipCode(string $location_city, string $location_state): string
    {

        $client = HttpClient::create();
        $response = $client->request(
            "GET",
            "https://nominatim.openstreetmap.org/?format=json&addressdetails=1&city={$location_city}&postcode={$location_state}&limit=1"
        );

        $content = $response->getContent();
        $content = $response->toArray();

        //if(!empty($content[0]['address']['postcode']))
        $zip_code = $content[0]['address']['postcode'] ?? '0';

        //var_dump($zip_code);

        return $zip_code;

    }


    // others geolocation methods...

}
