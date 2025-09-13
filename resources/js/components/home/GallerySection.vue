<template>
    <section
        class="gallery-section"
        :style="sectionStyles"
        v-if="sectionSettings.is_active && galleryItems.length > 0"
    >
        <div class="container" :style="containerStyles">
            <div class="section-header" :style="headerStyles">
                <h2 :style="titleStyles">
                    {{ sectionSettings.section_title }}
                </h2>
                <p :style="descriptionStyles">
                    {{ sectionSettings.section_description }}
                </p>
            </div>
            <div class="gallery-grid" :style="gridStyles">
                <div
                    class="gallery-item"
                    v-for="(item, index) in activeGalleryItems"
                    :key="item.id"
                    :style="getItemStyles(item, index)"
                    @click="handleItemClick(item)"
                >
                    <div
                        class="gallery-image"
                        :style="getItemBackgroundStyle(item)"
                    >
                        <img
                            v-if="
                                item.background_type === 'image' &&
                                item.background_image
                            "
                            :src="`uploads/image/${item.background_image}`"
                            :alt="item.title"
                            class="background-image"
                        />
                        <!-- Always visible overlay for image backgrounds -->
                        <div
                            v-if="item.background_type === 'image'"
                            class="image-overlay"
                            :style="getImageOverlayStyle(item)"
                        ></div>
                        <div
                            class="gallery-content"
                            :style="getContentOverlayStyle(item)"
                        >
                            <span
                                class="gallery-icon"
                                :style="getIconStyles(item)"
                                >{{ item.icon }}</span
                            >
                            <h3 :style="getTitleStyles(item)">
                                {{ item.title }}
                            </h3>
                            <p
                                v-if="item.description"
                                :style="getDescriptionStyles(item)"
                            >
                                {{ item.description }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>

<script>
import axios from "axios";

export default {
    name: "DynamicGallerySection",
    data() {
        return {
            galleryItems: [],
            sectionSettings: {
                section_title: "Explore Our Beautiful Campus",
                section_description:
                    "Take a virtual tour of our state-of-the-art facilities designed to inspire learning and spiritual growth.",
                section_title_color: "#102e4a",
                section_title_font_size: "2.5rem",
                section_title_font_weight: "700",
                section_description_color: "#111111",
                section_description_font_size: "1.1rem",
                section_background_color: "#f8fbfe",
                section_padding: "80px 0",
                header_text_align: "center",
                grid_columns: "repeat(auto-fit, minmax(350px, 1fr))",
                grid_gap: "20px",
                container_max_width: "1240px",
                container_padding: "0 20px",
                is_active: true,
            },
            isLoading: true,
            observer: null,
        };
    },
    computed: {
        activeGalleryItems() {
            return this.galleryItems.filter((item) => item.is_active);
        },
        sectionStyles() {
            return {
                padding: this.sectionSettings.section_padding,
                background: this.sectionSettings.section_background_color,
            };
        },
        containerStyles() {
            return {
                maxWidth: this.sectionSettings.container_max_width,
                margin: "0 auto",
                padding: this.sectionSettings.container_padding,
            };
        },
        headerStyles() {
            return {
                textAlign: this.sectionSettings.header_text_align,
                marginBottom: "60px",
                opacity: 0,
                transform: "translateY(30px)",
                transition: "all 0.6s ease",
            };
        },
        titleStyles() {
            return {
                fontSize: this.sectionSettings.section_title_font_size,
                color: this.sectionSettings.section_title_color,
                marginBottom: "15px",
                fontWeight: this.sectionSettings.section_title_font_weight,
                margin: "0 0 15px 0",
            };
        },
        descriptionStyles() {
            return {
                fontSize: this.sectionSettings.section_description_font_size,
                color: this.sectionSettings.section_description_color,
                maxWidth: "600px",
                margin:
                    this.sectionSettings.header_text_align === "center"
                        ? "0 auto"
                        : "0",
                lineHeight: "1.6",
            };
        },
        gridStyles() {
            return {
                display: "grid",
                gridTemplateColumns: this.sectionSettings.grid_columns,
                gap: this.sectionSettings.grid_gap,
            };
        },
    },
    methods: {
        async fetchGalleryData() {
            this.isLoading = true;
            try {
                // Fetch both gallery items and settings in parallel
                const [itemsResponse, settingsResponse] = await Promise.all([
                    axios.get("/gallery"),
                    axios.get("/gallery-settings"),
                ]);

                this.galleryItems = itemsResponse.data;
                if (settingsResponse.data) {
                    this.sectionSettings = {
                        ...this.sectionSettings,
                        ...settingsResponse.data,
                    };
                }
            } catch (error) {
                console.error("Error fetching gallery data:", error);
                // Fail gracefully - keep default data
            } finally {
                this.isLoading = false;
            }
        },

        getImageUrl(path) {
            return `uploads/image/${path}`;
        },

        getItemStyles(item, index) {
            return {
                borderRadius: item.border_radius || "15px",
                overflow: "hidden",
                height: item.item_height || "250px",
                transition: "all 0.4s ease",
                cursor: "pointer",
                opacity: 0,
                transform: "translateY(50px)",
                animationDelay: `${index * 0.1}s`,
                "--hover-transform":
                    item.hover_transform || "translateY(-5px) scale(1.02)",
                "--hover-shadow":
                    item.hover_shadow || "0 15px 40px rgba(0, 0, 0, 0.2)",
            };
        },

        getItemBackgroundStyle(item) {
            const style = {
                height: "100%",
                display: "flex",
                alignItems: "center",
                justifyContent: "center",
                position: "relative",
                color: "#fff",
            };

            if (item.background_type === "gradient") {
                style.background = `linear-gradient(${item.gradient_direction}, ${item.gradient_start}, ${item.gradient_end})`;
            } else if (item.background_type === "color") {
                style.background = item.background_color;
            } else if (
                item.background_type === "image" &&
                item.background_image
            ) {
                style.backgroundImage = `url('${this.getImageUrl(
                    item.background_image
                )}')`;
                style.backgroundSize = "cover";
                style.backgroundPosition = "center";
            }

            return style;
        },

        getImageOverlayStyle(item) {
            return {
                position: "absolute",
                top: 0,
                left: 0,
                right: 0,
                bottom: 0,
                background: item.overlay_color || "rgba(0, 0, 0, 0.5)",
                zIndex: 1,
                transition: "all 0.3s ease",
            };
        },

        getContentOverlayStyle(item) {
            return {
                textAlign: "center",
                position: "relative",
                zIndex: 3, // Higher z-index to be above overlay
                padding: "20px",
            };
        },

        getIconStyles(item) {
            return {
                fontSize: item.icon_size || "3rem",
                display: "block",
                marginBottom: "10px",
                filter: "drop-shadow(0 3px 6px rgba(0,0,0,0.5))", // Enhanced shadow for better readability
                textShadow: "0 2px 8px rgba(0,0,0,0.8)", // Additional text shadow
            };
        },

        getTitleStyles(item) {
            return {
                fontSize: item.title_font_size || "1.5rem",
                fontWeight: item.title_font_weight || "600",
                color: item.title_color || "#ffffff",
                margin: "0 0 8px 0",
                textShadow: "0 2px 8px rgba(0,0,0,0.8)", // Enhanced text shadow
                WebkitTextStroke:
                    item.background_type === "image"
                        ? "0.5px rgba(0,0,0,0.3)"
                        : "none", // Text stroke for better readability
            };
        },

        getDescriptionStyles(item) {
            return {
                fontSize: item.description_font_size || "1rem",
                color: item.description_color || "#ffffff",
                margin: "0",
                lineHeight: "1.5",
                textShadow: "0 2px 6px rgba(0,0,0,0.8)", // Enhanced text shadow
                WebkitTextStroke:
                    item.background_type === "image"
                        ? "0.3px rgba(0,0,0,0.2)"
                        : "none", // Text stroke for better readability
            };
        },

        handleItemClick(item) {
            // Emit event for parent component to handle
            this.$emit("gallery-item-click", item);

            // Or handle click logic here (e.g., open modal, navigate, etc.)
            console.log("Gallery item clicked:", item);
        },

        setupIntersectionObserver() {
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
        },

        observeElements() {
            this.$nextTick(() => {
                const sectionHeader = this.$el.querySelector(".section-header");
                const galleryItems = this.$el.querySelectorAll(".gallery-item");

                if (sectionHeader) {
                    this.observer.observe(sectionHeader);
                }

                galleryItems.forEach((item) => {
                    this.observer.observe(item);
                });
            });
        },
    },

    async mounted() {
        await this.fetchGalleryData();
        this.setupIntersectionObserver();
        this.observeElements();
    },

    beforeDestroy() {
        if (this.observer) {
            this.observer.disconnect();
        }
    },
};
</script>

<style scoped>
.gallery-section {
    position: relative;
    overflow: hidden;
}

/* Animation classes */
.section-header.animate {
    opacity: 1 !important;
    transform: translateY(0) !important;
}

.gallery-item.animate {
    opacity: 1 !important;
    transform: translateY(0) !important;
}

/* Gallery item hover effects */
.gallery-item:hover {
    transform: var(--hover-transform) !important;
    box-shadow: var(--hover-shadow) !important;
}

/* Enhanced hover effect for image overlay */
.gallery-item:hover .image-overlay {
    background: rgba(0, 0, 0, 0.7) !important;
}

.gallery-image {
    position: relative;
    overflow: hidden;
}

.background-image {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    object-fit: cover;
    z-index: 0;
}

.image-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    z-index: 1;
}

.gallery-content {
    position: relative;
    z-index: 3;
}

/* Remove the old overlay logic since we now have dedicated image overlay */
.gallery-item .gallery-image::before {
    display: none;
}

/* Enhanced text readability styles */
.gallery-item[data-background="image"] .gallery-icon,
.gallery-item[data-background="image"] .gallery-content h3,
.gallery-item[data-background="image"] .gallery-content p {
    text-shadow: 0 3px 8px rgba(0, 0, 0, 0.9) !important;
    -webkit-font-smoothing: antialiased;
    -moz-osx-font-smoothing: grayscale;
}

/* Alternative overlay styles that can be customized per item */
.overlay-light {
    background: rgba(255, 255, 255, 0.3) !important;
}

.overlay-dark {
    background: rgba(0, 0, 0, 0.5) !important;
}

.overlay-gradient {
    background: linear-gradient(
        to bottom,
        rgba(0, 0, 0, 0.2),
        rgba(0, 0, 0, 0.8)
    ) !important;
}

/* Responsive design */
@media (max-width: 768px) {
    .gallery-grid {
        grid-template-columns: 1fr !important;
    }

    .gallery-item {
        height: 200px !important;
    }

    .section-header h2 {
        font-size: 2rem !important;
    }

    .section-header p {
        font-size: 1rem !important;
    }
}

@media (max-width: 480px) {
    .gallery-section {
        padding: 60px 0 !important;
    }

    .container {
        padding: 0 15px !important;
    }

    .section-header {
        margin-bottom: 40px !important;
    }

    .gallery-icon {
        font-size: 2.5rem !important;
    }

    .gallery-content h3 {
        font-size: 1.2rem !important;
    }

    /* Enhanced mobile readability */
    .image-overlay {
        background: rgba(0, 0, 0, 0.6) !important;
    }
}

/* Loading state */
.gallery-section.loading {
    min-height: 400px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.loading-spinner {
    width: 40px;
    height: 40px;
    border: 4px solid #f3f3f3;
    border-top: 4px solid #ff7101;
    border-radius: 50%;
    animation: spin 1s linear infinite;
}

@keyframes spin {
    0% {
        transform: rotate(0deg);
    }
    100% {
        transform: rotate(360deg);
    }
}

/* Staggered animation effect */
.gallery-item:nth-child(1) {
    --animation-delay: 0.1s;
}
.gallery-item:nth-child(2) {
    --animation-delay: 0.2s;
}
.gallery-item:nth-child(3) {
    --animation-delay: 0.3s;
}
.gallery-item:nth-child(4) {
    --animation-delay: 0.4s;
}
.gallery-item:nth-child(5) {
    --animation-delay: 0.5s;
}
.gallery-item:nth-child(6) {
    --animation-delay: 0.6s;
}
.gallery-item:nth-child(7) {
    --animation-delay: 0.7s;
}
.gallery-item:nth-child(8) {
    --animation-delay: 0.8s;
}

/* Accessibility improvements */
.gallery-item {
    outline: none;
    transition: all 0.3s ease, outline 0.1s ease;
}

.gallery-item:focus {
    outline: 2px solid #ff7101;
    outline-offset: 2px;
}

.gallery-item:focus:not(:focus-visible) {
    outline: none;
}

/* Dark mode support (if needed) */
@media (prefers-color-scheme: dark) {
    .gallery-section {
        background: #1a1a1a !important;
    }

    .section-header h2 {
        color: #ffffff !important;
    }

    .section-header p {
        color: #cccccc !important;
    }
}
</style>
