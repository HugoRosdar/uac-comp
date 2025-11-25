<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
class RequestController extends Controller {
    public function index(){ $rows = DB::table('requests')->orderBy('created_at','desc')->get(); return response()->json($rows); }
    public function store(Request $r){
        DB::beginTransaction();
        try{
            $folio = 'FOL-'.time();
            $id = DB::table('requests')->insertGetId(['folio'=>$folio,'type'=>$r->type,'solicitante'=>$r->solicitante,'user_id'=>$r->user()->id ?? null,'status'=>'pendiente','return_date'=>$r->return_date ?? null,'created_at'=>now(),'updated_at'=>now()]);
            foreach($r->items as $it){ DB::table('request_items')->insert(['request_id'=>$id,'product_id'=>$it['product_id'],'quantity'=>$it['quantity'],'created_at'=>now(),'updated_at'=>now()]); DB::table('products')->where('id',$it['product_id'])->decrement('quantity',$it['quantity']); }
            DB::table('movements')->insert(['user_id'=>$r->user()->id ?? null,'action'=>'create_request','details'=>'Solicitud '.$folio,'created_at'=>now(),'updated_at'=>now()]);
            DB::commit(); return response()->json(['id'=>$id,'folio'=>$folio],201);
        }catch(\Exception $e){ DB::rollBack(); return response()->json(['error'=>$e->getMessage()],500); }
    }
    public function markReturn(Request $r,$id){
        $items = DB::table('request_items')->where('request_id',$id)->get();
        foreach($items as $it){ DB::table('products')->where('id',$it->product_id)->increment('quantity',$it->quantity); DB::table('request_items')->where('id',$it->id)->update(['returned'=>1]); }
        DB::table('requests')->where('id',$id)->update(['status'=>'devuelta']);
        DB::table('movements')->insert(['user_id'=>$r->user()->id ?? null,'action'=>'return_request','details'=>'Devolución solicitud '.$id,'created_at'=>now(),'updated_at'=>now()]);
        return response()->json(['returned'=>true]);
    }
    public function destroy(Request $r,$id){ DB::table('requests')->where('id',$id)->delete(); DB::table('movements')->insert(['user_id'=>$r->user()->id ?? null,'action'=>'delete_request','details'=>'Solicitud eliminada '.$id,'created_at'=>now(),'updated_at'=>now()]); return response()->json(['deleted'=>true]); }
    public function history(){ $rows = DB::table('requests')->orderBy('created_at','desc')->get(); return response()->json($rows); }
}
