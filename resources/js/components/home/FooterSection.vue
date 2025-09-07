<template>
    <footer class="footer" :style="getFooterStyle()">
        <div class="container">
            <div class="footer-content">
                <!-- Logo and Description Section -->
                <div class="footer-section">
                    <a class="nav-logo" href="#"
                        ><img
                            :src="
                                '/uploads/image/' + footerData?.logo_path ||
                                '/public/uploads/image/logo.png'
                            "
                            alt="Footer Logo"
                    /></a>
                    <p>{{ footerData?.tagline }}</p>
                    <p>
                        {{ footerData?.description }}
                    </p>
                    <div
                        class="footer-social"
                        v-if="filteredSocialLinks.length"
                    >
                        <a
                            v-for="(social, index) in filteredSocialLinks"
                            :key="index"
                            :href="social.url"
                            class="social-link"
                            :style="getSocialLinkStyle()"
                        >
                            <i :class="social.icon"></i>
                        </a>
                    </div>
                </div>

                <!-- Quick Links Section -->
                <div class="footer-section">
                    <h3 :style="getHeadingStyle()">Quick Links</h3>
                    <ul
                        class="footer-links"
                        v-if="
                            footerData?.quick_links &&
                            footerData.quick_links.length
                        "
                    >
                        <li
                            v-for="(link, index) in footerData.quick_links"
                            :key="index"
                        >
                            <a :href="link.url" :style="getLinkStyle()">{{
                                link.text
                            }}</a>
                        </li>
                    </ul>
                </div>

                <!-- Programs Section -->
                <div class="footer-section">
                    <h3 :style="getHeadingStyle()">Programs</h3>
                    <ul
                        class="footer-links"
                        v-if="
                            footerData?.programs && footerData.programs.length
                        "
                    >
                        <li
                            v-for="(program, index) in footerData.programs"
                            :key="index"
                        >
                            <a :href="program.url" :style="getLinkStyle()">{{
                                program.text
                            }}</a>
                        </li>
                    </ul>
                </div>

                <!-- Contact Info Section -->
                <div class="footer-section">
                    <h3 :style="getHeadingStyle()">Contact Info</h3>
                    <p :style="getTextStyle()">{{ footerData?.address }}</p>
                    <p :style="getTextStyle()">{{ footerData?.phone }}</p>
                    <p :style="getTextStyle()">{{ footerData?.email }}</p>
                    <p :style="getTextStyle()">
                        {{ footerData?.working_hours }}
                    </p>
                </div>
            </div>
            <div class="footer-bottom">
                <p :style="getTextStyle()">
                    &copy; 2025 Edu World. All rights reserved. | Privacy Policy
                    | Terms of Service
                </p>
            </div>
        </div>
    </footer>
</template>

<script>
import axios from "axios";

export default {
    name: "FooterSection",
    data() {
        return {
            footerData: null,
            isLoading: false,
            error: null,
        };
    },

    // ADD THIS COMPUTED SECTION - This was missing!
    computed: {
        filteredSocialLinks() {
            if (!this.footerData?.social_links) return [];
            return this.footerData.social_links.filter(
                (link) => link.url && link.url !== "#" && link.url !== ""
            );
        },
    },

    methods: {
        async fetchFooterData() {
            this.isLoading = true;
            this.error = null;

            try {
                const response = await axios.get("/footer-sections/active");
                this.footerData = response.data;
            } catch (error) {
                console.error("Error fetching footer data:", error);
                this.error = error.message;

                // Fallback to default data if API fails
                this.footerData = {
                    tagline: "A Complete ERP System",
                    description:
                        "Providing exceptional Islamic Cambridge education that prepares students for success in this world and the hereafter.",
                    address: "Chattogram, Bangladesh",
                    phone: "+880 123 456 789",
                    email: "info@eduworld.edu",
                    working_hours: "Mon-Fri: 8:00 AM - 4:00 PM",
                    background_color: "#030811",
                    text_color: "#ffffff",
                    accent_color: "#ff7101",
                    link_color: "#cccccc",
                    link_hover_color: "#ff7101",
                    social_links: [
                        {
                            platform: "facebook",
                            url: "https://facebook.com",
                            icon: "fab fa-facebook-f",
                        },
                        {
                            platform: "twitter",
                            url: "https://twitter.com",
                            icon: "fab fa-twitter",
                        },
                        {
                            platform: "instagram",
                            url: "https://instagram.com",
                            icon: "fab fa-instagram",
                        },
                        {
                            platform: "youtube",
                            url: "https://youtube.com",
                            icon: "fab fa-youtube",
                        },
                    ],
                    quick_links: [
                        { text: "About Us", url: "#" },
                        { text: "Admissions", url: "#" },
                        { text: "Programs", url: "#" },
                        { text: "Campus Life", url: "#" },
                        { text: "News & Events", url: "#" },
                    ],
                    programs: [
                        { text: "Islamic Studies", url: "#" },
                        { text: "Cambridge Curriculum", url: "#" },
                        { text: "Hifz Program", url: "#" },
                        { text: "Primary Education", url: "#" },
                        { text: "Secondary Education", url: "#" },
                    ],
                };
            } finally {
                this.isLoading = false;
            }
        },

        getFooterStyle() {
            if (!this.footerData)
                return { background: "#030811", color: "#fff" };
            return {
                background: this.footerData.background_color || "#030811",
                color: this.footerData.text_color || "#fff",
            };
        },

        getTextStyle() {
            if (!this.footerData) return { color: "#fff" };
            return {
                color: this.footerData.text_color || "#fff",
            };
        },

        getHeadingStyle() {
            if (!this.footerData) return { color: "#ff7101" };
            return {
                color: this.footerData.accent_color || "#ff7101",
            };
        },

        getLinkStyle() {
            if (!this.footerData) return { color: "#ccc" };
            return {
                color: this.footerData.link_color || "#ccc",
            };
        },

        getSocialLinkStyle() {
            if (!this.footerData) return { backgroundColor: "#ff7101" };
            return {
                backgroundColor: this.footerData.accent_color || "#ff7101",
            };
        },
    },

    mounted() {
        this.fetchFooterData();
    },
};
</script>

<style scoped>
.container {
    max-width: 1240px;
    margin: 0 auto;
    padding: 0 20px;
}
.footer {
    padding: 60px 0 20px;
}
.footer-content {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 40px;
    margin-bottom: 40px;
}
.footer-section h3 {
    margin-bottom: 20px;
    font-size: 1.3rem;
}
.footer-section p,
.footer-section a {
    text-decoration: none;
    line-height: 1.8;
}
.footer-section a:hover {
    color: v-bind('footerData?.link_hover_color || "#ff7101"');
}
.footer-links {
    list-style: none;
    padding: 0;
}
.footer-links li {
    margin-bottom: 10px;
}
.footer-social {
    display: flex;
    gap: 15px;
    margin-top: 20px;
}
.social-link {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #fff;
    text-decoration: none;
    transition: all 0.3s ease;
}
.social-link:hover {
    transform: translateY(-3px);
    background: v-bind('footerData?.link_hover_color || "#d35b00"') !important;
}
.footer-bottom {
    border-top: 1px solid #333;
    padding-top: 20px;
    text-align: center;
    color: #999;
}
.nav-logo img {
    width: 180px;
    height: auto;
    object-fit: contain;
    margin-bottom: 15px;
}

/* Loading and Error States */
.footer-loading {
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 200px;
    background: #030811;
    color: #fff;
}

.footer-error {
    background: #030811;
    color: #fff;
    padding: 40px 0;
    text-align: center;
}
</style>
