<?php

namespace App\Http\Controllers\admin\user;

use App\Http\Controllers\Controller;
use App\Models\Ad;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query();
        if ($request->filled('first_name')) {
            $query->where('first_name', 'like', '%' . $request->first_name . '%');
        }
        if ($request->filled('last_name')) {
            $query->where('last_name', 'like', '%' . $request->last_name . '%');
        }
        if ($request->filled('email')) {
            $query->where('email', 'like', '%' . $request->email . '%');
        }

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('first_name', 'like', '%' . $request->search . '%')
                    ->orWhere('last_name', 'like', '%' . $request->search . '%')
                    ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }


        $users = $query->orderBy('id', 'desc')->paginate(10);

        return view('admin.users.index', compact('users'));
    }







    public function show(string $id){

        $user=User::with('ad')->find($id);
        return view('admin.users.show',compact('user'));
    }




    public function showAd( Request $request,string $id){

        $user = User::with('ad')->find($id);
        if (!$user) {
            return redirect()->back()->with('error', 'کاربر پیدا نشد');
        }


        $showads = $user->ad()->latest()->paginate(5);

        return view('admin.users.showAds', compact('showads'));

    }


    public function destroy(string $id){
        $user=User::find($id);
        if(!$user){
            return redirect()->back()->with('error','User not found');
        }
        $user->delete();
        return redirect()->route('user.index')->with('success','user deleted successfully');
    }
}
