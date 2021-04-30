<?php

spl_autoload_register(function($class)
{
	$s=explode('\\',$class);
	require_once 'datavista_lib/'.$s[1].'.php';
});

?>