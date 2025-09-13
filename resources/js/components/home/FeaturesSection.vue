<template>
    <section
        class="features-section"
        :style="sectionStyles"
        v-if="sectionData && sectionData.is_active"
    >
        <div class="container" :style="containerStyles">
            <div
                class="section-header"
                :style="headerStyles"
                ref="sectionHeader"
            >
                <h2 :style="titleStyles">{{ sectionData.section_title }}</h2>
                <p :style="descriptionStyles">
                    {{ sectionData.section_description }}
                </p>
            </div>
            <div class="features-grid" :style="gridStyles">
                <div
                    class="feature-card"
                    v-for="(feature, index) in activeFeatures"
                    :key="feature.id"
                    :style="getFeatureCardStyles(feature, index)"
                    ref="featureCards"
                >
                    <div class="feature-icon" :style="getIconStyles(feature)">
                        {{ feature.icon }}
                    </div>
                    <h3 :style="getFeatureTitleStyles(feature)">
                        {{ feature.title }}
                    </h3>
                    <p :style="getFeatureDescriptionStyles(feature)">
                        {{ feature.description }}
                    </p>
                </div>
            </div>
        </div>
    </section>
</template>

<script>
import axios from "axios";

export default {
    name: "DynamicFeaturesSection",
    data() {
        return {
            sectionData: null,
            features: [],
            isLoading: true,
            error: null,
        };
    },
    computed: {
        activeFeatures() {
            return this.features.filter((feature) => feature.is_active);
        },
        sectionStyles() {
            if (!this.sectionData) return {};

            const styles = {
                padding: this.sectionData.section_padding || "80px 0",
                background: this.sectionData.section_background_color || "#fff",
            };

            if (this.sectionData.section_background_image) {
                styles.backgroundImage = `url('${this.getImageUrl(
                    this.sectionData.section_background_image
                )}')`;
                styles.backgroundSize = "cover";
                styles.backgroundPosition = "center";
                styles.backgroundRepeat = "no-repeat";
            }

            return styles;
        },
        containerStyles() {
            if (!this.sectionData) return {};

            return {
                maxWidth: this.sectionData.container_max_width || "1240px",
                margin: "0 auto",
                padding: this.sectionData.container_padding || "0 20px",
            };
        },
        headerStyles() {
            if (!this.sectionData) return {};

            return {
                textAlign: this.sectionData.header_text_align || "center",
                marginBottom: this.sectionData.header_margin_bottom || "60px",
                opacity: "0",
                transform: "translateY(30px)",
                transition: this.sectionData.enable_animations
                    ? `all ${
                          this.sectionData.animation_duration || "0.6s"
                      } ease`
                    : "none",
            };
        },
        titleStyles() {
            if (!this.sectionData) return {};

            return {
                fontSize: this.sectionData.section_title_font_size || "2.5rem",
                color: this.sectionData.section_title_color || "#102e4a",
                marginBottom: "15px",
                fontWeight: this.sectionData.section_title_font_weight || "700",
            };
        },
        descriptionStyles() {
            if (!this.sectionData) return {};

            return {
                fontSize:
                    this.sectionData.section_description_font_size || "1.1rem",
                color: this.sectionData.section_description_color || "#111",
                maxWidth: "600px",
                margin: "0 auto",
                fontWeight:
                    this.sectionData.section_description_font_weight || "400",
            };
        },
        gridStyles() {
            if (!this.sectionData) return {};

            const baseStyles = {
                display: "grid",
                gridTemplateColumns:
                    this.sectionData.grid_columns ||
                    "repeat(auto-fit, minmax(300px, 1fr))",
                gap: this.sectionData.grid_gap || "30px",
            };

            // Add mobile responsiveness
            if (this.sectionData.mobile_breakpoint) {
                const breakpoint = parseInt(this.sectionData.mobile_breakpoint);
                if (window.innerWidth <= breakpoint) {
                    baseStyles.gridTemplateColumns =
                        this.sectionData.mobile_grid_columns || "1fr";
                }
            }

            return baseStyles;
        },
    },
    methods: {
        async fetchData() {
            this.isLoading = true;
            this.error = null;

            try {
                const response = await axios.get("/features/active");
                this.sectionData = response.data.section;
                this.features = response.data.features;
            } catch (error) {
                console.error("Error fetching features data:", error);
                this.error = "Failed to load features data";

                // Fallback to default data in case of error
                this.loadFallbackData();
            } finally {
                this.isLoading = false;
            }
        },

        loadFallbackData() {
            // Fallback data in case API fails
            this.sectionData = {
                section_title: "Why Choose Edu World?",
                section_description:
                    "Discover what makes our Islamic Cambridge education exceptional and how we prepare students for success in this life and the next.",
                section_title_color: "#102e4a",
                section_title_font_size: "2.5rem",
                section_title_font_weight: "700",
                section_description_color: "#111111",
                section_description_font_size: "1.1rem",
                section_background_color: "#ffffff",
                section_padding: "80px 0",
                header_text_align: "center",
                header_margin_bottom: "60px",
                grid_columns: "repeat(auto-fit, minmax(300px, 1fr))",
                grid_gap: "30px",
                container_max_width: "1240px",
                container_padding: "0 20px",
                enable_animations: true,
                animation_duration: "0.6s",
                animation_delay_increment: "0.1s",
                is_active: true,
            };

            this.features = [
                {
                    id: 1,
                    icon: "🎓",
                    title: "Academic Excellence",
                    description:
                        "Our Cambridge curriculum ensures international standards while our dedicated teachers provide personalized attention to help every student reach their full potential.",
                    color: "#ff7101",
                    is_active: true,
                    title_color: "#102e4a",
                    title_font_size: "1.5rem",
                    title_font_weight: "600",
                    description_color: "#111111",
                    card_background_color: "#ffffff",
                },
                {
                    id: 2,
                    icon: "🕌",
                    title: "Islamic Values",
                    description:
                        "We integrate Islamic principles into every aspect of learning, fostering spiritual growth alongside academic achievement in a nurturing environment.",
                    color: "#20bf6b",
                    is_active: true,
                    title_color: "#102e4a",
                    title_font_size: "1.5rem",
                    title_font_weight: "600",
                    description_color: "#111111",
                    card_background_color: "#ffffff",
                },
            ];
        },

        getFeatureCardStyles(feature, index) {
            const baseStyles = {
                background: feature.card_background_color || "#fff",
                padding: feature.card_padding || "30px",
                borderRadius: feature.card_border_radius || "15px",
                boxShadow:
                    feature.card_shadow || "0 10px 30px rgba(0, 0, 0, 0.1)",
                textAlign: "center",
                transition:
                    this.sectionData && this.sectionData.enable_animations
                        ? `all ${
                              this.sectionData.animation_duration || "0.6s"
                          } ease`
                        : "all 0.3s ease",
                borderTop: `${feature.card_border_top_width || "4px"} ${
                    feature.card_border_top_style || "solid"
                } ${feature.color || "#ff7101"}`,
                opacity: "0",
                transform: "translateY(30px)",
                cursor: "pointer",
            };

            // Add animation delay
            if (this.sectionData && this.sectionData.enable_animations) {
                const delay =
                    parseFloat(
                        this.sectionData.animation_delay_increment || "0.1"
                    ) * index;
                baseStyles.transitionDelay = `${delay}s`;
            }

            return baseStyles;
        },

        getIconStyles(feature) {
            return {
                width: feature.icon_size || "80px",
                height: feature.icon_size || "80px",
                borderRadius: "50%",
                display: "flex",
                alignItems: "center",
                justifyContent: "center",
                margin: `0 auto ${feature.icon_margin_bottom || "20px"}`,
                fontSize: feature.icon_font_size || "2rem",
                color: feature.icon_color || "#fff",
                background: feature.color || "#ff7101",
            };
        },

        getFeatureTitleStyles(feature) {
            return {
                fontSize: feature.title_font_size || "1.5rem",
                color: feature.title_color || "#102e4a",
                marginBottom: "15px",
                fontWeight: feature.title_font_weight || "600",
            };
        },

        getFeatureDescriptionStyles(feature) {
            return {
                color: feature.description_color || "#111",
                lineHeight: "1.6",
                fontSize: feature.description_font_size || "1rem",
                fontWeight: feature.description_font_weight || "400",
            };
        },

        getImageUrl(path) {
            if (!path) return "";
            return path.startsWith("http") ? path : `/${path}`;
        },

        handleCardHover(event, feature) {
            if (!this.sectionData || !this.sectionData.enable_animations)
                return;

            event.currentTarget.style.transform =
                feature.card_hover_transform || "translateY(-10px)";
            event.currentTarget.style.boxShadow =
                feature.card_hover_shadow || "0 20px 40px rgba(0, 0, 0, 0.15)";
        },

        handleCardLeave(event, feature) {
            if (!this.sectionData || !this.sectionData.enable_animations)
                return;

            event.currentTarget.style.transform = "translateY(0)";
            event.currentTarget.style.boxShadow =
                feature.card_shadow || "0 10px 30px rgba(0, 0, 0, 0.1)";
        },

        initializeAnimations() {
            if (!this.sectionData || !this.sectionData.enable_animations) {
                // If animations are disabled, show everything immediately
                if (this.$refs.sectionHeader) {
                    this.$refs.sectionHeader.style.opacity = "1";
                    this.$refs.sectionHeader.style.transform = "translateY(0)";
                }
                if (this.$refs.featureCards) {
                    this.$refs.featureCards.forEach((card) => {
                        card.style.opacity = "1";
                        card.style.transform = "translateY(0)";
                    });
                }
                return;
            }

            // Use Intersection Observer for scroll animations
            const observer = new IntersectionObserver(
                (entries) => {
                    entries.forEach((entry) => {
                        if (entry.isIntersecting) {
                            entry.target.classList.add("animate");
                            entry.target.style.opacity = "1";
                            entry.target.style.transform = "translateY(0)";
                        }
                    });
                },
                { threshold: 0.1 }
            );

            // Observe header
            if (this.$refs.sectionHeader) {
                observer.observe(this.$refs.sectionHeader);
            }

            // Observe feature cards
            if (this.$refs.featureCards) {
                this.$refs.featureCards.forEach((card) => {
                    observer.observe(card);
                });
            }
        },

        handleResize() {
            // Update grid styles on window resize for mobile responsiveness
            this.$forceUpdate();
        },
    },

    async mounted() {
        await this.fetchData();

        // Initialize animations after data is loaded and DOM is updated
        this.$nextTick(() => {
            this.initializeAnimations();

            // Add hover event listeners to feature cards
            if (this.$refs.featureCards) {
                this.$refs.featureCards.forEach((card, index) => {
                    const feature = this.activeFeatures[index];
                    card.addEventListener("mouseenter", (e) =>
                        this.handleCardHover(e, feature)
                    );
                    card.addEventListener("mouseleave", (e) =>
                        this.handleCardLeave(e, feature)
                    );
                });
            }
        });

        // Add resize listener for mobile responsiveness
        window.addEventListener("resize", this.handleResize);
    },

    beforeUnmount() {
        // Clean up event listeners
        window.removeEventListener("resize", this.handleResize);
    },

    watch: {
        // Re-initialize animations when data changes
        sectionData: {
            handler() {
                this.$nextTick(() => {
                    this.initializeAnimations();
                });
            },
            deep: true,
        },
        features: {
            handler() {
                this.$nextTick(() => {
                    this.initializeAnimations();
                });
            },
            deep: true,
        },
    },
};
</script>

<style scoped>
/* Base styles that work with dynamic data */
.features-section {
    position: relative;
}

.section-header h2 {
    margin: 0;
    /* Other styles applied dynamically */
}

.section-header p {
    margin: 0;
    /* Other styles applied dynamically */
}

.feature-card {
    /* Base styles, most styling applied dynamically */
    position: relative;
    overflow: hidden;
    transition: all 0.3s ease !important; /* Force consistent timing */
}

.feature-card:hover {
    /* Hover effects are handled via JavaScript for dynamic control */
    /* But ensure consistent timing */
    transition: all 0.3s ease !important;
}

.feature-card h3 {
    margin: 0;
    /* Other styles applied dynamically */
}

.feature-card p {
    margin: 0;
    /* Other styles applied dynamically */
}

/* Loading and error states */
.loading-state {
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 200px;
    font-size: 1.2rem;
    color: #6b7280;
}

.error-state {
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    min-height: 200px;
    padding: 20px;
    text-align: center;
}

.error-state h3 {
    color: #ef4444;
    margin-bottom: 10px;
}

.error-state p {
    color: #6b7280;
    margin-bottom: 20px;
}

.retry-btn {
    padding: 10px 20px;
    background: #ff7101;
    color: white;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    font-weight: 500;
    transition: background 0.2s;
}

.retry-btn:hover {
    background: #e56500;
}

/* Mobile responsiveness - fallback styles */
@media (max-width: 768px) {
    .section-header h2 {
        font-size: 2rem !important;
    }

    .section-header p {
        font-size: 1rem !important;
    }

    .feature-card {
        margin-bottom: 20px;
    }

    .feature-card h3 {
        font-size: 1.3rem !important;
    }
}

@media (max-width: 480px) {
    .section-header h2 {
        font-size: 1.8rem !important;
    }

    .features-grid {
        grid-template-columns: 1fr !important;
        gap: 20px !important;
    }
}

/* Staggered animation delays are handled dynamically via JavaScript */
</style>
