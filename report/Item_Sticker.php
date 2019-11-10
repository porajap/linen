<?php

date_default_timezone_set("Asia/Bangkok");
$xDate = date('d-m-Y');
require '../connect/connect.php';
session_start();
$lang = $_GET['lang'];
$ItemCode = $_GET['ItemCode'];
$TotalQty = $_GET['TotalQty'];
$sendQty = $_GET['sendQty'];
$UserID = $_SESSION['PmID'];
$count = 0;





// $Sql = "SELECT
//   item.ItemName,
//   item_unit.UnitName,
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
//   GROUP BY item.ItemCode ORDER BY item.ItemName ASC ";
// $meQuery = mysqli_query($conn, $Sql);
// while ($Result = mysqli_fetch_assoc($meQuery)) {
//   $ItemName = $Result['ItemName'];
//   $UserC = $Result['UserC'];
//   $FName = $Result['FName'];
//   $UnitName = $Result['UnitName'];
// }


$Sql="SELECT item.ItemName FROM item WHERE item.ItemCode = '$ItemCode'";
$meQuery = mysqli_query($conn, $Sql);
$Result = mysqli_fetch_assoc($meQuery);
$ItemName = $Result['ItemName'];
/**
 * @package com.tecnick.tcpdf
 * @abstract TCPDF - Example: 2D barcodes.
 * @author Nicola Asuni
 * @since 2008-03-04
 */

// Include the main TCPDF library (search for installation path).
require_once('../tcpdf/tcpdf.php');




// create new PDF document
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, array(45,45), true, 'UTF-8', false);

$pdf->SetTitle('Sticker_Shelfcount');
// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
$pdf->SetMargins(1, 2, 1);
$pdf->SetHeaderMargin(10);
$pdf->SetFooterMargin(10);
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);
// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);



// set some language-dependent strings (optional)
if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
    require_once(dirname(__FILE__).'/lang/eng.php');
    $pdf->setLanguageArray($l);
}

// ---------------------------------------------------------

// set font
$pdf->SetFont('thsarabunnew', '', 13);
$imagex = "../img/mhee1.png";
$image2 = "../img/mhee2.png";
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
// add a page
$loop1 = floor($TotalQty/$sendQty);
for($i=1;$i<=$loop1;$i++){

$pdf->AddPage();
$pdf->SetY(1);
$pdf->SetFont('thsarabunnew', '', 10);
$pdf->Cell(45,0, $xDate, 0, 1, 'L', 0, '', 0);
$pdf->SetY(6);
$pdf->SetX(8);
$pdf->Cell(30, 0,$ItemName. ' '. $sendQty. ' ชิ้น' , 0, 1, 'C', 0, '', 1);
$pdf->SetFont('thsarabunnew', '', 10);
$pdf->Cell(43, 5, $ItemCode , 0, 0, 'C', 0, '', 1);
$pdf->write2DBarcode($ItemCode.','.$sendQty, 'QRCODE,L', 12,13, 22, 22, $style, 'L');
$pdf->SetFont('thsarabunnew', '', 12);
$pdf->SetY(35);
$pdf->Cell(0,0, 'ผู้จัด ' , 0, 0, 'L', 0, '', 1);
$pdf->SetX(7);
$pdf->Cell(36, 1, 'ศรายุธ' , 0, 0, 'L', 0, '', 1);
$pdf->SetX(7);
$pdf->Cell(25, 0, 'ผู้ตรวจ ' , 0, 0, 'R', 0, '', 0);
$pdf->SetX(1);
$pdf->Cell(45, 1, '. . . . . . . . ' , 0, 1, 'R', 0, '', 1);
// $pdf->SetY(0);


// ==================================================================================================
// $pdf->SetY(0);
// $pdf->Cell(52, 4, '         '  , 0, 0, 'L', 0, '', 1);
// $pdf->Cell(25,0,$pdf->Image($imagex,3, 5, 18 ),0,1,'L');
// $pdf->SetFont('thsarabunnew', 'U', 16);
// $pdf->SetX(0);
// $pdf->Cell("",  0, "                                       ", 0, 1, 'L', 0, '', 0);
// $pdf->SetFont('thsarabunnew', '', 14);
// $pdf->Cell(50,  0, $ItemName, 0, 1, 'L', 0, '', 0);
// $pdf->SetY(19);
// $pdf->SetFont('thsarabunnew', '', 14);
// $pdf->Cell(11, 4, $sendQty. ' ชิ้น' , 0, 0, 'L', 0, '', 1);
// $pdf->Cell(30, 4, $ItemCode , 0, 1, 'R', 0, '', 1);
// $pdf->Cell(50,  4, "", 0, 1, 'L', 0, '', 0);


// $pdf->SetY(26);
// $pdf->SetFont('thsarabunnew', '', 14);
// $pdf->SetX(27);
// $pdf->Cell(36, 1, 'ผู้จัด ' , 0, 1, 'L', 0, '', 1);
// $pdf->SetX(27);
// $pdf->SetFont('thsarabunnew', '', 12);
// $pdf->Cell(36, 1, $FName , 0, 1, 'L', 0, '', 1);

// $pdf->SetX(27);
// $pdf->SetFont('thsarabunnew', '', 14);
// $pdf->Cell(38, 1, 'ผู้ตรวจ' , 0, 1, 'L', 0, '', 1);
// $pdf->SetY(42);
// $pdf->SetFont('thsarabunnew', '', 12);
// $pdf->SetX(27);
// $pdf->Cell(38, 1, '. . . . . . . . . .' , 0, 1, 'L', 0, '', 1);

//   // $pdf->lastPage();
//   $pdf->write2DBarcode($ItemCode.','.$sendQty, 'QRCODE,L', 1,23, 26, 26, $style, 'L');
// ==================================================================================================

}
$loop2 = $loop1*$sendQty;
$totallast =$TotalQty - $loop2;
if($loop2<$TotalQty){
  $pdf->AddPage();
  $pdf->SetY(1);
  $pdf->SetFont('thsarabunnew', '', 10);
  $pdf->Cell(45,0, $xDate, 0, 1, 'L', 0, '', 0);
  $pdf->SetY(6);
  $pdf->SetX(8);
  $pdf->Cell(30, 0,$ItemName. ' '. $totallast. ' ชิ้น' , 0, 1, 'C', 0, '', 1);
  $pdf->SetFont('thsarabunnew', '', 10);
  $pdf->Cell(43, 5, $ItemCode , 0, 0, 'C', 0, '', 1);
  $pdf->write2DBarcode($ItemCode.','.$totallast, 'QRCODE,L', 12,13, 22, 22, $style, 'L');
  $pdf->SetFont('thsarabunnew', '', 12);
  $pdf->SetY(35);
  $pdf->Cell(0,0, 'ผู้จัด ' , 0, 0, 'L', 0, '', 1);
  $pdf->SetX(7);
  $pdf->Cell(36, 1, 'ศรายุธ' , 0, 0, 'L', 0, '', 1);
  $pdf->SetX(7);
  $pdf->Cell(25, 0, 'ผู้ตรวจ ' , 0, 0, 'R', 0, '', 0);
  $pdf->SetX(1);
  $pdf->Cell(45, 1, '. . . . . . . . ' , 0, 1, 'R', 0, '', 1);
  }

// ---------------------------------------------------------

//Close and output PDF document
$pdf->Output('Item_Sticker.pdf', 'I');

//============================================================+
// END OF FILE
//============================================================+