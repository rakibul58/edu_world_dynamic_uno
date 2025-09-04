<template>
    <div class="admin-admission-process">
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
                <h2>Admission Process Management</h2>
                <p>
                    Manage your admission process sections and customize their
                    appearance
                </p>
            </div>
            <button
                class="btn btn-primary"
                @click="showCreateForm = true"
                :disabled="isLoading"
            >
                <i class="fas fa-plus"></i>
                <span>Create New Process</span>
            </button>
        </div>

        <!-- Create/Edit Form Modal -->
        <div
            v-if="showCreateForm || editingProcess"
            class="modal-overlay"
            @click="closeForm"
        >
            <div class="modal-content" @click.stop>
                <div class="modal-header">
                    <h3>
                        {{ editingProcess ? "Edit" : "Create" }} Admission
                        Process
                    </h3>
                    <button @click="closeForm" class="close-btn">
                        &times;
                    </button>
                </div>

                <div class="modal-body">
                    <form @submit.prevent="saveProcess" class="process-form">
                        <!-- Basic Content -->
                        <div class="form-section">
                            <h4><i class="fas fa-edit"></i> Content</h4>
                            <div class="form-row">
                                <div class="form-group full-width">
                                    <label>Title</label>
                                    <input
                                        type="text"
                                        v-model="formData.title"
                                        required
                                        class="form-control"
                                        placeholder="Enter process title"
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
                                        placeholder="Enter process description"
                                    ></textarea>
                                </div>
                            </div>
                        </div>

                        <!-- Section Styling -->
                        <div class="form-section">
                            <h4>
                                <i class="fas fa-palette"></i> Section Style
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
                                            placeholder="#102e4a"
                                        />
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
                                        <input
                                            type="text"
                                            v-model="formData.text_color"
                                            class="form-control color-text-input"
                                            placeholder="#ffffff"
                                        />
                                    </div>
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

                        <!-- Title Styling -->
                        <div class="form-section">
                            <h4>
                                <i class="fas fa-heading"></i> Title Styling
                            </h4>
                            <div class="form-row">
                                <div class="form-group">
                                    <label>Title Color</label>
                                    <div class="color-input-group">
                                        <input
                                            type="color"
                                            v-model="formData.title_color"
                                            class="form-control color-input"
                                        />
                                        <input
                                            type="text"
                                            v-model="formData.title_color"
                                            class="form-control color-text-input"
                                            placeholder="#ffffff"
                                        />
                                    </div>
                                </div>
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
                            </div>
                        </div>

                        <!-- Description Styling -->
                        <div class="form-section">
                            <h4>
                                <i class="fas fa-align-left"></i> Description
                                Styling
                            </h4>
                            <div class="form-row">
                                <div class="form-group">
                                    <label>Description Color</label>
                                    <div class="color-input-group">
                                        <input
                                            type="color"
                                            v-model="formData.description_color"
                                            class="form-control color-input"
                                        />
                                        <input
                                            type="text"
                                            v-model="formData.description_color"
                                            class="form-control color-text-input"
                                            placeholder="#ffffff"
                                        />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>Description Font Size</label>
                                    <input
                                        type="text"
                                        v-model="formData.description_font_size"
                                        placeholder="1.1rem"
                                        class="form-control"
                                    />
                                </div>
                                <div class="form-group">
                                    <label>Description Opacity</label>
                                    <input
                                        type="text"
                                        v-model="formData.description_opacity"
                                        placeholder="0.9"
                                        class="form-control"
                                    />
                                </div>
                            </div>
                        </div>

                        <!-- Step Styling -->
                        <div class="form-section">
                            <h4><i class="fas fa-list-ol"></i> Step Styling</h4>
                            <div class="form-row">
                                <div class="form-group">
                                    <label>Step Number Background</label>
                                    <div class="color-input-group">
                                        <input
                                            type="color"
                                            v-model="
                                                formData.step_number_bg_color
                                            "
                                            class="form-control color-input"
                                        />
                                        <input
                                            type="text"
                                            v-model="
                                                formData.step_number_bg_color
                                            "
                                            class="form-control color-text-input"
                                            placeholder="#ff7101"
                                        />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>Step Number Text Color</label>
                                    <div class="color-input-group">
                                        <input
                                            type="color"
                                            v-model="
                                                formData.step_number_text_color
                                            "
                                            class="form-control color-input"
                                        />
                                        <input
                                            type="text"
                                            v-model="
                                                formData.step_number_text_color
                                            "
                                            class="form-control color-text-input"
                                            placeholder="#ffffff"
                                        />
                                    </div>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group">
                                    <label>Step Title Color</label>
                                    <div class="color-input-group">
                                        <input
                                            type="color"
                                            v-model="formData.step_title_color"
                                            class="form-control color-input"
                                        />
                                        <input
                                            type="text"
                                            v-model="formData.step_title_color"
                                            class="form-control color-text-input"
                                            placeholder="#ffffff"
                                        />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>Step Title Font Size</label>
                                    <input
                                        type="text"
                                        v-model="formData.step_title_font_size"
                                        placeholder="1.3rem"
                                        class="form-control"
                                    />
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group">
                                    <label>Step Description Color</label>
                                    <div class="color-input-group">
                                        <input
                                            type="color"
                                            v-model="
                                                formData.step_description_color
                                            "
                                            class="form-control color-input"
                                        />
                                        <input
                                            type="text"
                                            v-model="
                                                formData.step_description_color
                                            "
                                            class="form-control color-text-input"
                                            placeholder="#ffffff"
                                        />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>Step Description Opacity</label>
                                    <input
                                        type="text"
                                        v-model="
                                            formData.step_description_opacity
                                        "
                                        placeholder="0.9"
                                        class="form-control"
                                    />
                                </div>
                            </div>
                        </div>

                        <!-- Layout -->
                        <div class="form-section">
                            <h4><i class="fas fa-th-large"></i> Layout</h4>
                            <div class="form-row">
                                <div class="form-group">
                                    <label>Grid Min Width</label>
                                    <input
                                        type="text"
                                        v-model="formData.grid_min_width"
                                        placeholder="250px"
                                        class="form-control"
                                    />
                                </div>
                                <div class="form-group">
                                    <label>Step Gap</label>
                                    <input
                                        type="text"
                                        v-model="formData.step_gap"
                                        placeholder="30px"
                                        class="form-control"
                                    />
                                </div>
                            </div>
                        </div>

                        <!-- Steps Section -->
                        <div class="form-section">
                            <div class="steps-header">
                                <h4><i class="fas fa-tasks"></i> Steps</h4>
                                <button
                                    type="button"
                                    @click="addStep"
                                    class="btn btn-secondary btn-sm"
                                >
                                    <i class="fas fa-plus"></i> Add Step
                                </button>
                            </div>

                            <div class="steps-container">
                                <div
                                    v-for="(step, index) in formData.steps"
                                    :key="index"
                                    class="step-item"
                                >
                                    <div class="step-header">
                                        <h5>
                                            <i class="fas fa-step-forward"></i>
                                            Step {{ index + 1 }}
                                        </h5>
                                        <button
                                            type="button"
                                            @click="removeStep(index)"
                                            class="btn btn-danger btn-sm"
                                            :disabled="
                                                formData.steps.length <= 1
                                            "
                                        >
                                            <i class="fas fa-trash"></i> Remove
                                        </button>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-group">
                                            <label>Step Number</label>
                                            <input
                                                type="number"
                                                v-model="step.step_number"
                                                min="1"
                                                class="form-control"
                                                required
                                            />
                                        </div>
                                        <div class="form-group flex-2">
                                            <label>Title</label>
                                            <input
                                                type="text"
                                                v-model="step.title"
                                                placeholder="Step Title"
                                                class="form-control"
                                                required
                                            />
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-group full-width">
                                            <label>Description</label>
                                            <textarea
                                                v-model="step.description"
                                                placeholder="Step Description"
                                                rows="3"
                                                class="form-control"
                                                required
                                            ></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div
                                v-if="formData.steps.length === 0"
                                class="no-steps"
                            >
                                <p>
                                    No steps added yet. Click "Add Step" to
                                    create your first step.
                                </p>
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
                                    isSaving || formData.steps.length === 0
                                "
                            >
                                <i class="fas fa-save"></i>
                                {{
                                    isSaving
                                        ? "Saving..."
                                        : editingProcess
                                        ? "Update Process"
                                        : "Create Process"
                                }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Processes List -->
        <div class="processes-list">
            <div class="list-header">
                <h3>
                    Admission Processes
                    <span class="count">({{ processes.length }})</span>
                </h3>
                <div class="list-actions">
                    <button
                        v-if="processes.length > 0"
                        class="btn btn-outline-primary btn-sm"
                        @click="refreshProcesses"
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

            <div class="processes-container" v-if="processes.length > 0">
                <div
                    v-for="process in processes"
                    :key="process.id"
                    class="process-item"
                    :class="{ 'is-active': process.is_active }"
                >
                    <div class="process-card">
                        <div class="card-header">
                            <div class="process-status">
                                <span
                                    class="status-indicator"
                                    :class="{ active: process.is_active }"
                                ></span>
                                <span class="status-text">
                                    {{
                                        process.is_active
                                            ? "Active"
                                            : "Inactive"
                                    }}
                                </span>
                            </div>
                            <div class="card-actions">
                                <button
                                    class="action-btn toggle-btn"
                                    @click="toggleProcess(process)"
                                    :class="{ active: process.is_active }"
                                    :title="
                                        process.is_active
                                            ? 'Deactivate'
                                            : 'Activate'
                                    "
                                    :disabled="isLoading"
                                >
                                    {{ process.is_active ? "Hide" : "Show" }}
                                </button>
                                <button
                                    class="action-btn edit-btn"
                                    @click="editProcess(process)"
                                    title="Edit"
                                    :disabled="isLoading"
                                >
                                    Edit
                                </button>
                                <button
                                    class="action-btn delete-btn"
                                    @click="deleteProcess(process.id)"
                                    :disabled="process.is_active || isLoading"
                                    title="Delete"
                                >
                                    Delete
                                </button>
                            </div>
                        </div>

                        <div class="card-content">
                            <div
                                class="process-preview"
                                :style="getProcessPreviewStyle(process)"
                            >
                                <div class="preview-content">
                                    <h3 :style="getPreviewTitleStyle(process)">
                                        {{ process.title }}
                                    </h3>
                                    <p
                                        :style="
                                            getPreviewDescriptionStyle(process)
                                        "
                                    >
                                        {{
                                            process.description.substring(
                                                0,
                                                120
                                            )
                                        }}...
                                    </p>
                                    <div
                                        class="preview-steps"
                                        v-if="
                                            process.steps &&
                                            process.steps.length
                                        "
                                    >
                                        <div
                                            v-for="step in process.steps.slice(
                                                0,
                                                3
                                            )"
                                            :key="step.id"
                                            class="preview-step"
                                        >
                                            <div
                                                class="preview-step-number"
                                                :style="
                                                    getPreviewStepNumberStyle(
                                                        process
                                                    )
                                                "
                                            >
                                                {{ step.step_number }}
                                            </div>
                                            <div class="preview-step-content">
                                                <h4
                                                    :style="
                                                        getPreviewStepTitleStyle(
                                                            process
                                                        )
                                                    "
                                                >
                                                    {{ step.title }}
                                                </h4>
                                            </div>
                                        </div>
                                        <span
                                            v-if="process.steps.length > 3"
                                            class="more-steps"
                                        >
                                            +{{ process.steps.length - 3 }} more
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card-footer">
                            <div class="process-meta">
                                <small class="update-time">
                                    <i class="fas fa-clock"></i>
                                    Last updated:
                                    {{ formatDate(process.updated_at) }}
                                </small>
                                <small class="step-count">
                                    <i class="fas fa-list"></i>
                                    {{
                                        process.steps ? process.steps.length : 0
                                    }}
                                    steps
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Empty State -->
            <div v-else class="empty-state">
                <div class="empty-icon">
                    <i class="fas fa-clipboard-list"></i>
                </div>
                <h4>No admission processes yet</h4>
                <p>
                    Create your first admission process to guide your visitors
                    through the enrollment journey
                </p>
                <button
                    class="btn btn-primary btn-lg"
                    @click="showCreateForm = true"
                    :disabled="isLoading"
                >
                    <i class="fas fa-plus"></i> Create Your First Process
                </button>
            </div>

            <!-- Loading State -->
            <div
                v-if="isLoading && processes.length === 0"
                class="loading-state"
            >
                <div class="loading-spinner">
                    <i class="fas fa-spinner fa-spin"></i>
                </div>
                <p>Loading admission processes...</p>
            </div>
        </div>
    </div>
</template>

<script>
import axios from "axios";

export default {
    name: "AdmissionProcessAdmin",
    data() {
        return {
            processes: [],
            showCreateForm: false,
            editingProcess: null,
            isLoading: false,
            isSaving: false,
            toasts: [],
            toastIdCounter: 0,
            formData: {
                title: "",
                description: "",
                background_color: "#102e4a",
                text_color: "#ffffff",
                padding_top: "80px",
                padding_bottom: "80px",
                text_align: "center",
                title_color: "#ffffff",
                title_font_size: "2.5rem",
                title_font_weight: "700",
                description_color: "#ffffff",
                description_font_size: "1.1rem",
                description_opacity: "0.9",
                step_number_bg_color: "#ff7101",
                step_number_text_color: "#ffffff",
                step_title_color: "#ffffff",
                step_title_font_size: "1.3rem",
                step_description_color: "#ffffff",
                step_description_opacity: "0.9",
                grid_min_width: "250px",
                step_gap: "30px",
                steps: [],
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

        async fetchProcesses() {
            this.isLoading = true;
            try {
                const response = await axios.get("/admin/admission-processes");
                this.processes = response.data;

                if (this.processes.length > 0) {
                    this.showSuccess(
                        "Processes Loaded",
                        `Successfully loaded ${
                            response.data.length
                        } admission process${
                            response.data.length === 1 ? "" : "es"
                        }`
                    );
                }
            } catch (error) {
                console.error("Error fetching processes:", error);
                this.showError(
                    "Loading Failed",
                    "Failed to load admission processes from server"
                );
            } finally {
                this.isLoading = false;
            }
        },

        async refreshProcesses() {
            await this.fetchProcesses();
        },

        resetForm() {
            this.formData = {
                title: "",
                description: "",
                background_color: "#102e4a",
                text_color: "#ffffff",
                padding_top: "80px",
                padding_bottom: "80px",
                text_align: "center",
                title_color: "#ffffff",
                title_font_size: "2.5rem",
                title_font_weight: "700",
                description_color: "#ffffff",
                description_font_size: "1.1rem",
                description_opacity: "0.9",
                step_number_bg_color: "#ff7101",
                step_number_text_color: "#ffffff",
                step_title_color: "#ffffff",
                step_title_font_size: "1.3rem",
                step_description_color: "#ffffff",
                step_description_opacity: "0.9",
                grid_min_width: "250px",
                step_gap: "30px",
                steps: [
                    {
                        title: "Submit Application",
                        description:
                            "Complete our online application form with required documents and photographs.",
                        step_number: 1,
                    },
                    {
                        title: "Assessment & Interview",
                        description:
                            "Attend the entrance assessment and family interview with our admissions team.",
                        step_number: 2,
                    },
                    {
                        title: "Acceptance & Enrollment",
                        description:
                            "Receive your acceptance letter and complete the enrollment process with fee payment.",
                        step_number: 3,
                    },
                    {
                        title: "Welcome Orientation",
                        description:
                            "Join our new family orientation program and begin your journey with us.",
                        step_number: 4,
                    },
                ],
            };
        },

        closeForm() {
            this.showCreateForm = false;
            this.editingProcess = null;
            this.resetForm();
        },

        addStep() {
            this.formData.steps.push({
                title: "",
                description: "",
                step_number: this.formData.steps.length + 1,
            });
        },

        removeStep(index) {
            if (this.formData.steps.length <= 1) {
                this.showWarning(
                    "Cannot Remove",
                    "At least one step is required"
                );
                return;
            }

            this.formData.steps.splice(index, 1);
            // Renumber remaining steps
            this.formData.steps.forEach((step, i) => {
                step.step_number = i + 1;
            });
        },

        async saveProcess() {
            if (this.formData.steps.length === 0) {
                this.showWarning(
                    "Validation Error",
                    "At least one step is required"
                );
                return;
            }

            this.isSaving = true;
            try {
                const payload = { ...this.formData };

                let response;
                if (this.editingProcess) {
                    response = await axios.put(
                        `/admin/admission-processes/${this.editingProcess.id}`,
                        payload
                    );
                } else {
                    response = await axios.post(
                        "/admin/admission-processes",
                        payload
                    );
                }

                await this.fetchProcesses();
                this.closeForm();
                this.showSuccess(
                    "Process Saved",
                    this.editingProcess
                        ? "Admission process updated successfully!"
                        : "Admission process created successfully!"
                );
            } catch (error) {
                console.error("Error saving process:", error);
                let errorMessage =
                    "Error saving admission process. Please try again.";

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

        editProcess(process) {
            this.editingProcess = process;
            this.formData = {
                title: process.title || "",
                description: process.description || "",
                background_color: process.background_color || "#102e4a",
                text_color: process.text_color || "#ffffff",
                padding_top: process.padding_top || "80px",
                padding_bottom: process.padding_bottom || "80px",
                text_align: process.text_align || "center",
                title_color: process.title_color || "#ffffff",
                title_font_size: process.title_font_size || "2.5rem",
                title_font_weight: process.title_font_weight || "700",
                description_color: process.description_color || "#ffffff",
                description_font_size:
                    process.description_font_size || "1.1rem",
                description_opacity: process.description_opacity || "0.9",
                step_number_bg_color: process.step_number_bg_color || "#ff7101",
                step_number_text_color:
                    process.step_number_text_color || "#ffffff",
                step_title_color: process.step_title_color || "#ffffff",
                step_title_font_size: process.step_title_font_size || "1.3rem",
                step_description_color:
                    process.step_description_color || "#ffffff",
                step_description_opacity:
                    process.step_description_opacity || "0.9",
                grid_min_width: process.grid_min_width || "250px",
                step_gap: process.step_gap || "30px",
                steps: process.steps
                    ? process.steps.map((step) => ({
                          title: step.title,
                          description: step.description,
                          step_number: step.step_number,
                      }))
                    : [],
            };
        },

        async toggleProcess(process) {
            try {
                if (!process.is_active) {
                    await axios.post(
                        `/admin/admission-processes/${process.id}/activate`
                    );
                    this.showSuccess(
                        "Process Activated",
                        `"${process.title}" is now active and all other processes have been deactivated`
                    );
                } else {
                    await axios.put(
                        `/admin/admission-processes/${process.id}`,
                        {
                            ...process,
                            is_active: false,
                        }
                    );
                    this.showSuccess(
                        "Process Deactivated",
                        `"${process.title}" has been hidden`
                    );
                }
                await this.fetchProcesses();
            } catch (error) {
                console.error("Error toggling process:", error);
                this.showError(
                    "Status Update Failed",
                    `Could not change status for "${process.title}"`
                );
            }
        },

        async deleteProcess(id) {
            const process = this.processes.find((p) => p.id === id);
            if (!process) return;

            if (process.is_active) {
                this.showWarning(
                    "Cannot Delete Active Process",
                    "Please activate another process first, then delete this one."
                );
                return;
            }

            if (
                confirm(
                    `Are you sure you want to delete "${process.title}"? This action cannot be undone.`
                )
            ) {
                try {
                    await axios.delete(`/admin/admission-processes/${id}`);
                    await this.fetchProcesses();
                    this.showSuccess(
                        "Process Deleted",
                        `"${process.title}" has been permanently removed`
                    );
                } catch (error) {
                    console.error("Error deleting process:", error);
                    this.showError(
                        "Delete Failed",
                        `Could not delete "${process.title}"`
                    );
                }
            }
        },

        getProcessPreviewStyle(process) {
            return {
                padding: "20px",
                borderRadius: "10px",
                minHeight: "200px",
                textAlign: process.text_align || "center",
                background: process.background_color || "#102e4a",
                color: process.text_color || "#ffffff",
            };
        },

        getPreviewTitleStyle(process) {
            return {
                fontSize: "1.4rem",
                color: process.title_color || "#ffffff",
                fontWeight: process.title_font_weight || "700",
                margin: "0 0 15px 0",
            };
        },

        getPreviewDescriptionStyle(process) {
            return {
                fontSize: "0.95rem",
                color: process.description_color || "#ffffff",
                margin: "0 0 20px 0",
                opacity: process.description_opacity || "0.9",
            };
        },

        getPreviewStepNumberStyle(process) {
            return {
                backgroundColor: process.step_number_bg_color || "#ff7101",
                color: process.step_number_text_color || "#ffffff",
            };
        },

        getPreviewStepTitleStyle(process) {
            return {
                color: process.step_title_color || "#ffffff",
                fontSize: "0.9rem",
                fontWeight: "600",
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
        await this.fetchProcesses();
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
.admin-admission-process {
    padding: 24px;
    background: #f8f9fa;
    min-height: 100vh;
    font-family: "Inter", -apple-system, BlinkMacSystemFont, sans-serif;
}

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
    padding: 20px;
}

.modal-content {
    background: white;
    border-radius: 12px;
    width: 100%;
    max-width: 900px;
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
}

/* Form Styles */
.process-form {
    padding: 20px;
}

.form-section {
    margin-bottom: 30px;
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

textarea.form-control {
    resize: vertical;
    min-height: 80px;
    font-family: inherit;
}

/* Steps Section */
.steps-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
}

.steps-container {
    display: flex;
    flex-direction: column;
    gap: 15px;
}

.step-item {
    background: white;
    padding: 20px;
    border-radius: 8px;
    border: 1px solid #e5e7eb;
    transition: all 0.2s;
}

.step-item:hover {
    border-color: #ff7101;
    box-shadow: 0 2px 8px rgba(255, 113, 1, 0.1);
}

.step-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 15px;
    padding-bottom: 10px;
    border-bottom: 1px solid #e5e7eb;
}

.step-header h5 {
    margin: 0;
    color: #1f2937;
    font-size: 14px;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 8px;
}

.step-header h5 i {
    color: #ff7101;
}

.no-steps {
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
    margin-top: 30px;
    padding-top: 20px;
    border-top: 1px solid #e5e7eb;
}

/* Process List Styles */
.processes-list {
    margin-top: 30px;
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

.list-actions {
    display: flex;
    gap: 10px;
}

.processes-container {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(400px, 1fr));
    gap: 20px;
}

/* Process Cards */
.process-card {
    background: white;
    border: 2px solid #e5e7eb;
    border-radius: 12px;
    overflow: hidden;
    transition: all 0.2s;
    box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
}

.process-item.is-active .process-card {
    border-color: #10b981;
    box-shadow: 0 4px 12px rgba(16, 185, 129, 0.15);
}

.process-card:hover {
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    transform: translateY(-2px);
}

.card-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 16px;
    background: #f9fafb;
    border-bottom: 1px solid #e5e7eb;
}

.process-status {
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

/* Card Content */
.card-content {
    padding: 20px;
}

.preview-steps {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
    align-items: center;
}

.preview-step {
    display: flex;
    align-items: center;
    gap: 8px;
}

.preview-step-number {
    width: 24px;
    height: 24px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 12px;
    font-weight: 600;
}

.preview-step-content h4 {
    margin: 0;
    font-size: 0.8rem;
    font-weight: 500;
}

.more-steps {
    color: #6b7280;
    font-size: 0.8rem;
    font-style: italic;
}

.card-footer {
    padding: 12px 16px;
    background: #f9fafb;
    border-top: 1px solid #e5e7eb;
}

.process-meta {
    display: flex;
    flex-direction: column;
    gap: 4px;
}

.update-time,
.step-count {
    font-size: 12px;
    color: #6b7280;
    display: flex;
    align-items: center;
    gap: 4px;
}

.update-time i,
.step-count i {
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

.btn-lg {
    padding: 14px 24px;
    font-size: 16px;
}

.btn:disabled {
    opacity: 0.6;
    cursor: not-allowed;
    transform: none !important;
}

/* Responsive Design */
@media (max-width: 1024px) {
    .processes-container {
        grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
    }

    .modal-content {
        max-width: 800px;
    }
}

@media (max-width: 768px) {
    .admin-admission-process {
        padding: 16px;
    }

    .admin-header {
        flex-direction: column;
        gap: 16px;
        padding: 20px;
        text-align: center;
    }

    .processes-container {
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

    .preview-steps {
        flex-direction: column;
        gap: 8px;
        align-items: flex-start;
    }

    .preview-step {
        width: 100%;
        justify-content: flex-start;
    }

    .steps-header {
        flex-direction: column;
        gap: 10px;
        align-items: stretch;
    }

    .toast-container {
        left: 10px;
        right: 10px;
        max-width: none;
    }
}

@media (max-width: 480px) {
    .list-header {
        flex-direction: column;
        gap: 12px;
        text-align: center;
    }

    .process-meta {
        align-items: center;
    }

    .btn span {
        display: none;
    }

    .btn i {
        margin: 0;
    }

    .step-header {
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
}
</style>
