<?php namespace SourceScript\SocialConnect\Social;

use Config, Event, Input, Redirect, Session;
use Sourcescript\SocialConnect\URI\Chainable\Chainable;

abstract class OAuth2 
{
	protected  $payloadLogin;
	protected  $payloadGraph;
	protected  $type;

	public function __construct($type, $payloadLogin, $payloadGraph)
	{
		$this->type = $type;
		$this->payloadLogin = $payloadLogin;
		$this->payloadGraph = $payloadGraph;
	}

	public static function make()
	{
		$class = get_called_class();
		return new $class;
	}


	public function loginUrl($redirect_uri = '', $code = null)
	{
		$config = Config::get('social-connect::config.'.$this->type);

		$chainable = Chainable::make($this->payloadLogin);
		$chainable->setAttrib('client_id', $config['app_id'])
			->setAttrib('redirect_uri', $redirect_uri)
			->setAttrib('type', 'web_server')
			->setAttrib('response_type', 'token')
			->setAttrib('client_secret', $config['app_secret'])
			->setAttrib('scope',implode(',',$config['scopes']));

		return ! $code ? $chainable->getURI() : $chainable->setAttrib('code', $code)->getURI();
	}

	public function getTokenUrl()
	{
		return $this->payloadGraph."/oauth/access_token";
	}	

	public function loginRedirect($redirect_uri = '')
	{
		$class = get_called_class();
		$login = $this->loginUrl($redirect_uri);
		return Redirect::to($login);
	}


	// this function is fucked up, please fix it. huurrr
	public function login($redirect_uri = '')
	{
		$config = Config::get('social-connect::config.'.$this->type);

		if ( ! $this->hasAccessToken() && ! Input::get('code') ) {

			return $this->loginRedirect($redirect_uri);

		}elseif(Input::get('error')) {

			return Input::all();

		}elseif($code = Input::get('code')) {

			$this->getAccessToken($code, $redirect_uri);

		}

		if($this->hasAccessToken()) {
			return Redirect::to($config['login']['redirect_uri']);
		}

	}

	public function hasAccessToken()
	{
		return Session::get($this->type.'.access_token') ? true : false;
	}

	public function getAccessToken($code = null, $redirect_uri = null)
	{
		if ( $this->hasAccessToken ) {
			return Session::get($this->type . '.access_token');
		}

		$config = Config::get('social-connect::config.'.$this->type);

		$chainable = Chainable::make($this->payloadGraph."/oauth/access_token");
		$chainable->setAttrib('client_id', $config['app_id'])
			->setAttrib('type', 'web_server')
			->setAttrib('response_type', 'token')
			->setAttrib('client_secret', $config['app_secret'])
			->setAttrib('redirect_uri', $redirect_uri)
			->setAttrib('code', $code);
						
		$contents = file_get_contents($chainable->getURI());
		$contents = explode('access_token=', $contents);

		$this->setAccessToken($contents[1]);

		return $contents[1];
	}

	public function setAccessToken($token)
	{
		return Session::put($this->type.'.access_token', $token);
	}

}
