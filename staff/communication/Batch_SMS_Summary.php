<?php
//Session File
include_once '../SessionInfo.php';
//Database File
include_once '../../config/database.php';

?>


<div class="col-md-12"><h4><i><b>  Batch SMS Summary Report</b></i>
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
<?php 
    if(isset($_GET['Generate_View'])){
        $start_date    = $_GET['start_date'];
        $end_date      = $_GET['end_date'];

        $start_f = date('Y-m-d', strtotime(str_replace('/', '-', $start_date)));
        $end_f   = date('Y-m-d', strtotime(str_replace('/', '-', $end_date)));

    
?>
<!-- -------------------------------------------------------------------------------------------------- -->
<!-- -------------------------------------------------------------------------------------------------- -->
<?php 
      
      $batch_fetch = mysqli_query($mysqli,"SELECT setup_batchmaster.* FROM setup_batchmaster JOIN setup_programmaster ON setup_programmaster.Id = setup_batchmaster.programmaster_Id JOIN setup_streammaster ON setup_streammaster.Id = setup_programmaster.streammaster_Id WHERE setup_streammaster.sectionmaster_Id = '$SectionMaster_Id' AND setup_batchmaster.academicyear_Id = '$Acadmic_Year_ID'");

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
            <th>Sr No</th>
            <th class="text-left">Batch Name</th>
            <th>Total SMS</th>
        </tr>

    </thead>
    <tbody>
        <?php $i = 1; while($r_batch = mysqli_fetch_array($batch_fetch)){
        
              $studentsms_fetch = mysqli_query($mysqli,"SELECT count(comm_message_log.comm_message_log_Id) As Student_SMS_Count FROM `comm_message_log` JOIN user_studentbatchmaster ON user_studentbatchmaster.Id = comm_message_log.User_Id WHERE comm_message_log.userIdType = 'SBM_Id' AND comm_message_log.moduleType = 'Communication' AND user_studentbatchmaster.batchMaster_Id = '$r_batch[Id]' AND comm_message_log.timestamp >= '$start_f 00:00:00' AND comm_message_log.timestamp <= '$end_f 24:00:00' ");
              $r_studentsms_fetch = mysqli_fetch_array($studentsms_fetch);
              
              $candidatesms_fetch = mysqli_query($mysqli,"SELECT count(comm_message_log.comm_message_log_Id) As Candidate_SMS_Count FROM `comm_message_log` JOIN user_applicationdetails ON user_applicationdetails.Id = comm_message_log.User_Id WHERE comm_message_log.userIdType = 'AD_Id' AND comm_message_log.moduleType = 'Communication' AND user_applicationdetails.batchMaster_Id = '$r_batch[Id]' AND comm_message_log.timestamp >= '$start_f 00:00:00' AND comm_message_log.timestamp <= '$end_f 24:00:00' ");
              $r_candidatesms_fetch = mysqli_fetch_array($candidatesms_fetch);

              $tot_sel_batch = $r_studentsms_fetch['Student_SMS_Count'] + $r_candidatesms_fetch['Candidate_SMS_Count'];
       ?>
        <tr>
            <th><?php echo $i; ?></th>
            <th class="text-left"><?php echo $r_batch['batch_name']; ?></th>
            <th><?php echo $tot_sel_batch; ?></th>
        </tr>

        <?php $i++; } ?>

    </tbody>
    
 </table>
 </form>

</div><!--Close Col-md-9-->

<!-- --------------------------------------------------------------------------------------------------------------------------         -->  


</div><!--Panel BOdy Close-->

</div>

</script>

<?php } // close isset ?> 

<script>
$('#search_access').click(function(e){
    e.preventDefault();

    var formdata = $('#ReceiptForm').serializeArray();

    $("#loader").css("display", "block");
    $("#DisplayDiv").css("display", "none");
    
    $.ajax({
        url:'./communication/Batch_SMS_Summary.php?Generate_View='+'u',
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