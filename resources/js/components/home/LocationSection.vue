<template>
    <section class="location-section" :style="sectionStyle" v-if="sectionData">
        <div class="container">
            <div class="section-header" ref="sectionHeader">
                <h2 :style="titleStyle">{{ sectionData.title }}</h2>
                <p :style="descriptionStyle">{{ sectionData.description }}</p>
            </div>

            <div class="location-content">
                <div class="location-info">
                    <div class="contact-cards">
                        <div
                            v-for="contact in contactCards"
                            :key="contact.id"
                            class="contact-card"
                            :style="contactCardStyle"
                            ref="contactCards"
                        >
                            <div class="contact-icon">{{ contact.icon }}</div>
                            <h4 :style="contactTitleStyle">
                                {{ contact.title }}
                            </h4>
                            <p
                                v-html="contact.content"
                                :style="contactContentStyle"
                            ></p>
                        </div>
                    </div>
                </div>

                <div class="map-container" ref="mapContainer" v-if="mapData">
                    <iframe
                        :src="mapData.embed_url"
                        :width="mapData.width"
                        :height="mapData.height"
                        :style="mapStyle"
                        allowfullscreen=""
                        loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade"
                    ></iframe>
                </div>
            </div>
        </div>
    </section>
</template>

<script>
import axios from "axios";

export default {
    name: "DynamicLocationSection",
    data() {
        return {
            sectionData: null,
            contactCards: [],
            mapData: null,
            styles: {},
            isLoading: false,
            error: null,
        };
    },
    computed: {
        sectionStyle() {
            if (!this.sectionData) return {};

            return {
                padding: `${this.sectionData.padding_top}px 0 ${this.sectionData.padding_bottom}px 0`,
                background: this.sectionData.background_color || "#f8fbfe",
                ...this.getCustomStyles("location-section"),
            };
        },

        titleStyle() {
            const baseStyle = {
                fontSize: "2.5rem",
                color: "#102e4a",
                marginBottom: "15px",
                fontWeight: "700",
            };

            return {
                ...baseStyle,
                ...this.getCustomStyles("section-header-title"),
            };
        },

        descriptionStyle() {
            const baseStyle = {
                fontSize: "1.1rem",
                color: "#111",
                maxWidth: "600px",
                margin: "0 auto",
            };

            return {
                ...baseStyle,
                ...this.getCustomStyles("section-header-description"),
            };
        },

        contactCardStyle() {
            const baseStyle = {
                background: "#fff",
                padding: "25px",
                borderRadius: "15px",
                boxShadow: "0 5px 20px rgba(0, 0, 0, 0.08)",
                textAlign: "center",
                transition: "all 0.3s ease",
            };

            return {
                ...baseStyle,
                ...this.getCustomStyles("contact-card"),
            };
        },

        contactTitleStyle() {
            const baseStyle = {
                color: "#102e4a",
                marginBottom: "10px",
                fontSize: "1.2rem",
                fontWeight: "600",
            };

            return {
                ...baseStyle,
                ...this.getCustomStyles("contact-card-title"),
            };
        },

        contactContentStyle() {
            const baseStyle = {
                color: "#111",
                lineHeight: "1.6",
                margin: 0,
            };

            return {
                ...baseStyle,
                ...this.getCustomStyles("contact-card-content"),
            };
        },

        mapStyle() {
            const baseStyle = {
                width: "100%",
                border: "0",
                borderRadius: this.mapData?.border_radius || "15px",
            };

            return {
                ...baseStyle,
                height: `${this.mapData?.height || 400}px`,
                ...this.getCustomStyles("map-iframe"),
            };
        },
    },
    methods: {
        async fetchLocationData() {
            this.isLoading = true;
            this.error = null;

            try {
                const response = await axios.get("/location-section/public");

                if (response.data.success) {
                    this.sectionData = response.data.data.section;
                    this.contactCards =
                        response.data.data.section.contact_cards || [];
                    this.mapData = response.data.data.section.location_map;
                    this.styles = response.data.data.styles || {};

                    // Initialize animations after data is loaded
                    this.$nextTick(() => {
                        this.initializeAnimations();
                    });
                } else {
                    this.error = "Failed to load location data";
                }
            } catch (error) {
                console.error("Error fetching location data:", error);
                this.error =
                    error.response?.data?.message ||
                    "Failed to load location section";

                // Fallback to default data
                this.loadFallbackData();
            } finally {
                this.isLoading = false;
            }
        },

        loadFallbackData() {
            // Fallback data in case API fails
            this.sectionData = {
                title: "Visit Our Campus",
                description:
                    "Located in the heart of Chattogram, our campus is easily accessible and provides a serene environment for learning.",
                background_color: "#f8fbfe",
                padding_top: 80,
                padding_bottom: 80,
            };

            this.contactCards = [
                {
                    id: 1,
                    icon: "📍",
                    title: "Address",
                    content:
                        "Edu World<br />123 Education Street<br />Chattogram 4000, Bangladesh",
                },
                {
                    id: 2,
                    icon: "📞",
                    title: "Phone",
                    content: "+880 31 123 4567<br />+880 1712 345 678",
                },
                {
                    id: 3,
                    icon: "✉️",
                    title: "Email",
                    content: "info@eduworld.edu<br />admissions@eduworld.edu",
                },
                {
                    id: 4,
                    icon: "🕒",
                    title: "Office Hours",
                    content: "Saturday - Thursday<br />8:00 AM - 4:00 PM",
                },
            ];

            this.mapData = {
                embed_url:
                    "https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3079.6418820516014!2d91.80977059999998!3d22.360993000000004!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x30acd8909da98ea1%3A0x3d9b8053516f015c!2sThe%20world%20IT!5e1!3m2!1sen!2sbd!4v1755059269124!5m2!1sen!2sbd",
                width: 600,
                height: 400,
                border_radius: "15px",
            };
        },

        getCustomStyles(className) {
            return this.styles[className] || {};
        },

        initializeAnimations() {
            const observer = new IntersectionObserver(
                (entries) => {
                    entries.forEach((entry) => {
                        if (entry.isIntersecting) {
                            entry.target.classList.add("animate");
                        }
                    });
                },
                { threshold: 0.1 }
            );

            // Observe section header
            if (this.$refs.sectionHeader) {
                observer.observe(this.$refs.sectionHeader);
            }

            // Observe contact cards
            if (this.$refs.contactCards) {
                this.$refs.contactCards.forEach((el, index) => {
                    if (el) {
                        // Add staggered animation delay
                        el.style.setProperty(
                            "--delay",
                            `${(index + 1) * 0.1}s`
                        );
                        observer.observe(el);
                    }
                });
            }

            // Observe map container
            if (this.$refs.mapContainer) {
                observer.observe(this.$refs.mapContainer);
            }
        },

        // Method to refresh data (can be called from parent components)
        refreshData() {
            this.fetchLocationData();
        },
    },

    async mounted() {
        await this.fetchLocationData();
    },
};
</script>

<style scoped>
.container {
    max-width: 1240px;
    margin: 0 auto;
    padding: 0 20px;
}

.location-section {
    transition: all 0.3s ease;
}

.section-header {
    text-align: center;
    margin-bottom: 60px;
    opacity: 0;
    transform: translateY(30px);
    transition: all 0.6s ease;
}

.section-header.animate {
    opacity: 1;
    transform: translateY(0);
}

.section-header h2 {
    margin-bottom: 15px;
}

.section-header p {
    margin: 0 auto;
}

.location-content {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 60px;
    align-items: start;
}

.contact-cards {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 20px;
}

.contact-card {
    opacity: 0;
    transform: translateY(30px);
    transition: all 0.6s ease var(--delay, 0s);
}

.contact-card.animate {
    opacity: 1;
    transform: translateY(0);
}

.contact-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 30px rgba(0, 0, 0, 0.15);
}

.contact-icon {
    font-size: 2.5rem;
    margin-bottom: 15px;
    display: block;
}

.contact-card h4 {
    margin-bottom: 10px;
}

.map-container {
    border-radius: 15px;
    overflow: hidden;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    opacity: 0;
    transform: translateY(30px);
    transition: all 0.6s ease;
}

.map-container.animate {
    opacity: 1;
    transform: translateY(0);
}

/* Error State */
.error-state {
    text-align: center;
    padding: 60px 20px;
    color: #ef4444;
    background: #fef2f2;
    border-radius: 15px;
    border: 1px solid #fecaca;
}

.error-state h3 {
    margin: 0 0 10px 0;
    color: #dc2626;
}

.error-state p {
    margin: 0 0 20px 0;
    color: #7f1d1d;
}

.retry-btn {
    background: #dc2626;
    color: white;
    border: none;
    padding: 10px 20px;
    border-radius: 8px;
    cursor: pointer;
    font-weight: 500;
    transition: all 0.2s;
}

.retry-btn:hover {
    background: #b91c1c;
    transform: translateY(-1px);
}

/* Loading State */
.loading-state {
    text-align: center;
    padding: 60px 20px;
    color: #6b7280;
}

.loading-spinner {
    font-size: 2rem;
    color: #ff7101;
    margin-bottom: 20px;
}

.fa-spin {
    animation: spin 2s linear infinite;
}

@keyframes spin {
    0% {
        transform: rotate(0deg);
    }
    100% {
        transform: rotate(360deg);
    }
}

/* Responsive Design */
@media (max-width: 768px) {
    .location-content {
        grid-template-columns: 1fr;
        gap: 30px;
    }

    .contact-cards {
        grid-template-columns: 1fr;
    }

    .section-header h2 {
        font-size: 2rem;
    }

    .section-header p {
        font-size: 1rem;
    }

    .contact-card {
        padding: 20px;
    }

    .contact-icon {
        font-size: 2rem;
        margin-bottom: 10px;
    }
}

@media (max-width: 480px) {
    .container {
        padding: 0 15px;
    }

    .section-header {
        margin-bottom: 40px;
    }

    .section-header h2 {
        font-size: 1.75rem;
    }

    .location-content {
        gap: 20px;
    }

    .contact-cards {
        gap: 15px;
    }

    .contact-card {
        padding: 15px;
    }
}

/* Admin Preview Mode */
.admin-preview {
    position: relative;
}

.admin-preview::before {
    content: "Preview Mode";
    position: absolute;
    top: 10px;
    right: 10px;
    background: #ff7101;
    color: white;
    padding: 4px 8px;
    border-radius: 4px;
    font-size: 12px;
    font-weight: 500;
    z-index: 10;
}

/* High contrast mode support */
@media (prefers-contrast: high) {
    .contact-card {
        border: 2px solid #000;
    }

    .map-container {
        border: 2px solid #000;
    }
}

/* Reduced motion support */
@media (prefers-reduced-motion: reduce) {
    .section-header,
    .contact-card,
    .map-container {
        transition: none;
        opacity: 1;
        transform: none;
    }

    .contact-card:hover {
        transform: none;
    }
}
</style>
