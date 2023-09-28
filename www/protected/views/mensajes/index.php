<?php
    $this->pageTitle = Yii::app()->name . ' - Mensajes';
?>

<div class="content">

    <?php if (!Yii::app()->user->isAdoptante()) { ?>
        <div class="row">
            <div class="col-md-12">
                <div class="pull-right">
                    <a href="<?php echo $this->createUrl('mensajes/nuevo'); ?>"
                        class="btn btn-success">Nuevo</a>
                </div>
            </div>
        </div>
    <?php } ?>

    <h1>Mensajes</h1>

    <?php foreach(Yii::app()->user->getFlashes() as $key => $message) { ?>
        <div class="alert alert-<?php echo $key; ?> alert-dismissable">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            <?php echo $message ?>
        </div>
    <?php } ?>

    <!-- Nav tabs -->
    <ul class="nav nav-tabs">
        <li class="active">
            <a href="#recibidos" data-toggle="tab"><i class="fa fa-inbox"></i> Recibidos</a>
        </li>
        <?php if (!Yii::app()->user->isAdoptante()) { ?>
            <li>
                <a href="#enviados" data-toggle="tab"><i class="fa fa-share"></i> Enviados</a>
            </li>
        <?php } ?>
    </ul>
    <!-- Tab panes -->
    <div class="tab-content">
        <div class="tab-pane active" id="recibidos">
            <div class="list-group">
                <?php if (!empty($mensajesRecibidos)) { ?>
                    <?php foreach ($mensajesRecibidos as $recibido) { ?>
                        <a href="<?php echo Yii::app()->createUrl('mensajes/'.$recibido->id); ?>" class="list-group-item <?php echo ($recibido->isRead())?'read':''; ?>">
                            <div class="row">
                                <div class="col-xs-4">
                                    <i class="fa fa-user"></i> <?php echo $recibido->getSenderName(); ?>
                                </div>
                                <div class="col-xs-5">
                                    <span><?php echo $recibido->titulo; ?></span>
                                    <span class="text-muted" style="font-size: 11px;">
                                        - <?php echo (strlen($recibido->contenido) > 25)?
                                            substr($recibido->contenido, 0, 25).'...':
                                            $recibido->contenido; ?>
                                    </span>
                                </div>
                                <div class="col-xs-3">
                                    <span class="label <?php echo ($recibido->isRead())?'label-success':'label-default'; ?> pull-right">
                                        <?php echo date("d/m/Y H:i", strtotime($recibido->f_envio)); ?>
                                    </span>
                                </div>
                            </div>                   
                        </a>
                    <?php } ?>
                <?php } else { ?>
                    <div class="list-group-item">
                        <div class="row">
                            <div class="col-xs-12">
                                No tienes mensajes
                            </div>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>
        <?php if (!Yii::app()->user->isAdoptante()) { ?>
        <div class="tab-pane" id="enviados">
            <div class="list-group">
                <?php if (!empty($mensajesEnviados)) { ?>
                    <?php foreach ($mensajesEnviados as $enviado) { ?>
                        <a href="<?php echo Yii::app()->createUrl('mensajes/'.$enviado->id); ?>" class="list-group-item">
                            <div class="row">
                                <div class="col-xs-4">
                                    <i class="fa fa-user"></i> <?php echo $enviado->getReceiverName(); ?>
                                </div>
                                <div class="col-xs-5">
                                    <span><?php echo $enviado->titulo; ?></span>
                                    <span class="text-muted" style="font-size: 11px;">
                                        - <?php echo (strlen($enviado->contenido) > 25)?
                                            substr($enviado->contenido, 0, 25).'...':
                                            $enviado->contenido; ?>
                                    </span>
                                </div>
                                <div class="col-xs-3">
                                    <span class="label label-primary pull-right">
                                        <?php echo date("d/m/Y H:i", strtotime($enviado->f_envio)); ?>
                                    </span>
                                </div>
                            </div>                   
                        </a>
                    <?php } ?>
                <?php } else { ?>
                    <div class="list-group-item">
                        <div class="row">
                            <div class="col-xs-12">
                                No tienes mensajes
                            </div>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>
        <?php } ?>
    </div>
</div>