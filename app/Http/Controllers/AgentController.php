<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserAccess;
use Illuminate\Http\Request;

class AgentController extends Controller
{
    public function index()
    {
        $dataUser = User::orderBy('created_at', 'DESC')->paginate(20);
        return view('agent.index', [
            'title' => 'List Agent',
            'data' => $dataUser,
            'search' => ''
        ]);
    }

    public function create()
    {
        $dataAccess = UserAccess::get();
        return view('agent.create', [
            'title' => 'Add New Agent',
            'totalnote' => 0,
            'dataAccess' => $dataAccess
        ]);
    }

    public function store(Request $request)
    {
        dd('test');
        $request->validate([
            'username' => 'required|unique:users,username',
            'password' => 'required|min:8',
            'pin' => 'required|numeric|digits:6',
            'divisi' => 'required',
        ]);

        $user = new User();
        $user->name = $request->username;
        $user->username = $request->username;
        $user->divisi = $request->divisi;
        $user->password = bcrypt($request->password);
        $user->pin = bcrypt($request->pin);
        $user->image = "";
        $user->status = 'active';

        try {
            $user->save();
            return redirect('/agentds')->with('success', 'Agent successfully added.');
        } catch (\Exception $e) {
            return back()->with('error', 'An error occurred, please try again.')->withInput();
        }
    }

}
