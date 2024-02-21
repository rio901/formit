<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="{{ asset('css/style.css') }}" rel="stylesheet">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="{{ asset('js/script.js') }}" defer></script>
  <script>
  function toggleFreeCommentForm() {
    var selectBox = document.getElementById('use_free_comment');
    var selectedValue = selectBox.options[selectBox.selectedIndex].value;
    var freeCommentForm = document.getElementById('free-comment-form');
    if (selectedValue === '1') {
      freeCommentForm.style.display = 'block'; // フォーム表示
    } else {
      freeCommentForm.style.display = 'none';  // フォーム非表示
    }
  }
</script>
  <title>フォーム作成</title>
</head>
@include('layouts.header')
<div class="wrap">
@if(session('error'))
    <div class="alert alert-success">
        {{ session('error') }}
    </div>
@endif
<div class="content">
  <form method="POST" action="{{ route('store-questions') }}">
    @csrf
    @if(session('surveyId'))
    <input type="hidden" name="survey_id" value="{{ session('surveyId') }}">
    @else
    <input type="hidden" name="survey_id" value="{{ $_GET['surveyId'] }}">
    @endif
    <div>
      <button type="button" onclick="addQuestion()">質問を追加</button><br>
    </div>
    <div id="questions-container">
      <!-- ここに質問が追加されます -->
    </div>

    <div class="content">
      <label for="use_free_comment">フリーコメント機能の使用</label>
      <select name="use_free_comment" id="use_free_comment" onchange="toggleFreeCommentForm()">
        <option value="0">使用しない</option>
        <option value="1">使用する</option>
      </select>
      <!-- フリーコメントの質問記入フォーム -->
        <div id="free-comment-form" style="display: none;">
          <label for="free_comment">例：花粉症について思うこと。</label>
          <input type="text" name="survey[free_comment]" id="free_comment">
        </div>
    </div>

    <button type="submit">作成</button>

  </form>
</div>
</div>