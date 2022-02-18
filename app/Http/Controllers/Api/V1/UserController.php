<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\UserRequest;
use App\Models\User;
use App\Models\User_Personal_Info;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules;


class UserController extends Controller
{
    public $keyForApi = 'ts=1000&apikey=464d401b8a77f2d600b136c874f65476&hash=3b8d7333a2d054cd47025e42daae5b9b';
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::with('user_personal_info')->get();

        if($users){
            return response()->json(
                [
                    "res" => true,
                    "data" => $users,
                    "status"=>Response::HTTP_OK
                ],Response::HTTP_OK);
        }
        return response()->json(
            [
                "res" => false,
                "message" => 'No hay datos coincidientes',
                "status"=>Response::HTTP_BAD_REQUEST
            ],Response::HTTP_BAD_REQUEST);

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'gender' => ['required', 'string', 'max:6'],
            'age' => ['required', 'numeric'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required'],
            'city' => ['required','string', 'max:255'],
            'state' => ['required','string', 'max:255'],
            'country' => ['required','string', 'max:255'],
            'favorite_marvel_character' => ['required','string', 'max:255'],
            'favorite_marvel_comic' => ['required','string', 'max:255'],
        ]);

        $characterName = $this->getHeroName($request->input('favorite_marvel_character'));
        // dd($characterName);

        $newUser = User::create([
            'name' => $request->name,
            'last_name' => $request->last_name,
            'gender' => $request->gender,
            'age' => $request->age,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $newUser->user_personal_info()->create([
            'user_id' => $newUser->id,
            'city' => $request->input('city'),
            'state' => $request->input('state'),
            'country' => $request->input('country'),
            'favorite_marvel_character' => $characterName,
            'favorite_marvel_comic' => $request->input('favorite_marvel_comic'),
        ]);

        $resUser = $newUser->save();

        if ($resUser) {
            return response()->json(
                [
                    "res" => true,
                    'message' => 'User create succesfully',
                    "status"=>Response::HTTP_OK
                ],Response::HTTP_OK);
        }
        return response()->json(['message' => 'Error al crear usuario'], 500);


    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $users = User::with('user_personal_info')->get();
        try {
            $userSearch = $users->find($id);
            if ($userSearch) {
                return response()->json(
                    [
                        "res" => true,
                        'data' => $userSearch,
                        "status"=>Response::HTTP_OK
                    ],Response::HTTP_OK);
            }
            return response()->json(['message' => 'Usuario no encontrado'], 500);
        } catch (\Throwable $th) {
            return response()->json(['message' => 'Hubo un error con tu petición, revisa tus parametros'], 500);
        }

    }

    public function getHeroName($id){
        $marvelEndPoint = 'http://gateway.marvel.com/v1/public/characters/';
        $fullConnection = $marvelEndPoint . $id . '?' . $this->keyForApi;
        $arrayConnection = Http::get($fullConnection);
        if($arrayConnection->ok()){
            $data = $arrayConnection['data']['results'];
            $character = $data[0];

            return $character['name'];
        }else{
            return response()->json(['message' => 'No existe el personaje que buscas'], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {

        // dd($request->all());
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'gender' => ['required', 'string', 'max:6'],
            'age' => ['required', 'numeric'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user)],
            'password' => ['required'],
            'city' => ['required','string', 'max:255'],
            'state' => ['required','string', 'max:255'],
            'country' => ['required','string', 'max:255'],
            'favorite_marvel_character' => ['required','string', 'max:255'],
            'favorite_marvel_comic' => ['required','string', 'max:255'],
        ]);

        $searchUser = User::findOrFail($user->id);
        $searchUser->name = $request->name;
        $searchUser->last_name = $request->last_name;
        $searchUser->gender = $request->gender;
        $searchUser->age = $request->age;
        $searchUser->email = $request->email;
        $searchUser->password = Hash::make($request->password);
        $searchUser->user_personal_info-> user_id = $searchUser->id;
        $searchUser->user_personal_info-> city = $request->city;
        $searchUser->user_personal_info-> state = $request->state;
        $searchUser->user_personal_info-> country = $request->country;
        $searchUser->user_personal_info-> favorite_marvel_character = $request->favorite_marvel_character;
        $searchUser->user_personal_info-> favorite_marvel_comic = $request->favorite_marvel_comic;

        $resUser = $searchUser->save();

        if ($resUser) {
            return response()->json(
                [
                    "res" => true,
                    'message' => 'User actualizado correctamente',
                    "status"=>Response::HTTP_OK
                ],Response::HTTP_OK);
        }
        return response()->json(['message' => 'Error al actualizar user'], 500);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $searchUser = User::findOrFail($id);
        $resUser = $searchUser->delete();

        if ($resUser) {
            return response()->json(
                [
                    "res" => true,
                    'message' => 'User borrado correctamente',
                    "status"=>Response::HTTP_OK
                ],Response::HTTP_OK);
        }
        return response()->json(['message' => 'Error al borrar user'], 500);


    }
}
