<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="{{ asset('css/style.css') }}" rel="stylesheet">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="{{ asset('js/script.js') }}" defer></script>
  <title>フォーム結果</title>
</head>
@include('layouts.header')
<div class="wrap">
@if($isCreator == 1)
<div class="content">    
    <h2>フォーム回答状況</h2>

    <p>回答: {{ $uniqueResponsesCount }}件</p>
    <p>認証ユーザーの回答: {{ $responseCountTwo }}件</p>
    <p>ゲストユーザーの回答: {{ $responseCountOne }}件</p>
    <a href="{{ route('csv.download', ['surveyId' => $surveyId]) }}">CSVファイルをダウンロード</a>
</div>
    <!-- $questionOptionsCounts[$question->id][$option->label] ?? 0  -->
@endif
    <div class="content">
    <h2>各設問毎の結果</h2>
    @foreach($questions as $question)
    
        <div class="card">
            <div class="card-header">
                {{ $question->title }}
            </div>
            <div class="card-body">
            @if($question->type == 0)
                <ul>
                    <li>
                        <p>回答記入件数: {{ $questionOptionsCounts[$question->id][$question->free_description] ?? 0 }}件</p></br>
                        @if($isCreator == 1)
                        <a href="{{ route('survey.details', ['questionId' => $question->id]) }}">詳細</a>
                        @endif
                    </li>
                </ul>
                @else
                <ul>
                @foreach($question->options as $option)
                    <li>
                        {{ $option->label }}: {{ $questionOptionsCounts[$question->id][$option->id] ?? 0 }}件
                    </li>
                @endforeach
                </ul>
                @endif
            
            </div>
        </div>
    @endforeach
    </div>
    @if($showFreeCommentLink)
    <div class="content">
        <a href="{{ route('free_comment', ['surveyId' => $surveyId]) }}">フリーコメントの結果を見る</a>
    </div>
    @endif
</div>