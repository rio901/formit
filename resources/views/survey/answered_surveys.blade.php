<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="{{ asset('css/style.css') }}" rel="stylesheet">
  <link rel="stylesheet" href="{{ asset('css/app.css') }}">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="{{ asset('js/script.js') }}"></script>
  <title>フォーム作成</title>
</head>
@include('layouts.header')
<div class="wrap">
<div class="card">
  <div class="card-header">
  <h1>回答済みアンケート一覧</h1>
  </div>
  <div class="card-body">
    <ul>
        @foreach ($answeredSurveys as $answeredSurvey)
            <li><a href="{{ route('survey.result', ['surveyId' => $answeredSurvey->survey_id]) }}">{{ $surveys->where('id', $answeredSurvey->survey_id)->first()->name }}</a></li>
        @endforeach
    </ul>
  </div>
</div>
</div>