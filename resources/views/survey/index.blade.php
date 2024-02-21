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
  <title>USER TOP</title>
</head>
<body>
@include('layouts.header')
@if(session('error'))
    <div class="alert alert-success">
        {{ session('error') }}
    </div>
@endif
@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

<div class="wrap">
  <div class='container_index'>
    <div class="content">
      <button class="A" onclick="location.href='{{ route('survey.create') }}'">アンケートを新規作成</button>
    </div>
    <div class="content">
      <form method="GET" action="{{ route('survey.search') }}">
        @csrf
        <input type="text" name="invite_code" placeholder="招待コードを入力...">
        <button class="A" type="submit">検索</button>
      </form>
    </div>
    <div class="content">
    <a href="{{ route('answered.surveys') }}">回答したフォームの結果を確認</a>
    </div>
  </div>

  <div class="content">
    
    @if(isset($forms) && $forms->count() > 0)
    <h3>作成済みフォーム</h3>
        @foreach($forms as $form)
          <div class="card">
            <div class="card-header">
              タイトル：{{ $form->name }} <br>
              招待コード: {{ $form->invite_code }}
            </div>
            <div class="card-body">
              @if(isset($responses[$form->id]) && $responses[$form->id])
                <a href="{{ route('survey.result', ['surveyId' => $form->id]) }}">回答状況を見る</a>
              @elseif($form->questions->isEmpty())
                <p>設問が作成されていません</p>
                <a href="{{ route('survey.questions', ['surveyId' => $form->id]) }}">作成</a>
              @else
                <p>回答はまだありません</p>
                <a href="{{ route('survey.edit', ['surveyId' => $form->id]) }}">編集</a>
              @endif

              <form method="POST" action="{{ route('survey.delete', ['surveyId' => $form->id]) }}">
                @csrf
                @method('DELETE')
                <button class="delete" type="submit" onclick="return confirm('削除してもよろしいですか？')">削除</button>
              </form>
              
              </div>
          </div>
        @endforeach

        {{-- ページネーションの表示 --}}
      
        {{ $forms->links() }}
    @else
    <div class="content">
        <button class="A" onclick="location.href='{{ route('dashboard') }}'">フォーム一覧を表示</button>
    </div>
    @endif
</div>



</div>