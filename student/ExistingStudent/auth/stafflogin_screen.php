<?php

ini_set('max_execution_time', 0);
set_time_limit(0);
ini_set('memory_limit','-1');
ini_set('display_errors',1);
error_reporting(E_ALL);

include('../../../config/database.php');

$SM_Id = $_GET['SM_Id'];

$sectiondetail_q = mysqli_query($mysqli, "Select * from setup_sectionmaster Where Id = '$SM_Id'");
$row_sectiondetail = mysqli_num_rows($sectiondetail_q);

if($row_sectiondetail > '0'){

  $r_sectiondetail = mysqli_fetch_array($sectiondetail_q);
  $OpenLogin = $r_sectiondetail['open_login'];

  $Section_Name = $r_sectiondetail['section_name'];


?>
  <!----------------------------------------------------------------------------------------------------------------------------------->    

  <img src="../../../<?php echo $r_sectiondetail['section_logo']; ?>" height="150px" style="max-width:220px;display: block;margin: 0 auto;"/>
  <h3 class="text-center" style="background-color: #a72826; padding: 8px;border-radius: 49px;font-weight: 700;color: white;"><?php echo $Section_Name; ?></h3>
  <h3 class="text-center">Welcome to Existing Student Portal</h3>

  <?php if($OpenLogin == '1'){ ?>

  <div class="container">

    <div class="col-md-12 col-sm-12" style="display: grid;place-items: center;">

      <div class="panel panel-info img-thumbnail">
        <div class="panel-heading">
          <h3 class="panel-title"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i> Registered Users login with Student Id and password.</h3>
        </div>
        <div class="panel-body">
        
        
        <div class="row">

          <div class="form-group" style="padding:15px;">
            <input type="hidden" class="form-control" id="SM_Id" name="SM_Id"  value="<?php echo $SM_Id; ?>">

            <input type="text" class="form-control" id="login_username" name="login_username" placeholder="Enter Student Id" value="">

            <input type="password" class="form-control" id="login_password" name="login_password" placeholder="Enter Password" value="">


            <button type="button" class="btn btn-link pull-right" data-toggle="modal" data-target="#myModal">Forgot Password ?</button>
                
          </div>

        </div><!--DD Row2 Close-->


        </div><!--PBody Close-->

        
        <div class="panel-footer" style="text-align: center;">
              <button type="button" class="btn save_btn_effect login_details btn-primary" value="Login" style="padding-left: 40px;    padding-right: 40px;"><i class="fa fa-sign-in" aria-hidden="true"></i> Login</button>
        </div><!--Panel Footer Close-->

      </div>
      
    </div><!--Close Col-->

  </div><!--Close Container-->


  <!-- Modal -->
  <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="myModalLabel">Set New Password</h4>
        </div>
        <div class="modal-body">
              <input type="hidden" value="" id="SR_Id" name="SR_Id">
              <label for="email">Enter Student Id*</label>
                  <input type="text" class="form-control" id="user_loginId" name="user_loginId" placeholder="Enter Student Id" >
              <label for="email">Enter Date Of Birth*</label>
                <input type="text" class="form-control" id="user_dob" name="user_dob" placeholder="Enter DOB" >
              <label for="email">Enter your OTP*</label>
                      <input type="text" class="form-control" id="user_otp" name="user_otp" placeholder="Enter your OTP" value="" disabled>

        </div>
        <div class="modal-footer">
              <button type="button" class="btn save_btn_effect sendotp_mobile_details btn-danger" value="Login" style="padding-left: 40px;    padding-right: 40px;"> SEND OTP</button>
                              
              <button type="button" class="btn save_btn_effect verify_otp_details btn-success" value="Login" style="padding-left: 40px;    padding-right: 40px;" disabled> CONFIRM OTP & SUBMIT</button>
            
          <!-- <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button type="button" class="btn btn-primary">Save changes</button> -->
        </div>
      </div>
    </div>
  </div>

  <!----------------------------------------------------------------------------------------------------------------------------------->


  <?php }else{ ?>
    
    <div class="alert" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
      
        <div class="modal-header">
          <h4 class="modal-title" style="text-align:center;">Staff Login</h4>
        </div>
        <div class="modal-body">
          
        <h3 style="text-align:center;"><?php echo $r_sectiondetail['maintenance_message']; ?></h3>          

          </div>
        </div><!-- /.modal-content -->
      </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->


  <?php } ?>

<?php }else{ ?>


  <div class="alert" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
      
        <div class="modal-header">
          <h4 class="modal-title" style="text-align:center;">Staff Login</h4>
        </div>
        <div class="modal-body">
          
        <h3 style="text-align:center;">Please Visit URL Provided By Admin. <br> Page Not Found.</h3>          

          </div>
        </div><!-- /.modal-content -->
      </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

<?php } ?>

<script>
     $('#user_dob').datepicker({
          format: 'dd/mm/yyyy',
          autoclose: true
      });

$('.login_details').click(function(e){
    e.preventDefault();
    var SM_Id = $('#SM_Id').val();
    var login_username = $('#login_username').val();
    var login_password = $('#login_password').val();


    if(login_username == '' || login_password == ''){
        iziToast.error({
            title: 'Invalid Fields',
            message: 'All Field is Required',
        });
        return false;
    }


    $("#loader").css("display", "block");
    $("#DisplayDiv").css("display", "none");


    
    $.ajax({
        url: './onlinelogin_api.php?Validate_LoginForm='+'u',
        type: 'POST',
        data: {login_username:login_username, login_password: login_password, SM_Id:SM_Id},
        dataType: "json",
        success:function(srh_gr_response){

          //Checking Status 
          if(srh_gr_response['status'] == 'success') {

            iziToast.success({
                title: 'Success',
                message: 'Successful Login',
            });

            $("#loader").css("display", "none");
            $("#DisplayDiv").css("display", "block");
            window.open('../index.php','_self');

          }else{

            $("#loader").css("display", "none");
            $("#DisplayDiv").css("display", "block");
            iziToast.error({
                title: 'Error',
                message: 'Invalid Username Or Password',
            });
            

          }//close else


     
       },
    });      

});



///////////////////////////////////FORGET PASSWORD///////////////////////////////////////////////////////////////


$('.sendotp_mobile_details').click(function(e){
    e.preventDefault();
    var user_loginId = $('#user_loginId').val();
    var user_dob = $('#user_dob').val();
    var SM_Id = $('#SM_Id').val();

    if(user_loginId == ''){
        iziToast.error({
            title: 'Invalid Fields',
            message: 'Username is Required',
        });
        return false;
    }

    $("#loader").css("display", "block");
    $("#DisplayDiv").css("display", "none");


    $.ajax({
        url: './onlinelogin_api.php?Request_ForChangeMobileNo='+'u',
        type: 'POST',
        data: {user_loginId:user_loginId, SM_Id:SM_Id, user_dob:user_dob},
        dataType: "json",
        success:function(srh_gr_response){

          //Checking Status 
          if(srh_gr_response['status'] == 'success') {
            var SR_Id = srh_gr_response['SR_Id'];
            // --------------SMS--------------------------------------------------------------------------------
            var CML_Id = srh_gr_response['Comm_Message_Logs_Id'];
            var smsStatus = '';
            if(CML_Id === 'NA' || CML_Id === ''){
                $("#loader").css("display", "none");
                $("#DisplayDiv").css("display", "block");

                alert('Try Again After Sometimes');
            }else if(CML_Id != '' || CML_Id != 'NA'){
                
                $.ajax({
                    type:'POST',
                    url:'https://www.dvsl.in/schoolzone2021/staff/communication/Communication_api.php?SendingStudentSMS='+'u',
                    dataType: "json",
                    data: {CML_Id: CML_Id},
                    success:function(res_data_sms){

                      $('#SR_Id').val(SR_Id);

                      $('#user_otp').prop("disabled", false);
                      $('.verify_otp_details').prop("disabled", false);

                      $('#user_loginId').prop("disabled", true);
                      $('.sendotp_mobile_details').prop("disabled", true);

                      $('#user_dob').prop("disabled", true);

                      $("#loader").css("display", "none");
                      $("#DisplayDiv").css("display", "block");

                      alert('An SMS has been sent to your Mobile no and Email send to your email Id. Please verify otp and Confirm .Do Not Refresh Your Page');

      
                    },
                });

            }
            // ----------------------------------------------------------------------------------------------
            // -------------EMAIL----------------------------------------------------------------------------
            var emailstatus = '';
            var CEL_Id = srh_gr_response['Comm_Email_Logs_Id'];

            if(CEL_Id === 'NA' || CEL_Id === ''){
                $("#loader").css("display", "none");
                $("#DisplayDiv").css("display", "block");

                alert('Try Again Not Able to Create Entries')
            }else if(CEL_Id != '' || CEL_Id != 'NA'){
                
                $.ajax({
                    type:'POST',
                    url:'https://www.dvsl.in/schoolzone2021/staff/communication/Communication_api.php?SendingStudentEmails='+'u',
                    dataType: "json",
                    data: {CEL_Id: CEL_Id},
                    success:function(res_data_sms){

                    },
                });

            }
            // ----------------------------------------------------------------------------------------------


          }else if(srh_gr_response['status'] == 'MobileNoFailed'){
            $("#loader").css("display", "none");
            $("#DisplayDiv").css("display", "block");
            iziToast.error({
                title: 'Error',
                message: 'Please Contact to Admin, Your Mobile Number is not present is Our Database',
            });
          }else{

            $("#loader").css("display", "none");
            $("#DisplayDiv").css("display", "block");
            iziToast.error({
                title: 'Error',
                message: 'Invalid Details',
            });
            

          }//close else


     
       },
    });

          

}); // close send_otp_details



$('.verify_otp_details').click(function(e){
    e.preventDefault();
    var user_otp = $('#user_otp').val();
    var SR_Id = $('#SR_Id').val();

    if(user_otp == ''){
        iziToast.error({
            title: 'Invalid Fields',
            message: 'OTP is Required',
        });
        return false;
    }

    $("#loader").css("display", "block");
    $("#DisplayDiv").css("display", "none");


    $.ajax({
        url: './onlinelogin_api.php?Request_ForOTPVerfication='+'u',
        type: 'POST',
        data: {SR_Id:SR_Id,user_otp:user_otp},
        dataType: "json",
        success:function(srh_gr_response){

          //Checking Status 
          if(srh_gr_response['status'] == 'success') {
            

            // --------------SMS--------------------------------------------------------------------------------
            var CML_Id = srh_gr_response['Comm_Message_Logs_Id'];
            var smsStatus = '';
            if(CML_Id === 'NA' || CML_Id === ''){
                $("#loader").css("display", "none");
                $("#DisplayDiv").css("display", "block");

                alert('Try Again After Sometimes');
            }else if(CML_Id != '' || CML_Id != 'NA'){
                
                $.ajax({
                    type:'POST',
                    url:'https://www.dvsl.in/schoolzone2021/staff/communication/Communication_api.php?SendingStudentSMS='+'u',
                    dataType: "json",
                    data: {CML_Id: CML_Id},
                    success:function(res_data_sms){

                      $("#loader").css("display", "none");
                      $("#DisplayDiv").css("display", "block");


                      $('#user_otp').prop("disabled", true);
                      $('.verify_otp_details').prop("disabled", true);

                      alert('New Password is sent to your Mobile and Email Id')
                      window.open('https://dvsl.in/schoolzone2021/student/ExistingStudent/auth/login.php?sectionmaster_Id='+ SM_Id, '_self');

      
                    },
                });

            }
            // ----------------------------------------------------------------------------------------------
            // -------------EMAIL----------------------------------------------------------------------------
            var emailstatus = '';
            var CEL_Id = srh_gr_response['Comm_Email_Logs_Id'];

            if(CEL_Id === 'NA' || CEL_Id === ''){
                $("#loader").css("display", "none");
                $("#DisplayDiv").css("display", "block");

                alert('Try Again Not Able to Create Entries')
            }else if(CEL_Id != '' || CEL_Id != 'NA'){
                
                $.ajax({
                    type:'POST',
                    url:'https://www.dvsl.in/schoolzone2021/staff/communication/Communication_api.php?SendingStudentEmails='+'u',
                    dataType: "json",
                    data: {CEL_Id: CEL_Id},
                    success:function(res_data_sms){

                    },
                });

            }
            // ----------------------------------------------------------------------------------------------




          }else{

            $("#loader").css("display", "none");
            $("#DisplayDiv").css("display", "block");
            iziToast.error({
                title: 'Error',
                message: 'Invalid Details',
            });
            

          }//close else


     
       },
    });

          

}); // close send_otp_details



</script>

<style>

.return-btn {
  color: #fff;
  font-size: x-large;
  cursor: pointer;
}

.panel-title{
  font-size: 14px;
  font-weight: 700;
  line-height: 18px;
  font-style: italic;
}

input{
  margin-bottom:8px;
}

#DisplayDiv{
  background-color:white;
}
</style>

