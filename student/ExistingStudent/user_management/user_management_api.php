<?php
ini_set('max_execution_time', 0);
set_time_limit(0);
ini_set('memory_limit','-1');
ini_set('display_errors',1);
error_reporting(E_ALL);
session_start();
date_default_timezone_set("Asia/Calcutta");
include_once '../../../config/database_student.php';


use Box\Spout\Reader\ReaderFactory;
use Box\Spout\Common\Type;

require_once '../../../assets/plugins/spout-2.4.3/src/Spout/Autoloader/autoload.php';


//-----------------------------------------------------------------------------------------------------------------------
if(isset($_GET['MenuActivity_Log_Report'])){

    extract($_POST);
    $timeStamp = date("Y-m-d h:i:s");
    

     //Inserting data in comm_message_log
     $Inserting_UserDetails = mysqli_query($mysqli,"Insert into user_activitylog (activityType_Id, user_Id, userType, timeStamp, pagelink_Id) values ('2', '$UserId', '$UserType', '$timeStamp', '$pageId')");
     

	$res['status'] = 'success';
	echo json_encode($res);
    
}
//-----------------------------------------------------------------------------------------------------------------------

?>