<?php

date_default_timezone_set("Asia/Bangkok");
$xDate = date('Y-m-d');
require '../connect/connect.php';

$DocNo = $_GET['DocNo'];
$lang = $_GET['lang'];
$ItemCode = $_GET['ItemCode'];
$TotalQty = $_GET['TotalQty'];
$sendQty = $_GET['sendQty'];
$UserID = $_SESSION['PmID'];
$count = 0;

// $Sql = "SELECT
//   shelfcount_detail.ItemCode,
//   item.ItemName,
//   item_unit.UnitName,
//   shelfcount_detail.ParQty,
//   shelfcount_detail.CcQty,
//   shelfcount_detail.TotalQty,
//   users.FName,
// 	(
// 		SELECT users.FName FROM users WHERE ID = $UserID
// 	) AS UserC
//   FROM item
//   INNER JOIN item_category ON item.CategoryCode = item_category.CategoryCode
//   INNER JOIN item_unit ON item.UnitCode = item_unit.UnitCode
//   INNER JOIN shelfcount_detail ON shelfcount_detail.ItemCode = item.ItemCode
//   INNER JOIN shelfcount ON shelfcount.DocNo = shelfcount_detail.DocNo
// 	INNER JOIN users ON users.ID = shelfcount.Modify_Code
//   WHERE shelfcount_detail.DocNo = '$DocNo' AND shelfcount_detail.ItemCode = '$ItemCode'
//   GROUP BY item.ItemCode
//   ORDER BY item.ItemName ASC ";
// $meQuery = mysqli_query($conn, $Sql);
// while ($Result = mysqli_fetch_assoc($meQuery)) {
//   $DepCode = $Result['DepCode'];
// }

// echo '<br>DocNo : '.$DocNo ;
// echo '<br>ItemCode : '.$ItemCode ;
// echo '<br>TotalQty : '.$TotalQty ;
// echo '<br>sendQty : '.$sendQty ;

// $loop1 = floor($TotalQty/$sendQty);
// for($i=1;$i<=$loop1;$i++){
//   echo '<br>'.$i.'. ItemCode : '. $ItemCode;
//   echo '<br>TotalQty : '. $sendQty;
//   echo '<br><hr>';
// }
// echo "<br>";
// $loop2 = $loop1*$sendQty;
// if($loop2<$TotalQty){
//   $lass = $TotalQty - $loop2;
//   echo '<br>'.$i.'. ItemCode : '. $ItemCode;
//   echo '<br>TotalQty : '. $lass;
//   echo '<br><hr>';
// }



/**
 * @package com.tecnick.tcpdf
 * @abstract TCPDF - Example: 2D barcodes.
 * @author Nicola Asuni
 * @since 2008-03-04
 */

// Include the main TCPDF library (search for installation path).
require_once('../tcpdf/tcpdf.php');

// create new PDF document
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, array(50,45), true, 'UTF-8', false);


$pdf->SetTitle('TCPDF Example 050');

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);
// set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// set some language-dependent strings (optional)
if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
    require_once(dirname(__FILE__).'/lang/eng.php');
    $pdf->setLanguageArray($l);
}

// ---------------------------------------------------------

// NOTE: 2D barcode algorithms must be implemented on 2dbarcode.php class file.

// set font
$pdf->SetFont('helvetica', '', 3);

// - - - - - - - - - - - - - - - - - - - - - - - - - - - - -

// set style for barcode
$style = array(
    'border' => false,
    'vpadding' => 'auto',
    'hpadding' => 'auto',
    'fgcolor' => array(0,0,0),
    'bgcolor' => false, //array(255,255,255)
    'module_width' => 1, // width of a single module in points
    'module_height' => 1 // height of a single module in points
);

$loop1 = floor($TotalQty/$sendQty);
for($i=1;$i<=$loop1;$i++){
  $pdf->AddPage();
  $html = 'itemcode :'.$ItemCode.'';
  $pdf->writeHTML($html, true, false, true, false, '');
  $pdf->write2DBarcode('www.tcpdf.org', 'QRCODE,L', 17,10, 5, 5, $style, 'N');
  // $pdf->Text(20, 25, 'QRCODE L');
  // echo '<br>'.$i.'. ItemCode : '. $ItemCode;
  // echo '<br>TotalQty : '. $sendQty;
  // echo '<br><hr>';
}
$loop2 = $loop1*$sendQty;
if($loop2<$TotalQty){
  // $pdf->AddPage();
  $html = 'itemcode :'.$ItemCode.'';
  $pdf->writeHTML($html, true, false, true, false, '');
  $pdf->write2DBarcode('www.tcpdf.org', 'QRCODE,L', 20, 5, 15, 20, $style, 'N');
  // $pdf->Text(20, 25, 'QRCODE L');
}


//Close and output PDF document
$pdf->Output('example_050.pdf', 'I');

//============================================================+
// END OF FILE
//============================================================+