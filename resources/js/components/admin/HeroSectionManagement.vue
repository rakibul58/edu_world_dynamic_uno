<template>
    <div class="section-manager">
        <!-- Toast Container -->
        <div class="toast-container">
            <div
                v-for="toast in toasts"
                :key="toast.id"
                class="toast"
                :class="'toast-' + toast.type"
                @click="removeToast(toast.id)"
            >
                <div class="toast-icon">
                    <span v-if="toast.type === 'success'">✓</span>
                    <span v-else-if="toast.type === 'error'">✕</span>
                    <span v-else-if="toast.type === 'warning'">⚠</span>
                    <span v-else>ℹ</span>
                </div>
                <div class="toast-content">
                    <div class="toast-title">{{ toast.title }}</div>
                    <div class="toast-message">{{ toast.message }}</div>
                </div>
                <button class="toast-close" @click.stop="removeToast(toast.id)">
                    ×
                </button>
            </div>
        </div>

        <!-- Page Header -->
        <div class="page-header">
            <div class="header-content">
                <div class="header-left">
                    <h1 class="page-title">Hero Section Management</h1>
                    <p class="page-subtitle">
                        Create and customize dynamic hero sections for your
                        website
                    </p>
                </div>
                <div class="header-actions">
                    <button
                        @click="startNewHeroSection"
                        class="btn btn-primary"
                        :disabled="loading"
                    >
                        <svg
                            width="18"
                            height="18"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="2"
                        >
                            <line x1="12" y1="5" x2="12" y2="19" />
                            <line x1="5" y1="12" x2="19" y2="12" />
                        </svg>
                        Create Hero Section
                    </button>
                    <button
                        @click="fetchHeroSections"
                        class="btn btn-outline"
                        :disabled="loading"
                    >
                        <svg
                            width="18"
                            height="18"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="2"
                        >
                            <polyline points="23,4 23,10 17,10" />
                            <path d="M20.49 15a9 9 0 1 1-2.12-9.36L23 10" />
                        </svg>
                        Refresh
                    </button>
                </div>
            </div>
        </div>

        <!-- Hero Editor Modal -->
        <div
            v-if="showEditorModal"
            class="modal-overlay"
            @click.self="closeEditor"
        >
            <div class="modal editor-modal">
                <div class="modal-header">
                    <h2>
                        {{
                            isEditing
                                ? "Edit Hero Section"
                                : "Create Hero Section"
                        }}
                    </h2>
                    <button @click="closeEditor" class="close-btn">
                        &times;
                    </button>
                </div>
                <div class="modal-body">
                    <HeroSectionEditor
                        :heroSection="currentHeroSection"
                        @save="saveHeroSection"
                        @cancel="closeEditor"
                        @error="showToast($event, 'error', 'Error')"
                    />
                </div>
            </div>
        </div>

        <!-- Loading State -->
        <div
            v-if="loading && heroSections.length === 0"
            class="loading-container"
        >
            <div class="loading-spinner"></div>
            <p>Loading hero sections...</p>
        </div>

        <!-- Hero Sections List -->
        <div v-else class="sections-container">
            <div class="sections-header">
                <div class="sections-info">
                    <h2 class="sections-title">Hero Sections</h2>
                    <p class="sections-count">
                        {{ activeHeroSectionsCount }} of
                        {{ heroSections.length }} sections active
                    </p>
                </div>
            </div>

            <!-- List View -->
            <div class="sections-list">
                <div
                    v-for="heroSection in heroSections"
                    :key="`hero-${heroSection.id}-${
                        heroSection.updated_at || heroSection.created_at
                    }`"
                    class="section-item"
                    :class="{ 'section-hidden': !heroSection.is_active }"
                >
                    <!-- Debug info - remove this after testing -->
                    <div
                        style="font-size: 10px; color: #999; margin-bottom: 5px"
                    >
                        ID: {{ heroSection.id }} | Updated:
                        {{ heroSection.updated_at }}
                    </div>

                    <div class="section-content">
                        <div class="section-info">
                            <div class="hero-preview-small">
                                <div
                                    class="preview-hero"
                                    :style="getHeroPreviewStyle(heroSection)"
                                >
                                    <div class="preview-overlay"></div>
                                    <div class="preview-content">
                                        <div class="preview-text">
                                            <h3>
                                                {{
                                                    heroSection.title ||
                                                    "Hero Title"
                                                }}
                                            </h3>
                                            <h4
                                                v-if="
                                                    heroSection.title_highlight
                                                "
                                            >
                                                {{
                                                    heroSection.title_highlight
                                                }}
                                            </h4>
                                            <p>
                                                {{
                                                    heroSection.subtitle ||
                                                    "Hero Subtitle"
                                                }}
                                            </p>
                                            <small>{{
                                                heroSection.tagline ||
                                                "Hero tagline content..."
                                            }}</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <h3 class="section-name">
                                {{
                                    heroSection.section_name ||
                                    `Hero Section #${heroSection.id}`
                                }}
                            </h3>
                            <div class="section-meta">
                                <span class="section-key">
                                    {{
                                        (heroSection.background_images || [])
                                            .length
                                    }}
                                    images
                                </span>
                                <span class="section-buttons">
                                    {{ (heroSection.cta_buttons || []).length }}
                                    buttons
                                </span>
                                <span
                                    v-if="heroSection.enable_slider"
                                    class="section-feature"
                                >
                                    Slider enabled
                                </span>
                            </div>
                        </div>

                        <div class="section-controls">
                            <div class="visibility-toggle">
                                <label class="toggle-switch">
                                    <input
                                        type="checkbox"
                                        :checked="heroSection.is_active"
                                        @change="
                                            toggleHeroSectionActive(heroSection)
                                        "
                                        :disabled="
                                            toggleLoading === heroSection.id
                                        "
                                    />
                                    <span class="toggle-slider"></span>
                                </label>
                                <span class="toggle-label">
                                    {{
                                        heroSection.is_active
                                            ? "Active"
                                            : "Inactive"
                                    }}
                                </span>
                            </div>
                            <div class="action-buttons">
                                <button
                                    @click="editHeroSection(heroSection)"
                                    class="btn-icon"
                                    title="Edit"
                                >
                                    <svg
                                        width="16"
                                        height="16"
                                        viewBox="0 0 24 24"
                                        fill="none"
                                        stroke="currentColor"
                                        stroke-width="2"
                                    >
                                        <path
                                            d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"
                                        />
                                        <path
                                            d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"
                                        />
                                    </svg>
                                </button>
                                <button
                                    @click="duplicateHeroSection(heroSection)"
                                    class="btn-icon"
                                    title="Duplicate"
                                    :disabled="loading"
                                >
                                    <svg
                                        width="16"
                                        height="16"
                                        viewBox="0 0 24 24"
                                        fill="none"
                                        stroke="currentColor"
                                        stroke-width="2"
                                    >
                                        <rect
                                            x="9"
                                            y="9"
                                            width="13"
                                            height="13"
                                            rx="2"
                                            ry="2"
                                        />
                                        <path
                                            d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"
                                        />
                                    </svg>
                                </button>
                                <button
                                    @click="deleteHeroSection(heroSection.id)"
                                    class="btn-icon danger"
                                    title="Delete"
                                    :disabled="
                                        loading ||
                                        (heroSection.is_active &&
                                            activeHeroSectionsCount === 1)
                                    "
                                >
                                    <svg
                                        width="16"
                                        height="16"
                                        viewBox="0 0 24 24"
                                        fill="none"
                                        stroke="currentColor"
                                        stroke-width="2"
                                    >
                                        <polyline points="3,6 5,6 21,6" />
                                        <path
                                            d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"
                                        />
                                        <line x1="10" y1="11" x2="10" y2="17" />
                                        <line x1="14" y1="11" x2="14" y2="17" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Empty State -->
            <div v-if="heroSections.length === 0" class="empty-state">
                <div class="empty-icon">
                    <svg
                        width="48"
                        height="48"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2"
                    >
                        <rect
                            x="3"
                            y="3"
                            width="18"
                            height="18"
                            rx="2"
                            ry="2"
                        />
                        <polyline points="9,9 15,15" />
                        <polyline points="15,9 9,15" />
                    </svg>
                </div>
                <h3>No Hero Sections Created Yet</h3>
                <p>Create your first hero section to get started</p>
                <button @click="startNewHeroSection" class="btn btn-primary">
                    Create Your First Hero Section
                </button>
            </div>
        </div>
    </div>
</template>

<script>
import axios from "axios";
import HeroSectionEditor from "./HeroSectionEditor.vue";

export default {
    name: "HeroSectionManagement",
    components: {
        HeroSectionEditor,
    },
    data() {
        return {
            heroSections: [],
            loading: false,
            toggleLoading: null,
            showEditorModal: false,
            currentHeroSection: null,
            isEditing: false,
            toasts: [],
            toastCounter: 0,
        };
    },
    computed: {
        activeHeroSectionsCount() {
            return this.heroSections.filter((section) => section.is_active)
                .length;
        },
    },
    mounted() {
        this.fetchHeroSections();
    },
    methods: {
        async fetchHeroSections() {
            this.loading = true;
            try {
                const response = await axios.get("/admin/hero-sections");
                if (response.data.success) {
                    // Force reactivity update
                    this.heroSections = [];
                    this.$nextTick(() => {
                        this.heroSections = response.data.data;
                    });
                } else {
                    throw new Error(
                        response.data.message || "Failed to fetch hero sections"
                    );
                }
            } catch (error) {
                console.error("Error fetching hero sections:", error);
                this.showToast(
                    error.response?.data?.message ||
                        "Failed to fetch hero sections",
                    "error",
                    "Error"
                );
            } finally {
                this.loading = false;
            }
        },

        showToast(message, type = "success", title = "") {
            const toast = {
                id: ++this.toastCounter,
                message,
                type,
                title:
                    title ||
                    (type === "success"
                        ? "Success"
                        : type === "error"
                        ? "Error"
                        : "Info"),
            };

            this.toasts.push(toast);

            // Auto remove after 5 seconds
            setTimeout(() => {
                this.removeToast(toast.id);
            }, 5000);
        },

        removeToast(id) {
            const index = this.toasts.findIndex((t) => t.id === id);
            if (index > -1) {
                this.toasts.splice(index, 1);
            }
        },

        async toggleHeroSectionActive(heroSection) {
            if (this.toggleLoading === heroSection.id) return;

            try {
                this.toggleLoading = heroSection.id;

                const response = await axios.patch(
                    `/admin/hero-sections/${heroSection.id}/set-active`
                );

                if (response.data.success) {
                    this.showToast(
                        "Hero section status updated successfully",
                        "success"
                    );

                    // Force reactivity by recreating the array
                    const updatedSection = response.data.data;
                    const newSections = [...this.heroSections];
                    const index = newSections.findIndex(
                        (s) => s.id === updatedSection.id
                    );

                    if (index !== -1) {
                        // Update the specific section
                        newSections[index] = { ...updatedSection };

                        // If this section was activated, deactivate others
                        if (updatedSection.is_active) {
                            newSections.forEach((section, idx) => {
                                if (idx !== index) {
                                    section.is_active = false;
                                }
                            });
                        }

                        // Replace the entire array to trigger reactivity
                        this.heroSections = newSections;
                    }
                } else {
                    throw new Error(
                        response.data.message || "Failed to update status"
                    );
                }
            } catch (error) {
                console.error("Error toggling hero section:", error);
                this.showToast(
                    error.response?.data?.message ||
                        "Failed to update hero section status",
                    "error"
                );
            } finally {
                this.toggleLoading = null;
            }
        },

        async deleteHeroSection(id) {
            const section = this.heroSections.find((s) => s.id === id);
            const confirmMessage =
                section &&
                section.is_active &&
                this.activeHeroSectionsCount === 1
                    ? "This is the only active hero section. Are you sure you want to delete it?"
                    : "Are you sure you want to delete this hero section?";

            if (!confirm(confirmMessage)) return;

            try {
                this.loading = true;

                const response = await axios.delete(
                    `/admin/hero-sections/${id}`
                );

                if (response.data.success) {
                    this.showToast(
                        "Hero section deleted successfully",
                        "success"
                    );
                    // Force reactivity update
                    this.heroSections = this.heroSections.filter(
                        (s) => s.id !== id
                    );
                } else {
                    throw new Error(
                        response.data.message || "Failed to delete hero section"
                    );
                }
            } catch (error) {
                console.error("Error deleting hero section:", error);
                this.showToast(
                    error.response?.data?.message ||
                        "Failed to delete hero section",
                    "error"
                );
            } finally {
                this.loading = false;
            }
        },

        startNewHeroSection() {
            this.currentHeroSection = {
                section_name: "",
                title: "Welcome To",
                title_highlight: "Your Brand",
                subtitle: "A Complete Solution",
                tagline:
                    "Your perfect tagline goes here to describe your amazing service.",
                background_images: [],
                background_gradients: [
                    "linear-gradient(135deg, #ff7101, #102e4a)",
                    "linear-gradient(135deg, #20bf6b, #102e4a)",
                    "linear-gradient(135deg, #f7b731, #102e4a)",
                ],
                slider_interval: 3500,
                enable_slider: true,
                cta_buttons: [
                    {
                        text: "Get Started",
                        url: "#",
                        type: "primary",
                        target: "_self",
                        styles: {
                            background: "#20bf6b",
                            color: "#fff",
                            border: "2px solid #20bf6b",
                            borderRadius: "6px",
                            padding: "10px 28px",
                            fontWeight: "500",
                        },
                        hover_styles: {
                            background: "transparent",
                            color: "#20bf6b",
                            transform: "translateY(-2px)",
                        },
                    },
                    {
                        text: "Learn More",
                        url: "#",
                        type: "secondary",
                        target: "_self",
                        styles: {
                            background: "transparent",
                            color: "#ff7101",
                            border: "2px solid #ff7101",
                            borderRadius: "6px",
                            padding: "10px 28px",
                            fontWeight: "500",
                        },
                        hover_styles: {
                            background: "#ff7101",
                            color: "#fff",
                            transform: "translateY(-2px)",
                        },
                    },
                ],
                show_navigation: true,
                navigation: {
                    logo: {
                        type: "text",
                        text: "Your Brand",
                        url: "/",
                        alt: "Logo",
                    },
                    menu_items: [],
                },
                text_styles: {
                    title: {
                        fontSize: "1.9rem",
                        fontWeight: "700",
                        color: "#ffffff",
                        marginBottom: "8px",
                    },
                    title_highlight: {
                        color: "#ff7101",
                    },
                    subtitle: {
                        fontSize: "1.5rem",
                        fontWeight: "600",
                        color: "#21bf6b",
                        marginBottom: "12px",
                    },
                    tagline: {
                        fontSize: "2.2rem",
                        fontWeight: "800",
                        color: "#f7b731",
                        lineHeight: "1.05",
                        marginBottom: "18px",
                    },
                },
                overlay_styles: {
                    background:
                        "linear-gradient(135deg, rgba(6, 6, 7, 0.8), rgba(0, 30, 60, 0.7))",
                },
                section_styles: {
                    minHeight: "80vh",
                    padding: "110px 20px 80px",
                },
                nav_styles: {
                    background: "transparent",
                    scrolled_background: "rgba(0, 30, 60, 0.95)",
                    link_color: "#fff",
                    link_hover_color: "#d35b00",
                    dropdown_background: "#fff",
                    dropdown_border_color: "#ff7101",
                    mobile_background: "rgba(0, 30, 60, 0.95)",
                },
                advanced_settings: {
                    enable_animations: true,
                    transition_duration: "1s",
                    enable_zoom_effect: true,
                    zoom_duration: "8s",
                    mobile_responsive: true,
                    lazy_load_images: true,
                    scroll_behavior: "smooth",
                    preload_images: true,
                },
                meta_title: "",
                meta_description: "",
                meta_tags: [],
                is_active: false,
            };
            this.isEditing = false;
            this.showEditorModal = true;
        },

        editHeroSection(heroSection) {
            this.currentHeroSection = JSON.parse(JSON.stringify(heroSection));
            this.isEditing = true;
            this.showEditorModal = true;
        },

        async duplicateHeroSection(heroSection) {
            if (this.loading) return;

            try {
                this.loading = true;

                const response = await axios.post(
                    `/admin/hero-sections/${heroSection.id}/duplicate`
                );

                if (response.data.success) {
                    this.showToast(
                        "Hero section duplicated successfully",
                        "success"
                    );

                    // Check if the section already exists to prevent duplicates
                    const newSection = response.data.data;
                    const existingIndex = this.heroSections.findIndex(
                        (s) => s.id === newSection.id
                    );

                    if (existingIndex === -1) {
                        // Add new section at the beginning only if it doesn't exist
                        this.heroSections.unshift({ ...newSection });
                    }
                } else {
                    throw new Error(
                        response.data.message ||
                            "Failed to duplicate hero section"
                    );
                }
            } catch (error) {
                console.error("Error duplicating hero section:", error);
                this.showToast(
                    error.response?.data?.message ||
                        "Failed to duplicate hero section",
                    "error"
                );
            } finally {
                this.loading = false;
            }
        },

        async saveHeroSection(formData) {
            if (this.loading) return;

            try {
                this.loading = true;
                let response;

                if (this.isEditing) {
                    response = await axios.post(
                        `/admin/hero-sections/${this.currentHeroSection.id}`,
                        formData,
                        {
                            headers: {
                                "Content-Type": "multipart/form-data",
                            },
                        }
                    );
                } else {
                    response = await axios.post(
                        "/admin/hero-sections",
                        formData,
                        {
                            headers: {
                                "Content-Type": "multipart/form-data",
                            },
                        }
                    );
                }

                if (response.data.success) {
                    const actionText = this.isEditing ? "updated" : "created";
                    this.showToast(
                        `Hero section ${actionText} successfully`,
                        "success"
                    );
                    this.closeEditor();

                    // Force reactivity update
                    if (this.isEditing) {
                        const newSections = [...this.heroSections];
                        const index = newSections.findIndex(
                            (s) => s.id === response.data.data.id
                        );
                        if (index !== -1) {
                            newSections[index] = { ...response.data.data };
                        }

                        // Handle active status updates
                        if (response.data.data.is_active) {
                            newSections.forEach((section, idx) => {
                                if (idx !== index) {
                                    section.is_active = false;
                                }
                            });
                        }

                        this.heroSections = newSections;
                    } else {
                        // For new sections, add to beginning
                        const newSections = [
                            response.data.data,
                            ...this.heroSections,
                        ];

                        // Handle active status
                        if (response.data.data.is_active) {
                            newSections.forEach((section, idx) => {
                                if (idx !== 0) {
                                    section.is_active = false;
                                }
                            });
                        }

                        this.heroSections = newSections;
                    }
                } else {
                    throw new Error(
                        response.data.message || "Operation failed"
                    );
                }
            } catch (error) {
                console.error("Save error:", error);
                let errorMessage = `Failed to ${
                    this.isEditing ? "update" : "create"
                } hero section`;

                if (error.response?.data?.message) {
                    errorMessage += `: ${error.response.data.message}`;
                } else if (error.response?.data?.errors) {
                    const errors = Object.values(
                        error.response.data.errors
                    ).flat();
                    errorMessage += `: ${errors.join(", ")}`;
                }

                this.showToast(errorMessage, "error");
            } finally {
                this.loading = false;
            }
        },

        closeEditor() {
            this.showEditorModal = false;
            this.currentHeroSection = null;
            this.isEditing = false;
        },

        getHeroPreviewStyle(heroSection) {
            let backgroundStyle = {};

            if (
                heroSection.background_images &&
                heroSection.background_images.length > 0
            ) {
                backgroundStyle.backgroundImage = `url(${heroSection.background_images[0]})`;
                backgroundStyle.backgroundSize = "cover";
                backgroundStyle.backgroundPosition = "center";
            } else if (
                heroSection.background_gradients &&
                heroSection.background_gradients.length > 0
            ) {
                backgroundStyle.background =
                    heroSection.background_gradients[0];
            } else {
                backgroundStyle.background =
                    "linear-gradient(135deg, #ff7101, #102e4a)";
            }

            return {
                ...backgroundStyle,
                minHeight: "120px",
                position: "relative",
                display: "flex",
                alignItems: "center",
                overflow: "hidden",
            };
        },
    },
};
</script>

<style scoped>
.section-manager {
    padding: 24px;
    background: #f8fafc;
    min-height: 100vh;
}

/* Toast Styles */
.toast-container {
    position: fixed;
    top: 20px;
    right: 20px;
    z-index: 10000;
    pointer-events: none;
}

.toast {
    display: flex;
    align-items: flex-start;
    gap: 12px;
    background: white;
    border-radius: 8px;
    padding: 16px;
    margin-bottom: 12px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    border-left: 4px solid;
    max-width: 400px;
    pointer-events: auto;
    cursor: pointer;
    transition: all 0.3s ease;
    animation: slideIn 0.3s ease;
}

.toast:hover {
    transform: translateX(-5px);
}

.toast-success {
    border-left-color: #10b981;
    background: #f0fdf4;
}

.toast-error {
    border-left-color: #ef4444;
    background: #fef2f2;
}

.toast-warning {
    border-left-color: #f59e0b;
    background: #fffbeb;
}

.toast-info {
    border-left-color: #3b82f6;
    background: #eff6ff;
}

.toast-icon {
    flex-shrink: 0;
    width: 24px;
    height: 24px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    font-size: 14px;
    color: white;
}

.toast-success .toast-icon {
    background: #10b981;
}

.toast-error .toast-icon {
    background: #ef4444;
}

.toast-warning .toast-icon {
    background: #f59e0b;
}

.toast-info .toast-icon {
    background: #3b82f6;
}

.toast-content {
    flex: 1;
    min-width: 0;
}

.toast-title {
    font-weight: 600;
    margin-bottom: 4px;
    color: #111827;
}

.toast-message {
    font-size: 14px;
    color: #6b7280;
    line-height: 1.4;
}

.toast-close {
    flex-shrink: 0;
    background: none;
    border: none;
    font-size: 18px;
    color: #9ca3af;
    cursor: pointer;
    padding: 0;
    width: 20px;
    height: 20px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.toast-close:hover {
    color: #6b7280;
}

@keyframes slideIn {
    from {
        transform: translateX(100%);
        opacity: 0;
    }
    to {
        transform: translateX(0);
        opacity: 1;
    }
}

.page-header {
    background: white;
    border-radius: 12px;
    padding: 24px;
    margin-bottom: 24px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}

.header-content {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    gap: 20px;
}

.header-left h1.page-title {
    margin: 0 0 8px 0;
    font-size: 2rem;
    font-weight: 700;
    color: #1e293b;
}

.header-left p.page-subtitle {
    margin: 0;
    color: #64748b;
    font-size: 1rem;
}

.header-actions {
    display: flex;
    gap: 12px;
    align-items: center;
}

.btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 10px 16px;
    border-radius: 8px;
    font-weight: 500;
    text-decoration: none;
    cursor: pointer;
    transition: all 0.2s;
    border: none;
    font-size: 0.9rem;
}

.btn-primary {
    background: #ff7101;
    color: white;
}

.btn-primary:hover {
    background: #e06500;
}

.btn-outline {
    background: white;
    color: #374151;
    border: 1px solid #d1d5db;
}

.btn-outline:hover {
    background: #f9fafb;
    border-color: #9ca3af;
}

.btn:disabled {
    opacity: 0.5;
    cursor: not-allowed;
}

.loading-container {
    text-align: center;
    padding: 60px 20px;
    color: #64748b;
}

.loading-spinner {
    width: 40px;
    height: 40px;
    border: 4px solid #e2e8f0;
    border-top-color: #ff7101;
    border-radius: 50%;
    animation: spin 1s linear infinite;
    margin: 0 auto 16px;
}

@keyframes spin {
    to {
        transform: rotate(360deg);
    }
}

.sections-container {
    background: white;
    border-radius: 12px;
    padding: 24px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}

.sections-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 24px;
    padding-bottom: 16px;
    border-bottom: 1px solid #e2e8f0;
}

.sections-title {
    margin: 0 0 4px 0;
    font-size: 1.5rem;
    font-weight: 600;
    color: #1e293b;
}

.sections-count {
    margin: 0;
    color: #64748b;
    font-size: 0.9rem;
}

.sections-list {
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.section-item {
    display: flex;
    align-items: center;
    gap: 16px;
    padding: 16px;
    background: #fafbfc;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    transition: all 0.2s;
}

.section-item:hover {
    border-color: #cbd5e1;
}

.section-item.section-hidden {
    opacity: 0.6;
}

.section-content {
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex: 1;
    gap: 20px;
}

.section-info {
    flex: 1;
}

.hero-preview-small {
    margin-bottom: 12px;
    border: 1px solid #e2e8f0;
    border-radius: 6px;
    overflow: hidden;
}

.preview-hero {
    position: relative;
    min-height: 120px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.preview-overlay {
    position: absolute;
    inset: 0;
    background: linear-gradient(
        135deg,
        rgba(6, 6, 7, 0.8),
        rgba(0, 30, 60, 0.7)
    );
    z-index: 1;
}

.preview-content {
    position: relative;
    z-index: 2;
    text-align: center;
    padding: 16px;
}

.preview-text {
    color: white;
}

.preview-text h3 {
    margin: 0 0 4px 0;
    font-size: 1rem;
    font-weight: 700;
}

.preview-text h4 {
    margin: 0 0 4px 0;
    font-size: 0.9rem;
    font-weight: 600;
    color: #ff7101;
}

.preview-text p {
    margin: 0 0 4px 0;
    font-size: 0.85rem;
    color: #21bf6b;
}

.preview-text small {
    font-size: 0.75rem;
    color: #f7b731;
    line-height: 1.2;
    display: block;
}

.section-name {
    margin: 0 0 8px 0;
    font-size: 1.1rem;
    font-weight: 600;
    color: #1e293b;
}

.section-meta {
    display: flex;
    gap: 12px;
    flex-wrap: wrap;
    font-size: 0.85rem;
    color: #64748b;
}

.section-key {
    background: #f1f5f9;
    padding: 2px 6px;
    border-radius: 4px;
    font-family: monospace;
    font-size: 0.8rem;
}

.section-feature {
    background: #dcfce7;
    color: #166534;
    padding: 2px 6px;
    border-radius: 4px;
    font-size: 0.8rem;
}

.section-controls {
    display: flex;
    align-items: center;
    gap: 16px;
}

.visibility-toggle {
    display: flex;
    align-items: center;
    gap: 8px;
}

.toggle-switch {
    position: relative;
    display: inline-block;
    width: 44px;
    height: 24px;
}

.toggle-switch input {
    opacity: 0;
    width: 0;
    height: 0;
}

.toggle-slider {
    position: absolute;
    cursor: pointer;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: #ccc;
    transition: 0.4s;
    border-radius: 24px;
}

.toggle-slider:before {
    position: absolute;
    content: "";
    height: 18px;
    width: 18px;
    left: 3px;
    bottom: 3px;
    background-color: white;
    transition: 0.4s;
    border-radius: 50%;
}

input:checked + .toggle-slider {
    background-color: #ff7101;
}

input:checked + .toggle-slider:before {
    transform: translateX(20px);
}

.toggle-label {
    font-size: 0.85rem;
    color: #374151;
    font-weight: 500;
}

.action-buttons {
    display: flex;
    gap: 4px;
}

.btn-icon {
    width: 32px;
    height: 32px;
    padding: 0;
    border: 1px solid #e2e8f0;
    background: white;
    border-radius: 6px;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.2s;
    color: #64748b;
}

.btn-icon:hover {
    background: #f1f5f9;
    border-color: #cbd5e1;
    color: #374151;
}

.btn-icon.danger:hover {
    background: #fef2f2;
    border-color: #fecaca;
    color: #ef4444;
}

.btn-icon:disabled {
    opacity: 0.5;
    cursor: not-allowed;
}

.modal-overlay {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.5);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 1000;
    backdrop-filter: blur(4px);
}

.editor-modal {
    background: white;
    border-radius: 12px;
    box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
    width: 95%;
    max-width: 1400px;
    max-height: 90vh;
    overflow: hidden;
    display: flex;
    flex-direction: column;
}

.modal-header {
    padding: 20px 24px;
    border-bottom: 1px solid #e2e8f0;
    display: flex;
    justify-content: space-between;
    align-items: center;
    background: #fafbfc;
}

.modal-header h2 {
    margin: 0;
    font-size: 1.5rem;
    color: #1e293b;
    font-weight: 600;
}

.close-btn {
    background: none;
    border: none;
    font-size: 24px;
    cursor: pointer;
    color: #9ca3af;
    width: 32px;
    height: 32px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    transition: all 0.2s;
}

.close-btn:hover {
    background: #f3f4f6;
    color: #374151;
}

.modal-body {
    padding: 0;
    overflow: auto;
    flex: 1;
}

.empty-state {
    text-align: center;
    padding: 60px 20px;
    color: #64748b;
}

.empty-icon {
    margin-bottom: 16px;
    color: #cbd5e1;
}

.empty-state h3 {
    margin: 0 0 8px 0;
    color: #374151;
    font-size: 1.25rem;
    font-weight: 600;
}

.empty-state p {
    margin: 0 0 24px 0;
    font-size: 0.95rem;
}

@media (max-width: 768px) {
    .section-manager {
        padding: 16px;
    }

    .header-content {
        flex-direction: column;
        align-items: flex-start;
        gap: 16px;
    }

    .header-actions {
        width: 100%;
        flex-wrap: wrap;
        justify-content: flex-start;
    }

    .sections-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 16px;
    }

    .section-content {
        flex-direction: column;
        align-items: flex-start;
        gap: 16px;
    }

    .section-controls {
        width: 100%;
        justify-content: space-between;
    }

    .editor-modal {
        width: 95%;
        max-height: 95vh;
    }

    .preview-hero {
        min-height: 100px;
    }

    .preview-content {
        padding: 12px;
    }

    .preview-text h3 {
        font-size: 0.9rem;
    }

    .preview-text h4 {
        font-size: 0.8rem;
    }

    .preview-text p {
        font-size: 0.75rem;
    }

    .preview-text small {
        font-size: 0.7rem;
    }

    .toast-container {
        top: 10px;
        right: 10px;
        left: 10px;
    }

    .toast {
        max-width: none;
    }
}

@media (max-width: 480px) {
    .page-header {
        padding: 16px;
    }

    .sections-container {
        padding: 16px;
    }

    .btn {
        padding: 8px 12px;
        font-size: 0.85rem;
    }

    .section-item {
        flex-direction: column;
        align-items: flex-start;
        padding: 12px;
    }

    .header-actions {
        gap: 8px;
    }

    .btn {
        flex: 1;
        min-width: 0;
        justify-content: center;
    }

    .hero-preview-small {
        width: 100%;
    }

    .preview-hero {
        min-height: 80px;
    }

    .section-meta {
        flex-direction: column;
        gap: 8px;
    }
}
</style>
