<?php
$title='Recuperar contraseña';
$css =[];
$js=[];
?>

<?php include'../resources/views/mod/start.php';?>


<!-- navbar -->
<?php include'../resources/views/mod/navbar.php';?>

<!-- Body -->
<section class="container">
    <div class="card col-md-8 mx-auto my-3 add-shadow">
        <form action="<?php echo $url; ?>" method="POST">
            <fieldset>
                <legend class='text-info mb-0'>Recuperar contraseña:</legend>
                <small class="text-muted d-block">Haz solicitado la recuperación de tu contraseña, llena este formulario para crear una nueva.</small>
                <label for="password" class='small font-weight-bold'>Nueva Contraseña</label>
                <input type="text" class='form-control mb-2' name="password" id='password' required>
                <label for="passwordRepeat" class='small font-weight-bold'>Confirmar Contraseña:</label>
                <input type="text" class='form-control mb-3' name="passwordRepeat" id='passwordRepeat' required>
                <input type="submit" class='btn btn-sm btn-info mb-3' value="Restablecer contraseña">
            </fieldset>
        </form>
    </div>
</section>
<!-- fin de body -->
<?php
if ( isset($error) )
    echo $error;
?>

<?php include'../resources/views/mod/end.php';?>

