<?php

// Parameters for use with the ae_SiteBuilder class.
// @see admin.php
// @see templates/


$paramsHead = new stdClass;
$paramsHead->title = 'admin area';


$paramsNav = array(
	'Dashboard' => array(
		'active' => ( !isset( $_GET['page'] ) || $_GET['page'] == 'dashboard' ),
		'icon' => 'grid3x3',
		'link' => 'dashboard'
	),
	'Manage' => array(
		'active' => ( isset( $_GET['page'] ) && $_GET['page'] == 'manage' ),
		'icon' => 'book',

		'Comments' => array(
			'active' => ( isset( $_GET['page'], $_GET['comments'] ) && $_GET['page'] == 'manage' ),
			'link' => 'manage&comments'
		),
		'Posts' => array(
			'active' => ( isset( $_GET['page'], $_GET['posts'] ) && $_GET['page'] == 'manage' ),
			'link' => 'manage&posts'
		),
		'Pages' => array(
			'active' => ( isset( $_GET['page'], $_GET['pages'] ) && $_GET['page'] == 'manage' ),
			'link' => 'manage&pages'
		)
	),
	'Create' => array(
		'active' => ( isset( $_GET['page'] ) && $_GET['page'] == 'create' ),
		'icon' => 'pen',

		'Posts' => array(
			'active' => ( isset( $_GET['page'], $_GET['comments'] ) && $_GET['page'] == 'create' ),
			'link' => 'create&posts'
		),
		'Pages' => array(
			'link' => 'create&pages'
		)
	)
);


$paramsFooter = array();
