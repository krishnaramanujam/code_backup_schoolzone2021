<?php
session_start();
date_default_timezone_set("Asia/Calcutta");
ini_set('max_execution_time', 0);
set_time_limit(0);
ini_set('memory_limit','-1');
ini_set('display_errors',1);
error_reporting(E_ALL);


include('../../config/database.php');

//-----------------------------------------------------------------------------------------------------------------------
if(isset($_GET['Validate_LoginForm'])){

    extract($_POST);
       $time = date("Y-m-d h:i:s");
       // Checking the Number is Valid or Not
	   $checking_number = mysqli_query($mysqli, "SELECT user_stafflogin.* FROM `user_stafflogin` JOIN setup_departmentmaster ON setup_departmentmaster.Id = user_stafflogin.departmentmaster_Id JOIN setup_sectionmaster ON setup_sectionmaster.Id = setup_departmentmaster.sectionmaster_Id WHERE user_stafflogin.username = '$login_username' AND setup_sectionmaster.Id = '$SM_Id' "); 

	   $row_check = mysqli_num_rows($checking_number);
   
	   
	   if($row_check > '0') {
		   // Number Exist and Not Valid
		   $r_checking_number = mysqli_fetch_array($checking_number);

           $stafflogin_Id = $r_checking_number['Id']; 

           $staffLoginStatus = $r_checking_number['staff_status'];

           if($staffLoginStatus == '1'){

                $savedPass = $r_checking_number['password'];
            
                if(password_verify($login_password,$savedPass)){

                        
                        $count_Inc = (int)$r_checking_number['login_count'];
                            
                        $newCount = $count_Inc + 1;

                        //Updating Login Count
                        $updating_SR = mysqli_query($mysqli,"Update user_stafflogin set user_stafflogin.login_count = '$newCount' Where Id = '$stafflogin_Id'");

                        //Inserting Log in Activity log
                        $Inserting_UserDetails = mysqli_query($mysqli,"Insert into user_activitylog (activityType_Id, user_Id, userType, timeStamp) values ('1', '$stafflogin_Id' ,'0', '$time' )");

                        $_SESSION['schoolzone']['SectionMaster_Id'] = $SM_Id;
                        $_SESSION['schoolzone']['ActiveStaffLogin_Id'] = $stafflogin_Id;

                        $res['status'] = 'success';
                        echo json_encode($res);
        
                }else{

        
                    $res['status'] = 'failed';
                    echo json_encode($res); 
                }

           }else{
                //Login Status Disabled
                $res['status'] = 'NOPERMISSION';
                echo json_encode($res); 
           }
   
	   } else {

            //Checking That User is SUperAdmin
            if($login_username == 'superadmin' || $login_username == 'sectionadmin'){
                
                $checking_fetching_password = mysqli_query($mysqli, "SELECT user_stafflogin.* FROM `user_stafflogin` WHERE user_stafflogin.username = '$login_username'"); 
                $r_checking_details = mysqli_fetch_array($checking_fetching_password);
                $stafflogin_Id = $r_checking_details['Id']; 
                $savedPass = $r_checking_details['password'];

                if(password_verify($login_password,$savedPass)){
                    $count_Inc = (int)$r_checking_details['login_count'];
                    
                    $newCount = $count_Inc + 1;

                    //Updating Login Count
                    $updating_SR = mysqli_query($mysqli,"Update user_stafflogin set user_stafflogin.login_count = '$newCount' Where Id = '$stafflogin_Id'");

                    //Inserting Log in Activity log
                    $Inserting_UserDetails = mysqli_query($mysqli,"Insert into user_activitylog (activityType_Id, user_Id, userType, timeStamp) values ('1', '$stafflogin_Id' ,'0', '$time' )");

                    $_SESSION['schoolzone']['SectionMaster_Id'] = $SM_Id;
                    $_SESSION['schoolzone']['ActiveStaffLogin_Id'] = $stafflogin_Id;
                    $_SESSION['schoolzone']['ViaDirectLogin'] = 'OFF';

                    $res['status'] = 'success';
                    echo json_encode($res);
                    exit();
                    
                } // close password Verfiy
            } // close if        



            // Username Not Exist
            $res['status'] = 'failed';
            echo json_encode($res);

		   
	   }
    
    
}
//-----------------------------------------------------------------------------------------------------------------------


//-----------------------------------------------------------------------------------------------------------------------
if(isset($_GET['Request_ForChangeMobileNo'])){

    extract($_POST);
    include('../communication/functions.php');

    
    // Checking the Number is Valid or Not
    $checking_pnr = mysqli_query($mysqli, "SELECT user_stafflogin.Id AS SL_Id, user_stafflogin.reg_mobile_no As registeredMobileNo,user_stafflogin.reg_email_address, user_stafflogin.verificationCode, user_stafflogin.staff_status FROM user_stafflogin JOIN setup_departmentmaster ON setup_departmentmaster.Id = user_stafflogin.departmentmaster_Id JOIN setup_sectionmaster ON setup_sectionmaster.Id = setup_departmentmaster.sectionmaster_Id WHERE user_stafflogin.username = '$user_loginId' AND setup_sectionmaster.Id = '$SM_Id' "); 

    $row_check = mysqli_num_rows($checking_pnr);
    

    if($row_check > '0') {
        $r_detail_fetch = mysqli_fetch_array($checking_pnr);


        $SL_Id = $r_detail_fetch['SL_Id'];
        $user_mobileno = $r_detail_fetch['registeredMobileNo'];
        $user_emailaddress = $r_detail_fetch['reg_email_address'];

        $staffLoginStatus = $r_detail_fetch['staff_status'];

        if($staffLoginStatus == '1'){

                if(!empty($user_mobileno)){
            
                                
                    //Fetching Template
                    $fetching_header_ids_q = mysqli_query($mysqli,"SELECT comm_sms_header_ids.sectionmaster_Id, comm_sms_header_ids.Id As CSHI_Id, comm_sms_header_ids.header_name, comm_sms_header_ids.PE_Id, comm_sms_templates.registered_sms_template, comm_sms_templates.template_Id, comm_sms_templates.msg_type,  comm_sms_templates.Id As CST_Id, comm_sms_templates.actual_message_template FROM comm_sms_header_ids JOIN comm_sms_templates ON comm_sms_templates.sms_header_Id = comm_sms_header_ids.Id WHERE comm_sms_header_ids.isDefault = '1' AND comm_sms_header_ids.sectionmaster_Id = '$SM_Id' AND comm_sms_templates.msg_type = '1' ");
                    $r_fetch_header_ids = mysqli_fetch_array($fetching_header_ids_q);

                    $sender_header_Id = $r_fetch_header_ids['CSHI_Id'];
                    $template_Id = $r_fetch_header_ids['CST_Id'];

                    $message_template = $r_fetch_header_ids['actual_message_template'];

                    $Student_Res = StaffMessageDecrypt($SL_Id, $message_template, $mysqli);
            
                    $message = $Student_Res['Decrypt_Message'];
                    $pin = $Student_Res['VerficationCode'];

                    
                    $updating_studentregister = mysqli_query($mysqli,"Update user_stafflogin set verificationCode = '$pin' Where Id = '$SL_Id'");
            
                    
           
                    $no_of_charater = strlen($message);
                    $userIdType = 'SL_Id';
                    $time = date("Y-m-d h:m:s");
                
                    $format_message =  htmlspecialchars($message, ENT_QUOTES);

            

                    $Inserting_UserDetails = mysqli_query($mysqli,"Insert into comm_message_log (User_Id, message_type, recipient_address, no_of_characters, Decrypt_Msg, sender_name, timestamp, SenderHeader, userIdType, moduleType, template_Id) values ('$SL_Id', '1' ,'$user_mobileno', '$no_of_charater' , '".$format_message."', '1', '$time' , '$sender_header_Id', '$userIdType', 'StaffPortal', '$template_Id')");

                
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

        
                    

                    if(!empty($user_emailaddress)){
                       //Fetching Mail Details
                       $mail_detail_q = mysqli_query($mysqli, "SELECT setup_sectionmaildetails.* FROM `setup_sectionmaildetails` WHERE setup_sectionmaildetails.sectionmaster_Id = '$SM_Id' AND setup_sectionmaildetails.isDefault = '1' ");
                       $r_mail_detail = mysqli_fetch_array($mail_detail_q);
   
                       $mail_sender_header = $r_mail_detail['Id'];
                       $emailSubjects = 'Verfication Code';
   
                       
                       $Inserting_UserDetailsa = mysqli_query($mysqli,"Insert into comm_message_log (User_Id, message_type, recipient_address, no_of_characters, Decrypt_Msg, sender_name, timestamp, SenderHeader, userIdType, moduleType, template_Id , email_Subjects, Status) values ('$SL_Id', '2' ,'$user_emailaddress', '$no_of_charater' , '".$format_message."', '1', '$time' , '$mail_sender_header', '$userIdType', 'StaffPortal', '$template_Id', '$emailSubjects', 'Pending')");
                       
   
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
            
                    $res['SL_Id'] = $SL_Id;

                    $res['status'] = 'success';
                    echo json_encode($res);
                // close empty       
                }else{
                    $res['status'] = 'MobileNoFailed';
                    echo json_encode($res);     
                }

        }else{
            //Login Status Disabled
            $res['status'] = 'NOPERMISSION';
            echo json_encode($res); 
        }

     } else {
        // Number Not Exist and Not Valid
        $res['status'] = 'failed';
        echo json_encode($res);
     }


}
//-----------------------------------------------------------------------------------------------------------------------



//-----------------------------------------------------------------------------------------------------------------------
if(isset($_GET['Request_ForOTPVerfication'])){

    extract($_POST);

    // Checking the Number is Valid or Not
    $checking_pnr = mysqli_query($mysqli, "SELECT user_stafflogin.Id AS SL_Id, user_stafflogin.reg_mobile_no As registeredMobileNo, user_stafflogin.verificationCode FROM user_stafflogin WHERE user_stafflogin.Id = '$SL_Id' And user_stafflogin.verificationCode = '$user_otp'"); 

    $row_check = mysqli_num_rows($checking_pnr);
    

    if($row_check > '0') {
        $r_detail_fetch = mysqli_fetch_array($checking_pnr);
      
  
        $res['status'] = 'success';
        echo json_encode($res);

     } else {
        // Number Not Exist and Not Valid
        $res['status'] = 'failed';
        echo json_encode($res);
     }


}
//-----------------------------------------------------------------------------------------------------------------------


//-----------------------------------------------------------------------------------------------------------------------
if(isset($_GET['Request_NewPasswordChanged'])){

    extract($_POST);

    $password = password_hash( $new_password, PASSWORD_DEFAULT );
    $updating_studentregister = mysqli_query($mysqli,"Update user_stafflogin set password = '$password' Where Id = '$SL_Id'");

    $res['status'] = 'success';
    echo json_encode($res);

}
//-----------------------------------------------------------------------------------------------------------------------
//-----------------------------------------------------------------------------------------------------------------------
if(isset($_GET['Change_Academic_Year'])){

    extract($_POST);

    $result = mysqli_query( $mysqli, "SELECT password FROM user_stafflogin WHERE Id = " . $_SESSION['schoolzone']['ActiveStaffLogin_Id'] . " LIMIT 1 " );

    // echo "SELECT password FROM user_stafflogin WHERE Id = " . $_SESSION['schoolzone']['ActiveStaffLogin_Id'] . " LIMIT 1 ";
    $res = mysqli_fetch_assoc( $result );
    $old_password = $res['password'];
  
    if ( password_verify( $_POST['passworday'], $old_password ) )
    {
      $_SESSION['AY_ID_New'] = $_POST['modal_ay_change_pass'];
      echo('Success');
    }
    else
    {
      echo("Incorrect Password!!!");
    }

}
//-----------------------------------------------------------------------------------------------------------------------


//-----------------------------------------------------------------------------------------------------------------------
if(isset($_GET['Change_PersonalDetails'])){

    extract($_POST);

    
    $enter_Date = date('Y-m-d',strtotime(str_replace('/','-',$staff_dateofbirth)));


    $User_Id = $_SESSION['schoolzone']['ActiveStaffLogin_Id'];

    // Checking the Number is Valid or Not
    $checking_pnr = mysqli_query($mysqli, "SELECT user_stafflogin.Id AS SL_Id, user_stafflogin.reg_mobile_no As registeredMobileNo, user_stafflogin.verificationCode FROM user_stafflogin WHERE user_stafflogin.Id = '$User_Id'  AND user_stafflogin.date_of_birth = '$enter_Date'"); 



    $row_check = mysqli_num_rows($checking_pnr);
    

    if($row_check > '0') {
        $r_detail_fetch = mysqli_fetch_array($checking_pnr);

        if($staff_operation == 'CHANGE_MOBILE'){
            $updating_studentregister = mysqli_query($mysqli,"Update user_stafflogin set reg_mobile_no = '$staff_newmobileno' Where Id = '$User_Id'");
        }elseif($staff_operation == 'CHANGE_EMAIL'){
            $updating_studentregister = mysqli_query($mysqli,"Update user_stafflogin set reg_email_address = '$staff_newemailaddress' Where Id = '$User_Id'");
        }elseif($staff_operation == 'CHANGE_PASSWORD'){
            $password = password_hash( $staff_newpassword, PASSWORD_DEFAULT );
            $updating_studentregister = mysqli_query($mysqli,"Update user_stafflogin set password = '$password' Where Id = '$User_Id'");
        }
        
        
        $res['status'] = 'success';
        echo json_encode($res);
    } else {
    // Number Not Exist and Not Valid
        $res['status'] = 'failed';
        echo json_encode($res);
    }



}
//-----------------------------------------------------------------------------------------------------------------------