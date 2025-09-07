<template>
    <div class="admin-news-events">
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
                <h2>News & Events Management</h2>
                <p>Manage news items, events, and section settings</p>
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
                    <i class="fas fa-plus"></i> Add News/Event
                </button>
            </div>
        </div>

        <!-- Create/Edit Item Modal -->
        <div v-if="showCreateForm" class="modal-overlay" @click="closeForm">
            <div class="modal-content item-modal" @click.stop>
                <div class="modal-header">
                    <h3>{{ editingItem ? "Edit" : "Create" }} News/Event</h3>
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
                                <label>Date *</label>
                                <input
                                    type="date"
                                    v-model="formData.date"
                                    required
                                    class="form-control"
                                />
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group full-width">
                                <label>Description *</label>
                                <textarea
                                    v-model="formData.description"
                                    required
                                    rows="4"
                                    class="form-control"
                                    placeholder="Enter description"
                                ></textarea>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group full-width">
                                <label>Link (Optional)</label>
                                <input
                                    type="url"
                                    v-model="formData.link"
                                    class="form-control"
                                    placeholder="https://example.com"
                                />
                            </div>
                        </div>
                    </div>

                    <!-- Card Background -->
                    <div class="form-section">
                        <h4>Card Background</h4>
                        <div class="form-row">
                            <div class="form-group">
                                <label>Background Type</label>
                                <select
                                    v-model="formData.card_background_type"
                                    @change="onBackgroundTypeChange"
                                    class="form-control"
                                >
                                    <option value="gradient">Gradient</option>
                                    <option value="image">Image</option>
                                </select>
                            </div>
                            <div
                                class="form-group"
                                v-if="
                                    formData.card_background_type === 'gradient'
                                "
                            >
                                <label>Icon</label>
                                <input
                                    type="text"
                                    v-model="formData.icon"
                                    class="form-control"
                                    placeholder="📰"
                                />
                            </div>
                        </div>

                        <!-- Gradient Settings -->
                        <div
                            v-if="formData.card_background_type === 'gradient'"
                        >
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

                        <!-- Image Upload -->
                        <div v-if="formData.card_background_type === 'image'">
                            <div class="form-row">
                                <div class="form-group full-width">
                                    <label>Upload Image</label>
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

                        <!-- Title Styling -->
                        <div class="form-subsection">
                            <h5>Title</h5>
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
                                    <label>Font Size</label>
                                    <input
                                        type="text"
                                        v-model="formData.title_font_size"
                                        placeholder="1.3rem"
                                        class="form-control"
                                    />
                                </div>
                                <div class="form-group">
                                    <label>Font Weight</label>
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
                        </div>

                        <!-- Description Styling -->
                        <div class="form-subsection">
                            <h5>Description</h5>
                            <div class="form-row">
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
                                    <label>Font Size</label>
                                    <input
                                        type="text"
                                        v-model="formData.description_font_size"
                                        placeholder="1rem"
                                        class="form-control"
                                    />
                                </div>
                                <div class="form-group">
                                    <label>Font Weight</label>
                                    <select
                                        v-model="
                                            formData.description_font_weight
                                        "
                                        class="form-control"
                                    >
                                        <option value="300">Light</option>
                                        <option value="400">Normal</option>
                                        <option value="500">Medium</option>
                                        <option value="600">Semi Bold</option>
                                        <option value="700">Bold</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Date Styling -->
                        <div class="form-subsection">
                            <h5>Date</h5>
                            <div class="form-row">
                                <div class="form-group">
                                    <label>Date Color</label>
                                    <div class="color-input-group">
                                        <input
                                            type="color"
                                            v-model="formData.date_color"
                                            class="form-control color-input"
                                        />
                                        <span class="color-value">{{
                                            formData.date_color
                                        }}</span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>Font Size</label>
                                    <input
                                        type="text"
                                        v-model="formData.date_font_size"
                                        placeholder="0.9rem"
                                        class="form-control"
                                    />
                                </div>
                                <div class="form-group">
                                    <label>Font Weight</label>
                                    <select
                                        v-model="formData.date_font_weight"
                                        class="form-control"
                                    >
                                        <option value="400">Normal</option>
                                        <option value="500">Medium</option>
                                        <option value="600">Semi Bold</option>
                                        <option value="700">Bold</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Link Styling -->
                        <div class="form-subsection">
                            <h5>Link</h5>
                            <div class="form-row">
                                <div class="form-group">
                                    <label>Link Color</label>
                                    <div class="color-input-group">
                                        <input
                                            type="color"
                                            v-model="formData.link_color"
                                            class="form-control color-input"
                                        />
                                        <span class="color-value">{{
                                            formData.link_color
                                        }}</span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>Hover Color</label>
                                    <div class="color-input-group">
                                        <input
                                            type="color"
                                            v-model="formData.link_hover_color"
                                            class="form-control color-input"
                                        />
                                        <span class="color-value">{{
                                            formData.link_hover_color
                                        }}</span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>Font Weight</label>
                                    <select
                                        v-model="formData.link_font_weight"
                                        class="form-control"
                                    >
                                        <option value="400">Normal</option>
                                        <option value="500">Medium</option>
                                        <option value="600">Semi Bold</option>
                                        <option value="700">Bold</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Card Styling -->
                    <div class="form-section">
                        <h4>Card Styling</h4>
                        <div class="form-row">
                            <div class="form-group">
                                <label>Background Color</label>
                                <div class="color-input-group">
                                    <input
                                        type="color"
                                        v-model="formData.card_background_color"
                                        class="form-control color-input"
                                    />
                                    <span class="color-value">{{
                                        formData.card_background_color
                                    }}</span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Border Radius</label>
                                <input
                                    type="text"
                                    v-model="formData.card_border_radius"
                                    placeholder="15px"
                                    class="form-control"
                                />
                            </div>
                            <div class="form-group">
                                <label>Shadow</label>
                                <input
                                    type="text"
                                    v-model="formData.card_shadow"
                                    placeholder="0 5px 20px rgba(0, 0, 0, 0.08)"
                                    class="form-control"
                                />
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label>Hover Shadow</label>
                                <input
                                    type="text"
                                    v-model="formData.card_hover_shadow"
                                    placeholder="0 15px 30px rgba(0, 0, 0, 0.15)"
                                    class="form-control"
                                />
                            </div>
                            <div class="form-group">
                                <label>Hover Transform</label>
                                <input
                                    type="text"
                                    v-model="formData.card_hover_transform"
                                    placeholder="translateY(-10px)"
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
                    <h3>Section Settings</h3>
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
                            <div class="form-group">
                                <label>Header Margin Bottom</label>
                                <input
                                    type="text"
                                    v-model="
                                        sectionSettings.header_margin_bottom
                                    "
                                    placeholder="60px"
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
                        <div class="form-row">
                            <div class="form-group full-width">
                                <label>Background Image</label>
                                <input
                                    type="file"
                                    @change="onSectionImageChange"
                                    accept="image/*"
                                    class="form-control"
                                />
                                <div
                                    v-if="
                                        sectionImagePreview ||
                                        sectionSettings.section_background_image
                                    "
                                    class="image-preview"
                                >
                                    <img
                                        :src="
                                            sectionImagePreview ||
                                            getImageUrl(
                                                sectionSettings.section_background_image
                                            )
                                        "
                                        alt="Background Preview"
                                    />
                                    <button
                                        type="button"
                                        @click="removeSectionImage"
                                        class="remove-image"
                                    >
                                        ×
                                    </button>
                                </div>
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
                                    placeholder="30px"
                                    class="form-control"
                                />
                            </div>
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
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label>Container Padding</label>
                                <input
                                    type="text"
                                    v-model="sectionSettings.container_padding"
                                    placeholder="0 20px"
                                    class="form-control"
                                />
                            </div>
                            <div class="form-group">
                                <label>Mobile Breakpoint</label>
                                <input
                                    type="text"
                                    v-model="sectionSettings.mobile_breakpoint"
                                    placeholder="768px"
                                    class="form-control"
                                />
                            </div>
                            <div class="form-group">
                                <label>Mobile Grid</label>
                                <input
                                    type="text"
                                    v-model="
                                        sectionSettings.mobile_grid_columns
                                    "
                                    placeholder="1fr"
                                    class="form-control"
                                />
                            </div>
                        </div>
                    </div>

                    <!-- Animation Settings -->
                    <div class="form-section">
                        <h4>Animation Settings</h4>
                        <div class="form-row">
                            <div class="form-group">
                                <label>
                                    <input
                                        type="checkbox"
                                        v-model="
                                            sectionSettings.enable_animations
                                        "
                                    />
                                    Enable Animations
                                </label>
                            </div>
                            <div class="form-group">
                                <label>Animation Duration</label>
                                <input
                                    type="text"
                                    v-model="sectionSettings.animation_duration"
                                    placeholder="0.6s"
                                    class="form-control"
                                />
                            </div>
                            <div class="form-group">
                                <label>Delay Increment</label>
                                <input
                                    type="text"
                                    v-model="
                                        sectionSettings.animation_delay_increment
                                    "
                                    placeholder="0.1s"
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
                <h3>News & Events ({{ items.length }})</h3>
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
                            <span class="status-text">
                                {{ item.is_active ? "Active" : "Inactive" }}
                            </span>
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
                                        item.card_background_type === 'image' &&
                                        item.image_url
                                    "
                                    :src="getImageUrl(item.image_url)"
                                    alt="Card image"
                                    class="card-image"
                                />
                                <span v-else class="card-icon">{{
                                    item.icon
                                }}</span>
                            </div>
                            <div class="preview-content">
                                <div
                                    class="item-date"
                                    :style="{ color: item.date_color }"
                                >
                                    {{ formatDate(item.date) }}
                                </div>
                                <h3 :style="getPreviewTitleStyle(item)">
                                    {{ item.title }}
                                </h3>
                                <p :style="getPreviewDescriptionStyle(item)">
                                    {{ item.description.substring(0, 120) }}...
                                </p>
                                <a
                                    v-if="item.link"
                                    href="#"
                                    :style="{ color: item.link_color }"
                                    class="preview-link"
                                >
                                    Read More
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer">
                        <small class="update-time">
                            Last updated: {{ formatDateTime(item.updated_at) }}
                        </small>
                    </div>
                </div>
            </div>

            <div v-if="items.length === 0" class="empty-state">
                <div class="empty-icon">📰</div>
                <h4>No news or events yet</h4>
                <p>Get started by adding your first news item or event</p>
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
    name: "NewsEventsAdmin",
    data() {
        return {
            items: [],
            sectionSettings: {
                section_title: "News & Events",
                section_description:
                    "Stay updated with our latest news and upcoming events",
                section_title_color: "#102e4a",
                section_title_font_size: "2.5rem",
                section_title_font_weight: "700",
                section_description_color: "#111111",
                section_description_font_size: "1.1rem",
                section_description_font_weight: "400",
                section_background_color: "#ffffff",
                section_padding: "80px 0",
                header_text_align: "center",
                header_margin_bottom: "60px",
                grid_columns: "repeat(auto-fit, minmax(350px, 1fr))",
                grid_gap: "30px",
                container_max_width: "1240px",
                container_padding: "0 20px",
                enable_animations: true,
                animation_duration: "0.6s",
                animation_delay_increment: "0.1s",
                mobile_breakpoint: "768px",
                mobile_grid_columns: "1fr",
                is_active: true,
            },
            showCreateForm: false,
            showSettingsForm: false,
            editingItem: null,
            imagePreview: null,
            sectionImagePreview: null,
            selectedImage: null,
            selectedSectionImage: null,
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
                date: "",
                description: "",
                link: "",
                card_background_type: "gradient",
                icon: "📰",
                gradient_start: "#ff7101",
                gradient_end: "#d35b00",
                gradient_direction: "135deg",
                title_color: "#102e4a",
                title_font_size: "1.3rem",
                title_font_weight: "600",
                description_color: "#111111",
                description_font_size: "1rem",
                description_font_weight: "400",
                date_color: "#ff7101",
                date_font_size: "0.9rem",
                date_font_weight: "600",
                link_color: "#ff7101",
                link_hover_color: "#d35b00",
                link_font_weight: "600",
                card_border_radius: "15px",
                card_shadow: "0 5px 20px rgba(0, 0, 0, 0.08)",
                card_hover_shadow: "0 15px 30px rgba(0, 0, 0, 0.15)",
                card_hover_transform: "translateY(-10px)",
                card_background_color: "#ffffff",
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

        // Drag and drop methods
        onDragStart(event, item, index) {
            this.isDragging = true;
            this.draggedItem = item;
            this.draggedIndex = index;
            event.dataTransfer.effectAllowed = "move";
            event.dataTransfer.setData("text/html", event.target);
        },

        onDragEnd() {
            this.isDragging = false;
            this.draggedItem = null;
            this.draggedIndex = null;
        },

        moveUp(index) {
            if (index === 0) return;

            // Swap items
            const item = this.items[index];
            const previousItem = this.items[index - 1];

            this.items.splice(index - 1, 2, item, previousItem);

            // Update order
            this.updateAllOrders();
            this.showInfo("Item Moved", `"${item.title}" moved up`);
        },

        moveDown(index) {
            if (index === this.items.length - 1) return;

            // Swap items
            const item = this.items[index];
            const nextItem = this.items[index + 1];

            this.items.splice(index, 2, nextItem, item);

            // Update order
            this.updateAllOrders();
            this.showInfo("Item Moved", `"${item.title}" moved down`);
        },

        // Enhanced drag and drop with better error handling
        onDrop(event, dropIndex) {
            event.preventDefault();

            if (this.draggedIndex === null || this.draggedIndex === dropIndex) {
                return;
            }

            try {
                // Remove item from old position
                const item = this.items.splice(this.draggedIndex, 1)[0];
                // Insert at new position
                this.items.splice(dropIndex, 0, item);

                // Update order
                this.updateAllOrders();
                this.showInfo(
                    "Order Updated",
                    `"${item.title}" moved successfully`
                );
            } catch (error) {
                console.error("Drag and drop error:", error);
                this.showError("Reorder Failed", "Could not reorder items");
                // Refresh items to reset state
                this.fetchItems();
            }
        },

        // Enhanced order update with better error handling
        async updateAllOrders() {
            try {
                const updates = this.items.map((item, index) => ({
                    id: item.id,
                    order: index,
                }));

                await axios.post("/admin/news-events/update-order", {
                    items: updates,
                });
            } catch (error) {
                console.error("Error updating order:", error);
                this.showError(
                    "Order Update Failed",
                    "Could not save new item order. Page will refresh."
                );
                // Refresh the page data to ensure consistency
                setTimeout(() => {
                    this.fetchItems();
                }, 2000);
            }
        },

        // API methods
        async fetchItems() {
            this.isLoading = true;
            try {
                const response = await axios.get("/admin/news-events");
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
                const response = await axios.get("/admin/news-events-settings");
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
                    formData.append("image", this.selectedImage);
                }

                let response;
                if (this.editingItem) {
                    formData.append("_method", "PUT");
                    response = await axios.post(
                        `/admin/news-events/${this.editingItem.id}`,
                        formData,
                        {
                            headers: {
                                "Content-Type": "multipart/form-data",
                            },
                        }
                    );
                } else {
                    response = await axios.post(
                        "/admin/news-events",
                        formData,
                        {
                            headers: {
                                "Content-Type": "multipart/form-data",
                            },
                        }
                    );
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
                let errorMessage = "Error saving item. Please try again.";
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

        async saveSectionSettings() {
            this.isSavingSettings = true;
            try {
                const formData = new FormData();

                Object.keys(this.sectionSettings).forEach((key) => {
                    if (
                        this.sectionSettings[key] !== null &&
                        this.sectionSettings[key] !== undefined
                    ) {
                        formData.append(key, this.sectionSettings[key]);
                    }
                });

                if (this.selectedSectionImage) {
                    formData.append(
                        "section_background_image",
                        this.selectedSectionImage
                    );
                }

                const response = await axios.post(
                    "/admin/news-events-settings",
                    formData,
                    {
                        headers: {
                            "Content-Type": "multipart/form-data",
                        },
                    }
                );

                this.sectionSettings = {
                    ...this.sectionSettings,
                    ...response.data.data,
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
                await axios.post(`/admin/news-events/${item.id}/toggle-status`);
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
                    await axios.delete(`/admin/news-events/${id}`);
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
                await axios.post("/admin/news-events/update-order", {
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

            if (item.image_url) {
                this.imagePreview = this.getImageUrl(item.image_url);
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
            this.sectionImagePreview = null;
            this.selectedSectionImage = null;
        },

        resetForm() {
            this.formData = {
                title: "",
                date: "",
                description: "",
                link: "",
                card_background_type: "gradient",
                icon: "📰",
                gradient_start: "#ff7101",
                gradient_end: "#d35b00",
                gradient_direction: "135deg",
                title_color: "#102e4a",
                title_font_size: "1.3rem",
                title_font_weight: "600",
                description_color: "#111111",
                description_font_size: "1rem",
                description_font_weight: "400",
                date_color: "#ff7101",
                date_font_size: "0.9rem",
                date_font_weight: "600",
                link_color: "#ff7101",
                link_hover_color: "#d35b00",
                link_font_weight: "600",
                card_border_radius: "15px",
                card_shadow: "0 5px 20px rgba(0, 0, 0, 0.08)",
                card_hover_shadow: "0 15px 30px rgba(0, 0, 0, 0.15)",
                card_hover_transform: "translateY(-10px)",
                card_background_color: "#ffffff",
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
            this.formData.image_url = null;
        },

        onSectionImageChange(event) {
            const file = event.target.files[0];
            if (file) {
                this.selectedSectionImage = file;
                const reader = new FileReader();
                reader.onload = (e) => {
                    this.sectionImagePreview = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        },

        removeSectionImage() {
            this.sectionImagePreview = null;
            this.selectedSectionImage = null;
            this.sectionSettings.section_background_image = null;
        },

        onBackgroundTypeChange() {
            if (this.formData.card_background_type === "gradient") {
                this.imagePreview = null;
                this.selectedImage = null;
            }
        },

        // Utility methods
        getImageUrl(path) {
            if (!path) return "";
            return path.startsWith("http") ? path : `/${path}`;
        },

        formatDate(dateString) {
            return new Date(dateString).toLocaleDateString("en-US", {
                year: "numeric",
                month: "long",
                day: "numeric",
            });
        },

        formatDateTime(dateString) {
            return new Date(dateString).toLocaleDateString();
        },

        getItemPreviewStyle(item) {
            const style = {
                width: "200px",
                height: "200px",
                borderRadius: item.card_border_radius || "15px",
                display: "flex",
                alignItems: "center",
                justifyContent: "center",
                fontSize: "3rem",
                color: "#fff",
                flexShrink: 0,
            };

            if (item.card_background_type === "gradient") {
                style.background = `linear-gradient(${item.gradient_direction}, ${item.gradient_start}, ${item.gradient_end})`;
            } else if (item.image_url) {
                style.backgroundImage = `url('${this.getImageUrl(
                    item.image_url
                )}')`;
                style.backgroundSize = "cover";
                style.backgroundPosition = "center";
            }

            return style;
        },

        getPreviewTitleStyle(item) {
            return {
                color: item.title_color,
                fontSize: item.title_font_size,
                fontWeight: item.title_font_weight,
                margin: "10px 0",
            };
        },

        getPreviewDescriptionStyle(item) {
            return {
                color: item.description_color,
                fontSize: item.description_font_size,
                fontWeight: item.description_font_weight,
                lineHeight: "1.6",
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

.drag-info {
    margin: 0;
    font-size: 14px;
    color: #6b7280;
}

.items-container {
    padding: 20px;
}

.items-container.is-dragging {
    background: #f9fafb;
}

/* Item Card */
.item-card {
    background: white;
    border: 1px solid #e5e7eb;
    border-radius: 12px;
    margin-bottom: 20px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.04);
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
}

.item-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
}

.item-card.is-active {
    border-color: #10b981;
    box-shadow: 0 2px 8px rgba(16, 185, 129, 0.15);
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
.admin-news-events {
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
}

.image-preview img {
    max-width: 200px;
    max-height: 150px;
    object-fit: cover;
    border-radius: 6px;
    border: 1px solid #d1d5db;
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

.view-options {
    display: flex;
    gap: 8px;
}

.items-container {
    display: grid;
    gap: 20px;
}

.items-container.view-grid {
    grid-template-columns: repeat(auto-fill, minmax(500px, 1fr));
}

.items-container.view-list {
    grid-template-columns: 1fr;
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

.move-btn:disabled {
    opacity: 0.5;
    cursor: not-allowed;
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

.item-preview {
    display: flex;
    gap: 20px;
    align-items: flex-start;
}

.preview-image {
    position: relative;
    overflow: hidden;
}

.card-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.card-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 100%;
    height: 100%;
    font-size: 3rem;
}

.preview-content {
    flex: 1;
    min-width: 0;
}

.item-date {
    font-size: 14px;
    font-weight: 600;
    margin-bottom: 8px;
}

.preview-content h3 {
    margin: 0 0 10px 0;
    font-size: 18px;
}

.preview-content p {
    margin: 0 0 15px 0;
    line-height: 1.6;
    color: #6b7280;
}

.preview-link {
    text-decoration: none;
    font-weight: 600;
    transition: color 0.2s;
}

.preview-link:hover {
    text-decoration: underline;
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
    background: white;
    border-radius: 12px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
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

/* Update the card-actions to accommodate the new buttons */
.card-actions {
    display: flex;
    align-items: flex-start;
    gap: 8px;
    flex-wrap: wrap;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .reorder-buttons {
        flex-direction: row;
        margin-right: 0;
        margin-bottom: 8px;
    }

    .card-actions {
        flex-direction: column;
        align-items: stretch;
        gap: 8px;
    }

    .reorder-btn {
        width: 32px;
        height: 32px;
        font-size: 14px;
    }
}

/* Enhanced drag state styles */
.item-card.is-dragging {
    transform: rotate(2deg);
    opacity: 0.8;
    z-index: 1000;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
}

.items-container.is-dragging .item-card:not(.is-dragging) {
    opacity: 0.7;
}

/* Drag handle improvements */
.drag-handle {
    cursor: grab;
    color: #adb5bd;
    font-size: 16px;
    padding: 8px 4px;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: color 0.2s ease;
}

.drag-handle:hover {
    color: #6c757d;
}

.drag-handle:active {
    cursor: grabbing;
}

/* Toast improvements for reordering feedback */
.toast-container {
    position: fixed;
    top: 20px;
    right: 20px;
    z-index: 9999;
    max-width: 400px;
}

.toast {
    background: white;
    border-radius: 8px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    margin-bottom: 12px;
    padding: 16px;
    display: flex;
    align-items: flex-start;
    gap: 12px;
    cursor: pointer;
    transition: all 0.3s ease;
    border-left: 4px solid #007bff;
}

.toast:hover {
    transform: translateX(-4px);
}

.toast-success {
    border-left-color: #28a745;
}

.toast-error {
    border-left-color: #dc3545;
}

.toast-warning {
    border-left-color: #ffc107;
}

.toast-info {
    border-left-color: #17a2b8;
}

.toast-icon {
    font-weight: bold;
    font-size: 18px;
}

.toast-content {
    flex: 1;
}

.toast-title {
    font-weight: 600;
    margin-bottom: 4px;
    color: #333;
}

.toast-message {
    color: #666;
    font-size: 14px;
    line-height: 1.4;
}

.toast-close {
    background: none;
    border: none;
    font-size: 18px;
    color: #adb5bd;
    cursor: pointer;
    padding: 0;
    width: 20px;
    height: 20px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.toast-close:hover {
    color: #6c757d;
}

/* Responsive Design */
@media (max-width: 1024px) {
    .items-container.view-grid {
        grid-template-columns: repeat(auto-fill, minmax(400px, 1fr));
    }
}

@media (max-width: 768px) {
    .admin-news-events {
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

    .items-container.view-grid {
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

    .item-preview {
        flex-direction: column;
        align-items: stretch;
    }

    .preview-image {
        width: 100%;
        height: 200px;
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
        text-align: center;
    }

    .toast-container {
        left: 10px;
        right: 10px;
        max-width: none;
    }
}
</style>
