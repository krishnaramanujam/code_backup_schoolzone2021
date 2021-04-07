<?php
//Session File
include_once '../SessionInfo.php';
//Database File
include_once '../../config/database.php';

extract($_POST);

$credit_balance_q = mysqli_query($mysqli, "SELECT comm_sms_credit.* FROM `comm_sms_credit` WHERE comm_sms_credit.batchmaster_Id = '$return_batch_sel' AND active_status = '1'");
$row_credit_balance = mysqli_num_rows($credit_balance_q);


if($row_credit_balance == '0'){

?>

<input type="hidden" name="return_batch_sel" id="return_batch_sel" class="form-control" value="<?php echo $return_batch_sel; ?>">
<input type="hidden" name="return_batch_name" id="return_batch_name" class="form-control" value="<?php echo $return_batch_name; ?>">
<input type="hidden" name="return_list_sel" id="return_list_sel" class="form-control" value="<?php echo $return_list_sel; ?>">
<input type="hidden" name="return_list_name" id="return_list_name" class="form-control" value="<?php echo $return_list_name; ?>">
<input type="hidden" name="return_contact_sel" id="return_contact_sel" class="form-control" value="<?php echo $return_contact_sel; ?>">
<input type="hidden" name="return_group_sel" id="return_group_sel" class="form-control" value="<?php echo $return_group_sel; ?>">

<div class="container">

    <div class="row">
        <div class="col-md-6"><h3><i class="fa fa-envelope text-primary" aria-hidden="true"></i>  SEND MESSAGE</h3></div>
        <div class="col-md-5">
            <h2 class="return_btn pull-right"><i class="fa fa-arrow-circle-left"></i></h2>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
             <div class="alert alert-warning" role="alert">Please Contact Admin, SMS Credit is Not Assign.</div>
        </div>
    </div>

</div>


<?php
    
}elseif(isset($_POST['User_Id'])){
    
    $formating_User_Id = implode(",",$User_Id);

    $select_user_count = count($User_Id);


    $r_credit_balance = mysqli_fetch_array($credit_balance_q);

?>

<input type="hidden" name="return_batch_sel" id="return_batch_sel" class="form-control" value="<?php echo $return_batch_sel; ?>">
<input type="hidden" name="return_batch_name" id="return_batch_name" class="form-control" value="<?php echo $return_batch_name; ?>">
<input type="hidden" name="return_list_sel" id="return_list_sel" class="form-control" value="<?php echo $return_list_sel; ?>">
<input type="hidden" name="return_list_name" id="return_list_name" class="form-control" value="<?php echo $return_list_name; ?>">
<input type="hidden" name="return_contact_sel" id="return_contact_sel" class="form-control" value="<?php echo $return_contact_sel; ?>">
<input type="hidden" name="return_group_sel" id="return_group_sel" class="form-control" value="<?php echo $return_group_sel; ?>">


<input type="hidden" name="select_user_count" id="select_user_count" class="form-control" value="<?php echo $select_user_count; ?>">


<div class="container col-md-10">

    <div class="row">
        <div class="col-md-6"><h3><i class="fa fa-envelope text-primary" aria-hidden="true"></i>  SEND SMS</h3></div>
        <div class="col-md-5">
            <h2 class="return_btn pull-right"><i class="fa fa-arrow-circle-left"></i></h2>
        </div>
    </div>

    <form id="SMSDetailsForm">

        <?php foreach($User_Id as $index => $value) { ?>
            <input type="hidden" name="User_Id[]" class="form-control" value="<?php echo $User_Id[$index]; ?>">
        <?php } ?>
        <input type="hidden" name="contact_Person" id="contact_Person" class="form-control" value="<?php echo $return_contact_sel; ?>">
        <input type="hidden" name="SenderName" id="SenderName" class="form-control" value="<?php echo $ActiveStaffLogin_Id; ?>">
        <input type="hidden" name="UserType" id="UserType" class="form-control" value="<?php echo 'Student'; ?>">
        

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
                    
                    <label for="exampleFormControlSelect1">Select SMS Header*</label>
                    <select name="Sender_Header" id="Sender_Header" class="form-control"  required>
                    <option value="">---- Select SMS Header ----</option>
                    <?php 
                        
                        if($ActiveStaffLogin_Id != 2){
                            $q = "SELECT comm_sms_header_ids.* FROM `comm_sms_header_ids` JOIN comm_smsheaderids_access ON comm_smsheaderids_access.smsheader_Id = comm_sms_header_ids.Id WHERE comm_sms_header_ids.sectionmaster_Id = '$SectionMaster_Id' AND comm_smsheaderids_access.userId = '$ActiveStaffLogin_Id' ";
                        }else{
                            $q = "SELECT comm_sms_header_ids.* FROM `comm_sms_header_ids`  WHERE comm_sms_header_ids.sectionmaster_Id = '$SectionMaster_Id'";
                        }
                    
                        $batch_fetch = mysqli_query($mysqli,$q);

                        while($r_batch = mysqli_fetch_array($batch_fetch)){ ?>
                        <option value="<?php echo $r_batch['Id']; ?>"  
                        ><?php echo $r_batch['header_name']; ?></option>
                    <?php }   ?>
                    </select>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">

                <div class="form-group">
                    
                    <label for="exampleFormControlSelect1">Select SMS Template*</label>
                    <select name="Sender_Template_Id" id="Sender_Template_Id" class="form-control"  required>
                    <option value="">---- Select SMS Template ----</option>
                    
                    </select>
                </div>
            </div>
        </div>


        <div class="row">
            <div class="col-md-12">

                <div class="form-group">
                    
                    <label for="exampleFormControlSelect1">Message*</label><br>
                    <small id="emailHelp" class="form-text text-muted">Use #NAME #BATCH #ROLLNO #DIVISION #EMAIL  #STUDENTID #GRNO as place holder for each students details.</small><br>
                    <small id="emailHelp" class="form-text text-muted">Only Replace Dynamic Variable.</small><br>
                    <!-- <small id="emailHelp" class="form-text text-muted"> Use #S1 #S2 #S3 #S4 #S5 and #S6 as place holder for students subject..</small><br> -->
                    <!-- <small id="emailHelp" class="form-text text-danger"><b>Avoid copy paste. Type the special characater like @$^&\_|' "</b></small><br> -->
                    <textarea rows="8" class="form-control detailsinput" id="messageText" name="message" maxlength="1000"></textarea readonly><br>
                    <small id="emailHelp" class="form-text text-muted char_cal"></small>

                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">

                <div class="form-group">
                      <button type="submit" id="send_sms" name="send_sms" class="btn btn-primary btn-block"
                      <?php if((int)$r_credit_balance['balance'] <= 0){ echo 'disabled'; } ?>
                      ><i class="fa fa-send-o" aria-hidden="true"></i>  Send Message</button>                    
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
        <div class="col-md-6"><h3><i class="fa fa-envelope text-primary" aria-hidden="true"></i>  SEND MESSAGE</h3></div>
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
    
    var text_max = 1000;
    $('.char_cal').html(text_max + ' Characters Remaining');
    $('#messageText').keyup(function () {
      var text_length = $('#messageText').val().length;
        var tot = parseInt(text_max) - parseInt(text_length);  
      $('.char_cal').html(tot + ' Characters Remaining');
    });

});




$('#SMSDetailsForm').submit(function(e){
e.preventDefault();

    var return_batch_sel = $('#return_batch_sel').val();
    var return_batch_name = $('#return_batch_name').val();
    var return_list_sel = $('#return_list_sel').val();
    var return_list_name = $('#return_list_name').val();
    var return_contact_sel = $('#return_contact_sel').val();

    var select_user_count = $('#select_user_count').val();

    var FORMDATASMS = $('#SMSDetailsForm').serializeArray();
    $("#loader").css("display", "block");
    $("#DisplayDiv").css("display", "none");

    $.ajax({
        type:'POST',
        url:'./communication/Communication_api.php?InsertingSMSLogEntries='+'u',
        dataType: "json",
        data: FORMDATASMS,
        success:function(res_data){
            
            if(res_data['status'] == 'success') {
                var CML_Id = res_data['Comm_Message_Logs_Id'];

                if(CML_Id === 'NA' || CML_Id === ''){
                    $("#loader").css("display", "none");
                    $("#DisplayDiv").css("display", "block");

                    alert('Try Again Not Able to Create Entries')
                }else if(CML_Id != '' || CML_Id != 'NA'){
                    
                    $.ajax({
                        type:'POST',
                        url:'./communication/Communication_api.php?SendingStudentSMS='+'u',
                        dataType: "json",
                        data: {CML_Id: CML_Id},
                        success:function(res_data_sms){
                            $("#loader").css("display", "none");
                            $("#DisplayDiv").css("display", "block");
                            

                            var UploadedFilePath = res_data_sms['UploadedFilePath'];
              
                            var i = 0;

                            var errorString = '';
                            var successString = '';

                            res_data_sms['SMSReponse'].forEach(function(entry) {
                                var pattn = /SUBMIT_SUCCESS/g;
                                var PatternVerify = pattn.test(res_data_sms['SMSReponse']);
                                if(PatternVerify === true){
                                    successString = successString.concat(res_data_sms['SMSUserId'][i] + " : " + res_data_sms['SMSReponse'][i], "</br>");
                                }else{
                                    errorString = errorString.concat(res_data_sms['SMSUserId'][i] + " : " + res_data_sms['SMSReponse'][i] , "</br>");
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


                            $.ajax({
                                type:'POST',
                                url:'./communication/Communication_api.php?SMSCreditUpdate='+'u',
                                dataType: "json",
                                data: {batch_sel: return_batch_sel, select_user_count:select_user_count},
                                success:function(res_data_sms){

                                },
                            });




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

<script>
$(document).on('change', '#Sender_Header', function(event) {
    var Sender_Header_Id = $(this).val(); // the selected options’s value

    if(Sender_Header_Id == ''){
        iziToast.warning({
            title: 'Empty Fields',
            message: 'Select Valid Field',
        });
        return false;
    }

    $.ajax({
        url:'./communication/Communication_api.php?Template_From_Header='+'u',
        type: 'POST',
        data: {Sender_Header_Id:Sender_Header_Id},
        success:function(batch_data_res){
            
          $('#Sender_Template_Id').html(batch_data_res);

        },
    });

});

$(document).on('change', '#Sender_Template_Id', function(event) {
    var Sender_Template_Id = $(this).val(); // the selected options’s value

    if(Sender_Template_Id == ''){
        iziToast.warning({
            title: 'Empty Fields',
            message: 'Select Valid Field',
        });
        $('#messageText').val('');
        return false;
    }
    var text = $( "#Sender_Template_Id option:selected" ).text();
    $('#messageText').val('');
    $('#messageText').val(text);
    console.log(text);

});
</script>