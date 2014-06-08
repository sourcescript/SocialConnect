<?php namespace Sourcescript\SocialConnect\URI\Chainable;

class Chainable
{
	private $uri = "";

	public function __construct($uri = '')
	{
		$this->uri = $uri;
	}

	public static function make($uri_base)
	{
		return  new Chainable($uri_base);
	}

	public function setAttrib($key, $value)
	{
		if(strpos($this->uri, "&") < 0)
		{
			$this->uri .= "?";
		}

		$this->uri .= "&".$key."=".$value;

		return $this;
	}

	public function getURI()
	{
		return $this->uri;
	}
}