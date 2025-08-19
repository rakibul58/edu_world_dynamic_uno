<template>
    <!-- Dynamic sections based on backend configuration -->
    <component
        v-for="section in visibleSections"
        :key="section.section_key"
        :is="section.component"
        :section-settings="section.settings"
        :section-data="section"
    />

    <!-- Loading state -->
    <div v-if="loading" class="loading-container">
        <div class="loading-spinner"></div>
        <p>Loading page content...</p>
    </div>

    <!-- Error state -->
    <div v-if="error" class="error-container">
        <div class="error-icon">
            <svg
                width="48"
                height="48"
                viewBox="0 0 24 24"
                fill="none"
                stroke="currentColor"
                stroke-width="2"
            >
                <circle cx="12" cy="12" r="10" />
                <line x1="15" y1="9" x2="9" y2="15" />
                <line x1="9" y1="9" x2="15" y2="15" />
            </svg>
        </div>
        <h2>Unable to load page</h2>
        <p>{{ error }}</p>
        <button @click="loadSections" class="retry-btn">
            <svg
                width="16"
                height="16"
                viewBox="0 0 24 24"
                fill="none"
                stroke="currentColor"
                stroke-width="2"
            >
                <polyline points="23,4 23,10 17,10" />
                <path d="M20.49 15a9 9 0 1 1-2.12-9.36L23 10" />
            </svg>
            Retry
        </button>
    </div>
</template>

<script>
import { ref, computed, onMounted } from "vue";
import axios from "axios";

// Import all section components
import HeaderSection from "../components/home/HeaderSection.vue";
import HeroSection from "../components/home/HeroSection.vue";
import ProgramsSection from "../components/home/ProgramsSection.vue";
import AboutSection from "../components/home/AboutSection.vue";
import FeaturesSection from "../components/home/FeaturesSection.vue";
import TestimonialsSection from "../components/home/TestimonialsSection.vue";
import GallerySection from "../components/home/GallerySection.vue";
import FacilitiesSection from "../components/home/FacilitiesSection.vue";
import AcademicPrograms from "../components/home/AcademicPrograms.vue";
import NewsEvents from "../components/home/NewsEvents.vue";
import LocationSection from "../components/home/LocationSection.vue";
import AdmissionProcess from "../components/home/AdmissionProcess.vue";
import CtaSection from "../components/home/CtaSection.vue";
import FooterSection from "../components/home/FooterSection.vue";

export default {
    name: "Home",
    components: {
        HeaderSection,
        HeroSection,
        ProgramsSection,
        AboutSection,
        FeaturesSection,
        TestimonialsSection,
        GallerySection,
        FacilitiesSection,
        AcademicPrograms,
        NewsEvents,
        LocationSection,
        AdmissionProcess,
        CtaSection,
        FooterSection,
    },
    setup() {
        const sections = ref([]);
        const loading = ref(true);
        const error = ref("");

        // Component mapping - maps section keys to component names
        const componentMap = {
            header_section: "HeaderSection",
            hero_section: "HeroSection",
            programs_section: "ProgramsSection",
            about_section: "AboutSection",
            features_section: "FeaturesSection",
            testimonials_section: "TestimonialsSection",
            gallery_section: "GallerySection",
            facilities_section: "FacilitiesSection",
            academic_programs: "AcademicPrograms",
            news_events: "NewsEvents",
            location_section: "LocationSection",
            admission_process: "AdmissionProcess",
            cta_section: "CtaSection",
            footer_section: "FooterSection",
        };

        const visibleSections = computed(() => {
            return sections.value
                .filter(
                    (section) =>
                        section.is_visible &&
                        getSectionComponent(section.section_key)
                )
                .map((section) => ({
                    ...section,
                    component: getSectionComponent(section.section_key),
                }))
                .sort((a, b) => a.sort_order - b.sort_order);
        });

        const getSectionComponent = (sectionKey) => {
            return componentMap[sectionKey] || null;
        };

        const loadSections = async () => {
            try {
                loading.value = true;
                error.value = "";

                const response = await axios.get("/page-sections/visible", {
                    params: { page: "home" },
                });

                if (response.data.success) {
                    sections.value = response.data.data;
                } else {
                    throw new Error("Failed to load page sections");
                }
            } catch (err) {
                console.error("Error loading sections:", err);
                error.value =
                    err.response?.data?.message ||
                    "Failed to load page content";

                // Fallback to default sections if API fails
                sections.value = getDefaultSections();
            } finally {
                loading.value = false;
            }
        };

        const getDefaultSections = () => {
            // Fallback configuration in case API is unavailable
            return [
                {
                    section_key: "header_section",
                    is_visible: true,
                    sort_order: 1,
                    settings: {},
                },
                {
                    section_key: "hero_section",
                    is_visible: true,
                    sort_order: 2,
                    settings: {},
                },
                {
                    section_key: "programs_section",
                    is_visible: true,
                    sort_order: 3,
                    settings: {},
                },
                {
                    section_key: "about_section",
                    is_visible: true,
                    sort_order: 4,
                    settings: {},
                },
                {
                    section_key: "features_section",
                    is_visible: true,
                    sort_order: 5,
                    settings: {},
                },
                {
                    section_key: "testimonials_section",
                    is_visible: true,
                    sort_order: 6,
                    settings: {},
                },
                {
                    section_key: "gallery_section",
                    is_visible: true,
                    sort_order: 7,
                    settings: {},
                },
                {
                    section_key: "facilities_section",
                    is_visible: true,
                    sort_order: 8,
                    settings: {},
                },
                {
                    section_key: "academic_programs",
                    is_visible: true,
                    sort_order: 9,
                    settings: {},
                },
                {
                    section_key: "news_events",
                    is_visible: true,
                    sort_order: 10,
                    settings: {},
                },
                {
                    section_key: "location_section",
                    is_visible: true,
                    sort_order: 11,
                    settings: {},
                },
                {
                    section_key: "admission_process",
                    is_visible: true,
                    sort_order: 12,
                    settings: {},
                },
                {
                    section_key: "cta_section",
                    is_visible: true,
                    sort_order: 13,
                    settings: {},
                },
                {
                    section_key: "footer_section",
                    is_visible: true,
                    sort_order: 14,
                    settings: {},
                },
            ];
        };

        onMounted(() => {
            loadSections();
        });

        return {
            visibleSections,
            loading,
            error,
            getSectionComponent,
            loadSections,
        };
    },
};
</script>

<style scoped>
.loading-container {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    min-height: 60vh;
    color: #d1d5db;
    text-align: center;
    padding: 40px 20px;
}

.loading-spinner {
    width: 48px;
    height: 48px;
    border: 4px solid #f8fbfe;
    border-radius: 50%;
    border-top-color: #ff7101;
    animation: spin 1s ease-in-out infinite;
    margin-bottom: 24px;
}

@keyframes spin {
    to {
        transform: rotate(360deg);
    }
}

.loading-container p {
    font-size: 1.1rem;
    margin: 0;
    color: #102e4a;
}

.error-container {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    min-height: 60vh;
    text-align: center;
    padding: 40px 20px;
    color: #102e4a;
}

.error-icon {
    color: #e74c3c;
    margin-bottom: 24px;
}

.error-container h2 {
    font-size: 1.8rem;
    font-weight: 700;
    margin: 0 0 12px 0;
    color: #102e4a;
}

.error-container p {
    font-size: 1.1rem;
    margin: 0 0 32px 0;
    color: #d1d5db;
    max-width: 400px;
}

.retry-btn {
    background: #ff7101;
    color: #ffffff;
    border: none;
    padding: 14px 24px;
    border-radius: 10px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 1rem;
}

.retry-btn:hover {
    background: #d35b00;
    transform: translateY(-2px);
}
</style>
