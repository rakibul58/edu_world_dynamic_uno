<template>
    <section class="admission-process" :style="sectionStyle" v-if="processData">
        <div class="container">
            <div class="section-header" ref="sectionHeader">
                <h2 :style="titleStyle">{{ processData.title }}</h2>
                <p :style="descriptionStyle">{{ processData.description }}</p>
            </div>
            <div class="process-steps" :style="stepsContainerStyle">
                <div
                    class="step-item"
                    v-for="(step, index) in processData.active_steps"
                    :key="step.id"
                    :ref="`stepItem${index}`"
                    :style="{ '--delay': `${index * 0.1}s` }"
                >
                    <div class="step-number" :style="stepNumberStyle">
                        {{ step.step_number }}
                    </div>
                    <div class="step-content">
                        <h3 :style="stepTitleStyle">{{ step.title }}</h3>
                        <p :style="stepDescriptionStyle">
                            {{ step.description }}
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
    name: "AdmissionProcess",
    data() {
        return {
            processData: null,
            isLoading: true,
            error: null,
        };
    },
    computed: {
        sectionStyle() {
            if (!this.processData) return {};

            return {
                padding: `${this.processData.padding_top || "80px"} 0 ${
                    this.processData.padding_bottom || "80px"
                } 0`,
                background: this.processData.background_color || "#102e4a",
                color: this.processData.text_color || "#fff",
                textAlign: this.processData.text_align || "center",
            };
        },
        titleStyle() {
            if (!this.processData) return {};

            return {
                color: this.processData.title_color || "#fff",
                fontSize: this.processData.title_font_size || "2.5rem",
                fontWeight: this.processData.title_font_weight || "700",
                margin: "0 0 15px 0",
            };
        },
        descriptionStyle() {
            if (!this.processData) return {};

            return {
                color: this.processData.description_color || "#fff",
                fontSize: this.processData.description_font_size || "1.1rem",
                opacity: this.processData.description_opacity || "0.9",
                maxWidth: "600px",
                margin: "0 auto",
                lineHeight: "1.6",
            };
        },
        stepNumberStyle() {
            if (!this.processData) return {};

            return {
                backgroundColor:
                    this.processData.step_number_bg_color || "#ff7101",
                color: this.processData.step_number_text_color || "#fff",
            };
        },
        stepTitleStyle() {
            if (!this.processData) return {};

            return {
                color: this.processData.step_title_color || "#fff",
                fontSize: this.processData.step_title_font_size || "1.3rem",
                margin: "0 0 15px 0",
                fontWeight: "600",
            };
        },
        stepDescriptionStyle() {
            if (!this.processData) return {};

            return {
                color: this.processData.step_description_color || "#fff",
                opacity: this.processData.step_description_opacity || "0.9",
                lineHeight: "1.6",
                margin: "0",
            };
        },
        stepsContainerStyle() {
            if (!this.processData) return {};

            return {
                display: "grid",
                gridTemplateColumns: `repeat(auto-fit, minmax(${
                    this.processData.grid_min_width || "250px"
                }, 1fr))`,
                gap: this.processData.step_gap || "30px",
            };
        },
    },
    methods: {
        async fetchActiveProcess() {
            this.isLoading = true;
            this.error = null;

            try {
                const response = await axios.get("/admission-process/active");
                this.processData = response.data;
            } catch (error) {
                console.error(
                    "Error fetching active admission process:",
                    error
                );
                this.error = "Failed to load admission process";

                // Fallback to default data
                this.processData = {
                    title: "Simple Admission Process",
                    description:
                        "Getting started at Edu World is easy. Follow these simple steps to secure your child's future.",
                    background_color: "#102e4a",
                    text_color: "#ffffff",
                    padding_top: "80px",
                    padding_bottom: "80px",
                    text_align: "center",
                    title_color: "#ffffff",
                    title_font_size: "2.5rem",
                    title_font_weight: "700",
                    description_color: "#ffffff",
                    description_font_size: "1.1rem",
                    description_opacity: "0.9",
                    step_number_bg_color: "#ff7101",
                    step_number_text_color: "#ffffff",
                    step_title_color: "#ffffff",
                    step_title_font_size: "1.3rem",
                    step_description_color: "#ffffff",
                    step_description_opacity: "0.9",
                    grid_min_width: "250px",
                    step_gap: "30px",
                    active_steps: [
                        {
                            id: 1,
                            step_number: 1,
                            title: "Submit Application",
                            description:
                                "Complete our online application form with required documents and photographs.",
                        },
                        {
                            id: 2,
                            step_number: 2,
                            title: "Assessment & Interview",
                            description:
                                "Attend the entrance assessment and family interview with our admissions team.",
                        },
                        {
                            id: 3,
                            step_number: 3,
                            title: "Acceptance & Enrollment",
                            description:
                                "Receive your acceptance letter and complete the enrollment process with fee payment.",
                        },
                        {
                            id: 4,
                            step_number: 4,
                            title: "Welcome Orientation",
                            description:
                                "Join our new family orientation program and begin your journey with us.",
                        },
                    ],
                };
            } finally {
                this.isLoading = false;
            }
        },

        setupAnimations() {
            this.$nextTick(() => {
                const observer = new IntersectionObserver(
                    (entries) => {
                        entries.forEach((entry) => {
                            if (entry.isIntersecting) {
                                entry.target.classList.add("animate");
                            }
                        });
                    },
                    { threshold: 0.1 }
                );

                // Observe section header
                if (this.$refs.sectionHeader) {
                    observer.observe(this.$refs.sectionHeader);
                }

                // Observe step items
                Object.keys(this.$refs).forEach((key) => {
                    if (
                        key.startsWith("stepItem") &&
                        this.$refs[key] &&
                        this.$refs[key][0]
                    ) {
                        observer.observe(this.$refs[key][0]);
                    }
                });
            });
        },
    },
    async mounted() {
        await this.fetchActiveProcess();
        this.setupAnimations();
    },
};
</script>

<style scoped>
.container {
    max-width: 1240px;
    margin: 0 auto;
    padding: 0 20px;
}

.admission-process {
    transition: all 0.3s ease;
}

.section-header {
    margin-bottom: 60px;
    opacity: 0;
    transform: translateY(30px);
    transition: all 0.6s ease;
}

.section-header.animate {
    opacity: 1;
    transform: translateY(0);
}

.step-item {
    text-align: center;
    position: relative;
    opacity: 0;
    transform: translateY(30px);
    transition: all 0.6s ease var(--delay, 0s);
}

.step-item.animate {
    opacity: 1;
    transform: translateY(0);
}

.step-number {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2rem;
    font-weight: 700;
    margin: 0 auto 20px;
    position: relative;
    z-index: 2;
    transition: all 0.3s ease;
}

.step-item:hover .step-number {
    transform: scale(1.1);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
}

.step-content h3 {
    font-weight: 600;
    transition: all 0.3s ease;
}

.step-content p {
    transition: all 0.3s ease;
}

.step-item:hover .step-content h3 {
    transform: translateY(-2px);
}

/* Loading and Error States */
.loading-state,
.error-state {
    display: flex;
    align-items: center;
    justify-content: center;
    min-height: 300px;
    color: #6b7280;
    font-size: 16px;
}

.error-state {
    color: #ef4444;
}

/* Responsive Design */
@media (max-width: 768px) {
    .step-number {
        width: 60px;
        height: 60px;
        font-size: 1.5rem;
        margin-bottom: 15px;
    }

    .section-header {
        margin-bottom: 40px;
    }
}

@media (max-width: 480px) {
    .container {
        padding: 0 15px;
    }

    .step-number {
        width: 50px;
        height: 50px;
        font-size: 1.2rem;
    }
}

/* Enhanced animations for modern feel */
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

.step-item {
    animation: fadeInUp 0.6s ease forwards;
    animation-delay: var(--delay, 0s);
}

/* Hover effects for better interactivity */
.step-item {
    cursor: pointer;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.step-item:hover {
    transform: translateY(-5px);
}

/* Accessibility improvements */
.step-item:focus-within {
    outline: 2px solid rgba(255, 255, 255, 0.5);
    outline-offset: 4px;
    border-radius: 8px;
}

</style>
