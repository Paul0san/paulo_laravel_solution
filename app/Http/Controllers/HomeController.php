<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class HomeController extends Controller
{
    public $keyForApi = 'ts=1000&apikey=464d401b8a77f2d600b136c874f65476&hash=3b8d7333a2d054cd47025e42daae5b9b';

    public function index(Request $request){

        $texto = trim($request->get('inputValue'));
        if($texto === ''){
            $text = 'null';
        }
        $text = strtr($texto," ","-");

        $category = trim($request->get('comicOrHero'));
        if($category === ''){
            $category = 'allHeros';
        }

        switch ($category) {
            case $category === 'hero':
                if($text === ''){
                    $text = 'null';
                }
                $marvelEndPoint = 'http://gateway.marvel.com/v1/public/characters?nameStartsWith=';
                $fullConnection = $marvelEndPoint . $text . '&' . $this->keyForApi;
                $arrayConnection = HTTP::get($fullConnection);
                $data = $arrayConnection['data']['results'];

                break;

            case $category === 'comic':
                if($text === ''){
                    $text = 'null';
                }
                $text2 = strtr($text,"-"," ");

                $marvelEndPoint = 'http://gateway.marvel.com/v1/public/comics?titleStartsWith=';
                $fullConnection = $marvelEndPoint . $text2 . '&' . $this->keyForApi;
                // dd($fullConnection);
                $arrayConnection = HTTP::get($fullConnection);
                $data = $arrayConnection['data']['results'];

                break;

            case $category === 'allHeros':
                $marvelEndPoint = 'http://gateway.marvel.com/v1/public/characters?';
                $fullConnection = $marvelEndPoint . '&' . $this->keyForApi;
                $arrayConnection = HTTP::get($fullConnection);
                $data = $arrayConnection['data']['results'];

                break;

            default:
                $category = 'hero';
                $marvelEndPoint = 'http://gateway.marvel.com/v1/public/characters?';
                $fullConnection = $marvelEndPoint . '&' . $this->keyForApi;
                $arrayConnection = HTTP::get($fullConnection);
                $data = $arrayConnection['data']['results'];

                break;
        }

        return view('dashboard', compact('data','category'));
    }

    public function markAsFavorite($id){

        $marvelEndPoint = 'http://gateway.marvel.com/v1/public/characters/';
        $fullConnection = $marvelEndPoint . $id . '?' . $this->keyForApi;
        $arrayConnection = HTTP::get($fullConnection);
        $data = $arrayConnection['data']['results'];
        $character = $data[0];

        $currentUserId = Auth::user()->id;
        $currentUser = User::find($currentUserId);
        $currentUser->user_personal_info->update([
            'favorite_marvel_character' => $character['name']
        ]);

        return redirect()->route('dashboard');
    }
}
