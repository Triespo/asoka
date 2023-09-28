<?php
	$this->pageTitle = Yii::app()->name . ' - Registro';
?>

<div class="content">
    <div class="row">
        <div class="col-md-12">
    		<h1>Registro</h1>
    	</div>
    </div>

    <?php foreach(Yii::app()->user->getFlashes() as $key => $message) { ?>
        <div class="alert alert-<?php echo $key; ?> alert-dismissable">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            <?php echo $message ?>
        </div>
    <?php } ?>

    <div class="row">
        <div class="col-md-12">
        	<form id="create-user-form" class="form-horizontal required-form" role="form" method="post" 
                action="<?php echo $this->createUrl('/registro');?>">
				<div class="well well-sm">
					<strong>
						<i class="fa fa-asterisk"></i> Campo obligatorio
					</strong>
				</div>
				<div class="row">
					<div class="col-md-5">
						<div class="form-group">
							<label for="email">Email</label>
							<div class="input-group">
								<input type="text" class="form-control" name="email" id="email" 
									placeholder="Introduce un email" value="<?php echo $model->email; ?>" required />
								<span class="input-group-addon">
									<i class="fa fa-asterisk"></i>
								</span>
							</div>
						</div>
						<div class="form-group">
							<label for="password">Contraseña</label>
							<div class="input-group">
								<input type="password" class="form-control" name="password" id="password" 
									placeholder="Introduce una contraseña" required />
								<span class="input-group-addon">
									<i class="fa fa-asterisk"></i>
								</span>
							</div>
						</div>
						<div class="form-group">
							<label for="password_confirm">Repetir contraseña</label>
							<div class="input-group">
								<input type="password" class="form-control" name="password_confirm" 
									id="password_confirm" placeholder="Repite la contraseña" required />
								<span class="input-group-addon">
									<i class="fa fa-asterisk"></i>
								</span>
							</div>
						</div>
						<div class="form-group">
							<label for="dni">Dni</label>
							<div class="input-group-no-icon">
								<input type="text" class="form-control" name="dni" id="dni" 
									placeholder="Introduce el dni" value="<?php echo $model->dni; ?>" />
							</div>
						</div>
					</div>
					<div class="col-md-5 col-md-offset-2">
						<div class="form-group">
							<label for="nombre">Nombre</label>
							<div class="input-group">
								<input type="text" class="form-control" name="nombre" id="nombre" 
									placeholder="Introduce el nombre" value="<?php echo $model->nombre; ?>" 
									required />
								<span class="input-group-addon">
									<i class="fa fa-asterisk"></i>
								</span>
							</div>
						</div>
						<div class="form-group">
							<label for="apellidos">Apellidos</label>
							<div class="input-group">
								<input type="text" class="form-control" name="apellidos" id="apellidos" 
									placeholder="Introduce los apellidos" value="<?php echo $model->apellidos; ?>" 
									required />
								<span class="input-group-addon">
									<i class="fa fa-asterisk"></i>
								</span>
							</div>
						</div>
						<div class="form-group">
							<label for="direccion">Dirección</label>
							<div class="input-group">
								<input type="text" class="form-control" name="direccion" id="direccion" 
									placeholder="Introduce la dirección" value="<?php echo $model->direccion; ?>" 
									required />
								<span class="input-group-addon">
									<i class="fa fa-asterisk"></i>
								</span>
							</div>
						</div>
						<div class="form-group">
							<label for="tlf">Teléfono</label>
							<div class="input-group">
								<input type="text" class="form-control" name="telefono" id="telefono" 
									placeholder="Introduce el Telefóno" value="<?php echo $model->telefono; ?>" 
									required />
								<span class="input-group-addon">
									<i class="fa fa-asterisk"></i>
								</span>
							</div>
						</div>
						<div class="form-group">
							<label for="f_nacimiento">Fecha de nacimiento</label>
							<div class="input-group">
						  		<input type="text" class="form-control date-picker" name="f_nacimiento" 
						  			id="f_nacimiento" 
						  			value="<?php echo ($model->f_nacimiento)?date('d/m/Y', $model->f_nacimiento):''; ?>" />
						  		<span class="input-group-addon">
									<i class="fa fa-calendar"></i>
								</span>
							</div>
						</div>
					</div>
				</div>
      			<input type="submit" value="Enviar" class="btn btn-success pull-right">
            </form>
        </div>
    </div>
</div>
