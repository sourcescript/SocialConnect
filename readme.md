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
	return [
		'facebook' 	=> [

			/**
			 *  Your Application ID
			 *  ==========================/
			 *  Found in your application itself
			 */
			'app_id'		=> '1420529088231410',

			/**
			 *  Your Application Secret
			 *  ==========================/
			 *  Given By Facebook for auth purposes
			 */
			'app_secret'	=> 'e5321ec55a8bda414cb1dadbe54e715f',

			/**
			 *  SCOPES
			 *  ==========================/
			 *  Granted by facebook. Required scopes 
			 *  for facebook access
			 */
			'scopes' => [
				'email',
				'public_profile',
				'user_friends',
				'publish_stream'
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

