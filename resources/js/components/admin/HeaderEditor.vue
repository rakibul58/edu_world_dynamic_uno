<template>
    <div class="header-editor">
        <form @submit.prevent="handleSubmit" class="editor-form">
            <!-- Navigation tabs -->
            <div class="editor-tabs">
                <button
                    type="button"
                    v-for="tab in tabs"
                    :key="tab.key"
                    :class="['tab-btn', { active: activeTab === tab.key }]"
                    @click="activeTab = tab.key"
                >
                    <component :is="tab.icon" class="tab-icon" />
                    {{ tab.label }}
                </button>
            </div>

            <div class="editor-content">
                <!-- Basic Settings Tab -->
                <div v-show="activeTab === 'basic'" class="tab-content">
                    <h3 class="section-title">Basic Information</h3>

                    <div class="form-group">
                        <label class="form-label">Header Name</label>
                        <input
                            v-model="formData.name"
                            type="text"
                            class="form-input"
                            placeholder="e.g., Main Header, Landing Page Header"
                            required
                        />
                    </div>

                    <div class="form-group">
                        <label class="form-label">Logo Type</label>
                        <div class="radio-group">
                            <label class="radio-option">
                                <input
                                    v-model="formData.logo_type"
                                    type="radio"
                                    value="text"
                                />
                                <span class="radio-custom"></span>
                                Text Logo
                            </label>
                            <label class="radio-option">
                                <input
                                    v-model="formData.logo_type"
                                    type="radio"
                                    value="image"
                                />
                                <span class="radio-custom"></span>
                                Image Logo
                            </label>
                        </div>
                    </div>

                    <div
                        v-if="formData.logo_type === 'text'"
                        class="form-group"
                    >
                        <label class="form-label">Logo Text</label>
                        <input
                            v-model="formData.logo_text"
                            type="text"
                            class="form-input"
                            placeholder="Your Brand Name"
                        />
                    </div>

                    <div
                        v-if="formData.logo_type === 'image'"
                        class="form-group"
                    >
                        <label class="form-label">Logo Image</label>
                        <div class="file-upload-area">
                            <input
                                ref="logoUpload"
                                type="file"
                                @change="handleLogoUpload"
                                accept="image/*"
                                class="file-input"
                            />
                            <div
                                class="upload-dropzone"
                                @click="$refs.logoUpload.click()"
                                @dragover.prevent
                                @drop.prevent="handleLogoDrop"
                            >
                                <img
                                    v-if="
                                        logoPreview || formData.logo_image_path
                                    "
                                    :src="
                                        logoPreview || formData.logo_image_path
                                    "
                                    alt="Logo preview"
                                    class="logo-preview"
                                />
                                <div v-else class="upload-placeholder">
                                    <svg
                                        width="48"
                                        height="48"
                                        viewBox="0 0 24 24"
                                        fill="none"
                                        stroke="currentColor"
                                        stroke-width="1"
                                    >
                                        <rect
                                            x="3"
                                            y="3"
                                            width="18"
                                            height="18"
                                            rx="2"
                                            ry="2"
                                        />
                                        <circle cx="8.5" cy="8.5" r="1.5" />
                                        <polyline points="21,15 16,10 5,21" />
                                    </svg>
                                    <p>Click to upload logo or drag and drop</p>
                                    <small>PNG, JPG, GIF up to 2MB</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label"
                            >Logo Tagline (Optional)</label
                        >
                        <input
                            v-model="formData.logo_tagline"
                            type="text"
                            class="form-input"
                            placeholder="e.g., Quality Education for All"
                        />
                    </div>

                    <div class="form-group">
                        <label class="form-label">Logo Link</label>
                        <div class="input-group">
                            <input
                                v-model="formData.logo_link.url"
                                type="text"
                                class="form-input"
                                placeholder="https://example.com or /"
                            />
                            <select
                                v-model="formData.logo_link.target"
                                class="form-select"
                            >
                                <option value="_self">Same Window</option>
                                <option value="_blank">New Window</option>
                            </select>
                        </div>
                        <small class="form-help"
                            >Current link:
                            {{ formData.logo_link.url || "Not set" }}</small
                        >
                    </div>
                </div>

                <!-- Buttons Tab -->
                <div v-show="activeTab === 'buttons'" class="tab-content">
                    <div class="section-header">
                        <h3 class="section-title">Navigation Buttons</h3>
                        <button
                            type="button"
                            @click="addButton"
                            class="btn btn-primary btn-sm"
                        >
                            <svg
                                width="16"
                                height="16"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="2"
                            >
                                <line x1="12" y1="5" x2="12" y2="19" />
                                <line x1="5" y1="12" x2="19" y2="12" />
                            </svg>
                            Add Button
                        </button>
                    </div>

                    <div class="buttons-list">
                        <div
                            v-for="(button, index) in formData.buttons"
                            :key="index"
                            class="button-editor"
                        >
                            <div class="button-header">
                                <h4>Button {{ index + 1 }}</h4>
                                <button
                                    type="button"
                                    @click="removeButton(index)"
                                    class="btn-icon btn-danger"
                                    v-if="formData.buttons.length > 1"
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
                                    </svg>
                                </button>
                            </div>

                            <div class="button-form">
                                <div class="form-row">
                                    <div class="form-group">
                                        <label class="form-label"
                                            >Button Text</label
                                        >
                                        <input
                                            v-model="button.text"
                                            type="text"
                                            class="form-input"
                                            placeholder="Get Started"
                                        />
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label"
                                            >Button URL</label
                                        >
                                        <input
                                            v-model="button.url"
                                            type="text"
                                            class="form-input"
                                            placeholder="/get-started or https://example.com"
                                        />
                                    </div>
                                </div>

                                <div class="form-row">
                                    <div class="form-group">
                                        <label class="form-label"
                                            >Button Style</label
                                        >
                                        <select
                                            v-model="button.style"
                                            class="form-select"
                                        >
                                            <option value="filled">
                                                Filled
                                            </option>
                                            <option value="outlined">
                                                Outlined
                                            </option>
                                            <option value="ghost">Ghost</option>
                                            <option value="transparent">
                                                Transparent
                                            </option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label"
                                            >Button Size</label
                                        >
                                        <select
                                            v-model="button.size"
                                            class="form-select"
                                        >
                                            <option value="sm">Small</option>
                                            <option value="md">Medium</option>
                                            <option value="lg">Large</option>
                                        </select>
                                    </div>
                                </div>

                                <!-- Button Preview -->
                                <div class="button-preview">
                                    <span class="preview-label">Preview:</span>
                                    <button
                                        type="button"
                                        class="preview-button"
                                        :style="getButtonPreviewStyle(button)"
                                    >
                                        {{ button.text || "Button Text" }}
                                    </button>
                                </div>

                                <!-- Custom Styles -->
                                <details class="custom-styles-toggle">
                                    <summary>Custom Colors & Styles</summary>
                                    <div class="custom-styles-content">
                                        <div class="color-row">
                                            <div class="color-group">
                                                <label class="form-label"
                                                    >Background Color</label
                                                >
                                                <div class="color-input-group">
                                                    <input
                                                        v-model="
                                                            button.custom_styles
                                                                .background_color
                                                        "
                                                        type="color"
                                                        class="color-input"
                                                    />
                                                    <input
                                                        v-model="
                                                            button.custom_styles
                                                                .background_color
                                                        "
                                                        type="text"
                                                        class="form-input"
                                                        placeholder="#ff7101"
                                                    />
                                                </div>
                                            </div>
                                            <div class="color-group">
                                                <label class="form-label"
                                                    >Text Color</label
                                                >
                                                <div class="color-input-group">
                                                    <input
                                                        v-model="
                                                            button.custom_styles
                                                                .text_color
                                                        "
                                                        type="color"
                                                        class="color-input"
                                                    />
                                                    <input
                                                        v-model="
                                                            button.custom_styles
                                                                .text_color
                                                        "
                                                        type="text"
                                                        class="form-input"
                                                        placeholder="#ffffff"
                                                    />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="color-row">
                                            <div class="color-group">
                                                <label class="form-label"
                                                    >Border Color</label
                                                >
                                                <div class="color-input-group">
                                                    <input
                                                        v-model="
                                                            button.custom_styles
                                                                .border_color
                                                        "
                                                        type="color"
                                                        class="color-input"
                                                    />
                                                    <input
                                                        v-model="
                                                            button.custom_styles
                                                                .border_color
                                                        "
                                                        type="text"
                                                        class="form-input"
                                                        placeholder="#ff7101"
                                                    />
                                                </div>
                                            </div>
                                            <div class="color-group">
                                                <label class="form-label"
                                                    >Border Radius</label
                                                >
                                                <input
                                                    v-model="
                                                        button.custom_styles
                                                            .border_radius
                                                    "
                                                    type="text"
                                                    class="form-input"
                                                    placeholder="5px"
                                                />
                                            </div>
                                        </div>
                                        <div class="color-row">
                                            <div class="color-group">
                                                <label class="form-label"
                                                    >Hover Background</label
                                                >
                                                <div class="color-input-group">
                                                    <input
                                                        v-model="
                                                            button.custom_styles
                                                                .hover_background_color
                                                        "
                                                        type="color"
                                                        class="color-input"
                                                    />
                                                    <input
                                                        v-model="
                                                            button.custom_styles
                                                                .hover_background_color
                                                        "
                                                        type="text"
                                                        class="form-input"
                                                        placeholder="transparent"
                                                    />
                                                </div>
                                            </div>
                                            <div class="color-group">
                                                <label class="form-label"
                                                    >Hover Text</label
                                                >
                                                <div class="color-input-group">
                                                    <input
                                                        v-model="
                                                            button.custom_styles
                                                                .hover_text_color
                                                        "
                                                        type="color"
                                                        class="color-input"
                                                    />
                                                    <input
                                                        v-model="
                                                            button.custom_styles
                                                                .hover_text_color
                                                        "
                                                        type="text"
                                                        class="form-input"
                                                        placeholder="#ffffff"
                                                    />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="color-row">
                                            <div class="color-group">
                                                <label class="form-label"
                                                    >Hover Border</label
                                                >
                                                <div class="color-input-group">
                                                    <input
                                                        v-model="
                                                            button.custom_styles
                                                                .hover_border_color
                                                        "
                                                        type="color"
                                                        class="color-input"
                                                    />
                                                    <input
                                                        v-model="
                                                            button.custom_styles
                                                                .hover_border_color
                                                        "
                                                        type="text"
                                                        class="form-input"
                                                        placeholder="#ff7101"
                                                    />
                                                </div>
                                            </div>
                                            <div class="color-group">
                                                <label class="form-label"
                                                    >Button Opacity</label
                                                >
                                                <input
                                                    v-model="
                                                        button.custom_styles
                                                            .opacity
                                                    "
                                                    type="number"
                                                    min="0"
                                                    max="1"
                                                    step="0.1"
                                                    class="form-input"
                                                    placeholder="1"
                                                />
                                            </div>
                                        </div>
                                    </div>
                                </details>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Styles Tab -->
                <div v-show="activeTab === 'styles'" class="tab-content">
                    <h3 class="section-title">Visual Styles</h3>

                    <!-- Navigation Styles -->
                    <div class="style-section">
                        <h4 class="subsection-title">Navigation Bar</h4>
                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label"
                                    >Background Color</label
                                >
                                <div class="color-input-group">
                                    <input
                                        v-model="formData.nav_styles.background"
                                        type="color"
                                        class="color-input"
                                    />
                                    <input
                                        v-model="formData.nav_styles.background"
                                        type="text"
                                        class="form-input"
                                        placeholder="#102e4a"
                                    />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Padding</label>
                                <input
                                    v-model="formData.nav_styles.padding"
                                    type="text"
                                    class="form-input"
                                    placeholder="0 20px"
                                />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Box Shadow</label>
                            <input
                                v-model="formData.nav_styles.box_shadow"
                                type="text"
                                class="form-input"
                                placeholder="0 2px 10px rgba(0,0,0,0.1)"
                            />
                        </div>
                    </div>

                    <!-- Logo Text Styles -->
                    <div
                        v-if="formData.logo_type === 'text'"
                        class="style-section"
                    >
                        <h4 class="subsection-title">Logo Text</h4>
                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label">Font Family</label>
                                <select
                                    v-model="
                                        formData.logo_styles.text.font_family
                                    "
                                    class="form-select"
                                >
                                    <option value="'Poppins', sans-serif">
                                        Poppins
                                    </option>
                                    <option value="'Inter', sans-serif">
                                        Inter
                                    </option>
                                    <option value="'Roboto', sans-serif">
                                        Roboto
                                    </option>
                                    <option value="'Open Sans', sans-serif">
                                        Open Sans
                                    </option>
                                    <option value="'Lato', sans-serif">
                                        Lato
                                    </option>
                                    <option value="'Montserrat', sans-serif">
                                        Montserrat
                                    </option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Font Size</label>
                                <input
                                    v-model="
                                        formData.logo_styles.text.font_size
                                    "
                                    type="text"
                                    class="form-input"
                                    placeholder="24px"
                                />
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label">Font Weight</label>
                                <select
                                    v-model="
                                        formData.logo_styles.text.font_weight
                                    "
                                    class="form-select"
                                >
                                    <option value="300">Light (300)</option>
                                    <option value="400">Normal (400)</option>
                                    <option value="500">Medium (500)</option>
                                    <option value="600">Semi Bold (600)</option>
                                    <option value="700">Bold (700)</option>
                                    <option value="800">
                                        Extra Bold (800)
                                    </option>
                                    <option value="900">Black (900)</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Text Color</label>
                                <div class="color-input-group">
                                    <input
                                        v-model="
                                            formData.logo_styles.text.color
                                        "
                                        type="color"
                                        class="color-input"
                                    />
                                    <input
                                        v-model="
                                            formData.logo_styles.text.color
                                        "
                                        type="text"
                                        class="form-input"
                                        placeholder="#ffffff"
                                    />
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Logo Image Styles -->
                    <div
                        v-if="formData.logo_type === 'image'"
                        class="style-section"
                    >
                        <h4 class="subsection-title">Logo Image</h4>
                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label">Width</label>
                                <input
                                    v-model="formData.logo_styles.image.width"
                                    type="text"
                                    class="form-input"
                                    placeholder="180px"
                                />
                            </div>
                            <div class="form-group">
                                <label class="form-label">Height</label>
                                <input
                                    v-model="formData.logo_styles.image.height"
                                    type="text"
                                    class="form-input"
                                    placeholder="auto"
                                />
                            </div>
                        </div>
                    </div>

                    <!-- Tagline Styles -->
                    <div v-if="formData.logo_tagline" class="style-section">
                        <h4 class="subsection-title">Tagline</h4>
                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label">Font Size</label>
                                <input
                                    v-model="formData.tagline_styles.font_size"
                                    type="text"
                                    class="form-input"
                                    placeholder="14px"
                                />
                            </div>
                            <div class="form-group">
                                <label class="form-label">Text Color</label>
                                <div class="color-input-group">
                                    <input
                                        v-model="formData.tagline_styles.color"
                                        type="color"
                                        class="color-input"
                                    />
                                    <input
                                        v-model="formData.tagline_styles.color"
                                        type="text"
                                        class="form-input"
                                        placeholder="#d1d5db"
                                    />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Advanced Tab -->
                <div v-show="activeTab === 'advanced'" class="tab-content">
                    <h3 class="section-title">Advanced Settings</h3>

                    <div class="form-group">
                        <label class="form-label">Status</label>
                        <div class="toggle-group">
                            <label class="toggle-switch">
                                <input
                                    v-model="formData.is_active"
                                    type="checkbox"
                                />
                                <span class="toggle-slider"></span>
                            </label>
                            <span class="toggle-label">
                                {{ formData.is_active ? "Active" : "Inactive" }}
                            </span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Sort Order</label>
                        <input
                            v-model.number="formData.sort_order"
                            type="number"
                            class="form-input"
                            min="1"
                        />
                        <small class="form-help"
                            >Lower numbers appear first</small
                        >
                    </div>

                    <div class="advanced-options">
                        <h4 class="subsection-title">Animation & Behavior</h4>

                        <div class="checkbox-group">
                            <label class="checkbox-option">
                                <input
                                    v-model="
                                        formData.advanced_settings
                                            .enableAnimations
                                    "
                                    type="checkbox"
                                />
                                <span class="checkbox-custom"></span>
                                Enable Animations
                            </label>
                            <label class="checkbox-option">
                                <input
                                    v-model="
                                        formData.advanced_settings.stickyHeader
                                    "
                                    type="checkbox"
                                />
                                <span class="checkbox-custom"></span>
                                Sticky Header
                            </label>
                            <label class="checkbox-option">
                                <input
                                    v-model="
                                        formData.advanced_settings.hideOnMobile
                                    "
                                    type="checkbox"
                                />
                                <span class="checkbox-custom"></span>
                                Hide on Mobile
                            </label>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label"
                                    >Transition Duration</label
                                >
                                <input
                                    v-model="
                                        formData.advanced_settings
                                            .transitionDuration
                                    "
                                    type="text"
                                    class="form-input"
                                    placeholder="0.3s"
                                />
                            </div>
                            <div class="form-group">
                                <label class="form-label">Z-Index</label>
                                <input
                                    v-model.number="
                                        formData.advanced_settings.zIndex
                                    "
                                    type="number"
                                    class="form-input"
                                    placeholder="1000"
                                />
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Preview Tab -->
                <div v-show="activeTab === 'preview'" class="tab-content">
                    <h3 class="section-title">Live Preview</h3>
                    <div class="preview-container">
                        <div
                            class="preview-header"
                            :style="getPreviewNavStyles()"
                        >
                            <div class="preview-content">
                                <div class="preview-logo">
                                    <a href="#" class="preview-logo-link">
                                        <img
                                            v-if="
                                                formData.logo_type ===
                                                    'image' &&
                                                (logoPreview ||
                                                    formData.logo_image_path)
                                            "
                                            :src="
                                                logoPreview ||
                                                formData.logo_image_path
                                            "
                                            alt="Logo"
                                            :style="getPreviewLogoImageStyles()"
                                        />
                                        <div
                                            v-else
                                            class="preview-text-logo"
                                            :style="getPreviewLogoTextStyles()"
                                        >
                                            <span>{{
                                                formData.logo_text ||
                                                "Your Brand"
                                            }}</span>
                                            <small
                                                v-if="formData.logo_tagline"
                                                :style="
                                                    getPreviewTaglineStyles()
                                                "
                                            >
                                                {{ formData.logo_tagline }}
                                            </small>
                                        </div>
                                    </a>
                                    <div class="logo-link-info">
                                        <small
                                            >Links to:
                                            {{
                                                formData.logo_link.url || "/"
                                            }}</small
                                        >
                                    </div>
                                </div>
                                <div class="preview-buttons">
                                    <a
                                        v-for="(
                                            button, index
                                        ) in formData.buttons"
                                        :key="index"
                                        href="#"
                                        class="preview-btn"
                                        :style="getButtonPreviewStyle(button)"
                                    >
                                        {{ button.text || "Button" }}
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="form-actions">
                <button
                    type="button"
                    @click="$emit('cancel')"
                    class="btn btn-outline"
                >
                    Cancel
                </button>
                <button
                    type="submit"
                    class="btn btn-primary"
                    :disabled="saving"
                >
                    <span v-if="saving" class="btn-spinner"></span>
                    {{
                        saving
                            ? "Saving..."
                            : isEditing
                            ? "Update Header"
                            : "Create Header"
                    }}
                </button>
            </div>
        </form>
    </div>
</template>

<script>
export default {
    name: "HeaderEditor",
    props: {
        header: {
            type: Object,
            default: () => ({}),
        },
    },
    emits: ["save", "cancel", "error"],
    data() {
        return {
            activeTab: "basic",
            saving: false,
            logoPreview: null,
            logoFile: null,
            formData: {
                name: "",
                logo_type: "text",
                logo_text: "Your Brand",
                logo_image_path: null,
                logo_tagline: "",
                logo_link: {
                    url: "/",
                    target: "_self",
                },
                buttons: [
                    {
                        text: "Get Started",
                        url: "/get-started",
                        style: "filled",
                        size: "md",
                        custom_styles: {
                            background_color: "#ff7101",
                            text_color: "#ffffff",
                            border_color: "#ff7101",
                            border_radius: "5px",
                            hover_background_color: "#e06500",
                            hover_text_color: "#ffffff",
                            hover_border_color: "#e06500",
                            opacity: "1",
                        },
                    },
                ],
                logo_styles: {
                    image: { width: "180px", height: "auto" },
                    text: {
                        font_family: "'Poppins', sans-serif",
                        font_size: "24px",
                        font_weight: "700",
                        color: "#ffffff",
                    },
                },
                tagline_styles: {
                    font_size: "14px",
                    color: "#d1d5db",
                },
                nav_styles: {
                    background: "#102e4a",
                    padding: "0 20px",
                    box_shadow: "0 2px 10px rgba(0,0,0,0.1)",
                },
                advanced_settings: {
                    enableAnimations: true,
                    stickyHeader: false,
                    hideOnMobile: false,
                    transitionDuration: "0.3s",
                    zIndex: 1000,
                },
                is_active: false,
                sort_order: 1,
            },
            tabs: [
                {
                    key: "basic",
                    label: "Basic Info",
                    icon: "InfoIcon",
                },
                {
                    key: "buttons",
                    label: "Buttons",
                    icon: "ButtonIcon",
                },
                {
                    key: "styles",
                    label: "Styles",
                    icon: "PaletteIcon",
                },
                {
                    key: "advanced",
                    label: "Advanced",
                    icon: "SettingsIcon",
                },
                {
                    key: "preview",
                    label: "Preview",
                    icon: "EyeIcon",
                },
            ],
        };
    },
    computed: {
        isEditing() {
            return !!(this.header && this.header.id);
        },
    },
    watch: {
        header: {
            handler(newHeader) {
                if (newHeader && Object.keys(newHeader).length > 0) {
                    this.formData = { ...this.formData, ...newHeader };

                    // Ensure logo_link has proper structure
                    if (
                        !this.formData.logo_link ||
                        typeof this.formData.logo_link !== "object"
                    ) {
                        this.formData.logo_link = {
                            url: "/",
                            target: "_self",
                        };
                    }

                    // Ensure buttons have all required fields
                    if (
                        this.formData.buttons &&
                        Array.isArray(this.formData.buttons)
                    ) {
                        this.formData.buttons = this.formData.buttons.map(
                            (button) => ({
                                text: button.text || "Button",
                                url: button.url || "#",
                                style: button.style || "filled",
                                size: button.size || "md",
                                custom_styles: {
                                    background_color: "#ff7101",
                                    text_color: "#ffffff",
                                    border_color: "#ff7101",
                                    border_radius: "5px",
                                    hover_background_color: "#e06500",
                                    hover_text_color: "#ffffff",
                                    hover_border_color: "#e06500",
                                    opacity: "1",
                                    ...button.custom_styles,
                                },
                            })
                        );
                    }
                }
            },
            immediate: true,
            deep: true,
        },
    },
    methods: {
        handleSubmit() {
            this.saving = true;

            const formData = new FormData();

            // Add all form fields
            Object.keys(this.formData).forEach((key) => {
                const value = this.formData[key];
                if (typeof value === "object" && value !== null) {
                    formData.append(key, JSON.stringify(value));
                } else {
                    formData.append(key, value || "");
                }
            });

            // Add logo file if uploaded
            if (this.logoFile) {
                formData.append("logo_image", this.logoFile);
            }

            // IMPORTANT FIX: Add _method parameter for Laravel to handle PUT/PATCH requests with files
            if (this.isEditing) {
                formData.append("_method", "POST"); // Use POST method spoofing for updates with files
            }

            this.$emit("save", formData);

            // Reset saving state after a delay
            setTimeout(() => {
                this.saving = false;
            }, 2000);
        },

        handleLogoUpload(event) {
            const file = event.target.files[0];
            if (file) {
                this.processLogoFile(file);
            }
        },

        handleLogoDrop(event) {
            const file = event.dataTransfer.files[0];
            if (file && file.type.startsWith("image/")) {
                this.processLogoFile(file);
            }
        },

        processLogoFile(file) {
            if (file.size > 2 * 1024 * 1024) {
                // 2MB limit
                this.$emit("error", "Logo file size must be less than 2MB");
                return;
            }

            this.logoFile = file;

            // Create preview
            const reader = new FileReader();
            reader.onload = (e) => {
                this.logoPreview = e.target.result;
            };
            reader.readAsDataURL(file);
        },

        addButton() {
            this.formData.buttons.push({
                text: "New Button",
                url: "#",
                style: "filled",
                size: "md",
                custom_styles: {
                    background_color: "#ff7101",
                    text_color: "#ffffff",
                    border_color: "#ff7101",
                    border_radius: "5px",
                    hover_background_color: "#e06500",
                    hover_text_color: "#ffffff",
                    hover_border_color: "#e06500",
                    opacity: "1",
                },
            });
        },

        removeButton(index) {
            this.formData.buttons.splice(index, 1);
        },

        getButtonPreviewStyle(button) {
            const custom = button.custom_styles || {};
            const size = this.getButtonSize(button.size || "md");

            let style = {
                ...size,
                borderRadius: custom.border_radius || "5px",
                cursor: "pointer",
                transition: "all 0.3s ease",
                opacity: custom.opacity || "1",
                textDecoration: "none",
                display: "inline-block",
                fontWeight: "500",
            };

            // Apply different styles based on button type
            switch (button.style) {
                case "filled":
                    style.backgroundColor =
                        custom.background_color || "#ff7101";
                    style.color = custom.text_color || "#ffffff";
                    style.border = `2px solid ${
                        custom.border_color ||
                        custom.background_color ||
                        "#ff7101"
                    }`;
                    break;
                case "outlined":
                    style.backgroundColor = "transparent";
                    style.color =
                        custom.text_color || custom.border_color || "#ff7101";
                    style.border = `2px solid ${
                        custom.border_color || "#ff7101"
                    }`;
                    break;
                case "ghost":
                    style.backgroundColor = "transparent";
                    style.color = custom.text_color || "#ffffff";
                    style.border = "2px solid transparent";
                    break;
                case "transparent":
                    style.backgroundColor =
                        custom.background_color || "transparent";
                    style.color = custom.text_color || "#ffffff";
                    style.border = custom.border_color
                        ? `2px solid ${custom.border_color}`
                        : "2px solid transparent";
                    break;
                default:
                    style.backgroundColor =
                        custom.background_color || "#ff7101";
                    style.color = custom.text_color || "#ffffff";
                    style.border = `2px solid ${
                        custom.border_color ||
                        custom.background_color ||
                        "#ff7101"
                    }`;
            }

            return style;
        },

        getButtonSize(size) {
            const sizes = {
                sm: { padding: "6px 12px", fontSize: "0.875rem" },
                md: { padding: "8px 16px", fontSize: "0.95rem" },
                lg: { padding: "10px 20px", fontSize: "1.1rem" },
            };
            return sizes[size] || sizes.md;
        },

        getDefaultButtonColor(style) {
            const colors = {
                filled: "#ff7101",
                outlined: "#ff7101",
                ghost: "#ffffff",
                transparent: "transparent",
            };
            return colors[style] || "#ff7101";
        },

        getPreviewNavStyles() {
            const styles = this.formData.nav_styles || {};
            return {
                background: styles.background || "#102e4a",
                padding: styles.padding || "0 20px",
                boxShadow: styles.box_shadow || "0 2px 10px rgba(0,0,0,0.1)",
                minHeight: "60px",
                display: "flex",
                alignItems: "center",
            };
        },

        getPreviewLogoImageStyles() {
            const styles = this.formData.logo_styles?.image || {};
            return {
                width: styles.width || "180px",
                height: styles.height || "auto",
                maxHeight: "50px",
            };
        },

        getPreviewLogoTextStyles() {
            const styles = this.formData.logo_styles?.text || {};
            return {
                fontFamily: styles.font_family || "'Poppins', sans-serif",
                fontSize: styles.font_size || "24px",
                fontWeight: styles.font_weight || "700",
                color: styles.color || "#ffffff",
            };
        },

        getPreviewTaglineStyles() {
            const styles = this.formData.tagline_styles || {};
            return {
                fontSize: styles.font_size || "14px",
                color: styles.color || "#d1d5db",
                marginTop: "2px",
                display: "block",
            };
        },
    },
    components: {
        InfoIcon: {
            template: `<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>`,
        },
        ButtonIcon: {
            template: `<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"/><line x1="9" y1="12" x2="15" y2="12"/></svg>`,
        },
        PaletteIcon: {
            template: `<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="13.5" cy="6.5" r=".5"/><circle cx="17.5" cy="10.5" r=".5"/><circle cx="8.5" cy="7.5" r=".5"/><circle cx="6.5" cy="12.5" r=".5"/><path d="M12 2C6.5 2 2 6.5 2 12s4.5 10 10 10c.926 0 1.648-.746 1.648-1.688 0-.437-.18-.835-.437-1.125-.29-.289-.438-.652-.438-1.125a1.64 1.64 0 0 1 1.668-1.668h1.996c3.051 0 5.555-2.503 5.555-5.554C21.965 6.012 17.461 2 12 2z"/></svg>`,
        },
        SettingsIcon: {
            template: `<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="3"/><path d="M12 1v6m0 6v6m11-7h-6m-6 0H1m15.5-3.5L19 9l-1.5-1.5M6.5 14.5L5 16l1.5 1.5"/></svg>`,
        },
        EyeIcon: {
            template: `<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>`,
        },
    },
};
</script>

<style scoped>
.header-editor {
    background: white;
}

.editor-form {
    display: flex;
    flex-direction: column;
    height: 100%;
}

/* Tabs */
.editor-tabs {
    display: flex;
    border-bottom: 1px solid #e2e8f0;
    background: #f8fafc;
    padding: 0 24px;
}

.tab-btn {
    background: none;
    border: none;
    padding: 16px 20px;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 8px;
    color: #64748b;
    font-weight: 500;
    border-bottom: 3px solid transparent;
    transition: all 0.2s;
}

.tab-btn:hover {
    color: #374151;
    background: rgba(255, 113, 1, 0.05);
}

.tab-btn.active {
    color: #ff7101;
    border-bottom-color: #ff7101;
    background: rgba(255, 113, 1, 0.05);
}

.tab-icon {
    flex-shrink: 0;
}

/* Content */
.editor-content {
    flex: 1;
    overflow: auto;
    padding: 24px;
}

.tab-content {
    max-width: 800px;
}

.section-title {
    margin: 0 0 24px 0;
    color: #1e293b;
    font-size: 1.25rem;
    font-weight: 600;
}

.section-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 24px;
}

.subsection-title {
    margin: 0 0 16px 0;
    color: #374151;
    font-size: 1.1rem;
    font-weight: 600;
}

/* Form elements */
.form-group {
    margin-bottom: 20px;
}

.form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 16px;
    margin-bottom: 20px;
}

.form-label {
    display: block;
    margin-bottom: 6px;
    color: #374151;
    font-weight: 500;
    font-size: 0.9rem;
}

.form-input,
.form-select {
    width: 100%;
    padding: 10px 12px;
    border: 2px solid #e2e8f0;
    border-radius: 6px;
    font-size: 0.9rem;
    transition: border-color 0.2s;
}

.form-input:focus,
.form-select:focus {
    outline: none;
    border-color: #ff7101;
}

.form-help {
    display: block;
    margin-top: 4px;
    color: #64748b;
    font-size: 0.8rem;
}

/* Input groups */
.input-group {
    display: flex;
    gap: 8px;
}

.input-group .form-input {
    flex: 1;
}

.input-group .form-select {
    min-width: 140px;
}

.color-input-group {
    display: flex;
    gap: 8px;
}

.color-input {
    width: 50px;
    height: 40px;
    padding: 2px;
    border: 2px solid #e2e8f0;
    border-radius: 6px;
    cursor: pointer;
}

.color-input-group .form-input {
    flex: 1;
}

/* Radio and checkbox groups */
.radio-group,
.checkbox-group {
    display: flex;
    gap: 16px;
    flex-wrap: wrap;
}

.radio-option,
.checkbox-option {
    display: flex;
    align-items: center;
    gap: 8px;
    cursor: pointer;
    color: #374151;
}

.radio-custom,
.checkbox-custom {
    width: 18px;
    height: 18px;
    border: 2px solid #d1d5db;
    border-radius: 50%;
    position: relative;
    transition: all 0.2s;
}

.checkbox-custom {
    border-radius: 3px;
}

.radio-option input,
.checkbox-option input {
    display: none;
}

.radio-option input:checked + .radio-custom {
    border-color: #ff7101;
}

.radio-option input:checked + .radio-custom::after {
    content: "";
    width: 8px;
    height: 8px;
    background: #ff7101;
    border-radius: 50%;
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
}

.checkbox-option input:checked + .checkbox-custom {
    border-color: #ff7101;
    background: #ff7101;
}

.checkbox-option input:checked + .checkbox-custom::after {
    content: "✓";
    color: white;
    font-size: 12px;
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
}

/* Toggle switch */
.toggle-group {
    display: flex;
    align-items: center;
    gap: 12px;
}

.toggle-switch {
    position: relative;
    width: 48px;
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
    background-color: #cbd5e1;
    transition: 0.3s;
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
    transition: 0.3s;
    border-radius: 50%;
}

input:checked + .toggle-slider {
    background-color: #ff7101;
}

input:checked + .toggle-slider:before {
    transform: translateX(24px);
}

.toggle-label {
    font-weight: 500;
    color: #374151;
}

/* File upload */
.file-upload-area {
    margin-top: 8px;
}

.file-input {
    display: none;
}

.upload-dropzone {
    border: 2px dashed #cbd5e1;
    border-radius: 8px;
    padding: 24px;
    text-align: center;
    cursor: pointer;
    transition: all 0.2s;
    background: #f8fafc;
}

.upload-dropzone:hover {
    border-color: #ff7101;
    background: #fff5eb;
}

.logo-preview {
    max-width: 200px;
    max-height: 100px;
    object-fit: contain;
    border-radius: 4px;
}

.upload-placeholder {
    color: #64748b;
}

.upload-placeholder svg {
    margin: 0 auto 12px;
    color: #cbd5e1;
}

.upload-placeholder p {
    margin: 0 0 4px 0;
    font-weight: 500;
}

.upload-placeholder small {
    color: #9ca3af;
}

/* Button editor */
.buttons-list {
    display: flex;
    flex-direction: column;
    gap: 24px;
}

.button-editor {
    border: 2px solid #f1f5f9;
    border-radius: 12px;
    padding: 20px;
    background: #fafbfc;
}

.button-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 16px;
}

.button-header h4 {
    margin: 0;
    color: #374151;
    font-size: 1rem;
    font-weight: 600;
}

.btn-danger {
    color: #dc2626;
    border-color: #fecaca;
}

.btn-danger:hover {
    background: #fef2f2;
    border-color: #f87171;
}

.button-form {
    display: flex;
    flex-direction: column;
    gap: 16px;
}

.button-preview {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 16px;
    background: white;
    border-radius: 8px;
    border: 1px solid #e2e8f0;
}

.preview-label {
    font-weight: 500;
    color: #374151;
    min-width: 60px;
}

.preview-button {
    border: none;
    font-weight: 500;
    text-decoration: none;
    display: inline-block;
}

/* Custom styles toggle */
.custom-styles-toggle {
    margin-top: 16px;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    background: white;
}

.custom-styles-toggle summary {
    padding: 12px 16px;
    cursor: pointer;
    font-weight: 500;
    color: #374151;
    list-style: none;
    user-select: none;
}

.custom-styles-toggle summary::-webkit-details-marker {
    display: none;
}

.custom-styles-toggle summary::before {
    content: "▶";
    margin-right: 8px;
    transition: transform 0.2s;
}

.custom-styles-toggle[open] summary::before {
    transform: rotate(90deg);
}

.custom-styles-content {
    padding: 16px;
    border-top: 1px solid #f1f5f9;
}

.color-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 16px;
    margin-bottom: 16px;
}

.color-group {
    display: flex;
    flex-direction: column;
    gap: 6px;
}

/* Style sections */
.style-section {
    margin-bottom: 32px;
    padding: 20px;
    background: #f8fafc;
    border-radius: 12px;
    border: 1px solid #e2e8f0;
}

/* Preview */
.preview-container {
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    overflow: hidden;
    margin-top: 16px;
}

.preview-header {
    min-height: 80px;
    display: flex;
    align-items: center;
}

.preview-content {
    display: flex;
    justify-content: space-between;
    align-items: center;
    width: 100%;
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 20px;
}

.preview-logo {
    display: flex;
    flex-direction: column;
    align-items: flex-start;
    gap: 4px;
}

.preview-logo-link {
    display: flex;
    align-items: center;
    gap: 12px;
    text-decoration: none;
    color: inherit;
}

.preview-text-logo {
    display: flex;
    flex-direction: column;
    align-items: flex-start;
}

.logo-link-info {
    margin-top: 4px;
}

.logo-link-info small {
    color: #9ca3af;
    font-size: 0.75rem;
}

.preview-buttons {
    display: flex;
    gap: 12px;
    align-items: center;
}

.preview-btn {
    border: none;
    border-radius: 6px;
    font-weight: 500;
    cursor: pointer;
    text-decoration: none;
    display: inline-block;
    transition: all 0.3s ease;
}

.preview-btn:hover {
    transform: translateY(-1px);
}

/* Form actions */
.form-actions {
    padding: 20px 24px;
    border-top: 1px solid #e2e8f0;
    background: #f8fafc;
    display: flex;
    justify-content: flex-end;
    gap: 12px;
}

.btn {
    padding: 10px 20px;
    border-radius: 8px;
    font-weight: 500;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 8px;
    transition: all 0.2s;
    text-decoration: none;
    border: 2px solid;
}

.btn-primary {
    background: #ff7101;
    color: white;
    border-color: #ff7101;
}

.btn-primary:hover:not(:disabled) {
    background: #e06500;
    border-color: #e06500;
}

.btn-outline {
    background: white;
    color: #374151;
    border-color: #d1d5db;
}

.btn-outline:hover {
    background: #f9fafb;
    border-color: #9ca3af;
}

.btn-sm {
    padding: 8px 16px;
    font-size: 0.875rem;
}

.btn:disabled {
    opacity: 0.6;
    cursor: not-allowed;
}

.btn-spinner {
    width: 16px;
    height: 16px;
    border: 2px solid transparent;
    border-top: 2px solid currentColor;
    border-radius: 50%;
    animation: spin 1s linear infinite;
}

.btn-icon {
    width: 32px;
    height: 32px;
    padding: 0;
    border-radius: 6px;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.2s;
    background: white;
    border: 1px solid #e2e8f0;
    color: #64748b;
}

.btn-icon:hover {
    background: #f1f5f9;
    border-color: #cbd5e1;
}

@keyframes spin {
    0% {
        transform: rotate(0deg);
    }
    100% {
        transform: rotate(360deg);
    }
}

/* Responsive design */
@media (max-width: 768px) {
    .editor-tabs {
        padding: 0 16px;
        overflow-x: auto;
    }

    .tab-btn {
        padding: 12px 16px;
        white-space: nowrap;
    }

    .editor-content {
        padding: 16px;
    }

    .form-row,
    .color-row {
        grid-template-columns: 1fr;
        gap: 12px;
    }

    .input-group {
        flex-direction: column;
    }

    .radio-group,
    .checkbox-group {
        flex-direction: column;
        gap: 12px;
    }

    .button-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 12px;
    }

    .preview-content {
        flex-direction: column;
        gap: 16px;
        text-align: center;
        padding: 20px 16px;
    }

    .preview-buttons {
        flex-wrap: wrap;
        justify-content: center;
    }

    .form-actions {
        flex-direction: column-reverse;
        gap: 8px;
    }

    .form-actions .btn {
        width: 100%;
        justify-content: center;
    }
}

@media (max-width: 480px) {
    .editor-tabs {
        padding: 0 8px;
    }

    .tab-btn {
        padding: 10px 12px;
        font-size: 0.875rem;
    }

    .editor-content {
        padding: 12px;
    }

    .form-actions {
        padding: 16px;
    }

    .upload-dropzone {
        padding: 16px;
    }

    .button-editor {
        padding: 16px;
    }

    .style-section {
        padding: 16px;
    }
}
</style>
