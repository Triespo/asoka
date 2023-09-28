<?php

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');

// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
return array(
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	'name'=>'Asoka',
	'language'=>'es',

	// preloading 'log' component
	'preload'=>array('log'),

	// autoloading model and component classes
	'import'=>array(
		'application.models.*',
		'application.components.*',
		'application.Asoka.*',
		'application.extensions.image.Image',
	),

	'modules'=>array(
		// uncomment the following to enable the Gii tool
		'gii'=>array(
			'class'=>'system.gii.GiiModule',
			'password'=>'gii',
			// If removed, Gii defaults to localhost only. Edit carefully to taste.
			'ipFilters'=>array('10.10.10.1','::1'),
		),
	),

	// application components
	'components'=>array(
		'user'=>array(
            // enable cookie-based authentication
            'allowAutoLogin'=>true,
            'class' => 'WebUser',
		),
		'mailer' => array(
			'class' => 'application.extensions.mailer.EMailer',
			'pathViews' => 'application.views.email',
			'pathLayouts' => 'application.views.email.layouts'
		),	
		'urlManager'=>array(
			'urlFormat'=>'path',
			'showScriptName'=>false,
			'rules'=>array(
				'mensajes' => 'mensajes',
				'mensajes/nuevo' => 'mensajes/nuevo',
				'mensajes/enviar' => 'mensajes/enviar',
				'viajes'=>'viajes/listarViajes',
				'viajes/nuevo'=>'viajes/createViaje',
				'admin/usuarios/nuevo'=>'admin/newUser',
				'admin/usuarios/crear'=>'admin/createUser',
				'admin/usuarios/editar/<id:\d+>'=>'admin/editUser',
				'admin/usuarios/borrar/<id:\d+>'=>'admin/deleteUser',
				'admin/animales/nuevo'=>'admin/createAnimal',
				'admin/animales/editar/<id:\d+>'=>'admin/editAnimal',
				'admin/animales/borrar/<id:\d+>'=>'admin/deleteAnimal',
				'admin/voluntarios/nuevo'=>'admin/newUser',
				'admin/adoptantes/nuevo'=>'admin/newAdoptante',
				'voluntario/animales/nuevo'=>'voluntario/createAnimal',
				'voluntario/animales/borrar/<id:\d+>'=>'voluntario/deleteAnimal',
				'voluntario/animales/editar/<id:\d+>'=>'voluntario/editAnimal',
				'confirmar/<id:\d+>/<key:\w+>' => 'site/confirmar',
				'<controller:\w+>/<id:\d+>'=>'<controller>/view',
				'<controller:\w+>/<action:\w+>/<id:\d+>'=>'<controller>/<action>',
				'<controller:\w+>/<action:\w+>'=>'<controller>/<action>',
				'<action:\w+>'=>'site/<action>',
			),
		),
		'image'=>array(
            'class'=>'application.extensions.image.CImageComponent',
            // GD or ImageMagick
            'driver'=>'GD',
            // ImageMagick setup path
            //'params'=>array('directory'=>'/opt/local/bin'),
        ),
        'file'=>array(
            'class'=>'ext.cfile.CFile',
        ),
		'db'=>array(
			'connectionString' => 'mysql:host=localhost;dbname=asoka',
			'username' => 'root',
			'password' => '',
			'charset' => 'utf8',
		),
		'errorHandler'=>array(
			// use 'site/error' action to display errors
			'errorAction'=>'site/error',
		),
		'log' => array(
            'class' => 'CLogRouter',
            'routes' => array(
               'asoka_logger'=>array(
                    'class'=>'CFileLogRoute',
                    'levels'=>'error, warning, trace, info',
                    'categories'=>'devel',
                ),
            ),
        ),
	),

	// application-level parameters that can be accessed
	// using Yii::app()->params['paramName']
	'params'=>array(
		'mailer_host' =>'smtp.gmail.com',
		'mailer_port' =>'465',
		'mailer_username' =>'dssgrupo3@gmail.com',
		'mailer_password' =>'DssCorreo',
		'mailer_smtpauth'=>true,
        'SMTPSecure' => 'ssl',
        'mailer_senderName' => 'Asoka',
		'mailer_senderEmail' => 'support@asoka.dev',
		'mailer_replyToEmail' => 'no-reply@asoka.dev',
		'mailer_replyToName' => 'Asoka',
	),
);