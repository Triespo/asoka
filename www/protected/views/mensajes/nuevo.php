<?php
  $this->pageTitle = Yii::app()->name . ' - Nuevo Mensaje';

  $this->breadcrumbs=array(
    'Mensajes' => array('/mensajes'),
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
        <h1>Nuevo Mensaje</h1>
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
            <form id="create-mensaje-form" class="form-horizontal required-form" role="form" method="post"
                action="<?php echo $this->createUrl('/mensajes/enviar'); ?>">
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
                                <select id="select-destinatario" name="destinatario" class="form-control" required>
                                    <?php foreach ($usuariosDisponibles as $key => $value) { ?>
                                        <option value="<?php echo $key; ?>"><?php echo $value; ?></option>
                                    <?php } ?>
                                </select>
                                <span class="input-group-addon">
                                    <i class="fa fa-asterisk"></i>
                                </span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="titulo">Titulo</label>
                            <div class="input-group">
                                <input type="text" class="form-control" name="titulo" id="titulo" 
                                    placeholder="Introduce un titulo" value="<?php echo $model->titulo; ?>" required />
                                <span class="input-group-addon">
                                    <i class="fa fa-asterisk"></i>
                                </span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="mensaje">Mensaje</label>
                            <div class="input-group">
                                <textarea class="form-control" name="mensaje" id="mensaje" 
                                    placeholder="Introduce el mensaje" rows="6" required><?php echo $model->contenido; ?></textarea>
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
    if ($destinatario) {
        $extra = '$("#select-destinatario").val("'.$destinatario->id.'").trigger("change");';
    } else {
        $extra = '';
    }
    Yii::app()->clientScript->registerScript(
        'typeahead',
        '$(document).ready(function() { 
            $("#select-destinatario").select2();'
        . $extra
        . '});'
    );
?>