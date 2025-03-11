<div id="app">
    <div v-if="currentStep">
        <div class="training-complete-message">
            <h1>培训已完成</h1>
            <p>恭喜您完成了本培训步骤！</p>
            <p>您可以继续进行下一步操作。</p>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
            <a href="#" onclick="document.getElementById('logout-form').submit();">退出以继续其他角色</a>
        </div>
    </div>
</div>

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
        }
    }
});
</script>

<style>
.training-complete-message {
    text-align: center;
    margin-top: 50px;
}

.training-complete-message h1 {
    font-size: 2em;
    color: #4CAF50;
}

.training-complete-message p {
    font-size: 1.2em;
    color: #333;
}

.training-complete-message a {
    display: inline-block;
    margin-top: 20px;
    padding: 10px 20px;
    background-color: #4CAF50;
    color: white;
    text-decoration: none;
    border-radius: 5px;
}

.training-complete-message a:hover {
    background-color: #45a049;
}
</style>
