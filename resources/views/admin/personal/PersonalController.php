<?php

namespace App\Http\Controllers\admin\personal;

use App\Http\Controllers\Controller;
use App\Models\Ad;
use App\Models\Kitchen\KitchenAd;
use App\Models\PersonalAd;
//use http\Env\Request;
use Illuminate\Http\Request;


class PersonalController extends Controller
{
    public function index(Request $request)
    {
        $query = PersonalAd::with('ad');

        if (!empty($request->status)) {
            $query->whereHas('ad', function ($q) use ($request) {
                $q->where('status', $request->status);
            });
        }

        if (!empty($request->title)) {
            $query->whereHas('ad', function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->title . '%');
            });
        }


        if (!empty($request->search)) {
            $query->where('text', 'like', '%' . $request->search . '%');
        }


        $personal = $query->paginate(10)->withQueryString();

        return view('admin.ads.Personal belongings.index', compact('personal'));
    }

    public function show(string $id)
    {
        $per = PersonalAd::with('ad')->find($id);
        if (!$per) {
            abort(404, 'not found');
        }
        return view('admin.ads.Personal belongings.show', compact('per'));
    }
    public function updateStatus(Request $request ,string $id)
    {
        $request->validate([
            'status' => 'required|in:sold,approved,rejected,pending',
        ]);
        $per=Ad::find($id);
        $per->update(['status' => $request->status]);
        return redirect()->back()->with('success','با موفقیت آپدیت شد');
    }
    public function destroy(string $id){
        $per=PersonalAd::find($id);
        $per->delete();
        return redirect()->back()->with('success','با موفقیت حذف شد');
    }

}
