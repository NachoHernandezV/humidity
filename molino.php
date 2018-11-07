<!DOCTYPE html>
<html>
<?php
session_start();
?>
<?php
  if($salir2=="salir2")
  {
  	$_SESSION['molgeneral']="no";
  }
  if($_SESSION['molgeneral'] != "si") // MANDA A VENTANA DE VENTAS
  {
  	$_SESSION['molgeneral']="no";
  	echo '<script>window.location="salir.php"</script>';
  }
?>
<head>
    <title>Registro de Humedad</title>
    <meta charset="utf-8"/>
    <meta name="description" content="Intranet de Pirineos"/>
    <meta name="keywords" content="Humedad Pirineos"/>
    <link rel="stylesheet" type="text/css" href="css/molino.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script><!--Para que funcione el .load que carga el contenido JQUERY-->

    <script type="text/javascript">
        /*<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js">*//*SE REQUIERE PARA CARGAR EL CONTENIDO LA FUNCION .load*/
        $("document").ready(function()
        {
        			$("#etapas").load("archivos_para_load/arranque_etapas.php");
        })

        $("document").ready(function()
        {
              $("#horas").load("archivos_para_load/arranque_horas.php");
        })

        $("document").ready(function()
		    {
                /*PARA QUE ARRANQUEN LOS DATOS DE ZONA*/
				       $("#zona").load("archivos_para_load/arranque_zona.php");

        		//ESTA LINEA SE EJECUTA CUANDO SE SELECCIONA EL SELECT
        			$("#zona").change(function()
        				{

              					//OBTENEMOS LA VARIABLE QUE VAMOS A MANDAR EN ESTE CASO SERIA
              					//IDZONA
              					var id= $("#zona").val();

              					//CAMBIAMOS la ETAPA CUANDO CAMBIEMOS LA ZONA
              					$.get("archivos_para_load/cambio_etapas.php",{param2_id:id})
              					.done(function(data)
              					{
              						$("#etapas").html(data);
              					})

                        //CAMBIAMOS la hora CUANDO CAMBIEMOS LA ZONA
              					$.get("archivos_para_load/cambio_horas.php",{param2_id:id})
              					.done(function(data)
              					{
              						$("#horas").html(data);
              					})
        			 })
		  })
    </script>
    <script>

  		function habilitar(value)
  		{
        if(document.getElementById('hayparo').checked)
          {
                document.getElementById("humedad").disabled=true;
                document.getElementById("humedad").value="";
          }
          else
          {
              document.getElementById("humedad").disabled=false;
          }
  		}
  	</script>
</head>

<body>
  <header>
    <div id="contenedor-header">
          <div>
            <img src="imagenes/logo.png" alt="Logo" class="logo" >
          </div>
          <div class="tituloprincipal">
            Harinera Los Pirineos S.A de C.V
          </div>
          <div class="letrasUser">
                <img src="<?php echo $_SESSION['foto']; ?>" alt="Logo" class="imagenuser" >
                <?php echo $_SESSION['usuario']; ?></br>
                <form action=salir.php method=POST name="delet">
            		<input type="hidden" name="salir2" value=salir2 >
            		<a href="salir.php" target="_top">Cerrar sesión</a>
            		</form>
          </div>
    </div>

  </header>
  <?php
	date_default_timezone_set('America/Monterrey');
   ?>

  <h4><a width="70" height="40" onclick="window.open('info.php','nuevaVentana','width=500, height=700')">Acerca del Software</a></h4>
  <h3>Registro de Humedad en Molino</h3>

<div id="contenedor-flex">
    <div id="formulario">
        <form action="molino.php" method="post">
            <label for="fname">Fecha</label>
            <input type="date" id="fecha" name="fecha" value="<?php echo date("Y-m-d");?>">

            <label for="zona">Zona</label>
            <select id="zona" name="zona">
            </select>

            <label for="etapas">Etapas</label>
            <select id="etapas" name="etapas">
            </select>

            <label for="horas">Hora</label>
            <select id="horas" name="horas">
            </select>

            <label for="fname">Humedad</label>
            <input type="text" id="humedad" name="humedad" placeholder="Ingrese la humedad de Molino.." required pattern="^\d{0,3}(\.\d{0,4})?$">
            <input type="checkbox" name="hayparo" id="hayparo" onclick="habilitar();" value="si">Hay Inactividad<br>

            <input type="submit" class="botoninsertar" value="Insertar">
        </form>
    </div>
    <div id="reportes">
        <p id="tituloreporte">Generación de Reportes</p>
        <p id="titulofecha">Seleccione el rango de fecha</p>
        <div id="ElementosReportes">
            <FORM ACTION="reportesexcel\excelreport.php" METHOD=post name="excelreportM" id="excelreportM">
                <label >Del:</label>
                <input type="date" id="fecha_inicio" name="fecha_inicio" value="<?php echo date("Y-m-d");?>">
                <label >Al:</label>
                <input type="date" id="fecha_fin" name="fecha_fin" value="<?php echo date("Y-m-d");?>">
                <input type="submit" class="boton" value="Crear Reporte">
            </form>
        </div>

      <div id="elemenoseditar">
        <p id="tituloreporte2">Editar Los Registros</p>
        <input type="submit" class="boton" value="Editar" onclick="window.open('archivoseditar_molino/editar.php','EditarRegistros','width=460, height=650')">
      </div>

    </div>

</div>



<?php
$link=mysqli_connect("localhost", "root", "pirineos", "humedad");

$fecha=$_POST["fecha"];
$zona=$_POST["zona"];
$etapas=$_POST["etapas"];
$horas=$_POST["horas"];
$humedad=$_POST["humedad"];
$hayparo=$_POST["hayparo"];
$usuariodemolino=$_SESSION['idusuarioMol'];
$usuariodelaboratorio=$_SESSION['idusuarioLab'];
$fechaactual=date("Y-m-d H:i:s");

if($zona == "1")$zonanombre="Molino 1";
if($zona == "2")$zonanombre="Molino 2";
if($zona == "3")$zonanombre="Mezclas";


//11111111111111111111111111111111111111111111111111111111111111111111111111111111111111
if($zona == "1" and $etapas=="Limpia") //ZONA  MOLINO 1 Y LIMPIA
{
        //ANALISIS DE REPETIDOS
        $repetidos="select id,humedadMol from molino1_limpia where fecha='".$fecha."' and hora='".$horas."'";
        $cantidadrepetidos=mysqli_query($link,$repetidos);
        mysqli_data_seek($cantidadrepetidos,0);
        $numrepetidos = mysqli_fetch_row($cantidadrepetidos);
        //FIN DE REPETIDOS

        if($numrepetidos[0] >= 1 and $numrepetidos[1]==0) //sI EXISTE EL REGISTRO VALIDAR QUE ESTA VACIO EN HumedadMol
          {
            if($hayparo=='si')
            $sql="UPDATE molino1_limpia SET humedadMol='100',idMol='".$usuariodemolino."' WHERE id='".$numrepetidos[0]."'";
            else
            $sql="UPDATE molino1_limpia SET humedadMol='".$humedad."' ,idMol='".$usuariodemolino."' WHERE id='".$numrepetidos[0]."'";
          }
        if($numrepetidos[0] =="")//si no hay registros HACEMOS UN INSERT COMPLETO
          {
              if($hayparo=='si')
              $sql="INSERT INTO molino1_limpia (fecha,hora,humedadMol,humedadLab,idMol,idLab,fechainsertado) VALUES ('$fecha','$horas','100','','$usuariodemolino','','$fechaactual')";
              else
              $sql="INSERT INTO molino1_limpia (fecha,hora,humedadMol,humedadLab,idMol,idLab,fechainsertado) VALUES ('$fecha','$horas','$humedad','','$usuariodemolino','','$fechaactual')";
          }

          if($numrepetidos[1]>0){
          echo "Ya insertaste datos en la zona ".$zonanombre." en la etapa ".$etapas." y en la Hora ".$horas.".  ";
          echo "Si quieres cambiar el dato de esta hora ve a la parte de Editar";
          }
          else
          $resultad=mysqli_query($link,$sql);
}//FIN DE ZONA1-->>LIMPIA

if($zona == "1" and $etapas=="Cambio") //ETAPA 2 LIMPIA
{
    //ANALISIS DE REPETIDOS
    $repetidos="select id,humedadMol from molino1_cambio where fecha='".$fecha."' and hora='".$horas."'";
    $cantidadrepetidos=mysqli_query($link,$repetidos);
    mysqli_data_seek($cantidadrepetidos,0);
    $numrepetidos = mysqli_fetch_row($cantidadrepetidos);
    //FIN DE REPETIDOS

    if($numrepetidos[0] >= 1 and $numrepetidos[1]==0) //sI EXISTE EL REGISTRO VALIDAR QUE ESTA VACIO EN HumedadMol
      {
        if($hayparo=='si')
          $sql="UPDATE molino1_cambio SET humedadMol='100' ,idMol='".$usuariodemolino."' WHERE id='".$numrepetidos[0]."'";
        else
          $sql="UPDATE molino1_cambio SET humedadMol='".$humedad."' ,idMol='".$usuariodemolino."' WHERE id='".$numrepetidos[0]."'";
      }
    if($numrepetidos[0] =="")//si no hay registros HACEMOS UN INSERT COMPLETO
      {
          if($hayparo=='si')
          $sql="INSERT INTO molino1_cambio (fecha,hora,humedadMol,humedadLab,idMol,idLab,fechainsertado) VALUES ('$fecha','$horas','100','','$usuariodemolino','','$fechaactual')";
          else
          $sql="INSERT INTO molino1_cambio (fecha,hora,humedadMol,humedadLab,idMol,idLab,fechainsertado) VALUES ('$fecha','$horas','$humedad','','$usuariodemolino','','$fechaactual')";
      }

      if($numrepetidos[1]>0){
      echo "Ya insertaste datos en la zona ".$zonanombre." en la etapa ".$etapas." y en la Hora ".$horas.".  ";
      echo "Si quieres cambiar el dato de esta hora ve a la parte de Editar";
      }
      else
      $resultad=mysqli_query($link,$sql);
}//FIN DE ZONA1--->> CAMBIO

if($zona == "1" and $etapas=="Trigo al T1") //ETAPA 3 LIMPIA ANTES MOLIENDA
{
    //ANALISIS DE REPETIDOS
    $repetidos="select id,humedadMol from molino1_molienda where fecha='".$fecha."' and hora='".$horas."'";
    $cantidadrepetidos=mysqli_query($link,$repetidos);
    mysqli_data_seek($cantidadrepetidos,0);
    $numrepetidos = mysqli_fetch_row($cantidadrepetidos);
    //FIN DE REPETIDOS

    if($numrepetidos[0] >= 1 and $numrepetidos[1]==0) //sI EXISTE EL REGISTRO VALIDAR QUE ESTA VACIO EN HumedadMol
      {
        if($hayparo=='si')
          $sql="UPDATE molino1_molienda SET humedadMol='100' ,idMol='".$usuariodemolino."' WHERE id='".$numrepetidos[0]."'";
        else
          $sql="UPDATE molino1_molienda SET humedadMol='".$humedad."' ,idMol='".$usuariodemolino."' WHERE id='".$numrepetidos[0]."'";
      }
    if($numrepetidos[0] =="")//si no hay registros HACEMOS UN INSERT COMPLETO
      {
          if($hayparo=='si')
          $sql="INSERT INTO molino1_molienda (fecha,hora,humedadMol,humedadLab,idMol,idLab,fechainsertado) VALUES ('$fecha','$horas','100','','$usuariodemolino','','$fechaactual')";
          else
          $sql="INSERT INTO molino1_molienda (fecha,hora,humedadMol,humedadLab,idMol,idLab,fechainsertado) VALUES ('$fecha','$horas','$humedad','','$usuariodemolino','','$fechaactual')";
      }

      if($numrepetidos[1]>0){
      echo "Ya insertaste datos en la zona ".$zonanombre." en la etapa ".$etapas." y en la Hora ".$horas.".  ";
      echo "Si quieres cambiar el dato de esta hora ve a la parte de Editar";
      }
      else
      $resultad=mysqli_query($link,$sql);
}

if($zona == "1" and $etapas=="Harina") //ETAPA 4 LIMPIA  ahora es HARINA
{
    //ANALISIS DE REPETIDOS
    $repetidos="select id,humedadMol from molino1_envio where fecha='".$fecha."' and hora='".$horas."'";
    $cantidadrepetidos=mysqli_query($link,$repetidos);
    mysqli_data_seek($cantidadrepetidos,0);
    $numrepetidos = mysqli_fetch_row($cantidadrepetidos);
    //FIN DE REPETIDOS

    if($numrepetidos[0] >= 1 and $numrepetidos[1]==0) //sI EXISTE EL REGISTRO VALIDAR QUE ESTA VACIO EN HumedadMol
      {
          if($hayparo=='si')
          $sql="UPDATE molino1_envio SET humedadMol='100' ,idMol='".$usuariodemolino."' WHERE id='".$numrepetidos[0]."'";
          else
          $sql="UPDATE molino1_envio SET humedadMol='".$humedad."' ,idMol='".$usuariodemolino."' WHERE id='".$numrepetidos[0]."'";
      }
    if($numrepetidos[0] =="")//si no hay registros HACEMOS UN INSERT COMPLETO
      {
          if($hayparo=='si')
          $sql="INSERT INTO molino1_envio (fecha,hora,humedadMol,humedadLab,cenizas,idMol,idLab,fechainsertado) VALUES ('$fecha','$horas','100','','','$usuariodemolino','','$fechaactual')";
          else
          $sql="INSERT INTO molino1_envio (fecha,hora,humedadMol,humedadLab,cenizas,idMol,idLab,fechainsertado) VALUES ('$fecha','$horas','$humedad','','','$usuariodemolino','','$fechaactual')";
      }

      if($numrepetidos[1]>0){
      echo "Ya insertaste datos en la zona ".$zonanombre." en la etapa ".$etapas." y en la Hora ".$horas.".  ";
      echo "Si quieres cambiar el dato de esta hora ve a la parte de Editar";
      }
      else
      $resultad=mysqli_query($link,$sql);
}
//FIN ZONA 1  //////////////////FIN ZONA 1////////////////////FIN ZONA 1///////////////////////FIN ZONA 1//////////////////FIN ZONA 1//////////////FIN ZONA 1////




////222222222222222222222222222222222222222222222222222222222222222222222222222222222222222222222222222222222222222222222222222222222222222222222222222222
if($zona == "2" and $etapas=="Limpia") //ZONA  MOLINO 2 Y LIMPIA
{
        //ANALISIS DE REPETIDOS
        $repetidos="select id,humedadMol from molino2_limpia where fecha='".$fecha."' and hora='".$horas."'";
        $cantidadrepetidos=mysqli_query($link,$repetidos);
        mysqli_data_seek($cantidadrepetidos,0);
        $numrepetidos = mysqli_fetch_row($cantidadrepetidos);
        //FIN DE REPETIDOS

        if($numrepetidos[0] >= 1 and $numrepetidos[1]==0) //sI EXISTE EL REGISTRO VALIDAR QUE ESTA VACIO EN HumedadMol
          {
            if($hayparo=='si')
            $sql="UPDATE molino2_limpia SET humedadMol='100' ,idMol='".$usuariodemolino."' WHERE id='".$numrepetidos[0]."'";
            else
            $sql="UPDATE molino2_limpia SET humedadMol='".$humedad."' ,idMol='".$usuariodemolino."' WHERE id='".$numrepetidos[0]."'";
          }
        if($numrepetidos[0] =="")//si no hay registros HACEMOS UN INSERT COMPLETO
          {
              if($hayparo=='si')
              $sql="INSERT INTO molino2_limpia (fecha,hora,humedadMol,humedadLab,idMol,idLab,fechainsertado) VALUES ('$fecha','$horas','100','','$usuariodemolino','','$fechaactual')";
              else
              $sql="INSERT INTO molino2_limpia (fecha,hora,humedadMol,humedadLab,idMol,idLab,fechainsertado) VALUES ('$fecha','$horas','$humedad','','$usuariodemolino','','$fechaactual')";
          }

          if($numrepetidos[1]>0){
          echo "Ya insertaste datos en la zona ".$zonanombre." en la etapa ".$etapas." y en la Hora ".$horas.".  ";
          echo "Si quieres cambiar el dato de esta hora ve a la parte de Editar";
          }
          else
          $resultad=mysqli_query($link,$sql);
}//FIN DE ZONA2-->>LIMPIA

if($zona == "2" and $etapas=="Trigo al T1") //ZONA  MOLINO 2 Y Caída del Banco
{
        //ANALISIS DE REPETIDOS
        $repetidos="select id,humedadMol from molino2_caidablanco where fecha='".$fecha."' and hora='".$horas."'";
        $cantidadrepetidos=mysqli_query($link,$repetidos);
        mysqli_data_seek($cantidadrepetidos,0);
        $numrepetidos = mysqli_fetch_row($cantidadrepetidos);
        //FIN DE REPETIDOS

        if($numrepetidos[0] >= 1 and $numrepetidos[1]==0) //sI EXISTE EL REGISTRO VALIDAR QUE ESTA VACIO EN HumedadMol
          {
            if($hayparo=='si')
              $sql="UPDATE molino2_caidablanco SET humedadMol='100' ,idMol='".$usuariodemolino."' WHERE id='".$numrepetidos[0]."'";
            else
              $sql="UPDATE molino2_caidablanco SET humedadMol='".$humedad."' ,idMol='".$usuariodemolino."' WHERE id='".$numrepetidos[0]."'";
          }
        if($numrepetidos[0] =="")//si no hay registros HACEMOS UN INSERT COMPLETO
          {
              if($hayparo=='si')
              $sql="INSERT INTO molino2_caidablanco (fecha,hora,humedadMol,humedadLab,idMol,idLab,fechainsertado) VALUES ('$fecha','$horas','100','','$usuariodemolino','','$fechaactual')";
              else
              $sql="INSERT INTO molino2_caidablanco (fecha,hora,humedadMol,humedadLab,idMol,idLab,fechainsertado) VALUES ('$fecha','$horas','$humedad','','$usuariodemolino','','$fechaactual')";
          }

          if($numrepetidos[1]>0){
          echo "Ya insertaste datos en la zona ".$zonanombre." en la etapa ".$etapas." y en la Hora ".$horas.".  ";
          echo "Si quieres cambiar el dato de esta hora ve a la parte de Editar";
          }
          else
          $resultad=mysqli_query($link,$sql);
}//FIN DE ZONA2-->>Caída del Banco

if($zona == "2" and $etapas=="Harina") //ZONA  MOLINO 2 Y Caída del Banco
{
        //ANALISIS DE REPETIDOS
        $repetidos="select id,humedadMol from molino2_molienda where fecha='".$fecha."' and hora='".$horas."'";
        $cantidadrepetidos=mysqli_query($link,$repetidos);
        mysqli_data_seek($cantidadrepetidos,0);
        $numrepetidos = mysqli_fetch_row($cantidadrepetidos);
        //FIN DE REPETIDOS

        if($numrepetidos[0] >= 1 and $numrepetidos[1]==0) //sI EXISTE EL REGISTRO VALIDAR QUE ESTA VACIO EN HumedadMol
          {
              if($hayparo=='si')
              $sql="UPDATE molino2_molienda SET humedadMol='100' ,idMol='".$usuariodemolino."' WHERE id='".$numrepetidos[0]."'";
              else
              $sql="UPDATE molino2_molienda SET humedadMol='".$humedad."' ,idMol='".$usuariodemolino."' WHERE id='".$numrepetidos[0]."'";
          }
        if($numrepetidos[0] =="")//si no hay registros HACEMOS UN INSERT COMPLETO
          {
              if($hayparo=='si')
              $sql="INSERT INTO molino2_molienda (fecha,hora,humedadMol,humedadLab,cenizas,idMol,idLab,fechainsertado) VALUES ('$fecha','$horas','100','','','$usuariodemolino','','$fechaactual')";
              else
              $sql="INSERT INTO molino2_molienda (fecha,hora,humedadMol,humedadLab,cenizas,idMol,idLab,fechainsertado) VALUES ('$fecha','$horas','$humedad','','','$usuariodemolino','','$fechaactual')";
          }

          if($numrepetidos[1]>0){
          echo "Ya insertaste datos en la zona ".$zonanombre." en la etapa ".$etapas." y en la Hora ".$horas.".  ";
          echo "Si quieres cambiar el dato de esta hora ve a la parte de Editar";
          }
          else
          $resultad=mysqli_query($link,$sql);
}//FIN DE ZONA2-->>MOLIENDA
////////FIN DE ZONA2/////////////////FIN DE ZONA2/////////////////////FIN DE ZONA2////////////////FIN DE ZONA2///////FIN DE ZONA2///////////////////////FIN DE ZONA2


//333333333333333333333333333333333333333333333333333333333333333333333333333333333333333333333333333333333333333333333333333333333333333333333333333333333333333333333333
if($zona == "3" and $etapas=="Mezclas Continuas") //ZONA  MOLINO 3 Y Mezclas Continuas
{
        //ANALISIS DE REPETIDOS
        $repetidos="select id,humedadMol from mezclas_continuas where fecha='".$fecha."' and hora='".$horas."'";
        $cantidadrepetidos=mysqli_query($link,$repetidos);
        mysqli_data_seek($cantidadrepetidos,0);
        $numrepetidos = mysqli_fetch_row($cantidadrepetidos);
        //FIN DE REPETIDOS

        if($numrepetidos[0] >= 1 and $numrepetidos[1]==0) //sI EXISTE EL REGISTRO VALIDAR QUE ESTA VACIO EN HumedadMol
          {
            if($hayparo=='si')
              $sql="UPDATE mezclas_continuas SET humedadMol='100' ,idMol='".$usuariodemolino."' WHERE id='".$numrepetidos[0]."'";
              else
              $sql="UPDATE mezclas_continuas SET humedadMol='".$humedad."' ,idMol='".$usuariodemolino."' WHERE id='".$numrepetidos[0]."'";
          }
        if($numrepetidos[0] =="")//si no hay registros HACEMOS UN INSERT COMPLETO
          {
              if($hayparo=='si')
              $sql="INSERT INTO mezclas_continuas (fecha,hora,humedadMol,humedadLab,cenizas,idMol,idLab,fechainsertado) VALUES ('$fecha','$horas','100','','','$usuariodemolino','','$fechaactual')";
              else
              $sql="INSERT INTO mezclas_continuas (fecha,hora,humedadMol,humedadLab,cenizas,idMol,idLab,fechainsertado) VALUES ('$fecha','$horas','$humedad','','','$usuariodemolino','','$fechaactual')";
          }

          if($numrepetidos[1]>0){
          echo "Ya insertaste datos en la zona ".$zonanombre." en la etapa ".$etapas." y en la Hora ".$horas.".  ";
          echo "Si quieres cambiar el dato de esta hora ve a la parte de Editar";
          }
          else
          $resultad=mysqli_query($link,$sql);
}//FIN DE ZONA3-->>Mezclas

if($zona == "3" and $etapas=="Empaque") //ZONA  MOLINO 2 Y Caída del Banco
{
        //ANALISIS DE REPETIDOS
        $repetidos="select id,humedadMol from mezclas_empaque where fecha='".$fecha."' and hora='".$horas."'";
        $cantidadrepetidos=mysqli_query($link,$repetidos);
        mysqli_data_seek($cantidadrepetidos,0);
        $numrepetidos = mysqli_fetch_row($cantidadrepetidos);
        //FIN DE REPETIDOS

        if($numrepetidos[0] >= 1 and $numrepetidos[1]==0) //sI EXISTE EL REGISTRO VALIDAR QUE ESTA VACIO EN HumedadMol
          {
            if($hayparo=='si')
              $sql="UPDATE mezclas_empaque SET humedadMol='100' ,idMol='".$usuariodemolino."' WHERE id='".$numrepetidos[0]."'";
            else
              $sql="UPDATE mezclas_empaque SET humedadMol='".$humedad."' ,idMol='".$usuariodemolino."' WHERE id='".$numrepetidos[0]."'";
          }
        if($numrepetidos[0] =="")//si no hay registros HACEMOS UN INSERT COMPLETO
          {
              if($hayparo=='si')
              $sql="INSERT INTO mezclas_empaque (fecha,hora,humedadMol,humedadLab,cenizas,idMol,idLab,fechainsertado) VALUES ('$fecha','$horas','100','','','$usuariodemolino','','$fechaactual')";
              else
              $sql="INSERT INTO mezclas_empaque (fecha,hora,humedadMol,humedadLab,cenizas,idMol,idLab,fechainsertado) VALUES ('$fecha','$horas','$humedad','','','$usuariodemolino','','$fechaactual')";
          }

          if($numrepetidos[1]>0){
          echo "Ya insertaste datos en la zona ".$zonanombre." en la etapa ".$etapas." y en la Hora ".$horas.".  ";
          echo "Si quieres cambiar el dato de esta hora ve a la parte de Editar";
          }
          else
        $resultad=mysqli_query($link,$sql);
}//FIN DE ZONA3-->>Empaque

if($zona == "3" and $etapas=="Envasado") //ZONA  MOLINO 2 Y Caída del Banco
{
        //ANALISIS DE REPETIDOS
        $repetidos="select id,humedadMol from mezclas_envasado where fecha='".$fecha."' and hora='".$horas."'";
        $cantidadrepetidos=mysqli_query($link,$repetidos);
        mysqli_data_seek($cantidadrepetidos,0);
        $numrepetidos = mysqli_fetch_row($cantidadrepetidos);
        //FIN DE REPETIDOS

        if($numrepetidos[0] >= 1 and $numrepetidos[1]==0) //sI EXISTE EL REGISTRO VALIDAR QUE ESTA VACIO EN HumedadMol
          {
            if($hayparo=='si')
              $sql="UPDATE mezclas_envasado SET humedadMol='100' ,idMol='".$usuariodemolino."' WHERE id='".$numrepetidos[0]."'";
            else
              $sql="UPDATE mezclas_envasado SET humedadMol='".$humedad."' ,idMol='".$usuariodemolino."' WHERE id='".$numrepetidos[0]."'";
          }
        if($numrepetidos[0] =="")//si no hay registros HACEMOS UN INSERT COMPLETO
          {
              if($hayparo=='si')
              $sql="INSERT INTO mezclas_envasado (fecha,hora,humedadMol,humedadLab,cenizas,idMol,idLab,fechainsertado) VALUES ('$fecha','$horas','100','','','$usuariodemolino','','$fechaactual')";
              else
              $sql="INSERT INTO mezclas_envasado (fecha,hora,humedadMol,humedadLab,cenizas,idMol,idLab,fechainsertado) VALUES ('$fecha','$horas','$humedad','','','$usuariodemolino','','$fechaactual')";
          }

          if($numrepetidos[1]>0){
          echo "Ya insertaste datos en la zona ".$zonanombre." en la etapa ".$etapas." y en la Hora ".$horas.".  ";
          echo "Si quieres cambiar el dato de esta hora ve a la parte de Editar";
          }
          else
          $resultad=mysqli_query($link,$sql);
}//FIN DE ZONA3-->>ENVASADO

?>
<footer id="pie">
  Copyright© 2018. Ing. Jose Ignacio Hernandez Villafuerte. All rights reserved.
</footer>
</body>

</html>
