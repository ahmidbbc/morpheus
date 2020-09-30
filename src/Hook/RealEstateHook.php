<?php
// src/Hook/RealEstateHook.php
namespace App\Hook;

class RealEstateHook
{

    private int $id                 = 0;
    private static string $vertical = 'real_estate';
    private static bool $pro_ad     = true;

    private const CATEGORIE_VENTE = 1;
    private const CATEGORIE_LOCATION = 2;
    private const CATEGORIE_COLOCATION = 3;
    private const CATEGORIE_BUREAUX_ET_COMMERCES = 4;

    private const TYPE_VENTE = "1";
    private const TYPE_LOCATION = "2"; // unused
    private const TYPE_VIAGER = "3"; // unused

    public function formatAd(array $ad): array
    {

        //vars
        $formatted_ad = []; //not required initialization just for understanding
        $buildedAd = $this->buildDescription($ad);

        /**
         * API rules validation
         */

        $formatted_ad['id']         = $buildedAd['id'];

        // limit to 100 chars
        $formatted_ad['title']      = substr($buildedAd['title'], 0, 100);

        // limit to 500 chars
        $formatted_ad['body']       = substr($buildedAd['body'], 0, 500);

        // limited by self private attribute : not visible by web client
        // TODO : should be force limit to 100 chars ?
        $formatted_ad['vertical']   = $buildedAd['vertical'];

        // no limit price
        $formatted_ad['price']      = $buildedAd['price'];

        // limit to 100 chars
        $formatted_ad['city']       = substr($buildedAd['city'], 0, 100);

        // limited by self private attribute as a boolean according to Api rules
        // not visible by web client
        $formatted_ad['pro_ad']     = $buildedAd['pro_ad'];

        // send zip code only if not empty string returned by GeolocationHook
        !empty($buildedAd['zip_code']) ?
            $formatted_ad['zip_code'] = substr($buildedAd['zip_code'], 0, 5) : '';

        // get only the first 10 images from $buildedAd['images'] array
        isset($buildedAd['images']) && count( $buildedAd['images'] ) > 0 ?
            ( $formatted_ad['images'] = array_slice($buildedAd['images'], 0, 10) ) : '';

        $buildedAd['category'] ? $formatted_ad['category']     = $buildedAd['category'] : '';

        isset($buildedAd['type']) ? $formatted_ad['type'] = $buildedAd['type'] : '';


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
        $buildedAd['id']         = $this->getID();

        // get title :string
        $buildedAd['title']      = filter_var( $ad['titre'], FILTER_SANITIZE_STRING );

        // limit to 500 according to Api rules
        $buildedAd['body']       = filter_var( $ad['description'], FILTER_SANITIZE_STRING );

        // get vertical : string
        $buildedAd['vertical']   = self::$vertical;

        // setting and transtyping string to int only if positive numbers
        (int) $ad['prix'] > 0 && preg_match('/^[0-9]*$/', $ad['prix']) ?
            $buildedAd['price'] = (int) filter_var( $ad['prix'], FILTER_SANITIZE_STRING ) : '';

        // limit to 100 according to Api rules
        $buildedAd['city']       = filter_var( $ad['ville'], FILTER_SANITIZE_STRING );

        // get pro_ad boolean
        $buildedAd['pro_ad']     = self::$pro_ad;

        // check if zip code is > 0 and is numbers
        strlen( $ad['code_postal'] ) > 0 && preg_match('/^[0-9]*$/', $ad['code_postal']) ?
            $buildedAd['zip_code']   = filter_var( $ad['code_postal'], FILTER_SANITIZE_STRING ) : '';

        // get images array if not empty
        count( $ad['photos'] ) > 0
            ? $buildedAd['images'] = $this->sanitizeArray($ad['photos'])
            : '';

        // check category $ad['categorie'] value and set corresponding const int (enum) according to Api rules
        // equivalent to !empty( $ad['categorie'] )
        switch (strtolower($ad['categorie'])){
            case 'vente':
                $buildedAd['category'] = self::CATEGORIE_VENTE;
                break;
            case 'location':
                $buildedAd['category'] = self::CATEGORIE_LOCATION;
                break;
            case 'colocation':
                $buildedAd['category'] = self::CATEGORIE_COLOCATION;
                break;
            case 'bureaux et commerces':
                $buildedAd['category'] = self::CATEGORIE_BUREAUX_ET_COMMERCES;
                break;
            default:
                $buildedAd['category'] = 0;
                break;
        }

        /*switch ($buildedAd['category']){
            case 1:
                $buildedAd['type'] = self::TYPE_VENTE || self::TYPE_VIAGER; ??
            case 4:
                $buildedAd['type'] = self::TYPE_VENTE;
                break;
            case 3:
                $buildedAd['type'] = self::TYPE_LOCATION;
                break;
            default:
                break;
        }*/

        // only type for category 4 is required so just testing this one
        // equivalent to if($buildedAd['category']) { $buildedAd['type'] = $ad['type'] }
        $buildedAd['category'] == 4 && $ad['type'] ? $buildedAd['type'] = self::TYPE_VENTE : '';

        //var_dump($ad);

        return $buildedAd;

    }

    /**
     * return unique id
     * @return int
     * TODO: duplicate in JobHook => service ?
     */
    public function getID(): int
    {

        $this->id++;

        return $this->id;

    }

    /**
     * Function to sanitize all elements of an array
     * TODO : duplicate -> service ?
     * @param array $array
     * @return array
     */
    public function sanitizeArray(array $array): array
    {

        $sanitizedArray = array();

        foreach ($array as $el){

            switch (gettype($el)){
                case 'int':
                    filter_var($el, FILTER_SANITIZE_NUMBER_INT);
                    break;
                case 'string':
                    filter_var($el, FILTER_SANITIZE_STRING);
                    break;
                // can implement all sanitize type
            }

            array_push($sanitizedArray, $el);
        }

        //var_dump($sanitizedArray);

        return $sanitizedArray;

    }

}
