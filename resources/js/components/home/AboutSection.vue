<template>
    <section class="about-section">
        <div class="container">
            <div class="about-content">
                <div class="about-text">
                    <h2>Excellence in Islamic Education</h2>
                    <p>
                        At Edu World, we bridge the gap between traditional
                        Islamic values and modern academic excellence. Our
                        unique approach combines the globally recognized
                        Cambridge curriculum with comprehensive Islamic studies,
                        creating well-rounded individuals prepared for both this
                        world and the hereafter.
                    </p>
                    <p>
                        Founded on the principles of knowledge, wisdom, and
                        character development, we nurture students to become
                        confident, compassionate leaders who contribute
                        positively to society while maintaining their Islamic
                        identity.
                    </p>
                    <div class="about-stats">
                        <div
                            class="stat-item"
                            v-for="(stat, index) in stats"
                            :key="index"
                        >
                            <span class="stat-number">{{ stat.value }}</span>
                            <span class="stat-label">{{ stat.label }}</span>
                        </div>
                    </div>
                </div>
                <div class="about-image">📚</div>
            </div>
        </div>
    </section>
</template>

<script>
export default {
    name: "AboutSection",
    data() {
        return {
            stats: [
                { value: "500+", label: "Students" },
                { value: "50+", label: "Teachers" },
                { value: "95%", label: "Success Rate" },
                { value: "15+", label: "Years Experience" },
            ],
        };
    },
    mounted() {
        const observer = new IntersectionObserver(
            (entries) => {
                entries.forEach((entry) => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add("animate");
                        if (entry.target.classList.contains("about-stats")) {
                            this.animateStats();
                        }
                    }
                });
            },
            { threshold: 0.5 }
        );

        document
            .querySelectorAll(".about-content, .about-image, .about-stats")
            .forEach((el) => {
                observer.observe(el);
            });
    },
    methods: {
        animateStats() {
            this.stats.forEach((stat, index) => {
                const finalNumber = parseInt(stat.value);
                let currentNumber = 0;
                const increment = finalNumber / 50;

                const timer = setInterval(() => {
                    currentNumber += increment;
                    if (currentNumber >= finalNumber) {
                        this.stats[index].value =
                            finalNumber +
                            (stat.value.includes("+") ? "+" : "") +
                            (stat.value.includes("%") ? "%" : "");
                        clearInterval(timer);
                    } else {
                        this.stats[index].value =
                            Math.floor(currentNumber) +
                            (stat.value.includes("+") ? "+" : "") +
                            (stat.value.includes("%") ? "%" : "");
                    }
                }, 50);
            });
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
    opacity: 1;
    transform: translateY(0);
}
.about-content {
    opacity: 0;
    transform: translateY(30px);
    transition: all 0.6s ease;
}
.about-content.animate {
    opacity: 1;
    transform: translateY(0);
}
.about-stats {
    opacity: 0;
    transform: translateY(30px);
    transition: all 0.6s ease;
}
.about-stats.animate {
    opacity: 1;
    transform: translateY(0);
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
