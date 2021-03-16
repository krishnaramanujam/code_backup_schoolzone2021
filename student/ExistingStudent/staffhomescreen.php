<?php
session_start();
include_once '../config/database.php';


$SectionMaster_Id = $_SESSION['schoolzone']['SectionMaster_Id'];
$ActiveStaffLogin_Id = $_SESSION['schoolzone']['ActiveStaffLogin_Id'];


//Fetching User Details
$data_query = "SELECT user_stafflogin.username,setup_sectionmaster.section_name FROM user_stafflogin JOIN setup_departmentmaster ON setup_departmentmaster.Id = user_stafflogin.departmentmaster_Id JOIN setup_sectionmaster ON setup_sectionmaster.Id = setup_departmentmaster.sectionmaster_Id WHERE user_stafflogin.Id = '$ActiveStaffLogin_Id' AND setup_sectionmaster.Id = '$SectionMaster_Id' ";
$fetch_data_q = mysqli_query($mysqli,$data_query);

$r_Staffdata_fetch = mysqli_fetch_array($fetch_data_q);

?>



<h3 style="text-align:center;font-weight: 700;" class="text-warning">STAFF PORTAL <br><br> <?php echo $r_Staffdata_fetch['section_name']; ?>
    <br><br> 



