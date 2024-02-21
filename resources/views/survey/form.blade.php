<!DOCTYPE html>
@php
    $count = 1; // カウンターの初期値
@endphp
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="{{ asset('css/style.css') }}" rel="stylesheet">
  <link rel="stylesheet" href="{{ asset('css/app.css') }}">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="{{ asset('js/script.js') }}"></script>
  <title>アンケートフォーム</title>
</head>
<body>
@include('layouts.header')
<div class="wrap_form">
  <form method="POST" action="{{ route('submit_answers') }}">
    @csrf
    <div class="title_wrap"><h2>{{ $survey->name }}</h2></div>
    @auth
    <input type="hidden" name="answers" value="{{ Auth::check() ? Auth::user()->email : 'guest' }}" readonly>
    @else
    <a>ゲストとして回答中</a>
    <input type="hidden" name="answers" value="{{ Auth::check() ? Auth::user()->email : '1' }}" readonly>
    @endauth
    <input type="hidden" name="survey" value="{{ $survey->id }}" readonly>
      @foreach($questions as $question)
      <table>
      <thead>
        <tr>
        <th>問{{ $count }}. {{ $question->title }}</th>
        @php
        $count++; // カウンターをインクリメント
        @endphp
        </tr>
      </thead>
      <tbody>
        <tr>
          <td>
          @if($question->type == 0)
            <!-- 自由記述の入力フォーム -->
            <input type="hidden" name="answers[{{ $question->id }}][id]" value="{{ $question->id }}">
            <input type="hidden" name="answers[{{ $question->id }}][type]" value="{{ $question->type }}">
            <textarea name="answers[{{ $question->id }}][text]"></textarea>
          @elseif($question->type == 1)
            <!-- ラジオボタンの入力フォーム -->
            @foreach($question->options as $option)
            <div class="option">
              <input type="hidden" name="answers[{{ $question->id }}][id]" value="{{ $question->id }}">
              <input type="hidden" name="answers[{{ $question->id }}][type]" value="{{ $question->type }}">
              <input type="radio" name="options[{{ $question->id }}][option_id][]" value="{{ $option->id }} ">
              <input readonly type="text" name="options[{{ $question->id }}][label][]" value="{{ $option->label }}"><br>
            </div>
            @endforeach
            @else
            <!-- チェックボックスの入力フォーム -->
            @foreach($question->options as $option)
            <div class="option">
              <input type="hidden" name="answers[{{ $question->id }}][id]" value="{{ $question->id }}">
              <input type="hidden" name="answers[{{ $question->id }}][type]" value="{{ $question->type }}">
              <input type="checkbox" name="options[{{ $question->id }}][option_id][]" value="{{ $option->id }}">
              <input readonly type="text" name="options[{{ $question->id }}][label][]" value="{{ $option->label }}"><br>
            </div>
            @endforeach
          @endif
          </td>
        </tr>
      @endforeach
      </tbody>
    </table>
    @auth
      @if(isset($survey->free_comment))
        <div class="content">
          <h1>フリーコメント：{{ $survey->free_comment }}</h1><br>
          
          <input type="text" name="response">

        </div>
      @endif
    @endauth
    <button type="submit">回答を送信</button>
  </form>
</div>
</div>
</body>
