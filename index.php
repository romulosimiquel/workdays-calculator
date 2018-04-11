<?php

function somar_dias_uteis($str_data,$int_qtd_dias_somar = 7, $feriados = null) {

    // Caso seja informado uma data do MySQL do tipo DATETIME - aaaa-mm-dd 00:00:00
    // Transforma para DATE - aaaa-mm-dd
    $str_data = substr($str_data,0,10);
    // Se a data estiver no formato brasileiro: dd/mm/aaaa
    // Converte-a para o padrão americano: aaaa-mm-dd
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

$feriados = array(strtotime("05/01/2018"), strtotime("09/07/2018"));

echo somar_dias_uteis('2018-04-10', 15, $feriados);