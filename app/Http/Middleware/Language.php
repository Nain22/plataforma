<?php

namespace App\Http\Middleware;

use Carbon\Carbon;
use Closure;
use Illuminate\Support\Facades\App;

class Language
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        //comprovamos si exite la session => applocale
        if(session('applocale')){
            //del archivo de configuracion de idiomas cojer el que ayamos establecido con la session
            $configLanguage = config('languages')[session('applocale')];
            //configuramos el idioma con la primera posicion   setlocale(LC_TIME , $configLanguage[1] . 'es_ES');
            setlocale(LC_TIME , $configLanguage[1] . 'utf8');
            //foramteamos fecha
            Carbon::setlocale(session('applocale'));
            App::setlocale(session('applocale'));

        }else{
            //entonces tomara el idioma por defecto de nuestra aplicacion
            session()->put('applocale' , config('app.fallback_locale'));
            setlocale(LC_TIME , 'es_ES.utf8');
            Carbon::setlocale(session('applocale'));
            App::setlocale(session('app.fallback_locale'));
        }
        return $next($request);
    }
}
