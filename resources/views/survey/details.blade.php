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
<h3>{{ $questionTitle }}:回答内容詳細</h3>
<div class="container_index">
<ul>
    @foreach($freeDescriptions as $description)
        <div class="content">
          <li>・{{ $description }}</li>
        </div>
    @endforeach
</ul>
</div>
<a href="{{ route('survey.result', ['surveyId' => $surveyId]) }}">戻る</a>
</div>