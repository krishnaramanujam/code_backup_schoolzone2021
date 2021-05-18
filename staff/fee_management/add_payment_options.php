<form id="paymentform">
        <div class="modal fade" tabindex="-1" role="dialog" id="freeshipmodal" data-backdrop="static" data-keyboard="false">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Payment Details</h4>
                </div>
                <div class="modal-body">
                        
                Payment Mode:       
                <label style="margin-left: 50px;">
                    <input type="radio" name="optionsRadios" id="Cash_Options" value="Cash" checked>
                    Cash
                </label>
   
                <label style="margin-left: 50px;">
                    <input type="radio" name="optionsRadios" id="DD_Options" value="DD">
                    DD
                </label>

                <label style="margin-left: 50px;">
                    <input type="radio" name="optionsRadios" id="Card_Options" value="Card">
                    Credit/Debit Card
                </label>

                <label style="margin-left: 50px;">
                    <input type="radio" name="optionsRadios" id="NEFT_Options" value="NEFT">
                    NEFT
                </label>

                <label style="margin-left: 50px;">
                    <input type="radio" name="optionsRadios" id="Cheque_Options" value="Cheque">
                    Cheque
                </label>

                <label style="margin-left: 50px;">
                    <input type="radio" name="optionsRadios" id="UPI_Options" value="UPI">
                    UPI
                </label>

                <div class="cashPanel form-inline paymentOptionPanel">
                    <div class="row">
        
                        <div class="col-md-2" style="text-align:center;">Amount :</div>
                        <div class="col-md-4">
                            <input type="text" class="form-control" id="cash_amt" name="cash_amt" value="" style="border-bottom: 2px solid #19aa6e;" onkeypress="return (event.charCode == 8 || event.charCode == 0) ? null : event.charCode >= 48 && event.charCode <= 57" > 
                        </div>

                    </div><!--DD Row2 Close-->

                </div>

                <div class="UPIPanel form-inline paymentOptionPanel">
                    <div class="row">
        
                        <div class="col-md-2" style="text-align:center;">Amount :</div>
                        <div class="col-md-4">
                            <input type="text" class="form-control" id="upi_amt" name="upi_amt" value="" style="border-bottom: 2px solid #19aa6e;" onkeypress="return (event.charCode == 8 || event.charCode == 0) ? null : event.charCode >= 48 && event.charCode <= 57" > 
                        </div>

                        <div class="col-md-2" style="text-align:center;">Transaction Id :</div>
                        <div class="col-md-4">
                            <input type="text" class="form-control" id="upi_Id" name="upi_Id" value="" style="border-bottom: 2px solid #19aa6e;"> 
                        </div>

                    </div><!--DD Row2 Close-->

                </div>

            <div class="cardPanel form-inline paymentOptionPanel">
                <div class="row">
    
                    <div class="col-md-2" style="text-align:center;">RRN :</div>
                    <div class="col-md-4">
                        <input type="text" class="form-control" id="card_rrn" name="card_rrn" value="" style="margin-right: 60px;border-bottom: 2px solid #19aa6e;" maxlength = "12" placeholder="Enter 12 Digit RRN" onkeypress="return isNumberKey(event)"  >
                    </div>

                    <div class="col-md-2" style="text-align:center;">Authorization Code (APPR) :</div>
                    <div class="col-md-4">
                        <input type="text" class="form-control" id="card_appr" name="card_appr" value="" placeholder="Enter 6 Digit APPR"  maxlength = "6" onkeypress="return isNumberKey(event)"  style="border-bottom: 2px solid #19aa6e;"> 
                    </div>


                </div><!--DD Row2 Close-->

                <div class="row">
                    <div class="col-md-2" style="text-align:center;">Amount :</div>
                    <div class="col-md-4">
                        <input type="text" class="form-control" id="card_amt" name="card_amt" value="" style="margin-right: 60px;border-bottom: 2px solid #19aa6e;"  placeholder="Enter Card Amount" onkeypress='return event.charCode >= 48 && event.charCode <= 57' >
                    </div>

                </div><!--DD Row2 Close-->

            </div>


            <div class="NEFTPanel form-inline paymentOptionPanel">
                <div class="row">
    
                    <div class="col-md-2" style="text-align:center;">Transaction Id :</div>
                    <div class="col-md-4">
                        <input type="text" class="form-control" id="transaction_Id" name="transaction_Id" value="" style="margin-right: 60px;border-bottom: 2px solid #19aa6e;"  placeholder="Enter Transaction Id" onkeypress='return event.charCode >= 48 && event.charCode <= 57'  >
                    </div>

                    <div class="col-md-2" style="text-align:center;">Amount :</div>
                    <div class="col-md-4">
                        <input type="text" class="form-control" id="neft_amt" name="neft_amt" value="" style="margin-right: 60px;border-bottom: 2px solid #19aa6e;"  placeholder="Enter NEFT Amount" onkeypress='return event.charCode >= 48 && event.charCode <= 57' >
                    </div>

                </div><!--DD Row2 Close-->

            </div>

            <div class="ddPanel form-inline paymentOptionPanel">
                <div class="row">
                    <div class="col-md-2" style="text-align:center;">Name Of Bank : </div>
                    
                    <div class="col-md-4">
                        <select name="dd_bankname" id="dd_bankname" class="drop_sel form-control" style="width: 200px;">
                            <option value="">Select</option>
                            <?php $bank_query = mysqli_query($mysqli,"SELECT setup_bankmaster.* FROM setup_bankmaster ORDER BY `bank_name`"); 
                                while($r_bank_query = mysqli_fetch_array($bank_query)){ ?>
                                <option value="<?php echo $r_bank_query['Id']; ?>"><?php echo $r_bank_query['bank_name']; ?></option>
                            <?php } ?>
                        </select>
                    </div>

                    <div class="col-md-2" style="text-align:center;">DD Amount :</div>
                    <div class="col-md-4">
                        <input type="text" class="form-control" id="dd_amt" name="dd_amt" value="" style="border-bottom: 2px solid #19aa6e;" onkeypress="return (event.charCode == 8 || event.charCode == 0) ? null : event.charCode >= 48 && event.charCode <= 57" > 
                    </div>
                </div><!--DD Row1 Close-->

                <div class="row">
                    <div class="col-md-2" style="text-align:center;">DD No :</div>
                    <div class="col-md-4">
                        <input type="text" class="form-control" id="dd_no" name="dd_no" value="" style="margin-right: 60px;border-bottom: 2px solid #19aa6e;" maxlength = "6" minlength = "6" placeholder="Enter DD No" onkeypress='return event.charCode >= 48 && event.charCode <= 57' >
                    </div>

                    <div class="col-md-2" style="text-align:center;">DD Date :</div>
                    <div class="col-md-4">
                        <input type="text" class="form-control" id="dd_date" name="dd_date" value="" placeholder="Enter DD Date" style="border-bottom: 2px solid #19aa6e;"> 
                    </div>

                </div><!--DD Row2 Close-->


            </div><!--DD Panel Close-->


            <div class="ChequePanel form-inline paymentOptionPanel">
                <div class="row">
                    <div class="col-md-2" style="text-align:center;">Name Of Bank : </div>
                    
                    <div class="col-md-4">
                        <select name="cheque_bankname" id="cheque_bankname" class="drop_sel form-control" style="width: 200px;">
                              <option value="">Select</option>
                            <?php $bank_query = mysqli_query($mysqli,"SELECT setup_bankmaster.* FROM setup_bankmaster ORDER BY `bank_name`"); 
                                while($r_bank_query = mysqli_fetch_array($bank_query)){ ?>
                                <option value="<?php echo $r_bank_query['Id']; ?>"><?php echo $r_bank_query['bank_name']; ?></option>
                            <?php } ?>
                        </select>
                    </div>

                    <div class="col-md-2" style="text-align:center;">Cheque Amount :</div>
                    <div class="col-md-4">
                        <input type="text" class="form-control" id="cheque_amt" name="cheque_amt" value="" style="border-bottom: 2px solid #19aa6e;" onkeypress="return (event.charCode == 8 || event.charCode == 0) ? null : event.charCode >= 48 && event.charCode <= 57" > 
                    </div>
                </div><!--DD Row1 Close-->

                <div class="row">
                    <div class="col-md-2" style="text-align:center;">Cheque No :</div>
                    <div class="col-md-4">
                        <input type="text" class="form-control" id="cheque_no" name="cheque_no" value="" style="margin-right: 60px;border-bottom: 2px solid #19aa6e;" maxlength = "6" minlength = "6" placeholder="Enter Cheque No" onkeypress='return event.charCode >= 48 && event.charCode <= 57' >
                    </div>

                    <div class="col-md-2" style="text-align:center;">Cheque Date :</div>
                    <div class="col-md-4">
                        <input type="text" class="form-control" id="cheque_date" name="cheque_date" value="" placeholder="Enter Cheque Date" style="border-bottom: 2px solid #19aa6e;"> 
                    </div>

                </div><!--DD Row2 Close-->

            </div><!--DD Panel Close-->

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="model_save_btn">Save changes</button>
                </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->
</form>
<link rel="stylesheet" href="../extra/plugins/datepicker/datepicker3.css">
<link rel="stylesheet" href="../extra/plugins/daterangepicker/daterangepicker.css">
<script src="../extra/plugins/datepicker/bootstrap-datepicker.js"></script>
<link rel="stylesheet" href="../extra/plugins/datepicker/datepicker3.css">

<script>

$('.ddPanel').hide();
$('.cardPanel').hide();
$('.NEFTPanel').hide();
$('.ChequePanel').hide();
$('.UPIPanel').hide();
$("input[name='optionsRadios']:radio").change(function(){

    if($(this).val() == 'DD') {
        $('.ddPanel').show();
        $('.cardPanel').hide();
        $('.NEFTPanel').hide();
        $('.ChequePanel').hide();
        $('.cashPanel').hide();
        $('.UPIPanel').hide();
    }
    else if($(this).val() == 'Card') {
        $('.cardPanel').show();
        $('.ddPanel').hide();
        $('.UPIPanel').hide();
        $('.NEFTPanel').hide();
        $('.ChequePanel').hide();
        $('.cashPanel').hide();
    }
    else if($(this).val() == 'NEFT') {
        $('.cardPanel').hide();
        $('.ddPanel').hide();
        $('.NEFTPanel').show();
        $('.ChequePanel').hide();
        $('.cashPanel').hide();
        $('.UPIPanel').hide();
    }

    else if($(this).val() == 'Cheque') {
        $('.cardPanel').hide();
        $('.ddPanel').hide();
        $('.NEFTPanel').hide();
        $('.ChequePanel').show();
        $('.cashPanel').hide();
        $('.UPIPanel').hide();
    }
    else if($(this).val() == 'UPI') {
        $('.cardPanel').hide();
        $('.ddPanel').hide();
        $('.NEFTPanel').hide();
        $('.ChequePanel').hide();
        $('.cashPanel').hide();
        $('.UPIPanel').show();
    }
    else {
        $('.ddPanel').hide();
        $('.cardPanel').hide();
        $('.NEFTPanel').hide();
        $('.ChequePanel').hide();
        $('.cashPanel').show();
        $('.UPIPanel').hide();
    }

});



$('#model_save_btn').click(function(e){

    var RadioOptionCheck = $('input[name=optionsRadios]:checked', '#paymentform').val(); 

    var amountOfRows = $(".payment_table_body tr").length;

    if(RadioOptionCheck == 'Cash') {
        if($('#cash_amt').val() == ''){
            iziToast.warning({
                title: 'Warning',
                message: 'All Fields are mandatory',
            });
            return false;
        }

        //Fields
        var cash_amt = $('#cash_amt').val();
        var PaymentType = 'Cash';
        var PaymentType_Id = '1';

        var bankmaster_Id = '';
        var bankmaster_name = '';
        var instrument_no = '';
        var instrument_date = '';
        var rrn = '';
        var appr = '';
    }//close cash

    if(RadioOptionCheck == 'DD') {
        if($('#dd_amt').val() == '' || $('#dd_bankname').val() == '' || $('#dd_no').val() == '' || $('#dd_date').val() == ''){
            iziToast.warning({
                title: 'Warning',
                message: 'All Fields are mandatory',
            });
            return false;
        }

        //Fields
        var cash_amt = $('#dd_amt').val();
        var PaymentType = 'Demand draft';
        var PaymentType_Id = '5';

        var bankmaster_Id = $('#dd_bankname').val();
        var bankmaster_name = $("#dd_bankname option:selected").html();
        var instrument_no = $('#dd_no').val();
        var instrument_date = $('#dd_date').val();
        var rrn = '';
        var appr = '';
    }//close cash

    if(RadioOptionCheck == 'Card') {
        if($('#card_amt').val() == '' || $('#card_rrn').val() == '' || $('#card_appr').val() == ''){
            iziToast.warning({
                title: 'Warning',
                message: 'All Fields are mandatory',
            });
            return false;
        }

        //Fields
        var cash_amt = $('#card_amt').val();
        var PaymentType = 'Credit / Debit Card';
        var PaymentType_Id = '2';

        var bankmaster_Id = '';
        var bankmaster_name = '';
        var instrument_no = '';
        var instrument_date = '';
        var rrn = $('#card_rrn').val();
        var appr = $('#card_appr').val();
    }//close cash


    if(RadioOptionCheck == 'NEFT') {
        if($('#neft_amt').val() == '' || $('#transaction_Id').val() == ''){
            iziToast.warning({
                title: 'Warning',
                message: 'All Fields are mandatory',
            });
            return false;
        }

        //Fields
        var cash_amt = $('#neft_amt').val();
        var PaymentType = 'NEFT';
        var PaymentType_Id = '6';

        var bankmaster_Id = '';
        var bankmaster_name = '';
        var instrument_no = $('#transaction_Id').val();
        var instrument_date = '';
        var rrn = '';
        var appr = '';
    }//close cash

    if(RadioOptionCheck == 'Cheque') {
        if($('#cheque_amt').val() == '' || $('#cheque_bankname').val() == '' || $('#cheque_no').val() == '' || $('#cheque_date').val() == ''){
            iziToast.warning({
                title: 'Warning',
                message: 'All Fields are mandatory',
            });
            return false;
        }

        //Fields
        var cash_amt = $('#cheque_amt').val();
        var PaymentType = 'Cheque';
        var PaymentType_Id = '4';

        var bankmaster_Id = $('#cheque_bankname').val();
        var bankmaster_name = $("#cheque_bankname option:selected").html();
        var instrument_no = $('#cheque_no').val();
        var instrument_date = $('#cheque_date').val();
        var rrn = '';
        var appr = '';
    }//close cash

    if(RadioOptionCheck == 'UPI') {
        if($('#upi_amt').val() == '' || $('#upi_Id').val() == ''){
            iziToast.warning({
                title: 'Warning',
                message: 'All Fields are mandatory',
            });
            return false;
        }

        //Fields
        var cash_amt = $('#upi_amt').val();
        var PaymentType = 'UPI';
        var PaymentType_Id = '7';

        var bankmaster_Id = '';
        var bankmaster_name = '';
        var instrument_no = $('#upi_Id').val();
        var instrument_date = '';
        var rrn = '';
        var appr = '';
    }//close cash


  
    var PaymentObject = { 
        SrNo : amountOfRows + 1,
        PaymentType_Id : PaymentType_Id,
        PaymentType : PaymentType,
        amount : cash_amt,
        bankmaster_Id : bankmaster_Id,
        bankmaster_name : bankmaster_name,
        instrument_no : instrument_no,
        instrument_date : instrument_date,
        rrn: rrn,
        appr : appr
    };

    let html;

    html = "<tr class='tr_clone' id='"+PaymentObject.SrNo+"'>";
    //Payment Mode
    html += "<td><input type='hidden' name='paymentmode_sr[]' id='paymentmode_sr_"+PaymentObject.SrNo+"' value='"+PaymentObject.SrNo+"' readonly><input type='hidden' name='paymentmodeId[]' id='paymentmode_Id_"+PaymentObject.SrNo+"' value='"+PaymentObject.PaymentType_Id+"' readonly>"+PaymentObject.PaymentType+"</td>";
    //Amount
    html += "<td><input type='text' class='paymentamt_all' name='paymentamt[]' id='paymentamt_"+PaymentObject.SrNo+"' value='"+PaymentObject.amount+"' readonly></td>";
    //Bank Name
    html += "<td><input type='hidden' name='bankmaster_Id[]' id='bankmaster_Id_"+PaymentObject.SrNo+"' value='"+PaymentObject.bankmaster_Id+"' readonly>"+PaymentObject.bankmaster_name+"</td>";
    //Instrument No
    html += "<td><input type='text' name='instrument_no[]' id='instrument_no_"+PaymentObject.SrNo+"' value='"+PaymentObject.instrument_no+"' readonly></td>";
    //Instrument Date
    html += "<td><input type='text' name='instrument_date[]' id='instrument_date_"+PaymentObject.SrNo+"' value='"+PaymentObject.instrument_date+"' readonly></td>";
    //RRN
    html += "<td><input type='text' name='rrn[]' id='rrn_"+PaymentObject.SrNo+"' value='"+PaymentObject.rrn+"' readonly></td>";
    //APPR
    html += "<td><input type='text' name='appr[]' id='appr_"+PaymentObject.SrNo+"' value='"+PaymentObject.appr+"' readonly></td>";
    //Remove Button
    html += "<td><button type='button' class='btn btn-default delete_instance_btn' id='1' data-placement='top' title='Delete Fee Instance' data-toggle='tooltip'><span class='glyphicon glyphicon-trash' aria-hidden='true' style='color:#ff3547;'></span></button></td>";
    html += "</tr>";

    $(".payment_table_body").append($(html));

    $('#freeshipmodal').toggle();
    $('#exampleModal').hide();
    $('div').removeClass("modal-backdrop");
    $('div').removeClass("fade in");


}); // close Valdiation
$('#dd_date').datepicker({
            format: "dd/mm/yyyy",
            autoclose: true,
            orientation: "top",
            endDate: "today"

});

$('#cheque_date').datepicker({
        format: "dd/mm/yyyy",
        autoclose: true,
        orientation: "top",
        endDate: "today"
});


</script>

<script
    src="https://code.jquery.com/jquery-2.2.4.min.js"
    integrity="sha256-BbhdlvQf/xTY9gja0Dq3HiwQF8LaCRTXxZKRutelT44="
    crossorigin="anonymous"></script>

