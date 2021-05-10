<?php 


//Function For Decrypting Message
function CandidateMessageDecrypt($CR_Id, $messageText, $mysqli){

    if(isset($CR_Id) AND isset($messageText)){ 
      
            $patterns = array();
            $patterns[0] = '/#CANDIDATEBATCH/i';
            $patterns[1] = '/#CANDIDATEMOBILE/i';
            $patterns[2] = '/#CANDIDATEEMAIL/i';
            $patterns[3] = '/#CANDIDATEPASSWORD/i';
 
        
            $replacements = array();
            $replacements[0] = '#CANDIDATEBATCH';
            $replacements[1] = '#CANDIDATEMOBILE';
            $replacements[2] = '#CANDIDATEEMAIL';
            $replacements[3] = '#CANDIDATEPASSWORD';
      



            $F_StudentData_q = mysqli_query($mysqli,"SELECT user_candidateregister.batchmaster_Id, user_candidateregister.OTP, user_candidateregister.ContactNo, user_candidateregister.EmailAddress, setup_batchmaster.batch_name FROM `user_candidateregister` JOIN setup_batchmaster ON setup_batchmaster.Id = user_candidateregister.batchmaster_Id WHERE user_candidateregister.Id = '$CR_Id' ");

            $rows_F_StudentData = mysqli_num_rows($F_StudentData_q);
            if($rows_F_StudentData > '0'){
                $r_Fetching_StudentData = mysqli_fetch_array($F_StudentData_q);

                //Replaceing Value in Tags-----------------------------------------------------------------------------------
                            
                if(is_null($r_Fetching_StudentData['batch_name']) or $r_Fetching_StudentData['batch_name'] == ''){
                    $replacements[0] = '-';
                }else{
                    $replacements[0] = $r_Fetching_StudentData['batch_name'];
                }

                if(is_null($r_Fetching_StudentData['ContactNo']) or $r_Fetching_StudentData['ContactNo'] == ''){
                    $replacements[1] = '-';
                }else{
                    $replacements[1] = $r_Fetching_StudentData['ContactNo'];
                }


                if(is_null($r_Fetching_StudentData['EmailAddress']) or $r_Fetching_StudentData['EmailAddress'] == ''){
                    $replacements[2] = '-';
                }else{
                    $replacements[2] = $r_Fetching_StudentData['EmailAddress'];
                }


                if(is_null($r_Fetching_StudentData['OTP']) or $r_Fetching_StudentData['OTP'] == ''){
                    $replacements[3] = '-';
                }else{
                    $replacements[3] = $r_Fetching_StudentData['OTP'];
                }

                //------------------------------------------------------------------------------------------------------------

                    
                $replace_special_text = preg_replace( $patterns, $replacements, $messageText);
                $sms = $replace_special_text;

     
                
                $Student_Res['Decrypt_Message'] = $sms;
                
                return $Student_Res;

            }// close if

    }// close isset

}// close functions



//Function For Decrypting Message
function StaffMessageDecrypt($SL_Id, $messageText, $mysqli){

    if(isset($SL_Id) AND isset($messageText)){ 
      
            $patterns = array();
            $patterns[0] = '/#VerficationCode/i';
    
        
            $replacements = array();
            $replacements[0] = '#VerficationCode';
    
            $pin = mt_rand(100000, 999999);



            $F_StudentData_q = mysqli_query($mysqli,"SELECT user_stafflogin.Id AS SL_Id, user_stafflogin.reg_mobile_no As registeredMobileNo,user_stafflogin.reg_email_address, user_stafflogin.verificationCode, user_stafflogin.staff_status FROM user_stafflogin JOIN setup_departmentmaster ON setup_departmentmaster.Id = user_stafflogin.departmentmaster_Id JOIN setup_sectionmaster ON setup_sectionmaster.Id = setup_departmentmaster.sectionmaster_Id WHERE user_stafflogin.Id = '$SL_Id' ");

            $rows_F_StudentData = mysqli_num_rows($F_StudentData_q);
            if($rows_F_StudentData > '0'){
                $r_Fetching_StudentData = mysqli_fetch_array($F_StudentData_q);

                //Replaceing Value in Tags-----------------------------------------------------------------------------------

                    $replacements[0] = $pin;

                //------------------------------------------------------------------------------------------------------------

                    
                $replace_special_text = preg_replace( $patterns, $replacements, $messageText);
                $sms = $replace_special_text;

     
                
                $Student_Res['Decrypt_Message'] = $sms;
                $Student_Res['VerficationCode'] = $pin;
                
                return $Student_Res;

            }// close if

    }// close isset

}// close functions




//Function For Decrypting Message
function ExistingStudentMessageDecrypt($SR_Id, $messageText, $mysqli){

    if(isset($SR_Id) AND isset($messageText)){
            $pin = mt_rand(100000, 999999);
 
      
            $patterns = array();
            $patterns[0] = '/#CANDIDATEBATCH/i';
            $patterns[1] = '/#CANDIDATEMOBILE/i';
            $patterns[2] = '/#CANDIDATEEMAIL/i';
            $patterns[3] = '/#CANDIDATEPASSWORD/i';
        
            $replacements = array();
            $replacements[0] = '#CANDIDATEBATCH';
            $replacements[1] = '#CANDIDATEMOBILE';
            $replacements[2] = '#CANDIDATEEMAIL';
            $replacements[3] = '#CANDIDATEPASSWORD';

            $F_StudentData_q = mysqli_query($mysqli,"SELECT user_studentregister.mobile_no As ContactNo, user_studentregister.email_address As EmailAddress,user_studentregister.student_Id FROM `user_studentregister` WHERE user_studentregister.Id = '$SR_Id'  ");

            $rows_F_StudentData = mysqli_num_rows($F_StudentData_q);
            if($rows_F_StudentData > '0'){
                $r_Fetching_StudentData = mysqli_fetch_array($F_StudentData_q);

                //Replaceing Value in Tags-----------------------------------------------------------------------------------
                if(is_null($r_Fetching_StudentData['student_Id']) or $r_Fetching_StudentData['student_Id'] == ''){
                    $replacements[0] = '-';
                }else{
                    $replacements[0] = $r_Fetching_StudentData['student_Id'];
                }   
              
                if(is_null($r_Fetching_StudentData['ContactNo']) or $r_Fetching_StudentData['ContactNo'] == ''){
                    $replacements[1] = '-';
                }else{
                    $replacements[1] = $r_Fetching_StudentData['ContactNo'];
                }


                if(is_null($r_Fetching_StudentData['EmailAddress']) or $r_Fetching_StudentData['EmailAddress'] == ''){
                    $replacements[2] = '-';
                }else{
                    $replacements[2] = $r_Fetching_StudentData['EmailAddress'];
                }


                
                $replacements[3] = $pin;
                
              

                //------------------------------------------------------------------------------------------------------------

                    
                $replace_special_text = preg_replace( $patterns, $replacements, $messageText);
                $sms = $replace_special_text;

     
                
                $Student_Res['Decrypt_Message'] = $sms;
                $Student_Res['pin'] = $pin;
                return $Student_Res;

            }// close if

    }// close isset

}// close functions

?>