<?php
//Session File
include_once '../SessionInfo.php';
//Database File
include_once '../../config/database.php';


use Box\Spout\Reader\ReaderFactory;
use Box\Spout\Writer\WriterFactory;

use Box\Spout\Common\Type;

require_once '../../assets/plugins/spout-2.4.3/src/Spout/Autoloader/autoload.php';


//-----------------------------------------------------------------------------------------------------------------------
if(isset($_GET['Add_FeeMasterInstance'])){
   
    extract($_POST);

    $ActiveStaffLogin_Id = $_SESSION['schoolzone']['ActiveStaffLogin_Id'];
    $SectionMaster_Id = $_SESSION['schoolzone']['SectionMaster_Id'];

    $Inserting_StaffQualification = mysqli_query($mysqli,"Insert into fee_feemaster
    (sectionmaster_Id, feeheader_Id, Gender, applicable_to, freeship, Amount, feestructure_Id, batchmaster_Id, bankaccountmaster_Id) 
    Values
    ('$SectionMaster_Id', '".htmlspecialchars($add_feeheader_Id, ENT_QUOTES)."', '".htmlspecialchars($add_Gender, ENT_QUOTES)."', '".htmlspecialchars($add_applicable_to, ENT_QUOTES)."', '".htmlspecialchars($add_freeship, ENT_QUOTES)."', '$add_Amount', '$add_feestructure_Id', '$add_batchmasterid', '$add_bankaccountmaster_Id')");


    
    $res['status'] = 'success';
    echo json_encode($res);

    
}
//-----------------------------------------------------------------------------------------------------------------------

//-----------------------------------------------------------------------------------------------------------------------
if(isset($_GET['Edit_FeeMasterInstance'])){

    extract($_POST);


    $updating_CalenderInstance = mysqli_query($mysqli,"Update fee_feemaster Set 
    feeheader_Id = '".htmlspecialchars($edit_feeheader_Id, ENT_QUOTES)."',
    Gender='".htmlspecialchars($edit_Gender, ENT_QUOTES)."',
    applicable_to='".htmlspecialchars($edit_applicable_to, ENT_QUOTES)."',
    freeship='".htmlspecialchars($edit_freeship, ENT_QUOTES)."',
    Amount='".htmlspecialchars($edit_Amount, ENT_QUOTES)."',
    feestructure_Id='".htmlspecialchars($edit_feestructure_Id, ENT_QUOTES)."',
    batchmaster_Id='".htmlspecialchars($edit_batchmasterid, ENT_QUOTES)."',
    bankaccountmaster_Id = '".htmlspecialchars($edit_bankaccountmaster_Id, ENT_QUOTES)."'
    where Id  = '$edit_InstanceId'");

    echo "200";
    
}
//-----------------------------------------------------------------------------------------------------------------------


//-----------------------------------------------------------------------------------------------------------------------
if(isset($_GET['Delete_FeeMasterInstance'])){

    extract($_POST);

    $deleting_formheader = mysqli_query($mysqli,"DELETE FROM fee_feemaster where Id = '$delete_instance_Id'");

    echo "200";
    
}
//-----------------------------------------------------------------------------------------------------------------------


//-----------------------------------------------------------------------------------------------------------------------
if(isset($_GET['Add_FeeMasterInstance_InBulk'])){

    $SectionMaster_Id = $_SESSION['schoolzone']['SectionMaster_Id'];  
    
    $batch_sel_import = $_POST['batch_sel_import'];

    // check file name is not empty
    $pathinfo = pathinfo($_FILES["upload_file"]["name"]); //file extension

    $folderMap = '../';
    $fileLocation = "FileUploadLogs/FeeMaster_".$SectionMaster_Id."_".date("Y-m-d-h-i-s").'.xlsx';
    $targetfolder =  $folderMap."".$fileLocation;
    move_uploaded_file($_FILES['upload_file']['name'], $targetfolder);

    $writer = WriterFactory::create(Type::XLSX); // for XLSX files

    $fileName= $targetfolder;
    $writer->openToFile($fileName); // write data to a file or to a PHP stream
    // $writer->openToBrowser($fileName); // stream data directly to the browser

    $singleRow =['Sr No','Fee Header No','Gender','Applicable to','Freeship','Amount','Fee Structure No','Bank Account No','Upload Status'];
    $writer->addRow($singleRow); // add a row at a time





   
    if (($pathinfo['extension'] == 'xlsx' || $pathinfo['extension'] == 'xls') && $_FILES['upload_file']['size'] > 0 ) { //check if file is an excel file && is not empty
        $inputFileName = $_FILES['upload_file']['tmp_name'];  // Temporary file name
        
        // Read excel file by using ReadFactory object.
        $reader = ReaderFactory::create(Type::XLSX);

        // Open file
        $reader->open($inputFileName);


        $count = 1;
        $flag = 0;



        // Number of sheet in excel file
        foreach ($reader->getSheetIterator() as $sheet) {
            // Number of Rows in Excel sheet
            foreach ($sheet->getRowIterator() as $row) {
                // It reads data after header. In the my excel sheet, header is in the first row.
                if ($count > 1) {  if(!empty($row[0])){


//---------------------------------------------------------------------------------------------------------------                  

    
            $Inserting_StaffQualification = mysqli_query($mysqli,"Insert into fee_feemaster
            (sectionmaster_Id, feeheader_Id, Gender, applicable_to, freeship, Amount, feestructure_Id, batchmaster_Id, bankaccountmaster_Id) 
            Values
            ('$SectionMaster_Id', '".htmlspecialchars($row[1], ENT_QUOTES)."', '".htmlspecialchars($row[2], ENT_QUOTES)."', '".htmlspecialchars($row[3], ENT_QUOTES)."', '".htmlspecialchars($row[4], ENT_QUOTES)."', '$row[5]', '$row[6]', '$batch_sel_import', '$row[7]')");

            if(mysqli_error($mysqli)){
                $displayMessage[]  = $row[1].' : Query Failed';
                $uploadMessage = "Query Failed";
            }else{
                $displayMessage[]  = $row[1].' : Fee Master Added';
                $uploadMessage = "Fee Master  Added";    
            }

 

        $multipleRows=[
            [$row[0],$row[1],$row[2],$row[3],$row[4],$row[5],$row[6],$row[7],$uploadMessage],
        ];
        $writer->addRows($multipleRows); // add multiple rows at a time

   
    unset($uploadMessage);
//----------------------------------------------------------------------------------------------------------------------
                   

        }}
        $count++;
        }

        }




        }


        $writer->close();


        $res['UploadedFilePath'] = $fileLocation;
        $res['displayMessage'] = $displayMessage;
        echo json_encode($res);

}
//-----------------------------------------------------------------------------------------------------------------------

