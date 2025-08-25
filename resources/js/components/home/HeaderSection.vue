<template>
    <nav
        class="navbar-top"
        :style="navStyles"
        :class="{
            'sticky-header': headerData?.advanced_settings?.stickyHeader,
            'hide-mobile': headerData?.advanced_settings?.hideOnMobile,
            animated: headerData?.advanced_settings?.enableAnimations,
        }"
        v-if="headerData && headerData.is_active"
    >
        <div class="container nav-container">
            <a
                class="nav-logo"
                :href="headerData.logo_link?.url || '#'"
                :target="headerData.logo_link?.target || '_self'"
            >
                <img
                    v-if="
                        headerData.logo_type === 'image' &&
                        headerData.logo_image_path
                    "
                    :src="headerData.logo_image_path"
                    :alt="headerData.logo_text || 'Site Logo'"
                    :style="logoImageStyles"
                />
                <div v-else class="logo-text-container">
                    <span class="logo-text" :style="logoTextStyles">{{
                        headerData.logo_text || "Your Brand"
                    }}</span>
                    <small
                        v-if="headerData.logo_tagline"
                        class="logo-tagline"
                        :style="taglineStyles"
                    >
                        {{ headerData.logo_tagline }}
                    </small>
                </div>
            </a>
            <div class="top-actions">
                <a
                    v-for="(button, index) in headerData.buttons"
                    :key="index"
                    :href="button.url"
                    :target="button.target || '_self'"
                    class="nav-btn"
                    :class="[
                        `nav-btn-${button.style}`,
                        `nav-btn-${button.size || 'md'}`,
                    ]"
                    :style="getButtonStyles(button)"
                    @mouseenter="handleButtonHover($event, button)"
                    @mouseleave="handleButtonLeave($event, button)"
                >
                    {{ button.text }}
                </a>
            </div>
        </div>
    </nav>
</template>

<script>
import axios from "axios";
export default {
    name: "HeaderSection",
    data() {
        return {
            headerData: null,
            loading: false,
            isSticky: false,
        };
    },
    computed: {
        navStyles() {
            const styles = this.headerData?.nav_styles || {
                background: "#102e4a",
            };
            const advanced = this.headerData?.advanced_settings || {};

            return {
                ...styles,
                transition: advanced.enableAnimations
                    ? `all ${advanced.transitionDuration || "0.3s"} ease`
                    : "none",
                zIndex: advanced.zIndex || 1000,
            };
        },
        logoImageStyles() {
            return (
                this.headerData?.logo_styles?.image || {
                    width: "180px",
                    height: "auto",
                    maxHeight: "50px",
                }
            );
        },
        logoTextStyles() {
            return (
                this.headerData?.logo_styles?.text || {
                    color: "#fff",
                    fontSize: "24px",
                    fontWeight: "700",
                    fontFamily: "'Poppins', sans-serif",
                }
            );
        },
        taglineStyles() {
            return (
                this.headerData?.tagline_styles || {
                    color: "#d1d5db",
                    fontSize: "14px",
                }
            );
        },
    },
    async mounted() {
        await this.fetchHeader();
        this.setupStickyHeader();
    },
    methods: {
        async fetchHeader() {
            this.loading = true;
            try {
                const response = await axios.get("/header-sections/active");
                if (response.data.success) {
                    this.headerData = response.data.data;
                }
            } catch (error) {
                console.error("Error fetching header:", error);
            } finally {
                this.loading = false;
            }
        },

        setupStickyHeader() {
            if (this.headerData?.advanced_settings?.stickyHeader) {
                window.addEventListener("scroll", this.handleScroll);
            }
        },

        handleScroll() {
            this.isSticky = window.scrollY > 100;
        },

        getButtonStyles(button) {
            const custom = button.custom_styles || {};
            const size = this.getButtonSize(button.size || "md");

            let baseStyles = {
                ...size,
                borderRadius: custom.border_radius || "5px",
                opacity: custom.opacity || "1",
                transition: "all 0.3s ease",
                textDecoration: "none",
                display: "inline-block",
                fontWeight: "500",
            };

            // Apply different styles based on button type
            switch (button.style) {
                case "filled":
                    return {
                        ...baseStyles,
                        backgroundColor: custom.background_color || "#ff7101",
                        color: custom.text_color || "#ffffff",
                        border: `2px solid ${
                            custom.border_color ||
                            custom.background_color ||
                            "#ff7101"
                        }`,
                    };
                case "outlined":
                    return {
                        ...baseStyles,
                        backgroundColor: "transparent",
                        color:
                            custom.text_color ||
                            custom.border_color ||
                            "#ff7101",
                        border: `2px solid ${custom.border_color || "#ff7101"}`,
                    };
                case "ghost":
                    return {
                        ...baseStyles,
                        backgroundColor: "transparent",
                        color: custom.text_color || "#ffffff",
                        border: "2px solid transparent",
                    };
                case "transparent":
                    return {
                        ...baseStyles,
                        backgroundColor:
                            custom.background_color || "transparent",
                        color: custom.text_color || "#ffffff",
                        border: custom.border_color
                            ? `2px solid ${custom.border_color}`
                            : "2px solid transparent",
                    };
                default:
                    return {
                        ...baseStyles,
                        backgroundColor: custom.background_color || "#ff7101",
                        color: custom.text_color || "#ffffff",
                        border: `2px solid ${
                            custom.border_color ||
                            custom.background_color ||
                            "#ff7101"
                        }`,
                    };
            }
        },

        getButtonSize(size) {
            const sizes = {
                sm: { padding: "6px 12px", fontSize: "0.875rem" },
                md: { padding: "8px 16px", fontSize: "0.95rem" },
                lg: { padding: "10px 20px", fontSize: "1.1rem" },
            };
            return sizes[size] || sizes.md;
        },

        handleButtonHover(event, button) {
            const custom = button.custom_styles || {};
            const element = event.target;

            if (custom.hover_background_color) {
                element.style.backgroundColor = custom.hover_background_color;
            }
            if (custom.hover_text_color) {
                element.style.color = custom.hover_text_color;
            }
            if (custom.hover_border_color) {
                element.style.borderColor = custom.hover_border_color;
            }

            // Add subtle transform for better UX
            if (this.headerData?.advanced_settings?.enableAnimations) {
                element.style.transform = "translateY(-2px)";
            }
        },

        handleButtonLeave(event, button) {
            const element = event.target;
            const originalStyles = this.getButtonStyles(button);

            // Restore original styles
            element.style.backgroundColor = originalStyles.backgroundColor;
            element.style.color = originalStyles.color;
            element.style.borderColor = originalStyles.border.split(" ")[2]; // Extract color from border

            // Remove transform
            if (this.headerData?.advanced_settings?.enableAnimations) {
                element.style.transform = "translateY(0)";
            }
        },

        getDefaultButtonColor(style) {
            const colors = {
                filled: "#ff7101",
                outlined: "#ff7101",
                ghost: "#ffffff",
                transparent: "transparent",
            };
            return colors[style] || "#ff7101";
        },
    },

    beforeUnmount() {
        if (this.headerData?.advanced_settings?.stickyHeader) {
            window.removeEventListener("scroll", this.handleScroll);
        }
    },
};
</script>

<style scoped>
.navbar-top {
    width: 100%;
    padding: 0 10px;
    transition: all 0.3s ease;
    position: relative;
}

.navbar-top.sticky-header {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    z-index: 1000;
}

.navbar-top.animated {
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.nav-container {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 10px 0;
    max-width: 1200px;
    margin: 0 auto;
}

.nav-logo {
    display: flex;
    align-items: center;
    gap: 10px;
    text-decoration: none;
    transition: all 0.3s ease;
}

.nav-logo:hover {
    opacity: 0.9;
}

.logo-text-container {
    display: flex;
    flex-direction: column;
    align-items: flex-start;
}

.logo-text {
    line-height: 1.2;
}

.logo-tagline {
    font-size: 0.8em;
    opacity: 0.8;
    margin-top: 2px;
    line-height: 1.2;
}

.top-actions {
    display: flex;
    gap: 10px;
    align-items: center;
}

.nav-btn {
    text-decoration: none;
    font-weight: 500;
    text-align: center;
    border-radius: 5px;
    transition: all 0.3s ease;
    cursor: pointer;
    white-space: nowrap;
}

/* Button sizes */
.nav-btn-sm {
    padding: 6px 12px;
    font-size: 0.875rem;
}

.nav-btn-md {
    padding: 8px 16px;
    font-size: 0.95rem;
}

.nav-btn-lg {
    padding: 10px 20px;
    font-size: 1.1rem;
}

/* Button styles - base hover effects */
.nav-btn-filled:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}

.nav-btn-outlined:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

.nav-btn-ghost:hover {
    background: rgba(255, 255, 255, 0.1) !important;
}

.nav-btn-transparent:hover {
    background: rgba(255, 255, 255, 0.05) !important;
}

/* Animation classes */
.animated .nav-btn {
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.animated .nav-logo {
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

/* Mobile responsiveness */
@media (max-width: 991px) {
    .navbar-top.hide-mobile {
        display: none;
    }

    .nav-container {
        padding: 8px 0;
    }

    .top-actions {
        gap: 8px;
    }

    .nav-btn {
        padding: 6px 12px !important;
        font-size: 0.875rem !important;
    }

    .logo-text {
        font-size: 20px !important;
    }

    .logo-tagline {
        font-size: 0.75em !important;
    }
}

@media (max-width: 768px) {
    .navbar-top {
        padding: 0 5px;
    }

    .nav-container {
        flex-direction: column;
        gap: 12px;
        text-align: center;
    }

    .top-actions {
        flex-wrap: wrap;
        justify-content: center;
        gap: 8px;
    }

    .nav-btn {
        min-width: 80px;
    }
}

@media (max-width: 480px) {
    .top-actions {
        width: 100%;
    }

    .nav-btn {
        flex: 1;
        text-align: center;
        padding: 8px 12px !important;
    }

    .logo-text {
        font-size: 18px !important;
    }
}

/* Accessibility improvements */
.nav-btn:focus {
    outline: 2px solid #ff7101;
    outline-offset: 2px;
}

.nav-logo:focus {
    outline: 2px solid #ff7101;
    outline-offset: 2px;
    border-radius: 4px;
}

/* High contrast mode support */
@media (prefers-contrast: high) {
    .nav-btn {
        border-width: 2px !important;
    }
}

/* Reduced motion support */
@media (prefers-reduced-motion: reduce) {
    .navbar-top,
    .nav-btn,
    .nav-logo {
        transition: none !important;
    }

    .nav-btn:hover {
        transform: none !important;
    }
}

/* Print styles */
@media print {
    .navbar-top {
        background: white !important;
        color: black !important;
        box-shadow: none !important;
    }

    .nav-btn {
        border: 1px solid black !important;
        color: black !important;
        background: white !important;
    }
}
</style>
