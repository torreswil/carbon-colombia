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


    protected $festivos = [];

    protected $mes_pascua;
    protected $dia_pascua;

    public function __construct($time = null, $tz = null)
    {
        parent::__construct($time, $tz);
        $this->calcularFestivos();

    }

    public function addBussinessDays($days)
    {
        while ($days)
        {
            $this->addWeekday();
            print $this->toDateString().' - ';
            if($this->esFestivo()) {
                continue;
            }

            $days--;
        }
    }

    public function getFestivos(){
        return $this->festivos;
    }

    protected function calcularFestivos()
    {
        $this->festivos = ['01-01','05-01','07-20','08-07','12-08','12-25'];

        $this->mes_pascua = date('m',easter_date($this->year));
        $this->dia_pascua = date('d',easter_date($this->year));
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

    private function calcula_emiliani($mes_festivo,$dia_festivo)
    {
        // funcion que mueve una fecha diferente a lunes al siguiente lunes en el
        // calendario y se aplica a fechas que estan bajo la ley emiliani
        //global  $y,$dia_festivo,$mes_festivo,$festivo;
        // Extrae el dia de la semana
        // 0 Domingo … 6 Sábado
        $dd = date("w",mktime(0,0,0,$mes_festivo,$dia_festivo,$this->year));
        switch ($dd) {
            case 0:                                    // Domingo
                $dia_festivo = $dia_festivo + 1;
                break;
            case 2:                                    // Martes.
                $dia_festivo = $dia_festivo + 6;
                break;
            case 3:                                    // Miércoles
                $dia_festivo = $dia_festivo + 5;
                break;
            case 4:                                     // Jueves
                $dia_festivo = $dia_festivo + 4;
                break;
            case 5:                                     // Viernes
                $dia_festivo = $dia_festivo + 3;
                break;
            case 6:                                     // Sábado
                $dia_festivo = $dia_festivo + 2;
                break;
        }
        $fest = date("m-d", mktime(0,0,0,$mes_festivo,$dia_festivo,$this->year));
        array_push($this->festivos,$fest);

    }

    private function pascuas($cantidadDias=0,$siguienteLunes=false)
    {
        $mes_festivo = date("n", mktime(0,0,0,$this->mes_pascua,$this->dia_pascua+$cantidadDias,$this->year));
        $dia_festivo = date("d", mktime(0,0,0,$this->mes_pascua,$this->dia_pascua+$cantidadDias,$this->year));

        if ($siguienteLunes)
        {
            $this->calcula_emiliani($mes_festivo, $dia_festivo);
        }
        else
        {
            $fest = date("m-d", mktime(0,0,0,$mes_festivo,$dia_festivo,$this->year));
            array_push($this->festivos,$fest);
        }
    }

    public function esFestivo()
    {
        $this->calcularFestivos();
        return in_array($this->format('m-d'),$this->festivos);
    }
}