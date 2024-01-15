<?php
require('fpdf.php');
include "../../includes/db.php";
include "../../includes/functions.php";

// $business_name = '';
// $business_add = '';
// $business_no = '';
// $business_logo = '';

// $query_info = 'SELECT * FROM business_info';
// $fetch_info = mysqli_query($con, $query_info);
// if($fetch_info){
//     $row = mysqli_fetch_array($fetch_info);

//     $business_name = $row['Business_name'];
//     $business_add = $row['Address'];
//     $business_no = $row['Contact_No'];
//     $business_logo = '../images/logo/'.$row['Logo'];
// }
// else{
//     $business_name = '';
//     $business_add = '';
//     $business_no = '';
//     $business_logo = '../images/logo/logo-icon.png';
// }

// $GLOBALS["BusinessLogo"] = $business_logo;
// $GLOBALS["BusinessName"] = $business_name;
// $GLOBALS["BusinessAddress"] = $business_add;
// $GLOBALS["BusinessPhone"] = $business_no;

class PDF extends FPDF
{
// // Page header
//     function Header()
//     {
    
//         $this->SetFont('Arial','B',15);
//         // Move to the right
//         $this->Cell(80);
//         // Title
//         $this->Image($GLOBALS["BusinessLogo"],10,6,25);
//         $this->Cell(20,10,$GLOBALS["BusinessName"],0,0,'C');
//         $this->Ln(5);
//         $this->SetFont('Arial','',10);
//         // Move to the right
//         $this->Cell(80);
        
//         $this->Cell(20,10,$GLOBALS["BusinessAddress"],0,1, 'C');
//         $this->SetFont('Arial','',10);
        
//         // Move to the right
//         $this->SetY(20);
//         $this->Cell(80);
//         $this->Cell(20,10,'Phonenumber : '.$GLOBALS["BusinessPhone"],0,1, 'C');
//         // Line break
//         $this->Ln(3);
//     }

//     // Page footer
//     function Footer()
//     {
//         // Position at 1.5 cm from bottom
//         $this->SetY(-15);
//         // Arial italic 8
//         $this->SetFont('Arial','I',8);
//         // Page number
//         $this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
//     }

//     function Sector($xc, $yc, $r, $a, $b, $style='FD', $cw=true, $o=90)
//     {
//         $d0 = $a - $b;
//         if($cw){
//             $d = $b;
//             $b = $o - $a;
//             $a = $o - $d;
//         }else{
//             $b += $o;
//             $a += $o;
//         }
//         while($a<0)
//             $a += 360;
//         while($a>360)
//             $a -= 360;
//         while($b<0)
//             $b += 360;
//         while($b>360)
//             $b -= 360;
//         if ($a > $b)
//             $b += 360;
//         $b = $b/360*2*M_PI;
//         $a = $a/360*2*M_PI;
//         $d = $b - $a;
//         if ($d == 0 && $d0 != 0)
//             $d = 2*M_PI;
//         $k = $this->k;
//         $hp = $this->h;
//         if (sin($d/2))
//             $MyArc = 4/3*(1-cos($d/2))/sin($d/2)*$r;
//         else
//             $MyArc = 0;
//         //first put the center
//         $this->_out(sprintf('%.2F %.2F m',($xc)*$k,($hp-$yc)*$k));
//         //put the first point
//         $this->_out(sprintf('%.2F %.2F l',($xc+$r*cos($a))*$k,(($hp-($yc-$r*sin($a)))*$k)));
//         //draw the arc
//         if ($d < M_PI/2){
//             $this->_Arc($xc+$r*cos($a)+$MyArc*cos(M_PI/2+$a),
//                         $yc-$r*sin($a)-$MyArc*sin(M_PI/2+$a),
//                         $xc+$r*cos($b)+$MyArc*cos($b-M_PI/2),
//                         $yc-$r*sin($b)-$MyArc*sin($b-M_PI/2),
//                         $xc+$r*cos($b),
//                         $yc-$r*sin($b)
//                         );
//         }else{
//             $b = $a + $d/4;
//             $MyArc = 4/3*(1-cos($d/8))/sin($d/8)*$r;
//             $this->_Arc($xc+$r*cos($a)+$MyArc*cos(M_PI/2+$a),
//                         $yc-$r*sin($a)-$MyArc*sin(M_PI/2+$a),
//                         $xc+$r*cos($b)+$MyArc*cos($b-M_PI/2),
//                         $yc-$r*sin($b)-$MyArc*sin($b-M_PI/2),
//                         $xc+$r*cos($b),
//                         $yc-$r*sin($b)
//                         );
//             $a = $b;
//             $b = $a + $d/4;
//             $this->_Arc($xc+$r*cos($a)+$MyArc*cos(M_PI/2+$a),
//                         $yc-$r*sin($a)-$MyArc*sin(M_PI/2+$a),
//                         $xc+$r*cos($b)+$MyArc*cos($b-M_PI/2),
//                         $yc-$r*sin($b)-$MyArc*sin($b-M_PI/2),
//                         $xc+$r*cos($b),
//                         $yc-$r*sin($b)
//                         );
//             $a = $b;
//             $b = $a + $d/4;
//             $this->_Arc($xc+$r*cos($a)+$MyArc*cos(M_PI/2+$a),
//                         $yc-$r*sin($a)-$MyArc*sin(M_PI/2+$a),
//                         $xc+$r*cos($b)+$MyArc*cos($b-M_PI/2),
//                         $yc-$r*sin($b)-$MyArc*sin($b-M_PI/2),
//                         $xc+$r*cos($b),
//                         $yc-$r*sin($b)
//                         );
//             $a = $b;
//             $b = $a + $d/4;
//             $this->_Arc($xc+$r*cos($a)+$MyArc*cos(M_PI/2+$a),
//                         $yc-$r*sin($a)-$MyArc*sin(M_PI/2+$a),
//                         $xc+$r*cos($b)+$MyArc*cos($b-M_PI/2),
//                         $yc-$r*sin($b)-$MyArc*sin($b-M_PI/2),
//                         $xc+$r*cos($b),
//                         $yc-$r*sin($b)
//                         );
//         }
//         //terminate drawing
//         if($style=='F')
//             $op='f';
//         elseif($style=='FD' || $style=='DF')
//             $op='b';
//         else
//             $op='s';
//         $this->_out($op);
//     }
 
//     function _Arc($x1, $y1, $x2, $y2, $x3, $y3 )
//     {
//         $h = $this->h;
//         $this->_out(sprintf('%.2F %.2F %.2F %.2F %.2F %.2F c',
//             $x1*$this->k,
//             ($h-$y1)*$this->k,
//             $x2*$this->k,
//             ($h-$y2)*$this->k,
//             $x3*$this->k,
//             ($h-$y3)*$this->k));
//     }


//     var $legends;
//     var $wLegend;
//     var $sum;
//     var $NbVal;

//     function PieChart($w, $h, $data, $format, $colors=null)
//     {
//         $this->SetFont('Courier', '', 10);
//         $this->SetLegends($data,$format);

//         $XPage = $this->GetX();
//         $YPage = $this->GetY();
//         $margin = 2;
//         $hLegend = 5;
//         $radius = min($w - $margin * 4 - $hLegend - $this->wLegend, $h - $margin * 2);
//         $radius = floor($radius / 2);
//         $XDiag = $XPage + $margin + $radius;
//         $YDiag = $YPage + $margin + $radius;
//         if($colors == null) {
//             for($i = 0; $i < $this->NbVal; $i++) {
//                 $gray = $i * intval(255 / $this->NbVal);
//                 $colors[$i] = array($gray,$gray,$gray);
//             }
//         }

//         //Sectors
//         $this->SetLineWidth(0.2);
//         $angleStart = 0;
//         $angleEnd = 0;
//         $i = 0;
//         foreach($data as $val) {
//             $angle = ($val * 360) / doubleval($this->sum);
//             if ($angle != 0) {
//                 $angleEnd = $angleStart + $angle;
//                 $this->SetFillColor($colors[$i][0],$colors[$i][1],$colors[$i][2]);
//                 $this->Sector($XDiag, $YDiag, $radius, $angleStart, $angleEnd);
//                 $angleStart += $angle;
//             }
//             $i++;
//         }

//         //Legends
//         $this->SetFont('Courier', '', 10);
//         $x1 = $XPage + 2 * $radius + 4 * $margin;
//         $x2 = $x1 + $hLegend + $margin;
//         $y1 = $YDiag - $radius + (2 * $radius - $this->NbVal*($hLegend + $margin)) / 2;
//         for($i=0; $i<$this->NbVal; $i++) {
//             $this->SetFillColor($colors[$i][0],$colors[$i][1],$colors[$i][2]);
//             $this->Rect($x1, $y1, $hLegend, $hLegend, 'DF');
//             $this->SetXY($x2,$y1);
//             $this->Cell(0,$hLegend,$this->legends[$i]);
//             $y1+=$hLegend + $margin;
//         }
//     }

//     function SetLegends($data, $format)
//     {
//         $this->legends=array();
//         $this->wLegend=0;
//         $this->sum=array_sum($data);
//         $this->NbVal=count($data);
//         foreach($data as $l=>$val)
//         {
//             $p=sprintf('%.2f',$val/$this->sum*100).'%';
//             $legend=str_replace(array('%l','%v','%p'),array($l,$val,$p),$format);
//             $this->legends[]=$legend;
//             $this->wLegend=max($this->GetStringWidth($legend),$this->wLegend);
//         }
//     }
}


?>