<?php
require('fpdf.php');
require('connect.php');
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
  function Header()
  {
    $datetime = new DatetimeTH();
    $printdate = date('d')." ".$datetime->getTHmonth(date('F'))." พ.ศ. ".$datetime->getTHyear(date('Y'));
    $edate = $eDate[0]." ".$datetime->getTHmonthFromnum($eDate[1])." พ.ศ. ".$datetime->getTHyear($eDate[2]);

    if($this->page==1){
      // Move to the right
      $this->SetFont('THSarabun','',10);
      $this->Cell(190,10,iconv("UTF-8","TIS-620","วันที่พิมพ์รายงาน ".$printdate),0,0,'R');
      $this->Ln(5);
      // Title
      $this->SetFont('THSarabun','b',20);
      $this->Cell(80);
      $this->Cell(30,10,iconv("UTF-8","TIS-620","สรุปรายการรับผ้าสะอาด Non PPU"),0,0,'C');
      $this->Ln(7);
      $this->Cell(190,10,iconv("UTF-8","TIS-620","โรงพยาบาล กรุงเทพ ศูนย์วิจัย"),0,0,'C');
      $this->Ln(10);

    }else{
      // Line break
      $this->Ln(7);
    }
  }



  // Page footer
  function Footer()
  {
      // Position at 1.5 cm from bottom
      $this->SetY(-15);
      // Arial italic 8
      $this->SetFont('THSarabun','i',9);
      // Page number
      $this->Cell(0,10,iconv("UTF-8","TIS-620",'').$this->PageNo().'/{nb}',0,0,'R');
  }

  }

// *** Prepare Data Resource *** //
// Instanciation of inherited class
$pdf = new PDF();
$font = new Font($pdf);
$data = new Data();
$datetime = new DatetimeTH();
$Sql = "SELECT
        cleaned_linen_weight.date,
        factory.FacName
        FROM
        cleaned_linen_weight
        INNER JOIN factory ON factory.faccode=cleaned_linen_weight.clean_code
        WHERE DATE(cleaned_linen_weight.date) = DATE('2019-07-26')";
$meQuery = mysqli_query($conn,$Sql);
while ($Result = mysqli_fetch_assoc($meQuery)) {
        $DocDate = date('d/m/Y',strtotime($Result['date']));
        $time=date('H:i:s',strtotime($Result['date']));
        $DocDate = explode("/",$DocDate);
        $DocDate = $DocDate[2].'-'.$DocDate[1].'-'.$DocDate[0];
        $printdate = date('d')." ".$datetime->getTHmonth(date('F'))." พ.ศ. ".$datetime->getTHyear(date('Y'));
        $DocDate = $DocDate[0]." ".$datetime->getTHmonthFromnum($DocDate[1])." พ.ศ. ".$datetime->getTHyear($DocDate[2]);
        $faccode = $Result['FacName'];
}
// Using Coding
$pdf->AddPage("P","A4");
$pdf->SetFont('THSarabun','b',11);
$pdf->Cell(100,10,iconv("UTF-8","TIS-620","โรงซัก ".$faccode),0,0,'L');
$pdf->Cell(90,10,iconv("UTF-8","TIS-620","วันที่ส่ง ".$printdate." เวลา " .$time),0,0,'R');
$pdf->Ln(10);
$pdf->SetFont('THSarabun','b',12);
$pdf->Cell(20,20,iconv("UTF-8","TIS-620","No"),1,0,'C');
$pdf->Cell(50,10,iconv("UTF-8","TIS-620","ผ้าจัดเลี้ยง"),1,0,'C');
$pdf->Cell(50,10,iconv("UTF-8","TIS-620","ผ้าม่านมีจีบ"),1,0,'C');
$pdf->Cell(50,10,iconv("UTF-8","TIS-620","เสื้อกาวท์แพทย์"),1,0,'C');
$pdf->Cell(20,20,iconv("UTF-8","TIS-620","รวมเงิน"),1,0,'C');
$pdf->Cell(0,10,'',0,1);

$pdf->Cell(20,10,'',0,0);
$pdf->Cell(25,10,iconv("UTF-8","TIS-620","จำนวน"),1,0,'C');
$pdf->Cell(25,10,iconv("UTF-8","TIS-620","รวมเป็นเงิน"),1,0,'C');
$pdf->Cell(25,10,iconv("UTF-8","TIS-620","จำนวน"),1,0,'C');
$pdf->Cell(25,10,iconv("UTF-8","TIS-620","รวมเป็นเงิน"),1,0,'C');
$pdf->Cell(25,10,iconv("UTF-8","TIS-620","จำนวน"),1,0,'C');
$pdf->Cell(25,10,iconv("UTF-8","TIS-620","รวมเป็นเงิน"),1,1,'C');



$Sql = "SELECT
        cleaned_linen_weight.count_a,
        (cleaned_linen_weight.count_a*cleaned_linen_weight.price_a) as total_a,
        cleaned_linen_weight.count_b,
        (cleaned_linen_weight.count_b*cleaned_linen_weight.price_b) as total_b,
        cleaned_linen_weight.count_c,
        (cleaned_linen_weight.count_c*cleaned_linen_weight.price_c) as total_c
        FROM
        cleaned_linen_weight
        INNER JOIN factory ON factory.faccode=cleaned_linen_weight.clean_code
        WHERE DATE(cleaned_linen_weight.date) = DATE('2019-07-26')";
$meQuery = mysqli_query($conn,$Sql);
$i=1;
$rows=1;
$header=0;
while ($Result = mysqli_fetch_assoc($meQuery)) {
  if ($rows>20) {
      $header++;
    if( $header%22==1) {
      $pdf->SetFont('THSarabun','b',12);
      $pdf->Cell(20,20,iconv("UTF-8","TIS-620","No"),1,0,'C');
      $pdf->Cell(50,10,iconv("UTF-8","TIS-620","ผ้าจัดเลี้ยง"),1,0,'C');
      $pdf->Cell(50,10,iconv("UTF-8","TIS-620","ผ้าม่านมีจีบ"),1,0,'C');
      $pdf->Cell(50,10,iconv("UTF-8","TIS-620","เสื้อกาวท์แพทย์"),1,0,'C');
      $pdf->Cell(20,20,iconv("UTF-8","TIS-620","รวมเงิน"),1,0,'C');
      $pdf->Cell(0,10,'',0,1);

      $pdf->Cell(20,10,'',0,0);
      $pdf->Cell(25,10,iconv("UTF-8","TIS-620","จำนวน"),1,0,'C');
      $pdf->Cell(25,10,iconv("UTF-8","TIS-620","รวมเป็นเงิน"),1,0,'C');
      $pdf->Cell(25,10,iconv("UTF-8","TIS-620","จำนวน"),1,0,'C');
      $pdf->Cell(25,10,iconv("UTF-8","TIS-620","รวมเป็นเงิน"),1,0,'C');
      $pdf->Cell(25,10,iconv("UTF-8","TIS-620","จำนวน"),1,0,'C');
      $pdf->Cell(25,10,iconv("UTF-8","TIS-620","รวมเป็นเงิน"),1,0,'C');
      $pdf->ln();
  }
}
  $pdf->SetFont('THSarabun','',12);
  $total=$Result['total_a']+$Result['total_b']+$Result['total_c'];
  $pdf->Cell(20,10,iconv("UTF-8","TIS-620","$i"),1,0,'C');
  $pdf->Cell(25,10,iconv("UTF-8","TIS-620",number_format($Result['count_a'])),1,0,'C');
  $pdf->Cell(25,10,iconv("UTF-8","TIS-620",number_format($Result['total_a'],2)),1,0,'C');
  $pdf->Cell(25,10,iconv("UTF-8","TIS-620",number_format($Result['count_b'])),1,0,'C');
  $pdf->Cell(25,10,iconv("UTF-8","TIS-620",number_format($Result['total_b'],2)),1,0,'C');
  $pdf->Cell(25,10,iconv("UTF-8","TIS-620",number_format($Result['count_c'])),1,0,'C');
  $pdf->Cell(25,10,iconv("UTF-8","TIS-620",number_format($Result['total_c'],2)),1,0,'C');
  $pdf->Cell(20,10,iconv("UTF-8","TIS-620",number_format($total,2)),1,0,'C');
  $pdf->Ln();
  $i++;
  $rows++;
  $sum_count_a+=$Result['count_a'];
  $sum_total_a+=$Result['total_a'];
  $sum_count_b+=$Result['count_b'];
  $sum_total_b+=$Result['total_b'];
  $sum_count_c+=$Result['count_c'];
  $sum_total_c+=$Result['total_c'];
  $sum_total+=$total;
}
$pdf->Cell(20,10,iconv("UTF-8","TIS-620",""),1,0,'C');
$pdf->Cell(25,10,iconv("UTF-8","TIS-620",number_format($sum_count_a)),1,0,'C');
$pdf->Cell(25,10,iconv("UTF-8","TIS-620",number_format($sum_total_a,2)),1,0,'C');
$pdf->Cell(25,10,iconv("UTF-8","TIS-620",number_format($sum_count_b)),1,0,'C');
$pdf->Cell(25,10,iconv("UTF-8","TIS-620",number_format($sum_total_b,2)),1,0,'C');
$pdf->Cell(25,10,iconv("UTF-8","TIS-620",number_format($sum_count_c)),1,0,'C');
$pdf->Cell(25,10,iconv("UTF-8","TIS-620",number_format($sum_total_c,2)),1,0,'C');
$pdf->Cell(20,10,iconv("UTF-8","TIS-620",number_format($sum_total,2)),1,0,'C');
$pdf->Ln();



// Footer Table

$ddate = date('d_m_Y');
$pdf->Output('I','Report_Clean_'.$ddate.'.pdf');
?>
