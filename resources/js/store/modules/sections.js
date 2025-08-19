import axios from "axios";

export default {
    namespaced: true,

    state: {
        sections: [],
        loading: false,
        error: null,
        lastUpdated: null,
    },

    mutations: {
        SET_LOADING(state, loading) {
            state.loading = loading;
        },

        SET_SECTIONS(state, sections) {
            state.sections = sections;
            state.lastUpdated = new Date().toISOString();
        },

        SET_ERROR(state, error) {
            state.error = error;
        },

        UPDATE_SECTION(state, updatedSection) {
            const index = state.sections.findIndex(
                (s) => s.id === updatedSection.id
            );
            if (index !== -1) {
                state.sections.splice(index, 1, updatedSection);
            }
        },

        TOGGLE_SECTION_VISIBILITY(state, { sectionId, isVisible }) {
            const section = state.sections.find((s) => s.id === sectionId);
            if (section) {
                section.is_visible = isVisible;
            }
        },

        UPDATE_SECTIONS_ORDER(state, sections) {
            state.sections = sections;
        },

        CLEAR_ERROR(state) {
            state.error = null;
        },
    },

    actions: {
        async fetchSections({ commit }, { page = "home", admin = false } = {}) {
            try {
                commit("SET_LOADING", true);
                commit("CLEAR_ERROR");

                const endpoint = admin
                    ? "/admin/page-sections"
                    : "/page-sections/visible";

                const response = await axios.get(endpoint, {
                    params: { page },
                });

                if (response.data.success) {
                    commit("SET_SECTIONS", response.data.data);
                    return { success: true, data: response.data.data };
                } else {
                    throw new Error(
                        response.data.message || "Failed to fetch sections"
                    );
                }
            } catch (error) {
                const errorMessage =
                    error.response?.data?.message ||
                    error.message ||
                    "Failed to load sections";
                commit("SET_ERROR", errorMessage);
                return { success: false, message: errorMessage };
            } finally {
                commit("SET_LOADING", false);
            }
        },

        async updateSection({ commit }, sectionData) {
            try {
                const response = await axios.put(
                    `/admin/page-sections/${sectionData.id}`,
                    sectionData
                );

                if (response.data.success) {
                    commit("UPDATE_SECTION", response.data.data);
                    return { success: true, data: response.data.data };
                } else {
                    throw new Error(
                        response.data.message || "Failed to update section"
                    );
                }
            } catch (error) {
                const errorMessage =
                    error.response?.data?.message ||
                    error.message ||
                    "Failed to update section";
                commit("SET_ERROR", errorMessage);
                return { success: false, message: errorMessage };
            }
        },

        async toggleSectionVisibility({ commit }, section) {
            try {
                const response = await axios.patch(
                    `/admin/page-sections/${section.id}/toggle`
                );

                if (response.data.success) {
                    commit("TOGGLE_SECTION_VISIBILITY", {
                        sectionId: section.id,
                        isVisible: response.data.data.is_visible,
                    });
                    return { success: true, data: response.data.data };
                } else {
                    throw new Error(
                        response.data.message ||
                            "Failed to toggle section visibility"
                    );
                }
            } catch (error) {
                const errorMessage =
                    error.response?.data?.message ||
                    error.message ||
                    "Failed to toggle visibility";
                commit("SET_ERROR", errorMessage);
                return { success: false, message: errorMessage };
            }
        },

        async bulkToggleSections(
            { commit, dispatch },
            { sectionIds, isVisible }
        ) {
            try {
                const response = await axios.patch(
                    "/admin/page-sections/bulk-toggle",
                    {
                        section_ids: sectionIds,
                        is_visible: isVisible,
                    }
                );

                if (response.data.success) {
                    // Refresh sections to get updated data
                    await dispatch("fetchSections", { admin: true });
                    return { success: true };
                } else {
                    throw new Error(
                        response.data.message || "Failed to update sections"
                    );
                }
            } catch (error) {
                const errorMessage =
                    error.response?.data?.message ||
                    error.message ||
                    "Failed to update sections";
                commit("SET_ERROR", errorMessage);
                return { success: false, message: errorMessage };
            }
        },

        async updateSectionsOrder({ commit }, sections) {
            try {
                const response = await axios.patch(
                    "/admin/page-sections/update-order",
                    {
                        sections: sections.map((section) => ({
                            id: section.id,
                            sort_order: section.sort_order,
                        })),
                    }
                );

                if (response.data.success) {
                    commit("UPDATE_SECTIONS_ORDER", sections);
                    return { success: true };
                } else {
                    throw new Error(
                        response.data.message ||
                            "Failed to update sections order"
                    );
                }
            } catch (error) {
                const errorMessage =
                    error.response?.data?.message ||
                    error.message ||
                    "Failed to update order";
                commit("SET_ERROR", errorMessage);
                return { success: false, message: errorMessage };
            }
        },

        async resetSections({ commit }) {
            try {
                const response = await axios.post(
                    "/admin/page-sections/reset"
                );

                if (response.data.success) {
                    commit("SET_SECTIONS", response.data.data);
                    return { success: true, data: response.data.data };
                } else {
                    throw new Error(
                        response.data.message || "Failed to reset sections"
                    );
                }
            } catch (error) {
                const errorMessage =
                    error.response?.data?.message ||
                    error.message ||
                    "Failed to reset sections";
                commit("SET_ERROR", errorMessage);
                return { success: false, message: errorMessage };
            }
        },
    },
};
