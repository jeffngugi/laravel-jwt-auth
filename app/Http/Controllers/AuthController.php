<?php

namespace App\Http\Controllers;

use App\USer;
use Mail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;

class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
    }

    /**
     * Get a JWT token via given credentials.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        //see if the user email is verified
        $credentials = $request->only('email', 'password');

        if ($token = $this->guard()->attempt($credentials)) {
            return $this->respondWithToken($token);
        }

        return response()->json(['error' => 'Unauthorized'], 401);
    }



    public function register(Request $request){
       
        //validate request
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:users',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);
            //check if validator passess
        if($validator->fails()){
            return response()->json($validator->errors(), 400);
        }

        //append verification code to cod
        $verification_code = sha1(time());
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'verification_code' =>  $verification_code,
        ]);
            
        if($user){
            $email= $request->email;
            $name=$request->name;

            //Make mail function for the code below
            $subject = 'Please verify your email address';
    	    Mail::send('email.verify', ['name'=>$name,'verification_code'=>$verification_code],
    		function($mail) use ($email,$name,$subject){
    			$mail->from('sutralian@gmail.com','test verify');
    			$mail->to($email,$name);
    			$mail->subject($subject);
    		

    		});
            return response()->json(['status', 'User created successfully'], 201);
           
        }else{
            //ask users to retry
            return response()->json(['status', 'User could not be created'], 401);
        }


        if(!$token = auth()->attempt($request->only(['email', 'password'])))
        {
            return abort(401);
        }

        return $token;
    }

    public function emailVerify(){
        return response()->json(['status', 'email verification logics']);
    }
    /**
     * Get the authenticated User
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        return response()->json($this->guard()->user());
    }

    /**
     * Log the user out (Invalidate the token)
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        $this->guard()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken($this->guard()->refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => $this->guard()->factory()->getTTL() * 60
        ]);
    }

    /**
     * Get the guard to be used during authentication.
     *
     * @return \Illuminate\Contracts\Auth\Guard
     */
    public function guard()
    {
        return Auth::guard();
    }
}