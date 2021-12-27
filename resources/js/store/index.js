import Vue from 'vue'
import Vuex from 'vuex'
import axios from "axios";
import createPersistedState from "vuex-persistedstate";

Vue.use(Vuex)

export const store = new Vuex.Store({
    state: {
        fixtures: [],
        clubs: [],
        thisWeekFixture: [],
        table: [],
        lastPlayedWeek: [],
        resetData: 0,
        championsPredictions: [],
    },
    mutations: {
        setClubs(state, clubs)
        {
            state.clubs = clubs;
        },
        setFixture(state, fixtures)
        {
            state.fixtures = fixtures;
        },
        setThisWeekFixture(state, thisWeekFixture)
        {
            state.thisWeekFixture = thisWeekFixture;
        },
        setTable(state, table)
        {
            state.table = table;
        },
        setLastPlayedWeek(state, lastPlayedWeek)
        {
            state.lastPlayedWeek.push({[lastPlayedWeek[0].week_number] : lastPlayedWeek});
        },
        setAllPlayedWeek(state, allPlayedWeek)
        {
            state.lastPlayedWeek.push(allPlayedWeek);
        },
        setResetData(state)
        {
            state.resetData += 1 ;
            state.lastPlayedWeek = [];
        },
        setChampionsPredictions(state, championsPredictions)
        {
            state.championsPredictions = championsPredictions ;
        },

    },
    actions: {
        getClubs({commit})
        {
            axios.get('api/clubs')
                 .then(response =>
                 {
                     commit('setClubs', response.data);
                 })
        },
        getFixtures({commit})
        {
            axios.get('api/pull-fixture')
                 .then(response =>
                 {
                     commit('setFixture', response.data);
                 })
        },
        playNextWeek({commit})
        {
            axios.get('api/play-next-week')
                 .then(response =>
                 {
                     commit('setLastPlayedWeek', response.data);
                 })
        },
        playAllWeek({commit})
        {
            axios.get('api/play-all-week')
                 .then(response =>
                 {
                     commit('setAllPlayedWeek', response.data);
                 })
        },
        getThisWeekFixture({commit})
        {
            axios.get('api/this-week-fixture')
                 .then(response =>
                 {
                     commit('setThisWeekFixture', response.data);
                 })
        },
        getTable: async function ({commit})
        {
            await axios.get('api/get-table')
                       .then(response =>
                       {
                           commit('setTable', response.data);
                       })
        },
        resetData: async function ({commit})
        {
            await axios.get('api/reset-data')
                       .then(response =>
                       {
                           commit('setResetData');
                       });
        },
        championsPredictions: async function ({commit})
        {
            await axios.get('api/champions-predictions')
                       .then(response =>
                       {
                           commit('setChampionsPredictions', response.data);
                       })
        },
    },
    getters: {
        getClubs(state)
        {
            return state.clubs;
        },
        getFixtures(state)
        {
            return state.fixtures;
        },
        getThisWeekFixture(state)
        {
            return state.thisWeekFixture;
        },
        getTable(state)
        {
            return state.table;
        },
        getLastPlayedWeek(state)
        {
            return state.lastPlayedWeek;
        },
        getChampionsPredictions(state)
        {
            return state.championsPredictions;
        },

    },
    modules: {},
    plugins: [createPersistedState()]
});

