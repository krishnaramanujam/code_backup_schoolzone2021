<?php

namespace datavista_lib;

ini_set('max_execution_time', 0);
set_time_limit(0);
ini_set('memory_limit','-1');
ini_set('display_errors',1);
error_reporting(E_ALL);


require_once("master.php");

class Formaub extends master
{
	private $struc;
	private $title;
	private $form_id;
	private $element;
	function __construct($title,$form_id,$element)
	{
		$this->title=$title;
		$this->form_id=$form_id;
		$this->element=$element;
	}
	function init()
	{
		$this->struc.="<div class='box'>
            <div class='box-header'>
            <h3 align=center class='box-title'>".$this->title."</h3>
            </div>
            <div class='box-body'>
        	<form id='".$this->form_id."'>";
        $i=1;
        
        foreach($this->element as $j=>$ele)
        {

        	if($i==1)
        	{
        		$this->struc.="<div class='row' style='padding:5px'>";
        	}
        	elseif($i==6)
        	{
        		$this->struc.="</div>";
        		$i=1;
        	}

        	if($ele[0]!='')
        	{
        	$this->struc.="<label style='text-align:right' for='".$ele[1][1][0]."' class='col col-lg-2'>".$ele[0]."</label>";
        	$i=$i+1;
        	}
        	$this->struc.="<div class='col col-lg-2' style='padding:5px'>";
        	$this->struc.=parent::input($ele[1]);
        	$this->struc.="</div>";
        	$i=$i+1;

        }
        $this->struc.="</form></div></div>";
        return $this->struc;
	}
}

?>