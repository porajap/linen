<?php
require '../connect/connect.php';
session_start();
date_default_timezone_set("Asia/Bangkok");

// Include the main TCPDF library (search for installation path).
require_once('../tcpdf/tcpdf.php');


// $custom_layout = array(50, 48);
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, "mm", array(50, 48), true, 'UTF-8', false);

// set document information
$pdf->SetTitle('TCPDF Example 050');

//Add a custom size  

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
$pdf->SetMargins(1, 1, 1);
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


// set font
$pdf->SetFont('helvetica', '', 11);



$DocNo = $_GET['DocNo'];
$lang = $_GET['lang'];
$UserID = $_SESSION['PmID'];
$count = 0;

// set style for barcode
$style = array(
  'position' => 'B',
  'align' => 'C',
  'stretch' => false,
  'fitwidth' => true,
  'cellfitalign' => '',
  'border' => true,
  'hpadding' => '20',
  'vpadding' => 'auto',
  'fgcolor' => array(0,0,0),
  'bgcolor' => false, //array(255,255,255),
  'text' => true,
  'font' => 'helvetica',
  'fontsize' => 8,
  'stretchtext' => 4
);

$Sql = "SELECT
  shelfcount_detail.ItemCode,
  item.ItemName,
  item_unit.UnitName,
  shelfcount_detail.ParQty,
  shelfcount_detail.CcQty,
  shelfcount_detail.TotalQty
  FROM item
  INNER JOIN item_category ON item.CategoryCode = item_category.CategoryCode
  INNER JOIN item_unit ON item.UnitCode = item_unit.UnitCode
  INNER JOIN shelfcount_detail ON shelfcount_detail.ItemCode = item.ItemCode
  WHERE shelfcount_detail.DocNo = '$DocNo'
  GROUP BY item.ItemCode
  ORDER BY item.ItemName ASC ";
  $meQuery = mysqli_query($conn,$Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    $pdf->AddPage();
    $ItemCode = $Result['ItemCode'];
    $ItemName = $Result['ItemName'];
    $UnitName = $Result['UnitName'];
    $TotalQty = $Result['TotalQty'];
    $dataQR = $DocNo.' '.$ItemCode.' จำนวน/ขนาด: '.$TotalQty.' '.$UnitName;
    $count ++;
    $html = '<table>
      <tr width="100%"><td align="left" style="font-size:12px;font-weight:bold;">'.$ItemName.'</td></tr>
      <tr width="100%"><td align="right">'.$ItemCode.'</td></tr>
      <tr width="100%"><td align="left">'.$TotalQty.' '.$UnitName.'</td><td align="right">'.$ItemCode.'</td></tr>
    </table>';
    $pdf->writeHTML($html, true, false, true, false, '');
    $pdf->SetAutoPageBreak(TRUE, 0);
    $pdf->write2DBarcode($dataQR, 'QRCODE,H', 2, 0, 20, 90, $style);

  }

// -------------------------------------------------------------------
//Close and output PDF document
$pdf->Output('example_050.pdf', 'I');

//============================================================+
// END OF FILE
//============================================================+