<template>
    <nav
        class="navbar-top"
        :style="navStyles"
        v-if="headerData && headerData.is_active"
    >
        <div class="container nav-container">
            <a class="nav-logo" :href="headerData.logo_link.url || '#'">
                <img
                    v-if="
                        headerData.logo_type === 'image' &&
                        headerData.logo_image_path
                    "
                    :src="headerData.logo_image_path"
                    :alt="headerData.logo_text || 'Site Logo'"
                    :style="logoImageStyles"
                />
                <span v-else :style="logoTextStyles">{{
                    headerData.logo_text || "Your Brand"
                }}</span>
                <small v-if="headerData.logo_tagline" :style="taglineStyles">
                    {{ headerData.logo_tagline }}
                </small>
            </a>
            <div class="top-actions">
                <a
                    v-for="(button, index) in headerData.buttons"
                    :key="index"
                    :href="button.url"
                    class="nav-btn"
                    :class="`nav-btn-${button.style}`"
                    :style="getButtonStyles(button)"
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
        };
    },
    computed: {
        navStyles() {
            return this.headerData?.nav_styles || { background: "#102e4a" };
        },
        logoImageStyles() {
            return (
                this.headerData?.logo_styles?.image || {
                    width: "180px",
                    height: "auto",
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
        getButtonStyles(button) {
            const custom = button.custom_styles || {};
            return {
                backgroundColor:
                    custom.background_color ||
                    this.getDefaultButtonColor(button.style),
                color: custom.text_color || "#ffffff",
                border: `2px solid ${
                    custom.border_color ||
                    custom.background_color ||
                    this.getDefaultButtonColor(button.style)
                }`,
                borderRadius: custom.border_radius || "5px",
            };
        },
        getDefaultButtonColor(style) {
            const colors = {
                primary: "#20bf6b",
                secondary: "#ff7101",
                outlined: "transparent",
                ghost: "transparent",
            };
            return colors[style] || "#3b82f6";
        },
    },
};
</script>

<style scoped>
.navbar-top {
    width: 100%;
    padding: 0 10px;
    transition: all 0.3s ease;
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
}
.nav-logo small {
    font-size: 0.8em;
    opacity: 0.8;
}
.top-actions {
    display: flex;
    gap: 10px;
    align-items: center;
}
.nav-btn {
    text-decoration: none;
    padding: 8px 20px;
    font-weight: 500;
    font-size: 0.95rem;
    transition: all 0.3s ease;
    text-align: center;
    border-radius: 5px;
}
.nav-btn-primary:hover {
    background: transparent !important;
    color: inherit;
}
.nav-btn-secondary:hover {
    background: transparent !important;
    color: inherit;
}
.nav-btn-outlined:hover {
    background: #3b82f6 !important;
    color: white !important;
}
.nav-btn-ghost:hover {
    background: rgba(255, 255, 255, 0.1) !important;
}

@media (max-width: 991px) {
    .navbar-top {
        display: none;
    }
}
</style>
