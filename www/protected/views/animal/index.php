<?php
    $this->pageTitle = Yii::app()->name . ' - Animales';
?>

<div class="content">

    <div class="row">
        <div class="col-md-12">
            <div class="pull-right">
            <a href="<?php echo $this->createUrl(Yii::app()->getController()->id . '/animales/nuevo'); ?>"
                	class="btn btn-success">Añadir nuevo</a>
            </div>
        </div>
    </div>

    <h1>Animales</h1>

    <?php foreach(Yii::app()->user->getFlashes() as $key => $message) { ?>
        <div class="alert alert-<?php echo $key; ?> alert-dismissable">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            <?php echo $message ?>
        </div>
    <?php } ?>
    
    <?php 
    	$this->widget('zii.widgets.grid.CGridView', array(
        'dataProvider'=>$model->search(), //borrar el search
        'itemsCssClass'=>'table table-hover table-center',
        'columns'=>array(
            array(
                'name'=>'nombre',
                'value'=>'$data->nombre',
            ),
        	array(
        		'header'=>'Edad',
            	'name'=>'f_nacimiento',
                'value'=>'($data->f_nacimiento)?Common::getEdad($data->f_nacimiento):"-"',
        	),
            array(
                'name'=>'descripcion',
                'value'=>'substr($data->descripcion, 0, 30)."..."',
            ),
            array(
                'name'=>'sexo',
                'value'=>'substr($data->sexoName(), 0, 1)',
            ),
            array(
            	'header'=>'Tamaño',
                'name'=>'tamanyo',
                'value'=>'($data->tamanyo)?$data->tamanyo . " cm":"-"',
            ),
            array(
                'name'=>'peso',
                'value'=>'($data->peso)?$data->peso . " Kg":"-"',
            ),
            array(
                'name'=>'raza',
                'value'=>'($data->raza)?$data->raza:"-"',
            ),
            array(
                'header'=>'Estado',
                'type'=>'raw',
                'name'=>'estado',
                'value'=>'$data->estadoDropDown',
            ),
            array(
            	'header'=>'Adoptante',
                'name'=>'adoptante',
                'value'=>'($data->adoptante)?$data->adoptante->usuario->getFullName():"no"',
            ),
            array(
                'name'=>'Acciones',
                'type'=>'raw',
                'value'=>'((Yii::app()->user->getUser()->canEditAnimals())
                    ? CHtml::link("<i class=\"glyphicon glyphicon-pencil\" title=\"editar\"></i>", 
                        Yii::app()->createUrl(Yii::app()->getController()->id . "/animales/editar") . "/" . $data->id)
                    : "") . " " 
                        . ((Yii::app()->user->getUser()->canEditAnimals())
                    ? CHtml::link("<i class=\"glyphicon glyphicon-remove\" title=\"borrar\"></i>", 
                        Yii::app()->createUrl(Yii::app()->getController()->id . "/animales/borrar") . "/" . $data->id)
                    : "")',
            ),
        ),
        'rowHtmlOptionsExpression'=>'array("data-id"=>"$data->id")',
    ));
    ?>
</div>