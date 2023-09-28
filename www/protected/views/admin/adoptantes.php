<?php
    $this->pageTitle = Yii::app()->name . ' - Adoptantes';
?>

<div class="content">

    <div class="row">
        <div class="col-md-12">
            <div class="pull-right">
                <a href="<?php echo $this->createUrl('admin/adoptantes/nuevo'); ?>"
                    class="btn btn-success">Añadir nuevo</a>
            </div>
        </div>
    </div>

    <h1>Adoptantes</h1>

    <?php foreach(Yii::app()->user->getFlashes() as $key => $message) { ?>
        <div class="alert alert-<?php echo $key; ?> alert-dismissable">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            <?php echo $message ?>
        </div>
    <?php } ?>
    
    <?php $this->widget('zii.widgets.grid.CGridView', array(
        'dataProvider'=>$model->search(),
        'itemsCssClass'=>'table table-hover',
        'columns'=>array(
            array(
                'name'=>'email',
                'value'=>'$data->usuario->email',
            ),
            array(
                'name'=>'nombre',
                'value'=>'$data->usuario->nombre',
            ),
            array(
                'name'=>'apellidos',
                'value'=>'$data->usuario->apellidos',
            ),
            
            array(
                'header'=>'Estado',
                'type'=>'raw',
                'name'=>'estado',
                'value'=>'$data->usuario->statusName()',
            ),
            array(
                'name'=>'animal',
                'value'=>'(!empty($data->animales))?$data->animales[0]->nombre:"No"',
            ),
            array(
                'name'=>'Acciones',
                'type'=>'raw',
                'value'=>'((Yii::app()->user->getUser()->canEdit($data->usuario))
                    ? CHtml::link("<i class=\"glyphicon glyphicon-pencil\" title=\"editar\"></i>", 
                        Yii::app()->createUrl(Yii::app()->getController()->id . "/usuarios/editar") . "/" . $data->usuario->id)
                    : "") . " " 
                        . ((Yii::app()->user->getUser()->canEdit($data->usuario))
                    ? CHtml::link("<i class=\"glyphicon glyphicon-remove\" title=\"borrar\"></i>", 
                        Yii::app()->createUrl(Yii::app()->getController()->id . "/usuarios/borrar") . "/" . $data->usuario->id)
                    : "")',
            ),
        ),
    )); ?>
</div>