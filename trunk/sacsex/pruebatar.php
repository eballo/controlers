<?php
	include_once "includes/functions.php";
	include_once "includes/libsheader.php";
	$compFecha=false;
?> 
<html>
<body>
<div class="valoresBusc">
	<form action='pruebatar.php?accion=buscar' method=post>
		<table>
		<tr>
			<th>Fichero</th>
			<th>Periodo</th>
			<td></td>
		</tr>
		<tr>
			<td><input type='text' name='fname' size='40'/></td>
			<td>
				<SELECT name="rel">
					<optgroup label="relacion">
						<OPTION value='min'>Hace menos de</OPTION>
						<OPTION value='max'>Hace mas de</OPTION>
					</optgroup>
				</SELECT>
				<input type=text name='numd' style="width:40px;" />
				<select name='freq'>
					<optgroup label="tiempo">
						<option value='dias'>dias</option>
						<option value='mes'>meses</option>
						<option value='anyos'>años</option>	
					</optgroup>		
				</select>
			</td>
			<td>
				<input type='submit' value='buscar' />
			</td>
		</tr>
		</table>
		
	</form>
</div>

<?php 
if (isset($_POST['numd']) && $_POST['numd']!=''){
		$num=$_POST['numd'];
		//comparafechas($res[1],$text,$num);
		$text=$_POST['freq'];
		$rel=$_POST['rel'];
		$compFecha=true;
	}else{
		$compFecha=false;
	}
$dir="/home/sacs/1912702";
$cmd="ls $dir";
exec($cmd,$result);

foreach($result as $cont){
	$ruta="$dir/$cont";
	$cmd="ls $ruta";
	exec($cmd,$contFecha);
	echo $ruta;
	echo '<div class="tablaRes">
		<table>
			<tr><th colspan=4>'.$ruta.'</th></tr>
			<tr><td>Tar Origen</td><td>nom</td><td>Data</td><td>tamany</td>
			</tr>';
		
		foreach($contFecha as $elem){
			$ruta2="$ruta/$elem";
			$comando="tar -tvf $ruta2 | tr -s ' ' | cut -f3- -d' '";
			exec($comando,$aqui);
			
			foreach($aqui as $line ) { 
				if ($compFecha){
					
					$res=explode(" ",$line);
					$fecha="$res[1]";
					list($year,$month, $day) =explode("-",$res[1]);
					$fecha="$day-$month-$year";
					$resFech=comparafechas($res[1],$text,$num);
						if ($resFech==-1 && $rel=='min' || $resFech==1 && $rel=='max'){
							$nom=$res[3];
							$tam=$res[0];
							echo "<tr><td>".$ruta2."</td>";
							echo "<td>".$nom."</td>";
							echo "<td>".$fecha."</td>";
							echo "<td>".$tam."</td></tr>";
							echo "</tr>";
						}
					}else{
						$res=explode(" ",$line);
						$fecha="$res[1]";
						list($year,$month, $day) =explode("-",$res[1]);
						$fecha="$day-$month-$year";
						$nom=$res[3];
						$tam=$res[0];
						echo "<tr><td>".$ruta2."</td>";
						echo "<td>".$nom."</td>";
						echo "<td>".$fecha."</td>";
						echo "<td>".$tam."</td></tr>";
						echo "</tr>";
					}
				
			}
			unset($aqui);
		}
		unset($contFecha);
		
		echo "</table>
	
	</div>";
}
?>

</body>
</html>
