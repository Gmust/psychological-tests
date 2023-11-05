<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\TestResource;
use App\Models\Test;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Response;

class TestController extends Controller
{

    /**
     * @return Response
     */
    public function index()
    {
        return  Test::with(['questions.answers'])->paginate(10);
    }

    /**
     * @param integer $id
     * @return Response
     */
    public function show(int $id): TestResource
    {
        return new TestResource(Test::with(['questions.answers'])->find($id));
    }
}
