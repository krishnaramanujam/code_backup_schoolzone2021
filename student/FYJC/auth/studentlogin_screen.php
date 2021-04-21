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

<div class="col-md-2"></div>

<div class="col-md-4">

  <div class="panel panel-info img-thumbnail" style="width:100%;">
    <div class="panel-heading">
      <h3 class="panel-title"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i> First time users please register here
      <br><br>The above mobile number will be used for all further communications related to Admission<br><br>
      You will receive an sms with the password on your mobile number
      </h3>
    </div>
    <div class="panel-body">

        <div class="row">

          <div class="form-group" style="padding:15px;">


            <label for="email" style="width:100%" class="panel-title">Mobile No (to be used for login)*</label>
            <input type="text" class="form-control" id="register_mobile_no" name="register_mobile_no" placeholder="Enter Mobile No" value=""  pattern= "[0-9].{9,}"  oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" onkeypress='return event.charCode >= 48 && event.charCode <= 57' maxlength = "10">

            <label for="email" style="width:100%" class="panel-title">Email Id*</label>
            <input type="email" class="form-control" id="register_email_id" name="register_email_id" placeholder="Enter Email Id" value="">          

            <label for="email" style="width:100%" class="panel-title">Batch*</label>
            <select class="form-control" id="register_batch_sel" name="register_batch_sel">
              <option value="">---- Select Batch ----</option>
                <?php 
                    $batch_fetch = mysqli_query($mysqli,"SELECT setup_batchmaster.*, setup_academicyear.academic_year FROM `setup_batchmaster` JOIN setup_academicyear ON setup_academicyear.Id = setup_batchmaster.academicyear_Id JOIN setup_programmaster ON setup_programmaster.Id = setup_batchmaster.programmaster_Id JOIN setup_streammaster ON setup_streammaster.Id = setup_programmaster.streammaster_Id WHERE setup_batchmaster.student_registration = '1' AND setup_academicyear.isDefault_Student = '1' AND setup_streammaster.sectionmaster_Id = '$SM_Id' ORDER BY batch_name");
                    while($r_batch = mysqli_fetch_array($batch_fetch)){ ?>
                    <option value="<?php echo $r_batch['Id']; ?>" ><?php echo $r_batch['batch_name']; ?></option>
                <?php }   ?>
            </select> 


              

          </div>

        </div><!--DD Row2 Close-->

    </div><!--PBody Close-->

    <div class="panel-footer" style="text-align: center;">
          <button type="button" class="btn save_btn_effect register_details btn-primary" value="Register" style="padding-left: 40px;    padding-right: 40px;"><i class="fa fa-sign-in" aria-hidden="true"></i> Register</button>
    </div><!--Panel Footer Close-->

  </div>
    
</div><!--Close COl-->


<div class="col-md-4">

  <div class="panel panel-info img-thumbnail">
    <div class="panel-heading">
      <h3 class="panel-title"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i> Registered Users login with Mobile number and password.</h3>
    </div>
    <div class="panel-body">
     
    
    <div class="row">

      <div class="form-group" style="padding:15px;">

        <input type="text" class="form-control" id="login_username" name="login_username" placeholder="Enter Register Mobile No" value="">

        <input type="password" class="form-control" id="login_password" name="login_password" placeholder="Enter Password" value="">


        <button type="button" class="btn btn-link pull-right" id="forget_link" name="forget_link">Forgot Password ?</button>
            
      </div>

    </div><!--DD Row2 Close-->


    </div><!--PBody Close-->

    
    <div class="panel-footer" style="text-align: center;">
          <button type="button" class="btn save_btn_effect login_details btn-primary" value="Login" style="padding-left: 40px;    padding-right: 40px;"><i class="fa fa-sign-in" aria-hidden="true"></i> Login</button>
          <br><br>
          <a href="https://youtu.be/2ScOJQPcSOY" target="_blank"><button type="button" class="btn save_btn_effect btn-primary" value="Form Filling Page" ><i class="fa fa-video-camera" aria-hidden="true"></i> Video Guide for filling the form</button></a>
    </div><!--Panel Footer Close-->

  </div>
  
</div><!--Close Col-->

</div><!--Close Container-->
<!----------------------------------------------------------------------------------------------------------------------------------->


    



  <!----------------------------------------------------------------------------------------------------------------------------------->


  <?php }else{ ?>
    
    <div class="alert" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
      
        <div class="modal-header">
          <h4 class="modal-title" style="text-align:center;">Student Login</h4>
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
          <h4 class="modal-title" style="text-align:center;">Student Login</h4>
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

      $('#u_dateofbirth').datepicker({
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



$('.register_details').click(function(e){
    e.preventDefault();
    var SM_Id = $('#SM_Id').val();
    var register_email_id = $('#register_email_id').val();
    var register_mobile_no = $('#register_mobile_no').val();

    var register_batch_sel = $('#register_batch_sel').val();



    if(register_mobile_no == ''  || register_email_id == '' || register_batch_sel == ''){
        iziToast.error({
            title: 'Invalid Fields',
            message: 'All Field is Required',
        });
        return false;
    }


    if(register_mobile_no.length != '10'){
        $('#register_mobile_no').val('');
        iziToast.error({
            title: 'Invalid Fields',
            message: 'Mobile No Must be 10 Digits',
        });
        return false;
    }

    $("#loader").css("display", "block");
    $("#DisplayDiv").css("display", "none");


    
    $.ajax({
        url: './onlinelogin_api.php?Validate_RegisterForm='+'u',
        type: 'POST',
        data: {register_email_id: register_email_id,register_mobile_no:register_mobile_no, register_batch_sel:register_batch_sel, SM_Id:SM_Id},
        dataType: "json",
        success:function(srh_gr_response){

          //Checking Status 
          if(srh_gr_response['status'] == 'success') {
            // -------------Mobile----------------------------------------------------------------------------
              
            var CML_Id = srh_gr_response['Comm_Message_Logs_Id'];

            if(CML_Id === 'NA' || CML_Id === ''){
                $("#loader").css("display", "none");
                $("#DisplayDiv").css("display", "block");

                alert('Try Again Not Able to Create Entries')
            }else if(CML_Id != '' || CML_Id != 'NA'){
                
                $.ajax({
                    type:'POST',
                    url:'https://www.dvsl.in/schoolzone2021/staff/communication/Communication_api.php?SendingStudentSMS='+'u',
                    dataType: "json",
                    data: {CML_Id: CML_Id},
                    success:function(res_data_sms){

                          $.ajax({
                          type:'GET',
                          url: './online_mobile_verify.php',
                          success:function(response){
                              // console.log(response);
                                $('#DisplayDiv').html(response);
                                $("#loader").css("display", "none");
                                $("#DisplayDiv").css("display", "block");

                                            
                                alert('You have registered successfully. An SMS has been sent to your Mobile no '+ register_mobile_no +' with your password. Please use the same to login');

                            },
                        });



                    },
                });

            }

            // -------------Mobile----------------------------------------------------------------------------

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

           


          }else if(srh_gr_response['status'] == 'NumberExist'){
            $("#loader").css("display", "none");
            $("#DisplayDiv").css("display", "block");
            iziToast.error({
                title: 'Error',
                message: 'Mobile Number Already Exists for Selected Batch',
            });
            
          }else{
            $("#loader").css("display", "none");
            $("#DisplayDiv").css("display", "block");
            iziToast.error({
                title: 'Error',
                message: 'Try After Sometime',
            });
            
          }


     
       },
    });      

});

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

