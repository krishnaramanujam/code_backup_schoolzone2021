<?php

ini_set('max_execution_time', 0);
set_time_limit(0);
ini_set('memory_limit','-1');
ini_set('display_errors',1);
error_reporting(E_ALL);
session_start();

include_once '../../config/database.php';


$ActiveStaffLogin_Id = $_SESSION['schoolzone']['ActiveStaffLogin_Id'];
$SectionMaster_Id = $_SESSION['schoolzone']['SectionMaster_Id'];



$user_id_edit = $_GET['SelectedUser_Id'];


?>





<div class="container">
    <div class="row">

    <div class="col-md-12"><h4><span class="badge btn btn-primary" onclick="getPage('./user_management/homescreen.php');"><i class="fa fa-arrow-left"></i></span><i><b>  SMS Heeader & Email Details Access</b></i></h4></div>



<form id= "SMS_FormData">
    <input type="hidden" value="<?php echo $user_id_edit; ?>" id="sms_user_id" name="sms_user_id">  
    
    <div class="col-md-5 col-sm-12" style="margin-bottom:40px;">
    <ul class="list-group" id="list-tab" role="tablist">
    <li class="list-group-item active">SMS Header Id<span class="pull-right">Permission</span></li>
    <?php 
            $dept_sel = "select * from comm_sms_header_ids Where sectionmaster_Id = '$SectionMaster_Id'";
            $sms_header_details_q = mysqli_query($mysqli,$dept_sel);
            $row_smsheader_details = mysqli_num_rows($sms_header_details_q);

            if($row_smsheader_details > 0){
            while($row = mysqli_fetch_array($sms_header_details_q)){
    ?>
    
    <li class="list-group-item d-flex justify-content-between align-items-center">
        <?php echo $row['header_name']; ?>
        <span class="pull-right">
            <?php 
                $check_entry = "SELECT * FROM `comm_smsheaderIds_access` where userId = '$user_id_edit' AND smsheader_Id = '".$row['Id']."'";
                $q_entry = mysqli_query($mysqli,$check_entry);
                $row_check = mysqli_num_rows($q_entry); 
                if($row_check > 0){ ?>
                
                <div class="pretty p-icon p-smooth">
                <input type="checkbox" class="form-check-label subcheck" checked id="check[<?php echo $row['Id']; ?>]" name="check[]" value="<?php echo $row['Id']; ?>"> 
                        <div class="state p-success">
                            <i class="icon fa fa-check"></i>
                            <label></label>
                        </div>
                </div>     

                <?php } else {?>

                <div class="pretty p-icon p-smooth">
                <input type="checkbox" class="form-check-label subcheck" id="check[<?php echo $row['Id']; ?>]" name="check[]" value="<?php echo $row['Id']; ?>">
                        <div class="state p-success">
                            <i class="icon fa fa-check"></i>
                            <label></label>
                        </div>
                </div>       
           
                <?php } ?>    
        </span>
    </li>
    
    
    <?php   }  }?> 

    </ul>

    <div class="col-md-12 col-sm-12">
        <button type="button" id="submit_smsinstance" name="submit" class="btn btn-primary pull-right"
                <?php if($row_smsheader_details == 0){ echo 'disabled'; } ?>
        >Save Changes</button>
    </div>

    </div>
</form>                   

<!-- ================================================================================================================= -->
<form id= "Email_FormData">
    <input type="hidden" value="<?php echo $user_id_edit; ?>" id="email_user_id" name="email_user_id">  
        
<div class="col-md-5 col-sm-12">
    <ul class="list-group" id="list-tab" role="tablist">
    <li class="list-group-item active">Set From Address<span class="pull-right">Permission</span></li>
    <?php 
            $dept_sel = "select * from setup_sectionmaildetails Where sectionmaster_Id = '$SectionMaster_Id'";
            $email_header_details_q = mysqli_query($mysqli,$dept_sel);
            $row_emailheader_details = mysqli_num_rows($email_header_details_q);


            if($row_emailheader_details > 0){
            while($row = mysqli_fetch_array($email_header_details_q)){
    ?>
    
    <li class="list-group-item d-flex justify-content-between align-items-center">
        <?php echo $row['setFromAddress']; ?>
        <span class="pull-right">
            <?php 
                $check_entry = "SELECT * FROM `comm_emailDetails_access` where userId = '$user_id_edit' AND sectionmaildetails_Id = '".$row['Id']."'";
                $q_entry = mysqli_query($mysqli,$check_entry);
                $row_check = mysqli_num_rows($q_entry); 
                if($row_check > 0){ ?>
                
                <div class="pretty p-icon p-smooth">
                <input type="checkbox" class="form-check-label subcheck" checked id="check[<?php echo $row['Id']; ?>]" name="check[]" value="<?php echo $row['Id']; ?>"> 
                        <div class="state p-success">
                            <i class="icon fa fa-check"></i>
                            <label></label>
                        </div>
                </div>     

                <?php } else {?>

                <div class="pretty p-icon p-smooth">
                <input type="checkbox" class="form-check-label subcheck" id="check[<?php echo $row['Id']; ?>]" name="check[]" value="<?php echo $row['Id']; ?>">
                        <div class="state p-success">
                            <i class="icon fa fa-check"></i>
                            <label></label>
                        </div>
                </div>       
           
                <?php } ?>    
        </span>
    </li>
    
    
    <?php   }  }?> 

    </ul>

    <div class="col-md-12 col-sm-12">
        <button type="button" id="submit_emailinstance" name="submit" class="btn btn-primary pull-right"
                <?php if($row_emailheader_details == 0){ echo 'disabled'; } ?>
        >Save Changes</button>
    </div>

    </div>
</form>

    </div>
</div>

    



<script>

$('#check').click(function(){
    if($(this).prop("checked") == false){
        $(".sel_box").removeAttr('checked');
    
    }else{

        $(".sel_box").prop('checked', true);

    }   
});

$('#checks').click(function(){
    if($(this).prop("checked") == false){
        $(".sel_boxs").removeAttr('checked');
    
    }else{

        $(".sel_boxs").prop('checked', true);

    }   
});

$('#submit_smsinstance').click(function(e){
    e.preventDefault();
    var SelectedUser_Id = $('#sms_user_id').val();

    $("#loader").css("display", "block");
    $("#DisplayDiv").css("display", "none");

    var FormData = $('#SMS_FormData').serializeArray();
    
    $.ajax({
        url:'./user_management/user_management_api.php?SMS_Header_Mapping='+'u',
        type:'POST',
        data: FormData,
        dataType: "json",
        success:function(srh_response){

            if(srh_response['status'] == 'success') {
                alert('Access Successfully Changed');
                $.ajax({
                        url:'./user_management/SMSHeader_EMAILDetailsMapping.php',
                        type:'GET',
                        data: {SelectedUser_Id:SelectedUser_Id},
                        success:function(srh_response){
                            $('#DisplayDiv').html(srh_response);
                            $("#loader").css("display", "none");
                            $("#DisplayDiv").css("display", "block");
                        },
                });
                    
            }


        },
   });



});




$('#submit_emailinstance').click(function(e){
    e.preventDefault();
    var SelectedUser_Id = $('#email_user_id').val();

    $("#loader").css("display", "block");
    $("#DisplayDiv").css("display", "none");

    var FormData = $('#Email_FormData').serializeArray();
    
    $.ajax({
        url:'./user_management/user_management_api.php?Email_Header_Mapping='+'u',
        type:'POST',
        data: FormData,
        dataType: "json",
        success:function(srh_response){

            if(srh_response['status'] == 'success') {
                alert('Access Successfully Changed');
                $.ajax({
                        url:'./user_management/SMSHeader_EMAILDetailsMapping.php',
                        type:'GET',
                        data: {SelectedUser_Id:SelectedUser_Id},
                        success:function(srh_response){
                            $('#DisplayDiv').html(srh_response);
                            $("#loader").css("display", "none");
                            $("#DisplayDiv").css("display", "block");
                        },
                });
                    
            }


        },
   });



});

</script>

<style>
.font_all{
    font-family: 'Josefin Sans', sans-serif;
}

input[type=text]{
border:0px;
border-bottom: 2px solid #3c8dbc;
}

.design_btn{
    background-color:white;
    color: black;
    border:2px solid #3c8dbc;

}

th{
    text-align: center;
    padding: 25px;
    border-bottom: 0;
    font-style: italic;
    font-weight:bold;
}

td{
    text-align:center;
}

.detailsinput{
    border-bottom: 2px solid #3c8dbc;
    font-weight: bold;
}


.design_sel{
    height: 34px;
    border:0px;
    background-color: transparent;
    text-align: center;
    border-bottom: 2px solid #3c8dbc;
    -webkit-appearance: none;
    background-position: right center;
    background-repeat: no-repeat;
    background-size: 1ex;
    background-origin: content-box;
    background-image: url("data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iVVRGLTgiIHN0YW5kYWxvbmU9Im5vIj8+CjxzdmcKICAgeG1sbnM6ZGM9Imh0dHA6Ly9wdXJsLm9yZy9kYy9lbGVtZW50cy8xLjEvIgogICB4bWxuczpjYz0iaHR0cDovL2NyZWF0aXZlY29tbW9ucy5vcmcvbnMjIgogICB4bWxuczpyZGY9Imh0dHA6Ly93d3cudzMub3JnLzE5OTkvMDIvMjItcmRmLXN5bnRheC1ucyMiCiAgIHhtbG5zOnN2Zz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciCiAgIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIKICAgdmVyc2lvbj0iMS4xIgogICBpZD0ic3ZnMiIKICAgdmlld0JveD0iMCAwIDM1Ljk3MDk4MyAyMy4wOTE1MTgiCiAgIGhlaWdodD0iNi41MTY5Mzk2bW0iCiAgIHdpZHRoPSIxMC4xNTE4MTFtbSI+CiAgPGRlZnMKICAgICBpZD0iZGVmczQiIC8+CiAgPG1ldGFkYXRhCiAgICAgaWQ9Im1ldGFkYXRhNyI+CiAgICA8cmRmOlJERj4KICAgICAgPGNjOldvcmsKICAgICAgICAgcmRmOmFib3V0PSIiPgogICAgICAgIDxkYzpmb3JtYXQ+aW1hZ2Uvc3ZnK3htbDwvZGM6Zm9ybWF0PgogICAgICAgIDxkYzp0eXBlCiAgICAgICAgICAgcmRmOnJlc291cmNlPSJodHRwOi8vcHVybC5vcmcvZGMvZGNtaXR5cGUvU3RpbGxJbWFnZSIgLz4KICAgICAgICA8ZGM6dGl0bGU+PC9kYzp0aXRsZT4KICAgICAgPC9jYzpXb3JrPgogICAgPC9yZGY6UkRGPgogIDwvbWV0YWRhdGE+CiAgPGcKICAgICB0cmFuc2Zvcm09InRyYW5zbGF0ZSgtMjAyLjAxNDUxLC00MDcuMTIyMjUpIgogICAgIGlkPSJsYXllcjEiPgogICAgPHRleHQKICAgICAgIGlkPSJ0ZXh0MzMzNiIKICAgICAgIHk9IjYyOS41MDUwNyIKICAgICAgIHg9IjI5MS40Mjg1NiIKICAgICAgIHN0eWxlPSJmb250LXN0eWxlOm5vcm1hbDtmb250LXdlaWdodDpub3JtYWw7Zm9udC1zaXplOjQwcHg7bGluZS1oZWlnaHQ6MTI1JTtmb250LWZhbWlseTpzYW5zLXNlcmlmO2xldHRlci1zcGFjaW5nOjBweDt3b3JkLXNwYWNpbmc6MHB4O2ZpbGw6IzAwMDAwMDtmaWxsLW9wYWNpdHk6MTtzdHJva2U6bm9uZTtzdHJva2Utd2lkdGg6MXB4O3N0cm9rZS1saW5lY2FwOmJ1dHQ7c3Ryb2tlLWxpbmVqb2luOm1pdGVyO3N0cm9rZS1vcGFjaXR5OjEiCiAgICAgICB4bWw6c3BhY2U9InByZXNlcnZlIj48dHNwYW4KICAgICAgICAgeT0iNjI5LjUwNTA3IgogICAgICAgICB4PSIyOTEuNDI4NTYiCiAgICAgICAgIGlkPSJ0c3BhbjMzMzgiPjwvdHNwYW4+PC90ZXh0PgogICAgPGcKICAgICAgIGlkPSJ0ZXh0MzM0MCIKICAgICAgIHN0eWxlPSJmb250LXN0eWxlOm5vcm1hbDtmb250LXZhcmlhbnQ6bm9ybWFsO2ZvbnQtd2VpZ2h0Om5vcm1hbDtmb250LXN0cmV0Y2g6bm9ybWFsO2ZvbnQtc2l6ZTo0MHB4O2xpbmUtaGVpZ2h0OjEyNSU7Zm9udC1mYW1pbHk6Rm9udEF3ZXNvbWU7LWlua3NjYXBlLWZvbnQtc3BlY2lmaWNhdGlvbjpGb250QXdlc29tZTtsZXR0ZXItc3BhY2luZzowcHg7d29yZC1zcGFjaW5nOjBweDtmaWxsOiMwMDAwMDA7ZmlsbC1vcGFjaXR5OjE7c3Ryb2tlOm5vbmU7c3Ryb2tlLXdpZHRoOjFweDtzdHJva2UtbGluZWNhcDpidXR0O3N0cm9rZS1saW5lam9pbjptaXRlcjtzdHJva2Utb3BhY2l0eToxIj4KICAgICAgPHBhdGgKICAgICAgICAgaWQ9InBhdGgzMzQ1IgogICAgICAgICBzdHlsZT0iZmlsbDojMzMzMzMzO2ZpbGwtb3BhY2l0eToxIgogICAgICAgICBkPSJtIDIzNy41NjY5Niw0MTMuMjU1MDcgYyAwLjU1ODA0LC0wLjU1ODA0IDAuNTU4MDQsLTEuNDczMjIgMCwtMi4wMzEyNSBsIC0zLjcwNTM1LC0zLjY4MzA0IGMgLTAuNTU4MDQsLTAuNTU4MDQgLTEuNDUwOSwtMC41NTgwNCAtMi4wMDg5MywwIEwgMjIwLDQxOS4zOTM0NiAyMDguMTQ3MzIsNDA3LjU0MDc4IGMgLTAuNTU4MDMsLTAuNTU4MDQgLTEuNDUwODksLTAuNTU4MDQgLTIuMDA4OTMsMCBsIC0zLjcwNTM1LDMuNjgzMDQgYyAtMC41NTgwNCwwLjU1ODAzIC0wLjU1ODA0LDEuNDczMjEgMCwyLjAzMTI1IGwgMTYuNTYyNSwxNi41NDAxNyBjIDAuNTU4MDMsMC41NTgwNCAxLjQ1MDg5LDAuNTU4MDQgMi4wMDg5MiwwIGwgMTYuNTYyNSwtMTYuNTQwMTcgeiIgLz4KICAgIDwvZz4KICA8L2c+Cjwvc3ZnPgo=");
}

.design_sel::-ms-expand {
	display: none;
}

.Update_Link {
    font-weight:bold;
    text-decoration:underline;
    cursor:pointer;	
    text-shadow: 1px 1px 1px #337ab7;
}
.Update_Link:hover{
    font-size:13px;
}

.fixed-padding {
  padding-right: 0px !important;
}

table.dataTable thead th {
  border-bottom: 0;
}
table.dataTable tfoot th {
  border-top: 0;
}

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

input[type=password]{
border:0px;
border-bottom: 2px solid #3c8dbc;
}



</style>
