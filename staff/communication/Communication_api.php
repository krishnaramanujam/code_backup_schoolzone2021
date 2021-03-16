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

//-----------------------------------------------------------------------------------------------------------------------
if(isset($_GET['SendingStudentSMS'])){

        extract($_POST);

        if(isset($CML_Id)){
                                
                $comm_fetch_q = mysqli_query($mysqli, "SELECT comm_message_log.index, comm_message_log.timestamp, comm_message_log.sender_name, comm_message_log.sender_address As phone, comm_message_log.Decrypt_Msg, comm_message_log.SenderHeader,  comm_message_log.User_Id As Users_Id,comm_message_log.template_Id FROM comm_message_log  WHERE comm_message_log.index IN ($CML_Id) ");
        
                $rows_comm_fetch = mysqli_num_rows($comm_fetch_q);
                if($rows_comm_fetch > '0'){
                    while($r_comm_fetch = mysqli_fetch_array($comm_fetch_q)){
                        $message = htmlspecialchars_decode($r_comm_fetch['Decrypt_Msg'], ENT_QUOTES);
                        $mobile_number = $r_comm_fetch['phone'];
                        $senderHeader = $r_comm_fetch['SenderHeader'];
                        $template_Id = $r_comm_fetch['template_Id'];
                        if($mobile_number != '0'){
                        
                            $sess = file_get_contents( "http://sms.rightchoicesms.com/sendsms/sendsms.php?username=RCdvsl17&password=speed100&type=TEXT&sender=" . $senderHeader . "&mobile=" . $mobile_number . "&message=" . urlencode( $message ) . "&peId=1201159166724485185&tempId=" . $template_Id );

                            $sess = str_replace('<br/>', '', $sess);
                            //Updating Reponse in database
                                                        
                            $SMS_ResponseArray = explode("|", $sess);
                            $StatusCode = trim($SMS_ResponseArray[0]);


                            $patt = "/SUBMIT_SUCCESS$/i";
                            $MatchingPattern = preg_match($patt, $StatusCode);

                            $Updating_Error_Reponse = mysqli_query($mysqli, "Update comm_message_log Set comm_message_log.Status = '$StatusCode' Where comm_message_log.index = '$r_comm_fetch[index]'");
                            
                            if($MatchingPattern){ 
                                 $StatusId = trim($SMS_ResponseArray[1]);
                                 $Updating_Error_Reponse = mysqli_query($mysqli, "Update comm_message_log Set comm_message_log.ID = '$StatusId' Where comm_message_log.index = '$r_comm_fetch[index]'");
                            }

    
                            $SMSReponse[] = $sess;
                            $SMSUserId[] = $r_comm_fetch['Users_Id'];
                        }
                    }// close while
                }
        }// close isset
  

        $res['SMSReponse'] = $SMSReponse;
        $res['SMSUserId'] = $SMSUserId;

        $res['status'] = 'success';
        echo json_encode($res);

}
//-----------------------------------------------------------------------------------------------------------------------



//-----------------------------------------------------------------------------------------------------------------------
if(isset($_GET['SendingStudentEmails'])){

    extract($_POST);

    require '../../assets/plugins/phpmailer/PHPMailerAutoload.php';
    
    $fetching_details = mysqli_query($mysqli,"SELECT comm_message_log.* FROM comm_message_log  WHERE comm_message_log.index IN ($CEL_Id) ");

    // $fetching_details = mysqli_query($mysqli,"SELECT * FROM `bulk_mail` Where bulk_mail.sendStatus = '200'");

    
    $row_count = mysqli_num_rows($fetching_details);
    // print_r($row_count);
    if($row_count > 0){ 

        while($r_fetching_details = mysqli_fetch_array($fetching_details)){

            
            if($r_fetching_details['userIdType'] == 'StaffLoginId'){
                //based on Staff login Id

                $maildetails_q = mysqli_query($mysqli, "SELECT setup_sectionmaildetails.* FROM user_stafflogin JOIN setup_departmentmaster ON setup_departmentmaster.Id = user_stafflogin.departmentmaster_Id JOIN setup_sectionmaildetails ON setup_sectionmaildetails.sectionmaster_Id = setup_departmentmaster.sectionmaster_Id WHERE user_stafflogin.Id = '$r_fetching_details[User_Id]' ");
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
            $emailTo = $r_fetching_details['sender_address'];
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
                $mail->addAttachment("./".$r_fetching_details['attachment_path']);
            }


            if($mail->Send()){
                $updating_Status = mysqli_query($mysqli,"Update comm_message_log set comm_message_log.Status = 'Success' Where comm_message_log.index = '$r_fetching_details[index]'");
                $res['EmailResponse'] = 'Success';
                $res['email'] = $emailTo;
                $res['EmailUserId'] = $UserId;
            }else{
                $updating_Status = mysqli_query($mysqli,"Update comm_message_log set comm_message_log.Status = 'Failed' Where comm_message_log.index = '$r_fetching_details[index]'");
                $res['EmailResponse'] = 'Failed';
                $res['email'] = $emailTo;
                $res['EmailUserId'] = $UserId;
            }// close else

            unset($mail);
            sleep(2);
            // unset($fileName);
        }// close while
        
    }else{
        $res['Message'] = 'No New Email Found';
    }    
    

    $res['status'] = 'success';
    echo json_encode($res);    


}
//-----------------------------------------------------------------------------------------------------------------------



?>
