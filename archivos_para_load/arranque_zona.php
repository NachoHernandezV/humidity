<?php
	/*archivo para Arrancar los datos de ETAPAS al inicio, solo se ejecuta al abrir o actualizar*/
			date_default_timezone_set('America/Monterrey');
			$user="root";
			$pass="pirineos";
			$server="localhost";
			$bd="humedad";
			$con=mysqli_connect($server,$user,$pass,$bd);


			$sql5="SELECT * FROM zonas ";
			$resultad=mysqli_query($con,$sql5);

			while($row2 = mysqli_fetch_array($resultad))
			{
				echo '<option value="'.$row2['idzona'].'">'.$row2['zona'].'</option>';
			}
?>
