<?php

namespace App\Converter;

class JsonConverter
{

    /**
     * Return a JSON file content as an array
     * @param string $filepath
     * @return array
     */
    public static function jsonToArray(string $filepath): array
    {

        /**
         * get string content from a JSON file
         * @param $filepath : string
         */
        $jsondata = file_get_contents($filepath);

        //var_dump(json_decode($jsondata));

        /**
         * get and return assoc array from string content
         * @param $jsondata : string
         * @param isAssoc : boolean
         */
        return json_decode($jsondata, true);

    }
}
