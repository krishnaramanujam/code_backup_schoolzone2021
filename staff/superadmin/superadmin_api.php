<?php
ini_set('max_execution_time', 0);
set_time_limit(0);
ini_set('memory_limit','-1');
ini_set('display_errors',1);
error_reporting(E_ALL);
session_start();
date_default_timezone_set("Asia/Calcutta");
include_once '../../config/database.php';


use Box\Spout\Reader\ReaderFactory;
use Box\Spout\Common\Type;

require_once '../../assets/plugins/spout-2.4.3/src/Spout/Autoloader/autoload.php';

?>