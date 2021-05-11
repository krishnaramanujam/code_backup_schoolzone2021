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
if(isset($_GET['Add_StudentDataInstance'])){
   
    extract($_POST);

    $ActiveStaffLogin_Id = $_SESSION['schoolzone']['ActiveStaffLogin_Id'];
    $SectionMaster_Id = $_SESSION['schoolzone']['SectionMaster_Id'];

    //checking Department Abbbr & Name
    $depart_fetch_q = mysqli_query($mysqli,"SELECT user_studentregister.* FROM user_studentregister Where user_studentregister.sectionmaster_Id = '$SectionMaster_Id' AND user_studentregister.student_Id = '".htmlspecialchars($add_student_Id, ENT_QUOTES)."' ");

    $message = '';
    $row_depart_fetch = mysqli_num_rows($depart_fetch_q);

    if($row_depart_fetch > 0){

        
        $res['status'] = 'EXISTS';
        echo json_encode($res);

    }else{

        
        mysqli_query( $mysqli, 'SET AUTOCOMMIT = 0' );
        mysqli_query( $mysqli,'SET foreign_key_checks = 0');

            //Adding Data in CR
            $pin = 'Pass@123';

            //Login Id NOt Exist
            $Inserting_CandidateRegister = mysqli_query($mysqli,"Insert into user_candidateregister
            (OTP,
            Login_Count,
            ContactNo,
            EmailAddress,
            batchmaster_Id,
            full_name) Values
            ('$pin',
            '0',
            '$add_mobile_no',
            '$add_email_address',
            '$add_batchmaster_Id',
            '$add_student_name')");

            if(mysqli_error($mysqli)){
                $message .= "ERROR(1): Unable to create.".mysqli_error($mysqli);
            }

            $CR_Id = mysqli_insert_id($mysqli);

            $Inserting_CandidateDetails = mysqli_query($mysqli,"Insert into user_candidatedetails
            (candidateRegister_Id,
            Full_name,
            Email_id,
            Mobile_no) Values
            ('$CR_Id',
            '$add_student_name',
            '$add_email_address',
            '$add_mobile_no')");
            
            if(mysqli_error($mysqli)){
                $message .= "ERROR(2): Unable to create.".mysqli_error($mysqli);
            }

            $Name_of_Examination             = "X";
            $Inserting_CandidateAcademicDetails = mysqli_query( $mysqli, "INSERT INTO user_candidateacademicdetails (candidateRegister_Id, Name_of_Examination) VALUES('" . $CR_Id . "', '" . $Name_of_Examination . "')" );

            
            if(mysqli_error($mysqli)){
                $message .= "ERROR(3): Unable to create.".mysqli_error($mysqli);
            }

            $password = password_hash( $pin, PASSWORD_DEFAULT );
            $DOB = date('Y-m-d',strtotime(str_replace('/','-',$_POST['add_date_of_birth'])));
    

            //Inserting In SR
            $Inserting_StaffQualification = mysqli_query($mysqli,"Insert into user_studentregister
            (CR_Id, student_name, student_Id, mobile_no, email_address, password, date_of_birth, sectionmaster_Id) 
            Values
            ('$CR_Id', '$add_student_name', '$add_student_Id', '$add_mobile_no', '$add_email_address', '$password', '$DOB', '$SectionMaster_Id')");

            if(mysqli_error($mysqli)){
                $message .= "ERROR(4): Unable to create.".mysqli_error($mysqli);
            }
            $SR_Id = mysqli_insert_id($mysqli);

            //Inserting In SR
            $Inserting_studentregister = mysqli_query($mysqli,"Insert into user_studentregister
            (CR_Id, student_name, student_Id, mobile_no, email_address, password, date_of_birth, sectionmaster_Id) 
            Values
            ('$CR_Id', '$add_student_name', '$add_student_Id', '$add_mobile_no', '$add_email_address', '$password', '$DOB', '$SectionMaster_Id')");

            if(mysqli_error($mysqli)){
                $message .= "ERROR(5): Unable to create.".mysqli_error($mysqli);
            }

            $Inserting_studentdetails = mysqli_query($mysqli,"Insert into user_studentdetails
            (studentregister_Id) Values
            ('$SR_Id')");

            if(mysqli_error($mysqli)){
                $message .= "ERROR(6): Unable to create.".mysqli_error($mysqli);
            }

            
            $Inserting_studentbatchmaster = mysqli_query($mysqli,"Insert into user_studentbatchmaster
            (studentRegister_Id, batchMaster_Id, applicationDetails_Id) Values
            ('$SR_Id', '$add_batchmaster_Id', '0')");

            if(mysqli_error($mysqli)){
                $message .= "ERROR(7): Unable to create.".mysqli_error($mysqli);
            }

            $SBM_Id = mysqli_insert_id($mysqli);

            $Updating_Contact = mysqli_query($mysqli,"Update user_studentregister Set user_studentregister.SBM_Id = '$SBM_Id' Where user_studentregister.Id = '$SR_Id'");


            if(empty($message)){
                mysqli_commit( $mysqli);
                mysqli_query( $mysqli, 'SET foreign_key_checks = 1');
                mysqli_query( $mysqli, 'SET AUTOCOMMIT = 1' );        

                        
                $res['status'] = 'success';
                echo json_encode($res);
            }else{

                $res['status'] = 'failed';
                echo json_encode($res);
            }

        
    }

    
}
//-----------------------------------------------------------------------------------------------------------------------

//-----------------------------------------------------------------------------------------------------------------------
if(isset($_GET['Edit_StudentDataInstance'])){

    extract($_POST);

        $DOB = date('Y-m-d',strtotime(str_replace('/','-',$_POST['edit_date_of_birth'])));

        $updating_CalenderInstance = mysqli_query($mysqli,"Update user_studentregister Set 
        student_name = '$edit_student_name',student_Id = '$edit_student_Id',email_address = '$edit_email_address',mobile_no = '$edit_mobile_no',date_of_birth = '$DOB'
        where Id  = '$edit_InstanceId'");


    echo "200";
    
}
//-----------------------------------------------------------------------------------------------------------------------



//-----------------------------------------------------------------------------------------------------------------------
if(isset($_GET['Add_StudentDataInstance_InBulk'])){
    $ActiveStaffLogin_Id = $_SESSION['schoolzone']['ActiveStaffLogin_Id'];
    $SectionMaster_Id = $_SESSION['schoolzone']['SectionMaster_Id'];
    $batch_sel_import = $_POST['batch_sel_import'];
    // check file name is not empty
    $pathinfo = pathinfo($_FILES["upload_file"]["name"]); //file extension

    $folderMap = '../';
    $fileLocation = "FileUploadLogs/StudentDetails_".$SectionMaster_Id."_".date("Y-m-d-h-i-s").'.xlsx';
    $targetfolder =  $folderMap."".$fileLocation;
    move_uploaded_file($_FILES['upload_file']['name'], $targetfolder);

    $writer = WriterFactory::create(Type::XLSX); // for XLSX files

    $fileName= $targetfolder;
    $writer->openToFile($fileName); // write data to a file or to a PHP stream
    // $writer->openToBrowser($fileName); // stream data directly to the browser

    $singleRow =['Sr No','Student Name','student Id','Email Id','Mobile No','Date Of Birth','Upload Status'];
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


    $UserDetails_q = mysqli_query($mysqli, "SELECT user_studentregister.* FROM user_studentregister Where user_studentregister.sectionmaster_Id = '$SectionMaster_Id' AND user_studentregister.student_Id = '".htmlspecialchars($row[2], ENT_QUOTES)."' ");

    $row_UserDetails = mysqli_num_rows($UserDetails_q);



    if($row_UserDetails == '0'){

        

        mysqli_query( $mysqli, 'SET AUTOCOMMIT = 0' );
        mysqli_query( $mysqli,'SET foreign_key_checks = 0');

            //Adding Data in CR
            $pin = 'Pass@123';

            //Login Id NOt Exist
            $Inserting_CandidateRegister = mysqli_query($mysqli,"Insert into user_candidateregister
            (OTP,
            Login_Count,
            ContactNo,
            EmailAddress,
            batchmaster_Id,
            full_name) Values
            ('$pin',
            '0',
            '$row[3]',
            '$row[4]',
            '$batch_sel_import',
            '$row[1]')");

            if(mysqli_error($mysqli)){
                $message .= "ERROR(1): Unable to create.".mysqli_error($mysqli);
            }

            $CR_Id = mysqli_insert_id($mysqli);

            $Inserting_CandidateDetails = mysqli_query($mysqli,"Insert into user_candidatedetails
            (candidateRegister_Id,
            Full_name,
            Email_id,
            Mobile_no) Values
            ('$CR_Id',
            '$row[1]',
            '$row[4]',
            '$row[3]')");
            
            if(mysqli_error($mysqli)){
                $message .= "ERROR(2): Unable to create.".mysqli_error($mysqli);
            }

            $Name_of_Examination             = "X";
            $Inserting_CandidateAcademicDetails = mysqli_query( $mysqli, "INSERT INTO user_candidateacademicdetails (candidateRegister_Id, Name_of_Examination) VALUES('" . $CR_Id . "', '" . $Name_of_Examination . "')" );

            
            if(mysqli_error($mysqli)){
                $message .= "ERROR(3): Unable to create.".mysqli_error($mysqli);
            }

            $password = password_hash( $pin, PASSWORD_DEFAULT );
    

            //Inserting In SR
            $Inserting_StaffQualification = mysqli_query($mysqli,"Insert into user_studentregister
            (CR_Id, student_name, student_Id, mobile_no, email_address, password, date_of_birth, sectionmaster_Id) 
            Values
            ('$CR_Id', '$row[1]', '$row[2]', '$row[3]', '$row[4]', '$password', '".$row[5]->format('Y-m-d')."', '$SectionMaster_Id')");

            if(mysqli_error($mysqli)){
                $message .= "ERROR(4): Unable to create.".mysqli_error($mysqli);
            }
            $SR_Id = mysqli_insert_id($mysqli);

       

            $Inserting_studentdetails = mysqli_query($mysqli,"Insert into user_studentdetails
            (studentregister_Id) Values
            ('$SR_Id')");

            if(mysqli_error($mysqli)){
                $message .= "ERROR(6): Unable to create.".mysqli_error($mysqli);
            }

            
            $Inserting_studentbatchmaster = mysqli_query($mysqli,"Insert into user_studentbatchmaster
            (studentRegister_Id, batchMaster_Id, applicationDetails_Id) Values
            ('$SR_Id', '$batch_sel_import', '0')");

            if(mysqli_error($mysqli)){
                $message .= "ERROR(7): Unable to create.".mysqli_error($mysqli);
            }

            $SBM_Id = mysqli_insert_id($mysqli);

            $Updating_Contact = mysqli_query($mysqli,"Update user_studentregister Set user_studentregister.SBM_Id = '$SBM_Id' Where user_studentregister.Id = '$SR_Id'");


            if(empty($message)){
                mysqli_commit( $mysqli);
                mysqli_query( $mysqli, 'SET foreign_key_checks = 1');
                mysqli_query( $mysqli, 'SET AUTOCOMMIT = 1' );        

                $displayMessage[]  = $row[1].' : Success';
                $uploadMessage = "Success";
            }else{
                $displayMessage[]  = $row[1].' : Error while adding entries' . $message;
                $uploadMessage = "Error while adding entries" . $message;
            }


            unset($SR_Id); unset($CR_Id); unset($SBM_Id);



        $multipleRows=[
            [$row[0],$row[1],$row[2],$row[3],$row[4],$row[5]->format('Y-m-d'),$uploadMessage],
        ];
        $writer->addRows($multipleRows); // add multiple rows at a time
    

    }elseif($row_UserDetails > '0'){
        $displayMessage[]  = $row[1].' : Student Id  Already Present';

        $uploadMessage = "Student Id  Already Present";

        $multipleRows=[
            [$row[0],$row[1],$row[2],$row[3],$row[4],$row[5]->format('Y-m-d'),$uploadMessage],
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
