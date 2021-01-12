<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;


class LangController extends Controller
{
    
    /**
     * Switchlang route (for language selection)
     */
    public function switchlang($locale)
    {
        \App::setLocale($locale);
        if (in_array($locale, \Config::get('app.locales'))) {
            \Session::put('locale', $locale);
        }
        return redirect()->back();
    }
    
}
