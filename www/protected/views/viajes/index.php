<?php
    $this->pageTitle = Yii::app()->name . ' - Viajes';
?>

<div class="content">

    <div class="row">
        <div class="col-md-12">
            <div class="pull-right">
                <a href="<?php echo $this->createUrl('viajes/nuevo'); ?>"
                    class="btn btn-success">Nuevo</a>
            </div>
        </div>
    </div>

    <h1>Viajes</h1>

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
                'name'=>'Animal',
                'value'=>'$data->animal->nombre',
            ),
            array(
            	'name'=>'Destino',
                'value'=>'$data->destino',
        	),
        	array(
                'name'=>'Fecha Salida',
                'value'=>'date("d/m/Y", strtotime($data->f_salida))',
            ),
            array(
                'name'=>'Fecha Llegada',
                'value'=>'($data->f_llegada)?date("d/m/Y", strtotime($data->f_llegada)):"-"',
            ),
        ),
    ));
    ?>
</div>