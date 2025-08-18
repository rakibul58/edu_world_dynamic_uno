<template>
    <section class="features-section">
        <div class="container">
            <div class="section-header">
                <h2>Why Choose Edu World?</h2>
                <p>
                    Discover what makes our Islamic Cambridge education
                    exceptional and how we prepare students for success in this
                    life and the next.
                </p>
            </div>
            <div class="features-grid">
                <div
                    class="feature-card"
                    v-for="(feature, index) in features"
                    :key="index"
                    :style="{ borderTopColor: feature.color }"
                >
                    <div
                        class="feature-icon"
                        :style="{ background: feature.color }"
                    >
                        {{ feature.icon }}
                    </div>
                    <h3>{{ feature.title }}</h3>
                    <p>{{ feature.description }}</p>
                </div>
            </div>
        </div>
    </section>
</template>

<script>
export default {
    name: "FeaturesSection",
    data() {
        return {
            features: [
                {
                    icon: "🎓",
                    title: "Academic Excellence",
                    description:
                        "Our Cambridge curriculum ensures international standards while our dedicated teachers provide personalized attention to help every student reach their full potential.",
                    color: "#ff7101",
                },
                {
                    icon: "🕌",
                    title: "Islamic Values",
                    description:
                        "We integrate Islamic principles into every aspect of learning, fostering spiritual growth alongside academic achievement in a nurturing environment.",
                    color: "#20bf6b",
                },
                {
                    icon: "🌟",
                    title: "Character Development",
                    description:
                        "Beyond academics, we focus on building strong moral character, leadership skills, and social responsibility in our students.",
                    color: "#f7b731",
                },
                {
                    icon: "🌍",
                    title: "Global Perspective",
                    description:
                        "Preparing students for international opportunities while keeping them rooted in Islamic values and cultural heritage.",
                    color: "#102e4a",
                },
                {
                    icon: "👥",
                    title: "Small Class Sizes",
                    description:
                        "Our low student-teacher ratio ensures personalized attention and meaningful relationships between educators and students.",
                    color: "#8e44ad",
                },
                {
                    icon: "🏆",
                    title: "Proven Results",
                    description:
                        "Our graduates consistently excel in higher education and contribute meaningfully to their communities worldwide.",
                    color: "#e74c3c",
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
            .querySelectorAll(".section-header, .feature-card")
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
.features-section {
    padding: 80px 0;
    background: #fff;
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
.features-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 30px;
}
.feature-card {
    background: #fff;
    padding: 30px;
    border-radius: 15px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    text-align: center;
    transition: all 0.1s ease;
    border-top: 4px solid;
    opacity: 0;
    transform: translateY(30px);
    transition: all 0.3s ease;
    cursor: pointer;
}
.feature-card.animate {
    opacity: 1;
    transform: translateY(0);
    transition: all 0.3s ease;
}
.feature-card:hover {
    transform: translateY(-10px);
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
}
.feature-icon {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 20px;
    font-size: 2rem;
    color: #fff;
}
.feature-card h3 {
    font-size: 1.5rem;
    color: #102e4a;
    margin-bottom: 15px;
}
.feature-card p {
    color: #111;
    line-height: 1.6;
}

/* Staggered animation */
.feature-card:nth-child(1) {
    --delay: 0.1s;
}
.feature-card:nth-child(2) {
    --delay: 0.2s;
}
.feature-card:nth-child(3) {
    --delay: 0.3s;
}
.feature-card:nth-child(4) {
    --delay: 0.4s;
}
.feature-card:nth-child(5) {
    --delay: 0.5s;
}
.feature-card:nth-child(6) {
    --delay: 0.6s;
}
</style>
