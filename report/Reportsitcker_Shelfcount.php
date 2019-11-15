<?php
require('fpdf.php');
require('../connect/connect.php');
require('Class.php');
header('Content-Type: text/html; charset=utf-8');
date_default_timezone_set("Asia/Bangkok");
// Date
// $eDate = "2018-06-06";
$eDate = $_GET['eDate'];
$eDate = explode("/",$eDate);
$eDate = $eDate[2].'-'.$eDate[1].'-'.$eDate[0];

$dept = $_GET['dept'];

$language = $_GET['lang'];
if($language=="en"){
  $language = "en";
}else{
  $language = "th";
}
header ('Content-type: text/html; charset=utf-8');
$xml = simplexml_load_file('../xml/report_lang.xml');
$json = json_encode($xml);
$array = json_decode($json,TRUE);

class PDF extends FPDF
{
  function setTable($pdf,$header,$data,$width,$numfield,$field)
  {
    $field = explode(",",$field);
    // Column widths
    $w = $width;
    // Header
    $this->SetFont('THSarabun','b',16);
    $count = 0;
    $image1 = "../img/mhee1.png";
    $image2 = "../img/qrcode.png";
    $this->SetFont('THSarabun','',14);
    // $pdf->SetX(2);  
    // $pdf->SetY(20);  
    if(is_array($data)){
       $pdf->SetY(10);   
      foreach($data as $data=>$inner_array){
      $pdf->SetFont('THSarabun','b',14);
      $pdf->Cell(50,7,iconv("UTF-8","TIS-620",$inner_array[$field[2]]),0,1,'L');
      $pdf->SetFont('THSarabun','b',12);
      $pdf->Cell(50,5,iconv("UTF-8","TIS-620",'60 x 120 CM.'),0,1,0);
      $pdf->Cell(25,5,iconv("UTF-8","TIS-620",$inner_array[$field[5]].' ชิ้น'),0,0,'L');
      $pdf->Cell(25,5,iconv("UTF-8","TIS-620",$inner_array[$field[1]]),0,1,'R');
      $pdf->Cell(25,5,iconv("UTF-8","TIS-620",'ผู้จัด: หมี อิอิ'),0,0,'L');
      $pdf->Cell(25,5,iconv("UTF-8","TIS-620",'ผู้ตรวจ: หมี อิอิ'),0,1,'R');      
      $pdf->SetFont('THSarabun','b',12);
      // $pdf->SetX(55);   
      $pdf->Cell(50,5,iconv("UTF-8","TIS-620"),0,1,'R');
      $pdf->Cell(50,5,iconv("UTF-8","TIS-620"),0,0,'R');

      $pdf->Cell(25,5,$pdf->Image($image2,12, $pdf->GetY(), 15.6 ),0,0,'L');
      $pdf->Cell(25,5,$pdf->Image($image1, 54, $pdf->GetY(), 3.8 ),0,1,'R');
      $pdf->Cell(50,2,iconv("UTF-8","TIS-620"),0,1,'R');
      $pdf->Cell(50,5,iconv("UTF-8","TIS-620"),0,1,'R');
      $pdf->Cell(50,10,iconv("UTF-8","TIS-620"),0,1,'L');
      $count++;
      $pdf->ln(5);

    }
  }
  }

  }
$pdf = new PDF('P','mm',array(70,150));
$font = new Font($pdf);
$data = new Data();
$datetime = new DatetimeTH();

$DocNo = $_GET['DocNo'];

// Using Coding
$pdf->AddPage();

$query = "SELECT
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
          ORDER BY item.ItemName ASC
          ";
// var_dump($query); die;
// Number of column
$numfield = 7;
// Field data (Must match with Query)
$field = "no,ItemCode,ItemName,ParQty,CcQty,TotalQty,UnitName";
// Table header
$header = array($array['no'][$language],$array['itemcode'][$language],$array['itemname'][$language],'Par','Left(Shelf)','Order',$array['unit'][$language]);
// width of column table
$width = array(15,35,45,20,25,20,20);
// Get Data and store in Result
$result = $data->getdata($conn,$query,$numfield,$field);
// Set Table
$pdf->SetFont('THSarabun','b',14);
$pdf->setTable($pdf,$header,$result,$width,$numfield,$field);
$pdf->Ln();
$ddate = date('d_m_Y');
$pdf->Output('I','Report_Stock_'.$ddate.'.pdf');
?>