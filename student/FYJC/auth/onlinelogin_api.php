<?php
session_start();
date_default_timezone_set("Asia/Calcutta");
ini_set('max_execution_time', 0);
set_time_limit(0);
ini_set('memory_limit','-1');
ini_set('display_errors',1);
error_reporting(E_ALL);


include('../../../config/database_candidate.php');

//-----------------------------------------------------------------------------------------------------------------------
if(isset($_GET['Validate_RegisterForm'])){
    
    include('../../../staff/communication/functions.php');

    extract($_POST);

    $checking_login = mysqli_query($mysqli,"SELECT * FROM `user_candidateregister` Where ContactNo = '$register_mobile_no' AND batchmaster_Id = '$register_batch_sel' ");
    $rows_login = mysqli_num_rows($checking_login);

    if($rows_login > 0){
        //Login Id ALready Exist
        $r_detail_fetch = mysqli_fetch_array($checking_login);


        $CR_Id = $r_detail_fetch['Id']; 
        // echo $CR_Id;
        
        $res['status'] = 'NumberExist';
        echo json_encode($res);
    
    }else{

        $pin = mt_rand(100000, 999999);

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
        '$register_mobile_no',
        '$register_email_id',
        '$register_batch_sel',
        '$register_first_name')");

        $CR_Id =  mysqli_insert_id($mysqli);


        //Fetching Template
        $fetching_header_ids_q = mysqli_query($mysqli,"SELECT comm_sms_header_ids.sectionmaster_Id, comm_sms_header_ids.Id As CSHI_Id, comm_sms_header_ids.header_name, comm_sms_header_ids.PE_Id, comm_sms_templates.registered_sms_template, comm_sms_templates.template_Id, comm_sms_templates.msg_type,  comm_sms_templates.Id As CST_Id, comm_sms_templates.actual_message_template FROM comm_sms_header_ids JOIN comm_sms_templates ON comm_sms_templates.sms_header_Id = comm_sms_header_ids.Id WHERE comm_sms_header_ids.isDefault = '1' AND comm_sms_header_ids.sectionmaster_Id = '$SM_Id' AND comm_sms_templates.msg_type = '0' ");
        $r_fetch_header_ids = mysqli_fetch_array($fetching_header_ids_q);

        $sender_header_Id = $r_fetch_header_ids['CSHI_Id'];
        $template_Id = $r_fetch_header_ids['CST_Id'];

        $message_template = $r_fetch_header_ids['actual_message_template'];

        $Student_Res = CandidateMessageDecrypt($CR_Id, $message_template, $mysqli);
  

        
        
        $message = $Student_Res['Decrypt_Message'];

        $no_of_charater = strlen($message);
        $userIdType = 'CR_Id';
        $time = date("Y-m-d h:m:s");
     
        $format_message =  htmlspecialchars($message, ENT_QUOTES);

   

        $Inserting_UserDetails = mysqli_query($mysqli,"Insert into comm_message_log (User_Id, message_type, recipient_address, no_of_characters, Decrypt_Msg, sender_name, timestamp, SenderHeader, userIdType, moduleType, template_Id) values ('$CR_Id', '1' ,'$register_mobile_no', '$no_of_charater' , '".$format_message."', '1', '$time' , '$sender_header_Id', '$userIdType', 'Admission', '$template_Id')");

    
        if(mysqli_error($mysqli)){
            $Generating_Error[] = 'Mobile Error Occurred : '; 
        }else{
            $CML_Id_Array[] = mysqli_insert_id($mysqli);
        }              

        if(isset($CML_Id_Array)){
            $format_CML_Id = implode(",",$CML_Id_Array);
        }else{
            $format_CML_Id = 'NA';
        }


        if(!empty($register_email_id)){

            //Fetching Mail Details
            $mail_detail_q = mysqli_query($mysqli, "SELECT setup_sectionmaildetails.* FROM `setup_sectionmaildetails` WHERE setup_sectionmaildetails.sectionmaster_Id = '$SM_Id' AND setup_sectionmaildetails.isDefault = '1' ");
            $r_mail_detail = mysqli_fetch_array($mail_detail_q);

            $mail_sender_header = $r_mail_detail['Id'];
            $emailSubjects = 'Registration Details';

            
            $Inserting_UserDetailsa = mysqli_query($mysqli,"Insert into comm_message_log (User_Id, message_type, recipient_address, no_of_characters, Decrypt_Msg, sender_name, timestamp, SenderHeader, userIdType, moduleType, template_Id , email_Subjects, Status) values ('$CR_Id', '2' ,'$register_email_id', '$no_of_charater' , '".$format_message."', '1', '$time' , '$mail_sender_header', '$userIdType', 'Admission', '$template_Id', '$emailSubjects', 'Pending')");
            

            if(mysqli_error($mysqli)){
                $Generating_Error[] = 'Email Error Occurred : '; 
            }else{
                $CEL_Id_Array[] = mysqli_insert_id($mysqli);
            }              

            if(isset($CEL_Id_Array)){
                $format_CEL_Id = implode(",",$CEL_Id_Array);
            }else{
                $format_CEL_Id = 'NA';
            }
        }//close email ematy
        
      




        $res['Error_Message'] = $Generating_Error;
        $res['Comm_Message_Logs_Id'] = $format_CML_Id;
        $res['Comm_Email_Logs_Id'] = $format_CEL_Id;

        
        $res['status'] = 'success';
        echo json_encode($res);

    }

}
//-----------------------------------------------------------------------------------------------------------------------



//-----------------------------------------------------------------------------------------------------------------------
if(isset($_GET['Validate_LoginForm'])){

    extract($_POST);
        $time = date("Y-m-d h:i:s");
       // Checking the Number is Valid or Not
	   $checking_number = mysqli_query($mysqli, "Select user_candidateregister.* from user_candidateregister WHERE user_candidateregister.batchmaster_Id = '$login_batch_sel' AND user_candidateregister.ContactNo = '$login_username'  AND user_candidateregister.OTP = '$login_password' "); 

	   $row_check = mysqli_num_rows($checking_number);
   
	   
	   if($row_check > '0') {
		   // Number Exist and Not Valid
		   $r_checking_number = mysqli_fetch_array($checking_number);

           $CR_Id = $r_checking_number['Id']; 

           $staffLoginStatus = $r_checking_number['candidate_status'];

           if($staffLoginStatus == '1'){

                    
                    $count_Inc = (int)$r_checking_number['Login_Count'];
                        
                    $newCount = $count_Inc + 1;

                    //Updating Login Count
                    $updating_SR = mysqli_query($mysqli,"Update user_candidateregister set user_candidateregister.Login_Count = '$newCount' Where Id = '$CR_Id'");

                    //Inserting Log in Activity log
                    $Inserting_UserDetails = mysqli_query($mysqli,"Insert into user_activitylog (activityType_Id, user_Id, userType, timeStamp) values ('1', '$CR_Id' ,'1', '$time' )");

                    $_SESSION['schoolzone_student']['SectionMaster_Id'] = $SM_Id;
                    $_SESSION['schoolzone_student']['Activecandidateregister_Id'] = $CR_Id;

                    $res['status'] = 'success';
                    echo json_encode($res);

            }else{
                //Login Status Disabled
                $res['status'] = 'NOPERMISSION';
                echo json_encode($res); 
            }
            
   
	   } else {

           
            // Username Not Exist
            $res['status'] = 'failed';
            echo json_encode($res);

		   
	   }
    
    
}
//-----------------------------------------------------------------------------------------------------------------------



//-----------------------------------------------------------------------------------------------------------------------
if(isset($_GET['Request_ForResendPassword'])){
    include('../../../staff/communication/functions.php');

    extract($_POST);
        $time = date("Y-m-d h:i:s");
       // Checking the Number is Valid or Not
	   $checking_number = mysqli_query($mysqli, "Select user_candidateregister.* from user_candidateregister WHERE user_candidateregister.batchmaster_Id = '$forget_batch_sel' AND user_candidateregister.ContactNo = '$forget_mobileno' "); 

	   $row_check = mysqli_num_rows($checking_number);
   
	   
	   if($row_check > '0') {
		   // Number Exist and Not Valid
		   $r_checking_number = mysqli_fetch_array($checking_number);

           $CR_Id = $r_checking_number['Id']; 
           $register_mobile_no = $forget_mobileno;
           $register_email_id = $r_checking_number['EmailAddress'];

           $staffLoginStatus = $r_checking_number['candidate_status'];

           if($staffLoginStatus == '1'){


                if($r_checkdetails['resend_password_count'] <= 5) {
                    
                    
                        $count_Inc = (int)$r_checking_number['resend_password_count'];
                            
                        $newCount = $count_Inc + 1;

                        //Updating Login Count
                        $updating_SR = mysqli_query($mysqli,"Update user_candidateregister set user_candidateregister.resend_password_count = '$newCount' Where Id = '$CR_Id'");

                

                                
                        //Fetching Template
                        $fetching_header_ids_q = mysqli_query($mysqli,"SELECT comm_sms_header_ids.sectionmaster_Id, comm_sms_header_ids.Id As CSHI_Id, comm_sms_header_ids.header_name, comm_sms_header_ids.PE_Id, comm_sms_templates.registered_sms_template, comm_sms_templates.template_Id, comm_sms_templates.msg_type,  comm_sms_templates.Id As CST_Id, comm_sms_templates.actual_message_template FROM comm_sms_header_ids JOIN comm_sms_templates ON comm_sms_templates.sms_header_Id = comm_sms_header_ids.Id WHERE comm_sms_header_ids.isDefault = '1' AND comm_sms_header_ids.sectionmaster_Id = '$SM_Id' AND comm_sms_templates.msg_type = '0' ");
                        $r_fetch_header_ids = mysqli_fetch_array($fetching_header_ids_q);

                        $sender_header_Id = $r_fetch_header_ids['CSHI_Id'];
                        $template_Id = $r_fetch_header_ids['CST_Id'];

                        $message_template = $r_fetch_header_ids['actual_message_template'];

                        $Student_Res = CandidateMessageDecrypt($CR_Id, $message_template, $mysqli);
                

                        
                        
                        $message = $Student_Res['Decrypt_Message'];

                        $no_of_charater = strlen($message);
                        $userIdType = 'CR_Id';
                        $time = date("Y-m-d h:m:s");
                    
                        $format_message =  htmlspecialchars($message, ENT_QUOTES);

                

                        $Inserting_UserDetails = mysqli_query($mysqli,"Insert into comm_message_log (User_Id, message_type, recipient_address, no_of_characters, Decrypt_Msg, sender_name, timestamp, SenderHeader, userIdType, moduleType, template_Id) values ('$CR_Id', '1' ,'$register_mobile_no', '$no_of_charater' , '".$format_message."', '1', '$time' , '$sender_header_Id', '$userIdType', 'Admission', '$template_Id')");

                    
                        if(mysqli_error($mysqli)){
                            $Generating_Error[] = 'Mobile Error Occurred : '; 
                        }else{
                            $CML_Id_Array[] = mysqli_insert_id($mysqli);
                        }              

                        if(isset($CML_Id_Array)){
                            $format_CML_Id = implode(",",$CML_Id_Array);
                        }else{
                            $format_CML_Id = 'NA';
                        }


                        if(!empty($register_email_id)){

                            //Fetching Mail Details
                            $mail_detail_q = mysqli_query($mysqli, "SELECT setup_sectionmaildetails.* FROM `setup_sectionmaildetails` WHERE setup_sectionmaildetails.sectionmaster_Id = '$SM_Id' AND setup_sectionmaildetails.isDefault = '1' ");
                            $r_mail_detail = mysqli_fetch_array($mail_detail_q);

                            $mail_sender_header = $r_mail_detail['Id'];
                            $emailSubjects = 'Registration Details';

                            
                            $Inserting_UserDetailsa = mysqli_query($mysqli,"Insert into comm_message_log (User_Id, message_type, recipient_address, no_of_characters, Decrypt_Msg, sender_name, timestamp, SenderHeader, userIdType, moduleType, template_Id , email_Subjects, Status) values ('$CR_Id', '2' ,'$register_email_id', '$no_of_charater' , '".$format_message."', '1', '$time' , '$mail_sender_header', '$userIdType', 'Admission', '$template_Id', '$emailSubjects', 'Pending')");
                            

                            if(mysqli_error($mysqli)){
                                $Generating_Error[] = 'Email Error Occurred : '; 
                            }else{
                                $CEL_Id_Array[] = mysqli_insert_id($mysqli);
                            }              

                            if(isset($CEL_Id_Array)){
                                $format_CEL_Id = implode(",",$CEL_Id_Array);
                            }else{
                                $format_CEL_Id = 'NA';
                            }
                        }//close email ematy
                        
                    




                        $res['Error_Message'] = $Generating_Error;
                        $res['Comm_Message_Logs_Id'] = $format_CML_Id;
                        $res['Comm_Email_Logs_Id'] = $format_CEL_Id;


                        $res['status'] = 'success';
                        echo json_encode($res);


                }else{

                    $res['status'] = 'PasswordSendLimitReached';
                    echo json_encode($res);

                }

            }else{
                //Login Status Disabled
                $res['status'] = 'NOPERMISSION';
                echo json_encode($res); 
            }
    
            
   
	   } else {

           
            // Username Not Exist
            $res['status'] = 'failed';
            echo json_encode($res);

		   
	   }
    
    
}
//-----------------------------------------------------------------------------------------------------------------------
