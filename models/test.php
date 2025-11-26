<?


///////////////////CONFIGURACIÓN A LAS BASES DE DATOS POSTGRESQL (SIGESH) Y MYSQL (SISTEMA DE COMPRAS) ///////////////////////////

////////////////////POSTGRESQL///////////////
function ConectarsePost()
{
	if (!($conexionpostgresql = pg_connect("host=192.168.0.203 port=5432 dbname=ALTEA_SP_RN_PROD user=pablon password=pablon")))
	{
    $fp = fopen("/home/diez/logs-actualizacion/errorconexionpost-".date("Y-m-d H:i:s").".txt", "w");
    fputs ($fp, "NO SE PUDO CONECTAR A LA BASE DE DATOS POSTGRESQL, REVISAR...");
    fclose ($fp);
    exit();
	}
	return $conexionpostgresql;
}
//////////////////MYSQL///////////////
function ConectarseMysql()
{
  global $host, $puerto, $usuario, $contrasena, $baseDeDatos, $tabla;
  $conexionmysql = new mysqli("192.168.0.254","root","mcsajc74","haz");
   
  if (mysqli_connect_error()) {
    $fp = fopen("/home/diez/logs-actualizacion/errorconexionmysql-".date("Y-m-d H:i:s").".txt", "w");

    fputs ($fp, "NO SE PUDO CONECTAR A LA BASE DE DATOS MYSQL, REVISAR...");
    fclose ($fp);
    exit();
  }    
 return $conexionmysql;
 }

////////////////////CONEXION A LAS BASES DE DATOS MYSQL Y POSTGRESQL /////////////////////////////////

$conexionpostgresql = ConectarsePost();
$conexionmysql = ConectarseMysql();

/////////////////////CIERRE DE LAS CONEXIONES A LAS BASES DE DATOS /////////////////////////////

function cerraconexion( $conexionmysql){
  pg_close(pg_connect("host=192.168.0.203 port=5432 dbname=ALTEA_SP_RN_PROD user=pablon password=pablon"));
  $conexionmysql->close();

}

////////////////////OBTENER LOS PACIENTES  DE LA BASE DEL SISTEMA SIGESH ///////////////////////////////////

date_default_timezone_set("America/Argentina/Buenos_Aires");
  $fecha_actual = date('Y-m-d');
  $fecha_pasada = strtotime('-26 day', strtotime($fecha_actual));
  $fecha_pasada = date('Y-m-d', $fecha_pasada);
  $sql="SELECT emperson.empenumero,emperson.empeapenom, emphc.emphcnro, emperson.empesexo , emperson.empefecnac, emdomici.emdocanomb,emdomici.emdonumero, emtelefo.emtenumcom,emdocume.emdcnumero
    FROM public.emperson
    JOIN emphc ON emperson.empenumero = emphc.empenumero --historia clinica
    JOIN emdomici ON emperson.empenumero = emdomici.empenumero --domicilio //traer el primero
    JOIN emdocume ON emperson.empenumero = emdocume.empenumero --documentos //  traer el primero
    LEFT JOIN emtelefo ON emperson.empenumero = emtelefo.empenumero -- telefonos //  traer el primer
    WHERE
    emphc.emphccacod =100 AND  emperson.embajcodig is null -- pertenece al hospital y no esta dado de baja
    AND emphc.emphcfecha BETWEEN '".$fecha_pasada."' AND '".$fecha_actual."'"; //incluir tambien la especialidad


if (pg_send_query($conexionpostgresql, $sql)) {
  $RSPac=pg_get_result($conexionpostgresql);
  if ($RSPac) {
    $estado = pg_result_error_field($RSPac, PGSQL_DIAG_SQLSTATE);
    if ($estado != 0) {
      $textoInsert="";

      $fp = fopen("/home/diez/logs-actualizacion/consultabd-".date("Y-m-d H:i:s").".txt", "w");
      fputs ($fp, "ERROR DE CONSULTA A LA BASE DE DATOS SIGESH, REVISAR...");
      fclose ($fp);
      cerraconexion( $conexionmysql);
      exit();

    }
  }  
}

////////////////////////////// RECORRER LOS PACIENTES OBTENIDOS DEL SIGESH E INSERTARLOS A LA SISTEMA DE COMPRAS ///////////////////
  $textoInsert = "";
  $textoNoinsertError = "";
  $conexionmysql->autocommit(FALSE);

  while($pacientePostgres = pg_fetch_object($RSPac) )
  {   

      $pacientesMysql = $conexionmysql-> query(
      'SELECT * FROM pacientes WHERE idpaciente=' . $pacientePostgres->emphcnro);
        if (mysqli_num_rows($pacientesMysql) == 0)
        {
		echo $pacientePostgres->emphcnro."---";

	$checkQuery = "SELECT 1 FROM hclinicas WHERE idhclinica = " . $pacientePostgres->emphcnro;
		

	$checkResult = $conexionmysql->query($checkQuery);

	if ($checkResult && $checkResult->num_rows > 0) {
	    // El registro existe, entonces hacer un UPDATE
	    $sql = "UPDATE hclinicas SET fecha = '$fecha_actual' WHERE idhclinica = " . $pacientePostgres->emphcnro;
	} else {
	    // El registro no existe, entonces hacer un INSERT
	    $sql = "INSERT INTO hclinicas (idhclinica, fecha) VALUES(" . $pacientePostgres->emphcnro . ", '$fecha_actual')";
	}


	if (!$conexionmysql->query($sql)) {
	    die("Error en la consulta: " . $conexionmysql->error);
	}

        $VarCam = "INSERT INTO pacientes(";
        $VarDat = "VALUES(";
        $VarCam .= "idpaciente, ";
        $VarDat .= trim($pacientePostgres->emphcnro) . ", ";
        $VarCam .= "idhclinica, ";
        $VarDat .= trim($pacientePostgres->emphcnro) . ", ";
        $VarCam .= "apellido, ";
        $VarDat .= "'" . $conexionmysql->real_escape_string(trim(utf8_decode($pacientePostgres->empeapenom))) . "', ";
        $VarCam .= "calle, ";
        $VarDat .= "'" . $conexionmysql->real_escape_string(trim(utf8_decode($pacientePostgres->emdocanomb)) . ' ' . trim($pacientePostgres->emdonumero)) . "', ";
        $VarCam .= "documento, ";
        $VarDat .= trim($pacientePostgres->emdcnumero) . ", ";
        $VarCam .= "sexo, ";
        $VarDat .= "'" . $conexionmysql->real_escape_string(trim($pacientePostgres->empesexo)) . "', ";
        $VarCam .= "telefonos, ";
        $VarDat .= "'" . $conexionmysql->real_escape_string(trim($pacientePostgres->emtenumcom)) . "', ";
        $VarCam .= "observaciones, ";
        $VarDat .= "NULL, ";
        $VarCam .= "fecultconsulta, ";
        $VarDat .= "NULL, ";
        $VarCam .= "fecnacimiento) ";
        $VarDat .= "'" . $conexionmysql->real_escape_string(trim($pacientePostgres->empefecnac)) . "')";
        $sql = $VarCam . $VarDat;
        $result = $conexionmysql->query($sql);


          if ( $result === false ) {
            //Enviar por correo

            $textoNoinsertError .= "El registro ".trim($pacientePostgres->emphcnro)." no se inserto, revisar lo sucedido. \n";
          }else{

            $textoInsert .= "Se inserto el registro ".trim($pacientePostgres->emphcnro)." en la base haz. \n";        
    }
        }


  }


  // Commit transaccion
  if (!$conexionmysql->commit()) {
    $textoInsert="";

    $fp = fopen("/home/diez/logs-actualizacion/error-transaccion-".date("Y-m-d H:i:s").".txt", "w");
    fputs ($fp, "Fallo la transaccion, revisar...");
    fclose ($fp);
    cerraconexion($conexionmysql);
    exit();
  }
  // Rollback transaccion
  $conexionmysql->rollback();

  if ($textoInsert!==""){

    $fp = fopen("/home/diez/logs-actualizacion/log-".date("Y-m-d H:i:s").".txt", "w");
    fputs ($fp, $textoInsert);
    fclose ($fp);
  }
  if ($textoNoinsertError!==""){

    $fp = fopen("/home/diez/logs-actualizacion/logErrorInsert-".date("Y-m-d H:i:s").".txt", "w");
    fputs ($fp, $textoNoinsertError);
    fclose ($fp);
  }

  cerraconexion($conexionmysql);
  echo "</br>";
  echo "###################### </br>";
  echo "# Fin de Importación # </br>";
  echo "###################### </br>";



?>
