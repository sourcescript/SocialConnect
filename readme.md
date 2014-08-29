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
			 *  Login Callback Action after logging in
			 */

			'login'  => [
				'redirect_uri' 	=> ''
			]
		]
	];
```

##How To Login Using SocialConnect

```php
	<?php 
		...
			//you should first fix the config file's login->redirect_uri to point to your callback function (That's where the script will go when the login function is all finished)
			$facebook = Facebook::make();
			$redirect_uri = '/facebook/login'; //the current route you want to point the login script
			return $facebook->login($redirect_uri); 
		...
```

##How to get the user profile in SocialConnect
```php

	<?php
		//returns array
		Facebook::make()->getProfile(); //the parameter would be either 'me' (current user logged in) or any valid facebook username or ID
```

##How to get the user's profile image in SocialConnect
```php
	<?php
		Facebook::make()->getProfileImage();
	
```

Twitter Soon!
