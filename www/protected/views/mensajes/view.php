<?php
    $this->pageTitle = Yii::app()->name . ' - Ver Mensaje';

    $this->breadcrumbs=array(
        'Mensajes' => array('/mensajes'),
        'Ver'
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
        <?php if ($enviado || Yii::app()->user->isAdoptante()) { ?>
            <div class="col-xs-12">
                <h2><?php echo $mensaje->titulo; ?></h2>
            </div>
        <?php } else { ?>
            <div class="col-xs-8">
                <h2><?php echo $mensaje->titulo; ?></h2>
            </div>
            <div class="col-xs-4">
                <div class="pull-right">
                    <form id="reply-form" class="form-horizontal" role="form" method="post" 
                        action="<?php echo $this->createUrl('mensajes/nuevo');?>">
                        <input type="hidden" name="usuario" value="<?php echo $mensaje->usuario->id; ?>"/>
                        <button id="reply" type="button" class="btn btn-default"><i class="fa fa-reply"></i> Responder</button>
                    </form>
                </div>
            </div>
        <?php } ?>
    </div>
    <div class="row">
        <div class="col-xs-12">
            <hr/>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-6">
            <h4>De: <?php echo ($enviado)?'Mi':$mensaje->getSenderName(); ?></h4>
        </div>
        <div class="col-xs-6">
            <h4><span class="label label-primary pull-right">
                <?php echo date("d/m/Y H:i", strtotime($mensaje->f_envio)); ?>
            </span></h4>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12">
            <h4>Para: <?php echo ($enviado)?$mensaje->getReceiverName():'Mi'; ?></h4>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12">
            <p><?php echo $mensaje->contenido; ?></p>
        </div>
    </div>
</div>

<?php 
    Yii::app()->clientScript->registerScript(
        'typeahead',
        "$('#reply').click(function(e) {
            e.preventDefault();
            $('#reply-form').submit();
        });"
    );
?>

