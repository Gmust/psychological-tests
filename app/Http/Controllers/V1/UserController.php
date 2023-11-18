<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\UserCollection;
use App\Http\Resources\V1\UserResource;
use App\Models\Test;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{

  /**
   * Create user
   * @param Request $request
   * @return JsonResponse
   *
   **/

  public function createUser(Request $request)
  {
    try {
      $validateUser = Validator::make($request->all(),
        [
          'name' => 'required',
          'email' => 'required|email|unique:users,email',
          'password' => 'required'
        ]);

      if ($validateUser->fails()) {
        return response()->json([
          'status' => false,
          'message' => 'Validation Error',
          'errors' => $validateUser->errors()
        ], 401);
      }

      $user = User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => Hash::make($request->password)
      ]);

      return response()->json([
        'status' => true,
        'message' => 'User created successfully',
        'token' => $user->createToken('user-token')->plainTextToken
      ], 200);

    } catch (\Throwable $th) {
      return response()->json([
        'status' => false,
        'message' => $th->getMessage()
      ], 500);
    }
  }


  /**
   *
   * @param Request
   * @return JsonResponse
   *
   **/
  public function loginUser(Request $request)
  {
    try {
      $validateUser = Validator::make($request->all(), [
        'email' => 'required|email',
        'password' => 'required'
      ]);

      if ($validateUser->fails()) {
        return response()->json([
          'status' => false,
          'message' => 'Validation Error',
          'errors' => $validateUser->errors()
        ], 401);
      }

      if (!Auth::attempt($request->only(['email', 'password']))) {
        return response()->json([
          'status' => false,
          'message' => 'Invalid credentials'
        ], 401);
      }

      $user = User::with('role', 'passedTests')->where('email', $request->email)->first();

      if ($user->role_id === 1) {
        return response()->json([
          'status' => true,
          'message' => 'User logged in successfully',
          'token' => $user->createToken('admin-token', ['create', 'update', 'delete'])->plainTextToken,
          'user' => new UserResource($user)
        ], 200);
      }
      return response()->json([
        'status' => true,
        'message' => 'User logged in successfully',
        'token' => $user->createToken('user-token', ['create'])->plainTextToken,
        'user' => new UserResource($user)
      ], 200);
    } catch (\Throwable $th) {
      return response()->json([
        'status' => false,
        'message' => $th->getMessage()
      ], 500);
    }
  }


  /**
   *
   * @param Request
   * @return JsonResponse
   *
   **/
  public function passTest(Request $request)
  {
    try {

      $validateUser = Validator::make($request->all(), [
        'testId' => 'required',
        'userId' => 'required'
      ]);


      if ($validateUser->fails()) {
        return response()->json([
          'status' => false,
          'message' => 'Validation Error',
          'errors' => $validateUser->errors()
        ], 401);
      }

      $test_id = $request->testId;
      $user_id = $request->userId;

      $test = Test::find($test_id);
      $user = User::find($user_id);

      $user->passedTests()->save($test);

      return response()->json(['status' => true, 'message' => 'Test successfully passed and added to your statistic '], 200);

    } catch (\Throwable $th) {
      return response()->json([
        'status' => false,
        'message' => $th->getMessage()
      ], 500);
    }
  }
}
