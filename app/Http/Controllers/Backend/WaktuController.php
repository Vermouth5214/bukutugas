<?php


namespace App\Http\Controllers\Backend;

use Session;
use App\Http\Controllers;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Model\Waktu;
use Illuminate\Support\Facades\Redirect;
use Image;

class WaktuController extends Controller {
	public function index(Request $request) {
        $data = Waktu::orderBy('id','ASC')->get();
        view()->share('data', $data);
		return view ('backend.setwaktu');
	}
	
    public function update(Request $request)
    {
        $data = Waktu::orderBy('id','ASC')->get();
        foreach ($data as $item):
            $update = Waktu::find($item->id);
            $update->istirahat_awal = date("H:i", strtotime($_POST['istirahat_awal_'.$item->id]));
            $update->istirahat_akhir = date("H:i", strtotime($_POST['istirahat_akhir_'.$item->id]));
            $update->jam_masuk = date("H:i", strtotime($_POST['jam_masuk_'.$item->id]));
            $update->jam_pulang = date("H:i", strtotime($_POST['jam_pulang_'.$item->id]));
            $update->user_modified = Session::get('userinfo')['user_id'];
            $update->save();
        endforeach;
		return Redirect::to('/backend/set-waktu')->with('success', "Data saved successfully")->with('mode', 'success');
    }
	
}