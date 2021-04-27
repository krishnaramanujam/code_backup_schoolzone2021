<?php
session_start();
include_once '../../config/database_candidate.php';


$SectionMaster_Id = $_SESSION['schoolzone_student']['SectionMaster_Id'];
$Activestudentregister_Id = $_SESSION['schoolzone_student']['Activestudentregister_Id'];


//Fetching User Details
$data_query = "SELECT setup_sectionmaster.* FROM setup_sectionmaster WHERE setup_sectionmaster.Id = '$SectionMaster_Id' ";
$fetch_data_q = mysqli_query($mysqli,$data_query);

$r_Staffdata_fetch = mysqli_fetch_array($fetch_data_q);

?>



<div class="jumbotron" style="padding-top: 0px;padding-bottom: 0px;background-color: transparent;text-align:center;">
  <h3 class="text-warning"><img src="../../<?php echo $r_Staffdata_fetch['section_logo']; ?>" style="border-radius: 50%;max-width: 120px;max-height: 100px;margin-right: 10px;"/><?php echo  $r_Staffdata_fetch['section_name']; ?></h3>
  <h3 class="text-warning">FYJC STUDENT PORTAL</h3>
</div>


