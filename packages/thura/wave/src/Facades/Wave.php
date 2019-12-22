<?php

namespace Thura\Wave\Facades;

use Illuminate\Support\Facades\Facade;

/**
* 
*/
class Wave extends Facade
{
	protected static function getFacadeAccessor()
	{
		return "thura-wave";
	}
}