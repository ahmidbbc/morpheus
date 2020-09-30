<?php
// src/Hook/RealEstateHook.php
namespace App\Hook;

class RealEstateHook
{

    private int $id                 = 0;
    private static string $vertical = 'real_estate';
    private static bool $pro_ad     = true;

    public function formatAd(array $ad): array
    {

        $formatted_ad = []; //not required initialization just for understanding

        // as ID is a required field, return $ad['id'] if not (null, empty, or false)
        // else generate unique ID
        // :? return false if empty as ?? return empty string
        $formatted_ad['id']         = $ad['id'] ?: $this->getID();

        // limit to 100 according to Api rules
        $formatted_ad['title']      = substr($ad['titre'], 0, 100);

        // limit to 500 according to Api rules
        $formatted_ad['body']       = substr($ad['description'], 0, 500);

        // limited by self private attribute (not visible for web client)
        // TODO : should be force limit to 100 chars ?
        $formatted_ad['vertical']   = self::$vertical;

        // transtyping string to int
        (int) $ad['prix'] > 0 ? $formatted_ad['price'] = (int) $ad['prix'] : '';

        // limit to 100 according to Api rules
        $formatted_ad['city']       = substr($ad['ville'], 0, 100);

        // limit to 5 according to Api rules
        strlen( $ad['code_postal'] ) > 0 && strlen ( $ad['code_postal'] ) < 6
            ? $formatted_ad['zip_code']   = $ad['code_postal']
            : $formatted_ad['zip_code']   = substr($ad['code_postal'], 0, 4);

        // limited by self private attribute as a boolean according to Api rules
        // not visible by web client
        $formatted_ad['pro_ad']     = self::$pro_ad;

        // get only the first  10 images from $ad['photos'] array according to to Api rules
        count( $ad['photos'] ) > 0
            ? ( $formatted_ad['images'] = array_slice($ad['photos'], 0, 10) )
            : '';

        // equivalent to !empty($ad['categorie'])
        $formatted_ad['category']   = $ad['categorie'] ? 4 : 0;

        // equivalent to if($formatted_ad['category']) { $formatted_ad['type'] = $ad['type'] }
        $formatted_ad['category'] ? $formatted_ad['type'] = $ad['type'] : '';

        //var_dump($ad['photos']);

        return $formatted_ad;

    }

    /**
     * TODO
     * Just build body ad ?
     */
    public function buildDescription()
    {
        // ...


    }

    /**
     * return unique id
     * @return int
     * TODO: duplicate in JobHook => service ?
     */
    function getID(): int
    {

        $this->id++;
        //$this->id <= 100 ? $this->id++ : $this->id = 100;
        //var_dump($this->id);

        return $this->id;
        //return (int) (time().mt_rand());

    }
}
