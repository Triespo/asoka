<?php
$this->renderPartial('//email/_header'); ?>
<p>Hola <?php echo $user->fullName; ?>,</p>
<p>Gracias por registrarte en Asoka, antes de empezar a usar nuestro servicio necesitamos que confirmes tu dirección de correo.Por favor haz click en este link:<br>
<?php echo CHtml::link($user->confirmationLink(), $user->confirmationLink()); ?></p>
<?php $this->renderPartial('//email/_footer');
?>
