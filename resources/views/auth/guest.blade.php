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
<x-guest-layout>
  <div class="container_index">
  <div class="content">
    <h3>フォームを検索</h3>
    <form method="GET" action="{{ route('survey.search') }}">
      @csrf
      <input type="text" name="invite_code" placeholder="招待コードを入力...">
      <button type="submit">検索</button>
    </form>
  </div>
    <a class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800" href="{{ route('login') }}">
        {{ __('登録済みの方はこちら') }}
    </a>
</div>
</div>
</x-guest-layout>