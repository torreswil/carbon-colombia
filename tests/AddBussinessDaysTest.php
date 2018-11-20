<?php

use torreswil\CarbonColombia;


/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 19/11/2018
 * Time: 4:57 PM
 */

class AddBussinessDaysTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Test that true does in fact equal true
     */
    public function testSemanaSanta()
    {
        $fecha = CarbonColombia::create(2019,04,16);

        $fecha->addBussinessDays(5);

        $this->assertEquals('2019-04-25',$fecha->toDateString());
    }

    /**
     * Test that true does in fact equal true
     */
    public function testAnoNuevo()
    {
        $fecha = CarbonColombia::create(2018,12,28);

        $fecha->addBussinessDays(10);

        $this->assertEquals('2019-01-15',$fecha->toDateString());
    }
}