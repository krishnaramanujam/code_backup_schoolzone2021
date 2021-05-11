<?php

ini_set('max_execution_time', 0);
set_time_limit(0);
ini_set('memory_limit','-1');
ini_set('display_errors',1);
error_reporting(E_ALL);
session_start();

header('Access-Control-Allow-Origin: *');

header('Access-Control-Allow-Methods: GET, POST');

include('../../config/database.php');


use Box\Spout\Reader\ReaderFactory;
use Box\Spout\Writer\WriterFactory;

use Box\Spout\Common\Type;

require_once '../../assets/plugins/spout-2.4.3/src/Spout/Autoloader/autoload.php';

//-----------------------------------------------------------------------------------------------------------------------
if(isset($_GET['SendingStudentSMS'])){

        extract($_POST);

        if(isset($CML_Id)){

                $folderMap = '../';
                $fileLocation = "FileUploadLogs/SMSLogFile_".$SectionMaster_Id."_".date("Y-m-d-h-i-s").'.xlsx';
                $targetfolder =  $folderMap."".$fileLocation;
               
                $writer = WriterFactory::create(Type::XLSX); // for XLSX files

                $fileName= $targetfolder;
                $writer->openToFile($fileName); // write data to a file or to a PHP stream
                // $writer->openToBrowser($fileName); // stream data directly to the browser

                $singleRow =['Sr No','Recipient Address','Decrypt Message','Header Id','SMS Send Unique Id','SMS Status'];
                $writer->addRow($singleRow); // add a row at a time
                                
                $comm_fetch_q = mysqli_query($mysqli, "SELECT comm_message_log.comm_message_log_Id, comm_message_log.timestamp, comm_message_log.sender_name, comm_message_log.recipient_address AS phone, comm_message_log.Decrypt_Msg, comm_message_log.SenderHeader, comm_message_log.User_Id AS Users_Id, comm_message_log.template_Id, comm_sms_header_ids.header_name, comm_sms_header_ids.PE_Id, comm_sms_templates.template_Id AS Temp_Id FROM comm_message_log JOIN comm_sms_header_ids ON comm_sms_header_ids.Id = comm_message_log.SenderHeader JOIN comm_sms_templates ON comm_sms_templates.Id = comm_message_log.template_Id WHERE comm_message_log.comm_message_log_Id IN ($CML_Id) ");
  
                $rows_comm_fetch = mysqli_num_rows($comm_fetch_q);
                if($rows_comm_fetch > '0'){
                    $i = 1;
                    while($r_comm_fetch = mysqli_fetch_array($comm_fetch_q)){
                        $message = htmlspecialchars_decode($r_comm_fetch['Decrypt_Msg'], ENT_QUOTES);
                        $mobile_number = $r_comm_fetch['phone'];
                        $senderHeader = $r_comm_fetch['header_name'];
                        $template_Id = $r_comm_fetch['Temp_Id'];
                        $PE_Id = $r_comm_fetch['PE_Id'];
                        if($mobile_number != '0'){
                        
                            $sess = file_get_contents( "http://sms.rightchoicesms.com/sendsms/sendsms.php?username=RCdvsl17&password=speed100&type=TEXT&sender=".$senderHeader."&mobile=".$mobile_number."&message=". urlencode($message)."&peId=" . $PE_Id."&tempId=".$template_Id );

                            $res['ss'] = "http://sms.rightchoicesms.com/sendsms/sendsms.php?username=RCdvsl17&password=speed100&type=TEXT&sender=".$senderHeader."&mobile=".$mobile_number."&message=". urlencode($message)."&peId=" . $PE_Id."&tempId=".$template_Id;

                            $sess = str_replace('<br/>', '', $sess);
                            //Updating Reponse in database
                                                        
                            $SMS_ResponseArray = explode("|", $sess);
                            $StatusCode = trim($SMS_ResponseArray[0]);


                            $patt = "/SUBMIT_SUCCESS$/i";
                            $MatchingPattern = preg_match($patt, $StatusCode);

                            $Updating_Error_Reponse = mysqli_query($mysqli, "Update comm_message_log Set comm_message_log.Status = '$StatusCode' Where comm_message_log.comm_message_log_Id = '$r_comm_fetch[comm_message_log_Id]'");
                            
                            if($MatchingPattern){ 
                                 $StatusId = trim($SMS_ResponseArray[1]);
                                 $Updating_Error_Reponse = mysqli_query($mysqli, "Update comm_message_log Set comm_message_log.ID = '$StatusId' Where comm_message_log.comm_message_log_Id = '$r_comm_fetch[comm_message_log_Id]'");
                            }


                            $multipleRows=[
                                [$i ,$mobile_number, $message, $senderHeader ,$StatusId, $StatusCode],
                            ];
                            $writer->addRows($multipleRows); // add multiple rows at a time

                            
                            $SMSReponse[] = $sess;
                            $SMSUserId[] = $r_comm_fetch['Users_Id'];
                        }
                        $i++;
                    }// close while
                }

                
                $writer->close();
        }// close isset
  
        $res['UploadedFilePath'] = $fileLocation;
        $res['SMSReponse'] = $SMSReponse;
        $res['SMSUserId'] = $SMSUserId;

        $res['status'] = 'success';
        echo json_encode($res);

}
//-----------------------------------------------------------------------------------------------------------------------



//-----------------------------------------------------------------------------------------------------------------------
if(isset($_GET['SendingStudentEmails'])){

    extract($_POST);
    //Section Id
    $SectionMaster_Id = $_SESSION['schoolzone']['SectionMaster_Id'];

    require '../../assets/plugins/phpmailer/PHPMailerAutoload.php';
    
    $fetching_details = mysqli_query($mysqli,"SELECT comm_message_log.* FROM comm_message_log  WHERE comm_message_log.comm_message_log_Id IN ($CEL_Id) ");

    // $fetching_details = mysqli_query($mysqli,"SELECT * FROM `bulk_mail` Where bulk_mail.sendStatus = '200'");

    
    $row_count = mysqli_num_rows($fetching_details);
    // print_r($row_count);
    if($row_count > 0){ 

        $folderMap = '../';
        $fileLocation = "FileUploadLogs/EmailLogFile_".$SectionMaster_Id."_".date("Y-m-d-h-i-s").'.xlsx';
        $targetfolder =  $folderMap."".$fileLocation;
       
        $writer = WriterFactory::create(Type::XLSX); // for XLSX files

        $fileName= $targetfolder;
        $writer->openToFile($fileName); // write data to a file or to a PHP stream
        // $writer->openToBrowser($fileName); // stream data directly to the browser

        $singleRow =['Sr No','Recipient Address','Decrypt Message','Sender Address','Subject','Email Status'];
        $writer->addRow($singleRow); // add a row at a time
        $i = 1;
        while($r_fetching_details = mysqli_fetch_array($fetching_details)){

            
            if($r_fetching_details['userIdType'] == 'StaffLoginId'){
                //based on Staff login Id

                $maildetails_q = mysqli_query($mysqli, "SELECT setup_sectionmaildetails.* FROM user_stafflogin JOIN setup_departmentmaster ON setup_departmentmaster.Id = user_stafflogin.departmentmaster_Id JOIN setup_sectionmaildetails ON setup_sectionmaildetails.sectionmaster_Id = setup_departmentmaster.sectionmaster_Id WHERE user_stafflogin.Id = '$r_fetching_details[User_Id]' limit 1");
                $row_maildetails = mysqli_num_rows($maildetails_q);

                if($row_maildetails > '0'){
                    $r_maildetails = mysqli_fetch_array($maildetails_q);

                    $mail_host = $r_maildetails['host'];
                    $mail_username = $r_maildetails['username'];
                    $mail_password = $r_maildetails['password'];
                    $mail_setFromAddress = $r_maildetails['setFromAddress'];
                    $mail_setFromName = $r_maildetails['setFromName'];

                    $res['Messagea'] = $mail_setFromAddress;

                    $res['Messages'] = $mail_setFromName;
                }// close row

            }else{

                $maildetails_q = mysqli_query($mysqli, "SELECT setup_sectionmaildetails.* FROM  setup_sectionmaildetails WHERE  setup_sectionmaildetails.Id ='$r_fetching_details[SenderHeader]' ");
                $row_maildetails = mysqli_num_rows($maildetails_q);

                if($row_maildetails > '0'){
                    $r_maildetails = mysqli_fetch_array($maildetails_q);

                    $mail_host = $r_maildetails['host'];
                    $mail_username = $r_maildetails['username'];
                    $mail_password = $r_maildetails['password'];
                    $mail_setFromAddress = $r_maildetails['setFromAddress'];
                    $mail_setFromName = $r_maildetails['setFromName'];

                    $res['Messagea'] = $mail_setFromAddress;

                    $res['Messages'] = $mail_setFromName;
                }// close row
                
            }


            $emailSubjects = $r_fetching_details['email_Subjects'];
            $emailMessage = $r_fetching_details['Decrypt_Msg'];
            $emailTo = $r_fetching_details['recipient_address'];
            $UserId = $r_fetching_details['User_Id'];

            if(empty($emailSubjects)){ $emailSubjects = 'NA'; }
            if(empty($emailMessage)){ $emailMessage = 'NA'; }

            // echo "<pre>"; print_r($r_fetching_details); echo "</pre>";
            $mail = new PHPMailer( true );
            $mail->isSMTP();                              // telling the class to use SMTP
            // $mail->SMTPDebug = 2;                         // enables SMTP debug information (for testing)
            $mail->SMTPAuth = true;                       // enable SMTP authentication      comment this while working localhost
            $mail->SMTPSecure = "ssl";                    // sets the prefix to the servier  comment this while working localhost
            $mail->Host = $mail_host;  // sets GMAIL as the SMTP server
            $mail->Port = 465;                            // set the SMTP port for the GMAIL server
            $mail->Username = $mail_username;
            $mail->Password = $mail_password;

            $mail->ClearReplyTos();
            // $mail->addReplyTo('Email address', 'Name');
            $mail->setFrom( $mail_setFromAddress , $mail_setFromName);

            $mail->Subject = $emailSubjects;

            $mail->addAddress($emailTo);
            $mail->IsHTML(true);
            $mail->Body = nl2br($emailMessage);
            
            if(!empty($r_fetching_details['attachment_path'])){
                $mail->addAttachment("../../assets/".$r_fetching_details['attachment_path']);
            }


            if($mail->Send()){
                $updating_Status = mysqli_query($mysqli,"Update comm_message_log set comm_message_log.Status = 'Success' Where comm_message_log.comm_message_log_Id = '$r_fetching_details[comm_message_log_Id]'");
                
                $EmailResponse[] = 'Success';
                $email[] = $emailTo;
                $EmailUserId[] = $UserId;

                $multipleRows=[
                    [$i ,$r_fetching_details['recipient_address'], $r_fetching_details['Decrypt_Msg'], $mail_setFromAddress ,$emailSubjects, 'Success'],
                ];
                $writer->addRows($multipleRows); // add multiple rows at a time


            }else{
                $updating_Status = mysqli_query($mysqli,"Update comm_message_log set comm_message_log.Status = 'Failed' Where comm_message_log.comm_message_log_Id = '$r_fetching_details[comm_message_log_Id]'");

                $EmailResponse[] = 'Failed';
                $email[] = $emailTo;
                $EmailUserId[] = $UserId;

                
                $multipleRows=[
                    [$i ,$r_fetching_details['recipient_address'], $r_fetching_details['Decrypt_Msg'], $mail_setFromAddress ,$emailSubjects, 'Failed'],
                ];
                $writer->addRows($multipleRows); // add multiple rows at a time

            }// close else

            unset($mail);
            sleep(2);
            // unset($fileName);


            $i++;
        }// close while
        
        $writer->close();

    }else{
        $res['Message'] = 'No New Email Found';
    }    
    $res['UploadedFilePath'] = $fileLocation;
    $res['EmailResponse'] = $EmailResponse;
    $res['email'] = $emailRes;
    $res['EmailUserId'] = $EmailUserId;

    $res['status'] = 'success';
    echo json_encode($res);    


}
//-----------------------------------------------------------------------------------------------------------------------


//-----------------------------------------------------------------------------------------------------------------------
if(isset($_GET['Template_From_Header'])){

    $Sender_Header_Id = $_POST['Sender_Header_Id'];

    $Batch_Fetch_q = mysqli_query($mysqli,"SELECT comm_sms_templates.* FROM `comm_sms_templates` JOIN comm_sms_header_ids ON comm_sms_header_ids.Id = comm_sms_templates.sms_header_Id WHERE comm_sms_templates.sms_header_Id = '$Sender_Header_Id' ");
   
    $type_count = mysqli_num_rows($Batch_Fetch_q);
    
    if($type_count > 0){
        $i = 0;
        while($r_type_fetch = mysqli_fetch_array($Batch_Fetch_q)){
            if($i == 0){
                echo '<option value="">Select Value</option>';
            }
            echo '<option value="'.$r_type_fetch['Id'].'">'.$r_type_fetch['registered_sms_template'].'</option>';

            $i++;
        }

    }else{
        echo '<option value="">No Template Entry Found</option>';
    }

}
//-----------------------------------------------------------------------------------------------------------------------


//-----------------------------------------------------------------------------------------------------------------------
if(isset($_GET['InsertingSMSLogEntries'])){

    extract($_POST);

    $format_message =  htmlspecialchars($message, ENT_QUOTES);


    if(isset($User_Id)){
  
        foreach($User_Id as $index => $value) {

                //Fetching Decrypted Message
                if($UserType == 'Student'){
                    $Student_Res = StudentMessageDecrypt($User_Id[$index], $format_message , $contact_Person, $mysqli);
                    $userIdType = 'SBM_Id';
                }elseif($UserType == 'Candidate'){
                    $Student_Res = CandidateMessageDecrypt($User_Id[$index], $format_message , $contact_Person , $mysqli);
                    $userIdType = 'CR_Id';
                }elseif($UserType == 'CandidateApplication'){
                    $Student_Res = CandidateApplicationMessageDecrypt($User_Id[$index], $format_message , $contact_Person , $mysqli);
                    $userIdType = 'AD_Id';
                }
               
                //no_of_charater
                $no_of_charater = strlen($Student_Res['Decrypt_Message']);
                
                $time = date("Y-m-d h:m:s");

                if(!isset($moduleType)){ $moduleType = 'Communication'; }


                $template_Id = $Sender_Template_Id;

                //Inserting data in comm_message_log
                $Inserting_UserDetails = mysqli_query($mysqli,"Insert into comm_message_log (User_Id, recipient_address, no_of_characters, Decrypt_Msg, sender_name, timestamp, SenderHeader, userIdType, moduleType, template_Id, message_type) values ('$User_Id[$index]', '$Student_Res[ContactNo]', '$no_of_charater' , '".$Student_Res[Decrypt_Message]."', '$SenderName', '$time' , '$Sender_Header', '$userIdType' , '$moduleType', '$template_Id', '1')");

                
                if(mysqli_error($mysqli)){
                    $Generating_Error[] = 'Error Occurred SBM_Id : ' . $User_Id[$index]; 
                }else{
                    $CML_Id_Array[] = mysqli_insert_id($mysqli);
                }              


        }// close Foreach

    }// close isset

    if(isset($CML_Id_Array)){
        $format_CML_Id = implode(",",$CML_Id_Array);
    }else{
        $format_CML_Id = 'NA';
    }

    if(!isset($Generating_Error)){
        $Generating_Error = 'NA';
    }


    $res['Error_Message'] = $Generating_Error;
    $res['Comm_Message_Logs_Id'] = $format_CML_Id;
    $res['status'] = 'success';
    echo json_encode($res);

}
//-----------------------------------------------------------------------------------------------------------------------


//Function For Decrypting Message
function StudentMessageDecrypt($StudentBatchMaster_Id, $messageText, $contact_Person, $mysqli){

    if(isset($StudentBatchMaster_Id) AND isset($messageText)){ 
      
            $patterns = array();
            $patterns[0] = '/#NAME/i';
            $patterns[1] = '/#OTP/i';
            $patterns[2] = '/#BATCH/i';
            $patterns[3] = '/#ROLLNO/i';
            $patterns[4] = '/#DIVISION/i';
            $patterns[5] = '/#EMAIL/i';
            $patterns[6] = '/#GSEMAIL/i';
            $patterns[7] = '/#STUDENTID/i';
            $patterns[8] = '/#GRNO/i';
        
            $replacements = array();
            $replacements[0] = '#NAME';
            $replacements[1] = '#OTP';
            $replacements[2] = '#BATCH';
            $replacements[3] = '#ROLLNO';
            $replacements[4] = '#DIVISION';
            $replacements[5] = '#EMAIL';
            $replacements[6] = '#GSEMAIL';
            $replacements[7] = '#STUDENTID';
            $replacements[8] = '#GRNO';

        //     $patte1 = array();
        //     $patte1[0] = '/#S1/i';
        //     $patte1[1] = '/#S2/i';
        //     $patte1[2] = '/#S3/i';
        //     $patte1[3] = '/#S4/i';
        //     $patte1[4]  = '/#S5/i';
        //     $patte1[5]  = '/#S6/i';

        //     $replac_sub = array();
        //     $replac_sub[1] = '#S1';
        //     $replac_sub[2] = '#S2';
        //     $replac_sub[3] = '#S3';
        //     $replac_sub[4] = '#S4';
        //     $replac_sub[5] = '#S5';
        //     $replac_sub[6] = '#S6';

        //     $F_Subject_q = mysqli_query($mysqli,"SELECT
        //     `setup_batchcoursemaster`.`course_numbering`,
        //     `setup_coursemaster`.`Course_name` AS `batchCourse_Name`,
        //     `studentBatchMaster_Id`
        //   FROM
        //     `setup_studentbatchcoursemaster`
        //     INNER JOIN `user_studentbatchmaster` ON `setup_studentbatchcoursemaster`.`studentBatchMaster_Id` = `user_studentbatchmaster`.`Id`
        //     INNER JOIN `setup_batchcoursemaster` ON `setup_studentbatchcoursemaster`.`batchCourseMaster_Id` = `setup_batchcoursemaster`.`Id`
        //     INNER JOIN `setup_coursemaster` ON `setup_batchcoursemaster`.`courseMaster_Id` = `setup_coursemaster`.`Id`
        //   WHERE
        //     `setup_studentbatchcoursemaster`.`studentBatchMaster_Id` = '$StudentBatchMaster_Id'  ");
        //     $rows_F_Subjects = mysqli_num_rows($F_Subject_q);
        //     if($rows_F_Subjects > '0'){
        //         while($r_subjects = mysqli_fetch_array($F_Subject_q)){
        //             $replac_sub[$r_subjects['course_numbering']] = $r_subjects['batchCourse_Name'];

                    
        //         }

        //         $replace_special_text_subjects = preg_replace( $patte1, $replac_sub, $messageText);
        //             $messageText = $replace_special_text_subjects;
               
        //     }


            $F_StudentData_q = mysqli_query($mysqli,"SELECT user_studentregister.mobile_no AS Student_Contact, user_studentbatchmaster.roll_no, user_studentdetails.Student_name, user_studentdetails.fathers_Contact, user_studentdetails.mothers_Contact, user_studentregister.password AsOTP, setup_batchmaster.batch_name AS Batch_Name, user_studentbatchmaster.Div, user_studentregister.email_address AS registeredEmailAddress, user_studentregister.gr_no AS GR_No, user_studentregister.student_Id FROM user_studentbatchmaster JOIN user_studentregister ON user_studentbatchmaster.studentRegister_Id = user_studentregister.Id JOIN user_studentdetails ON user_studentbatchmaster.studentRegister_Id = user_studentdetails.studentregister_Id JOIN setup_batchmaster ON setup_batchmaster.Id = user_studentbatchmaster.batchMaster_Id WHERE user_studentbatchmaster.Id = '$StudentBatchMaster_Id'   ");

            $rows_F_StudentData = mysqli_num_rows($F_StudentData_q);
            if($rows_F_StudentData > '0'){
                $r_Fetching_StudentData = mysqli_fetch_array($F_StudentData_q);

                //Replaceing Value in Tags-----------------------------------------------------------------------------------
                            
                if(is_null($r_Fetching_StudentData['Student_name']) or $r_Fetching_StudentData['Student_name'] == ''){
                    $replacements[0] = '-';
                }else{
                    $replacements[0] = $r_Fetching_StudentData['Student_name'];
                }



                if(is_null($r_Fetching_StudentData['Batch_Name']) or $r_Fetching_StudentData['Batch_Name'] == ''){
                    $replacements[2] = '-';
                }else{
                    $replacements[2] = $r_Fetching_StudentData['Batch_Name'];
                }

                if(is_null($r_Fetching_StudentData['roll_no']) or $r_Fetching_StudentData['roll_no'] == ''){
                    $replacements[3] = '-';
                }else{
                    $replacements[3] = $r_Fetching_StudentData['roll_no'];
                }

                if(is_null($r_Fetching_StudentData['Div']) or $r_Fetching_StudentData['Div'] == ''){
                    $replacements[4] = '-';
                }else{
                    $replacements[4] = $r_Fetching_StudentData['Div'];
                }

                if(is_null($r_Fetching_StudentData['registeredEmailAddress']) or $r_Fetching_StudentData['registeredEmailAddress'] == ''){
                    $replacements[5] = '-';
                }else{
                    $replacements[5] = $r_Fetching_StudentData['registeredEmailAddress'];
                }


                if(is_null($r_Fetching_StudentData['student_Id']) or $r_Fetching_StudentData['student_Id'] == ''){
                    $replacements[7] = '-';
                }else{
                    $replacements[7] = $r_Fetching_StudentData['student_Id'];
                }

                if(is_null($r_Fetching_StudentData['GR_No']) or $r_Fetching_StudentData['GR_No'] == ''){
                    $replacements[8] = '-';
                }else{
                    $replacements[8] = $r_Fetching_StudentData['GR_No'];
                }

                //------------------------------------------------------------------------------------------------------------

                    
                $replace_special_text = preg_replace( $patterns, $replacements, $messageText);
                $sms = $replace_special_text;

                //contact Number
                if($contact_Person == 'Father') { $phone_no = $r_Fetching_StudentData['fathers_Contact']; }
                else if($contact_Person == 'Mother'){ $phone_no = $r_Fetching_StudentData['mothers_Contact']; }
                else{ $phone_no = $r_Fetching_StudentData['Student_Contact']; }
                
                $Student_Res['Decrypt_Message'] = $sms;
                $Student_Res['ContactNo'] = $phone_no;
                $Student_Res['Student_Name'] = $r_Fetching_StudentData['Student_name'];
                $Student_Res['Student_Email'] = $r_Fetching_StudentData['registeredEmailAddress'];
               
                return $Student_Res;

            }// close if

    }// close isset

}// close functions


//-----------------------------------------------------------------------------------------------------------------------
if(isset($_GET['InsertingEmailLogEntries'])){

    //Section Id
    $SectionMaster_Id = $_SESSION['schoolzone']['SectionMaster_Id'];

    $image_Error = $_FILES['attachmentPath']['error'];
    if($image_Error == 0) {
            
        $image_Array = $_FILES['attachmentPath'];
        $image_Name = $_FILES['attachmentPath']['name'];
        $image_TmpName = $_FILES['attachmentPath']['tmp_name'];
        $image_Size = $_FILES['attachmentPath']['size'];
        $image_Type = $_FILES['attachmentPath']['type'];

        $ext = strtolower(pathinfo($image_Name, PATHINFO_EXTENSION));

        $Image_Filename = "Attachment_".$SectionMaster_Id."_".date("Y-m-d-h-i-s").".".$ext;
        
        $targetfolder = "../../assets/emailattachment/".$Image_Filename;

        $Err_Msg = '';

        if (file_exists($targetfolder)) {
            $Err_Msg = 'File Already Exist with same Name';
        }else{
            
            if(move_uploaded_file($_FILES['attachmentPath']['tmp_name'], $targetfolder))
            {
               
                $Image_Link = "emailattachment/".$Image_Filename;

                
            }
            else {
                $Err_Msg = 'Error while Uploading the Image';
            }

        }


        
        
        if(empty($Err_Msg)){
            $Image_Res['Image_Link'] = $Image_Link;
            $Image_Res['Error_Message'] = $Err_Msg;
            $Image_Res['status'] = 'Success'; 
        }else{
            $Image_Res['Image_Link'] = '';
            $Image_Res['Error_Message'] = $Err_Msg;
            $Image_Res['status'] = 'failed'; 
        }

    }//checking if image Uplaod


extract($_POST);

$format_message =  htmlspecialchars($message, ENT_QUOTES);

if(isset($User_Id)){

    foreach($User_Id as $index => $value) {

            //Fetching Decrypted Message
            if($UserType == 'Student'){
                $Student_Res = StudentMessageDecrypt($User_Id[$index], $format_message , $contact_Person, $mysqli);
                $userIdType = 'SBM_Id';
            }elseif($UserType == 'Candidate'){
                $Student_Res = CandidateMessageDecrypt($User_Id[$index], $format_message , $contact_Person , $mysqli);
                $userIdType = 'CR_Id';
            }
           
            //no_of_charater
            $no_of_charater = strlen($Student_Res['Decrypt_Message']);
            
            $time = date("Y-m-d h:m:s");

            if(!isset($moduleType)){ $moduleType = 'Communication'; }

            if(!isset($Image_Link)){ $Image_Link = ''; }

            //Inserting data in comm_message_log
            $Inserting_UserDetails = mysqli_query($mysqli,"Insert into comm_message_log (User_Id, recipient_address, no_of_characters, Decrypt_Msg, sender_name, timestamp, userIdType, moduleType, email_Subjects, attachment_path, Status, message_type, SenderHeader) values ('$User_Id[$index]', '$Student_Res[Student_Email]', '$no_of_charater' , '".$Student_Res[Decrypt_Message]."', '$SenderName', '$time' , '$userIdType' , '$moduleType', '$emailSubjects', '$Image_Link', 'Pending', '2', '$Sender_Header')");

            if(mysqli_error($mysqli)){
                $Generating_Error[] = 'Error Occurred SBM_Id : ' . $User_Id[$index]; 
            }else{
                $CEL_Id_Array[] = mysqli_insert_id($mysqli);
            }              


    }// close Foreach

}// close isset

if(isset($CEL_Id_Array)){
    $format_CEL_Id = implode(",",$CEL_Id_Array);
}else{
    $format_CEL_Id = 'NA';
}

if(!isset($Generating_Error)){
    $Generating_Error = 'NA';
}


$res['Error_Message'] = $Generating_Error;
$res['Comm_Email_Logs_Id'] = $format_CEL_Id;
$res['status'] = 'success';
echo json_encode($res);

}
//-----------------------------------------------------------------------------------------------------------------------


//-----------------------------------------------------------------------------------------------------------------------
if(isset($_GET['SMSCreditUpdate'])){

    extract($_POST);

    $fetching_current_entry = mysqli_query($mysqli, "SELECT * FROM `comm_sms_credit` Where active_status = '1' AND batchmaster_Id = '$batch_sel'");
    $r_fetching_current_details = mysqli_fetch_array($fetching_current_entry);

    $current_balance = $r_fetching_current_details['balance'];

    $new_balance = (int)$current_balance - (int)$select_user_count;
    $new_usage = $select_user_count;

    //Updating All Entry to 0
    $update_credit = mysqli_query($mysqli, "Update comm_sms_credit Set active_status = '0' Where batchmaster_Id = '$batch_sel'");

    //Adding new entry
    $adding_credit = mysqli_query($mysqli, "insert into comm_sms_credit (batchmaster_Id, sms_usage, type, balance, active_status) Values ('$batch_sel', '$new_usage', '1', '$new_balance', '1')");


}
//-----------------------------------------------------------------------------------------------------------------------



//-----------------------------------------------------------------------------------------------------------------------
if(isset($_GET['Delete_TelegramInstance'])){

    extract($_POST);

    $deleting_formheader = mysqli_query($mysqli,"DELETE FROM setup_telegrammaster where Id = '$delete_instance_Id'");

    echo "200";
    
}
//-----------------------------------------------------------------------------------------------------------------------

//-----------------------------------------------------------------------------------------------------------------------
if(isset($_GET['Add_TelegramInstance'])){
   
    extract($_POST);

    $ActiveStaffLogin_Id = $_SESSION['schoolzone']['ActiveStaffLogin_Id'];
    $SectionMaster_Id = $_SESSION['schoolzone']['SectionMaster_Id'];

    //checking Department Abbbr & Name
    $depart_fetch_q = mysqli_query($mysqli,"SELECT setup_telegrammaster.* FROM setup_telegrammaster Where setup_telegrammaster.sectionmaster_Id  = '$SectionMaster_Id' AND setup_telegrammaster.channel_name = '".htmlspecialchars($add_channel_name, ENT_QUOTES)."' ");


    $row_depart_fetch = mysqli_num_rows($depart_fetch_q);

    if($row_depart_fetch > 0){

        
        $res['status'] = 'EXISTS';
        echo json_encode($res);

    }else{

        $Inserting_StaffQualification = mysqli_query($mysqli,"Insert into setup_telegrammaster
        (sectionmaster_Id, staff_name, registermobileno, channel_name, auth_token, stafflogin_Id, linked_page) 
        Values
        ('$SectionMaster_Id', '".htmlspecialchars($add_staff_name, ENT_QUOTES)."', '".htmlspecialchars($add_registermobileno, ENT_QUOTES)."', '".htmlspecialchars($add_channel_name, ENT_QUOTES)."', '$add_auth_token', '$ActiveStaffLogin_Id', '$add_linked_page')");
        
        $res['status'] = 'success';
        echo json_encode($res);
    }

    
}
//-----------------------------------------------------------------------------------------------------------------------


//-----------------------------------------------------------------------------------------------------------------------
if(isset($_GET['Edit_TelegramInstance'])){

    extract($_POST);

     
    $updating_CalenderInstance = mysqli_query($mysqli,"Update setup_telegrammaster Set 
    staff_name ='".htmlspecialchars($edit_staff_name, ENT_QUOTES)."',
    registermobileno = '".htmlspecialchars($edit_registermobileno, ENT_QUOTES)."',
    channel_name='".htmlspecialchars($edit_channel_name, ENT_QUOTES)."',
    auth_token='".htmlspecialchars($edit_auth_token, ENT_QUOTES)."',
    linked_page='".htmlspecialchars($edit_linked_page, ENT_QUOTES)."'
    where Id  = '$edit_InstanceId'");

    echo "200";
    
}
//-----------------------------------------------------------------------------------------------------------------------




//-----------------------------------------------------------------------------------------------------------------------
if(isset($_GET['Add_TelegramInstance_InBulk'])){

    $SectionMaster_Id = $_SESSION['schoolzone']['SectionMaster_Id'];  

    // check file name is not empty
    $pathinfo = pathinfo($_FILES["upload_file"]["name"]); //file extension

    $folderMap = '../';
    $fileLocation = "FileUploadLogs/TelegramMaster_".$SectionMaster_Id."_".date("Y-m-d-h-i-s").'.xlsx';
    $targetfolder =  $folderMap."".$fileLocation;
    move_uploaded_file($_FILES['upload_file']['name'], $targetfolder);

    $writer = WriterFactory::create(Type::XLSX); // for XLSX files

    $fileName= $targetfolder;
    $writer->openToFile($fileName); // write data to a file or to a PHP stream
    // $writer->openToBrowser($fileName); // stream data directly to the browser

    $singleRow =['Sr No','Staff Name','Registered Mobile No','Channel Name','Auth Token','Upload Status'];
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

    $StreamDetails_q = mysqli_query($mysqli, "SELECT setup_telegrammaster.* FROM setup_telegrammaster Where setup_telegrammaster.sectionmaster_Id  = '$SectionMaster_Id' AND setup_telegrammaster.channel_name = '".htmlspecialchars($row[3], ENT_QUOTES)."'");

    $row_StreamDetails = mysqli_num_rows($StreamDetails_q);



    if($row_StreamDetails == '0'){

        //checking Group and Sem Belong to this Program



        $Inserting_StaffQualification = mysqli_query($mysqli,"Insert into setup_telegrammaster
        (sectionmaster_Id, staff_name, registermobileno, channel_name, auth_token, stafflogin_Id) 
        Values
        ('$SectionMaster_Id', '".htmlspecialchars($row[1], ENT_QUOTES)."', '".htmlspecialchars($row[2], ENT_QUOTES)."', '".htmlspecialchars($row[3], ENT_QUOTES)."', '$row[4]', '$ActiveStaffLogin_Id')");



        if(mysqli_error($mysqli)){
            $displayMessage[]  = $row[3].' : Query Failed';
            $uploadMessage = "Query Failed";
        }else{
            $displayMessage[]  = $row[3].' : Telegram Entry Added';
            $uploadMessage = "Telegram Entry Added";    
        }

        

        $multipleRows=[
            [$row[0],$row[1],$row[2],$row[3],$row[4],$uploadMessage],
        ];
        $writer->addRows($multipleRows); // add multiple rows at a time

        
    }elseif($row_StreamDetails > '0'){
        $displayMessage[]  = $row[3].' : Telegram Entry Already Present';
        $uploadMessage = "Telegram Entry Already Added";

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
if(isset($_GET['Add_SMSHeaderInstance'])){
   
    extract($_POST);

    $ActiveStaffLogin_Id = $_SESSION['schoolzone']['ActiveStaffLogin_Id'];
    $SectionMaster_Id = $_SESSION['schoolzone']['SectionMaster_Id'];

    //checking Department Abbbr & Name
    $depart_fetch_q = mysqli_query($mysqli,"SELECT comm_sms_header_ids.* FROM comm_sms_header_ids Where comm_sms_header_ids.sectionmaster_Id = '$SectionMaster_Id' AND (comm_sms_header_ids.header_name = '".htmlspecialchars($add_header_name, ENT_QUOTES)."' OR comm_sms_header_ids.PE_Id = '".htmlspecialchars($add_PE_Id, ENT_QUOTES)."')");


    $row_depart_fetch = mysqli_num_rows($depart_fetch_q);

    if($row_depart_fetch > 0){

        
        $res['status'] = 'EXISTS';
        echo json_encode($res);

    }else{

        $Inserting_StaffQualification = mysqli_query($mysqli,"Insert into comm_sms_header_ids
        (sectionmaster_Id, header_name, PE_Id) 
        Values
        ('$SectionMaster_Id', '".htmlspecialchars($add_header_name, ENT_QUOTES)."', '".htmlspecialchars($add_PE_Id, ENT_QUOTES)."')");
        
        $res['status'] = 'success';
        echo json_encode($res);
    }

    
}
//-----------------------------------------------------------------------------------------------------------------------

//-----------------------------------------------------------------------------------------------------------------------
if(isset($_GET['Edit_SMSHeaderInstance'])){

    extract($_POST);

     
    $updating_CalenderInstance = mysqli_query($mysqli,"Update comm_sms_header_ids Set header_name = '".htmlspecialchars($edit_header_name, ENT_QUOTES)."',PE_Id='".htmlspecialchars($edit_PE_Id, ENT_QUOTES)."' where Id  = '$edit_InstanceId'");

    echo "200";
    
}
//-----------------------------------------------------------------------------------------------------------------------


//-----------------------------------------------------------------------------------------------------------------------
if(isset($_GET['Delete_SMSHeaderInstance'])){

    extract($_POST);

    $deleting_formheader = mysqli_query($mysqli,"DELETE FROM comm_sms_header_ids where Id = '$delete_instance_Id'");

    echo "200";
    
}
//----------------------------------------------------------------------------------------------------


//-----------------------------------------------------------------------------------------------------------------------
if(isset($_GET['Add_SMSHeaderInstance_InBulk'])){

    $SectionMaster_Id = $_SESSION['schoolzone']['SectionMaster_Id'];  

    // check file name is not empty
    $pathinfo = pathinfo($_FILES["upload_file"]["name"]); //file extension

    $folderMap = '../';
    $fileLocation = "FileUploadLogs/SMSHeaderMaster_".$SectionMaster_Id."_".date("Y-m-d-h-i-s").'.xlsx';
    $targetfolder =  $folderMap."".$fileLocation;
    move_uploaded_file($_FILES['upload_file']['name'], $targetfolder);

    $writer = WriterFactory::create(Type::XLSX); // for XLSX files

    $fileName= $targetfolder;
    $writer->openToFile($fileName); // write data to a file or to a PHP stream
    // $writer->openToBrowser($fileName); // stream data directly to the browser

    $singleRow =['Sr No','Header Name','PE Id','Upload Status'];
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

    $StreamDetails_q = mysqli_query($mysqli, "SELECT comm_sms_header_ids.* FROM comm_sms_header_ids Where comm_sms_header_ids.sectionmaster_Id = '$SectionMaster_Id' AND (comm_sms_header_ids.header_name = '".htmlspecialchars($row[1], ENT_QUOTES)."' OR comm_sms_header_ids.PE_Id = '".htmlspecialchars($row[2], ENT_QUOTES)."')");

    $row_StreamDetails = mysqli_num_rows($StreamDetails_q);



    if($row_StreamDetails == '0'){

        //checking Group and Sem Belong to this Program

        
    
    
        $Inserting_StaffQualification = mysqli_query($mysqli,"Insert into comm_sms_header_ids
        (sectionmaster_Id, header_name, PE_Id) 
        Values
        ('$SectionMaster_Id', '".htmlspecialchars($row[1], ENT_QUOTES)."', '".htmlspecialchars($row[2], ENT_QUOTES)."')");



        if(mysqli_error($mysqli)){
            $displayMessage[]  = $row[1].' : Query Failed';
            $uploadMessage = "Query Failed";
        }else{
            $displayMessage[]  = $row[1].' : SMS Header Added';
            $uploadMessage = "SMS Header Added";    
        }

        

        $multipleRows=[
            [$row[0],$row[1],$row[2],$uploadMessage],
        ];
        $writer->addRows($multipleRows); // add multiple rows at a time

        
    }elseif($row_StreamDetails > '0'){
        $displayMessage[]  = $row[1].' : SMS Header Already Present';
        $uploadMessage = "SMS Header Already Added";

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
if(isset($_GET['Change_SMSHeader_DefaultEntry'])){
    $SectionMaster_Id = $_SESSION['schoolzone']['SectionMaster_Id'];  

    $Header_Id = $_POST['Header_Id'];

    //Make ALL 0
    $updating_Attend_Staff = mysqli_query($mysqli,"Update comm_sms_header_ids set isDefault = '0' where sectionmaster_Id = '$SectionMaster_Id'");


    //Make Selected 1
    $updating_Attend_Staff = mysqli_query($mysqli,"Update comm_sms_header_ids set isDefault = '1' where Id = '$Header_Id'");

    echo "200";
}
//-----------------------------------------------------------------------------------------------------------------------

//-----------------------------------------------------------------------------------------------------------------------
if(isset($_GET['Edit_SMSTemplateInstance'])){

    extract($_POST);

     
    $updating_CalenderInstance = mysqli_query($mysqli,"Update comm_sms_templates Set 
    registered_sms_template = '".htmlspecialchars($edit_registered_sms_template, ENT_QUOTES)."',
    template_Id='".htmlspecialchars($edit_template_Id, ENT_QUOTES)."',
    date_of_approval= '".date('Y-m-d',strtotime(str_replace('/','-',$edit_date_of_approval)))."',
    actual_message_template='".htmlspecialchars($edit_actual_message_template, ENT_QUOTES)."'
    where Id  = '$edit_InstanceId'");


    echo "200";
    
}
//-----------------------------------------------------------------------------------------------------------------------


//-----------------------------------------------------------------------------------------------------------------------
if(isset($_GET['Delete_SMSTemplateInstance'])){

    extract($_POST);

    $deleting_formheader = mysqli_query($mysqli,"DELETE FROM comm_sms_templates where Id = '$delete_instance_Id'");

    echo "200";
    
}
//----------------------------------------------------------------------------------------------------


//-----------------------------------------------------------------------------------------------------------------------
if(isset($_GET['Add_SMSTemplateInstance'])){
   
    extract($_POST);

    $ActiveStaffLogin_Id = $_SESSION['schoolzone']['ActiveStaffLogin_Id'];
    $SectionMaster_Id = $_SESSION['schoolzone']['SectionMaster_Id'];

    //checking Department Abbbr & Name
    $depart_fetch_q = mysqli_query($mysqli,"SELECT comm_sms_templates.* FROM comm_sms_templates Where comm_sms_templates.sms_header_Id = '$add_sms_header_Id' AND comm_sms_templates.template_Id = '$add_template_Id' ");

    $row_depart_fetch = mysqli_num_rows($depart_fetch_q);

    if($row_depart_fetch > 0){

        
        $res['status'] = 'EXISTS';
        echo json_encode($res);

    }else{

        $Inserting_StaffQualification = mysqli_query($mysqli,"Insert into comm_sms_templates
        (sms_header_Id, registered_sms_template, template_Id, date_of_approval, actual_message_template) 
        Values
        ('$add_sms_header_Id', '".htmlspecialchars($add_registered_sms_template, ENT_QUOTES)."', '".htmlspecialchars($add_template_Id, ENT_QUOTES)."', '".date('Y-m-d',strtotime(str_replace('/','-',$add_date_of_approval)))."' , '".htmlspecialchars($add_actual_message_template, ENT_QUOTES)."')");
        
        $res['status'] = 'success';
        echo json_encode($res);
    }

    
}
//-----------------------------------------------------------------------------------------------------------------------




//-----------------------------------------------------------------------------------------------------------------------
if(isset($_GET['Add_SMSTemplateInstance_InBulk'])){

    $SectionMaster_Id = $_SESSION['schoolzone']['SectionMaster_Id'];  
    $add_sms_header_Id = $_GET['add_sms_header_Id'];

    // check file name is not empty
    $pathinfo = pathinfo($_FILES["upload_file"]["name"]); //file extension

    $folderMap = '../';
    $fileLocation = "FileUploadLogs/SMSTemplateMaster_".$SectionMaster_Id."_".date("Y-m-d-h-i-s").'.xlsx';
    $targetfolder =  $folderMap."".$fileLocation;
    move_uploaded_file($_FILES['upload_file']['name'], $targetfolder);

    $writer = WriterFactory::create(Type::XLSX); // for XLSX files

    $fileName= $targetfolder;
    $writer->openToFile($fileName); // write data to a file or to a PHP stream
    // $writer->openToBrowser($fileName); // stream data directly to the browser

    $singleRow =['Sr No','Registered SMS Template','Template Id','Date Of Approval','Actual Message Template','Upload Status'];
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

    $StreamDetails_q = mysqli_query($mysqli, "SELECT comm_sms_templates.* FROM comm_sms_templates Where comm_sms_templates.sms_header_Id = '$add_sms_header_Id' AND comm_sms_templates.template_Id = '".htmlspecialchars($row[2], ENT_QUOTES)."'");

    $row_StreamDetails = mysqli_num_rows($StreamDetails_q);



    if($row_StreamDetails == '0'){

        //checking Group and Sem Belong to this Program


        $Inserting_StaffQualification = mysqli_query($mysqli,"Insert into comm_sms_templates
        (sms_header_Id, registered_sms_template, template_Id, date_of_approval, actual_message_template) 
        Values
        ('$add_sms_header_Id', '".htmlspecialchars($row[1], ENT_QUOTES)."', '".htmlspecialchars($row[2], ENT_QUOTES)."', '".htmlspecialchars($row[3]->format('Y-m-d'), ENT_QUOTES)."', '".htmlspecialchars($row[4], ENT_QUOTES)."')");
        

        if(mysqli_error($mysqli)){
            $displayMessage[]  = $row[1].' : Query Failed';
            $uploadMessage = "Query Failed";
        }else{
            $displayMessage[]  = $row[1].' : SMS Template Added';
            $uploadMessage = "SMS Template Added";    
        }

        

        $multipleRows=[
            [$row[0],$row[1],$row[2],$row[3]->format('Y-m-d'),$row[4],$uploadMessage],
        ];
        $writer->addRows($multipleRows); // add multiple rows at a time

        
    }elseif($row_StreamDetails > '0'){
        $displayMessage[]  = $row[1].' : SMS Template Already Present';
        $uploadMessage = "SMS Template Already Added";

        $multipleRows=[
            [$row[0],$row[1],$row[2],$row[3]->format('Y-m-d'),$row[4],$uploadMessage],
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
if(isset($_GET['Delete_EmailsInstance'])){

    extract($_POST);

    $deleting_formheader = mysqli_query($mysqli,"DELETE FROM setup_sectionmaildetails where Id = '$delete_instance_Id'");

    echo "200";
    
}
//-----------------------------------------------------------------------------------------------------------------------

//-----------------------------------------------------------------------------------------------------------------------
if(isset($_GET['Add_EmailsInstance'])){
   
    extract($_POST);

    $ActiveStaffLogin_Id = $_SESSION['schoolzone']['ActiveStaffLogin_Id'];
    $SectionMaster_Id = $_SESSION['schoolzone']['SectionMaster_Id'];

    //checking Department Abbbr & Name
    $depart_fetch_q = mysqli_query($mysqli,"SELECT setup_sectionmaildetails.* FROM setup_sectionmaildetails Where setup_sectionmaildetails.sectionmaster_Id  = '$SectionMaster_Id' AND setup_sectionmaildetails.username = '".htmlspecialchars($add_username, ENT_QUOTES)."' ");


    $row_depart_fetch = mysqli_num_rows($depart_fetch_q);

    if($row_depart_fetch > 0){

        
        $res['status'] = 'EXISTS';
        echo json_encode($res);

    }else{

        $Inserting_StaffQualification = mysqli_query($mysqli,"Insert into setup_sectionmaildetails
        (sectionmaster_Id, host, username, password, setFromAddress, setFromName) 
        Values
        ('$SectionMaster_Id', '".htmlspecialchars($add_host, ENT_QUOTES)."', '".htmlspecialchars($add_username, ENT_QUOTES)."', '".htmlspecialchars($add_password, ENT_QUOTES)."', '$add_setFromAddress', '$add_setFromName')");
        
        $res['status'] = 'success';
        echo json_encode($res);
    }

    
}
//-----------------------------------------------------------------------------------------------------------------------


//-----------------------------------------------------------------------------------------------------------------------
if(isset($_GET['Edit_EmailsInstance'])){

    extract($_POST);

     
    $updating_CalenderInstance = mysqli_query($mysqli,"Update setup_sectionmaildetails Set 
    host ='".htmlspecialchars($edit_host, ENT_QUOTES)."',
    username = '".htmlspecialchars($edit_username, ENT_QUOTES)."',
    password='".htmlspecialchars($edit_password, ENT_QUOTES)."',
    setFromAddress='".htmlspecialchars($edit_setFromAddress, ENT_QUOTES)."',
    setFromName='".htmlspecialchars($edit_setFromName, ENT_QUOTES)."'
    where Id  = '$edit_InstanceId'");

    echo "200";
    
}
//-----------------------------------------------------------------------------------------------------------------------

//-----------------------------------------------------------------------------------------------------------------------
if(isset($_GET['Add_EmailsInstance_InBulk'])){

    $SectionMaster_Id = $_SESSION['schoolzone']['SectionMaster_Id'];  
  
    // check file name is not empty
    $pathinfo = pathinfo($_FILES["upload_file"]["name"]); //file extension

    $folderMap = '../';
    $fileLocation = "FileUploadLogs/EmailTemplateMaster_".$SectionMaster_Id."_".date("Y-m-d-h-i-s").'.xlsx';
    $targetfolder =  $folderMap."".$fileLocation;
    move_uploaded_file($_FILES['upload_file']['name'], $targetfolder);

    $writer = WriterFactory::create(Type::XLSX); // for XLSX files

    $fileName= $targetfolder;
    $writer->openToFile($fileName); // write data to a file or to a PHP stream
    // $writer->openToBrowser($fileName); // stream data directly to the browser

    $singleRow =['Sr No','Host','Username','Password','Set From Address','Set From Name','Upload Status'];
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

    $StreamDetails_q = mysqli_query($mysqli, "SELECT setup_sectionmaildetails.* FROM setup_sectionmaildetails Where setup_sectionmaildetails.sectionmaster_Id  = '$SectionMaster_Id' AND setup_sectionmaildetails.username = '".htmlspecialchars($row[2], ENT_QUOTES)."'");

    $row_StreamDetails = mysqli_num_rows($StreamDetails_q);



    if($row_StreamDetails == '0'){

        //checking Group and Sem Belong to this Program


        $Inserting_StaffQualification = mysqli_query($mysqli,"Insert into setup_sectionmaildetails
        (sectionmaster_Id, host, username, password, setFromAddress, setFromName) 
        Values
        ('$SectionMaster_Id', '".htmlspecialchars($row[1], ENT_QUOTES)."', '".htmlspecialchars($row[2], ENT_QUOTES)."', '".htmlspecialchars($row[3], ENT_QUOTES)."', '$row[4]', '$row[5]')");
        

        if(mysqli_error($mysqli)){
            $displayMessage[]  = $row[1].' : Query Failed';
            $uploadMessage = "Query Failed";
        }else{
            $displayMessage[]  = $row[1].' : Email Template Added';
            $uploadMessage = "Email Template Added";    
        }

        

        $multipleRows=[
            [$row[0],$row[1],$row[2],$row[3],$row[4],$row[5],$uploadMessage],
        ];
        $writer->addRows($multipleRows); // add multiple rows at a time

        
    }elseif($row_StreamDetails > '0'){
        $displayMessage[]  = $row[1].' : Email Template Already Present';
        $uploadMessage = "Email Template Already Added";

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
if(isset($_GET['Change_EmailsInstance_DefaultEntry'])){
    $SectionMaster_Id = $_SESSION['schoolzone']['SectionMaster_Id'];  

    $AY_Id = $_POST['AY_Id'];

    //Make ALL 0
    $updating_Attend_Staff = mysqli_query($mysqli,"Update setup_sectionmaildetails set isDefault = '0' where sectionmaster_Id = '$SectionMaster_Id'");


    //Make Selected 1
    $updating_Attend_Staff = mysqli_query($mysqli,"Update setup_sectionmaildetails set isDefault = '1' where Id = '$AY_Id'");

    echo "200";
}
//-----------------------------------------------------------------------------------------------------------------------



?>
