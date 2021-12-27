<template>
    <div class="container-fluid">
        <div class="d-flex flex-column min-vh-100 justify-content-center align-items-center" v-if="!showFixtures">
            <transition name="fade">
                <div class="col-md-4 mx-auto" v-if="!showCountDown">
                    <h3>Tournament Teams</h3>
                    <div class="card justify-content-center">
                        <div class="card-header bg-dark text-white">
                            Team Name
                        </div>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item" v-for="club in clubs">{{ club.name }}</li>
                        </ul>
                    </div>
                    <transition name="fade">
                        <a v-on:click="pullFixture" v-if="showPullFixtureBtn" class="btn btn-success col-3 mt-3">
                            Generate Fixtures
                        </a>
                    </transition>
                </div>
            </transition>
            <transition name="fade">
                <div class="col-md-8 mx-auto" v-if="showCountDown">
                    <div class="justify-content-center">
                        <h1 class="text-center" style="font-size: 15rem">
                            {{ countDownTimer }}
                        </h1>
                    </div>
                </div>
            </transition>
        </div>
        <transition name="fadeInLeft">
            <div class="row" v-if="showFixtures">
                <div class="col-12">
                    <h1 class="text-center mt-5">Generated Fixtures</h1>
                </div>
                <div class="col-3 mt-4" v-for="(week, index) in fixtures">
                    <div class="card justify-content-center">
                        <div class="card-header bg-dark text-white">
                            Week {{ index }}
                        </div>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item " v-for="match in week">
                                <div class="row justify-content-center align-items-center ">
                                    <div class="col">
                                        <p style="margin-left: auto; margin-right: auto; width:8em; margin-bottom: 0">
                                            {{ match.home_club.name }}
                                        </p>
                                    </div>
                                    <div class="col-2 d-flex justify-content-center text-start">-</div>
                                    <div class="col d-flex  justify-content-center">
                                        <p style="margin-left: auto; margin-right: auto; width:8em; margin-bottom: 0 ">
                                            {{ match.away_club.name }}
                                        </p>
                                    </div>
                                </div>
                                <!--                                    <span class="float-start">{{ match.home_club.name }}</span>-->
                                <!--                                    <span class="mx-auto">-</span>-->
                                <!--                                    <span class="float-end">{{ match.away_club.name }}</span>-->
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="col-12 mt-5">
                    <router-link :to="{ name: 'Simulation' }" class="btn btn-info">
                        Start Simulation
                    </router-link>
                </div>
            </div>
        </transition>
    </div>
</template>
<script>
export default {
    data()
    {
        return {
            clubs: [],
            fixtures: [],
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

            this.$store.dispatch('getFixtures');
            this.fixtures = this.$store.state.fixtures;
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
                    this.showFixtures = !this.showFixtures;
                    this.countDownStopInterval(interval);
                }
            }, 1000)
        },
        countDownStopInterval: function (interval)
        {
            clearInterval(interval);
        },
        getClubs: function ()
        {
            this.$store.dispatch('getClubs');
            this.clubs = this.$store.getters.getClubs;
        }
    },
    beforeMount()
    {
        this.getClubs();
    },
}
</script>
