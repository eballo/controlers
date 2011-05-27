<?php
$dir="/home/kirsley/Escriptori/shels.tar.gz";
$comando="tar -tvf $dir | tr -s ' ' | cut -f3- -d' '";
exec($comando,$aqui);

$datetime1 = new DateTime('2009-05-11');
$datetime2 = new DateTime('2009-10-13');
$interval = $datetime1->diff($datetime2);
echo $interval->format('%R%a days');
$dias=$interval->format('%d');
$meses=$interval->format('%m');
$anyos=$interval->format('%y');

$total=$interval->format('%a');

function comparafechas($fecha1,$freq,$num){
	$hoy=getdate();
	$hoy=$hoy['year']."-".$hoy['mon']."-".$hoy['mday'];
	
	$datetime1 = new DateTime($fecha1);
	$datetime2 = new DateTime($hoy);
	$interval = $datetime1->diff($datetime2);
	echo "<br>".$interval->format('%R%d days');
	if($freq=='mes'){
		$dias=$num*30;
	}elseif($freq=='anyo'){
		$dias=$num*365;
	}else{
		$dias=$num;
	}
	echo $dias;
	$total=$interval->format('%a');
	
	if (($total-$dias)>0){
		return 1;
	}elseif(($total-$dias)<0){
		return -1;
	}else{
		return 0;
	}
		
}
/*
$res=comparafechas('2010-05-11','mes',1);
echo $res;
if ($res==-1){
	echo "Menos";
}elseif($res==1){
	echo "Mas";
}else{
	echo "iguales";
}
*/
echo "<br><br>";
echo "<table><tr><td>nom</td><td>Data</td><td>tamany</td></tr>";

foreach($aqui as $line ) { 
	 
	$res=explode(" ",$line);
	$tam=$res[0];
	$fecha="$res[1]";
	$nom=$res[3];
	list($year,$month, $day) =explode("-",$res[1]);
	$fecha="$day-$month-$year";


	echo "<tr><td>".$nom."</td>";
	$resFech=comparafechas($res[1],'dias',4);

	if ($resFech==-1){
		echo "<td>Menos de 3 Dias $fecha</td>";
	}elseif($resFech==1){
		echo "<td>Mas de 3 Dias $fecha</td>";
	}else{
		echo "<td>Hace 3 Dias $fecha</td>";
	}
/*
	echo "<td>".$fecha."</td>";
*/
	
	echo "<td>".$tam."</td></tr>";

/*
$hoy=getdate();
$hoy=$hoy['year']."-".$hoy['mon']."-".$hoy['mday'];
$datetime1 = new DateTime($res[1]);
$datetime2 = new DateTime($hoy);
$interval = $datetime1->diff($datetime2);
echo "<br>".$interval->format('%R%d days');
*/

/*	echo "<br>$nom $fechahora $tam<br>";

	foreach($res as $a){
		echo" <td>$a</td>";
	}
*/
	echo "</tr>";
}

echo "</table>";
/*
$output = `dir`;
	echo "$output";*/
?> 
