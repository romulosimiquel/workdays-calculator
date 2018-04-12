<?php
/**

     * <b>somar_dias_uteis</b>

     * This method adds workdays to a given date, considering Holidays, Saturdays and Sundays 

     * it can recieve a array of Holidays date to be calculated (days wich will be not be counted as working day)

     * in case of this array not beeing seted, the function dias_feriados() will return a array with national days (Brazillian holidays for standard)

     * then do the calc considering it

     * @param Date $str_data = use formats dd/mm/yyyy or aaaa-mm-dd

     * @param Int $int_qtd_dias_somar = number of workdays to be added

     * @param Array $feriados = holidays array (optional)

     * @return Date on format dd/mm/yyyy (EU standard)

     */

    function somar_dias_uteis($str_data,$int_qtd_dias_somar = 7, $feriados = null) 

    {

      

        //Function that returns all of holidays of the year

        function dias_feriados($ano = null)

        {

            if ($ano === null)

            {

              $ano = intval(date('Y'));

            }



            $pascoa     = easter_date($ano); // Limit, dates before 1970 and after 2037 will return a message from easter_date PHP see http://php.net/manual/en/function.easter-date.php

            $dia_pascoa = date('j', $pascoa);

            $mes_pascoa = date('n', $pascoa);

            $ano_pascoa = date('Y', $pascoa);



            $feriados = array(

              // National holidays on fixed date (National Brazilians holidays)

              mktime(0, 0, 0, 1,  1,   $ano), // Confraternização Universal - Lei nº 662, de 06/04/49
                
              mktime(0, 0, 0, 1,  1,   ($ano + 1)), // Confraternização Universal - Lei nº 662, de 06/04/49

              mktime(0, 0, 0, 4,  21,  $ano), // Tiradentes - Lei nº 662, de 06/04/49

              mktime(0, 0, 0, 5,  1,   $ano), // Dia do Trabalhador - Lei nº 662, de 06/04/49

              mktime(0, 0, 0, 9,  7,   $ano), // Dia da Independência - Lei nº 662, de 06/04/49

              mktime(0, 0, 0, 10,  12, $ano), // N. S. Aparecida - Lei nº 6802, de 30/06/80

              mktime(0, 0, 0, 11,  2,  $ano), // Todos os santos - Lei nº 662, de 06/04/49

              mktime(0, 0, 0, 11, 15,  $ano), // Proclamação da republica - Lei nº 662, de 06/04/49

              mktime(0, 0, 0, 12, 25,  $ano), // Natal - Lei nº 662, de 06/04/49



              // These days have a date depending on easter

              mktime(0, 0, 0, $mes_pascoa, $dia_pascoa - 48,  $ano_pascoa),//2ºferia Carnaval

              mktime(0, 0, 0, $mes_pascoa, $dia_pascoa - 47,  $ano_pascoa),//3ºferia Carnaval	

              mktime(0, 0, 0, $mes_pascoa, $dia_pascoa - 2 ,  $ano_pascoa),//6ºfeira Santa  

              mktime(0, 0, 0, $mes_pascoa, $dia_pascoa     ,  $ano_pascoa),//Pascoa

              mktime(0, 0, 0, $mes_pascoa, $dia_pascoa + 60,  $ano_pascoa),//Corpus Cirist

            );



            sort($feriados);



            return $feriados;

        }



        

        if(empty($feriados))

        {

            $feriados = array();



            $ano_=date("Y");// $ano_='2010'; 

            foreach(dias_feriados($ano_) as $a)

            {

                $feriados[] =  strtotime(date("m/d/Y",$a));						 

            }

        }

        

        

        // If is informed a MySQL date type DATETIME - yyyy-mm-dd 00:00:00

        // Transforms to DATE - yyyy-mm-dd

        $str_data = substr($str_data,0,10);

        

        // If the date is passed on EU standard: dd/mm/yyyy

        // It will be changed to this format: yyyy-mm-dd

        if ( preg_match("@/@",$str_data) == 1 )

        {

            $str_data = implode("-", array_reverse(explode("/",$str_data)));

        }

        

        

        $array_data = explode('-', $str_data);

        $count_days = 0;

        $int_qtd_dias_uteis = 0;

        while ( $int_qtd_dias_uteis < $int_qtd_dias_somar )

        {

            $count_days++;

            if ( ( $dias_da_semana = gmdate('w', strtotime('+'.$count_days.' day', mktime(0, 0, 0, $array_data[1], $array_data[2], $array_data[0]))) ) != '0' && $dias_da_semana != '6' && !in_array(strtotime('+'.$count_days.' day', mktime(0, 0, 0, $array_data[1], $array_data[2], $array_data[0])),$feriados))

            {

               $int_qtd_dias_uteis++;

            }       

        }

        return gmdate('d/m/Y',strtotime('+'.$count_days.' day',strtotime($str_data)));

    }
