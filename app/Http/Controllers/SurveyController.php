<?php

namespace App\Http\Controllers;
use App\Http\Requests\SurveyRequest;
use App\Models\create_format;
use App\Models\response_answers;
use App\Models\surveys;
use App\Models\questions;
use App\Models\question_options;
use App\Models\answers;
use App\Models\answers_options;
use App\Models\survey;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SurveyController extends Controller
{
    public function guest()
    {
        return view('auth.guest');
    }

    public function index(){
        // 現在ログイン中のユーザーのIDを取得
        $userId = Auth::id();

        // ログイン中のユーザーが作成したフォームを取得
        $forms = surveys::whereHas('createFormat', function ($query) use ($userId) {
            $query->where('user_id', $userId);
        })->latest()->paginate(5);

        foreach ($forms as $form) {
            $inviteCode = create_format::where('id', $form->invite_id)->value('invite_code');
            $form->invite_code = $inviteCode;
        }

        // フォームごとの回答データを取得
        $responses = [];
        foreach ($forms as $form) {
            $response = response_answers::where('survey_id', $form->id)->exists();
            $responses[$form->id] = $response;
        }


        return view('survey.index', [
            'forms' => $forms,
            'responses' => $responses,
        ]);
    }


    public function viewResponses($surveyId) {
        // アンケート情報を取得
        $survey = surveys::findOrFail($surveyId);
        $userId = Auth::id();
        $create_format = create_format::where('id', $survey->invite_id)->first();
        $isCreator = 0; // 初期値を0に設定

        if ($create_format && $create_format->user_id == $userId) {
            $isCreator = 1; // $userIdと$user_idが一致する場合、$isCreatorを1に設定
        }
        // dd($isCreator);
        $allResponses = response_answers::where('survey_id', $surveyId)->get();

        // responseが1または2の数を取得
        $responseCountOne = response_answers::where('survey_id', $surveyId)->where('user_type', 0)->count();
        $responseCountTwo = response_answers::where('survey_id', $surveyId)
        ->where('user_type', '!=', 0)
        ->count();
    
        // 各質問内容を取得
        $questions = questions::where('survey_id', $surveyId)->get();
    
        foreach ($questions as $question) {
            $questionCount = [];
            if ($question->type == 0) {
                // 自由記述回答の件数を取得
                $freeTextResponsesCount = DB::table('answers')
                    ->where('question_id', $question->id)
                    ->whereNotNull('free_description')
                    ->count();
                
                // 質問IDをキーとして自由記述回答の件数または 0 を代入
                $questionOptionsCounts[$question->id][$question->free_description] = $freeTextResponsesCount;
            }

            $options = $question->options;
            $optionCounts = [];

            foreach ($options as $option) {
                // answer_optionsテーブルから該当するラベルの回答数を取得
                $optionCount = answers_options::where('option_id', $option->id)->count();
    
                // オプションIDをキーとして回答数を配列に格納
                $optionCounts[$option->id] = $optionCount;
                $questionOptionsCounts[$question->id] = $optionCounts;
                
            }
        };

        $responses = response_answers::where('survey_id', $surveyId)->get();
        $processedResponses = [];

        foreach ($responses as $response) {
            $answers = answers::where('response_id', $response->id)->get();
            $processedAnswers = [];

            foreach ($answers as $answer) {
                $options = answers_options::where('option_id', $answer->text_option)->first();
                $optionCount = 0; // 初期化

                if ($options) {
                    $optionCount = answers::where('question_id', $options->question_id)
                    ->where('text_option', $options->option_id)
                    ->count();
                    $options->responsesCount = $optionCount;
                }

                $processedAnswers[] = [
                    'answer' => $answer,
                    'options' => $options,
                    'responsesCount' => $optionCount,
                ];
            }
        }      
        $showFreeCommentLink = $survey->free_comment !== null;
        // dd($processedResponses);
        $downloadLink = $this->downloadResponses($surveyId);
        return view('survey.result', [
            'surveyId' => $surveyId, 
            'isCreator' => $isCreator,
            'uniqueResponsesCount' => $allResponses->count(),  // 全件数を取得
            'responseCountOne' => $responseCountOne,  // responseが1の数を取得
            'responseCountTwo' => $responseCountTwo,  // responseが2の数を取得
            'questions' => $questions,
            'questionOptionsCounts' => $questionOptionsCounts,
            'processedResponses' => $processedResponses,
            'downloadLink' => $downloadLink,
            'showFreeCommentLink' => $showFreeCommentLink,

        ]);
    }

    public function showFreeComment($surveyId)
    {
        // 指定された調査に対する回答のフリーコメントを取得します
        $responseAnswers = response_answers::where('survey_id', $surveyId)->get();

        $questionTitle = surveys::findOrFail($surveyId)->free_comment;

        // その他の処理（例えば、ビューを返すなど）...

        return view('survey.free_comment', [
            'surveyId' => $surveyId,
            'responseAnswers' => $responseAnswers,
            'questionTitle' => $questionTitle,
        ]);
    }

    public function showDetails($questionId) {
        // 該当する質問IDを持つ回答の自由記述回答を取得
        $freeDescriptions = answers::where('question_id', $questionId)
                                    ->whereNotNull('free_description')
                                    ->pluck('free_description');

        $surveyId = questions::findOrFail($questionId)->survey_id;
        $questionTitle = questions::findOrFail($questionId)->title;
    
        return view('survey.details', [
            'surveyId' => $surveyId,
            'freeDescriptions' => $freeDescriptions,
            'questionTitle' => $questionTitle,
        ]);
    }
    
    public function downloadResponses($surveyId) {
        // response_answersテーブルからデータを取得
        $responses = DB::table('response_answers')->where('survey_id', $surveyId)->get();
    
        // 質問情報を取得
        $questions = questions::where('survey_id', $surveyId)->get();
    
        // ファイル名
        $fileName = 'responses.csv';
    
        // CSVファイルを生成してダウンロード
        return response()->streamDownload(function () use ($responses, $questions) {
            $stream = fopen('php://output', 'w');
            
            // CSVヘッダーを書き込む
            $headers = ['ユーザーID', 'ユーザー種別'];
            foreach ($questions as $question) {
                if ($question->type == 0) {
                    $headers[] = $question->title;
                } else {
                    foreach ($question->options as $option) {
                        $headers[] = $option->label;
                    }
                }
            }
            fputcsv($stream, $headers);

            // CSVデータを書き込む
            foreach ($responses as $response) {
                $rowData = [
                    $response->user_id,
                    $response->user_type,
                ];
                foreach ($questions as $question) {
                    if ($question->type == 0) {
                        $answer = answers::where('response_id', $response->id)
                            ->where('question_id', $question->id)
                            ->first();
                        if ($answer) {
                            $rowData[] = $answer->free_description;
                        } else {
                            $rowData[] = '';
                        }
                    } else {
                        $selectedOptions = [];
                        foreach ($question->options as $option) {
                            $answer = answers::where('response_id', $response->id)
                                ->where('question_id', $question->id)
                                ->whereHas('options', function ($query) use ($option) {
                                    $query->where('option_id', $option->id);
                                })
                                ->exists();
                            // 選択されているオプションに対応する位置に 1 を設定
                            $selectedOptions[] = $answer ? 1 : 0;
                        }
                        $rowData = array_merge($rowData, $selectedOptions);
                    }
                }
                fputcsv($stream, $rowData);
            }
    
            fclose($stream);
        }, $fileName);
    }

    public function form($surveyId) {
        // $surveyIdに基づいてアンケートを取得
        $survey = surveys::findOrFail($surveyId);
    
        // アンケートに紐づく質問を取得
        $questions = $survey->questions;

        return view('survey.form', ['questions' => $questions]);
    }
    public function search(Request $request)
    {
        $inviteCode = $request->input('invite_code');

        // 招待コードに基づいてアンケートを検索
        $survey = surveys::whereHas('createFormat', function ($query) use ($inviteCode) {
            $query->where('invite_code', $inviteCode);
        })->first();

        if ($survey) {
            // アンケートに紐づく質問を取得
            $questions = $survey->questions;

            // 検索結果をフォーム画面に渡す
            return view('survey.form', ['questions' => $questions, 'survey' => $survey]);
        } else {
            // 該当するアンケートが見つからない場合の処理
            return redirect()->route('survey.index')->with('error', '該当するアンケートが見つかりませんでした。');
        }
    }

    public function create(){
    return view('survey.create');
    }
    public function storeSurvey(SurveyRequest $request)
{
    $rules = [
        'invite_code' => 'required|max:32', 
        'name' => 'required|string|max:255',
    ];
    $customMessages = [
        'invite_code.required' => '招待コードは必須項目です。',
        'invite_code.max' => '招待コードは:max文字以内で入力してください。',
        'name.required' => '招待コードは必須項目です。',
        'name.max' => '招待コードは:max文字以内で入力してください。',
        
    ];
    // フォームリクエストを検証して、有効なデータを取得
    $validatedData = $request->validated();

    // create_format テーブルにデータを登録
    $create_format = new create_format();
    $create_format->invite_code = $request->invite_code;
    $create_format->user_id = Auth::user()->id;
    $create_format->save();

    $surveys = new surveys();
    $surveys->name = $request->input('name');
    $surveys->invite_id = $create_format->id; 
    $surveys->save();


    // リダイレクトとメッセージの設定
    return redirect()
    ->route('survey.questions')
    ->with([
        'message' => 'アンケートの作成が完了しました。',
        'surveyId' => $surveys->id,
    ]);
}

public function storeQuestions(Request $request)
{
    $questions = $request->input('questions');
    $options = $request->input('options');
    $surveyId = session('surveyId');
    logger('Survey ID: ' . $surveyId); // ログに出力
    // $validatedData = $request->validated();
    // dd($questions, $options);
    $freeComment = $request->input('survey');
    $survey_id = $request->input('survey_id');
    // dd($freeComment);
    // dd($survey_id);
    $survey = surveys::find($survey_id);
    if ($survey !== null) {
        $freeComment = $request->input('survey');
        if ($freeComment !== null && isset($freeComment['free_comment'])) {
            $survey->free_comment = $freeComment['free_comment'];
            $survey->save();
        }
    }   

    // 質問データの保存
    foreach ($questions as $question) {
        $newQuestion = questions::create([
            'survey_id' => $question['survey_id'],
            'question_num' => $question['question_num'],
            'title' => $question['title'],
            'type' => $question['type'],
        ]);

        // 質問の選択肢データの保存
        if (isset($options[$question['question_num']]['label'])) {
            foreach ($options[$question['question_num']]['label'] as $optionData) {
                $newQuestion->options()->create([
                    'label' => $optionData['text'],
                ]);
            }
        }
    }

    return redirect()->route('survey.complete')->with('message', 'アンケートの作成が完了しました。');
}



public function storeAnswers(Request $request)
{
    logger('Request Data: ' . json_encode($request->all()));
    $answers = $request->input('answers');
    $options = $request->input('options');
    $survey = $request->input('survey');
    $response = $request->input('response');
    // $validatedData = $SurveyRequest->validated();

    $userIdToSave = Auth::check() ? Auth::user()->id : 1;
    $userType = Auth::check() ? Auth::user()->id : 0;

    // response_answers テーブルに回答を保存
    $newResponse = response_answers::create([
        'user_id' => $userIdToSave, 
        'survey_id' => $survey,
        'user_type' => $userType,
        'response' => $response,
    ]);
    $responseId = $newResponse->id; // 保存した回答の ID を取得

    foreach ($answers as $id => $answer) {
    logger("Answer Data for Question ID $id: " . json_encode($answer));

    $responseId = $newResponse->id; // 保存した回答の ID を取得

    // 質問の回答を保存
    $newAnswers =answers::create([
        'question_id' => $id,
        'response_id' => $responseId,
        'text_option' => $answer['type'],
        'free_description' => $answer['text'] ?? null,
        'email' => $userIdToSave && Auth::user() ? Auth::user()->email : "1", // 認証ユーザーの場合は email を保存
    ]);

    // ラジオボタンまたはチェックボックスの場合、オプションのデータを保存
    if (isset($options[$id]) && is_array($options[$id]['option_id'])) {
        foreach ($options[$id]['option_id'] as $index => $optionNum) {
            $newAnswers->options()->create([
                'label' => $options[$id]['label'][$index],
                'option_id' => $optionNum,
            ]);
        }
    }
    }

    return redirect()->route('survey.complete')->with('message', '回答が送信されました。');
}

public function editSurvey($surveyId)
{
    // アンケートデータを取得します
    $survey = surveys::findOrFail($surveyId);

    // 既に作成された質問を取得します
    $questions = questions::where('survey_id', $surveyId)->get();
    
    // 他の必要な処理を行います
    
    // edit.phpに質問データとsurveyIdを渡して返します
    return view('survey.edit')
        ->with([
            'message' => 'アンケートの編集が完了しました。',
            'surveyId' => $survey->id,
            'surveyName' => $survey->name,
            'questions' => $questions,
        ]);
}
public function update(Request $request, $id) {
    $formData = $request->input('questions');
    $surveyData = $request->input('survey');

    try {
        DB::beginTransaction();

        // アンケートフォームを取得
        $form = surveys::findOrFail($id);

        // アンケートフォームの名前を更新
        $existingSurvey = surveys::find($surveyData['id']);
        if ($existingSurvey) {
            $existingSurvey->update(['name' => $surveyData['name']]);
        }

        // 質問とオプションの更新を行う
        foreach ($formData as $questionId => $questionData) {
            $existingQuestion = questions::find($questionId);
            if ($existingQuestion) {
                // 質問のタイトルを更新
                $existingQuestion->update(['title' => $questionData['title']]);
        
                // オプションの処理
                if (isset($questionData['options'])) {
                    // dd($questionData['options']);
                    foreach ($questionData['options'] as $optionData) {
                        
                        // IDが存在するかどうかをチェック
                        if (isset($optionData['id'])) {
                            // dd($optionData);
                            $existingOption = question_options::find($optionData['id']);
                            if ($existingOption) {
                                $existingOption->update(['label' => $optionData['label']]);
                            }
                        } else {
                            // dd($optionData);
                            // IDが存在しない場合は新しいオプションを追加
                            $newOption = new question_options;
                            $newOption->question_id = $optionData['question_id'];
                            $newOption->label = $optionData['text'];
                            $newOption->save();
                        }
                    }
                }
            }
        }

        DB::commit();

        return redirect()->route('survey.index')->with('success', 'フォームが更新されました。');
    } catch (\Exception $e) {
        DB::rollBack();
        return back()->with('error', 'フォームの更新中にエラーが発生しました。');
    }
}

    public function deleteQuestion(Request $request)
    {
        // リクエストから削除対象の設問のIDを取得
        $questionId = $request->input('question_id');
        dd($questionId);

        try {
            // 設問を取得して削除
            $question = questions::findOrFail($questionId);
            $question->delete();

            // 成功時のレスポンス
            return response()->json(['success' => true, 'message' => '設問が削除されました']);
        } catch (\Exception $e) {
            // エラー時のレスポンス
            return response()->json(['success' => false, 'message' => '設問の削除中にエラーが発生しました']);
        }
    }

    public function answeredSurveys()
        {
    // 現在ログイン中のユーザーのIDを取得
    $userId = Auth::id();
    
    // ログイン中のユーザーが回答したアンケート一覧を取得
    $answeredSurveys = response_answers::where('user_id', $userId)->get();
    
    // アンケートの名前を取得する
    $surveyIds = $answeredSurveys->pluck('survey_id')->unique()->toArray();
    $surveys = surveys::whereIn('id', $surveyIds)->get();

    return view('survey.answered_surveys', [
        'answeredSurveys' => $answeredSurveys,
        'surveys' => $surveys,
    ]);
    }

    public function aggregate($surveyId)
    {
        // 特定の survey_id に紐づく質問データを取得
        $questions = questions::where('survey_id', $surveyId)->pluck('id');
    
        // 特定の質問に紐づく回答データを取得
        $answers = answers::whereIn('question_id', $questions)->get();
    
        return view('survey.data', ['answers' => $answers]);
    }

    public function deleteSurvey($surveyId)
    {
        // 該当するフォームをデータベースから取得して削除します
        $form = surveys::findOrFail($surveyId);
        
        // 関連するcreate_formatテーブルのデータも削除します
        if ($form->createFormat) {
            $form->createFormat->delete();
        }

        // Surveyテーブルのデータを削除します
        $form->delete();

        // 削除後はリダイレクトや他の処理を行います
        return redirect()->route('survey.index')->with('message', 'フォームが削除されました');
    }


    // public function show(Post $post){
    // return view('survey.result', compact('post'));
    // }
    // public function edit(Post $post){
    // return view('survey.edit', compact('post'));
    // }
    // public function update(PostRequest $request, Post $post){
    // $post->update([
    //     'title' => $request->title,
    //     'description' => $request->description
    // ]);

    // return redirect()->route('survey.index')->with('message', '更新が完了しました。');
    // }

    // public function destroy(Post $post){
    // $post->delete();
    // return redirect()->route('survey.index')->with('message', '削除が完了しました。');
    // }
}
