<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller {
    public function checkAccount(Request $r){
        $user = User::where('email',$r->user)->orWhere('name',$r->user)->first();
        return response()->json(['exists'=>(bool)$user]);
    }
    public function login(Request $r){
        $user = User::where('email',$r->user)->orWhere('name',$r->user)->first();
        if(!$user) return response()->json(['message'=>'Usuario no existe'],404);
        if(!Hash::check($r->password, $user->password)) return response()->json(['message'=>'Clave incorrecta'],401);
        $token = $user->createToken('api-token')->plainTextToken;
        return response()->json(['token'=>$token,'role'=>$user->role,'user'=>['id'=>$user->id,'name'=>$user->name]]);
    }
    public function logout(Request $r){
        $r->user()->currentAccessToken()->delete();
        return response()->json(['logout'=>true]);
    }
}
