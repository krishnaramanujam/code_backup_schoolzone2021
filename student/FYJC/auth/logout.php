<?php
session_start();


if(isset($_SESSION['schoolzone_student']['SectionMaster_Id'])) {
    
    $SM_Id = $_SESSION['schoolzone_student']['SectionMaster_Id'];

    session_destroy();
    unset($_SESSION['schoolzone_student']['SectionMaster_Id']);
    unset($_SESSION['schoolzone_student']['Activestudentregister_Id']);


    header("Location: https://dvsl.in/schoolzone2021/student/existingstudent/auth/login.php?sectionmaster_Id=". $SM_Id);
} else {
    header("Location: https://dvsl.in/schoolzone2021/student/existingstudent/auth/login.php");
}
?>