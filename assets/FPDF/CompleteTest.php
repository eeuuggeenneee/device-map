<?php
require('class_list.php');

date_default_timezone_set("Asia/Manila");
$date = date('jS M Y');

$cat_id = 0;
if(isset($_POST['SheetID'])){
    $cat_id = $_POST['SheetID'];
}

// Instanciation of inherited class
// $pdf = new PDF(); -----------HxW-----
$pdf = new FPDF('P','mm',array(152,112));
$pdf->AliasNbPages();
$pdf->AddPage();

//------------------------------------//
// $pdf->SetXY(1,5);
// $pdf->Cell(133,0,'',1,0);
$pdf->Ln(1);
// $pdf->Cell(20);
$pdf->SetXY(0,1);

$pdf->SetFont('Helvetica','',8);
$pdf->Cell(28,7,'',0,0, 'L');
$pdf->Cell(56,7,'PHILIPPINE BATTERIES, INC.',0,0, 'C');
$pdf->Cell(28,7,'',0,1, 'L');

$pdf->SetXY(0,4);

$pdf->SetFont('Helvetica','',8);
$pdf->Cell(28,7,'',0,0, 'L');
$pdf->Cell(56,7,'Plates Department',0,0, 'C');
$pdf->Cell(28,7,'',0,1, 'L');

$pdf->SetXY(0,8);

$pdf->SetFont('Helvetica','B',10);
$pdf->Cell(28,7,'',0,0, 'L');
$pdf->Cell(56,7,'GREEN PLATE MONITORING SHEET',0,0, 'C');
$pdf->Cell(28,7,'',0,1, 'L');

$query1 = "SELECT Top 1 sd.DateCreated as 'prodDate', s.SheetNo, s.DateCreated as 'prodDate2', cb.CurringBooth ";
$query1 .= "FROM sheetdetail_tbl sd ";
$query1 .= "JOIN sheet_tbl s ON sd.SheetID = s.SheetID ";
$query1 .= "JOIN curringbooth_tbl cb ON sd.CurringBoothID = cb.CurringBoothID ";
$query1 .= "WHERE s.SheetID = (SELECT SheetID FROM sheet_tbl WHERE SheetNo = '".$cat_id."') order by sd.DateCreated ASC ";
$resultQuery1 = odbc_exec($conn, $query1);
$prodate = "";
$sheetNo = "";
$cb = "";
while($rowQuery1 = odbc_fetch_array($resultQuery1)){
    $prodate = $rowQuery1['prodDate'];
    $sheetNo = $rowQuery1['SheetNo'];
    $cb = $rowQuery1['CurringBooth'];
}

$pdf->SetXY(0,18);

$pdf->SetFont('Helvetica','',8);
$pdf->Cell(9,5,'',0,0, 'L');
$pdf->Cell(22,5,'Production Date:',0,0, 'L');
$pdf->SetFont('Helvetica','B',9);
$pdf->Cell(20,5,date('d-M-y', strtotime($prodate)),0,0, 'L');
$pdf->Cell(10,5,'',0,0, 'C');
$pdf->SetFont('Helvetica','',8);
$pdf->Cell(20,5,'Sheet No.:',0,0, 'R');
$pdf->SetFont('Helvetica','B',9);
$pdf->Cell(22,5,$sheetNo,0,0, 'L');
$pdf->Cell(9,5,'',0,1, 'L');

$pdf->SetXY(0,23);

$pdf->SetFont('Helvetica','',8);
$pdf->Cell(9,5,'',0,0, 'L');
$pdf->Cell(22,5,'Curing Booth:',0,0, 'L');
$pdf->SetFont('Helvetica','B',9);
$pdf->Cell(20,5,$cb,0,1, 'L');

$pdf->SetXY(0,30);

$pdf->SetFont('Helvetica','B',9);
$pdf->Cell(2,5,'',0,0, 'C');
$pdf->Cell(7,5,'SN',1,0, 'C');
$pdf->Cell(20,5,'Line',1,0, 'C');
$pdf->Cell(12,5,'Shift',1,0, 'C');
$pdf->Cell(15,5,'Rack No.',1,0, 'C');
$pdf->Cell(23,5,'Plate Type',1,0, 'C');
$pdf->Cell(20,5,'Oxide Type',1,0, 'C');
$pdf->Cell(11,5,'Result',1,0, 'C');
$pdf->Cell(2,5,'',0,1, 'C');

$query2 = "SELECT l.Line, sd.Shift, sd.RackNo, p.PlateType, o.OxideType ";
$query2 .="FROM sheetdetail_tbl sd ";
$query2 .="JOIN line_tbl l ON sd.LineID = l.LineID ";
$query2 .="JOIN platetype_tbl p ON sd.PlateTypeID = p.PlateTypeID ";
$query2 .="JOIN oxidetype_tbl o ON sd.OxideTypeID = o.OxideTypeID ";
$query2 .="WHERE sd.SheetID = (SELECT SheetID FROM sheet_tbl WHERE SheetNo = '".$cat_id."') and sd.IsActive = 1 and sd.IsDeleted = 0 ";

$resultQuery2 = odbc_exec($conn, $query2);
$setY = 35; 
$SN = 1;

while($rowQuery2 = odbc_fetch_array($resultQuery2)){
    $pdf->SetXY(0,$setY);
    $pdf->SetFont('Helvetica','',8);
    $pdf->Cell(2,4,'',0,0, 'C');
    $pdf->Cell(7,4,$SN,1,0, 'C');
    $pdf->Cell(20,4,$rowQuery2['Line'],1,0, 'C');
    $pdf->Cell(12,4,$rowQuery2['Shift'],1,0, 'C');
    $pdf->Cell(15,4,$rowQuery2['RackNo'],1,0, 'C');
    $pdf->Cell(23,4,$rowQuery2['PlateType'],1,0, 'C');
    $pdf->Cell(20,4,$rowQuery2['OxideType'],1,0, 'C');
    $pdf->Cell(11,4,'',1,0, 'C');
    $pdf->Cell(2,4,'',0,1, 'C');

    $setY = $setY+4;
    $SN++;
}

$file = "../../system_file/".$cat_id.".pdf";
$pdf->Output('F', $file);

echo json_encode($cat_id);

?>