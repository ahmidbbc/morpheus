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

        $formatted_ad['id']         = $ad['id'];
        $formatted_ad['title']      = $ad['titre'];
        $formatted_ad['body']       = $ad['description'];
        $formatted_ad['vertical']   = self::$vertical;
        $formatted_ad['price']      = (int) $ad['prix'];
        $formatted_ad['city']       = $ad['ville'];
        $formatted_ad['zip_code']   = $ad['code_postal'];
        $formatted_ad['pro_ad']     = self::$pro_ad;
        $formatted_ad['images']     = $ad['photos'];
        $formatted_ad['category']   = (int) $ad['categorie'];
        //var_dump($formatted_ad);

        return $formatted_ad;

    }

    public function buildDescription()
    {
        //test ?????????????
        // ...
    }
}
