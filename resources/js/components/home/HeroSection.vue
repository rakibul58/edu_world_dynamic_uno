<template>
    <section
        class="hero-section"
        v-if="heroData && heroData.is_active"
        :style="getSectionStyles()"
    >
        <!-- Dynamic backgrounds -->
        <div
            v-for="(background, index) in backgroundSources"
            :key="index"
            class="hero-background"
            :class="{
                active: index === currentSlide,
                'zoom-effect':
                    heroData.advanced_settings?.enable_zoom_effect !== false,
            }"
            :style="getBackgroundStyle(background, index)"
        ></div>

        <div class="hero-overlay" :style="getOverlayStyles()"></div>

        <!-- Dynamic navigation (if enabled) -->
        <div
            v-if="heroData.show_navigation && heroData.navigation"
            class="hero-nav"
            :class="{ scrolled: isScrolled }"
            :style="getNavStyles()"
        >
            <div class="hero-nav-inner">
                <a class="brand" :href="heroData.navigation.logo?.url || '#'">
                    <img
                        v-if="
                            heroData.navigation.logo?.type === 'image' &&
                            heroData.navigation.logo?.image_path
                        "
                        :src="
                            getLogoImageUrl(heroData.navigation.logo.image_path)
                        "
                        :alt="heroData.navigation.logo?.alt || 'Logo'"
                    />
                    <span v-else :style="getLogoTextStyles()">{{
                        heroData.navigation.logo?.text || "Logo"
                    }}</span>
                </a>

                <div
                    class="menu-toggle"
                    aria-label="Toggle menu"
                    title="Open menu"
                    @click="toggleMenu"
                    :style="getMenuToggleStyles()"
                >
                    <span></span><span></span><span></span>
                </div>

                <nav
                    class="nav-links"
                    :class="{ active: menuActive }"
                    aria-label="Primary"
                    :style="getNavLinksStyles()"
                >
                    <div
                        class="dropdown"
                        v-for="(item, index) in processedMenuItems"
                        :key="index"
                    >
                        <a
                            :href="item.url || '#'"
                            class="drop-toggle"
                            :style="getNavLinkStyles()"
                            @mouseover="hoverNavLink($event, true)"
                            @mouseleave="hoverNavLink($event, false)"
                            @click.prevent="toggleDropdown(index)"
                            >{{ item.title }}
                            <span v-if="item.sub_items?.length"> ▾</span></a
                        >
                        <div
                            class="dropdown-content"
                            :style="getDropdownStyles()"
                            v-if="
                                (item.showDropdown || !isMobile) &&
                                item.sub_items?.length
                            "
                        >
                            <a
                                v-for="(subItem, subIndex) in item.sub_items"
                                :key="subIndex"
                                :href="subItem.url || '#'"
                                :target="subItem.target || '_self'"
                                :style="getDropdownItemStyles()"
                                @mouseover="hoverDropdownItem($event, true)"
                                @mouseleave="hoverDropdownItem($event, false)"
                                >{{ subItem.text || subItem }}</a
                            >
                        </div>
                    </div>
                </nav>
            </div>
        </div>

        <!-- Dynamic hero content -->
        <div class="hero-content container" :style="getContentStyles()">
            <div class="hero-text">
                <h2 class="hero-title" :style="getTitleStyles()">
                    {{ heroData.title || "Welcome To" }}
                    <span
                        v-if="heroData.title_highlight"
                        :style="getTitleHighlightStyles()"
                    >
                        {{ heroData.title_highlight }}</span
                    >
                </h2>
                <h3 class="hero-subtitle" :style="getSubtitleStyles()">
                    {{ heroData.subtitle || "A Complete ERP System" }}
                </h3>
                <h1 class="hero-tagline" :style="getTaglineStyles()">
                    {{
                        heroData.tagline ||
                        "A complete system for educational institution management."
                    }}
                </h1>
                <div class="hero-ctas">
                    <a
                        v-for="(button, index) in heroData.cta_buttons ||
                        defaultButtons"
                        :key="index"
                        :class="getButtonClass(button, index)"
                        :href="button.url || '#'"
                        :target="button.target || '_self'"
                        :style="getButtonStyles(button)"
                        @mouseover="hoverButton($event, button, true)"
                        @mouseleave="hoverButton($event, button, false)"
                        >{{ button.text }}</a
                    >
                </div>
            </div>
        </div>
    </section>
</template>

<script>
import axios from "axios";

export default {
    name: "DynamicHeroSection",
    data() {
        return {
            heroData: null,
            loading: false,
            isScrolled: false,
            menuActive: false,
            isMobile: false,
            currentSlide: 0,
            sliderInterval: null,
            processedMenuItems: [],

            // Fallback defaults
            defaultButtons: [
                {
                    text: "Apply now",
                    url: "#",
                    type: "primary",
                },
                {
                    text: "Learn more",
                    url: "#",
                    type: "secondary",
                },
            ],
        };
    },
    computed: {
        backgroundSources() {
            if (!this.heroData) return [];

            // Use images if available, otherwise use gradients
            if (this.heroData.background_images?.length) {
                return this.heroData.background_images.map((img, index) => {
                    let imageUrl;

                    if (typeof img === "string") {
                        imageUrl = img;
                    } else if (img.url) {
                        imageUrl = img.url;
                    } else {
                        imageUrl = img;
                    }

                    // Get corresponding gradient as fallback
                    const gradients = this.heroData.background_gradients || [
                        "linear-gradient(135deg, #ff7101, #102e4a)",
                        "linear-gradient(135deg, #20bf6b, #102e4a)",
                        "linear-gradient(135deg, #f7b731, #102e4a)",
                        "linear-gradient(135deg, #102e4a, #030811)",
                        "linear-gradient(135deg, #d35b00, #102e4a)",
                    ];

                    const fallbackGradient =
                        gradients[index % gradients.length];

                    return {
                        type: "image",
                        source: imageUrl,
                        fallbackGradient: fallbackGradient,
                        alt: img.alt || "Hero background image",
                    };
                });
            }

            // Fallback to gradients only
            const gradients = this.heroData.background_gradients || [
                "linear-gradient(135deg, #ff7101, #102e4a)",
                "linear-gradient(135deg, #20bf6b, #102e4a)",
                "linear-gradient(135deg, #f7b731, #102e4a)",
                "linear-gradient(135deg, #102e4a, #030811)",
                "linear-gradient(135deg, #d35b00, #102e4a)",
            ];

            return gradients.map((gradient) => ({
                type: "gradient",
                source: gradient,
            }));
        },
    },

    async mounted() {
        await this.fetchHeroData();
        this.setupEventListeners();
        this.processMenuItems();
        // Wait for next tick to ensure DOM is ready
        this.$nextTick(() => {
            this.startSlider();
        });
    },

    beforeUnmount() {
        this.cleanupEventListeners();
        if (this.sliderInterval) {
            clearInterval(this.sliderInterval);
        }
    },

    methods: {
        async fetchHeroData() {
            this.loading = true;
            try {
                const response = await axios.get("/hero-sections/active");
                if (response.data.success) {
                    this.heroData = response.data.data;
                }
            } catch (error) {
                console.error("Error fetching hero data:", error);
                // Use fallback data if needed
                this.setFallbackData();
            } finally {
                this.loading = false;
            }
        },

        setFallbackData() {
            // Fallback to match your original hardcoded design
            this.heroData = {
                is_active: true,
                show_navigation: true,
                title: "Welcome To",
                title_highlight: "Edu World",
                subtitle: "A Complete ERP System",
                tagline:
                    "A complete system for educational institution management.",
                background_gradients: [
                    "linear-gradient(135deg, #ff7101, #102e4a)",
                    "linear-gradient(135deg, #20bf6b, #102e4a)",
                    "linear-gradient(135deg, #f7b731, #102e4a)",
                    "linear-gradient(135deg, #102e4a, #030811)",
                    "linear-gradient(135deg, #d35b00, #102e4a)",
                ],
                cta_buttons: this.defaultButtons,
                slider_interval: 3500,
                enable_slider: true,
                text_styles: {
                    title: {
                        color: "#ffffff",
                        fontSize: "1.9rem",
                        fontWeight: "700",
                        marginBottom: "8px",
                    },
                    title_highlight: { color: "#ff7101" },
                    subtitle: {
                        color: "#20bf6b",
                        fontSize: "1.5rem",
                        fontWeight: "600",
                        marginBottom: "12px",
                    },
                    tagline: {
                        color: "#f7b731",
                        fontSize: "2.2rem",
                        fontWeight: "800",
                        lineHeight: "1.05",
                        marginBottom: "18px",
                    },
                },
                nav_styles: {
                    background: "transparent",
                    link_color: "#fff",
                    link_hover_color: "#d35b00",
                    mobile_background: "rgba(0, 30, 60, 0.95)",
                    dropdown_background: "#fff",
                    scrolled_background: "rgba(0, 30, 60, 0.95)",
                    dropdown_border_color: "#ff7101",
                    dropdown_text_color: "#111",
                    dropdown_hover_color: "#ff7101",
                },
                overlay_styles: {
                    background:
                        "linear-gradient(135deg, rgba(6, 6, 7, 0.8), rgba(0, 30, 60, 0.7))",
                    opacity: "1",
                },
                section_styles: {
                    height: "60vh",
                    display: "block",
                    overflow: "hidden",
                },
                advanced_settings: {
                    enable_animations: true,
                    transition_duration: "1s",
                    enable_zoom_effect: true,
                    zoom_duration: "8s",
                },
            };
        },

        setupEventListeners() {
            window.addEventListener("scroll", this.handleScroll);
            window.addEventListener("resize", this.checkMobile);
            this.checkMobile();
        },

        cleanupEventListeners() {
            window.removeEventListener("scroll", this.handleScroll);
            window.removeEventListener("resize", this.checkMobile);
        },

        handleScroll() {
            this.isScrolled = window.scrollY > 50;
        },

        checkMobile() {
            this.isMobile = window.innerWidth < 992;
        },

        toggleMenu() {
            this.menuActive = !this.menuActive;
        },

        toggleDropdown(index) {
            if (this.isMobile) {
                this.processedMenuItems.forEach((item, i) => {
                    if (i !== index) {
                        item.showDropdown = false;
                    }
                });
                this.processedMenuItems[index].showDropdown =
                    !this.processedMenuItems[index].showDropdown;
            }
        },

        processMenuItems() {
            if (!this.heroData?.navigation?.menu_items) {
                this.processedMenuItems = [];
                return;
            }

            this.processedMenuItems = this.heroData.navigation.menu_items.map(
                (item) => ({
                    ...item,
                    showDropdown: false,
                })
            );
        },

        getBackgroundStyle(background, index) {
            const styles = {};

            if (background.type === "image") {
                styles.backgroundImage = `url("${background.source}")`;
                styles.backgroundSize = "cover";
                styles.backgroundPosition = "center";

                // Add animation duration if enabled
                if (this.heroData.advanced_settings?.enable_animations) {
                    styles.transitionDuration =
                        this.heroData.advanced_settings.transition_duration ||
                        "1s";
                }
            } else {
                styles.background = background.source;
            }

            return styles;
        },

        startSlider() {
            if (
                !this.heroData?.enable_slider ||
                this.backgroundSources.length <= 1
            )
                return;

            const slides = this.$el.querySelectorAll(".hero-background");
            if (!slides.length) return;

            const interval = this.heroData.slider_interval || 3500;

            this.sliderInterval = setInterval(() => {
                this.currentSlide =
                    (this.currentSlide + 1) % this.backgroundSources.length;
            }, interval);
        },

        getLogoImageUrl(imagePath) {
            if (!imagePath) return "";

            // If it's already a full URL, return as is
            if (imagePath.startsWith("http")) {
                return imagePath;
            }

            // If it starts with /, use as is
            if (imagePath.startsWith("/")) {
                return imagePath;
            }

            // Otherwise, prepend with /
            return "/" + imagePath;
        },

        // Style getter methods
        getSectionStyles() {
            const styles = this.heroData?.section_styles || {};

            // Apply dynamic zoom duration if set
            if (this.heroData?.advanced_settings?.zoom_duration) {
                styles["--zoom-duration"] =
                    this.heroData.advanced_settings.zoom_duration;
            }

            // Ensure reasonable default height
            if (!styles.minHeight && !styles.height) {
                styles.height = "60vh";
            }
            return styles;
        },

        getOverlayStyles() {
            const overlayStyles = this.heroData?.overlay_styles || {};
            const styles = { ...overlayStyles };

            // Apply opacity if set
            if (overlayStyles.opacity) {
                styles.opacity = overlayStyles.opacity;
            }

            return styles;
        },

        getNavStyles() {
            const styles = {};
            const navStyles = this.heroData?.nav_styles;

            if (navStyles) {
                if (!this.isScrolled) {
                    styles.background = navStyles.background || "transparent";
                } else {
                    styles.background =
                        navStyles.scrolled_background ||
                        "rgba(0, 30, 60, 0.95)";
                }

                // Apply transition duration if set
                if (this.heroData?.advanced_settings?.transition_duration) {
                    styles.transition = `background ${this.heroData.advanced_settings.transition_duration} ease`;
                }
            }

            return styles;
        },

        getNavLinksStyles() {
            const styles = {};
            const navStyles = this.heroData?.nav_styles;

            if (this.isMobile && navStyles?.mobile_background) {
                styles.background = navStyles.mobile_background;
            }

            return styles;
        },

        getNavLinkStyles() {
            const styles = {};
            const navStyles = this.heroData?.nav_styles;

            if (navStyles?.link_color) {
                styles.color = navStyles.link_color;
            }

            // Add transition for smooth hover effects
            if (this.heroData?.advanced_settings?.transition_duration) {
                styles.transition = `color ${this.heroData.advanced_settings.transition_duration} ease`;
            }

            return styles;
        },

        getDropdownStyles() {
            const styles = {};
            const navStyles = this.heroData?.nav_styles;

            if (navStyles?.dropdown_background) {
                styles.background = navStyles.dropdown_background;
            }
            if (navStyles?.dropdown_border_color) {
                styles.borderTop = `3px solid ${navStyles.dropdown_border_color}`;
            }

            return styles;
        },

        getDropdownItemStyles() {
            const styles = {};
            const navStyles = this.heroData?.nav_styles;

            // Use dynamic dropdown text color or fallback
            styles.color = navStyles?.dropdown_text_color || "#111";

            // Add transition for smooth hover effects
            if (this.heroData?.advanced_settings?.transition_duration) {
                styles.transition = `color ${this.heroData.advanced_settings.transition_duration} ease`;
            }

            return styles;
        },

        getLogoTextStyles() {
            const navStyles = this.heroData?.nav_styles;
            return {
                color: navStyles?.link_color || "#fff",
                fontWeight: "bold",
                fontSize: "1.2rem",
            };
        },

        getMenuToggleStyles() {
            const navStyles = this.heroData?.nav_styles;
            const styles = {};

            // Use link_color for menu toggle or fallback
            if (navStyles?.link_color) {
                styles["--toggle-color"] = navStyles.link_color;
            }

            return styles;
        },

        getContentStyles() {
            // Use section styles for content padding if available
            const sectionStyles = this.heroData?.section_styles || {};
            const styles = {};

            if (sectionStyles.padding) {
                styles.padding = sectionStyles.padding;
            } else {
                // Default padding
                styles.padding = "110px 20px 80px";
            }

            return styles;
        },

        getTitleStyles() {
            return this.heroData?.text_styles?.title || {};
        },

        getTitleHighlightStyles() {
            return (
                this.heroData?.text_styles?.title_highlight || {
                    color: "#ff7101",
                }
            );
        },

        getSubtitleStyles() {
            return this.heroData?.text_styles?.subtitle || {};
        },

        getTaglineStyles() {
            return this.heroData?.text_styles?.tagline || {};
        },

        getButtonClass(button, index) {
            // Use type-based classes for fallback styling
            if (button.type === "primary") return "btn-primary";
            if (button.type === "secondary") return "btn-secondary";

            // Fallback to index-based classes
            return index === 0 ? "btn-primary" : "btn-secondary";
        },

        getButtonStyles(button) {
            const styles = { ...button.styles } || {};

            // Add transition for smooth hover effects
            if (this.heroData?.advanced_settings?.transition_duration) {
                styles.transition = `all ${this.heroData.advanced_settings.transition_duration} ease`;
            }

            return styles;
        },

        hoverButton(event, button, isHover) {
            if (isHover && button.hover_styles) {
                Object.assign(event.target.style, button.hover_styles);
            } else if (button.styles) {
                // Reset to original styles
                Object.assign(event.target.style, button.styles);
            }
        },

        hoverNavLink(event, isHover) {
            const navStyles = this.heroData?.nav_styles;
            if (navStyles) {
                if (isHover && navStyles.link_hover_color) {
                    event.target.style.color = navStyles.link_hover_color;
                } else if (navStyles.link_color) {
                    event.target.style.color = navStyles.link_color;
                }
            }
        },

        hoverDropdownItem(event, isHover) {
            const navStyles = this.heroData?.nav_styles;
            if (navStyles) {
                if (isHover && navStyles.dropdown_hover_color) {
                    event.target.style.color = navStyles.dropdown_hover_color;
                } else if (navStyles.dropdown_text_color) {
                    event.target.style.color = navStyles.dropdown_text_color;
                } else {
                    event.target.style.color = "#111"; // fallback
                }
            }
        },
    },

    watch: {
        heroData: {
            handler() {
                this.processMenuItems();
                this.$nextTick(() => {
                    if (this.sliderInterval) {
                        clearInterval(this.sliderInterval);
                    }
                    this.startSlider();
                    this.setupDropdownStyles(); // Add this line
                });
            },
            deep: true,
        },
    },
};
</script>

<style scoped>
.container {
    max-width: 1240px;
    margin: 0 auto;
    padding: 0 20px;
}

.hero-section {
    position: relative;
    height: 60vh;
    display: block;
    overflow: hidden;
}

.hero-background {
    position: absolute;
    inset: 0;
    background-size: cover;
    background-position: center;
    z-index: 0;
    opacity: 0;
    transition: opacity 1s ease-in-out;
    transform-origin: center center;
}

.hero-background.active {
    opacity: 1;
}

.hero-background.zoom-effect {
    animation: zoomOut var(--zoom-duration, 8s) ease-out infinite;
}

/* Fallback gradients for when no images are available */
.hero-background:nth-child(1) {
    background: linear-gradient(135deg, #ff7101, #102e4a);
}
.hero-background:nth-child(2) {
    background: linear-gradient(135deg, #20bf6b, #102e4a);
}
.hero-background:nth-child(3) {
    background: linear-gradient(135deg, #f7b731, #102e4a);
}
.hero-background:nth-child(4) {
    background: linear-gradient(135deg, #102e4a, #030811);
}
.hero-background:nth-child(5) {
    background: linear-gradient(135deg, #d35b00, #102e4a);
}

.hero-overlay {
    position: absolute;
    inset: 0;
    background: linear-gradient(
        135deg,
        rgba(6, 6, 7, 0.8),
        rgba(0, 30, 60, 0.7)
    );
    z-index: 1;
}

@keyframes zoomOut {
    0% {
        transform: scale(1.08);
    }
    100% {
        transform: scale(1);
    }
}

/* Hero nav */
.hero-nav {
    position: absolute;
    top: 20px;
    left: 0;
    right: 0;
    z-index: 950;
    padding: 0 20px;
    pointer-events: auto;
    transition: background 0.3s ease;
}

.hero-nav.scrolled {
    background: rgba(0, 30, 60, 0.95);
}

.hero-nav-inner {
    max-width: 1240px;
    margin: 0 auto;
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.hero-nav .brand {
    display: none;
}

.hero-nav .brand img {
    width: 150px;
    height: auto;
}

.nav-links {
    display: flex;
    gap: 16px;
    align-items: center;
    transition: all 0.5s ease-in-out;
}

.nav-links a,
.dropdown > a {
    text-decoration: none;
    font-weight: 400;
    padding: 6px 6px;
    border-radius: 2px;
    transition: color 0.2s ease-in-out;
}

/* dropdown (desktop hover) */
.dropdown {
    position: relative;
}

.dropdown-content {
    position: absolute;
    top: calc(100% + 6px);
    left: 0;
    min-width: 210px;
    border-radius: 2px;
    display: none;
    box-shadow: 0 6px 18px rgba(2, 10, 30, 0.25);
    z-index: 1000;
}

.dropdown-content a {
    display: block;
    padding: 10px 16px;
    text-decoration: none;
    border-bottom: 1px solid #eee;
    transition: color 0.2s ease-in-out;
}

.dropdown:hover .dropdown-content {
    display: block;
}

/* hamburger */
.menu-toggle {
    display: none;
    flex-direction: column;
    gap: 5px;
    cursor: pointer;
    padding: 8px;
}

.menu-toggle span {
    display: block;
    height: 3px;
    width: 26px;
    background: var(--toggle-color, #ff7101);
    border-radius: 3px;
    transition: all 0.3s ease;
}

/* Hero content */
.hero-content {
    position: relative;
    z-index: 920;
    height: 60vh;
    display: flex;
    align-items: center;
    padding: 110px 20px 80px;
}

.hero-text {
    max-width: 780px;
    color: #fff;
}

.hero-title {
    font-size: 1.9rem;
    font-weight: 700;
    margin-bottom: 8px;
}

.hero-subtitle {
    font-size: 1.5rem;
    font-weight: 600;
    margin-bottom: 12px;
}

.hero-tagline {
    font-size: 2.2rem;
    font-weight: 800;
    line-height: 1.05;
    margin-bottom: 18px;
}

.hero-ctas {
    display: flex;
    gap: 12px;
}

.btn-primary,
.btn-secondary {
    border: 2px solid;
    padding: 10px 28px;
    border-radius: 6px;
    text-decoration: none;
    font-weight: 500;
    text-align: center;
    transition: all 0.3s ease;
    cursor: pointer;
}

/* Default fallback styles - will be overridden by dynamic styles */
.btn-primary {
    background: #20bf6b;
    border-color: #20bf6b;
    color: #fff;
}

.btn-primary:hover {
    background: transparent;
    color: #20bf6b;
    transform: translateY(-2px);
}

.btn-secondary {
    background: transparent;
    border-color: #ff7101;
    color: #ff7101;
}

.btn-secondary:hover {
    background: #ff7101;
    color: #fff;
    transform: translateY(-2px);
}

@media (max-width: 991px) {
    .hero-nav .brand {
        display: block;
    }
    .menu-toggle {
        display: flex;
    }
    .nav-links {
        position: fixed;
        top: 72px;
        right: 18px;
        display: none;
        flex-direction: column;
        background: rgba(0, 30, 60, 0.95);
        padding: 16px;
        border-radius: 10px;
        gap: 6px;
        min-width: 200px;
        width: fit-content;
        z-index: 960;
    }
    .nav-links.active {
        display: flex;
    }
    .nav-links a {
        padding: 8px 10px;
    }
    .dropdown-content {
        position: static;
        display: none;
        margin-top: 5px;
        background: rgba(0, 0, 0, 0.2);
        border-top: none;
        box-shadow: none;
    }
    .dropdown-content a {
        color: #fff; /* Will be overridden by JavaScript */
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    }
    /* Mobile dropdown hover will be handled by JavaScript */

    .hero-content {
        padding: 80px 20px 60px;
        height: 60vh;
    }
}

@media (max-width: 768px) {
    .hero-tagline {
        font-size: 1.8rem;
    }
    .hero-subtitle {
        font-size: 1.15rem;
    }
    .hero-content {
        padding: 60px 16px 40px;
        min-height: 70vh;
    }
    .hero-ctas {
        flex-direction: column;
    }
    .btn-primary,
    .btn-secondary {
        width: 100%;
    }

    .hero-section {
        min-height: 70vh;
    }
}

@media (max-width: 480px) {
    .hero-content {
        padding: 50px 12px 30px;
        height: 60vh;
    }

    .hero-section {
        height: 60vh;
    }

    .hero-title {
        font-size: 1.6rem;
    }

    .hero-subtitle {
        font-size: 1.1rem;
    }

    .hero-tagline {
        font-size: 1.5rem;
    }
}
</style>
