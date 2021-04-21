<?php
session_start();
date_default_timezone_set("Asia/Calcutta");
ini_set('max_execution_time', 0);
set_time_limit(0);
ini_set('memory_limit','-1');
ini_set('display_errors',1);
error_reporting(E_ALL);


include('../../../config/database_student.php');

//-----------------------------------------------------------------------------------------------------------------------
if(isset($_GET['Validate_RegisterForm'])){

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
        batchmaster_Id) Values
        ('$pin',
        '0',
        '$register_mobile_no',
        '$register_email_id',
        '$register_batch_sel')");

        $CR_Id =  mysqli_insert_id($mysqli);


        $message = "Dear Staff, Your Verfication Pin : V" . $pin;
    
        $no_of_charater = strlen($message);
        $userIdType = 'CR_Id';
        $time = date("Y-m-d h:m:s");
        $template_Id = '1';
     
        $format_message =  htmlspecialchars($message, ENT_QUOTES);


        $Inserting_UserDetails = mysqli_query($mysqli,"Insert into comm_message_log (User_Id, message_type, sender_address, no_of_characters, Decrypt_Msg, sender_name, timestamp, SenderHeader, userIdType, moduleType, template_Id) values ('$SL_Id', 'SMS' ,'$register_mobile_no', '$no_of_charater' , '".$format_message."', '1', '$time' , '1', '$userIdType', 'Admission', '$template_Id')");

    
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
            $Inserting_UserDetails = mysqli_query($mysqli,"Insert into comm_message_log (User_Id, message_type, sender_address, no_of_characters, Decrypt_Msg, sender_name, timestamp, SenderHeader, userIdType, moduleType, email_Subjects) values ('$CR_Id', 'Email' ,'$register_email_id', '$no_of_charater' , '".$format_message."', '1', '$time' , 'SIWSAD', '$userIdType', 'Admission', 'Admission Login Details')");

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
