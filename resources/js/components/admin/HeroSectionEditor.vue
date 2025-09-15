<template>
    <div class="hero-editor">
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
                    {{ tab.label }}
                </button>
            </div>

            <div class="editor-content">
                <!-- Basic Settings Tab -->
                <div v-show="activeTab === 'basic'" class="tab-content">
                    <h3 class="section-title">Basic Information</h3>

                    <div class="form-group">
                        <label class="form-label">Section Name</label>
                        <input
                            v-model="formData.section_name"
                            type="text"
                            class="form-input"
                            placeholder="e.g., Main Hero Section, Landing Hero, Product Hero"
                            required
                        />
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Main Title</label>
                            <input
                                v-model="formData.title"
                                type="text"
                                class="form-input"
                                placeholder="Welcome To"
                            />
                        </div>
                        <div class="form-group">
                            <label class="form-label">Title Highlight</label>
                            <input
                                v-model="formData.title_highlight"
                                type="text"
                                class="form-input"
                                placeholder="Edu World"
                            />
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Subtitle</label>
                        <input
                            v-model="formData.subtitle"
                            type="text"
                            class="form-input"
                            placeholder="A Complete ERP System"
                        />
                    </div>

                    <div class="form-group">
                        <label class="form-label">Tagline</label>
                        <textarea
                            v-model="formData.tagline"
                            class="form-textarea"
                            rows="3"
                            placeholder="A complete system for educational institution management."
                        ></textarea>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Status</label>
                        <div class="toggle-group">
                            <label class="toggle-switch">
                                <input
                                    v-model="formData.is_active"
                                    type="checkbox"
                                    class="toggle-switch-input"
                                />
                                <span class="toggle-slider"></span>
                            </label>
                            <span class="toggle-label">
                                {{ formData.is_active ? "Active" : "Inactive" }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Backgrounds Tab -->
                <div v-show="activeTab === 'backgrounds'" class="tab-content">
                    <h3 class="section-title">Background Settings</h3>

                    <!-- Background Images -->
                    <div class="background-section">
                        <h4 class="subsection-title">Background Images</h4>
                        <div class="form-group">
                            <label class="form-label"
                                >Upload Background Images</label
                            >
                            <div class="file-upload-area">
                                <input
                                    ref="backgroundUpload"
                                    type="file"
                                    @change="handleBackgroundUpload"
                                    accept="image/*"
                                    multiple
                                    class="file-input"
                                />
                                <div
                                    class="upload-dropzone"
                                    @click="$refs.backgroundUpload.click()"
                                    @dragover.prevent
                                    @drop.prevent="handleBackgroundDrop"
                                >
                                    <div class="upload-placeholder">
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
                                            <polyline
                                                points="21,15 16,10 5,21"
                                            />
                                        </svg>
                                        <p>
                                            Click to upload images or drag and
                                            drop
                                        </p>
                                        <small
                                            >PNG, JPG, GIF up to 2MB each
                                            (multiple files allowed)</small
                                        >
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Background Images List -->
                        <div
                            v-if="
                                formData.background_images &&
                                formData.background_images.length > 0
                            "
                            class="images-grid"
                        >
                            <div
                                v-for="(
                                    image, index
                                ) in formData.background_images"
                                :key="index"
                                class="image-item"
                            >
                                <img
                                    :src="
                                        typeof image === 'string'
                                            ? image
                                            : image.url
                                    "
                                    :alt="`Background ${index + 1}`"
                                />
                                <button
                                    type="button"
                                    @click="removeBackgroundImage(index)"
                                    class="remove-image-btn"
                                >
                                    <svg
                                        width="16"
                                        height="16"
                                        viewBox="0 0 24 24"
                                        fill="none"
                                        stroke="currentColor"
                                        stroke-width="2"
                                    >
                                        <line x1="18" y1="6" x2="6" y2="18" />
                                        <line x1="6" y1="6" x2="18" y2="18" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Slider Settings -->
                    <div class="background-section">
                        <h4 class="subsection-title">Slider Settings</h4>
                        <div class="form-group">
                            <div class="toggle-group">
                                <label class="toggle-switch">
                                    <input
                                        v-model="formData.enable_slider"
                                        type="checkbox"
                                        class="toggle-switch-input"
                                    />
                                    <span class="toggle-slider"></span>
                                </label>
                                <span class="toggle-label"
                                    >Enable Background Slider</span
                                >
                            </div>
                        </div>

                        <div v-if="formData.enable_slider" class="form-group">
                            <label class="form-label"
                                >Slider Interval (ms)</label
                            >
                            <select
                                v-model.number="formData.slider_interval"
                                class="form-select"
                            >
                                <option value="2000">2 seconds</option>
                                <option value="3000">3 seconds</option>
                                <option value="3500">3.5 seconds</option>
                                <option value="4000">4 seconds</option>
                                <option value="5000">5 seconds</option>
                                <option value="7000">7 seconds</option>
                                <option value="10000">10 seconds</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Text Styles Tab -->
                <div v-show="activeTab === 'text'" class="tab-content">
                    <h3 class="section-title">Text Styling</h3>

                    <!-- Title Styles -->
                    <div class="style-section">
                        <h4 class="subsection-title">Main Title</h4>
                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label">Font Size</label>
                                <input
                                    v-model="
                                        formData.text_styles.title.fontSize
                                    "
                                    type="text"
                                    class="form-input"
                                    placeholder="1.9rem"
                                />
                            </div>
                            <div class="form-group">
                                <label class="form-label">Font Weight</label>
                                <select
                                    v-model="
                                        formData.text_styles.title.fontWeight
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
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label">Text Color</label>
                                <div class="color-input-group">
                                    <input
                                        v-model="
                                            formData.text_styles.title.color
                                        "
                                        type="color"
                                        class="color-input"
                                    />
                                    <input
                                        v-model="
                                            formData.text_styles.title.color
                                        "
                                        type="text"
                                        class="form-input"
                                        placeholder="#ffffff"
                                    />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Margin Bottom</label>
                                <input
                                    v-model="
                                        formData.text_styles.title.marginBottom
                                    "
                                    type="text"
                                    class="form-input"
                                    placeholder="8px"
                                />
                            </div>
                        </div>
                    </div>

                    <!-- Title Highlight Styles -->
                    <div class="style-section">
                        <h4 class="subsection-title">Title Highlight</h4>
                        <div class="form-group">
                            <label class="form-label">Highlight Color</label>
                            <div class="color-input-group">
                                <input
                                    v-model="
                                        formData.text_styles.title_highlight
                                            .color
                                    "
                                    type="color"
                                    class="color-input"
                                />
                                <input
                                    v-model="
                                        formData.text_styles.title_highlight
                                            .color
                                    "
                                    type="text"
                                    class="form-input"
                                    placeholder="#ff7101"
                                />
                            </div>
                        </div>
                    </div>

                    <!-- Subtitle Styles -->
                    <div class="style-section">
                        <h4 class="subsection-title">Subtitle</h4>
                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label">Font Size</label>
                                <input
                                    v-model="
                                        formData.text_styles.subtitle.fontSize
                                    "
                                    type="text"
                                    class="form-input"
                                    placeholder="1.5rem"
                                />
                            </div>
                            <div class="form-group">
                                <label class="form-label">Font Weight</label>
                                <select
                                    v-model="
                                        formData.text_styles.subtitle.fontWeight
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
                                </select>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label">Text Color</label>
                                <div class="color-input-group">
                                    <input
                                        v-model="
                                            formData.text_styles.subtitle.color
                                        "
                                        type="color"
                                        class="color-input"
                                    />
                                    <input
                                        v-model="
                                            formData.text_styles.subtitle.color
                                        "
                                        type="text"
                                        class="form-input"
                                        placeholder="#21bf6b"
                                    />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Margin Bottom</label>
                                <input
                                    v-model="
                                        formData.text_styles.subtitle
                                            .marginBottom
                                    "
                                    type="text"
                                    class="form-input"
                                    placeholder="12px"
                                />
                            </div>
                        </div>
                    </div>

                    <!-- Tagline Styles -->
                    <div class="style-section">
                        <h4 class="subsection-title">Tagline</h4>
                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label">Font Size</label>
                                <input
                                    v-model="
                                        formData.text_styles.tagline.fontSize
                                    "
                                    type="text"
                                    class="form-input"
                                    placeholder="2.2rem"
                                />
                            </div>
                            <div class="form-group">
                                <label class="form-label">Font Weight</label>
                                <select
                                    v-model="
                                        formData.text_styles.tagline.fontWeight
                                    "
                                    class="form-select"
                                >
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
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label">Text Color</label>
                                <div class="color-input-group">
                                    <input
                                        v-model="
                                            formData.text_styles.tagline.color
                                        "
                                        type="color"
                                        class="color-input"
                                    />
                                    <input
                                        v-model="
                                            formData.text_styles.tagline.color
                                        "
                                        type="text"
                                        class="form-input"
                                        placeholder="#f7b731"
                                    />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Line Height</label>
                                <input
                                    v-model="
                                        formData.text_styles.tagline.lineHeight
                                    "
                                    type="text"
                                    class="form-input"
                                    placeholder="1.05"
                                />
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Buttons Tab -->
                <div v-show="activeTab === 'buttons'" class="tab-content">
                    <div class="section-header">
                        <h3 class="section-title">Call-to-Action Buttons</h3>
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
                            v-for="(button, index) in formData.cta_buttons"
                            :key="index"
                            class="button-editor"
                        >
                            <div class="button-header">
                                <h4>Button {{ index + 1 }}</h4>
                                <button
                                    type="button"
                                    @click="removeButton(index)"
                                    class="btn-icon btn-danger"
                                    v-if="formData.cta_buttons.length > 1"
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
                                            placeholder="Apply now"
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
                                            placeholder="/apply or https://example.com"
                                        />
                                    </div>
                                </div>

                                <div class="form-row">
                                    <div class="form-group">
                                        <label class="form-label"
                                            >Button Type</label
                                        >
                                        <select
                                            v-model="button.type"
                                            class="form-select"
                                        >
                                            <option value="primary">
                                                Primary
                                            </option>
                                            <option value="secondary">
                                                Secondary
                                            </option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label"
                                            >Link Target</label
                                        >
                                        <select
                                            v-model="button.target"
                                            class="form-select"
                                        >
                                            <option value="_self">
                                                Same Window
                                            </option>
                                            <option value="_blank">
                                                New Window
                                            </option>
                                        </select>
                                    </div>
                                </div>

                                <!-- Button Styling -->
                                <div class="button-styling">
                                    <h5 class="styling-title">Button Styles</h5>
                                    <div class="form-row">
                                        <div class="form-group">
                                            <label class="form-label"
                                                >Background</label
                                            >
                                            <div class="color-input-group">
                                                <input
                                                    v-model="
                                                        button.styles.background
                                                    "
                                                    type="color"
                                                    class="color-input"
                                                />
                                                <input
                                                    v-model="
                                                        button.styles.background
                                                    "
                                                    type="text"
                                                    class="form-input"
                                                    placeholder="#20bf6b"
                                                />
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="form-label"
                                                >Text Color</label
                                            >
                                            <div class="color-input-group">
                                                <input
                                                    v-model="
                                                        button.styles.color
                                                    "
                                                    type="color"
                                                    class="color-input"
                                                />
                                                <input
                                                    v-model="
                                                        button.styles.color
                                                    "
                                                    type="text"
                                                    class="form-input"
                                                    placeholder="#fff"
                                                />
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-row">
                                        <div class="form-group">
                                            <label class="form-label"
                                                >Border</label
                                            >
                                            <input
                                                v-model="button.styles.border"
                                                type="text"
                                                class="form-input"
                                                placeholder="2px solid #20bf6b"
                                            />
                                        </div>
                                        <div class="form-group">
                                            <label class="form-label"
                                                >Border Radius</label
                                            >
                                            <input
                                                v-model="
                                                    button.styles.borderRadius
                                                "
                                                type="text"
                                                class="form-input"
                                                placeholder="6px"
                                            />
                                        </div>
                                    </div>

                                    <div class="form-row">
                                        <div class="form-group">
                                            <label class="form-label"
                                                >Padding</label
                                            >
                                            <input
                                                v-model="button.styles.padding"
                                                type="text"
                                                class="form-input"
                                                placeholder="10px 28px"
                                            />
                                        </div>
                                        <div class="form-group">
                                            <label class="form-label"
                                                >Font Weight</label
                                            >
                                            <select
                                                v-model="
                                                    button.styles.fontWeight
                                                "
                                                class="form-select"
                                            >
                                                <option value="400">
                                                    Normal (400)
                                                </option>
                                                <option value="500">
                                                    Medium (500)
                                                </option>
                                                <option value="600">
                                                    Semi Bold (600)
                                                </option>
                                                <option value="700">
                                                    Bold (700)
                                                </option>
                                            </select>
                                        </div>
                                    </div>

                                    <!-- Hover Styles -->
                                    <h5 class="styling-title">Hover Effects</h5>
                                    <div class="form-row">
                                        <div class="form-group">
                                            <label class="form-label"
                                                >Hover Background</label
                                            >
                                            <input
                                                v-model="
                                                    button.hover_styles
                                                        .background
                                                "
                                                type="text"
                                                class="form-input"
                                                placeholder="transparent"
                                            />
                                        </div>
                                        <div class="form-group">
                                            <label class="form-label"
                                                >Hover Text Color</label
                                            >
                                            <div class="color-input-group">
                                                <input
                                                    v-model="
                                                        button.hover_styles
                                                            .color
                                                    "
                                                    type="color"
                                                    class="color-input"
                                                />
                                                <input
                                                    v-model="
                                                        button.hover_styles
                                                            .color
                                                    "
                                                    type="text"
                                                    class="form-input"
                                                    placeholder="#20bf6b"
                                                />
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="form-label"
                                            >Hover Transform</label
                                        >
                                        <input
                                            v-model="
                                                button.hover_styles.transform
                                            "
                                            type="text"
                                            class="form-input"
                                            placeholder="translateY(-2px)"
                                        />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Layout Tab -->
                <div v-show="activeTab === 'layout'" class="tab-content">
                    <h3 class="section-title">Layout & Spacing</h3>

                    <!-- Section Dimensions -->
                    <div class="style-section">
                        <h4 class="subsection-title">Section Dimensions</h4>
                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label">Minimum Height</label>
                                <input
                                    v-model="formData.section_styles.minHeight"
                                    type="text"
                                    class="form-input"
                                    placeholder="80vh"
                                />
                            </div>
                            <div class="form-group">
                                <label class="form-label">Padding</label>
                                <input
                                    v-model="formData.section_styles.padding"
                                    type="text"
                                    class="form-input"
                                    placeholder="110px 20px 80px"
                                />
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label">Display</label>
                                <select
                                    v-model="formData.section_styles.display"
                                    class="form-select"
                                >
                                    <option value="block">Block</option>
                                    <option value="flex">Flex</option>
                                    <option value="grid">Grid</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Overflow</label>
                                <select
                                    v-model="formData.section_styles.overflow"
                                    class="form-select"
                                >
                                    <option value="hidden">Hidden</option>
                                    <option value="visible">Visible</option>
                                    <option value="auto">Auto</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Overlay Settings -->
                    <div class="style-section">
                        <h4 class="subsection-title">Overlay Settings</h4>
                        <div class="form-group">
                            <label class="form-label">Overlay Background</label>
                            <input
                                v-model="formData.overlay_styles.background"
                                type="text"
                                class="form-input"
                                placeholder="linear-gradient(135deg, rgba(6, 6, 7, 0.8), rgba(0, 30, 60, 0.7))"
                            />
                            <small class="form-help"
                                >Use CSS gradient or solid color. Example:
                                rgba(0,0,0,0.5) or linear-gradient(...)</small
                            >
                        </div>
                        <div class="form-group">
                            <label class="form-label">Overlay Opacity</label>
                            <input
                                v-model="formData.overlay_styles.opacity"
                                type="text"
                                class="form-input"
                                placeholder="1"
                            />
                        </div>
                        <div class="overlay-preview">
                            <div
                                class="preview-overlay"
                                :style="{
                                    background:
                                        formData.overlay_styles.background,
                                    opacity: formData.overlay_styles.opacity,
                                }"
                            >
                                <span>Overlay Preview</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Navigation Tab -->
                <div v-show="activeTab === 'navigation'" class="tab-content">
                    <h3 class="section-title">Navigation Settings</h3>

                    <div class="form-group">
                        <div class="toggle-group">
                            <label class="toggle-switch">
                                <input
                                    v-model="formData.show_navigation"
                                    type="checkbox"
                                    class="toggle-switch-input"
                                />
                                <span class="toggle-slider"></span>
                            </label>
                            <span class="toggle-label"
                                >Show Navigation Bar</span
                            >
                        </div>
                    </div>

                    <div v-if="formData.show_navigation">
                        <!-- Logo Settings -->
                        <div class="style-section">
                            <h4 class="subsection-title">Logo</h4>
                            <div class="form-group">
                                <label class="form-label">Logo Type</label>
                                <div class="radio-group">
                                    <label class="radio-option">
                                        <input
                                            v-model="
                                                formData.navigation.logo.type
                                            "
                                            type="radio"
                                            value="text"
                                        />
                                        <span class="radio-custom"></span>
                                        Text Logo
                                    </label>
                                    <label class="radio-option">
                                        <input
                                            v-model="
                                                formData.navigation.logo.type
                                            "
                                            type="radio"
                                            value="image"
                                        />
                                        <span class="radio-custom"></span>
                                        Image Logo
                                    </label>
                                </div>
                            </div>

                            <div
                                v-if="formData.navigation.logo.type === 'text'"
                                class="form-group"
                            >
                                <label class="form-label">Logo Text</label>
                                <input
                                    v-model="formData.navigation.logo.text"
                                    type="text"
                                    class="form-input"
                                    placeholder="Edu World"
                                />
                            </div>

                            <div
                                v-if="formData.navigation.logo.type === 'image'"
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
                                        class="upload-dropzone small"
                                        @click="$refs.logoUpload.click()"
                                        @dragover.prevent
                                        @drop.prevent="handleLogoDrop"
                                    >
                                        <img
                                            v-if="
                                                logoPreview ||
                                                formData.navigation.logo
                                                    .image_path
                                            "
                                            :src="getLogoUrl()"
                                            alt="Logo preview"
                                            class="logo-preview"
                                        />
                                        <div v-else class="upload-placeholder">
                                            <svg
                                                width="32"
                                                height="32"
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
                                                <circle
                                                    cx="8.5"
                                                    cy="8.5"
                                                    r="1.5"
                                                />
                                                <polyline
                                                    points="21,15 16,10 5,21"
                                                />
                                            </svg>
                                            <p>Upload Logo</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="form-label">Logo URL</label>
                                <input
                                    v-model="formData.navigation.logo.url"
                                    type="text"
                                    class="form-input"
                                    placeholder="#"
                                />
                            </div>

                            <div class="form-group">
                                <label class="form-label">Logo Alt Text</label>
                                <input
                                    v-model="formData.navigation.logo.alt"
                                    type="text"
                                    class="form-input"
                                    placeholder="Logo"
                                />
                            </div>
                        </div>

                        <!-- Menu Items -->
                        <div class="style-section">
                            <div class="section-header">
                                <h4 class="subsection-title">Menu Items</h4>
                                <button
                                    type="button"
                                    @click="addMenuItem"
                                    class="btn btn-outline btn-sm"
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
                                    Add Menu Item
                                </button>
                            </div>

                            <div class="menu-items-list">
                                <div
                                    v-for="(item, index) in formData.navigation
                                        .menu_items"
                                    :key="index"
                                    class="menu-item-editor"
                                >
                                    <div class="form-row">
                                        <div class="form-group">
                                            <label class="form-label"
                                                >Menu Title</label
                                            >
                                            <input
                                                v-model="item.title"
                                                type="text"
                                                class="form-input"
                                                placeholder="Home"
                                            />
                                        </div>
                                        <div class="form-group">
                                            <label class="form-label"
                                                >URL</label
                                            >
                                            <input
                                                v-model="item.url"
                                                type="text"
                                                class="form-input"
                                                placeholder="#"
                                            />
                                        </div>
                                        <div class="form-group">
                                            <label class="form-label"
                                                >Target</label
                                            >
                                            <select
                                                v-model="item.target"
                                                class="form-select"
                                            >
                                                <option value="_self">
                                                    Same Window
                                                </option>
                                                <option value="_blank">
                                                    New Window
                                                </option>
                                            </select>
                                        </div>
                                        <div class="form-group-small">
                                            <button
                                                type="button"
                                                @click="removeMenuItem(index)"
                                                class="btn-icon btn-danger"
                                            >
                                                <svg
                                                    width="16"
                                                    height="16"
                                                    viewBox="0 0 24 24"
                                                    fill="none"
                                                    stroke="currentColor"
                                                    stroke-width="2"
                                                >
                                                    <line
                                                        x1="18"
                                                        y1="6"
                                                        x2="6"
                                                        y2="18"
                                                    />
                                                    <line
                                                        x1="6"
                                                        y1="6"
                                                        x2="18"
                                                        y2="18"
                                                    />
                                                </svg>
                                            </button>
                                        </div>
                                    </div>

                                    <!-- Sub Items -->
                                    <div class="sub-items-section">
                                        <div class="sub-items-header">
                                            <label class="form-label"
                                                >Sub Menu Items</label
                                            >
                                            <button
                                                type="button"
                                                @click="addSubMenuItem(index)"
                                                class="btn btn-outline btn-xs"
                                            >
                                                <svg
                                                    width="14"
                                                    height="14"
                                                    viewBox="0 0 24 24"
                                                    fill="none"
                                                    stroke="currentColor"
                                                    stroke-width="2"
                                                >
                                                    <line
                                                        x1="12"
                                                        y1="5"
                                                        x2="12"
                                                        y2="19"
                                                    />
                                                    <line
                                                        x1="5"
                                                        y1="12"
                                                        x2="19"
                                                        y2="12"
                                                    />
                                                </svg>
                                                Add Sub Item
                                            </button>
                                        </div>
                                        <div
                                            v-if="
                                                item.sub_items &&
                                                item.sub_items.length > 0
                                            "
                                            class="sub-items-list"
                                        >
                                            <div
                                                v-for="(
                                                    subItem, subIndex
                                                ) in item.sub_items"
                                                :key="subIndex"
                                                class="sub-item-row"
                                            >
                                                <input
                                                    v-model="subItem.text"
                                                    type="text"
                                                    class="form-input"
                                                    placeholder="Sub menu text"
                                                />
                                                <input
                                                    v-model="subItem.url"
                                                    type="text"
                                                    class="form-input"
                                                    placeholder="Sub menu URL"
                                                />
                                                <button
                                                    type="button"
                                                    @click="
                                                        removeSubMenuItem(
                                                            index,
                                                            subIndex
                                                        )
                                                    "
                                                    class="btn-icon btn-danger btn-xs"
                                                >
                                                    <svg
                                                        width="12"
                                                        height="12"
                                                        viewBox="0 0 24 24"
                                                        fill="none"
                                                        stroke="currentColor"
                                                        stroke-width="2"
                                                    >
                                                        <line
                                                            x1="18"
                                                            y1="6"
                                                            x2="6"
                                                            y2="18"
                                                        />
                                                        <line
                                                            x1="6"
                                                            y1="6"
                                                            x2="18"
                                                            y2="18"
                                                        />
                                                    </svg>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Navigation Styles -->
                        <div class="style-section">
                            <h4 class="subsection-title">Navigation Styles</h4>
                            <div class="form-row">
                                <div class="form-group">
                                    <label class="form-label"
                                        >Background (Normal)</label
                                    >
                                    <input
                                        v-model="formData.nav_styles.background"
                                        type="text"
                                        class="form-input"
                                        placeholder="transparent"
                                    />
                                </div>
                                <div class="form-group">
                                    <label class="form-label"
                                        >Background (Scrolled)</label
                                    >
                                    <input
                                        v-model="
                                            formData.nav_styles
                                                .scrolled_background
                                        "
                                        type="text"
                                        class="form-input"
                                        placeholder="rgba(0, 30, 60, 0.95)"
                                    />
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group">
                                    <label class="form-label">Link Color</label>
                                    <div class="color-input-group">
                                        <input
                                            v-model="
                                                formData.nav_styles.link_color
                                            "
                                            type="color"
                                            class="color-input"
                                        />
                                        <input
                                            v-model="
                                                formData.nav_styles.link_color
                                            "
                                            type="text"
                                            class="form-input"
                                            placeholder="#fff"
                                        />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="form-label"
                                        >Link Hover Color</label
                                    >
                                    <div class="color-input-group">
                                        <input
                                            v-model="
                                                formData.nav_styles
                                                    .link_hover_color
                                            "
                                            type="color"
                                            class="color-input"
                                        />
                                        <input
                                            v-model="
                                                formData.nav_styles
                                                    .link_hover_color
                                            "
                                            type="text"
                                            class="form-input"
                                            placeholder="#d35b00"
                                        />
                                    </div>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group">
                                    <label class="form-label"
                                        >Dropdown Background</label
                                    >
                                    <div class="color-input-group">
                                        <input
                                            v-model="
                                                formData.nav_styles
                                                    .dropdown_background
                                            "
                                            type="color"
                                            class="color-input"
                                        />
                                        <input
                                            v-model="
                                                formData.nav_styles
                                                    .dropdown_background
                                            "
                                            type="text"
                                            class="form-input"
                                            placeholder="#fff"
                                        />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="form-label"
                                        >Dropdown Border</label
                                    >
                                    <div class="color-input-group">
                                        <input
                                            v-model="
                                                formData.nav_styles
                                                    .dropdown_border_color
                                            "
                                            type="color"
                                            class="color-input"
                                        />
                                        <input
                                            v-model="
                                                formData.nav_styles
                                                    .dropdown_border_color
                                            "
                                            type="text"
                                            class="form-input"
                                            placeholder="#ff7101"
                                        />
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="form-label"
                                    >Mobile Background</label
                                >
                                <input
                                    v-model="
                                        formData.nav_styles.mobile_background
                                    "
                                    type="text"
                                    class="form-input"
                                    placeholder="rgba(0, 30, 60, 0.95)"
                                />
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Advanced Tab -->
                <div v-show="activeTab === 'advanced'" class="tab-content">
                    <h3 class="section-title">Advanced Settings</h3>

                    <!-- Meta Information -->
                    <div class="style-section">
                        <h4 class="subsection-title">SEO & Meta</h4>
                        <div class="form-group">
                            <label class="form-label">Meta Title</label>
                            <input
                                v-model="formData.meta_title"
                                type="text"
                                class="form-input"
                                placeholder="Edu World - Complete ERP System for Educational Institutions"
                            />
                        </div>
                        <div class="form-group">
                            <label class="form-label">Meta Description</label>
                            <textarea
                                v-model="formData.meta_description"
                                class="form-textarea"
                                rows="3"
                                placeholder="A comprehensive educational management system designed for modern institutions. Streamline admissions, academics, and campus life with Edu World."
                            ></textarea>
                        </div>
                        <div class="form-group">
                            <label class="form-label"
                                >Meta Tags (comma-separated)</label
                            >
                            <input
                                v-model="metaTagsString"
                                type="text"
                                class="form-input"
                                placeholder="education, erp, school management, educational institution"
                            />
                        </div>
                    </div>

                    <!-- Animation Settings -->
                    <div class="style-section">
                        <h4 class="subsection-title">Animation & Effects</h4>
                        <div class="checkbox-group">
                            <label class="checkbox-option">
                                <input
                                    v-model="
                                        formData.advanced_settings
                                            .enable_animations
                                    "
                                    type="checkbox"
                                />
                                <span class="checkbox-custom"></span>
                                Enable Animations
                            </label>
                            <label class="checkbox-option">
                                <input
                                    v-model="
                                        formData.advanced_settings
                                            .enable_zoom_effect
                                    "
                                    type="checkbox"
                                />
                                <span class="checkbox-custom"></span>
                                Background Zoom Effect
                            </label>
                            <label class="checkbox-option">
                                <input
                                    v-model="
                                        formData.advanced_settings
                                            .lazy_load_images
                                    "
                                    type="checkbox"
                                />
                                <span class="checkbox-custom"></span>
                                Lazy Load Images
                            </label>
                            <label class="checkbox-option">
                                <input
                                    v-model="
                                        formData.advanced_settings
                                            .preload_images
                                    "
                                    type="checkbox"
                                />
                                <span class="checkbox-custom"></span>
                                Preload Images
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
                                            .transition_duration
                                    "
                                    type="text"
                                    class="form-input"
                                    placeholder="1s"
                                />
                            </div>
                            <div class="form-group">
                                <label class="form-label">Zoom Duration</label>
                                <input
                                    v-model="
                                        formData.advanced_settings.zoom_duration
                                    "
                                    type="text"
                                    class="form-input"
                                    placeholder="8s"
                                />
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Scroll Behavior</label>
                            <select
                                v-model="
                                    formData.advanced_settings.scroll_behavior
                                "
                                class="form-select"
                            >
                                <option value="smooth">Smooth</option>
                                <option value="auto">Auto</option>
                            </select>
                        </div>
                    </div>

                    <!-- Responsive Settings -->
                    <div class="style-section">
                        <h4 class="subsection-title">Responsive Behavior</h4>
                        <div class="checkbox-group">
                            <label class="checkbox-option">
                                <input
                                    v-model="
                                        formData.advanced_settings
                                            .mobile_responsive
                                    "
                                    type="checkbox"
                                />
                                <span class="checkbox-custom"></span>
                                Mobile Responsive
                            </label>
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
                            ? "Update Hero Section"
                            : "Create Hero Section"
                    }}
                </button>
            </div>
        </form>
    </div>
</template>

<script>
export default {
    name: "HeroSectionEditor",
    props: {
        heroSection: {
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
            backgroundFiles: [],
            // Track original images for proper deletion
            originalBackgroundImages: [],
            previewSlide: 0,
            previewSliderActive: false,
            previewInterval: null,
            metaTagsString: "",
            formData: {
                section_name: "",
                title: "Welcome To",
                title_highlight: "Your Brand",
                subtitle: "A Complete Solution",
                tagline: "Your perfect tagline goes here to describe your amazing service.",
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
                        image_path: "",
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
                    background: "linear-gradient(135deg, rgba(6, 6, 7, 0.8), rgba(0, 30, 60, 0.7))",
                    opacity: "1",
                },
                section_styles: {
                    minHeight: "80vh",
                    padding: "110px 20px 80px",
                    display: "block",
                    overflow: "hidden",
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
            },
            predefinedGradients: [
                "linear-gradient(135deg, #ff7101, #102e4a)",
                "linear-gradient(135deg, #20bf6b, #102e4a)",
                "linear-gradient(135deg, #f7b731, #102e4a)",
                "linear-gradient(135deg, #102e4a, #030811)",
                "linear-gradient(135deg, #d35b00, #102e4a)",
                "linear-gradient(135deg, #667eea, #764ba2)",
                "linear-gradient(135deg, #f093fb, #f5576c)",
                "linear-gradient(135deg, #4facfe, #00f2fe)",
                "linear-gradient(135deg, #43e97b, #38f9d7)",
                "linear-gradient(135deg, #fa709a, #fee140)",
                "linear-gradient(135deg, #a8edea, #fed6e3)",
                "linear-gradient(135deg, #ff9a9e, #fecfef)",
            ],
            tabs: [
                { key: "basic", label: "Basic Info" },
                { key: "backgrounds", label: "Backgrounds" },
                { key: "text", label: "Text Styles" },
                { key: "buttons", label: "Buttons" },
                { key: "layout", label: "Layout" },
                { key: "navigation", label: "Navigation" },
                { key: "advanced", label: "Advanced" },
            ],
        };
    },
    computed: {
        isEditing() {
            return !!(this.heroSection && this.heroSection.id);
        },
    },
    watch: {
        heroSection: {
            handler(newHeroSection) {
                if (newHeroSection && Object.keys(newHeroSection).length > 0) {
                    this.initializeFormData(newHeroSection);
                }
            },
            immediate: true,
            deep: true,
        },
        metaTagsString: {
            handler(newValue) {
                if (newValue) {
                    this.formData.meta_tags = newValue
                        .split(",")
                        .map((tag) => tag.trim())
                        .filter((tag) => tag);
                } else {
                    this.formData.meta_tags = [];
                }
            },
        },
        "formData.enable_slider": {
            handler(enabled) {
                if (enabled && this.getPreviewBackgrounds().length > 1) {
                    this.startPreviewSlider();
                } else {
                    this.stopPreviewSlider();
                }
            },
        },
    },
    mounted() {
        this.startPreviewSlider();
    },
    beforeDestroy() {
        this.stopPreviewSlider();
    },
    methods: {
        initializeFormData(heroSection) {
            // Deep merge the hero section data with default form data
            this.formData = this.deepMerge(this.formData, heroSection);
            
            // Store original background images for tracking deletion
            this.originalBackgroundImages = Array.isArray(this.formData.background_images) 
                ? [...this.formData.background_images] 
                : [];
            
            // Ensure required fields are not null
            this.formData.section_name = this.formData.section_name || "";
            this.formData.title = this.formData.title || "Welcome To";
            this.formData.title_highlight = this.formData.title_highlight || "";
            this.formData.subtitle = this.formData.subtitle || "";
            this.formData.tagline = this.formData.tagline || "";
            this.formData.meta_title = this.formData.meta_title || "";
            this.formData.meta_description = this.formData.meta_description || "";
            this.formData.slider_interval = this.formData.slider_interval || 3500;
            
            // Ensure booleans are properly set
            this.formData.is_active = Boolean(this.formData.is_active);
            this.formData.enable_slider = Boolean(this.formData.enable_slider);
            this.formData.show_navigation = Boolean(this.formData.show_navigation);
            
            // Ensure arrays are properly initialized
            this.formData.background_images = Array.isArray(this.formData.background_images) 
                ? this.formData.background_images 
                : [];
            this.formData.background_gradients = Array.isArray(this.formData.background_gradients) 
                ? this.formData.background_gradients 
                : [
                    "linear-gradient(135deg, #ff7101, #102e4a)",
                    "linear-gradient(135deg, #20bf6b, #102e4a)",
                    "linear-gradient(135deg, #f7b731, #102e4a)"
                ];
            this.formData.meta_tags = Array.isArray(this.formData.meta_tags) 
                ? this.formData.meta_tags 
                : [];
            
            // Ensure button structures are properly initialized
            if (!Array.isArray(this.formData.cta_buttons) || this.formData.cta_buttons.length === 0) {
                this.formData.cta_buttons = [
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
                    }
                ];
            } else {
                this.formData.cta_buttons = this.formData.cta_buttons.map((button) => ({
                    text: button.text || "Button",
                    url: button.url || "#",
                    type: button.type || "primary",
                    target: button.target || "_self",
                    styles: {
                        background: "#20bf6b",
                        color: "#fff",
                        border: "2px solid #20bf6b",
                        borderRadius: "6px",
                        padding: "10px 28px",
                        fontWeight: "500",
                        ...button.styles
                    },
                    hover_styles: {
                        background: "transparent",
                        color: "#20bf6b",
                        transform: "translateY(-2px)",
                        ...button.hover_styles
                    },
                }));
            }

            // Convert meta_tags array to string for editing
            if (this.formData.meta_tags && Array.isArray(this.formData.meta_tags)) {
                this.metaTagsString = this.formData.meta_tags.join(", ");
            }

            // Ensure navigation structure is correct
            if (!this.formData.navigation) {
                this.formData.navigation = { 
                    logo: {
                        type: "text",
                        text: "Your Brand",
                        url: "/",
                        alt: "Logo",
                        image_path: ""
                    }, 
                    menu_items: [] 
                };
            }
            if (!this.formData.navigation.logo) {
                this.formData.navigation.logo = {
                    type: "text",
                    text: "Your Brand",
                    url: "/",
                    alt: "Logo",
                    image_path: ""
                };
            }
            if (!Array.isArray(this.formData.navigation.menu_items)) {
                this.formData.navigation.menu_items = [];
            }

            // Ensure nested objects are properly initialized
            this.formData.text_styles = this.deepMerge({
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
            }, this.formData.text_styles || {});

            this.formData.overlay_styles = this.deepMerge({
                background: "linear-gradient(135deg, rgba(6, 6, 7, 0.8), rgba(0, 30, 60, 0.7))",
                opacity: "1",
            }, this.formData.overlay_styles || {});

            this.formData.section_styles = this.deepMerge({
                minHeight: "80vh",
                padding: "110px 20px 80px",
                display: "block",
                overflow: "hidden",
            }, this.formData.section_styles || {});

            this.formData.nav_styles = this.deepMerge({
                background: "transparent",
                scrolled_background: "rgba(0, 30, 60, 0.95)",
                link_color: "#fff",
                link_hover_color: "#d35b00",
                dropdown_background: "#fff",
                dropdown_border_color: "#ff7101",
                mobile_background: "rgba(0, 30, 60, 0.95)",
            }, this.formData.nav_styles || {});

            this.formData.advanced_settings = this.deepMerge({
                enable_animations: true,
                transition_duration: "1s",
                enable_zoom_effect: true,
                zoom_duration: "8s",
                mobile_responsive: true,
                lazy_load_images: true,
                scroll_behavior: "smooth",
                preload_images: true,
            }, this.formData.advanced_settings || {});
        },

        deepMerge(target, source) {
            const result = { ...target };
            for (const key in source) {
                if (source[key] && typeof source[key] === 'object' && !Array.isArray(source[key])) {
                    result[key] = this.deepMerge(target[key] || {}, source[key]);
                } else {
                    result[key] = source[key];
                }
            }
            return result;
        },

        validateFormData() {
            const errors = [];

            // Required field validation
            if (!this.formData.section_name || this.formData.section_name.trim() === '') {
                errors.push('Section name is required');
            }

            // String length validation
            if (this.formData.section_name && this.formData.section_name.length > 255) {
                errors.push('Section name must not exceed 255 characters');
            }
            if (this.formData.title && this.formData.title.length > 255) {
                errors.push('Title must not exceed 255 characters');
            }
            if (this.formData.title_highlight && this.formData.title_highlight.length > 255) {
                errors.push('Title highlight must not exceed 255 characters');
            }
            if (this.formData.subtitle && this.formData.subtitle.length > 255) {
                errors.push('Subtitle must not exceed 255 characters');
            }
            if (this.formData.meta_title && this.formData.meta_title.length > 255) {
                errors.push('Meta title must not exceed 255 characters');
            }
            if (this.formData.meta_description && this.formData.meta_description.length > 500) {
                errors.push('Meta description must not exceed 500 characters');
            }

            // Slider interval validation
            if (this.formData.slider_interval && (this.formData.slider_interval < 1000 || this.formData.slider_interval > 15000)) {
                errors.push('Slider interval must be between 1000 and 15000 milliseconds');
            }

            // Button validation
            if (this.formData.cta_buttons && this.formData.cta_buttons.length > 0) {
                this.formData.cta_buttons.forEach((button, index) => {
                    if (!button.text || button.text.trim() === '') {
                        errors.push(`Button ${index + 1} text is required`);
                    }
                    if (!button.url || button.url.trim() === '') {
                        errors.push(`Button ${index + 1} URL is required`);
                    }
                });
            }

            // Navigation validation
            if (this.formData.show_navigation && this.formData.navigation) {
                if (this.formData.navigation.logo && this.formData.navigation.logo.type === 'text' && 
                    (!this.formData.navigation.logo.text || this.formData.navigation.logo.text.trim() === '')) {
                    errors.push('Logo text is required when using text logo');
                }
            }

            return errors;
        },

        handleSubmit() {
            if (this.saving) return;
            
            // Validate form data
            const validationErrors = this.validateFormData();
            if (validationErrors.length > 0) {
                this.$emit("error", validationErrors.join('. '));
                return;
            }
            
            this.saving = true;

            const formData = new FormData();

            // Track current background images for proper deletion handling
            const currentBackgroundImages = this.getCurrentBackgroundImages();
            formData.append('current_background_images', JSON.stringify(currentBackgroundImages));

            // Add all form fields with proper handling
            const jsonFields = [
                'background_gradients',
                'cta_buttons', 
                'text_styles',
                'overlay_styles', 
                'section_styles',
                'navigation',
                'nav_styles',
                'advanced_settings',
                'meta_tags'
            ];

            Object.keys(this.formData).forEach((key) => {
                const value = this.formData[key];
                
                if (jsonFields.includes(key)) {
                    if (value !== null && value !== undefined) {
                        formData.append(key, JSON.stringify(value));
                    }
                } else if (key === 'background_images') {
                    // Skip background_images as we handle it via current_background_images and files
                    return;
                } else if (typeof value === 'boolean') {
                    formData.append(key, value ? '1' : '0');
                } else if (value !== null && value !== undefined && value !== '') {
                    formData.append(key, String(value));
                }
            });

            // Add logo file if uploaded
            if (this.logoFile) {
                formData.append("logo_image", this.logoFile);
            }

            // Add background files if uploaded
            if (this.backgroundFiles.length > 0) {
                this.backgroundFiles.forEach((file) => {
                    formData.append("background_images[]", file);
                });
            }

            // Add method for Laravel to handle PUT/PATCH requests with files
            if (this.isEditing) {
                formData.append("_method", "POST");
            }

            this.$emit("save", formData);

            // Reset saving state after a delay
            setTimeout(() => {
                this.saving = false;
            }, 2000);
        },

        getCurrentBackgroundImages() {
            // Return current background images that should be kept
            // Only include existing images that haven't been removed
            return this.formData.background_images.filter(image => {
                // Keep images that are URLs (existing images)
                return typeof image === 'string' && (
                    image.startsWith('http') || 
                    image.startsWith('/uploads/') || 
                    image.startsWith('uploads/')
                );
            });
        },

        getLogoUrl() {
            if (this.logoPreview) {
                return this.logoPreview;
            }

            if (this.formData.navigation?.logo?.image_path) {
                const imagePath = this.formData.navigation.logo.image_path;
                if (imagePath.startsWith("http") || imagePath.startsWith("/")) {
                    return imagePath;
                } else if (imagePath.startsWith("uploads/")) {
                    return "/" + imagePath;
                }
                return imagePath;
            }

            return "";
        },

        // Background handling methods
        handleBackgroundUpload(event) {
            const files = Array.from(event.target.files);
            files.forEach((file) => this.processBackgroundFile(file));
        },

        handleBackgroundDrop(event) {
            const files = Array.from(event.dataTransfer.files).filter((file) =>
                file.type.startsWith("image/")
            );
            files.forEach((file) => this.processBackgroundFile(file));
        },

        processBackgroundFile(file) {
            this.backgroundFiles.push(file);

            const reader = new FileReader();
            reader.onload = (e) => {
                if (!this.formData.background_images) {
                    this.formData.background_images = [];
                }
                // Add preview URL for display
                this.formData.background_images.push(e.target.result);
            };
            reader.readAsDataURL(file);
        },

        removeBackgroundImage(index) {
            if (this.formData.background_images && this.formData.background_images[index]) {
                const removedImage = this.formData.background_images[index];
                
                // Remove from display array
                this.formData.background_images.splice(index, 1);
                
                // If it's a newly uploaded file (data URL), also remove from backgroundFiles
                if (removedImage.startsWith('data:')) {
                    const fileIndex = this.backgroundFiles.findIndex((file, fileIdx) => {
                        return fileIdx === index - this.getCurrentBackgroundImages().length;
                    });
                    if (fileIndex !== -1) {
                        this.backgroundFiles.splice(fileIndex, 1);
                    }
                }
            }
        },

        // Logo handling
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
            this.logoFile = file;

            const reader = new FileReader();
            reader.onload = (e) => {
                this.logoPreview = e.target.result;
            };
            reader.readAsDataURL(file);
        },

        // Button methods
        addButton() {
            if (!this.formData.cta_buttons) {
                this.formData.cta_buttons = [];
            }
            
            this.formData.cta_buttons.push({
                text: "New Button",
                url: "#",
                type: "primary",
                target: "_self",
                styles: {
                    background: "#ff7101",
                    color: "#fff",
                    border: "2px solid #ff7101",
                    borderRadius: "6px",
                    padding: "10px 28px",
                    fontWeight: "500",
                },
                hover_styles: {
                    background: "transparent",
                    color: "#ff7101",
                    transform: "translateY(-2px)",
                },
            });
        },

        removeButton(index) {
            if (this.formData.cta_buttons && this.formData.cta_buttons.length > 1) {
                this.formData.cta_buttons.splice(index, 1);
            }
        },

        // Menu methods
        addMenuItem() {
            if (!this.formData.navigation.menu_items) {
                this.formData.navigation.menu_items = [];
            }
            
            this.formData.navigation.menu_items.push({
                title: "Menu Item",
                url: "#",
                sub_items: [],
                target: "_self",
            });
        },

        removeMenuItem(index) {
            if (this.formData.navigation.menu_items) {
                this.formData.navigation.menu_items.splice(index, 1);
            }
        },

        addSubMenuItem(parentIndex) {
            if (!this.formData.navigation.menu_items[parentIndex].sub_items) {
                this.$set(
                    this.formData.navigation.menu_items[parentIndex],
                    "sub_items",
                    []
                );
            }
            this.formData.navigation.menu_items[parentIndex].sub_items.push({
                text: "Sub Item",
                url: "#",
            });
        },

        removeSubMenuItem(parentIndex, subIndex) {
            if (this.formData.navigation.menu_items[parentIndex].sub_items) {
                this.formData.navigation.menu_items[parentIndex].sub_items.splice(
                    subIndex,
                    1
                );
            }
        },

        // Preview methods
        getPreviewBackgrounds() {
            const backgrounds = [];

            if (
                this.formData.background_images &&
                this.formData.background_images.length > 0
            ) {
                this.formData.background_images.forEach((img) => {
                    backgrounds.push({ type: "image", source: img });
                });
            }

            if (
                this.formData.background_gradients &&
                this.formData.background_gradients.length > 0
            ) {
                this.formData.background_gradients.forEach((gradient) => {
                    backgrounds.push({ type: "gradient", source: gradient });
                });
            }

            if (backgrounds.length === 0) {
                backgrounds.push({
                    type: "gradient",
                    source: "linear-gradient(135deg, #102e4a, #030811)",
                });
            }

            return backgrounds;
        },

        getPreviewBackgroundStyle(background) {
            if (background.type === "image") {
                return {
                    backgroundImage: `url(${background.source})`,
                    backgroundSize: "cover",
                    backgroundPosition: "center",
                };
            } else {
                return { background: background.source };
            }
        },

        startPreviewSlider() {
            if (
                this.formData.enable_slider &&
                this.getPreviewBackgrounds().length > 1
            ) {
                this.previewSliderActive = true;
                this.previewInterval = setInterval(() => {
                    this.previewSlide =
                        (this.previewSlide + 1) %
                        this.getPreviewBackgrounds().length;
                }, this.formData.slider_interval);
            }
        },

        stopPreviewSlider() {
            this.previewSliderActive = false;
            if (this.previewInterval) {
                clearInterval(this.previewInterval);
                this.previewInterval = null;
            }
        },
    },
};
</script>
<style scoped>
.hero-editor {
    background: #fff;
    border-radius: 8px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    overflow: hidden;
}

.editor-tabs {
    display: flex;
    border-bottom: 1px solid #e2e8f0;
    background: #f8fafc;
    padding: 0 20px;
}

.tab-btn {
    padding: 12px 16px;
    background: none;
    border: none;
    border-bottom: 2px solid transparent;
    font-weight: 500;
    color: #64748b;
    cursor: pointer;
    transition: all 0.2s;
}

.tab-btn:hover {
    color: #334155;
}

.tab-btn.active {
    color: #ff7101;
    border-bottom-color: #ff7101;
}

.editor-content {
    padding: 20px;
    max-height: 60vh;
    overflow-y: auto;
}

.tab-content {
    animation: fadeIn 0.3s ease;
}

.section-title {
    font-size: 1.5rem;
    font-weight: 600;
    margin-bottom: 20px;
    color: #1e293b;
}

.subsection-title {
    font-size: 1.1rem;
    font-weight: 500;
    margin-bottom: 16px;
    color: #334155;
}

.styling-title {
    font-size: 1rem;
    font-weight: 500;
    margin: 16px 0 12px 0;
    color: #475569;
}

.form-group {
    margin-bottom: 16px;
}

.form-group-small {
    display: flex;
    align-items: flex-end;
}

.form-label {
    display: block;
    margin-bottom: 6px;
    font-weight: 500;
    color: #374151;
}

.form-help {
    display: block;
    margin-top: 4px;
    font-size: 0.875rem;
    color: #6b7280;
}

.form-input,
.form-select,
.form-textarea {
    width: 100%;
    padding: 8px 12px;
    border: 1px solid #d1d5db;
    border-radius: 6px;
    font-size: 14px;
    transition: border-color 0.2s;
}

.form-input:focus,
.form-select:focus,
.form-textarea:focus {
    outline: none;
    border-color: #ff7101;
    box-shadow: 0 0 0 3px rgba(255, 113, 1, 0.1);
}

.form-textarea {
    resize: vertical;
    min-height: 80px;
}

.form-row {
    display: flex;
    gap: 16px;
    margin-bottom: 16px;
}

.form-row .form-group {
    flex: 1;
    margin-bottom: 0;
}

.color-input-group {
    display: flex;
    gap: 8px;
}

.color-input {
    width: 48px;
    height: 36px;
    padding: 4px;
    border: 1px solid #d1d5db;
    border-radius: 6px;
    cursor: pointer;
}

.color-input:focus {
    border-color: #ff7101;
}

.toggle-group {
    display: flex;
    align-items: center;
    cursor: pointer;
}

.toggle-switch {
    position: relative;
    display: inline-block;
    width: 48px;
    height: 24px;
    margin-right: 10px;
    flex-shrink: 0;
}

.toggle-switch-input {
    opacity: 0;
    width: 0;
    height: 0;
    position: absolute;
}

.toggle-slider {
    position: absolute;
    cursor: pointer;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: #cbd5e1;
    transition: all 0.3s ease;
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
    transition: all 0.3s ease;
    border-radius: 50%;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.2);
}

/* Active state for toggle */
.toggle-switch-input:checked + .toggle-slider {
    background-color: #ff7101;
}

.toggle-switch-input:checked + .toggle-slider:before {
    transform: translateX(24px);
}

/* Focus state for accessibility */
.toggle-switch-input:focus + .toggle-slider {
    box-shadow: 0 0 0 3px rgba(255, 113, 1, 0.1);
}

.toggle-label {
    margin-left: 8px;
    font-weight: 500;
    color: #374151;
    user-select: none;
}

/* Checkbox styling */
.checkbox-group {
    display: flex;
    flex-direction: column;
    gap: 12px;
    margin-bottom: 16px;
}

.checkbox-option {
    display: flex;
    align-items: center;
    cursor: pointer;
    padding: 4px 0;
    user-select: none;
}

.checkbox-option input[type="checkbox"] {
    position: absolute;
    opacity: 0;
    width: 0;
    height: 0;
}

.checkbox-custom {
    width: 18px;
    height: 18px;
    border: 2px solid #d1d5db;
    border-radius: 4px;
    margin-right: 10px;
    position: relative;
    transition: all 0.2s ease;
    background: white;
    flex-shrink: 0;
}

.checkbox-option input[type="checkbox"]:checked + .checkbox-custom {
    border-color: #ff7101;
    background: #ff7101;
}

.checkbox-option input[type="checkbox"]:checked + .checkbox-custom:after {
    content: "";
    position: absolute;
    left: 5px;
    top: 2px;
    width: 4px;
    height: 8px;
    border: solid white;
    border-width: 0 2px 2px 0;
    transform: rotate(45deg);
}

.checkbox-option input[type="checkbox"]:focus + .checkbox-custom {
    box-shadow: 0 0 0 3px rgba(255, 113, 1, 0.1);
}

.checkbox-option:hover .checkbox-custom {
    border-color: #ff7101;
}

/* Radio button styling */
.radio-group {
    display: flex;
    gap: 16px;
    margin-top: 8px;
}

.radio-option {
    display: flex;
    align-items: center;
    cursor: pointer;
    user-select: none;
}

.radio-option input[type="radio"] {
    position: absolute;
    opacity: 0;
    width: 0;
    height: 0;
}

.radio-custom {
    width: 18px;
    height: 18px;
    border: 2px solid #d1d5db;
    border-radius: 50%;
    margin-right: 8px;
    position: relative;
    transition: all 0.2s ease;
    background: white;
    flex-shrink: 0;
}

.radio-option input[type="radio"]:checked + .radio-custom {
    border-color: #ff7101;
}

.radio-option input[type="radio"]:checked + .radio-custom:after {
    content: "";
    position: absolute;
    top: 3px;
    left: 3px;
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background: #ff7101;
}

.radio-option input[type="radio"]:focus + .radio-custom {
    box-shadow: 0 0 0 3px rgba(255, 113, 1, 0.1);
}

.radio-option:hover .radio-custom {
    border-color: #ff7101;
}

.upload-dropzone {
    border: 2px dashed #d1d5db;
    border-radius: 6px;
    padding: 20px;
    text-align: center;
    cursor: pointer;
    transition: border-color 0.2s;
}

.upload-dropzone:hover {
    border-color: #ff7101;
}

.upload-dropzone.small {
    padding: 10px;
    min-height: 100px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.upload-placeholder {
    color: #6b7280;
}

.upload-placeholder svg {
    margin-bottom: 8px;
    color: #9ca3af;
}

.file-input {
    display: none;
}

.logo-preview {
    max-width: 100px;
    max-height: 80px;
    object-fit: contain;
}

.images-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
    gap: 12px;
    margin-top: 16px;
}

.image-item {
    position: relative;
    border-radius: 6px;
    overflow: hidden;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}

.image-item img {
    width: 100%;
    height: 80px;
    object-fit: cover;
}

.remove-image-btn {
    position: absolute;
    top: 4px;
    right: 4px;
    background: rgba(239, 68, 68, 0.9);
    border: none;
    border-radius: 50%;
    width: 24px;
    height: 24px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    cursor: pointer;
}

.gradients-container {
    display: flex;
    flex-direction: column;
    gap: 12px;
    margin-bottom: 16px;
}

.gradient-item {
    display: flex;
    align-items: center;
    gap: 12px;
}

.gradient-preview {
    width: 60px;
    height: 40px;
    border-radius: 4px;
    flex-shrink: 0;
}

.gradient-presets {
    display: grid;
    grid-template-columns: repeat(6, 1fr);
    gap: 8px;
    margin-top: 12px;
}

.gradient-preset {
    height: 30px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    transition: transform 0.2s;
}

.gradient-preset:hover {
    transform: scale(1.1);
}

.btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 8px 16px;
    border: 1px solid #d1d5db;
    border-radius: 6px;
    background: white;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.2s;
    text-decoration: none;
}

.btn:hover {
    background: #f9fafb;
}

.btn-primary {
    background: #ff7101;
    color: white;
    border-color: #ff7101;
}

.btn-primary:hover {
    background: #e5640e;
}

.btn-outline {
    background: transparent;
    color: #ff7101;
    border-color: #ff7101;
}

.btn-outline:hover {
    background: #fff7ed;
}

.btn-sm {
    padding: 6px 12px;
    font-size: 0.875rem;
}

.btn-xs {
    padding: 4px 8px;
    font-size: 0.75rem;
}

.btn-icon {
    padding: 6px;
    border-radius: 4px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
}

.btn-danger {
    color: #ef4444;
    border-color: #fecaca;
}

.btn-danger:hover {
    background: #fef2f2;
}

.section-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 20px;
}

.buttons-list {
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.button-editor {
    border: 1px solid #e5e7eb;
    border-radius: 8px;
    padding: 16px;
}

.button-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 16px;
}

.button-styling {
    margin-top: 20px;
    padding-top: 16px;
    border-top: 1px solid #e5e7eb;
}

.menu-items-list {
    display: flex;
    flex-direction: column;
    gap: 16px;
}

.menu-item-editor {
    border: 1px solid #e5e7eb;
    border-radius: 6px;
    padding: 16px;
}

.sub-items-section {
    margin-top: 16px;
    padding-top: 12px;
    border-top: 1px solid #f3f4f6;
}

.sub-items-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 12px;
}

.sub-items-list {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.sub-item-row {
    display: flex;
    gap: 8px;
    align-items: center;
}

.style-section {
    margin-bottom: 24px;
    padding-bottom: 16px;
    border-bottom: 1px solid #f1f5f9;
}

.style-section:last-child {
    border-bottom: none;
}

.overlay-preview {
    margin-top: 12px;
    border-radius: 6px;
    overflow: hidden;
}

.preview-overlay {
    padding: 10px;
}

.form-actions {
    display: flex;
    justify-content: flex-end;
    gap: 12px;
    padding: 20px;
    border-top: 1px solid #e5e7eb;
    background: #f9fafb;
}

.btn-spinner {
    width: 16px;
    height: 16px;
    border: 2px solid transparent;
    border-top: 2px solid currentColor;
    border-radius: 50%;
    animation: spin 1s linear infinite;
    margin-right: 8px;
}

@keyframes spin {
    from {
        transform: rotate(0deg);
    }
    to {
        transform: rotate(360deg);
    }
}

@keyframes fadeIn {
    from {
        opacity: 0;
    }
    to {
        opacity: 1;
    }
}
</style>
