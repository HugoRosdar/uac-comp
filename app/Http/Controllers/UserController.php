<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
class UserController extends Controller {
    public function index(){ return response()->json(User::all()); }
    public function store(Request $r){ $data = $r->only(['name','email','role']); $data['password']=Hash::make($r->password ?? 'password'); $u = User::create($data); return response()->json($u,201); }
    public function update(Request $r,$id){ $u = User::findOrFail($id); $u->update($r->only(['name','email','role'])); return response()->json($u); }
    public function toggle(Request $r,$id){ $u = User::findOrFail($id); $u->active = !$u->active; $u->save(); return response()->json($u); }
}
