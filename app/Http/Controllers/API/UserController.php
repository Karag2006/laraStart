<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Gate;
use Intervention\Image\ImageManagerStatic as Image;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
        //$this->authorize('isAdmin');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //$this->authorize('isAdmin');
        if (Gate::allows('isAdmin') || Gate::allows('isAuthor')) {
            return User::latest()->paginate(5);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->authorize('isAdmin');
        $this->validate($request, [
            'name'              => 'required|string|max:50|min:4',
            'email'             => 'required|string|email|max:191|unique:users',
            'password'          => 'required|string|min:4',
            'type'              => 'required',
        ]);

        return User::create([
            'name' => $request['name'],
            'email' => $request['email'],
            'type' => $request['type'],
            'bio' => $request['bio'],
            'photo' => $request['photo'],
            'password' => Hash::make($request['password']),
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

      /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function profile()
    {
        return auth('api')->user();
    }

    public function profileUpdate(Request $request)
    {
        $user = auth('api')->user();

        $this->validate($request, [
            'name'              => 'required|string|max:50|min:4',
            'email'             => 'required|string|email|max:191|unique:users,email,'.$user->id,
            'password'          => 'sometimes|required|string|min:4',
            'type'              => 'required',
        ]);

        $currentPhoto = $user->photo;

        if ($request->photo != $currentPhoto) {
            $name = time(). '.' . explode('/', explode(':', substr($request->photo, 0, strpos($request->photo, ';')))[1])[1];

            Image::make($request->photo)->save(public_path('img/profile/').$name);

            $request->merge(['photo' => $name]);

            $oldUserPhoto = public_path('img/profile/').$currentPhoto;

            if (file_exists($oldUserPhoto)) {
                @unlink($oldUserPhoto);
            }


        }

        if(!empty($request->password)){
            $request->merge(['password' => Hash::make($request['password'])]);
        }

        $user->update($request->all());
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->authorize('isAdmin');
        $user = User::findOrFail($id);

        $this->validate($request, [
            'name'              => 'required|string|max:50|min:4',
            'email'             => 'required|string|email|max:191|unique:users,email,'.$user->id,
            'password'          => 'sometimes|string|min:4',
            'type'              => 'required',
        ]);

        $user->update($request->all());

        return $id;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->authorize('isAdmin');

        $user = User::findOrFail($id);

        $user->delete();

        return ['message' => "User Deleted $id"];

    }
}
