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


//Router::connect('/pages/:action', array('plugin' => 'rita_pages', 'controller' => 'pages'));

Router::connect('/admin/pages', array('admin' => true,'plugin' => 'page', 'controller' => 'pages','action'=>'index'));
Router::connect('/admin/pages/index/*', array('admin' => true,'plugin' => 'page', 'controller' => 'pages','action'=>'index'));
Router::connect('/admin/pages/:action/*', array('admin' => true,'plugin' => 'page', 'controller' => 'pages'));

//Router::connect('/admin/page/:controller', array('admin' => true,'plugin' => 'page', 'action' => 'index'));
//Router::connect('/admin/page/:controller/index/*', array('admin' => true,'plugin' => 'page','action' => 'index'));
//Router::connect('/admin/page/:controller/:action/*', array('admin' => true,'plugin' => 'page'));
//
