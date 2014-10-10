<?php

// Parameters for use with the ae_SiteBuilder class.
// @see admin.php
// @see templates/


$paramsHead = new stdClass;
$paramsHead->title = 'admin area';
$paramsHead->css = ( $area == 'edit' ) ? 'create' : $area;
$paramsHead->js = ( $area == 'create' || $area == 'edit' ) ? 'create' : FALSE;


$paramsNav = array(
	'Dashboard' => array(
		'active' => ( $area == 'dashboard' ),
		'icon' => 'grid3x3',
		'link' => 'dashboard'
	),

	'Manage' => array(
		'active' => ( $area == 'manage' || $area == 'edit' ),
		'icon' => 'book',

		'Categories' => array(
			'active' => ( isset( $_GET['category'] ) && ( $area == 'manage' || $area == 'edit' ) ),
			'link' => 'manage&category'
		),
		'Comments' => array(
			'active' => ( isset( $_GET['comment'] ) && ( $area == 'manage' || $area == 'edit' ) ),
			'link' => 'manage&comment'
		),
		'Pages' => array(
			'active' => ( isset( $_GET['page'] ) && ( $area == 'manage' || $area == 'edit' ) ),
			'link' => 'manage&page'
		),
		'Posts' => array(
			'active' => ( isset( $_GET['post'] ) && ( $area == 'manage' || $area == 'edit' ) ),
			'link' => 'manage&post'
		),
		'Users' => array(
			'active' => ( isset( $_GET['user'] ) && ( $area == 'manage' || $area == 'edit' ) ),
			'link' => 'manage&user'
		)
	),

	'Create' => array(
		'active' => ( $area == 'create' ),
		'icon' => 'pen',

		'Category' => array(
			'active' => ( isset( $_GET['category'] ) && $area == 'create' ),
			'link' => 'create&category'
		),
		'Page' => array(
			'active' => ( isset( $_GET['page'] ) && $area == 'create' ),
			'link' => 'create&page'
		),
		'Post' => array(
			'active' => ( isset( $_GET['post'] ) && $area == 'create' ),
			'link' => 'create&post'
		),
		'User' => array(
			'active' => ( isset( $_GET['user'] ) && $area == 'create' ),
			'link' => 'create&user'
		)
	),

	'Media' => array(
		'active' => ( $area == 'media' ),
		'icon' => 'folder',
		'link' => 'media'
	),

	'Settings' => array(
		'active' => ( $area == 'settings' ),
		'icon' => 'wrench',
		'link' => 'settings'
	)
);
