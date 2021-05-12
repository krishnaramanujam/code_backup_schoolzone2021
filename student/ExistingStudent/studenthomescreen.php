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



<h3 style="text-align:center;font-weight: 700;" class="text-warning">EXISTING STUDENT PORTAL <?php echo $Acadmic_Year_ID; ?> <br><br> <?php echo $r_Staffdata_fetch['section_name']; ?>
    <br><br> 

    <?php
        $checking_sbm_cy = mysqli_query($mysqli, "SELECT user_studentregister.SBM_Id, user_studentbatchmaster.batchMaster_Id, setup_batchmaster.academicyear_Id, user_studentbatchmaster.promoted_batch, a1.academicyear_Id As Promoted_AY FROM user_studentbatchmaster JOIN user_studentregister ON user_studentbatchmaster.studentRegister_Id = user_studentregister.Id JOIN setup_batchmaster ON setup_batchmaster.Id = user_studentbatchmaster.batchMaster_Id LEFT JOIN setup_batchmaster a1 ON a1.Id = user_studentbatchmaster.promoted_batch WHERE user_studentregister.Id = '$Activestudentregister_Id'  ");
        $r_checking_sbm_cy = mysqli_fetch_array($checking_sbm_cy);

        if($r_checking_sbm_cy['academicyear_Id'] != $Acadmic_Year_ID){
            //Checking next Year 
            if(empty($r_checking_sbm_cy['promoted_batch']) OR $r_checking_sbm_cy['Promoted_AY'] != $Acadmic_Year_ID){
                //Premoted Batch Not Present Remove Access 
                echo 'Contact College';
            }// empty close 

        }



    ?>

