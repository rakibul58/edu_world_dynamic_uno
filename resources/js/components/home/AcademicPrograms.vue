<template>
    <section class="academic-programs">
        <div class="container">
            <div class="section-header">
                <h2>Academic Excellence at Every Level</h2>
                <p>
                    From foundation to advanced levels, our comprehensive
                    curriculum ensures holistic development.
                </p>
            </div>
            <div class="programs-detail">
                <div
                    class="program-level"
                    v-for="(program, index) in programs"
                    :key="index"
                >
                    <div class="level-header">
                        <span class="level-icon">{{ program.icon }}</span>
                        <div class="level-info">
                            <h3>{{ program.title }}</h3>
                            <span class="age-range">{{
                                program.ageRange
                            }}</span>
                        </div>
                    </div>
                    <div class="level-content">
                        <ul>
                            <li
                                v-for="(item, itemIndex) in program.items"
                                :key="itemIndex"
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
export default {
    name: "AcademicPrograms",
    data() {
        return {
            programs: [
                {
                    icon: "🌱",
                    title: "Foundation (Years 1-6)",
                    ageRange: "Ages 5-11",
                    items: [
                        "Cambridge Primary Curriculum",
                        "Arabic Language & Islamic Studies",
                        "Quran Recitation & Basic Hifz",
                        "Creative Arts & Physical Education",
                    ],
                },
                {
                    icon: "🌿",
                    title: "Lower Secondary (Years 7-9)",
                    ageRange: "Ages 11-14",
                    items: [
                        "Cambridge Lower Secondary",
                        "Advanced Islamic Studies",
                        "STEM Focus with Islamic Ethics",
                        "Leadership Development Programs",
                    ],
                },
                {
                    icon: "🌳",
                    title: "Upper Secondary (Years 10-12)",
                    ageRange: "Ages 14-18",
                    items: [
                        "Cambridge IGCSE & A Levels",
                        "University Preparation",
                        "Research Projects",
                        "Community Service Programs",
                    ],
                },
            ],
        };
    },
    mounted() {
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

        document
            .querySelectorAll(".section-header, .program-level")
            .forEach((el) => {
                observer.observe(el);
            });
    },
};
</script>

<style scoped>
.container {
    max-width: 1240px;
    margin: 0 auto;
    padding: 0 20px;
}
.academic-programs {
    padding: 80px 0;
    background: #f8fbfe;
}
.section-header {
    text-align: center;
    margin-bottom: 60px;
    opacity: 0;
    transform: translateY(30px);
    transition: all 0.6s ease;
}
.section-header.animate {
    opacity: 1;
    transform: translateY(0);
}
.section-header h2 {
    font-size: 2.5rem;
    color: #102e4a;
    margin-bottom: 15px;
    font-weight: 700;
}
.section-header p {
    font-size: 1.1rem;
    color: #111;
    max-width: 600px;
    margin: 0 auto;
}
.programs-detail {
    display: grid;
    gap: 30px;
}
.program-level {
    background: #fff;
    border-radius: 15px;
    padding: 30px;
    box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
    transition: all 0.3s ease;
    opacity: 0;
    transform: translateY(30px);
}
.program-level.animate {
    opacity: 1;
    transform: translateY(0);
    transition: all 0.6s ease;
}
.program-level:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 30px rgba(0, 0, 0, 0.15);
}
.level-header {
    display: flex;
    align-items: center;
    gap: 20px;
    margin-bottom: 20px;
}
.level-icon {
    font-size: 2.5rem;
    background: #ff7101;
    color: #fff;
    width: 60px;
    height: 60px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
}
.level-info h3 {
    color: #102e4a;
    font-size: 1.5rem;
    margin-bottom: 5px;
}
.age-range {
    color: #ff7101;
    font-weight: 600;
    font-size: 0.9rem;
}
.level-content ul {
    list-style: none;
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 10px;
}
.level-content li {
    color: #111;
    padding: 8px 0;
    position: relative;
    padding-left: 25px;
}
.level-content li:before {
    content: "✓";
    color: #20bf6b;
    font-weight: bold;
    position: absolute;
    left: 0;
}

/* Staggered animation */
.program-level:nth-child(1) {
    --delay: 0.1s;
}
.program-level:nth-child(2) {
    --delay: 0.2s;
}
.program-level:nth-child(3) {
    --delay: 0.3s;
}
</style>
