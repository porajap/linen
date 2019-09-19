<?php
require('fpdf.php');
require('connect.php');
require('Class.php');
 
$pdf=new FPDF();
 

 
//สร้างหน้าเอกสาร
$pdf->AddPage();
 
// กำหนดฟอนต์ที่จะใช้  อังสนา ตัวธรรมดา ขนาด 12
$pdf->SetFont('THSarabun','',12);
// พิมพ์ข้อความลงเอกสาร
$pdf->setXY( 10, 10  );
$pdf->MultiCell( 0  , 0 , iconv( 'UTF-8','cp874' , 'อังสนา ตัวธรรมดา ขนาด 12' ) );
 
$pdf->SetFont('THSarabun','B',16);
$pdf->setXY( 10, 20  );
$pdf->MultiCell( 0  , 0 , iconv( 'UTF-8','cp874' , 'อังสนา ตัวหนา ขนาด 16' )  );
 
$pdf->SetFont('THSarabun','I',24);
$pdf->setXY( 10, 30  );
$pdf->MultiCell( 0  , 0 , iconv( 'UTF-8','cp874' , 'อังสนา ตัวเอียง ขนาด 24' )  );
 
$pdf->SetFont('THSarabun','BI',32);
$pdf->setXY( 10, 40  );
$pdf->MultiCell( 0  , 0 , iconv( 'UTF-8','cp874' , 'อังสนา ตัวหนาเอียง ขนาด 32' )  );
 
$pdf->Output();
?>