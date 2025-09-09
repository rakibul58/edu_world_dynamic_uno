<template>
    <div class="admin-academic-programs">
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
                <h2>Academic Programs Management</h2>
                <p>
                    Manage academic program levels, content, and section
                    settings
                </p>
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
                    <i class="fas fa-plus"></i> Add Program Level
                </button>
            </div>
        </div>

        <!-- Create/Edit Program Modal -->
        <div v-if="showCreateForm" class="modal-overlay" @click="closeForm">
            <div class="modal-content program-modal" @click.stop>
                <div class="modal-header">
                    <h3>
                        {{ editingProgram ? "Edit" : "Create" }} Program Level
                    </h3>
                    <button @click="closeForm" class="close-btn">×</button>
                </div>
                <form @submit.prevent="saveProgram" class="program-form">
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
                                    placeholder="Foundation (Years 1-6)"
                                />
                            </div>
                            <div class="form-group">
                                <label>Age Range *</label>
                                <input
                                    type="text"
                                    v-model="formData.ageRange"
                                    required
                                    class="form-control"
                                    placeholder="Ages 5-11"
                                />
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group full-width">
                                <label>Icon *</label>
                                <div class="icon-input-group">
                                    <input
                                        type="text"
                                        v-model="formData.icon"
                                        required
                                        class="form-control icon-input"
                                        placeholder="🌱"
                                    />
                                    <div
                                        class="icon-preview"
                                        :style="getIconPreviewStyle()"
                                    >
                                        {{ formData.icon }}
                                    </div>
                                </div>
                                <small class="help-text"
                                    >Use emojis or Unicode characters</small
                                >
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group full-width">
                                <label>Program Items</label>
                                <div class="items-manager">
                                    <div
                                        v-for="(item, index) in formData.items"
                                        :key="index"
                                        class="item-row"
                                    >
                                        <input
                                            type="text"
                                            v-model="formData.items[index]"
                                            class="form-control"
                                            placeholder="Enter program item"
                                        />
                                        <button
                                            type="button"
                                            @click="removeItem(index)"
                                            class="btn-remove-item"
                                        >
                                            ×
                                        </button>
                                    </div>
                                    <button
                                        type="button"
                                        @click="addItem"
                                        class="btn btn-secondary btn-sm"
                                    >
                                        + Add Item
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Icon Styling -->
                    <div class="form-section">
                        <h4>Icon Styling</h4>
                        <div class="form-row">
                            <div class="form-group">
                                <label>Background Color</label>
                                <div class="enhanced-color-input-group">
                                    <input
                                        type="color"
                                        v-model="formData.icon_background_color"
                                        class="form-control color-input"
                                        @input="
                                            validateColorInput(
                                                'icon_background_color'
                                            )
                                        "
                                    />
                                    <input
                                        type="text"
                                        v-model="formData.icon_background_color"
                                        class="form-control color-text-input"
                                        placeholder="#ff7101"
                                        @input="
                                            validateColorInput(
                                                'icon_background_color'
                                            )
                                        "
                                        @blur="
                                            formatColorValue(
                                                'icon_background_color'
                                            )
                                        "
                                    />
                                    <span
                                        class="color-preview"
                                        :style="{
                                            backgroundColor:
                                                formData.icon_background_color,
                                        }"
                                    ></span>
                                </div>
                                <small class="help-text"
                                    >Use hex, rgb, or color names</small
                                >
                            </div>
                            <div class="form-group">
                                <label>Icon Color</label>
                                <div class="enhanced-color-input-group">
                                    <input
                                        type="color"
                                        v-model="formData.icon_color"
                                        class="form-control color-input"
                                        @input="
                                            validateColorInput('icon_color')
                                        "
                                    />
                                    <input
                                        type="text"
                                        v-model="formData.icon_color"
                                        class="form-control color-text-input"
                                        placeholder="#ffffff"
                                        @input="
                                            validateColorInput('icon_color')
                                        "
                                        @blur="formatColorValue('icon_color')"
                                    />
                                    <span
                                        class="color-preview"
                                        :style="{
                                            backgroundColor:
                                                formData.icon_color,
                                        }"
                                    ></span>
                                </div>
                                <small class="help-text"
                                    >Use hex, rgb, or color names</small
                                >
                            </div>
                            <div class="form-group">
                                <label>Icon Size</label>
                                <input
                                    type="text"
                                    v-model="formData.icon_size"
                                    placeholder="2.5rem"
                                    class="form-control"
                                />
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label>Icon Width</label>
                                <input
                                    type="text"
                                    v-model="formData.icon_width"
                                    placeholder="60px"
                                    class="form-control"
                                />
                            </div>
                            <div class="form-group">
                                <label>Icon Height</label>
                                <input
                                    type="text"
                                    v-model="formData.icon_height"
                                    placeholder="60px"
                                    class="form-control"
                                />
                            </div>
                            <div class="form-group">
                                <label>Border Radius</label>
                                <input
                                    type="text"
                                    v-model="formData.icon_border_radius"
                                    placeholder="50%"
                                    class="form-control"
                                />
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
                                    <div class="enhanced-color-input-group">
                                        <input
                                            type="color"
                                            v-model="formData.title_color"
                                            class="form-control color-input"
                                            @input="
                                                validateColorInput(
                                                    'title_color'
                                                )
                                            "
                                        />
                                        <input
                                            type="text"
                                            v-model="formData.title_color"
                                            class="form-control color-text-input"
                                            placeholder="#102e4a"
                                            @input="
                                                validateColorInput(
                                                    'title_color'
                                                )
                                            "
                                            @blur="
                                                formatColorValue('title_color')
                                            "
                                        />
                                        <span
                                            class="color-preview"
                                            :style="{
                                                backgroundColor:
                                                    formData.title_color,
                                            }"
                                        ></span>
                                    </div>
                                    <small class="help-text"
                                        >Use hex, rgb, or color names</small
                                    >
                                </div>
                                <div class="form-group">
                                    <label>Font Size</label>
                                    <input
                                        type="text"
                                        v-model="formData.title_font_size"
                                        placeholder="1.5rem"
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

                        <!-- Age Range Styling -->
                        <div class="form-subsection">
                            <h5>Age Range</h5>
                            <div class="form-row">
                                <div class="form-group">
                                    <label>Age Range Color</label>
                                    <div class="enhanced-color-input-group">
                                        <input
                                            type="color"
                                            v-model="formData.age_range_color"
                                            class="form-control color-input"
                                            @input="
                                                validateColorInput(
                                                    'age_range_color'
                                                )
                                            "
                                        />
                                        <input
                                            type="text"
                                            v-model="formData.age_range_color"
                                            class="form-control color-text-input"
                                            placeholder="#ff7101"
                                            @input="
                                                validateColorInput(
                                                    'age_range_color'
                                                )
                                            "
                                            @blur="
                                                formatColorValue(
                                                    'age_range_color'
                                                )
                                            "
                                        />
                                        <span
                                            class="color-preview"
                                            :style="{
                                                backgroundColor:
                                                    formData.age_range_color,
                                            }"
                                        ></span>
                                    </div>
                                    <small class="help-text"
                                        >Use hex, rgb, or color names</small
                                    >
                                </div>
                                <div class="form-group">
                                    <label>Font Size</label>
                                    <input
                                        type="text"
                                        v-model="formData.age_range_font_size"
                                        placeholder="0.9rem"
                                        class="form-control"
                                    />
                                </div>
                                <div class="form-group">
                                    <label>Font Weight</label>
                                    <select
                                        v-model="formData.age_range_font_weight"
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

                        <!-- Items Styling -->
                        <div class="form-subsection">
                            <h5>Program Items</h5>
                            <div class="form-row">
                                <div class="form-group">
                                    <label>Items Color</label>
                                    <div class="enhanced-color-input-group">
                                        <input
                                            type="color"
                                            v-model="formData.items_color"
                                            class="form-control color-input"
                                            @input="
                                                validateColorInput(
                                                    'items_color'
                                                )
                                            "
                                        />
                                        <input
                                            type="text"
                                            v-model="formData.items_color"
                                            class="form-control color-text-input"
                                            placeholder="#111111"
                                            @input="
                                                validateColorInput(
                                                    'items_color'
                                                )
                                            "
                                            @blur="
                                                formatColorValue('items_color')
                                            "
                                        />
                                        <span
                                            class="color-preview"
                                            :style="{
                                                backgroundColor:
                                                    formData.items_color,
                                            }"
                                        ></span>
                                    </div>
                                    <small class="help-text"
                                        >Use hex, rgb, or color names</small
                                    >
                                </div>
                                <div class="form-group">
                                    <label>Font Size</label>
                                    <input
                                        type="text"
                                        v-model="formData.items_font_size"
                                        placeholder="1rem"
                                        class="form-control"
                                    />
                                </div>
                                <div class="form-group">
                                    <label>Font Weight</label>
                                    <select
                                        v-model="formData.items_font_weight"
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
                            <div class="form-row">
                                <div class="form-group">
                                    <label>Check Mark Color</label>
                                    <div class="enhanced-color-input-group">
                                        <input
                                            type="color"
                                            v-model="formData.items_check_color"
                                            class="form-control color-input"
                                            @input="
                                                validateColorInput(
                                                    'items_check_color'
                                                )
                                            "
                                        />
                                        <input
                                            type="text"
                                            v-model="formData.items_check_color"
                                            class="form-control color-text-input"
                                            placeholder="#20bf6b"
                                            @input="
                                                validateColorInput(
                                                    'items_check_color'
                                                )
                                            "
                                            @blur="
                                                formatColorValue(
                                                    'items_check_color'
                                                )
                                            "
                                        />
                                        <span
                                            class="color-preview"
                                            :style="{
                                                backgroundColor:
                                                    formData.items_check_color,
                                            }"
                                        ></span>
                                    </div>
                                    <small class="help-text"
                                        >Use hex, rgb, or color names</small
                                    >
                                </div>
                                <div class="form-group">
                                    <label>Items Padding</label>
                                    <input
                                        type="text"
                                        v-model="formData.items_padding"
                                        placeholder="8px 0"
                                        class="form-control"
                                    />
                                </div>
                                <div class="form-group">
                                    <label>Left Padding</label>
                                    <input
                                        type="text"
                                        v-model="formData.items_padding_left"
                                        placeholder="25px"
                                        class="form-control"
                                    />
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
                                <div class="enhanced-color-input-group">
                                    <input
                                        type="color"
                                        v-model="formData.card_background_color"
                                        class="form-control color-input"
                                        @input="
                                            validateColorInput(
                                                'card_background_color'
                                            )
                                        "
                                    />
                                    <input
                                        type="text"
                                        v-model="formData.card_background_color"
                                        class="form-control color-text-input"
                                        placeholder="#ffffff"
                                        @input="
                                            validateColorInput(
                                                'card_background_color'
                                            )
                                        "
                                        @blur="
                                            formatColorValue(
                                                'card_background_color'
                                            )
                                        "
                                    />
                                    <span
                                        class="color-preview"
                                        :style="{
                                            backgroundColor:
                                                formData.card_background_color,
                                        }"
                                    ></span>
                                </div>
                                <small class="help-text"
                                    >Use hex, rgb, or color names</small
                                >
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
                                <label>Padding</label>
                                <input
                                    type="text"
                                    v-model="formData.card_padding"
                                    placeholder="30px"
                                    class="form-control"
                                />
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label>Shadow</label>
                                <input
                                    type="text"
                                    v-model="formData.card_shadow"
                                    placeholder="0 5px 20px rgba(0, 0, 0, 0.08)"
                                    class="form-control"
                                />
                            </div>
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
                                    placeholder="translateY(-5px)"
                                    class="form-control"
                                />
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label>Header Gap</label>
                                <input
                                    type="text"
                                    v-model="formData.header_gap"
                                    placeholder="20px"
                                    class="form-control"
                                />
                            </div>
                            <div class="form-group">
                                <label>Content Margin Top</label>
                                <input
                                    type="text"
                                    v-model="formData.content_margin_top"
                                    placeholder="20px"
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
                                    : editingProgram
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
                                <div class="enhanced-color-input-group">
                                    <input
                                        type="color"
                                        v-model="
                                            sectionSettings.section_title_color
                                        "
                                        class="form-control color-input"
                                        @input="
                                            validateColorInput(
                                                'section_title_color'
                                            )
                                        "
                                    />
                                    <input
                                        type="text"
                                        v-model="
                                            sectionSettings.section_title_color
                                        "
                                        class="form-control color-text-input"
                                        placeholder="#102e4a"
                                        @input="
                                            validateColorInput(
                                                'section_title_color'
                                            )
                                        "
                                        @blur="
                                            formatColorValue(
                                                'section_title_color'
                                            )
                                        "
                                    />
                                    <span
                                        class="color-preview"
                                        :style="{
                                            backgroundColor:
                                                sectionSettings.section_title_color,
                                        }"
                                    ></span>
                                </div>
                                <small class="help-text"
                                    >Use hex, rgb, or color names</small
                                >
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
                                <div class="enhanced-color-input-group">
                                    <input
                                        type="color"
                                        v-model="
                                            sectionSettings.section_description_color
                                        "
                                        class="form-control color-input"
                                        @input="
                                            validateColorInput(
                                                'section_description_color'
                                            )
                                        "
                                    />
                                    <input
                                        type="text"
                                        v-model="
                                            sectionSettings.section_description_color
                                        "
                                        class="form-control color-text-input"
                                        placeholder="#111111"
                                        @input="
                                            validateColorInput(
                                                'section_description_color'
                                            )
                                        "
                                        @blur="
                                            formatColorValue(
                                                'section_description_color'
                                            )
                                        "
                                    />
                                    <span
                                        class="color-preview"
                                        :style="{
                                            backgroundColor:
                                                sectionSettings.section_description_color,
                                        }"
                                    ></span>
                                </div>
                                <small class="help-text"
                                    >Use hex, rgb, or color names</small
                                >
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
                                <div class="enhanced-color-input-group">
                                    <input
                                        type="color"
                                        v-model="
                                            sectionSettings.section_background_color
                                        "
                                        class="form-control color-input"
                                        @input="
                                            validateColorInput(
                                                'section_background_color'
                                            )
                                        "
                                    />
                                    <input
                                        type="text"
                                        v-model="
                                            sectionSettings.section_background_color
                                        "
                                        class="form-control color-text-input"
                                        placeholder="#f8fbfe"
                                        @input="
                                            validateColorInput(
                                                'section_background_color'
                                            )
                                        "
                                        @blur="
                                            formatColorValue(
                                                'section_background_color'
                                            )
                                        "
                                    />
                                    <span
                                        class="color-preview"
                                        :style="{
                                            backgroundColor:
                                                sectionSettings.section_background_color,
                                        }"
                                    ></span>
                                </div>
                                <small class="help-text"
                                    >Use hex, rgb, or color names</small
                                >
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
                    </div>

                    <!-- Layout Settings -->
                    <div class="form-section">
                        <h4>Layout Settings</h4>
                        <div class="form-row">
                            <div class="form-group">
                                <label>Programs Gap</label>
                                <input
                                    type="text"
                                    v-model="sectionSettings.programs_gap"
                                    placeholder="30px"
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

        <!-- Programs List -->
        <div class="programs-list">
            <div class="list-header">
                <h3>Program Levels ({{ programs.length }})</h3>
                <p class="drag-info">Drag and drop to reorder programs</p>
            </div>

            <div
                class="programs-container"
                :class="{ 'is-dragging': isDragging }"
            >
                <div
                    v-for="(program, index) in programs"
                    :key="program.id"
                    class="program-card"
                    :class="{
                        'is-active': program.is_active,
                        'is-dragging': draggedProgram === program,
                    }"
                    draggable="true"
                    @dragstart="onDragStart($event, program, index)"
                    @dragend="onDragEnd"
                    @dragover.prevent
                    @drop="onDrop($event, index)"
                >
                    <div class="drag-handle">⋮⋮</div>

                    <div class="card-header">
                        <div class="program-status">
                            <span
                                class="status-indicator"
                                :class="{ active: program.is_active }"
                            ></span>
                            <span class="status-text">
                                {{ program.is_active ? "Active" : "Inactive" }}
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
                                        index === programs.length - 1 ||
                                        isLoading
                                    "
                                    title="Move Down"
                                >
                                    ↓
                                </button>
                            </div>
                            <button
                                class="action-btn toggle-btn"
                                @click="toggleStatus(program)"
                                :class="{ active: program.is_active }"
                            >
                                {{ program.is_active ? "Hide" : "Show" }}
                            </button>
                            <button
                                class="action-btn edit-btn"
                                @click="editProgram(program)"
                                title="Edit"
                            >
                                Edit
                            </button>
                            <button
                                class="action-btn delete-btn"
                                @click="deleteProgram(program.id)"
                                title="Delete"
                            >
                                Delete
                            </button>
                        </div>
                    </div>

                    <div class="card-content">
                        <div class="program-preview">
                            <div class="preview-header">
                                <div
                                    class="level-icon"
                                    :style="getProgramIconStyle(program)"
                                >
                                    {{ program.icon }}
                                </div>
                                <div class="level-info">
                                    <h3 :style="getProgramTitleStyle(program)">
                                        {{ program.title }}
                                    </h3>
                                    <span
                                        :style="
                                            getProgramAgeRangeStyle(program)
                                        "
                                    >
                                        {{ program.ageRange }}
                                    </span>
                                </div>
                            </div>
                            <div class="level-content">
                                <ul :style="{ gap: '10px' }">
                                    <li
                                        v-for="(
                                            item, itemIndex
                                        ) in program.items"
                                        :key="itemIndex"
                                        :style="getProgramItemStyle(program)"
                                    >
                                        {{ item }}
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer">
                        <small class="update-time">
                            Last updated:
                            {{
                                formatDateTime(program.updated_at || new Date())
                            }}
                        </small>
                    </div>
                </div>
            </div>

            <div v-if="programs.length === 0" class="empty-state">
                <div class="empty-icon">🎓</div>
                <h4>No program levels yet</h4>
                <p>Get started by adding your first academic program level</p>
                <button
                    class="btn btn-primary"
                    @click="showCreateForm = true"
                    :disabled="isLoading"
                >
                    + Add Your First Program Level
                </button>
            </div>
        </div>
    </div>
</template>

<script>
import axios from "axios";
export default {
    name: "AcademicProgramsAdmin",
    data() {
        return {
            programs: [],
            sectionSettings: {
                section_title: "Academic Excellence at Every Level",
                section_description:
                    "From foundation to advanced levels, our comprehensive curriculum ensures holistic development.",
                section_title_color: "#102e4a",
                section_title_font_size: "2.5rem",
                section_title_font_weight: "700",
                section_description_color: "#111111",
                section_description_font_size: "1.1rem",
                section_description_font_weight: "400",
                section_background_color: "#f8fbfe",
                section_padding: "80px 0",
                header_text_align: "center",
                header_margin_bottom: "60px",
                container_max_width: "1240px",
                container_padding: "0 20px",
                programs_gap: "30px",
                enable_animations: true,
                animation_duration: "0.6s",
                animation_delay_increment: "0.1s",
                is_active: true,
            },
            showCreateForm: false,
            showSettingsForm: false,
            editingProgram: null,
            isLoading: false,
            isSaving: false,
            isSavingSettings: false,
            toasts: [],
            toastIdCounter: 0,

            // Drag and drop
            isDragging: false,
            draggedProgram: null,
            draggedIndex: null,

            formData: {
                title: "",
                ageRange: "",
                icon: "🎓",
                items: [""],
                // Icon styling
                icon_background_color: "#ff7101",
                icon_color: "#ffffff",
                icon_size: "2.5rem",
                icon_width: "60px",
                icon_height: "60px",
                icon_border_radius: "50%",
                // Title styling
                title_color: "#102e4a",
                title_font_size: "1.5rem",
                title_font_weight: "600",
                title_margin_bottom: "5px",
                // Age range styling
                age_range_color: "#ff7101",
                age_range_font_size: "0.9rem",
                age_range_font_weight: "600",
                // Items styling
                items_color: "#111111",
                items_font_size: "1rem",
                items_font_weight: "400",
                items_padding: "8px 0",
                items_check_color: "#20bf6b",
                items_padding_left: "25px",
                // Card styling
                card_background_color: "#ffffff",
                card_border_radius: "15px",
                card_padding: "30px",
                card_shadow: "0 5px 20px rgba(0, 0, 0, 0.08)",
                card_hover_shadow: "0 15px 30px rgba(0, 0, 0, 0.15)",
                card_hover_transform: "translateY(-5px)",
                header_gap: "20px",
                content_margin_top: "20px",
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

        // Color validation methods
        validateColorInput(fieldName) {
            const value = this.getFieldValue(fieldName);

            // Allow common color formats
            const colorRegex =
                /^(#[0-9A-F]{6}|#[0-9A-F]{3}|rgb\(\d+,\s*\d+,\s*\d+\)|rgba\(\d+,\s*\d+,\s*\d+,\s*[\d.]+\)|hsl\(\d+,\s*\d+%,\s*\d+%\)|hsla\(\d+,\s*\d+%,\s*\d+%,\s*[\d.]+\)|[a-zA-Z]+)$/i;

            if (value && !colorRegex.test(value.trim())) {
                console.warn(`Invalid color format: ${value}`);
            }
        },

        formatColorValue(fieldName) {
            let value = this.getFieldValue(fieldName);
            if (!value) return;

            value = value.trim();

            // Convert common color names or ensure proper hex format
            if (
                value &&
                !value.startsWith("#") &&
                !value.startsWith("rgb") &&
                !value.startsWith("hsl")
            ) {
                // If it's not a recognized format, try to prefix with # if it looks like hex
                if (/^[0-9A-F]{6}$/i.test(value)) {
                    this.setFieldValue(fieldName, "#" + value);
                } else if (/^[0-9A-F]{3}$/i.test(value)) {
                    this.setFieldValue(fieldName, "#" + value);
                }
            }
        },

        getFieldValue(fieldName) {
            if (fieldName.startsWith("section_")) {
                return this.sectionSettings[fieldName];
            } else {
                return this.formData[fieldName];
            }
        },

        setFieldValue(fieldName, value) {
            if (fieldName.startsWith("section_")) {
                this.sectionSettings[fieldName] = value;
            } else {
                this.formData[fieldName] = value;
            }
        },

        isValidColor(color) {
            const tempDiv = document.createElement("div");
            tempDiv.style.color = color;
            return tempDiv.style.color !== "";
        },

        // API methods
        async fetchPrograms() {
            this.isLoading = true;
            try {
                const response = await axios.get("/admin/academic-programs");
                this.programs = response.data.data || response.data;
                console.log("Programs loaded:", this.programs);
            } catch (error) {
                console.error("Error fetching programs:", error);
                this.showError(
                    "Loading Failed",
                    "Failed to load programs from server"
                );
            } finally {
                this.isLoading = false;
            }
        },

        async fetchSectionSettings() {
            try {
                const response = await axios.get(
                    "/admin/academic-programs-settings"
                );
                if (response.data) {
                    this.sectionSettings = {
                        ...this.sectionSettings,
                        ...(response.data.data || response.data),
                    };
                }
            } catch (error) {
                console.error("Error fetching section settings:", error);
                this.showError(
                    "Settings Failed",
                    "Failed to load section settings"
                );
            }
        },

        async saveProgram() {
            this.isSaving = true;
            try {
                // Define the exact fields that should be sent to the backend
                const allowedFields = [
                    "title",
                    "age_range",
                    "icon",
                    "items",
                    "is_active",
                    "icon_background_color",
                    "icon_color",
                    "icon_size",
                    "icon_width",
                    "icon_height",
                    "icon_border_radius",
                    "title_color",
                    "title_font_size",
                    "title_font_weight",
                    "title_margin_bottom",
                    "age_range_color",
                    "age_range_font_size",
                    "age_range_font_weight",
                    "items_color",
                    "items_font_size",
                    "items_font_weight",
                    "items_padding",
                    "items_check_color",
                    "items_padding_left",
                    "card_background_color",
                    "card_border_radius",
                    "card_padding",
                    "card_shadow",
                    "card_hover_shadow",
                    "card_hover_transform",
                    "header_gap",
                    "content_margin_top",
                ];

                // Create clean data object with only allowed fields
                const dataToSend = {};

                // Map ageRange to age_range and include other allowed fields
                allowedFields.forEach((field) => {
                    let sourceField = field;

                    // Handle the ageRange mapping
                    if (field === "age_range") {
                        sourceField = "ageRange";
                    }

                    if (
                        this.formData.hasOwnProperty(sourceField) &&
                        this.formData[sourceField] !== null &&
                        this.formData[sourceField] !== undefined
                    ) {
                        dataToSend[field] = this.formData[sourceField];
                    }
                });

                // Clean and process items array
                if (dataToSend.items) {
                    dataToSend.items = dataToSend.items.filter(
                        (item) => item && item.trim()
                    );
                }

                // Ensure is_active is properly set
                if (
                    this.editingProgram &&
                    this.editingProgram.is_active !== undefined
                ) {
                    dataToSend.is_active = this.editingProgram.is_active;
                } else if (dataToSend.is_active === undefined) {
                    dataToSend.is_active = true; // Default to active
                }

                console.log("Data being sent:", dataToSend);

                let response;
                if (this.editingProgram) {
                    response = await axios.put(
                        `/admin/academic-programs/${this.editingProgram.id}`,
                        dataToSend,
                        {
                            headers: {
                                "Content-Type": "application/json",
                                Accept: "application/json",
                            },
                        }
                    );
                } else {
                    response = await axios.post(
                        "/admin/academic-programs",
                        dataToSend,
                        {
                            headers: {
                                "Content-Type": "application/json",
                                Accept: "application/json",
                            },
                        }
                    );
                }

                console.log("Response:", response.data);

                await this.fetchPrograms();
                this.closeForm();
                this.showSuccess(
                    "Program Saved",
                    this.editingProgram
                        ? "Program updated successfully!"
                        : "Program created successfully!"
                );
            } catch (error) {
                console.error("Error saving program:", error);
                console.error("Error response:", error.response?.data);

                let errorMessage = "Error saving program. Please try again.";
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

                const response = await axios.post(
                    "/admin/academic-programs-settings",
                    formData,
                    {
                        headers: { "Content-Type": "multipart/form-data" },
                    }
                );

                this.sectionSettings = {
                    ...this.sectionSettings,
                    ...(response.data.data || response.data),
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

        async toggleStatus(program) {
            try {
                await axios.post(
                    `/admin/academic-programs/${program.id}/toggle-status`
                );
                await this.fetchPrograms();
                this.showSuccess(
                    "Status Updated",
                    `"${program.title}" is now ${
                        !program.is_active ? "visible" : "hidden"
                    }`
                );
            } catch (error) {
                console.error("Error toggling status:", error);
                this.showError(
                    "Status Update Failed",
                    `Could not change status for "${program.title}"`
                );
            }
        },

        async deleteProgram(id) {
            if (
                confirm("Are you sure you want to delete this program level?")
            ) {
                try {
                    await axios.delete(`/admin/academic-programs/${id}`);
                    await this.fetchPrograms();
                    this.showSuccess(
                        "Program Deleted",
                        "Program level has been permanently removed"
                    );
                } catch (error) {
                    console.error("Error deleting program:", error);
                    this.showError(
                        "Delete Failed",
                        "Could not delete program level"
                    );
                }
            }
        },

        async updateAllOrders() {
            try {
                const updates = this.programs.map((program, index) => ({
                    id: program.id,
                    order: index,
                }));
                await axios.post("/admin/academic-programs/update-order", {
                    programs: updates,
                });
            } catch (error) {
                console.error("Error updating order:", error);
                this.showError(
                    "Order Update Failed",
                    "Could not save new program order"
                );
            }
        },

        // Form methods
        editProgram(program) {
            this.editingProgram = program;

            // Handle the age field mapping issue
            const ageRange =
                program.ageRange || program.age_range || program.agerange || "";

            // Handle items array properly
            let items = [];
            if (Array.isArray(program.items)) {
                items = [...program.items];
            } else if (typeof program.items === "string") {
                try {
                    items = JSON.parse(program.items);
                } catch (e) {
                    items = [program.items];
                }
            } else {
                items = [""];
            }

            this.formData = {
                ...program,
                ageRange: ageRange,
                items: items.length > 0 ? items : [""],
            };

            console.log("Edit program data:", this.formData);
            this.showCreateForm = true;
        },

        closeForm() {
            this.showCreateForm = false;
            this.editingProgram = null;
            this.resetForm();
        },

        closeSettingsForm() {
            this.showSettingsForm = false;
        },

        resetForm() {
            this.formData = {
                title: "",
                ageRange: "",
                icon: "🎓",
                items: [""],
                icon_background_color: "#ff7101",
                icon_color: "#ffffff",
                icon_size: "2.5rem",
                icon_width: "60px",
                icon_height: "60px",
                icon_border_radius: "50%",
                title_color: "#102e4a",
                title_font_size: "1.5rem",
                title_font_weight: "600",
                title_margin_bottom: "5px",
                age_range_color: "#ff7101",
                age_range_font_size: "0.9rem",
                age_range_font_weight: "600",
                items_color: "#111111",
                items_font_size: "1rem",
                items_font_weight: "400",
                items_padding: "8px 0",
                items_check_color: "#20bf6b",
                items_padding_left: "25px",
                card_background_color: "#ffffff",
                card_border_radius: "15px",
                card_padding: "30px",
                card_shadow: "0 5px 20px rgba(0, 0, 0, 0.08)",
                card_hover_shadow: "0 15px 30px rgba(0, 0, 0, 0.15)",
                card_hover_transform: "translateY(-5px)",
                header_gap: "20px",
                content_margin_top: "20px",
            };
        },

        addItem() {
            this.formData.items.push("");
        },

        removeItem(index) {
            if (this.formData.items.length > 1) {
                this.formData.items.splice(index, 1);
            }
        },

        // Drag and drop methods
        onDragStart(event, program, index) {
            this.isDragging = true;
            this.draggedProgram = program;
            this.draggedIndex = index;
            event.dataTransfer.effectAllowed = "move";
            event.dataTransfer.setData("text/html", event.target);
        },

        onDragEnd() {
            this.isDragging = false;
            this.draggedProgram = null;
            this.draggedIndex = null;
        },

        moveUp(index) {
            if (index === 0) return;

            const program = this.programs[index];
            const previousProgram = this.programs[index - 1];

            this.programs.splice(index - 1, 2, program, previousProgram);
            this.updateAllOrders();
            this.showInfo("Program Moved", `"${program.title}" moved up`);
        },

        moveDown(index) {
            if (index === this.programs.length - 1) return;

            const program = this.programs[index];
            const nextProgram = this.programs[index + 1];

            this.programs.splice(index, 2, nextProgram, program);
            this.updateAllOrders();
            this.showInfo("Program Moved", `"${program.title}" moved down`);
        },

        onDrop(event, dropIndex) {
            event.preventDefault();

            if (this.draggedIndex === null || this.draggedIndex === dropIndex) {
                return;
            }

            try {
                const program = this.programs.splice(this.draggedIndex, 1)[0];
                this.programs.splice(dropIndex, 0, program);
                this.updateAllOrders();
                this.showInfo(
                    "Order Updated",
                    `"${program.title}" moved successfully`
                );
            } catch (error) {
                console.error("Drag and drop error:", error);
                this.showError("Reorder Failed", "Could not reorder programs");
                this.fetchPrograms();
            }
        },

        // Utility methods
        formatDateTime(dateString) {
            return new Date(dateString).toLocaleDateString();
        },

        getIconPreviewStyle() {
            return {
                backgroundColor: this.formData.icon_background_color,
                color: this.formData.icon_color,
                fontSize: this.formData.icon_size,
                width: this.formData.icon_width,
                height: this.formData.icon_height,
                borderRadius: this.formData.icon_border_radius,
                display: "flex",
                alignItems: "center",
                justifyContent: "center",
            };
        },

        getProgramIconStyle(program) {
            return {
                backgroundColor: program.icon_background_color,
                color: program.icon_color,
                fontSize: program.icon_size,
                width: program.icon_width,
                height: program.icon_height,
                borderRadius: program.icon_border_radius,
                display: "flex",
                alignItems: "center",
                justifyContent: "center",
                flexShrink: 0,
            };
        },

        getProgramTitleStyle(program) {
            return {
                color: program.title_color,
                fontSize: program.title_font_size,
                fontWeight: program.title_font_weight,
                marginBottom: program.title_margin_bottom,
                margin: `0 0 ${program.title_margin_bottom} 0`,
            };
        },

        getProgramAgeRangeStyle(program) {
            return {
                color: program.age_range_color,
                fontSize: program.age_range_font_size,
                fontWeight: program.age_range_font_weight,
            };
        },

        getProgramItemStyle(program) {
            return {
                color: program.items_color,
                fontSize: program.items_font_size,
                fontWeight: program.items_font_weight,
                padding: program.items_padding,
                paddingLeft: program.items_padding_left,
                position: "relative",
                listStyle: "none",
            };
        },
    },

    mounted() {
        // Fetch actual data from Laravel backend
        this.fetchPrograms();
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
.admin-academic-programs {
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
.program-form,
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

.form-subsection {
    margin-bottom: 25px;
    padding: 15px;
    background: white;
    border-radius: 6px;
    border-left: 3px solid #ff7101;
}

.form-subsection h5 {
    margin: 0 0 15px 0;
    color: #374151;
    font-size: 14px;
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

/* Icon Input Styles */
.icon-input-group {
    display: flex;
    align-items: center;
    gap: 12px;
}

.icon-input {
    flex: 1;
}

.icon-preview {
    min-width: 60px;
    min-height: 60px;
    border: 2px dashed #d1d5db;
    border-radius: 6px;
}

.help-text {
    color: #6b7280;
    font-size: 12px;
    margin-top: 4px;
}

/* Items Manager */
.items-manager {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.item-row {
    display: flex;
    align-items: center;
    gap: 8px;
}

.btn-remove-item {
    background: #ef4444;
    color: white;
    border: none;
    width: 32px;
    height: 32px;
    border-radius: 6px;
    cursor: pointer;
    font-size: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: background 0.2s;
}

.btn-remove-item:hover {
    background: #dc2626;
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

/* Programs List Styles */
.programs-list {
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

.programs-container {
    display: grid;
    gap: 20px;
}

.programs-container.is-dragging {
    background: #f9fafb;
}

/* Program Card Styles */
.program-card {
    background: white;
    border: 1px solid #e5e7eb;
    border-radius: 12px;
    margin-bottom: 20px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.04);
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
}

.program-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
}

.program-card.is-active {
    border-color: #10b981;
    box-shadow: 0 2px 8px rgba(16, 185, 129, 0.15);
}

.program-card.is-dragging {
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

.program-status {
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

.reorder-buttons {
    display: flex;
    gap: 2px;
    margin-right: 8px;
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

.reorder-btn:disabled {
    opacity: 0.5;
    cursor: not-allowed;
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
    padding-left: 50px; /* Account for drag handle */
}

.program-preview {
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.preview-header {
    display: flex;
    align-items: center;
    gap: 20px;
}

.level-icon {
    flex-shrink: 0;
}

.level-info {
    flex: 1;
    min-width: 0;
}

.level-info h3 {
    margin: 0;
}

.level-content ul {
    list-style: none;
    padding: 0;
    margin: 0;
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 10px;
}

.level-content li {
    position: relative;
    list-style: none;
}

.level-content li:before {
    content: "✓";
    color: #20bf6b;
    font-weight: bold;
    position: absolute;
    left: 0;
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
    .programs-container {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 768px) {
    .admin-academic-programs {
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

    .preview-header {
        flex-direction: column;
        align-items: stretch;
        text-align: center;
    }

    .level-content ul {
        grid-template-columns: 1fr;
    }

    .card-content {
        padding-left: 20px;
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

/* Enhanced Color Input Styling */
.enhanced-color-input-group {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 4px;
    border: 1px solid #ddd;
    border-radius: 6px;
    background: #fff;
    transition: border-color 0.2s ease;
}

.enhanced-color-input-group:focus-within {
    border-color: #007cba;
    box-shadow: 0 0 0 1px #007cba;
}

.color-input {
    width: 50px;
    height: 36px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    padding: 0;
    flex-shrink: 0;
}

.color-text-input {
    flex: 1;
    min-width: 100px;
    border: none;
    background: transparent;
    font-family: "Monaco", "Menlo", "Ubuntu Mono", monospace;
    font-size: 13px;
    padding: 4px 8px;
}

.color-text-input:focus {
    outline: none;
}

.color-text-input::placeholder {
    color: #999;
    font-style: italic;
}

.color-preview {
    width: 24px;
    height: 24px;
    border-radius: 4px;
    border: 1px solid #ddd;
    flex-shrink: 0;
    box-shadow: inset 0 0 0 1px rgba(0, 0, 0, 0.1);
}

.help-text {
    color: #666;
    font-size: 11px;
    margin-top: 4px;
    display: block;
    font-style: italic;
}
</style>
