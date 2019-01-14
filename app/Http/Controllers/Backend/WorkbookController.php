<?php

namespace App\Http\Controllers\Backend;

use Session;
use Illuminate\Http\Request;
use App\Http\Controllers;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use App\Model\Workbook;
use App\Model\Waktu;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Redirect;
use Datatables;

class WorkbookController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    	$startDate = date('d-m-Y');
        $endDate = date('d-m-Y');
        if (isset($_GET["startDate"]) || isset($_GET["endDate"])){
			if ((isset($_GET['startDate'])) && ($_GET['startDate'] != "")){
				$startDate = $_GET["startDate"];
			}
			if ((isset($_GET['endDate'])) && ($_GET['endDate'] != "")){
				$endDate = $_GET["endDate"];
            }
        }
		view()->share('startDate',$startDate);
		view()->share('endDate',$endDate);

        return view ('backend.workbook.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view ('backend.workbook.update');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $ambil_jam_masuk = Waktu::whereRaw('dayname(NOW()) = hari')->get();
        $jam_masuk = $ambil_jam_masuk[0]->jam_masuk;
        $jam_awal = date('H:i', strtotime($request->awal));
        $jam_akhir = date('H:i', strtotime($request->akhir));
        $jam_istirahat_awal = $ambil_jam_masuk[0]->istirahat_awal;
        $jam_istirahat_akhir = $ambil_jam_masuk[0]->istirahat_akhir;

        if ($request->awal == ""){
            return Redirect::to('/backend/workbook/create')->with('success', "Jam Mulai tidak boleh kosong")->with('mode', 'danger');
        }
        if ($request->akhir == ""){
            return Redirect::to('/backend/workbook/create')->with('success', "Jam Selesai tidak boleh kosong")->with('mode', 'danger');
        }
        $start = strtotime($request->awal);
        $end = strtotime($request->akhir);
        if ($end - $start <= 0){
            return Redirect::to('/backend/workbook/create')->with('success', "Jam Selesai harus lebih besar dari Jam Mulai")->with('mode', 'danger');
        }
        if ($request->requester == ""){
            return Redirect::to('/backend/workbook/create')->with('success', "Diminta oleh tidak boleh kosong")->with('mode', 'danger');
        }
        if ($request->keterangan == ""){
            return Redirect::to('/backend/workbook/create')->with('success', "Keterangan tidak boleh kosong")->with('mode', 'danger');
        }

        //cek apakah inputan waktu jam istirahat
        if (strtotime($request->awal) >=  strtotime($jam_istirahat_awal) && strtotime($request->awal) <  strtotime($jam_istirahat_akhir)) {
            return Redirect::to('/backend/workbook/create')->with('success', "Jam yang diinput adalah jam istirahat")->with('mode', 'danger');
        }
        if (strtotime($request->akhir) >  strtotime($jam_istirahat_awal) && strtotime($request->akhir) <=  strtotime($jam_istirahat_akhir)) {
            return Redirect::to('/backend/workbook/create')->with('success', "Jam yang diinput adalah jam istirahat")->with('mode', 'danger');
        }
        if ((strtotime($request->awal) < strtotime($jam_istirahat_awal)) && (strtotime($request->akhir) > strtotime($jam_istirahat_akhir))){
            return Redirect::to('/backend/workbook/create')->with('success', "Jam yang diinput adalah jam istirahat")->with('mode', 'danger');
        }

        //cek jam awal < jam masuk
        if (strtotime($request->awal) - strtotime($jam_masuk) < 0){
            return Redirect::to('/backend/workbook/create')->with('success', "Jam Mulai lebih kecil dari Jam Masuk")->with('mode', 'danger');
        }

        //kasus 1 jam awal >= awal dan jam akhir <= akhir
        $cek_jam = Workbook::where('user_modified', Session::get('userinfo')['user_id'])->where('tanggal', date('Y-m-d'))->whereRaw('CAST(awal as TIME) <="'.date('H:i:s', strtotime($request->awal)).'"')->whereRaw('CAST(akhir as TIME) >="'.date('H:i:s', strtotime($request->akhir)).'"')->count();
        if ($cek_jam > 0){
            return Redirect::to('/backend/workbook/create')->with('success', "Area Jam tersebut sudah pernah diinput")->with('mode', 'danger');
        }

        //kasus 2 jam awal <= awal dan jam akhir <= akhir dan jam akhir > awal
        $cek_jam = Workbook::where('user_modified', Session::get('userinfo')['user_id'])->where('tanggal', date('Y-m-d'))->whereRaw('CAST(awal as TIME) >="'.date('H:i:s', strtotime($request->awal)).'"')->whereRaw('CAST(akhir as TIME) >="'.date('H:i:s', strtotime($request->akhir)).'"')->whereRaw('CAST(awal as TIME) <"'.date('H:i:s', strtotime($request->akhir)).'"')->count();
        if ($cek_jam > 0){
            return Redirect::to('/backend/workbook/create')->with('success', "Area Jam tersebut sudah pernah diinput")->with('mode', 'danger');
        }

        //kasus 3 jam awal >= awal dan jam awal < akhir dan jam akhir >= akhir
        $cek_jam = Workbook::where('user_modified', Session::get('userinfo')['user_id'])->where('tanggal', date('Y-m-d'))->whereRaw('CAST(awal as TIME) <="'.date('H:i:s', strtotime($request->awal)).'"')->whereRaw('CAST(akhir as TIME) >"'.date('H:i:s', strtotime($request->awal)).'"')->whereRaw('CAST(akhir as TIME) <="'.date('H:i:s', strtotime($request->akhir)).'"')->count();
        if ($cek_jam > 0){
            return Redirect::to('/backend/workbook/create')->with('success', "Area Jam tersebut sudah pernah diinput")->with('mode', 'danger');
        }

        //kasus 4 jam awal <= awal dan jam akhir >= akhir
        $cek_jam = Workbook::where('user_modified', Session::get('userinfo')['user_id'])->where('tanggal', date('Y-m-d'))->whereRaw('CAST(awal as TIME) >="'.date('H:i:s', strtotime($request->awal)).'"')->whereRaw('CAST(akhir as TIME) <="'.date('H:i:s', strtotime($request->akhir)).'"')->count();
        if ($cek_jam > 0){
            return Redirect::to('/backend/workbook/create')->with('success', "Area Jam tersebut sudah pernah diinput")->with('mode', 'danger');
        }

        $data = new Workbook();
        $data->tanggal = date('Y-m-d');
        $data->awal = date('H:i', strtotime($request->awal));
        $data->akhir = date('H:i', strtotime($request->akhir));
        $data->requester = $request->requester;
        $data->keterangan = $request->keterangan;
        $data->active = 1;
        $data->user_modified = Session::get('userinfo')['user_id'];
		if($data->save()){
			return Redirect::to('/backend/workbook/')->with('success', "Data saved successfully")->with('mode', 'success');
		}
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
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

	public function datatable() {
        $userinfo = Session::get('userinfo');
    	$startDate = date('d-m-Y');
        $endDate = date('d-m-Y');
        if (isset($_GET["startDate"]) || isset($_GET["endDate"])){
			if ((isset($_GET['startDate'])) && ($_GET['startDate'] != "")){
				$startDate = $_GET["startDate"];
			}
			if ((isset($_GET['endDate'])) && ($_GET['endDate'] != "")){
				$endDate = $_GET["endDate"];
            }
        }
        
        $data = Workbook::where('user_modified', $userinfo['user_id']);

        $data = $data->where('tanggal','>=', date('Y-m-d 00:00:00',strtotime($startDate)));
        $data = $data->where('tanggal','<=',date('Y-m-d 23:59:59',strtotime($endDate)));
        $data = $data->orderBy('tanggal', 'ASC')->orderBy('awal', 'ASC')->orderBy('akhir', 'ASC');
        
        return Datatables::of($data)
            ->editColumn('keterangan', function($data){
                return nl2br($data->keterangan);
            })
            ->editColumn('tanggal', function($data) {
                return date('d-m-Y', strtotime($data->tanggal));
            })
            ->editColumn('awal', function($data) {
                return date('H:i', strtotime($data->awal));
            })
            ->editColumn('akhir', function($data) {
                return date('H:i', strtotime($data->akhir));
            })
            ->rawColumns(['keterangan'])
            ->make(true);
	}    
}
