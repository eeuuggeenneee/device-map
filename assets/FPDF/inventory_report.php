<?php
require('class_list.php');

date_default_timezone_set("Asia/Manila");
$date = date('jS M Y');

$cat_id = 0;
if(isset($_GET['cat_id'])){
    $cat_id = $_GET['cat_id'];
}

// Instanciation of inherited class
$pdf = new PDF();
$pdf->AliasNbPages();
$pdf->AddPage();

//------------------------------------//
$data = array('Category1' => 1510, 'Category2' => 1610, 'Category3' => 1400);

//Pie chart
$pdf->Cell(188,0,'',1,0);
$pdf->Ln(1);
// $pdf->Cell(20);
$pdf->SetFont('Arial','B',12);
$pdf->Cell(20 ,7,'All Item Category Reports',0,1, 'L');
$pdf->Ln(8);



$pdf->SetFont('Arial', '', 10);
$valX = $pdf->GetX();
$valY = $pdf->GetY();
$pdf->Cell(30, 5, 'Category1:');
$pdf->Cell(15, 5, $data['Category1'], 0, 0, 'R');
$pdf->Ln();
$pdf->Cell(30, 5, 'Category2:');
$pdf->Cell(15, 5, $data['Category2'], 0, 0, 'R');
$pdf->Ln();
$pdf->Cell(30, 5, 'Category3:');
$pdf->Cell(15, 5, $data['Category3'], 0, 0, 'R');
$pdf->Ln();
$pdf->Ln(8);

$pdf->SetXY(90, $valY);
$col1=array(100,100,255);
$col2=array(255,100,100);
$col3=array(255,255,100);
$pdf->PieChart(150, 40, $data, '%l (%p)', array($col1,$col2,$col3));
$pdf->SetXY($valX, $valY + 40);
//------------------------------------//


$pdf->Ln(10);
$pdf->SetFont('Arial','B',10);
$pdf->Cell(130 ,5,'ITEM BREAKDOWN DETAILS',0,0);
$pdf->Ln(7);

$pdf->SetFont('Arial','B',10);
/*Heading Of the table*/
$pdf->Cell(40 ,6,'Product ID',1,0,'C');
$pdf->Cell(75 ,6,'Description',1,0,'C');
$pdf->Cell(30 ,6,'Category',1,0,'C');
$pdf->Cell(20 ,6,'Qty',1,0,'C');
$pdf->Cell(23 ,6,'Unit Price',1,0,'C');

$pdf->Ln();

/*Heading Of the table end*/
$pdf->SetFont('Arial','',10);


$query2 = 'SELECT i.Item_name, i.Amount, ii.Quantity, c.Category_name ';
$query2 .= 'from items i ';
$query2 .= 'JOIN item_invt ii ON i.Item_id = ii.Item_Id ';
$query2 .= 'JOIN category c ON c.Category_Id = i.Category_Id ';
$query2 .= 'WHERE i.Status = 1 and ii.Status = 1 ';
if($cat_id!=0){
    $query2 .= 'and c.Category_Id = '.$cat_id.' ';
}
$i = 1;
$subttotal = 0;

$fetch2 = mysqli_query($con, $query2);

if($fetch2){
    while($row = mysqli_fetch_array($fetch2)){
        
        $pdf->Cell(40 ,6,$i,1,0);
		$pdf->Cell(75 ,6,$row['Item_name'],1,0,'C');
        $pdf->Cell(30 ,6,$row['Category_name'],1,0,'C');
        $pdf->Cell(20 ,6,$row['Quantity'],1,0,'C');
        $pdf->Cell(23 ,6,$row['Amount'],1,0,'C');
        $pdf->Ln();
        $i += 1;
    }
}

// $query3 = 'SELECT * FROM orders where Order_id = "'.$transaction_no.'" and Status = 1 ';
// $fetch3 = mysqli_query($con, $query3);
// $subtotal = 0;
// $discount = 0;
// $amount_total_cash = 0;
// $grandTotal = 0;
// $change = 0;
// if($fetch3){
//     $row = mysqli_fetch_array($fetch3);
//     $subtotal = $row['Subtotal'];
//     $discount = $row['Discount'];
//     $amount_total_cash = $row['Amount'];
//     $grandTotal = $row['Grand_Total'];
//     $change = $row['Change_amount'];
// }

// $pdf->Cell(100 ,6,'',0,0);
// $pdf->Cell(33 ,6,'Subtotal:',0,0);
// $pdf->Cell(55 ,6,$subtotal,0,1,'R');

// $pdf->Cell(100 ,6,'',0,0);
// $pdf->Cell(33 ,6,'Discount Rate:',0,0);
// $pdf->Cell(55 ,6,$discount.'%',0,1,'R');

// $pdf->Cell(100 ,6,'',0,0);
// $pdf->Cell(33 ,6,'Discounted Amount:','B',0);
// $pdf->Cell(55 ,6,'00.00','B',1,'R');

// $pdf->Cell(100 ,1,'',0,0);
// $pdf->Cell(33 ,1,'','B',0);
// $pdf->Cell(55 ,1,'','B',1,'R');

// $pdf->SetFont('Arial','B',11);
// $pdf->Cell(100 ,6,'',0,0);
// $pdf->Cell(33 ,6,'GRAND TOTAL',0,0);
// $pdf->Cell(55 ,6,$grandTotal,0,1,'R');

// $pdf->SetFont('Arial','',10);

// $pdf->Cell(100 ,6,'',0,0);
// $pdf->Cell(33 ,6,'Cash:',0,0);
// $pdf->Cell(55 ,6,$amount_total_cash,0,1,'R');

// $pdf->Cell(100 ,6,'',0,0);
// $pdf->Cell(33 ,6,'Change:',0,0);
// $pdf->Cell(55 ,6,$change,0,1,'R');

$pdf->Ln(7);
$pdf->SetFont('Arial','B',10);
$pdf->Cell(71 ,5,'Generator Details',0,0);
$pdf->Cell(59 ,5,'',0,0);
$pdf->Cell(59 ,5,'',0,1);

$pdf->SetFont('Arial','',10);

$pdf->Cell(130 ,5,'Name : '.$_COOKIE['user_name'],0,0);
$pdf->Cell(19 ,5,'',0,0);
$pdf->Cell(40 ,5,'',0,1);

$pdf->Cell(130 ,5,$date,0,0);
$pdf->Cell(25 ,5,'',0,0);
$pdf->Cell(34 ,5,'',0,1);
 
$pdf->Cell(130 ,5,'',0,0);
$pdf->Cell(19 ,5,'',0,0);
$pdf->SetFont('Arial','B',10);
$pdf->Cell(40 ,5,'',0,1);



$pdf->Ln(7);


$pdf->Output();
?>