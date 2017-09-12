<?php
/*====================================================================+
|| # Formulario PHP - Desarrollo Web 2016 - Universidad de Valparaíso
|+====================================================================+
|| # Copyright © 2016 Miguel González Aravena. All rights reserved.
|| # https://github.com/MiguelGonzalezAravena/FormularioPHP
|+====================================================================*/

// Función para evitar inyecciones
function Filtro($texto) {
  return htmlspecialchars(trim($texto), ENT_QUOTES);
}

function DigitoVerificador($rut) {
  while($rut[0] == "0") {
    $rut = substr($rut, 1);
  }

  $factor = 2;
  $suma = 0;
  for($i = strlen($rut) - 1; $i >= 0; $i--) {
    $suma += $factor * $rut[$i];
    $factor = $factor % 7 == 0 ? 2 : $factor + 1;
  }

  $dv = 11 - $suma % 11;
  $dv = $dv == 11 ? 0 : ($dv == 10 ? "K" : $dv);
  return $dv;
}

// Variables
$directorio = dirname($_SERVER['SCRIPT_FILENAME']).'/assets/';

if(!file_exists($directorio) && !is_dir($directorio))
  mkdir($directorio);

$extension = explode('.', $_FILES['foto']['name']);
$nombre_foto = time() . '.' . $extension[1];
$foto_subida = $directorio . basename($nombre_foto);
$enviado = isset($_POST['enviado']) ? (int) $_POST['enviado'] : 0;
$contenido = isset($_POST['contenido']) ? (int) $_POST['contenido'] : 0;
$anio = isset($_POST['anio']) ? (int) $_POST['anio'] : 0;
$terminos = isset($_POST['terminos']) ? (int) $_POST['terminos'] : 0;
$enviarmails = isset($_POST['enviomails']) ? (int) $_POST['enviomails'] : 0;
$nombre = isset($_POST['nombre']) ? Filtro($_POST['nombre']) : '';
$correo = isset($_POST['correo']) ? Filtro($_POST['correo']) : '';
$contrasena = isset($_POST['contrasena']) ? Filtro($_POST['contrasena']) : '';
$rut = isset($_POST['rut']) ? Filtro($_POST['rut']) : '';
$foto = isset($_FILES['foto']) ? $_FILES['foto'] : '';
$descripcion = isset($_POST['descripcion']) ? Filtro($_POST['descripcion']) : '';
$sexo = isset($_POST['sexo']) ? Filtro($_POST['sexo']) : '';
$error = '';
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <meta name="description" content="Proyecto para enseñar el funcionamiento de un formulario en PHP">
  <meta name="keywords" content="html, bootstrap, php, formulario, desarrollo, web">
  <meta name="author" content="Miguel González Aravena">
  <title>Formulario enviado</title>
  <!-- CSS -->
  <link rel="stylesheet" type="text/css" href="bower_components/bootstrap/dist/css/bootstrap.min.css">
  <link rel="stylesheet" type="text/css" href="bower_components/bootstrap/dist/css/bootstrap-theme.min.css">
</head>
<body>
<div class="container">
  <span style="padding-top: 10px;"></span>
<?php
// Mostrar contenido
if($enviado == 1 && $contenido == 1) {
  echo '<pre>';
  print_r($_POST);
  print_r($foto);
  echo '</pre>';
  exit;
} else if(empty($nombre)) {
  $error = 'Por favor, ingrese su nombre.';
} else if(empty($correo)) {
  $error = 'Por favor, ingrese su correo electrónico.';
} else if(empty($contrasena)) {
  $error = 'Por favor, ingrese su contraseña.';
} else if(empty($rut) || !is_numeric($rut)) {
  $error = 'Por favor, ingrese su RUT con números solamente';
} else if(empty($foto)) {
  $error = 'Por favor, seleccione su foto de perfil.';
} else if(empty($descripcion)) {
  $error = 'Por favor, ingrese su descripción.';
} else if(empty($anio)) {
  $error = 'Por favor, seleccione su año de ingreso.';
} else if(empty($sexo)) {
  $error = 'Por favor, ingrese su sexo.';
} else if(empty($terminos)) {
  $error = 'Debe aceptar los términos y condiciones para poder seguir.';
}

// Vista de error
if(!empty($error)) {
?>
<div class="alert alert-info">
  <i class="glyphicon glyphicon-info-sign"></i>
  <?php echo $error; ?>
</div>
<a href="./" class="btn btn-warning">
  <i class="glyphicon glyphicon-chevron-left"></i>
  Volver
</a>
<?php
// Vista de éxito
} else {
  // Subir imagen
  move_uploaded_file($foto['tmp_name'], $foto_subida);
?>
  <h3>¡Formulario enviado satisfactoriamente!</h3>
  <div class="panel panel-default">
    <div class="panel-heading">
      <h3 class="panel-title">Datos enviados</h3>
    </div>
    <div class="panel-body">
      <p>Bienvenido(a) <b><?php echo $nombre; ?></b>,</p>
      <p>Tu correo electrónico es <b><?php echo $correo; ?></b>, y tu contraseña tiene <b><?php echo strlen($contrasena); ?></b> caracteres.</p>
      <p>
        Tu foto de perfil es: <br />
        <img src="./assets/<?php echo $nombre_foto; ?>" class="thumbnail">
      </p>
      <p>
        Tu RUT es: <br />
        <blockquote>
          <?php echo number_format($rut, 0, ',', '.').'-'.DigitoVerificador($rut); ?>
        </blockquote>
      </p>
      <p>
        Tu descripción es: <br />
        <blockquote>
          <?php echo $descripcion; ?>
        </blockquote>
      </p>
      <p>
        Tu año de ingreso es: <b><?php echo $anio; ?></b>
      </p>
      <p>
<?php if($sexo == 'n'): ?>
        No <b>definiste</b> tu Sexo
<?php else: ?>
        Tu <b><?php echo ($terminos == 1 ? 'sí' : 'no'); ?></b> aceptaste los términos y condiciones.</b>
<?php endif; ?>
      </p>
<?php if($enviarmails): ?>
      <p>
        Tu aceptaste recibir correo basura de la empresa.</b>
      </p>
<?php endif; ?>
    </div>
    <div class="panel-footer">
      <div class="text-right">
        <a href="./" class="btn btn-primary">
          <i class="glyphicon glyphicon-chevron-left"></i>
          Volver
        </a>
      </div>
    </div>
  </div>
<?php } ?>
  <!-- Pie de página -->
  <footer>
    <div class="text-center">
      <i class="glyphicon glyphicon-leaf"></i>
      Desarrollado por <a href="https://github.com/MiguelGonzalezAravena" target="_blank">Miguel González Aravena</a>
    </div>
  </footer>
</div>
</body>
</html>