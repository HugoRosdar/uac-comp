<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Product extends Model {
    protected $fillable = ['name','description','category_id','quantity','min_quantity'];
    public function category(){ return $this->belongsTo(Category::class); }
}
