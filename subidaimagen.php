<html>
<head>
<title>Procesa una subida de archivos </title>
<meta charset="UTF-8">
<link href="default.css" rel="stylesheet" type="text/css" />
</head>
<?php

function pruebaFichero($archivo) {//Funcion que nos indica la subida o error de la imagen
// se incluyen esta tabla de  códigos de error que produce la subida de archivos en PHPP
// Posibles errores de subida segun el manual de PHP
    $codigosErrorSubida= [
        0 => 'Subida correcta',
        1 => 'El tamaño del archivo excede el admitido por el servidor',  // directiva upload_max_filesize en php.ini
        2 => 'El tamaño del archivo excede el admitido por el cliente',  // directiva MAX_FILE_SIZE en el formulario HTML
        3 => 'El archivo no se pudo subir completamente',
        4 => 'No se seleccionó ningún archivo para ser subido',
        6 => 'No existe un directorio temporal donde subir el archivo',
        7 => 'No se pudo guardar el archivo en disco',  // permisos
        8 => 'Una extensión PHP evito la subida del archivo'  // extensión PHP
    ]; 
    $mensaje = ''; //devuelve un mensaje vacio si no hay ningun error, indica que es correcto
	// si no se reciben el directorio de alojamiento y el archivo, se descarta el proceso
    if (! isset($_FILES[$archivo]['name'])) {
        $mensaje .= 'ERROR: No se indicó el archivo y/o no se indicó el directorio';
    } else {   
		// se reciben el directorio de alojamiento y el archivo
        $directorioSubida = "C:\Users\carlo\imgusers";
		// Información sobre el archivo subido
        $nombreFichero   =   $_FILES[$archivo]['name'];
        $tipoFichero     =   $_FILES[$archivo]['type'];
        $tamanioFichero  =   $_FILES[$archivo]['size'];
        $temporalFichero =   $_FILES[$archivo]['tmp_name'];
        $errorFichero    =   $_FILES[$archivo]['error'];

		// Obtengo el código de error de la operación, 0 si todo ha ido bien
        if ($errorFichero > 0) {
            $mensaje .= "Error Nº $errorFichero: "
            . $codigosErrorSubida[$errorFichero] . ' <br />';
        } else { // subida correcta del temporal
            if($tipoFichero == "image/jpeg" || $tipoFichero  == "image/png"){ //comprobacion de tipo de archivo
                if(!(file_exists($directorioSubida ."/". $nombreFichero))){ //comprobacion si ese fichero ya existe		
                        echo "Archivo guardado con exito <br>";
						//Le puse un echo en vez de mensaje porque no me subia el archivo al mover las carpetas
                } else {
                    $mensaje.= 'El archivo ya existe en la ruta especificada <br>';      
                }
            } else {
                $mensaje .= 'El tipo de archivo no es el correcto <br>';
            }
        }
    }
    return $mensaje;
}

function moverArchivo($archivo) {
    $mensaje="";
    $directorioSubida = "C:\Users\carlo\imgusers";
    $temporalFichero = $_FILES[$archivo]['tmp_name'];
	//Si se mueve correctamente => move_uploaded_file(file, dest)
    if (move_uploaded_file($temporalFichero,  $directorioSubida .'/'. $_FILES[$archivo]['name']) == true) {
        $mensaje .= 'Archivo guardado en: ' . $directorioSubida .'/'. $_FILES[$archivo]['name'] . ' <br />';
    } else {
        $mensaje .= 'ERROR: Archivo no guardado correctamente <br />';
    }
    return $mensaje;
}

?>

<body>
<div id="container"  style="width: 600px;">
<div id="header">
<h2>Subida y alojamiento de archivos en el servidor</h2>
</div>

	<h3>Archivo 1</h3>
	<p>
	
	
	<?php
	if(pruebaFichero('archivo1') == ''){
	    // veo si el tamaño de los ficheros es el correcto, chequeo si los 2 son menores que 300KB o 1 de 200KB
		// en bytes
	    if($_FILES['archivo1']['size'] + $_FILES['archivo2']['size'] < 300000
	        && $_FILES['archivo1']['size'] < 200000 ){
	       //muevo el archivo 
	       echo moverArchivo('archivo1');
	       echo "<br>";
	    } else {
	        echo "El archivo es demasiado grande";   
	    }    
	} else {    
	    echo pruebaFichero('archivo1');
	}
	?>
	</p>
	
	<h3>Archivo 2</h3>
	<p>
	
	
	<?php
	if(pruebaFichero('archivo2') == ''){	    
	    if($_FILES['archivo1']['size'] + $_FILES['archivo2']['size'] < 300000
	          && $_FILES['archivo2']['size'] < 200000){
	        
	            echo moverArchivo('archivo2');
	            echo "<br>";	            
	    } else {	        
	        echo "El archivo es demasiado grande";
	    }
	} else {  
	    echo pruebaFichero('archivo2');
	}
	
    ?>
	</p>
	
<br/>
<?php
header( "refresh:3;url=imagen.html" ); //para volver al formulario
?> 
</body>
</html>