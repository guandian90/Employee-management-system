<!DOCTYPE html>
<html>
<head>
    <title>当前步骤 - 入职流程</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

</head>
<body>
<h1>{{ $currentStep->title }}</h1>
<p>{{ $currentStep->description }}</p>
@php($stepId = $currentStep->id)

@switch($currentStep->resource_type)
    @case('video')
        <video id="stepVideo" src="{{ $currentStep->resource_path }}" controls></video>
        @break

    @case('document')
        <iframe src="{{ $currentStep->resource_path }}" style="width: 100%; height: 600px;"></iframe>
        @break
    @case('image')
        <img src="{{ $currentStep->resource_path }}" alt="步骤图片" style="max-width: 100%;">
        @break

    @default
        <p>未支持的资源类型</p>
@endswitch
@if ($currentStep->questionnaire)
    <br><br><br>
    <a href="{{ route('step.show', $currentStep->id) }}">进入问卷</a>
@endif

<!-- 新增下一步按钮 -->
<br/><br/><br/>
<button id="nextButton" disabled>下一步</button>
<br/><br/><br/>

<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
    @csrf
</form>
<a href="#" onclick="document.getElementById('logout-form').submit();">退出以继续其他角色</a>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const video = document.getElementById('stepVideo');
        const stepId = {{ $currentStep->id }};
        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

        // 监听视频元数据加载完成事件
        video.addEventListener('loadedmetadata', async () => {
            try {
                // 获取进度
                const res = await fetch(`/progress/${stepId}`, {
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                    },
                    credentials: 'include',
                });
                const data = await res.json();

                // 设置视频进度（使用正确的字段名）
                if (data.video_progress_percent) {
                    const percent = parseFloat(data.video_progress_percent);
                    const duration = video.duration;
                    console.log('设置进度:', percent, '%', '总时长:', duration, '秒');
                    if (duration > 0 && !isNaN(duration)) {
                        const time = (percent / 100) * duration;
                        video.currentTime = time;
                        console.log('设置 currentTime:', time);
                    }
                }

            } catch (error) {
                console.error('获取进度失败:', error);
            }
        });

        // 定时上报进度（每3秒一次）
        let timer;
        const sendProgress = () => {
            const duration = video.duration;
            if (duration <= 0 || isNaN(duration)) return;

            const percent = (video.currentTime / duration) * 100;
            if (!isNaN(percent)) {
                fetch('/video-progress', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                    },
                    credentials: 'include',
                    body: JSON.stringify({
                        step_id: stepId,
                        progress_percent: Math.round(percent)
                    })
                });
            }
        };

        video.addEventListener('play', () => {
            timer = setInterval(sendProgress, 3000); // 每3秒上报一次
        });

        video.addEventListener('pause', () => {
            clearInterval(timer);
            sendProgress(); // 暂停时立即上报当前进度
        });
    });
</script>

</body>
</html>
