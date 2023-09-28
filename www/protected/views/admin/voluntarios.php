<?php
    $this->pageTitle = Yii::app()->name . ' - Voluntarios';
?>

<div class="content">

    <div class="row">
        <div class="col-md-12">
            <div class="pull-right">
                <a href="<?php echo $this->createUrl('admin/voluntarios/nuevo'); ?>"
                    class="btn btn-success">Añadir nuevo</a>
            </div>
        </div>
    </div>

    <h1>Voluntarios</h1>

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
        ),
    )); ?>
</div>