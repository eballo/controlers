<?php
$dir="/home/kirsley/Escritorio/shels.tar.gz";
$comando="tar -tvf $dir | tr -s ' ' | cut -f3- -d' '";
exec($comando,$aqui);
print_r ($aqui);
echo "<table><tr><td>tamany</td><td>Data</td><td>nom</td></tr>";
foreach($aqui as $line) { 
	 
	echo "<tr>";
	$res=explode(" ",$line);
	foreach($res as $a){
		echo" <td>$a</td>";
	}
	echo "</tr>";
}
echo "</table>";
?> 