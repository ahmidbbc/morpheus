<?php


namespace App\Hook;

use Symfony\Component\HttpClient\HttpClient;

class GeolocationHook
{

    /**
     * @param string $location_city
     * @param string $location_state
     * @return string
     * @throws \Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     */
    public function getZipCode(string $location_city, string $location_state): string
    {

        // instance of HttpClient
        $client = HttpClient::create();

        /**
         * Request
         * @param string method
         * @param string url
         */
        $response = $client->request(
            "GET",
            "https://nominatim.openstreetmap.org/?format=json&addressdetails=1&city={$location_city}&postcode={$location_state}&limit=1"
        );

        // get response as an array
        //$content = $response->getContent();
        $statusCode = $response->getStatusCode();
        $content = $response->toArray();


        // ternary + coalesce null operators (really !? good practice ??)
        // equivalent to :
        // if ($statusCode == 200 ) $zip_code = $content[0]['address']['postcode'] OR '0'
        // else zip code = 0
        $statusCode == 200 ? $zip_code = $content[0]['address']['postcode'] ?? '0' : $zip_code = '0';

        //var_dump($statusCode);

        return $zip_code;

    }


    // others Geolocation methods...

}
