<?php

namespace App\Tests;

use PHPUnit\Framework\TestCase;
use App\Hook\JobHook;

class JobHookTest extends TestCase
{

    /**
     * Function to test if RealEstateHook::formatAd return an array
     */
    public function testFormatAdIsArray()
    {
        $array = [
            'id' => 1,
            'title' => 'test',
            'location_city' => 'Los Angeles',
            'location_state' => 'Los Angeles',
            'description_poste' => 'The job of your dream !',
            'description_entreprise' => 'The best place to work',
            'pictures' => 'chat.png', 'cheval.png', 'renard.png',
            'zip_code' => '91000',
        ];

        $realEstateHook = new JobHook();
        $expected = $realEstateHook->formatAd($array);
        $this->assertIsArray($expected);
    }

    /**
     * Function to test if RealEstateHook::getID return an int
     */
    public function testGetIDIsInt()
    {
        $realEstateHook = new JobHook();
        $expected = $realEstateHook->getID();
        $this->assertIsInt($expected);
    }


    // others test methods
}
