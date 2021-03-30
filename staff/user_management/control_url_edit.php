<?php
/**
 * Created by PhpStorm.
 * User: hp
 * Date: 05-09-2019
 * Time: 12:31 PM
 */

ini_set( 'display_errors', 1 );
error_reporting( E_ALL );
session_start();
include_once '../../config/database.php';

if ( $_SERVER['REQUEST_METHOD'] == 'POST' ) {

  if ( isset( $_POST['update_c_id'] ) ) {
    $update_c_id = $_POST['update_c_id'];
    $header_name = $_POST['c_head'];
    $url         = $_POST['c_url'];
    $p_id        = $_POST['parent_id'];
    $access_type = $_POST['access_type'];
    $modulelist_id = $_POST['modulelist_id'];

    $update_header = QUERY::run('UPDATE 
                                    `setup_links` AS `dest`,
                                    ( SELECT `Id`, `depth` FROM `setup_links` WHERE `Id` = ? ) AS `src`
                                  SET 
                                    `dest`.`parent` = `src`.`Id`,
                                    `dest`.`depth`  = `src`.`depth` + 1, 
                                    `dest`.`header` = ?,
                                    `dest`.`url` = ? ,
                                    dest.access_type = ?,
                                    dest.modulelist_Id = ?
                                  WHERE
                                    `dest`.`Id` = ? ', [ $p_id, $header_name, $url, $access_type , $modulelist_id , $update_c_id ] );

    if ( $update_header ) echo 'SUCCESS';
    else echo 'ERROR';
  }
  elseif ( isset( $_REQUEST['c_id'] ) ) {
    $update_c_id = $_REQUEST['c_id'];
    $fetch_header_result = QUERY::run( 'SELECT
                                        `child`.`Id`      AS `c_id`,
                                        `child`.`parent`  AS `c_pid`,
                                        `child`.`depth`   AS `c_dep`,
                                        `child`.`header`  AS `c_head`,
                                        `child`.`url`     AS `c_url`,
                                        `parent`.`Id`     AS `p_id`,
                                        `parent`.`parent` AS `p_pid`,
                                        `parent`.`depth`  AS `p_dep`,
                                        `parent`.`header` AS `p_head`,
                                        `parent`.`url`    AS `p_url`,
                                        child.access_type As c_access_type,
                                        child.modulelist_Id As modulelist_Id

                                       FROM
                                        `setup_links` `child`
                                        LEFT JOIN `setup_links` `parent`    ON `parent`.`Id`    = `child`.`parent` 
                                        LEFT JOIN `setup_links` `gran`      ON `gran`.`Id`      = `parent`.`parent`
                                       WHERE
                                         `child`.`Id` = ?', [ $update_c_id ] )->fetch();
    ?>

    <div class="box">
      <div class="box-header">
        <h3 class="box-title">Page/Link Edit</h3>
      </div>
      <div id="actions">
        <div class="box-header">
          <button class="btn btn-danger pull-right"
                  style="border:1px solid #504343; bgcolor:#e7e7e7; font-size:15px; padding:10px;"
                  onclick="getPage('./user_management/control_url.php');">Return
          </button>
        </div>

        <form class="form-horizontal"
              id="update_c_id_edit"
              method="post"
              action="Fees_Header_Edit"
              name="editfeeheader"
              enctype="multipart/form-data"
              onsubmit="return false">

          <div class="form-group">

            <label for="class" class="col-sm-2 control-label hidden" style="font-size:16px">ID</label>
            <div class="col-sm-2 hidden">
              <input type="text"
                     value='<?php echo $fetch_header_result['c_id']; ?>'
                     name="update_c_id"
                     class="form-control"
                     readonly
                     placeholder="">
            </div>
          </div>
          <div class="form-group">

            <label class="col-sm-2 input-sm control-label"
                   for="inputfee_header_name"
                   style="font-size:15px">Page Name</label>
            <div class="col-sm-4">
              <input class="form-control"
                     required
                     type="text"
                     value='<?php echo $fetch_header_result['c_head']; ?>'
                     name="c_head"
                     id="c_head"
                     title="Enter unique page name here">
            </div>
          </div>
          <!--                                                                                            -->
          <div class="form-group docs-buttons">

            <div class="btn-group">

              <div class="col-sm-5">
                <button type="button"
                        class="btn btn-primary"
                        data-method="insertpage"
                        data-option="<?php echo $fetch_header_result['c_url']; ?>"
                        data-target="#c_url"
                        title="Insert Page">

                          <span class="docs-tooltip" data-toggle="tooltip" title="Insert Page">
                            <span>Insert Page</span>
                          </span>
                </button>
              </div>

              <div class="col-sm-5">
                <button type="button"
                        class="btn btn-primary"
                        data-method="insertmenu"
                        data-option="NULL"
                        data-target="#c_url"
                        title="Insert Menu">

                          <span class="docs-tooltip" data-toggle="tooltip" title="Insert Menu">
                            <span>Insert Menu</span>
                          </span>
                </button>
              </div>

            </div>
            <label class="col-sm-2 input-sm control-label"
                   for="inputfee_header_name"
                   style="font-size:15px">File Name</label>
            <div class="col-sm-4">
              <input class="form-control"
                     type="text"
                     value='<?php echo $fetch_header_result['c_url']; ?>'
                     name="c_url"
                     id="c_url"
                     title="Enter unique file name here" />
            </div>
          </div>

          <div class="form-group">
            <label class="col-sm-2 input-sm control-label"
                   for="inputfee_header_name"
                   style="font-size:15px">Parent ID</label>
            <div class="col-sm-4">
              <select class="form-control"
                      required
                      name="parent_id"
                      id="parent_id"
                      title="Select parent page">

                <option value="">--Select--</option>
                <option value="0" class="strong">Root</option>

                <?php
                $pages = QUERY::run( 'SELECT * FROM `setup_links` WHERE `url` IS NULL' );
                foreach ( $pages as $view ) {
                  if ( $fetch_header_result['p_id'] == $view['Id'] )
                    echo '<option value="' . $view['Id'] . '" selected >' . $view['header'] . '</option>';
                  else
                    echo '<option value="' . $view['Id'] . '">' . $view['header'] . '</option>';
                }
                ?>
              </select>
            </div>
          </div>

          <div class="form-group">

          <label class="col-sm-2 input-sm control-label"
                 for="inputheader"
                 style="font-size:15px">Access Type</label>
          <div class="col-sm-4">
            <select class="form-control"
                    required
                    name="access_type"
                    id="access_type"
                    title="Select Access Type">

              <option value="">--Select--</option>
              <option value="1" class="strong" <?php if($fetch_header_result['c_access_type'] == '1'){ echo 'Selected'; } ?> >Super Admin</option>
              <option value="0" class="strong" <?php if($fetch_header_result['c_access_type'] == '0'){ echo 'Selected'; } ?> >Users</option>
              ?>
            </select>

          </div>
          
          </div>


          <div class="form-group">
            <label class="col-sm-2 input-sm control-label"
                   for="inputfee_header_name"
                   style="font-size:15px">Module List</label>
            <div class="col-sm-4">
              <select class="form-control"
                      required
                      name="modulelist_id"
                      id="modulelist_id"
                      title="Select Module List">

                <option value="">--Select--</option>
              
                <?php
                $pages = QUERY::run( 'SELECT * FROM `setup_modulelist`' );
                foreach ( $pages as $view ) {
                  if ( $fetch_header_result['modulelist_Id'] == $view['Id'] )
                    echo '<option value="' . $view['Id'] . '" selected >' . $view['modulelist'] . '</option>';
                  else
                    echo '<option value="' . $view['Id'] . '">' . $view['modulelist'] . '</option>';
                }
                ?>
              </select>
            </div>
          </div>



          <div class="form-group">
            <div class="col-sm-offset-2 col-sm-10">
              <button type="submit" class="btn btn-default">Edit</button>
            </div>
          </div>
        </form>
      </div>
    </div>


    <script>
      (() => {
        //debugger;
        var actions = document.getElementById('actions');

        // Methods
        actions.querySelector('.docs-buttons').onclick = function (event) {
          var e      = event || window.event;
          var target = e.target || e.srcElement;
          var input;
          var data;

          while (target !== this) {
            if (target.getAttribute('data-method')) {
              break;
            }
            target = target.parentNode;
          }
          if (target === this || target.disabled || target.className.indexOf('disabled') > -1) {
            return;
          }
          data = {
            method: target.getAttribute('data-method'),
            option: target.getAttribute('data-option') || undefined,
            target: target.getAttribute('data-target'),
            //secondOption: target.getAttribute('data-second-option') || undefined
          };

          if (data.method) {
            if (typeof data.target !== 'undefined') {
              input = document.querySelector(data.target);
              if (!target.hasAttribute('data-option') && data.target && input) {
                try {
                  data.option = input.value;
                }
                catch (e) {
                  console.log(e.message);
                }
              }
            }

            switch (data.method) {
              case 'insertpage':
                try {
                  //data.option = JSON.parse(data.option);
                  document.getElementById('c_url').value = data.option;
                }
                catch (e) {
                  console.log(e.message);
                }
                break;
              case 'insertmenu':
                try {
                  document.getElementById('c_url').value = data.option;
                }
                catch (e) {
                  console.log(e.message);
                }
                break;
            }
          }
        }
      })();

      $('#update_c_id_edit').submit(function (event) {
        $("#loader").css("display", "block");
        $("#DisplayDiv").css("display", "none");
        $.ajax({
          url: './user_management/control_url_edit.php',
          type: 'POST',
          dataType: 'html',   //expect return data as html from server
          data: $('#update_c_id_edit').serialize(),

          success: function (response, textStatus, jqXHR) {

            if (response == 'SUCCESS')
              alert('Edit Success');
            else
              alert('Edit Error');

            jQuery.ajax({
              url: './user_management/control_url.php',
              type: 'GET',
              dataType: 'html',

              success: function (response, textStatus, jqXHR) {
                $('#DisplayDiv').html(response);
                $("#loader").css("display", "none");
                $("#DisplayDiv").css("display", "block");
              },
              error: function (xhr, textStatus, errorThrown) {
                // console.log();
                $("#loader").css("display", "none");
                $("#DisplayDiv").css("display", "block");
              }
            });
          },
          error: function (jqXHR, textStatus, errorThrown) {
            console.log('error(s):' + textStatus, errorThrown);
          }
        });
      });

      function cancel() {
        $("#loader").css("display", "block");
        $("#DisplayDiv").css("display", "none");

        jQuery.ajax({
          url: './user_management/control_url.php',
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
    <?php

  }
}
?>
