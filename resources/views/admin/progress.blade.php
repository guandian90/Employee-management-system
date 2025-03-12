<!DOCTYPE html>
<html>
<head>
    <title>员工进度</title>
</head>
<body>
    <h1>员工进度</h1>
    <table border="1">
        <thead>
            <tr>
                <th>员工ID</th>
                <th>员工姓名</th>
                <th>当前步骤</th>
                <th>视频进度</th>
                <th>角色</th>
                <th>操作</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($progress as $userProgress)
                <tr>
                    <td>{{ $userProgress['user_id'] }}</td>
                    <td>{{ $userProgress['name'] }}</td>
                    <td>
                        @php
                            $lastStep = $userProgress['progress']->last();
                            $stepTitle = $lastStep ? $lastStep['step']['title'] : '无';
                        @endphp
                        {{ $stepTitle }}
                    </td>
                    <td>
                        @php
                            $progressPercent = $lastStep ? $lastStep['progress_percent'] : '0%';
                        @endphp
                        {{ $progressPercent }}
                    </td>
                    <td>{{ $userProgress['role'] }}</td>
                    <td>
                        <form action="{{ route('admin.updateRole', $userProgress['user_id']) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <select name="role" onchange="this.form.submit()">
                                <option value="user" {{ $userProgress['role'] == 'user' ? 'selected' : '' }}>用户</option>
                                <option value="admin" {{ $userProgress['role'] == 'admin' ? 'selected' : '' }}>管理员</option>
                            </select>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
        @csrf
    </form>
    <a href="#" onclick="document.getElementById('logout-form').submit();">退出以继续其他角色</a>
</body>
</html>
