<template>
    <div class="admin-footer-section">
        <!-- Toast Notifications -->
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
                <button class="toast-close" @click="removeToast(toast.id)">
                    ×
                </button>
            </div>
        </div>

        <!-- Header Section -->
        <div class="admin-header">
            <div class="header-content">
                <h2>Footer Section Management</h2>
                <p>Manage your website footer content, links, and styling</p>
            </div>
            <button
                class="btn btn-primary"
                @click="showCreateForm = true"
                :disabled="isLoading"
            >
                <i class="fas fa-plus"></i> Add New Footer Section
            </button>
        </div>

        <!-- Create/Edit Form Modal -->
        <div
            v-if="showCreateForm || editingSection"
            class="modal-overlay"
            @click="closeForm"
        >
            <div class="modal-content" @click.stop>
                <div class="modal-header">
                    <h3>
                        {{ editingSection ? "Edit" : "Create" }} Footer Section
                    </h3>
                    <button @click="closeForm" class="close-btn">
                        &times;
                    </button>
                </div>
                <form @submit.prevent="saveSection" class="section-form">
                    <!-- Logo Upload -->
                    <div class="form-section">
                        <h4>Logo</h4>
                        <div class="form-row">
                            <div class="form-group full-width">
                                <label>Logo Image</label>
                                <input
                                    type="file"
                                    @change="handleLogoUpload"
                                    accept="image/*"
                                    class="form-control"
                                />
                                <small class="text-muted"
                                    >Logo will be saved as
                                    /public/uploads/image/logo.png</small
                                >
                            </div>
                        </div>
                    </div>

                    <!-- Basic Content -->
                    <div class="form-section">
                        <h4>Basic Information</h4>
                        <div class="form-row">
                            <div class="form-group">
                                <label>Tagline</label>
                                <input
                                    type="text"
                                    v-model="formData.tagline"
                                    placeholder="A Complete ERP System"
                                    required
                                    class="form-control"
                                />
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group full-width">
                                <label>Description</label>
                                <textarea
                                    v-model="formData.description"
                                    required
                                    rows="3"
                                    placeholder="Providing exceptional Islamic Cambridge education..."
                                    class="form-control"
                                ></textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Contact Information -->
                    <div class="form-section">
                        <h4>Contact Information</h4>
                        <div class="form-row">
                            <div class="form-group">
                                <label>Address</label>
                                <input
                                    type="text"
                                    v-model="formData.address"
                                    placeholder="Chattogram, Bangladesh"
                                    required
                                    class="form-control"
                                />
                            </div>
                            <div class="form-group">
                                <label>Phone</label>
                                <input
                                    type="text"
                                    v-model="formData.phone"
                                    placeholder="+880 123 456 789"
                                    required
                                    class="form-control"
                                />
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label>Email</label>
                                <input
                                    type="text"
                                    v-model="formData.email"
                                    placeholder="info@eduworld.edu"
                                    required
                                    class="form-control"
                                />
                            </div>
                            <div class="form-group">
                                <label>Working Hours</label>
                                <input
                                    type="text"
                                    v-model="formData.working_hours"
                                    placeholder="Mon-Fri: 8:00 AM - 4:00 PM"
                                    required
                                    class="form-control"
                                />
                            </div>
                        </div>
                    </div>

                    <!-- Social Links -->
                    <div class="form-section">
                        <h4>Social Links</h4>
                        <div
                            v-for="(link, index) in formData.social_links"
                            :key="index"
                            class="link-item"
                        >
                            <div class="form-row">
                                <div class="form-group">
                                    <label>Platform</label>
                                    <input
                                        type="text"
                                        v-model="link.platform"
                                        placeholder="facebook"
                                        class="form-control"
                                    />
                                </div>
                                <div class="form-group">
                                    <label>URL</label>
                                    <input
                                        type="url"
                                        v-model="link.url"
                                        placeholder="https://facebook.com/yourpage"
                                        class="form-control"
                                    />
                                </div>
                                <div class="form-group">
                                    <label>Icon Class</label>
                                    <input
                                        type="text"
                                        v-model="link.icon"
                                        placeholder="fab fa-facebook-f"
                                        class="form-control"
                                    />
                                </div>
                                <div class="form-group">
                                    <button
                                        type="button"
                                        @click="removeSocialLink(index)"
                                        class="btn btn-danger btn-sm"
                                        style="margin-top: 25px"
                                    >
                                        Remove
                                    </button>
                                </div>
                            </div>
                        </div>
                        <button
                            type="button"
                            @click="addSocialLink"
                            class="btn btn-secondary"
                        >
                            <i class="fas fa-plus"></i> Add Social Link
                        </button>
                    </div>

                    <!-- Quick Links -->
                    <div class="form-section">
                        <h4>Quick Links</h4>
                        <div
                            v-for="(link, index) in formData.quick_links"
                            :key="index"
                            class="link-item"
                        >
                            <div class="form-row">
                                <div class="form-group">
                                    <label>Link Text</label>
                                    <input
                                        type="text"
                                        v-model="link.text"
                                        placeholder="About Us"
                                        class="form-control"
                                    />
                                </div>
                                <div class="form-group">
                                    <label>URL</label>
                                    <input
                                        type="text"
                                        v-model="link.url"
                                        placeholder="/about"
                                        class="form-control"
                                    />
                                </div>
                                <div class="form-group">
                                    <button
                                        type="button"
                                        @click="removeQuickLink(index)"
                                        class="btn btn-danger btn-sm"
                                        style="margin-top: 25px"
                                    >
                                        Remove
                                    </button>
                                </div>
                            </div>
                        </div>
                        <button
                            type="button"
                            @click="addQuickLink"
                            class="btn btn-secondary"
                        >
                            <i class="fas fa-plus"></i> Add Quick Link
                        </button>
                    </div>

                    <!-- Programs Links -->
                    <div class="form-section">
                        <h4>Programs</h4>
                        <div
                            v-for="(program, index) in formData.programs"
                            :key="index"
                            class="link-item"
                        >
                            <div class="form-row">
                                <div class="form-group">
                                    <label>Program Name</label>
                                    <input
                                        type="text"
                                        v-model="program.text"
                                        placeholder="Islamic Studies"
                                        class="form-control"
                                    />
                                </div>
                                <div class="form-group">
                                    <label>URL</label>
                                    <input
                                        type="text"
                                        v-model="program.url"
                                        placeholder="/programs/islamic-studies"
                                        class="form-control"
                                    />
                                </div>
                                <div class="form-group">
                                    <button
                                        type="button"
                                        @click="removeProgram(index)"
                                        class="btn btn-danger btn-sm"
                                        style="margin-top: 25px"
                                    >
                                        Remove
                                    </button>
                                </div>
                            </div>
                        </div>
                        <button
                            type="button"
                            @click="addProgram"
                            class="btn btn-secondary"
                        >
                            <i class="fas fa-plus"></i> Add Program
                        </button>
                    </div>

                    <!-- Color Styling -->
                    <div class="form-section">
                        <h4>Color Styling</h4>
                        <div class="form-row">
                            <div class="form-group">
                                <label>Background Color</label>
                                <div class="color-input-group">
                                    <input
                                        type="color"
                                        v-model="formData.background_color"
                                        class="form-control color-input"
                                    />
                                    <input
                                        type="text"
                                        v-model="formData.background_color"
                                        placeholder="#030811"
                                        class="form-control color-text-input"
                                    />
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Text Color</label>
                                <div class="color-input-group">
                                    <input
                                        type="color"
                                        v-model="formData.text_color"
                                        class="form-control color-input"
                                    />
                                    <input
                                        type="text"
                                        v-model="formData.text_color"
                                        placeholder="#ffffff"
                                        class="form-control color-text-input"
                                    />
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Accent Color</label>
                                <div class="color-input-group">
                                    <input
                                        type="color"
                                        v-model="formData.accent_color"
                                        class="form-control color-input"
                                    />
                                    <input
                                        type="text"
                                        v-model="formData.accent_color"
                                        placeholder="#ff7101"
                                        class="form-control color-text-input"
                                    />
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label>Link Color</label>
                                <div class="color-input-group">
                                    <input
                                        type="color"
                                        v-model="formData.link_color"
                                        class="form-control color-input"
                                    />
                                    <input
                                        type="text"
                                        v-model="formData.link_color"
                                        placeholder="#cccccc"
                                        class="form-control color-text-input"
                                    />
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Link Hover Color</label>
                                <div class="color-input-group">
                                    <input
                                        type="color"
                                        v-model="formData.link_hover_color"
                                        class="form-control color-input"
                                    />
                                    <input
                                        type="text"
                                        v-model="formData.link_hover_color"
                                        placeholder="#ff7101"
                                        class="form-control color-text-input"
                                    />
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-actions">
                        <button
                            type="button"
                            @click="closeForm"
                            class="btn btn-secondary"
                        >
                            Cancel
                        </button>
                        <button
                            type="submit"
                            class="btn btn-primary"
                            :disabled="isSaving"
                        >
                            {{
                                isSaving
                                    ? "Saving..."
                                    : editingSection
                                    ? "Update"
                                    : "Create"
                            }}
                            Footer Section
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Sections List -->
        <div class="sections-list">
            <div class="list-header">
                <h3>Footer Sections ({{ sections.length }})</h3>
                <div class="view-options">
                    <button
                        class="btn btn-sm"
                        :class="{ 'btn-primary': viewMode === 'grid' }"
                        @click="viewMode = 'grid'"
                    >
                        Grid View
                    </button>
                    <button
                        class="btn btn-sm"
                        :class="{ 'btn-primary': viewMode === 'list' }"
                        @click="viewMode = 'list'"
                    >
                        List View
                    </button>
                </div>
            </div>

            <div class="sections-container" :class="'view-' + viewMode">
                <div
                    v-for="section in sections"
                    :key="section.id"
                    class="section-item"
                    :class="{ 'is-active': section.is_active }"
                >
                    <div class="section-card">
                        <div class="card-header">
                            <div class="section-status">
                                <span
                                    class="status-indicator"
                                    :class="{ active: section.is_active }"
                                ></span>
                                <span class="status-text">
                                    {{
                                        section.is_active
                                            ? "Active"
                                            : "Inactive"
                                    }}
                                </span>
                            </div>
                            <div class="card-actions">
                                <button
                                    class="action-btn toggle-btn"
                                    @click="toggleSection(section)"
                                    :class="{ active: section.is_active }"
                                >
                                    {{ section.is_active ? "Hide" : "Show" }}
                                </button>
                                <button
                                    class="action-btn delete-btn"
                                    @click="deleteSection(section.id)"
                                    :disabled="section.is_active"
                                >
                                    Delete
                                </button>
                            </div>
                        </div>

                        <div class="card-content">
                            <div
                                class="section-preview"
                                :style="getSectionPreviewStyle(section)"
                            >
                                <div class="preview-content">
                                    <div class="preview-tagline">
                                        {{ section.tagline }}
                                    </div>
                                    <p class="preview-description">
                                        {{
                                            section.description.substring(
                                                0,
                                                100
                                            )
                                        }}...
                                    </p>
                                    <div class="preview-contact">
                                        <small>{{ section.address }}</small>
                                        <small>{{ section.phone }}</small>
                                        <small>{{ section.email }}</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card-footer">
                            <small class="update-time">
                                Last updated:
                                {{ formatDate(section.updated_at) }}
                            </small>
                            <button
                                @click="editSection(section)"
                                class="btn btn-sm btn-outline-primary"
                            >
                                <i class="fas fa-edit"></i> Edit
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div v-if="sections.length === 0" class="empty-state">
                <div class="empty-icon">🦶</div>
                <h4>No footer sections yet</h4>
                <p>
                    Create your first footer section to display on your website
                </p>
                <button
                    class="btn btn-primary"
                    @click="showCreateForm = true"
                    :disabled="isLoading"
                >
                    + Add Your First Footer Section
                </button>
            </div>
        </div>
    </div>
</template>

<script>
import axios from "axios";

export default {
    name: "FooterSectionAdmin",
    data() {
        return {
            sections: [],
            showCreateForm: false,
            editingSection: null,
            isLoading: false,
            isSaving: false,
            viewMode: "grid",
            toasts: [],
            toastIdCounter: 0,
            formData: {
                description: "",
                tagline: "",
                social_links: [
                    {
                        platform: "facebook",
                        url: "",
                        icon: "fab fa-facebook-f",
                    },
                    { platform: "twitter", url: "", icon: "fab fa-twitter" },
                    {
                        platform: "instagram",
                        url: "",
                        icon: "fab fa-instagram",
                    },
                    { platform: "youtube", url: "", icon: "fab fa-youtube" },
                ],
                quick_links: [
                    { text: "About Us", url: "#" },
                    { text: "Admissions", url: "#" },
                    { text: "Programs", url: "#" },
                    { text: "Campus Life", url: "#" },
                    { text: "News & Events", url: "#" },
                ],
                programs: [
                    { text: "Islamic Studies", url: "#" },
                    { text: "Cambridge Curriculum", url: "#" },
                    { text: "Hifz Program", url: "#" },
                    { text: "Primary Education", url: "#" },
                    { text: "Secondary Education", url: "#" },
                ],
                address: "",
                phone: "",
                email: "",
                working_hours: "",
                background_color: "#030811",
                text_color: "#ffffff",
                accent_color: "#ff7101",
                link_color: "#cccccc",
                link_hover_color: "#ff7101",
                logo: null,
            },
        };
    },
    methods: {
        // Toast notification methods (reused from CTA component)
        showToast(type, title, message, duration = 5000) {
            const toast = {
                id: ++this.toastIdCounter,
                type,
                title,
                message,
                duration,
            };
            this.toasts.push(toast);
            setTimeout(() => {
                this.removeToast(toast.id);
            }, duration);
        },
        removeToast(id) {
            const index = this.toasts.findIndex((toast) => toast.id === id);
            if (index > -1) {
                this.toasts.splice(index, 1);
            }
        },
        showSuccess(title, message) {
            this.showToast("success", title, message);
        },
        showError(title, message) {
            this.showToast("error", title, message, 7000);
        },

        handleLogoUpload(event) {
            this.formData.logo = event.target.files[0];
        },

        // Link management methods
        addSocialLink() {
            this.formData.social_links.push({
                platform: "",
                url: "",
                icon: "fab fa-",
            });
        },
        removeSocialLink(index) {
            this.formData.social_links.splice(index, 1);
        },
        addQuickLink() {
            this.formData.quick_links.push({
                text: "",
                url: "",
            });
        },
        removeQuickLink(index) {
            this.formData.quick_links.splice(index, 1);
        },
        addProgram() {
            this.formData.programs.push({
                text: "",
                url: "",
            });
        },
        removeProgram(index) {
            this.formData.programs.splice(index, 1);
        },

        async fetchSections() {
            this.isLoading = true;
            try {
                const response = await axios.get("/admin/footer-sections");
                this.sections = response.data;
                this.showSuccess(
                    "Sections Loaded",
                    `Successfully loaded ${response.data.length} footer sections`
                );
            } catch (error) {
                console.error("Error fetching sections:", error);
                this.showError(
                    "Loading Failed",
                    "Failed to load footer sections"
                );
            } finally {
                this.isLoading = false;
            }
        },

        resetForm() {
            this.formData = {
                description: "",
                tagline: "",
                social_links: [
                    {
                        platform: "facebook",
                        url: "",
                        icon: "fab fa-facebook-f",
                    },
                    { platform: "twitter", url: "", icon: "fab fa-twitter" },
                    {
                        platform: "instagram",
                        url: "",
                        icon: "fab fa-instagram",
                    },
                    { platform: "youtube", url: "", icon: "fab fa-youtube" },
                ],
                quick_links: [
                    { text: "About Us", url: "#" },
                    { text: "Admissions", url: "#" },
                    { text: "Programs", url: "#" },
                    { text: "Campus Life", url: "#" },
                    { text: "News & Events", url: "#" },
                ],
                programs: [
                    { text: "Islamic Studies", url: "#" },
                    { text: "Cambridge Curriculum", url: "#" },
                    { text: "Hifz Program", url: "#" },
                    { text: "Primary Education", url: "#" },
                    { text: "Secondary Education", url: "#" },
                ],
                address: "",
                phone: "",
                email: "",
                working_hours: "",
                background_color: "#030811",
                text_color: "#ffffff",
                accent_color: "#ff7101",
                link_color: "#cccccc",
                link_hover_color: "#ff7101",
                logo: null,
            };
        },

        closeForm() {
            this.showCreateForm = false;
            this.editingSection = null;
            this.resetForm();
        },

        async saveSection() {
            this.isSaving = true;
            try {
                const formData = new FormData();

                // Append scalar fields
                const scalarFields = [
                    "description",
                    "tagline",
                    "address",
                    "phone",
                    "email",
                    "working_hours",
                    "background_color",
                    "text_color",
                    "accent_color",
                    "link_color",
                    "link_hover_color",
                ];

                scalarFields.forEach((key) => {
                    if (
                        this.formData[key] !== null &&
                        this.formData[key] !== undefined
                    ) {
                        formData.append(key, this.formData[key]);
                    }
                });

                // Handle logo file
                if (this.formData.logo) {
                    formData.append("logo", this.formData.logo);
                }

                // Handle array fields properly
                const arrayFields = ["social_links", "quick_links", "programs"];
                arrayFields.forEach((fieldName) => {
                    if (Array.isArray(this.formData[fieldName])) {
                        this.formData[fieldName].forEach((item, index) => {
                            Object.keys(item).forEach((key) => {
                                formData.append(
                                    `${fieldName}[${index}][${key}]`,
                                    item[key] || ""
                                );
                            });
                        });
                    }
                });

                let response;
                if (this.editingSection) {
                    formData.append("_method", "PUT");
                    response = await axios.post(
                        `/admin/footer-sections/${this.editingSection.id}`,
                        formData,
                        { headers: { "Content-Type": "multipart/form-data" } }
                    );
                } else {
                    response = await axios.post(
                        "/admin/footer-sections",
                        formData,
                        { headers: { "Content-Type": "multipart/form-data" } }
                    );
                }

                await this.fetchSections();
                this.closeForm();
                this.showSuccess(
                    "Section Saved",
                    this.editingSection
                        ? "Footer section updated successfully!"
                        : "Footer section created successfully!"
                );
            } catch (error) {
                console.error("Error saving section:", error);
                this.showError("Save Failed", "Error saving footer section");
            } finally {
                this.isSaving = false;
            }
        },

        editSection(section) {
            this.editingSection = section;
            this.formData = {
                description: section.description || "",
                tagline: section.tagline || "",
                social_links: section.social_links || [],
                quick_links: section.quick_links || [],
                programs: section.programs || [],
                address: section.address || "",
                phone: section.phone || "",
                email: section.email || "",
                working_hours: section.working_hours || "",
                background_color: section.background_color || "#030811",
                text_color: section.text_color || "#ffffff",
                accent_color: section.accent_color || "#ff7101",
                link_color: section.link_color || "#cccccc",
                link_hover_color: section.link_hover_color || "#ff7101",
                logo: null,
            };
        },

        async toggleSection(section) {
            try {
                if (!section.is_active) {
                    await axios.post(
                        `/admin/footer-sections/${section.id}/activate`
                    );
                    this.showSuccess(
                        "Section Activated",
                        `Footer section is now active`
                    );
                } else {
                    await axios.put(`/admin/footer-sections/${section.id}`, {
                        ...section,
                        is_active: false,
                    });
                    this.showSuccess(
                        "Section Deactivated",
                        "Footer section has been hidden"
                    );
                }
                await this.fetchSections();
            } catch (error) {
                console.error("Error toggling section:", error);
                this.showError(
                    "Status Update Failed",
                    "Could not change footer status"
                );
            }
        },

        async deleteSection(id) {
            if (
                confirm("Are you sure you want to delete this footer section?")
            ) {
                try {
                    await axios.delete(`/admin/footer-sections/${id}`);
                    await this.fetchSections();
                    this.showSuccess(
                        "Section Deleted",
                        "Footer section deleted successfully"
                    );
                } catch (error) {
                    console.error("Error deleting section:", error);
                    this.showError(
                        "Delete Failed",
                        "Could not delete footer section"
                    );
                }
            }
        },

        getSectionPreviewStyle(section) {
            return {
                background: section.background_color,
                color: section.text_color,
                padding: "15px",
                borderRadius: "8px",
                minHeight: "120px",
            };
        },

        formatDate(dateString) {
            return new Date(dateString).toLocaleDateString();
        },
    },

    mounted() {
        this.fetchSections();
    },
};
</script>

<style scoped>
.toast-container {
    position: fixed;
    top: 20px;
    right: 20px;
    z-index: 9999;
    display: flex;
    flex-direction: column;
    gap: 12px;
    max-width: 400px;
}
.toast {
    display: flex;
    align-items: flex-start;
    gap: 12px;
    padding: 16px;
    background: white;
    border-radius: 8px;
    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.12);
    border-left: 4px solid;
    cursor: pointer;
    transform: translateX(400px);
    animation: slideIn 0.3s ease-out forwards;
    transition: all 0.2s ease;
}
.toast:hover {
    transform: translateX(0) scale(1.02);
    box-shadow: 0 12px 40px rgba(0, 0, 0, 0.15);
}
.toast-success {
    border-left-color: #10b981;
}
.toast-error {
    border-left-color: #ef4444;
}
.toast-warning {
    border-left-color: #f59e0b;
}
.toast-info {
    border-left-color: #3b82f6;
}
.toast-icon {
    width: 24px;
    height: 24px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    color: white;
    font-size: 14px;
    flex-shrink: 0;
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
    font-size: 14px;
    color: #1f2937;
    margin-bottom: 2px;
}
.toast-message {
    font-size: 13px;
    color: #6b7280;
    line-height: 1.4;
}
.toast-close {
    background: none;
    border: none;
    color: #9ca3af;
    font-size: 18px;
    cursor: pointer;
    padding: 0;
    width: 20px;
    height: 20px;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: color 0.2s;
}
.toast-close:hover {
    color: #6b7280;
}
@keyframes slideIn {
    from {
        transform: translateX(400px);
        opacity: 0;
    }
    to {
        transform: translateX(0);
        opacity: 1;
    }
}

/* Main Container */
.admin-cta-section {
    padding: 24px;
    background: #f8f9fa;
    min-height: 100vh;
    font-family: "Inter", -apple-system, BlinkMacSystemFont, sans-serif;
}

/* Header Styles */
.admin-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 24px;
    padding: 24px;
    background: white;
    border-radius: 12px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}
.header-content h2 {
    margin: 0 0 8px 0;
    color: #1f2937;
    font-size: 24px;
    font-weight: 600;
}
.header-content p {
    margin: 0;
    color: #6b7280;
    font-size: 14px;
}

/* Modal Styles */
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
}
.modal-content {
    background: white;
    border-radius: 12px;
    width: 90%;
    max-width: 900px;
    max-height: 90vh;
    overflow-y: auto;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
}
.modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 20px;
    border-bottom: 1px solid #e5e7eb;
}
.modal-header h3 {
    margin: 0;
    color: #1f2937;
    font-size: 20px;
    font-weight: 600;
}
.close-btn {
    background: none;
    border: none;
    font-size: 24px;
    cursor: pointer;
    color: #666;
}
.close-btn:hover {
    color: #000;
}

/* Form Styles */
.section-form {
    padding: 20px;
}
.form-section {
    margin-bottom: 30px;
    padding: 20px;
    background: #f8f9fa;
    border-radius: 8px;
}
.form-section h4 {
    margin: 0 0 15px 0;
    color: #1f2937;
    font-size: 16px;
    font-weight: 600;
}
.form-row {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 15px;
    margin-bottom: 15px;
}
.form-group {
    display: flex;
    flex-direction: column;
}
.form-group.full-width {
    grid-column: 1 / -1;
}
.form-group label {
    margin-bottom: 5px;
    font-weight: 500;
    color: #374151;
    font-size: 14px;
}
.form-control {
    padding: 10px 12px;
    border: 1px solid #d1d5db;
    border-radius: 6px;
    font-size: 14px;
    transition: all 0.2s;
}
.form-control:focus {
    outline: none;
    border-color: #ff7101;
    box-shadow: 0 0 0 3px rgba(255, 113, 1, 0.1);
}
.color-input {
    height: 40px;
    padding: 2px;
    cursor: pointer;
}
.color-text-input {
    font-family: monospace;
    font-size: 13px;
}
textarea.form-control {
    resize: vertical;
    min-height: 80px;
}

/* Color Input Styles */
.color-input-group {
    display: flex;
    align-items: center;
    gap: 8px;
}
.color-input-group input[type="color"] {
    width: 40px;
    height: 40px;
    padding: 2px;
    border-radius: 6px;
    cursor: pointer;
    flex-shrink: 0;
}
.color-input-group input[type="text"] {
    flex: 1;
    min-width: 0;
}

/* Button Styles */
.button-item {
    background: white;
    padding: 15px;
    border-radius: 8px;
    margin-bottom: 15px;
    border: 1px solid #e5e7eb;
}
.button-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 15px;
}
.button-header h5 {
    margin: 0;
    color: #1f2937;
    font-size: 14px;
}

/* Form Actions */
.form-actions {
    display: flex;
    justify-content: flex-end;
    gap: 10px;
    margin-top: 30px;
    padding-top: 20px;
    border-top: 1px solid #e5e7eb;
}

/* Sections List */
.sections-list {
    margin-top: 30px;
}
.list-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
}
.list-header h3 {
    margin: 0;
    color: #1f2937;
    font-size: 18px;
    font-weight: 600;
}
.view-options {
    display: flex;
    gap: 8px;
}
.sections-container {
    display: grid;
    gap: 20px;
}
.sections-container.view-grid {
    grid-template-columns: repeat(auto-fill, minmax(400px, 1fr));
}
.sections-container.view-list {
    grid-template-columns: 1fr;
}

/* Section Cards */
.section-card {
    background: white;
    border: 2px solid #e5e7eb;
    border-radius: 12px;
    overflow: hidden;
    transition: all 0.2s;
    box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
}
.section-card:hover {
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    border-color: #ff7101;
}
.card-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 16px;
    background: #f9fafb;
    border-bottom: 1px solid #e5e7eb;
}
.section-status {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 14px;
}
.status-indicator {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background: #ef4444;
    transition: all 0.2s;
}
.status-indicator.active {
    background: #10b981;
}
.status-text {
    color: #6b7280;
    font-weight: 500;
}
.card-actions {
    display: flex;
    gap: 8px;
}
.action-btn {
    padding: 6px 12px;
    border: 1px solid #d1d5db;
    border-radius: 6px;
    background: white;
    color: #6b7280;
    cursor: pointer;
    transition: all 0.2s;
    font-size: 12px;
    font-weight: 500;
}
.action-btn:hover:not(:disabled) {
    background: #f3f4f6;
    border-color: #9ca3af;
    color: #374151;
}
.toggle-btn.active {
    background: #10b981;
    color: white;
    border-color: #10b981;
}
.toggle-btn:not(.active) {
    background: #ef4444;
    color: white;
    border-color: #ef4444;
}
.delete-btn:hover:not(:disabled) {
    background: #fef2f2;
    color: #dc2626;
    border-color: #fca5a5;
}

/* Card Content */
.card-content {
    padding: 20px;
}
.preview-buttons {
    display: flex;
    gap: 5px;
    flex-wrap: wrap;
    align-items: center;
}
.preview-btn {
    display: inline-block;
    font-size: 0.75rem;
}
.more-buttons {
    color: #6b7280;
    font-size: 0.8rem;
    font-style: italic;
}
.card-footer {
    padding: 12px 16px;
    background: #f9fafb;
    border-top: 1px solid #e5e7eb;
    display: flex;
    justify-content: space-between;
    align-items: center;
}
.update-time {
    font-size: 12px;
    color: #6b7280;
}

/* Empty State */
.empty-state {
    text-align: center;
    padding: 60px 20px;
    color: #6b7280;
}
.empty-icon {
    font-size: 48px;
    margin-bottom: 16px;
}
.empty-state h4 {
    margin: 0 0 8px 0;
    color: #374151;
    font-size: 18px;
}
.empty-state p {
    margin: 0 0 20px 0;
    font-size: 14px;
}

/* Button Styles */
.btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    padding: 10px 16px;
    border: none;
    border-radius: 6px;
    font-weight: 500;
    font-size: 14px;
    cursor: pointer;
    transition: all 0.2s;
    text-decoration: none;
}
.btn-primary {
    background: #ff7101;
    color: white;
}
.btn-primary:hover:not(:disabled) {
    background: #e56500;
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(255, 113, 1, 0.3);
}
.btn-secondary {
    background: #e5e7eb;
    color: #374151;
}
.btn-secondary:hover:not(:disabled) {
    background: #d1d5db;
    transform: translateY(-1px);
}
.btn-outline-primary {
    background: transparent;
    border: 1px solid #ff7101;
    color: #ff7101;
}
.btn-outline-primary:hover:not(:disabled) {
    background: #ff7101;
    color: white;
}
.btn-danger {
    background: #dc3545;
    color: white;
}
.btn-danger:hover:not(:disabled) {
    background: #c82333;
}
.btn-sm {
    padding: 6px 12px;
    font-size: 13px;
}
.btn:disabled {
    opacity: 0.6;
    cursor: not-allowed;
    transform: none !important;
}

/* Responsive Design */
@media (max-width: 1024px) {
    .sections-container.view-grid {
        grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
    }
}
@media (max-width: 768px) {
    .admin-cta-section {
        padding: 16px;
    }
    .admin-header {
        flex-direction: column;
        gap: 16px;
        padding: 20px;
    }
    .sections-container.view-grid {
        grid-template-columns: 1fr;
    }
    .form-row {
        grid-template-columns: 1fr;
    }
    .color-input-group {
        flex-direction: column;
        align-items: stretch;
    }
    .color-input-group input[type="color"] {
        width: 100%;
        height: 40px;
    }
    .card-actions {
        flex-wrap: wrap;
        gap: 4px;
    }
    .action-btn {
        font-size: 11px;
        padding: 4px 8px;
    }
    .modal-content {
        width: 95%;
        margin: 10px;
    }
}
@media (max-width: 480px) {
    .list-header {
        flex-direction: column;
        gap: 12px;
        align-items: stretch;
    }
    .view-options {
        justify-content: center;
    }
    .card-footer {
        flex-direction: column;
        gap: 10px;
    }
}
.toast-close {
    background: none;
    border: none;
    color: #9ca3af;
    font-size: 18px;
    cursor: pointer;
    padding: 0;
    width: 20px;
    height: 20px;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: color 0.2s;
}
.toast-close:hover {
    color: #6b7280;
}
@keyframes slideIn {
    from {
        transform: translateX(400px);
        opacity: 0;
    }
    to {
        transform: translateX(0);
        opacity: 1;
    }
}

.admin-footer-section {
    padding: 24px;
    background: #f8f9fa;
    min-height: 100vh;
    font-family: "Inter", -apple-system, BlinkMacSystemFont, sans-serif;
}

.admin-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 24px;
    padding: 24px;
    background: white;
    border-radius: 12px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}
.header-content h2 {
    margin: 0 0 8px 0;
    color: #1f2937;
    font-size: 24px;
    font-weight: 600;
}
.header-content p {
    margin: 0;
    color: #6b7280;
    font-size: 14px;
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
}
.modal-content {
    background: white;
    border-radius: 12px;
    width: 90%;
    max-width: 900px;
    max-height: 90vh;
    overflow-y: auto;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
}
.modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 20px;
    border-bottom: 1px solid #e5e7eb;
}
.modal-header h3 {
    margin: 0;
    color: #1f2937;
    font-size: 20px;
    font-weight: 600;
}
.close-btn {
    background: none;
    border: none;
    font-size: 24px;
    cursor: pointer;
    color: #666;
}
.close-btn:hover {
    color: #000;
}

.section-form {
    padding: 20px;
}
.form-section {
    margin-bottom: 30px;
    padding: 20px;
    background: #f8f9fa;
    border-radius: 8px;
}
.form-section h4 {
    margin: 0 0 15px 0;
    color: #1f2937;
    font-size: 16px;
    font-weight: 600;
}
.form-row {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 15px;
    margin-bottom: 15px;
}
.form-group {
    display: flex;
    flex-direction: column;
}
.form-group.full-width {
    grid-column: 1 / -1;
}
.form-group label {
    margin-bottom: 5px;
    font-weight: 500;
    color: #374151;
    font-size: 14px;
}
.form-control {
    padding: 10px 12px;
    border: 1px solid #d1d5db;
    border-radius: 6px;
    font-size: 14px;
    transition: all 0.2s;
}
.form-control:focus {
    outline: none;
    border-color: #ff7101;
    box-shadow: 0 0 0 3px rgba(255, 113, 1, 0.1);
}
.color-input {
    height: 40px;
    padding: 2px;
    cursor: pointer;
}
.color-text-input {
    font-family: monospace;
    font-size: 13px;
}
textarea.form-control {
    resize: vertical;
    min-height: 80px;
}
.text-muted {
    color: #6b7280;
    font-size: 12px;
}

.color-input-group {
    display: flex;
    align-items: center;
    gap: 8px;
}
.color-input-group input[type="color"] {
    width: 40px;
    height: 40px;
    padding: 2px;
    border-radius: 6px;
    cursor: pointer;
    flex-shrink: 0;
}
.color-input-group input[type="text"] {
    flex: 1;
    min-width: 0;
}

.link-item {
    background: white;
    padding: 15px;
    border-radius: 8px;
    margin-bottom: 15px;
    border: 1px solid #e5e7eb;
}

.form-actions {
    display: flex;
    justify-content: flex-end;
    gap: 10px;
    margin-top: 30px;
    padding-top: 20px;
    border-top: 1px solid #e5e7eb;
}

.sections-list {
    margin-top: 30px;
}
.list-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
}
.list-header h3 {
    margin: 0;
    color: #1f2937;
    font-size: 18px;
    font-weight: 600;
}
.view-options {
    display: flex;
    gap: 8px;
}
.sections-container {
    display: grid;
    gap: 20px;
}
.sections-container.view-grid {
    grid-template-columns: repeat(auto-fill, minmax(400px, 1fr));
}
.sections-container.view-list {
    grid-template-columns: 1fr;
}

.section-card {
    background: white;
    border: 2px solid #e5e7eb;
    border-radius: 12px;
    overflow: hidden;
    transition: all 0.2s;
    box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
}
.section-card:hover {
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    border-color: #ff7101;
}
.card-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 16px;
    background: #f9fafb;
    border-bottom: 1px solid #e5e7eb;
}
.section-status {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 14px;
}
.status-indicator {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background: #ef4444;
    transition: all 0.2s;
}
.status-indicator.active {
    background: #10b981;
}
.status-text {
    color: #6b7280;
    font-weight: 500;
}
.card-actions {
    display: flex;
    gap: 8px;
}
.action-btn {
    padding: 6px 12px;
    border: 1px solid #d1d5db;
    border-radius: 6px;
    background: white;
    color: #6b7280;
    cursor: pointer;
    transition: all 0.2s;
    font-size: 12px;
    font-weight: 500;
}
.action-btn:hover:not(:disabled) {
    background: #f3f4f6;
    border-color: #9ca3af;
    color: #374151;
}
.toggle-btn.active {
    background: #10b981;
    color: white;
    border-color: #10b981;
}
.toggle-btn:not(.active) {
    background: #ef4444;
    color: white;
    border-color: #ef4444;
}
.delete-btn:hover:not(:disabled) {
    background: #fef2f2;
    color: #dc2626;
    border-color: #fca5a5;
}

.card-content {
    padding: 20px;
}
.preview-tagline {
    font-weight: 600;
    font-size: 1.1rem;
    margin-bottom: 8px;
}
.preview-description {
    font-size: 0.9rem;
    margin-bottom: 12px;
    opacity: 0.9;
}
.preview-contact {
    display: flex;
    flex-direction: column;
    gap: 4px;
}
.preview-contact small {
    font-size: 0.8rem;
    opacity: 0.8;
}
.card-footer {
    padding: 12px 16px;
    background: #f9fafb;
    border-top: 1px solid #e5e7eb;
    display: flex;
    justify-content: space-between;
    align-items: center;
}
.update-time {
    font-size: 12px;
    color: #6b7280;
}

.empty-state {
    text-align: center;
    padding: 60px 20px;
    color: #6b7280;
}
.empty-icon {
    font-size: 48px;
    margin-bottom: 16px;
}
.empty-state h4 {
    margin: 0 0 8px 0;
    color: #374151;
    font-size: 18px;
}
.empty-state p {
    margin: 0 0 20px 0;
    font-size: 14px;
}

.btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    padding: 10px 16px;
    border: none;
    border-radius: 6px;
    font-weight: 500;
    font-size: 14px;
    cursor: pointer;
    transition: all 0.2s;
    text-decoration: none;
}
.btn-primary {
    background: #ff7101;
    color: white;
}
.btn-primary:hover:not(:disabled) {
    background: #e56500;
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(255, 113, 1, 0.3);
}
.btn-secondary {
    background: #e5e7eb;
    color: #374151;
}
.btn-secondary:hover:not(:disabled) {
    background: #d1d5db;
    transform: translateY(-1px);
}
.btn-outline-primary {
    background: transparent;
    border: 1px solid #ff7101;
    color: #ff7101;
}
.btn-outline-primary:hover:not(:disabled) {
    background: #ff7101;
    color: white;
}
.btn-danger {
    background: #dc3545;
    color: white;
}
.btn-danger:hover:not(:disabled) {
    background: #c82333;
}
.btn-sm {
    padding: 6px 12px;
    font-size: 13px;
}
.btn:disabled {
    opacity: 0.6;
    cursor: not-allowed;
    transform: none !important;
}

@media (max-width: 1024px) {
    .sections-container.view-grid {
        grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
    }
}
@media (max-width: 768px) {
    .admin-footer-section {
        padding: 16px;
    }
    .admin-header {
        flex-direction: column;
        gap: 16px;
        padding: 20px;
    }
    .sections-container.view-grid {
        grid-template-columns: 1fr;
    }
    .form-row {
        grid-template-columns: 1fr;
    }
    .color-input-group {
        flex-direction: column;
        align-items: stretch;
    }
    .color-input-group input[type="color"] {
        width: 100%;
        height: 40px;
    }
    .card-actions {
        flex-wrap: wrap;
        gap: 4px;
    }
    .action-btn {
        font-size: 11px;
        padding: 4px 8px;
    }
    .modal-content {
        width: 95%;
        margin: 10px;
    }
}
@media (max-width: 480px) {
    .list-header {
        flex-direction: column;
        gap: 12px;
        align-items: stretch;
    }
    .view-options {
        justify-content: center;
    }
    .card-footer {
        flex-direction: column;
        gap: 10px;
    }
}

.container {
    position: fixed;
    top: 20px;
    right: 20px;
    z-index: 9999;
    display: flex;
    flex-direction: column;
    gap: 12px;
    max-width: 400px;
}
.toast {
    display: flex;
    align-items: flex-start;
    gap: 12px;
    padding: 16px;
    background: white;
    border-radius: 8px;
    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.12);
    border-left: 4px solid;
    cursor: pointer;
    transform: translateX(400px);
    animation: slideIn 0.3s ease-out forwards;
    transition: all 0.2s ease;
}
.toast:hover {
    transform: translateX(0) scale(1.02);
    box-shadow: 0 12px 40px rgba(0, 0, 0, 0.15);
}
.toast-success {
    border-left-color: #10b981;
}
.toast-error {
    border-left-color: #ef4444;
}
.toast-warning {
    border-left-color: #f59e0b;
}
.toast-info {
    border-left-color: #3b82f6;
}
.toast-icon {
    width: 24px;
    height: 24px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    color: white;
    font-size: 14px;
    flex-shrink: 0;
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
    font-size: 14px;
    color: #1f2937;
    margin-bottom: 2px;
}
.toast-message {
    font-size: 13px;
    color: #6b7280;
    line-height: 1.4;
}
</style>
