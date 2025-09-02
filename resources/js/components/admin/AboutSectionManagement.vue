<template>
    <div class="admin-about-section">
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
                <h2>About Section Management</h2>
                <p>Manage your about sections and their display settings</p>
            </div>
            <button
                class="btn btn-primary"
                @click="showCreateForm = true"
                :disabled="isLoading"
            >
                <i class="fas fa-plus"></i> Add New Section
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
                        {{ editingSection ? "Edit" : "Create" }} About Section
                    </h3>
                    <button @click="closeForm" class="close-btn">
                        &times;
                    </button>
                </div>
                <form @submit.prevent="saveSection" class="section-form">
                    <div class="form-row">
                        <div class="form-group full-width">
                            <label>Title</label>
                            <input
                                type="text"
                                v-model="formData.title"
                                required
                                class="form-control"
                            />
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group full-width">
                            <label>Description 1</label>
                            <textarea
                                v-model="formData.description_1"
                                required
                                rows="3"
                                class="form-control"
                            ></textarea>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group full-width">
                            <label>Description 2</label>
                            <textarea
                                v-model="formData.description_2"
                                required
                                rows="3"
                                class="form-control"
                            ></textarea>
                        </div>
                    </div>
                    <!-- Style Settings -->
                    <div class="form-section">
                        <h4>Text Styling</h4>
                        <div class="form-row">
                            <div class="form-group">
                                <label>Background Color</label>
                                <div class="color-input-group">
                                    <input
                                        type="color"
                                        v-model="formData.background_color"
                                        class="form-control color-input"
                                    />
                                    <span class="color-value">{{
                                        formData.background_color
                                    }}</span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Title Color</label>
                                <div class="color-input-group">
                                    <input
                                        type="color"
                                        v-model="formData.title_color"
                                        class="form-control color-input"
                                    />
                                    <span class="color-value">{{
                                        formData.title_color
                                    }}</span>
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
                                    <span class="color-value">{{
                                        formData.text_color
                                    }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label>Title Font Size</label>
                                <div class="input-with-unit">
                                    <input
                                        type="text"
                                        v-model="formData.title_font_size"
                                        placeholder="e.g., 2.6rem"
                                        class="form-control"
                                    />
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Title Font Weight</label>
                                <select
                                    v-model="formData.title_font_weight"
                                    class="form-control"
                                >
                                    <option value="300">Light</option>
                                    <option value="400">Normal</option>
                                    <option value="500">Medium</option>
                                    <option value="600">Semi Bold</option>
                                    <option value="700">Bold</option>
                                    <option value="800">Extra Bold</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Text Font Size</label>
                                <div class="input-with-unit">
                                    <input
                                        type="text"
                                        v-model="formData.text_font_size"
                                        placeholder="e.g., 1.3rem"
                                        class="form-control"
                                    />
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Right Side Settings -->
                    <div class="form-section">
                        <h4>Right Side Display</h4>
                        <div class="form-row">
                            <div class="form-group">
                                <label>Display Type</label>
                                <select
                                    v-model="formData.right_side_type"
                                    class="form-control"
                                    @change="onRightSideTypeChange"
                                >
                                    <option value="gradient">
                                        Gradient Background
                                    </option>
                                    <option value="image">Image</option>
                                </select>
                            </div>
                        </div>
                        <div
                            v-if="formData.right_side_type === 'gradient'"
                            class="form-row"
                        >
                            <div class="form-group">
                                <label>Gradient Start Color</label>
                                <div class="color-input-group">
                                    <input
                                        type="color"
                                        v-model="formData.gradient_start"
                                        class="form-control color-input"
                                    />
                                    <span class="color-value">{{
                                        formData.gradient_start
                                    }}</span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Gradient End Color</label>
                                <div class="color-input-group">
                                    <input
                                        type="color"
                                        v-model="formData.gradient_end"
                                        class="form-control color-input"
                                    />
                                    <span class="color-value">{{
                                        formData.gradient_end
                                    }}</span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Gradient Direction</label>
                                <select
                                    v-model="formData.gradient_direction"
                                    class="form-control"
                                >
                                    <option value="90deg">Top to Bottom</option>
                                    <option value="45deg">
                                        Top Left to Bottom Right
                                    </option>
                                    <option value="135deg">
                                        Top Right to Bottom Left
                                    </option>
                                    <option value="180deg">
                                        Left to Right
                                    </option>
                                    <option value="270deg">
                                        Bottom to Top
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div
                            v-if="formData.right_side_type === 'gradient'"
                            class="form-row"
                        >
                            <div class="form-group">
                                <label>Icon/Emoji</label>
                                <input
                                    type="text"
                                    v-model="formData.image_icon"
                                    placeholder="📚"
                                    class="form-control"
                                />
                            </div>
                        </div>
                        <div
                            v-if="formData.right_side_type === 'image'"
                            class="form-row"
                        >
                            <div class="form-group full-width">
                                <label>Upload Image</label>
                                <input
                                    type="file"
                                    @change="onImageChange"
                                    accept="image/*"
                                    class="form-control"
                                />
                                <div v-if="imagePreview" class="image-preview">
                                    <img :src="imagePreview" alt="Preview" />
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Statistics Section -->
                    <div class="form-section">
                        <h4>Statistics</h4>
                        <div
                            v-for="(stat, index) in formData.stats"
                            :key="index"
                            class="stat-item"
                        >
                            <div class="stat-header">
                                <h5>Stat {{ index + 1 }}</h5>
                                <button
                                    type="button"
                                    @click="removeStat(index)"
                                    class="btn btn-danger btn-sm"
                                >
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                            <div class="form-row">
                                <div class="form-group">
                                    <label>Value</label>
                                    <input
                                        type="text"
                                        v-model="stat.value"
                                        placeholder="500+"
                                        class="form-control"
                                    />
                                </div>
                                <div class="form-group">
                                    <label>Label</label>
                                    <input
                                        type="text"
                                        v-model="stat.label"
                                        placeholder="Students"
                                        class="form-control"
                                    />
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group">
                                    <label>Number Color</label>
                                    <div class="color-input-group">
                                        <input
                                            type="color"
                                            v-model="stat.number_color"
                                            class="form-control color-input"
                                        />
                                        <span class="color-value">{{
                                            stat.number_color
                                        }}</span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>Label Color</label>
                                    <div class="color-input-group">
                                        <input
                                            type="color"
                                            v-model="stat.label_color"
                                            class="form-control color-input"
                                        />
                                        <span class="color-value">{{
                                            stat.label_color
                                        }}</span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>Number Font Size</label>
                                    <div class="input-with-unit">
                                        <input
                                            type="text"
                                            v-model="stat.number_font_size"
                                            placeholder="2rem"
                                            class="form-control"
                                        />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>Label Font Size</label>
                                    <div class="input-with-unit">
                                        <input
                                            type="text"
                                            v-model="stat.label_font_size"
                                            placeholder="0.9rem"
                                            class="form-control"
                                        />
                                    </div>
                                </div>
                            </div>
                        </div>
                        <button
                            type="button"
                            @click="addStat"
                            class="btn btn-secondary"
                        >
                            <i class="fas fa-plus"></i> Add Stat
                        </button>
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
                            Section
                        </button>
                    </div>
                </form>
            </div>
        </div>
        <!-- Sections List -->
        <div class="sections-list">
            <div class="list-header">
                <h3>Sections ({{ sections.length }})</h3>
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
                    v-for="(section, index) in sections"
                    :key="section.id"
                    class="section-item"
                    :class="{
                        'is-active': section.is_active,
                        'is-dragging': draggedIndex === index,
                        'drag-over':
                            dragOverIndex === index && draggedIndex !== index,
                    }"
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
                                    :title="
                                        section.is_active
                                            ? 'Deactivate'
                                            : 'Activate'
                                    "
                                >
                                    {{ section.is_active ? "Hide" : "Show" }}
                                </button>
                                <button
                                    class="action-btn move-btn"
                                    @click="moveUp(index)"
                                    :disabled="index === 0"
                                    title="Move Up"
                                >
                                    ↑
                                </button>
                                <button
                                    class="action-btn move-btn"
                                    @click="moveDown(index)"
                                    :disabled="index === sections.length - 1"
                                    title="Move Down"
                                >
                                    ↓
                                </button>
                                <button
                                    class="action-btn delete-btn"
                                    @click="deleteSection(section.id)"
                                    :disabled="section.is_active"
                                    title="Delete"
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
                                    <h3 :style="getPreviewTitleStyle(section)">
                                        {{ section.title }}
                                    </h3>
                                    <p :style="getPreviewTextStyle(section)">
                                        {{
                                            section.description_1.substring(
                                                0,
                                                100
                                            )
                                        }}...
                                    </p>
                                </div>
                                <div
                                    class="preview-right"
                                    :style="getPreviewRightStyle(section)"
                                >
                                    <img
                                        v-if="
                                            section.right_side_type ===
                                                'image' && section.image_url
                                        "
                                        :src="section.image_url"
                                        alt="Section image"
                                        class="preview-image"
                                    />
                                    <span v-else class="preview-icon">{{
                                        section.image_icon
                                    }}</span>
                                </div>
                            </div>
                            <div class="section-stats">
                                <div class="stats-preview">
                                    <div
                                        v-for="stat in section.stats.slice(
                                            0,
                                            2
                                        )"
                                        :key="stat.id"
                                        class="stat-preview"
                                    >
                                        <span
                                            :style="{
                                                color: stat.number_color,
                                            }"
                                            >{{ stat.value }}</span
                                        >
                                        <small>{{ stat.label }}</small>
                                    </div>
                                    <span
                                        v-if="section.stats.length > 2"
                                        class="more-stats"
                                    >
                                        +{{ section.stats.length - 2 }} more
                                    </span>
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
                <div class="empty-icon">📝</div>
                <h4>No sections yet</h4>
                <p>Get started by adding your first about section</p>
                <button
                    class="btn btn-primary"
                    @click="showCreateForm = true"
                    :disabled="isLoading"
                >
                    + Add Your First Section
                </button>
            </div>
        </div>
    </div>
</template>

<script>
import axios from "axios";
export default {
    name: "AboutSectionAdmin",
    data() {
        return {
            sections: [],
            showCreateForm: false,
            editingSection: null,
            imagePreview: null,
            selectedImage: null,
            isLoading: false,
            isSaving: false,
            viewMode: "grid",
            toasts: [],
            toastIdCounter: 0,
            draggedIndex: null,
            dragOverIndex: null,
            formData: {
                title: "",
                description_1: "",
                description_2: "",
                background_color: "#f8fbfe",
                title_color: "#102e4a",
                title_font_size: "2.6rem",
                title_font_weight: "700",
                text_color: "#111111",
                text_font_size: "1.3rem",
                text_font_weight: "300",
                gradient_start: "#ff7101",
                gradient_end: "#102e4a",
                gradient_direction: "135deg",
                right_side_type: "gradient",
                image_icon: "📚",
                stats: [],
            },
        };
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
            // Auto remove after duration
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
        // Helper methods for different toast types
        showSuccess(title, message) {
            this.showToast("success", title, message);
        },
        showError(title, message) {
            this.showToast("error", title, message, 7000); // Keep errors longer
        },
        showWarning(title, message) {
            this.showToast("warning", title, message);
        },
        showInfo(title, message) {
            this.showToast("info", title, message);
        },
        async fetchSections() {
            this.isLoading = true;
            try {
                const response = await axios.get("/admin/about-sections");
                this.sections = response.data;
                this.showSuccess(
                    "Sections Loaded",
                    `Successfully loaded ${response.data.length} sections`
                );
            } catch (error) {
                console.error("Error fetching sections:", error);
                this.showError(
                    "Loading Failed",
                    "Failed to load sections from server"
                );
            } finally {
                this.isLoading = false;
            }
        },
        resetForm() {
            this.formData = {
                title: "",
                description_1: "",
                description_2: "",
                background_color: "#f8fbfe",
                title_color: "#102e4a",
                title_font_size: "2.6rem",
                title_font_weight: "700",
                text_color: "#111111",
                text_font_size: "1.3rem",
                text_font_weight: "300",
                gradient_start: "#ff7101",
                gradient_end: "#102e4a",
                gradient_direction: "135deg",
                right_side_type: "gradient",
                image_icon: "📚",
                stats: [],
            };
            this.imagePreview = null;
            this.selectedImage = null;
        },
        closeForm() {
            this.showCreateForm = false;
            this.editingSection = null;
            this.resetForm();
        },
        addStat() {
            this.formData.stats.push({
                value: "",
                label: "",
                number_color: "#ff7101",
                label_color: "#111111",
                number_font_size: "2rem",
                label_font_size: "0.9rem",
            });
        },
        removeStat(index) {
            this.formData.stats.splice(index, 1);
        },
        onImageChange(event) {
            const file = event.target.files[0];
            if (file) {
                this.selectedImage = file;
                const reader = new FileReader();
                reader.onload = (e) => {
                    this.imagePreview = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        },
        onRightSideTypeChange() {
            if (this.formData.right_side_type === "gradient") {
                this.imagePreview = null;
                this.selectedImage = null;
            }
        },
        async saveSection() {
            this.isSaving = true;
            try {
                const formData = new FormData();

                // Append all form fields
                Object.keys(this.formData).forEach((key) => {
                    if (key === "stats") {
                        // Convert stats array to JSON string for form data
                        formData.append(
                            key,
                            JSON.stringify(this.formData.stats)
                        );
                    } else {
                        formData.append(key, this.formData[key]);
                    }
                });

                // Append image if selected
                if (this.selectedImage) {
                    formData.append("image", this.selectedImage);
                }

                let response;
                if (this.editingSection) {
                    // For updates, we need to send PUT method
                    formData.append("_method", "PUT");
                    response = await axios.post(
                        `/admin/about-sections/${this.editingSection.id}`,
                        formData,
                        {
                            headers: {
                                "Content-Type": "multipart/form-data",
                            },
                        }
                    );
                } else {
                    response = await axios.post(
                        "/admin/about-sections",
                        formData,
                        {
                            headers: {
                                "Content-Type": "multipart/form-data",
                            },
                        }
                    );
                }

                // Refresh the sections list
                await this.fetchSections();
                this.closeForm();
                this.showSuccess(
                    "Section Saved",
                    this.editingSection
                        ? "Section updated successfully!"
                        : "Section created successfully!"
                );
            } catch (error) {
                console.error("Error saving section:", error);
                let errorMessage = "Error saving section. Please try again.";
                if (error.response?.data?.message) {
                    errorMessage = error.response.data.message;
                }
                this.showError("Save Failed", errorMessage);
            } finally {
                this.isSaving = false;
            }
        },
        editSection(section) {
            this.editingSection = section;
            // Create a deep copy of the section data
            this.formData = { ...section };

            // Handle stats properly - ensure it's an array
            if (section.stats && Array.isArray(section.stats)) {
                this.formData.stats = section.stats.map((stat) => ({
                    ...stat,
                }));
            } else {
                this.formData.stats = [];
            }

            // Set image preview if exists
            if (section.image_url) {
                this.imagePreview = section.image_url;
            }
        },
        async toggleSection(section) {
            try {
                // Optimistically update the UI
                const originalStatus = section.is_active;
                section.is_active = !originalStatus;

                if (!originalStatus) {
                    // Activating a section - this will deactivate others
                    await axios.post(
                        `/admin/about-sections/${section.id}/activate`
                    );
                    this.showSuccess(
                        "Section Activated",
                        `"${section.title}" is now active`
                    );
                } else {
                    // Deactivating a section
                    await axios.put(`/admin/about-sections/${section.id}`, {
                        is_active: false,
                    });
                    this.showSuccess(
                        "Section Deactivated",
                        `"${section.title}" has been hidden`
                    );
                }

                // Refresh to get the latest state
                await this.fetchSections();
            } catch (error) {
                console.error("Error toggling section:", error);
                // Revert on error
                section.is_active = !section.is_active;
                this.showError(
                    "Status Update Failed",
                    `Could not change status for "${section.title}"`
                );
            }
        },
        async deleteSection(id) {
            if (confirm("Are you sure you want to delete this section?")) {
                try {
                    await axios.delete(`/admin/about-sections/${id}`);
                    await this.fetchSections();
                    this.showSuccess(
                        "Section Deleted",
                        "Section has been permanently removed"
                    );
                } catch (error) {
                    console.error("Error deleting section:", error);
                    this.showError("Delete Failed", "Could not delete section");
                }
            }
        },
        async moveUp(index) {
            if (index > 0) {
                const temp = this.sections[index];
                this.sections.splice(index, 1);
                this.sections.splice(index - 1, 0, temp);
                await this.updateAllOrders();
                this.showInfo("Order Updated", `"${temp.title}" moved up`);
            }
        },
        async moveDown(index) {
            if (index < this.sections.length - 1) {
                const temp = this.sections[index];
                this.sections.splice(index, 1);
                this.sections.splice(index + 1, 0, temp);
                await this.updateAllOrders();
                this.showInfo("Order Updated", `"${temp.title}" moved down`);
            }
        },
        async updateAllOrders() {
            try {
                const updates = this.sections.map((section, index) => {
                    section.order = index;
                    return axios.put(
                        `/admin/about-sections/${section.id}`,
                        section
                    );
                });
                await Promise.all(updates);
            } catch (error) {
                console.error("Error updating order:", error);
                this.showError(
                    "Order Update Failed",
                    "Could not save new section order"
                );
            }
        },
        getSectionPreviewStyle(section) {
            return {
                background: section.background_color,
                padding: "20px",
                borderRadius: "10px",
                display: "grid",
                gridTemplateColumns: "1fr auto",
                gap: "20px",
                alignItems: "center",
            };
        },
        getPreviewTitleStyle(section) {
            return {
                fontSize: "1.2rem",
                color: section.title_color,
                fontWeight: section.title_font_weight,
                margin: "0 0 10px 0",
            };
        },
        getPreviewTextStyle(section) {
            return {
                fontSize: "0.9rem",
                color: section.text_color,
                fontWeight: section.text_font_weight,
                margin: "0",
            };
        },
        getPreviewRightStyle(section) {
            const style = {
                width: "80px",
                height: "80px",
                borderRadius: "10px",
                display: "flex",
                alignItems: "center",
                justifyContent: "center",
                fontSize: "2rem",
                color: "#fff",
            };
            if (section.right_side_type === "gradient") {
                style.background = `linear-gradient(${section.gradient_direction}, ${section.gradient_start}, ${section.gradient_end})`;
            } else {
                style.background = "#f0f0f0";
                style.color = "#333";
            }
            return style;
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
.admin-about-section {
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
}
.color-value {
    font-size: 12px;
    color: #6b7280;
    font-family: monospace;
}
.input-with-unit {
    position: relative;
}
.input-with-unit input {
    padding-right: 40px;
}
/* Statistics Styles */
.stat-item {
    background: white;
    padding: 15px;
    border-radius: 8px;
    margin-bottom: 15px;
    border: 1px solid #e5e7eb;
}
.stat-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 15px;
}
.stat-header h5 {
    margin: 0;
    color: #1f2937;
    font-size: 14px;
}
/* Image Preview */
.image-preview {
    margin-top: 10px;
}
.image-preview img {
    max-width: 200px;
    max-height: 150px;
    object-fit: cover;
    border-radius: 5px;
    border: 1px solid #ddd;
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
/* Sections List Styles */
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
.section-item {
    transition: all 0.3s ease;
    position: relative;
}
.section-card {
    background: white;
    border: 2px solid #e5e7eb;
    border-radius: 12px;
    overflow: hidden;
    transition: all 0.2s;
    box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
    position: relative;
}
.section-card:hover {
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    border-color: #ff7101;
}
/* Card Content */
.section-card .card-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 16px;
    background: #f9fafb;
    border-bottom: 1px solid #e5e7eb;
    margin: 0;
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
.move-btn:disabled {
    opacity: 0.5;
    cursor: not-allowed;
}
.delete-btn:hover:not(:disabled) {
    background: #fef2f2;
    color: #dc2626;
    border-color: #fca5a5;
}
.card-content {
    padding: 20px;
}
.section-preview {
    padding: 20px;
    margin-bottom: 20px;
    border-radius: 12px;
    display: grid;
    grid-template-columns: 1fr auto;
    gap: 20px;
    align-items: center;
}
.preview-content {
    min-width: 0;
}
.preview-content h3 {
    margin: 0 0 10px 0;
    font-size: 18px;
    font-weight: 600;
}
.preview-content p {
    margin: 0;
    font-size: 14px;
    line-height: 1.5;
    opacity: 0.9;
}
.preview-right {
    width: 80px;
    height: 80px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2rem;
}
.preview-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
    border-radius: 5px;
}
.section-stats {
    margin-bottom: 20px;
}
.stats-preview {
    display: flex;
    gap: 20px;
    align-items: center;
}
.stat-preview {
    text-align: center;
}
.stat-preview span {
    display: block;
    font-weight: bold;
    font-size: 1.1rem;
}
.stat-preview small {
    color: #6b7280;
    font-size: 0.8rem;
}
.more-stats {
    color: #6b7280;
    font-size: 0.9rem;
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
    .admin-about-section {
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
    .section-preview {
        grid-template-columns: 1fr;
    }
}
</style>
