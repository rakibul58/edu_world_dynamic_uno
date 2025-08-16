<template>
    <div class="admin-panel">
        <!-- Admin Header -->
        <header class="admin-header">
            <div class="admin-nav">
                <div class="admin-logo">
                    <img :src="settings.logo" :alt="settings.site_name" />
                    <span>Admin Panel</span>
                </div>
                <div class="admin-actions">
                    <span class="welcome">Welcome, {{ user?.name }}</span>
                    <button @click="logout" class="logout-btn">Logout</button>
                </div>
            </div>
        </header>

        <!-- Admin Content -->
        <div class="admin-content">
            <!-- Sidebar -->
            <aside class="admin-sidebar">
                <nav class="sidebar-nav">
                    <a
                        href="#"
                        @click="activeTab = 'dashboard'"
                        :class="{ active: activeTab === 'dashboard' }"
                    >
                        📊 Dashboard
                    </a>
                    <a
                        href="#"
                        @click="activeTab = 'settings'"
                        :class="{ active: activeTab === 'settings' }"
                    >
                        ⚙️ Site Settings
                    </a>
                    <a
                        href="#"
                        @click="activeTab = 'hero'"
                        :class="{ active: activeTab === 'hero' }"
                    >
                        🖼️ Hero Slides
                    </a>
                    <a
                        href="#"
                        @click="activeTab = 'programs'"
                        :class="{ active: activeTab === 'programs' }"
                    >
                        📚 Programs
                    </a>
                    <a
                        href="#"
                        @click="activeTab = 'features'"
                        :class="{ active: activeTab === 'features' }"
                    >
                        ⭐ Features
                    </a>
                    <a
                        href="#"
                        @click="activeTab = 'testimonials'"
                        :class="{ active: activeTab === 'testimonials' }"
                    >
                        💬 Testimonials
                    </a>
                    <a
                        href="#"
                        @click="activeTab = 'gallery'"
                        :class="{ active: activeTab === 'gallery' }"
                    >
                        🖼️ Gallery
                    </a>
                    <a
                        href="#"
                        @click="activeTab = 'news'"
                        :class="{ active: activeTab === 'news' }"
                    >
                        📰 News & Events
                    </a>
                </nav>
            </aside>

            <!-- Main Content -->
            <main class="admin-main">
                <!-- Dashboard -->
                <div v-if="activeTab === 'dashboard'" class="admin-section">
                    <h2>Dashboard</h2>
                    <div class="dashboard-stats">
                        <div class="stat-card">
                            <div class="stat-icon">📚</div>
                            <div class="stat-info">
                                <span class="stat-number">{{
                                    adminData.programs?.length || 0
                                }}</span>
                                <span class="stat-label">Programs</span>
                            </div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-icon">⭐</div>
                            <div class="stat-info">
                                <span class="stat-number">{{
                                    adminData.features?.length || 0
                                }}</span>
                                <span class="stat-label">Features</span>
                            </div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-icon">💬</div>
                            <div class="stat-info">
                                <span class="stat-number">{{
                                    adminData.testimonials?.length || 0
                                }}</span>
                                <span class="stat-label">Testimonials</span>
                            </div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-icon">📰</div>
                            <div class="stat-info">
                                <span class="stat-number">{{
                                    adminData.newsEvents?.length || 0
                                }}</span>
                                <span class="stat-label">News & Events</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Site Settings -->
                <div v-if="activeTab === 'settings'" class="admin-section">
                    <h2>Site Settings</h2>
                    <div class="settings-tabs">
                        <button
                            v-for="group in settingsGroups"
                            :key="group"
                            @click="activeSettingsGroup = group"
                            :class="{ active: activeSettingsGroup === group }"
                            class="settings-tab"
                        >
                            {{ formatGroupName(group) }}
                        </button>
                    </div>

                    <form @submit.prevent="saveSettings" class="settings-form">
                        <div class="settings-grid">
                            <div
                                v-for="setting in filteredSettings"
                                :key="setting.key"
                                class="setting-item"
                            >
                                <label :for="setting.key">{{
                                    formatSettingName(setting.key)
                                }}</label>

                                <!-- Color input -->
                                <input
                                    v-if="setting.type === 'color'"
                                    :id="setting.key"
                                    type="color"
                                    v-model="settingsForm[setting.key]"
                                    class="color-input"
                                />

                                <!-- Image input -->
                                <div
                                    v-else-if="setting.type === 'image'"
                                    class="image-input"
                                >
                                    <img
                                        v-if="settingsForm[setting.key]"
                                        :src="settingsForm[setting.key]"
                                        :alt="setting.key"
                                        class="current-image"
                                    />
                                    <input
                                        type="file"
                                        @change="
                                            handleImageUpload(
                                                $event,
                                                setting.key
                                            )
                                        "
                                        accept="image/*"
                                        class="file-input"
                                    />
                                </div>

                                <!-- Textarea input -->
                                <textarea
                                    v-else-if="setting.type === 'textarea'"
                                    :id="setting.key"
                                    v-model="settingsForm[setting.key]"
                                    rows="3"
                                    class="textarea-input"
                                ></textarea>

                                <!-- Text input -->
                                <input
                                    v-else
                                    :id="setting.key"
                                    type="text"
                                    v-model="settingsForm[setting.key]"
                                    class="text-input"
                                />
                            </div>
                        </div>

                        <button
                            type="submit"
                            class="save-btn"
                            :disabled="loading"
                        >
                            {{ loading ? "Saving..." : "Save Settings" }}
                        </button>
                    </form>
                </div>

                <!-- Hero Slides Management -->
                <div v-if="activeTab === 'hero'" class="admin-section">
                    <div class="section-header">
                        <h2>Hero Slides</h2>
                        <button
                            @click="showHeroForm = !showHeroForm"
                            class="add-btn"
                        >
                            {{ showHeroForm ? "Cancel" : "Add New Slide" }}
                        </button>
                    </div>

                    <!-- Hero Form -->
                    <div v-if="showHeroForm" class="form-card">
                        <h3>{{ editingHero ? "Edit" : "Add" }} Hero Slide</h3>
                        <form
                            @submit.prevent="saveHeroSlide"
                            class="entity-form"
                        >
                            <div class="form-row">
                                <div class="form-group">
                                    <label>Title</label>
                                    <input
                                        type="text"
                                        v-model="heroForm.title"
                                        required
                                    />
                                </div>
                                <div class="form-group">
                                    <label>Subtitle</label>
                                    <input
                                        type="text"
                                        v-model="heroForm.subtitle"
                                    />
                                </div>
                            </div>

                            <div class="form-group">
                                <label>Description</label>
                                <textarea
                                    v-model="heroForm.description"
                                    rows="3"
                                ></textarea>
                            </div>

                            <div class="form-row">
                                <div class="form-group">
                                    <label>Background Color</label>
                                    <input
                                        type="color"
                                        v-model="heroForm.background_color"
                                    />
                                </div>
                                <div class="form-group">
                                    <label>Background Image</label>
                                    <input
                                        type="file"
                                        @change="handleHeroImageUpload"
                                        accept="image/*"
                                    />
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group">
                                    <label>Primary CTA Text</label>
                                    <input
                                        type="text"
                                        v-model="heroForm.cta_primary_text"
                                    />
                                </div>
                                <div class="form-group">
                                    <label>Primary CTA URL</label>
                                    <input
                                        type="text"
                                        v-model="heroForm.cta_primary_url"
                                    />
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group">
                                    <label>Secondary CTA Text</label>
                                    <input
                                        type="text"
                                        v-model="heroForm.cta_secondary_text"
                                    />
                                </div>
                                <div class="form-group">
                                    <label>Secondary CTA URL</label>
                                    <input
                                        type="text"
                                        v-model="heroForm.cta_secondary_url"
                                    />
                                </div>
                            </div>

                            <div class="form-actions">
                                <button type="submit" class="save-btn">
                                    {{
                                        editingHero ? "Update" : "Create"
                                    }}
                                    Slide
                                </button>
                                <button
                                    type="button"
                                    @click="resetHeroForm"
                                    class="cancel-btn"
                                >
                                    Cancel
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Hero Slides List -->
                    <div class="items-list">
                        <div
                            v-for="slide in adminData.heroSlides"
                            :key="slide.id"
                            class="item-card"
                        >
                            <div class="item-preview">
                                <div
                                    class="slide-preview"
                                    :style="{
                                        background: slide.background_image
                                            ? `url(${slide.background_image})`
                                            : slide.background_color,
                                    }"
                                >
                                    <div class="slide-overlay">
                                        <h4>{{ slide.title }}</h4>
                                        <p>{{ slide.subtitle }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="item-details">
                                <h4>{{ slide.title }}</h4>
                                <p>{{ slide.description }}</p>
                                <div class="item-actions">
                                    <button
                                        @click="editHeroSlide(slide)"
                                        class="edit-btn"
                                    >
                                        Edit
                                    </button>
                                    <button
                                        @click="deleteHeroSlide(slide.id)"
                                        class="delete-btn"
                                    >
                                        Delete
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Programs Management -->
                <div v-if="activeTab === 'programs'" class="admin-section">
                    <div class="section-header">
                        <h2>Programs</h2>
                        <button
                            @click="showProgramForm = !showProgramForm"
                            class="add-btn"
                        >
                            {{ showProgramForm ? "Cancel" : "Add New Program" }}
                        </button>
                    </div>

                    <!-- Program Form -->
                    <div v-if="showProgramForm" class="form-card">
                        <h3>{{ editingProgram ? "Edit" : "Add" }} Program</h3>
                        <form @submit.prevent="saveProgram" class="entity-form">
                            <div class="form-row">
                                <div class="form-group">
                                    <label>Title</label>
                                    <input
                                        type="text"
                                        v-model="programForm.title"
                                        required
                                    />
                                </div>
                                <div class="form-group">
                                    <label>Icon</label>
                                    <input
                                        type="text"
                                        v-model="programForm.icon"
                                        placeholder="🎓"
                                    />
                                </div>
                            </div>

                            <div class="form-group">
                                <label>Description</label>
                                <textarea
                                    v-model="programForm.description"
                                    rows="4"
                                    required
                                ></textarea>
                            </div>

                            <div class="form-row">
                                <div class="form-group">
                                    <label>Background Color</label>
                                    <input
                                        type="color"
                                        v-model="programForm.background_color"
                                    />
                                </div>
                                <div class="form-group">
                                    <label>Button Text</label>
                                    <input
                                        type="text"
                                        v-model="programForm.button_text"
                                    />
                                </div>
                            </div>

                            <div class="form-group">
                                <label>Button URL</label>
                                <input
                                    type="text"
                                    v-model="programForm.button_url"
                                />
                            </div>

                            <div class="form-actions">
                                <button type="submit" class="save-btn">
                                    {{
                                        editingProgram ? "Update" : "Create"
                                    }}
                                    Program
                                </button>
                                <button
                                    type="button"
                                    @click="resetProgramForm"
                                    class="cancel-btn"
                                >
                                    Cancel
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Programs List -->
                    <div class="items-list">
                        <div
                            v-for="program in adminData.programs"
                            :key="program.id"
                            class="item-card"
                        >
                            <div class="item-preview">
                                <div
                                    class="program-preview"
                                    :style="{
                                        background: program.background_color,
                                        borderRadius:
                                            'var(--border-radius-base)',
                                    }"
                                >
                                    <span class="program-icon">{{
                                        program.icon
                                    }}</span>
                                </div>
                            </div>
                            <div class="item-details">
                                <h4>{{ program.title }}</h4>
                                <p>
                                    {{
                                        program.description.substring(0, 100)
                                    }}...
                                </p>
                                <div class="item-actions">
                                    <button
                                        @click="editProgram(program)"
                                        class="edit-btn"
                                    >
                                        Edit
                                    </button>
                                    <button
                                        @click="deleteProgram(program.id)"
                                        class="delete-btn"
                                    >
                                        Delete
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Features Management -->
                <div v-if="activeTab === 'features'" class="admin-section">
                    <div class="section-header">
                        <h2>Features</h2>
                        <button
                            @click="showFeatureForm = !showFeatureForm"
                            class="add-btn"
                        >
                            {{ showFeatureForm ? "Cancel" : "Add New Feature" }}
                        </button>
                    </div>

                    <!-- Feature Form -->
                    <div v-if="showFeatureForm" class="form-card">
                        <h3>{{ editingFeature ? "Edit" : "Add" }} Feature</h3>
                        <form @submit.prevent="saveFeature" class="entity-form">
                            <div class="form-row">
                                <div class="form-group">
                                    <label>Title</label>
                                    <input
                                        type="text"
                                        v-model="featureForm.title"
                                        required
                                    />
                                </div>
                                <div class="form-group">
                                    <label>Icon</label>
                                    <input
                                        type="text"
                                        v-model="featureForm.icon"
                                        placeholder="🎓"
                                    />
                                </div>
                            </div>

                            <div class="form-group">
                                <label>Description</label>
                                <textarea
                                    v-model="featureForm.description"
                                    rows="4"
                                    required
                                ></textarea>
                            </div>

                            <div class="form-group">
                                <label>Icon Background Color</label>
                                <input
                                    type="color"
                                    v-model="featureForm.icon_background_color"
                                />
                            </div>

                            <div class="form-actions">
                                <button type="submit" class="save-btn">
                                    {{
                                        editingFeature ? "Update" : "Create"
                                    }}
                                    Feature
                                </button>
                                <button
                                    type="button"
                                    @click="resetFeatureForm"
                                    class="cancel-btn"
                                >
                                    Cancel
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Features List -->
                    <div class="items-list">
                        <div
                            v-for="feature in adminData.features"
                            :key="feature.id"
                            class="item-card"
                        >
                            <div class="item-preview">
                                <div
                                    class="feature-preview"
                                    :style="{
                                        background:
                                            feature.icon_background_color,
                                        borderRadius: '50%',
                                        width: '60px',
                                        height: '60px',
                                        display: 'flex',
                                        alignItems: 'center',
                                        justifyContent: 'center',
                                        fontSize: '1.5rem',
                                        color: '#fff',
                                    }"
                                >
                                    {{ feature.icon }}
                                </div>
                            </div>
                            <div class="item-details">
                                <h4>{{ feature.title }}</h4>
                                <p>
                                    {{
                                        feature.description.substring(0, 100)
                                    }}...
                                </p>
                                <div class="item-actions">
                                    <button
                                        @click="editFeature(feature)"
                                        class="edit-btn"
                                    >
                                        Edit
                                    </button>
                                    <button
                                        @click="deleteFeature(feature.id)"
                                        class="delete-btn"
                                    >
                                        Delete
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Testimonials Management -->
                <div v-if="activeTab === 'testimonials'" class="admin-section">
                    <div class="section-header">
                        <h2>Testimonials</h2>
                        <button
                            @click="showTestimonialForm = !showTestimonialForm"
                            class="add-btn"
                        >
                            {{
                                showTestimonialForm
                                    ? "Cancel"
                                    : "Add New Testimonial"
                            }}
                        </button>
                    </div>

                    <!-- Testimonial Form -->
                    <div v-if="showTestimonialForm" class="form-card">
                        <h3>
                            {{
                                editingTestimonial ? "Edit" : "Add"
                            }}
                            Testimonial
                        </h3>
                        <form
                            @submit.prevent="saveTestimonial"
                            class="entity-form"
                        >
                            <div class="form-group">
                                <label>Content</label>
                                <textarea
                                    v-model="testimonialForm.content"
                                    rows="4"
                                    required
                                ></textarea>
                            </div>

                            <div class="form-row">
                                <div class="form-group">
                                    <label>Author Name</label>
                                    <input
                                        type="text"
                                        v-model="testimonialForm.author_name"
                                        required
                                    />
                                </div>
                                <div class="form-group">
                                    <label>Author Title</label>
                                    <input
                                        type="text"
                                        v-model="testimonialForm.author_title"
                                        required
                                    />
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group">
                                    <label>Author Initials</label>
                                    <input
                                        type="text"
                                        v-model="
                                            testimonialForm.author_initials
                                        "
                                        maxlength="5"
                                    />
                                </div>
                                <div class="form-group">
                                    <label>Author Avatar</label>
                                    <input
                                        type="file"
                                        @change="handleTestimonialImageUpload"
                                        accept="image/*"
                                    />
                                </div>
                            </div>

                            <div class="form-actions">
                                <button type="submit" class="save-btn">
                                    {{
                                        editingTestimonial ? "Update" : "Create"
                                    }}
                                    Testimonial
                                </button>
                                <button
                                    type="button"
                                    @click="resetTestimonialForm"
                                    class="cancel-btn"
                                >
                                    Cancel
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Testimonials List -->
                    <div class="items-list">
                        <div
                            v-for="testimonial in adminData.testimonials"
                            :key="testimonial.id"
                            class="item-card"
                        >
                            <div class="item-preview">
                                <div
                                    class="author-preview"
                                    :style="{
                                        background: 'var(--c0)',
                                        borderRadius: '50%',
                                        width: '50px',
                                        height: '50px',
                                        display: 'flex',
                                        alignItems: 'center',
                                        justifyContent: 'center',
                                        color: '#fff',
                                        fontWeight: '600',
                                    }"
                                >
                                    {{ testimonial.author_initials }}
                                </div>
                            </div>
                            <div class="item-details">
                                <h4>{{ testimonial.author_name }}</h4>
                                <p class="author-title">
                                    {{ testimonial.author_title }}
                                </p>
                                <p class="testimonial-excerpt">
                                    "{{
                                        testimonial.content.substring(0, 100)
                                    }}..."
                                </p>
                                <div class="item-actions">
                                    <button
                                        @click="editTestimonial(testimonial)"
                                        class="edit-btn"
                                    >
                                        Edit
                                    </button>
                                    <button
                                        @click="
                                            deleteTestimonial(testimonial.id)
                                        "
                                        class="delete-btn"
                                    >
                                        Delete
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
</template>

<script>
import { ref, computed, onMounted, watch } from "vue";
import { useStore } from "vuex";
import { useRouter } from "vue-router";

export default {
    name: "Admin",
    setup() {
        const store = useStore();
        const router = useRouter();

        const activeTab = ref("dashboard");
        const activeSettingsGroup = ref("colors");
        const loading = ref(false);

        // Form visibility states
        const showHeroForm = ref(false);
        const showProgramForm = ref(false);
        const showFeatureForm = ref(false);
        const showTestimonialForm = ref(false);

        // Editing states
        const editingHero = ref(null);
        const editingProgram = ref(null);
        const editingFeature = ref(null);
        const editingTestimonial = ref(null);

        // Form data
        const settingsForm = ref({});
        const heroForm = ref({
            title: "",
            subtitle: "",
            description: "",
            background_color: "#ff7101",
            background_image: "",
            cta_primary_text: "Apply Now",
            cta_primary_url: "#",
            cta_secondary_text: "Learn More",
            cta_secondary_url: "#",
        });

        const programForm = ref({
            title: "",
            description: "",
            icon: "🎓",
            background_color: "#ff7101",
            button_text: "Learn more",
            button_url: "#",
        });

        const featureForm = ref({
            title: "",
            description: "",
            icon: "⭐",
            icon_background_color: "#ff7101",
        });

        const testimonialForm = ref({
            content: "",
            author_name: "",
            author_title: "",
            author_initials: "",
            author_avatar: "",
        });

        // Computed properties
        const settings = computed(() => store.state.site.settings);
        const adminData = computed(() => store.state.admin);
        const user = computed(() => store.state.auth.user);

        const settingsGroups = computed(() => {
            return [
                ...new Set(
                    Object.values(store.state.admin.settings).flatMap((group) =>
                        Object.keys(group)
                    )
                ),
            ];
        });

        const filteredSettings = computed(() => {
            const group = store.state.admin.settings[activeSettingsGroup.value];
            if (!group) return [];

            return Object.entries(group).map(([key, value]) => ({
                key,
                value,
                type: getSettingType(key),
            }));
        });

        // Methods
        const getSettingType = (key) => {
            if (key.includes("color")) return "color";
            if (
                key.includes("logo") ||
                key.includes("favicon") ||
                key.includes("image")
            )
                return "image";
            if (key.includes("description")) return "textarea";
            return "text";
        };

        const formatGroupName = (group) => {
            return (
                group.charAt(0).toUpperCase() + group.slice(1).replace("_", " ")
            );
        };

        const formatSettingName = (key) => {
            return key
                .replace(/_/g, " ")
                .replace(/\b\w/g, (l) => l.toUpperCase());
        };

        const logout = async () => {
            await store.dispatch("auth/logout");
            router.push("/login");
        };

        // Settings methods
        const saveSettings = async () => {
            loading.value = true;
            try {
                const types = {};
                const groups = {};

                Object.keys(settingsForm.value).forEach((key) => {
                    types[key] = getSettingType(key);
                    groups[key] = activeSettingsGroup.value;
                });

                await store.dispatch("admin/updateSettings", {
                    settings: settingsForm.value,
                    types,
                    groups,
                });

                // Reload site settings
                await store.dispatch("site/loadSettings");

                alert("Settings saved successfully!");
            } catch (error) {
                alert("Error saving settings: " + error.message);
            } finally {
                loading.value = false;
            }
        };

        const handleImageUpload = async (event, settingKey) => {
            const file = event.target.files[0];
            if (!file) return;

            try {
                const result = await store.dispatch("admin/uploadFile", {
                    file,
                    type: settingKey.includes("favicon") ? "favicon" : "logo",
                });

                settingsForm.value[settingKey] = result.url;
            } catch (error) {
                alert("Error uploading image: " + error.message);
            }
        };

        // Hero slide methods
        const saveHeroSlide = async () => {
            try {
                if (editingHero.value) {
                    await store.dispatch("admin/updateHeroSlide", {
                        ...heroForm.value,
                        id: editingHero.value.id,
                    });
                } else {
                    await store.dispatch(
                        "admin/createHeroSlide",
                        heroForm.value
                    );
                }

                resetHeroForm();
                alert("Hero slide saved successfully!");
            } catch (error) {
                alert("Error saving hero slide: " + error.message);
            }
        };

        const editHeroSlide = (slide) => {
            editingHero.value = slide;
            heroForm.value = { ...slide };
            showHeroForm.value = true;
        };

        const deleteHeroSlide = async (id) => {
            if (confirm("Are you sure you want to delete this hero slide?")) {
                try {
                    await store.dispatch("admin/deleteHeroSlide", id);
                    alert("Hero slide deleted successfully!");
                } catch (error) {
                    alert("Error deleting hero slide: " + error.message);
                }
            }
        };

        const resetHeroForm = () => {
            editingHero.value = null;
            showHeroForm.value = false;
            heroForm.value = {
                title: "",
                subtitle: "",
                description: "",
                background_color: "#ff7101",
                background_image: "",
                cta_primary_text: "Apply Now",
                cta_primary_url: "#",
                cta_secondary_text: "Learn More",
                cta_secondary_url: "#",
            };
        };

        const handleHeroImageUpload = async (event) => {
            const file = event.target.files[0];
            if (!file) return;

            try {
                const result = await store.dispatch("admin/uploadFile", {
                    file,
                    type: "hero",
                });

                heroForm.value.background_image = result.url;
            } catch (error) {
                alert("Error uploading image: " + error.message);
            }
        };

        // Program methods
        const saveProgram = async () => {
            try {
                if (editingProgram.value) {
                    await store.dispatch("admin/updateProgram", {
                        ...programForm.value,
                        id: editingProgram.value.id,
                    });
                } else {
                    await store.dispatch(
                        "admin/createProgram",
                        programForm.value
                    );
                }

                resetProgramForm();
                alert("Program saved successfully!");
            } catch (error) {
                alert("Error saving program: " + error.message);
            }
        };

        const editProgram = (program) => {
            editingProgram.value = program;
            programForm.value = { ...program };
            showProgramForm.value = true;
        };

        const deleteProgram = async (id) => {
            if (confirm("Are you sure you want to delete this program?")) {
                try {
                    await store.dispatch("admin/deleteProgram", id);
                    alert("Program deleted successfully!");
                } catch (error) {
                    alert("Error deleting program: " + error.message);
                }
            }
        };

        const resetProgramForm = () => {
            editingProgram.value = null;
            showProgramForm.value = false;
            programForm.value = {
                title: "",
                description: "",
                icon: "🎓",
                background_color: "#ff7101",
                button_text: "Learn more",
                button_url: "#",
            };
        };

        // Feature methods (similar pattern)
        const saveFeature = async () => {
            try {
                if (editingFeature.value) {
                    await store.dispatch("admin/updateFeature", {
                        ...featureForm.value,
                        id: editingFeature.value.id,
                    });
                } else {
                    await store.dispatch(
                        "admin/createFeature",
                        featureForm.value
                    );
                }

                resetFeatureForm();
                alert("Feature saved successfully!");
            } catch (error) {
                alert("Error saving feature: " + error.message);
            }
        };

        const editFeature = (feature) => {
            editingFeature.value = feature;
            featureForm.value = { ...feature };
            showFeatureForm.value = true;
        };

        const deleteFeature = async (id) => {
            if (confirm("Are you sure you want to delete this feature?")) {
                try {
                    await store.dispatch("admin/deleteFeature", id);
                    alert("Feature deleted successfully!");
                } catch (error) {
                    alert("Error deleting feature: " + error.message);
                }
            }
        };

        const resetFeatureForm = () => {
            editingFeature.value = null;
            showFeatureForm.value = false;
            featureForm.value = {
                title: "",
                description: "",
                icon: "⭐",
                icon_background_color: "#ff7101",
            };
        };

        // Testimonial methods
        const saveTestimonial = async () => {
            try {
                if (editingTestimonial.value) {
                    await store.dispatch("admin/updateTestimonial", {
                        ...testimonialForm.value,
                        id: editingTestimonial.value.id,
                    });
                } else {
                    await store.dispatch(
                        "admin/createTestimonial",
                        testimonialForm.value
                    );
                }

                resetTestimonialForm();
                alert("Testimonial saved successfully!");
            } catch (error) {
                alert("Error saving testimonial: " + error.message);
            }
        };

        const editTestimonial = (testimonial) => {
            editingTestimonial.value = testimonial;
            testimonialForm.value = { ...testimonial };
            showTestimonialForm.value = true;
        };

        const deleteTestimonial = async (id) => {
            if (confirm("Are you sure you want to delete this testimonial?")) {
                try {
                    await store.dispatch("admin/deleteTestimonial", id);
                    alert("Testimonial deleted successfully!");
                } catch (error) {
                    alert("Error deleting testimonial: " + error.message);
                }
            }
        };

        const resetTestimonialForm = () => {
            editingTestimonial.value = null;
            showTestimonialForm.value = false;
            testimonialForm.value = {
                content: "",
                author_name: "",
                author_title: "",
                author_initials: "",
                author_avatar: "",
            };
        };

        const handleTestimonialImageUpload = async (event) => {
            const file = event.target.files[0];
            if (!file) return;

            try {
                const result = await store.dispatch("admin/uploadFile", {
                    file,
                    type: "image",
                });

                testimonialForm.value.author_avatar = result.url;
            } catch (error) {
                alert("Error uploading image: " + error.message);
            }
        };

        // Load admin data
        const loadAdminData = async () => {
            await Promise.all([
                store.dispatch("admin/fetchSettings"),
                store.dispatch("admin/fetchHeroSlides"),
                store.dispatch("admin/fetchPrograms"),
                store.dispatch("admin/fetchFeatures"),
                store.dispatch("admin/fetchTestimonials"),
            ]);
        };

        // Watch for settings changes to update form
        watch(
            () => store.state.admin.settings,
            (newSettings) => {
                if (newSettings[activeSettingsGroup.value]) {
                    settingsForm.value = {
                        ...newSettings[activeSettingsGroup.value],
                    };
                }
            },
            { deep: true }
        );

        watch(activeSettingsGroup, (newGroup) => {
            if (store.state.admin.settings[newGroup]) {
                settingsForm.value = {
                    ...store.state.admin.settings[newGroup],
                };
            }
        });

        onMounted(() => {
            loadAdminData();
            store.dispatch("site/loadSettings");
        });

        return {
            activeTab,
            activeSettingsGroup,
            loading,
            settings,
            adminData,
            user,
            settingsGroups,
            filteredSettings,
            settingsForm,
            heroForm,
            programForm,
            featureForm,
            testimonialForm,
            showHeroForm,
            showProgramForm,
            showFeatureForm,
            showTestimonialForm,
            editingHero,
            editingProgram,
            editingFeature,
            editingTestimonial,
            formatGroupName,
            formatSettingName,
            logout,
            saveSettings,
            handleImageUpload,
            saveHeroSlide,
            editHeroSlide,
            deleteHeroSlide,
            resetHeroForm,
            handleHeroImageUpload,
            saveProgram,
            editProgram,
            deleteProgram,
            resetProgramForm,
            saveFeature,
            editFeature,
            deleteFeature,
            resetFeatureForm,
            saveTestimonial,
            editTestimonial,
            deleteTestimonial,
            resetTestimonialForm,
            handleTestimonialImageUpload,
        };
    },
};
</script>

<style scoped>
.admin-panel {
    min-height: 100vh;
    background: #f5f7fa;
}

.admin-header {
    background: #fff;
    border-bottom: 1px solid #e2e8f0;
    padding: 0 20px;
    position: sticky;
    top: 0;
    z-index: 100;
}

.admin-nav {
    max-width: var(--container-max-width);
    margin: 0 auto;
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 15px 0;
}

.admin-logo {
    display: flex;
    align-items: center;
    gap: 15px;
}

.admin-logo img {
    width: 40px;
    height: auto;
}

.admin-logo span {
    font-weight: 700;
    color: var(--c3);
    font-size: 1.2rem;
}

.admin-actions {
    display: flex;
    align-items: center;
    gap: 20px;
}

.welcome {
    color: var(--c8);
    font-weight: 500;
}

.logout-btn:hover {
    background: var(--c1);
}

.admin-content {
    display: grid;
    grid-template-columns: 250px 1fr;
    min-height: calc(100vh - 70px);
}

.admin-sidebar {
    background: #fff;
    border-right: 1px solid #e2e8f0;
    padding: 20px 0;
}

.sidebar-nav {
    display: flex;
    flex-direction: column;
    gap: 5px;
    padding: 0 20px;
}

.sidebar-nav a {
    padding: 12px 15px;
    text-decoration: none;
    color: var(--c8);
    border-radius: var(--border-radius-small);
    transition: all 0.3s ease;
    font-weight: 500;
}

.sidebar-nav a:hover,
.sidebar-nav a.active {
    background: var(--c0);
    color: #fff;
}

.admin-main {
    padding: 30px;
    overflow-y: auto;
}

.admin-section {
    background: #fff;
    border-radius: var(--border-radius-base);
    padding: 30px;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
}

.section-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 30px;
    padding-bottom: 15px;
    border-bottom: 2px solid #f0f2f5;
}

.section-header h2 {
    color: var(--c3);
    font-size: 1.8rem;
    font-weight: 700;
}

.add-btn {
    background: var(--c9);
    color: #fff;
    border: none;
    padding: 10px 20px;
    border-radius: var(--border-radius-small);
    cursor: pointer;
    font-weight: 500;
    transition: all 0.3s ease;
}

.add-btn:hover {
    background: var(--c11);
    transform: translateY(-2px);
}

/* Dashboard Stats */
.dashboard-stats {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 20px;
    margin-top: 20px;
}

.stat-card {
    background: linear-gradient(135deg, var(--c0), var(--c1));
    color: #fff;
    padding: 25px;
    border-radius: var(--border-radius-base);
    display: flex;
    align-items: center;
    gap: 15px;
    transition: transform 0.3s ease;
}

.stat-card:hover {
    transform: translateY(-5px);
}

.stat-icon {
    font-size: 2.5rem;
    opacity: 0.8;
}

.stat-info {
    display: flex;
    flex-direction: column;
}

.stat-number {
    font-size: 2rem;
    font-weight: 700;
}

.stat-label {
    opacity: 0.9;
    font-size: 0.9rem;
}

/* Settings */
.settings-tabs {
    display: flex;
    gap: 10px;
    margin-bottom: 25px;
    flex-wrap: wrap;
}

.settings-tab {
    padding: 10px 20px;
    border: 2px solid #e2e8f0;
    background: #fff;
    border-radius: var(--border-radius-small);
    cursor: pointer;
    font-weight: 500;
    color: var(--c8);
    transition: all 0.3s ease;
}

.settings-tab.active,
.settings-tab:hover {
    background: var(--c0);
    color: #fff;
    border-color: var(--c0);
}

.settings-form {
    background: #f8f9fa;
    padding: 25px;
    border-radius: var(--border-radius-base);
}

.settings-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 20px;
    margin-bottom: 25px;
}

.setting-item {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.setting-item label {
    font-weight: 600;
    color: var(--c3);
    font-size: 0.9rem;
}

.text-input,
.textarea-input {
    padding: 10px 12px;
    border: 2px solid #e2e8f0;
    border-radius: var(--border-radius-small);
    font-size: 0.95rem;
    transition: border-color 0.3s ease;
}

.text-input:focus,
.textarea-input:focus {
    outline: none;
    border-color: var(--c0);
}

.color-input {
    width: 60px;
    height: 40px;
    padding: 2px;
    border: 2px solid #e2e8f0;
    border-radius: var(--border-radius-small);
    cursor: pointer;
}

.image-input {
    display: flex;
    flex-direction: column;
    gap: 10px;
}

.current-image {
    width: 80px;
    height: 80px;
    object-fit: cover;
    border-radius: var(--border-radius-small);
    border: 2px solid #e2e8f0;
}

.file-input {
    padding: 8px;
    border: 2px dashed #e2e8f0;
    border-radius: var(--border-radius-small);
    background: #fff;
}

/* Form Cards */
.form-card {
    background: #f8f9fa;
    border-radius: var(--border-radius-base);
    padding: 25px;
    margin-bottom: 30px;
    border-left: 4px solid var(--c0);
}

.form-card h3 {
    color: var(--c3);
    margin-bottom: 20px;
    font-size: 1.4rem;
}

.entity-form {
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
}

.form-group {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.form-group label {
    font-weight: 600;
    color: var(--c3);
    font-size: 0.9rem;
}

.form-group input,
.form-group textarea {
    padding: 10px 12px;
    border: 2px solid #e2e8f0;
    border-radius: var(--border-radius-small);
    font-size: 0.95rem;
    transition: border-color 0.3s ease;
}

.form-group input:focus,
.form-group textarea:focus {
    outline: none;
    border-color: var(--c0);
}

.form-actions {
    display: flex;
    gap: 15px;
    margin-top: 10px;
}

.save-btn {
    background: var(--c9);
    color: #fff;
    border: none;
    padding: 12px 25px;
    border-radius: var(--border-radius-small);
    cursor: pointer;
    font-weight: 600;
    transition: all 0.3s ease;
}

.save-btn:hover:not(:disabled) {
    background: var(--c11);
    transform: translateY(-2px);
}

.save-btn:disabled {
    opacity: 0.6;
    cursor: not-allowed;
}

.cancel-btn {
    background: transparent;
    color: var(--c8);
    border: 2px solid #e2e8f0;
    padding: 10px 25px;
    border-radius: var(--border-radius-small);
    cursor: pointer;
    font-weight: 500;
    transition: all 0.3s ease;
}

.cancel-btn:hover {
    background: #f8f9fa;
    border-color: var(--c8);
}

/* Items List */
.items-list {
    display: grid;
    gap: 20px;
}

.item-card {
    background: #fff;
    border: 1px solid #e2e8f0;
    border-radius: var(--border-radius-base);
    padding: 20px;
    display: grid;
    grid-template-columns: auto 1fr;
    gap: 20px;
    align-items: center;
    transition: all 0.3s ease;
}

.item-card:hover {
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    transform: translateY(-2px);
}

.item-preview {
    display: flex;
    align-items: center;
    justify-content: center;
}

.slide-preview {
    width: 120px;
    height: 80px;
    border-radius: var(--border-radius-small);
    background-size: cover;
    background-position: center;
    position: relative;
    overflow: hidden;
}

.slide-overlay {
    position: absolute;
    inset: 0;
    background: rgba(0, 0, 0, 0.6);
    color: #fff;
    padding: 10px;
    display: flex;
    flex-direction: column;
    justify-content: center;
    text-align: center;
}

.slide-overlay h4 {
    font-size: 0.8rem;
    margin-bottom: 5px;
}

.slide-overlay p {
    font-size: 0.7rem;
    opacity: 0.9;
}

.program-preview {
    width: 80px;
    height: 80px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #fff;
}

.program-icon {
    font-size: 2rem;
}

.item-details {
    flex: 1;
}

.item-details h4 {
    color: var(--c3);
    margin-bottom: 8px;
    font-size: 1.2rem;
}

.item-details p {
    color: var(--c8);
    margin-bottom: 12px;
    line-height: 1.5;
}

.author-title {
    font-size: 0.9rem;
    color: var(--c0);
    font-weight: 500;
}

.testimonial-excerpt {
    font-style: italic;
    margin-top: 8px;
}

.item-actions {
    display: flex;
    gap: 10px;
}

.edit-btn,
.delete-btn {
    padding: 6px 15px;
    border: none;
    border-radius: var(--border-radius-small);
    cursor: pointer;
    font-size: 0.85rem;
    font-weight: 500;
    transition: all 0.3s ease;
}

.edit-btn {
    background: var(--c10);
    color: #fff;
}

.edit-btn:hover {
    background: #e67e22;
    transform: translateY(-1px);
}

.delete-btn {
    background: #e74c3c;
    color: #fff;
}

.delete-btn:hover {
    background: #c0392b;
    transform: translateY(-1px);
}

/* Responsive Design */
@media (max-width: 768px) {
    .admin-content {
        grid-template-columns: 1fr;
    }

    .admin-sidebar {
        order: 2;
        border-right: none;
        border-top: 1px solid #e2e8f0;
    }

    .sidebar-nav {
        flex-direction: row;
        overflow-x: auto;
        gap: 10px;
    }

    .sidebar-nav a {
        white-space: nowrap;
        font-size: 0.9rem;
    }

    .admin-main {
        order: 1;
        padding: 20px;
    }

    .form-row {
        grid-template-columns: 1fr;
    }

    .settings-grid {
        grid-template-columns: 1fr;
    }

    .item-card {
        grid-template-columns: 1fr;
        text-align: center;
    }

    .settings-tabs {
        justify-content: center;
    }

    .form-actions {
        flex-direction: column;
    }
}

@media (max-width: 480px) {
    .admin-nav {
        flex-direction: column;
        gap: 15px;
        text-align: center;
    }

    .admin-actions {
        flex-direction: column;
        gap: 10px;
    }
}
</style>
