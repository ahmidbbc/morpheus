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
        //vars
        $formatted_ad = []; //not required initialization just for understanding
        $buildedAd = $this->buildDescription($ad);

        /**
         * API rules validation
         */


        // documented as a string required field but expected as an integer according to Api Assert\Type
        // to implement as a string unique id : just change getID() method, call uniqid('', true) => length=23
        // change returning (method & attribute) type and change Api Assert\Type
        // limit to 100 (int)
        $buildedAd['id'] && $buildedAd['id'] < 101 ?
            $formatted_ad['id'] = $buildedAd['id'] : $formatted_ad['id'] = null;

        // limit to 100 chars
        $formatted_ad['title']      = substr($buildedAd['title'], 0, 100);

        // limit to 500 chars
        $formatted_ad['body']       = substr($buildedAd['body'], 0, 500);

        // limited by self private attribute : not visible by web client
        // TODO : should be force limit to 100 chars ?
        $formatted_ad['vertical']   = $buildedAd['vertical'];

        // limit to 100 chars
        $formatted_ad['city']       = substr($buildedAd['city'], 0, 100);

        // limited by self private attribute as a boolean according to Api rules
        // not visible by web client
        $formatted_ad['pro_ad']     = $buildedAd['pro_ad'];

        // limit to 5 numbers
        $buildedAd['zip_code'] ? $formatted_ad['zip_code'] = substr($buildedAd['zip_code'], 0, 4) : '';


        // get only the first 10 images from $buildedAd['images'] array
        count( $buildedAd['images'] ) > 0 ?
            ( $formatted_ad['images'] = array_slice($buildedAd['images'], 0, 10) ) : '';

        //var_dump($formatted_ad);

        return $formatted_ad;
    }

    /**
     * TODO
     * Just build body ad ?
     */
    public function buildDescription(array $ad): array
    {
        
        $buildedAd = array();
        
        // generate unique ID as this is a required field according to Api rules
        $buildedAd['id']         =  $ad['id'] ?? $this->getID();

        // get title :string
        $buildedAd['title']      = $ad['title'];

        // concat description_poste & description_entreprise : string
        $body = $ad['description_poste'] . $ad['description_entreprise'];
        $buildedAd['body']       = $body;

        // get vertical : string
        $buildedAd['vertical']   = self::$vertical;

        // get location_city : string
        $buildedAd['city']       = $ad['location_city'];

        // get pro_ad boolean
        $buildedAd['pro_ad']     = self::$pro_ad;

        // TODO: get zip code from geolocation API webservice as Google Maps or openStreetMap ?
        // TODO = is done ? 'Nice :-)' : 'improve geolocation hook'
        /**
         * instance of GeolocationHook
         * get zip code from openStreetMap API webservice => less reliable than Google Map
         * sometimes missing the zip code : ex. "location_city=Metz&location_state=57"
         * not returning any postcode from openStreetMap
         */
        $geo = new GeolocationHook();
        $buildedAd['zip_code'] = $geo->getZipCode( $ad['location_city'], $ad['location_state'] );

        // format pictures string list to array according to Api rules
        $picturesString = trim($ad['pictures']);
        $picturesFormat = str_replace(" ", "\n", $picturesString);
        $picturesArray = explode("\n", $picturesFormat);
        $buildedAd['images'] = $this->trimArray($picturesArray);

        // not expected by the Api and not in XML file but documented as a required field ?
        // TODO : check this with my team / team Lead
        // $buildedAd['contract']   = $ad['time_type'];

        //var_dump($ad['id']);

        return $buildedAd;
        
    }


    /**
     * return unique id
     * @return int
     * TODO: duplicate in RealEstateHook => service ?
     */
    function getID(): int
    {
        $this->id++;

        return $this->id;

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
