<template>
    <div class="admin-cta-section">
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
                <h2>CTA Section Management</h2>
                <p>
                    Manage your call-to-action sections and customize their
                    appearance
                </p>
            </div>
            <button
                class="btn btn-primary"
                @click="showCreateForm = true"
                :disabled="isLoading"
            >
                <i class="fas fa-plus"></i> Add New CTA Section
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
                        {{ editingSection ? "Edit" : "Create" }} CTA Section
                    </h3>
                    <button @click="closeForm" class="close-btn">
                        &times;
                    </button>
                </div>
                <form @submit.prevent="saveSection" class="section-form">
                    <!-- Basic Content -->
                    <div class="form-section">
                        <h4>Content</h4>
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
                                <label>Description</label>
                                <textarea
                                    v-model="formData.description"
                                    required
                                    rows="3"
                                    class="form-control"
                                ></textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Section Styling -->
                    <div class="form-section">
                        <h4>Section Style</h4>
                        <div class="form-row">
                            <div class="form-group">
                                <label>Background Type</label>
                                <select
                                    v-model="formData.background_type"
                                    class="form-control"
                                >
                                    <option value="solid">Solid Color</option>
                                    <option value="gradient">Gradient</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Text Alignment</label>
                                <select
                                    v-model="formData.text_align"
                                    class="form-control"
                                >
                                    <option value="left">Left</option>
                                    <option value="center">Center</option>
                                    <option value="right">Right</option>
                                </select>
                            </div>
                        </div>

                        <!-- Solid Background -->
                        <div
                            v-if="formData.background_type === 'solid'"
                            class="form-row"
                        >
                            <div class="form-group">
                                <label>Background Color</label>
                                <div class="color-input-group">
                                    <input
                                        type="color"
                                        v-model="formData.background_color"
                                        class="form-control color-input"
                                        @input="
                                            updateColorValue(
                                                'background_color',
                                                $event
                                            )
                                        "
                                    />
                                    <input
                                        type="text"
                                        v-model="formData.background_color"
                                        placeholder="#ff7101 or rgba(255,113,1,0.8) or transparent"
                                        class="form-control color-text-input"
                                        @input="
                                            updateColorPicker(
                                                'background_color',
                                                $event
                                            )
                                        "
                                    />
                                </div>
                            </div>
                        </div>

                        <!-- Gradient Background -->
                        <div v-if="formData.background_type === 'gradient'">
                            <div class="form-row">
                                <div class="form-group">
                                    <label>Gradient Start Color</label>
                                    <div class="color-input-group">
                                        <input
                                            type="color"
                                            v-model="formData.gradient_start"
                                            class="form-control color-input"
                                            @input="
                                                updateColorValue(
                                                    'gradient_start',
                                                    $event
                                                )
                                            "
                                        />
                                        <input
                                            type="text"
                                            v-model="formData.gradient_start"
                                            placeholder="#ff7101 or rgba(255,113,1,0.8)"
                                            class="form-control color-text-input"
                                            @input="
                                                updateColorPicker(
                                                    'gradient_start',
                                                    $event
                                                )
                                            "
                                        />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>Gradient End Color</label>
                                    <div class="color-input-group">
                                        <input
                                            type="color"
                                            v-model="formData.gradient_end"
                                            class="form-control color-input"
                                            @input="
                                                updateColorValue(
                                                    'gradient_end',
                                                    $event
                                                )
                                            "
                                        />
                                        <input
                                            type="text"
                                            v-model="formData.gradient_end"
                                            placeholder="#d35b00 or rgba(211,91,0,0.8)"
                                            class="form-control color-text-input"
                                            @input="
                                                updateColorPicker(
                                                    'gradient_end',
                                                    $event
                                                )
                                            "
                                        />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>Gradient Direction</label>
                                    <select
                                        v-model="formData.gradient_direction"
                                        class="form-control"
                                    >
                                        <option value="90deg">
                                            Top to Bottom
                                        </option>
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
                        </div>

                        <!-- Padding -->
                        <div class="form-row">
                            <div class="form-group">
                                <label>Top Padding</label>
                                <input
                                    type="text"
                                    v-model="formData.padding_top"
                                    placeholder="80px"
                                    class="form-control"
                                />
                            </div>
                            <div class="form-group">
                                <label>Bottom Padding</label>
                                <input
                                    type="text"
                                    v-model="formData.padding_bottom"
                                    placeholder="80px"
                                    class="form-control"
                                />
                            </div>
                        </div>
                    </div>

                    <!-- Text Styling -->
                    <div class="form-section">
                        <h4>Text Styling</h4>
                        <div class="form-row">
                            <div class="form-group">
                                <label>Title Color</label>
                                <div class="color-input-group">
                                    <input
                                        type="color"
                                        v-model="formData.title_color"
                                        class="form-control color-input"
                                        @input="
                                            updateColorValue(
                                                'title_color',
                                                $event
                                            )
                                        "
                                    />
                                    <input
                                        type="text"
                                        v-model="formData.title_color"
                                        placeholder="#ffffff or rgba(255,255,255,0.9)"
                                        class="form-control color-text-input"
                                        @input="
                                            updateColorPicker(
                                                'title_color',
                                                $event
                                            )
                                        "
                                    />
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Description Color</label>
                                <div class="color-input-group">
                                    <input
                                        type="color"
                                        v-model="formData.description_color"
                                        class="form-control color-input"
                                        @input="
                                            updateColorValue(
                                                'description_color',
                                                $event
                                            )
                                        "
                                    />
                                    <input
                                        type="text"
                                        v-model="formData.description_color"
                                        placeholder="#ffffff or rgba(255,255,255,0.9)"
                                        class="form-control color-text-input"
                                        @input="
                                            updateColorPicker(
                                                'description_color',
                                                $event
                                            )
                                        "
                                    />
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label>Title Font Size</label>
                                <input
                                    type="text"
                                    v-model="formData.title_font_size"
                                    placeholder="2.5rem"
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
                            <div class="form-group">
                                <label>Description Font Size</label>
                                <input
                                    type="text"
                                    v-model="formData.description_font_size"
                                    placeholder="1.2rem"
                                    class="form-control"
                                />
                            </div>
                        </div>
                    </div>

                    <!-- Buttons Section -->
                    <div class="form-section">
                        <h4>Buttons</h4>
                        <div
                            v-for="(button, index) in formData.buttons"
                            :key="index"
                            class="button-item"
                        >
                            <div class="button-header">
                                <h5>Button {{ index + 1 }}</h5>
                                <button
                                    type="button"
                                    @click="removeButton(index)"
                                    class="btn btn-danger btn-sm"
                                >
                                    <i class="fas fa-trash"></i> Remove
                                </button>
                            </div>
                            <div class="form-row">
                                <div class="form-group">
                                    <label>Button Text</label>
                                    <input
                                        type="text"
                                        v-model="button.text"
                                        placeholder="Apply Now"
                                        class="form-control"
                                    />
                                </div>
                                <div class="form-group">
                                    <label>URL</label>
                                    <input
                                        type="text"
                                        v-model="button.url"
                                        placeholder="https://example.com"
                                        class="form-control"
                                    />
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group">
                                    <label>Background Color</label>
                                    <div class="color-input-group">
                                        <input
                                            type="color"
                                            v-model="button.background_color"
                                            class="form-control color-input"
                                            @input="
                                                updateButtonColor(
                                                    index,
                                                    'background_color',
                                                    $event
                                                )
                                            "
                                        />
                                        <input
                                            type="text"
                                            v-model="button.background_color"
                                            placeholder="#ffffff or rgba(255,255,255,0.9) or transparent"
                                            class="form-control color-text-input"
                                            @input="
                                                updateButtonColorPicker(
                                                    index,
                                                    'background_color',
                                                    $event
                                                )
                                            "
                                        />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>Text Color</label>
                                    <div class="color-input-group">
                                        <input
                                            type="color"
                                            v-model="button.text_color"
                                            class="form-control color-input"
                                            @input="
                                                updateButtonColor(
                                                    index,
                                                    'text_color',
                                                    $event
                                                )
                                            "
                                        />
                                        <input
                                            type="text"
                                            v-model="button.text_color"
                                            placeholder="#ff7101 or rgba(255,113,1,0.9)"
                                            class="form-control color-text-input"
                                            @input="
                                                updateButtonColorPicker(
                                                    index,
                                                    'text_color',
                                                    $event
                                                )
                                            "
                                        />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>Border Color</label>
                                    <div class="color-input-group">
                                        <input
                                            type="color"
                                            v-model="button.border_color"
                                            class="form-control color-input"
                                            @input="
                                                updateButtonColor(
                                                    index,
                                                    'border_color',
                                                    $event
                                                )
                                            "
                                        />
                                        <input
                                            type="text"
                                            v-model="button.border_color"
                                            placeholder="#ffffff or rgba(255,255,255,0.9)"
                                            class="form-control color-text-input"
                                            @input="
                                                updateButtonColorPicker(
                                                    index,
                                                    'border_color',
                                                    $event
                                                )
                                            "
                                        />
                                    </div>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group">
                                    <label>Hover Background</label>
                                    <div class="color-input-group">
                                        <input
                                            type="color"
                                            v-model="button.hover_background"
                                            class="form-control color-input"
                                            @input="
                                                updateButtonColor(
                                                    index,
                                                    'hover_background',
                                                    $event
                                                )
                                            "
                                        />
                                        <input
                                            type="text"
                                            v-model="button.hover_background"
                                            placeholder="transparent or #ffffff or rgba(255,255,255,0.9)"
                                            class="form-control color-text-input"
                                            @input="
                                                updateButtonColorPicker(
                                                    index,
                                                    'hover_background',
                                                    $event
                                                )
                                            "
                                        />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>Hover Text Color</label>
                                    <div class="color-input-group">
                                        <input
                                            type="color"
                                            v-model="button.hover_text_color"
                                            class="form-control color-input"
                                            @input="
                                                updateButtonColor(
                                                    index,
                                                    'hover_text_color',
                                                    $event
                                                )
                                            "
                                        />
                                        <input
                                            type="text"
                                            v-model="button.hover_text_color"
                                            placeholder="#ffffff or rgba(255,255,255,0.9)"
                                            class="form-control color-text-input"
                                            @input="
                                                updateButtonColorPicker(
                                                    index,
                                                    'hover_text_color',
                                                    $event
                                                )
                                            "
                                        />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>Hover Border Color</label>
                                    <div class="color-input-group">
                                        <input
                                            type="color"
                                            v-model="button.hover_border_color"
                                            class="form-control color-input"
                                            @input="
                                                updateButtonColor(
                                                    index,
                                                    'hover_border_color',
                                                    $event
                                                )
                                            "
                                        />
                                        <input
                                            type="text"
                                            v-model="button.hover_border_color"
                                            placeholder="#ffffff or rgba(255,255,255,0.9)"
                                            class="form-control color-text-input"
                                            @input="
                                                updateButtonColorPicker(
                                                    index,
                                                    'hover_border_color',
                                                    $event
                                                )
                                            "
                                        />
                                    </div>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group">
                                    <label>Font Size</label>
                                    <input
                                        type="text"
                                        v-model="button.font_size"
                                        placeholder="1.1rem"
                                        class="form-control"
                                    />
                                </div>
                                <div class="form-group">
                                    <label>Font Weight</label>
                                    <select
                                        v-model="button.font_weight"
                                        class="form-control"
                                    >
                                        <option value="300">Light</option>
                                        <option value="400">Normal</option>
                                        <option value="500">Medium</option>
                                        <option value="600">Semi Bold</option>
                                        <option value="700">Bold</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Padding</label>
                                    <input
                                        type="text"
                                        v-model="button.padding"
                                        placeholder="15px 35px"
                                        class="form-control"
                                    />
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group">
                                    <label>Border Radius</label>
                                    <input
                                        type="text"
                                        v-model="button.border_radius"
                                        placeholder="8px"
                                        class="form-control"
                                    />
                                </div>
                                <div class="form-group">
                                    <label>Border Width</label>
                                    <input
                                        type="text"
                                        v-model="button.border_width"
                                        placeholder="2px"
                                        class="form-control"
                                    />
                                </div>
                            </div>
                        </div>
                        <button
                            type="button"
                            @click="addButton"
                            class="btn btn-secondary"
                        >
                            <i class="fas fa-plus"></i> Add Button
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
                <h3>CTA Sections ({{ sections.length }})</h3>
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
                    :class="{
                        'is-active': section.is_active,
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
                                    <p
                                        :style="
                                            getPreviewDescriptionStyle(section)
                                        "
                                    >
                                        {{
                                            section.description.substring(
                                                0,
                                                100
                                            )
                                        }}...
                                    </p>
                                    <div
                                        class="preview-buttons"
                                        v-if="
                                            section.buttons &&
                                            section.buttons.length
                                        "
                                    >
                                        <div
                                            v-for="(
                                                button, btnIndex
                                            ) in section.buttons.slice(0, 2)"
                                            :key="btnIndex"
                                            class="preview-btn"
                                            :style="
                                                getPreviewButtonStyle(button)
                                            "
                                        >
                                            {{ button.text }}
                                        </div>
                                        <span
                                            v-if="section.buttons.length > 2"
                                            class="more-buttons"
                                        >
                                            +{{ section.buttons.length - 2 }}
                                            more
                                        </span>
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
                <div class="empty-icon">📢</div>
                <h4>No CTA sections yet</h4>
                <p>
                    Create your first call-to-action section to engage your
                    visitors
                </p>
                <button
                    class="btn btn-primary"
                    @click="showCreateForm = true"
                    :disabled="isLoading"
                >
                    + Add Your First CTA Section
                </button>
            </div>
        </div>
    </div>
</template>

<script>
import axios from "axios";

export default {
    name: "CtaSectionAdmin",
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
                title: "",
                description: "",
                title_color: "#ffffff",
                title_font_size: "2.5rem",
                title_font_weight: "700",
                description_color: "#ffffff",
                description_font_size: "1.2rem",
                description_font_weight: "400",
                background_type: "gradient",
                background_color: "#ff7101",
                gradient_start: "#ff7101",
                gradient_end: "#d35b00",
                gradient_direction: "135deg",
                padding_top: "80px",
                padding_bottom: "80px",
                text_align: "center",
                buttons: [],
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
        showInfo(title, message) {
            this.showToast("info", title, message);
        },

        // Color input synchronization methods
        updateColorValue(field, event) {
            // Update the color value from color picker
            this.formData[field] = event.target.value;
        },

        updateColorPicker(field, event) {
            // Update the color picker from text input
            const value = event.target.value;
            this.formData[field] = value;

            // Try to convert to hex for the color picker if it's a valid color
            if (this.isValidColor(value)) {
                // Find the color input element and update it
                const colorInput = event.target.previousElementSibling;
                if (colorInput && colorInput.type === "color") {
                    const hexColor = this.convertToHex(value);
                    if (hexColor) {
                        colorInput.value = hexColor;
                    }
                }
            }
        },

        updateButtonColor(index, field, event) {
            // Update button color from color picker
            this.formData.buttons[index][field] = event.target.value;
        },

        updateButtonColorPicker(index, field, event) {
            // Update button color picker from text input
            const value = event.target.value;
            this.formData.buttons[index][field] = value;

            // Try to convert to hex for the color picker if it's a valid color
            if (this.isValidColor(value)) {
                // Find the color input element and update it
                const colorInput = event.target.previousElementSibling;
                if (colorInput && colorInput.type === "color") {
                    const hexColor = this.convertToHex(value);
                    if (hexColor) {
                        colorInput.value = hexColor;
                    }
                }
            }
        },

        // Helper methods for color conversion
        isValidColor(color) {
            // Simple check for valid color formats
            const s = new Option().style;
            s.color = color;
            return s.color !== "";
        },

        convertToHex(color) {
            // Convert various color formats to hex
            if (!color) return null;

            // If it's already a hex color
            if (/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/.test(color)) {
                return color;
            }

            // Create a temporary element to compute the color
            const tempEl = document.createElement("div");
            tempEl.style.color = color;
            tempEl.style.display = "none";
            document.body.appendChild(tempEl);

            // Get computed color
            const computedColor = window.getComputedStyle(tempEl).color;
            document.body.removeChild(tempEl);

            // Convert rgb/rgba to hex
            if (computedColor.startsWith("rgb")) {
                const rgbValues = computedColor.match(/\d+/g);
                if (rgbValues && rgbValues.length >= 3) {
                    const r = parseInt(rgbValues[0])
                        .toString(16)
                        .padStart(2, "0");
                    const g = parseInt(rgbValues[1])
                        .toString(16)
                        .padStart(2, "0");
                    const b = parseInt(rgbValues[2])
                        .toString(16)
                        .padStart(2, "0");
                    return `#${r}${g}${b}`;
                }
            }

            return null;
        },

        async fetchSections() {
            this.isLoading = true;
            try {
                const response = await axios.get("/admin/cta-sections");
                this.sections = response.data;
                this.showSuccess(
                    "Sections Loaded",
                    `Successfully loaded ${response.data.length} CTA sections`
                );
            } catch (error) {
                console.error("Error fetching sections:", error);
                this.showError(
                    "Loading Failed",
                    "Failed to load CTA sections from server"
                );
            } finally {
                this.isLoading = false;
            }
        },

        resetForm() {
            this.formData = {
                title: "",
                description: "",
                title_color: "#ffffff",
                title_font_size: "2.5rem",
                title_font_weight: "700",
                description_color: "#ffffff",
                description_font_size: "1.2rem",
                description_font_weight: "400",
                background_type: "gradient",
                background_color: "#ff7101",
                gradient_start: "#ff7101",
                gradient_end: "#d35b00",
                gradient_direction: "135deg",
                padding_top: "80px",
                padding_bottom: "80px",
                text_align: "center",
                buttons: [],
            };
        },

        closeForm() {
            this.showCreateForm = false;
            this.editingSection = null;
            this.resetForm();
        },

        addButton() {
            this.formData.buttons.push({
                text: "",
                url: "",
                background_color: "#ffffff",
                text_color: "#ff7101",
                hover_background: "transparent",
                hover_text_color: "#ffffff",
                border_color: "#ffffff",
                hover_border_color: "#ffffff",
                font_size: "1.1rem",
                font_weight: "600",
                padding: "15px 35px",
                border_radius: "8px",
                border_width: "2px",
            });
        },

        removeButton(index) {
            this.formData.buttons.splice(index, 1);
        },

        async saveSection() {
            this.isSaving = true;
            try {
                const payload = { ...this.formData };

                let response;
                if (this.editingSection) {
                    response = await axios.put(
                        `/admin/cta-sections/${this.editingSection.id}`,
                        payload
                    );
                } else {
                    response = await axios.post("/admin/cta-sections", payload);
                }

                await this.fetchSections();
                this.closeForm();
                this.showSuccess(
                    "Section Saved",
                    this.editingSection
                        ? "CTA section updated successfully!"
                        : "CTA section created successfully!"
                );
            } catch (error) {
                console.error("Error saving section:", error);
                let errorMessage =
                    "Error saving CTA section. Please try again.";
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
                title_color: section.title_color || "#ffffff",
                title_font_size: section.title_font_size || "2.5rem",
                title_font_weight: section.title_font_weight || "700",
                description_color: section.description_color || "#ffffff",
                description_font_size:
                    section.description_font_size || "1.2rem",
                description_font_weight:
                    section.description_font_weight || "400",
                background_type: section.background_type || "gradient",
                background_color: section.background_color || "#ff7101",
                gradient_start: section.gradient_start || "#ff7101",
                gradient_end: section.gradient_end || "#d35b00",
                gradient_direction: section.gradient_direction || "135deg",
                padding_top: section.padding_top || "80px",
                padding_bottom: section.padding_bottom || "80px",
                text_align: section.text_align || "center",
                buttons: [],
            };

            if (section.buttons && Array.isArray(section.buttons)) {
                this.formData.buttons = section.buttons.map((button) => ({
                    text: button.text || "",
                    url: button.url || "",
                    background_color: button.background_color || "#ffffff",
                    text_color: button.text_color || "#ff7101",
                    hover_background: button.hover_background || "transparent",
                    hover_text_color: button.hover_text_color || "#ffffff",
                    border_color: button.border_color || "#ffffff",
                    hover_border_color: button.hover_border_color || "#ffffff",
                    font_size: button.font_size || "1.1rem",
                    font_weight: button.font_weight || "600",
                    padding: button.padding || "15px 35px",
                    border_radius: button.border_radius || "8px",
                    border_width: button.border_width || "2px",
                }));
            }
        },

        async toggleSection(section) {
            try {
                if (!section.is_active) {
                    await axios.post(
                        `/admin/cta-sections/${section.id}/activate`
                    );
                    this.showSuccess(
                        "Section Activated",
                        `"${section.title}" is now active and all other sections have been deactivated`
                    );
                } else {
                    await axios.put(`/admin/cta-sections/${section.id}`, {
                        ...section,
                        is_active: false,
                    });
                    this.showSuccess(
                        "Section Deactivated",
                        `"${section.title}" has been hidden`
                    );
                }
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
            if (confirm("Are you sure you want to delete this CTA section?")) {
                try {
                    await axios.delete(`/admin/cta-sections/${id}`);
                    await this.fetchSections();
                    this.showSuccess(
                        "Section Deleted",
                        "CTA section has been permanently removed"
                    );
                } catch (error) {
                    console.error("Error deleting section:", error);
                    this.showError(
                        "Delete Failed",
                        "Could not delete CTA section"
                    );
                }
            }
        },

        getSectionPreviewStyle(section) {
            const style = {
                padding: "20px",
                borderRadius: "10px",
                minHeight: "120px",
                textAlign: section.text_align || "center",
            };

            if (section.background_type === "gradient") {
                style.background = `linear-gradient(${section.gradient_direction}, ${section.gradient_start}, ${section.gradient_end})`;
            } else {
                style.background = section.background_color;
            }

            return style;
        },

        getPreviewTitleStyle(section) {
            return {
                fontSize: "1.2rem",
                color: section.title_color,
                fontWeight: section.title_font_weight,
                margin: "0 0 10px 0",
            };
        },

        getPreviewDescriptionStyle(section) {
            return {
                fontSize: "0.9rem",
                color: section.description_color,
                fontWeight: section.description_font_weight,
                margin: "0 0 15px 0",
                opacity: "0.9",
            };
        },

        getPreviewButtonStyle(button) {
            return {
                display: "inline-block",
                padding: "8px 16px",
                margin: "0 5px 5px 0",
                backgroundColor: button.background_color,
                color: button.text_color,
                border: `${button.border_width} solid ${button.border_color}`,
                borderRadius: button.border_radius,
                fontSize: "0.8rem",
                fontWeight: button.font_weight,
                textDecoration: "none",
                cursor: "pointer",
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
</style>
