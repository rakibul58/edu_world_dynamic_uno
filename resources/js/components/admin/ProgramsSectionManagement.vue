<template>
    <div class="programs-admin">
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
                <h2>Programs Management</h2>
                <p>
                    Manage your educational programs and their display settings
                </p>
            </div>
            <button
                class="btn btn-primary"
                @click="addNewProgram"
                :disabled="isAddingProgram"
            >
                {{ isAddingProgram ? "Adding..." : "+ Add New Program" }}
            </button>
        </div>

        <!-- Global Settings Section -->
        <div class="settings-card">
            <div class="card-header">
                <h3>Global Settings</h3>
                <button
                    class="btn btn-secondary"
                    @click="updateGlobalSettings"
                    :disabled="!settingsChanged"
                >
                    Update Settings
                </button>
            </div>

            <div class="settings-grid">
                <!-- Section Background -->
                <div class="form-group">
                    <label>Background Color</label>
                    <div class="color-input-group">
                        <input
                            type="color"
                            v-model="globalSettings.background"
                            @input="settingsChanged = true"
                        />
                        <span class="color-value">{{
                            globalSettings.background
                        }}</span>
                    </div>
                </div>

                <div class="form-group">
                    <label>Padding Top</label>
                    <div class="input-with-unit">
                        <input
                            type="number"
                            v-model.number="globalSettings.paddingTop"
                            @input="settingsChanged = true"
                        />
                        <span class="unit">px</span>
                    </div>
                </div>

                <div class="form-group">
                    <label>Padding Bottom</label>
                    <div class="input-with-unit">
                        <input
                            type="number"
                            v-model.number="globalSettings.paddingBottom"
                            @input="settingsChanged = true"
                        />
                        <span class="unit">px</span>
                    </div>
                </div>

                <!-- Wrap Container Settings -->
                <div class="form-group">
                    <label>Wrap Max Width</label>
                    <div class="input-with-unit">
                        <input
                            type="number"
                            v-model.number="globalSettings.wrapMaxWidth"
                            @input="settingsChanged = true"
                        />
                        <span class="unit">px</span>
                    </div>
                </div>

                <div class="form-group">
                    <label>Wrap Padding</label>
                    <input
                        type="text"
                        v-model="globalSettings.wrapPadding"
                        @input="settingsChanged = true"
                        placeholder="0 20px"
                    />
                </div>

                <div class="form-group">
                    <label>Wrap Margin Top</label>
                    <div class="input-with-unit">
                        <input
                            type="number"
                            v-model.number="globalSettings.wrapMarginTop"
                            @input="settingsChanged = true"
                        />
                        <span class="unit">px</span>
                    </div>
                </div>

                <div class="form-group">
                    <label>Wrap Margin Top (Mobile)</label>
                    <div class="input-with-unit">
                        <input
                            type="number"
                            v-model.number="globalSettings.wrapMarginTopMobile"
                            @input="settingsChanged = true"
                        />
                        <span class="unit">px</span>
                    </div>
                </div>

                <!-- Card Settings -->
                <div class="form-group">
                    <label>Card Background</label>
                    <textarea
                        v-model="globalSettings.cardBackground"
                        @input="settingsChanged = true"
                        placeholder="linear-gradient(180deg, rgba(16, 46, 74, 0.35), rgba(3, 8, 17, 0.25))"
                    ></textarea>
                </div>

                <div class="form-group">
                    <label>Border Radius</label>
                    <div class="input-with-unit">
                        <input
                            type="number"
                            v-model.number="globalSettings.borderRadius"
                            @input="settingsChanged = true"
                        />
                        <span class="unit">px</span>
                    </div>
                </div>

                <div class="form-group">
                    <label>Border Width</label>
                    <div class="input-with-unit">
                        <input
                            type="number"
                            v-model.number="globalSettings.borderWidth"
                            @input="settingsChanged = true"
                        />
                        <span class="unit">px</span>
                    </div>
                </div>

                <div class="form-group">
                    <label>Backdrop Filter</label>
                    <input
                        type="text"
                        v-model="globalSettings.backdropFilter"
                        @input="settingsChanged = true"
                        placeholder="blur(8px)"
                    />
                </div>

                <div class="form-group">
                    <label>Min Height</label>
                    <div class="input-with-unit">
                        <input
                            type="number"
                            v-model.number="globalSettings.minHeight"
                            @input="settingsChanged = true"
                        />
                        <span class="unit">px</span>
                    </div>
                </div>

                <div class="form-group">
                    <label>Default Button Text</label>
                    <input
                        type="text"
                        v-model="globalSettings.defaultButtonText"
                        @input="settingsChanged = true"
                        placeholder="Learn more"
                    />
                </div>

                <div class="form-group">
                    <label>Button Border Radius</label>
                    <div class="input-with-unit">
                        <input
                            type="number"
                            v-model.number="globalSettings.buttonBorderRadius"
                            @input="settingsChanged = true"
                        />
                        <span class="unit">px</span>
                    </div>
                </div>

                <div class="form-group">
                    <label>Programs Per Row</label>
                    <select
                        v-model="globalSettings.programs_per_row"
                        @change="settingsChanged = true"
                    >
                        <option value="2">2 Programs</option>
                        <option value="3">3 Programs</option>
                        <option value="4">4 Programs</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Programs List -->
        <div class="programs-list">
            <div class="list-header">
                <h3>Programs ({{ programs.length }})</h3>
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

            <div class="programs-container" :class="'view-' + viewMode">
                <div
                    v-for="(program, index) in programs"
                    :key="program.id"
                    class="program-item"
                    :class="{
                        'is-active': program.is_active,
                        'is-dragging': draggedIndex === index,
                        'drag-over':
                            dragOverIndex === index && draggedIndex !== index,
                    }"
                    draggable="true"
                    @dragstart="dragStart(index, $event)"
                    @dragover.prevent="dragOver(index, $event)"
                    @dragenter="dragEnter(index, $event)"
                    @dragleave="dragLeave($event)"
                    @drop="drop(index, $event)"
                    @dragend="dragEnd"
                >
                    <div class="program-card">
                        <!-- Drag Handle -->
                        <div class="drag-handle">
                            <div class="drag-dots">
                                <div class="dot"></div>
                                <div class="dot"></div>
                                <div class="dot"></div>
                                <div class="dot"></div>
                                <div class="dot"></div>
                                <div class="dot"></div>
                            </div>
                            <span class="drag-text">Drag to reorder</span>
                        </div>

                        <div class="card-header">
                            <div class="program-status">
                                <span
                                    class="status-indicator"
                                    :class="{ active: program.is_active }"
                                ></span>
                                <span class="status-text">
                                    {{
                                        program.is_active
                                            ? "Active"
                                            : "Inactive"
                                    }}
                                </span>
                            </div>
                            <div class="card-actions">
                                <button
                                    class="action-btn toggle-btn"
                                    @click="toggleProgram(program)"
                                    :class="{ active: program.is_active }"
                                    :title="
                                        program.is_active
                                            ? 'Deactivate'
                                            : 'Activate'
                                    "
                                >
                                    {{ program.is_active ? "Hide" : "Show" }}
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
                                    :disabled="index === programs.length - 1"
                                    title="Move Down"
                                >
                                    ↓
                                </button>
                                <button
                                    class="action-btn delete-btn"
                                    @click="removeProgram(index)"
                                    title="Delete"
                                >
                                    Delete
                                </button>
                            </div>
                        </div>

                        <div class="card-content">
                            <div
                                class="program-preview"
                                :style="{
                                    borderColor: program.border_color,
                                    background: globalSettings.cardBackground,
                                    borderRadius:
                                        globalSettings.borderRadius + 'px',
                                    minHeight: globalSettings.minHeight + 'px',
                                }"
                            >
                                <h4>{{ program.title || "Program Title" }}</h4>
                                <p>
                                    {{
                                        program.description
                                            ? truncateText(
                                                  program.description,
                                                  80
                                              )
                                            : "Program description will appear here..."
                                    }}
                                </p>
                                <div
                                    class="btn-preview"
                                    :style="{
                                        background: program.btn_color,
                                        border: `2px solid ${program.btn_border_color}`,
                                        color: '#fff',
                                    }"
                                >
                                    Learn more
                                </div>
                            </div>

                            <div class="program-form">
                                <div class="form-row">
                                    <div class="form-group">
                                        <label>Title</label>
                                        <input
                                            type="text"
                                            v-model="program.title"
                                            @blur="updateProgram(program)"
                                            placeholder="Program Title"
                                        />
                                    </div>
                                    <div class="form-group">
                                        <label>CSS Class</label>
                                        <input
                                            type="text"
                                            v-model="program.class_name"
                                            @blur="updateProgram(program)"
                                            placeholder="e.g., islamic-studies"
                                        />
                                    </div>
                                </div>

                                <div style="margintop: 10px" class="form-group">
                                    <label>Description</label>
                                    <textarea
                                        v-model="program.description"
                                        @blur="updateProgram(program)"
                                        placeholder="Program description..."
                                    ></textarea>
                                </div>

                                <div style="margintop: 10px" class="form-group">
                                    <label>Button Text</label>
                                    <input
                                        type="text"
                                        v-model="program.button_text"
                                        @blur="updateProgram(program)"
                                        placeholder="Leave empty to use default"
                                    />
                                </div>

                                <div style="margintop: 10px" class="form-row">
                                    <div class="form-group">
                                        <label>Border Color</label>
                                        <div class="color-input-group">
                                            <input
                                                type="color"
                                                v-model="program.border_color"
                                                @change="updateProgram(program)"
                                            />
                                            <span class="color-value">{{
                                                program.border_color
                                            }}</span>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label>Button Color</label>
                                        <div class="color-input-group">
                                            <input
                                                type="color"
                                                v-model="program.btn_color"
                                                @change="updateProgram(program)"
                                            />
                                            <span class="color-value">{{
                                                program.btn_color
                                            }}</span>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label>Button Border Color</label>
                                        <div class="color-input-group">
                                            <input
                                                type="color"
                                                v-model="
                                                    program.btn_border_color
                                                "
                                                @change="updateProgram(program)"
                                            />
                                            <span class="color-value">{{
                                                program.btn_border_color
                                            }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card-footer">
                            <small class="update-time">
                                Last updated:
                                {{ formatDate(program.updated_at) }}
                            </small>
                        </div>
                    </div>
                </div>
            </div>

            <div v-if="programs.length === 0" class="empty-state">
                <div class="empty-icon">📚</div>
                <h4>No programs yet</h4>
                <p>Get started by adding your first program</p>
                <button
                    class="btn btn-primary"
                    @click="addNewProgram"
                    :disabled="isAddingProgram"
                >
                    {{
                        isAddingProgram
                            ? "Adding..."
                            : "+ Add Your First Program"
                    }}
                </button>
            </div>
        </div>
    </div>
</template>

<script>
import axios from "axios";

export default {
    name: "ProgramsSectionManagement",
    data() {
        return {
            programs: [],
            viewMode: "grid",
            settingsChanged: false,
            isLoading: false,
            isAddingProgram: false,
            toasts: [],
            toastIdCounter: 0,
            globalSettings: {
                background: "#102e4a",
                paddingTop: 60,
                paddingBottom: 80,
                wrapMaxWidth: 1240,
                wrapPadding: "0 20px",
                wrapMarginTop: -90,
                wrapMarginTopMobile: -60,
                cardBackground:
                    "linear-gradient(180deg, rgba(16, 46, 74, 0.35), rgba(3, 8, 17, 0.25))",
                borderRadius: 15,
                borderWidth: 10,
                backdropFilter: "blur(8px)",
                minHeight: 280,
                programs_per_row: "3",
                defaultButtonText: "Learn more",
                buttonBorderRadius: 5,
            },
            draggedIndex: null,
            dragOverIndex: null,
        };
    },
    mounted() {
        this.fetchPrograms();
        this.fetchSettings();
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

        async fetchPrograms() {
            try {
                this.isLoading = true;
                const response = await axios.get("/admin/programs/admin");
                this.programs = response.data;
                this.showSuccess(
                    "Programs Loaded",
                    `Successfully loaded ${response.data.length} programs`
                );
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
        async fetchSettings() {
            try {
                const response = await axios.get("/programs/settings");
                this.globalSettings = {
                    ...this.globalSettings,
                    ...response.data,
                };
                this.showInfo(
                    "Settings Loaded",
                    "Global settings have been loaded"
                );
            } catch (error) {
                console.error("Error fetching settings:", error);
                this.showWarning(
                    "Settings Warning",
                    "Using default settings - could not load from server"
                );
            }
        },
        async addNewProgram() {
            if (this.isAddingProgram) return; // Prevent double clicks

            this.isAddingProgram = true;

            try {
                const newProgram = {
                    title: "New Program",
                    description: "Enter program description here...",
                    class_name: "new-program",
                    border_color: "#ff7101",
                    btn_color: "#ff7101",
                    btn_border_color: "#ff7101",
                    order: this.programs.length,
                    is_active: true,
                };

                console.log("Adding new program:", newProgram);

                const response = await axios.post(
                    "/admin/programs",
                    newProgram
                );

                console.log("Response from server:", response.data);

                // Add the new program to the local array
                const addedProgram = response.data;
                // Ensure it has all required fields
                addedProgram.updated_at =
                    addedProgram.updated_at || new Date().toISOString();

                this.programs.push(addedProgram);

                this.showSuccess(
                    "Program Added",
                    `"${addedProgram.title}" has been created successfully`
                );
            } catch (error) {
                console.error("Error adding program:", error);
                console.error("Error details:", error.response?.data);

                let errorMessage = "Could not create new program";
                if (error.response?.data?.message) {
                    errorMessage = error.response.data.message;
                } else if (error.message) {
                    errorMessage = error.message;
                }

                this.showError("Failed to Add Program", errorMessage);
            } finally {
                this.isAddingProgram = false;
            }
        },
        async updateProgram(program) {
            try {
                await axios.put(`/admin/programs/${program.id}`, program);
                this.showSuccess(
                    "Program Updated",
                    `"${program.title}" has been saved`
                );
            } catch (error) {
                console.error("Error updating program:", error);
                this.showError(
                    "Update Failed",
                    `Could not save changes to "${program.title}"`
                );
            }
        },
        async removeProgram(index) {
            const program = this.programs[index];
            if (
                confirm(
                    `Are you sure you want to delete "${program.title}"?\n\nThis action cannot be undone.`
                )
            ) {
                try {
                    await axios.delete(`/admin/programs/${program.id}`);
                    this.programs.splice(index, 1);
                    this.updateAllOrders();
                    this.showSuccess(
                        "Program Deleted",
                        `"${program.title}" has been permanently removed`
                    );
                } catch (error) {
                    console.error("Error deleting program:", error);
                    this.showError(
                        "Delete Failed",
                        `Could not delete "${program.title}"`
                    );
                }
            }
        },
        async toggleProgram(program) {
            try {
                const oldStatus = program.is_active;
                program.is_active = !program.is_active;
                await this.updateProgram(program);

                const statusText = program.is_active
                    ? "activated"
                    : "deactivated";
                this.showSuccess(
                    "Status Updated",
                    `"${program.title}" has been ${statusText}`
                );
            } catch (error) {
                program.is_active = !program.is_active; // Revert on error
                this.showError(
                    "Status Update Failed",
                    `Could not change status for "${program.title}"`
                );
            }
        },
        async moveUp(index) {
            if (index > 0) {
                const temp = this.programs[index];
                this.programs.splice(index, 1);
                this.programs.splice(index - 1, 0, temp);
                await this.updateAllOrders();
                this.showInfo("Order Updated", `"${temp.title}" moved up`);
            }
        },
        async moveDown(index) {
            if (index < this.programs.length - 1) {
                const temp = this.programs[index];
                this.programs.splice(index, 1);
                this.programs.splice(index + 1, 0, temp);
                await this.updateAllOrders();
                this.showInfo("Order Updated", `"${temp.title}" moved down`);
            }
        },
        async updateAllOrders() {
            try {
                const updates = this.programs.map((program, index) => {
                    program.order = index;
                    return axios.put(`/admin/programs/${program.id}`, program);
                });

                await Promise.all(updates);
            } catch (error) {
                console.error("Error updating order:", error);
                this.showError(
                    "Order Update Failed",
                    "Could not save new program order"
                );
            }
        },
        async updateGlobalSettings() {
            try {
                await axios.post(
                    "/admin/programs/settings",
                    this.globalSettings
                );
                this.settingsChanged = false;
                this.showSuccess(
                    "Settings Saved",
                    "Global display settings have been updated"
                );
            } catch (error) {
                console.error("Error updating settings:", error);
                this.showError(
                    "Settings Update Failed",
                    "Could not save global settings"
                );
            }
        },
        truncateText(text, length) {
            return text.length > length
                ? text.substring(0, length) + "..."
                : text;
        },
        formatDate(dateString) {
            return new Date(dateString).toLocaleDateString();
        },
        // Drag and Drop methods
        dragStart(index, event) {
            this.draggedIndex = index;
            event.dataTransfer.effectAllowed = "move";
            event.dataTransfer.setData("text/plain", index);
        },
        dragOver(index, event) {
            event.preventDefault();
            if (this.draggedIndex !== null && this.draggedIndex !== index) {
                this.dragOverIndex = index;
            }
        },
        dragEnter(index, event) {
            event.preventDefault();
        },
        dragLeave(event) {
            event.preventDefault();
            // Only reset if leaving the entire card
            if (!event.currentTarget.contains(event.relatedTarget)) {
                this.dragOverIndex = null;
            }
        },
        async drop(index, event) {
            event.preventDefault();

            if (this.draggedIndex !== null && this.draggedIndex !== index) {
                const movedProgram = this.programs[this.draggedIndex];
                const movedItem = this.programs.splice(this.draggedIndex, 1)[0];
                this.programs.splice(index, 0, movedItem);
                await this.updateAllOrders();
                this.showInfo(
                    "Program Reordered",
                    `"${movedProgram.title}" has been moved`
                );
            }

            this.dragOverIndex = null;
            this.draggedIndex = null;
        },
        dragEnd() {
            this.draggedIndex = null;
            this.dragOverIndex = null;
        },
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

.programs-admin {
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

/* Settings Card */
.settings-card {
    background: white;
    padding: 24px;
    border-radius: 12px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    margin-bottom: 24px;
}

.card-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
    padding-bottom: 16px;
    border-bottom: 1px solid #e5e7eb;
}

.card-header h3 {
    margin: 0;
    color: #1f2937;
    font-size: 18px;
    font-weight: 600;
}

.settings-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 20px;
}

/* Form Styles */
.form-group {
    display: flex;
    flex-direction: column;
    gap: 6px;
}

.form-group label {
    font-weight: 500;
    color: #374151;
    font-size: 14px;
}

.form-group input,
.form-group textarea,
.form-group select {
    padding: 10px 12px;
    border: 1px solid #d1d5db;
    border-radius: 6px;
    font-size: 14px;
    transition: all 0.2s;
}

.form-group input:focus,
.form-group textarea:focus,
.form-group select:focus {
    outline: none;
    border-color: #ff7101;
    box-shadow: 0 0 0 3px rgba(255, 113, 1, 0.1);
}

.form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 16px;
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

.unit {
    position: absolute;
    right: 12px;
    top: 50%;
    transform: translateY(-50%);
    color: #6b7280;
    font-size: 14px;
}

/* Programs List */
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

/* Program Items */
.programs-container {
    display: grid;
    gap: 20px;
}

.programs-container.view-grid {
    grid-template-columns: repeat(auto-fill, minmax(400px, 1fr));
}

.programs-container.view-list {
    grid-template-columns: 1fr;
}

.program-item {
    transition: all 0.3s ease;
    position: relative;
}

.program-item.is-dragging {
    opacity: 0.6;
    transform: rotate(3deg) scale(1.02);
    z-index: 1000;
}

.program-item.drag-over {
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(255, 113, 1, 0.2);
}

.program-card {
    background: white;
    border: 2px solid #e5e7eb;
    border-radius: 12px;
    overflow: hidden;
    transition: all 0.2s;
    box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
    position: relative;
}

.program-card:hover {
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    border-color: #ff7101;
}

/* Drag Handle */
.drag-handle {
    position: absolute;
    top: 8px;
    left: 8px;
    z-index: 10;
    display: flex;
    align-items: center;
    gap: 6px;
    padding: 4px 8px;
    background: rgba(255, 255, 255, 0.9);
    border: 1px solid #e5e7eb;
    border-radius: 6px;
    cursor: grab;
    font-size: 11px;
    color: #6b7280;
    backdrop-filter: blur(4px);
    opacity: 0;
    transition: all 0.2s;
}

.program-item:hover .drag-handle {
    opacity: 1;
}

.drag-handle:active {
    cursor: grabbing;
}

.drag-dots {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 2px;
}

.dot {
    width: 3px;
    height: 3px;
    background: #9ca3af;
    border-radius: 50%;
}

.drag-text {
    font-weight: 500;
}

/* Card Content */
.program-card .card-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 16px;
    background: #f9fafb;
    border-bottom: 1px solid #e5e7eb;
    margin: 0;
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

.program-preview {
    padding: 20px;
    margin-bottom: 20px;
    border: 10px solid;
    color: white;
    border-radius: 12px;
    transition: all 0.2s;
}

.program-preview h4 {
    margin: 0 0 12px 0;
    font-size: 18px;
    font-weight: 600;
}

.program-preview p {
    margin: 0 0 16px 0;
    font-size: 14px;
    line-height: 1.5;
    opacity: 0.9;
}

.btn-preview {
    display: inline-block;
    padding: 8px 20px;
    border-radius: 6px;
    font-weight: 500;
    font-size: 14px;
    transition: all 0.2s;
}

.program-form {
    margin-top: 20px;
}

.card-footer {
    padding: 12px 16px;
    background: #f9fafb;
    border-top: 1px solid #e5e7eb;
    text-align: center;
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

.btn:disabled {
    opacity: 0.6;
    cursor: not-allowed;
    transform: none !important;
}

.btn-sm {
    padding: 6px 12px;
    font-size: 13px;
}

/* Enhanced Drag & Drop Visual Feedback */
.program-item.is-dragging .program-card {
    border-color: #ff7101;
    background: #fff;
    box-shadow: 0 10px 30px rgba(255, 113, 1, 0.3);
}

.program-item.drag-over .program-card {
    border-color: #ff7101;
    border-style: dashed;
    background: rgba(255, 113, 1, 0.02);
}

.program-item.drag-over::before {
    content: "Drop here";
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    background: #ff7101;
    color: white;
    padding: 8px 16px;
    border-radius: 6px;
    font-size: 12px;
    font-weight: 600;
    z-index: 100;
    pointer-events: none;
}

/* Responsive Design */
@media (max-width: 1024px) {
    .programs-container.view-grid {
        grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
    }
}

@media (max-width: 768px) {
    .programs-admin {
        padding: 16px;
    }

    .admin-header {
        flex-direction: column;
        gap: 16px;
        padding: 20px;
    }

    .settings-grid {
        grid-template-columns: 1fr;
    }

    .programs-container.view-grid {
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

    .drag-handle {
        position: static;
        opacity: 1;
        margin-bottom: 8px;
        justify-content: center;
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
}
</style>
