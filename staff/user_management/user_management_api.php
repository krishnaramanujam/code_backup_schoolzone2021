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

//-----------------------------------------------------------------------------------------------------------------------
if(isset($_GET['Add_USERInstance'])){
   
    extract($_POST);

        $password = password_hash( $add_password, PASSWORD_DEFAULT );
        $DOB = date('Y-m-d',strtotime(str_replace('/','-',$_POST['add_date_of_birth'])));

        $Inserting_StaffQualification = mysqli_query($mysqli,"Insert into user_stafflogin
        (username, reg_mobile_no, reg_email_address, date_of_birth, staff_type, staff_status, departmentmaster_Id, password) 
        Values
        ('$add_username', '$add_reg_mobile_no', '$add_reg_email_address', '$DOB', '$add_staff_type', '$add_staff_status', '$add_departmentmaster_Id', '$password')");

    echo "200";
    
}
//-----------------------------------------------------------------------------------------------------------------------

//-----------------------------------------------------------------------------------------------------------------------
if(isset($_GET['Edit_USERInstance'])){

    extract($_POST);

        $DOB = date('Y-m-d',strtotime(str_replace('/','-',$_POST['edit_date_of_birth'])));

        $updating_CalenderInstance = mysqli_query($mysqli,"Update user_stafflogin Set 
        username = '$edit_username',reg_mobile_no = '$edit_reg_mobile_no',reg_email_address = '$edit_reg_email_address',date_of_birth = '$DOB',staff_type = '$edit_staff_type',staff_status = '$edit_staff_status',departmentmaster_Id = '$edit_departmentmaster_Id'
        where Id  = '$edit_InstanceId'");


    echo "200";
    
}
//-----------------------------------------------------------------------------------------------------------------------



//-----------------------------------------------------------------------------------------------------------------------
if(isset($_GET['Allow_Batch_Access'])){

    extract($_POST);

    
    //Deleting UserPageAccess
    $Deleting_BatchAccess = mysqli_query($mysqli,"DELETE FROM comm_batch_access WHERE comm_batch_access.batchMaster_Id = '$batch_sel'");

    if(isset($check)){
        foreach($check as $index => $value) {

            //Inserting in BatchPageAccess
            $Inserting_BatchAccess = mysqli_query($mysqli,"INSERT INTO comm_batch_access(userId, batchMaster_Id) 
            values ('$check[$index]', '$batch_sel')");
            
        }
    }


	$res['status'] = 'success';
	echo json_encode($res);
    
}
//-----------------------------------------------------------------------------------------------------------------------


//-----------------------------------------------------------------------------------------------------------------------
if(isset($_GET['Add_USERInstance_InBulk'])){

    // check file name is not empty
    $pathinfo = pathinfo($_FILES["upload_file"]["name"]); //file extension
            
    if (($pathinfo['extension'] == 'xlsx' || $pathinfo['extension'] == 'xls') && $_FILES['upload_file']['size'] > 0 ) { //check if file is an excel file && is not empty
        $inputFileName = $_FILES['upload_file']['tmp_name'];  // Temporary file name
        
        // Read excel file by using ReadFactory object.
        $reader = ReaderFactory::create(Type::XLSX);

        // Open file
        $reader->open($inputFileName);
        $count = 1;
        $flag = 0;

        // Number of sheet in excel file
        foreach ($reader->getSheetIterator() as $sheet) {
            // Number of Rows in Excel sheet
            foreach ($sheet->getRowIterator() as $row) {
                // It reads data after header. In the my excel sheet, header is in the first row.
                if ($count > 1) {  if(!empty($row[0])){


//---------------------------------------------------------------------------------------------------------------                  

    $SectionMaster_Id = $_SESSION['schoolzone']['SectionMaster_Id'];  

    $UserDetails_q = mysqli_query($mysqli, "SELECT user_stafflogin.*, setup_sectionmaster.section_name FROM user_stafflogin  JOIN setup_departmentmaster ON setup_departmentmaster.Id = user_stafflogin.departmentmaster_Id JOIN setup_sectionmaster ON setup_sectionmaster.Id = setup_departmentmaster.sectionmaster_Id Where setup_sectionmaster.Id = '$SectionMaster_Id' AND user_stafflogin.username = '$row[1]'");

    $row_UserDetails = mysqli_num_rows($UserDetails_q);



    if($row_UserDetails == '0'){

        $password = password_hash( $row[2], PASSWORD_DEFAULT );

        //Checking Department and Adding
        $department_fetch_q = mysqli_query($mysqli, "Select setup_departmentmaster.Id from setup_departmentmaster Where setup_departmentmaster.department_name LIKE '%$row[8]%' AND setup_departmentmaster.sectionmaster_Id = '$SectionMaster_Id'");   


        $DOB =  date('Y-m-d',strtotime(str_replace('/','-',$row[5])));

        if(mysqli_num_rows($department_fetch_q) > 0){

            $r_department_fetch = mysqli_fetch_array($department_fetch_q);
    
            $Inserting_StaffQualification = mysqli_query($mysqli,"Insert into user_stafflogin
            (username, reg_mobile_no, reg_email_address, date_of_birth, staff_type, staff_status, departmentmaster_Id, password) 
            Values
            ('$row[1]', '$row[3]', '$row[4]', '$DOB', '$row[6]', '$row[7]', '$r_department_fetch[Id]', '$password')");

            $displayMessage[]  = $row[1].' : User Added';

        }else{
            $displayMessage[]  = $row[1].' : Department Not Found';
        }
        
    }elseif($row_UserDetails > '0'){
        $displayMessage[]  = $row[1].' : User Already Present';
    }

//----------------------------------------------------------------------------------------------------------------------
                   

        }}
        $count++;
        }

        }




        }


        $res['displayMessage'] = $displayMessage;
        echo json_encode($res);

}
//-----------------------------------------------------------------------------------------------------------------------

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


//-----------------------------------------------------------------------------------------------------------------------
if(isset($_GET['Department_Access_Control'])){

    extract($_POST);
    
    $del_entry = "DELETE FROM comm_dept_access WHERE userId = '$user_id'";
    $del_check = mysqli_query($mysqli,$del_entry);

    foreach($check as $val){
        
        $check_entry = "SELECT * FROM `comm_dept_access` where userId = '$user_id' AND departmentmaster_Id = '$val'";
        $q_entry = mysqli_query($mysqli,$check_entry);
        $row_check = mysqli_num_rows($q_entry); 

        if($row_check > 0){

        }
        else{

            $insert_dept_access = "insert into comm_dept_access (userId,departmentmaster_Id) values ('$user_id','$val')";
            $check_dept_access = mysqli_query($mysqli,$insert_dept_access);    

        }

        
        
    }
    echo "SUCCESS";
    
}
//-----------------------------------------------------------------------------------------------------------------------

//-----------------------------------------------------------------------------------------------------------------------
if(isset($_GET['Edit_USERInstance'])){

    extract($_POST);
        
        $updating_CalenderInstance = mysqli_query($mysqli,"Update setup_departmentmaster Set department_name = '$edit_department_name',abbreviation = '$edit_abbreviation',department_type = '$edit_department_type'
        where Id  = '$edit_InstanceId'");


    echo "200";
    
}
//-----------------------------------------------------------------------------------------------------------------------

//-----------------------------------------------------------------------------------------------------------------------
if(isset($_GET['Delete_DepartmentInstance'])){

    extract($_POST);

    $deleting_formheader = mysqli_query($mysqli,"DELETE FROM setup_departmentmaster where Id = '$delete_instance_Id'");

    echo "200";
    
}
//-----------------------------------------------------------------------------------------------------------------------


//-----------------------------------------------------------------------------------------------------------------------
if(isset($_GET['Add_DepartmentInstance'])){
   
    extract($_POST);

        $ActiveStaffLogin_Id = $_SESSION['schoolzone']['ActiveStaffLogin_Id'];
        $SectionMaster_Id = $_SESSION['schoolzone']['SectionMaster_Id'];
    
        //checking Department Abbbr & Name
        $depart_fetch_q = mysqli_query($mysqli,"SELECT setup_departmentmaster.* FROM setup_departmentmaster Where setup_departmentmaster.sectionmaster_Id = '$SectionMaster_Id' AND (setup_departmentmaster.department_name LIKE '%$add_department_name%' OR setup_departmentmaster.department_name LIKE '%$add_abbreviation%')");
        $row_depart_fetch = mysqli_num_rows($depart_fetch_q);

        if($row_depart_fetch == 0){

            
            $res['status'] = 'EXISTS';
            echo json_encode($res);

        }else{

            $Inserting_StaffQualification = mysqli_query($mysqli,"Insert into setup_departmentmaster
            (sectionmaster_Id, department_name, abbreviation, department_type) 
            Values
            ('$SectionMaster_Id', '$add_department_name', '$add_abbreviation', '$add_department_type')");
            
            $res['status'] = 'success';
            echo json_encode($res);
        }

}
//-----------------------------------------------------------------------------------------------------------------------

?>