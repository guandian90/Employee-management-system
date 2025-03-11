<!DOCTYPE html>
<html>
<head>
    <title>登录</title>
</head>
<body>
    <h2>登录</h2>
    <form method="POST" action="{{ route('login') }}">
        @csrf
        <div>
            <label>Email:</label>
            <input type="email" name="email" required>
        </div>
        <div>
            <label>密码:</label>
            <input type="password" name="password" required>
        </div>
        <button type="submit">登录</button>
    </form>
    @if ($errors->any())
        <div>登录失败，请检查输入</div>
    @endif
    <div>
        <a href="{{ route('register') }}">注册</a>
    </div>
</body>
</html>
