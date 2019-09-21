
<?php
header("Content-Type: application/vnd.ms-excel");
header('Content-Disposition: attachment; filename="MyXls.xls"');#ชื่อไฟล์
?>
 <!DOCTYPE html>
 <html lang="en">
 <head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <meta http-equiv="X-UA-Compatible" content="ie=edge">
   <title>Document</title>
 </head>
 <body>
 <table >
    <thead id="theadsum" style="font-size:11px;">
      <tr>tets</tr>
    </thead>
    <tbody id="body_table">
      <tr><td></td></tr>
    </tbody>
  </table>
 </body>
 </html>