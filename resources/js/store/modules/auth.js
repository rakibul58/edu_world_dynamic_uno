import axios from 'axios';

export default {
    namespaced: true,

    state: {
        user: null,
        token: localStorage.getItem("auth_token"),
        loading: false,
    },

    mutations: {
        SET_USER(state, user) {
            state.user = user;
        },

        SET_TOKEN(state, token) {
            state.token = token;
            if (token) {
                localStorage.setItem("auth_token", token);
            } else {
                localStorage.removeItem("auth_token");
            }
        },

        SET_LOADING(state, loading) {
            state.loading = loading;
        },
    },

    actions: {
        async login({ commit }, credentials) {
            try {
                commit("SET_LOADING", true);
                const response = await axios.post("/login", credentials);

                if (response.data.success) {
                    const { user, token } = response.data;
                    commit("SET_USER", user);
                    commit("SET_TOKEN", token);
                    return { success: true };
                } else {
                    return { success: false, message: response.data.message };
                }
            } catch (error) {
                return {
                    success: false,
                    message: error.response?.data?.message || "Login failed",
                };
            } finally {
                commit("SET_LOADING", false);
            }
        },

        async logout({ commit }) {
            try {
                await axios.post("/logout");
            } catch (error) {
                // Handle logout error if needed
            }

            commit("SET_USER", null);
            commit("SET_TOKEN", null);
        },

        async getUser({ commit }) {
            try {
                const response = await axios.get("/user");
                commit("SET_USER", response.data);
                return response.data;
            } catch (error) {
                commit("SET_USER", null);
                commit("SET_TOKEN", null);
                throw error;
            }
        },
    },

    getters: {
        isAuthenticated: (state) => !!state.token,
        user: (state) => state.user,
    },
};
