<?php
    $this->pageTitle = Yii::app()->name . ' - Dashboard';
?>

<div class="content">
    <h1>Dashboard</h1>

    <p>Bienvenido, <?php echo Yii::app()->user->getUser()->nombre; ?></p>
    <p>Desde aquí podrás gestionar los animales del refugio.</p>
    <p>Accede a las distintas opciones desde el menú de navegación en la parte superior de la página.</p>

    <div class="alert alert-info"><strong>Aviso</strong> Si encuentras algún problema con la aplicación, rogamos te pongas
    	en contacto con uno de los encargados del refugio</div>

	<p>Muchas gracias por tu trabajo.</p>
	<p>Atentamente,</p>
	<p>El equipo de dirección de Asoka</p>
</div>