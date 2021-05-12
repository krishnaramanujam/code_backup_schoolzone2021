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
use Box\Spout\Writer\WriterFactory;

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


//-----------------------------------------------------------------------------------------------------------------------
if(isset($_GET['Add_bankInstance'])){
   
    extract($_POST);

    $ActiveStaffLogin_Id = $_SESSION['schoolzone']['ActiveStaffLogin_Id'];
    $SectionMaster_Id = $_SESSION['schoolzone']['SectionMaster_Id'];

    //checking Department Abbbr & Name
    $depart_fetch_q = mysqli_query($mysqli,"SELECT setup_bankmaster.* FROM setup_bankmaster Where (setup_bankmaster.bank_name = '".htmlspecialchars($add_bank_name, ENT_QUOTES)."' OR setup_bankmaster.abbreviation = '".htmlspecialchars($add_abbreviation, ENT_QUOTES)."')");


    $row_depart_fetch = mysqli_num_rows($depart_fetch_q);

    if($row_depart_fetch > 0){

        
        $res['status'] = 'EXISTS';
        echo json_encode($res);

    }else{

        $Inserting_StaffQualification = mysqli_query($mysqli,"Insert into setup_bankmaster
        (bank_name, abbreviation) 
        Values
        ('".htmlspecialchars($add_bank_name, ENT_QUOTES)."', '".htmlspecialchars($add_abbreviation, ENT_QUOTES)."')");
        
        $res['status'] = 'success';
        echo json_encode($res);
    }

    
}
//-----------------------------------------------------------------------------------------------------------------------

//-----------------------------------------------------------------------------------------------------------------------
if(isset($_GET['Edit_bankInstance'])){

    extract($_POST);

     
    $updating_CalenderInstance = mysqli_query($mysqli,"Update setup_bankmaster Set bank_name = '".htmlspecialchars($edit_bank_name, ENT_QUOTES)."',abbreviation='".htmlspecialchars($edit_abbreviation, ENT_QUOTES)."' where Id  = '$edit_InstanceId'");

    echo "200";
    
}
//-----------------------------------------------------------------------------------------------------------------------


//-----------------------------------------------------------------------------------------------------------------------
if(isset($_GET['Delete_BankInstance'])){

    extract($_POST);

    $deleting_formheader = mysqli_query($mysqli,"DELETE FROM setup_bankmaster where Id = '$delete_instance_Id'");

    echo "200";
    
}
//-----------------------------------------------------------------------------------------------------------------------

//-----------------------------------------------------------------------------------------------------------------------
if(isset($_GET['Add_BankInstance_InBulk'])){

    $SectionMaster_Id = $_SESSION['schoolzone']['SectionMaster_Id'];  

    // check file name is not empty
    $pathinfo = pathinfo($_FILES["upload_file"]["name"]); //file extension

    $folderMap = '../';
    $fileLocation = "FileUploadLogs/BankMaster_".$SectionMaster_Id."_".date("Y-m-d-h-i-s").'.xlsx';
    $targetfolder =  $folderMap."".$fileLocation;
    move_uploaded_file($_FILES['upload_file']['name'], $targetfolder);

    $writer = WriterFactory::create(Type::XLSX); // for XLSX files

    $fileName= $targetfolder;
    $writer->openToFile($fileName); // write data to a file or to a PHP stream
    // $writer->openToBrowser($fileName); // stream data directly to the browser

    $singleRow =['Sr No','Bank Name','Abbreviation','Upload Status'];
    $writer->addRow($singleRow); // add a row at a time





   
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

    $StreamDetails_q = mysqli_query($mysqli, "SELECT setup_bankmaster.* FROM setup_bankmaster Where (setup_bankmaster.bank_name = '".htmlspecialchars($row[1], ENT_QUOTES)."' OR setup_bankmaster.abbreviation = '".htmlspecialchars($row[2], ENT_QUOTES)."')");

    $row_StreamDetails = mysqli_num_rows($StreamDetails_q);



    if($row_StreamDetails == '0'){

        //checking Group and Sem Belong to this Program

        

        $Inserting_StaffQualification = mysqli_query($mysqli,"Insert into setup_bankmaster
        (bank_name, abbreviation) 
        Values
        ('".htmlspecialchars($row[1], ENT_QUOTES)."', '".htmlspecialchars($row[2], ENT_QUOTES)."')");



        if(mysqli_error($mysqli)){
            $displayMessage[]  = $row[1].' : Query Failed';
            $uploadMessage = "Query Failed";
        }else{
            $displayMessage[]  = $row[1].' : Bank Added';
            $uploadMessage = "Bank Added";    
        }

        

        $multipleRows=[
            [$row[0],$row[1],$row[2],$uploadMessage],
        ];
        $writer->addRows($multipleRows); // add multiple rows at a time

        
    }elseif($row_StreamDetails > '0'){
        $displayMessage[]  = $row[1].' : Bank Already Present';
        $uploadMessage = "Bank Already Added";

        $multipleRows=[
            [$row[0],$row[1],$row[2],$uploadMessage],
        ];
        $writer->addRows($multipleRows); // add multiple rows at a time

    }
    unset($uploadMessage);
//----------------------------------------------------------------------------------------------------------------------
                   

        }}
        $count++;
        }

        }




        }


        $writer->close();


        $res['UploadedFilePath'] = $fileLocation;
        $res['displayMessage'] = $displayMessage;
        echo json_encode($res);

}
//-----------------------------------------------------------------------------------------------------------------------

//-----------------------------------------------------------------------------------------------------------------------
if(isset($_GET['Add_FeeHeaderInstance'])){
   
    extract($_POST);

    $ActiveStaffLogin_Id = $_SESSION['schoolzone']['ActiveStaffLogin_Id'];
    $SectionMaster_Id = $_SESSION['schoolzone']['SectionMaster_Id'];

    //checking Department Abbbr & Name
    $depart_fetch_q = mysqli_query($mysqli,"SELECT fee_headermaster.* FROM fee_headermaster Where ( fee_headermaster.header_name = '".htmlspecialchars($add_header_name, ENT_QUOTES)."' OR fee_headermaster.abbreviation = '".htmlspecialchars($add_abbreviation, ENT_QUOTES)."' ) AND  fee_headermaster.sectionmaster_Id = '$SectionMaster_Id'");


    $row_depart_fetch = mysqli_num_rows($depart_fetch_q);

    if($row_depart_fetch > 0){

        
        $res['status'] = 'EXISTS';
        echo json_encode($res);

    }else{

        $Inserting_StaffQualification = mysqli_query($mysqli,"Insert into fee_headermaster
        (sectionmaster_Id, header_name, abbreviation, type_of_receipt) 
        Values
        ('$SectionMaster_Id', '".htmlspecialchars($add_header_name, ENT_QUOTES)."', '".htmlspecialchars($add_abbreviation, ENT_QUOTES)."', '".htmlspecialchars($add_type_of_receipt, ENT_QUOTES)."')");
        
        $res['status'] = 'success';
        echo json_encode($res);
    }

    
}
//-----------------------------------------------------------------------------------------------------------------------

//-----------------------------------------------------------------------------------------------------------------------
if(isset($_GET['Edit_FeeHeaderInstance'])){

    extract($_POST);

     
    $updating_CalenderInstance = mysqli_query($mysqli,"Update fee_headermaster Set header_name = '".htmlspecialchars($edit_header_name, ENT_QUOTES)."',abbreviation='".htmlspecialchars($edit_abbreviation, ENT_QUOTES)."',type_of_receipt='".htmlspecialchars($edit_type_of_receipt, ENT_QUOTES)."' where Id  = '$edit_InstanceId'");

    echo "200";
    
}
//-----------------------------------------------------------------------------------------------------------------------


//-----------------------------------------------------------------------------------------------------------------------
if(isset($_GET['Delete_FeeHeaderInstance'])){

    extract($_POST);

    $deleting_formheader = mysqli_query($mysqli,"DELETE FROM fee_headermaster where Id = '$delete_instance_Id'");

    echo "200";
    
}
//-----------------------------------------------------------------------------------------------------------------------


//-----------------------------------------------------------------------------------------------------------------------
if(isset($_GET['Add_FeeHeaderInstance_InBulk'])){

    $SectionMaster_Id = $_SESSION['schoolzone']['SectionMaster_Id'];  

    // check file name is not empty
    $pathinfo = pathinfo($_FILES["upload_file"]["name"]); //file extension

    $folderMap = '../';
    $fileLocation = "FileUploadLogs/FeeHeaderMaster_".$SectionMaster_Id."_".date("Y-m-d-h-i-s").'.xlsx';
    $targetfolder =  $folderMap."".$fileLocation;
    move_uploaded_file($_FILES['upload_file']['name'], $targetfolder);

    $writer = WriterFactory::create(Type::XLSX); // for XLSX files

    $fileName= $targetfolder;
    $writer->openToFile($fileName); // write data to a file or to a PHP stream
    // $writer->openToBrowser($fileName); // stream data directly to the browser

    $singleRow =['Sr No','Header Name','Abbreviation','Type Of Receipt','Upload Status'];
    $writer->addRow($singleRow); // add a row at a time





   
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

    $StreamDetails_q = mysqli_query($mysqli, "SELECT fee_headermaster.* FROM fee_headermaster Where ( fee_headermaster.header_name = '".htmlspecialchars($row[1], ENT_QUOTES)."' OR fee_headermaster.abbreviation = '".htmlspecialchars($row[2], ENT_QUOTES)."' ) AND  fee_headermaster.sectionmaster_Id = '$SectionMaster_Id'");

    $row_StreamDetails = mysqli_num_rows($StreamDetails_q);



    if($row_StreamDetails == '0'){

        //checking Group and Sem Belong to this Program

        
            $Inserting_StaffQualification = mysqli_query($mysqli,"Insert into fee_headermaster
            (sectionmaster_Id, header_name, abbreviation, type_of_receipt) 
            Values
            ('$SectionMaster_Id', '".htmlspecialchars($row[1], ENT_QUOTES)."', '".htmlspecialchars($row[2], ENT_QUOTES)."', '".htmlspecialchars($row[3], ENT_QUOTES)."')");



            if(mysqli_error($mysqli)){
                $displayMessage[]  = $row[2].' : Query Failed';
                $uploadMessage = "Query Failed";
            }else{
                $displayMessage[]  = $row[2].' : Fee Header Added';
                $uploadMessage = "Fee Header Added";    
            }

         //close checking Details
        

        $multipleRows=[
            [$row[0],$row[1],$row[2],$row[3],$uploadMessage],
        ];
        $writer->addRows($multipleRows); // add multiple rows at a time

        
    }elseif($row_StreamDetails > '0'){
        $displayMessage[]  = $row[2].' : Fee Header Already Present';
        $uploadMessage = "Fee Header Already Added";

        $multipleRows=[
            [$row[0],$row[1],$row[2],$row[3],$uploadMessage],
        ];
        $writer->addRows($multipleRows); // add multiple rows at a time

    }
    unset($uploadMessage);
//----------------------------------------------------------------------------------------------------------------------
                   

        }}
        $count++;
        }

        }




        }


        $writer->close();


        $res['UploadedFilePath'] = $fileLocation;
        $res['displayMessage'] = $displayMessage;
        echo json_encode($res);

}
//-----------------------------------------------------------------------------------------------------------------------

?>