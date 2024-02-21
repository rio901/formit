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
    <dic class="wrap">
        <h1>ログイン</h1>
        <form action method="post">
            @csrf <!-- CSRFトークンを追加 -->
            <dl>
                <dd><input type="email" name="email" placeholder="メールアドレス"></dd>
                <dd><input type="password" name="password" placeholder="パスワード"></dd>
                <dd><button type="submit" name="signin-submit" class="button submit">送 信</button></dd>
            </dl>
            <dl class="signin_sns">
                <!-- 以下、SNSボタン -->
                <dd><button name="twitter"><img src="{{ asset('img/twitter.png') }}" alt=""></button></dd>
                <dd><button name="facebook"><img src="{{ asset('img/fb.png') }}" alt=""></button></dd>
                <dd><button name="google"><img src="{{ asset('img/google.png') }}" alt=""></button></dd>
                <dd><button name="apple"><img src="{{ asset('img/apple.png') }}" alt=""></button></dd>
            </dl>
        </form>
    </div>
    <a href="{{ route('guest.login') }}">ゲストログイン</a>
</div>