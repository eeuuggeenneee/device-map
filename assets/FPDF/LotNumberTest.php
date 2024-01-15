<?php
require('class_list.php');

date_default_timezone_set("Asia/Manila");
$date = date('jS M Y');

$cat_id = "";
if(isset($_POST['lot_id'])){
    $cat_id = $_POST['lot_id'];
}

// Instanciation of inherited class
// $pdf = new PDF(); -----------HxW-----
$pdf = new FPDF('L','mm',array(112,130));
$pdf->AliasNbPages();
$pdf->AddPage();

//------------------------------------//
// $pdf->SetXY(1,5);
// $pdf->Cell(133,0,'',1,0);
$pdf->Ln(1);
// $pdf->Cell(20);
$pdf->SetFont('Helvetica','B',20);
$pdf->SetXY(0,5);
$pdf->Cell(30,7,'',0,0, 'L');
$pdf->Cell(70,7,'PRODUCTION TAG',0,0, 'C');
$pdf->Cell(30,7,'',0,1, 'L');


$pdf->SetFont('Helvetica','',11);
$pdf->SetXY(0,12);
$pdf->Cell(30,7,'',0,0, 'L');
$pdf->Cell(70,7,'Pasting & Plate Curing',0,0, 'C');
$pdf->Cell(30,7,'',0,1, 'L');

$query = "SELECT QRDataUrl FROM sheetdetailqr ";
$query .= "WHERE SheetDetailID = (SELECT SheetDetailID FROM sheetdetail_tbl WHERE LotNumber = '".$cat_id."') ";
$result = odbc_exec($conn, $query);
confirmQuery($result);
$imageQR = "";
while($rowImage = odbc_fetch_array($result)){
    $imageQR = $rowImage['QRDataUrl'];
}


$pdf->Image("../../QRuploads/".$imageQR, 5, 5, 22);

$pdf->Image("../../QRuploads/".$imageQR, 100, 5, 22);

$pdf->SetFont('Helvetica','B',10);
$pdf->SetXY(2,26);
$pdf->Cell(38,7,$cat_id,0,0, 'L');
$pdf->Cell(57,7,'',0,0, 'C');
$pdf->Cell(36,7,$cat_id,0,1, 'L');


$queryDetail = "SELECT p.PlateType, sd.Quantity, l.Line, sd.RackNo, sd.DateCreated, cb.CurringBooth, sd.Shift, sd.BatchNo, paster.LastName as 'PasterLname', paster.FirstName as 'PasterFname', stacker.LastName as 'stackerLname', stacker.FirstName as 'stackerFname', sd.MoiseContent, s.SheetNo ";
$queryDetail .= "FROM sheetdetail_tbl sd ";
$queryDetail .= "JOIN line_tbl l ON sd.LineID = l.LineID ";
$queryDetail .= "JOIN platetype_tbl p ON sd.PlateTypeID = p.PlateTypeID ";
$queryDetail .= "JOIN curringbooth_tbl cb ON sd.CurringBoothID = cb.CurringBoothID ";
$queryDetail .= "JOIN employee_tbl paster ON sd.PasterID = paster.EmployeeID ";
$queryDetail .= "JOIN employee_tbl stacker ON sd.StackerID = stacker.EmployeeID ";
$queryDetail .= "JOIN sheet_tbl s ON sd.SheetID = s.SheetID ";
$queryDetail .= "WHERE sd.LotNumber = '".$cat_id."' ";

$resultDetail = odbc_exec($conn, $queryDetail);

$plateType = "";
$quantity = 0;
$line = "";
$rackno = "";
$Date = "";
$curringbooth = "";
$shift = "";
$batchNo = "";
$pasterFname = "";
$pasterLname = "";
$stackerFname = "";
$stackerLname = "";
$sheetNo = "";
$mc = "";
while($rowDetail = odbc_fetch_array($resultDetail)){
    $plateType = $rowDetail['PlateType'];
    $quantity = $rowDetail['Quantity'];
    $line = $rowDetail['Line'];
    $rackno = $rowDetail['RackNo'];
    $Date = $rowDetail['DateCreated'];
    $curringbooth = $rowDetail['CurringBooth'];
    $shift = $rowDetail['Shift'];
    $batchNo = $rowDetail['BatchNo'];
    $pasterFname = $rowDetail['PasterFname'];
    $pasterLname = $rowDetail['PasterLname'];
    $stackerFname = $rowDetail['stackerFname'];
    $stackerLname = $rowDetail['stackerLname'];
    $sheetNo = $rowDetail['SheetNo'];
    $mc = floatval($rowDetail['MoiseContent']);
}

$pdf->SetXY(2,42);

$pdf->SetFont('Helvetica','',10);
$pdf->Cell(20,7,'Plate Type:',0,0, 'L');

$pdf->SetFont('Helvetica','B',20);
$pdf->Cell(45,7,$plateType,0,0, 'L');

$pdf->Cell(8,7,'',0,0, 'L');

$pdf->SetFont('Helvetica','',10);
$pdf->Cell(23,7,'Qty Produced:',0,0, 'L');

$pdf->SetFont('Helvetica','B',20);
$pdf->Cell(20,7,number_format($quantity),0,0, 'R');
$pdf->SetFont('Helvetica','',10);
$pdf->Cell(7,7,'pcs',0,1, 'R');

$pdf->SetXY(2,52);

$pdf->SetFont('Helvetica','',10);
$pdf->Cell(20,7,'Line:',0,0, 'L');

$pdf->SetFont('Helvetica','U',13);
$pdf->Cell(45,7,$line,0,0, 'L');

$pdf->Cell(8,7,'',0,0, 'L');

$pdf->SetFont('Helvetica','',10);
$pdf->Cell(23,7,'Rack No.:',0,0, 'L');

$pdf->SetFont('Helvetica','U',13);
$pdf->Cell(20,7,$rackno,0,1, 'L');

$pdf->SetXY(2,60);

$pdf->SetFont('Helvetica','',10);
$pdf->Cell(20,7,'Date:',0,0, 'L');

$pdf->SetFont('Helvetica','U',13);
$pdf->Cell(45,7,date('d-M-y', strtotime($Date)),0,0, 'L');

$pdf->Cell(8,7,'',0,0, 'L');

$pdf->SetFont('Helvetica','',10);
$pdf->Cell(23,7,'Curing Booth:',0,0, 'L');

$pdf->SetFont('Helvetica','U',13);
$pdf->Cell(20,7,$curringbooth,0,1, 'L');

$pdf->SetXY(2,68);

$pdf->SetFont('Helvetica','',10);
$pdf->Cell(20,7,'Shift:',0,0, 'L');

$pdf->SetFont('Helvetica','U',13);
$pdf->Cell(45,7,$shift,0,0, 'L');

$pdf->Cell(8,7,'',0,0, 'L');

$pdf->SetFont('Helvetica','',10);
$pdf->Cell(23,7,'Batch No.:',0,0, 'L');

$pdf->SetFont('Helvetica','U',13);
$pdf->Cell(20,7,$batchNo,0,1, 'L');

$pdf->SetXY(2,76);

$pdf->SetFont('Helvetica','',10);
$pdf->Cell(20,7,'Paster:',0,0, 'L');

$pdf->SetFont('Helvetica','U',13);
$pdf->Cell(45,7,htmlspecialchars($pasterLname).', '.htmlspecialchars($pasterFname),0,0, 'L');

$pdf->Cell(8,7,'',0,0, 'L');

$pdf->SetFont('Helvetica','',10);
$pdf->Cell(23,7,'Moisture Content:',0,0, 'L');

$pdf->SetFont('Helvetica','U',13);
$pdf->Cell(20,7,$mc.'%',0,1, 'R');

$pdf->SetXY(2,84);

$pdf->SetFont('Helvetica','',10);
$pdf->Cell(20,7,'Stacker:',0,0, 'L');

$pdf->SetFont('Helvetica','U',13);
$pdf->Cell(45,7,htmlspecialchars($stackerLname).', '.htmlspecialchars($stackerFname),0,0, 'L');

$pdf->Cell(8,7,'',0,0, 'L');

$pdf->SetFont('Helvetica','',10);
$pdf->Cell(23,7,'Sheet No.:',0,0, 'L');

$pdf->SetFont('Helvetica','U',13);
$pdf->Cell(20,7,$sheetNo,0,1, 'L');


$file = "../../system_file/".$cat_id."print.pdf";
$pdf->Output('F', $file);
// $pdf->Output();

echo json_encode($cat_id);
?>