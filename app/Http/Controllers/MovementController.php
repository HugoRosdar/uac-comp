<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
class MovementController extends Controller {
    public function index(Request $r){
        $page = max(1,(int)($r->page ?? 1)); $per = 30; $offset = ($page-1)*$per;
        $rows = DB::select('SELECT m.*, u.name as user FROM movements m LEFT JOIN users u ON u.id=m.user_id ORDER BY m.created_at DESC LIMIT ? OFFSET ?', [$per,$offset]);
        return response()->json(['data'=>$rows,'page'=>$page]);
    }
}
