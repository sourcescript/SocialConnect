#SocialConnect
##Created for Laravel By Sourcescript Innovations

#Installation
add the the following to your composer.json `"sourcescript/social-connect": "dev-master"`.  Do a `composer update --prefer-dist`.

Add the following to `config/app.php`:

###Facade
```php
<?php
	'Facebook'		  => 'Sourcescript\SocialConnect\Facades\Facebook'

```

###Providers
```php
<?php
	...
	'Sourcescript\SocialConnect\SocialConnectServiceProvider'
	...
```


After the installation you have to do the following to be able to use the configuration fiels.

```
	php artisan config:publish sourcescript/social-connect
```

You'll have a blank configuration file as follows
```php
<?php
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
			 *  For Logging In
			 */

			'login'  => [
				'redirect_uri' 	=> ''
			]
		]
	];
```

##Social Media Integration

