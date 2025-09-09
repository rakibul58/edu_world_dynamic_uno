<template>
    <section
        class="academic-programs"
        :style="sectionStyles"
        v-if="settings.is_active"
    >
        <div class="container" :style="containerStyles">
            <div
                class="section-header"
                :style="headerStyles"
                :class="{ animate: isVisible && settings.enable_animations }"
                ref="sectionHeader"
            >
                <h2 :style="titleStyles">{{ settings.section_title }}</h2>
                <p :style="descriptionStyles">
                    {{ settings.section_description }}
                </p>
            </div>
            <div class="programs-detail" :style="programsDetailStyles">
                <div
                    class="program-level"
                    v-for="(program, index) in programs"
                    :key="program.id"
                    :style="getProgramCardStyles(program)"
                    :class="{
                        animate: isVisible && settings.enable_animations,
                    }"
                    @mouseenter="onProgramHover(program, true)"
                    @mouseleave="onProgramHover(program, false)"
                    :ref="`program-${index}`"
                >
                    <div
                        class="level-header"
                        :style="getLevelHeaderStyles(program)"
                    >
                        <span
                            class="level-icon"
                            :style="getIconStyles(program)"
                        >
                            {{ program.icon }}
                        </span>
                        <div class="level-info">
                            <h3 :style="getTitleStyles(program)">
                                {{ program.title }}
                            </h3>
                            <span
                                class="age-range"
                                :style="getAgeRangeStyles(program)"
                            >
                                {{ program.ageRange || program.age_range }}
                            </span>
                        </div>
                    </div>
                    <div
                        class="level-content"
                        :style="getLevelContentStyles(program)"
                    >
                        <ul :style="getItemsListStyles(program)">
                            <li
                                v-for="(item, itemIndex) in program.items"
                                :key="itemIndex"
                                class="program-item"
                                :style="getItemStyles(program)"
                            >
                                {{ item }}
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>

<script>
import axios from "axios";

export default {
    name: "AcademicPrograms",
    data() {
        return {
            settings: {
                section_title: "Academic Excellence at Every Level",
                section_description:
                    "From foundation to advanced levels, our comprehensive curriculum ensures holistic development.",
                section_title_color: "#102e4a",
                section_title_font_size: "2.5rem",
                section_title_font_weight: "700",
                section_description_color: "#111111",
                section_description_font_size: "1.1rem",
                section_description_font_weight: "400",
                section_background_color: "#f8fbfe",
                section_padding: "80px 0",
                header_text_align: "center",
                header_margin_bottom: "60px",
                container_max_width: "1240px",
                container_padding: "0 20px",
                programs_gap: "30px",
                enable_animations: true,
                animation_duration: "0.6s",
                animation_delay_increment: "0.1s",
                is_active: true,
            },
            programs: [],
            isVisible: false,
            isLoading: true,
            error: null,
            hoveredProgram: null,
        };
    },
    computed: {
        sectionStyles() {
            return {
                background: this.settings.section_background_color,
                padding: this.settings.section_padding,
            };
        },
        containerStyles() {
            return {
                maxWidth: this.settings.container_max_width,
                margin: "0 auto",
                padding: this.settings.container_padding,
            };
        },
        headerStyles() {
            return {
                textAlign: this.settings.header_text_align,
                marginBottom: this.settings.header_margin_bottom,
            };
        },
        titleStyles() {
            return {
                fontSize: this.settings.section_title_font_size,
                color: this.settings.section_title_color,
                fontWeight: this.settings.section_title_font_weight,
                marginBottom: "15px",
            };
        },
        descriptionStyles() {
            return {
                fontSize: this.settings.section_description_font_size,
                color: this.settings.section_description_color,
                fontWeight: this.settings.section_description_font_weight,
                maxWidth: "600px",
                margin: "0 auto",
            };
        },
        programsDetailStyles() {
            return {
                display: "grid",
                gap: this.settings.programs_gap,
            };
        },
    },
    methods: {
        async fetchData() {
            this.isLoading = true;
            this.error = null;

            try {
                // Change this URL to match your Laravel route
                const response = await axios.get("/academic-programs");
                
                if (response.data) {
                    // Adjust based on your API response structure
                    this.programs = response.data.programs || response.data;
                    if (response.data.settings) {
                        this.settings = { ...this.settings, ...response.data.settings };
                    }
                } else {
                    this.loadDemoData();
                }
            } catch (error) {
                console.error("Error fetching academic programs:", error);
                this.loadDemoData();
            } finally {
                this.isLoading = false;
            }
        },

        loadDemoData() {
            this.programs = [
                {
                    id: 1,
                    icon: "🌱",
                    title: "Foundation (Years 1-6)",
                    ageRange: "Ages 5-11",
                    items: [
                        "Cambridge Primary Curriculum",
                        "Arabic Language & Islamic Studies",
                        "Quran Recitation & Basic Hifz",
                        "Creative Arts & Physical Education",
                    ],
                    icon_background_color: "#ff7101",
                    icon_color: "#ffffff",
                    icon_size: "2.5rem",
                    icon_width: "60px",
                    icon_height: "60px",
                    icon_border_radius: "50%",
                    title_color: "#102e4a",
                    title_font_size: "1.5rem",
                    title_font_weight: "600",
                    title_margin_bottom: "5px",
                    age_range_color: "#ff7101",
                    age_range_font_size: "0.9rem",
                    age_range_font_weight: "600",
                    items_color: "#111",
                    items_font_size: "1rem",
                    items_font_weight: "400",
                    items_padding: "8px 0",
                    items_check_color: "#20bf6b",
                    items_padding_left: "25px",
                    card_background_color: "#fff",
                    card_border_radius: "15px",
                    card_padding: "30px",
                    card_shadow: "0 5px 20px rgba(0, 0, 0, 0.08)",
                    card_hover_shadow: "0 15px 30px rgba(0, 0, 0, 0.15)",
                    card_hover_transform: "translateY(-5px)",
                    header_gap: "20px",
                    content_margin_top: "20px",
                },
                {
                    id: 2,
                    icon: "🌿",
                    title: "Lower Secondary (Years 7-9)",
                    ageRange: "Ages 11-14",
                    items: [
                        "Cambridge Lower Secondary",
                        "Advanced Islamic Studies",
                        "STEM Focus with Islamic Ethics",
                        "Leadership Development Programs",
                    ],
                    icon_background_color: "#ff7101",
                    icon_color: "#ffffff",
                    icon_size: "2.5rem",
                    icon_width: "60px",
                    icon_height: "60px",
                    icon_border_radius: "50%",
                    title_color: "#102e4a",
                    title_font_size: "1.5rem",
                    title_font_weight: "600",
                    title_margin_bottom: "5px",
                    age_range_color: "#ff7101",
                    age_range_font_size: "0.9rem",
                    age_range_font_weight: "600",
                    items_color: "#111",
                    items_font_size: "1rem",
                    items_font_weight: "400",
                    items_padding: "8px 0",
                    items_check_color: "#20bf6b",
                    items_padding_left: "25px",
                    card_background_color: "#fff",
                    card_border_radius: "15px",
                    card_padding: "30px",
                    card_shadow: "0 5px 20px rgba(0, 0, 0, 0.08)",
                    card_hover_shadow: "0 15px 30px rgba(0, 0, 0, 0.15)",
                    card_hover_transform: "translateY(-5px)",
                    header_gap: "20px",
                    content_margin_top: "20px",
                },
                {
                    id: 3,
                    icon: "🌳",
                    title: "Upper Secondary (Years 10-12)",
                    ageRange: "Ages 14-18",
                    items: [
                        "Cambridge IGCSE & A Levels",
                        "University Preparation",
                        "Research Projects",
                        "Community Service Programs",
                    ],
                    icon_background_color: "#ff7101",
                    icon_color: "#ffffff",
                    icon_size: "2.5rem",
                    icon_width: "60px",
                    icon_height: "60px",
                    icon_border_radius: "50%",
                    title_color: "#102e4a",
                    title_font_size: "1.5rem",
                    title_font_weight: "600",
                    title_margin_bottom: "5px",
                    age_range_color: "#ff7101",
                    age_range_font_size: "0.9rem",
                    age_range_font_weight: "600",
                    items_color: "#111",
                    items_font_size: "1rem",
                    items_font_weight: "400",
                    items_padding: "8px 0",
                    items_check_color: "#20bf6b",
                    items_padding_left: "25px",
                    card_background_color: "#fff",
                    card_border_radius: "15px",
                    card_padding: "30px",
                    card_shadow: "0 5px 20px rgba(0, 0, 0, 0.08)",
                    card_hover_shadow: "0 15px 30px rgba(0, 0, 0, 0.15)",
                    card_hover_transform: "translateY(-5px)",
                    header_gap: "20px",
                    content_margin_top: "20px",
                },
            ];
        },

        getProgramCardStyles(program) {
            const baseStyles = {
                background: program.card_background_color,
                borderRadius: program.card_border_radius,
                padding: program.card_padding,
                boxShadow: program.card_shadow,
                transition: "all 0.3s ease",
            };

            if (this.hoveredProgram && this.hoveredProgram.id === program.id) {
                baseStyles.boxShadow = program.card_hover_shadow;
                baseStyles.transform = program.card_hover_transform;
            }

            return baseStyles;
        },

        getLevelHeaderStyles(program) {
            return {
                display: "flex",
                alignItems: "center",
                gap: program.header_gap,
                marginBottom: program.content_margin_top,
            };
        },

        getIconStyles(program) {
            return {
                fontSize: program.icon_size,
                background: program.icon_background_color,
                color: program.icon_color,
                width: program.icon_width,
                height: program.icon_height,
                borderRadius: program.icon_border_radius,
                display: "flex",
                alignItems: "center",
                justifyContent: "center",
                flexShrink: 0,
            };
        },

        getTitleStyles(program) {
            return {
                color: program.title_color,
                fontSize: program.title_font_size,
                fontWeight: program.title_font_weight,
                marginBottom: program.title_margin_bottom,
            };
        },

        getAgeRangeStyles(program) {
            return {
                color: program.age_range_color,
                fontWeight: program.age_range_font_weight,
                fontSize: program.age_range_font_size,
            };
        },

        getLevelContentStyles(program) {
            return {
                marginTop: program.content_margin_top,
            };
        },

        getItemsListStyles(program) {
            return {
                listStyle: "none",
                display: "grid",
                gridTemplateColumns: "repeat(auto-fit, minmax(250px, 1fr))",
                gap: "10px",
                margin: 0,
                padding: 0,
            };
        },

        getItemStyles(program) {
            return {
                color: program.items_color,
                padding: program.items_padding,
                position: "relative",
                paddingLeft: program.items_padding_left,
                fontSize: program.items_font_size,
                fontWeight: program.items_font_weight,
            };
        },

        onProgramHover(program, isHovering) {
            this.hoveredProgram = isHovering ? program : null;
        },

        setupIntersectionObserver() {
            if (!this.settings.enable_animations) {
                this.isVisible = true;
                return;
            }

            const observer = new IntersectionObserver(
                (entries) => {
                    entries.forEach((entry) => {
                        if (entry.isIntersecting) {
                            this.isVisible = true;
                            entry.target.classList.add("animate");
                        }
                    });
                },
                { threshold: 0.1 }
            );

            if (this.$refs.sectionHeader) {
                observer.observe(this.$refs.sectionHeader);
            }

            this.programs.forEach((program, index) => {
                const programRef = this.$refs[`program-${index}`];
                if (programRef && programRef[0]) {
                    observer.observe(programRef[0]);
                }
            });
        },

        async refreshData() {
            await this.fetchData();
            this.$nextTick(() => {
                this.setupIntersectionObserver();
            });
        },
    },

    async mounted() {
        await this.fetchData();
        
        this.$nextTick(() => {
            this.setupIntersectionObserver();
        });
    },
};
</script>

<style scoped>
.academic-programs {
    font-family: "Inter", -apple-system, BlinkMacSystemFont, sans-serif;
}

.container {
    position: relative;
}

.section-header {
    opacity: 0;
    transform: translateY(30px);
    transition: all 0.6s ease;
}

.section-header.animate {
    opacity: 1;
    transform: translateY(0);
}

.program-level {
    opacity: 0;
    transform: translateY(30px);
    cursor: pointer;
}

.program-level.animate {
    opacity: 1;
    transform: translateY(0);
    transition: all 0.6s ease;
}

.level-info {
    flex: 1;
    min-width: 0;
}

.level-info h3 {
    margin: 0;
}

.program-item {
    list-style: none;
    position: relative;
}

.program-item:before {
    content: "✓";
    font-weight: bold;
    position: absolute;
    left: 0;
    color: var(--check-color, #20bf6b);
}

/* Animation delays */
.program-level:nth-child(1) {
    animation-delay: 0.1s;
}
.program-level:nth-child(2) {
    animation-delay: 0.2s;
}
.program-level:nth-child(3) {
    animation-delay: 0.3s;
}

/* Responsive Design */
@media (max-width: 768px) {
    .level-header {
        flex-direction: column;
        text-align: center;
        gap: 15px !important;
    }

    .level-info {
        text-align: center;
    }

    .level-content ul {
        grid-template-columns: 1fr !important;
    }

    .program-level {
        padding: 20px !important;
    }
}
</style>