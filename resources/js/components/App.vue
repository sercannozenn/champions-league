<template>
    <div id="app">
        <slot name="content"></slot>

        <router-view></router-view>
    </div>
</template>

<script>
/**
 export default {
    data()
    {
        return {
            clubs: [],
            fixture: [],
            showPullFixtureBtn: true,
            countDownTimer: 1,
            showCountDown: false,
            showFixtures: false,
        }
    },
    methods: {
        pullFixture: function (event)
        {
            this.showPullFixtureBtn = !this.showPullFixtureBtn;
            this.showCountDown = !this.showCountDown;
            this.countDownStartInterval();

            axios.get('api/pull-fixture')
                 .then(response =>
                 {
                     this.fixture = response.data
                     this.$store.dispatch("setFixture", response.data);
                 })
        },
        countDownStartInterval: function ()
        {
            let interval = setInterval(() =>
            {
                if (this.countDownTimer > 0)
                {
                    this.countDownTimer--;
                }
                else
                {
                    this.showFixtures = !this.showFixtures
                    this.countDownStopInterval(interval);
                }
            }, 1000)
        },
        countDownStopInterval: function (interval)
        {
            clearInterval(interval)
        },
        getClubs: function ()
        {
            axios.get('api/clubs')
                 .then(response =>
                 {
                     this.clubs = response.data;
                 })
        }
    },
    beforeMount()
    {
        this.getClubs();
    },
}*/
</script>
