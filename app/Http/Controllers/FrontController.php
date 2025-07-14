<?php

namespace App\Http\Controllers;

use App\Values\MenuValues;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class FrontController extends Controller
{
    public function index()
    {
        return view('front.home');
    }

    public function menu($menu = null)
    {
        if ($menu !== null) {
            $title = '';
            switch ($menu) {
                case MenuValues::BREAKFAST_ALL_DAY['value']:
                    $title = MenuValues::BREAKFAST_ALL_DAY['label'];
                    $tag = MenuValues::BREAKFAST_ALL_DAY['value'];
                    break;
                case MenuValues::TO_SHARE['value']:
                    $title = MenuValues::TO_SHARE['label'];
                    $tag = MenuValues::TO_SHARE['value'];
                    break;
                case MenuValues::SANDWICHES_BURGERS['value']:
                    $title = MenuValues::SANDWICHES_BURGERS['label'];
                    $tag = MenuValues::SANDWICHES_BURGERS['value'];
                    break;
                case MenuValues::LUNCH_DINNER['value']:
                    $title = MenuValues::LUNCH_DINNER['label'];
                    $tag = MenuValues::LUNCH_DINNER['value'];
                    break;
                case MenuValues::SALAD_BOWLS['value']:
                    $title = MenuValues::SALAD_BOWLS['label'];
                    $tag = MenuValues::SALAD_BOWLS['value'];
                    break;
                case MenuValues::KIDS_MENU['value']:
                    $title = MenuValues::KIDS_MENU['label'];
                    $tag = MenuValues::KIDS_MENU['value'];
                    break;
                case MenuValues::COFFEE_AND_HOT_DRINKS['value']:
                    $title = MenuValues::COFFEE_AND_HOT_DRINKS['label'];
                    $tag = MenuValues::COFFEE_AND_HOT_DRINKS['value'];
                    break;
                case MenuValues::COLD_DRINKS_AND_FRESH['value']:
                    $title = MenuValues::COLD_DRINKS_AND_FRESH['label'];
                    $tag = MenuValues::COLD_DRINKS_AND_FRESH['value'];
                    break;
                case MenuValues::TOSTO_BAR['value']:
                    $title = MenuValues::TOSTO_BAR['label'];
                    $tag = MenuValues::TOSTO_BAR['value'];
                    break;
                case MenuValues::CHEF_SPECIALTIES['value']:
                    $title = MenuValues::CHEF_SPECIALTIES['label'];
                    $tag = MenuValues::CHEF_SPECIALTIES['value'];
                    break;
                default:
                    return abort(404);
            }

            return view('front.menu.show', array_merge(compact(['menu', 'title', 'tag']), ['is_section' =>  true]));
        }

        return view('front.menu.index', ['is_section' =>  true]);
    }

    public function lang($locale)
    {
        if (!in_array($locale, config('app.available_locales'), true)) {
            abort(400);
        }

        App::setLocale($locale);
        Session::put('locale', $locale);
        
        // Create a cookie that will work on the current domain with appropriate settings
        $cookie = cookie('locale', $locale, 60*24*30); // 30 days expiration
        
        return redirect()->back()->withCookie($cookie);
    }

    public function new_branch ()
    {
        return view('front.new-branch');
    }
}
