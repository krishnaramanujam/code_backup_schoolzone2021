<?php
//Session File
include_once '../SessionInfo.php';
//Database File
include_once '../../config/database.php';

extract($_POST);

$maildetails_q = mysqli_query($mysqli, "SELECT setup_sectionmaildetails.* FROM  setup_sectionmaildetails WHERE setup_sectionmaildetails.sectionmaster_Id = '$SectionMaster_Id' ");
$row_maildetails = mysqli_num_rows($maildetails_q);

if($row_maildetails == '0'){ ?>

<input type="hidden" name="return_batch_sel" id="return_batch_sel" class="form-control" value="<?php echo $return_batch_sel; ?>">
<input type="hidden" name="return_batch_name" id="return_batch_name" class="form-control" value="<?php echo $return_batch_name; ?>">
<input type="hidden" name="return_list_sel" id="return_list_sel" class="form-control" value="<?php echo $return_list_sel; ?>">
<input type="hidden" name="return_list_name" id="return_list_name" class="form-control" value="<?php echo $return_list_name; ?>">
<input type="hidden" name="return_contact_sel" id="return_contact_sel" class="form-control" value="<?php echo $return_contact_sel; ?>">
<input type="hidden" name="return_group_sel" id="return_group_sel" class="form-control" value="<?php echo $return_group_sel; ?>">

<div class="container">

    <div class="row">
        <div class="col-md-6"><h3><i class="fa fa-envelope text-primary" aria-hidden="true"></i>  SEND EMAIL</h3></div>
        <div class="col-md-5">
            <h2 class="return_btn pull-right"><i class="fa fa-arrow-circle-left"></i></h2>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
             <div class="alert alert-warning" role="alert">Contact Admin, Your Email Details Is not linked.</div>
        </div>
    </div>

</div>


<?php

}elseif(isset($_POST['User_Id'])){
   

    $formating_User_Id = implode(",",$User_Id);


?>

<input type="hidden" name="return_batch_sel" id="return_batch_sel" class="form-control" value="<?php echo $return_batch_sel; ?>">
<input type="hidden" name="return_batch_name" id="return_batch_name" class="form-control" value="<?php echo $return_batch_name; ?>">
<input type="hidden" name="return_list_sel" id="return_list_sel" class="form-control" value="<?php echo $return_list_sel; ?>">
<input type="hidden" name="return_list_name" id="return_list_name" class="form-control" value="<?php echo $return_list_name; ?>">
<input type="hidden" name="return_contact_sel" id="return_contact_sel" class="form-control" value="<?php echo $return_contact_sel; ?>">
<input type="hidden" name="return_group_sel" id="return_group_sel" class="form-control" value="<?php echo $return_group_sel; ?>">

<div class="container col-md-10">

    <div class="row">
        <div class="col-md-6"><h3><i class="fa fa-envelope text-primary" aria-hidden="true"></i>  SEND EMAILS</h3></div>
        <div class="col-md-5">
            <h2 class="return_btn pull-right"><i class="fa fa-arrow-circle-left"></i></h2>
        </div>
    </div>

    <form id="SMSDetailsForm" enctype="multipart/form-data" method="POST" action="./communication/Communication_api.php?InsertingEmailLogEntries=u" onsubmit="return false">

        <?php foreach($User_Id as $index => $value) { ?>
            <input type="hidden" name="User_Id[]" class="form-control" value="<?php echo $User_Id[$index]; ?>">
        <?php } ?>
        <input type="hidden" name="contact_Person" id="contact_Person" class="form-control" value="<?php echo $return_contact_sel; ?>">
        <input type="hidden" name="SenderName" id="SenderName" class="form-control" value="<?php echo $ActiveStaffLogin_Id; ?>">
        <input type="hidden" name="UserType" id="UserType" class="form-control" value="<?php echo 'Student'; ?>">
        <input type="hidden" name="Sender_Header" id="Sender_Header" class="form-control" value="<?php echo 'SIWSAD'; ?>">
        

        <div class="row">
            <div class="col-md-12">

                <div class="form-group">
                    
                    <label for="exampleFormControlSelect1">Send Message To (SBM_Ids)</label><br>
                    <textarea rows="4" class="form-control detailsinput" readonly><?php echo $formating_User_Id; ?></textarea>
                    
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">

                <div class="form-group">
                    
                    <label for="exampleFormControlSelect1">Select Email Header*</label>
                    <select name="Sender_Header" id="Sender_Header" class="form-control"  required>
                    <option value="">---- Select Email Header ----</option>
                    <?php 
                        
                        if($ActiveStaffLogin_Id != 2){
                            $q = "SELECT setup_sectionmaildetails.* FROM `setup_sectionmaildetails` JOIN comm_emaildetails_access ON comm_emaildetails_access.sectionmaildetails_Id = setup_sectionmaildetails.Id WHERE setup_sectionmaildetails.sectionmaster_Id = '$SectionMaster_Id' AND comm_emaildetails_access.userId = '$ActiveStaffLogin_Id' ";
                        }else{
                            $q = "SELECT setup_sectionmaildetails.* FROM `setup_sectionmaildetails`  WHERE setup_sectionmaildetails.sectionmaster_Id = '$SectionMaster_Id'";
                        }
                    
                        $batch_fetch = mysqli_query($mysqli,$q);

                        while($r_batch = mysqli_fetch_array($batch_fetch)){ ?>
                        <option value="<?php echo $r_batch['Id']; ?>"  
                        ><?php echo $r_batch['setFromAddress']; ?></option>
                    <?php }   ?>
                    </select>
                </div>
            </div>
        </div>


        <div class="row">
            <div class="col-md-12">

                <div class="form-group">
                    
                    <label for="exampleFormControlSelect1">Subject</label><br>
                    <input type="text" name="emailSubjects" id="emailSubjects" class="form-control" value="">
                    
                </div>
            </div>
        </div>


        <div class="row">
            <div class="col-md-12">

                <div class="form-group">
                    
                    <label for="exampleFormControlSelect1">Attachment</label><br>
                    <input type='file'  id="attachmentPath" class="btn btn-block save_btn_effect fontbtn" name="attachmentPath" style="background-color:#d9edf7;color:#31708f;border-radius: 25px;" accept="image/jpeg, image/png, application/pdf"/>  
                   
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">

                <div class="form-group">

                
                    
                    <label for="exampleFormControlSelect1">Message*</label><br>
                    <small id="emailHelp" class="form-text text-muted">Use #NAME #BATCH #ROLLNO #DIVISION #EMAIL as place holder for each students details.</small><br>
                    <!-- <small id="emailHelp" class="form-text text-danger"><b>Avoid copy paste. Type the special characater like @$^&\_|' "</b></small><br> -->
                    <textarea rows="8" class="form-control detailsinput" id="messageText" name="message" maxlength="230"></textarea><br>
                    <small id="emailHelp" class="form-text text-muted char_cal"></small>

                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">

                <div class="form-group">
                      <input type="submit" id="send_sms" name="send_sms" class="btn btn-primary btn-block" value="Send Email">
                </div>
            </div>
        </div>

    </form>

</div> <!--Container Close-->

<?php }else{ ?>


<input type="hidden" name="return_batch_sel" id="return_batch_sel" class="form-control" value="<?php echo $return_batch_sel; ?>">
<input type="hidden" name="return_batch_name" id="return_batch_name" class="form-control" value="<?php echo $return_batch_name; ?>">
<input type="hidden" name="return_list_sel" id="return_list_sel" class="form-control" value="<?php echo $return_list_sel; ?>">
<input type="hidden" name="return_list_name" id="return_list_name" class="form-control" value="<?php echo $return_list_name; ?>">
<input type="hidden" name="return_contact_sel" id="return_contact_sel" class="form-control" value="<?php echo $return_contact_sel; ?>">
<input type="hidden" name="return_group_sel" id="return_group_sel" class="form-control" value="<?php echo $return_group_sel; ?>">

<div class="container">

    <div class="row">
        <div class="col-md-6"><h3><i class="fa fa-envelope text-primary" aria-hidden="true"></i>  SEND EMAIL</h3></div>
        <div class="col-md-5">
            <h2 class="return_btn pull-right"><i class="fa fa-arrow-circle-left"></i></h2>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
             <div class="alert alert-warning" role="alert">Please GO Back And Select Student From List For Further Processing.</div>
        </div>
    </div>

</div>
<?php } ?>


<script>



$('.return_btn').click(function(event){
    event.preventDefault();

    var return_batch_sel = $('#return_batch_sel').val();
    var return_batch_name = $('#return_batch_name').val();
    var return_list_sel = $('#return_list_sel').val();
    var return_list_name = $('#return_list_name').val();
    var return_contact_sel = $('#return_contact_sel').val();
    var return_group_sel = $('#return_group_sel').val();

    $("#loader").css("display", "block");
    $("#DisplayDiv").css("display", "none");
    $.ajax({
        url:'./communication/communication_student.php?Generate_View='+'u',
        type:'GET',
        data: {batch_sel:return_batch_sel, batch_name:return_batch_name, list_sel:return_list_sel, list_name:return_list_name, contact_sel:return_contact_sel, group_sel: return_group_sel},
        success:function(response){
            $('#DisplayDiv').html(response);
            $("#loader").css("display", "none");
            $("#DisplayDiv").css("display", "block");
        },
    });
});

$(document).ready(function () {
    
    var text_max = 0;
    $('.char_cal').html(text_max + ' Characters Remaining');
    $('#messageText').keyup(function () {
      var text_length = $('#messageText').val().length;

      $('.char_cal').html(text_length + ' Characters');
    });

});


$(document).ready(function () {
    
    var text_max = 230;
    $('.char_cal').html(text_max + ' Characters Remaining');
    $('#messageText').keyup(function () {
      var text_length = $('#messageText').val().length;
        var tot = parseInt(text_max) - parseInt(text_length);  
      $('.char_cal').html(tot + ' Characters Remaining');
    });

});





$('#SMSDetailsForm').submit(function(event){

    
    var return_batch_sel = $('#return_batch_sel').val();
    var return_batch_name = $('#return_batch_name').val();
    var return_list_sel = $('#return_list_sel').val();
    var return_list_name = $('#return_list_name').val();
    var return_contact_sel = $('#return_contact_sel').val();

    var FORMDATASMS = new FormData(this);
    $("#loader").css("display", "block");
    $("#DisplayDiv").css("display", "none");


    $.ajax({
        url:'./communication/Communication_api.php?InsertingEmailLogEntries='+'u',
        data: FORMDATASMS,
        type: 'POST',
        contentType:false,   //expect return data as html from server
        async : false,
        enctype: 'multipart/form-data',
        processData : false,
        cache : false,
        dataType: "json",
        success:function(res_data){
            
            if(res_data['status'] == 'success') {
                var CEL_Id = res_data['Comm_Email_Logs_Id'];

                if(CEL_Id === 'NA' || CEL_Id === ''){
                    $("#loader").css("display", "none");
                    $("#DisplayDiv").css("display", "block");

                    alert('Try Again Not Able to Create Entries')
                }else if(CEL_Id != '' || CEL_Id != 'NA'){
                    
                    $.ajax({
                        type:'POST',
                        url:'./communication/Communication_api.php?SendingStudentEmails='+'u',
                        dataType: "json",
                        data: {CEL_Id: CEL_Id},
                        success:function(res_data_sms){
                            $("#loader").css("display", "none");
                            $("#DisplayDiv").css("display", "block");
                            

                            var UploadedFilePath = res_data_sms['UploadedFilePath'];
              
                            var i = 0;

                            var errorString = '';
                            var successString = '';

                            res_data_sms['EmailResponse'].forEach(function(entry) {
                                var pattn = /Success/g;
                                var PatternVerify = pattn.test(res_data_sms['EmailResponse']);
                                if(PatternVerify === true){
                                    successString = successString.concat(res_data_sms['EmailUserId'][i] + " : " + res_data_sms['EmailResponse'][i], "</br>");
                                }else{
                                    errorString = errorString.concat(res_data_sms['EmailUserId'][i] + " : " + res_data_sms['EmailResponse'][i] , "</br>");
                                }
                                i++;
                            });


                            if(successString != ''){
                
                                iziToast.success({
                                    title: 'Success',
                                    timeout: false,
                                    enableHtml: true,
                                    message: successString
                                });
                    
                            }

                            if(errorString != ''){
                        
                                iziToast.error({
                                    title: 'Error',
                                    timeout: false,
                                    enableHtml: true,
                                    message: errorString
                                });
                    
                            }



                            if(UploadedFilePath === 'NA' || UploadedFilePath === ''){

                            }else if(UploadedFilePath != '' || UploadedFilePath != 'NA'){
                                window.open(UploadedFilePath, '_blank');
                            }


                        },
                    });

                }

        
            }else {

                $("#loader").css("display", "none");
                $("#DisplayDiv").css("display", "block");

                alert('Failed')

            }// close else

        },
    });

});

</script>

<style>
.return_btn{
    box-shadow: 0px 17px 10px -10px rgba(0,0,0,0.4);
    transition: all ease-in-out 300ms;
    color: #f44336;
    cursor: pointer;
}

.return_btn:hover {
  box-shadow: 0px 37px 20px -15px rgba(0,0,0,0.2);
  transform: translate(0px, -10px);
}

.detailsinput{
    border-bottom: 2px solid #3c8dbc;
}
</style>

