<?php
	$this->pageTitle = Yii::app()->name . ' - Nuevo Animal';
	
	$mode = ($model->isNewRecord)?'Nuevo':'Editar';

	$this->breadcrumbs=array(
		'Animales' => array('animales'),
		$mode,
	);
?>

<div class="content">
	<div class="row">
        <div class="col-md-12">
        	<?php
				$this->widget('zii.widgets.CBreadcrumbs', array(
				    'links'=>$this->breadcrumbs,
			        'tagName'=>'ol',
			        'homeLink'=>false,
			        'separator'=>'',
			        'activeLinkTemplate'=>'<li><a href="{url}">{label}</a></li>',
			        'inactiveLinkTemplate'=>'<li class="active">{label}</li>',
			        'htmlOptions'=>array ('class'=>'breadcrumb')
			    )); 
		    ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
    		<h1><?php echo $mode; ?> Animal</h1>
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
        	<form id="create-animal-form" class="form-horizontal required-form" role="form" method="post"
        		enctype="multipart/form-data">
				<div class="well well-sm">
					<strong>
						<i class="fa fa-asterisk"></i> Campo obligatorio
					</strong>
				</div>
				<div class="row">
					<div class="col-md-5">
						<div class="form-group">
							<label for="nombre">Nombre</label>
							<div class="input-group">
								<input type="text" class="form-control" name="nombre" id="nombre" 
									placeholder="Introduce un nombre" value="<?php echo $model->nombre; ?>" required />
								<span class="input-group-addon">
									<i class="fa fa-asterisk"></i>
								</span>
							</div>
						</div>
						<div class="form-group">
							<label for="password">Descripcion</label>
							<div class="input-group">
								<textarea class="form-control" name="descripcion" id="descripcion" 
									placeholder="Introduce una descripción" rows="6" required><?php echo $model->descripcion; ?></textarea>
								<span class="input-group-addon">
									<i class="fa fa-asterisk"></i>
								</span>
							</div>
						</div>
						<div class="form-group">
							<div class="input-group">
								<select name="sexo">
			                        <option <?php echo ($model->sexo == $model::SEXO_MACHO)?'selected':'' ?> 
			                        	value="<?php echo $model::SEXO_MACHO; ?>">
			                            <?php echo $model::getSexoName($model::SEXO_MACHO); ?>
			                        </option>
			                        <option <?php echo ($model->sexo == $model::SEXO_HEMBRA)?'selected':'' ?> 
			                        	value="<?php echo $model::SEXO_HEMBRA; ?>">
			                            <?php echo $model::getSexoName($model::SEXO_HEMBRA); ?>
			                        </option>
			                    </select>
								<span class="input-group-addon">
									<i class="fa fa-asterisk"></i>
								</span>
							</div>
						</div>
					<?php if ($model->isNewRecord) { ?>
					</div>
					<div class="col-md-5 col-md-offset-2">
						<div class="form-group">
							<label for="imagen">Imagen</label>
							<div class="input-group">
								<input type="file" class="form-control" name="imagen" id="imagen" />
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
						<div class="form-group">
							<label for="tamanyo">Tamaño</label>
							<div class="input-group-no-icon">
								<input type="text" class="form-control" name="tamanyo" id="tamanyo" 
									placeholder="Introduce el tamaño" value="<?php echo $model->tamanyo; ?>" />
							</div>
						</div>
						<div class="form-group">
							<label for="peso">Peso</label>
							<div class="input-group-no-icon">
								<input type="text" class="form-control" name="peso" id="nombre" 
									placeholder="Introduce el peso" value="<?php echo $model->peso; ?>" />
							</div>
						</div>
						<div class="form-group">
							<label for="raza">Raza</label>
							<div class="input-group-no-icon">
								<input type="text" class="form-control" name="raza" id="raza" 
									placeholder="Introduce la raza" value="<?php echo $model->raza; ?>" />
							</div>
						</div>
						<?php if($mode == 'Editar'){ ?>
							<div class="form-group">
								<label for="estado">Estado</label>
								<select name="estado">
									<option <?php echo ($model->estado == $model::STATUS_REFUGIO)?'selected':'' ?>
										value="<?php echo $model::STATUS_REFUGIO; ?>">
										<?php echo $model::getEstadoName($model::STATUS_REFUGIO); ?>
									</option>
									<option <?php echo ($model->estado == $model::STATUS_SOLICITADO)?'selected':'' ?>
										value="<?php echo $model::STATUS_SOLICITADO; ?>">
										<?php echo $model::getEstadoName($model::STATUS_SOLICITADO); ?>
									</option>
									<option <?php echo ($model->estado == $model::STATUS_EN_PROCESO)?'selected':'' ?>
										value="<?php echo $model::STATUS_EN_PROCESO; ?>">
										<?php echo $model::getEstadoName($model::STATUS_EN_PROCESO); ?>
									</option>
									<option <?php echo ($model->estado == $model::STATUS_ADOPTADO)?'selected':'' ?>
										value="<?php echo $model::STATUS_ADOPTADO; ?>">
										<?php echo $model::getEstadoName($model::STATUS_ADOPTADO); ?>
									</option>
									<option <?php echo ($model->estado == $model::STATUS_EXTRANJERO)?'selected':'' ?>
										value="<?php echo $model::STATUS_EXTRANJERO; ?>">
										<?php echo $model::getEstadoName($model::STATUS_EXTRANJERO); ?>
									</option>
								</select>
							</div>
						<?php } ?> 
					</div>
					<?php } else { ?>
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
						<div class="form-group">
							<label for="tamanyo">Tamaño</label>
							<div class="input-group-no-icon">
								<input type="text" class="form-control" name="tamanyo" id="tamanyo" 
									placeholder="Introduce el tamaño" value="<?php echo $model->tamanyo; ?>" />
							</div>
						</div>
					</div>
					<div class="col-md-5 col-md-offset-2">
						<label>Imagen actual</label>
						<div class="row">
							<div class="col-md-8">
								<img class="responsive" src="<?php echo $model->imagenSrc(); ?>" 
	                    			alt="<?php echo $model->nombre; ?>">
							</div>
						</div>
						<div class="form-group">
							<label for="imagen">Cambiar Imagen</label>
							<div class="input-group">
								<input type="file" class="form-control" name="imagen" id="imagen" />
							</div>
						</div>
						<div class="form-group">
							<label for="peso">Peso</label>
							<div class="input-group-no-icon">
								<input type="text" class="form-control" name="peso" id="nombre" 
									placeholder="Introduce el peso" value="<?php echo $model->peso; ?>" />
							</div>
						</div>
						<div class="form-group">
							<label for="raza">Raza</label>
							<div class="input-group-no-icon">
								<input type="text" class="form-control" name="raza" id="raza" 
									placeholder="Introduce la raza" value="<?php echo $model->raza; ?>" />
							</div>
						</div>
					</div>
					<?php } ?>
				</div>
      			<input type="submit" value="<?php echo ($model->isNewRecord)?'Crear':'Editar'; ?>" class="btn btn-success pull-right">
            </form>
        </div>
    </div>
</div>
