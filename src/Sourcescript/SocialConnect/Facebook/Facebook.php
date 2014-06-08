<?php namespace Sourcescript\SocialConnect\Facebook;

use \Config;
use \Input;
use \Redirect;
use \Session;


use \Sourcescript\SocialConnect\URI\Chainable\Chainable;

class Facebook
{
	private static $payloadLogin = "https://www.facebook.com/dialog/oauth";
	private static $payloadGraph = "https://graph.facebook.com/oauth";

	public static function loginUrl($redirect_uri = '', $code = null)
	{
		$config = Config::get('social-connect::config.facebook');
		$chainable = Chainable::make(self::$payloadLogin);
		$chainable->setAttrib('client_id', $config['app_id'])
					->setAttrib('redirect_uri', $redirect_uri)
					->setAttrib('type', 'webserver')
					->setAttrib('response_type', 'token')
					->setAttrib('client_secret', $config['app_secret']);
		if(!$code) {
			return $chainable->getURI();
		}else {
			return $chainable->setAttrib('code', $code)->getURI();
		}
	}

	public static function getTokenUrl()
	{
		return self::$payloadGraph."/access_token";
	}	

	public static function loginRedirect($redirect_uri = '')
	{
		$login = Facebook::loginUrl($redirect_uri);
		return Redirect::to($login);
	}

	public static function login($redirect_uri = '')
	{
		if(!self::hasAccessToken() 
			&& !Input::get('code') ) {

			return self::loginRedirect($redirect_uri);

		}elseif(Input::get('error')) {

			return Input::all();

		}elseif($code = Input::get('code')) {

			return self::getAccessToken($code);

		}elseif(Input::get('access_token')) {

			self::setAccessToken($token);
			return Input::all();
			
		}
	}

	public static function hasAccessToken()
	{
		return Session::get('facebook.access_token') ? true : false;
	}

	public static function getAccessToken($code)
	{
		$config = Config::get('social-connect::config.facebook');

		if(!self::hasAccessToken()) {

			$chainable = Chainable::make(self::$payloadGraph."/access_token");
			$chainable->setAttrib('client_id', $config['app_id'])
						->setAttrib('type', 'webserver')
						->setAttrib('response_type', 'token')
						->setAttrib('client_secret', $config['app_secret']);
			
			
		}else {
			return Session::get('facebook.access_token');
		}
		
	}

	public static function setAccessToken($token)
	{
		return Session::set('facebook.access_token', $token);
	}
}