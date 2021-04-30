<?php

namespace datavista_lib;

ini_set('max_execution_time', 0);
set_time_limit(0);
ini_set('memory_limit','-1');
ini_set('display_errors',1);
error_reporting(E_ALL);

require_once("master.php");

class Formaux extends master
{
private $class;
private $pid;
private $id;
private $button;
private $heading;
private $parent;
private $form_id;
private $elements;
private $sub;
public $struct='';
	function __construct($class,$pid,$id,$button=false,$heading,$parent,$form_id,$elements,$sub)
	{
		$this->class=$class;
		$this->pid=$pid;
		$this->id=$id;
		$this->button=$button;
		$this->parent=$parent;
		$this->heading=$heading;
		$this->form_id=$form_id;
		$this->elements=$elements;
		$this->sub=$sub;
		
	}
function init(){
				$this->struct.='<div class="panel panel-default '.$this->class.'" id="'.$this->pid.'"><div class="panel-heading" role="tab" id="'.$this->id.'"><h4 class="panel-title">';
				if($this->button!=false)
				{
					$this->struct.='<button type="button" class="close '.$this->button[0].'"><span class="glyphicon '.$this->button[1].'" aria-hidden="true" style="color:'.$this->button[2].';"></span></button>';
				}
				$this->struct.='<a role="button" data-toggle="collapse" data-parent="#'.$this->parent.'" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                    '.$this->heading.'</a></h4></div><div id="collapseOne" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="'.$this->heading.'">
                    <form id="'.$this->form_id.'" enctype="multipart/form-data"><div class="panel-body">
                    <div class="form-group">';
                foreach($this->elements as $ele)
                { 
                   	$this->struct.='<div>';
                    if($ele[0]!='')
                    {
                	$this->struct.='<label for="'.$ele[1][1][0].'">'.$ele[0].'</label>';
                    }
                    $this->struct.=parent::input($ele[1]);
                	$this->struct.='</div>';

                }
                if($this->sub[0]!='')
                {
                $this->struct.='<div class="panel-footer"><input type="submit" name="'.$this->sub[0].'" value="'.$this->sub[1].'" id="'.$this->sub[2].'" class="btn btn-primary"></div>';
                }
                $this->struct.="</div></div></form></div></div><style>
                         img {
                            border: 1px solid #ddd; 
                            border-radius: 4px;  
                            padding: 5px; 
                            width: 40px; 
                            }
                        img:hover
                        {
                             box-shadow: 0 0 2px 1px rgba(0, 140, 186, 0.5);
                        }</style>";

                return $this->struct;
                }

}

?>