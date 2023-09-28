<?php /* @var $this Controller */ ?>
<!DOCTYPE html>
<html lang="es-ES">
	<head>
	    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta name="language" content="es-ES" />
	    <meta http-equiv="X-UA-Compatible" content="IE=edge">
	    <meta name="viewport" content="width=device-width, initial-scale=1">

	    <title><?php echo $this->pageTitle; ?></title>

	    <link rel="shortcut icon" href="http://getbootstrap.com/assets/ico/favicon.ico">

	    <!-- Bootstrap css-->
	    <link href="<?php echo Common::css('bootstrap.min.css'); ?>" rel="stylesheet">
	    <link href="<?php echo Common::css('datepicker.css'); ?>" rel="stylesheet">
	    
	    <link href="<?php echo Common::js('select2-3.4.8/select2.css'); ?>" rel="stylesheet">
	    <link href="<?php echo Common::js('select2-3.4.8/select2-bootstrap.css'); ?>" rel="stylesheet">
	    
	    <!-- FontAwesome -->
	    <link href="<?php echo Common::css('font-awesome.min.css'); ?>" rel="stylesheet">
	    <!-- Main css-->
	    <link href="<?php echo Common::css('main.css'); ?>" rel="stylesheet">

	    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
	    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
	    <!--[if lt IE 9]>
	      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
	      <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
	    <![endif]-->
	</head>
	<body>
	    <header>
	      <div class="bottom">
	        <div class="row">
	          <div class="col-md-3">
            	<a href="<?php echo Yii::app()->homeUrl; ?>">
            		<img src="<?php echo Common::img('logo.png'); ?>">
            	</a>
	          </div>
	          <div class="col-md-9 right">
	            <ul class="nav">
		            <?php if (Yii::app()->user->isGuest) { ?>
 		              	<li class="<?php echo (Yii::app()->getController()->action->id === "index")?'active':'' ?>">
 		              		<a href="<?php echo Yii::app()->homeUrl; ?>">Inicio</a>
 		              	</li>
 		              	<li class="<?php echo (Yii::app()->getController()->action->id === "registro")?'active':'' ?>">
 							<a href="<?php echo $this->createUrl('/registro'); ?>">Registro</a>
 						</li>
 		              	<li class="<?php echo (Yii::app()->getController()->action->id === "login")?'active':'' ?>">
 							<a href="<?php echo $this->createUrl('/login'); ?>">Login</a>
 						</li>
					<?php  } else { ?>
						<li class="<?php echo (Yii::app()->getController()->action->id === "dashboard")?'active':'' ?>">
		              		<a href="<?php echo Yii::app()->homeUrl; ?>">Dashboard</a>
		              	</li>
	              	<?php } ?>
 					<?php if (Yii::app()->user->isAdmin()) { ?>
		              	<?php 
		              		$usuarioRoutes = array(
	              				'usuarios',
	              				'newUser',
	              				'createUser',
	              				'editUser',
	              				'newAdoptante'
             				);
              			?>
		              	<li class="<?php echo (in_array(Yii::app()->getController()->action->id, $usuarioRoutes))?'active':'' ?>">
		              		<a href="<?php echo $this->createUrl('/admin/usuarios'); ?>">Usuarios</a>
		              	</li>
		              	<?php 
		              		$animalRoutes = array(
	              				'animales',
	              				'createAnimal',
	              				'editAnimal'
             				);
              			?>
		              	<li class="<?php echo (in_array(Yii::app()->getController()->action->id, $animalRoutes))?'active':'' ?>">
		              		<a href="<?php echo $this->createUrl('/admin/animales'); ?>">Animales</a>
		              	</li>
		              	<?php 
	              			$viajeRoutes = array(
              					'listarViajes',
              					'createViaje'
	         				);
	          			?>

		              	<li class="<?php echo (in_array(Yii::app()->getController()->action->id, $viajeRoutes))?'active':'' ?>">
					 		<a href="<?php echo $this->createUrl('viajes'); ?>"
					 			data-toggle="dropdown">Viajes <span class="caret"></span></a>
					 		<ul class="dropdown-menu" aria-labelledby="dLabel">
					 			<li>
					 				<a href="<?php echo $this->createUrl('/viajes'); ?>">Listar Viajes</a>
					 			</li>
					 			<li>
					 				<a href="<?php echo $this->createUrl('/viajes/nuevo'); ?>">Crear Viaje</a>
					 			</li>
					 		</ul>
					 	</li>						
						<?php 
		              		$voluntarioRoutes = array(
	              				'voluntarios',
             				);
              			?>
		              	<li class="<?php echo (in_array(Yii::app()->getController()->action->id, $voluntarioRoutes))?'active':'' ?>">
		              		<a href="<?php echo $this->createUrl('/admin/voluntarios'); ?>"
		              			data-toggle="dropdown">Voluntarios <span class="caret"></span></a>								
					 		<ul class="dropdown-menu" aria-labelledby="dLabel">
					 			<li>
				              		<a href="<?php echo $this->createUrl('/admin/voluntarios'); ?>">Listar Voluntarios</a>
				              	</li>
				              	<li>
				              		<a href="<?php echo $this->createUrl('/admin/voluntarios/nuevo'); ?>">Crear Voluntario</a>
				              	</li>
			             	</ul>
					 	</li>
					 	<?php
					 		$adoptanteRoutes = array(
					 			'adoptantes',
					 		);
					 	?>
					 	<li class="<?php echo (in_array(Yii::app()->getController()->action->id, $adoptanteRoutes))?'active':'' ?>">
					 		<a href="<?php echo $this->createUrl('/admin/adoptantes'); ?>"
					 			data-toggle="dropdown">Adoptantes <span class="caret"></span></a>
					 		<ul class="dropdown-menu" aria-labelledby="dLabel">
					 			<li>
					 				<a href="<?php echo $this->createUrl('/admin/adoptantes'); ?>">Listar Adoptantes</a>
					 			</li>
					 			<li>
					 				<a href="<?php echo $this->createUrl('/admin/adoptantes/nuevo'); ?>">Crear Adoptante</a>
					 			</li>
					 		</ul>
					 	</li>	
					<?php } elseif (Yii::app()->user->isVoluntario()) { ?>
		              	<?php 
		              		$animalRoutes = array(
	              				'animales',
	              				'createAnimal',
	              				'editAnimal'
             				);
              			?>
		              	<li class="<?php echo (in_array(Yii::app()->getController()->action->id, $animalRoutes))?'active':'' ?>">
		              		<a href="<?php echo $this->createUrl('/voluntario/animales'); ?>">Animales</a>
		              	</li>

		              	<?php 
	              			$viajeRoutes = array(
              					'listarViajes',
              					'createViaje'
	         				);
	          			?>

		              	<li class="<?php echo (in_array(Yii::app()->getController()->action->id, $viajeRoutes))?'active':'' ?>">
					 		<a href="<?php echo $this->createUrl('viajes'); ?>"
					 			data-toggle="dropdown">Viajes <span class="caret"></span></a>
					 		<ul class="dropdown-menu" aria-labelledby="dLabel">
					 			<li>
					 				<a href="<?php echo $this->createUrl('/viajes'); ?>">Listar Viajes</a>
					 			</li>
					 			<li>
					 				<a href="<?php echo $this->createUrl('/viajes/nuevo'); ?>">Crear Viaje</a>
					 			</li>
					 		</ul>
					 	</li>
					<?php } ?>
					<?php if (!Yii::app()->user->isGuest) { ?>
						<?php 
		              		$mensajeRoutes = array(
	              				'index',
	              				'view',
	              				'nuevo',
	              				'enviar'
             				);
              			?>
		              	<li class="<?php echo (in_array(Yii::app()->getController()->action->id, $mensajeRoutes))?'active':'' ?>">
		              		<a href="<?php echo $this->createUrl('/mensajes'); ?>"
					 			data-toggle="dropdown">
					 			Mensajes 
					 			<span class="badge <?php echo (count(Yii::app()->user->getUser()->mensajesNuevos) > 0)?'badge-warning':''; ?>">
					 				<?php echo count(Yii::app()->user->getUser()->mensajesNuevos); ?></span>
					 			<span class="caret"></span>
					 		</a>
					 		<ul class="dropdown-menu" aria-labelledby="dLabel">
					 			<li>
					 				<a href="<?php echo $this->createUrl('/mensajes'); ?>">Ver mensajes</a>
					 			</li>
					 			<li>
					 				<a href="<?php echo $this->createUrl('/mensajes/nuevo'); ?>">Nuevo Mensaje</a>
					 			</li>
					 		</ul>
		              	</li>
					<?php } ?>
		        </ul>
	          </div>
	        </div>
        	<?php if (!Yii::app()->user->isGuest) { ?>
        	<div class="user-info">
          		<div>Bienvenido, <?php echo Yii::app()->user->getEmail(); ?></div>
          		<div><a href="<?php echo $this->createUrl('/site/logout'); ?>">Desconectar</a></div>
      		</div>
          	<?php } ?>
	      </div>
	    </header>
	    <div class="wrapper">
	      	<div class="full-height">
	      		<div class="container">
	      			<?php if (!Yii::app()->user->isGuest 
	      				&& count(Yii::app()->user->getUser()->mensajesNuevos) > 0
	      				&& !in_array(Yii::app()->getController()->action->id, $mensajeRoutes)) { ?>
				        <div class="alert-messages alert alert-info alert-dismissable">
				            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
				            Tienes mensajes sin leer, haz click <a href="<?php echo $this->createUrl('/mensajes');?>"><strong>aquí</strong></a> para verlos.
				        </div>
				    <?php } ?>
        			<?php echo $content; ?>
        		</div>
    		</div>
		</div>
		<footer>
	        <span>Copyright 2014 &copy; <a target="_blank" href="#">Asoka</a></span>
	        <span class="pull-right">Web por: <a target="_blank" href="#">@DSSTeam</a></span>
	    </footer>

	    <!-- jQuery -->
	    <script src="<?php echo Common::js('jquery.min.js'); ?>"></script>
	    <!-- Bootstrap js -->
	    <script src="<?php echo Common::js('bootstrap.min.js'); ?>"></script>
	    <script src="<?php echo Common::js('bootstrap-datepicker.js'); ?>"></script>
	    <script src="<?php echo Common::js('bootstrap-datepicker.es.js'); ?>"></script>
	    <script src="<?php echo Common::js('select2-3.4.8/select2.min.js'); ?>"></script>
	    
	    <!-- Main js -->
	    <script src="<?php echo Common::js('main.js'); ?>"></script>
	<body>
</html>
