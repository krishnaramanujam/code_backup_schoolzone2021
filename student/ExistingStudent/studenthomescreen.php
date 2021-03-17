<?php
session_start();
include_once '../../config/database_student.php';


$SectionMaster_Id = $_SESSION['schoolzone_student']['SectionMaster_Id'];
$Activestudentregister_Id = $_SESSION['schoolzone_student']['Activestudentregister_Id'];


//Fetching User Details
$data_query = "SELECT user_studentregister.student_name AS username,setup_sectionmaster.section_name FROM user_studentregister JOIN setup_sectionmaster ON setup_sectionmaster.Id = user_studentregister.sectionmaster_Id WHERE user_studentregister.Id = '$Activestudentregister_Id' AND setup_sectionmaster.Id = '$SectionMaster_Id' ";
$fetch_data_q = mysqli_query($mysqli,$data_query);

$r_Staffdata_fetch = mysqli_fetch_array($fetch_data_q);

?>



<h3 style="text-align:center;font-weight: 700;" class="text-warning">EXISTING STUDENT PORTAL <br><br> <?php echo $r_Staffdata_fetch['section_name']; ?>
    <br><br> 



