<template>
    <div class="section-manager">
        <!-- Page Header -->
        <div class="page-header">
            <div class="header-content">
                <div class="header-left">
                    <h1 class="page-title">Page Section Manager</h1>
                    <p class="page-subtitle">
                        Control which sections are visible on your landing page
                    </p>
                </div>
                <div class="header-actions">
                    <button
                        @click="toggleAllSections(false)"
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
                            <path d="M18 6L6 18M6 6l12 12" />
                        </svg>
                        Hide All
                    </button>
                    <button
                        @click="toggleAllSections(true)"
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
                            <polyline points="9,11 12,14 22,4" />
                            <path
                                d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"
                            />
                        </svg>
                        Show All
                    </button>
                    <button
                        @click="refreshSections"
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
                            <polyline points="23,4 23,10 17,10" />
                            <path d="M20.49 15a9 9 0 1 1-2.12-9.36L23 10" />
                        </svg>
                        Refresh
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
                <polyline points="9,11 12,14 22,4" />
                <path
                    d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"
                />
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

        <!-- Loading State -->
        <div v-if="loading && sections.length === 0" class="loading-container">
            <div class="loading-spinner"></div>
            <p>Loading sections...</p>
        </div>

        <!-- Sections List -->
        <div v-else class="sections-container">
            <div class="sections-header">
                <div class="sections-info">
                    <h2 class="sections-title">Landing Page Sections</h2>
                    <p class="sections-count">
                        {{ visibleSectionsCount }} of
                        {{ sections.length }} sections visible
                    </p>
                </div>
                <div class="view-controls">
                    <button
                        @click="viewMode = 'list'"
                        class="view-btn"
                        :class="{ active: viewMode === 'list' }"
                    >
                        <svg
                            width="16"
                            height="16"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="2"
                        >
                            <line x1="8" y1="6" x2="21" y2="6" />
                            <line x1="8" y1="12" x2="21" y2="12" />
                            <line x1="8" y1="18" x2="21" y2="18" />
                            <line x1="3" y1="6" x2="3.01" y2="6" />
                            <line x1="3" y1="12" x2="3.01" y2="12" />
                            <line x1="3" y1="18" x2="3.01" y2="18" />
                        </svg>
                        List
                    </button>
                    <button
                        @click="viewMode = 'grid'"
                        class="view-btn"
                        :class="{ active: viewMode === 'grid' }"
                    >
                        <svg
                            width="16"
                            height="16"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="2"
                        >
                            <rect x="3" y="3" width="7" height="7" />
                            <rect x="14" y="3" width="7" height="7" />
                            <rect x="3" y="14" width="7" height="7" />
                            <rect x="14" y="14" width="7" height="7" />
                        </svg>
                        Grid
                    </button>
                </div>
            </div>

            <!-- List View -->
            <div
                v-if="viewMode === 'list'"
                class="sections-list"
                @dragover.prevent
                @dragenter.prevent
            >
                <div
                    v-for="(section, index) in sortedSections"
                    :key="section.id"
                    class="section-item"
                    :class="{
                        'section-hidden': !section.is_visible,
                        dragging: section.id === draggingId,
                        'drag-over': dragOverIndex === index,
                    }"
                    draggable="true"
                    @dragstart="startDrag($event, section.id, index)"
                    @dragover="dragOver(index)"
                    @dragenter="dragEnter($event, index)"
                    @dragleave="dragLeave"
                    @drop="drop(index)"
                    @dragend="dragEnd"
                >
                    <div class="section-drag-handle">
                        <svg
                            width="16"
                            height="16"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="2"
                        >
                            <line x1="3" y1="6" x2="21" y2="6" />
                            <line x1="3" y1="12" x2="21" y2="12" />
                            <line x1="3" y1="18" x2="21" y2="18" />
                        </svg>
                    </div>

                    <div class="section-content">
                        <div class="section-info">
                            <h3 class="section-name">
                                {{ section.section_name }}
                            </h3>
                            <p class="section-description">
                                {{
                                    section.description ||
                                    "No description available"
                                }}
                            </p>
                            <div class="section-meta">
                                <span class="section-key">{{
                                    section.section_key
                                }}</span>
                                <span class="section-order"
                                    >Order: {{ section.sort_order }}</span
                                >
                            </div>
                        </div>

                        <div class="section-controls">
                            <div class="visibility-toggle">
                                <label class="toggle-switch">
                                    <input
                                        type="checkbox"
                                        v-model="section.is_visible"
                                        @change="
                                            toggleSectionVisibility(section)
                                        "
                                        :disabled="loading"
                                    />
                                    <span class="toggle-slider"></span>
                                </label>
                                <span class="toggle-label">
                                    {{
                                        section.is_visible
                                            ? "Visible"
                                            : "Hidden"
                                    }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Grid View -->
            <div v-else class="sections-grid">
                <div
                    v-for="section in sortedSections"
                    :key="section.id"
                    class="section-card"
                    :class="{ 'section-hidden': !section.is_visible }"
                >
                    <div class="card-header">
                        <div class="section-status">
                            <span
                                class="status-indicator"
                                :class="{ active: section.is_visible }"
                            ></span>
                            <span class="section-order">{{
                                section.sort_order
                            }}</span>
                        </div>
                    </div>

                    <div class="card-content">
                        <h3 class="card-title">{{ section.section_name }}</h3>
                        <p class="card-description">
                            {{ section.description || "No description" }}
                        </p>
                        <div class="card-meta">
                            <code class="section-key">{{
                                section.section_key
                            }}</code>
                        </div>
                    </div>

                    <div class="card-footer">
                        <label class="toggle-switch-sm">
                            <input
                                type="checkbox"
                                v-model="section.is_visible"
                                @change="toggleSectionVisibility(section)"
                                :disabled="loading"
                            />
                            <span class="toggle-slider-sm"></span>
                        </label>
                        <span class="toggle-label-sm">
                            {{ section.is_visible ? "Visible" : "Hidden" }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { ref, computed, onMounted } from "vue";
import axios from "axios";

export default {
    name: "SectionManager",
    setup() {
        const sections = ref([]);
        const loading = ref(false);
        const message = ref({ text: "", type: "" });
        const viewMode = ref("list");
        const draggingId = ref(null);
        const dragOverIndex = ref(null);
        const dragStartIndex = ref(null);

        const sortedSections = computed(() => {
            return [...sections.value].sort(
                (a, b) => a.sort_order - b.sort_order
            );
        });

        const visibleSectionsCount = computed(() => {
            return sections.value.filter((section) => section.is_visible)
                .length;
        });

        const showMessage = (text, type = "success") => {
            message.value = { text, type };
            setTimeout(() => {
                message.value = { text: "", type: "" };
            }, 5000);
        };

        const fetchSections = async () => {
            try {
                loading.value = true;
                const response = await axios.get("/admin/page-sections", {
                    params: { page: "home" },
                });

                if (response.data.success) {
                    sections.value = response.data.data;
                }
            } catch (error) {
                showMessage("Failed to load sections", "error");
                console.error("Error fetching sections:", error);
            } finally {
                loading.value = false;
            }
        };

        const toggleSectionVisibility = async (section) => {
            const originalVisibility = section.is_visible;
            try {
                // If hero_section → also hide programs_section
                if (
                    section.section_key === "hero_section" &&
                    !section.is_visible
                ) {
                    const programsSection = sections.value.find(
                        (s) => s.section_key === "programs_section"
                    );
                    if (programsSection) {
                        programsSection.is_visible = false;
                    }
                }

                const response = await axios.patch(
                    `/admin/page-sections/${section.id}/toggle`
                );

                if (response.data.success) {
                    showMessage(
                        `${section.section_name} ${
                            section.is_visible ? "shown" : "hidden"
                        }`
                    );
                }
            } catch (error) {
                // Revert on failure
                section.is_visible = !originalVisibility;
                showMessage("Failed to update section visibility", "error");
                console.error("Error toggling visibility:", error);
            }
        };

        const toggleAllSections = async (isVisible) => {
            try {
                loading.value = true;
                const sectionIds = sections.value.map((section) => section.id);

                const response = await axios.patch(
                    "/admin/page-sections/bulk-toggle",
                    {
                        section_ids: sectionIds,
                        is_visible: isVisible,
                    }
                );

                if (response.data.success) {
                    sections.value.forEach((section) => {
                        section.is_visible = isVisible;
                    });

                    showMessage(
                        `All sections ${isVisible ? "shown" : "hidden"}`
                    );
                }
            } catch (error) {
                showMessage("Failed to update sections", "error");
                console.error("Error in bulk toggle:", error);
            } finally {
                loading.value = false;
            }
        };

        const refreshSections = () => {
            fetchSections();
        };

        // Drag and drop functions
        const startDrag = (event, id, index) => {
            event.dataTransfer.dropEffect = "move";
            event.dataTransfer.effectAllowed = "move";
            draggingId.value = id;
            dragStartIndex.value = index;
        };

        const dragOver = (index) => {
            dragOverIndex.value = index;
        };

        const dragEnter = (event, index) => {
            event.preventDefault();
            dragOverIndex.value = index;
        };

        const dragLeave = () => {
            dragOverIndex.value = null;
        };

        const drop = async (targetIndex) => {
            if (dragStartIndex.value === null || dragOverIndex.value === null)
                return;

            // Create a new array with the updated order
            const newSections = [...sections.value];
            const movedSection = newSections[dragStartIndex.value];

            // Remove from original position
            newSections.splice(dragStartIndex.value, 1);
            // Insert at new position
            newSections.splice(targetIndex, 0, movedSection);

            // Update sort_order based on new position
            newSections.forEach((section, index) => {
                section.sort_order = index + 1;
            });

            sections.value = newSections;
            dragOverIndex.value = null;
            dragStartIndex.value = null;

            // Send update to server
            try {
                loading.value = true;
                const response = await axios.patch(
                    "/admin/page-sections/update-order",
                    {
                        sections: newSections.map((section) => ({
                            id: section.id,
                            sort_order: section.sort_order,
                        })),
                    }
                );

                if (response.data.success) {
                    showMessage("Section order updated successfully");
                }
            } catch (error) {
                // Revert if update fails
                fetchSections();
                showMessage("Failed to update section order", "error");
                console.error("Error updating section order:", error);
            } finally {
                loading.value = false;
            }
        };

        const dragEnd = () => {
            draggingId.value = null;
            dragOverIndex.value = null;
            dragStartIndex.value = null;
        };

        onMounted(() => {
            fetchSections();
        });

        return {
            sections,
            loading,
            message,
            viewMode,
            draggingId,
            dragOverIndex,
            sortedSections,
            visibleSectionsCount,
            fetchSections,
            toggleSectionVisibility,
            toggleAllSections,
            refreshSections,
            startDrag,
            dragOver,
            dragEnter,
            dragLeave,
            drop,
            dragEnd,
        };
    },
};
</script>

<style scoped>
.section-manager {
    max-width: 1200px;
    margin: 0 auto;
    padding: 24px;
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
    background: #ff7101;
    color: #ffffff;
}

.btn-primary:hover:not(:disabled) {
    background: #d35b00;
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(255, 113, 1, 0.3);
}

.btn-outline {
    background: #ffffff;
    color: #334155;
    border: 1px solid #cbd5e1;
}

.btn-outline:hover:not(:disabled) {
    background: #f1f5f9;
    border-color: #94a3b8;
    transform: translateY(-2px);
}

.btn:disabled {
    opacity: 0.6;
    cursor: not-allowed;
    transform: none;
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

/* Loading State */
.loading-container {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    min-height: 300px;
    gap: 16px;
}

.loading-spinner {
    width: 48px;
    height: 48px;
    border: 4px solid #f1f5f9;
    border-radius: 50%;
    border-top-color: #ff7101;
    animation: spin 1s ease-in-out infinite;
}

@keyframes spin {
    to {
        transform: rotate(360deg);
    }
}

/* Sections Container */
.sections-container {
    background: #ffffff;
    border-radius: 16px;
    padding: 32px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
    border: 1px solid #e2e8f0;
}

.sections-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 24px;
}

.sections-title {
    font-size: 1.5rem;
    font-weight: 700;
    color: #1e293b;
    margin: 0;
}

.sections-count {
    font-size: 0.9rem;
    color: #64748b;
    margin: 4px 0 0 0;
}

.view-controls {
    display: flex;
    gap: 8px;
}

.view-btn {
    padding: 8px 16px;
    border-radius: 8px;
    font-weight: 500;
    font-size: 0.9rem;
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    gap: 8px;
    background: #f1f5f9;
    color: #334155;
    border: 1px solid #cbd5e1;
}

.view-btn.active {
    background: #ff7101;
    color: #ffffff;
    border-color: #ff7101;
}

/* Sections List View */
.sections-list {
    display: flex;
    flex-direction: column;
    gap: 16px;
}

.section-item {
    background: #ffffff;
    border-radius: 12px;
    padding: 16px;
    display: flex;
    gap: 16px;
    align-items: center;
    border: 1px solid #e2e8f0;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.03);
    transition: all 0.3s ease;
    position: relative;
}

.section-item.section-hidden {
    opacity: 0.85;
    background: #f8fafc;
}

.section-item.dragging {
    opacity: 0.6;
    background: #f1f5f9;
}

.section-item.drag-over {
    background: #dbeafe;
    border: 1px dashed #ff7101;
}

.section-drag-handle {
    cursor: move;
    padding: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #94a3b8;
    transition: all 0.3s ease;
}

.section-drag-handle:hover {
    color: #ff7101;
}

.section-content {
    flex: 1;
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 16px;
}

.section-info {
    flex: 1;
}

.section-name {
    font-size: 1.1rem;
    font-weight: 600;
    color: #1e293b;
    margin: 0 0 4px 0;
}

.section-description {
    font-size: 0.9rem;
    color: #64748b;
    margin: 0 0 8px 0;
    line-height: 1.5;
}

.section-meta {
    display: flex;
    gap: 12px;
    align-items: center;
}

.section-key {
    font-size: 0.8rem;
    background: #f1f5f9;
    color: #ff7101;
    padding: 4px 8px;
    border-radius: 4px;
    font-family: monospace;
}

.section-order {
    font-size: 0.8rem;
    color: #64748b;
    background: #f8fafc;
    padding: 4px 8px;
    border-radius: 4px;
}

.section-controls {
    display: flex;
    gap: 12px;
    align-items: center;
}

.visibility-toggle {
    display: flex;
    align-items: center;
    gap: 8px;
}

.toggle-label {
    font-size: 0.9rem;
    color: #334155;
    font-weight: 500;
}

/* Toggle Switch */
.toggle-switch {
    position: relative;
    display: inline-block;
    width: 50px;
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
    transition: 0.4s;
    border-radius: 24px;
}

.toggle-slider:before {
    position: absolute;
    content: "";
    height: 16px;
    width: 16px;
    left: 4px;
    bottom: 4px;
    background-color: white;
    transition: 0.4s;
    border-radius: 50%;
}

input:checked + .toggle-slider {
    background-color: #ff7101;
}

input:checked + .toggle-slider:before {
    transform: translateX(26px);
}

/* Sections Grid View */
.sections-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 20px;
}

.section-card {
    background: #ffffff;
    border-radius: 12px;
    padding: 20px;
    display: flex;
    flex-direction: column;
    border: 1px solid #e2e8f0;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.03);
    transition: all 0.3s ease;
}

.section-card.section-hidden {
    opacity: 0.85;
    background: #f8fafc;
}

.card-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 16px;
}

.section-status {
    display: flex;
    align-items: center;
    gap: 8px;
}

.status-indicator {
    width: 12px;
    height: 12px;
    border-radius: 50%;
    background: #cbd5e1;
}

.status-indicator.active {
    background: #10b981;
}

.section-order {
    font-size: 0.8rem;
    color: #64748b;
    background: #f8fafc;
    padding: 4px 8px;
    border-radius: 4px;
}

.card-content {
    flex: 1;
    margin-bottom: 16px;
}

.card-title {
    font-size: 1.1rem;
    font-weight: 600;
    color: #1e293b;
    margin: 0 0 8px 0;
}

.card-description {
    font-size: 0.9rem;
    color: #64748b;
    margin: 0 0 12px 0;
    line-height: 1.5;
}

.card-meta {
    margin-top: 12px;
}

.section-key {
    font-size: 0.8rem;
    background: #f1f5f9;
    color: #ff7101;
    padding: 4px 8px;
    border-radius: 4px;
    font-family: monospace;
    display: inline-block;
}

.card-footer {
    display: flex;
    align-items: center;
    gap: 8px;
}

.toggle-switch-sm {
    position: relative;
    display: inline-block;
    width: 40px;
    height: 20px;
}

.toggle-switch-sm input {
    opacity: 0;
    width: 0;
    height: 0;
}

.toggle-slider-sm {
    position: absolute;
    cursor: pointer;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: #cbd5e1;
    transition: 0.4s;
    border-radius: 20px;
}

.toggle-slider-sm:before {
    position: absolute;
    content: "";
    height: 14px;
    width: 14px;
    left: 3px;
    bottom: 3px;
    background-color: white;
    transition: 0.4s;
    border-radius: 50%;
}

.toggle-switch-sm input:checked + .toggle-slider-sm {
    background-color: #ff7101;
}

.toggle-switch-sm input:checked + .toggle-slider-sm:before {
    transform: translateX(20px);
}

.toggle-label-sm {
    font-size: 0.8rem;
    color: #334155;
    font-weight: 500;
}

/* Responsive Styles */
@media (max-width: 768px) {
    .header-content {
        flex-direction: column;
        align-items: flex-start;
        gap: 16px;
    }

    .header-actions {
        width: 100%;
        flex-wrap: wrap;
    }

    .sections-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 16px;
    }

    .view-controls {
        width: 100%;
        justify-content: flex-end;
    }

    .section-content {
        flex-direction: column;
        align-items: flex-start;
        gap: 16px;
    }

    .section-controls {
        width: 100%;
        justify-content: space-between;
    }

    .sections-grid {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 480px) {
    .section-manager {
        padding: 16px;
    }

    .page-header {
        padding: 16px;
    }

    .sections-container {
        padding: 20px;
    }

    .btn {
        padding: 8px 12px;
        font-size: 0.8rem;
    }

    .section-item {
        flex-direction: column;
        align-items: flex-start;
    }

    .section-drag-handle {
        align-self: center;
    }
}
</style>
