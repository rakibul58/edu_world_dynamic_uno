<template>
    <div class="header-manager">
        <div class="page-header">
            <div class="header-content">
                <div class="header-left">
                    <h1 class="page-title">Header Section Manager</h1>
                    <p class="page-subtitle">
                        Customize your website header with dynamic content and
                        styles
                    </p>
                </div>
                <div class="header-actions">
                    <button
                        @click="resetForm"
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
                            <path d="M2.5 2v6h6M21.5 22v-6h-6" />
                            <path
                                d="M22 11.5A10 10 0 0 0 3.2 7.2M2 12.5a10 10 0 0 0 18.8 4.2"
                            />
                        </svg>
                        Reset
                    </button>
                    <button
                        @click="saveHeader"
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
                            <path
                                d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"
                            />
                            <polyline points="17 21 17 13 7 13 7 21" />
                            <polyline points="7 3 7 8 15 8" />
                        </svg>
                        Save Header
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
                <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14" />
                <polyline points="22 4 12 14.01 9 11.01" />
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

        <div class="editor-container">
            <div class="editor-content">
                <!-- Logo Settings -->
                <div class="settings-card">
                    <h3 class="card-title">Logo Settings</h3>
                    <div class="form-group">
                        <label class="form-label">Logo Type</label>
                        <div class="radio-group">
                            <label class="radio-label">
                                <input
                                    type="radio"
                                    v-model="form.logo_type"
                                    value="image"
                                />
                                <span class="radio-checkmark"></span>
                                Image Logo
                            </label>
                            <label class="radio-label">
                                <input
                                    type="radio"
                                    v-model="form.logo_type"
                                    value="text"
                                />
                                <span class="radio-checkmark"></span>
                                Text Logo
                            </label>
                        </div>
                    </div>

                    <!-- Image Logo Settings -->
                    <div
                        v-if="form.logo_type === 'image'"
                        class="logo-image-settings"
                    >
                        <div class="form-group">
                            <label class="form-label">Logo Image</label>
                            <div class="image-uploader">
                                <div
                                    v-if="form.logo_image_path"
                                    class="image-preview"
                                >
                                    <img
                                        :src="form.logo_image_path"
                                        alt="Logo preview"
                                    />
                                    <button
                                        type="button"
                                        @click="removeImage"
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
                                <label v-else class="upload-area">
                                    <input
                                        type="file"
                                        @change="handleImageUpload"
                                        accept="image/*"
                                        ref="fileInput"
                                    />
                                    <svg
                                        width="32"
                                        height="32"
                                        viewBox="0 0 24 24"
                                        fill="none"
                                        stroke="currentColor"
                                        stroke-width="2"
                                    >
                                        <path
                                            d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h3l2-3h6l2 3h3a2 2 0 0 1 2 2z"
                                        />
                                        <circle cx="12" cy="13" r="4" />
                                    </svg>
                                    <span>Click to upload logo</span>
                                </label>
                            </div>
                        </div>

                        <div class="style-expander">
                            <h4
                                class="expander-title"
                                @click="toggleExpander('imageStyles')"
                            >
                                <svg
                                    width="16"
                                    height="16"
                                    viewBox="0 0 24 24"
                                    fill="none"
                                    stroke="currentColor"
                                    stroke-width="2"
                                >
                                    <polyline
                                        points="6 9 12 15 18 9"
                                    ></polyline>
                                </svg>
                                Image Styles
                            </h4>

                            <div
                                v-if="expandedSections.imageStyles"
                                class="expander-content"
                            >
                                <div class="form-row">
                                    <div class="form-group">
                                        <label class="form-label">Width</label>
                                        <input
                                            type="text"
                                            v-model="
                                                form.logo_styles.image.width
                                            "
                                            class="form-input"
                                            placeholder="180px"
                                        />
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">Height</label>
                                        <input
                                            type="text"
                                            v-model="
                                                form.logo_styles.image.height
                                            "
                                            class="form-input"
                                            placeholder="auto"
                                        />
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="form-label">Object Fit</label>
                                    <select
                                        v-model="
                                            form.logo_styles.image.object_fit
                                        "
                                        class="form-select"
                                    >
                                        <option value="contain">Contain</option>
                                        <option value="cover">Cover</option>
                                        <option value="fill">Fill</option>
                                        <option value="none">None</option>
                                        <option value="scale-down">
                                            Scale Down
                                        </option>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label class="form-label">Alt Text</label>
                                    <input
                                        type="text"
                                        v-model="form.logo_styles.image.alt"
                                        class="form-input"
                                        placeholder="Site Logo"
                                    />
                                </div>

                                <h5 class="sub-section-title">Border</h5>
                                <div class="form-row">
                                    <div class="form-group">
                                        <label class="form-label"
                                            >Border Width</label
                                        >
                                        <input
                                            type="text"
                                            v-model="
                                                form.logo_styles.image.border
                                                    .width
                                            "
                                            class="form-input"
                                            placeholder="0"
                                        />
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label"
                                            >Border Style</label
                                        >
                                        <select
                                            v-model="
                                                form.logo_styles.image.border
                                                    .style
                                            "
                                            class="form-select"
                                        >
                                            <option value="solid">Solid</option>
                                            <option value="dashed">
                                                Dashed
                                            </option>
                                            <option value="dotted">
                                                Dotted
                                            </option>
                                            <option value="double">
                                                Double
                                            </option>
                                            <option value="none">None</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-row">
                                    <div class="form-group">
                                        <label class="form-label"
                                            >Border Color</label
                                        >
                                        <input
                                            type="color"
                                            v-model="
                                                form.logo_styles.image.border
                                                    .color
                                            "
                                            class="color-picker"
                                        />
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label"
                                            >Border Radius</label
                                        >
                                        <input
                                            type="text"
                                            v-model="
                                                form.logo_styles.image.border
                                                    .radius
                                            "
                                            class="form-input"
                                            placeholder="0"
                                        />
                                    </div>
                                </div>

                                <h5 class="sub-section-title">Hover Effects</h5>
                                <div class="form-row">
                                    <div class="form-group">
                                        <label class="form-label"
                                            >Hover Opacity</label
                                        >
                                        <input
                                            type="number"
                                            v-model="
                                                form.logo_styles.image.hover
                                                    .opacity
                                            "
                                            class="form-input"
                                            min="0"
                                            max="1"
                                            step="0.1"
                                            placeholder="0.8"
                                        />
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label"
                                            >Hover Scale</label
                                        >
                                        <input
                                            type="number"
                                            v-model="
                                                form.logo_styles.image.hover
                                                    .scale
                                            "
                                            class="form-input"
                                            min="0.5"
                                            max="2"
                                            step="0.05"
                                            placeholder="1.05"
                                        />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Text Logo Settings -->
                    <div v-else class="logo-text-settings">
                        <div class="form-group">
                            <label class="form-label">Logo Text</label>
                            <input
                                type="text"
                                v-model="form.logo_text"
                                class="form-input"
                                placeholder="Your Logo Text"
                            />
                        </div>

                        <div class="style-expander">
                            <h4
                                class="expander-title"
                                @click="toggleExpander('textStyles')"
                            >
                                <svg
                                    width="16"
                                    height="16"
                                    viewBox="0 0 24 24"
                                    fill="none"
                                    stroke="currentColor"
                                    stroke-width="2"
                                >
                                    <polyline
                                        points="6 9 12 15 18 9"
                                    ></polyline>
                                </svg>
                                Text Styles
                            </h4>

                            <div
                                v-if="expandedSections.textStyles"
                                class="expander-content"
                            >
                                <div class="form-row">
                                    <div class="form-group">
                                        <label class="form-label"
                                            >Font Family</label
                                        >
                                        <select
                                            v-model="
                                                form.logo_styles.text
                                                    .font_family
                                            "
                                            class="form-select"
                                        >
                                            <option value="inherit">
                                                Inherit
                                            </option>
                                            <option value="Arial, sans-serif">
                                                Arial
                                            </option>
                                            <option value="Georgia, serif">
                                                Georgia
                                            </option>
                                            <option
                                                value="'Helvetica Neue', Helvetica, sans-serif"
                                            >
                                                Helvetica
                                            </option>
                                            <option
                                                value="'Times New Roman', serif"
                                            >
                                                Times New Roman
                                            </option>
                                            <option
                                                value="'Courier New', monospace"
                                            >
                                                Courier New
                                            </option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label"
                                            >Font Size</label
                                        >
                                        <input
                                            type="text"
                                            v-model="
                                                form.logo_styles.text.font_size
                                            "
                                            class="form-input"
                                            placeholder="24px"
                                        />
                                    </div>
                                </div>

                                <div class="form-row">
                                    <div class="form-group">
                                        <label class="form-label"
                                            >Font Weight</label
                                        >
                                        <select
                                            v-model="
                                                form.logo_styles.text
                                                    .font_weight
                                            "
                                            class="form-select"
                                        >
                                            <option value="normal">
                                                Normal
                                            </option>
                                            <option value="bold">Bold</option>
                                            <option value="bolder">
                                                Bolder
                                            </option>
                                            <option value="lighter">
                                                Lighter
                                            </option>
                                            <option value="100">100</option>
                                            <option value="200">200</option>
                                            <option value="300">300</option>
                                            <option value="400">400</option>
                                            <option value="500">500</option>
                                            <option value="600">600</option>
                                            <option value="700">700</option>
                                            <option value="800">800</option>
                                            <option value="900">900</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label"
                                            >Text Transform</label
                                        >
                                        <select
                                            v-model="
                                                form.logo_styles.text.transform
                                            "
                                            class="form-select"
                                        >
                                            <option value="none">None</option>
                                            <option value="uppercase">
                                                Uppercase
                                            </option>
                                            <option value="lowercase">
                                                Lowercase
                                            </option>
                                            <option value="capitalize">
                                                Capitalize
                                            </option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-row">
                                    <div class="form-group">
                                        <label class="form-label"
                                            >Text Color</label
                                        >
                                        <input
                                            type="color"
                                            v-model="
                                                form.logo_styles.text.color
                                            "
                                            class="color-picker"
                                        />
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label"
                                            >Letter Spacing</label
                                        >
                                        <input
                                            type="text"
                                            v-model="
                                                form.logo_styles.text.spacing
                                            "
                                            class="form-input"
                                            placeholder="0"
                                        />
                                    </div>
                                </div>

                                <h5 class="sub-section-title">Hover Effects</h5>
                                <div class="form-row">
                                    <div class="form-group">
                                        <label class="form-label"
                                            >Hover Color</label
                                        >
                                        <input
                                            type="color"
                                            v-model="
                                                form.logo_styles.text.hover
                                                    .color
                                            "
                                            class="color-picker"
                                        />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Logo Container Settings -->
                    <div class="style-expander">
                        <h4
                            class="expander-title"
                            @click="toggleExpander('containerStyles')"
                        >
                            <svg
                                width="16"
                                height="16"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="2"
                            >
                                <polyline points="6 9 12 15 18 9"></polyline>
                            </svg>
                            Container Styles
                        </h4>

                        <div
                            v-if="expandedSections.containerStyles"
                            class="expander-content"
                        >
                            <div class="form-row">
                                <div class="form-group">
                                    <label class="form-label"
                                        >Background Color</label
                                    >
                                    <input
                                        type="color"
                                        v-model="
                                            form.logo_styles.container
                                                .background
                                        "
                                        class="color-picker"
                                    />
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Padding</label>
                                    <input
                                        type="text"
                                        v-model="
                                            form.logo_styles.container.padding
                                        "
                                        class="form-input"
                                        placeholder="0"
                                    />
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group">
                                    <label class="form-label">Margin</label>
                                    <input
                                        type="text"
                                        v-model="
                                            form.logo_styles.container.margin
                                        "
                                        class="form-input"
                                        placeholder="0"
                                    />
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Alignment</label>
                                    <select
                                        v-model="
                                            form.logo_styles.container.alignment
                                        "
                                        class="form-select"
                                    >
                                        <option value="flex-start">Left</option>
                                        <option value="center">Center</option>
                                        <option value="flex-end">Right</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Logo Link Settings -->
                    <div class="style-expander">
                        <h4
                            class="expander-title"
                            @click="toggleExpander('linkSettings')"
                        >
                            <svg
                                width="16"
                                height="16"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="2"
                            >
                                <polyline points="6 9 12 15 18 9"></polyline>
                            </svg>
                            Link Settings
                        </h4>

                        <div
                            v-if="expandedSections.linkSettings"
                            class="expander-content"
                        >
                            <div class="form-group">
                                <label class="form-label">Logo Link URL</label>
                                <input
                                    type="text"
                                    v-model="form.logo_link.url"
                                    class="form-input"
                                    placeholder="/"
                                />
                            </div>

                            <div class="form-row">
                                <div class="form-group">
                                    <label class="checkbox-label">
                                        <input
                                            type="checkbox"
                                            v-model="form.logo_link.target"
                                            true-value="_blank"
                                            false-value="_self"
                                        />
                                        <span class="checkbox-text"
                                            >Open in new tab</span
                                        >
                                    </label>
                                </div>
                                <div class="form-group">
                                    <label class="checkbox-label">
                                        <input
                                            type="checkbox"
                                            v-model="form.logo_link.nofollow"
                                        />
                                        <span class="checkbox-text"
                                            >No-follow link</span
                                        >
                                    </label>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="form-label">Link Title</label>
                                <input
                                    type="text"
                                    v-model="form.logo_link.title"
                                    class="form-input"
                                    placeholder="Home Page"
                                />
                            </div>
                        </div>
                    </div>

                    <!-- Logo Tagline -->
                    <div class="form-group">
                        <label class="form-label">Logo Tagline</label>
                        <input
                            type="text"
                            v-model="form.logo_tagline"
                            class="form-input"
                            placeholder="Optional tagline"
                        />
                    </div>

                    <!-- Tagline Styles -->
                    <div class="style-expander" v-if="form.logo_tagline">
                        <h4
                            class="expander-title"
                            @click="toggleExpander('taglineStyles')"
                        >
                            <svg
                                width="16"
                                height="16"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="2"
                            >
                                <polyline points="6 9 12 15 18 9"></polyline>
                            </svg>
                            Tagline Styles
                        </h4>

                        <div
                            v-if="expandedSections.taglineStyles"
                            class="expander-content"
                        >
                            <div class="form-row">
                                <div class="form-group">
                                    <label class="form-label">Font Size</label>
                                    <input
                                        type="text"
                                        v-model="form.tagline_styles.font_size"
                                        class="form-input"
                                        placeholder="14px"
                                    />
                                </div>
                                <div class="form-group">
                                    <label class="form-label"
                                        >Font Weight</label
                                    >
                                    <select
                                        v-model="
                                            form.tagline_styles.font_weight
                                        "
                                        class="form-select"
                                    >
                                        <option value="normal">Normal</option>
                                        <option value="bold">Bold</option>
                                        <option value="lighter">Lighter</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group">
                                    <label class="form-label">Text Color</label>
                                    <input
                                        type="color"
                                        v-model="form.tagline_styles.color"
                                        class="color-picker"
                                    />
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Font Style</label>
                                    <select
                                        v-model="form.tagline_styles.style"
                                        class="form-select"
                                    >
                                        <option value="normal">Normal</option>
                                        <option value="italic">Italic</option>
                                        <option value="oblique">Oblique</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="form-label">Margin</label>
                                <input
                                    type="text"
                                    v-model="form.tagline_styles.margin"
                                    class="form-input"
                                    placeholder="0 0 0 10px"
                                />
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Buttons Settings -->
                <div class="settings-card">
                    <div class="card-header">
                        <h3 class="card-title">Navigation Buttons</h3>
                        <button
                            type="button"
                            @click="addButton"
                            class="btn btn-sm btn-primary"
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

                    <div v-if="form.buttons.length === 0" class="empty-state">
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
                            <line x1="9" y1="9" x2="15" y2="15" />
                            <line x1="15" y1="9" x2="9" y2="15" />
                        </svg>
                        <p>No buttons added yet</p>
                    </div>

                    <draggable
                        v-else
                        v-model="form.buttons"
                        item-key="id"
                        handle=".drag-handle"
                        class="buttons-list"
                    >
                        <template #item="{ element: button, index }">
                            <div class="button-item">
                                <div class="button-header">
                                    <div class="drag-handle">
                                        <svg
                                            width="16"
                                            height="16"
                                            viewBox="0 0 24 24"
                                            fill="none"
                                            stroke="currentColor"
                                            stroke-width="2"
                                        >
                                            <line
                                                x1="3"
                                                y1="12"
                                                x2="21"
                                                y2="12"
                                            />
                                            <line
                                                x1="3"
                                                y1="6"
                                                x2="21"
                                                y2="6"
                                            />
                                            <line
                                                x1="3"
                                                y1="18"
                                                x2="21"
                                                y2="18"
                                            />
                                        </svg>
                                        <span>Button {{ index + 1 }}</span>
                                    </div>
                                    <button
                                        type="button"
                                        @click="removeButton(index)"
                                        class="btn-icon-sm btn-danger"
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

                                <div class="button-form">
                                    <div class="form-row">
                                        <div class="form-group">
                                            <label class="form-label"
                                                >Button Text</label
                                            >
                                            <input
                                                type="text"
                                                v-model="button.text"
                                                class="form-input"
                                                placeholder="Button text"
                                            />
                                        </div>
                                        <div class="form-group">
                                            <label class="form-label"
                                                >Button URL</label
                                            >
                                            <input
                                                type="text"
                                                v-model="button.url"
                                                class="form-input"
                                                placeholder="https://example.com"
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
                                                <option value="primary">
                                                    Primary
                                                </option>
                                                <option value="secondary">
                                                    Secondary
                                                </option>
                                                <option value="success">
                                                    Success
                                                </option>
                                                <option value="danger">
                                                    Danger
                                                </option>
                                                <option value="warning">
                                                    Warning
                                                </option>
                                                <option value="info">
                                                    Info
                                                </option>
                                                <option value="light">
                                                    Light
                                                </option>
                                                <option value="dark">
                                                    Dark
                                                </option>
                                                <option value="custom">
                                                    Custom
                                                </option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label class="form-label"
                                                >Button Type</label
                                            >
                                            <select
                                                v-model="button.type"
                                                class="form-select"
                                            >
                                                <option value="filled">
                                                    Filled
                                                </option>
                                                <option value="outlined">
                                                    Outlined
                                                </option>
                                            </select>
                                        </div>
                                    </div>

                                    <!-- Custom Button Styles -->
                                    <div
                                        v-if="button.style === 'custom'"
                                        class="style-expander"
                                    >
                                        <h4
                                            class="expander-title"
                                            @click="
                                                toggleButtonStyleExpander(index)
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
                                                <polyline
                                                    points="6 9 12 15 18 9"
                                                ></polyline>
                                            </svg>
                                            Custom Styles
                                        </h4>

                                        <div
                                            v-if="expandedButtonIndex === index"
                                            class="expander-content"
                                        >
                                            <div class="form-row">
                                                <div class="form-group">
                                                    <label class="form-label"
                                                        >Background Color</label
                                                    >
                                                    <input
                                                        type="color"
                                                        v-model="
                                                            button.custom_styles
                                                                .background_color
                                                        "
                                                        class="color-picker"
                                                    />
                                                </div>
                                                <div class="form-group">
                                                    <label class="form-label"
                                                        >Text Color</label
                                                    >
                                                    <input
                                                        type="color"
                                                        v-model="
                                                            button.custom_styles
                                                                .text_color
                                                        "
                                                        class="color-picker"
                                                    />
                                                </div>
                                            </div>

                                            <div class="form-row">
                                                <div class="form-group">
                                                    <label class="form-label"
                                                        >Border Color</label
                                                    >
                                                    <input
                                                        type="color"
                                                        v-model="
                                                            button.custom_styles
                                                                .border_color
                                                        "
                                                        class="color-picker"
                                                    />
                                                </div>
                                                <div class="form-group">
                                                    <label class="form-label"
                                                        >Border Width</label
                                                    >
                                                    <input
                                                        type="text"
                                                        v-model="
                                                            button.custom_styles
                                                                .border_width
                                                        "
                                                        class="form-input"
                                                        placeholder="2px"
                                                    />
                                                </div>
                                            </div>

                                            <div class="form-row">
                                                <div class="form-group">
                                                    <label class="form-label"
                                                        >Border Radius</label
                                                    >
                                                    <input
                                                        type="text"
                                                        v-model="
                                                            button.custom_styles
                                                                .border_radius
                                                        "
                                                        class="form-input"
                                                        placeholder="5px"
                                                    />
                                                </div>
                                                <div class="form-group">
                                                    <label class="form-label"
                                                        >Font Size</label
                                                    >
                                                    <input
                                                        type="text"
                                                        v-model="
                                                            button.custom_styles
                                                                .font_size
                                                        "
                                                        class="form-input"
                                                        placeholder="0.95rem"
                                                    />
                                                </div>
                                            </div>

                                            <h5 class="sub-section-title">
                                                Hover Styles
                                            </h5>

                                            <div class="form-row">
                                                <div class="form-group">
                                                    <label class="form-label"
                                                        >Hover Background</label
                                                    >
                                                    <input
                                                        type="color"
                                                        v-model="
                                                            button.custom_styles
                                                                .hover_background_color
                                                        "
                                                        class="color-picker"
                                                    />
                                                </div>
                                                <div class="form-group">
                                                    <label class="form-label"
                                                        >Hover Text Color</label
                                                    >
                                                    <input
                                                        type="color"
                                                        v-model="
                                                            button.custom_styles
                                                                .hover_text_color
                                                        "
                                                        class="color-picker"
                                                    />
                                                </div>
                                            </div>

                                            <div class="form-row">
                                                <div class="form-group">
                                                    <label class="form-label"
                                                        >Hover Border
                                                        Color</label
                                                    >
                                                    <input
                                                        type="color"
                                                        v-model="
                                                            button.custom_styles
                                                                .hover_border_color
                                                        "
                                                        class="color-picker"
                                                    />
                                                </div>
                                                <div class="form-group">
                                                    <label class="form-label"
                                                        >Transition
                                                        Duration</label
                                                    >
                                                    <input
                                                        type="text"
                                                        v-model="
                                                            button.custom_styles
                                                                .transition_duration
                                                        "
                                                        class="form-input"
                                                        placeholder="0.3s"
                                                    />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </draggable>
                </div>

                <!-- Navigation Styles -->
                <div class="settings-card">
                    <h3 class="card-title">Navigation Styles</h3>
                    <div class="style-controls-grid">
                        <div class="form-group">
                            <label class="form-label">Background Color</label>
                            <input
                                type="color"
                                v-model="form.nav_styles.background"
                                class="color-picker"
                            />
                        </div>
                        <div class="form-group">
                            <label class="form-label">Padding</label>
                            <input
                                type="text"
                                v-model="form.nav_styles.padding"
                                class="form-input"
                                placeholder="e.g., 0 10px"
                            />
                        </div>
                        <div class="form-group">
                            <label class="form-label">Justify Content</label>
                            <select
                                v-model="form.nav_styles.justifyContent"
                                class="form-select"
                            >
                                <option value="space-between">
                                    Space Between
                                </option>
                                <option value="flex-start">Flex Start</option>
                                <option value="flex-end">Flex End</option>
                                <option value="center">Center</option>
                                <option value="space-around">
                                    Space Around
                                </option>
                                <option value="space-evenly">
                                    Space Evenly
                                </option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Preview Panel -->
            <div class="preview-panel">
                <h3 class="panel-title">Live Preview</h3>
                <div class="preview-content">
                    <nav
                        class="navbar-top preview-nav"
                        :style="previewNavStyles"
                    >
                        <div
                            class="container nav-container preview-container"
                            :style="previewContainerStyles"
                        >
                            <div
                                class="logo-section preview-logo-section"
                                :style="previewLogoSectionStyles"
                            >
                                <a
                                    class="nav-logo preview-logo-link"
                                    href="#"
                                    :style="previewLogoLinkStyles"
                                    @mouseover="previewLogoHover = true"
                                    @mouseleave="previewLogoHover = false"
                                >
                                    <img
                                        v-if="
                                            form.logo_type === 'image' &&
                                            form.logo_image_path
                                        "
                                        :src="form.logo_image_path"
                                        alt="Site Logo"
                                        :style="previewLogoImageStyles"
                                        :class="{
                                            'logo-hover': previewLogoHover,
                                        }"
                                    />
                                    <span
                                        v-else-if="
                                            form.logo_type === 'text' &&
                                            form.logo_text
                                        "
                                        class="logo-text preview-logo-text"
                                        :style="previewLogoTextStyles"
                                        :class="{
                                            'logo-hover': previewLogoHover,
                                        }"
                                    >
                                        {{ form.logo_text }}
                                    </span>
                                    <div v-else class="logo-placeholder">
                                        [Logo Placeholder]
                                    </div>
                                </a>

                                <span
                                    v-if="form.logo_tagline"
                                    class="logo-tagline preview-tagline"
                                    :style="previewTaglineStyles"
                                >
                                    {{ form.logo_tagline }}
                                </span>
                            </div>

                            <div
                                class="top-actions preview-actions"
                                :style="previewActionsStyles"
                            >
                                <a
                                    v-for="(button, index) in form.buttons"
                                    :key="index"
                                    href="#"
                                    class="nav-btn preview-btn"
                                    :class="[
                                        `nav-btn-${button.type}`,
                                        `nav-btn-${button.style}`,
                                    ]"
                                    :style="getPreviewButtonStyles(button)"
                                >
                                    {{ button.text || "Button" }}
                                </a>
                                <div
                                    v-if="form.buttons.length === 0"
                                    class="buttons-placeholder"
                                >
                                    [Buttons will appear here]
                                </div>
                            </div>
                        </div>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { ref, onMounted, computed, reactive } from "vue";
import axios from "axios";
import draggable from "vuedraggable";

export default {
    name: "HeaderSectionManager",
    components: {
        draggable,
    },
    setup() {
        const form = reactive({
            logo_type: "image",
            logo_image_path: "",
            logo_text: "Site Name",
            logo_styles: {
                image: {
                    width: "180px",
                    height: "auto",
                    object_fit: "contain",
                    border: {
                        width: "0",
                        style: "solid",
                        color: "#000000",
                        radius: "0",
                    },
                    shadow: {
                        enabled: false,
                        color: "rgba(0,0,0,0.1)",
                        x: "0",
                        y: "2",
                        blur: "4",
                        spread: "0",
                    },
                    hover: {
                        opacity: "0.8",
                        scale: "1.05",
                    },
                },
                text: {
                    font_family: "inherit",
                    font_size: "24px",
                    font_weight: "700",
                    color: "#ffffff",
                    transform: "none",
                    spacing: "0",
                    shadow: {
                        enabled: false,
                        color: "rgba(0,0,0,0.3)",
                        x: "1",
                        y: "1",
                        blur: "2",
                    },
                    hover: {
                        color: "#20bf6b",
                    },
                },
                container: {
                    background: "transparent",
                    padding: "0",
                    margin: "0",
                    alignment: "flex-start",
                    display: "flex",
                },
            },
            logo_link: {
                url: "/",
                target: "_self",
                nofollow: false,
                title: "Home Page",
            },
            logo_responsive: {
                mobile: {
                    type: "same",
                },
            },
            logo_tagline: "",
            tagline_styles: {
                font_size: "14px",
                font_weight: "400",
                color: "#d1d5db",
                style: "italic",
                margin: "0 0 0 10px",
            },
            buttons: [],
            nav_styles: {
                background: "#102e4a",
                padding: "0 10px",
                justifyContent: "space-between",
            },
        });

        const loading = ref(false);
        const message = ref({ text: "", type: "" });
        const fileInput = ref(null);
        const expandedSections = ref({
            imageStyles: false,
            textStyles: false,
            containerStyles: false,
            linkSettings: false,
            taglineStyles: false,
        });
        const expandedButtonIndex = ref(null);
        const previewLogoHover = ref(false);

        const showMessage = (text, type = "success") => {
            message.value = { text, type };
            setTimeout(() => {
                message.value = { text: "", type: "" };
            }, 5000);
        };

        const fetchHeaderData = async () => {
            try {
                loading.value = true;
                const response = await axios.get("/header-sections");

                if (response.data.success) {
                    Object.assign(form, response.data.data);
                }
            } catch (error) {
                console.error("Error fetching header data:", error);
                showMessage("Failed to load header data", "error");
            } finally {
                loading.value = false;
            }
        };

        const handleImageUpload = (event) => {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = (e) => {
                    form.logo_image_path = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        };

        const removeImage = () => {
            form.logo_image_path = "";
            if (fileInput.value) {
                fileInput.value.value = "";
            }
        };

        const addButton = () => {
            form.buttons.push({
                text: "New Button",
                url: "#",
                style: "primary",
                type: "filled",
                custom_styles: {
                    background_color: "#3b82f6",
                    text_color: "#ffffff",
                    border_color: "#3b82f6",
                    border_width: "2px",
                    border_radius: "5px",
                    font_size: "0.95rem",
                    hover_background_color: "#2563eb",
                    hover_text_color: "#ffffff",
                    hover_border_color: "#2563eb",
                    transition_duration: "0.3s",
                },
            });
        };

        const removeButton = (index) => {
            form.buttons.splice(index, 1);
        };

        const toggleExpander = (section) => {
            expandedSections.value[section] = !expandedSections.value[section];
        };

        const toggleButtonStyleExpander = (index) => {
            expandedButtonIndex.value =
                expandedButtonIndex.value === index ? null : index;
        };

        const resetForm = () => {
            Object.assign(form, {
                logo_type: "image",
                logo_image_path: "",
                logo_text: "Site Name",
                logo_styles: {
                    image: {
                        width: "180px",
                        height: "auto",
                        object_fit: "contain",
                        border: {
                            width: "0",
                            style: "solid",
                            color: "#000000",
                            radius: "0",
                        },
                        shadow: {
                            enabled: false,
                            color: "rgba(0,0,0,0.1)",
                            x: "0",
                            y: "2",
                            blur: "4",
                            spread: "0",
                        },
                        hover: {
                            opacity: "0.8",
                            scale: "1.05",
                        },
                    },
                    text: {
                        font_family: "inherit",
                        font_size: "24px",
                        font_weight: "700",
                        color: "#ffffff",
                        transform: "none",
                        spacing: "0",
                        shadow: {
                            enabled: false,
                            color: "rgba(0,0,0,0.3)",
                            x: "1",
                            y: "1",
                            blur: "2",
                        },
                        hover: {
                            color: "#20bf6b",
                        },
                    },
                    container: {
                        background: "transparent",
                        padding: "0",
                        margin: "0",
                        alignment: "flex-start",
                        display: "flex",
                    },
                },
                logo_link: {
                    url: "/",
                    target: "_self",
                    nofollow: false,
                    title: "Home Page",
                },
                logo_responsive: {
                    mobile: {
                        type: "same",
                    },
                },
                logo_tagline: "",
                tagline_styles: {
                    font_size: "14px",
                    font_weight: "400",
                    color: "#d1d5db",
                    style: "italic",
                    margin: "0 0 0 10px",
                },
                buttons: [],
                nav_styles: {
                    background: "#102e4a",
                    padding: "0 10px",
                    justifyContent: "space-between",
                },
            });
            showMessage("Form has been reset", "success");
        };

        const saveHeader = async () => {
            try {
                loading.value = true;
                const formData = new FormData();

                // Append all form data
                Object.keys(form).forEach((key) => {
                    if (typeof form[key] === "object") {
                        formData.append(key, JSON.stringify(form[key]));
                    } else {
                        formData.append(key, form[key]);
                    }
                });

                // If we have a new image file, append it
                if (fileInput.value && fileInput.value.files[0]) {
                    formData.append("logo_image", fileInput.value.files[0]);
                }

                const response = await axios.post(
                    "/header-sections",
                    formData,
                    {
                        headers: {
                            "Content-Type": "multipart/form-data",
                        },
                    }
                );

                if (response.data.success) {
                    showMessage("Header saved successfully", "success");
                }
            } catch (error) {
                console.error("Error saving header:", error);
                showMessage("Failed to save header", "error");
            } finally {
                loading.value = false;
            }
        };

        // Preview computed styles
        const previewNavStyles = computed(() => {
            return {
                background: form.nav_styles.background || "#102e4a",
                padding: form.nav_styles.padding || "0 10px",
            };
        });

        const previewContainerStyles = computed(() => {
            return {
                display: "flex",
                alignItems: "center",
                justifyContent:
                    form.nav_styles.justifyContent || "space-between",
                padding: "10px 15px",
            };
        });

        const previewLogoSectionStyles = computed(() => {
            const styles = form.logo_styles?.container || {};
            return {
                display: styles.display || "flex",
                alignItems: styles.alignment || "center",
                justifyContent: styles.justify || "flex-start",
                background: styles.background || "transparent",
                padding: styles.padding || "0",
                margin: styles.margin || "0",
                flexDirection: styles.direction || "row",
            };
        });

        const previewLogoLinkStyles = computed(() => {
            return {
                display: "flex",
                alignItems: "center",
                textDecoration: "none",
            };
        });

        const previewLogoImageStyles = computed(() => {
            const styles = form.logo_styles?.image || {};
            const hoverEffect = previewLogoHover.value ? styles.hover : {};

            return {
                width: styles.width || "180px",
                height: styles.height || "auto",
                objectFit: styles.object_fit || "contain",
                border: `${styles.border?.width || "0"} ${
                    styles.border?.style || "solid"
                } ${styles.border?.color || "transparent"}`,
                borderRadius: styles.border?.radius || "0",
                boxShadow: styles.shadow?.enabled
                    ? `${styles.shadow.x || "0"} ${styles.shadow.y || "0"} ${
                          styles.shadow.blur || "0"
                      } ${styles.shadow.spread || "0"} ${
                          styles.shadow.color || "rgba(0,0,0,0.1)"
                      }`
                    : "none",
                opacity: hoverEffect.opacity || styles.opacity || "1",
                transform: `scale(${hoverEffect.scale || "1"})`,
                transition: "all 0.3s ease",
            };
        });

        const previewLogoTextStyles = computed(() => {
            const styles = form.logo_styles?.text || {};
            const hoverEffect = previewLogoHover.value ? styles.hover : {};

            return {
                fontFamily: styles.font_family || "inherit",
                fontSize: styles.font_size || "24px",
                fontWeight: styles.font_weight || "700",
                color: hoverEffect.color || styles.color || "#ffffff",
                textTransform: styles.transform || "none",
                letterSpacing: styles.spacing || "0",
                textShadow: styles.shadow?.enabled
                    ? `${styles.shadow.x || "0"} ${styles.shadow.y || "0"} ${
                          styles.shadow.blur || "0"
                      } ${styles.shadow.color || "rgba(0,0,0,0.3)"}`
                    : "none",
                transition: "all 0.3s ease",
            };
        });

        const previewTaglineStyles = computed(() => {
            const styles = form.tagline_styles || {};
            return {
                fontFamily: styles.font_family || "inherit",
                fontSize: styles.font_size || "14px",
                fontWeight: styles.font_weight || "400",
                color: styles.color || "#d1d5db",
                fontStyle: styles.style || "italic",
                margin: styles.margin || "0 0 0 10px",
                display: styles.display || "block",
            };
        });

        const previewActionsStyles = computed(() => {
            return {
                display: "flex",
                gap: "10px",
                alignItems: "center",
            };
        });

        const getPreviewButtonStyles = (button) => {
            if (button.style === "custom" && button.custom_styles) {
                const styles = { ...button.custom_styles };

                // Clean up the style object for CSS
                const cssStyles = {};

                // Map our custom properties to CSS properties
                if (styles.background_color)
                    cssStyles.backgroundColor = styles.background_color;
                if (styles.text_color) cssStyles.color = styles.text_color;
                if (styles.border_color)
                    cssStyles.borderColor = styles.border_color;
                if (styles.border_width)
                    cssStyles.borderWidth = styles.border_width;
                if (styles.border_style) cssStyles.borderStyle = "solid";
                if (styles.border_radius)
                    cssStyles.borderRadius = styles.border_radius;

                // Padding
                if (
                    styles.padding_top ||
                    styles.padding_right ||
                    styles.padding_bottom ||
                    styles.padding_left
                ) {
                    cssStyles.padding = `${styles.padding_top || "8px"} ${
                        styles.padding_right || "30px"
                    } ${styles.padding_bottom || "8px"} ${
                        styles.padding_left || "30px"
                    }`;
                } else {
                    cssStyles.padding = "8px 30px";
                }

                // Typography
                if (styles.font_size) cssStyles.fontSize = styles.font_size;
                if (styles.font_weight)
                    cssStyles.fontWeight = styles.font_weight;
                if (styles.font_family)
                    cssStyles.fontFamily = styles.font_family;
                if (styles.text_transform)
                    cssStyles.textTransform = styles.text_transform;
                if (styles.letter_spacing)
                    cssStyles.letterSpacing = styles.letter_spacing;

                // Effects
                if (styles.box_shadow) cssStyles.boxShadow = styles.box_shadow;
                if (styles.opacity) cssStyles.opacity = styles.opacity;

                // Transitions
                if (styles.transition_duration) {
                    cssStyles.transition = `all ${styles.transition_duration}`;
                } else {
                    cssStyles.transition = "all 0.3s ease";
                }

                return cssStyles;
            }

            return {};
        };

        onMounted(() => {
            fetchHeaderData();
        });

        return {
            form,
            loading,
            message,
            fileInput,
            expandedSections,
            expandedButtonIndex,
            previewLogoHover,
            handleImageUpload,
            removeImage,
            addButton,
            removeButton,
            toggleExpander,
            toggleButtonStyleExpander,
            resetForm,
            saveHeader,
            previewNavStyles,
            previewContainerStyles,
            previewLogoSectionStyles,
            previewLogoLinkStyles,
            previewLogoImageStyles,
            previewLogoTextStyles,
            previewTaglineStyles,
            previewActionsStyles,
            getPreviewButtonStyles,
        };
    },
};
</script>

<style scoped>
.header-manager {
    max-width: 1400px;
    margin: 0 auto;
    padding: 24px;
    background-color: #f8fafc;
}

/* Page Header */
.page-header {
    background: #ffffff;
    border-radius: 16px;
    padding: 24px;
    margin-bottom: 24px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
    border: 1px solid #e2e8f0;
}

.header-content {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 32px;
}

.header-left {
    flex: 1;
}

.page-title {
    font-size: 1.8rem;
    font-weight: 700;
    color: #1e293b;
    margin: 0 0 8px 0;
}

.page-subtitle {
    font-size: 1.05rem;
    color: #64748b;
    margin: 0;
    line-height: 1.5;
}

.header-actions {
    display: flex;
    gap: 12px;
    flex-wrap: wrap;
}

/* Editor Layout */
.editor-container {
    display: grid;
    grid-template-columns: 1fr 400px;
    gap: 24px;
}

.editor-content {
    display: flex;
    flex-direction: column;
    gap: 24px;
}

.preview-panel {
    background: #ffffff;
    border-radius: 16px;
    padding: 24px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
    border: 1px solid #e2e8f0;
    height: fit-content;
    position: sticky;
    top: 24px;
}

.panel-title {
    font-size: 1.25rem;
    font-weight: 600;
    color: #1e293b;
    margin: 0 0 16px 0;
}

.preview-content {
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    overflow: hidden;
}

.preview-nav {
    width: 100%;
}

.preview-container {
    padding: 10px 15px !important;
}

.preview-logo-section {
    flex-shrink: 0;
}

.preview-logo-link {
    pointer-events: none;
}

.preview-logo-text {
    white-space: nowrap;
}

.preview-actions {
    flex-wrap: wrap;
    justify-content: flex-end;
}

.preview-btn {
    pointer-events: none;
    white-space: nowrap;
}

.logo-placeholder,
.buttons-placeholder {
    color: #94a3b8;
    font-style: italic;
    padding: 8px 12px;
    background: #f1f5f9;
    border-radius: 4px;
}

/* Settings Cards */
.settings-card {
    background: #ffffff;
    border-radius: 16px;
    padding: 24px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
    border: 1px solid #e2e8f0;
}

.card-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
}

.card-title {
    font-size: 1.25rem;
    font-weight: 600;
    color: #1e293b;
    margin: 0;
}

/* Form Styles */
.form-group {
    margin-bottom: 20px;
}

.form-label {
    display: block;
    font-size: 0.9rem;
    font-weight: 600;
    color: #334155;
    margin-bottom: 8px;
}

.form-input,
.form-select {
    width: 100%;
    padding: 10px 12px;
    border: 1px solid #cbd5e1;
    border-radius: 8px;
    font-size: 0.95rem;
    transition: all 0.3s ease;
    background: #ffffff;
    color: #334155;
}

.form-input:focus,
.form-select:focus {
    outline: none;
    border-color: #3b82f6;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

.form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 16px;
}

/* Radio Group */
.radio-group {
    display: flex;
    gap: 20px;
}

.radio-label {
    display: flex;
    align-items: center;
    gap: 8px;
    cursor: pointer;
    font-weight: 500;
}

.radio-label input[type="radio"] {
    display: none;
}

.radio-checkmark {
    width: 18px;
    height: 18px;
    border: 2px solid #cbd5e1;
    border-radius: 50%;
    display: inline-block;
    position: relative;
    transition: all 0.3s ease;
}

.radio-label input[type="radio"]:checked + .radio-checkmark {
    border-color: #3b82f6;
}

.radio-label input[type="radio"]:checked + .radio-checkmark::after {
    content: "";
    width: 10px;
    height: 10px;
    background: #3b82f6;
    border-radius: 50%;
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
}

/* Image Uploader */
.image-uploader {
    margin-top: 8px;
}

.image-preview {
    position: relative;
    width: 200px;
    height: 100px;
    border: 2px dashed #cbd5e1;
    border-radius: 8px;
    overflow: hidden;
}

.image-preview img {
    width: 100%;
    height: 100%;
    object-fit: contain;
}

.remove-image-btn {
    position: absolute;
    top: 4px;
    right: 4px;
    width: 24px;
    height: 24px;
    border-radius: 50%;
    border: none;
    background: #ef4444;
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
}

.upload-area {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    width: 200px;
    height: 100px;
    border: 2px dashed #cbd5e1;
    border-radius: 8px;
    cursor: pointer;
    transition: all 0.3s ease;
}

.upload-area:hover {
    border-color: #3b82f6;
    background: #f0f9ff;
}

.upload-area input[type="file"] {
    display: none;
}

.upload-area svg {
    color: #94a3b8;
    margin-bottom: 8px;
}

.upload-area span {
    font-size: 0.9rem;
    color: #64748b;
}

/* Style Controls */
.style-controls {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 16px;
}

.style-controls-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 16px;
}

.style-control label {
    display: block;
    font-size: 0.8rem;
    font-weight: 600;
    color: #334155;
    margin-bottom: 4px;
}

.color-picker {
    width: 100%;
    height: 40px;
    border: 1px solid #cbd5e1;
    border-radius: 8px;
    cursor: pointer;
}

/* Expander Styles */
.style-expander {
    margin-top: 20px;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    overflow: hidden;
}

.expander-title {
    background: #f8fafc;
    padding: 12px 16px;
    margin: 0;
    font-size: 1rem;
    font-weight: 600;
    color: #334155;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 8px;
    transition: all 0.3s ease;
}

.expander-title:hover {
    background: #f1f5f9;
}

.expander-content {
    padding: 16px;
    background: #ffffff;
}

.sub-section-title {
    font-size: 0.9rem;
    font-weight: 600;
    color: #334155;
    margin: 20px 0 12px 0;
    padding-bottom: 8px;
    border-bottom: 1px solid #e2e8f0;
}

/* Buttons List */
.buttons-list {
    display: flex;
    flex-direction: column;
    gap: 16px;
}

.button-item {
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    padding: 16px;
}

.button-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 16px;
}

.drag-handle {
    display: flex;
    align-items: center;
    gap: 8px;
    color: #64748b;
    font-weight: 500;
    cursor: move;
}

.button-form {
    background: #ffffff;
    border-radius: 8px;
    padding: 16px;
}

.empty-state {
    text-align: center;
    padding: 40px 20px;
    color: #94a3b8;
}

.empty-state svg {
    margin-bottom: 16px;
}

.empty-state p {
    margin: 0;
    font-style: italic;
}

/* Buttons */
.btn {
    padding: 10px 18px;
    border-radius: 10px;
    font-weight: 600;
    font-size: 0.9rem;
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    gap: 8px;
    border: none;
    text-decoration: none;
}

.btn-primary {
    background: #3b82f6;
    color: #ffffff;
}

.btn-primary:hover:not(:disabled) {
    background: #2563eb;
    transform: translateY(-2px);
}

.btn-outline {
    background: #ffffff;
    color: #334155;
    border: 1px solid #cbd5e1;
}

.btn-outline:hover:not(:disabled) {
    background: #f1f5f9;
    border-color: #94a3b8;
}

.btn-sm {
    padding: 6px 12px;
    font-size: 0.85rem;
}

.btn-icon-sm {
    width: 28px;
    height: 28px;
    border-radius: 6px;
    border: none;
    background: #f1f5f9;
    color: #64748b;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.3s ease;
}

.btn-icon-sm:hover {
    background: #e2e8f0;
}

.btn-danger {
    background: #fef2f2;
    color: #ef4444;
}

.btn-danger:hover {
    background: #ef4444;
    color: #ffffff;
}

.btn:disabled {
    opacity: 0.6;
    cursor: not-allowed;
    transform: none;
}

/* Checkbox Styles */
.checkbox-label {
    display: flex;
    align-items: center;
    gap: 8px;
    cursor: pointer;
}

.checkbox-text {
    font-size: 0.9rem;
    color: #334155;
}

/* Alerts */
.alert {
    padding: 16px 24px;
    border-radius: 10px;
    margin-bottom: 24px;
    display: flex;
    align-items: center;
    gap: 12px;
    font-weight: 500;
    font-size: 0.95rem;
}

.alert.success {
    background: #f0fdf4;
    color: #166534;
    border: 1px solid #bbf7d0;
}

.alert.error {
    background: #fef2f2;
    color: #991b1b;
    border: 1px solid #fecaca;
}

/* Responsive Styles */
@media (max-width: 1024px) {
    .editor-container {
        grid-template-columns: 1fr;
    }

    .preview-panel {
        position: static;
    }
}

@media (max-width: 768px) {
    .header-content {
        flex-direction: column;
        align-items: flex-start;
        gap: 16px;
    }

    .form-row {
        grid-template-columns: 1fr;
    }

    .style-controls {
        grid-template-columns: 1fr;
    }
}
</style>
