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

    // TODO : duplicate code in JobExecutor
    private RealEstateHook $formatter;
    private Api $api;

    // command to execute (terminal) : "php bin/console real-estate-executor"
    protected function configure()
    {
        $this->setName('real-estate-executor');
        $this->formatter = new RealEstateHook();
        $this->api = new Api();
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
            //$hook = new RealEstateHook();
            $id = filter_var($ad['id'], FILTER_SANITIZE_NUMBER_INT);
            $formatted_ads[$id] = $this->formatter->formatAd($ad);

            /**
             * instance of Api
             * send this ad
             * @param $input: array
             * @param $vertical: string
             */
            //$api = new Api();
            $this->api->send($formatted_ads[$id], $formatted_ads[$id]['vertical']);

        }

        print_r($formatted_ads);

        return 0;
    }

}
