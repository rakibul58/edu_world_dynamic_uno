<template>
    <div class="admin-gallery">
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
                <h2>Gallery Management</h2>
                <p>Manage gallery items and section settings</p>
            </div>
            <div class="header-actions">
                <button
                    class="btn btn-secondary"
                    @click="showSettingsForm = true"
                    :disabled="isLoading"
                >
                    <i class="fas fa-cog"></i> Section Settings
                </button>
                <button
                    class="btn btn-primary"
                    @click="showCreateForm = true"
                    :disabled="isLoading"
                >
                    <i class="fas fa-plus"></i> Add Gallery Item
                </button>
            </div>
        </div>

        <!-- Create/Edit Item Modal -->
        <div v-if="showCreateForm" class="modal-overlay" @click="closeForm">
            <div class="modal-content item-modal" @click.stop>
                <div class="modal-header">
                    <h3>{{ editingItem ? "Edit" : "Create" }} Gallery Item</h3>
                    <button @click="closeForm" class="close-btn">×</button>
                </div>
                <form @submit.prevent="saveItem" class="item-form">
                    <!-- Basic Information -->
                    <div class="form-section">
                        <h4>Basic Information</h4>
                        <div class="form-row">
                            <div class="form-group">
                                <label>Title *</label>
                                <input
                                    type="text"
                                    v-model="formData.title"
                                    required
                                    class="form-control"
                                    placeholder="Enter title"
                                />
                            </div>
                            <div class="form-group">
                                <label>Icon</label>
                                <input
                                    type="text"
                                    v-model="formData.icon"
                                    class="form-control"
                                    placeholder="🏛️"
                                />
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group full-width">
                                <label>Description (Optional)</label>
                                <textarea
                                    v-model="formData.description"
                                    rows="3"
                                    class="form-control"
                                    placeholder="Enter description"
                                ></textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Background Settings -->
                    <div class="form-section">
                        <h4>Background Settings</h4>
                        <div class="form-row">
                            <div class="form-group">
                                <label>Background Type</label>
                                <select
                                    v-model="formData.background_type"
                                    @change="onBackgroundTypeChange"
                                    class="form-control"
                                >
                                    <option value="gradient">Gradient</option>
                                    <option value="image">Image</option>
                                    <option value="color">Solid Color</option>
                                </select>
                            </div>
                        </div>

                        <!-- Gradient Settings -->
                        <div v-if="formData.background_type === 'gradient'">
                            <div class="form-row">
                                <div class="form-group">
                                    <label>Gradient Start</label>
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
                                    <label>Gradient End</label>
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
                                    <label>Direction</label>
                                    <select
                                        v-model="formData.gradient_direction"
                                        class="form-control"
                                    >
                                        <option value="135deg">
                                            Diagonal ↗
                                        </option>
                                        <option value="90deg">
                                            Vertical ↑
                                        </option>
                                        <option value="180deg">
                                            Horizontal →
                                        </option>
                                        <option value="45deg">
                                            Diagonal ↖
                                        </option>
                                        <option value="225deg">
                                            Diagonal ↙
                                        </option>
                                        <option value="315deg">
                                            Diagonal ↘
                                        </option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Solid Color Settings -->
                        <div v-if="formData.background_type === 'color'">
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
                            </div>
                        </div>

                        <!-- Image Upload -->
                        <div v-if="formData.background_type === 'image'">
                            <div class="form-row">
                                <div class="form-group full-width">
                                    <label>Upload Background Image</label>
                                    <input
                                        type="file"
                                        @change="onImageChange"
                                        accept="image/*"
                                        class="form-control"
                                    />
                                    <div
                                        v-if="imagePreview"
                                        class="image-preview"
                                    >
                                        <img
                                            :src="imagePreview"
                                            alt="Preview"
                                        />
                                        <button
                                            type="button"
                                            @click="removeImage"
                                            class="remove-image"
                                        >
                                            ×
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Typography -->
                    <div class="form-section">
                        <h4>Typography</h4>
                        <div class="form-row">
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
                                <label>Title Font Size</label>
                                <input
                                    type="text"
                                    v-model="formData.title_font_size"
                                    placeholder="1.5rem"
                                    class="form-control"
                                />
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
                        </div>
                        <div class="form-row" v-if="formData.description">
                            <div class="form-group">
                                <label>Description Color</label>
                                <div class="color-input-group">
                                    <input
                                        type="color"
                                        v-model="formData.description_color"
                                        class="form-control color-input"
                                    />
                                    <span class="color-value">{{
                                        formData.description_color
                                    }}</span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Description Font Size</label>
                                <input
                                    type="text"
                                    v-model="formData.description_font_size"
                                    placeholder="1rem"
                                    class="form-control"
                                />
                            </div>
                        </div>
                    </div>

                    <!-- Item Styling -->
                    <div class="form-section">
                        <h4>Item Styling</h4>
                        <div class="form-row">
                            <div class="form-group">
                                <label>Item Height</label>
                                <input
                                    type="text"
                                    v-model="formData.item_height"
                                    placeholder="250px"
                                    class="form-control"
                                />
                            </div>
                            <div class="form-group">
                                <label>Border Radius</label>
                                <input
                                    type="text"
                                    v-model="formData.border_radius"
                                    placeholder="15px"
                                    class="form-control"
                                />
                            </div>
                            <div class="form-group">
                                <label>Icon Size</label>
                                <input
                                    type="text"
                                    v-model="formData.icon_size"
                                    placeholder="3rem"
                                    class="form-control"
                                />
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label>Hover Transform</label>
                                <input
                                    type="text"
                                    v-model="formData.hover_transform"
                                    placeholder="translateY(-5px) scale(1.02)"
                                    class="form-control"
                                />
                            </div>
                            <div class="form-group">
                                <label>Hover Shadow</label>
                                <input
                                    type="text"
                                    v-model="formData.hover_shadow"
                                    placeholder="0 15px 40px rgba(0, 0, 0, 0.2)"
                                    class="form-control"
                                />
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
                                    : editingItem
                                    ? "Update"
                                    : "Create"
                            }}
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Section Settings Modal -->
        <div
            v-if="showSettingsForm"
            class="modal-overlay"
            @click="closeSettingsForm"
        >
            <div class="modal-content settings-modal" @click.stop>
                <div class="modal-header">
                    <h3>Gallery Section Settings</h3>
                    <button @click="closeSettingsForm" class="close-btn">
                        ×
                    </button>
                </div>
                <form
                    @submit.prevent="saveSectionSettings"
                    class="settings-form"
                >
                    <!-- Basic Settings -->
                    <div class="form-section">
                        <h4>Basic Settings</h4>
                        <div class="form-row">
                            <div class="form-group">
                                <label>Section Title</label>
                                <input
                                    type="text"
                                    v-model="sectionSettings.section_title"
                                    required
                                    class="form-control"
                                />
                            </div>
                            <div class="form-group">
                                <label>Text Alignment</label>
                                <select
                                    v-model="sectionSettings.header_text_align"
                                    class="form-control"
                                >
                                    <option value="left">Left</option>
                                    <option value="center">Center</option>
                                    <option value="right">Right</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group full-width">
                                <label>Section Description</label>
                                <textarea
                                    v-model="
                                        sectionSettings.section_description
                                    "
                                    required
                                    rows="3"
                                    class="form-control"
                                ></textarea>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label>
                                    <input
                                        type="checkbox"
                                        v-model="sectionSettings.is_active"
                                    />
                                    Section Active
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Header Typography -->
                    <div class="form-section">
                        <h4>Header Typography</h4>
                        <div class="form-row">
                            <div class="form-group">
                                <label>Title Color</label>
                                <div class="color-input-group">
                                    <input
                                        type="color"
                                        v-model="
                                            sectionSettings.section_title_color
                                        "
                                        class="form-control color-input"
                                    />
                                    <span class="color-value">{{
                                        sectionSettings.section_title_color
                                    }}</span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Title Font Size</label>
                                <input
                                    type="text"
                                    v-model="
                                        sectionSettings.section_title_font_size
                                    "
                                    placeholder="2.5rem"
                                    class="form-control"
                                />
                            </div>
                            <div class="form-group">
                                <label>Title Font Weight</label>
                                <select
                                    v-model="
                                        sectionSettings.section_title_font_weight
                                    "
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
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label>Description Color</label>
                                <div class="color-input-group">
                                    <input
                                        type="color"
                                        v-model="
                                            sectionSettings.section_description_color
                                        "
                                        class="form-control color-input"
                                    />
                                    <span class="color-value">{{
                                        sectionSettings.section_description_color
                                    }}</span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Description Font Size</label>
                                <input
                                    type="text"
                                    v-model="
                                        sectionSettings.section_description_font_size
                                    "
                                    placeholder="1.1rem"
                                    class="form-control"
                                />
                            </div>
                        </div>
                    </div>

                    <!-- Section Background -->
                    <div class="form-section">
                        <h4>Section Background</h4>
                        <div class="form-row">
                            <div class="form-group">
                                <label>Background Color</label>
                                <div class="color-input-group">
                                    <input
                                        type="color"
                                        v-model="
                                            sectionSettings.section_background_color
                                        "
                                        class="form-control color-input"
                                    />
                                    <span class="color-value">{{
                                        sectionSettings.section_background_color
                                    }}</span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Section Padding</label>
                                <input
                                    type="text"
                                    v-model="sectionSettings.section_padding"
                                    placeholder="80px 0"
                                    class="form-control"
                                />
                            </div>
                        </div>
                    </div>

                    <!-- Layout Settings -->
                    <div class="form-section">
                        <h4>Layout Settings</h4>
                        <div class="form-row">
                            <div class="form-group">
                                <label>Grid Columns</label>
                                <input
                                    type="text"
                                    v-model="sectionSettings.grid_columns"
                                    placeholder="repeat(auto-fit, minmax(350px, 1fr))"
                                    class="form-control"
                                />
                            </div>
                            <div class="form-group">
                                <label>Grid Gap</label>
                                <input
                                    type="text"
                                    v-model="sectionSettings.grid_gap"
                                    placeholder="20px"
                                    class="form-control"
                                />
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label>Container Max Width</label>
                                <input
                                    type="text"
                                    v-model="
                                        sectionSettings.container_max_width
                                    "
                                    placeholder="1240px"
                                    class="form-control"
                                />
                            </div>
                            <div class="form-group">
                                <label>Container Padding</label>
                                <input
                                    type="text"
                                    v-model="sectionSettings.container_padding"
                                    placeholder="0 20px"
                                    class="form-control"
                                />
                            </div>
                        </div>
                    </div>

                    <div class="form-actions">
                        <button
                            type="button"
                            @click="closeSettingsForm"
                            class="btn btn-secondary"
                        >
                            Cancel
                        </button>
                        <button
                            type="submit"
                            class="btn btn-primary"
                            :disabled="isSavingSettings"
                        >
                            {{
                                isSavingSettings ? "Saving..." : "Save Settings"
                            }}
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Items List -->
        <div class="items-list">
            <div class="list-header">
                <h3>Gallery Items ({{ items.length }})</h3>
                <p class="drag-info">Drag and drop to reorder items</p>
            </div>

            <div class="items-container" :class="{ 'is-dragging': isDragging }">
                <div
                    v-for="(item, index) in items"
                    :key="item.id"
                    class="item-card"
                    :class="{
                        'is-active': item.is_active,
                        'is-dragging': draggedItem === item,
                    }"
                    draggable="true"
                    @dragstart="onDragStart($event, item, index)"
                    @dragend="onDragEnd"
                    @dragover.prevent
                    @drop="onDrop($event, index)"
                >
                    <div class="drag-handle">⋮⋮</div>

                    <div class="card-header">
                        <div class="item-status">
                            <span
                                class="status-indicator"
                                :class="{ active: item.is_active }"
                            ></span>
                            <span class="status-text">{{
                                item.is_active ? "Active" : "Inactive"
                            }}</span>
                        </div>
                        <div class="card-actions">
                            <div class="reorder-buttons">
                                <button
                                    class="action-btn reorder-btn"
                                    @click="moveUp(index)"
                                    :disabled="index === 0 || isLoading"
                                    title="Move Up"
                                >
                                    ↑
                                </button>
                                <button
                                    class="action-btn reorder-btn"
                                    @click="moveDown(index)"
                                    :disabled="
                                        index === items.length - 1 || isLoading
                                    "
                                    title="Move Down"
                                >
                                    ↓
                                </button>
                            </div>
                            <button
                                class="action-btn toggle-btn"
                                @click="toggleStatus(item)"
                                :class="{ active: item.is_active }"
                            >
                                {{ item.is_active ? "Hide" : "Show" }}
                            </button>
                            <button
                                class="action-btn edit-btn"
                                @click="editItem(item)"
                                title="Edit"
                            >
                                Edit
                            </button>
                            <button
                                class="action-btn delete-btn"
                                @click="deleteItem(item.id)"
                                title="Delete"
                            >
                                Delete
                            </button>
                        </div>
                    </div>

                    <div class="card-content">
                        <div class="item-preview">
                            <div
                                class="preview-image"
                                :style="getItemPreviewStyle(item)"
                            >
                                <img
                                    v-if="
                                        item.background_type === 'image' &&
                                        item.background_image
                                    "
                                    :src="getImageUrl(item.background_image)"
                                    alt="Background image"
                                    class="background-image"
                                />
                                <div
                                    v-if="item.background_type !== 'image'"
                                    class="preview-content"
                                >
                                    <span class="preview-icon">{{
                                        item.icon
                                    }}</span>
                                    <h3 :style="getPreviewTitleStyle(item)">
                                        {{ item.title }}
                                    </h3>
                                    <p
                                        v-if="item.description"
                                        :style="
                                            getPreviewDescriptionStyle(item)
                                        "
                                    >
                                        {{
                                            item.description.substring(0, 100)
                                        }}...
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer">
                        <small class="update-time"
                            >Last updated:
                            {{ formatDateTime(item.updated_at) }}</small
                        >
                    </div>
                </div>
            </div>

            <div v-if="items.length === 0" class="empty-state">
                <div class="empty-icon">🖼️</div>
                <h4>No gallery items yet</h4>
                <p>Get started by adding your first gallery item</p>
                <button
                    class="btn btn-primary"
                    @click="showCreateForm = true"
                    :disabled="isLoading"
                >
                    + Add Your First Item
                </button>
            </div>
        </div>
    </div>
</template>

<script>
import axios from "axios";

export default {
    name: "GalleryAdmin",
    data() {
        return {
            items: [],
            sectionSettings: {
                section_title: "Explore Our Beautiful Campus",
                section_description:
                    "Take a virtual tour of our state-of-the-art facilities designed to inspire learning and spiritual growth.",
                section_title_color: "#102e4a",
                section_title_font_size: "2.5rem",
                section_title_font_weight: "700",
                section_description_color: "#111111",
                section_description_font_size: "1.1rem",
                section_background_color: "#f8fbfe",
                section_padding: "80px 0",
                header_text_align: "center",
                grid_columns: "repeat(auto-fit, minmax(350px, 1fr))",
                grid_gap: "20px",
                container_max_width: "1240px",
                container_padding: "0 20px",
                is_active: true,
            },
            showCreateForm: false,
            showSettingsForm: false,
            editingItem: null,
            imagePreview: null,
            selectedImage: null,
            isLoading: false,
            isSaving: false,
            isSavingSettings: false,
            toasts: [],
            toastIdCounter: 0,

            // Drag and drop
            isDragging: false,
            draggedItem: null,
            draggedIndex: null,

            formData: {
                title: "",
                icon: "🏛️",
                description: "",
                background_type: "gradient",
                gradient_start: "#ff7101",
                gradient_end: "#102e4a",
                gradient_direction: "135deg",
                background_color: "#ff7101",
                title_color: "#ffffff",
                title_font_size: "1.5rem",
                title_font_weight: "600",
                description_color: "#ffffff",
                description_font_size: "1rem",
                item_height: "250px",
                border_radius: "15px",
                icon_size: "3rem",
                hover_transform: "translateY(-5px) scale(1.02)",
                hover_shadow: "0 15px 40px rgba(0, 0, 0, 0.2)",
            },
        };
    },
    methods: {
        // Toast methods
        showToast(type, title, message, duration = 5000) {
            const toast = {
                id: ++this.toastIdCounter,
                type,
                title,
                message,
                duration,
            };
            this.toasts.push(toast);
            setTimeout(() => this.removeToast(toast.id), duration);
        },
        removeToast(id) {
            const index = this.toasts.findIndex((toast) => toast.id === id);
            if (index > -1) this.toasts.splice(index, 1);
        },
        showSuccess(title, message) {
            this.showToast("success", title, message);
        },
        showError(title, message) {
            this.showToast("error", title, message, 7000);
        },

        // Drag and drop methods
        onDragStart(event, item, index) {
            this.isDragging = true;
            this.draggedItem = item;
            this.draggedIndex = index;
            event.dataTransfer.effectAllowed = "move";
        },
        onDragEnd() {
            this.isDragging = false;
            this.draggedItem = null;
            this.draggedIndex = null;
        },
        onDrop(event, dropIndex) {
            event.preventDefault();
            if (this.draggedIndex === null || this.draggedIndex === dropIndex)
                return;

            const item = this.items.splice(this.draggedIndex, 1)[0];
            this.items.splice(dropIndex, 0, item);
            this.updateAllOrders();
        },
        moveUp(index) {
            if (index === 0) return;
            const item = this.items[index];
            const previousItem = this.items[index - 1];
            this.items.splice(index - 1, 2, item, previousItem);
            this.updateAllOrders();
        },
        moveDown(index) {
            if (index === this.items.length - 1) return;
            const item = this.items[index];
            const nextItem = this.items[index + 1];
            this.items.splice(index, 2, nextItem, item);
            this.updateAllOrders();
        },

        // API methods
        async fetchItems() {
            this.isLoading = true;
            try {
                const response = await axios.get("/admin/gallery");
                this.items = response.data;
                this.showSuccess(
                    "Items Loaded",
                    `Successfully loaded ${response.data.length} items`
                );
            } catch (error) {
                console.error("Error fetching items:", error);
                this.showError(
                    "Loading Failed",
                    "Failed to load items from server"
                );
            } finally {
                this.isLoading = false;
            }
        },

        async fetchSectionSettings() {
            try {
                const response = await axios.get("/admin/gallery-settings");
                this.sectionSettings = {
                    ...this.sectionSettings,
                    ...response.data,
                };
            } catch (error) {
                console.error("Error fetching section settings:", error);
                this.showError(
                    "Settings Failed",
                    "Failed to load section settings"
                );
            }
        },

        async saveItem() {
            this.isSaving = true;
            try {
                const formData = new FormData();
                Object.keys(this.formData).forEach((key) => {
                    if (
                        this.formData[key] !== null &&
                        this.formData[key] !== undefined
                    ) {
                        formData.append(key, this.formData[key]);
                    }
                });

                if (this.selectedImage) {
                    formData.append("background_image", this.selectedImage);
                }

                let response;
                if (this.editingItem) {
                    formData.append("_method", "PUT");
                    response = await axios.post(
                        `/admin/gallery/${this.editingItem.id}`,
                        formData,
                        {
                            headers: { "Content-Type": "multipart/form-data" },
                        }
                    );
                } else {
                    response = await axios.post("/admin/gallery", formData, {
                        headers: { "Content-Type": "multipart/form-data" },
                    });
                }

                await this.fetchItems();
                this.closeForm();
                this.showSuccess(
                    "Item Saved",
                    this.editingItem
                        ? "Item updated successfully!"
                        : "Item created successfully!"
                );
            } catch (error) {
                console.error("Error saving item:", error);
                this.showError(
                    "Save Failed",
                    "Error saving item. Please try again."
                );
            } finally {
                this.isSaving = false;
            }
        },

        async saveSectionSettings() {
            this.isSavingSettings = true;
            try {
                const response = await axios.post(
                    "/admin/gallery-settings",
                    this.sectionSettings
                );
                this.sectionSettings = {
                    ...this.sectionSettings,
                    ...response.data,
                };
                this.closeSettingsForm();
                this.showSuccess(
                    "Settings Saved",
                    "Section settings updated successfully!"
                );
            } catch (error) {
                console.error("Error saving settings:", error);
                this.showError(
                    "Settings Save Failed",
                    "Failed to save section settings"
                );
            } finally {
                this.isSavingSettings = false;
            }
        },

        async toggleStatus(item) {
            try {
                await axios.post(`/admin/gallery/${item.id}/toggle-status`);
                await this.fetchItems();
                this.showSuccess(
                    "Status Updated",
                    `"${item.title}" is now ${
                        item.is_active ? "hidden" : "visible"
                    }`
                );
            } catch (error) {
                console.error("Error toggling status:", error);
                this.showError(
                    "Status Update Failed",
                    `Could not change status for "${item.title}"`
                );
            }
        },

        async deleteItem(id) {
            if (confirm("Are you sure you want to delete this item?")) {
                try {
                    await axios.delete(`/admin/gallery/${id}`);
                    await this.fetchItems();
                    this.showSuccess(
                        "Item Deleted",
                        "Item has been permanently removed"
                    );
                } catch (error) {
                    console.error("Error deleting item:", error);
                    this.showError("Delete Failed", "Could not delete item");
                }
            }
        },

        async updateAllOrders() {
            try {
                const updates = this.items.map((item, index) => ({
                    id: item.id,
                    order: index,
                }));
                await axios.post("/admin/gallery/update-order", {
                    items: updates,
                });
            } catch (error) {
                console.error("Error updating order:", error);
                this.showError(
                    "Order Update Failed",
                    "Could not save new item order"
                );
            }
        },

        // Form methods
        editItem(item) {
            this.editingItem = item;
            this.formData = { ...item };
            this.showCreateForm = true;

            if (item.background_type === "image" && item.background_image) {
                this.imagePreview = this.getImageUrl(item.background_image);
            } else {
                this.imagePreview = null;
            }
            this.selectedImage = null;
        },

        closeForm() {
            this.showCreateForm = false;
            this.editingItem = null;
            this.resetForm();
        },

        closeSettingsForm() {
            this.showSettingsForm = false;
        },

        resetForm() {
            this.formData = {
                title: "",
                icon: "🏛️",
                description: "",
                background_type: "gradient",
                gradient_start: "#ff7101",
                gradient_end: "#102e4a",
                gradient_direction: "135deg",
                background_color: "#ff7101",
                title_color: "#ffffff",
                title_font_size: "1.5rem",
                title_font_weight: "600",
                description_color: "#ffffff",
                description_font_size: "1rem",
                item_height: "250px",
                border_radius: "15px",
                icon_size: "3rem",
                hover_transform: "translateY(-5px) scale(1.02)",
                hover_shadow: "0 15px 40px rgba(0, 0, 0, 0.2)",
            };
            this.imagePreview = null;
            this.selectedImage = null;
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

        removeImage() {
            this.imagePreview = null;
            this.selectedImage = null;
            this.formData.background_image = null;
        },

        onBackgroundTypeChange() {
            if (this.formData.background_type !== "image") {
                this.imagePreview = null;
                this.selectedImage = null;
            }
        },

        // Utility methods
        getImageUrl(path) {
            console.log({ path });
            if (!path) return "";

            return `/uploads/image/${path}`;
        },

        formatDateTime(dateString) {
            return new Date(dateString).toLocaleDateString();
        },

        getItemPreviewStyle(item) {
            const style = {
                width: "100%",
                height: item.item_height || "250px",
                borderRadius: item.border_radius || "15px",
                display: "flex",
                alignItems: "center",
                justifyContent: "center",
                position: "relative",
                overflow: "hidden",
            };

            if (item.background_type === "gradient") {
                style.background = `linear-gradient(${item.gradient_direction}, ${item.gradient_start}, ${item.gradient_end})`;
            } else if (item.background_type === "color") {
                style.background = item.background_color;
            } else if (
                item.background_type === "image" &&
                item.background_image
            ) {
                style.backgroundImage = `url('${this.getImageUrl(
                    item.background_image
                )}')`;
                style.backgroundSize = "cover";
                style.backgroundPosition = "center";
            }

            return style;
        },

        getPreviewTitleStyle(item) {
            return {
                color: item.title_color || "#ffffff",
                fontSize: item.title_font_size || "1.5rem",
                fontWeight: item.title_font_weight || "600",
                margin: "10px 0",
                textShadow: "0 2px 4px rgba(0,0,0,0.3)",
            };
        },

        getPreviewDescriptionStyle(item) {
            return {
                color: item.description_color || "#ffffff",
                fontSize: item.description_font_size || "1rem",
                margin: "0",
                lineHeight: "1.5",
                textShadow: "0 1px 2px rgba(0,0,0,0.3)",
            };
        },
    },

    mounted() {
        this.fetchItems();
        this.fetchSectionSettings();
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
    background-color: #10b981;
}
.toast-error .toast-icon {
    background-color: #ef4444;
}
.toast-warning .toast-icon {
    background-color: #f59e0b;
}
.toast-info .toast-icon {
    background-color: #3b82f6;
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

/* Main Admin Styles */
.admin-gallery {
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

.header-actions {
    display: flex;
    gap: 12px;
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

.settings-modal {
    max-width: 1000px;
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
    padding: 4px;
    border-radius: 4px;
    transition: all 0.2s;
}

.close-btn:hover {
    color: #000;
    background: #f3f4f6;
}

/* Form Styles */
.item-form,
.settings-form {
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

.form-control:disabled {
    background: #f3f4f6;
    color: #9ca3af;
}

.form-checkbox {
    margin-right: 8px;
    transform: scale(1.2);
}

textarea.form-control {
    resize: vertical;
    min-height: 80px;
    line-height: 1.5;
}

/* Color Input Styles */
.color-input-group {
    display: flex;
    align-items: center;
    gap: 8px;
}

.color-input {
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
    background: #f3f4f6;
    padding: 2px 6px;
    border-radius: 4px;
}

/* Image Preview */
.image-preview {
    margin-top: 10px;
    position: relative;
    display: inline-block;
}

.image-preview img {
    max-width: 200px;
    max-height: 150px;
    object-fit: cover;
    border-radius: 6px;
    border: 1px solid #d1d5db;
}

.remove-image {
    position: absolute;
    top: -10px;
    right: -10px;
    background: #ef4444;
    color: white;
    border: none;
    border-radius: 50%;
    width: 24px;
    height: 24px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    font-size: 16px;
    font-weight: bold;
}

/* Form Actions */
.form-actions {
    display: flex;
    justify-content: flex-end;
    gap: 12px;
    margin-top: 30px;
    padding-top: 20px;
    border-top: 1px solid #e5e7eb;
}

/* Items List Styles */
.items-list {
    margin-top: 30px;
}

.list-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
    padding: 20px 24px;
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

.drag-info {
    margin: 0;
    font-size: 14px;
    color: #6b7280;
}

/* Items Container */
.items-container {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(400px, 1fr));
    gap: 20px;
}

.items-container.is-dragging {
    background: #f9fafb;
}

/* Item Card Styles */
.item-card {
    background: white;
    border: 2px solid #e5e7eb;
    border-radius: 12px;
    overflow: hidden;
    transition: all 0.2s;
    box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
}

.item-card:hover {
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    border-color: #ff7101;
}

.item-card.is-active {
    border-color: #10b981;
}

.item-card.is-dragging {
    opacity: 0.5;
    transform: rotate(2deg);
}

.drag-handle {
    position: absolute;
    top: 12px;
    left: 12px;
    color: #9ca3af;
    cursor: grab;
    font-size: 16px;
    padding: 4px;
    border-radius: 4px;
    transition: all 0.2s;
}

.drag-handle:hover {
    background: #f3f4f6;
    color: #6b7280;
}

.drag-handle:active {
    cursor: grabbing;
}

.card-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 16px;
    background: #f9fafb;
    border-bottom: 1px solid #e5e7eb;
}

.item-status {
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
    flex-wrap: wrap;
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

.reorder-btn:disabled {
    opacity: 0.5;
    cursor: not-allowed;
}

.delete-btn:hover:not(:disabled) {
    background: #fef2f2;
    color: #dc2626;
    border-color: #fca5a5;
}

.reorder-buttons {
    display: flex;
    /* flex-direction: column; */
    gap: 2px;
    margin-right: 8px;
}

.reorder-btn {
    width: 24px;
    height: 24px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #f8f9fa;
    border: 1px solid #dee2e6;
    border-radius: 4px;
    color: #6c757d;
    font-size: 12px;
    font-weight: bold;
    cursor: pointer;
    transition: all 0.2s ease;
    padding: 0;
}

.reorder-btn:hover:not(:disabled) {
    background: #e9ecef;
    border-color: #adb5bd;
    color: #495057;
    transform: translateY(-1px);
}

.reorder-btn:active:not(:disabled) {
    transform: translateY(0);
    background: #dee2e6;
}

.reorder-btn:disabled {
    background: #f8f9fa;
    border-color: #e9ecef;
    color: #adb5bd;
    cursor: not-allowed;
    opacity: 0.5;
}

/* Card Content */
.card-content {
    padding: 20px;
}

.item-preview {
    display: flex;
    flex-direction: column;
    gap: 15px;
}

.preview-image {
    position: relative;
    overflow: hidden;
}

.background-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.preview-content {
    text-align: center;
}

.preview-icon {
    font-size: 3rem;
    display: block;
    margin-bottom: 10px;
}

.preview-content h3 {
    margin: 0 0 10px 0;
    font-size: 18px;
}

.preview-content p {
    margin: 0;
    line-height: 1.6;
    color: #6b7280;
}

.card-footer {
    padding: 12px 16px;
    background: #f9fafb;
    border-top: 1px solid #e5e7eb;
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
    background: white;
    border-radius: 12px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    grid-column: 1 / -1;
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

.btn:disabled {
    opacity: 0.6;
    cursor: not-allowed;
    transform: none !important;
}

/* Responsive Design */
@media (max-width: 1024px) {
    .items-container {
        grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
    }
}

@media (max-width: 768px) {
    .admin-gallery {
        padding: 16px;
    }

    .admin-header {
        flex-direction: column;
        gap: 16px;
        padding: 20px;
    }

    .header-actions {
        width: 100%;
        justify-content: stretch;
    }

    .header-actions .btn {
        flex: 1;
    }

    .items-container {
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

    .card-footer {
        flex-direction: column;
        gap: 10px;
        text-align: center;
    }

    .toast-container {
        left: 10px;
        right: 10px;
        max-width: none;
    }
}
</style>
