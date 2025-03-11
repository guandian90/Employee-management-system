<!DOCTYPE html>
<html>
<head>
    <title>注册</title>
</head>
<body>
    <h2>注册</h2>
    <form method="POST" action="{{ route('register') }}">
        @csrf
        <div>
            <label>姓名:</label>
            <input type="text" name="name" value="{{ old('name') }}" required>
            @error('name')
                <div>{{ $message }}</div>
            @enderror
        </div>
        <div>
            <label>Email:</label>
            <input type="email" name="email" value="{{ old('email') }}" required>
            @error('email')
                <div>{{ $message }}</div>
            @enderror
        </div>
        <div>
            <label>密码:</label>
            <input type="password" name="password" required>
            @error('password')
                <div>{{ $message }}</div>
            @enderror
        </div>
        <div>
            <label>确认密码:</label>
            <input type="password" name="password_confirmation" required>
        </div>
        <button type="submit">注册</button>
    </form>
    @if ($errors->any())
        <div>注册失败，请检查输入</div>
    @endif
</body>
</html>
