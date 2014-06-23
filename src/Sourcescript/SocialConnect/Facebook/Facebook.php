<?php namespace Sourcescript\SocialConnect\Facebook;

use \Config;
use \Event;
use \Input;
use \Redirect;
use \Session;


use \Sourcescript\SocialConnect\URI\Chainable\Chainable;
use \Sourcescript\SocialConnect\Social\OAuth2;


class Facebook extends OAuth2
{

	public function __construct()
	{
		parent::__construct(
			'facebook', 
			'https://www.facebook.com/dialog/oauth',
			'https://graph.facebook.com');
	}

	public static function isLoggingIn($function)
	{
		Event::listen('facebook.login', $function);
	}

	public function getProfile($id = 'me', $fields = [])
	{
		$config = Config::get('social-connect::config.'.$this->type);
		if(!$this->hasAccessToken()) {
			return [
				'message' 	=> 'No Access Token, login first.',
				'for'		=> 'facebook.get_profile',
				'type'		=> 'error'
			];
		}else {
			$chainable = Chainable::make($this->payloadGraph.'/'.$id);
			$chainable->setAttrib('fields', implode(',', $fields))
						->setAttrib('access_token', $this->getAccessToken());
			$contents = file_get_contents($chainable->getURI());
			return json_decode($contents, true);
		}
	}

}