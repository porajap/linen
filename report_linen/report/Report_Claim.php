<?php
require('fpdf.php');
require('connect.php');
require('Class.php');
header('Content-Type: text/html; charset=utf-8');
date_default_timezone_set("Asia/Bangkok");
session_start();
$data=$_SESSION['data_send'];
$HptCode=$data['HptCode'];
$FacCode=$data['FacCode'];
$date1=$data['date1'];
$date2=$data['date2'];
$chk=$data['chk'];
$year=$data['year'];
$format=$data['Format'];
$where='';
//print_r($data);
if($chk == 'one'){
  if ($format == 1) {
    $where =   "WHERE DATE (claim.Docdate) = DATE('$date1')";
    list($year,$mouth,$day) = explode("-", $date1);
    $datetime = new DatetimeTH();
    $date_header ="วันที่ ".$day." ".$datetime->getTHmonthFromnum($mouth) . " พ.ศ. " . $datetime->getTHyear($year);
  }
  elseif ($format = 3) {
      $where = "WHERE  year (claim.DocDate) LIKE '%$date1%'";
      $date_header= "ประจำปี : $date1";
    }
}
elseif($chk == 'between'){
  $where =   "WHERE claim.Docdate BETWEEN '$date1' AND '$date2'";
  list($year,$mouth,$day) = explode("-", $date1);
  list($year2,$mouth2,$day2) = explode("-", $date2);
  $datetime = new DatetimeTH();
  $date_header ="วันที่ ".$day." ".$datetime->getTHmonthFromnum($mouth) . " พ.ศ. " . $datetime->getTHyear($year)." ถึง ".
                "วันที่ ".$day2." ".$datetime->getTHmonthFromnum($mouth2) . " พ.ศ. " . $datetime->getTHyear($year2);

}
elseif($chk == 'month'){
    $where =   "WHERE month (claim.Docdate) = ".$date1;
    $datetime = new DatetimeTH();
    $date_header ="ประจำเดือน : ".$datetime->getTHmonthFromnum($date1) ;

}
elseif ($chk == 'monthbetween') {
  $where =   "WHERE month(claim.Docdate) BETWEEN $date1 AND $date2";
  $datetime = new DatetimeTH();
  $date_header ="ประจำเดือน : ".$datetime->getTHmonthFromnum($date1)." ถึง ".$datetime->getTHmonthFromnum($date2) ;
}

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

Class PDF extends FPDF
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
      $this->Cell(30,10,iconv("UTF-8","TIS-620","รายงานผ้าชำรุดเสียหาย "),0,0,'C');
      // Line break
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

  function setTable($pdf,$header,$data,$width,$numfield,$field)
  {
    $field = explode(",",$field);
    // Column widths
    $w = $width;
    // Header
    $this->SetFont('THSarabun','b',12);
    for($i=0;$i<count($header);$i++)
        $this->Cell($w[$i],10,iconv("UTF-8","TIS-620",$header[$i]),1,0,'C');
    $this->Ln();

    // set Data Details
    $loop = 1;
    $rows=1;
    $new_header= 0;
    $this->SetFont('THSarabun','',12);
    if(is_array($data)){
    foreach($data as $data=>$inner_array){
      $reject=100-(($inner_array[$field[1]]-$inner_array[$field[2]])/$inner_array[$field[1]]*100);
      $repair=100-(($inner_array[$field[1]]-$inner_array[$field[3]])/$inner_array[$field[1]]*100);
      $damaged=100-(($inner_array[$field[1]]-$inner_array[$field[4]])/$inner_array[$field[1]]*100);
      if ($rows>23) {
          $new_header++;
        if( $new_header%24==1) {
          for($i=0;$i<count($header);$i++)
              $this->Cell($w[$i],10,iconv("UTF-8","TIS-620",$header[$i]),1,0,'C');
                  $this->Ln();
      }
    }
      $this->Cell($w[0],10,iconv("UTF-8","TIS-620",$inner_array[$field[0]]),1,0,'C');
      $this->Cell($w[1],10,iconv("UTF-8","TIS-620",$inner_array[$field[1]]),1,0,'R');
      $this->Cell($w[2],10,iconv("UTF-8","TIS-620",$inner_array[$field[2]]),1,0,'R');
      $this->Cell($w[3],10,iconv("UTF-8","TIS-620",$inner_array[$field[3]]),1,0,'R');
      $this->Cell($w[4],10,iconv("UTF-8","TIS-620",$inner_array[$field[4]]),1,0,'R');
      $this->Cell($w[5],10,iconv("UTF-8","TIS-620",$reject),1,0,'R');
      $this->Cell($w[6],10,iconv("UTF-8","TIS-620",$repair),1,0,'R');
      $this->Cell($w[7],10,iconv("UTF-8","TIS-620",$damaged),1,1,'R');

        $rows++;



    }
  }

    // Closing line
    $pdf->Cell(array_sum($w),0,'','T');
  }

  }

// *** Prepare Data Resource *** //
// Instanciation of inherited class
$pdf = new PDF();
$font = new Font($pdf);
$data = new Data();
$datetime = new DatetimeTH();

// Using Coding
$pdf->AddPage("P","A4");

$Sql = "SELECT
        department.HptCode,
        claim.DocDate
        FROM
        claim
        INNER JOIN department ON claim.HptCode = department.HptCode
        $where
        AND claim.HptCode = '$HptCode'
        ";
$meQuery = mysqli_query($conn,$Sql);
while ($Result = mysqli_fetch_assoc($meQuery)) {
        $HptCode = $Result['HptCode'];
}

$pdf->SetFont('THSarabun','b',12);
$pdf->Cell(1);
$pdf->Cell(150,10,iconv("UTF-8","TIS-620","Linen Department site : " .$HptCode),0,0,'L');
$pdf->Cell(ุ60,10,iconv("UTF-8","TIS-620",$date_header),0,0,'R');
$pdf->Ln(10);

$query = "SELECT
          clean_detail.Qty,
          claim_detail.Qty1,
          repair_detail.Qty,
          damage_detail.Qty,
          factory.FacName
          FROM
          claim
					INNER JOIN Department ON claim.HptCode = department.HptCode
          INNER JOIN claim_detail ON claim.DocNo = claim_detail.DocNo
          INNER JOIN factory ON claim.FacCode = factory.FacCode
          INNER JOIN clean ON claim.docno = clean.refdocno
          INNER JOIN clean_detail ON clean.docno = clean_detail.docno
          INNER JOIN Repair ON claim.refdocno = Repair.docno
          INNER JOIN repair_detail ON Repair.docno = repair_detail.docno
          INNER JOIN damage ON claim.refdocno = damage.docno
          INNER JOIN damage_detail ON damage.docno = damage_detail.docno
					$where
					AND department.HptCode='$HptCode';
          ";
// var_dump($query); die;
// Number of column
$numfield = 8;
// Field data (Must match with Query)
$field = "FacName,Qty1,Qty2,Qty3,Qty4, , , ";
// Table header
$header = array('โรงซัก','ผ้าที่รับ(จำนวน)','Reject(จำนวน)','Repair(จำนวน)','Damaged(จำนวน)','Reject(%)','Repair(%)','Damaged(%)');
// width of column table
$width = array(25,25,25,25,25,20,20,25);
// Get Data and store in Result
$result = $data->getdata($conn,$query,$numfield,$field);
// Set Table
$pdf->SetFont('THSarabun','b',10);
$pdf->setTable($pdf,$header,$result,$width,$numfield,$field);
$pdf->Ln();
// Get $totalsum
$totalsum1 = 0;
$totalsum2 = 0;
$totalsum3 = 0;
$totalsum4 = 0;
$totalsum5 = 0;
$totalsum6 = 0;
$totalsum7 = 0;
if(is_array($result)){
  foreach($result as $result=>$inner_array){
    $reject=100-(($inner_array['Qty1']-$inner_array['Qty2'])/$inner_array['Qty1']*100);
    $repair=100-(($inner_array['Qty1']-$inner_array['Qty3'])/$inner_array['Qty1']*100);
    $damaged=100-(($inner_array['Qty1']-$inner_array['Qty4'])/$inner_array['Qty1']*100);

    $totalsum1 += $inner_array['Qty1'];
    $totalsum2 += $inner_array['Qty2'];
    $totalsum3 += $inner_array['Qty3'];
    $totalsum4 += $inner_array['Qty4'];
    $totalsum5 += $reject;
    $totalsum6 += $repair;
    $totalsum7 += $damaged;
  }
}
// Footer Table
$footer = array('รวม',number_format($totalsum1,2),number_format($totalsum2,2),number_format($totalsum3,2),number_format($totalsum4,2),number_format($totalsum5,2),number_format($totalsum6,2),number_format($totalsum7,2));
$pdf->SetFont('THSarabun','b',12);
for($i=0;$i<count($footer);$i++)
  $pdf->Cell($width[$i],7,iconv("UTF-8","TIS-620",$footer[$i]." "),1,0,'R');
$pdf->Ln(8);

$ddate = date('d_m_Y');
$pdf->Output('I','Report_Claim_'.$ddate.'.pdf');
