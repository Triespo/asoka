<?php
    $this->pageTitle = Yii::app()->name . ' - Dashboard';
?>

<div class="content">
    <h1>Dashboard</h1>

    <h2>Animales adoptados</h2>
    <?php 
    	$this->widget('zii.widgets.grid.CGridView', array(
        'dataProvider'=>$dataProvider,
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
                'value'=>'$data->descripcion',
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
    	),
    ));
    ?>
</div>