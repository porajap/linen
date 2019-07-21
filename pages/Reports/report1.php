<?php
// Important: Requires allow_url_include = On in php.ini
include_once("http://localhost:8888/JavaBridge/java/Java.inc"); //http://localhost:8888/JavaBridge/

//try {
//    $system = new Java('java.lang.System');
//
//    // accéder aux propriétés
//    echo 'Java version=' . $system->getProperty('java.version') . ' <br />';
//    echo 'Java vendor=' . $system->getProperty('java.vendor') . '<br />';
//    echo 'OS=' . $system->getProperty('os.name') . ' ' .
//        $system->getProperty('os.version') . ' on ' .
//        $system->getProperty('os.arch') . '<br />';
//
//    // Exemple avec java.util.Date
//    $formater = new Java('java.text.SimpleDateFormat',
//        "EEEE, MMMM dd, yyyy 'at' h:mm:ss a zzzz");
//
//    echo $formater->format(new Java('java.util.Date'));
//} catch (JavaException $ex) {
//    echo "An exception occured: "; echo $ex; echo "<br>\n";
//}

//if(checkJavaExtension() ) {

    try{
        java("java.lang.Class")->forName("com.mysql.jdbc.Driver");
        $driverManager = new JavaClass("java.sql.DriverManager");
        $dbConnection = $driverManager->getConnection("jdbc:mysql://localhost/linen", "root", "A$192dijd");
        try {
            // Open jasper report
            $compileManager = new JavaClass("net.sf.jasperreports.engine.JasperCompileManager");
            $viewer = new JavaClass("net.sf.jasperreports.view.JasperViewer");
            $report = $compileManager->compileReport(realpath("report1.jrxml"));
            // $report = $compileManager->compileReport(realpath("report/report1.jasper"));
            $fillManager = new JavaClass("net.sf.jasperreports.engine.JasperFillManager");
            $params = new java('java.util.HashMap');
            //$params->put("id",'111');
            //$params->put("bulan","12");
            //$params->put("tahun",'2010');
// $emptyDataSource = new Java("net.sf.jasperreports.engine.JREmptyDataSource");
            $jasperPrint = $fillManager->fillReport($report, $params, $dbConnection);
            // $jasperPrint = $fillManager->fillReport("report/report1.jasper", $params, $dbConnection);
            // $pm = $fm->fillReport($report, $params, $conn);
            // $viewer->viewReport($jasperPrint,false);
            /**
            header('Cache-Control: no-cache private');
            header('Content-Description: File Transfer');
            header('Content-Disposition: attachment, filename=report/report.pdf');
            header('Content-Type: application/pdf');
            header('Content-Transfer-Encoding: binary');
             **/
            // We'll be outputting a PDF
            // header('Content-type: application/pdf');
            // It will be called downloaded.pdf
            // header('Content-Disposition: attachment; filename="report/report1.pdf"');
            // The PDF source is in original.pdf
            // readfile('original.pdf');
            $outputPath = realpath(".")."/"."output.pdf";
            java_set_file_encoding("ISO-8859-1");
            $em = java('net.sf.jasperreports.engine.JasperExportManager');
            // $result = $em->exportReportToPdf($jasperPrint);
            // header('Content-Length: ' . strlen( $result ) );
            // echo $result;
            $em-> exportReportToPdfFile($jasperPrint, $outputPath);
            header("Content-type: application/pdf");
            // header('Content-type: application/ms-excel');
            readfile($outputPath);
            unlink($outputPath);
        }catch(JavaException $ex) {
            // ERROR generating Report
            echo "<b>ERROR generating Report:</b><br/> ".$ex->getCause();
            // exit 1;
        }
    }catch(JavaException $ex) {
        echo "<b>ERROR generating Report:</b><br/> ".$ex->getCause();
    }

//}

?>