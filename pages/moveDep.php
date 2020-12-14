<?php
session_start();
$Userid = $_SESSION['Userid'];
$TimeOut = $_SESSION['TimeOut'];
$PmID = $_SESSION['PmID'];
$HptCode = $_SESSION['HptCode'];

if ($Userid == "") {
  header("location:../index.html");
}

if (empty($_SESSION['lang'])) {
  $language = 'th';
} else {
  $language = $_SESSION['lang'];
}
require 'updatemouse.php';

header('Content-type: text/html; charset=utf-8');
$xml = simplexml_load_file('../xml/general_lang.xml');
$xml2 = simplexml_load_file('../xml/main_lang.xml');
$json = json_encode($xml);
$array = json_decode($json, TRUE);
$json2 = json_encode($xml2);
$array2 = json_decode($json2, TRUE);

?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Requests</title>
  <?php include_once('../assets/import/css.php'); ?>


</head>

<body>

  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="javascript:void(0)">Create status</a></li>
    <li class="breadcrumb-item active">ย้ายแผนก</li>
  </ol>

  <div class="col-12">
    <div class="row">
      <div class="col-6">
        <div class="card">
          <div class="card-body">
            <div class="row">
              <div class="col-md-12">
                <div class='form-group row'>
                  <label class="col-sm-4 col-form-label">
                    <?php echo $array['side'][$language]; ?>
                  </label>
                  <select class="form-control col-sm-7 icon_select " id="selectSite"></select>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-12">
                <div class='form-group row'>
                  <label class="col-sm-4 col-form-label">
                    <?php echo $array['department'][$language]; ?>
                  </label>
                  <select class="form-control col-sm-7 icon_select " id="selectSite"></select>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-12">
                <div class='form-group row'>
                  <label class="col-sm-4 col-form-label">
                    ย้ายแผนก
                  </label>
                  <input type="text" autocomplete="off" style="font-size:22px;" class="form-control col-sm-7 only1 " id="txtDocDate" placeholder="กรุณากรอกแผนกปลายทาง">
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-12 d-flex justify-content-end pr-5">
                <div class="menuMini mhee1">
                  <div class="circle4 d-flex justify-content-start">
                    <button class="btn">
                      <i class="fas fa-save mr-2 pl-2"></i>
                      <?php echo $array['save'][$language]; ?>
                    </button>
                  </div>
                </div>

              </div>
            </div>

          </div>
        </div>
      </div>

      <div class="col-6">
        <div class="card">
          <div class="card-body">
            <div class="row">
              <div class="col-md-12">
                <div class='form-group row'>
                  <label class="col-sm-4 col-form-label">
                    วันที่เริ่มต้น
                  </label>
                  <select class="form-control col-sm-7 icon_select " id="selectSite"></select>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-12">
                <div class='form-group row'>
                  <label class="col-sm-4 col-form-label">
                    วันที่สิ้นสุด
                  </label>
                  <select class="form-control col-sm-7 icon_select " id="selectSite"></select>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-12">
                <div class='form-group row'>
                  <label class="col-sm-4 col-form-label">
                    ค้นหาเลขที่เอกสาร
                  </label>
                  <input type="text" autocomplete="off" style="font-size:22px;" class="form-control col-sm-7 only1 " id="txtDocDate" placeholder="ค้นหาเลขที่เอกสาร">
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-12 d-flex justify-content-end pr-5">
                <div class="menuMini mhee1">
                  <div class="search_1 d-flex justify-content-start">
                    <button class="btn">
                      <i class="fas fa-search mr-2 pl-2"></i>
                      <?php echo $array['search'][$language]; ?>
                    </button>
                  </div>
                </div>

              </div>
            </div>

          </div>
        </div>
      </div>
    </div>

    <div class="row mt-4">
      <div class="col-12">
        <table class="table table-fixed table-condensed table-striped mt-3" id="tableItem" width="100%" cellspacing="0" role="grid">
          <thead id="theadsum" style="font-size:24px;">
            <tr role="row" id='tr_1'>
              <th nowrap style="width:15%;">วันที่สร้างเอกสาร</th>
              <th nowrap style="width:15%;">เลขที่เอกสาร</th>
              <th nowrap style="width:10%;">ผู้เรียกเก็บผ้า</th>
              <th nowrap style="width:10%;">แผนกต้นทาง</th>
              <th nowrap style="width:10%;">เวลาเรียกเก็บ</th>
              <th nowrap style="width:10%;">ผู้ตอบรับ</th>
              <th nowrap style="width:10%;">ห้องผ้าตอบรับ</th>
              <th nowrap style="width:10%;">สถานะ</th>
              <th nowrap style="width:10%;">ยกเลิก</th>

            </tr>
          </thead>
          <tbody id="tbody" class="nicescrolled" style="font-size:23px;height:300px;">
            <tr >
              <td nowrap style="width:15%;">2020-08-29</td>
              <td nowrap style="width:15%;">DDBHQ229-2020</td>
              <td nowrap style="width:10%;">นาย 123123</td>
              <td nowrap style="width:10%;">ดมยา</td>
              <td nowrap style="width:10%;">14.98.13</td>
              <td nowrap style="width:10%;">นาย 555</td>
              <td nowrap style="width:10%;">19.39.34</td>
              <td nowrap style="width:10%;">on process</td>
              <td nowrap style="width:10%;">cancel</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>



  </div>



  <?php include_once('../assets/import/js.php'); ?>
</body>

</html>