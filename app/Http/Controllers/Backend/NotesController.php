<?php

namespace App\Http\Controllers\Backend;

use Session;
use Illuminate\Http\Request;
use App\Http\Controllers;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use App\Model\Notes;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Redirect;
use Datatables;

class NotesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
		return view ('backend.notes.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
		return view ('backend.notes.update');
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
		$data = new Notes();
		$data->note = $request->note;
		$data->active = 1;
		$data->user_modified = Session::get('userinfo')['user_id'];
		if($data->save()){
			return Redirect::to('/backend/notes/')->with('success', "Data saved successfully")->with('mode', 'success');
		}

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\UserLevel $userLevel
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\UserLevel  $userLevel
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
        $data = Notes::where('id', $id)->where('active', 1)->get();
		if ($data->count() > 0){
            if ($data[0]->user_modified != Session::get('userinfo')['user_id']){
                return Redirect::to('/backend/notes/');
            }
			return view ('backend.notes.update', ['data' => $data]);
		}

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\UserLevel  $userLevel
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
		$data = Notes::find($id);
		$data->note = $request->note;
		$data->user_modified = Session::get('userinfo')['user_id'];
		if($data->save()){
			return Redirect::to('/backend/notes/')->with('success', "Data saved successfully")->with('mode', 'success');
		}
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\UserLevel  $userLevel
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        //
		$data = Notes::find($id);
        $data->active = 0;
        $data->user_modified = Session::get('userinfo')['user_id'];
        if($data->save()){
            Session::flash('success', 'Data deleted successfully');
            Session::flash('mode', 'success');
            return new JsonResponse(["status"=>true]);
        }else{
            return new JsonResponse(["status"=>false]);
        }
		return new JsonResponse(["status"=>false]);		
    }
	
	public function datatable() {	
		$userinfo = Session::get('userinfo');
		$data = Notes::where('user_modified', '=', $userinfo['user_id'])->where('active', '=', 1);
        return Datatables::of($data)
            ->editColumn('note', function($data){
                return nl2br($data->note);
            })
			->addColumn('action', function ($notes) {
				$url_edit = url('backend/notes/'.$notes->id.'/edit');
				$url = url('backend/notes/'.$notes->id);
				$view = "<a class='btn-action btn btn-primary btn-view' href='".$url."' title='View'><i class='fa fa-eye'></i></a>";
				$edit = "<a class='btn-action btn btn-info btn-edit' href='".$url_edit."' title='Edit'><i class='fa fa-edit'></i></a>";
                $delete = "<button data-url='".$url."' onclick='deleteData(this)' class='btn-action btn btn-danger btn-delete' title='Delete'><i class='fa fa-trash-o'></i></button>";
                return $edit." ".$delete;
            })			
            ->rawColumns(['action','note'])
            ->make(true);
	}
 	
}
