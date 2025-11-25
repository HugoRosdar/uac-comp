<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
class ProductController extends Controller {
    public function index(){ return response()->json(Product::with('category')->get()->map(function($p){ return ['id'=>$p->id,'name'=>$p->name,'description'=>$p->description,'category_id'=>$p->category_id,'category_name'=>$p->category? $p->category->name:'','quantity'=>$p->quantity,'min_quantity'=>$p->min_quantity]; })); }
    public function store(Request $r){ $p = Product::create($r->only(['name','description','category_id','quantity','min_quantity'])); DB::table('movements')->insert(['user_id'=>$r->user()->id ?? null,'action'=>'create_product','details'=>'Producto creado: '.$p->name,'created_at'=>now(),'updated_at'=>now()]); return response()->json($p,201); }
    public function update(Request $r,$id){ $p = Product::findOrFail($id); $p->update($r->only(['name','description','category_id','quantity','min_quantity'])); return response()->json($p); }
    public function destroy(Request $r,$id){ $p = Product::findOrFail($id); $p->delete(); DB::table('movements')->insert(['user_id'=>$r->user()->id ?? null,'action'=>'delete_product','details'=>'Eliminado: '.$p->name,'created_at'=>now(),'updated_at'=>now()]); return response()->json(['deleted'=>true]); }
}
