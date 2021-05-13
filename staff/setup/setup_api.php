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
                $displayMessage[]  = $row[1].' : Course Added';
                $uploadMessage = "Course Added";    
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
        $displayMessage[]  = $row[1].' : Course Already Present';
        $uploadMessage = "Course Already Added";

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


//-----------------------------------------------------------------------------------------------------------------------
if(isset($_GET['Add_SemesterInstance_InBulk'])){

    $SectionMaster_Id = $_SESSION['schoolzone']['SectionMaster_Id'];  

    // check file name is not empty
    $pathinfo = pathinfo($_FILES["upload_file"]["name"]); //file extension

    $folderMap = '../';
    $fileLocation = "FileUploadLogs/SemesterMaster_".$SectionMaster_Id."_".date("Y-m-d-h-i-s").'.xlsx';
    $targetfolder =  $folderMap."".$fileLocation;
    move_uploaded_file($_FILES['upload_file']['name'], $targetfolder);

    $writer = WriterFactory::create(Type::XLSX); // for XLSX files

    $fileName= $targetfolder;
    $writer->openToFile($fileName); // write data to a file or to a PHP stream
    // $writer->openToBrowser($fileName); // stream data directly to the browser

    $singleRow =['Sr No','Semester Name','Old Even','Previous Semester Id','Upload Status'];
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

    $StreamDetails_q = mysqli_query($mysqli, "SELECT setup_semestermaster.* FROM setup_semestermaster Where setup_semestermaster.sectionmaster_Id = '$SectionMaster_Id' AND setup_semestermaster.Semester_Name = '".htmlspecialchars($row[1], ENT_QUOTES)."'");

    $row_StreamDetails = mysqli_num_rows($StreamDetails_q);



    if($row_StreamDetails == '0'){

        //checking Group and Sem Belong to this Program

        
    
    
        $Inserting_StaffQualification = mysqli_query($mysqli,"Insert into setup_semestermaster
        (sectionmaster_Id, Semester_Name, odd_even, Previous_Semester_Id) 
        Values
        ('$SectionMaster_Id', '".htmlspecialchars($row[1], ENT_QUOTES)."', '".htmlspecialchars($row[2], ENT_QUOTES)."', '".htmlspecialchars($row[3], ENT_QUOTES)."')");



        if(mysqli_error($mysqli)){
            $displayMessage[]  = $row[1].' : Query Failed';
            $uploadMessage = "Query Failed";
        }else{
            $displayMessage[]  = $row[1].' : Semester Added';
            $uploadMessage = "Semester Added";    
        }

        

        $multipleRows=[
            [$row[0],$row[1],$row[2],$row[3],$uploadMessage],
        ];
        $writer->addRows($multipleRows); // add multiple rows at a time

        
    }elseif($row_StreamDetails > '0'){
        $displayMessage[]  = $row[1].' : Semester Already Present';
        $uploadMessage = "Semester Already Added";

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
if(isset($_GET['Add_AcademicYearInstance'])){
   
    extract($_POST);

    $ActiveStaffLogin_Id = $_SESSION['schoolzone']['ActiveStaffLogin_Id'];
    $SectionMaster_Id = $_SESSION['schoolzone']['SectionMaster_Id'];

    //checking Department Abbbr & Name
    $depart_fetch_q = mysqli_query($mysqli,"SELECT setup_academicyear.* FROM setup_academicyear Where setup_academicyear.sectionmaster_Id = '$SectionMaster_Id' AND (setup_academicyear.academic_year = '$add_academic_year' OR setup_academicyear.abbreviation = '$add_abbreviation' OR setup_academicyear.sequence_no = '$add_sequence_no')");


    $row_depart_fetch = mysqli_num_rows($depart_fetch_q);

    if($row_depart_fetch > 0){

        
        $res['status'] = 'EXISTS';
        echo json_encode($res);

    }else{

        $Inserting_StaffQualification = mysqli_query($mysqli,"Insert into setup_academicyear
        (sectionmaster_Id, academic_year, abbreviation, start_date, end_date, sequence_no) 
        Values
        ('$SectionMaster_Id', '".htmlspecialchars($add_academic_year, ENT_QUOTES)."', '".htmlspecialchars($add_abbreviation, ENT_QUOTES)."', '".htmlspecialchars($add_start_date, ENT_QUOTES)."', '".htmlspecialchars($add_end_date, ENT_QUOTES)."', '".htmlspecialchars($add_sequence_no, ENT_QUOTES)."')");


        $res['status'] = 'success';
        echo json_encode($res);
    }

    
}
//-----------------------------------------------------------------------------------------------------------------------

//-----------------------------------------------------------------------------------------------------------------------
if(isset($_GET['Edit_AcademicYearInstance'])){

    extract($_POST);

     
    $updating_CalenderInstance = mysqli_query($mysqli,"Update setup_academicyear Set 
        academic_year = '".htmlspecialchars($edit_academic_year, ENT_QUOTES)."',
        abbreviation='".htmlspecialchars($edit_abbreviation, ENT_QUOTES)."',
        start_date='".htmlspecialchars($edit_start_date, ENT_QUOTES)."',
        end_date='".htmlspecialchars($edit_end_date, ENT_QUOTES)."',
        sequence_no='".htmlspecialchars($edit_sequence_no, ENT_QUOTES)."'
    where Id  = '$edit_InstanceId'");

    echo "200";
    
}
//-----------------------------------------------------------------------------------------------------------------------


//-----------------------------------------------------------------------------------------------------------------------
if(isset($_GET['Delete_AcademicYearInstance'])){

    extract($_POST);

    $deleting_formheader = mysqli_query($mysqli,"DELETE FROM setup_academicyear where Id = '$delete_instance_Id'");

    echo "200";
    
}
//-----------------------------------------------------------------------------------------------------------------------


//-----------------------------------------------------------------------------------------------------------------------
if(isset($_GET['Add_AcademicYearInstance_InBulk'])){

    $SectionMaster_Id = $_SESSION['schoolzone']['SectionMaster_Id'];  

    // check file name is not empty
    $pathinfo = pathinfo($_FILES["upload_file"]["name"]); //file extension

    $folderMap = '../';
    $fileLocation = "FileUploadLogs/AcademicYearMaster_".$SectionMaster_Id."_".date("Y-m-d-h-i-s").'.xlsx';
    $targetfolder =  $folderMap."".$fileLocation;
    move_uploaded_file($_FILES['upload_file']['name'], $targetfolder);

    $writer = WriterFactory::create(Type::XLSX); // for XLSX files

    $fileName= $targetfolder;
    $writer->openToFile($fileName); // write data to a file or to a PHP stream
    // $writer->openToBrowser($fileName); // stream data directly to the browser

    $singleRow =['Sr No','Academic Year','Abbrivation','Start Date','End Date','Sequence No','Upload Status'];
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

    $StreamDetails_q = mysqli_query($mysqli, "SELECT setup_academicyear.* FROM setup_academicyear Where setup_academicyear.sectionmaster_Id = '$SectionMaster_Id' AND (setup_academicyear.academic_year = '$row[1]' OR setup_academicyear.abbreviation = '$row[2]' OR setup_academicyear.sequence_no = '$row[5]')");

    $row_StreamDetails = mysqli_num_rows($StreamDetails_q);



    if($row_StreamDetails == '0'){

        //checking Group and Sem Belong to this Program

        
        $Inserting_StaffQualification = mysqli_query($mysqli,"Insert into setup_academicyear
        (sectionmaster_Id, academic_year, abbreviation, start_date, end_date, sequence_no) 
        Values
        ('$SectionMaster_Id', '".htmlspecialchars($row[1], ENT_QUOTES)."', '".htmlspecialchars($row[2], ENT_QUOTES)."', '".htmlspecialchars($row[3], ENT_QUOTES)."', '".htmlspecialchars($row[4], ENT_QUOTES)."', '".htmlspecialchars($row[5], ENT_QUOTES)."')");




        if(mysqli_error($mysqli)){
            $displayMessage[]  = $row[1].' : Query Failed';
            $uploadMessage = "Query Failed";
        }else{
            $displayMessage[]  = $row[1].' : Academic Year Added';
            $uploadMessage = "Academic Year Added";    
        }

        

        $multipleRows=[
            [$row[0],$row[1],$row[2],$row[3],$row[4],$row[5],$uploadMessage],
        ];
        $writer->addRows($multipleRows); // add multiple rows at a time

        
    }elseif($row_StreamDetails > '0'){
        $displayMessage[]  = $row[1].' : Academic Year Already Present Or Sequence No is Duplicate';
        $uploadMessage = "Academic Year Already Added  Or Sequence No is Duplicate";

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
if(isset($_GET['Change_AcademicYearInstance_DefaultEntry'])){
    $SectionMaster_Id = $_SESSION['schoolzone']['SectionMaster_Id'];  

    $AY_Id = $_POST['AY_Id'];

    //Make ALL 0
    $updating_Attend_Staff = mysqli_query($mysqli,"Update setup_academicyear set isDefault = '0' where sectionmaster_Id = '$SectionMaster_Id'");


    //Make Selected 1
    $updating_Attend_Staff = mysqli_query($mysqli,"Update setup_academicyear set isDefault = '1' where Id = '$AY_Id'");

    echo "200";
}
//-----------------------------------------------------------------------------------------------------------------------


//-----------------------------------------------------------------------------------------------------------------------
if(isset($_GET['Change_AcademicYearInstance_DefaultEntry_Student'])){
    $SectionMaster_Id = $_SESSION['schoolzone']['SectionMaster_Id'];  

    $AY_Id = $_POST['AY_Id'];

    //Make ALL 0
    $updating_Attend_Staff = mysqli_query($mysqli,"Update setup_academicyear set isDefault_Student = '0' where sectionmaster_Id = '$SectionMaster_Id'");


    //Make Selected 1
    $updating_Attend_Staff = mysqli_query($mysqli,"Update setup_academicyear set isDefault_Student = '1' where Id = '$AY_Id'");

    echo "200";
}
//-----------------------------------------------------------------------------------------------------------------------


//-----------------------------------------------------------------------------------------------------------------------
if(isset($_GET['Change_AcademicYearInstance_DefaultEntry_Candidate'])){
    $SectionMaster_Id = $_SESSION['schoolzone']['SectionMaster_Id'];  

    $AY_Id = $_POST['AY_Id'];

    //Make ALL 0
    $updating_Attend_Staff = mysqli_query($mysqli,"Update setup_academicyear set isDefault_Candidate = '0' where sectionmaster_Id = '$SectionMaster_Id'");


    //Make Selected 1
    $updating_Attend_Staff = mysqli_query($mysqli,"Update setup_academicyear set isDefault_Candidate = '1' where Id = '$AY_Id'");

    echo "200";
}
//-----------------------------------------------------------------------------------------------------------------------

//-----------------------------------------------------------------------------------------------------------------------
if(isset($_GET['Edit_BatchInstance'])){

    extract($_POST);

     
    $updating_CalenderInstance = mysqli_query($mysqli,"Update setup_batchmaster Set 
    batch_name ='".htmlspecialchars($edit_batch_name, ENT_QUOTES)."',
    abbreviation = '".htmlspecialchars($edit_abbreviation, ENT_QUOTES)."',
    seat_capacity='".htmlspecialchars($edit_seat_capacity, ENT_QUOTES)."',
    academicyear_Id='".htmlspecialchars($edit_academicyear_Id, ENT_QUOTES)."'
    where Id  = '$edit_InstanceId'");

    echo "200";
    
}
//-----------------------------------------------------------------------------------------------------------------------


//-----------------------------------------------------------------------------------------------------------------------
if(isset($_GET['Delete_BatchInstance'])){

    extract($_POST);

    $deleting_formheader = mysqli_query($mysqli,"DELETE FROM setup_batchmaster where Id = '$delete_instance_Id'");

    echo "200";
    
}
//-----------------------------------------------------------------------------------------------------------------------

//-----------------------------------------------------------------------------------------------------------------------
if(isset($_GET['Add_BatchInstance'])){
   
    extract($_POST);

    $ActiveStaffLogin_Id = $_SESSION['schoolzone']['ActiveStaffLogin_Id'];
    $SectionMaster_Id = $_SESSION['schoolzone']['SectionMaster_Id'];

    //checking Department Abbbr & Name
    $depart_fetch_q = mysqli_query($mysqli,"SELECT setup_batchmaster.* FROM setup_batchmaster WHERE setup_batchmaster.programmaster_Id = '$add_programmaster_Id' AND( setup_batchmaster.batch_name = '".htmlspecialchars($add_batch_name, ENT_QUOTES)."' OR setup_batchmaster.abbreviation = '".htmlspecialchars($add_abbreviation, ENT_QUOTES)."' )");


    $row_depart_fetch = mysqli_num_rows($depart_fetch_q);

    if($row_depart_fetch > 0){

        
        $res['status'] = 'EXISTS';
        echo json_encode($res);

    }else{

        $Inserting_StaffQualification = mysqli_query($mysqli,"Insert into setup_batchmaster
        (programmaster_Id, academicyear_Id, batch_name, abbreviation, seat_capacity) 
        Values
        ('$add_programmaster_Id', '".htmlspecialchars($add_academicyear_Id, ENT_QUOTES)."', '".htmlspecialchars($add_batch_name, ENT_QUOTES)."', '".htmlspecialchars($add_abbreviation, ENT_QUOTES)."', '".htmlspecialchars($add_seat_capacity, ENT_QUOTES)."')");
        
        $res['status'] = 'success';
        echo json_encode($res);
    }

    
}
//-----------------------------------------------------------------------------------------------------------------------


//-----------------------------------------------------------------------------------------------------------------------
if(isset($_GET['Add_BatchInstance_InBulk'])){

    $SectionMaster_Id = $_SESSION['schoolzone']['SectionMaster_Id'];  

    $program_sel_import = $_POST['program_sel_import'];
    // check file name is not empty
    $pathinfo = pathinfo($_FILES["upload_file"]["name"]); //file extension

    $folderMap = '../';
    $fileLocation = "FileUploadLogs/BatchMaster_".$SectionMaster_Id."_".date("Y-m-d-h-i-s").'.xlsx';
    $targetfolder =  $folderMap."".$fileLocation;
    move_uploaded_file($_FILES['upload_file']['name'], $targetfolder);

    $writer = WriterFactory::create(Type::XLSX); // for XLSX files

    $fileName= $targetfolder;
    $writer->openToFile($fileName); // write data to a file or to a PHP stream
    // $writer->openToBrowser($fileName); // stream data directly to the browser

    $singleRow =['Sr No','Batch Name','Abbreviation','Seat Capacity','Academic Year','Upload Status'];
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

    $StreamDetails_q = mysqli_query($mysqli, "SELECT setup_batchmaster.* FROM setup_batchmaster WHERE setup_batchmaster.programmaster_Id = '$program_sel_import' AND( setup_batchmaster.batch_name = '".htmlspecialchars($row[1], ENT_QUOTES)."' OR setup_batchmaster.abbreviation = '".htmlspecialchars($row[2], ENT_QUOTES)."' )");

    $row_StreamDetails = mysqli_num_rows($StreamDetails_q);



    if($row_StreamDetails == '0'){

        //checking Group and Sem Belong to this Program

        $checkinggroupDetails_q = mysqli_query($mysqli, "SELECT setup_academicyear.* FROM setup_academicyear Where setup_academicyear.sectionmaster_Id = '$SectionMaster_Id' AND setup_academicyear.Id = '$row[4]'");
        $row_checkinggroupDetails = mysqli_num_rows($checkinggroupDetails_q);

        if($row_checkinggroupDetails > '0'){


        

                $Inserting_StaffQualification = mysqli_query($mysqli,"Insert into setup_batchmaster
                (programmaster_Id, academicyear_Id, batch_name, abbreviation, seat_capacity) 
                Values
                ('$program_sel_import', '".htmlspecialchars($row[4], ENT_QUOTES)."', '".htmlspecialchars($row[1], ENT_QUOTES)."', '".htmlspecialchars($row[2], ENT_QUOTES)."', '".htmlspecialchars($row[3], ENT_QUOTES)."')");
                


                if(mysqli_error($mysqli)){
                    $displayMessage[]  = $row[1].' : Query Failed';
                    $uploadMessage = "Query Failed";
                }else{
                    $displayMessage[]  = $row[1].' : Batch Added';
                    $uploadMessage = "Batch Added";    
                }

                       //close checking Details
        }else{

            $displayMessage[]  = $row[1].' : Invalid Academic Id ';
            $uploadMessage = "Invalid Academic Id";    
        }// clsoe else

                
            $multipleRows=[
                [$row[0],$row[1],$row[2],$row[3],$row[4],$uploadMessage],
            ];
            $writer->addRows($multipleRows); // add multiple rows at a time

        
    }elseif($row_StreamDetails > '0'){
        $displayMessage[]  = $row[1].' : Batch Already Present';
        $uploadMessage = "Batch Already Added";

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
if(isset($_GET['Edit_BatchGroupInstance'])){

    extract($_POST);

     
    $updating_CalenderInstance = mysqli_query($mysqli,"Update setup_batchgroupmaster Set 
    batchgroup_name ='".htmlspecialchars($edit_batchgroup_name, ENT_QUOTES)."',
    abbreviation = '".htmlspecialchars($edit_abbreviation, ENT_QUOTES)."',
    Capacity='".htmlspecialchars($edit_capacity, ENT_QUOTES)."',
    prev_group='".htmlspecialchars($edit_prev_group, ENT_QUOTES)."',
    groupmasterid='".htmlspecialchars($edit_groupmasterid, ENT_QUOTES)."'
    where Id  = '$edit_InstanceId'");

    echo "200";
    
}
//-----------------------------------------------------------------------------------------------------------------------


//-----------------------------------------------------------------------------------------------------------------------
if(isset($_GET['Delete_BatchGroupInstance'])){

    extract($_POST);

    $deleting_formheader = mysqli_query($mysqli,"DELETE FROM setup_batchgroupmaster where Id = '$delete_instance_Id'");

    echo "200";
    
}
//-----------------------------------------------------------------------------------------------------------------------

//-----------------------------------------------------------------------------------------------------------------------
if(isset($_GET['Add_BatchGroupInstance'])){
   
    extract($_POST);

    $ActiveStaffLogin_Id = $_SESSION['schoolzone']['ActiveStaffLogin_Id'];
    $SectionMaster_Id = $_SESSION['schoolzone']['SectionMaster_Id'];

    //checking Department Abbbr & Name
    $depart_fetch_q = mysqli_query($mysqli,"SELECT setup_batchgroupmaster.* FROM setup_batchgroupmaster WHERE setup_batchgroupmaster.batchmasterid = '$add_batchmasterid' AND( setup_batchgroupmaster.batchgroup_name = '".htmlspecialchars($add_batchgroup_name, ENT_QUOTES)."' OR setup_batchgroupmaster.abbreviation = '".htmlspecialchars($add_abbreviation, ENT_QUOTES)."' )");


    $row_depart_fetch = mysqli_num_rows($depart_fetch_q);

    if($row_depart_fetch > 0){

        
        $res['status'] = 'EXISTS';
        echo json_encode($res);

    }else{

        $Inserting_StaffQualification = mysqli_query($mysqli,"Insert into setup_batchgroupmaster
        (batchmasterid, groupmasterid, batchgroup_name, abbreviation, Capacity, prev_group) 
        Values
        ('$add_batchmasterid', '".htmlspecialchars($add_groupmasterid, ENT_QUOTES)."', '".htmlspecialchars($add_batchgroup_name, ENT_QUOTES)."', '".htmlspecialchars($add_abbreviation, ENT_QUOTES)."', '".htmlspecialchars($add_capacity, ENT_QUOTES)."', '".htmlspecialchars($add_prev_group, ENT_QUOTES)."')");
        
        $res['status'] = 'success';
        echo json_encode($res);
    }

    
}
//-----------------------------------------------------------------------------------------------------------------------

//-----------------------------------------------------------------------------------------------------------------------
if(isset($_GET['Add_BatchGroupInstance_InBulk'])){

    $SectionMaster_Id = $_SESSION['schoolzone']['SectionMaster_Id'];  

    $batch_sel_import = $_POST['batch_sel_import'];
    // check file name is not empty
    $pathinfo = pathinfo($_FILES["upload_file"]["name"]); //file extension

    $folderMap = '../';
    $fileLocation = "FileUploadLogs/BatchGroupMaster_".$SectionMaster_Id."_".date("Y-m-d-h-i-s").'.xlsx';
    $targetfolder =  $folderMap."".$fileLocation;
    move_uploaded_file($_FILES['upload_file']['name'], $targetfolder);

    $writer = WriterFactory::create(Type::XLSX); // for XLSX files

    $fileName= $targetfolder;
    $writer->openToFile($fileName); // write data to a file or to a PHP stream
    // $writer->openToBrowser($fileName); // stream data directly to the browser

    $singleRow =['Sr No','Batchgroup Name','Abbreviation','Capacity','Previous Group','Group Master No','Upload Status'];
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

    $StreamDetails_q = mysqli_query($mysqli, "SELECT setup_batchgroupmaster.* FROM setup_batchgroupmaster WHERE setup_batchgroupmaster.batchmasterid = '$batch_sel_import' AND( setup_batchgroupmaster.batchgroup_name = '".htmlspecialchars($row[1], ENT_QUOTES)."' OR setup_batchgroupmaster.abbreviation = '".htmlspecialchars($row[2], ENT_QUOTES)."' )");

    $row_StreamDetails = mysqli_num_rows($StreamDetails_q);



    if($row_StreamDetails == '0'){

        //checking Group and Sem Belong to this Program

    


            $checkinggroupDetails_q = mysqli_query($mysqli, "SELECT setup_groupmaster.* FROM `setup_groupmaster` JOIN setup_batchmaster ON setup_batchmaster.programmaster_Id = setup_groupmaster.programmaster_Id WHERE setup_batchmaster.Id = '$batch_sel_import'  AND setup_groupmaster.Id = '$row[5]'");
            $row_checkinggroupDetails = mysqli_num_rows($checkinggroupDetails_q);

            if($row_checkinggroupDetails > '0'){


                $Inserting_StaffQualification = mysqli_query($mysqli,"Insert into setup_batchgroupmaster
                (batchmasterid, groupmasterid, batchgroup_name, abbreviation, Capacity, prev_group) 
                Values
                ('$batch_sel_import', '".htmlspecialchars($row[5], ENT_QUOTES)."', '".htmlspecialchars($row[1], ENT_QUOTES)."', '".htmlspecialchars($row[2], ENT_QUOTES)."', '".htmlspecialchars($row[3], ENT_QUOTES)."', '".htmlspecialchars($row[4], ENT_QUOTES)."')");

                if(mysqli_error($mysqli)){
                    $displayMessage[]  = $row[1].' : Query Failed';
                    $uploadMessage = "Query Failed";
                }else{
                    $displayMessage[]  = $row[1].' : Batch Group Added';
                    $uploadMessage = "Batch Group Added";    
                }

                

                //close checking Details
            }else{

                $displayMessage[]  = $row[1].' : Invalid Group Id';
                $uploadMessage = "Invalid Group Id";    
            }// clsoe else


                
            $multipleRows=[
                [$row[0],$row[1],$row[2],$row[3],$row[4],$row[5],$uploadMessage],
            ];
            $writer->addRows($multipleRows); // add multiple rows at a time

        
    }elseif($row_StreamDetails > '0'){
        $displayMessage[]  = $row[1].' : Batch Group Already Present';
        $uploadMessage = "Batch Group Already Added";

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
if(isset($_GET['Edit_BatchCourseInstance'])){

    extract($_POST);

     
    $updating_CalenderInstance = mysqli_query($mysqli,"Update setup_batchcoursemaster Set 
    groupmaster_Id ='".htmlspecialchars($edit_groupmaster_Id, ENT_QUOTES)."',
    coursemaster_Id = '".htmlspecialchars($edit_coursemaster_Id, ENT_QUOTES)."',
    semestermaster_Id='".htmlspecialchars($edit_semestermaster_Id, ENT_QUOTES)."',
    divisionmaster_Id='".htmlspecialchars($edit_divisionmaster_Id, ENT_QUOTES)."',
    batchCourse_Name='".htmlspecialchars($edit_batchCourse_Name, ENT_QUOTES)."',
    abbreviation='".htmlspecialchars($edit_abbreviation, ENT_QUOTES)."',
    course_numbering='".htmlspecialchars($edit_course_numbering, ENT_QUOTES)."',
    seat_capacity='".htmlspecialchars($edit_capacity, ENT_QUOTES)."'
    where Id  = '$edit_InstanceId'");

    echo "200";
    
}
//-----------------------------------------------------------------------------------------------------------------------


//-----------------------------------------------------------------------------------------------------------------------
if(isset($_GET['Delete_BatchCourseInstance'])){

    extract($_POST);

    $deleting_formheader = mysqli_query($mysqli,"DELETE FROM setup_batchcoursemaster where Id = '$delete_instance_Id'");

    echo "200";
    
}
//-----------------------------------------------------------------------------------------------------------------------

//-----------------------------------------------------------------------------------------------------------------------
if(isset($_GET['Add_BatchCourseInstance'])){
   
    extract($_POST);

    $ActiveStaffLogin_Id = $_SESSION['schoolzone']['ActiveStaffLogin_Id'];
    $SectionMaster_Id = $_SESSION['schoolzone']['SectionMaster_Id'];

    //checking Department Abbbr & Name
    $depart_fetch_q = mysqli_query($mysqli,"SELECT setup_batchcoursemaster.* FROM setup_batchcoursemaster WHERE setup_batchcoursemaster.batchmaster_Id = '$add_batchmaster_Id' AND( setup_batchcoursemaster.batchCourse_Name = '".htmlspecialchars($add_batchCourse_Name, ENT_QUOTES)."' OR setup_batchcoursemaster.abbreviation = '".htmlspecialchars($add_abbreviation, ENT_QUOTES)."' )");


    $row_depart_fetch = mysqli_num_rows($depart_fetch_q);

    if($row_depart_fetch > 0){

        
        $res['status'] = 'EXISTS';
        echo json_encode($res);

    }else{

        $Inserting_StaffQualification = mysqli_query($mysqli,"Insert into setup_batchcoursemaster
        (batchmaster_Id, groupmaster_Id, coursemaster_Id, semestermaster_Id, divisionmaster_Id, batchCourse_Name, abbreviation, course_numbering, seat_capacity) 
        Values
        ('$add_batchmaster_Id', '".htmlspecialchars($add_groupmaster_Id, ENT_QUOTES)."', '".htmlspecialchars($add_coursemaster_Id, ENT_QUOTES)."', '".htmlspecialchars($add_semestermaster_Id, ENT_QUOTES)."', '".htmlspecialchars($add_divisionmaster_Id, ENT_QUOTES)."', '".htmlspecialchars($add_batchCourse_Name, ENT_QUOTES)."', '".htmlspecialchars($add_abbreviation, ENT_QUOTES)."', '".htmlspecialchars($add_course_numbering, ENT_QUOTES)."', '".htmlspecialchars($add_capacity, ENT_QUOTES)."')");

        $res['status'] = 'success';
        echo json_encode($res);
    }

    
}
//-----------------------------------------------------------------------------------------------------------------------


//-----------------------------------------------------------------------------------------------------------------------
if(isset($_GET['Add_BatchCourseInstance_InBulk'])){

    $SectionMaster_Id = $_SESSION['schoolzone']['SectionMaster_Id'];  

    $batch_sel_import = $_POST['batch_sel_import'];
    // check file name is not empty
    $pathinfo = pathinfo($_FILES["upload_file"]["name"]); //file extension

    $folderMap = '../';
    $fileLocation = "FileUploadLogs/BatchCourseMaster_".$SectionMaster_Id."_".date("Y-m-d-h-i-s").'.xlsx';
    $targetfolder =  $folderMap."".$fileLocation;
    move_uploaded_file($_FILES['upload_file']['name'], $targetfolder);

    $writer = WriterFactory::create(Type::XLSX); // for XLSX files

    $fileName= $targetfolder;
    $writer->openToFile($fileName); // write data to a file or to a PHP stream
    // $writer->openToBrowser($fileName); // stream data directly to the browser

    $singleRow =['Sr No','BatchCourse Name','Abbreviation','Capacity','Course Numbering','Group No','Course No','Sem No','Division No','Upload Status'];
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

    $StreamDetails_q = mysqli_query($mysqli, "SELECT setup_batchcoursemaster.* FROM setup_batchcoursemaster WHERE setup_batchcoursemaster.batchmaster_Id = '$batch_sel_import' AND( setup_batchcoursemaster.batchCourse_Name = '".htmlspecialchars($row[1], ENT_QUOTES)."' OR setup_batchcoursemaster.abbreviation = '".htmlspecialchars($$row[2], ENT_QUOTES)."' )");

    $row_StreamDetails = mysqli_num_rows($StreamDetails_q);



    if($row_StreamDetails == '0'){

        //checking Group and Sem Belong to this Program

    


            $checkinggroupDetails_q = mysqli_query($mysqli, "SELECT setup_groupmaster.* FROM `setup_groupmaster` JOIN setup_batchmaster ON setup_batchmaster.programmaster_Id = setup_groupmaster.programmaster_Id WHERE setup_batchmaster.Id = '$batch_sel_import'  AND setup_groupmaster.Id = '$row[5]'");
            $row_checkinggroupDetails = mysqli_num_rows($checkinggroupDetails_q);

            $checkingcourseDetails_q = mysqli_query($mysqli, "SELECT setup_coursemaster.* FROM setup_coursemaster JOIN setup_batchmaster ON setup_batchmaster.programmaster_Id = setup_coursemaster.programmaster_Id WHERE setup_batchmaster.Id = '$batch_sel_import'   AND setup_coursemaster.Id = '$row[6]'");
            $row_checkingcourseDetails = mysqli_num_rows($checkingcourseDetails_q);

            $checkingsemDetails_q = mysqli_query($mysqli, "SELECT setup_semestermaster.* FROM setup_semestermaster WHERE setup_semestermaster.sectionmaster_Id = '$SectionMaster_Id'    AND setup_semestermaster.Id = '$row[7]'");
            $row_checkingsemDetails = mysqli_num_rows($checkingsemDetails_q);

            $checkingdivDetails_q = mysqli_query($mysqli, "SELECT setup_divisionmaster.* FROM setup_divisionmaster WHERE setup_divisionmaster.sectionmaster_Id = '$SectionMaster_Id'    AND setup_divisionmaster.Id = '$row[8]'");
            $row_checkingdivDetails = mysqli_num_rows($checkingdivDetails_q);

            if($row_checkinggroupDetails > '0' AND $row_checkingcourseDetails > '0' AND $row_checkingsemDetails > '0' AND $row_checkingdivDetails > '0'){


                $Inserting_StaffQualification = mysqli_query($mysqli,"Insert into setup_batchcoursemaster
                (batchmaster_Id, groupmaster_Id, coursemaster_Id, semestermaster_Id, divisionmaster_Id, batchCourse_Name, abbreviation, course_numbering, seat_capacity) 
                Values
                ('$batch_sel_import', '".htmlspecialchars($row[5], ENT_QUOTES)."', '".htmlspecialchars($row[6], ENT_QUOTES)."', '".htmlspecialchars($row[7], ENT_QUOTES)."', '".htmlspecialchars($row[8], ENT_QUOTES)."', '".htmlspecialchars($row[1], ENT_QUOTES)."', '".htmlspecialchars($row[2], ENT_QUOTES)."', '".htmlspecialchars($row[4], ENT_QUOTES)."', '".htmlspecialchars($row[3], ENT_QUOTES)."')");
            

                if(mysqli_error($mysqli)){
                    $displayMessage[]  = $row[1].' : Query Failed';
                    $uploadMessage = "Query Failed";
                }else{
                    $displayMessage[]  = $row[1].' : Batch Course Added';
                    $uploadMessage = "Batch Course Added";    
                }

                

                //close checking Details
            }else{

                $displayMessage[]  = $row[1].' : Invalid Group No OR Sem No, Division No, Course No';
                $uploadMessage = "Invalid Group No OR Sem No, Division No, Course No";    
            }// clsoe else


                
            $multipleRows=[
                [$row[0],$row[1],$row[2],$row[3],$row[4],$row[5],$row[6],$row[7],$row[8],$uploadMessage],
            ];
            $writer->addRows($multipleRows); // add multiple rows at a time

        
    }elseif($row_StreamDetails > '0'){
        $displayMessage[]  = $row[1].' : Batch Course Already Present';
        $uploadMessage = "Batch Course Already Added";

        $multipleRows=[
            [$row[0],$row[1],$row[2],$row[3],$row[4],$row[5],$row[6],$row[7],$row[8],$uploadMessage],
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
if(isset($_GET['Add_DivisionInstance'])){
   
    extract($_POST);

    $ActiveStaffLogin_Id = $_SESSION['schoolzone']['ActiveStaffLogin_Id'];
    $SectionMaster_Id = $_SESSION['schoolzone']['SectionMaster_Id'];

    //checking Department Abbbr & Name
    $depart_fetch_q = mysqli_query($mysqli,"SELECT setup_divisionmaster.* FROM setup_divisionmaster Where setup_divisionmaster.sectionmaster_Id = '$SectionMaster_Id' AND setup_divisionmaster.Division = '".htmlspecialchars($add_Division, ENT_QUOTES)."'");


    $row_depart_fetch = mysqli_num_rows($depart_fetch_q);

    if($row_depart_fetch > 0){

        
        $res['status'] = 'EXISTS';
        echo json_encode($res);

    }else{

        $Inserting_StaffQualification = mysqli_query($mysqli,"Insert into setup_divisionmaster
        (sectionmaster_Id, Division) 
        Values
        ('$SectionMaster_Id', '".htmlspecialchars($add_Division, ENT_QUOTES)."')");


        $res['status'] = 'success';
        echo json_encode($res);
    }

    
}
//-----------------------------------------------------------------------------------------------------------------------

//-----------------------------------------------------------------------------------------------------------------------
if(isset($_GET['Edit_DivisionInstance'])){

    extract($_POST);

     
    $updating_CalenderInstance = mysqli_query($mysqli,"Update setup_divisionmaster Set Division = '".htmlspecialchars($edit_Division, ENT_QUOTES)."' where Id  = '$edit_InstanceId'");

    echo "200";
    
}
//-----------------------------------------------------------------------------------------------------------------------


//-----------------------------------------------------------------------------------------------------------------------
if(isset($_GET['Delete_DivisionInstance'])){

    extract($_POST);

    $deleting_formheader = mysqli_query($mysqli,"DELETE FROM setup_divisionmaster where Id = '$delete_instance_Id'");

    echo "200";
    
}
//-----------------------------------------------------------------------------------------------------------------------


//-----------------------------------------------------------------------------------------------------------------------
if(isset($_GET['Add_DivisionInstance_InBulk'])){

    $SectionMaster_Id = $_SESSION['schoolzone']['SectionMaster_Id'];  

    // check file name is not empty
    $pathinfo = pathinfo($_FILES["upload_file"]["name"]); //file extension

    $folderMap = '../';
    $fileLocation = "FileUploadLogs/DivisionMaster_".$SectionMaster_Id."_".date("Y-m-d-h-i-s").'.xlsx';
    $targetfolder =  $folderMap."".$fileLocation;
    move_uploaded_file($_FILES['upload_file']['name'], $targetfolder);

    $writer = WriterFactory::create(Type::XLSX); // for XLSX files

    $fileName= $targetfolder;
    $writer->openToFile($fileName); // write data to a file or to a PHP stream
    // $writer->openToBrowser($fileName); // stream data directly to the browser

    $singleRow =['Sr No','Division Name','Upload Status'];
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

    $StreamDetails_q = mysqli_query($mysqli, "SELECT setup_divisionmaster.* FROM setup_divisionmaster Where setup_divisionmaster.sectionmaster_Id = '$SectionMaster_Id' AND setup_divisionmaster.Division = '".htmlspecialchars($row[1], ENT_QUOTES)."'");

    $row_StreamDetails = mysqli_num_rows($StreamDetails_q);



    if($row_StreamDetails == '0'){

        //checking Group and Sem Belong to this Program

        
    
    
        $Inserting_StaffQualification = mysqli_query($mysqli,"Insert into setup_divisionmaster
        (sectionmaster_Id, Division) 
        Values
        ('$SectionMaster_Id', '".htmlspecialchars($row[1], ENT_QUOTES)."')");



        if(mysqli_error($mysqli)){
            $displayMessage[]  = $row[1].' : Query Failed';
            $uploadMessage = "Query Failed";
        }else{
            $displayMessage[]  = $row[1].' : Division Added';
            $uploadMessage = "Division Added";    
        }

        

        $multipleRows=[
            [$row[0],$row[1],$uploadMessage],
        ];
        $writer->addRows($multipleRows); // add multiple rows at a time

        
    }elseif($row_StreamDetails > '0'){
        $displayMessage[]  = $row[1].' : Division Already Present';
        $uploadMessage = "Division Already Added";

        $multipleRows=[
            [$row[0],$row[1],$uploadMessage],
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
if(isset($_GET['Edit_ResultAssessment'])){

    extract($_POST);

     
    $updating_CalenderInstance = mysqli_query($mysqli,"Update result_assessment_pattern Set 
    examheader_Id ='".htmlspecialchars($edit_examheader_Id, ENT_QUOTES)."',
    coursemaster_Id = '".htmlspecialchars($edit_coursemaster_Id, ENT_QUOTES)."',
    out_of_marks='".htmlspecialchars($edit_out_of_marks, ENT_QUOTES)."',
    passing_marks='".htmlspecialchars($edit_passing_marks, ENT_QUOTES)."'
    where Id  = '$edit_InstanceId'");

    echo "200";
    
}
//-----------------------------------------------------------------------------------------------------------------------


//-----------------------------------------------------------------------------------------------------------------------
if(isset($_GET['Delete_ResultAssessment'])){

    extract($_POST);

    $deleting_formheader = mysqli_query($mysqli,"DELETE FROM result_assessment_pattern where Id = '$delete_instance_Id'");

    echo "200";
    
}
//-----------------------------------------------------------------------------------------------------------------------

//-----------------------------------------------------------------------------------------------------------------------
if(isset($_GET['Add_ResultAssessment'])){
   
    extract($_POST);

    $ActiveStaffLogin_Id = $_SESSION['schoolzone']['ActiveStaffLogin_Id'];
    $SectionMaster_Id = $_SESSION['schoolzone']['SectionMaster_Id'];

    //checking Department Abbbr & Name
    $depart_fetch_q = mysqli_query($mysqli,"SELECT result_assessment_pattern.* FROM result_assessment_pattern JOIN setup_coursemaster ON setup_coursemaster.Id = result_assessment_pattern.coursemaster_Id WHERE result_assessment_pattern.examheader_Id = '$add_examheader_Id' AND result_assessment_pattern.coursemaster_Id = '$add_coursemaster_Id' AND setup_coursemaster.programmaster_Id = '$add_programmaster_Id' ");


    $row_depart_fetch = mysqli_num_rows($depart_fetch_q);

    if($row_depart_fetch > 0){

        
        $res['status'] = 'EXISTS';
        echo json_encode($res);

    }else{

        $Inserting_StaffQualification = mysqli_query($mysqli,"Insert into result_assessment_pattern
        (examheader_Id, coursemaster_Id, out_of_marks, passing_marks) 
        Values
        ('$add_examheader_Id', '".htmlspecialchars($add_coursemaster_Id, ENT_QUOTES)."', '".htmlspecialchars($add_out_of_marks, ENT_QUOTES)."', '".htmlspecialchars($add_passing_marks, ENT_QUOTES)."')");
        
        $res['status'] = 'success';
        echo json_encode($res);
    }

    
}
//-----------------------------------------------------------------------------------------------------------------------


//-----------------------------------------------------------------------------------------------------------------------
if(isset($_GET['Add_ResultAssessment_InBulk'])){

    $SectionMaster_Id = $_SESSION['schoolzone']['SectionMaster_Id'];  

    $program_sel_import = $_POST['program_sel_import'];
    // check file name is not empty
    $pathinfo = pathinfo($_FILES["upload_file"]["name"]); //file extension

    $folderMap = '../';
    $fileLocation = "FileUploadLogs/ResultAssessmentMaster_".$SectionMaster_Id."_".date("Y-m-d-h-i-s").'.xlsx';
    $targetfolder =  $folderMap."".$fileLocation;
    move_uploaded_file($_FILES['upload_file']['name'], $targetfolder);

    $writer = WriterFactory::create(Type::XLSX); // for XLSX files

    $fileName= $targetfolder;
    $writer->openToFile($fileName); // write data to a file or to a PHP stream
    // $writer->openToBrowser($fileName); // stream data directly to the browser

    $singleRow =['Sr No','Exam Header No','Course No','Out of Marks','Passing Marks','Upload Status'];
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

    $StreamDetails_q = mysqli_query($mysqli, "SELECT result_assessment_pattern.* FROM result_assessment_pattern JOIN setup_coursemaster ON setup_coursemaster.Id = result_assessment_pattern.coursemaster_Id WHERE result_assessment_pattern.examheader_Id = '$row[1]' AND result_assessment_pattern.coursemaster_Id = '$row[2]' AND setup_coursemaster.programmaster_Id = '$program_sel_import'");

    $row_StreamDetails = mysqli_num_rows($StreamDetails_q);



    if($row_StreamDetails == '0'){

        //checking Group and Sem Belong to this Program

        
        $checkingheaderDetails_q = mysqli_query($mysqli, "SELECT result_exam_header.* FROM result_exam_header Where result_exam_header.sectionmaster_Id = '$SectionMaster_Id' AND result_exam_header.Id = '$row[1]'");
        $row_checkingheaderDetails = mysqli_num_rows($checkingheaderDetails_q);

        $checkingcourseDetails_q = mysqli_query($mysqli, "SELECT setup_coursemaster.* FROM `setup_coursemaster` WHERE setup_coursemaster.programmaster_Id = '$program_sel_import' AND setup_coursemaster.Id = '$row[2]'");
        $row_checkingcourseDetails = mysqli_num_rows($checkingcourseDetails_q);

        if($row_checkingheaderDetails > '0' AND $row_checkingcourseDetails > '0'){
            
        
            $Inserting_StaffQualification = mysqli_query($mysqli,"Insert into result_assessment_pattern
            (examheader_Id, coursemaster_Id, out_of_marks, passing_marks) 
            Values
            ('$row[1]', '".htmlspecialchars($row[2], ENT_QUOTES)."', '".htmlspecialchars($row[3], ENT_QUOTES)."', '".htmlspecialchars($row[4], ENT_QUOTES)."')");


            if(mysqli_error($mysqli)){
                $displayMessage[]  = $row[1].' : Query Failed';
                $uploadMessage = "Query Failed";
            }else{
                $displayMessage[]  = $row[1].' : Assessment Pattern Entry Added';
                $uploadMessage = "Assessment Pattern Entry Added";    
            }
            
        //close checking Details
        }else{

            $displayMessage[]  = $row[1].' : Invalid Course Id or Exam Header Id';
            $uploadMessage = "Invalid Course Id or Exam Header";    
        }// clsoe else

            

        $multipleRows=[
            [$row[0],$row[1],$row[2],$row[3],$row[4],$uploadMessage],
        ];
        $writer->addRows($multipleRows); // add multiple rows at a time

        
    }elseif($row_StreamDetails > '0'){
        $displayMessage[]  = $row[1].' : Assessment Pattern Entry Already Present';
        $uploadMessage = "Assessment Pattern Entry Already Added";

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
if(isset($_GET['Edit_ExamInstance'])){

    extract($_POST);

     
    $updating_CalenderInstance = mysqli_query($mysqli,"Update exam_master Set 
    Maximum_marks ='".htmlspecialchars($edit_Maximum_marks, ENT_QUOTES)."',
    Date = '".htmlspecialchars($edit_Date, ENT_QUOTES)."',
    passing_marks='".htmlspecialchars($edit_passing_marks, ENT_QUOTES)."'
    where Id  = '$edit_InstanceId'");

    echo "200";
    
}
//-----------------------------------------------------------------------------------------------------------------------


//-----------------------------------------------------------------------------------------------------------------------
if(isset($_GET['Delete_ExamInstance'])){

    extract($_POST);

    $deleting_formheader = mysqli_query($mysqli,"DELETE FROM exam_master where Id = '$delete_instance_Id'");

    echo "200";
    
}
//-----------------------------------------------------------------------------------------------------------------------

//-----------------------------------------------------------------------------------------------------------------------
if(isset($_GET['Add_ExamInstance'])){
   
    extract($_POST);

    $ActiveStaffLogin_Id = $_SESSION['schoolzone']['ActiveStaffLogin_Id'];
    $SectionMaster_Id = $_SESSION['schoolzone']['SectionMaster_Id'];

    //checking Department Abbbr & Name
    $depart_fetch_q = mysqli_query($mysqli,"SELECT exam_master.* FROM `exam_master` WHERE exam_master.batchcoursemaster_Id = '$add_batchcoursemaster_Id' ");


    $row_depart_fetch = mysqli_num_rows($depart_fetch_q);

    if($row_depart_fetch > 0){

        
        $res['status'] = 'EXISTS';
        echo json_encode($res);

    }else{

        $Inserting_StaffQualification = mysqli_query($mysqli,"Insert into exam_master
        (batchcoursemaster_Id, Maximum_marks, Date, passing_marks) 
        Values
        ('$add_batchcoursemaster_Id', '".htmlspecialchars($add_Maximum_marks, ENT_QUOTES)."', '".htmlspecialchars($add_Date, ENT_QUOTES)."', '".htmlspecialchars($add_passing_marks, ENT_QUOTES)."')");

        $res['status'] = 'success';
        echo json_encode($res);
    }

    
}
//-----------------------------------------------------------------------------------------------------------------------


//-----------------------------------------------------------------------------------------------------------------------
if(isset($_GET['Add_ExamInstance_InBulk'])){

    $SectionMaster_Id = $_SESSION['schoolzone']['SectionMaster_Id'];  

    $batchcourse_sel_import = $_POST['batchcourse_sel_import'];
    // check file name is not empty
    $pathinfo = pathinfo($_FILES["upload_file"]["name"]); //file extension

    $folderMap = '../';
    $fileLocation = "FileUploadLogs/ExamMaster_".$SectionMaster_Id."_".date("Y-m-d-h-i-s").'.xlsx';
    $targetfolder =  $folderMap."".$fileLocation;
    move_uploaded_file($_FILES['upload_file']['name'], $targetfolder);

    $writer = WriterFactory::create(Type::XLSX); // for XLSX files

    $fileName= $targetfolder;
    $writer->openToFile($fileName); // write data to a file or to a PHP stream
    // $writer->openToBrowser($fileName); // stream data directly to the browser

    $singleRow =['Sr No','Maximum Marks','Date','Passing Marks','Upload Status'];
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

    $StreamDetails_q = mysqli_query($mysqli, "SELECT exam_master.* FROM `exam_master` WHERE exam_master.batchcoursemaster_Id = '$batchcourse_sel_import'");

    $row_StreamDetails = mysqli_num_rows($StreamDetails_q);



    if($row_StreamDetails == '0'){

        //checking Group and Sem Belong to this Program

    
        $Inserting_StaffQualification = mysqli_query($mysqli,"Insert into exam_master
        (batchcoursemaster_Id, Maximum_marks, Date, passing_marks) 
        Values
        ('$batchcourse_sel_import', '".htmlspecialchars($row[1], ENT_QUOTES)."', '".htmlspecialchars($row[2], ENT_QUOTES)."', '".htmlspecialchars($row[3], ENT_QUOTES)."')");

            

                if(mysqli_error($mysqli)){
                    $displayMessage[]  = $row[1].' : Query Failed';
                    $uploadMessage = "Query Failed";
                }else{
                    $displayMessage[]  = $row[1].' : Exam Added';
                    $uploadMessage = "Exam Added";    
                }

                

                
            $multipleRows=[
                [$row[0],$row[1],$row[2],$row[3],$uploadMessage],
            ];
            $writer->addRows($multipleRows); // add multiple rows at a time

        
    }elseif($row_StreamDetails > '0'){
        $displayMessage[]  = $row[1].' : Exam Already Present';
        $uploadMessage = "Exam Already Added";

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
if(isset($_GET['Add_FeeHeaderTypeInstance'])){
   
    extract($_POST);

    $ActiveStaffLogin_Id = $_SESSION['schoolzone']['ActiveStaffLogin_Id'];
    $SectionMaster_Id = $_SESSION['schoolzone']['SectionMaster_Id'];

    //checking Department Abbbr & Name
    $depart_fetch_q = mysqli_query($mysqli,"SELECT fee_headertype.* FROM fee_headertype Where ( fee_headertype.headertype_name = '".htmlspecialchars($add_headertype_name, ENT_QUOTES)."' OR fee_headertype.abbreviation = '".htmlspecialchars($add_abbreviation, ENT_QUOTES)."' ) ");


    $row_depart_fetch = mysqli_num_rows($depart_fetch_q);

    if($row_depart_fetch > 0){

        
        $res['status'] = 'EXISTS';
        echo json_encode($res);

    }else{

        $Inserting_StaffQualification = mysqli_query($mysqli,"Insert into fee_headertype
        ( headertype_name, abbreviation, sequence) 
        Values
        ( '".htmlspecialchars($add_headertype_name, ENT_QUOTES)."', '".htmlspecialchars($add_abbreviation, ENT_QUOTES)."', '".htmlspecialchars($add_sequence, ENT_QUOTES)."')");
        
        $res['status'] = 'success';
        echo json_encode($res);
    }

    
}
//-----------------------------------------------------------------------------------------------------------------------

//-----------------------------------------------------------------------------------------------------------------------
if(isset($_GET['Edit_FeeHeaderTypeInstance'])){

    extract($_POST);

     
    $updating_CalenderInstance = mysqli_query($mysqli,"Update fee_headertype Set headertype_name = '".htmlspecialchars($edit_headertype_name, ENT_QUOTES)."',abbreviation='".htmlspecialchars($edit_abbreviation, ENT_QUOTES)."',sequence='".htmlspecialchars($edit_sequence, ENT_QUOTES)."' where Id  = '$edit_InstanceId'");

    echo "200";
    
}
//-----------------------------------------------------------------------------------------------------------------------


//-----------------------------------------------------------------------------------------------------------------------
if(isset($_GET['Delete_FeeHeaderTypeInstance'])){

    extract($_POST);

    $deleting_formheader = mysqli_query($mysqli,"DELETE FROM fee_headertype where Id = '$delete_instance_Id'");

    echo "200";
    
}
//-----------------------------------------------------------------------------------------------------------------------


//-----------------------------------------------------------------------------------------------------------------------
if(isset($_GET['Add_FeeHeaderTypeInstance_InBulk'])){

    $SectionMaster_Id = $_SESSION['schoolzone']['SectionMaster_Id'];  

    // check file name is not empty
    $pathinfo = pathinfo($_FILES["upload_file"]["name"]); //file extension

    $folderMap = '../';
    $fileLocation = "FileUploadLogs/FeeHeaderTypeMaster_".$SectionMaster_Id."_".date("Y-m-d-h-i-s").'.xlsx';
    $targetfolder =  $folderMap."".$fileLocation;
    move_uploaded_file($_FILES['upload_file']['name'], $targetfolder);

    $writer = WriterFactory::create(Type::XLSX); // for XLSX files

    $fileName= $targetfolder;
    $writer->openToFile($fileName); // write data to a file or to a PHP stream
    // $writer->openToBrowser($fileName); // stream data directly to the browser

    $singleRow =['Sr No','Header Type Name','Abbreviation','Sequence','Upload Status'];
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

    $StreamDetails_q = mysqli_query($mysqli, "SELECT fee_headertype.* FROM fee_headertype Where ( fee_headertype.headertype_name = '".htmlspecialchars($row[1], ENT_QUOTES)."' OR fee_headertype.abbreviation = '".htmlspecialchars($row[2], ENT_QUOTES)."' ) ");

    $row_StreamDetails = mysqli_num_rows($StreamDetails_q);



    if($row_StreamDetails == '0'){

        //checking Group and Sem Belong to this Program

        
            $Inserting_StaffQualification = mysqli_query($mysqli,"Insert into fee_headertype
            ( headertype_name, abbreviation, sequence) 
            Values
            ('".htmlspecialchars($row[1], ENT_QUOTES)."', '".htmlspecialchars($row[2], ENT_QUOTES)."', '".htmlspecialchars($row[3], ENT_QUOTES)."')");



            if(mysqli_error($mysqli)){
                $displayMessage[]  = $row[2].' : Query Failed';
                $uploadMessage = "Query Failed";
            }else{
                $displayMessage[]  = $row[2].' : Fee Structure Added';
                $uploadMessage = "Fee Structure Added";    
            }

         //close checking Details
        

        $multipleRows=[
            [$row[0],$row[1],$row[2],$row[3],$uploadMessage],
        ];
        $writer->addRows($multipleRows); // add multiple rows at a time

        
    }elseif($row_StreamDetails > '0'){
        $displayMessage[]  = $row[2].' : Fee Structure Already Present';
        $uploadMessage = "Fee Structure Already Added";

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
if(isset($_GET['Add_FeeStructureInstance'])){
   
    extract($_POST);

    $ActiveStaffLogin_Id = $_SESSION['schoolzone']['ActiveStaffLogin_Id'];
    $SectionMaster_Id = $_SESSION['schoolzone']['SectionMaster_Id'];

    //checking Department Abbbr & Name
    $depart_fetch_q = mysqli_query($mysqli,"SELECT fee_structure_master.* FROM fee_structure_master Where ( fee_structure_master.Fee_Structure_Name = '".htmlspecialchars($add_Fee_Structure_Name, ENT_QUOTES)."' OR fee_structure_master.abbreviation = '".htmlspecialchars($add_abbreviation, ENT_QUOTES)."' ) AND  fee_structure_master.sectionmaster_Id = '$SectionMaster_Id'");


    $row_depart_fetch = mysqli_num_rows($depart_fetch_q);

    if($row_depart_fetch > 0){

        
        $res['status'] = 'EXISTS';
        echo json_encode($res);

    }else{

        $Inserting_StaffQualification = mysqli_query($mysqli,"Insert into fee_structure_master
        (sectionmaster_Id, Fee_Structure_Name, abbreviation, Annual_Frequemcy) 
        Values
        ('$SectionMaster_Id', '".htmlspecialchars($add_Fee_Structure_Name, ENT_QUOTES)."', '".htmlspecialchars($add_abbreviation, ENT_QUOTES)."', '".htmlspecialchars($add_Annual_Frequemcy, ENT_QUOTES)."')");
        
        $res['status'] = 'success';
        echo json_encode($res);
    }

    
}
//-----------------------------------------------------------------------------------------------------------------------

//-----------------------------------------------------------------------------------------------------------------------
if(isset($_GET['Edit_FeeStructureInstance'])){

    extract($_POST);

     
    $updating_CalenderInstance = mysqli_query($mysqli,"Update fee_structure_master Set Fee_Structure_Name = '".htmlspecialchars($edit_Fee_Structure_Name, ENT_QUOTES)."',abbreviation='".htmlspecialchars($edit_abbreviation, ENT_QUOTES)."',Annual_Frequemcy='".htmlspecialchars($edit_Annual_Frequemcy, ENT_QUOTES)."' where Id  = '$edit_InstanceId'");

    echo "200";
    
}
//-----------------------------------------------------------------------------------------------------------------------


//-----------------------------------------------------------------------------------------------------------------------
if(isset($_GET['Delete_FeeStructureInstance'])){

    extract($_POST);

    $deleting_formheader = mysqli_query($mysqli,"DELETE FROM fee_structure_master where Id = '$delete_instance_Id'");

    echo "200";
    
}
//-----------------------------------------------------------------------------------------------------------------------


//-----------------------------------------------------------------------------------------------------------------------
if(isset($_GET['Add_FeeStructureInstance_InBulk'])){

    $SectionMaster_Id = $_SESSION['schoolzone']['SectionMaster_Id'];  

    // check file name is not empty
    $pathinfo = pathinfo($_FILES["upload_file"]["name"]); //file extension

    $folderMap = '../';
    $fileLocation = "FileUploadLogs/FeeStructureMaster_".$SectionMaster_Id."_".date("Y-m-d-h-i-s").'.xlsx';
    $targetfolder =  $folderMap."".$fileLocation;
    move_uploaded_file($_FILES['upload_file']['name'], $targetfolder);

    $writer = WriterFactory::create(Type::XLSX); // for XLSX files

    $fileName= $targetfolder;
    $writer->openToFile($fileName); // write data to a file or to a PHP stream
    // $writer->openToBrowser($fileName); // stream data directly to the browser

    $singleRow =['Sr No','Fee Structure Name','Abbreviation','Annual Frequemcy','Upload Status'];
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

    $StreamDetails_q = mysqli_query($mysqli, "SELECT fee_structure_master.* FROM fee_structure_master Where ( fee_structure_master.Fee_Structure_Name = '".htmlspecialchars($row[1], ENT_QUOTES)."' OR fee_structure_master.abbreviation = '".htmlspecialchars($row[2], ENT_QUOTES)."' ) AND  fee_structure_master.sectionmaster_Id = '$SectionMaster_Id'");

    $row_StreamDetails = mysqli_num_rows($StreamDetails_q);



    if($row_StreamDetails == '0'){

        //checking Group and Sem Belong to this Program

        
            $Inserting_StaffQualification = mysqli_query($mysqli,"Insert into fee_structure_master
            (sectionmaster_Id, Fee_Structure_Name, abbreviation, Annual_Frequemcy) 
            Values
            ('$SectionMaster_Id', '".htmlspecialchars($row[1], ENT_QUOTES)."', '".htmlspecialchars($row[2], ENT_QUOTES)."', '".htmlspecialchars($row[3], ENT_QUOTES)."')");



            if(mysqli_error($mysqli)){
                $displayMessage[]  = $row[2].' : Query Failed';
                $uploadMessage = "Query Failed";
            }else{
                $displayMessage[]  = $row[2].' : Fee Structure Added';
                $uploadMessage = "Fee Structure Added";    
            }

         //close checking Details
        

        $multipleRows=[
            [$row[0],$row[1],$row[2],$row[3],$uploadMessage],
        ];
        $writer->addRows($multipleRows); // add multiple rows at a time

        
    }elseif($row_StreamDetails > '0'){
        $displayMessage[]  = $row[2].' : Fee Structure Already Present';
        $uploadMessage = "Fee Structure Already Added";

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
if(isset($_GET['Add_FeeDetailsStructureInstance'])){
   
    extract($_POST);

    $ActiveStaffLogin_Id = $_SESSION['schoolzone']['ActiveStaffLogin_Id'];
    $SectionMaster_Id = $_SESSION['schoolzone']['SectionMaster_Id'];

    $PAD = date('Y-m-d',strtotime(str_replace('/','-',$add_Payable_date)));
    $LD = date('Y-m-d',strtotime(str_replace('/','-',$add_Last_Date)));
  
    $Inserting_StaffQualification = mysqli_query($mysqli,"Insert into fee_structure_details
    (Fee_Structure_Id, Name, Abbreviation, Payable_date, Last_Date, academicYearId) 
    Values
    ('$add_Fee_Structure_Id', '".htmlspecialchars($add_Name, ENT_QUOTES)."', '".htmlspecialchars($add_Abbreviation, ENT_QUOTES)."', '".htmlspecialchars($PAD, ENT_QUOTES)."', '".htmlspecialchars($LD, ENT_QUOTES)."', '$add_academicYearId')");

    
    $res['status'] = 'success';
    echo json_encode($res);

    
}
//-----------------------------------------------------------------------------------------------------------------------

//-----------------------------------------------------------------------------------------------------------------------
if(isset($_GET['Edit_FeeDetailsStructureInstance'])){

    extract($_POST);

     
    $PAD = date('Y-m-d',strtotime(str_replace('/','-',$edit_Payable_date)));
    $LD = date('Y-m-d',strtotime(str_replace('/','-',$edit_Last_Date)));

    $updating_CalenderInstance = mysqli_query($mysqli,"Update fee_structure_details Set 
    Fee_Structure_Id = '".htmlspecialchars($edit_Fee_Structure_Id, ENT_QUOTES)."',
    Name='".htmlspecialchars($edit_Name, ENT_QUOTES)."',
    Abbreviation='".htmlspecialchars($edit_Abbreviation, ENT_QUOTES)."',
    Payable_date='".htmlspecialchars($PAD, ENT_QUOTES)."',
    Last_Date='".htmlspecialchars($LD, ENT_QUOTES)."',
    academicYearId='".htmlspecialchars($edit_academicYearId, ENT_QUOTES)."'
    where Id  = '$edit_InstanceId'");

    echo "200";
    
}
//-----------------------------------------------------------------------------------------------------------------------


//-----------------------------------------------------------------------------------------------------------------------
if(isset($_GET['Delete_FeeDetailsStructureInstance'])){

    extract($_POST);

    $deleting_formheader = mysqli_query($mysqli,"DELETE FROM fee_structure_details where Id = '$delete_instance_Id'");

    echo "200";
    
}
//-----------------------------------------------------------------------------------------------------------------------



//-----------------------------------------------------------------------------------------------------------------------
if(isset($_GET['Add_FeeDetailsStructureInstance_InBulk'])){

    $SectionMaster_Id = $_SESSION['schoolzone']['SectionMaster_Id'];  

    $FS_Id = $_POST['FS_Id'];

    // check file name is not empty
    $pathinfo = pathinfo($_FILES["upload_file"]["name"]); //file extension

    $folderMap = '../';
    $fileLocation = "FileUploadLogs/FeeStructureDetailsMaster_".$SectionMaster_Id."_".date("Y-m-d-h-i-s").'.xlsx';
    $targetfolder =  $folderMap."".$fileLocation;
    move_uploaded_file($_FILES['upload_file']['name'], $targetfolder);

    $writer = WriterFactory::create(Type::XLSX); // for XLSX files

    $fileName= $targetfolder;
    $writer->openToFile($fileName); // write data to a file or to a PHP stream
    // $writer->openToBrowser($fileName); // stream data directly to the browser

    $singleRow =['Sr No','Name','Abbreviation','Payable Date','Last Date','Upload Status'];
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

    
            $Inserting_StaffQualification = mysqli_query($mysqli,"Insert into fee_structure_details
            (Fee_Structure_Id, Name, Abbreviation, Payable_date, Last_Date, academicYearId) 
            Values
            ('$FS_Id', '".htmlspecialchars($row[1], ENT_QUOTES)."', '".htmlspecialchars($row[2], ENT_QUOTES)."', '".$row[3]->format('Y-m-d')."' , '".$row[4]->format('Y-m-d')."' , '$Acadmic_Year_ID')");



            if(mysqli_error($mysqli)){
                $displayMessage[]  = $row[1].' : Query Failed';
                $uploadMessage = "Query Failed";
            }else{
                $displayMessage[]  = $row[1].' : Fee Structure Details Added';
                $uploadMessage = "Fee Structure  Details Added";    
            }

 

        $multipleRows=[
            [$row[0],$row[1],$row[2],$row[3]->format('Y-m-d'),$row[4]->format('Y-m-d'),$uploadMessage],
        ];
        $writer->addRows($multipleRows); // add multiple rows at a time

   
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
