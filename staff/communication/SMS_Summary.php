<?php
//Session File
include_once '../SessionInfo.php';
//Database File
include_once '../../config/database.php';


?>

<div class="col-md-12"><h4><i><b>  SMS Summary Report</b></i>
</div>


<form id="ReceiptForm">
       <div class="form-group form-inline">
    
        <div class="row">

                <div class="col-md-1" style="text-align:center;">
                    <label for="email">Select Start Date:</label>
                </div>
                
                <div class="col-md-2"> 
                    <input type="text" name="start_date" id="start_date" value="<?php echo $_GET['start_date']; ?>">
                </div>

             


                <div class="col-md-1" style="text-align:center;">
                    <label for="email">Select End Date:</label>
                </div>
                
                <div class="col-md-2"> 
                    <input type="text" name="end_date" id="end_date" value="<?php echo $_GET['end_date']; ?>">
                </div>


                <div class="col-md-2" style="text-align:center;">      
                    <input type="button" name="search" id="search_access" value="Search" class="btn btn-primary"/></div>
                </div>


        </div><!--Row1 Close-->
        
        </div>
</form> 

<div class="col-md-8">
<!-- -------------------------------------------------------------------------------------------------- -->
<?php 
    if(isset($_GET['Generate_View'])){
        $start_date    = $_GET['start_date'];
        $end_date      = $_GET['end_date'];

        $start_f = date('Y-m-d', strtotime(str_replace('/', '-', $start_date)));
        $end_f   = date('Y-m-d', strtotime(str_replace('/', '-', $end_date)));

?>
<!-- -------------------------------------------------------------------------------------------------- -->
<?php 
      

// --------------------------------------------------------------------------------------------------------------------------------------------
        $deliver_candidate_admission_fetch_q = mysqli_query($mysqli, "SELECT comm_message_log.* FROM comm_message_log JOIN comm_sms_header_ids ON comm_sms_header_ids.Id = comm_message_log.SenderHeader Where comm_sms_header_ids.sectionmaster_Id = '$SectionMaster_Id' AND  (userIdType = 'CR_Id' OR userIdType = 'AD_Id') AND moduleType != 'Communication' AND comm_message_log.Status = 'SUBMIT_SUCCESS' AND comm_message_log.timestamp >= '$start_f 00:00:00' AND comm_message_log.timestamp <= '$end_f 24:00:00' AND comm_message_log.message_type = '1'");
        $rows_deliver_candidate_admission_fetch = mysqli_num_rows($deliver_candidate_admission_fetch_q);
        if($rows_deliver_candidate_admission_fetch == '0'){ $Candidate_SMS_Sent = 0; }else{ $Candidate_SMS_Sent = $rows_deliver_candidate_admission_fetch; }
        $DS_TOT_CAF = 0;
        $UNDS_TOT_CAF = 0;
        if($rows_deliver_candidate_admission_fetch > 0){    
            while($r_deliver_candidate_admission_fetch = mysqli_fetch_array($deliver_candidate_admission_fetch_q)){
                $message_Id = $r_deliver_candidate_admission_fetch['ID'];
                $delivery_Status = file_get_contents( "http://www.sms.rightchoicesms.com/sendsms/dlrstatus.php?username=RCdvsl17&password=speed100&messageid=".$message_Id);
                $DSArray = explode("|", $delivery_Status);
                $DStatus = trim($DSArray[0]);


                $patt1 = "/DELIVRD$/i";
                $MatchingPatternSuccess1 = preg_match($patt1, $DStatus);

                $patt2 = "/SUBMITD$/i";
                $MatchingPatternSuccess2 = preg_match($patt2, $DStatus);

                $patt3 = "/UNDELIV$/i";
                $MatchingPatternSuccess3 = preg_match($patt3, $DStatus);

                if($MatchingPatternSuccess1 OR $MatchingPatternSuccess2){
                    $DS_TOT_CAF ++;
                }

                if($MatchingPatternSuccess3){
                    $UNDS_TOT_CAF ++;
                }

     
            }
        }



// --------------------------------------------------------------------------------------------------------------------------------------------



// --------------------------------------------------------------------------------------------------------------------------------------------
$deliver_student_admission_fetch_q = mysqli_query($mysqli, "SELECT comm_message_log.* FROM comm_message_log JOIN comm_sms_header_ids ON comm_sms_header_ids.Id = comm_message_log.SenderHeader Where comm_sms_header_ids.sectionmaster_Id = '$SectionMaster_Id' AND  userIdType = 'SBM_Id' AND moduleType != 'Communication' AND Status = 'SUBMIT_SUCCESS' AND comm_message_log.timestamp >= '$start_f 00:00:00' AND comm_message_log.timestamp <= '$end_f 24:00:00' AND comm_message_log.message_type = '1'");
$rows_deliver_student_admission_fetch = mysqli_num_rows($deliver_student_admission_fetch_q);
if($rows_deliver_student_admission_fetch == '0'){ $Student_SMS_Sent = 0; }else{ $Student_SMS_Sent = $rows_deliver_student_admission_fetch; }
$DS_TOT_SAF = 0;
$UNDS_TOT_SAF = 0;
if($rows_deliver_student_admission_fetch > 0){    
    while($r_deliver_student_admission_fetch = mysqli_fetch_array($deliver_student_admission_fetch_q)){
        $message_Id = $r_deliver_student_admission_fetch['ID'];
        $delivery_Status = file_get_contents( "http://www.sms.rightchoicesms.com/sendsms/dlrstatus.php?username=RCdvsl17&password=speed100&messageid=".$message_Id);
        $DSArray = explode("|", $delivery_Status);
        $DStatus = trim($DSArray[0]);


        $patt1 = "/DELIVRD$/i";
        $MatchingPatternSuccess1 = preg_match($patt1, $DStatus);

        $patt2 = "/SUBMITD$/i";
        $MatchingPatternSuccess2 = preg_match($patt2, $DStatus);

        $patt3 = "/UNDELIV$/i";
        $MatchingPatternSuccess3 = preg_match($patt3, $DStatus);

        if($MatchingPatternSuccess1 OR $MatchingPatternSuccess2){
           $DS_TOT_SAF ++;
        }

        if($MatchingPatternSuccess3){
            $UNDS_TOT_SAF ++;
        }


    }
}

// --------------------------------------------------------------------------------------------------------------------------------------------

// --------------------------------------------------------------------------------------------------------------------------------------------
$deliver_comm_admission_fetch_q = mysqli_query($mysqli, "SELECT comm_message_log.* FROM comm_message_log JOIN comm_sms_header_ids ON comm_sms_header_ids.Id = comm_message_log.SenderHeader Where comm_sms_header_ids.sectionmaster_Id = '$SectionMaster_Id' AND  (userIdType = 'SBM_Id' OR userIdType = 'AD_Id') AND moduleType = 'Communication' AND Status = 'SUBMIT_SUCCESS' AND comm_message_log.timestamp >= '$start_f 00:00:00' AND comm_message_log.timestamp <= '$end_f 24:00:00' AND comm_message_log.message_type = '1'");
$rows_deliver_comm_admission_fetch = mysqli_num_rows($deliver_comm_admission_fetch_q);
if($rows_deliver_comm_admission_fetch == '0'){ $comm_SMS_Sent = 0; }else{ $comm_SMS_Sent = $rows_deliver_comm_admission_fetch; }
$DS_TOT_COMM = 0;
$UNDS_TOT_COMM = 0;
if($rows_deliver_comm_admission_fetch > 0){    
    while($r_deliver_comm_admission_fetch = mysqli_fetch_array($deliver_comm_admission_fetch_q)){
        $message_Id = $r_deliver_comm_admission_fetch['ID'];
        $delivery_Status = file_get_contents( "http://www.sms.rightchoicesms.com/sendsms/dlrstatus.php?username=RCdvsl17&password=speed100&messageid=".$message_Id);
        $DSArray = explode("|", $delivery_Status);
        $DStatus = trim($DSArray[0]);


        $patt1 = "/DELIVRD$/i";
        $MatchingPatternSuccess1 = preg_match($patt1, $DStatus);

        $patt2 = "/SUBMITD$/i";
        $MatchingPatternSuccess2 = preg_match($patt2, $DStatus);

        $patt3 = "/UNDELIV$/i";
        $MatchingPatternSuccess3 = preg_match($patt3, $DStatus);

        if($MatchingPatternSuccess1 OR $MatchingPatternSuccess2){
            $DS_TOT_COMM ++;
        }

        if($MatchingPatternSuccess3){
            $UNDS_TOT_COMM ++;
        }


    }
}

// --------------------------------------------------------------------------------------------------------------------------------------------


$total_sent = $Candidate_SMS_Sent + $Student_SMS_Sent + $comm_SMS_Sent;

$total_deliver_sms = $DS_TOT_CAF + $DS_TOT_SAF + $DS_TOT_COMM;

$total_undeliver_sms = $UNDS_TOT_CAF + $UNDS_TOT_SAF + $UNDS_TOT_COMM;
?>

<div class="panel panel-info">
<div class="panel-heading">Communication Report

</div>

<div class="panel-body">

<div class="col-md-12 col-sm-12">
<form id="editstudentdetailsphotosign">  

  <table id="CommunicationReportTable" class="table table-striped table-hover"> 
    <thead>
        <tr>
            <th></th>
            <th>Sent</th>
            <th>Delivered</th>
            <th>UnDelivered</th>
        </tr>

        <tr>
            <th class="text-left">Candidate Admission Login sms</th>
            <th><?php echo $Candidate_SMS_Sent; ?></th>
            <th><?php echo $DS_TOT_CAF; ?></th>
            <th><?php echo $UNDS_TOT_CAF; ?></th>
        </tr>

        <tr>
            <th class="text-left">Student Admission Login sms</th>
            <th><?php echo $Student_SMS_Sent; ?></th>
            <th><?php echo $DS_TOT_SAF; ?></th>
            <th><?php echo $UNDS_TOT_SAF; ?></th>
        </tr>

        <tr>
            <th class="text-left">Communication sms</th>
            <th><?php echo $comm_SMS_Sent; ?></th>
            <th><?php echo $DS_TOT_COMM; ?></th>
            <th><?php echo $UNDS_TOT_COMM; ?></th>
        </tr>

        <tr>
            <th>Total</th>
            <th><?php echo $total_sent; ?></th>
            <th><?php echo $total_deliver_sms; ?></th>
            <th><?php echo $total_undeliver_sms; ?></th>
        </tr>
    </thead>
    
 </table>
 </form>

</div><!--Close Col-md-9-->

<!-- --------------------------------------------------------------------------------------------------------------------------         -->  


</div><!--Panel BOdy Close-->

</div>

<?php } // close isset ?> 

<script>
$('#search_access').click(function(e){
    e.preventDefault();

    var formdata = $('#ReceiptForm').serializeArray();

    $("#loader").css("display", "block");
    $("#DisplayDiv").css("display", "none");
    
    $.ajax({
        url:'./communication/SMS_Summary.php?Generate_View='+'u',
        type:'GET',
        data: formdata,
        success:function(srh_response){
            $('#DisplayDiv').html(srh_response);
            $("#loader").css("display", "none");
            $("#DisplayDiv").css("display", "block");
        },
   });



});

$('#CommunicationReportTable').DataTable( {
    dom: 'Bifrtp',
    bPaginate:false,
    "ordering": false,
    buttons: [
    {
        extend: 'excel',
        footer: true,
		title: "Download Format",
		text: 'Download Format',
        exportOptions : {
            columns : ':visible',
            format : {
                header : function (mDataProp, columnIdx) {
            var htmlText = '<span>' + mDataProp + '</span>';
            var jHtmlObject = jQuery(htmlText);
            jHtmlObject.find('div').remove();
            var newHtml = jHtmlObject.text();
            console.log('My header > ' + newHtml);
            return newHtml;
                }
            }
        }
    }
    ]
} );

$('#start_date').datepicker({
		format: 'dd/mm/yyyy',
      autoclose: true
});

$('#end_date').datepicker({
		format: 'dd/mm/yyyy',
      autoclose: true
});
</script>
<style>

.modal-header {
  background-color: #203e5f;
  color: #FFF;
}

.designTheme {
  background-color: #203e5f;
  color: #FFF;
}

.designTheme:hover {
  background-color: #FFF;
  border: 1px solid #203e5f;
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



</style>