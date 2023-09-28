<?php
$this->pageTitle = Yii::app()->name . ' - Login';
?>

<div class="content">
    <div id="login-box" class="mainbox col-md-6 col-md-offset-3 col-sm-8 col-sm-offset-2">                    
        <div class="panel panel-info" >
            <div class="panel-heading">
                <div class="panel-title">Acceder</div>
            </div>    
            <div class="panel-body" >
                
                <?php foreach(Yii::app()->user->getFlashes() as $key => $message) { ?>
                    <div class="alert alert-<?php echo $key; ?> alert-dismissable">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        <?php echo $message ?>
                    </div>
                <?php } ?>

                <form id="login-form" class="form-horizontal" role="form" method="post">                  
                    <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-envelope"></i></span>
                        <input id="login-username" type="text" class="form-control" 
                            name="username" value="<?php echo $model->username; ?>" 
                            placeholder="<?php echo $model->getAttributeLabel('email'); ?>">                                        
                    </div>
                                
                    <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-lock"></i></span>
                        <input id="login-password" type="password" class="form-control" 
                            name="password" 
                            placeholder="<?php echo $model->getAttributeLabel('password'); ?>">
                    </div>

                    <div class="input-group">
                        <div class="checkbox">
                            <label>
                                <input id="login-remember" type="checkbox" name="remember" value="1">
                                <?php echo $model->getAttributeLabel('rememberMe'); ?>
                            </label>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-12 controls">
                            <input type="submit" value="Acceder" class="btn btn-success" />
                        </div>
                    </div>   
                </form>     
            </div>                     
        </div>  
    </div>
</div>