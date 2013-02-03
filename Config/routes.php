<?php
Router::parseExtensions('json');

Router::connect(
	'/api/pages',
	array('plugin'=> 'page','api'=>true,'controller' => 'pages', 'action' => 'index', '[method]' => 'GET')
);
Router::connect(
	'/api/pages/:id',
	array('plugin'=> 'page','api'=>true,'controller' => 'pages', 'action' => 'view', '[method]' => 'GET'),
	array('id' => '[0-9]+')
);

