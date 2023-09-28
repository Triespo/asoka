<?php
    $this->pageTitle = Yii::app()->name . ' - Usuarios';
?>

<div class="content">

    <div class="row">
        <div class="col-md-12">
            <div class="pull-right">
                <form id="new-user-form" class="form-inline" role="form" method="post" 
                    action="<?php echo $this->createUrl('admin/usuarios/nuevo');?>">
                    <select class="form-control" name="user-type">
                        <?php if (Yii::app()->user->isSuperAdmin()) { ?>
                            <option value="<?php echo $model::TYPE_ADMIN; ?>">
                                <?php echo $model::getTypeName($model::TYPE_ADMIN); ?>
                            </option>
                        <?php } ?>
                        <option value="<?php echo $model::TYPE_VOLUNTARIO; ?>">
                            <?php echo $model::getTypeName($model::TYPE_VOLUNTARIO); ?>
                        </option>
                        <option value="<?php echo $model::TYPE_ADOPTANTE; ?>">
                            <?php echo $model::getTypeName($model::TYPE_ADOPTANTE); ?>
                        </option>
                    </select>
                    <input type="submit" class="btn btn-success" value="Añadir nuevo">
                </form>
            </div>
        </div>
    </div>

    <h1>Usuarios</h1>

    <?php foreach(Yii::app()->user->getFlashes() as $key => $message) { ?>
        <div class="alert alert-<?php echo $key; ?> alert-dismissable">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            <?php echo $message ?>
        </div>
    <?php } ?>
    
    <?php 
    	$this->widget('zii.widgets.grid.CGridView', array(
        'dataProvider'=>$model->search(),
        'itemsCssClass'=>'table table-hover table-center',
        'columns'=>array(
            array(
                'name'=>'email',
                'value'=>'$data->email',
            ),
            array(
            	'name'=>'nombre',
                'value'=>'$data->nombre?$data->nombre:"-"',
        	),
        	array(
            	'name'=>'apellidos',
                'value'=>'$data->apellidos?$data->apellidos:"-"',
        	),
            array(
                'name'=>'tipo',
                'value'=>'$data->typeName()',
            ),
            array(
                'header'=>'Estado',
                'type'=>'raw',
                'name'=>'estado',
                'value'=>'(Yii::app()->user->getUser()->canEdit($data))?$data->getEstadoDropDown($data):$data->statusName()',
            ),
            array(
                'name'=>'Acciones',
                'type'=>'raw',
                'value'=>'((Yii::app()->user->getUser()->canEdit($data))
                    ? CHtml::link("<i class=\"glyphicon glyphicon-pencil\" title=\"editar\"></i>", 
                        Yii::app()->createUrl("admin/usuarios/editar") . "/" . $data->id)
                    : "") . " " 
                        . ((Yii::app()->user->getUser()->canEdit($data))
                    ? CHtml::link("<i class=\"glyphicon glyphicon-remove\" title=\"borrar\"></i>", 
                        Yii::app()->createUrl("admin/usuarios/borrar") . "/" . $data->id)
                    : "")',
            ),
        ),
        'rowHtmlOptionsExpression'=>'array("data-id"=>"$data->id")',
    ));
    ?>
</div>


