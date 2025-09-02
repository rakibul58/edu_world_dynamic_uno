<template>
    <section class="about-section" :style="sectionStyle">
        <div class="container">
            <div class="about-content">
                <div class="about-text">
                    <h2 :style="titleStyle">{{ sectionData.title }}</h2>
                    <p :style="textStyle">{{ sectionData.description_1 }}</p>
                    <p :style="textStyle">{{ sectionData.description_2 }}</p>
                    <div
                        class="about-stats"
                        v-if="sectionData.stats && sectionData.stats.length"
                    >
                        <div
                            class="stat-item"
                            v-for="(stat, index) in animatedStats"
                            :key="index"
                            :style="statItemStyle"
                        >
                            <span
                                class="stat-number"
                                :style="getStatNumberStyle(stat)"
                            >
                                {{ stat.animatedValue }}
                            </span>
                            <span
                                class="stat-label"
                                :style="getStatLabelStyle(stat)"
                            >
                                {{ stat.label }}
                            </span>
                        </div>
                    </div>
                </div>
                <div class="about-image" :style="rightSideStyle">
                    <img
                        v-if="
                            sectionData.right_side_type === 'image' &&
                            sectionData.image_path
                        "
                        :src="'uploads/image/'+sectionData.image_path"
                        :alt="sectionData.title"
                        class="section-image"
                    />
                    <span
                        v-else-if="
                            sectionData.right_side_type === 'gradient' &&
                            sectionData.image_icon
                        "
                        class="gradient-icon"
                    >
                        {{ sectionData.image_icon }}
                    </span>
                </div>
            </div>
        </div>
    </section>
</template>

<script>
import axios from "axios";

export default {
    name: "DynamicAboutSection",
    props: {
        sectionId: {
            type: Number,
            default: null,
        },
    },
    data() {
        return {
            sectionData: {
                title: "",
                description_1: "",
                description_2: "",
                background_color: "#f8fbfe",
                title_color: "#102e4a",
                title_font_size: "2.6rem",
                title_font_weight: "700",
                text_color: "#111",
                text_font_size: "1.3rem",
                text_font_weight: "300",
                gradient_start: "#ff7101",
                gradient_end: "#102e4a",
                gradient_direction: "135deg",
                right_side_type: "gradient",
                image_icon: "📚",
                image_url: null,
                stats: [],
            },
            animatedStats: [],
            hasAnimated: false,
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
                marginBottom: "20px",
                fontWeight: this.sectionData.title_font_weight,
            };
        },
        textStyle() {
            return {
                fontSize: this.sectionData.text_font_size,
                marginBottom: "20px",
                color: this.sectionData.text_color,
                fontWeight: this.sectionData.text_font_weight,
            };
        },
        statItemStyle() {
            return {
                textAlign: "center",
                padding: "32px",
                background: "#fff",
                borderRadius: "10px",
                boxShadow: "0 5px 15px rgba(0, 0, 0, 0.1)",
                transition: "transform 0.3s ease",
            };
        },
        rightSideStyle() {
            const baseStyle = {
                position: "relative",
                borderRadius: "15px",
                overflow: "hidden",
                height: "400px",
                display: "flex",
                alignItems: "center",
                justifyContent: "center",
                color: "#fff",
                fontSize: "3rem",
                opacity: "0",
                transform: "translateY(30px)",
                transition: "all 0.6s ease",
            };

            if (this.sectionData.right_side_type === "gradient") {
                baseStyle.background = `linear-gradient(${this.sectionData.gradient_direction}, ${this.sectionData.gradient_start}, ${this.sectionData.gradient_end})`;
            } else {
                baseStyle.background = "#f0f0f0";
                baseStyle.color = "#333";
            }

            return baseStyle;
        },
    },
    methods: {
        async fetchSectionData() {
            try {
                let response;
                if (this.sectionId) {
                    response = await axios.get(
                        `/about-sections/${this.sectionId}`
                    );
                } else {
                    response = await axios.get("/about-sections/active");
                }

                this.sectionData = response.data;
                this.initializeAnimatedStats();
            } catch (error) {
                console.error("Error fetching section data:", error);
                // Fallback to default data if API fails
                this.initializeAnimatedStats();
            }
        },
        initializeAnimatedStats() {
            this.animatedStats = this.sectionData.stats.map((stat) => ({
                ...stat,
                animatedValue: "0",
                finalValue: stat.value,
            }));
        },
        getStatNumberStyle(stat) {
            return {
                fontSize: stat.number_font_size || "2rem",
                fontWeight: "700",
                color: stat.number_color || "#ff7101",
                display: "block",
            };
        },
        getStatLabelStyle(stat) {
            return {
                color: stat.label_color || "#111",
                fontSize: stat.label_font_size || "0.9rem",
                marginTop: "5px",
            };
        },
        animateStats() {
            if (this.hasAnimated) return;
            this.hasAnimated = true;

            this.animatedStats.forEach((stat, index) => {
                const numericMatch = stat.finalValue.match(/\d+/);
                if (!numericMatch) return;

                const numericValue = parseInt(numericMatch[0]);
                const prefix = stat.finalValue.substring(0, numericMatch.index);
                const suffix = stat.finalValue.substring(
                    numericMatch.index + numericMatch[0].length
                );

                let currentNumber = 0;
                const increment = numericValue / 50;

                const timer = setInterval(() => {
                    currentNumber += increment;
                    if (currentNumber >= numericValue) {
                        this.animatedStats[index].animatedValue =
                            prefix + numericValue + suffix;
                        clearInterval(timer);
                    } else {
                        this.animatedStats[index].animatedValue =
                            prefix + Math.floor(currentNumber) + suffix;
                    }
                }, 50);
            });
        },
        setupScrollAnimation() {
            const observer = new IntersectionObserver(
                (entries) => {
                    entries.forEach((entry) => {
                        if (entry.isIntersecting) {
                            entry.target.classList.add("animate");
                            if (
                                entry.target.classList.contains("about-stats")
                            ) {
                                this.animateStats();
                            }
                        }
                    });
                },
                { threshold: 0.5 }
            );

            this.$nextTick(() => {
                const elements = this.$el.querySelectorAll(
                    ".about-content, .about-image, .about-stats"
                );
                elements.forEach((el) => {
                    observer.observe(el);
                });
            });
        },
    },
    async mounted() {
        await this.fetchSectionData();
        this.setupScrollAnimation();
    },
    watch: {
        sectionId: {
            handler() {
                this.hasAnimated = false;
                this.fetchSectionData();
            },
        },
    },
};
</script>

<style scoped>
.about-section {
    padding: 80px 0;
    background: #f8fbfe;
}

.container {
    max-width: 1240px;
    margin: 0 auto;
    padding: 0 20px;
}

.about-content {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 60px;
    align-items: center;
    opacity: 0;
    transform: translateY(30px);
    transition: all 0.6s ease;
}

.about-content.animate {
    opacity: 1;
    transform: translateY(0);
}

.about-text h2 {
    font-size: 2.6rem;
    color: #102e4a;
    margin-bottom: 20px;
    font-weight: 700;
}

.about-text p {
    font-size: 1.3rem;
    margin-bottom: 20px;
    color: #111;
    font-weight: 300;
}

.about-stats {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 20px;
    margin-top: 30px;
    opacity: 0;
    transform: translateY(30px);
    transition: all 0.6s ease;
}

.about-stats.animate {
    opacity: 1;
    transform: translateY(0);
}

.stat-item {
    text-align: center;
    padding: 32px;
    background: #fff;
    border-radius: 10px;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    transition: transform 0.3s ease;
}

.stat-item:hover {
    transform: translateY(-5px);
}

.stat-number {
    font-size: 2rem;
    font-weight: 700;
    color: #ff7101;
    display: block;
}

.stat-label {
    color: #111;
    font-size: 0.9rem;
    margin-top: 5px;
}

.about-image {
    position: relative;
    border-radius: 15px;
    overflow: hidden;
    height: 400px;
    background: linear-gradient(135deg, #ff7101, #102e4a);
    display: flex;
    align-items: center;
    justify-content: center;
    color: #fff;
    font-size: 3rem;
    opacity: 0;
    transform: translateY(30px);
    transition: all 0.6s ease;
}

.about-image.animate {
    opacity: 1 !important;
    transform: translateY(0) !important;
}

.section-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
    border-radius: 15px;
}

.gradient-icon {
    font-size: 3rem;
}

@media (max-width: 768px) {
    .about-content {
        grid-template-columns: 1fr;
        gap: 40px;
    }
    .about-stats {
        grid-template-columns: 1fr;
    }
    .about-text h2 {
        font-size: 2rem;
    }
}
</style>
