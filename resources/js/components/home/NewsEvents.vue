<template>
    <section
        class="dynamic-news-events"
        :style="sectionStyles"
        v-if="settings && settings.is_active"
    >
        <div class="container" :style="containerStyles">
            <div class="section-header" ref="sectionHeader" :style="headerStyles">
                <h2 :style="titleStyles">{{ settings.section_title }}</h2>
                <p :style="descriptionStyles">
                    {{ settings.section_description }}
                </p>
            </div>
            <div class="news-grid" :style="gridStyles">
                <div
                    v-for="(item, index) in activeNewsItems"
                    :key="item.id"
                    class="news-item"
                    :style="getItemAnimationStyle(index)"
                    @mouseenter="onItemHover(item, true)"
                    @mouseleave="onItemHover(item, false)"
                >
                    <div
                        class="news-image"
                        :style="getCardImageStyle(item)"
                        :class="{ 'has-hover': item.isHovered }"
                    >
                        <img
                            v-if="
                                item.card_background_type === 'image' &&
                                item.image_url
                            "
                            :src="getImageUrl(item.image_url)"
                            :alt="item.title"
                            class="card-background-image"
                        />
                        <div class="image-content">
                            <span
                                class="news-icon"
                                v-if="item.card_background_type === 'gradient'"
                            >
                                {{ item.icon }}
                            </span>
                        </div>
                    </div>
                    <div
                        class="news-content"
                        :style="getCardContentStyle(item)"
                    >
                        <span class="news-date" :style="getDateStyle(item)">
                            {{ formatDate(item.date) }}
                        </span>
                        <h3 :style="getTitleStyle(item)">{{ item.title }}</h3>
                        <p :style="getDescriptionStyle(item)">
                            {{ item.description }}
                        </p>
                        <a
                            v-if="item.link"
                            :href="item.link"
                            class="news-link"
                            :style="getLinkStyle(item)"
                            @mouseenter="onLinkHover(item, true)"
                            @mouseleave="onLinkHover(item, false)"
                        >
                            Read More
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Loading State -->
        <div v-if="isLoading" class="loading-state">
            <div class="loading-spinner"></div>
            <p>Loading news and events...</p>
        </div>

        <!-- Error State -->
        <div v-if="error" class="error-state">
            <div class="error-icon">⚠️</div>
            <h3>Unable to load news and events</h3>
            <p>{{ error }}</p>
            <button @click="fetchData" class="retry-btn">Try Again</button>
        </div>

        <!-- Empty State -->
        <div
            v-if="!isLoading && !error && activeNewsItems.length === 0"
            class="empty-state"
        >
            <div class="empty-icon">📰</div>
            <h3>No news or events available</h3>
            <p>Check back later for updates!</p>
        </div>
    </section>
</template>

<script>
import axios from "axios";

export default {
    name: "NewsEventsSection",
    data() {
        return {
            settings: null,
            newsItems: [],
            isLoading: true,
            error: null,
            headerAnimated: false,
            observer: null,
        };
    },
    computed: {
        activeNewsItems() {
            return this.newsItems
                .filter((item) => item.is_active)
                .sort((a, b) => a.order - b.order);
        },
        enableAnimations() {
            return this.settings?.enable_animations ?? true;
        },
        sectionStyles() {
            if (!this.settings) return {};

            const styles = {
                backgroundColor:
                    this.settings.section_background_color || "#fff",
                padding: this.settings.section_padding || "80px 0",
            };

            if (this.settings.section_background_image) {
                styles.backgroundImage = `url('${this.getImageUrl(
                    this.settings.section_background_image
                )}')`;
                styles.backgroundSize = "cover";
                styles.backgroundPosition = "center";
                styles.backgroundRepeat = "no-repeat";
            }

            return styles;
        },
        containerStyles() {
            if (!this.settings) return {};

            return {
                maxWidth: this.settings.container_max_width || "1240px",
                margin: "0 auto",
                padding: this.settings.container_padding || "0 20px",
            };
        },
        headerStyles() {
            if (!this.settings) return {};

            const baseStyles = {
                textAlign: this.settings.header_text_align || "center",
                marginBottom: this.settings.header_margin_bottom || "60px",
                transition: "all 0.6s ease",
            };

            // If animations are disabled or header is already animated, show immediately
            if (!this.enableAnimations || this.headerAnimated) {
                baseStyles.opacity = 1;
                baseStyles.transform = "translateY(0)";
            } else {
                baseStyles.opacity = 0;
                baseStyles.transform = "translateY(30px)";
            }

            return baseStyles;
        },
        titleStyles() {
            if (!this.settings) return {};

            return {
                fontSize: this.settings.section_title_font_size || "2.5rem",
                color: this.settings.section_title_color || "#102e4a",
                fontWeight: this.settings.section_title_font_weight || "700",
                margin: "0 0 15px 0",
            };
        },
        descriptionStyles() {
            if (!this.settings) return {};

            return {
                fontSize:
                    this.settings.section_description_font_size || "1.1rem",
                color: this.settings.section_description_color || "#111",
                fontWeight:
                    this.settings.section_description_font_weight || "normal",
                maxWidth: "600px",
                margin:
                    this.settings.header_text_align === "center"
                        ? "0 auto"
                        : "0",
            };
        },
        gridStyles() {
            if (!this.settings) return {};

            const isMobile = window.innerWidth <= (this.settings.mobile_breakpoint ? parseInt(this.settings.mobile_breakpoint) : 768);
            
            return {
                display: "grid",
                gridTemplateColumns: isMobile 
                    ? (this.settings.mobile_grid_columns || "1fr")
                    : (this.settings.grid_columns || "repeat(auto-fit, minmax(350px, 1fr))"),
                gap: this.settings.grid_gap || "30px",
            };
        },
    },
    methods: {
        async fetchData() {
            this.isLoading = true;
            this.error = null;

            try {
                const response = await axios.get("/news-events/active");
                this.settings = response.data.settings;
                this.newsItems = response.data.newsEvents.map((item) => ({
                    ...item,
                    isHovered: false,
                    linkHovered: false,
                }));
            } catch (error) {
                console.error("Error fetching news events:", error);
                this.error =
                    "Failed to load news and events. Please try again later.";
            } finally {
                this.isLoading = false;
            }
        },

        getImageUrl(path) {
            if (!path) return "";
            return path.startsWith("http") ? path : `/${path}`;
        },

        formatDate(dateString) {
            try {
                const date = new Date(dateString);
                if (isNaN(date.getTime())) {
                    return "Invalid Date";
                }
                return date.toLocaleDateString("en-US", {
                    year: "numeric",
                    month: "long",
                    day: "numeric",
                });
            } catch (error) {
                console.error("Error formatting date:", error);
                return "Invalid Date";
            }
        },

        getCardImageStyle(item) {
            const styles = {
                height: "200px",
                display: "flex",
                alignItems: "center",
                justifyContent: "center",
                borderRadius: `${item.card_border_radius || "15px"} ${
                    item.card_border_radius || "15px"
                } 0 0`,
                position: "relative",
                overflow: "hidden",
                transition: "all 0.3s ease",
                color: "#fff",
            };

            if (item.card_background_type === "gradient" && item.gradient_start && item.gradient_end) {
                styles.background = `linear-gradient(${
                    item.gradient_direction || "135deg"
                }, ${item.gradient_start}, ${item.gradient_end})`;
            } else if (item.image_url) {
                styles.backgroundColor = "#f0f0f0";
            } else {
                // Fallback background
                styles.backgroundColor = "#6b7280";
            }

            return styles;
        },

        getCardContentStyle(item) {
            return {
                padding: "25px",
                backgroundColor: item.card_background_color || "#fff",
                borderRadius: `0 0 ${item.card_border_radius || "15px"} ${
                    item.card_border_radius || "15px"
                }`,
            };
        },

        getDateStyle(item) {
            return {
                color: item.date_color || "#ff7101",
                fontSize: item.date_font_size || "0.9rem",
                fontWeight: item.date_font_weight || "600",
                display: "block",
                marginBottom: "10px",
            };
        },

        getTitleStyle(item) {
            return {
                color: item.title_color || "#102e4a",
                fontSize: item.title_font_size || "1.3rem",
                fontWeight: item.title_font_weight || "600",
                margin: "10px 0 15px 0",
                lineHeight: "1.3",
            };
        },

        getDescriptionStyle(item) {
            return {
                color: item.description_color || "#111",
                fontSize: item.description_font_size || "1rem",
                fontWeight: item.description_font_weight || "normal",
                lineHeight: "1.6",
                margin: "0 0 15px 0",
            };
        },

        getLinkStyle(item) {
            return {
                color: item.linkHovered
                    ? item.link_hover_color || "#d35b00"
                    : item.link_color || "#ff7101",
                textDecoration: "none",
                fontWeight: item.link_font_weight || "600",
                transition: "color 0.3s ease",
            };
        },

        getItemAnimationStyle(index) {
            if (!this.enableAnimations) return {};
            
            const delay = (this.settings?.animation_delay_increment || 0.1) * (index + 1);
            return {
                "--delay": `${delay}s`,
                animationDelay: `${delay}s`,
            };
        },

        onItemHover(item, isHovered) {
            this.$set(item, 'isHovered', isHovered);
        },

        onLinkHover(item, isHovered) {
            this.$set(item, 'linkHovered', isHovered);
        },

        animateHeader() {
            if (!this.enableAnimations || this.headerAnimated) return;

            this.$nextTick(() => {
                const header = this.$refs.sectionHeader;
                if (header) {
                    this.headerAnimated = true;
                    // Force reactivity update
                    this.$forceUpdate();
                    
                    // Add animate class for CSS transitions
                    setTimeout(() => {
                        header.classList.add("animate");
                    }, 50);
                }
            });
        },

        setupAnimations() {
            // Always show header first
            setTimeout(() => {
                this.animateHeader();
            }, 100);

            if (!this.enableAnimations) return;

            this.$nextTick(() => {
                // Clean up existing observer
                if (this.observer) {
                    this.observer.disconnect();
                }

                // Set up intersection observer for news items
                this.observer = new IntersectionObserver(
                    (entries) => {
                        entries.forEach((entry) => {
                            if (entry.isIntersecting) {
                                entry.target.classList.add("animate");
                            }
                        });
                    },
                    { threshold: 0.1, rootMargin: "50px" }
                );

                // Observe news items
                const newsItems = this.$el?.querySelectorAll(".news-item");
                if (newsItems) {
                    newsItems.forEach((el) => {
                        this.observer.observe(el);
                    });
                }
            });
        },

        handleResize() {
            // Force grid recalculation on resize
            this.$forceUpdate();
        },
    },

    mounted() {
        // Add resize listener
        window.addEventListener('resize', this.handleResize);
        
        this.fetchData().then(() => {
            // Ensure DOM is ready before setting up animations
            this.$nextTick(() => {
                setTimeout(() => {
                    this.setupAnimations();
                }, 100);
            });
        });
    },

    beforeDestroy() {
        // Clean up
        if (this.observer) {
            this.observer.disconnect();
        }
        window.removeEventListener('resize', this.handleResize);
    },

    updated() {
        // Re-setup animations when items change
        if (this.activeNewsItems.length > 0) {
            this.$nextTick(() => {
                this.setupAnimations();
            });
        }
    },
};
</script>

<style scoped>
.dynamic-news-events {
    position: relative;
    overflow: hidden;
}

.container {
    width: 100%;
}

.section-header {
    transition: all 0.6s ease;
}

.section-header.animate {
    opacity: 1 !important;
    transform: translateY(0) !important;
}

.news-grid {
    margin-top: 40px;
}

.news-item {
    background: #fff;
    border-radius: 15px;
    overflow: hidden;
    box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
    transition: all 0.3s ease;
    opacity: 0;
    transform: translateY(30px);
}

.news-item.animate {
    opacity: 1;
    transform: translateY(0);
    animation-name: fadeInUp;
    animation-duration: 0.6s;
    animation-fill-mode: forwards;
}

.news-item:hover {
    transform: translateY(-10px);
    box-shadow: 0 15px 30px rgba(0, 0, 0, 0.15);
}

.news-image {
    position: relative;
}

.card-background-image {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    object-fit: cover;
    z-index: 1;
}

.image-content {
    position: relative;
    z-index: 2;
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
}

.news-icon {
    font-size: 3rem;
    text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.news-content h3 {
    line-height: 1.3;
}

.news-content p {
    opacity: 0.9;
}

.news-link:hover {
    text-decoration: underline;
}

/* Loading State */
.loading-state {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 60px 20px;
    color: #6b7280;
}

.loading-spinner {
    width: 40px;
    height: 40px;
    border: 3px solid #f3f4f6;
    border-top: 3px solid #ff7101;
    border-radius: 50%;
    animation: spin 1s linear infinite;
    margin-bottom: 16px;
}

@keyframes spin {
    0% {
        transform: rotate(0deg);
    }
    100% {
        transform: rotate(360deg);
    }
}

/* Error State */
.error-state {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 60px 20px;
    color: #6b7280;
    text-align: center;
}

.error-icon {
    font-size: 48px;
    margin-bottom: 16px;
}

.error-state h3 {
    margin: 0 0 8px 0;
    color: #dc2626;
    font-size: 18px;
}

.error-state p {
    margin: 0 0 20px 0;
    font-size: 14px;
}

.retry-btn {
    background: #ff7101;
    color: white;
    border: none;
    padding: 10px 20px;
    border-radius: 6px;
    cursor: pointer;
    font-weight: 500;
    transition: background 0.2s;
}

.retry-btn:hover {
    background: #e56500;
}

/* Empty State */
.empty-state {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 60px 20px;
    color: #6b7280;
    text-align: center;
}

.empty-icon {
    font-size: 48px;
    margin-bottom: 16px;
}

.empty-state h3 {
    margin: 0 0 8px 0;
    color: #374151;
    font-size: 18px;
}

.empty-state p {
    margin: 0;
    font-size: 14px;
}

/* Custom animations */
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Responsive Design */
@media (max-width: 768px) {
    .news-grid {
        grid-template-columns: 1fr !important;
        gap: 20px !important;
    }

    .news-item {
        margin-bottom: 0;
    }

    .section-header {
        text-align: center !important;
        margin-bottom: 40px;
    }

    .container {
        padding: 0 16px !important;
    }

    .section-header h2 {
        font-size: 2rem !important;
    }

    .section-header p {
        font-size: 1rem !important;
    }
}

@media (max-width: 480px) {
    .news-image {
        height: 150px !important;
    }

    .news-icon {
        font-size: 2rem !important;
    }

    .news-content {
        padding: 20px !important;
    }

    .section-header h2 {
        font-size: 1.8rem !important;
    }

    .section-header {
        margin-bottom: 30px !important;
    }
}

/* Fallback for users who prefer reduced motion */
@media (prefers-reduced-motion: reduce) {
    .news-item,
    .section-header {
        opacity: 1 !important;
        transform: none !important;
        animation: none !important;
        transition: none !important;
    }
}
</style>