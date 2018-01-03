<?php
$title= $titulo;
$css =[];
$js=[];
?>

<?php include'../resources/views/mod/start.php';?>

<!-- navbar -->
<?php include'../resources/views/mod/navbar.php';?>

<!-- Body -->

<section class="container">
    <div class="jumbotron text-center">
    <div class="container">
        <h1 class="display-4 text-encargapp"><?php echo $titulo; ?></h1>
        <p class="lead"><?php echo $message; ?></p>
    </div>
    </div>
</section>
<!-- finde body -->
<?php
if ( isset($error) )
    echo $error;
?>

<?php include'../resources/views/mod/end.php';?>

