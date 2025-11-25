<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
class DashboardController extends Controller {
    public function summary(Request $r){
        $pending = DB::select('SELECT r.id,r.folio,r.solicitante,r.return_date,p.name as product FROM requests r JOIN request_items ri ON ri.request_id=r.id JOIN products p ON p.id=ri.product_id WHERE r.type="prestamo" AND r.status="pendiente" GROUP BY r.id LIMIT 5');
        $low = DB::select('SELECT id,name,quantity FROM products WHERE quantity<=min_quantity AND quantity>0 LIMIT 10');
        $out = DB::select('SELECT id,name FROM products WHERE quantity=0 LIMIT 10');
        return response()->json(['pending'=>$pending,'low'=>$low,'out'=>$out,'role'=>$r->user()->role ?? 'encargado']);
    }
}
