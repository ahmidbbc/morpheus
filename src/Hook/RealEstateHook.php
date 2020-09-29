<?php
// src/Hook/RealEstateHook.php
namespace App\Hook;

class RealEstateHook
{
    private static string $vertical = 'real_estate';
    private static bool $pro_ad     = true;

    public function formatAd(array $ad): array
    {

        $formatted_ad = []; //not required initialization just for understanding

        // as ID is a required field? return $ad['id'] if not (null, empty, or false)
        // else generate unique ID, (:? return false if empty as ?? return empty string)
        $formatted_ad['id']         = $ad['id'] ?: $this->getID();
        // limit to 100 according to Api rules
        $formatted_ad['title']      = substr($ad['titre'], 0, 100);
        // limit to 500 according to Api rules
        $formatted_ad['body']       = substr($ad['description'], 0, 500);
        $formatted_ad['vertical']   = self::$vertical;
        // transtyping string to int
        $formatted_ad['price']      = (int) $ad['prix'];
        $formatted_ad['city']       = $ad['ville'];
        $formatted_ad['zip_code']   = $ad['code_postal'];
        $formatted_ad['pro_ad']     = self::$pro_ad;
        $formatted_ad['images']     = $ad['photos'];
        // equivalent to !empty($ad['categorie'])
        $formatted_ad['category']   = $ad['categorie'] ? 4 : 0;
        // equivalent to if($formatted_ad['category']) { $formatted_ad['type'] = $ad['type'] }
        $formatted_ad['category'] ? $formatted_ad['type'] = $ad['type'] : '';

        //var_dump($formatted_ad);

        return $formatted_ad;

    }

    public function buildDescription()
    {
        //test ?????????????
        // ...
    }

    /**
     * return unique id
     * @return int
     * TODO: duplicate => service ?
     */
    function getID(): int
    {

        return (int) (time().mt_rand());

    }
}
