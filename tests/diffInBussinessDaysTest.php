<?php

use torreswil\CarbonColombia;


class diffInBussinessDaysTest extends \PHPUnit\Framework\TestCase
{
    public function testSemanaSanta()
    {
        $fecha = CarbonColombia::create(2019,04,16);

        $fecha2 = CarbonColombia::create(2019,04,25);

        $dias_diferencia = $fecha->diffInBussinessDays($fecha2);

        $this->assertEquals(5,$dias_diferencia);
    }

    /**
     * Test that true does in fact equal true
     */
    public function testAnoNuevo()
    {
        $fecha = CarbonColombia::create(2018,12,28);

        $fecha2 = CarbonColombia::create(2019,01,15);

        $dias_diferencia = $fecha->diffInBussinessDays($fecha2);

        $this->assertEquals(10,$dias_diferencia);
    }

    public function testSemanaSantaInvertido()
    {
        $fecha2 = CarbonColombia::create(2019,04,16);

        $fecha = CarbonColombia::create(2019,04,25);

        $dias_diferencia = $fecha->diffInBussinessDays($fecha2,false);

        $this->assertEquals(-5,$dias_diferencia);
    }

    /**
     * Test that true does in fact equal true
     */
    public function testAnoNuevoInvertido()
    {
        $fecha2 = CarbonColombia::create(2019,11,1);

        $fecha = CarbonColombia::create(2019,11,18);

        $dias_diferencia = $fecha->holidaysBetween($fecha2);

        $this->assertEquals(2,$dias_diferencia);
    }


    public function testHolidaysBetweenDates()
    {
        $fecha2 = CarbonColombia::create(2019,12,1);

        $fecha = CarbonColombia::create(2019,12,31);

        $dias_diferencia = $fecha->holidaysBetween($fecha2);

        $this->assertEquals(1,$dias_diferencia);
    }




}