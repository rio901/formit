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
<div>
<table>
    <thead>
        <tr>
          <th>タイトル</th>
          <th>回答順番</th>
          <th>回答数</th>
        </tr>
    </thead>
    <tbody>
    @foreach($answers as $answer)
        <tr>
        <td>{{ $answer }}</td>

        </tr>
    @endforeach
    </tbody>