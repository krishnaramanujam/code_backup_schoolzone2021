<?php

ini_set('max_execution_time', 0);
set_time_limit(0);
ini_set('memory_limit','-1');
ini_set('display_errors',1);
error_reporting(E_ALL);
session_start();
include('../../../config/database_student.php');


$SectionMaster_Id = $_SESSION['schoolzone_student']['SectionMaster_Id'];
$Activestudentregister_Id = $_SESSION['schoolzone_student']['Activestudentregister_Id'];
$SBM_Id = $_SESSION['schoolzone_student']['SBM_Id'];
$application_Id = $_GET['application_Id'];


//Student Details
$q = "SELECT user_studentregister.student_name, user_studentregister.student_Id, user_studentbatchmaster.fee_allocation_status, user_studentbatchmaster.Id AS SBM_Id, setup_batchmaster.batch_name, user_studentregister.gr_no FROM user_studentbatchmaster JOIN user_studentregister ON user_studentregister.SBM_Id = user_studentbatchmaster.Id JOIN setup_batchmaster ON setup_batchmaster.Id = user_studentbatchmaster.batchMaster_Id JOIN setup_programmaster ON setup_programmaster.Id = setup_batchmaster.programmaster_Id JOIN setup_streammaster ON setup_streammaster.Id = setup_programmaster.streammaster_Id WHERE  setup_streammaster.sectionmaster_Id = '$SectionMaster_Id' AND user_studentbatchmaster.Id = '$SBM_Id'";
$studentdetails_fetch_q = mysqli_query($mysqli,$q);

$r_studentdetails = mysqli_fetch_array($studentdetails_fetch_q);

?>
<div class="col-md-12"><h4><span class="badge btn btn-primary return_btn"><i class="fa fa-arrow-left"></i></span><i><b>Existing Student Fee Details</b></i></h4></div>


<input type="hidden" name="student_Id" id="student_Id" class="form-control" value="<?php echo $student_Id; ?>">
<input type="hidden" name="SBM_Id" id="SBM_Id" class="form-control" value="<?php echo $SBM_Id; ?>">


   <!-- ///////////////////////////////////////////////////////////////////////////////////////////////////////////// --> 
   <div class="col-md-11">
    
    <div class="box">

    <div class="headingPanel form-inline" style="font-size:medium;font-style: italic;text-transform: uppercase;margin-bottom:20px;">
        


        <div class="row" style="margin-top:20px;">

            <div class="col-md-3" style="text-align:center;"><label>Student Name :</label></div>
            <div class="col-md-4">
                <span class="text-primary"><?php echo $r_studentdetails['student_name']; ?></span>
            </div>

            <div class="col-md-2" style="text-align:center;"><label>Student Id :</label></div>
            <div class="col-md-3">
                <span class="text-primary"><?php echo $r_studentdetails['student_Id']; ?></span>
            </div>

        </div><!--DD Row1 Close-->

        <div class="row">

            <div class="col-md-3" style="text-align:center;"><label>Batch Name :</label></div>
            <div class="col-md-4">
                <span class="text-primary"><?php echo $r_studentdetails['batch_name']; ?></span>
            </div>

            <div class="col-md-2" style="text-align:center;"><label>GR NO :</label></div>
            <div class="col-md-3">
                <span class="text-primary"><?php echo $r_studentdetails['gr_no']; ?></span>
            </div>

        </div><!--DD Row2 Close-->

    
     
     
         </div>

    </div><!--Heading Panel Close-->
    </div>

    <!-- ///////////////////////////////////////////////////////////////////////////////////////////////////////////// --> 

   
    <script>



$('.return_btn').click(function(event){
    event.preventDefault();

    $("#loader").css("display", "block");
    $("#DisplayDiv").css("display", "none");
    $.ajax({
        url:'./admission/fee_payment_screen.php',
        type:'GET',
        success:function(response){
            $('#DisplayDiv').html(response);
            $("#loader").css("display", "none");
            $("#DisplayDiv").css("display", "block");
        },
    });
});

</script>