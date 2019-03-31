<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\User;
use Validator;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\API\ResponseController as ResponseController;

class RegisterController extends ResponseController
{
   /**
    * Register api
    *
    * @return \Illuminate\Http\Response
    */

   public function register(Request $request)
   {
      $validator = Validator::make($request->all(), [
        'name' => 'required',
        'email' => 'required|email',
        'password' => 'required',
        'c_password' => 'required|same:password',
      ]);

      if ($validator->fails()) {

         $response = [
           'success' => false,
           'message' => 'Validation Error',
           'data' => $validator->errors(),
         ];

         if (!empty($errorMessages)) {
            $response['data'] = $errorMessages;
         }

         return $this->sendError('Validation Error.', $validator->errors());
      }

      $user = User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => bcrypt($request->password)
      ]);

      $success['token'] = $user->createToken('MyApp')->accessToken;
      $success['name'] = $user->name;
      return $this->sendResponse($success, 'User registered successfully.');

   }


   /**
    * Handles Login Request
    *
    * @param Request $request
    * @return \Illuminate\Http\JsonResponse
    */
   public function login(Request $request)
   {
      $credentials = [
        'email' => $request->email,
        'password' => $request->password
      ];

      if (auth()->attempt($credentials)) {
         $success['token'] = auth()->user()->createToken('TutsForWeb')->accessToken;
         return $this->sendResponse($success, 'User Logged successfully.');
      } else {
         return $this->sendError('UnAuthorised Access', ['error' => 'UnAuthorised']);
      }
   }

   /**
    * Returns Authenticated User Details
    *
    * @return \Illuminate\Http\JsonResponse
    */
   public function details()
   {
      return response()->json(['user' => auth()->user()], 200);
   }
}
