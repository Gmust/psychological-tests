<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Models\Test;
use Illuminate\Http\Response;

class TestController extends Controller
{

    /**
     * @return Response
     */
    public function index()
    {
        return Test::with(['questions.answers'])->paginate();
    }


}
