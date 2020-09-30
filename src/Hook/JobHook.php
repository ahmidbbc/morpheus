<?php
// src/Hook/JobHook.php
namespace App\Hook;

use App\Converter\XMLConverter;
use Symfony\Component\HttpClient\HttpClient;

class JobHook
{

    private int $id                 = 0;
    private static string $vertical = 'job';
    private static bool $pro_ad     = true;

    /**
     * format and return the ad according to API rules
     * @param array $ad
     * @return array
     */
    public function formatAd(array $ad): array
    {
        $formatted_ad = []; //not required initialization just for understanding

        // generate unique ID (required field according to Api rules
        $formatted_ad['id']         = $this->getID();

        // limit to 100 according to Api rules
        $formatted_ad['title']      = substr($ad['title'], 0, 100);

        // format body to concat and limit to 500 according to Api rules
        $body = substr($ad['description_poste'] . $ad['description_entreprise'], 0, 500);
        $formatted_ad['body']       = $body;

        // limited by self private attribute (not visible for web client)
        // TODO : should be force limit to 100 chars ?
        $formatted_ad['vertical']   = self::$vertical;

        // limit to 100 according to Api rules
        $formatted_ad['city']       = substr($ad['location_city'], 0, 100);

        // limited by self private attribute as a boolean according to Api rules
        // not visible by web client
        $formatted_ad['pro_ad']     = self::$pro_ad;

        // format pictures string list to array according to Api rules
        $picturesString = trim($ad['pictures']);
        $picturesFormat = str_replace(" ", "\n", $picturesString);
        $picturesArray = explode("\n", $picturesFormat);
        $pictures = $this->trimArray($picturesArray);

        // get only the first 10 images from $pictures array according to to Api rules
        count( $pictures ) > 0
            ? ( $formatted_ad['images'] = array_slice($pictures, 0, 10) )
            : '';

        // not expected by the Api but documented as required field ?
        // TODO : check this with my team / team Lead
        //$formatted_ad['contract']   = $ad['time_type'];

        // TODO: get zip code from geolocation API webservice as Google Maps or openStreetMap ?
        // TODO : is done !? improve ??
        /**
         * instance of GeolocationHook
         * get zip code from openStreetMap API webservice : less reliable than Google Map
         * sometimes missing the zip code : ex. "Metz with location_state 57 not returning entire postcode"
         */
        $geo = new GeolocationHook();
        $address = $geo->getZipCode( $ad['location_city'], $ad['location_state'] );
        $address ? $formatted_ad['zip_code'] = $address : '';

        //var_dump($this->id);

        return $formatted_ad;
    }

    /**
     * TODO
     * Just build body ad ?
     */
    public function buildDescription()
    {
        //...
    }


    /**
     * return unique id
     * @return int
     * TODO: duplicate in RealEstateHook => service ?
     */
    function getID(): int
    {
        $this->id <= 100 ? $this->id++ : $this->id = 100;

        return $this->id;

        //return (int) (time().mt_rand());

    }

    /**
     * trim all elements of an array
     * @param array $ad
     * @return array
     */
    public function trimArray(array $ad): array
    {
        $array = array();

        foreach ($ad as $line){
            if(!empty($line))
                array_push($array, trim($line));
        }

        return $array;

    }
}
