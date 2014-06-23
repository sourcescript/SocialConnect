<?php namespace Sourcescript\SocialConnect\Facades;

use Illuminate\Support\Facades\Facade;

class Facebook extends Facade
{
	protected static function getFacadeAccessor()
	{
		return 'facebook';
	}
}