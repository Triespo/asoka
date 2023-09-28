<?php

return CMap::mergeArray(
	require(dirname(__FILE__).'/main.php'),
	array(
		'components'=>array(
			'fixture'=>array(
				'class'=>'system.test.CDbFixtureManager',
			),
			'urlManager'=>array(
				'urlFormat'=>'path',
				'showScriptName'=>false,
				'rules'=>array(
					'gii'=>'gii',
		            'gii/<controller:\w+>'=>'gii/<controller>',
		            'gii/<controller:\w+>/<action:\w+>'=>'gii/<controller>/<action>',
	            ),
            ),
            'log' => array(
	            'class' => 'CLogRouter',
	            'routes' => array(
	               'asoka_logger'=>array(
	                    'class'=>'CFileLogRoute',
	                    'levels'=>'error, warning, trace, info',
	                    'logFile'=>'devel.log',
	                    'categories'=>'devel',
	                ),
	            ),
	        ),
		),
	)
	
);
