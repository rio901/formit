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
@include('layouts.header')
<div class="wrap">
<div class="container_index">
    <h3>{{ $questionTitle }}</h3>
    @foreach($responseAnswers as $response)
        <div class="card">
            <div class="card-body">
                <p>・ {{ $response->response }}</p>
            </div>
        </div>
    @endforeach
</div>
<a href="{{ route('survey.result', ['surveyId' => $surveyId]) }}">戻る</a>
</div>