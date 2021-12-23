<?php

namespace App\Http\Controllers\API;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Transaction;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Validator;

class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct() {
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request){
    	$validator = Validator::make($request->all(), [
            'username' => 'required|string|min:1',
            'password' => 'required|string|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        if (! $token = auth()->attempt($validator->validated())) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $userLog = User::where('username', $request->username)->where('valid', 1)->first();
        if($userLog==NULL){
            return response()->json(['error' => 'NotValid']);
        }else{
            return $this->createNewToken($token);
        }
    }

    /**
     * Register a User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request) {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|between:2,100',
            'username' => 'required|string|max:100|unique:users',
            'password' => 'required|string|max:100',
        ]);

        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 400);
        }

        try{
            $user = new User;
            $encryptedPass = Hash::make($request->password);
            $user->name = $request->name;
            $user->password = $encryptedPass;
            $user->phone = $request->phone;
            $user->address = $request->address;
            $user->gender = $request->gender;
            $user->skill = $request->skill;
            $user->waktu = $request->waktu;
            $user->username = $request->username;
            $user->valid = 0;
            $user->save();
        }catch(Exception $e){
            return response()->json([
                'success'=>false,
                'message'=>'User not registered'
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'User successfully registered'
        ], 201);
    }


    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout() {
        auth()->logout();

        return response()->json(['message' => 'User successfully signed out']);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh() {
        return $this->createNewToken(auth()->refresh());
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function userProfile() {
        return response()->json(auth()->user());
    }

    public function userEdit(Request $request) {
        $user = auth()->user();
        foreach ($user as $sku){ 
            $id_user = $user->id_user;
        }
        $userFind = User::find($id_user);
        $userFind->name = $request->name;
        $userFind->phone = $request->phone;
        $userFind->address = $request->address;
        $userFind->gender = $request->gender;
        $userFind->skill = $request->skill;
        $userFind->waktu = $request->waktu;

        $userFind->update();

        return response()->json(['message' => 'User profile successfully edited.']);
    }

    public function userDelete() {
        $user = auth()->user();
        foreach ($user as $sku){ 
            $id_user = $user->id_user;
        }
        $transFind = Transaction::where('id_user_transaction', $id_user)->get();
        foreach ($transFind as $sku){ 
            $sku->delete();
        }
        $userFind = User::find($id_user)->delete();
        auth()->logout();

        return response()->json(['message' => 'User successfully deleted.']);
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function createNewToken($token){
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60,
            'user' => auth()->user()
        ]);
    }

}