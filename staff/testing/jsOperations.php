<script>
//Instance ----------------------------------------------------------------------------------------------------
function Javascript_Operations(DataObject){

    switch(DataObject.OperationType) {
        case 'DeleteOperation':
            var DeleteDataObject = DataObject;

            // ========================================================
            if (confirm(DeleteDataObject.alertConfirmMessage)) {

                $("#loader").css("display", "block");
                $("#DisplayDiv").css("display", "none");


                $.ajax({
                    url: DeleteDataObject.RequestData.RequestUrl+'?'+DeleteDataObject.RequestData.UniqueAPIName+'=i',
                    type: DeleteDataObject.RequestData.RequestType,
                    data: DeleteDataObject.RequestData.DataObj,
                    success:function(del_msg){
                        if(del_msg == '200'){
                            
                            $.ajax({
                                url: DeleteDataObject.RedirectData.RedirectUrl,
                                type: DeleteDataObject.RedirectData.RedirectType,
                                data: DeleteDataObject.RedirectData.DataObj,
                                success:function(st_logs){
                                    $('#DisplayDiv').html(st_logs);
                                    $("#loader").css("display", "none");
                                    $("#DisplayDiv").css("display", "block");
                                    iziToast.success({
                                        title: 'Success',
                                        message: DeleteDataObject.DisplayMessage.SuccessMessage,
                                    });
                                },
                            });   

                        }else{
                            $("#loader").css("display", "none");
                            $("#DisplayDiv").css("display", "block");
                            iziToast.error({
                                title: 'Error',
                                message: DeleteDataObject.DisplayMessage.ErrorMessage,
                            });
                        }
                    },
                });
            }
            // ========================================================
            break;

        case 'EditOperation':
            var EditDataObject = DataObject;

            // ========================================================

            $("#loader").css("display", "block");
            $("#DisplayDiv").css("display", "none");


            $.ajax({
                url: EditDataObject.RequestData.RequestUrl+'?'+EditDataObject.RequestData.UniqueAPIName+'=i',
                type: EditDataObject.RequestData.RequestType,
                data: EditDataObject.RequestData.DataObj,
                success:function(edit_msg){
                    if(edit_msg == '200'){
                        
                        $.ajax({
                            url: EditDataObject.RedirectData.RedirectUrl,
                            type: EditDataObject.RedirectData.RedirectType,
                            data: EditDataObject.RedirectData.DataObj,
                            success:function(st_logs){
                                $('#DisplayDiv').html(st_logs);
                                $("#loader").css("display", "none");
                                $("#DisplayDiv").css("display", "block");
                                iziToast.success({
                                    title: 'Success',
                                    message: EditDataObject.DisplayMessage.SuccessMessage,
                                });
                            },
                        });   

                    }else{
                        $("#loader").css("display", "none");
                        $("#DisplayDiv").css("display", "block");
                        iziToast.error({
                            title: 'Error',
                            message: EditDataObject.DisplayMessage.ErrorMessage,
                        });
                    }
                },
            });
            // ========================================================

            break;

        case 'AddOperation':
            var AddDataObject = DataObject;

            // ========================================================

            $("#loader").css("display", "block");
            $("#DisplayDiv").css("display", "none");


            $.ajax({
                url: AddDataObject.RequestData.RequestUrl+'?'+AddDataObject.RequestData.UniqueAPIName+'=i',
                type: AddDataObject.RequestData.RequestType,
                data: AddDataObject.RequestData.DataObj,
                dataType: "json",
                success:function(add_instance_res){
                    if(add_instance_res['status'] == 'success'){
                        
                        $.ajax({
                            url: AddDataObject.RedirectData.RedirectUrl,
                            type: AddDataObject.RedirectData.RedirectType,
                            data: AddDataObject.RedirectData.DataObj,
                            success:function(st_logs){
                                $('#DisplayDiv').html(st_logs);
                                $("#loader").css("display", "none");
                                $("#DisplayDiv").css("display", "block");
                                iziToast.success({
                                    title: 'Success',
                                    message: AddDataObject.DisplayMessage.SuccessMessage,
                                });
                            },
                        });   

                    }else{
                        $("#loader").css("display", "none");
                        $("#DisplayDiv").css("display", "block");
                        iziToast.error({
                            title: 'Error',
                            message: AddDataObject.DisplayMessage.ErrorMessage + " : " + add_instance_res['status'],
                        });
                    }
                },
            });
            // ========================================================

            break;

        case 'RedirectOperation':
            var RedirectDataObject = DataObject;
            // ========================================================
            $("#loader").css("display", "block");
            $("#DisplayDiv").css("display", "none");
            
            $.ajax({
                url: RedirectDataObject.RedirectData.RedirectUrl,
                type: RedirectDataObject.RedirectData.RedirectType,
                data: RedirectDataObject.RedirectData.DataObj,
                success:function(st_logs){
                    $('#DisplayDiv').html(st_logs);
                    $("#loader").css("display", "none");
                    $("#DisplayDiv").css("display", "block");
                },
            });   
            // ========================================================

            break;

        default:
            iziToast.error({
                title: 'Error',
                message: 'Invalid Operation Type Value',
            });
    }//close switch

}// Close 
//Instance Close----------------------------------------------------------------------------------------------------

</script>