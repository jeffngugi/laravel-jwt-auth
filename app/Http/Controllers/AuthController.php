<?php

namespace App\Http\Controllers;

use App\USer;
use Mail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\APIController;

class AuthController extends APIController
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        //Add methods that you would like to be protected by the jwt verify middleware
        $this->middleware('jwt.verify', ['except' => ['login', 'register']]);
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
        $email =  $request->email;
        $user = User::where('email', $email)->first();
       if(!$user){
           return $this->responseNotFound('User does not exist');
       }
        $credentials = $request->only('email', 'password');
        if ($token = $this->guard()->attempt($credentials)) {
            //Uncomment below to ensure user is verified before login
            // if(!$user->email_verified_at){
            //     return $this->responseUnprocessable('Verifiy your account first');
            // }
            return $this->respondWithToken($token);
        }

        return $this->responseUnauthorized();
    }



    public function register(Request $request){
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:users',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);
            //check if validator passess
        if($validator->fails()){
            return $this->responseUnprocessable($validator->errors());
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
            //Make mail function for the code below: UNcomment below to send email
            // $subject = 'Please verify your email address';
    	    // Mail::send('email.verify', ['name'=>$name,'verification_code'=>$verification_code],
    		// function($mail) use ($email,$name,$subject){
    		// 	$mail->from('sutralian@gmail.com','test verify');
    		// 	$mail->to($email,$name);
    		// 	$mail->subject($subject)
    		// });
            return $this->responseResourceCreated('User created successfully');
        }else{
            //ask users to retry
            return $this->responseUnauthorized('User could not be created');
        }


        if(!$token = auth()->attempt($request->only(['email', 'password'])))
        {
            return $this->responseUnauthorized();
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
        return response()->json($this->guard()->user()->only(['id','name','email']));
    }

    /**
     * Log the user out (Invalidate the token)
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        $this->guard()->logout();

        return $this->responseResourceUpdated('Successfully logged out');
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
            'access_token' => 'Bearer '.$token,
            'token_type' => 'bearer',
            'expires_in' => $this->guard()->factory()->getTTL() * 60
        ]);
    }

    public function getText(){
        return 'This is jeff';
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