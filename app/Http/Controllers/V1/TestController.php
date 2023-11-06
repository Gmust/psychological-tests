<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\StoreTestRequest;
use App\Http\Resources\V1\TestResource;
use App\Models\Answer;
use App\Models\Question;
use App\Models\Test;
use Illuminate\Http\Client\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class TestController extends Controller
{

    /**
     * @return Response
     */
    public function index()
    {
        return Test::with(['questions.answers'])->paginate(10);
    }

    /**
     *
     */
    public function store(StoreTestRequest $request)
    {
        try {
            $jsonData = $request;
            $test = Test::create([
                'title' => $jsonData['title'],
                'total_points' => $jsonData['totalPoints'],
                'result' => $jsonData['result'],
            ]);

            foreach ($jsonData['questions'] as $questionData) {
                $question = new Question(['question_text' => $questionData['questionText']]);
                $test->questions()->save($question);

                foreach ($questionData['answers'] as $answerData) {
                    $answer = new Answer([
                        'answer_text' => $answerData['answerText'],
                        'points_for_answer' => $answerData['pointsForAnswer'],
                    ]);
                    $question->answers()->save($answer);
                }
            }
            $createdTest = Test::with('questions.answers')->where('id', $test->id)->first();
            return response()->json(['message' => 'Data saved successfully', 'test' => new TestResource($createdTest)], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Failed to save data'], 500);
        }
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
