<template>
    <div class="admin-testimonials-section">
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
                <h2>Testimonials Management</h2>
                <p>Manage testimonials, styling, and display settings</p>
            </div>
            <button
                class="btn btn-primary"
                @click="showCreateForm = true"
                :disabled="isLoading"
            >
                <i class="fas fa-plus"></i> Add New Testimonial
            </button>
        </div>

        <!-- Section Settings -->
        <div class="section-settings-card">
            <div class="settings-header">
                <h3>Section Settings</h3>
                <button
                    class="btn btn-sm btn-secondary"
                    @click="showSectionSettings = !showSectionSettings"
                >
                    {{
                        showSectionSettings ? "Hide Settings" : "Show Settings"
                    }}
                </button>
            </div>

            <div v-if="showSectionSettings" class="settings-content">
                <div class="form-row">
                    <div class="form-group">
                        <label>Section Title</label>
                        <input
                            type="text"
                            v-model="sectionSettings.title"
                            class="form-control"
                        />
                    </div>
                    <div class="form-group">
                        <label>Section Subtitle</label>
                        <input
                            type="text"
                            v-model="sectionSettings.subtitle"
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
                                v-model="sectionSettings.background_color"
                                class="form-control color-input"
                            />
                            <input
                                type="text"
                                v-model="sectionSettings.background_color"
                                class="form-control color-text-input"
                                placeholder="#102e4a"
                            />
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Title Color</label>
                        <div class="color-input-group">
                            <input
                                type="color"
                                v-model="sectionSettings.title_color"
                                class="form-control color-input"
                            />
                            <input
                                type="text"
                                v-model="sectionSettings.title_color"
                                class="form-control color-text-input"
                                placeholder="#ffffff"
                            />
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Text Color</label>
                        <div class="color-input-group">
                            <input
                                type="color"
                                v-model="sectionSettings.text_color"
                                class="form-control color-input"
                            />
                            <input
                                type="text"
                                v-model="sectionSettings.text_color"
                                class="form-control color-text-input"
                                placeholder="#ffffff"
                            />
                        </div>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label>Card Background</label>
                        <div class="color-input-group">
                            <input
                                type="color"
                                v-model="sectionSettings.card_background"
                                class="form-control color-input"
                            />
                            <input
                                type="text"
                                v-model="sectionSettings.card_background"
                                class="form-control color-text-input"
                                placeholder="rgba(255, 255, 255, 0.1)"
                            />
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Card Border Color</label>
                        <div class="color-input-group">
                            <input
                                type="color"
                                v-model="sectionSettings.card_border_color"
                                class="form-control color-input"
                            />
                            <input
                                type="text"
                                v-model="sectionSettings.card_border_color"
                                class="form-control color-text-input"
                                placeholder="rgba(255, 255, 255, 0.2)"
                            />
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Avatar Background</label>
                        <div class="color-input-group">
                            <input
                                type="color"
                                v-model="sectionSettings.avatar_background"
                                class="form-control color-input"
                            />
                            <input
                                type="text"
                                v-model="sectionSettings.avatar_background"
                                class="form-control color-text-input"
                                placeholder="#ff7101"
                            />
                        </div>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label>Animation Style</label>
                        <select
                            v-model="sectionSettings.animation_style"
                            class="form-control"
                        >
                            <option value="fadeInUp">Fade In Up</option>
                            <option value="slideInLeft">Slide In Left</option>
                            <option value="slideInRight">Slide In Right</option>
                            <option value="zoomIn">Zoom In</option>
                            <option value="bounceIn">Bounce In</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Animation Duration (ms)</label>
                        <input
                            type="number"
                            v-model="sectionSettings.animation_duration"
                            class="form-control"
                            min="100"
                            max="3000"
                            step="100"
                        />
                    </div>
                    <div class="form-group">
                        <label>Columns Layout</label>
                        <select
                            v-model="sectionSettings.columns"
                            class="form-control"
                        >
                            <option value="1">1 Column</option>
                            <option value="2">2 Columns</option>
                            <option value="3">3 Columns</option>
                            <option value="auto">Auto Fit</option>
                        </select>
                    </div>
                </div>

                <div class="form-actions">
                    <button
                        class="btn btn-primary"
                        @click="saveSectionSettings"
                        :disabled="isSaving"
                    >
                        {{ isSaving ? "Saving..." : "Save Section Settings" }}
                    </button>
                </div>
            </div>
        </div>

        <!-- Create/Edit Form Modal -->
        <div
            v-if="showCreateForm || editingTestimonial"
            class="modal-overlay"
            @click="closeForm"
        >
            <div class="modal-content" @click.stop>
                <div class="modal-header">
                    <h3>
                        {{ editingTestimonial ? "Edit" : "Create" }} Testimonial
                    </h3>
                    <button @click="closeForm" class="close-btn">
                        &times;
                    </button>
                </div>

                <form
                    @submit.prevent="saveTestimonial"
                    class="testimonial-form"
                >
                    <!-- Basic Information -->
                    <div class="form-section">
                        <h4>Basic Information</h4>
                        <div class="form-row">
                            <div class="form-group full-width">
                                <label>Testimonial Text</label>
                                <textarea
                                    v-model="formData.text"
                                    required
                                    rows="4"
                                    class="form-control"
                                    placeholder="Enter the testimonial text..."
                                ></textarea>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label>Name</label>
                                <input
                                    type="text"
                                    v-model="formData.name"
                                    required
                                    class="form-control"
                                />
                            </div>
                            <div class="form-group">
                                <label>Role/Title</label>
                                <input
                                    type="text"
                                    v-model="formData.role"
                                    required
                                    class="form-control"
                                />
                            </div>
                        </div>
                    </div>

                    <!-- Avatar Settings -->
                    <div class="form-section">
                        <h4>Avatar Settings</h4>
                        <div class="form-row">
                            <div class="form-group">
                                <label>Avatar Type</label>
                                <select
                                    v-model="formData.avatar_type"
                                    class="form-control"
                                    @change="onAvatarTypeChange"
                                >
                                    <option value="initials">
                                        Text Initials
                                    </option>
                                    <option value="image">Upload Image</option>
                                </select>
                            </div>
                        </div>

                        <div
                            v-if="formData.avatar_type === 'initials'"
                            class="form-row"
                        >
                            <div class="form-group">
                                <label>Initials</label>
                                <input
                                    type="text"
                                    v-model="formData.initials"
                                    maxlength="3"
                                    class="form-control"
                                    placeholder="SA"
                                />
                            </div>
                            <div class="form-group">
                                <label>Avatar Background Color</label>
                                <div class="color-input-group">
                                    <input
                                        type="color"
                                        v-model="formData.avatar_bg_color"
                                        class="form-control color-input"
                                    />
                                    <input
                                        type="text"
                                        v-model="formData.avatar_bg_color"
                                        class="form-control color-text-input"
                                        placeholder="#ff7101"
                                    />
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Avatar Text Color</label>
                                <div class="color-input-group">
                                    <input
                                        type="color"
                                        v-model="formData.avatar_text_color"
                                        class="form-control color-input"
                                    />
                                    <input
                                        type="text"
                                        v-model="formData.avatar_text_color"
                                        class="form-control color-text-input"
                                        placeholder="#ffffff"
                                    />
                                </div>
                            </div>
                        </div>

                        <div
                            v-if="formData.avatar_type === 'image'"
                            class="form-row"
                        >
                            <div class="form-group full-width">
                                <label>Upload Avatar Image</label>
                                <input
                                    type="file"
                                    @change="onImageChange"
                                    accept="image/*"
                                    class="form-control"
                                />
                                <div v-if="imagePreview" class="image-preview">
                                    <img
                                        :src="imagePreview"
                                        alt="Avatar Preview"
                                    />
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Text Styling -->
                    <div class="form-section">
                        <h4>Text Styling</h4>
                        <div class="form-row">
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
                                        class="form-control color-text-input"
                                        placeholder="#ffffff"
                                    />
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Name Color</label>
                                <div class="color-input-group">
                                    <input
                                        type="color"
                                        v-model="formData.name_color"
                                        class="form-control color-input"
                                    />
                                    <input
                                        type="text"
                                        v-model="formData.name_color"
                                        class="form-control color-text-input"
                                        placeholder="#ffffff"
                                    />
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Role Color</label>
                                <div class="color-input-group">
                                    <input
                                        type="color"
                                        v-model="formData.role_color"
                                        class="form-control color-input"
                                    />
                                    <input
                                        type="text"
                                        v-model="formData.role_color"
                                        class="form-control color-text-input"
                                        placeholder="#ffffff"
                                    />
                                </div>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label>Text Font Size</label>
                                <input
                                    type="text"
                                    v-model="formData.text_font_size"
                                    placeholder="1.1rem"
                                    class="form-control"
                                />
                            </div>
                            <div class="form-group">
                                <label>Name Font Size</label>
                                <input
                                    type="text"
                                    v-model="formData.name_font_size"
                                    placeholder="1rem"
                                    class="form-control"
                                />
                            </div>
                            <div class="form-group">
                                <label>Role Font Size</label>
                                <input
                                    type="text"
                                    v-model="formData.role_font_size"
                                    placeholder="0.9rem"
                                    class="form-control"
                                />
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label>Text Font Weight</label>
                                <select
                                    v-model="formData.text_font_weight"
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
                                <label>Name Font Weight</label>
                                <select
                                    v-model="formData.name_font_weight"
                                    class="form-control"
                                >
                                    <option value="400">Normal</option>
                                    <option value="500">Medium</option>
                                    <option value="600">Semi Bold</option>
                                    <option value="700">Bold</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Role Font Weight</label>
                                <select
                                    v-model="formData.role_font_weight"
                                    class="form-control"
                                >
                                    <option value="300">Light</option>
                                    <option value="400">Normal</option>
                                    <option value="500">Medium</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Card Styling -->
                    <div class="form-section">
                        <h4>Card Styling</h4>
                        <div class="form-row">
                            <div class="form-group">
                                <label>Card Background</label>
                                <div class="color-input-group">
                                    <input
                                        type="color"
                                        v-model="formData.card_background"
                                        class="form-control color-input"
                                    />
                                    <input
                                        type="text"
                                        v-model="formData.card_background"
                                        class="form-control color-text-input"
                                        placeholder="rgba(255, 255, 255, 0.1)"
                                    />
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Card Border Color</label>
                                <div class="color-input-group">
                                    <input
                                        type="color"
                                        v-model="formData.card_border_color"
                                        class="form-control color-input"
                                    />
                                    <input
                                        type="text"
                                        v-model="formData.card_border_color"
                                        class="form-control color-text-input"
                                        placeholder="rgba(255, 255, 255, 0.2)"
                                    />
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Card Border Radius</label>
                                <input
                                    type="text"
                                    v-model="formData.card_border_radius"
                                    placeholder="15px"
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
                                    : editingTestimonial
                                    ? "Update"
                                    : "Create"
                            }}
                            Testimonial
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Testimonials List -->
        <div class="testimonials-list">
            <div class="list-header">
                <h3>Testimonials ({{ testimonials.length }})</h3>
                <div class="drag-info">
                    <span>💡 Drag cards to reorder</span>
                </div>
            </div>

            <div
                class="testimonials-container"
                @dragover="onDragOver"
                @drop="onDrop"
            >
                <div
                    v-for="(testimonial, index) in testimonials"
                    :key="testimonial.id"
                    class="testimonial-item"
                    :class="{
                        'is-active': testimonial.is_active,
                        dragging: draggedItem?.id === testimonial.id,
                    }"
                    draggable="true"
                    @dragstart="onDragStart(testimonial, index, $event)"
                    @dragend="onDragEnd"
                >
                    <div class="testimonial-card" >
                        <div class="drag-handle">⋮⋮</div>

                        <div class="card-header">
                            <div class="testimonial-status">
                                <span
                                    class="status-indicator"
                                    :class="{ active: testimonial.is_active }"
                                ></span>
                                <span class="status-text">{{
                                    testimonial.is_active
                                        ? "Active"
                                        : "Inactive"
                                }}</span>
                            </div>
                            <div class="card-actions">
                                <button
                                    class="action-btn toggle-btn"
                                    @click="toggleTestimonial(testimonial)"
                                    :class="{ active: testimonial.is_active }"
                                >
                                    {{
                                        testimonial.is_active ? "Hide" : "Show"
                                    }}
                                </button>
                                <button
                                    class="action-btn delete-btn"
                                    @click="deleteTestimonial(testimonial.id)"
                                    title="Delete"
                                >
                                    Delete
                                </button>
                            </div>
                        </div>

                        <div class="card-content" :style="{ backgroundColor: sectionSettings.background_color }">
                            <div
                                class="testimonial-preview"
                                :style="getCardStyle(testimonial)"
                            >
                                <div
                                    class="testimonial-text"
                                    :style="getTextStyle(testimonial)"
                                >
                                    "{{ testimonial.text.substring(0, 120)
                                    }}{{
                                        testimonial.text.length > 120
                                            ? "..."
                                            : ""
                                    }}"
                                </div>
                                <div class="testimonial-author">
                                    <div
                                        class="author-avatar"
                                        :style="getAvatarStyle(testimonial)"
                                    >
                                        <img
                                            v-if="
                                                testimonial.avatar_type ===
                                                    'image' &&
                                                testimonial.avatar_url
                                            "
                                            :src="testimonial.avatar_url"
                                            :alt="testimonial.name"
                                        />
                                        <span v-else>{{
                                            testimonial.initials
                                        }}</span>
                                    </div>
                                    <div class="author-info">
                                        <h4
                                            :style="{
                                                color: testimonial.name_color,
                                                fontSize:
                                                    testimonial.name_font_size,
                                                fontWeight:
                                                    testimonial.name_font_weight,
                                            }"
                                        >
                                            {{ testimonial.name }}
                                        </h4>
                                        <p
                                            :style="{
                                                color: testimonial.role_color,
                                                fontSize:
                                                    testimonial.role_font_size,
                                                fontWeight:
                                                    testimonial.role_font_weight,
                                            }"
                                        >
                                            {{ testimonial.role }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card-footer">
                            <small class="update-time">
                                Last updated:
                                {{ formatDate(testimonial.updated_at) }}
                            </small>
                            <button
                                @click="editTestimonial(testimonial)"
                                class="btn btn-sm btn-outline-primary"
                            >
                                <i class="fas fa-edit"></i> Edit
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div v-if="testimonials.length === 0" class="empty-state">
                <div class="empty-icon">💬</div>
                <h4>No testimonials yet</h4>
                <p>Get started by adding your first testimonial</p>
                <button
                    class="btn btn-primary"
                    @click="showCreateForm = true"
                    :disabled="isLoading"
                >
                    + Add Your First Testimonial
                </button>
            </div>
        </div>

        <!-- Live Preview -->
        <div class="live-preview">
            <h3>Live Preview</h3>
            <div class="preview-section" :style="getPreviewSectionStyle()">
                <div class="container">
                    <div class="section-header">
                        <h2 :style="{ color: sectionSettings.title_color }">
                            {{ sectionSettings.title }}
                        </h2>
                        <p :style="{ color: sectionSettings.text_color }">
                            {{ sectionSettings.subtitle }}
                        </p>
                    </div>
                    <div
                        class="testimonials-grid"
                        :style="getPreviewGridStyle()"
                    >
                        <div
                            v-for="testimonial in activeTestimonials.slice(
                                0,
                                3
                            )"
                            :key="testimonial.id"
                            class="testimonial-card-preview"
                            :style="getCardStyle(testimonial)"
                        >
                            <div
                                class="testimonial-text"
                                :style="getTextStyle(testimonial)"
                            >
                                "{{ testimonial.text }}"
                            </div>
                            <div class="testimonial-author">
                                <div
                                    class="author-avatar"
                                    :style="getAvatarStyle(testimonial)"
                                >
                                    <img
                                        v-if="
                                            testimonial.avatar_type ===
                                                'image' &&
                                            testimonial.avatar_url
                                        "
                                        :src="testimonial.avatar_url"
                                        :alt="testimonial.name"
                                    />
                                    <span v-else>{{
                                        testimonial.initials
                                    }}</span>
                                </div>
                                <div class="author-info">
                                    <h4
                                        :style="{
                                            color: testimonial.name_color,
                                            fontSize:
                                                testimonial.name_font_size,
                                            fontWeight:
                                                testimonial.name_font_weight,
                                        }"
                                    >
                                        {{ testimonial.name }}
                                    </h4>
                                    <p
                                        :style="{
                                            color: testimonial.role_color,
                                            fontSize:
                                                testimonial.role_font_size,
                                            fontWeight:
                                                testimonial.role_font_weight,
                                        }"
                                    >
                                        {{ testimonial.role }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import axios from "axios";

export default {
    name: "TestimonialsAdmin",
    data() {
        return {
            testimonials: [
                {
                    id: 1,
                    text: "Edu World has been a blessing for our family. The way they seamlessly integrate Islamic values with academic excellence is truly remarkable. My daughter has grown not just academically but spiritually as well.",
                    name: "Sarah Ahmed",
                    role: "Parent of Year 10 Student",
                    initials: "SA",
                    avatar_url: null,
                    avatar_type: "initials",
                    avatar_bg_color: "#ff7101",
                    avatar_text_color: "#ffffff",
                    text_color: "#ffffff",
                    name_color: "#ffffff",
                    role_color: "#ffffff",
                    text_font_size: "1.1rem",
                    name_font_size: "1rem",
                    role_font_size: "0.9rem",
                    text_font_weight: "400",
                    name_font_weight: "600",
                    role_font_weight: "400",
                    card_background: "rgba(255, 255, 255, 0.1)",
                    card_border_color: "rgba(255, 255, 255, 0.2)",
                    card_border_radius: "15px",
                    is_active: true,
                    order: 1,
                    created_at: "2024-01-15T10:00:00Z",
                    updated_at: "2024-01-15T10:00:00Z",
                },
                {
                    id: 2,
                    text: "The teachers at Edu World truly care about each student's success. The Islamic Studies program helped me understand my faith better while the Cambridge curriculum prepared me excellently for university.",
                    name: "Muhammad Hassan",
                    role: "Alumni - Cambridge University",
                    initials: "MH",
                    avatar_url: null,
                    avatar_type: "initials",
                    avatar_bg_color: "#ff7101",
                    avatar_text_color: "#ffffff",
                    text_color: "#ffffff",
                    name_color: "#ffffff",
                    role_color: "#ffffff",
                    text_font_size: "1.1rem",
                    name_font_size: "1rem",
                    role_font_size: "0.9rem",
                    text_font_weight: "400",
                    name_font_weight: "600",
                    role_font_weight: "400",
                    card_background: "rgba(255, 255, 255, 0.1)",
                    card_border_color: "rgba(255, 255, 255, 0.2)",
                    card_border_radius: "15px",
                    is_active: true,
                    order: 2,
                    created_at: "2024-01-16T10:00:00Z",
                    updated_at: "2024-01-16T10:00:00Z",
                },
            ],
            sectionSettings: {
                title: "What Our Community Says",
                subtitle:
                    "Hear from parents, students, and graduates about their transformative experience at Edu World.",
                background_color: "#102e4a",
                title_color: "#ffffff",
                text_color: "#ffffff",
                card_background: "rgba(255, 255, 255, 0.1)",
                card_border_color: "rgba(255, 255, 255, 0.2)",
                avatar_background: "#ff7101",
                animation_style: "fadeInUp",
                animation_duration: 600,
                columns: "auto",
            },
            showCreateForm: false,
            showSectionSettings: false,
            editingTestimonial: null,
            imagePreview: null,
            selectedImage: null,
            isLoading: false,
            isSaving: false,
            toasts: [],
            toastIdCounter: 0,
            draggedItem: null,
            draggedIndex: null,
            formData: {
                text: "",
                name: "",
                role: "",
                initials: "",
                avatar_type: "initials",
                avatar_bg_color: "#ff7101",
                avatar_text_color: "#ffffff",
                text_color: "#ffffff",
                name_color: "#ffffff",
                role_color: "#ffffff",
                text_font_size: "1.1rem",
                name_font_size: "1rem",
                role_font_size: "0.9rem",
                text_font_weight: "400",
                name_font_weight: "600",
                role_font_weight: "400",
                card_background: "rgba(255, 255, 255, 0.1)",
                card_border_color: "rgba(255, 255, 255, 0.2)",
                card_border_radius: "15px",
            },
        };
    },
    computed: {
        activeTestimonials() {
            return this.testimonials
                .filter((t) => t.is_active)
                .sort((a, b) => a.order - b.order);
        },
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

        // Drag and Drop methods
        onDragStart(testimonial, index, event) {
            this.draggedItem = testimonial;
            this.draggedIndex = index;
            event.dataTransfer.effectAllowed = "move";
            event.dataTransfer.setData("text/html", event.target.outerHTML);
        },

        onDragEnd() {
            this.draggedItem = null;
            this.draggedIndex = null;
        },

        onDragOver(event) {
            event.preventDefault();
            event.dataTransfer.dropEffect = "move";
        },

        onDrop(event) {
            event.preventDefault();

            if (!this.draggedItem) return;

            const dropTarget = event.target.closest(".testimonial-item");
            if (!dropTarget) return;

            const allItems = Array.from(event.currentTarget.children);
            const dropIndex = allItems.indexOf(dropTarget);

            if (dropIndex === -1 || dropIndex === this.draggedIndex) return;

            // Reorder the testimonials array
            const draggedTestimonial = this.testimonials.splice(
                this.draggedIndex,
                1
            )[0];
            this.testimonials.splice(dropIndex, 0, draggedTestimonial);

            // Update orders and save
            this.updateAllOrders();
            this.showSuccess(
                "Order Updated",
                `"${this.draggedItem.name}" moved to position ${dropIndex + 1}`
            );
        },

        // Form methods
        resetForm() {
            this.formData = {
                text: "",
                name: "",
                role: "",
                initials: "",
                avatar_type: "initials",
                avatar_bg_color: "#ff7101",
                avatar_text_color: "#ffffff",
                text_color: "#ffffff",
                name_color: "#ffffff",
                role_color: "#ffffff",
                text_font_size: "1.1rem",
                name_font_size: "1rem",
                role_font_size: "0.9rem",
                text_font_weight: "400",
                name_font_weight: "600",
                role_font_weight: "400",
                card_background: "rgba(255, 255, 255, 0.1)",
                card_border_color: "rgba(255, 255, 255, 0.2)",
                card_border_radius: "15px",
            };
            this.imagePreview = null;
            this.selectedImage = null;
        },

        closeForm() {
            this.showCreateForm = false;
            this.editingTestimonial = null;
            this.resetForm();
        },

        onAvatarTypeChange() {
            if (this.formData.avatar_type === "initials") {
                this.imagePreview = null;
                this.selectedImage = null;
            }
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

        // CRUD Operations
        async fetchTestimonials() {
            this.isLoading = true;
            try {
                const response = await axios.get("/admin/testimonials");
                this.testimonials = response.data.testimonials || [];
                if (response.data.section_settings) {
                    this.sectionSettings = {
                        ...this.sectionSettings,
                        ...response.data.section_settings,
                    };
                }
                this.showSuccess(
                    "Data Loaded",
                    `Successfully loaded ${this.testimonials.length} testimonials`
                );
            } catch (error) {
                console.error("Error fetching testimonials:", error);
                this.showError(
                    "Loading Failed",
                    "Failed to load testimonials from server"
                );
            } finally {
                this.isLoading = false;
            }
        },

        async saveTestimonial() {
            this.isSaving = true;
            try {
                const formData = new FormData();

                // Auto-generate initials if not provided
                if (
                    this.formData.avatar_type === "initials" &&
                    !this.formData.initials
                ) {
                    const nameParts = this.formData.name.trim().split(" ");
                    this.formData.initials = nameParts
                        .map((part) => part[0])
                        .join("")
                        .substring(0, 3)
                        .toUpperCase();
                }

                // Append all form fields
                Object.keys(this.formData).forEach((key) => {
                    if (
                        this.formData[key] !== null &&
                        this.formData[key] !== undefined
                    ) {
                        formData.append(key, this.formData[key]);
                    }
                });

                // Append image if selected
                if (this.selectedImage) {
                    formData.append("avatar_image", this.selectedImage);
                }

                let response;
                if (this.editingTestimonial) {
                    formData.append("_method", "PUT");
                    response = await axios.post(
                        `/admin/testimonials/${this.editingTestimonial.id}`,
                        formData,
                        {
                            headers: { "Content-Type": "multipart/form-data" },
                        }
                    );
                } else {
                    response = await axios.post(
                        "/admin/testimonials",
                        formData,
                        {
                            headers: { "Content-Type": "multipart/form-data" },
                        }
                    );
                }

                await this.fetchTestimonials();
                this.closeForm();
                this.showSuccess(
                    "Testimonial Saved",
                    this.editingTestimonial
                        ? "Testimonial updated successfully!"
                        : "Testimonial created successfully!"
                );
            } catch (error) {
                console.error("Error saving testimonial:", error);
                let errorMessage =
                    "Error saving testimonial. Please try again.";
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

        editTestimonial(testimonial) {
            this.editingTestimonial = testimonial;
            this.formData = { ...testimonial };

            if (testimonial.avatar_url) {
                this.imagePreview = testimonial.avatar_url;
            } else {
                this.imagePreview = null;
            }
            this.selectedImage = null;
        },

        async toggleTestimonial(testimonial) {
            try {
                const response = await axios.put(
                    `/admin/testimonials/${testimonial.id}`,
                    {
                        ...testimonial,
                        is_active: !testimonial.is_active,
                    }
                );

                await this.fetchTestimonials();
                this.showSuccess(
                    "Status Updated",
                    `"${testimonial.name}" is now ${
                        !testimonial.is_active ? "active" : "inactive"
                    }`
                );
            } catch (error) {
                console.error("Error toggling testimonial:", error);
                this.showError(
                    "Status Update Failed",
                    `Could not change status for "${testimonial.name}"`
                );
            }
        },

        async deleteTestimonial(id) {
            if (confirm("Are you sure you want to delete this testimonial?")) {
                try {
                    await axios.delete(`/admin/testimonials/${id}`);
                    await this.fetchTestimonials();
                    this.showSuccess(
                        "Testimonial Deleted",
                        "Testimonial has been permanently removed"
                    );
                } catch (error) {
                    console.error("Error deleting testimonial:", error);
                    this.showError(
                        "Delete Failed",
                        "Could not delete testimonial"
                    );
                }
            }
        },

        async updateAllOrders() {
            try {
                const updates = this.testimonials.map((testimonial, index) => {
                    testimonial.order = index;
                    return axios.put(
                        `/admin/testimonials/${testimonial.id}`,
                        testimonial
                    );
                });
                await Promise.all(updates);
            } catch (error) {
                console.error("Error updating order:", error);
                this.showError(
                    "Order Update Failed",
                    "Could not save new testimonial order"
                );
            }
        },

        async saveSectionSettings() {
            this.isSaving = true;
            try {
                await axios.post(
                    "/admin/testimonials/section-settings",
                    this.sectionSettings
                );
                this.showSuccess(
                    "Settings Saved",
                    "Section settings updated successfully!"
                );
            } catch (error) {
                console.error("Error saving section settings:", error);
                this.showError(
                    "Settings Save Failed",
                    "Could not save section settings"
                );
            } finally {
                this.isSaving = false;
            }
        },

        // Style methods (consolidated)
        getCardStyle(testimonial) {
            return {
                background: testimonial.card_background,
                backdropFilter: "blur(10px)",
                padding: "25px",
                borderRadius: testimonial.card_border_radius,
                border: `1px solid ${testimonial.card_border_color}`,
                transition: "all 0.3s ease",
            };
        },

        getTextStyle(testimonial) {
            return {
                fontStyle: "italic",
                fontSize: testimonial.text_font_size,
                fontWeight: testimonial.text_font_weight,
                color: testimonial.text_color,
                marginBottom: "20px",
                lineHeight: "1.6",
            };
        },

        getAvatarStyle(testimonial) {
            return {
                width: "50px",
                height: "50px",
                borderRadius: "50%",
                background: testimonial.avatar_bg_color,
                color: testimonial.avatar_text_color,
                display: "flex",
                alignItems: "center",
                justifyContent: "center",
                fontWeight: "600",
                fontSize: "1.2rem",
                overflow: "hidden",
                flexShrink: "0",
            };
        },

        // Preview methods
        getPreviewSectionStyle() {
            return {
                background: this.sectionSettings.background_color,
                padding: "80px 0",
                color: this.sectionSettings.text_color,
            };
        },

        getPreviewGridStyle() {
            let gridColumns;
            switch (this.sectionSettings.columns) {
                case "1":
                    gridColumns = "1fr";
                    break;
                case "2":
                    gridColumns = "repeat(2, 1fr)";
                    break;
                case "3":
                    gridColumns = "repeat(3, 1fr)";
                    break;
                default:
                    gridColumns = "repeat(auto-fit, minmax(350px, 1fr))";
            }
            return {
                display: "grid",
                gridTemplateColumns: gridColumns,
                gap: "30px",
                marginTop: "60px",
            };
        },

        formatDate(dateString) {
            return new Date(dateString).toLocaleDateString();
        },
    },

    mounted() {
        this.fetchTestimonials();
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

/* Main Layout */
.admin-testimonials-section {
    padding: 24px;
    background: #f8f9fa;
    min-height: 100vh;
    font-family: "Inter", -apple-system, BlinkMacSystemFont, sans-serif;
}

/* Header */
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

/* Section Settings Card */
.section-settings-card {
    background: white;
    border-radius: 12px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    margin-bottom: 24px;
}

.settings-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 20px;
    border-bottom: 1px solid #e5e7eb;
}

.settings-header h3 {
    margin: 0;
    color: #1f2937;
    font-size: 18px;
    font-weight: 600;
}

.settings-content {
    padding: 20px;
}

/* Modal */
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
    max-width: 1000px;
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
    padding: 0;
    width: 30px;
    height: 30px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    transition: all 0.2s;
}

.close-btn:hover {
    background: #f3f4f6;
    color: #000;
}

/* Forms */
.testimonial-form {
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
    font-family: inherit;
}

.form-control:focus {
    outline: none;
    border-color: #ff7101;
    box-shadow: 0 0 0 3px rgba(255, 113, 1, 0.1);
}

textarea.form-control {
    resize: vertical;
    min-height: 100px;
}

/* Color inputs */
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
}

/* Image preview */
.image-preview {
    margin-top: 10px;
}

.image-preview img {
    width: 80px;
    height: 80px;
    object-fit: cover;
    border-radius: 50%;
    border: 2px solid #e5e7eb;
}

/* Form actions */
.form-actions {
    display: flex;
    justify-content: flex-end;
    gap: 10px;
    margin-top: 30px;
    padding-top: 20px;
    border-top: 1px solid #e5e7eb;
}

/* List */
.testimonials-list {
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

.testimonials-container {
    display: grid;
    gap: 20px;
}

.testimonials-container.view-grid {
    grid-template-columns: repeat(auto-fill, minmax(400px, 1fr));
}

.testimonials-container.view-list {
    grid-template-columns: 1fr;
}

/* Cards */
.testimonial-card {
    background: white;
    border: 2px solid #e5e7eb;
    border-radius: 12px;
    overflow: hidden;
    transition: all 0.2s;
    box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
}

.testimonial-card:hover {
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

.testimonial-status {
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

.testimonial-preview {
    padding: 20px;
    border-radius: 12px;
    margin-bottom: 15px;
}

.testimonial-text {
    font-style: italic;
    margin-bottom: 20px;
    line-height: 1.6;
}

.testimonial-author {
    display: flex;
    align-items: center;
    gap: 15px;
}

.author-avatar {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
    font-size: 1.2rem;
    overflow: hidden;
}

.author-avatar img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.author-info h4 {
    font-weight: 600;
    margin: 0 0 5px 0;
    font-size: 14px;
}

.author-info p {
    margin: 0;
    font-size: 12px;
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

/* Empty state */
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

/* Live Preview */
.live-preview {
    margin-top: 40px;
    background: white;
    border-radius: 12px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    overflow: hidden;
}

.live-preview h3 {
    margin: 0;
    padding: 20px;
    color: #1f2937;
    font-size: 18px;
    font-weight: 600;
    border-bottom: 1px solid #e5e7eb;
}

.preview-section {
    min-height: 400px;
}

.container {
    max-width: 1240px;
    margin: 0 auto;
    padding: 0 20px;
}

.section-header {
    text-align: center;
    margin-bottom: 60px;
}

.section-header h2 {
    font-size: 2.5rem;
    margin-bottom: 15px;
    font-weight: 700;
}

.section-header p {
    font-size: 1.1rem;
    max-width: 600px;
    margin: 0 auto;
    opacity: 0.9;
}

.testimonial-card-preview {
    backdrop-filter: blur(10px);
    transition: all 0.3s ease;
}

.testimonial-card-preview:hover {
    transform: translateY(-10px);
}

/* Buttons */
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

.btn-sm {
    padding: 6px 12px;
    font-size: 13px;
}

.btn:disabled {
    opacity: 0.6;
    cursor: not-allowed;
    transform: none !important;
}

/* Responsive */
@media (max-width: 1024px) {
    .testimonials-container.view-grid {
        grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
    }
}

@media (max-width: 768px) {
    .admin-testimonials-section {
        padding: 16px;
    }

    .admin-header {
        flex-direction: column;
        gap: 16px;
        padding: 20px;
    }

    .testimonials-container.view-grid {
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
</style>
