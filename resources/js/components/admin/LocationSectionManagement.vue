<template>
    <div class="admin-location-section">
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
                <button class="toast-close" @click.stop="removeToast(toast.id)">
                    ×
                </button>
            </div>
        </div>

        <!-- Header Section -->
        <div class="admin-header">
            <div class="header-content">
                <h2>Location Section Management</h2>
                <p>
                    Manage your campus location information, contact details,
                    and customize the appearance
                </p>
            </div>
            <div class="header-actions">
                <button
                    class="btn btn-primary"
                    @click="showCreateForm = true"
                    :disabled="isLoading"
                >
                    <i class="fas fa-plus"></i>
                    <span>Create New Section</span>
                </button>
            </div>
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
                        {{ editingSection ? "Edit" : "Create" }} Location
                        Section
                    </h3>
                    <button @click="closeForm" class="close-btn">
                        &times;
                    </button>
                </div>

                <div class="modal-body">
                    <form @submit.prevent="saveSection" class="section-form">
                        <!-- Basic Content -->
                        <div class="form-section">
                            <h4><i class="fas fa-edit"></i> Section Content</h4>
                            <div class="form-row">
                                <div class="form-group full-width">
                                    <label>Title</label>
                                    <input
                                        type="text"
                                        v-model="formData.title"
                                        required
                                        class="form-control"
                                        placeholder="e.g., Visit Our Campus"
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
                                        class="form-control"
                                        placeholder="Enter section description"
                                    ></textarea>
                                </div>
                            </div>
                        </div>

                        <!-- Section Styling -->
                        <div class="form-section">
                            <h4>
                                <i class="fas fa-palette"></i> Section Styling
                            </h4>
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
                                            class="form-control color-text-input"
                                            placeholder="#f8fbfe"
                                        />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>Top Padding (px)</label>
                                    <input
                                        type="number"
                                        v-model="formData.padding_top"
                                        min="0"
                                        max="200"
                                        class="form-control"
                                        placeholder="80"
                                    />
                                </div>
                                <div class="form-group">
                                    <label>Bottom Padding (px)</label>
                                    <input
                                        type="number"
                                        v-model="formData.padding_bottom"
                                        min="0"
                                        max="200"
                                        class="form-control"
                                        placeholder="80"
                                    />
                                </div>
                            </div>
                        </div>

                        <!-- Contact Cards Section -->
                        <div class="form-section">
                            <div class="cards-header">
                                <h4>
                                    <i class="fas fa-address-card"></i> Contact
                                    Cards
                                </h4>
                                <button
                                    type="button"
                                    @click="addContactCard"
                                    class="btn btn-secondary btn-sm"
                                >
                                    <i class="fas fa-plus"></i> Add Card
                                </button>
                            </div>

                            <div class="cards-container">
                                <div
                                    v-for="(
                                        card, index
                                    ) in formData.contact_cards"
                                    :key="index"
                                    class="card-item"
                                >
                                    <div class="card-header">
                                        <h5>
                                            <i class="fas fa-id-card"></i>
                                            Contact Card {{ index + 1 }}
                                        </h5>
                                        <div class="card-actions">
                                            <button
                                                type="button"
                                                @click="moveCardUp(index)"
                                                class="btn btn-outline-secondary btn-xs"
                                                :disabled="index === 0"
                                            >
                                                <i class="fas fa-arrow-up"></i>
                                            </button>
                                            <button
                                                type="button"
                                                @click="moveCardDown(index)"
                                                class="btn btn-outline-secondary btn-xs"
                                                :disabled="
                                                    index ===
                                                    formData.contact_cards
                                                        .length -
                                                        1
                                                "
                                            >
                                                <i
                                                    class="fas fa-arrow-down"
                                                ></i>
                                            </button>
                                            <button
                                                type="button"
                                                @click="
                                                    removeContactCard(index)
                                                "
                                                class="btn btn-danger btn-xs"
                                                :disabled="
                                                    formData.contact_cards
                                                        .length <= 1
                                                "
                                            >
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </div>

                                    <div class="form-row">
                                        <div class="form-group">
                                            <label>Icon (Emoji)</label>
                                            <input
                                                type="text"
                                                v-model="card.icon"
                                                maxlength="10"
                                                class="form-control"
                                                placeholder="📍"
                                                required
                                            />
                                        </div>
                                        <div class="form-group flex-2">
                                            <label>Title</label>
                                            <input
                                                type="text"
                                                v-model="card.title"
                                                placeholder="Card Title"
                                                class="form-control"
                                                required
                                            />
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-group full-width">
                                            <label
                                                >Content (HTML Allowed)</label
                                            >
                                            <textarea
                                                v-model="card.content"
                                                placeholder="Card content with HTML (use &lt;br&gt; for line breaks)"
                                                rows="3"
                                                class="form-control"
                                                required
                                            ></textarea>
                                            <small class="form-help"
                                                >You can use HTML tags like
                                                &lt;br&gt; for line
                                                breaks</small
                                            >
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-group">
                                            <label>
                                                <input
                                                    type="checkbox"
                                                    v-model="card.is_active"
                                                    class="form-checkbox"
                                                />
                                                Active
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div
                                v-if="formData.contact_cards.length === 0"
                                class="no-cards"
                            >
                                <p>
                                    No contact cards added yet. Click "Add Card"
                                    to create your first contact card.
                                </p>
                            </div>
                        </div>

                        <!-- Map Section -->
                        <div class="form-section">
                            <h4><i class="fas fa-map"></i> Location Map</h4>
                            <div class="form-row">
                                <div class="form-group full-width">
                                    <label>Map Embed URL</label>
                                    <textarea
                                        v-model="formData.map_embed_url"
                                        rows="3"
                                        class="form-control"
                                        placeholder="Paste your Google Maps embed URL here"
                                        required
                                    ></textarea>
                                    <small class="form-help"
                                        >Get this from Google Maps → Share →
                                        Embed a map</small
                                    >
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group">
                                    <label>Map Width</label>
                                    <input
                                        type="number"
                                        v-model="formData.map_width"
                                        min="300"
                                        max="1200"
                                        class="form-control"
                                        placeholder="600"
                                    />
                                </div>
                                <div class="form-group">
                                    <label>Map Height</label>
                                    <input
                                        type="number"
                                        v-model="formData.map_height"
                                        min="200"
                                        max="800"
                                        class="form-control"
                                        placeholder="450"
                                    />
                                </div>
                                <div class="form-group">
                                    <label>Border Radius</label>
                                    <input
                                        type="text"
                                        v-model="formData.map_border_radius"
                                        class="form-control"
                                        placeholder="15px"
                                    />
                                </div>
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="form-actions">
                            <button
                                type="button"
                                @click="closeForm"
                                class="btn btn-secondary"
                            >
                                <i class="fas fa-times"></i> Cancel
                            </button>
                            <button
                                type="submit"
                                class="btn btn-primary"
                                :disabled="
                                    isSaving ||
                                    formData.contact_cards.length === 0
                                "
                            >
                                <i class="fas fa-save"></i>
                                {{
                                    isSaving
                                        ? "Saving..."
                                        : editingSection
                                        ? "Update Section"
                                        : "Create Section"
                                }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Sections List -->
        <div class="sections-list">
            <div class="list-header">
                <h3>
                    Location Sections
                    <span class="count">({{ sections.length }})</span>
                </h3>
                <div class="list-actions">
                    <button
                        v-if="sections.length > 0"
                        class="btn btn-outline-primary btn-sm"
                        @click="refreshSections"
                        :disabled="isLoading"
                    >
                        <i
                            class="fas fa-sync-alt"
                            :class="{ 'fa-spin': isLoading }"
                        ></i>
                        Refresh
                    </button>
                </div>
            </div>

            <div class="sections-container" v-if="sections.length > 0">
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
                                <span class="status-text">{{
                                    section.is_active ? "Active" : "Inactive"
                                }}</span>
                            </div>
                            <div class="card-actions">
                                <button
                                    class="action-btn toggle-btn"
                                    @click="toggleSection(section)"
                                    :class="{ active: section.is_active }"
                                    :disabled="isLoading"
                                >
                                    {{ section.is_active ? "Hide" : "Show" }}
                                </button>
                                <button
                                    class="action-btn edit-btn"
                                    @click="editSection(section)"
                                    :disabled="isLoading"
                                >
                                    Edit
                                </button>
                                <button
                                    class="action-btn delete-btn"
                                    @click="deleteSection(section.id)"
                                    :disabled="section.is_active || isLoading"
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
                                    <h3>{{ section.title }}</h3>
                                    <p>
                                        {{
                                            section.description.substring(
                                                0,
                                                120
                                            )
                                        }}
                                        <span
                                            v-if="
                                                section.description.length > 120
                                            "
                                            >...</span
                                        >
                                    </p>
                                    <div
                                        class="preview-cards"
                                        v-if="
                                            section.contact_cards &&
                                            section.contact_cards.length
                                        "
                                    >
                                        <div
                                            v-for="card in section.contact_cards.slice(
                                                0,
                                                4
                                            )"
                                            :key="card.id"
                                            class="preview-card"
                                        >
                                            <span class="preview-icon">{{
                                                card.icon
                                            }}</span>
                                            <span class="preview-title">{{
                                                card.title
                                            }}</span>
                                        </div>
                                        <span
                                            v-if="
                                                section.contact_cards.length > 4
                                            "
                                            class="more-cards"
                                        >
                                            +{{
                                                section.contact_cards.length - 4
                                            }}
                                            more
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card-footer">
                            <div class="section-meta">
                                <small class="update-time">
                                    <i class="fas fa-clock"></i>
                                    Last updated:
                                    {{ formatDate(section.updated_at) }}
                                </small>
                                <small class="card-count">
                                    <i class="fas fa-address-card"></i>
                                    {{
                                        section.contact_cards
                                            ? section.contact_cards.length
                                            : 0
                                    }}
                                    cards
                                </small>
                                <small class="map-status">
                                    <i class="fas fa-map"></i>
                                    Map:
                                    {{ section.location_map ? "Yes" : "No" }}
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Empty State -->
            <div v-else class="empty-state">
                <div class="empty-icon">
                    <i class="fas fa-map-marker-alt"></i>
                </div>
                <h4>No location sections yet</h4>
                <p>
                    Create your first location section to showcase your campus
                    location and contact information
                </p>
                <button
                    class="btn btn-primary btn-lg"
                    @click="showCreateForm = true"
                    :disabled="isLoading"
                >
                    <i class="fas fa-plus"></i> Create Your First Section
                </button>
            </div>

            <!-- Loading State -->
            <div
                v-if="isLoading && sections.length === 0"
                class="loading-state"
            >
                <div class="loading-spinner">
                    <i class="fas fa-spinner fa-spin"></i>
                </div>
                <p>Loading location sections...</p>
            </div>
        </div>
    </div>
</template>

<script>
import axios from "axios";

export default {
    name: "LocationSectionAdmin",
    data() {
        return {
            sections: [],
            showCreateForm: false,
            editingSection: null,
            isLoading: false,
            isSaving: false,
            toasts: [],
            toastIdCounter: 0,
            formData: {
                title: "",
                description: "",
                background_color: "#f8fbfe",
                padding_top: 80,
                padding_bottom: 80,
                contact_cards: [],
                map_embed_url: "",
                map_width: 600,
                map_height: 450,
                map_border_radius: "15px",
            },
        };
    },
    computed: {
        hasActiveSection() {
            return this.sections.some((section) => section.is_active);
        },

        totalContactCards() {
            return this.sections.reduce((total, section) => {
                return (
                    total +
                    (section.contact_cards ? section.contact_cards.length : 0)
                );
            }, 0);
        },

        activeSections() {
            return this.sections.filter((section) => section.is_active).length;
        },

        totalStyles() {
            return this.sections.reduce((total, section) => {
                return (
                    total +
                    (section.section_styles ? section.section_styles.length : 0)
                );
            }, 0);
        },
    },
    methods: {
        // Toast notification methods
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

        showWarning(title, message) {
            this.showToast("warning", title, message);
        },

        async fetchSections() {
            this.isLoading = true;
            try {
                const response = await axios.get("/admin/location-sections");
                this.sections = response.data.data || response.data;

                if (this.sections.length > 0) {
                    this.showSuccess(
                        "Sections Loaded",
                        `Successfully loaded ${
                            this.sections.length
                        } location section${
                            this.sections.length === 1 ? "" : "s"
                        }`
                    );
                }
            } catch (error) {
                console.error("Error fetching sections:", error);
                this.showError(
                    "Loading Failed",
                    "Failed to load location sections from server"
                );
            } finally {
                this.isLoading = false;
            }
        },

        async refreshSections() {
            await this.fetchSections();
        },

        resetForm() {
            this.formData = {
                title: "",
                description: "",
                background_color: "#f8fbfe",
                padding_top: 80,
                padding_bottom: 80,
                contact_cards: [
                    {
                        icon: "📍",
                        title: "Address",
                        content: "Your Address Here<br />City, Country",
                        is_active: true,
                    },
                    {
                        icon: "📞",
                        title: "Phone",
                        content: "+1 234 567 8900<br />+1 234 567 8901",
                        is_active: true,
                    },
                    {
                        icon: "✉️",
                        title: "Email",
                        content:
                            "info@yourschool.com<br />contact@yourschool.com",
                        is_active: true,
                    },
                    {
                        icon: "🕒",
                        title: "Hours",
                        content: "Monday - Friday<br />8:00 AM - 5:00 PM",
                        is_active: true,
                    },
                ],
                map_embed_url: "",
                map_width: 600,
                map_height: 450,
                map_border_radius: "15px",
            };
        },

        closeForm() {
            this.showCreateForm = false;
            this.editingSection = null;
            this.resetForm();
        },

        addContactCard() {
            this.formData.contact_cards.push({
                icon: "📍",
                title: "",
                content: "",
                is_active: true,
            });
        },

        removeContactCard(index) {
            if (this.formData.contact_cards.length <= 1) {
                this.showWarning(
                    "Cannot Remove",
                    "At least one contact card is required"
                );
                return;
            }
            this.formData.contact_cards.splice(index, 1);
        },

        moveCardUp(index) {
            if (index > 0) {
                const card = this.formData.contact_cards.splice(index, 1)[0];
                this.formData.contact_cards.splice(index - 1, 0, card);
            }
        },

        moveCardDown(index) {
            if (index < this.formData.contact_cards.length - 1) {
                const card = this.formData.contact_cards.splice(index, 1)[0];
                this.formData.contact_cards.splice(index + 1, 0, card);
            }
        },

        async saveSection() {
            if (this.formData.contact_cards.length === 0) {
                this.showWarning(
                    "Validation Error",
                    "At least one contact card is required"
                );
                return;
            }

            this.isSaving = true;
            try {
                // Structure the data properly for the backend
                const payload = {
                    title: this.formData.title,
                    description: this.formData.description,
                    background_color: this.formData.background_color,
                    padding_top: this.formData.padding_top,
                    padding_bottom: this.formData.padding_bottom,
                    is_active: this.editingSection
                        ? this.editingSection.is_active
                        : false,
                    contact_cards: this.formData.contact_cards,
                    map_embed_url: this.formData.map_embed_url,
                    map_width: this.formData.map_width,
                    map_height: this.formData.map_height,
                    map_border_radius: this.formData.map_border_radius,
                };

                let response;
                if (this.editingSection) {
                    response = await axios.put(
                        `/admin/location-sections/${this.editingSection.id}`,
                        payload
                    );
                } else {
                    response = await axios.post(
                        "/admin/location-sections",
                        payload
                    );
                }

                await this.fetchSections();
                this.closeForm();
                this.showSuccess(
                    "Section Saved",
                    this.editingSection
                        ? "Location section updated successfully!"
                        : "Location section created successfully!"
                );
            } catch (error) {
                console.error("Error saving section:", error);
                let errorMessage =
                    "Error saving location section. Please try again.";

                if (error.response?.data?.message) {
                    errorMessage = error.response.data.message;
                }

                if (error.response?.data?.errors) {
                    const errors = error.response.data.errors;
                    errorMessage = Object.values(errors).flat().join(", ");
                }

                this.showError("Save Failed", errorMessage);
            } finally {
                this.isSaving = false;
            }
        },
        editSection(section) {
            this.editingSection = section;
            this.formData = {
                title: section.title || "",
                description: section.description || "",
                background_color: section.background_color || "#f8fbfe",
                padding_top: section.padding_top || 80,
                padding_bottom: section.padding_bottom || 80,
                contact_cards: section.contact_cards
                    ? section.contact_cards.map((card) => ({
                          icon: card.icon,
                          title: card.title,
                          content: card.content,
                          is_active: card.is_active !== false,
                      }))
                    : [],
                map_embed_url: section.location_map
                    ? section.location_map.embed_url
                    : "",
                map_width: section.location_map
                    ? section.location_map.width
                    : 600,
                map_height: section.location_map
                    ? section.location_map.height
                    : 450,
                map_border_radius: section.location_map
                    ? section.location_map.border_radius
                    : "15px",
            };
        },

        async toggleSection(section) {
            try {
                await axios.put(`/admin/location-sections/${section.id}`, {
                    ...section,
                    is_active: !section.is_active,
                });

                this.showSuccess(
                    "Status Updated",
                    `"${section.title}" is now ${
                        !section.is_active ? "active" : "inactive"
                    }`
                );
                await this.fetchSections();
            } catch (error) {
                console.error("Error toggling section:", error);
                this.showError(
                    "Status Update Failed",
                    `Could not change status for "${section.title}"`
                );
            }
        },

        async deleteSection(id) {
            const section = this.sections.find((s) => s.id === id);
            if (!section) return;

            if (section.is_active) {
                this.showWarning(
                    "Cannot Delete Active Section",
                    "Please deactivate this section first, then delete it."
                );
                return;
            }

            if (
                confirm(
                    `Are you sure you want to delete "${section.title}"? This action cannot be undone.`
                )
            ) {
                try {
                    await axios.delete(`/admin/location-sections/${id}`);
                    await this.fetchSections();
                    this.showSuccess(
                        "Section Deleted",
                        `"${section.title}" has been permanently removed`
                    );
                } catch (error) {
                    console.error("Error deleting section:", error);
                    this.showError(
                        "Delete Failed",
                        `Could not delete "${section.title}"`
                    );
                }
            }
        },

        getSectionPreviewStyle(section) {
            return {
                padding: "20px",
                borderRadius: "10px",
                minHeight: "150px",
                textAlign: "center",
                background: section.background_color || "#f8fbfe",
                color: "#333",
            };
        },

        formatDate(dateString) {
            try {
                return new Date(dateString).toLocaleDateString("en-US", {
                    year: "numeric",
                    month: "short",
                    day: "numeric",
                });
            } catch {
                return "Invalid date";
            }
        },
    },

    async mounted() {
        await this.fetchSections();
    },
};
</script>

<style scoped>
/* Toast Notifications */
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

/* Main Component Styles */
.admin-location-section {
    padding: 24px;
    /* background: #f8f9fa; */
    min-height: 100vh;
    font-family: "Inter", -apple-system, BlinkMacSystemFont, sans-serif;
}

.admin-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 18px;
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

.header-actions {
    display: flex;
    gap: 10px;
}

/* Stats Grid */
.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 20px;
    margin-bottom: 30px;
}

.stat-card {
    background: white;
    padding: 20px;
    border-radius: 12px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    display: flex;
    align-items: center;
    gap: 15px;
    transition: all 0.2s;
}

.stat-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

.stat-icon {
    width: 50px;
    height: 50px;
    border-radius: 12px;
    background: linear-gradient(135deg, #ff7101, #e56500);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 20px;
    flex-shrink: 0;
}

.stat-content h3 {
    margin: 0;
    font-size: 24px;
    font-weight: 700;
    color: #1f2937;
}

.stat-content p {
    margin: 4px 0 0 0;
    font-size: 14px;
    color: #6b7280;
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
    padding: 20px;
}

.modal-content {
    background: white;
    border-radius: 12px;
    width: 100%;
    max-width: 1000px;
    max-height: 90vh;
    overflow: hidden;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
    display: flex;
    flex-direction: column;
}

.modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 20px;
    border-bottom: 1px solid #e5e7eb;
    flex-shrink: 0;
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
    width: 32px;
    height: 32px;
    border-radius: 6px;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.2s;
}

.close-btn:hover {
    color: #000;
    background: #f3f4f6;
}

.modal-body {
    overflow-y: auto;
    flex: 1;
    padding: 20px;
}

/* Form Styles */
.section-form {
    display: flex;
    flex-direction: column;
    gap: 25px;
}

.form-section {
    padding: 20px;
    background: #f8f9fa;
    border-radius: 8px;
    border: 1px solid #e5e7eb;
}

.form-section h4 {
    margin: 0 0 15px 0;
    color: #1f2937;
    font-size: 16px;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 8px;
}

.form-section h4 i {
    color: #ff7101;
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

.form-group.flex-2 {
    grid-column: span 2;
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
    background: white;
}

.form-control:focus {
    outline: none;
    border-color: #ff7101;
    box-shadow: 0 0 0 3px rgba(255, 113, 1, 0.1);
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
    border: 1px solid #d1d5db;
}

.color-input-group input[type="text"] {
    flex: 1;
    min-width: 0;
    font-family: monospace;
    font-size: 13px;
}

.form-checkbox {
    width: 16px;
    height: 16px;
    margin-right: 8px;
}

.form-help {
    margin-top: 4px;
    font-size: 12px;
    color: #6b7280;
}

textarea.form-control {
    resize: vertical;
    min-height: 80px;
    font-family: inherit;
}

/* Cards Section */
.cards-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
}

.cards-container {
    display: flex;
    flex-direction: column;
    gap: 15px;
}

.card-item {
    background: white;
    padding: 20px;
    border-radius: 8px;
    border: 1px solid #e5e7eb;
    transition: all 0.2s;
}

.card-item:hover {
    border-color: #ff7101;
    box-shadow: 0 2px 8px rgba(255, 113, 1, 0.1);
}

.card-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 15px;
    padding-bottom: 10px;
    border-bottom: 1px solid #e5e7eb;
}

.card-header h5 {
    margin: 0;
    color: #1f2937;
    font-size: 14px;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 8px;
}

.card-header h5 i {
    color: #ff7101;
}

.card-actions {
    display: flex;
    gap: 4px;
}

.no-cards {
    text-align: center;
    padding: 40px;
    color: #6b7280;
    background: white;
    border-radius: 8px;
    border: 2px dashed #d1d5db;
}

/* Form Actions */
.form-actions {
    display: flex;
    justify-content: flex-end;
    gap: 10px;
    margin-top: 20px;
    padding-top: 20px;
    border-top: 1px solid #e5e7eb;
}

/* Section List */
.sections-list {
    margin-top: 10px;
}

.list-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
    padding: 20px;
    background: white;
    border-radius: 12px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}

.list-header h3 {
    margin: 0;
    color: #1f2937;
    font-size: 18px;
    font-weight: 600;
}

.count {
    color: #6b7280;
    font-weight: 400;
}

.sections-container {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(400px, 1fr));
    gap: 20px;
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

.section-item.is-active .section-card {
    border-color: #10b981;
    box-shadow: 0 4px 12px rgba(16, 185, 129, 0.15);
}

.section-card:hover {
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    transform: translateY(-2px);
}

.card-header {
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

.card-content {
    padding: 20px;
}

.section-preview {
    border-radius: 8px;
    overflow: hidden;
}

.preview-content h3 {
    margin: 0 0 10px 0;
    font-size: 18px;
    font-weight: 600;
}

.preview-content p {
    margin: 0 0 15px 0;
    font-size: 14px;
    line-height: 1.5;
}

.preview-cards {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
    align-items: center;
}

.preview-card {
    display: flex;
    align-items: center;
    gap: 6px;
    padding: 4px 8px;
    background: rgba(255, 255, 255, 0.2);
    border-radius: 12px;
    font-size: 12px;
}

.preview-icon {
    font-size: 14px;
}

.preview-title {
    font-weight: 500;
}

.more-cards {
    color: rgba(255, 255, 255, 0.7);
    font-size: 11px;
    font-style: italic;
}

.card-footer {
    padding: 12px 16px;
    background: #f9fafb;
    border-top: 1px solid #e5e7eb;
}

.section-meta {
    display: flex;
    flex-wrap: wrap;
    gap: 12px;
    font-size: 12px;
    color: #6b7280;
}

.section-meta small {
    display: flex;
    align-items: center;
    gap: 4px;
}

.section-meta i {
    font-size: 10px;
}

/* Empty State */
.empty-state {
    text-align: center;
    padding: 60px 20px;
    color: #6b7280;
    background: white;
    border-radius: 12px;
    border: 2px dashed #d1d5db;
}

.empty-icon {
    font-size: 48px;
    margin-bottom: 16px;
    color: #9ca3af;
}

.empty-state h4 {
    margin: 0 0 8px 0;
    color: #374151;
    font-size: 18px;
    font-weight: 600;
}

.empty-state p {
    margin: 0 0 20px 0;
    font-size: 14px;
    max-width: 400px;
    margin-left: auto;
    margin-right: auto;
}

/* Loading State */
.loading-state {
    text-align: center;
    padding: 60px 20px;
    color: #6b7280;
    background: white;
    border-radius: 12px;
}

.loading-spinner {
    font-size: 32px;
    margin-bottom: 16px;
    color: #ff7101;
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
    white-space: nowrap;
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
    transform: translateY(-1px);
}

.btn-outline-secondary {
    background: transparent;
    border: 1px solid #d1d5db;
    color: #6b7280;
}

.btn-outline-secondary:hover:not(:disabled) {
    background: #f3f4f6;
    border-color: #9ca3af;
}

.btn-danger {
    background: #dc3545;
    color: white;
}

.btn-danger:hover:not(:disabled) {
    background: #c82333;
    transform: translateY(-1px);
}

.btn-sm {
    padding: 6px 12px;
    font-size: 13px;
}

.btn-xs {
    padding: 4px 8px;
    font-size: 12px;
}

.btn-lg {
    padding: 14px 24px;
    font-size: 16px;
}

.btn:disabled {
    opacity: 0.6;
    cursor: not-allowed;
    transform: none !important;
}

/* Action Buttons */
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
    transform: translateY(-1px);
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

.edit-btn:hover:not(:disabled) {
    background: #eff6ff;
    color: #1d4ed8;
    border-color: #60a5fa;
}

.delete-btn:hover:not(:disabled) {
    background: #fef2f2;
    color: #dc2626;
    border-color: #fca5a5;
}

/* Animations */
.fa-spin {
    animation: spin 2s linear infinite;
}

@keyframes spin {
    0% {
        transform: rotate(0deg);
    }
    100% {
        transform: rotate(360deg);
    }
}

/* Responsive Design */
@media (max-width: 1024px) {
    .sections-container {
        grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
    }

    .modal-content {
        max-width: 90vw;
    }

    .stats-grid {
        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
    }
}

@media (max-width: 768px) {
    .admin-location-section {
        padding: 16px;
    }

    .admin-header {
        flex-direction: column;
        gap: 16px;
        padding: 20px;
        text-align: center;
    }

    .header-actions {
        flex-direction: column;
        width: 100%;
    }

    .sections-container {
        grid-template-columns: 1fr;
    }

    .form-row {
        grid-template-columns: 1fr;
    }

    .form-group.flex-2 {
        grid-column: span 1;
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
        flex: 1;
    }

    .modal-overlay {
        padding: 10px;
    }

    .modal-content {
        width: 100%;
        max-height: 95vh;
    }

    .preview-cards {
        flex-direction: column;
        gap: 6px;
        align-items: flex-start;
    }

    .preview-card {
        width: 100%;
        justify-content: flex-start;
    }

    .cards-header {
        flex-direction: column;
        gap: 10px;
        align-items: stretch;
    }

    .toast-container {
        left: 10px;
        right: 10px;
        max-width: none;
    }

    .stats-grid {
        grid-template-columns: repeat(2, 1fr);
    }

    .stat-card {
        padding: 15px;
    }

    .stat-icon {
        width: 40px;
        height: 40px;
        font-size: 16px;
    }

    .stat-content h3 {
        font-size: 20px;
    }
}

@media (max-width: 480px) {
    .list-header {
        flex-direction: column;
        gap: 12px;
        text-align: center;
    }

    .section-meta {
        flex-direction: column;
        gap: 6px;
        align-items: flex-start;
    }

    .btn span {
        display: none;
    }

    .btn i {
        margin: 0;
    }

    .card-header {
        flex-direction: column;
        gap: 10px;
        align-items: stretch;
    }

    .form-actions {
        flex-direction: column-reverse;
    }

    .form-actions .btn {
        width: 100%;
    }

    .stats-grid {
        grid-template-columns: 1fr;
    }
}
</style>
