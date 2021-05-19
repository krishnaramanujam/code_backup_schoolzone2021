<?php
//Session File
include_once '../SessionInfo.php';
//Database File
include_once '../../config/database.php';

$R_Id = $_GET['R_Id'];
$SBM_Id = $_GET['SBM_Id'];

if(isset($_GET['R_Id']) AND isset($_GET['SBM_Id'])) {

  //Checking That Receipt Id is valid with section
  $validate_data_q = mysqli_query($mysqli, "SELECT fee_receipts.Id, fee_receipts.SBM_Id FROM `fee_receipts` JOIN user_studentbatchmaster ON user_studentbatchmaster.Id = fee_receipts.SBM_Id JOIN user_studentregister ON user_studentbatchmaster.Id = user_studentregister.SBM_Id WHERE fee_receipts.Id = '$R_Id' AND fee_receipts.SBM_Id = '$SBM_Id' AND user_studentregister.sectionmaster_Id = '$SectionMaster_Id'
  "); 
  $row_validate_data = mysqli_num_rows($validate_data_q);


  if($row_validate_data > 0){
    //Result Found

    //Student Details
    $student_details_q = mysqli_query($mysqli, "SELECT
    user_studentregister.student_name,
    setup_academicyear.academic_year,
    fee_receipts.receipt_no,
    fee_receipts.amount,
    fee_receipts.date_of_payment,
    receipt_header_master.image_path As header_path,
    setup_batchmaster.batch_name,
    GROUP_CONCAT(paymentmode_master.payment_mode,'') As payment_mode,
    user_candidatedetails.photograph As student_photo,
    user_studentregister.student_Id
  FROM
    fee_receipts
  JOIN
    user_studentbatchmaster ON fee_receipts.SBM_Id = user_studentbatchmaster.Id
  JOIN
    user_studentregister ON user_studentregister.Id = user_studentbatchmaster.studentRegister_Id
  JOIN
    setup_batchmaster ON setup_batchmaster.Id = user_studentbatchmaster.batchMaster_Id
  JOIN
    setup_programmaster ON setup_programmaster.Id = setup_batchmaster.programmaster_Id
  JOIN
    setup_streammaster ON setup_streammaster.Id = setup_programmaster.streammaster_Id
  JOIN 
   setup_academicyear ON setup_academicyear.Id = setup_batchmaster.academicyear_Id
  LEFT JOIN
   receipt_header_master ON receipt_header_master.Id = fee_receipts.receiptheader_Id
   JOIN
   paymentdetails_master ON paymentdetails_master.feereceipt_Id = fee_receipts.Id
  JOIN 
   paymentmode_master ON paymentmode_master.Id = paymentdetails_master.mode_of_payment
  JOIN 
   user_candidatedetails ON user_candidatedetails.candidateRegister_Id = fee_receipts.candidateregister_Id
    WHERE 
    fee_receipts.Id = '$R_Id' AND fee_receipts.SBM_Id ='$SBM_Id' ");
    $r_student_details = mysqli_fetch_array($student_details_q);

    $Download_Filename = $r_student_details['receipt_no']."-".$r_student_details['batch_name']."-".$r_student_details['student_name'];

    //Receipt Details 
    
    $get_receipt_details = mysqli_query($mysqli,  "
    SELECT fee_receiptsdetails.*,SUM(fee_receiptsdetails.amount) as Amount FROM fee_receiptsdetails JOIN fee_structure_details ON fee_structure_details.Id = fee_receiptsdetails.feestructuredetails_Id WHERE fee_receiptsdetails.feeReceipts_Id = '$R_Id' AND fee_receiptsdetails.feepaymentstatus = '1' Group BY fee_receiptsdetails.feeheader_Id ORDER BY fee_structure_details.sequence ");

    $row_get_receipt_details = mysqli_num_rows($get_receipt_details);


    //Payment Details 
    $get_payment_details = mysqli_query($mysqli, "SELECT paymentdetails_master.*, paymentmode_master.payment_mode, setup_bankmaster.abbreviation, paymentmode_master.abbreviation As PM_Abbr FROM `paymentdetails_master` JOIN paymentmode_master ON paymentdetails_master.mode_of_payment = paymentmode_master.Id LEFT JOIN setup_bankmaster ON paymentdetails_master.bankmaster_Id = setup_bankmaster.Id WHERE paymentdetails_master.feereceipt_Id = '$R_Id' ");
    $row_get_payment_details = mysqli_num_rows($get_payment_details);

    //Ajustment Fees
    $get_ajustment_details = mysqli_query($mysqli, "Select adjustment_fees_master.* from adjustment_fees_master Where adjustment_fees_master.receipt_Id = '$R_Id' ");
    $row_get_adjust_details = mysqli_num_rows($get_ajustment_details);
    
    echo '
    <!DOCTYPE html>
    <html>
    <head>
      <title>'.$Download_Filename.'</title>
      <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
      <style type="text/css">
        body {
          width: 100%;
          height: 100%;
          margin: 3;
          padding: 0;
          background-color: #FFFFFF;
          font: 26pt "Tahoma"
        }
        * {
          box-sizing: border-box;
          -moz-box-sizing: border-box;
        }
        .page {
          width: 210mm;
          min-height: 148.5mm;
          padding: 20mm;
          margin: 10mm auto;
          border: 1px #D3D3D3 solid;
          border-radius: 5px;
          background: white;
          box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
        }
        .subpage {
          padding: 1cm;
          border: 5px red solid;
          height: 257mm;
          outline: 2cm #FFEAEA solid;
        }
        @page {
          size: A4;
          margin: 1;
          margin-bottom: 0;
        }
        @media print {
          html, body {
            width: 210mm;
            height: 148.5mm;
          }
          .page {
            margin: 0;
            margin-top: 500px;
            border: initial;
            border-radius: initial;
            width: initial;
            min-height: initial;
            box-shadow: initial;
            background: initial;
          }
        }
      </style>
      <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
      
      </head>
      <body style="margin-top: 45px;">
    
    <!--  <table border="0"
           cellspacing="0"
           style="width: 100%;font-family:Arial;font-size:60%; text-align: left;border: thin solid #000000; margin-top:-30px;margin-bottom:0cm; padding: 0px;"
           align="center">-->
      <table border=0 
          cellspacing=\'0\' 
          style=\'width: 100%;
                 font-family:Arial;
                 font-size:65%;
                 /*text-align: left;border: thin solid #000000;*/
                 margin-bottom:0cm;
                 margin-top:-100px;
                 padding: 0px;\' 
          align=\'center\'>
      <tr>
      <th colspan="6" align="center"><p style="font-size: 22px; margin-bottom: 0px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<img src="../images/header_svjc.jpg" style="display: none;"/> 
      ';
    echo '<img src="../../'.$r_student_details['header_path'].'" width="100%" height="150px"/>';
    
    echo '  
      </th>
        </tr>     
      <tr>
        <th colspan="5" align="center"><p style="  margin-bottom:0cm;  margin-top:0.5cm;">
        </th>
      </tr>
      <tr>
        <td style="width:21%;border: thin solid #000000; ">Student Name</td>
        <th style="width:64%;border: thin solid #000000;"
              colspan="3"> ' . strtoupper($r_student_details['student_name']) . '
        </th>
      <td rowspan="7" align="center" style="width: 15%;border: thin solid #000000; color: #444444; text-align: center;">
        <img src="../../'.$r_student_details['header_path'].'" style="width:100px;">
    
        <tr>
        <td valign="top" style="border: thin solid #000000; padding:0px;">Student Id</td>
        <td valign="top"
            style="width:20%;border: thin solid #000000;padding:0px;text-align:left;"> ' . $r_student_details['student_Id'] . '
        </td>
        <td valign="top" style="width:21%;border: thin solid #000000;padding:0px; ">Academic Year</td>
        <td valign="top" style="width:23%;border: thin solid #000000; padding:0px;"> ' . $r_student_details['academic_year'] . '</td>
      </tr>
    
      <tr>
        <td valign="top" style="padding:0px;border: thin solid #000000;">Receipt No</td>
        <th valign="top" style="padding:0px;border: thin solid #000000;text-align:left;"> ' . $r_student_details['receipt_no'] . '</th>
        <td valign="top" style="padding:0px;border: thin solid #000000;">Date</td>
        <td valign="top" style="padding:0px;border: thin solid #000000;"> ' . date('d-F-Y',strtotime($r_student_details['date_of_payment'])) . '</td>
      </tr>

      <tr>
        <td valign="top" style="padding:0px;border: thin solid #000000;">Batch Name</td>
        <td valign="top" style="padding:0px;border: thin solid #000000;" colspan="3"> ' . $r_student_details['batch_name'] . ' </td>
      </tr>
      ';  
          echo '
        </table> ';
        
      echo '
      <table align="center" cellspacing="0" style="padding: 0px;font-size:60%;font-family:Arial; width: 100%;border: thin solid #000000; ">';
    
    
      if($row_get_receipt_details > 0){
        $i = 1;

        if($i == 1){
          echo '<tr>
 
            
            <th align="center" colspan="3">Fee Summary</th>

          </tr>';
        }
    
        
        echo '<tr>'
        . '<th style="width:21%;border: thin solid #000000; padding:0px;">'
        . 'Sr.No'
        . '</th>'
        . '<th align="left" style="width:64%;border: thin solid #000000; padding:0px;">&nbsp;'
        . 'Particulars'
        . '</th>'
        . '<th style="width:15%;border: thin solid #000000; padding:0px;">'
        . 'Amount'
        . '</th>'
        . '</tr>';
    
    
    
        while($r_get_receipt_details = mysqli_fetch_array($get_receipt_details)){
          $app_tot = $app_tot + $r_get_receipt_details['Amount'];
    
          if ( $r_get_receipt_details['Amount'] != 0 ){
    
            echo '<tr>';
            echo ' <td align="center" style="border: thin solid #000000;padding:0px;"> ' . $i++ . ' </td>';
            echo '
                  <th align="left" style="border: thin solid #000000;padding:0px;">&nbsp; ' . $r_get_receipt_details['Fee_name'];
            
            echo '</th>
            <th align="center" style="border: thin solid #000000;padding:0px;"> ' . $r_get_receipt_details['Amount'] . ' </th>
            </tr>';
    
          }
    
        }// close while

        if($row_get_adjust_details > '0'){
          while($r_get_adjust_details  = mysqli_fetch_array($get_ajustment_details)){
            echo '<tr>';
            echo ' <td align="center" style="border: thin solid #000000;padding:0px;"> ' . $i++ . ' </td>';
            echo '
                  <th align="left" style="border: thin solid #000000;padding:0px;">&nbsp; Adjustment Fees' ;
                  
            
            echo '</th>
            <th align="center" style="border: thin solid #000000;padding:0px;"> ' . $r_get_adjust_details['amount'] . ' </th>
            </tr>';
          }
      }

      $app_tot = $r_student_details['amount'];
    
        echo '
        <tr>
            <th style="border: thin solid #000000;padding:0px;">  </th>
            <th align="left" style="border: thin solid #000000;padding:0px;">&nbsp; Total </th>
            <th style="border: thin solid #000000;padding:0px;">' . $app_tot . '  </th>
        </tr>';
    
    
      $number =  $app_tot;
      $no = round($number);
      $point = round($number - $no, 2) * 100;
      $hundred = null;
      $digits_1 = strlen($no);
      $i = 0;
      $str = array();
      $words = array(
        '0' => '',
        '1' => 'one',
        '2' => 'two',
        '3' => 'three',
        '4' => 'four',
        '5' => 'five',
        '6' => 'six',
        '7' => 'seven',
        '8' => 'eight',
        '9' => 'nine',
        '10' => 'ten',
        '11' => 'eleven',
        '12' => 'twelve',
        '13' => 'thirteen',
        '14' => 'fourteen',
        '15' => 'fifteen',
        '16' => 'sixteen',
        '17' => 'seventeen',
        '18' => 'eighteen',
        '19' =>'nineteen',
        '20' => 'twenty',
        '30' => 'thirty',
        '40' => 'forty',
        '50' => 'fifty',
        '60' => 'sixty',
        '70' => 'seventy',
        '80' => 'eighty',
        '90' => 'ninety');
      $digits = array( '', 'hundred', 'thousand', 'lakh', 'crore' );
    
      while ( $i < $digits_1 )
      {
        $divider = ($i == 2) ? 10 : 100;
        $number = floor( $no % $divider );
        $no = floor( $no / $divider );
        $i += ($divider == 10) ? 1 : 2;
        if ( $number )
        {
          $plural = (($counter = count( $str )) && $number > 9) ? 's' : null;
          $hundred = ($counter == 1 && $str[0]) ? ' and ' : null;
          $str [] = ($number < 21) ? $words[$number] . ' ' . $digits[$counter] . $plural . ' ' . $hundred : $words[floor( $number / 10 ) * 10] . ' ' . $words[$number % 10] . ' ' . $digits[$counter] . $plural . ' ' . $hundred;
        }
        else $str[] = null;
      }
      $str = array_reverse( $str );
      $result = implode( '', $str );
    
    
        echo '
        <tr>
            <th align="right" colspan="3" style="border: thin solid #000000;padding:0px;">&#8377(in words) ' . strtoupper($result) . '</th>
        </tr>';
    
            
       
    
      }//close if
    
         echo '
             </tbody>
            </table>
          ';



          echo '
          <table align="center" cellspacing="0" style="padding: 0px;font-size:60%;font-family:Arial; width: 100%;border: thin solid #000000; ">';
        
        
          if($row_get_payment_details > 0){
            $j = 1;
    
            if($j == 1){
              echo '<tr>
                
                <th align="center" colspan="7">Payment Details</th>
                
              </tr>';
            }
        
          
            echo '<tr>'
            . '<th align="center" style="border: thin solid #000000; padding:0px; width: 10%">&nbsp;'
            . 'Mode'
            . '</th>'
            . '<th align="center" style="border: thin solid #000000; padding:0px; width: 15%">&nbsp;'
            . 'Bank Name'
            . '</th>'
            . '<th align="center" style="border: thin solid #000000; padding:0px; width: 10%">&nbsp;'
            . 'Inst. No'
            . '</th>'
            . '<th align="center" style="border: thin solid #000000; padding:0px; width: 10%">&nbsp;'
            . 'Inst. Date'
            . '</th>'
            . '<th align="center" style="border: thin solid #000000; padding:0px; width: 10%">&nbsp;'
            . 'RRN'
            . '</th>'
            . '<th align="center" style="border: thin solid #000000; padding:0px; width: 10%">&nbsp;'
            . 'APPR'
            . '</th>'
            . '<th style="border: thin solid #000000; padding:0px; width: 12.5%">'
            . 'Amount'
            . '</th>'
            . '</tr>';
        
        
        
            while($r_get_payment_details = mysqli_fetch_array($get_payment_details)){
                if(!empty($r_get_payment_details['instrument_date'])){
                  $in_date =  date( 'd-m-Y', strtotime( str_replace( '/', '-', $r_get_payment_details['instrument_date'] ) ) );
                }
        
                echo '<tr>';
                echo '
                <th align="center" style="border: thin solid #000000;padding:0px;">&nbsp; ' . $r_get_payment_details['PM_Abbr'].' </th>
                <th align="center" style="border: thin solid #000000;padding:0px;">&nbsp; ' . $r_get_payment_details['abbreviation'].' </th>
                <th align="center" style="border: thin solid #000000;padding:0px;">&nbsp; ' . $r_get_payment_details['instrument_no'].' </th>
                <th align="center" style="border: thin solid #000000;padding:0px;">&nbsp; ' . $in_date.' </th>
                <th align="center" style="border: thin solid #000000;padding:0px;">&nbsp; ' . $r_get_payment_details['RRN'].' </th>
                <th align="center" style="border: thin solid #000000;padding:0px;">&nbsp; ' . $r_get_payment_details['APPR'].' </th>
                <th align="center" style="border: thin solid #000000;padding:0px;">&nbsp; ' . $r_get_payment_details['amount'].' </th>
                </tr>';
        
       
                unset($in_date);
            }// close while
        
        
        
          }//close if
        
        
        
          echo '
                 </tbody>
                </table>
              ';
    


      echo '            
      <p align=center style="font-size:48%;padding-left:1cm;font-family:Arial; margin:0; width: 100%;">Note: 1. In the case of revision of fees by University, students are liable to pay the difference amount.</p>
      <br>
     <p align=center style="font-size:60%;padding-left:1cm;font-family:Arial; margin:0; width: 100%;">
     This is online generated receipt and does not requires signature.</p>';    

          
      echo '
            <script>
              $( document ).ready(function() {
                window.print();
              //  window.close();
            });
            </script>
            </body>
            </html>
            ';



  }else{
    //Not Result Found
    echo '<html><head></head><body><H1>NOT FOUND</H1></body></html>';
  }
}else{
  echo '<html><head></head><body><H1>NOT FOUND</H1></body></html>';
}