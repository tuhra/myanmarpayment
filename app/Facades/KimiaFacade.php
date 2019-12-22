<?php

namespace App\Facades;
use Illuminate\Support\Facades\Facade;

class KimiaFacade extends Facade
{

	protected static function getFacadeAccessor() {
		return 'Kimia';
	}

}