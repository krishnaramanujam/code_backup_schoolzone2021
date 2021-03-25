<?php
/**
 * Created by PhpStorm.
 * User: hp
 * Date: 04-09-2019
 * Time: 05:15 PM
 */

ini_set( 'display_errors', 1 );
error_reporting( E_ALL );
session_start();
include_once '../../config/database.php';
?>
<div class="box col-xs-12">
  <div class="box-header">
    <h3 class="box-title">Pages/Links in Index hierarchy</h3>
  </div>

  <div class="box-header">
    <a onclick="add_header()"
       class="floatRTL btn btn-success pull-right marginBottom15 no-print">Add New Page</a>
  </div>

  <div class="box-body table-responsive">
    <table id="header_list" class="table table-hover table-bordered">
      <thead>
      <tr>
        <th width="10%" style="text-align:center">Sr No</th>
        <th width="20%" style="text-align:center">Path</th>
        <th width="15%" style="text-align:center">Menu Header</th>
        <th width="15%" style="text-align:center">File Name</th>
        <th width="15%" style="text-align:center">Access Type</th>
        <th width="10%" style="text-align:center">Edit</th>
        <th width="10%" style="text-align:center">Delete</th>
      </tr>
      </thead>
      <tbody>
      <?php

      $list_index = 1;

      $header_result =
        QUERY::run( "SELECT
                       child.Id AS c_id,
                       if(child.access_type = 1, 'Super Admin', 'Users') As Access_Type,
                       CONCAT
                       (
                         ( CASE WHEN greateQuintic.header   IS NULL THEN '' ELSE CONCAT( '/', greateQuintic.header   ) END ),
                         ( CASE WHEN greatBiquadrate.header IS NULL THEN '' ELSE CONCAT( '/', greatBiquadrate.header ) END ),
                         ( CASE WHEN greateCube.header      IS NULL THEN '' ELSE CONCAT( '/', greateCube.header      ) END ),
                         ( CASE WHEN greateSquare.header    IS NULL THEN '' ELSE CONCAT( '/', greateSquare.header    ) END ),
                         
                         ( CASE WHEN greatgran.header IS NULL THEN '' ELSE CONCAT( '/', greatgran.header   ) END ),
                         ( CASE WHEN gran.header      IS NULL THEN '' ELSE CONCAT( '/', gran.header   ) END ),
                         ( CASE WHEN parent.header    IS NULL THEN '' ELSE CONCAT( '/', parent.header ) END ),
                         
                         '/', child.header
                       ) AS PATH,

                       ( 
                         CASE 
                           WHEN parent.header IS     NULL AND child.url IS NULL THEN '--root--' 
                           WHEN parent.header IS NOT NULL AND child.url IS NULL THEN '-subroot-' 
                           ELSE child.url 
                         END 
                       ) AS c_url,
                       child.header AS c_head  
                     FROM
                       setup_links child
                       LEFT JOIN setup_links parent    ON parent.Id    = child.parent 
                       LEFT JOIN setup_links gran      ON gran.Id      = parent.parent
                       LEFT JOIN setup_links greatgran ON greatgran.Id = gran.parent
                         
                       LEFT JOIN setup_links greateSquare    ON greateSquare.Id    = greatgran.parent 
                       LEFT JOIN setup_links greateCube      ON greateCube.Id      = greateCube.parent
                       LEFT JOIN setup_links greatBiquadrate ON greatBiquadrate.Id = greatBiquadrate.parent
                       LEFT JOIN setup_links greateQuintic   ON greateQuintic.Id   = greateQuintic.parent
                       Where  child.link_user_type = '0'
                     -- yeah, mysql 5 doesn't have self-referential/recursive queries... upgrade to 8... ASAP 
                     -- sorry for this mess, lol 
                     " );
      // fetch fee headers
      foreach ( $header_result as $header )
      {
        echo '
        <tr>
          <td style="vertical - align:middle;text - align:center">' . $list_index++ . '</td>
          <td style="vertical - align:middle;text - align:center">' . $header['PATH'] . '</td>
          <td style="vertical - align:middle;text - align:center">' . $header['c_head'] . '</td>
          <td style="vertical - align:middle;text - align:center">' . $header['c_url'] . '</td>
          <td style="vertical - align:middle;text - align:center">' . $header['Access_Type'] . '</td>
          
          <td style="text-align:center">
                <a onclick="edit_header(' . $header['c_id'] . ')" type="button" class="btn btn-info btn-flat" title="Edit" tooltip><i class="fa fa-pencil"></i></a>
          </td>
          <td style="text-align:center">
                 <a onclick="del_header(' . $header['c_id'] . ')" type="button" class="btn btn-danger btn-flat" title="Delete" tooltip><i class="fa fa-trash-o"></i></a>
          </td>
        </tr>
        ';
      } ?>
      </tbody>
    </table>
  </div>
</div>


<script>

$('#header_list').DataTable( {
    dom: 'Bifrtp',
    bPaginate:false,
    
    buttons: [
    {
        extend: 'excel',
        footer: true,
		title: "Download Format",
		text: 'Download Format',
        exportOptions : {
            columns : ':visible',
            format : {
                header : function (mDataProp, columnIdx) {
            var htmlText = '<span>' + mDataProp + '</span>';
            var jHtmlObject = jQuery(htmlText);
            jHtmlObject.find('div').remove();
            var newHtml = jHtmlObject.text();
            console.log('My header > ' + newHtml);
            return newHtml;
                }
            }
        }
    }
    ]
} );

  $('#header_list').dataTable().yadcf([
    {column_number: 2, filter_match_mode: "exact"}
  ]);


  function edit_header(c_id) {
    $("#loader").css("display", "block");
    $("#DisplayDiv").css("display", "none");

    jQuery.ajax({
      url: './user_management/control_url_edit.php',
      type: 'POST',
      data: {
        c_id: c_id,
      },
      dataType: 'html',

      success: function (response, textStatus, jqXHR) {
        $('#DisplayDiv').html(response);
        $("#loader").css("display", "none");
        $("#DisplayDiv").css("display", "block");
      },
      error: function (xhr, textStatus, errorThrown) {
        // console.log();
        $('#DisplayDiv').html(textStatus.reponseText);
        $("#loader").css("display", "none");
        $("#DisplayDiv").css("display", "block");
      }
    });
  }

  function del_header(c_id) {
    if (confirm('Are you sure you want to delete?')) {

      jQuery.ajax({
        url: './user_management/control_url_delete.php',
        type: 'POST',
        data: {
          c_id: c_id,
        },
        dataType: 'json',

        success: function (data, textStatus, xhr) {
          $("#loader").css("display", "block");
          $("#DisplayDiv").css("display", "none");
          alert(data);
          jQuery.ajax({
            url: "./user_management/control_url.php",
            type: "POST",

            success: function (data) {
              $('#DisplayDiv').html(data);
              $("#loader").css("display", "none");
              $("#DisplayDiv").css("display", "block");
            }
          });
          //  window.location.reload('/superadminallreceipts.php');
        },
        error: function (xhr, textStatus, errorThrown) {
        }
      });
    }
  }

  function add_header() {
    $("#loader").css("display", "block");
    $("#DisplayDiv").css("display", "none");

    jQuery.ajax({
      url: './user_management/control_url_add.php',
      type: 'GET',
      dataType: 'html',

      success: function (response, textStatus, jqXHR) {
        $('#DisplayDiv').html(response);
        $("#loader").css("display", "none");
        $("#DisplayDiv").css("display", "block");
      },
      error: function (xhr, textStatus, errorThrown) {
        // console.log();
        $('#DisplayDiv').html(textStatus.reponseText);
        $("#loader").css("display", "none");
        $("#DisplayDiv").css("display", "block");
      }
    });

  }
</script>
