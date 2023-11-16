<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\StoreQuestionRequest;
use App\Http\Requests\V1\UpdateQuestionRequest;
use App\Http\Resources\V1\QuestionResource;
use App\Models\Answer;
use App\Models\Question;
use App\Models\Test;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

// Corrected import statement

class QuestionController extends Controller
{

  /**
   *
   * @param Request $request
   *
   */
  public function store(StoreQuestionRequest $request): \Illuminate\Http\JsonResponse
  {
    try {
      $testId = $request->input('testId');
      $test = Test::find($testId);

      if (!$test) {
        return response()->json([
          'success' => false,
          'message' => 'Non-existent test selected',
        ], 403);
      }

      $question = new Question(["question_text" => $request['questionText']]);
      $test->questions()->save($question);

      foreach ($request['answers'] as $answerData) {
        $answer = new Answer([
          'answer_text' => $answerData['answerText'],
          'points_for_answer' => $answerData['pointsForAnswer']
        ]);
        $question->answers()->save($answer);
      }
      $newQuestion = Question::with('answers')->where('id', $question->id)->first();
      return response()->json(['message' => 'New question created successfully', 'question' => $newQuestion]);
    } catch (\Exception $e) {
      Log::error('Failed to save data: ' . $e->getMessage());
      return response()->json(['message' => 'Failed to save data. Please try again.'], 500);
    }
  }


  /**
   *
   * @param Request $request
   * @param Question $question
   * @return string|false
   */
  public function update(UpdateQuestionRequest $request, Question $question)
  {
    $request['question_text'] = $request['questionText'];
    $question->update($request->all());
    return response()->json(['message' => 'Data updated successfully', 'test' => new QuestionResource($question)], 201);
  }


  public function destroy(int $id)
  {
    $question = Question::find($id);
    $question->delete();
    return response()->json(['message' => 'Question successfully deleted'], 200);
  }

}
