<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\StoreAnswerRequest;
use App\Http\Requests\V1\UpdateAnswerRequest;
use App\Http\Resources\V1\AnswerResource;
use App\Models\Answer;
use App\Models\Question;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AnswerController extends Controller
{

  /**
   *
   * @param Request $request
   *
   * */
  public function store(StoreAnswerRequest $request): JsonResponse
  {
    try {

      $question_id = $request->input('questionId');
      $question = Question::find($question_id);

      if (!$question) {
        return response()->json([
          'success' => false,
          'message' => 'Non-existent question selected',
        ], 403);
      }

      $new_answer = new Answer(['answer_text' => $request['answerText'], 'points_for_answer' => $request['pointsForAnswer']]);
      $question->answers()->save($new_answer);

      return response()->json(['message' => 'New answer created successfully', 'answer' => $new_answer]);

    } catch (\Exception $e) {
      Log::error('Failed to save data: ' . $e->getMessage());
      return response()->json(['message' => 'Failed to save data. Please try again.'], 500);
    }
  }


  /**
   *
   * @param Request $request
   * @param Answer $answer
   * @return string|false
   */
  public function update(UpdateAnswerRequest $request, Answer $answer)
  {
    $request['answer_text'] = $request['answerText'];
    $request['points_for_answer'] = $request['pointsForAnswer'];
    $answer->update($request->all());
    return response()->json(['message' => 'Data updated successfully', 'test' => new AnswerResource($answer)], 201);
  }

  public function destroy(int $id)
  {
    $answer = Answer::find($id);
    $answer->delete();
    return response()->json(['message' => 'Answer successfully deleted'], 200);
  }


}
