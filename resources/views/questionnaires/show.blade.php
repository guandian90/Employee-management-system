<!DOCTYPE html>
<html>
<head>
    <title>问卷 - {{ $questionnaire->title }}</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
<h1>{{ $questionnaire->title }}</h1>

<!-- 在表单顶部显示全局错误 -->
@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

@if ($isCompleted)
    <div class="alert alert-success">
        <p>您已经完成了本步骤的问卷。</p>
    </div>
@else
<form id="questionnaireForm" method="POST" action="{{ route('questionnaire.submit', $step->id) }}">
    @csrf
    @foreach ($questionnaire->questions as $question)
        <div class="question">
            <h3>{{ $loop->iteration }}. {{ $question->question_text }}</h3>
            @if ($errors->has("answers[{$question->id}]"))
                <div class="alert alert-danger">
                    {{ $errors->first("answers[{$question->id}]") }}
                </div>
            @endif
            @if ($question->type === 'single_choice')
                @foreach ($question->options as $option)
                    <div>
                        <input type="radio"
                               name="answers[{{ $question->id }}]"
                               value="{{ $option }}"
                               required>
                        <label>{{ $option }}</label>
                    </div>
                @endforeach
            @elseif ($question->type === 'multiple_choice')
                @foreach ($question->options as $option)
                    <div>
                        <input type="checkbox"
                               name="answers[{{ $question->id }}][]"
                               value="{{ $option }}"/>
                        <label>{{ $option }}</label>
                    </div>
                @endforeach
            @elseif ($question->type === 'short_answer')
                <textarea
                    name="answers[{{ $question->id }}]"
                    rows="4"
                    required></textarea>
            @endif
        </div>
    @endforeach
    <button type="button" id="wj_submit">提交问卷</button>
{{--    <button type="submit">提交问卷</button>--}}
</form>
@endif
<script>
    $(document).ready(function() {
        $('#wj_submit').click(function() {
            let isValid = true;
            // 检查所有必填字段是否已填写
            $('form#questionnaireForm input[required], form#questionnaireForm textarea[required]').each(function() {
                if (!$(this).val()) {
                    isValid = false;
                    alert('请填写完所有题目');
                    return false;
                }
            });
            if (!isValid) {
                return; // 停止进一步的验证
            }

            //检测多选是否勾选了至少2个选项
            $('form#questionnaireForm .question').each(function() {
                const questionType = $(this).find('input[type="radio"]').length ? 'single_choice' :
                    $(this).find('input[type="checkbox"]').length ? 'multiple_choice' :
                        'short_answer';

                if (questionType === 'multiple_choice') {
                    const selectedOptions = $(this).find('input[type="checkbox"]:checked').length;
                    if (selectedOptions < 2) {
                        isValid = false;
                        alert('多选题至少需要勾选两个选项');
                    }
                }
            });

            if (!isValid) {
                alert(errorMessages.join('\n'));
            } else {
                $('form#questionnaireForm').submit();
            }
        });
    });
</script>
</body>
</html>
