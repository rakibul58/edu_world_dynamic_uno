import axios from "axios";

export default {
    namespaced: true,

    state: {
        loading: false,
        heroSlides: [],
        programs: [],
        features: [],
        testimonials: [],
        galleryItems: [],
        newsEvents: [],
        settings: {},
    },

    mutations: {
        SET_LOADING(state, loading) {
            state.loading = loading;
        },

        SET_HERO_SLIDES(state, slides) {
            state.heroSlides = slides;
        },

        SET_PROGRAMS(state, programs) {
            state.programs = programs;
        },

        SET_FEATURES(state, features) {
            state.features = features;
        },

        SET_TESTIMONIALS(state, testimonials) {
            state.testimonials = testimonials;
        },

        SET_GALLERY_ITEMS(state, items) {
            state.galleryItems = items;
        },

        SET_NEWS_EVENTS(state, newsEvents) {
            state.newsEvents = newsEvents;
        },

        SET_SETTINGS(state, settings) {
            state.settings = settings;
        },

        ADD_ITEM(state, { type, item }) {
            state[type].push(item);
        },

        UPDATE_ITEM(state, { type, item }) {
            const index = state[type].findIndex((i) => i.id === item.id);
            if (index !== -1) {
                state[type].splice(index, 1, item);
            }
        },

        REMOVE_ITEM(state, { type, id }) {
            const index = state[type].findIndex((i) => i.id === id);
            if (index !== -1) {
                state[type].splice(index, 1);
            }
        },
    },

    actions: {
        // Hero Slides
        async fetchHeroSlides({ commit }) {
            try {
                commit("SET_LOADING", true);
                const response = await axios.get("/admin/hero-slides");
                commit("SET_HERO_SLIDES", response.data);
            } catch (error) {
                console.error("Error fetching hero slides:", error);
            } finally {
                commit("SET_LOADING", false);
            }
        },

        async createHeroSlide({ commit }, slide) {
            try {
                const response = await axios.post("/admin/hero-slides", slide);
                commit("ADD_ITEM", { type: "heroSlides", item: response.data });
                return { success: true };
            } catch (error) {
                return {
                    success: false,
                    message: error.response?.data?.message,
                };
            }
        },

        async updateHeroSlide({ commit }, slide) {
            try {
                const response = await axios.put(
                    `/admin/hero-slides/${slide.id}`,
                    slide
                );
                commit("UPDATE_ITEM", {
                    type: "heroSlides",
                    item: response.data,
                });
                return { success: true };
            } catch (error) {
                return {
                    success: false,
                    message: error.response?.data?.message,
                };
            }
        },

        async deleteHeroSlide({ commit }, id) {
            try {
                await axios.delete(`/admin/hero-slides/${id}`);
                commit("REMOVE_ITEM", { type: "heroSlides", id });
                return { success: true };
            } catch (error) {
                return {
                    success: false,
                    message: error.response?.data?.message,
                };
            }
        },

        // Programs
        async fetchPrograms({ commit }) {
            try {
                const response = await axios.get("/admin/programs");
                commit("SET_PROGRAMS", response.data);
            } catch (error) {
                console.error("Error fetching programs:", error);
            }
        },

        async createProgram({ commit }, program) {
            try {
                const response = await axios.post("/admin/programs", program);
                commit("ADD_ITEM", { type: "programs", item: response.data });
                return { success: true };
            } catch (error) {
                return {
                    success: false,
                    message: error.response?.data?.message,
                };
            }
        },

        async updateProgram({ commit }, program) {
            try {
                const response = await axios.put(
                    `/admin/programs/${program.id}`,
                    program
                );
                commit("UPDATE_ITEM", {
                    type: "programs",
                    item: response.data,
                });
                return { success: true };
            } catch (error) {
                return {
                    success: false,
                    message: error.response?.data?.message,
                };
            }
        },

        async deleteProgram({ commit }, id) {
            try {
                await axios.delete(`/admin/programs/${id}`);
                commit("REMOVE_ITEM", { type: "programs", id });
                return { success: true };
            } catch (error) {
                return {
                    success: false,
                    message: error.response?.data?.message,
                };
            }
        },

        // Settings
        async fetchSettings({ commit }) {
            try {
                const response = await axios.get("/admin/settings");
                commit("SET_SETTINGS", response.data);
            } catch (error) {
                console.error("Error fetching settings:", error);
            }
        },

        async updateSettings({ commit }, { settings, types, groups }) {
            try {
                await axios.post("/admin/settings", {
                    settings,
                    types,
                    groups,
                });
                commit("SET_SETTINGS", settings);
                return { success: true };
            } catch (error) {
                return {
                    success: false,
                    message: error.response?.data?.message,
                };
            }
        },

        // File Upload
        async uploadFile(_, { file, type }) {
            try {
                const formData = new FormData();
                formData.append("file", file);
                formData.append("type", type);

                const response = await axios.post("/admin/upload", formData, {
                    headers: {
                        "Content-Type": "multipart/form-data",
                    },
                });

                return response.data;
            } catch (error) {
                throw error;
            }
        },
    },
};
