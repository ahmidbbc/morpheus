<?php
// src/Hook/JobHook.php
namespace App\Hook;

use App\Converter\XMLConverter;

class JobHook
{

    private static string $vertical = 'job';
    private static bool $pro_ad     = true;

    /**
     * Just format and return the ad according to API request !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
     * @param array $ad
     * @return array
     */
    public function formatAd(array $ad): array
    {
        $formatted_ad = []; //not required initialization just for understanding

        $formatted_ad['id']         = $this->getID();
        $formatted_ad['title']      = substr($ad['title'], 0, 100);
        $formatted_ad['vertical']   = self::$vertical;
        $formatted_ad['city']       = $ad['location_city'];
        //$formatted_ad['zip_code'] = $ad['code_postal']; // TODO: get zip code from geolocation API webservice as GOogle Maps or OpenStreetMap ?
        $formatted_ad['pro_ad']     = self::$pro_ad;
        //$formatted_ad['contract']   = $ad['time_type'];
        //$formatted_ad['salary']   = ''; // optional

        // format body to concat and limit to 500 according to Api rules
        $body = substr($ad['description_poste'] . $ad['description_entreprise'], 0, 500);
        $formatted_ad['body']       = $body;

        // format pictures string list to array according to Api rules
        $picturesString = trim($ad['pictures']);
        $picturesFormat = str_replace(" ", "\n", $picturesString);
        $picturesArray = explode("\n", $picturesFormat);
        $pictures = $this->trimAd($picturesArray);

        //add array of pictures
        $formatted_ad['images']     = $pictures;


        //var_dump($formatted_ad);

        return $formatted_ad;
    }

    /**
     * TODO
     * Just build
     */
    public function buildDescription()
    {



    }


    function getID()
    {

        //return unique id
        return (int) (time().mt_rand());

    }

    /**
     * trim all elements of an array
     * @param array $ad
     * @return array
     */
    public function trimAd(array $ad): array
    {
        $array = array();

        foreach ($ad as $line){
            if(!empty($line))
                array_push($array, trim($line));
        }

        return $array;

    }
}
