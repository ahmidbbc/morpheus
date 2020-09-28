<?php

namespace App\Converter;

use DOMDocument;
use DOMElement;

class XMLConverter
{

    /**
     * @param string $filepath
     * @return array
     */
    public static function xmlToArray(string $filepath): array
    {
        // array of jobs to return
        $jobs = array();

        /**
         * instanciate DOMDocument
         * then load xml file content
         */
        $doc = new DOMDocument('1.0', 'utf-8');
        $load = $doc->load($filepath);

        // Another way ?
        /* $xmlFile = $filepath;
         $xmlFileData = file_get_contents($xmlFile);
         $xmlDataObject = simplexml_load_string($xmlFileData);
         $jsonData = json_encode($xmlDataObject);
         $associateArray = json_decode($jsonData, true);
        */

        /**
         * check if load success and not empty file
         */
        if ($load != false && $doc->childNodes->length > 0) {

            //get all jobs
            foreach ($doc->getElementsByTagName('job') as $item) {

                //job array to push into jobs array
                $job = array();

                // unique id for each job to pass to formatted_ads
                $job['id'] = uniqid();

                //job nodes
                foreach ($item->childNodes as $el) {

                    // this node content
                    $job[$el->nodeName] = trim($el->textContent);

                }

                // push every job into jobs array
                array_push($jobs, $job);
            }

        }

        //var_dump($jobs);

        return $jobs;
    }

}
