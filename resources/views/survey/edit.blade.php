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
  <title>フォーム編集</title>
</head>
@include('layouts.header')
@if(session('error'))
    <div class="alert alert-success">
        {{ session('error') }}
    </div>
@endif
<div class="wrap">
    <div class="container_index">
    <form method="POST" action="{{ route('survey.update', $surveyId) }}">
    @csrf
    @method('PUT') 

    <input type="hidden" name="survey[id]" value="{{ $surveyId }}"> 

    <table>
        <thead>
            <tr>
                <th><h1>編集</h1></th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><div class="content">フォームタイトル<input type="text" name="survey[name]" value="{{ $surveyName }}"></div></td>
            </tr>
            
            @foreach($questions as $index => $question)
            <tr>
                <td>
                    <div class="content">
                        <p>{{ $index + 1 }}番目の設問本文</p>
                        <input type="hidden" name="questions[{{ $question->id }}][id]" value="{{ $question->id }}">
                        <input type="text" name="questions[{{ $question->id }}][title]" value="{{ $question->title }}"></p>
                        <br>
                        @if($question->type != 0)
                        <p>オプション</p>
                            @foreach($question->options as $option)
                            <input type="hidden" name="questions[{{ $question->id }}][options][{{ $option->id }}][id]" value="{{ $option->id }}">
                            <input type="text" name="questions[{{ $question->id }}][options][{{ $option->id }}][label]" value="{{ $option->label }}">
                            
                            @endforeach
                            <button type="button" onclick="addOptionsEdit({{ $question->id }})" data-question-id="{{ $question->id }}">オプションを追加</button>
                            <div id="options-container_{{ $question->id }}">
                                <!-- ここに選択肢が追加されます -->
                            </div>

                        @endif
                        
                    </div>
                </td>
            </tr>
            @endforeach           
        </tbody>
    </table>
    <div class="content">
        <button type="submit">更新</button> 
    </div>
</form>
    </div>
</div>

</body>
</html>
