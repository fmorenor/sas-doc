<?php

    function getToday(){        
        return date('Y-m-d');
    }

    function getMes($mes){
        switch($mes){
            case '01': $ret = "Enero"; break;
            case '02': $ret = "Febrero"; break;
            case '03': $ret = "Marzo"; break;
            case '04': $ret = "Abril"; break;
            case '05': $ret = "Mayo"; break;
            case '06': $ret = "Junio"; break;
            case '07': $ret = "Julio"; break;
            case '08': $ret = "Agosto"; break;
            case '09': $ret = "Septiembre"; break;
            case '10': $ret = "Octubre"; break;
            case '11': $ret = "Noviembre"; break;
            case '12': $ret = "Diciembre"; break;
        }
        return $ret;
    }

?>