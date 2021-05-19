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
    (sectionmaster_Id, feeheader_Id, Gender, applicable_to, freeship, Amount, feestructure_Id, batchmaster_Id, bankaccountmaster_Id, feeheadertype_Id) 
    Values
    ('$SectionMaster_Id', '".htmlspecialchars($add_feeheader_Id, ENT_QUOTES)."', '".htmlspecialchars($add_Gender, ENT_QUOTES)."', '".htmlspecialchars($add_applicable_to, ENT_QUOTES)."', '".htmlspecialchars($add_freeship, ENT_QUOTES)."', '$add_Amount', '$add_feestructure_Id', '$add_batchmasterid', '$add_bankaccountmaster_Id', '$add_feeheadertype_Id')");


    
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
    bankaccountmaster_Id = '".htmlspecialchars($edit_bankaccountmaster_Id, ENT_QUOTES)."',
    feeheadertype_Id = '".htmlspecialchars($edit_feeheadertype_Id, ENT_QUOTES)."'
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

    $singleRow =['Sr No','Fee Header No','Gender','Applicable to','Freeship','Amount','Fee Structure No','Bank Account No','Fee Header Type No','Upload Status'];
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
            (sectionmaster_Id, feeheader_Id, Gender, applicable_to, freeship, Amount, feestructure_Id, batchmaster_Id, bankaccountmaster_Id, feeheadertype_Id) 
            Values
            ('$SectionMaster_Id', '".htmlspecialchars($row[1], ENT_QUOTES)."', '".htmlspecialchars($row[2], ENT_QUOTES)."', '".htmlspecialchars($row[3], ENT_QUOTES)."', '".htmlspecialchars($row[4], ENT_QUOTES)."', '$row[5]', '$row[6]', '$batch_sel_import', '$row[7]', '$row[8]')");

            if(mysqli_error($mysqli)){
                $displayMessage[]  = $row[1].' : Query Failed';
                $uploadMessage = "Query Failed";
            }else{
                $displayMessage[]  = $row[1].' : Fee Master Added';
                $uploadMessage = "Fee Master  Added";    
            }

 

        $multipleRows=[
            [$row[0],$row[1],$row[2],$row[3],$row[4],$row[5],$row[6],$row[7],$row[8],$uploadMessage],
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


//-----------------------------------------------------------------------------------------------------------------------
if(isset($_GET['Add_FeeForNewExistingStudents'])){
   
    extract($_POST);

    $ActiveStaffLogin_Id = $_SESSION['schoolzone']['ActiveStaffLogin_Id'];
    $SectionMaster_Id = $_SESSION['schoolzone']['SectionMaster_Id'];

    $SBM_Id = explode(",",$SBM_Id);

    
    foreach($SBM_Id as $s => $value) {
        //Loop For Selected Header
        foreach($FM_Id as $i => $value) {
            //Fetching Fee Master Details
            $fee_masterdetail_q = mysqli_query($mysqli," SELECT fee_feemaster.*, fee_headermaster.header_name FROM `fee_feemaster` JOIN fee_headermaster ON fee_headermaster.Id = fee_feemaster.feeheader_Id WHERE fee_feemaster.Id = '$FM_Id[$i]'");
            $r_fee_masterdetail = mysqli_fetch_array($fee_masterdetail_q);

            foreach($FSD_Id as $j => $value) {
            
                $receiptdetailamt = $_POST['allocate_amount_'.$FM_Id[$i].'_'.$FSD_Id[$j]];
                $Feemaster_Id = $FM_Id[$i];
                $Feestructuredetail_Id = $FSD_Id[$j];

                $Inserting_StaffQualification = mysqli_query($mysqli,"Insert into fee_receiptsdetails
                (feemaster_Id, feereceipts_Id, Fee_name, amount, feestructuredetails_Id, feeheadertype_Id, feeheader_Id, SBM_Id, receiptheader_Id) 
                Values
                ('$Feemaster_Id', '0', '$r_fee_masterdetail[header_name]', '$receiptdetailamt[0]', '$Feestructuredetail_Id', '$r_fee_masterdetail[feeheadertype_Id]', '$r_fee_masterdetail[feeheader_Id]', '$SBM_Id[$s]' , '$r_fee_masterdetail[receiptheader_Id]')");


                unset($receiptdetailamt); unset($Feemaster_Id); unset($Feestructuredetail_Id);
            } // close header loop
        } // close header loop


        $updating_feeallocationstatus = mysqli_query($mysqli,"Update user_studentbatchmaster Set 
            fee_allocation_status = '1'
            where Id  = '$SBM_Id[$s]'");
    }//close SBM_Id
    

    $res['status'] = 'success';
    echo json_encode($res);

    
}
//-----------------------------------------------------------------------------------------------------------------------


//-----------------------------------------------------------------------------------------------------------------------
if(isset($_GET['Edit_FeeForNewExistingStudents'])){
   
    extract($_POST);

    $ActiveStaffLogin_Id = $_SESSION['schoolzone']['ActiveStaffLogin_Id'];
    $SectionMaster_Id = $_SESSION['schoolzone']['SectionMaster_Id'];

        //Updating Old Fee header to 0
        $updating_feereceipt = mysqli_query($mysqli,"Update fee_receiptsdetails Set 
        feeheadertype_Id = null
            where fee_receiptsdetails.SBM_Id  = '$SBM_Id'");


        foreach($FM_Id as $i => $value) {
            //Fetching Fee Master Details

            //Fetching Fee Master Id
            $fee_master_q = mysqli_query($mysqli,"Select fee_receiptsdetails.feemaster_Id from fee_receiptsdetails Where fee_receiptsdetails.Id = '$FM_Id[$i]'");
            $r_fee_master = mysqli_fetch_array($fee_master_q);

            $fee_masterdetail_q = mysqli_query($mysqli," SELECT fee_feemaster.*, fee_headermaster.header_name FROM `fee_feemaster` JOIN fee_headermaster ON fee_headermaster.Id = fee_feemaster.feeheader_Id WHERE fee_feemaster.Id = '$r_fee_master[feemaster_Id]'");
            $r_fee_masterdetail = mysqli_fetch_array($fee_masterdetail_q);

            foreach($FSD_Id as $j => $value) {
            
                $receiptdetailamt = $_POST['allocate_amount_'.$FM_Id[$i].'_'.$FSD_Id[$j]];
                $Feemaster_Id = $FM_Id[$i];
                $Feestructuredetail_Id = $FSD_Id[$j];

                $Inserting_StaffQualification = mysqli_query($mysqli,"Insert into fee_receiptsdetails
                (feemaster_Id, feereceipts_Id, Fee_name, amount, feestructuredetails_Id, feeheadertype_Id, feeheader_Id, SBM_Id, receiptheader_Id) 
                Values
                ('$Feemaster_Id', '0', '$r_fee_masterdetail[header_name]', '$receiptdetailamt[0]', '$Feestructuredetail_Id', '$r_fee_masterdetail[feeheadertype_Id]', '$r_fee_masterdetail[feeheader_Id]', '$SBM_Id', '$r_fee_masterdetail[receiptheader_Id]')");


                unset($receiptdetailamt); unset($Feemaster_Id); unset($Feestructuredetail_Id);
            } // close header loop
        } // close header loop


        // $updating_feeallocationstatus = mysqli_query($mysqli,"Update user_studentbatchmaster Set 
        //     fee_allocation_status = '1'
        //     where Id  = '$SBM_Id'");
    

    $res['status'] = 'success';
    echo json_encode($res);

    
}
//-----------------------------------------------------------------------------------------------------------------------


//-----------------------------------------------------------------------------------------------------------------------
if(isset($_GET['GenerateReceipt_FeeForNewExistingStudents'])){
   
    extract($_POST);

    $SBM_Id = $_GET['SBM_Id'];
    $Receipt_date = $_GET['Receipt_date'];


    if(!empty($Receipt_date)){
        $RD_date = date( 'Y-m-d', strtotime( str_replace( '/', '-', $Receipt_date ) ) );
    }else{
        $RD_date = '';
    }

    $ActiveStaffLogin_Id = $_SESSION['schoolzone']['ActiveStaffLogin_Id'];
    $SectionMaster_Id = $_SESSION['schoolzone']['SectionMaster_Id'];

    $fee_header_type_Id = '1';
    $time = date("Y-m-d h:m:s");

    try {

        $message = '';

        //Fetching Student Details For Receipt Generation
        $student_details_q = mysqli_query($mysqli, "SELECT user_studentregister.student_name, user_studentregister.student_Id, user_studentbatchmaster.Id AS SBM_Id, setup_batchmaster.Id As BM_Id,user_studentregister.CR_Id, user_studentbatchmaster.applicationDetails_Id As AD_Id, user_studentregister.Id As SR_Id  FROM user_studentbatchmaster JOIN user_studentregister ON user_studentregister.SBM_Id = user_studentbatchmaster.Id JOIN setup_batchmaster ON setup_batchmaster.Id = user_studentbatchmaster.batchMaster_Id  JOIN setup_programmaster ON setup_programmaster.Id = setup_batchmaster.programmaster_Id JOIN setup_streammaster ON setup_streammaster.Id = setup_programmaster.streammaster_Id WHERE  user_studentbatchmaster.Id  = '$SBM_Id'");
        $r_student_details = mysqli_fetch_array($student_details_q);


        mysqli_query( $mysqli, 'SET AUTOCOMMIT = 0' );
        mysqli_query( $mysqli,'SET foreign_key_checks = 0');

        $receipt_number_next = mysqli_query($mysqli, "SELECT MAX( CAST( `fee_receipts`.`receipt_no` AS UNSIGNED INTEGER ) ) AS `receipt_no` FROM `fee_receipts` JOIN setup_batchmaster ON setup_batchmaster.Id = fee_receipts.batchmaster_Id join setup_programmaster on setup_batchmaster.programMaster_Id = setup_programmaster.Id join setup_streammaster on setup_programmaster.streammaster_Id = setup_streammaster.Id  Where setup_streammaster.sectionmaster_Id = '$SectionMaster_Id' AND setup_batchmaster.academicyear_Id = '$Acadmic_Year_ID' AND fee_receipts.feeheadertype = '$fee_header_type_Id' ORDER BY fee_receipts.id DESC LIMIT 1 ");
        $r_next_receipt = mysqli_fetch_array($receipt_number_next);

        //type casting the (string)receipt_no to (integer)receipt_no
        $last_receipt_no = (int)$r_next_receipt['receipt_no'];

        // pading the receipt_no with zero to get receipt number: 0001
        $receipt_no = ++$last_receipt_no;


        //Inserting Fees Reciept
        $inserting_receipts = mysqli_query($mysqli, "Insert into fee_receipts (batchmaster_Id, candidateregister_Id, studentregister_Id, SBM_Id, applicationdetails_Id, Student_name, receipt_no, amount, date_of_payment, feeheadertype, stafflogin_id, timestamp) 
        values 
        ('$r_student_details[BM_Id]', '$r_student_details[CR_Id]', '$r_student_details[SR_Id]', '$SBM_Id', '$r_student_details[AD_Id]', '$r_student_details[student_name]', '$receipt_no', '$overall_total_amount', '$RD_date', '$fee_header_type_Id', '$ActiveStaffLogin_Id', '$time')"); 

        
        if(mysqli_error($mysqli)){
            $message = 'Error(R)'; 
        }

        $receipt_Id = mysqli_insert_id($mysqli);


        // if(isset($fees_details_Id)) {

        //     //Now Update Fee Details Status
        //     foreach($fees_details_Id as $index => $value) {
        //         //Getting Receipt Header Id
        //         if($index == 0){
        //             //Receipt 
        //             $headerId_q = mysqli_query($mysqli, "Select fee_receiptsdetails.receiptheader_Id from fee_receiptsdetails where fee_receiptsdetails.Id = '$fees_details_Id[$index]'");
        //             $r_headerId = mysqli_fetch_array($headerId_q);
        //         }

        //         $Update_fees_status = mysqli_query($mysqli,"Update fee_receiptsdetails set feepaymentstatus = '1' , feeReceipts_Id = '$receipt_Id' Where fee_receiptsdetails.Id = '$fees_details_Id[$index]'");

        //         if(mysqli_error($mysqli)){
        //             $message = 'Error(RD)'; 
        //         }
        //     }// close Foreach

        // }// close FSD_Id


        if(isset($paymentmode_sr)) {

            //Now Update Fee Details Status
            foreach($paymentmode_sr as $index => $value) {

                //INstrument Date
                if(!empty($instrument_date[$index])){
                    $ID_date = date( 'Y-m-d', strtotime( str_replace( '/', '-', $instrument_date[$index] ) ) );
                }else{
                    $ID_date = '';
                }

                $Update_fees_status = mysqli_query($mysqli,"Insert into paymentdetails_master
                (feereceipt_Id, mode_of_payment, date_of_payment, instrument_no, instrument_date, amount, bankmaster_Id, RRN, APPR) values 
                ('$receipt_Id', '$paymentmodeId[$index]', '$RD_date', '$instrument_no[$index]','$ID_date' ,'$paymentamt[$index]','$bankmaster_Id[$index]', '$rrn[$index]', '$appr[$index]')");

                if(mysqli_error($mysqli)){
                    $message = 'Error(PD)'; 
                }else{
                    $PD_Ids[] = mysqli_insert_id($mysqli);
                }    

            }// close Foreach

        }// close FSD_Id


        if(isset($PD_Ids)){
            $format_PD_Id = implode(",",$PD_Ids);
        }else{
            $format_PD_Id = 'NA';
        }
    
        //Ajustment Fees
        if(isset($current_fee_ajust_box) AND $current_fee != '0'){
            $Inserting_ajustment = mysqli_query($mysqli,"Insert into adjustment_fees_master(SR_Id, receipt_Id, paymentdetails_Id, amount) values ('$r_student_details[SR_Id]', '$receipt_Id', '$format_PD_Id', '$current_fee')");
        }
        

        if(empty($message)){

            $updating_Receipt = mysqli_query($mysqli, "Update fee_receipts set receiptheader_Id = '$r_headerId[receiptheader_Id]' Where fee_receipts.Id = '$receipt_Id'");


            mysqli_commit( $mysqli);
            mysqli_query( $mysqli, 'SET foreign_key_checks = 1');
            mysqli_query( $mysqli, 'SET AUTOCOMMIT = 1' );

            $res['status'] = 'success';
            echo json_encode($res);
        }else{

            mysqli_rollback($mysqli);        
            $res['message'] = $message;    
            $res['status'] = 'failed';
            echo json_encode($res);
        }
       
    }// close try
    catch(Exception $e){

        echo $e->getMessage();

        mysqli_rollback($mysqli);

        
        $res['status'] = 'failed';
        echo json_encode($res);

    } // close catch
    

    
}
//-----------------------------------------------------------------------------------------------------------------------
