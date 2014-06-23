<?php
	return [
		'facebook' 	=> [

			/**
			 *  Your Application ID
			 *  ==========================/
			 *  Found in your application itself
			 */
			'app_id'		=> 'app_id',

			/**
			 *  Your Application Secret
			 *  ==========================/
			 *  Given By Facebook for auth purposes
			 */
			'app_secret'	=> 'app_secret',

			/**
			 *  SCOPES
			 *  ==========================/
			 *  Granted by facebook. Required scopes 
			 *  for facebook access
			 */
			'scopes' => [

			],

			/**
			 *  Login Options
			 *  ==========================/
			 *  Callback URL
			 */

			'login'  => [
				'redirect_uri' 	=> ''
			]
		]
	];