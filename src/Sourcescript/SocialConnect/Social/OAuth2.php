<?php namespace SourceScript\SocialConnect\Social;

use Illuminate\Config\Repository as Config;
use Illuminate\Events\Dispatcher as Event;
use Illuminate\Http\Request as Input;
use Illuminate\Routing\Redirector as Redirect;
use Illuminate\Session\Store as Session;
use Sourcescript\SocialConnect\URI\Chainable\Chainable;

abstract class OAuth2 
{
	protected  $payloadLogin;
	protected  $payloadGraph;
	protected  $type;

	/**
	 * 
	 * 
	 * @param
	 * @param
	 * @param
	 * @return
	 */
	public function __construct($type, $payloadLogin, $payloadGraph)
	{
		$this->type = $type;
		$this->payloadLogin = $payloadLogin;
		$this->payloadGraph = $payloadGraph;

		// Inject dependencies
		$this->setDependencies();
	}

	/**
	 * Resolve class dependencies (setter injector)
	 * 
	 * @param Illuminate\Config\Repository $config
	 * @param Illuminate\Events\Dispatcher $event
	 * @param Illuminate\Http\Request $input
	 * @param Illuminate\Routing\Redirector $redirect
	 * @param Illuminate\Session\Store $session
	 * @return void
	 */
	protected function setDependencies(
		Config $config,
		Event $event,
		Input $input,
		Redirect $redirect,
		Session $session
	)
	{
		$this->config = $config;
		$this->event = $event;
		$this->input = $input;
		$this->redirect = $redirect;
		$this->session = $session;
	}

	/**
	 * Allows static creation of this class
	 * 
	 * @return {called class}
	 */
	public static function make()
	{
		$class = get_called_class();
		return new $class;
	}

	/**
	 * 
	 * @param str	$redirect_uri
	 * @param int	$code
	 * @return str
	 */
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

	/**
	 * 
	 * @return str
	 */
	public  function getTokenUrl()
	{
		return $this->payloadGraph."/oauth/access_token";
	}	

	/**
	 *
	 * @return Response
	 */ 
	public  function loginRedirect($redirect_uri = '')
	{
		$class = get_called_class();
		$login = $this->loginUrl($redirect_uri);
		return $this->redirect->to($login);
	}


	// this function is fucked up, please fix it. huurrr
	/**
	 * 
	 * @param str $redirect_uri
	 * @return
	 */
	public  function login($redirect_uri = '')
	{
		$config = $this->config->get('social-connect::config.'.$this->type);

		if(!$this->hasAccessToken() && !$this->input->has('code') ) {

			return $this->loginRedirect($redirect_uri);

		} elseif( $this->input->has('error') ) {

			return $this->input->all();

		} elseif($code = $this->input->get('code')) {

			$this->getAccessToken($code, $redirect_uri);

		}

		if( $this->hasAccessToken() ) {
			return $this->redirect->to($config['login']['redirect_uri']);
		}


	}

	/**
	 * 
	 * @return bool
	 */
	public  function hasAccessToken()
	{
		return $this->session->get($this->type.'.access_token') ? true : false;
	}

	public function getAccessToken($code = null, $redirect_uri = null)
	{
		if ( $this->hasAccessToken() ) {
			return $this->session->get($this->type . '.access_token');
		}

		$config = $this->config->get('social-connect::config.'.$this->type);

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

	/**
	 * 
	 * @return
	 */
	public function setAccessToken($token)
	{
		return $this->session->put($this->type.'.access_token', $token);
	}

}
