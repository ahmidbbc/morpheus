<?php

namespace App\Command;

use App\Converter\JsonConverter;
use App\Hook\RealEstateHook;
use App\Validator\Api;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class RealEstateExecutor extends Command
{

    // command to execute (terminal) : "php bin/console real-estate-executor"
    protected function configure()
    {
        $this->setName('real-estate-executor');
        $this->formatter = new RealEstateHook();
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // vars
        $formatted_ads = [];
        $filepath = 'data/real_estate.json';
        $ads      = JsonConverter::jsonToArray($filepath);

        foreach ($ads as $ad) {

            // format and send ads

            /**
             * instance of RealEstateHook
             * format this ad
             * @param $ad: array
             */
            $hook = new RealEstateHook();
            $formatted_ads[$ad['id']] = $hook->formatAd($ad);
            //var_dump($formatted_ads);

            /**
             * instance of Api
             * send this ad
             * @param $input: array
             * @param $vertical: string
             */
            $api = new Api();
            $api->send($formatted_ads[$ad['id']], $formatted_ads[$ad['id']]['vertical']);

        }

        print_r($formatted_ads);

        return 0;
    }

}
