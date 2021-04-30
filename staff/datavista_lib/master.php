<?php

namespace datavista_lib;

ini_set('max_execution_time', 0);
set_time_limit(0);
ini_set('memory_limit','-1');
ini_set('display_errors',1);
error_reporting(E_ALL);


class master
{
	function input($ele)
	{
	$struc="";
	switch($ele[0])
        {

            case 'hid':
            $struc.='<input type="hidden" class="form-control" id="'.$ele[1][0].'" name="'.$ele[1][1].'" placeholder="'.$ele[1][2].'">';
            break;
            case 'fil':
            $struc.='<input type="file" id="'.$ele[1][0].'" name="'.$ele[1][1].'"  placeholder="'.$ele[1][2].'">';
            break;
            case 'inp':
            $struc.='<input class="form-control" id="'.$ele[1][0].'"  name="'.$ele[1][1].'" placeholder="'.$ele[1][2].'">';
            break;
            case 'sel':
            $struc.='
            <select class="form-control" id="'.$ele[1][0].'" name="'.$ele[1][1].'" placeholder="'.$ele[1][2].'">
            <option disabled selected value> Select value </option>';
            foreach($ele[1][3] as $ex)
            { 
                $struc.='<option value ="'.$ex[0].'">'.$ex[1].'</option>';
            }
            $struc.='</select>';
            break;
            case 'tbn':
            $struc.='<a><img id="'.$ele[1][0].'" src="'.$ele[1][1].'"></a>';
            break;
            case 'chk':
            foreach ($ele[1] as $ck) {
                $struc.='<input type="checkbox" id="'.$ck[0].'" name="'.$ck[1].'" value="'.$ck[2].'">
                <label for="'.$ck[0].'">'.$ck[3].'</label><br/>';
            }
            break;
            case 'dt':
            $struc.='<input type="datetime-local" id="'.$ele[1][0].'" name="'.$ele[1][1].'" class="'.$ele[1][2].'">';
            break;
        }
        return $struc;
	}
}

?>