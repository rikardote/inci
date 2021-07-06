<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Requests\UserRequest;
use App\Http\Controllers\Controller;
use App\User;
use App\Deparment;

use Laracasts\Flash\Flash;

class UsersController extends Controller
{
     public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!\Auth::user()->admin()) {

            return redirect()->route('home.index');
        }
    	$users = User::all();

    	return view('admin.users.index')->with('users', $users);
    }

    public function create(User $user)
    {
    	$deparments = Deparment::all()->lists('deparment', 'id')->toArray();
        asort($deparments);
    	return view('admin.users.create')->with('user', $user)->with('departments', $deparments);
    }

    public function edit($id)
    {
    	$user = User::find($id);
    	$deparments = Deparment::all()->lists('deparment', 'id')->toArray();
        asort($deparments);

    	$departments_select = $user->centros->lists('id')->toArray();
    	
        return view('admin.users.edit')
        	->with('user', $user)
        	->with('departments', $deparments)
        	->with('departments_select', $departments_select);
    }

    public function store(UserRequest $request)
    {
        $user = new User($request->all());
        $user->password = bcrypt($request->password);
                
        $user->save();

        $user->centros()->sync($request->departments);

        Flash::success('Usuario creado con exito!');
        return redirect()->route('users.index');
       
    }

    public function update(Request $request, $id)
    {
        $user = User::find($id);
        $user->fill($request->all());
        //$user->password = bcrypt($request->password);
        
        $user->save();

        $user->centros()->sync($request->departments);

        Flash::success('Usuario modificado con exito!');
        return redirect()->route('users.index');
    }

    public function destroy($id)
    {
        $user = User::find($id);
        $user->delete();

        Flash::success('Usuario borrado con exito!');
        return redirect()->route('users.index');
    }

    public function change($id)
    {
    	$user = User::find($id);
    	return view('admin.users.change')->with('user', $user);
    }

    public function change_store(Request $request, $id)
    {
    	$user = User::find($id);
    	$user->password = bcrypt($request->password);

    	$user->save();

    	return redirect()->route('users.index');
    }

}
