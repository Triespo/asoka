<?php
  $this->pageTitle = Yii::app()->name . ' - Nuevo Viaje';

  $this->breadcrumbs=array(
    'Viajes' => array('/viajes'),
    'Nuevo',
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
        <h1>Nuevo Viaje</h1>
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
            <form id="create-mensaje-form" class="form-horizontal required-form" role="form" method="post">
                <div class="well well-sm">
                    <strong>
                        <i class="fa fa-asterisk"></i> Campo obligatorio
                    </strong>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="nombre">Destinatario</label>
                            <div class="input-group">
                                <select id="select-animal" name="animal" class="form-control" required>
                                    <?php foreach ($animales as $animal) { ?>
                                        <option value="<?php echo $animal->id; ?>"><?php echo $animal->nombre; ?></option>
                                    <?php } ?>
                                </select>
                                <span class="input-group-addon">
                                    <i class="fa fa-asterisk"></i>
                                </span>
                            </div>
                        </div>
                        <div class="form-group">
							<label for="destino">Destino</label>
							<div class="input-group">
								<input type="text" class="form-control" name="destino" id="destino" 
									placeholder="Introduce un destino" value="<?php echo $model->destino; ?>" required />
								<span class="input-group-addon">
									<i class="fa fa-asterisk"></i>
								</span>
							</div>
						</div>
						<div class="form-group">
							<label for="f_salida">Fecha de salida</label>
							<div class="input-group">
						  		<input type="text" class="form-control date-picker" name="f_salida" 
						  			id="f_salida" 
						  			value="<?php /*echo ($model->f_salida)?date('d/m/Y', $model->f_salida):'';*/ ?>" required/>
						  		<span class="input-group-addon">
									<i class="fa fa-calendar"></i>
								</span>
								<span class="input-group-addon">
									<i class="fa fa-asterisk"></i>
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

<?php 
    if ($model->animal) {
        $extra = '$("#select-animal").val("'.$model->animal->id.'").trigger("change");';
    } else {
        $extra = '';
    }
    Yii::app()->clientScript->registerScript(
        'typeahead',
        '$(document).ready(function() { 
            $("#select-animal").select2();'
        . $extra
        . '});'
    );
?>