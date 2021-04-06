<?php
//Session File
include_once '../SessionInfo.php';
//Database File
include_once '../../config/database.php';

?>


<div class="box">
<div class="col-md-12"><h4><i><b>  Student Admission Login SMS Report</b></i>
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
<!-- -------------------------------------------------------------------------------------------------- -->
<?php 
    if(isset($_GET['Generate_View'])){
        $start_date    = $_GET['start_date'];
        $end_date      = $_GET['end_date'];

        $start_f = date('Y-m-d', strtotime(str_replace('/', '-', $start_date)));
        $end_f   = date('Y-m-d', strtotime(str_replace('/', '-', $end_date)));
    

        $comm_fetch_q = mysqli_query($mysqli, "SELECT comm_message_log.* FROM comm_message_log  WHERE  userIdType = 'SBM_Id' AND moduleType != 'Communication' AND comm_message_log.timestamp >= '$start_f 00:00:00' AND comm_message_log.timestamp <= '$end_f 24:00:00' AND comm_message_log.message_type = '1'");

        $rows_comm_fetch = mysqli_num_rows($comm_fetch_q);
        
    
?>
<!-- --------------------------------------------------------------------------------------------------------------------------         -->  
<div class="panel panel-info">
<div class="panel-heading">Communication Report

</div>

<div class="panel-body">

<div class="col-md-12 col-sm-12">
<form id="editstudentdetailsphotosign">  

  <table id="CommunicationReportTable" class="table table-striped table-hover"> 
    <thead>
        <tr>
            <th>Sr No</th>
            <th>Batch Name</th>
            <th>Candidate Name</th>
            <th>Mobile No</th>
            <th>Type</th>
            <th>Count</th>
        
        </tr>
    </thead>
    <tbody>
        <?php 
            if($rows_comm_fetch > '0'){
                $i = 1;
                While($r_comm_fetch = mysqli_fetch_array($comm_fetch_q)){
                    $User_Id = $r_comm_fetch['User_Id'];

                    if($r_comm_fetch['userIdType'] == 'SBM_Id'){
                        $F_StudentData_q = mysqli_query($mysqli,"SELECT
                        user_studentregister.mobile_no AS Student_Contact,
                        user_studentbatchmaster.roll_no,
                        user_studentdetails.Student_name,
                        user_stafflogin.username AS Sender_login_Id,
                        user_studentdetails.fathers_Contact,
                        user_studentdetails.mothers_Contact,
                        user_studentregister.password As OTP,
                        setup_batchmaster.batch_name AS Batch_Name,
                        user_studentbatchmaster.Div,
                        user_studentregister.email_address AS registeredEmailAddress,
                        user_studentregister.gr_no AS GR_No,
                        user_studentregister.student_Id,
                        comm_message_log.recipient_address,
                        setup_batchmaster.Id As BM_Id
                      FROM
                        user_studentbatchmaster
                      JOIN
                        user_studentregister ON user_studentbatchmaster.studentRegister_Id = user_studentregister.Id
                      JOIN
                        user_studentdetails ON user_studentbatchmaster.studentRegister_Id = user_studentdetails.studentregister_Id
                      JOIN
                        setup_batchmaster ON setup_batchmaster.Id = user_studentbatchmaster.batchMaster_Id
                      JOIN 
                      comm_message_log ON comm_message_log.User_Id = user_studentbatchmaster.Id
                      JOIN
                        user_stafflogin ON user_stafflogin.Id = comm_message_log.User_Id
                      WHERE
                        user_studentbatchmaster.Id = '$r_comm_fetch[User_Id]' AND comm_message_log.comm_message_log_Id = '$r_comm_fetch[comm_message_log_Id]'");

                        $UserType = 'Student';
                        $r_Fetching_StudentData = mysqli_fetch_array($F_StudentData_q);

                    }elseif($r_comm_fetch['userIdType'] == 'CR_Id'){
                        $User_Res = CandidateInformation($User_Id, $mysqli);
                        $UserType = 'Candidate';
                    }elseif($r_comm_fetch['userIdType'] == 'AD_Id'){
                        $User_Res = ApplicationInformation($User_Id, $mysqli);
                        $UserType = 'CandidateApplication';
                    }
                    
           
                   

                    $datee = $r_comm_fetch['timestamp'];

                    $datee = date("d-m-Y h:m:s", strtotime($datee));
                    $message_Id = $r_comm_fetch['ID'];

                    if($r_comm_fetch['Status'] == 'SUBMIT_SUCCESS'){
                        $delivery_Status = file_get_contents( "http://www.sms.rightchoicesms.com/sendsms/dlrstatus.php?username=RCdvsl17&password=speed100&messageid=".$message_Id);
                        $DSArray = explode("|", $delivery_Status);
                        $DTime = trim($DSArray[1]);
                        $Formart_DTime = date("d-m-Y h:m:s", strtotime($DTime));

                    }else{
                        $delivery_Status = 'NA';
                    }


        ?>
        
        <tr>
            <td><?php echo $i; ?></td> 
            <td><?php echo $r_Fetching_StudentData['Batch_Name']; ?></td> 
            <td><?php echo $r_Fetching_StudentData['Student_name']; ?></td> 
            <td><?php echo $r_comm_fetch['recipient_address']; ?></td> 
            <td><?php echo $r_comm_fetch['moduleType']; ?></td>
            <td><?php echo $r_comm_fetch['no_of_characters']; ?></td>
        </tr>
            
                <?php $i++; } // close while?>

        <?php } //close if ?>
    </tbody>

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
        url:'./communication/StudentAdmissionLoginReport.php?Generate_View='+'u',
        type:'GET',
        data: formdata,
        success:function(srh_response){
            $('#DisplayDiv').html(srh_response);
            $("#loader").css("display", "none");
            $("#DisplayDiv").css("display", "block");
        },
   });



});


$('#start_date').datepicker({
		format: 'dd/mm/yyyy',
      autoclose: true
});

$('#end_date').datepicker({
		format: 'dd/mm/yyyy',
      autoclose: true
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