<?php

namespace App\Command;

use App\Converter\XMLConverter;
use App\Hook\JobHook;
use App\Validator\Api;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class JobExecutor extends Command
{
    protected function configure()
    {
        $this->setName('job-executor');
        $this->formatter = new JobHook();
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $formatted_ads = [];
        $filepath = 'data/job.xml';
        $ads           = XMLConverter::xmlToArray($filepath);

        foreach ($ads as $ad) {

            // format and send ads
            $jobHook = new JobHook();
            $formatted_ads[$ad['id']] = $jobHook->formatAd($ad);


            $api = new Api();
            $api->send($formatted_ads[$ad['id']], $formatted_ads[$ad['id']]['vertical']);
        }

        print_r($formatted_ads);

        return 0;
    }
}
