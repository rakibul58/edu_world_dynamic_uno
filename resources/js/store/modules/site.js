import axios from "axios";

export default {
    namespaced: true,

    state: {
        loading: false,
        settings: {},
        heroSlides: [],
        programs: [],
        features: [],
        testimonials: [],
        gallery: [],
        facilities: {
            large: null,
            items: [],
        },
        news: [],
        events: [],
        contact: [],
        academicLevels: [],
    },

    mutations: {
        SET_LOADING(state, loading) {
            state.loading = loading;
        },

        SET_SITE_DATA(state, data) {
            state.settings = data.settings;
            state.heroSlides = data.heroSlides;
            state.programs = data.programs;
            state.features = data.features;
            state.testimonials = data.testimonials;
            state.gallery = data.gallery;
            state.facilities = data.facilities;
            state.news = data.news;
            state.events = data.events;
            state.contact = data.contact;
            state.academicLevels = data.academicLevels;
        },

        SET_SETTINGS(state, settings) {
            state.settings = settings;
        },
    },

    actions: {
        async loadSiteData({ commit }) {
            try {
                commit("SET_LOADING", true);
                const response = await axios.get("/site-data");
                console.log({ response });
                commit("SET_SITE_DATA", response.data);
            } catch (error) {
                console.error("Error loading site data:", error);
            } finally {
                commit("SET_LOADING", false);
            }
        },

        async loadSettings({ commit }) {
            try {
                const response = await axios.get("/settings");
                commit("SET_SETTINGS", response.data);
            } catch (error) {
                console.error("Error loading settings:", error);
            }
        },
    },
};
