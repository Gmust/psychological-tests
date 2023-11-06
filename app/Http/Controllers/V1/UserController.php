<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\UserCollection;
use App\Http\Resources\V1\UserResource;
use App\Models\User;
use Illuminate\Http\Client\Response;

class UserController extends Controller
{


    public function index()
    {
        //return User::with('role', 'passedTests')->paginate();
        return new UserCollection(User::with('role', 'passedTests')->paginate());
    }

    public function show(int $id)
    {
        return new UserResource(User::with('role')->find($id));
    }
}
