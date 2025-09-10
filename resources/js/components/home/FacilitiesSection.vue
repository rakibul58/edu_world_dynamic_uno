<template>
    <section class="facilities-section" :style="sectionStyle">
        <div class="container">
            <div class="section-header" ref="sectionHeader">
                <h2 :style="titleStyle">{{ sectionData.title }}</h2>
                <p :style="subtitleStyle">{{ sectionData.subtitle }}</p>
            </div>

            <div class="facilities-showcase" ref="facilitiesShowcase">
                <!-- Featured Facility (Left Side) -->
                <div class="facility-large" ref="facilityLarge">
                    <div
                        class="facility-image"
                        :style="featuredBackgroundStyle"
                    >
                        <!-- Tint overlay for image -->
                        <div
                            v-if="sectionData.featured_type === 'image'"
                            class="facility-tint"
                            :style="tintOverlayStyle"
                        ></div>

                        <!-- Featured image -->
                        <img
                            v-if="
                                sectionData.featured_type === 'image' &&
                                sectionData.featured_image_path
                            "
                            :src="getImageUrl(sectionData.featured_image_path)"
                            :alt="
                                sectionData.featured_title || 'Facility image'
                            "
                            class="featured-image"
                        />

                        <!-- Content overlay -->
                        <div
                            class="facility-overlay"
                            :style="featuredTextStyle"
                        >
                            <h3 :style="featuredTitleStyle">
                                {{ sectionData.featured_title }}
                            </h3>
                            <p :style="featuredDescStyle">
                                {{ sectionData.featured_description }}
                            </p>

                            <!-- Dynamic buttons with hover styles -->
                            <div
                                class="facility-buttons"
                                v-if="
                                    sectionData.facility_buttons &&
                                    sectionData.facility_buttons.length
                                "
                            >
                                <a
                                    v-for="(
                                        button, index
                                    ) in sectionData.facility_buttons"
                                    :key="index"
                                    :href="button.url || '#'"
                                    class="facility-btn dynamic-btn"
                                    :style="getButtonStyle(button)"
                                    :data-hover-bg="
                                        button.hover_background_color
                                    "
                                    :data-hover-color="button.hover_text_color"
                                    @mouseenter="
                                        applyHoverStyles($event, button)
                                    "
                                    @mouseleave="
                                        removeHoverStyles($event, button)
                                    "
                                >
                                    {{ button.text }}
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Facility Items Grid (Right Side) -->
                <div class="facility-grid">
                    <div
                        class="facility-item"
                        v-for="(facility, index) in sectionData.facility_items"
                        :key="index"
                        ref="facilityItems"
                        :style="getFacilityItemStyle(facility)"
                    >
                        <div class="facility-icon">{{ facility.icon }}</div>
                        <h4 :style="getFacilityTitleStyle(facility)">
                            {{ facility.title }}
                        </h4>
                        <p :style="getFacilityDescStyle(facility)">
                            {{ facility.description }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>

<script>
import axios from "axios";

export default {
    name: "DynamicFacilitiesSection",
    props: {
        sectionId: {
            type: Number,
            default: null,
        },
    },
    data() {
        return {
            sectionData: {
                title: "World-Class Facilities",
                subtitle:
                    "Our campus is equipped with modern amenities and traditional Islamic architecture to create the perfect learning environment.",
                background_color: "#ffffff",
                title_color: "#102e4a",
                title_font_size: "2.5rem",
                title_font_weight: "700",
                subtitle_color: "#111111",
                subtitle_font_size: "1.1rem",
                subtitle_font_weight: "400",
                featured_title: "Modern Classrooms",
                featured_description:
                    "Smart boards, air conditioning, and ergonomic furniture in every classroom to enhance the learning experience.",
                featured_type: "gradient",
                featured_image_path: null,
                featured_gradient_start: "#ff7101",
                featured_gradient_end: "#102e4a",
                featured_gradient_direction: "135deg",
                featured_tint_color: "#000000",
                featured_tint_opacity: 0.5,
                featured_text_color: "#ffffff",
                featured_title_font_size: "2rem",
                featured_desc_font_size: "1.1rem",
                facility_items: [],
                facility_buttons: [],
            },
        };
    },
    computed: {
        sectionStyle() {
            return {
                padding: "80px 0",
                background: this.sectionData.background_color,
            };
        },
        titleStyle() {
            return {
                fontSize: this.sectionData.title_font_size,
                color: this.sectionData.title_color,
                marginBottom: "15px",
                fontWeight: this.sectionData.title_font_weight,
            };
        },
        subtitleStyle() {
            return {
                fontSize: this.sectionData.subtitle_font_size,
                color: this.sectionData.subtitle_color,
                fontWeight: this.sectionData.subtitle_font_weight,
                maxWidth: "600px",
                margin: "0 auto",
            };
        },
        featuredBackgroundStyle() {
            const baseStyle = {
                height: "100%",
                display: "flex",
                alignItems: "center",
                justifyContent: "center",
                position: "relative",
                borderRadius: "20px",
                overflow: "hidden",
            };

            if (this.sectionData.featured_type === "gradient") {
                baseStyle.background = `linear-gradient(${this.sectionData.featured_gradient_direction}, ${this.sectionData.featured_gradient_start}, ${this.sectionData.featured_gradient_end})`;
            } else {
                baseStyle.background = "#f0f0f0";
            }

            return baseStyle;
        },
        tintOverlayStyle() {
            return {
                position: "absolute",
                top: "0",
                left: "0",
                right: "0",
                bottom: "0",
                backgroundColor: this.sectionData.featured_tint_color,
                opacity: this.sectionData.featured_tint_opacity,
                zIndex: 1,
            };
        },
        featuredTextStyle() {
            return {
                textAlign: "center",
                color: this.sectionData.featured_text_color,
                padding: "30px",
                position: "relative",
                zIndex: 2,
            };
        },
        featuredTitleStyle() {
            return {
                fontSize: this.sectionData.featured_title_font_size,
                marginBottom: "15px",
                color: "inherit",
            };
        },
        featuredDescStyle() {
            return {
                fontSize: this.sectionData.featured_desc_font_size,
                marginBottom: "20px",
                opacity: "0.9",
                color: "inherit",
            };
        },
    },
    methods: {
        async fetchSectionData() {
            try {
                const url = this.sectionId
                    ? `/facilities-sections/${this.sectionId}`
                    : "/facilities-sections/active";

                const { data } = await axios.get(url);

                this.sectionData = {
                    ...this.sectionData,
                    ...data,
                };

                // Generate dynamic CSS for button hover styles
                this.generateButtonHoverStyles();
            } catch (error) {
                console.error("Error fetching facilities section data:", error);
            }
        },

        getImageUrl(imagePath) {
            return `${imagePath}`;
        },

        getButtonStyle(button) {
            return {
                backgroundColor: button.background_color,
                color: button.text_color,
                padding: button.padding,
                borderRadius: button.border_radius,
                fontSize: button.font_size,
                fontWeight: button.font_weight,
                textDecoration: "none",
                display: "inline-block",
                margin: "5px",
                transition: "all 0.3s ease",
                border: "none",
                cursor: "pointer",
            };
        },

        // Method to apply hover styles using JavaScript
        applyHoverStyles(event, button) {
            const element = event.target;
            element.style.backgroundColor =
                button.hover_background_color || button.background_color;
            element.style.color = button.hover_text_color || button.text_color;
        },

        // Method to remove hover styles
        removeHoverStyles(event, button) {
            const element = event.target;
            element.style.backgroundColor = button.background_color;
            element.style.color = button.text_color;
        },

        // Generate dynamic CSS for hover styles (Alternative approach)
        generateButtonHoverStyles() {
            // Remove existing dynamic styles
            const existingStyle = document.getElementById(
                "dynamic-button-styles"
            );
            if (existingStyle) {
                existingStyle.remove();
            }

            // Create new style element
            const style = document.createElement("style");
            style.id = "dynamic-button-styles";

            let css = "";
            this.sectionData.facility_buttons.forEach((button, index) => {
                css += `
                    .dynamic-btn-${index}:hover {
                        background-color: ${
                            button.hover_background_color ||
                            button.background_color
                        } !important;
                        color: ${
                            button.hover_text_color || button.text_color
                        } !important;
                        transform: translateY(-2px);
                    }
                `;
            });

            style.textContent = css;
            document.head.appendChild(style);
        },

        getFacilityItemStyle(facility) {
            return {
                background: facility.background_color || "#ffffff",
                padding: "25px",
                borderRadius: "15px",
                boxShadow: "0 5px 20px rgba(0, 0, 0, 0.08)",
                textAlign: "center",
                transition: "all 0.3s ease",
            };
        },

        getFacilityTitleStyle(facility) {
            return {
                color: facility.title_color || "#102e4a",
                fontSize: facility.title_font_size || "1.2rem",
                marginBottom: "10px",
                fontWeight: "600",
            };
        },

        getFacilityDescStyle(facility) {
            return {
                color: facility.description_color || "#111111",
                fontSize: facility.description_font_size || "0.95rem",
                lineHeight: "1.5",
                margin: "0",
            };
        },

        setupScrollAnimation() {
            if (this.observer) this.observer.disconnect();

            this.observer = new IntersectionObserver(
                (entries) => {
                    entries.forEach((entry) => {
                        if (entry.isIntersecting) {
                            entry.target.classList.add("animate");
                        }
                    });
                },
                { threshold: 0.1 }
            );

            this.$nextTick(() => {
                const elements = [];

                if (this.$refs.sectionHeader)
                    elements.push(this.$refs.sectionHeader);
                if (this.$refs.facilityLarge)
                    elements.push(this.$refs.facilityLarge);
                if (this.$refs.facilityItems) {
                    Array.isArray(this.$refs.facilityItems)
                        ? this.$refs.facilityItems.forEach((el) =>
                              elements.push(el)
                          )
                        : elements.push(this.$refs.facilityItems);
                }

                elements.forEach((el) => el && this.observer.observe(el));
            });
        },
    },
    async mounted() {
        await this.fetchSectionData();
        this.setupScrollAnimation();
    },
    beforeDestroy() {
        if (this.observer) this.observer.disconnect();
        // Clean up dynamic styles
        const existingStyle = document.getElementById("dynamic-button-styles");
        if (existingStyle) {
            existingStyle.remove();
        }
    },
    unmounted() {
        if (this.observer) this.observer.disconnect();
        // Clean up dynamic styles
        const existingStyle = document.getElementById("dynamic-button-styles");
        if (existingStyle) {
            existingStyle.remove();
        }
    },
};
</script>

<style scoped>
.container {
    max-width: 1240px;
    margin: 0 auto;
    padding: 0 20px;
}

.facilities-section {
    padding: 80px 0;
    background: #fff;
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
    max-width: 600px;
    margin: 0 auto;
}

.facilities-showcase {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 40px;
    align-items: center;
}

.facility-large {
    border-radius: 20px;
    overflow: hidden;
    height: 400px;
    opacity: 0;
    transform: translateY(30px);
    transition: all 0.6s ease;
}

.facility-large.animate {
    opacity: 1;
    transform: translateY(0);
}

.facility-image {
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    position: relative;
}

.featured-image {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    object-fit: cover;
    z-index: 0;
}

.facility-tint {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    z-index: 1;
}

.facility-overlay {
    text-align: center;
    padding: 30px;
    position: relative;
    z-index: 2;
}

.facility-overlay h3 {
    margin-bottom: 15px;
}

.facility-overlay p {
    margin-bottom: 20px;
    opacity: 0.9;
}

.facility-buttons {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    gap: 10px;
}

.facility-btn {
    display: inline-block;
    padding: 12px 30px;
    border-radius: 25px;
    text-decoration: none;
    font-weight: 600;
    transition: all 0.3s ease;
    border: none;
    cursor: pointer;
}

/* Default hover effect - will be overridden by dynamic styles */
.facility-btn:hover {
    transform: translateY(-2px);
}

.facility-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
}

.facility-item {
    background: #fff;
    padding: 25px;
    border-radius: 15px;
    box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
    text-align: center;
    transition: all 0.3s ease;
    opacity: 0;
    transform: translateY(70px);
}

.facility-item.animate {
    opacity: 1;
    transform: translateY(0);
}

.facility-item:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 30px rgba(0, 0, 0, 0.15);
}

.facility-icon {
    font-size: 2.5rem;
    margin-bottom: 15px;
    display: block;
}

.facility-item h4 {
    margin-bottom: 10px;
}

/* Staggered animation delays */
.facility-item:nth-child(1) {
    transition-delay: 0.1s;
}

.facility-item:nth-child(2) {
    transition-delay: 0.2s;
}

.facility-item:nth-child(3) {
    transition-delay: 0.3s;
}

.facility-item:nth-child(4) {
    transition-delay: 0.4s;
}

/* Responsive design */
@media (max-width: 768px) {
    .facilities-showcase {
        grid-template-columns: 1fr;
        gap: 30px;
    }

    .facility-grid {
        grid-template-columns: 1fr;
    }

    .facility-large {
        height: 300px;
    }

    .facility-overlay {
        padding: 20px;
    }

    .facility-overlay h3 {
        font-size: 1.5rem;
    }

    .facility-overlay p {
        font-size: 1rem;
    }

    .facility-buttons {
        flex-direction: column;
        align-items: center;
    }

    .facility-btn {
        width: 100%;
        max-width: 200px;
        text-align: center;
    }
}

@media (max-width: 480px) {
    .section-header h2 {
        font-size: 2rem !important;
    }

    .section-header p {
        font-size: 1rem !important;
    }

    .facility-large {
        height: 250px;
    }

    .facility-overlay {
        padding: 15px;
    }

    .facility-item {
        padding: 20px;
    }

    .facility-icon {
        font-size: 2rem;
    }
}
</style>
