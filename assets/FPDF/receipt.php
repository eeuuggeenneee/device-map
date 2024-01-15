<?php
require('class_list.php');

date_default_timezone_set("Asia/Manila");
$date = date('jS M Y');

$transaction_no = '';
if(isset($_GET['transaction'])){
    $transaction_no = $_GET['transaction'];
}

$query1 = 'SELECT o.Customer_id as "Order_cust_id", c.First_name, c.Last_name  from orders o ';
$query1.= 'JOIN customers c ON o.Customer_id = c.Customer_ID ';
$query1.= 'WHERE o.Order_id ="'.$transaction_no.'" and o.Status = 1';

$fetch1 = mysqli_query($con, $query1);
$customer_name = '*********';

if($fetch1){
    $row = mysqli_fetch_array($fetch1);

    if($row['First_name'] != null){
        $fname     = $row['First_name'];
        $lname   = $row['Last_name'];

        $customer_name = $fname.' '.$lname;
    }
    else{
        $customer_name = '*********';
    }

    
}


// Instanciation of inherited class
$pdf = new PDF();
$pdf->AliasNbPages();
$pdf->AddPage();

$pdf->Cell(188,0,'',1,0);
$pdf->Ln(1);
$pdf->Cell(80);
$pdf->SetFont('Arial','B',12);
$pdf->Cell(20 ,7,'Invoice',0,1, 'C');

$pdf->SetFont('Arial','B',10);
$pdf->Cell(71 ,5,'Customer Details',0,0);
$pdf->Cell(59 ,5,'',0,0);
$pdf->Cell(59 ,5,'Invoice Details',0,1);

$pdf->SetFont('Arial','',10);

$pdf->Cell(130 ,5,'Name : '.$customer_name,0,0);
$pdf->Cell(19 ,5,'Employee:',0,0);
$pdf->Cell(40 ,5,$_COOKIE['user_name'],0,1);

$pdf->Cell(130 ,5,'',0,0);
$pdf->Cell(25 ,5,'Invoice Date:',0,0);
$pdf->Cell(34 ,5,$date,0,1);
 
$pdf->Cell(130 ,5,'',0,0);
$pdf->Cell(19 ,5,'Invoice No:',0,0);
$pdf->SetFont('Arial','B',10);
$pdf->Cell(40 ,5,$transaction_no,0,1);

$pdf->Ln(3);
$pdf->SetFont('Arial','B',10);
$pdf->Cell(130 ,5,'INVOICE BREAKDOWN DETAILS',0,0);

$pdf->Ln(7);

$pdf->SetFont('Arial','B',10);
/*Heading Of the table*/
$pdf->Cell(40 ,6,'Product ID',1,0,'C');
$pdf->Cell(78 ,6,'Description',1,0,'C');
$pdf->Cell(15 ,6,'Qty',1,0,'C');
$pdf->Cell(30 ,6,'Unit Price',1,0,'C');
$pdf->Cell(25 ,6,'Total',1,1,'C');/*end of line*/
/*Heading Of the table end*/
$pdf->SetFont('Arial','',10);

$query2 = 'SELECT i.Item_name, i.Amount, od.Quantity ';
$query2 .= 'from order_details od ';
$query2 .= 'JOIN items i ON od.Item_id = i.Item_Id ';
$query2 .= 'WHERE od.Status = 1 and od.Order_Id = "'.$transaction_no.'" ';
$i = 1;
$subttotal = 0;

$fetch2 = mysqli_query($con, $query2);

if($fetch2){
    while($row = mysqli_fetch_array($fetch2)){
        $subttotal = $row['Amount'] * $row['Quantity'];
        $pdf->Cell(40 ,6,$i,1,0);
		$pdf->Cell(78 ,6,$row['Item_name'],1,0);
		$pdf->Cell(15 ,6,$row['Quantity'],1,0,'R');
		$pdf->Cell(30 ,6,$row['Amount'],1,0,'R');
		$pdf->Cell(25 ,6,$subttotal,1,1,'R');

        $i += 1;
    }
}

$query3 = 'SELECT * FROM orders where Order_id = "'.$transaction_no.'" and Status = 1 ';
$fetch3 = mysqli_query($con, $query3);
$subtotal = 0;
$discount = 0;
$amount_total_cash = 0;
$grandTotal = 0;
$change = 0;
if($fetch3){
    $row = mysqli_fetch_array($fetch3);
    $subtotal = $row['Subtotal'];
    $discount = $row['Discount'];
    $amount_total_cash = $row['Amount'];
    $grandTotal = $row['Grand_Total'];
    $change = $row['Change_amount'];
}

$pdf->Cell(100 ,6,'',0,0);
$pdf->Cell(33 ,6,'Subtotal:',0,0);
$pdf->Cell(55 ,6,$subtotal,0,1,'R');

$pdf->Cell(100 ,6,'',0,0);
$pdf->Cell(33 ,6,'Discount Rate:',0,0);
$pdf->Cell(55 ,6,$discount.'%',0,1,'R');

$pdf->Cell(100 ,6,'',0,0);
$pdf->Cell(33 ,6,'Discounted Amount:','B',0);
$pdf->Cell(55 ,6,'00.00','B',1,'R');

$pdf->Cell(100 ,1,'',0,0);
$pdf->Cell(33 ,1,'','B',0);
$pdf->Cell(55 ,1,'','B',1,'R');

$pdf->SetFont('Arial','B',11);
$pdf->Cell(100 ,6,'',0,0);
$pdf->Cell(33 ,6,'GRAND TOTAL',0,0);
$pdf->Cell(55 ,6,$grandTotal,0,1,'R');

$pdf->SetFont('Arial','',10);

$pdf->Cell(100 ,6,'',0,0);
$pdf->Cell(33 ,6,'Cash:',0,0);
$pdf->Cell(55 ,6,$amount_total_cash,0,1,'R');

$pdf->Cell(100 ,6,'',0,0);
$pdf->Cell(33 ,6,'Change:',0,0);
$pdf->Cell(55 ,6,$change,0,1,'R');


$pdf->Output();
?>