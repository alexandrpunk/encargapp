<?php
$title='Recuperar contraseña';
$css =[];
$js=[];
?>

<?php include'../resources/views/mod/start.php';?>

<!-- Body -->
<nav  class="navbar navbar-expand-md text-light bg-info">
   <div class="brand">encargapp</div>
</nav>
<section class="container">
    <div class="card col-md-8 mx-auto my-3">
        <form action="<?php echo $url; ?>" method="POST">
            <fieldset>
                <legend class='text-info mb-0'>Recuperar contraseña:</legend>
                <small class="text-muted d-block">Haz solicitado la recuperacion de tu contraseña, llena este formulario para crear una nueva.</small>
                <label for="password" class='small font-weight-bold'>Nueva Contraseña</label>
                <input type="text" class='form-control mb-2' name="password" id='password' required>
                <label for="passwordRepeat" class='small font-weight-bold'>Confirmar Contraseña:</label>
                <input type="text" class='form-control mb-3' name="passwordRepeat" id='passwordRepeat' required>
                <input type="submit" class='btn btn-sm btn-info mb-3' value="Restablecer contraseña">
            </fieldset>
        </form>
    </div>
</section>
<!-- finde body -->
<?php
if ( isset($error) )
    echo $error;
?>

<?php include'../resources/views/mod/end.php';?>

