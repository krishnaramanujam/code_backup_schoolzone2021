<?php
session_start();
include_once '../config/database.php';


$SectionMaster_Id = $_SESSION['schoolzone']['SectionMaster_Id'];
$ActiveStaffLogin_Id = $_SESSION['schoolzone']['ActiveStaffLogin_Id'];


//Fetching User Details
$data_query = "SELECT setup_sectionmaster.section_name,setup_sectionmaster.section_logo,setup_sectionmaster.address,setup_sectionmaster.abbreviation FROM setup_sectionmaster  WHERE  setup_sectionmaster.Id = '$SectionMaster_Id' ";
$fetch_data_q = mysqli_query($mysqli,$data_query);

$r_Staffdata_fetch    = mysqli_fetch_array($fetch_data_q);

?>


<div class="jumbotron" style="padding-top: 0px;padding-bottom: 0px;background-color: transparent;text-align:center;">
  <h3 class="text-warning"><img src="../<?php echo $r_Staffdata_fetch['section_logo']; ?>" style="border-radius: 50%;max-width: 120px;max-height: 100px;margin-right: 10px;"/><?php echo  $r_Staffdata_fetch['section_name']; ?></h3>
  <h3 class="text-warning">STAFF PORTAL</h3>
</div>

