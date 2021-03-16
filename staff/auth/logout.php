<?php
session_start();
include_once 'config.php';


if(isset($_SESSION['schoolzone']['SectionMaster_Id'])) {
    
    $SM_Id = $_SESSION['schoolzone']['SectionMaster_Id'];

    session_destroy();
    unset($_SESSION['schoolzone']['SectionMaster_Id']);
    unset($_SESSION['schoolzone']['ActiveStaffLogin_Id']);


    header("Location: https://dvsl.in/schoolzone2021/staff/auth/login.php?sectionmaster_Id=". $SM_Id);
} else {
    header("Location: https://dvsl.in/schoolzone2021/staff/auth/login.php");
}
?>