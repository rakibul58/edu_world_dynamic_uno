<template>
    <section class="dynamic-cta-section" :style="getSectionStyle">
        <div class="container">
            <div class="cta-content">
                <h2 :style="getTitleStyle" v-if="ctaData.title">
                    {{ ctaData.title }}
                </h2>
                <p :style="getDescriptionStyle" v-if="ctaData.description">
                    {{ ctaData.description }}
                </p>
                <div
                    class="cta-buttons"
                    v-if="ctaData.buttons && ctaData.buttons.length > 0"
                >
                    <a
                        v-for="(button, index) in ctaData.buttons"
                        :key="index"
                        :href="button.url || '#'"
                        class="cta-btn"
                        :style="getButtonStyle(button, index)"
                        @mouseover="handleButtonHover($event, button, true)"
                        @mouseout="handleButtonHover($event, button, false)"
                    >
                        {{ button.text }}
                    </a>
                </div>
            </div>
        </div>
    </section>
</template>

<script>
import axios from "axios";
export default {
    name: "CtaSection",
    props: {
        sectionId: {
            type: Number,
            default: null,
        },
    },
    data() {
        return {
            ctaData: {
                title: "Ready to Begin Your Journey?",
                description:
                    "Join our community of learners where Islamic values meet academic excellence. Apply today and give your child the education they deserve.",
                title_color: "#ffffff",
                title_font_size: "2.5rem",
                title_font_weight: "700",
                description_color: "#ffffff",
                description_font_size: "1.2rem",
                description_font_weight: "400",
                background_type: "gradient",
                background_color: "#ff7101",
                gradient_start: "#ff7101",
                gradient_end: "#d35b00",
                gradient_direction: "135deg",
                padding_top: "80px",
                padding_bottom: "80px",
                text_align: "center",
                buttons: [
                    {
                        text: "Apply Now",
                        url: "#",
                        background_color: "#ffffff",
                        text_color: "#ff7101",
                        hover_background: "transparent",
                        hover_text_color: "#ffffff",
                        border_color: "#ffffff",
                        hover_border_color: "#ffffff",
                        font_size: "1.1rem",
                        font_weight: "600",
                        padding: "15px 35px",
                        border_radius: "8px",
                        border_width: "2px",
                    },
                    {
                        text: "Schedule a Visit",
                        url: "#",
                        background_color: "transparent",
                        text_color: "#ffffff",
                        hover_background: "#ffffff",
                        hover_text_color: "#ff7101",
                        border_color: "#ffffff",
                        hover_border_color: "#ffffff",
                        font_size: "1.1rem",
                        font_weight: "600",
                        padding: "15px 35px",
                        border_radius: "8px",
                        border_width: "2px",
                    },
                ],
            },
            isLoading: false,
        };
    },
    computed: {
        getSectionStyle() {
            const style = {
                padding: `${this.ctaData.padding_top} 0 ${this.ctaData.padding_bottom}`,
                textAlign: this.ctaData.text_align || "center",
                color: this.ctaData.title_color || "#ffffff",
            };

            if (this.ctaData.background_type === "gradient") {
                style.background = `linear-gradient(${this.ctaData.gradient_direction}, ${this.ctaData.gradient_start}, ${this.ctaData.gradient_end})`;
            } else {
                style.background = this.ctaData.background_color;
            }

            return style;
        },
        getTitleStyle() {
            return {
                fontSize: this.ctaData.title_font_size,
                fontWeight: this.ctaData.title_font_weight,
                color: this.ctaData.title_color,
                marginBottom: "20px",
            };
        },
        getDescriptionStyle() {
            return {
                fontSize: this.ctaData.description_font_size,
                fontWeight: this.ctaData.description_font_weight,
                color: this.ctaData.description_color,
                marginBottom: "30px",
                opacity: "0.9",
            };
        },
    },
    methods: {
        async fetchCtaData() {

            this.isLoading = true;
            try {
                const response = await axios.get(
                    this.sectionId
                        ? `/cta-sections/${this.sectionId}`
                        : `/cta-sections/active`
                );
                this.ctaData = { ...this.ctaData, ...response.data };
            } catch (error) {
                console.error("Error fetching CTA data:", error);
            } finally {
                this.isLoading = false;
            }
        },
        getButtonStyle(button, index) {
            return {
                backgroundColor: button.background_color,
                color: button.text_color,
                border: `${button.border_width} solid ${button.border_color}`,
                fontSize: button.font_size,
                fontWeight: button.font_weight,
                padding: button.padding,
                borderRadius: button.border_radius,
                textDecoration: "none",
                display: "inline-block",
                transition: "all 0.3s ease",
                margin: "0 10px 10px 0",
            };
        },
        handleButtonHover(event, button, isHover) {
            const target = event.target;
            if (isHover) {
                target.style.backgroundColor = button.hover_background;
                target.style.color = button.hover_text_color;
                target.style.borderColor = button.hover_border_color;
            } else {
                target.style.backgroundColor = button.background_color;
                target.style.color = button.text_color;
                target.style.borderColor = button.border_color;
            }
        },
    },
    mounted() {
        this.fetchCtaData();

        // Animation observer
        const observer = new IntersectionObserver(
            (entries) => {
                entries.forEach((entry) => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add("animate");
                    }
                });
            },
            { threshold: 0.5 }
        );

        const el = this.$el.querySelector(".cta-content");
        if (el) observer.observe(el);
    },
};
</script>

<style scoped>
.container {
    max-width: 1240px;
    margin: 0 auto;
    padding: 0 20px;
}

.dynamic-cta-section {
    transition: all 0.3s ease;
}

.cta-content {
    opacity: 0;
    transform: translateY(30px);
    transition: all 0.6s ease;
}

.cta-content.animate {
    opacity: 1;
    transform: translateY(0);
}

.cta-buttons {
    display: flex;
    gap: 20px;
    justify-content: center;
    flex-wrap: wrap;
}

.cta-btn {
    cursor: pointer;
    text-decoration: none;
    display: inline-block;
    transition: all 0.3s ease;
}

.cta-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
}

@media (max-width: 768px) {
    .cta-buttons {
        flex-direction: column;
        align-items: center;
    }

    .cta-btn {
        width: 100%;
        max-width: 300px;
        text-align: center;
    }
}
</style>
