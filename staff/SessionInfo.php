<?php

ini_set('max_execution_time', 0);
set_time_limit(0);
ini_set('memory_limit','-1');
ini_set('display_errors',1);
error_reporting(E_ALL);
session_start();
include_once '../config/database.php';

//Current Active Staff Id
$ActiveStaffLogin_Id = $_SESSION['schoolzone']['ActiveStaffLogin_Id'];

//Section Id
$SectionMaster_Id = $_SESSION['schoolzone']['SectionMaster_Id'];

//Academic Year Id
$Acadmic_Year_ID = $Acadmic_Year_ID;

?>