<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Observers\BaseObserver;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
    	\App\AtoNormativo::observe(BaseObserver::class);    	
    	\App\Cargo::observe(BaseObserver::class);
    	\App\CargoUnidade::observe(BaseObserver::class);
    	\App\Endereco::observe(BaseObserver::class);
    	\App\Equipe::observe(BaseObserver::class);
    	\App\EstruturaOrganizacional::observe(BaseObserver::class);
    	\App\Funcao::observe(BaseObserver::class);
    	\App\Gestor::observe(BaseObserver::class);
    	\App\Perfil::observe(BaseObserver::class);
    	\App\Situacao::observe(BaseObserver::class);
    	\App\TipoAcaoAtoNormativo::observe(BaseObserver::class);
    	\App\TipoAtoNormativo::observe(BaseObserver::class);
    	\App\TipoCargo::observe(BaseObserver::class);
    	\App\TipoHierarquia::observe(BaseObserver::class);
    	\App\User::observe(BaseObserver::class);		    	
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}