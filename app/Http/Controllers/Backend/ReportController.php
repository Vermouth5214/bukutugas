<?php
namespace App\Http\Controllers\Backend;

use Session;
use App\Http\Controllers;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Model\Waktu;
use App\Model\Workbook;
use App\Model\User;
use DB;
 
class ReportController extends Controller {
	public function general_report(Request $request) {
        $id_user = 0;
        $startDate = date('d-m-Y');
        $endDate = date('d-m-Y');
        $user = User::where('active',1)->where('user_level_id','<>',1)->orderBy('firstname','ASC')->pluck('firstname','id')->toArray();

        if (isset($_GET["startDate"]) || isset($_GET["endDate"])){
			if ((isset($_GET['startDate'])) && ($_GET['startDate'] != "")){
				$startDate = $_GET["startDate"];
			}
			if ((isset($_GET['endDate'])) && ($_GET['endDate'] != "")){
				$endDate = $_GET["endDate"];
            }
			if (isset($_GET["user"])){
				$id_user = $_GET['user'];
            }

            $data_user = User::where('id', $id_user)->get();
            $data_workbook = DB::select("SELECT * FROM (
                                            SELECT a.tanggal, a.awal, a.akhir, a.keterangan, a.user_modified, a.requester
                                            FROM workbook a
                                            WHERE a.active = 1
                                            union 
                                            SELECT a.tanggal as tanggal, b.istirahat_awal as awal, b.istirahat_akhir as akhir, 'Jam Istirahat' as keterangan, a.user_modified, '' as requester
                                            FROM 
                                                workbook a
                                                left join
                                                waktu b
                                                on(dayname(a.tanggal) = b.hari)
                                            group by tanggal
                                            union 
                                            SELECT a.tanggal as tanggal, b.jam_masuk as awal, b.jam_masuk as akhir, 'Jam Masuk' as keterangan, a.user_modified, '' as requester
                                            FROM 
                                                workbook a
                                                left join
                                                waktu b
                                                on(dayname(a.tanggal) = b.hari)
                                            group by tanggal
                                            union 
                                            SELECT a.tanggal as tanggal, b.jam_pulang as awal, b.jam_pulang as akhir, 'Jam Pulang' as keterangan, a.user_modified, '' as requester
                                            FROM 
                                                workbook a
                                                left join
                                                waktu b
                                                on(dayname(a.tanggal) = b.hari)
                                            group by tanggal
                                        ) u
                                        where user_modified = :user and tanggal >= :tanggal_awal and tanggal <= :tanggal_akhir                                            
                                        order by tanggal asc, awal asc, akhir asc", ['tanggal_awal' => date('Y-m-d',strtotime($startDate)), 'tanggal_akhir' => date('Y-m-d',strtotime($endDate)), 'user' => $id_user]);
            view()->share('data_user', $data_user);
            view()->share('data_workbook', $data_workbook);
        }
        view()->share('id_user', $id_user);
        view()->share('user', $user);
		view()->share('startDate',$startDate);
		view()->share('endDate',$endDate);
        return view ('backend.report');
	}
}