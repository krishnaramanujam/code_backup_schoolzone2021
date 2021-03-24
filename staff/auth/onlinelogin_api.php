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

   
	   } else {

           
            // Username Not Exist
            $res['status'] = 'failed';
            echo json_encode($res);

		   
	   }
    
    
}
//-----------------------------------------------------------------------------------------------------------------------


//-----------------------------------------------------------------------------------------------------------------------
if(isset($_GET['Request_ForChangeMobileNo'])){

    extract($_POST);

    
    // Checking the Number is Valid or Not
    $checking_pnr = mysqli_query($mysqli, "SELECT user_stafflogin.Id AS SL_Id, user_stafflogin.reg_mobile_no As registeredMobileNo,user_stafflogin.reg_email_address, user_stafflogin.verificationCode FROM user_stafflogin JOIN setup_departmentmaster ON setup_departmentmaster.Id = user_stafflogin.departmentmaster_Id JOIN setup_sectionmaster ON setup_sectionmaster.Id = setup_departmentmaster.sectionmaster_Id WHERE user_stafflogin.username = '$user_loginId' AND setup_sectionmaster.Id = '$SM_Id' "); 

    $row_check = mysqli_num_rows($checking_pnr);
    

    if($row_check > '0') {
        $r_detail_fetch = mysqli_fetch_array($checking_pnr);


        $SL_Id = $r_detail_fetch['SL_Id'];
        $user_mobileno = $r_detail_fetch['registeredMobileNo'];
        $user_emailaddress = $r_detail_fetch['reg_email_address'];

        if(!empty($user_mobileno)){

            $pin = mt_rand(100000, 999999);
       

            $updating_studentregister = mysqli_query($mysqli,"Update user_stafflogin set verificationCode = 'V$pin' Where Id = '$SL_Id'");
            $message = "Dear Staff, Your Verfication Pin : V" . $pin;
    
    
    
            $no_of_charater = strlen($message);
            $userIdType = 'StaffLoginId';
            $time = date("Y-m-d h:m:s");
            $template_Id = '1207161192523376255';

            $format_message =  htmlspecialchars($message, ENT_QUOTES);
    
            $Inserting_UserDetails = mysqli_query($mysqli,"Insert into comm_message_log (User_Id, message_type, sender_address, no_of_characters, Decrypt_Msg, sender_name, timestamp, SenderHeader, userIdType, moduleType, template_Id) values ('$SL_Id', 'SMS' ,'$user_mobileno', '$no_of_charater' , '".$format_message."', '1', '$time' , 'SIWSAD', '$userIdType', 'UserVerfication', '$template_Id')");

    
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
                $Inserting_UserDetails = mysqli_query($mysqli,"Insert into comm_message_log (User_Id, message_type, sender_address, no_of_characters, Decrypt_Msg, sender_name, timestamp, SenderHeader, userIdType, moduleType, email_Subjects) values ('$SL_Id', 'Email' ,'$user_emailaddress', '$no_of_charater' , '".$format_message."', '1', '$time' , 'SIWSAD', '$userIdType', 'UserVerfication', 'Verfication Code')");
    
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

    echo "SELECT password FROM user_stafflogin WHERE Id = " . $_SESSION['schoolzone']['ActiveStaffLogin_Id'] . " LIMIT 1 ";
    $res = mysqli_fetch_assoc( $result );
    $old_password = $res['password'];
  
    if ( password_verify( $_POST['passworday'], $old_password ) )
    {
      $_SESSION['AY_ID'] = $_POST['modal_ay_change_pass'];
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