<template>
    <section class="hero-section" v-if="heroData && heroData.is_active">
        <!-- Dynamic backgrounds -->
        <div
            v-for="(background, index) in backgroundSources"
            :key="index"
            class="hero-background"
            :class="{ active: index === currentSlide }"
            :style="getBackgroundStyle(background, index)"
        ></div>

        <div class="hero-overlay"></div>

        <!-- Dynamic navigation (if enabled) -->
        <div
            v-if="heroData.show_navigation && heroData.navigation"
            class="hero-nav"
            :class="{ scrolled: isScrolled }"
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
                    <span v-else>{{
                        heroData.navigation.logo?.text || "Logo"
                    }}</span>
                </a>

                <div
                    class="menu-toggle"
                    aria-label="Toggle menu"
                    title="Open menu"
                    @click="toggleMenu"
                >
                    <span></span><span></span><span></span>
                </div>

                <nav
                    class="nav-links"
                    :class="{ active: menuActive }"
                    aria-label="Primary"
                >
                    
                    <div
                        class="dropdown"
                        v-for="(item, index) in processedMenuItems"
                        :key="index"
                    >
                        <a
                            :href="item.url || '#'"
                            class="drop-toggle"
                            @click.prevent="toggleDropdown(index)"
                            >{{ item.title }}
                            <span v-if="item.sub_items?.length"> ▾</span></a
                        >
                        <div
                            class="dropdown-content"
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
                                >{{ subItem.text || subItem }}</a
                            >
                        </div>
                    </div>
                </nav>
            </div>
        </div>

        <!-- Dynamic hero content -->
        <div class="hero-content container">
            <div class="hero-text">
                <h2 class="hero-title">
                    {{ heroData.title || "Welcome To" }}
                    <span
                        v-if="heroData.title_highlight"
                        style="color: #ff7101"
                    >
                        {{ heroData.title_highlight }}</span
                    >
                </h2>
                <h3 class="hero-subtitle">
                    {{ heroData.subtitle || "A Complete ERP System" }}
                </h3>
                <h1 class="hero-tagline">
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
                        :class="index === 0 ? 'btn-apply' : 'btn-learn'"
                        :href="button.url || '#'"
                        :target="button.target || '_self'"
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

            // Fallback defaults (matches your original component)
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
            if (background.type === "image") {
                return {
                    backgroundImage: `url("${background.source}")`,
                    backgroundSize: "cover",
                    backgroundPosition: "center",
                };
            } else {
                return {
                    background: background.source,
                };
            }
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
    min-height: 80vh;
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

.hero-background {
    animation: zoomOut 8s ease-out infinite;
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
    background: transparent;
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
}

.nav-links {
    display: flex;
    gap: 16px;
    align-items: center;
    transition: all 0.5s ease-in-out;
}

.nav-links a,
.dropdown > a {
    color: #fff;
    text-decoration: none;
    font-weight: 400;
    padding: 6px 6px;
    border-radius: 2px;
    transition: color 0.2s ease-in-out;
}

.nav-links a:hover,
.dropdown > a:hover {
    color: #d35b00;
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
    background: #fff;
    color: #000;
    border-radius: 2px;
    display: none;
    box-shadow: 0 6px 18px rgba(2, 10, 30, 0.25);
    border-top: 3px solid #ff7101;
}

.dropdown-content a {
    display: block;
    padding: 10px 16px;
    color: #111;
    text-decoration: none;
    border-bottom: 1px solid #eee;
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
}

.menu-toggle span {
    display: block;
    height: 3px;
    width: 26px;
    background: #ff7101;
    border-radius: 3px;
}

/* Hero content */
.hero-content {
    position: relative;
    z-index: 920;
    min-height: 80vh;
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
    color: #21bf6b;
    font-size: 1.5rem;
    font-weight: 600;
    margin-bottom: 12px;
}

.hero-tagline {
    font-size: 2.2rem;
    color: #f7b731;
    font-weight: 800;
    line-height: 1.05;
    margin-bottom: 18px;
}

.hero-ctas {
    display: flex;
    gap: 12px;
}

.btn-apply {
    background: #20bf6b;
    border: 2px solid #20bf6b;
    color: #fff;
    padding: 10px 28px;
    border-radius: 6px;
    text-decoration: none;
    font-weight: 500;
    text-align: center;
    transition: all 0.3s ease;
}

.btn-apply:hover {
    background: transparent;
    color: #20bf6b;
    transform: translateY(-2px);
}

.btn-learn {
    background: transparent;
    border: 2px solid #ff7101;
    color: #ff7101;
    padding: 10px 28px;
    border-radius: 6px;
    text-decoration: none;
    font-weight: 500;
    text-align: center;
    transition: all 0.3s ease;
}

.btn-learn:hover {
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
        color: #fff;
        padding: 8px 10px;
    }
    .dropdown-content {
        position: static;
        display: none;
        margin-top: 5px;
        background: rgba(0, 0, 0, 0.2);
        border-top: none;
    }
    .dropdown-content a {
        color: #fff;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
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
        padding: 80px 16px 50px;
    }
    .hero-ctas {
        flex-direction: column;
    }
    .btn-apply,
    .btn-learn {
        width: 100%;
    }
}
</style>
