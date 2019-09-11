<?php

date_default_timezone_set("Asia/Bangkok");
$xDate = date('Y-m-d');
require '../connect/connect.php';
session_start();
$DocNo = $_GET['DocNo'];
$lang = $_GET['lang'];
$ItemCode = $_GET['ItemCode'];
$TotalQty = $_GET['TotalQty'];
$sendQty = $_GET['sendQty'];
$UserID = $_SESSION['PmID'];
$count = 0;

$Sql = "SELECT
  item.ItemName,
  item_unit.UnitName,
  users.FName,
	(
		SELECT users.FName FROM users WHERE ID = $UserID
	) AS UserC
  FROM item
  INNER JOIN item_category ON item.CategoryCode = item_category.CategoryCode
  INNER JOIN item_unit ON item.UnitCode = item_unit.UnitCode
  INNER JOIN shelfcount_detail ON shelfcount_detail.ItemCode = item.ItemCode
  INNER JOIN shelfcount ON shelfcount.DocNo = shelfcount_detail.DocNo
	INNER JOIN users ON users.ID = shelfcount.Modify_Code
  WHERE shelfcount_detail.DocNo = '$DocNo' AND shelfcount_detail.ItemCode = '$ItemCode'
  GROUP BY item.ItemCode ORDER BY item.ItemName ASC ";
$meQuery = mysqli_query($conn, $Sql);
while ($Result = mysqli_fetch_assoc($meQuery)) {
  $ItemName = $Result['ItemName'];
  $UserC = $Result['UserC'];
  $FName = $Result['FName'];
  $UnitName = $Result['UnitName'];
}



/**
 * @package com.tecnick.tcpdf
 * @abstract TCPDF - Example: 2D barcodes.
 * @author Nicola Asuni
 * @since 2008-03-04
 */

// Include the main TCPDF library (search for installation path).
require_once('../tcpdf/tcpdf.php');

// create new PDF document
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, array(50,48), true, 'UTF-8', false);


$pdf->SetTitle('TCPDF Example 050');

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
$pdf->SetMargins(1, 2, 1);
// $pdf->SetHeaderMargin(10);
// $pdf->SetFooterMargin(10);

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
$pdf->SetFont('thsarabunnew', '', 6);

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
  $html = '<table>	
      <tr width="100%"><td align="left" width="100%" style="font-size:7px;font-weight:bold;">'.$ItemName.'</td></tr>	
      <tr width="100%"><td align="right">'.$ItemCode.'</td></tr>
      <tr width="100%"><td align="left" width="45%">'.$TotalQty.' '.$UnitName.'</td><td align="right" width="55%">'.$ItemCode.'</td></tr>
      <tr width="100%"><td align="right" width="100%"> </td></tr>
      <tr width="100%"><td align="right" width="100%"> </td></tr>
      <tr width="100%"><td align="right" width="100%"> </td></tr>
      <tr width="100%"><td align="right" width="100%"> </td></tr>
      <tr width="100%"><td align="right" width="100%"> </td></tr>
      <tr width="100%"><td align="right" width="100%"> </td></tr>
      <tr width="100%"><td align="right" width="100%"> </td></tr>
      <tr width="100%"><td align="right" width="100%"> </td></tr>
      <tr width="100%"><td align="right" width="100%"> </td></tr>
      <tr width="100%"><td align="right" width="100%"> </td></tr>
      <tr width="100%"><td align="right" width="100%"> </td></tr>
      <tr width="100%"><td align="right" width="100%"><img src="../img/mhee1.png" style="width:100%"></td></tr>
    </table>';
  $pdf->AddPage();
  $pdf->writeHTML($html, true, false, true, false, '');
  $pdf->lastPage();
  // $pdf->write2DBarcode('www.tcpdf.org', 'QRCODE,L', 17,10, 5, 5, $style, 'N');
}
$loop2 = $loop1*$sendQty;
if($loop2<$TotalQty){
  $pdf->AddPage();
  $html = 'itemcode :'.$ItemCode.'';
  $pdf->writeHTML($html, true, false, true, false, '');
  $pdf->write2DBarcode('www.tcpdf.org', 'QRCODE,L', 20, 5, 15, 20, $style, 'N');
}


//Close and output PDF document
$pdf->Output('example_050.pdf', 'I');

//============================================================+
// END OF FILE
//============================================================+