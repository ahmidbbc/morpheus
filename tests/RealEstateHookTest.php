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
            'photos' => ['hello.png'],
            'categorie' => 'vente'
        ];

        $realEstateHook = new RealEstateHook();
        $expected = $realEstateHook->formatAd($array);
        $this->assertIsArray($expected);
    }

    /**
     * Function to test if empty images array is not returning error from Api
     */
    public function testFormatAdImagesArrayIsEmpty()
    {
        $array = [
            'id' => 1,
            'titre' => 'test',
            'description' => 'no limit',
            'prix' => '200',
            'ville' => 'Here',
            'code_postal' => '2092099',
            'photos' => [],
            'categorie' => '',
            'type' => 'maison'
        ];

        $realEstateHook = new RealEstateHook();
        $expected = $realEstateHook->formatAd($array);
        $this->assertIsArray($expected);
    }

    /**
     * Function to test if type field value is returned when category is 4
     */
    public function testFormatAdWhenCategoryIsFour()
    {
        $array = [
            'id' => 1,
            'titre' => 'test',
            'description' => 'no limit',
            'prix' => '200',
            'ville' => 'Here',
            'code_postal' => '2092099',
            'photos' => [],
            'categorie' => 'bureaux et commerces',
            'type' => 'maison'
        ];

        $realEstateHook = new RealEstateHook();
        $result = $realEstateHook->formatAd($array);
        $expected = 1;
        $this->assertEquals($expected, $result['type']);
    }

    /**
     * Function to test if RealEstateHook::buildDescription return an array
     */
    public function testBuildDescriptionIsArray()
    {

        $array = [
            'id' => 1,
            'titre' => 'test',
            'description' => 'no limit',
            'prix' => '200',
            'ville' => 'Here',
            'code_postal' => '2092099',
            'photos' => [],
            'categorie' => 'location',
        ];

        $realEstateHook = new RealEstateHook();
        $expected = $realEstateHook->buildDescription($array);
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
    }

     public function testGetIDIsUnique()
    {
        //...
    }
    */

    // others test methods...

}
