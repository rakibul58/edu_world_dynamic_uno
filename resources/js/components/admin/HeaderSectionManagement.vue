<template>
    <div class="section-manager">
        <!-- Page Header -->
        <div class="page-header">
            <div class="header-content">
                <div class="header-left">
                    <h1 class="page-title">Header Management</h1>
                    <p class="page-subtitle">
                        Customize and manage your website header
                    </p>
                </div>
                <div class="header-actions">
                    <button
                        @click="startNewHeader"
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
                        Create New Header
                    </button>
                    <button
                        @click="fetchHeaders"
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

        <!-- Success/Error Messages -->
        <div v-if="message.text" class="alert" :class="message.type">
            <svg
                v-if="message.type === 'success'"
                width="20"
                height="20"
                viewBox="0 0 24 24"
                fill="none"
                stroke="currentColor"
                stroke-width="2"
            >
                <polyline points="9,11 12,14 22,4" />
                <path
                    d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"
                />
            </svg>
            <svg
                v-else
                width="20"
                height="20"
                viewBox="0 0 24 24"
                fill="none"
                stroke="currentColor"
                stroke-width="2"
            >
                <circle cx="12" cy="12" r="10" />
                <line x1="15" y1="9" x2="9" y2="15" />
                <line x1="9" y1="9" x2="15" y2="15" />
            </svg>
            {{ message.text }}
        </div>

        <!-- Header Editor Modal -->
        <div
            v-if="showEditorModal"
            class="modal-overlay"
            @click.self="closeEditor"
        >
            <div class="modal editor-modal">
                <div class="modal-header">
                    <h2>
                        {{ isEditing ? "Edit Header" : "Create New Header" }}
                    </h2>
                    <button @click="closeEditor" class="close-btn">
                        &times;
                    </button>
                </div>
                <div class="modal-body">
                    <HeaderEditor
                        :header="currentHeader"
                        @save="saveHeader"
                        @cancel="closeEditor"
                        @error="showMessage($event, 'error')"
                    />
                </div>
            </div>
        </div>

        <!-- Loading State -->
        <div v-if="loading && headers.length === 0" class="loading-container">
            <div class="loading-spinner"></div>
            <p>Loading headers...</p>
        </div>

        <!-- Headers List -->
        <div v-else class="sections-container">
            <div class="sections-header">
                <div class="sections-info">
                    <h2 class="sections-title">Website Header</h2>
                    <p class="sections-count">
                        {{ activeHeadersCount }} of {{ headers.length }} headers
                        active
                    </p>
                </div>
            </div>

            <!-- List View -->
            <div class="sections-list">
                <div
                    v-for="header in headers"
                    :key="header.id"
                    class="section-item"
                    :class="{ 'section-hidden': !header.is_active }"
                >
                    <div class="section-content">
                        <div class="section-info">
                            <div class="header-preview-small">
                                <div
                                    class="preview-nav"
                                    :style="getNavStyle(header)"
                                >
                                    <div class="preview-logo">
                                        <img
                                            v-if="
                                                header.logo_type === 'image' &&
                                                header.logo_image_path
                                            "
                                            :src="header.logo_image_path"
                                            alt="Logo"
                                            style="max-height: 30px"
                                        />
                                        <span v-else>{{
                                            header.logo_text || "Your Logo"
                                        }}</span>
                                    </div>
                                    <div class="preview-buttons">
                                        <span
                                            v-for="(
                                                button, btnIndex
                                            ) in header.buttons || []"
                                            :key="btnIndex"
                                            class="preview-btn"
                                            :style="getButtonStyle(button)"
                                        >
                                            {{ button.text }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <h3 class="section-name">
                                {{ header.name || `Header #${header.id}` }}
                            </h3>
                            <div class="section-meta">
                                <span class="section-key"
                                    >{{ header.logo_type }} logo</span
                                >
                                <span class="section-buttons"
                                    >{{
                                        (header.buttons || []).length
                                    }}
                                    buttons</span
                                >
                            </div>
                        </div>

                        <div class="section-controls">
                            <div class="visibility-toggle">
                                <label class="toggle-switch">
                                    <input
                                        type="checkbox"
                                        :checked="header.is_active"
                                        @change="toggleHeaderActive(header)"
                                        :disabled="loading"
                                    />
                                    <span class="toggle-slider"></span>
                                </label>
                                <span class="toggle-label">
                                    {{
                                        header.is_active ? "Active" : "Inactive"
                                    }}
                                </span>
                            </div>
                            <div class="action-buttons">
                                <button
                                    @click="editHeader(header)"
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
                                    @click="duplicateHeader(header)"
                                    class="btn-icon"
                                    title="Duplicate"
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
                                    @click="deleteHeader(header.id)"
                                    class="btn-icon danger"
                                    title="Delete"
                                    :disabled="header.is_active"
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
            <div v-if="headers.length === 0" class="empty-state">
                <div class="empty-icon">
                    <svg
                        width="48"
                        height="48"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2"
                    >
                        <path
                            d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"
                        />
                        <polyline points="7.5,4.21 12,6.81 16.5,4.21" />
                        <polyline points="7.5,19.79 7.5,14.6 3,12" />
                        <polyline points="21,12 16.5,14.6 16.5,19.79" />
                        <polyline points="3.27,6.96 12,12.01 20.73,6.96" />
                        <line x1="12" y1="22.08" x2="12" y2="12" />
                    </svg>
                </div>
                <h3>No Headers Created Yet</h3>
                <p>Get started by creating your website header</p>
                <button @click="startNewHeader" class="btn btn-primary">
                    Create Your First Header
                </button>
            </div>
        </div>
    </div>
</template>

<script>
import axios from "axios";
import HeaderEditor from "./HeaderEditor.vue";

export default {
    name: "HeaderSectionManagement",
    components: {
        HeaderEditor,
    },
    data() {
        return {
            headers: [],
            loading: false,
            message: { text: "", type: "" },
            showEditorModal: false,
            currentHeader: null,
            isEditing: false,
        };
    },
    computed: {
        activeHeadersCount() {
            return this.headers.filter((header) => header.is_active).length;
        },
    },
    mounted() {
        this.fetchHeaders();
    },
    methods: {
        async fetchHeaders() {
            this.loading = true;
            try {
                const response = await axios.get("/admin/header-sections");
                if (response.data.success) {
                    this.headers = response.data.data;
                }
            } catch (error) {
                this.showMessage("Failed to fetch headers", "error");
                console.error("Error fetching headers:", error);
            } finally {
                this.loading = false;
            }
        },

        showMessage(text, type = "success") {
            this.message = { text, type };
            setTimeout(() => {
                this.message = { text: "", type: "" };
            }, 5000);
        },

        async toggleHeaderActive(header) {
            try {
                this.loading = true;
                const response = await axios.patch(
                    `/admin/header-sections/${header.id}/toggle`
                );
                if (response.data.success) {
                    this.showMessage(
                        "Header status updated successfully",
                        "success"
                    );
                    this.fetchHeaders(); // Refresh to get updated data
                }
            } catch (error) {
                header.is_active = !header.is_active; // Revert on error
                this.showMessage("Failed to update header status", "error");
                console.error("Error toggling header:", error);
            } finally {
                this.loading = false;
            }
        },

        async deleteHeader(id) {
            if (!confirm("Are you sure you want to delete this header?"))
                return;

            try {
                this.loading = true;
                const response = await axios.delete(
                    `/admin/header-sections/${id}`
                );
                if (response.data.success) {
                    this.showMessage("Header deleted successfully", "success");
                    this.fetchHeaders();
                }
            } catch (error) {
                this.showMessage("Failed to delete header", "error");
                console.error("Error deleting header:", error);
            } finally {
                this.loading = false;
            }
        },

        startNewHeader() {
            this.currentHeader = {
                name: "",
                logo_type: "text",
                logo_text: "Your Brand",
                logo_image_path: null,
                logo_tagline: "",
                logo_link: { url: "/", target: "_self" },
                buttons: [],
                is_active: false,
                sort_order: 1,
            };
            this.isEditing = false;
            this.showEditorModal = true;
        },

        editHeader(header) {
            this.currentHeader = { ...header };
            this.isEditing = true;
            this.showEditorModal = true;
        },

        async duplicateHeader(header) {
            try {
                this.loading = true;
                const headerCopy = { ...header };
                delete headerCopy.id;
                headerCopy.name = `${header.name} (Copy)`;
                headerCopy.is_active = false;

                const response = await axios.post(
                    "/admin/header-sections",
                    headerCopy
                );
                if (response.data.success) {
                    this.showMessage(
                        "Header duplicated successfully",
                        "success"
                    );
                    this.fetchHeaders();
                }
            } catch (error) {
                this.showMessage("Failed to duplicate header", "error");
                console.error("Error duplicating header:", error);
            } finally {
                this.loading = false;
            }
        },

        async saveHeader(formData) {
            try {
                this.loading = true;
                let response;

                if (this.isEditing) {
                    // For updates with file uploads, use POST with method spoofing
                    response = await axios.post(
                        `/admin/header-sections/${this.currentHeader.id}`,
                        formData,
                        {
                            headers: {
                                "Content-Type": "multipart/form-data",
                            },
                        }
                    );
                } else {
                    response = await axios.post(
                        "/admin/header-sections",
                        formData,
                        {
                            headers: {
                                "Content-Type": "multipart/form-data",
                            },
                        }
                    );
                }

                if (response.data.success) {
                    this.showMessage(
                        `Header ${
                            this.isEditing ? "updated" : "created"
                        } successfully`,
                        "success"
                    );
                    this.closeEditor();
                    this.fetchHeaders();
                }
            } catch (error) {
                console.error("Save error:", error);
                let errorMessage = `Failed to ${
                    this.isEditing ? "update" : "create"
                } header`;

                if (error.response?.data?.message) {
                    errorMessage += `: ${error.response.data.message}`;
                } else if (error.response?.data?.errors) {
                    const errors = Object.values(
                        error.response.data.errors
                    ).flat();
                    errorMessage += `: ${errors.join(", ")}`;
                }

                this.showMessage(errorMessage, "error");
                console.error("Error saving header:", error);
            } finally {
                this.loading = false;
            }
        },

        closeEditor() {
            this.showEditorModal = false;
            this.currentHeader = null;
        },

        getNavStyle(header) {
            const styles = header.nav_styles || {};
            return {
                background: styles.background || "#102e4a",
                padding: styles.padding || "0 20px",
                boxShadow: styles.box_shadow || "0 2px 10px rgba(0,0,0,0.1)",
                ...styles,
            };
        },

        getButtonStyle(button) {
            const custom = button.custom_styles || {};
            const baseStyles = {
                backgroundColor:
                    custom.background_color ||
                    this.getDefaultButtonColor(button.style),
                color: custom.text_color || "#ffffff",
                border: `1px solid ${
                    custom.border_color ||
                    custom.background_color ||
                    this.getDefaultButtonColor(button.style)
                }`,
                borderRadius: custom.border_radius || "4px",
                padding: "2px 8px",
                fontSize: "0.7rem",
                display: "inline-block",
                textDecoration: "none",
            };

            // Handle outlined and ghost button styles
            if (button.style === "outlined") {
                baseStyles.backgroundColor = "transparent";
                baseStyles.color =
                    custom.text_color ||
                    custom.border_color ||
                    this.getDefaultButtonColor("primary");
            } else if (button.style === "ghost") {
                baseStyles.backgroundColor = "transparent";
                baseStyles.border = "none";
                baseStyles.color = custom.text_color || "#ffffff";
            }

            return baseStyles;
        },

        getDefaultButtonColor(style) {
            const colors = {
                primary: "#ff7101", // Orange
                secondary: "#20bf6b",
                outlined: "#ff7101", // Orange
                ghost: "#ffffff",
            };
            return colors[style] || "#ff7101"; // Default to orange
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

.alert {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 16px;
    border-radius: 8px;
    margin-bottom: 24px;
    font-weight: 500;
}

.alert.success {
    background: #dcfce7;
    color: #166534;
    border: 1px solid #bbf7d0;
}

.alert.error {
    background: #fef2f2;
    color: #dc2626;
    border: 1px solid #fecaca;
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

.section-controls {
    display: flex;
    align-items: center;
    gap: 16px;
}

.header-preview-small {
    margin-bottom: 12px;
    border: 1px solid #e2e8f0;
    border-radius: 6px;
    overflow: hidden;
}

.preview-nav {
    padding: 8px 12px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    min-height: 40px;
}

.preview-logo {
    font-weight: bold;
    font-size: 0.9rem;
    color: white;
}

.preview-buttons {
    display: flex;
    gap: 6px;
    align-items: center;
}

.preview-btn {
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    max-width: 80px;
    font-size: 0.7rem;
    padding: 2px 6px;
    border-radius: 3px;
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
    width: 90%;
    max-width: 1200px;
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
}
</style>
