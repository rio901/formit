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
  <title>アンケートフォーム</title>
</head>
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

        <input type="hidden" name="survey_id" value="{{ $surveyId }}"> 

        <table>
            <thead>
                <tr>
                    <th><h1>編集</h1></th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><div class="content">フォームタイトル：{{ $surveyName }}</div></td>

                </tr>
                <tr>
                    <td><div class="content">タイトルを変更<textarea type="text" name="name"></textarea></div></td>
                </tr>  
                <tr>
                    <td>
                        <div class="content">
                            <button type="button" onclick="addQuestion()">質問を追加</button><br>
                        </div>
                        <div id="questions-container">
                            <!-- ここに質問が追加されます -->
                        </div>
                    </td>
                </tr>
          
                    @foreach($questions as $index => $question)
                    <tr>
                        @if($question->type == 0)
                            <td>
                            <div class="content">
                                {{ $index + 1 }}番目の設問:
                                {{ $question['title'] }}
                                <br>自由記述


                                <button class="delete" onclick="remove({{ $index }})">設問の削除</button>
                            </div>
                            </td>
                        @else
                            <td>
                            <div class="content">
                                {{ $index + 1 }}番目の設問:
                                {{ $question['title'] }}</br>

                                @foreach($question->options as $option)
                                {{ $option['label'] }}
                                @endforeach


                                <button class="delete" onclick="remove({{ $index }})">設問の削除</button>
                            </div>
                            </td>
                        @endif
                    </tr>
                    <tr>
                        <td>
                            <div class="content">
                                <button type="button" onclick="editQuestion()">質問を追加</button><br>
                            </div>
                            <div id="edit_container">
                                <!-- ここに質問が追加されます -->
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
