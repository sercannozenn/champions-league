<template>
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <h2 class="text-center mt-3 mb-4">Simulation</h2>
            </div>
            <div class="col-6">
                <table class="table">
                    <thead class="table-dark">
                    <tr>
                        <th scope="col" colspan="4">Team Name</th>
                        <th scope="col">P</th>
                        <th scope="col">W</th>
                        <th scope="col">D</th>
                        <th scope="col">L</th>
                        <th scope="col">GD</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="row in this.$store.getters.getTable">
                        <th scope="row" colspan="4">{{ row.name }}</th>
                        <td>{{ row.p }}</td>
                        <td>{{ row.w }}</td>
                        <td>{{ row.d }}</td>
                        <td>{{ row.l }}</td>
                        <td>{{ row.gd }}</td>
                    </tr>
                    </tbody>
                </table>
            </div>
            <div class="col-3">
                <table class="table">
                    <thead class="table-dark">
                    <tr>
                        <th scope="col" colspan="3">Week {{
                                this.$store.getters.getThisWeekFixture.length ? this.$store.getters.getThisWeekFixture[0].week_number : ''
                            }}
                        </th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="week in this.$store.getters.getThisWeekFixture">
                        <td>{{ week.home_club.name }}</td>
                        <td>-</td>
                        <td>{{ week.away_club.name }}</td>
                    </tr>
                    </tbody>
                </table>
            </div>
            <div class="col-3">
                <table class="table">
                    <thead class="table-dark">
                    <tr>
                        <th scope="col" colspan="3">Championship Predictions</th>
                        <th scope="col">%</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <th scope="row" colspan="3">Liverpool</th>
                        <td>0</td>
                    </tr>
                    <tr>
                        <th scope="row" colspan="3">Manchester City</th>
                        <td>0</td>
                    </tr>
                    <tr>
                        <th scope="row" colspan="3">Arsenal</th>
                        <td>0</td>
                    </tr>
                    <tr>
                        <th scope="row" colspan="3">Chelsea</th>
                        <td>0</td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="row">
            <div class="col-6 text-center">
                <a v-on:click="playAllWeek" class="btn btn-info">Play All Weeks</a>
            </div>
            <div class="col-3">
                <a v-on:click="playNextWeek" class="btn btn-info">Play Next Week</a>
            </div>
            <div class="col-3">
                <a v-on:click="resetData" class="btn btn-danger">Reset Data</a>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    data()
    {
        return {
            lastPlayedWeek: [],
            championsPredictionsResult: [],
        }
    },
    methods: {
        getThisWeekFixture()
        {
            this.$store.dispatch('getThisWeekFixture');
        },
        getTable()
        {
            this.$store.dispatch('getTable');
        },
        playNextWeek()
        {
            this.$store.dispatch('playNextWeek');
        },
        playAllWeek()
        {
            this.$store.dispatch('playAllWeek');
        },
        resetData()
        {
            this.$store.dispatch('resetData');
        },
        championsPredictions()
        {
            this.$store.dispatch('championsPredictions');
        }
    },
    mounted()
    {
        this.getThisWeekFixture();
        this.getTable();
    },

    watch: {
        '$store.state.resetData': function ()
        {
            this.getTable();
            this.getThisWeekFixture();
        },
        '$store.state.lastPlayedWeek': function ()
        {
            this.getTable();
            this.getThisWeekFixture();
        },
        '$store.state.thisWeekFixture': function ()
        {
            if (this.$store.getters.getThisWeekFixture[0].week_number > 4)
            {
                this.championsPredictions();
            }
        },
        '$store.state.championsPredictions': function ()
        {
            if (this.$store.getters.getThisWeekFixture[0].week_number > 4)
            {
                this.championsPredictionsResult = this.$store.getters.getChampionsPredictions;
            }
        }
    }
}
</script>
