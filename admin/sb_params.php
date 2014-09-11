<?php

// Parameters for use with the ae_SiteBuilder class.
// @see admin.php
// @see templates/


$paramsHead = new stdClass;
$paramsHead->title = 'admin area';
$paramsHead->css = $area;


$paramsNav = array(
	'Dashboard' => array(
		'active' => ( $area == 'dashboard' ),
		'icon' => 'grid3x3',
		'link' => 'dashboard'
	),

	'Manage' => array(
		'active' => ( $area == 'manage' ),
		'icon' => 'book',

		'Categories' => array(
			'active' => ( isset( $_GET['categories'] ) && $area == 'manage' ),
			'link' => 'manage&categories'
		),
		'Comments' => array(
			'active' => ( isset( $_GET['comments'] ) && $area == 'manage' ),
			'link' => 'manage&comments'
		),
		'Pages' => array(
			'active' => ( isset( $_GET['pages'] ) && $area == 'manage' ),
			'link' => 'manage&pages'
		),
		'Posts' => array(
			'active' => ( isset( $_GET['posts'] ) && $area == 'manage' ),
			'link' => 'manage&posts'
		),
		'Users' => array(
			'active' => ( isset( $_GET['users'] ) && $area == 'manage' ),
			'link' => 'manage&users'
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
