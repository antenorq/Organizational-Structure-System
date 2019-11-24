<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Perfil;

class PerfilController extends Controller
{

	public function __construct() 
	{
		$this->perfil = new Perfil;
	}


    public function getPerfis()
    {
    	$perfis = $this->perfil->getPerfil();
    	return json_encode($perfis);
    }
}
