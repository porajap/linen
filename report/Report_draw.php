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
  function Header()
  {
    $datetime = new DatetimeTH();
    $eDate = $_GET['eDate'];
    $eDate = explode("/",$eDate);
    // $eDate = $eDate[2].'-'.$eDate[1].'-'.$eDate[0];
    $printdate = date('d-m-Y');
    $edate = $eDate[0]." ".$datetime->getTHmonthFromnum($eDate[1])." พ.ศ. ".$datetime->getTHyear($eDate[2]);

    if($this->page==1){
      // Move to the right
      $this->SetFont('THSarabun','',12);
      $this->Cell(190,10,iconv("UTF-8","TIS-620",$array['printdate'][$language].' '.$printdate),0,0,'R');
      $this->Ln(10);
      // Title
      $this->SetFont('THSarabun','b',21);
      $this->Cell(80);
      $this->Cell(30,10,iconv("UTF-8","TIS-620","Daily Return Request"),0,0,'C');
      // Line break
      $this->Ln(10);
    }else{
      $this->Ln(15);
    }
  }

  // Page footer
  function Footer()
  {
      // Position at 1.5 cm from bottom
      $this->SetY(-15);
      // Arial italic 8
      $this->SetFont('THSarabun','i',13);
      // Page number
      $this->Cell(0,10,iconv("UTF-8","TIS-620",$array['page'][$language]).$this->PageNo().'/{nb}',0,0,'R');
  }

  function setTable($pdf,$header,$data,$width,$numfield,$field)
  {
    $field = explode(",",$field);
    // Column widths
    $w = $width;
    // Header
    $this->SetFont('THSarabun','b',16);
    for($i=0;$i<count($header);$i++)
        $this->Cell($w[$i],7,iconv("UTF-8","TIS-620",$header[$i]),1,0,'C');
    $this->Ln();

    // set Data Details
    $count = 0;
    $this->SetFont('THSarabun','',14);
    if(is_array($data)){
    foreach($data as $data=>$inner_array){
      $this->Cell($w[0],6,iconv("UTF-8","TIS-620",$count+1),1,0,'C');
      $this->Cell($w[1],6,iconv("UTF-8","TIS-620",$inner_array[$field[1]]),1,0,'C');
      $this->Cell($w[2],6,iconv("UTF-8","TIS-620","  ".$inner_array[$field[2]]),1,0,'L');
      $this->Cell($w[3],6,iconv("UTF-8","TIS-620",$inner_array[$field[3]]." "),1,0,'R');
      $this->Cell($w[4],6,iconv("UTF-8","TIS-620",$inner_array[$field[4]]." "),1,0,'R');
      $this->Cell($w[5],6,iconv("UTF-8","TIS-620",$inner_array[$field[5]]." "),1,0,'R');
      $this->Cell($w[6],6,iconv("UTF-8","TIS-620",$inner_array[$field[6]]),1,0,'C');
      $this->Ln();
      $count++;
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

$DocNo = $_GET['DocNo'];

// Using Coding
$pdf->AddPage();


$Sql = "SELECT   
            site.HptName,
            department.DepName,
            draw.DocNo,
            DATE_FORMAT(draw.DocDate,'%d-%m-%Y')AS DocDate,
            draw.Total,
            users.FName,
            TIME(draw.Modify_Date) AS xTime,
            draw.IsStatus
        FROM draw
        INNER JOIN department ON department.DepCode = draw.DepCode
        INNER JOIN site ON department.HptCode = site.HptCode
        INNER JOIN users ON draw.Modify_Code = users.ID
        WHERE draw.DocNo = '$DocNo'";
        // echo $Sql;
$meQuery = mysqli_query($conn,$Sql);
while ($Result = mysqli_fetch_assoc($meQuery)) {
  $HptName = $Result['HptName'];
  $DepName = $Result['DepName'];
  $DocNo = $Result['DocNo'];
  $DocDate = $Result['DocDate'];
  $Total = $Result['Total'];
  $FirstName = $Result['FName'];
  $xTime = $Result['xTime'];
}

$pdf->SetFont('THSarabun','b',16);
$pdf->Cell(15);
$pdf->Cell(22,10,iconv("UTF-8","TIS-620",$array['site'][$language]),0,0,'L');
$pdf->Cell(78,10,iconv("UTF-8","TIS-620",": ".$HptName),0,0,'L');
$pdf->Cell(22,10,iconv("UTF-8","TIS-620",$array['department'][$language]),0,0,'L');
$pdf->Cell(40,10,iconv("UTF-8","TIS-620",": ".$DepName),0,0,'L');
$pdf->Ln();
$pdf->Cell(15);
$pdf->Cell(22,10,iconv("UTF-8","TIS-620",$array['docno'][$language]),0,0,'L');
$pdf->Cell(78,10,iconv("UTF-8","TIS-620",": ".$DocNo),0,0,'L');
$pdf->Cell(22,10,iconv("UTF-8","TIS-620",$array['docdate'][$language]),0,0,'L');
$pdf->Cell(40,10,iconv("UTF-8","TIS-620",": ".$DocDate),0,0,'L');
$pdf->Ln();
$pdf->Cell(15);
$pdf->Cell(22,10,iconv("UTF-8","TIS-620",$array['user'][$language]),0,0,'L');
$pdf->Cell(78,10,iconv("UTF-8","TIS-620",": ".$FirstName." ".$LastName),0,0,'L');
$pdf->Cell(22,10,iconv("UTF-8","TIS-620",$array['time'][$language]),0,0,'L');
$pdf->Cell(40,10,iconv("UTF-8","TIS-620",": ".$xTime),0,0,'L');
$pdf->SetMargins(15,0,0);
$pdf->Ln();
$pdf->Ln();
$query = "SELECT
            draw_detail.ItemCode,
            item.ItemName,
            item_unit.UnitName,
            draw_detail.ParQty,
            draw_detail.CcQty,
            draw_detail.TotalQty
          FROM draw_detail
          INNER JOIN item ON item.ItemCode = draw_detail.ItemCode
          INNER JOIN item_category ON item.CategoryCode = item_category.CategoryCode
          INNER JOIN item_unit ON item.UnitCode = item_unit.UnitCode
          INNER JOIN draw ON draw.DocNo = draw_detail.DocNo
          WHERE draw_detail.DocNo = '$DocNo'
		  GROUP BY item.ItemCode
          ORDER BY item.ItemName ASC  ";
// echo $query;
$numfield = 7;
// Field data (Must match with Query)
$field = "no,ItemCode,ItemName,ParQty,CcQty,TotalQty,UnitName";
// Table header
$header = array($array['no'][$language],$array['itemcode'][$language],$array['itemname'][$language],'Par','Left(Shelf)','Order',$array['unit'][$language]);
// width of column table
$width = array(15,25,55,20,25,20,20);
// Get Data and store in Result
$result = $data->getdata($conn,$query,$numfield,$field);
// Set Table
$pdf->SetFont('THSarabun','b',14);
$pdf->setTable($pdf,$header,$result,$width,$numfield,$field);
$pdf->Ln();


$ddate = date('d_m_Y');
$pdf->Output('I','Report_Stock_'.$ddate.'.pdf');
?>
