<?php
    $this->pageTitle = Yii::app()->name . ' - Dashboard';
?>

<div class="content">
    <h1>Dashboard</h1>

    <p>Bienvenido, <?php echo Yii::app()->user->getUser()->nombre; ?></p>
    <p>Desde aquí podrás llevar el control de completo de toda la aplicación.</p>
    <p>Accede a las distintas opciones desde el menú de navegación en la parte superior de la página.</p>
</div>