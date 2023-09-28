<?php
/* @var $this SiteController */

$this->pageTitle=Yii::app()->name;
?>

<div class="content">
	<div class="row">
	   	<h1>Animales en el refugio</h1>
	   	<ul class="thumbnails">
	   		<?php foreach ($animales as $animal) { ?>
   			<li class="col-md-4 col-sm-6">
          		<div class="thumbnail">
                	<img class="responsive" src="<? echo $animal->imagenSrc(); ?>" 
                    alt="<?php echo $animal->nombre; ?>">
                	<div class="caption">
                  		<h3><?php echo $animal->nombre; ?></h3>
                  		<p><?php echo $animal->descripcion; ?></p>
            		</div>
            	</div>
            </li>
	   		<?php } ?>
   		</ul>            
	</div>
</div>