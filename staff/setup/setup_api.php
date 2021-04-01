<?php
//Session File
include_once '../SessionInfo.php';
//Database File
include_once '../../config/database.php';


use Box\Spout\Reader\ReaderFactory;
use Box\Spout\Writer\WriterFactory;

use Box\Spout\Common\Type;

require_once '../../assets/plugins/spout-2.4.3/src/Spout/Autoloader/autoload.php';

//-----------------------------------------------------------------------------------------------------------------------
if(isset($_GET['Add_StreamInstance'])){
   
    extract($_POST);

    $ActiveStaffLogin_Id = $_SESSION['schoolzone']['ActiveStaffLogin_Id'];
    $SectionMaster_Id = $_SESSION['schoolzone']['SectionMaster_Id'];

    //checking Department Abbbr & Name
    $depart_fetch_q = mysqli_query($mysqli,"SELECT setup_streammaster.* FROM setup_streammaster Where setup_streammaster.sectionmaster_Id = '$SectionMaster_Id' AND (setup_streammaster.stream_name = '".htmlspecialchars($add_stream_name, ENT_QUOTES)."' OR setup_streammaster.abbreviation = '".htmlspecialchars($add_abbreviation, ENT_QUOTES)."')");


    $row_depart_fetch = mysqli_num_rows($depart_fetch_q);

    if($row_depart_fetch > 0){

        
        $res['status'] = 'EXISTS';
        echo json_encode($res);

    }else{

        $Inserting_StaffQualification = mysqli_query($mysqli,"Insert into setup_streammaster
        (sectionmaster_Id, stream_name, abbreviation) 
        Values
        ('$SectionMaster_Id', '".htmlspecialchars($add_stream_name, ENT_QUOTES)."', '".htmlspecialchars($add_abbreviation, ENT_QUOTES)."')");
        
        $res['status'] = 'success';
        echo json_encode($res);
    }

    
}
//-----------------------------------------------------------------------------------------------------------------------

//-----------------------------------------------------------------------------------------------------------------------
if(isset($_GET['Edit_StreamInstance'])){

    extract($_POST);

     
    $updating_CalenderInstance = mysqli_query($mysqli,"Update setup_streammaster Set stream_name = '".htmlspecialchars($edit_stream_name, ENT_QUOTES)."',abbreviation='".htmlspecialchars($edit_abbreviation, ENT_QUOTES)."' where Id  = '$edit_InstanceId'");

    echo "200";
    
}
//-----------------------------------------------------------------------------------------------------------------------


//-----------------------------------------------------------------------------------------------------------------------
if(isset($_GET['Delete_StreamInstance'])){

    extract($_POST);

    $deleting_formheader = mysqli_query($mysqli,"DELETE FROM setup_streammaster where Id = '$delete_instance_Id'");

    echo "200";
    
}
//-----------------------------------------------------------------------------------------------------------------------

//-----------------------------------------------------------------------------------------------------------------------
if(isset($_GET['Add_StreamInstance_InBulk'])){

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

    $StreamDetails_q = mysqli_query($mysqli, "SELECT setup_streammaster.* FROM setup_streammaster Where setup_streammaster.sectionmaster_Id = '$SectionMaster_Id' AND (setup_streammaster.stream_name = '".htmlspecialchars($row[1], ENT_QUOTES)."' OR setup_streammaster.abbreviation = '".htmlspecialchars($row[2], ENT_QUOTES)."')");

    $row_StreamDetails = mysqli_num_rows($StreamDetails_q);



    if($row_StreamDetails == '0'){


        $Inserting_StreamDetails = mysqli_query($mysqli,"Insert into setup_streammaster
        (sectionmaster_Id, stream_name, abbreviation) 
        Values
        ('$SectionMaster_Id', '".htmlspecialchars($row[1], ENT_QUOTES)."', '".htmlspecialchars($row[2], ENT_QUOTES)."')");
        
        $displayMessage[]  = $row[1].' : Stream Added';


        
    }elseif($row_StreamDetails > '0'){
        $displayMessage[]  = $row[1].' : Stream Already Present';
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
if(isset($_GET['Edit_ProgramInstance'])){

    extract($_POST);

     
    $updating_CalenderInstance = mysqli_query($mysqli,"Update setup_programmaster Set 
    streammaster_Id ='".htmlspecialchars($edit_streammaster_Id, ENT_QUOTES)."',
    program_name = '".htmlspecialchars($edit_program_name, ENT_QUOTES)."',
    abbreviation='".htmlspecialchars($edit_abbreviation, ENT_QUOTES)."',
    program_code='".htmlspecialchars($edit_program_code, ENT_QUOTES)."',
    year_of_program='".htmlspecialchars($edit_year_of_program, ENT_QUOTES)."'
    where Id  = '$edit_InstanceId'");

    echo "200";
    
}
//-----------------------------------------------------------------------------------------------------------------------


//-----------------------------------------------------------------------------------------------------------------------
if(isset($_GET['Delete_ProgramInstance'])){

    extract($_POST);

    $deleting_formheader = mysqli_query($mysqli,"DELETE FROM setup_programmaster where Id = '$delete_instance_Id'");

    echo "200";
    
}
//-----------------------------------------------------------------------------------------------------------------------

//-----------------------------------------------------------------------------------------------------------------------
if(isset($_GET['Add_ProgramInstance'])){
   
    extract($_POST);

    $ActiveStaffLogin_Id = $_SESSION['schoolzone']['ActiveStaffLogin_Id'];
    $SectionMaster_Id = $_SESSION['schoolzone']['SectionMaster_Id'];

    //checking Department Abbbr & Name
    $depart_fetch_q = mysqli_query($mysqli,"SELECT setup_programmaster.* FROM setup_programmaster Where setup_programmaster.streammaster_Id  = '$add_streammaster_Id' AND (setup_programmaster.program_name = '".htmlspecialchars($add_program_name, ENT_QUOTES)."' OR setup_programmaster.abbreviation = '".htmlspecialchars($add_abbreviation, ENT_QUOTES)."'  OR setup_programmaster.program_code = '".htmlspecialchars($add_program_code, ENT_QUOTES)."')");


    $row_depart_fetch = mysqli_num_rows($depart_fetch_q);

    if($row_depart_fetch > 0){

        
        $res['status'] = 'EXISTS';
        echo json_encode($res);

    }else{

        $Inserting_StaffQualification = mysqli_query($mysqli,"Insert into setup_programmaster
        (streammaster_Id, program_name, abbreviation, program_code, year_of_program, status) 
        Values
        ('$add_streammaster_Id', '".htmlspecialchars($add_program_name, ENT_QUOTES)."', '".htmlspecialchars($add_abbreviation, ENT_QUOTES)."', '".htmlspecialchars($add_program_code, ENT_QUOTES)."', '".htmlspecialchars($add_year_of_program, ENT_QUOTES)."', '1')");
        
        $res['status'] = 'success';
        echo json_encode($res);
    }

    
}
//-----------------------------------------------------------------------------------------------------------------------


//-----------------------------------------------------------------------------------------------------------------------
if(isset($_GET['Add_ProgramInstance_InBulk'])){

    $SectionMaster_Id = $_SESSION['schoolzone']['SectionMaster_Id'];  

    $stream_sel_import = $_POST['stream_sel_import'];
    // check file name is not empty
    $pathinfo = pathinfo($_FILES["upload_file"]["name"]); //file extension

    $folderMap = '../';
    $fileLocation = "FileUploadLogs/ProgramMaster_".$SectionMaster_Id."_".date("Y-m-d-h-i-s").'.xlsx';
    $targetfolder =  $folderMap."".$fileLocation;
    move_uploaded_file($_FILES['upload_file']['name'], $targetfolder);

    $writer = WriterFactory::create(Type::XLSX); // for XLSX files

    $fileName= $targetfolder;
    $writer->openToFile($fileName); // write data to a file or to a PHP stream
    // $writer->openToBrowser($fileName); // stream data directly to the browser

    $singleRow =['Sr No','Program Name','Abbreviation','Program Code','Year of Program','Upload Status'];
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


    $StreamDetails_q = mysqli_query($mysqli, "SELECT setup_programmaster.* FROM setup_programmaster Where setup_programmaster.streammaster_Id  = '$stream_sel_import' AND (setup_programmaster.program_name = '".htmlspecialchars($row[1], ENT_QUOTES)."' OR setup_programmaster.abbreviation = '".htmlspecialchars($row[2], ENT_QUOTES)."'  OR setup_programmaster.program_code = '".htmlspecialchars($row[3], ENT_QUOTES)."')");

    $row_StreamDetails = mysqli_num_rows($StreamDetails_q);



    if($row_StreamDetails == '0'){


        $Inserting_StaffQualification = mysqli_query($mysqli,"Insert into setup_programmaster
        (streammaster_Id, program_name, abbreviation, program_code, year_of_program, status) 
        Values
        ('$stream_sel_import', '".htmlspecialchars($row[1], ENT_QUOTES)."', '".htmlspecialchars($row[2], ENT_QUOTES)."', '".htmlspecialchars($row[3], ENT_QUOTES)."', '".htmlspecialchars($row[4], ENT_QUOTES)."', '1')");


        if(mysqli_error($mysqli)){
            $displayMessage[]  = $row[1].' : Query Failed';
            $uploadMessage = "Query Failed";
        }else{
            $displayMessage[]  = $row[1].' : Program Added';
            $uploadMessage = "Program Added";    
        }
        

        $multipleRows=[
            [$row[0],$row[1],$row[2],$row[3],$row[4],$uploadMessage],
        ];
        $writer->addRows($multipleRows); // add multiple rows at a time

        
    }elseif($row_StreamDetails > '0'){
        $displayMessage[]  = $row[1].' : program Already Present';
        $uploadMessage = "Program Already Added";

        $multipleRows=[
            [$row[0],$row[1],$row[2],$row[3],$row[4],$uploadMessage],
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
if(isset($_GET['Edit_GroupInstance'])){

    extract($_POST);

     
    $updating_CalenderInstance = mysqli_query($mysqli,"Update setup_groupmaster Set 
    programmaster_Id ='".htmlspecialchars($edit_programmaster_Id, ENT_QUOTES)."',
    group_name = '".htmlspecialchars($edit_group_name, ENT_QUOTES)."',
    abbreviation='".htmlspecialchars($edit_abbreviation, ENT_QUOTES)."',
    previous_group_id='".htmlspecialchars($edit_previous_group_id, ENT_QUOTES)."'
    where Id  = '$edit_InstanceId'");

    echo "200";
    
}
//-----------------------------------------------------------------------------------------------------------------------


//-----------------------------------------------------------------------------------------------------------------------
if(isset($_GET['Delete_GroupInstance'])){

    extract($_POST);

    $deleting_formheader = mysqli_query($mysqli,"DELETE FROM setup_groupmaster where Id = '$delete_instance_Id'");

    echo "200";
    
}
//-----------------------------------------------------------------------------------------------------------------------

//-----------------------------------------------------------------------------------------------------------------------
if(isset($_GET['Add_GroupInstance'])){
   
    extract($_POST);

    $ActiveStaffLogin_Id = $_SESSION['schoolzone']['ActiveStaffLogin_Id'];
    $SectionMaster_Id = $_SESSION['schoolzone']['SectionMaster_Id'];

    //checking Department Abbbr & Name
    $depart_fetch_q = mysqli_query($mysqli,"SELECT setup_groupmaster.* FROM setup_groupmaster WHERE setup_groupmaster.programmaster_Id = '$add_programmaster_Id' AND( setup_groupmaster.group_name = '".htmlspecialchars($add_group_name, ENT_QUOTES)."' OR setup_groupmaster.abbreviation = '".htmlspecialchars($add_abbreviation, ENT_QUOTES)."' )");


    $row_depart_fetch = mysqli_num_rows($depart_fetch_q);

    if($row_depart_fetch > 0){

        
        $res['status'] = 'EXISTS';
        echo json_encode($res);

    }else{

        $Inserting_StaffQualification = mysqli_query($mysqli,"Insert into setup_groupmaster
        (programmaster_Id, group_name, abbreviation, previous_group_id) 
        Values
        ('$add_programmaster_Id', '".htmlspecialchars($add_group_name, ENT_QUOTES)."', '".htmlspecialchars($add_abbreviation, ENT_QUOTES)."', '".htmlspecialchars($add_previous_group_id, ENT_QUOTES)."')");
        
        $res['status'] = 'success';
        echo json_encode($res);
    }

    
}
//-----------------------------------------------------------------------------------------------------------------------
//-----------------------------------------------------------------------------------------------------------------------
if(isset($_GET['Add_GroupInstance_InBulk'])){

    $SectionMaster_Id = $_SESSION['schoolzone']['SectionMaster_Id'];  

    $program_sel_import = $_POST['program_sel_import'];
    // check file name is not empty
    $pathinfo = pathinfo($_FILES["upload_file"]["name"]); //file extension

    $folderMap = '../';
    $fileLocation = "FileUploadLogs/GroupMaster_".$SectionMaster_Id."_".date("Y-m-d-h-i-s").'.xlsx';
    $targetfolder =  $folderMap."".$fileLocation;
    move_uploaded_file($_FILES['upload_file']['name'], $targetfolder);

    $writer = WriterFactory::create(Type::XLSX); // for XLSX files

    $fileName= $targetfolder;
    $writer->openToFile($fileName); // write data to a file or to a PHP stream
    // $writer->openToBrowser($fileName); // stream data directly to the browser

    $singleRow =['Sr No','Group Name','Abbreviation','Previous Group Id','Upload Status'];
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

    $StreamDetails_q = mysqli_query($mysqli, "SELECT setup_groupmaster.* FROM setup_groupmaster WHERE setup_groupmaster.programmaster_Id = '$program_sel_import' AND( setup_groupmaster.group_name = '".htmlspecialchars($row[1], ENT_QUOTES)."' OR setup_groupmaster.abbreviation = '".htmlspecialchars($row[2], ENT_QUOTES)."' )");

    $row_StreamDetails = mysqli_num_rows($StreamDetails_q);



    if($row_StreamDetails == '0'){


        $Inserting_StaffQualification = mysqli_query($mysqli,"Insert into setup_groupmaster
        (programmaster_Id, group_name, abbreviation, previous_group_id) 
        Values
        ('$program_sel_import', '".htmlspecialchars($row[1], ENT_QUOTES)."', '".htmlspecialchars($row[2], ENT_QUOTES)."', '".htmlspecialchars($row[3], ENT_QUOTES)."')");


        if(mysqli_error($mysqli)){
            $displayMessage[]  = $row[1].' : Query Failed';
            $uploadMessage = "Query Failed";
        }else{
            $displayMessage[]  = $row[1].' : Group Added';
            $uploadMessage = "Group Added";    
        }
        

        $multipleRows=[
            [$row[0],$row[1],$row[2],$row[3],$uploadMessage],
        ];
        $writer->addRows($multipleRows); // add multiple rows at a time

        
    }elseif($row_StreamDetails > '0'){
        $displayMessage[]  = $row[1].' : Group Already Present';
        $uploadMessage = "Group Already Added";

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
//-----------------------------------------------------------------------------------------------------------------------
if(isset($_GET['Edit_CourseInstance'])){

    extract($_POST);

     
    $updating_CalenderInstance = mysqli_query($mysqli,"Update setup_coursemaster Set 
    groupmaster_Id ='".htmlspecialchars($edit_groupmaster_Id, ENT_QUOTES)."',
    course_name = '".htmlspecialchars($edit_course_name, ENT_QUOTES)."',
    abbreviation='".htmlspecialchars($edit_abbreviation, ENT_QUOTES)."',
    sem_Id='".htmlspecialchars($edit_sem_Id, ENT_QUOTES)."',
    course_number='".htmlspecialchars($edit_course_number, ENT_QUOTES)."'
    where Id  = '$edit_InstanceId'");

    echo "200";
    
}
//-----------------------------------------------------------------------------------------------------------------------


//-----------------------------------------------------------------------------------------------------------------------
if(isset($_GET['Delete_CourseInstance'])){

    extract($_POST);

    $deleting_formheader = mysqli_query($mysqli,"DELETE FROM setup_coursemaster where Id = '$delete_instance_Id'");

    echo "200";
    
}
//-----------------------------------------------------------------------------------------------------------------------

//-----------------------------------------------------------------------------------------------------------------------
if(isset($_GET['Add_CourseInstance'])){
   
    extract($_POST);

    $ActiveStaffLogin_Id = $_SESSION['schoolzone']['ActiveStaffLogin_Id'];
    $SectionMaster_Id = $_SESSION['schoolzone']['SectionMaster_Id'];

    //checking Department Abbbr & Name
    $depart_fetch_q = mysqli_query($mysqli,"SELECT setup_coursemaster.* FROM setup_coursemaster WHERE setup_coursemaster.programmaster_Id = '$add_programmaster_Id' AND( setup_coursemaster.course_name = '".htmlspecialchars($add_course_name, ENT_QUOTES)."' OR setup_coursemaster.abbreviation = '".htmlspecialchars($add_abbreviation, ENT_QUOTES)."' )");


    $row_depart_fetch = mysqli_num_rows($depart_fetch_q);

    if($row_depart_fetch > 0){

        
        $res['status'] = 'EXISTS';
        echo json_encode($res);

    }else{

        $Inserting_StaffQualification = mysqli_query($mysqli,"Insert into setup_coursemaster
        (programmaster_Id, groupmaster_Id, course_name, abbreviation, sem_Id, course_number) 
        Values
        ('$add_programmaster_Id', '".htmlspecialchars($add_groupmaster_Id, ENT_QUOTES)."', '".htmlspecialchars($add_course_name, ENT_QUOTES)."', '".htmlspecialchars($add_abbreviation, ENT_QUOTES)."', '".htmlspecialchars($add_sem_Id, ENT_QUOTES)."', '".htmlspecialchars($add_course_number, ENT_QUOTES)."')");
        
        $res['status'] = 'success';
        echo json_encode($res);
    }

    
}
//-----------------------------------------------------------------------------------------------------------------------


//-----------------------------------------------------------------------------------------------------------------------
if(isset($_GET['Add_CourseInstance_InBulk'])){

    $SectionMaster_Id = $_SESSION['schoolzone']['SectionMaster_Id'];  

    $program_sel_import = $_POST['program_sel_import'];
    // check file name is not empty
    $pathinfo = pathinfo($_FILES["upload_file"]["name"]); //file extension

    $folderMap = '../';
    $fileLocation = "FileUploadLogs/CourseMaster_".$SectionMaster_Id."_".date("Y-m-d-h-i-s").'.xlsx';
    $targetfolder =  $folderMap."".$fileLocation;
    move_uploaded_file($_FILES['upload_file']['name'], $targetfolder);

    $writer = WriterFactory::create(Type::XLSX); // for XLSX files

    $fileName= $targetfolder;
    $writer->openToFile($fileName); // write data to a file or to a PHP stream
    // $writer->openToBrowser($fileName); // stream data directly to the browser

    $singleRow =['Sr No','Course Name','Abbreviation','Sem','Course Number','Group Name','Upload Status'];
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

    $StreamDetails_q = mysqli_query($mysqli, "SELECT setup_coursemaster.* FROM setup_coursemaster WHERE setup_coursemaster.programmaster_Id = '$program_sel_import' AND( setup_coursemaster.course_name = '".htmlspecialchars($row[1], ENT_QUOTES)."' OR setup_coursemaster.abbreviation = '".htmlspecialchars($row[2], ENT_QUOTES)."' )");

    $row_StreamDetails = mysqli_num_rows($StreamDetails_q);



    if($row_StreamDetails == '0'){

        //checking Group and Sem Belong to this Program

        
        $checkinggroupDetails_q = mysqli_query($mysqli, "SELECT setup_groupmaster.* FROM `setup_groupmaster` WHERE setup_groupmaster.programmaster_Id = '$program_sel_import' AND setup_groupmaster.Id = '$row[5]'");
        $row_checkinggroupDetails = mysqli_num_rows($checkinggroupDetails_q);

        $checkingsemDetails_q = mysqli_query($mysqli, "SELECT setup_semestermaster.* FROM setup_semestermaster Where Id = '$row[3]' AND  setup_semestermaster.sectionmaster_Id = '$SectionMaster_Id'");
        $row_checkingsemDetails = mysqli_num_rows($checkingsemDetails_q);

        if($row_checkinggroupDetails > '0' AND $row_checkingsemDetails > '0'){
            
        
            $Inserting_StaffQualification = mysqli_query($mysqli,"Insert into setup_coursemaster
            (programmaster_Id, groupmaster_Id, course_name, abbreviation, sem_Id, course_number) 
            Values
            ('$program_sel_import', '".htmlspecialchars($row[5], ENT_QUOTES)."', '".htmlspecialchars($row[1], ENT_QUOTES)."', '".htmlspecialchars($row[2], ENT_QUOTES)."', '".htmlspecialchars($row[3], ENT_QUOTES)."', '".htmlspecialchars($row[4], ENT_QUOTES)."')");


            if(mysqli_error($mysqli)){
                $displayMessage[]  = $row[1].' : Query Failed';
                $uploadMessage = "Query Failed";
            }else{
                $displayMessage[]  = $row[1].' : Group Added';
                $uploadMessage = "Group Added";    
            }
            
        //close checking Details
        }else{

            $displayMessage[]  = $row[1].' : Invalid Group Id or Sem Id';
            $uploadMessage = "Invalid Group Id or Sem Id";    
        }// clsoe else

            

        $multipleRows=[
            [$row[0],$row[1],$row[2],$row[3],$row[4],$row[5],$uploadMessage],
        ];
        $writer->addRows($multipleRows); // add multiple rows at a time

        
    }elseif($row_StreamDetails > '0'){
        $displayMessage[]  = $row[1].' : Group Already Present';
        $uploadMessage = "Group Already Added";

        $multipleRows=[
            [$row[0],$row[1],$row[2],$row[3],$row[4],$row[5],$uploadMessage],
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
if(isset($_GET['Add_SemesterInstance'])){
   
    extract($_POST);

    $ActiveStaffLogin_Id = $_SESSION['schoolzone']['ActiveStaffLogin_Id'];
    $SectionMaster_Id = $_SESSION['schoolzone']['SectionMaster_Id'];

    //checking Department Abbbr & Name
    $depart_fetch_q = mysqli_query($mysqli,"SELECT setup_semestermaster.* FROM setup_semestermaster Where setup_semestermaster.sectionmaster_Id = '$SectionMaster_Id' AND setup_semestermaster.Semester_Name = '".htmlspecialchars($add_Semester_Name, ENT_QUOTES)."'");


    $row_depart_fetch = mysqli_num_rows($depart_fetch_q);

    if($row_depart_fetch > 0){

        
        $res['status'] = 'EXISTS';
        echo json_encode($res);

    }else{

        $Inserting_StaffQualification = mysqli_query($mysqli,"Insert into setup_semestermaster
        (sectionmaster_Id, Semester_Name, odd_even, Previous_Semester_Id) 
        Values
        ('$SectionMaster_Id', '".htmlspecialchars($add_Semester_Name, ENT_QUOTES)."', '".htmlspecialchars($add_odd_even, ENT_QUOTES)."', '".htmlspecialchars($add_Previous_Semester_Id, ENT_QUOTES)."')");


        $res['status'] = 'success';
        echo json_encode($res);
    }

    
}
//-----------------------------------------------------------------------------------------------------------------------

//-----------------------------------------------------------------------------------------------------------------------
if(isset($_GET['Edit_SemesterInstance'])){

    extract($_POST);

     
    $updating_CalenderInstance = mysqli_query($mysqli,"Update setup_semestermaster Set Semester_Name = '".htmlspecialchars($edit_Semester_Name, ENT_QUOTES)."',odd_even='".htmlspecialchars($edit_odd_even, ENT_QUOTES)."',Previous_Semester_Id='".htmlspecialchars($edit_Previous_Semester_Id, ENT_QUOTES)."' where Id  = '$edit_InstanceId'");

    echo "200";
    
}
//-----------------------------------------------------------------------------------------------------------------------


//-----------------------------------------------------------------------------------------------------------------------
if(isset($_GET['Delete_SemesterInstance'])){

    extract($_POST);

    $deleting_formheader = mysqli_query($mysqli,"DELETE FROM setup_semestermaster where Id = '$delete_instance_Id'");

    echo "200";
    
}
//-----------------------------------------------------------------------------------------------------------------------
