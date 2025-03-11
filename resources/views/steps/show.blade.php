<div id="app">
    <div v-if="currentStep">
        <!-- 资源展示 -->
        <div v-if="currentStep.resource_type === 'video'">
{{--            <video--}}
{{--                :src="currentStep.resource_path"--}}
{{--                @timeupdate="updateProgress"--}}
{{--                ref="videoPlayer"--}}
{{--            ></video>--}}
            <video
                id="my-video"
                class="video-js vjs-default-skin"
                controls
                preload="auto"
                :src="currentStep.resource_path"
                @timeupdate="updateProgress"
            >
            </video>
        </div>
        <div v-else>
            <a :href="currentStep.resource_path">文档下载</a>
        </div>

        <!-- 问卷表单 -->
        <questionnaire-form
            :questions="currentStep.questionnaire.questions"
            @submitted="handleQuestionnaireSubmit"
        ></questionnaire-form>
    </div>
</div>

<script src="/vendor/video.js"></script>
<script>   var player = videojs('my-video');
</script>
<script>
new Vue({
    el: '#app',
    data() {
        return {
            currentStep: null
        };
    },
    mounted() {
        this.fetchCurrentStep();
    },
    methods: {
        async fetchCurrentStep() {
            const res = await axios.get('/api/steps/current');
            this.currentStep = res.data;
        },
        async handleQuestionnaireSubmit(answers) {
            try {
                await axios.post(
                    `/api/questionnaires/${this.currentStep.questionnaire.id}/submit`,
                    { answers }
                );
                alert('提交成功，跳转下一步');
                window.location.href = '/steps/next';
            } catch (e) {
                console.error(e);
            }
        },
        updateProgress() {
            const progress = (this.$refs.videoPlayer.currentTime / this.$refs.videoPlayer.duration) * 100;
            axios.post('/api/video-progress', {
                step_id: this.currentStep.id,
                progress_percent: progress
            });
        }
    }
});
</script>
