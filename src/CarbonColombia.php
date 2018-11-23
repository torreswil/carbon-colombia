<?php

namespace torreswil;

use Carbon\Carbon;

/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 19/11/2018
 * Time: 6:04 PM
 */

class CarbonColombia extends Carbon
{


    protected $year_holidays = [];

    protected $easter_month;
    protected $easter_day;

    public function __construct($time = null, $tz = null)
    {
        parent::__construct($time, $tz);
        $this->setYearHolidays();

    }

    public function addBussinessDays($days)
    {
        while ($days)
        {
            $this->addWeekday();

            if($this->isHoliday()) continue;

            $days--;
        }
    }

    public function diffInBussinessDays($date = null, $absolute = true)
    {
        $factor = ($this->greaterThan($date) && !$absolute) ? -1 : 1;


        $week_days = $this->diffInWeekdays($date);

        $holidays = $this->holidaysBetween($date);

        $days = $week_days - $holidays;

        return $days * $factor;

    }

    public function holidaysBetween($date){
        $oldest_date = $this->greaterThan($date) ? $date : $this;
        $newer_date = $this->greaterThan($date) ? $this : $date;

        $holidays = 0;

        while ($newer_date->greaterThan($oldest_date))
        {
            $oldest_date->addWeekday();

            if($oldest_date->isHoliday()) $holidays++;

        }

        return $holidays;

    }

    public function getYearHolidays(){
        return $this->year_holidays;
    }

    protected function setYearHolidays()
    {
        $this->year_holidays = ['01-01','05-01','07-20','08-07','12-08','12-25'];

        $this->easter_month = date('m',easter_date($this->year));
        $this->easter_day = date('d',easter_date($this->year));
        $this->calcula_emiliani(1, 6);				// Reyes Magos Enero 6
        $this->calcula_emiliani(3, 19);				// San Jose Marzo 19
        $this->calcula_emiliani(6, 29);				// San Pedro y San Pablo Junio 29
        $this->calcula_emiliani(8, 15);				// Asunción Agosto 15
        $this->calcula_emiliani(10, 12);			// Descubrimiento de América Oct 12
        $this->calcula_emiliani(11, 1);				// Todos los santos Nov 1
        $this->calcula_emiliani(11, 11);

        $this->pascuas(-3);			//jueves santo
        $this->pascuas(-2);			//viernes santo

        $this->pascuas(36,true);		//Ascención el Señor pascua
        $this->pascuas(60,true);		//Corpus Cristi
        $this->pascuas(68,true);		//Sagrado Corazón

    }

    private function calcula_emiliani($mes_festivo,$holiday)
    {
        // funcion que mueve una fecha diferente a lunes al siguiente lunes en el
        // calendario y se aplica a fechas que estan bajo la ley emiliani
        //global  $y,$holiday,$mes_festivo,$festivo;
        // Extrae el dia de la semana
        // 0 Domingo … 6 Sábado
        $dd = date("w",mktime(0,0,0,$mes_festivo,$holiday,$this->year));
        switch ($dd) {
            case 0:                                    // Domingo
                $holiday = $holiday + 1;
                break;
            case 2:                                    // Martes.
                $holiday = $holiday + 6;
                break;
            case 3:                                    // Miércoles
                $holiday = $holiday + 5;
                break;
            case 4:                                     // Jueves
                $holiday = $holiday + 4;
                break;
            case 5:                                     // Viernes
                $holiday = $holiday + 3;
                break;
            case 6:                                     // Sábado
                $holiday = $holiday + 2;
                break;
        }
        $fest = date("m-d", mktime(0,0,0,$mes_festivo,$holiday,$this->year));
        array_push($this->year_holidays,$fest);

    }

    private function pascuas($cantidadDias=0,$siguienteLunes=false)
    {
        $mes_festivo = date("n", mktime(0,0,0,$this->easter_month,$this->easter_day+$cantidadDias,$this->year));
        $holiday = date("d", mktime(0,0,0,$this->easter_month,$this->easter_day+$cantidadDias,$this->year));

        if ($siguienteLunes)
        {
            $this->calcula_emiliani($mes_festivo, $holiday);
        }
        else
        {
            $fest = date("m-d", mktime(0,0,0,$mes_festivo,$holiday,$this->year));
            array_push($this->year_holidays, $fest);
        }
    }

    public function isHoliday()
    {
        $this->getYearHolidays();
        return in_array($this->format('m-d'),$this->year_holidays);
    }
}