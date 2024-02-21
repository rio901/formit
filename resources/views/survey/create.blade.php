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
<form method="POST" action="{{ route('store_survey') }}">
    @csrf
    <input type="hidden" name="user_id" value="1">
    <div>
      <div>
        <label>タイトル</label>
      </div>
      <div>
        <input type="text" name="name" value="{{old('title')}}">
      </div>
      @error('title')
      <span>{{ $message }}</span>
      @enderror
    </div>

    <div>
      <div>
        <label>招待コード</label>
      </div>
      <div>
        <input type="text" name="invite_code" value="{{old('invite_code')}}">
      </div>
      @error('invite_code')
      <span>{{ $message }}</span>
      @enderror
    </div>

    <button type="submit">作成</button>

  </form>
</div>