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
if(isset($_GET['Delete_SectionInstance'])){

    extract($_POST);

    $deleting_formheader = mysqli_query($mysqli,"DELETE FROM setup_sectionmaster where Id = '$delete_instance_Id'");

    echo "200";
    
}
//-----------------------------------------------------------------------------------------------------------------------


//-----------------------------------------------------------------------------------------------------------------------
if(isset($_GET['Edit_SectionInstance'])){

    extract($_POST);

    
        $updating_CalenderInstance = mysqli_query($mysqli,"Update setup_sectionmaster Set 
        section_name = '".htmlspecialchars($edit_section_name, ENT_QUOTES)."',
        abbreviation = '".htmlspecialchars($edit_abbreviation, ENT_QUOTES)."',
        school_type = '".htmlspecialchars($edit_school_type, ENT_QUOTES)."',
        board = '".htmlspecialchars($edit_board, ENT_QUOTES)."',
        address = '".htmlspecialchars($edit_address, ENT_QUOTES)."',
        contact_no = '".htmlspecialchars($edit_contact_no, ENT_QUOTES)."',
        principal = '".htmlspecialchars($edit_principal, ENT_QUOTES)."',
        principal_contact_no = '".htmlspecialchars($edit_principal_contact_no, ENT_QUOTES)."',
        principal_mobile = '".htmlspecialchars($edit_principal_mobile, ENT_QUOTES)."',
        principal_email = '".htmlspecialchars($edit_principal_email, ENT_QUOTES)."',
        website = '".htmlspecialchars($edit_website, ENT_QUOTES)."',
        udise_no = '".htmlspecialchars($edit_udise_no, ENT_QUOTES)."',
        funding_type = '".htmlspecialchars($edit_funding_type, ENT_QUOTES)."',
        open_login = '".htmlspecialchars($edit_open_login, ENT_QUOTES)."',
        maintenance_message = '".htmlspecialchars($edit_maintenance_message, ENT_QUOTES)."',
        open_login_student = '".htmlspecialchars($edit_open_login_student, ENT_QUOTES)."',
        maintenance_message_student = '".htmlspecialchars($edit_maintenance_message_student, ENT_QUOTES)."',
        open_login_candidate = '".htmlspecialchars($edit_open_login_candidate, ENT_QUOTES)."',
        maintenance_message_candidate = '".htmlspecialchars($edit_maintenance_message_candidate, ENT_QUOTES)."'
        where Id  = '$edit_InstanceId'");


        if(isset($_FILES['sectionlogo'])){


            $photo_Error = $_FILES['sectionlogo']['error'];
            if($photo_Error == 0) {
                    
                $photo_Array = $_FILES['sectionlogo'];
                $photo_Name = $_FILES['sectionlogo']['name'];
                $photo_TmpName = $_FILES['sectionlogo']['tmp_name'];
                $photo_Size = $_FILES['sectionlogo']['size'];
                $photo_Type = $_FILES['sectionlogo']['type'];
        
                $ext = strtolower(pathinfo($photo_Name, PATHINFO_EXTENSION));
               
                $current_file_name = $edit_InstanceId.".".$ext;
                
                $targetfolder = "../../assets/section_logos/".$current_file_name;
                
                $targetfolder_new = "assets/section_logos/".$current_file_name;
    

                if (file_exists($targetfolder)) {
                    unlink($targetfolder);
                }
                //Path CHecking===========================================================
    
                if(move_uploaded_file($_FILES['sectionlogo']['tmp_name'], $targetfolder))
                {
                    $Updating_pdf = mysqli_query($mysqli,"UPDATE  setup_sectionmaster SET section_logo = '$targetfolder_new' WHERE  setup_sectionmaster.Id = '$edit_InstanceId'"); 

        
                }
                else {
                    $message = "Problem uploading file";
                }
    
    
            }//checking if image Uplaod
    
    
        }// close image isset
    
    

        $res['message'] = $message;
        $res['status'] = 'success';
        echo json_encode($res);
    
}
//-----------------------------------------------------------------------------------------------------------------------



//-----------------------------------------------------------------------------------------------------------------------
if(isset($_GET['Add_SectionInstance'])){

    extract($_POST);

    
        //Inserting data in comm_message_log
        $Inserting_UserDetails = mysqli_query($mysqli,"Insert into setup_sectionmaster 
        (section_name, abbreviation, school_type, board, address, contact_no, principal, principal_contact_no, principal_mobile, principal_email, website, udise_no, funding_type, open_login, maintenance_message, open_login_student, maintenance_message_student, open_login_candidate, maintenance_message_candidate) values 
        ('".htmlspecialchars($add_section_name, ENT_QUOTES)."', '".htmlspecialchars($add_abbreviation, ENT_QUOTES)."', '".htmlspecialchars($add_school_type, ENT_QUOTES)."', '".htmlspecialchars($add_board, ENT_QUOTES)."', '".htmlspecialchars($add_address, ENT_QUOTES)."', '".htmlspecialchars($add_contact_no, ENT_QUOTES)."', '".htmlspecialchars($add_principal, ENT_QUOTES)."', '".htmlspecialchars($add_principal_contact_no, ENT_QUOTES)."', '".htmlspecialchars($add_principal_mobile, ENT_QUOTES)."', '".htmlspecialchars($add_principal_email, ENT_QUOTES)."', '".htmlspecialchars($add_website, ENT_QUOTES)."', '".htmlspecialchars($add_udise_no, ENT_QUOTES)."', '".htmlspecialchars($add_funding_type, ENT_QUOTES)."', '".htmlspecialchars($add_open_login, ENT_QUOTES)."', '".htmlspecialchars($add_maintenance_message, ENT_QUOTES)."', '".htmlspecialchars($add_open_login_student, ENT_QUOTES)."', '".htmlspecialchars($add_maintenance_message_student, ENT_QUOTES)."', '".htmlspecialchars($add_open_login_candidate, ENT_QUOTES)."', '".htmlspecialchars($add_maintenance_message_candidate, ENT_QUOTES)."')");

        $last_id = mysqli_insert_id($mysqli);

        if(isset($_FILES['sectionlogo'])){


            $photo_Error = $_FILES['sectionlogo']['error'];
            if($photo_Error == 0) {
                    
                $photo_Array = $_FILES['sectionlogo'];
                $photo_Name = $_FILES['sectionlogo']['name'];
                $photo_TmpName = $_FILES['sectionlogo']['tmp_name'];
                $photo_Size = $_FILES['sectionlogo']['size'];
                $photo_Type = $_FILES['sectionlogo']['type'];
        
                $ext = strtolower(pathinfo($photo_Name, PATHINFO_EXTENSION));
               
                $current_file_name = $last_id.".".$ext;
                
                $targetfolder = "../../assets/section_logos/".$current_file_name;
                
                $targetfolder_new = "assets/section_logos/".$current_file_name;
    

                if (file_exists($targetfolder)) {
                    unlink($targetfolder);
                }
                //Path CHecking===========================================================
    
                if(move_uploaded_file($_FILES['sectionlogo']['tmp_name'], $targetfolder))
                {
                    $Updating_pdf = mysqli_query($mysqli,"UPDATE  setup_sectionmaster SET section_logo = '$targetfolder_new' WHERE  setup_sectionmaster.Id = '$last_id'"); 

        
                }
                else {
                    $message = "Problem uploading file";
                }
    
    
            }//checking if image Uplaod
    
    
        }// close image isset
    
    

        $res['message'] = $message;
        $res['status'] = 'success';
        echo json_encode($res);
    
}
//-----------------------------------------------------------------------------------------------------------------------


//-----------------------------------------------------------------------------------------------------------------------
if(isset($_GET['Mapping_Module'])){

    extract($_POST);

  
        
    //Deleting UserPageAccess
    $Deleting_BatchAccess = mysqli_query($mysqli,"DELETE FROM setup_modulemapping WHERE setup_modulemapping.sectionmaster_Id = '$selectedSection_Id'");

    if(isset($check)){
        foreach($check as $index => $value) {

            //Inserting in BatchPageAccess
            $Inserting_BatchAccess = mysqli_query($mysqli,"INSERT INTO setup_modulemapping(modulelist_Id, sectionmaster_Id, userType_Id) 
            values ('$check[$index]', '$selectedSection_Id', '0')");
            
        }
    }

    if(isset($checks)){
        foreach($checks as $index => $value) {

            //Inserting in BatchPageAccess
            $Inserting_BatchAccess = mysqli_query($mysqli,"INSERT INTO setup_modulemapping(modulelist_Id, sectionmaster_Id, userType_Id) 
            values ('$checks[$index]', '$selectedSection_Id' , '1')");
            
        }
    }

    if(isset($checkss)){
        foreach($checkss as $index => $value) {

            //Inserting in BatchPageAccess
            $Inserting_BatchAccess = mysqli_query($mysqli,"INSERT INTO setup_modulemapping(modulelist_Id, sectionmaster_Id, userType_Id) 
            values ('$checkss[$index]', '$selectedSection_Id' , '2')");
            
        }
    }


	$res['status'] = 'success';
	echo json_encode($res);
    
}
//-----------------------------------------------------------------------------------------------------------------------


//-----------------------------------------------------------------------------------------------------------------------
if(isset($_GET['Delete_ModulelistInstance'])){

    extract($_POST);

    $deleting_formheader = mysqli_query($mysqli,"DELETE FROM setup_modulelist where Id = '$delete_instance_Id'");

    echo "200";
    
}
//-----------------------------------------------------------------------------------------------------------------------
//-----------------------------------------------------------------------------------------------------------------------
if(isset($_GET['Add_ModulelistInstance'])){
   
    extract($_POST);

        $Inserting_StaffQualification = mysqli_query($mysqli,"Insert into setup_modulelist
        (modulelist) 
        Values
        ('$add_modulelist')");

    echo "200";
    
}
//-----------------------------------------------------------------------------------------------------------------------

//-----------------------------------------------------------------------------------------------------------------------
if(isset($_GET['Edit_ModulelistInstance'])){

    extract($_POST);


        $updating_CalenderInstance = mysqli_query($mysqli,"Update setup_modulelist Set 
        modulelist = '$edit_modulelist'
        where Id  = '$edit_InstanceId'");


    echo "200";
    
}
//-----------------------------------------------------------------------------------------------------------------------

?>