<?php

namespace App\Tests;

use PHPUnit\Framework\TestCase;
use App\Hook\RealEstateHook;
use App\Converter\JsonConverter;

class RealEstateHookTest extends TestCase
{

    /**
     * Function to test if Tests well implemented
     */
    public function testTests(){
        $this->assertEquals(2, 1+1);
    }

    /**
     * Function to test if RealEstateHook::formatAd return an array
     */
    public function testFormatAdIsArray()
    {
        $array = [
            'id' => 1,
            'titre' => 'test',
            'description' => 'no limit',
            'prix' => '200',
            'ville' => 'Here',
            'code_postal' => '2092099',
            'photos' => [],
            'categorie' => 'animals',
            'type' => 'felins',
        ];

        $realEstateHook = new RealEstateHook();
        $expected = $realEstateHook->formatAd($array);
        $this->assertIsArray($expected);
    }


    /**
     * Function to test if RealEstateHook::getID return an int
     */
    public function testGetIDIsInt()
    {
        $realEstateHook = new RealEstateHook();
        $expected = $realEstateHook->getID();
        $this->assertIsInt($expected);
    }


    /*
    public function testFormatAdAssertsValidation()
    {
        //...
    }


    public function testFormatAdNotEmptyField()
    {
        //...
    }

    public function testFormatAdRequiredFields()
    {
        //...
    }*/




    // others test methods

}
