<template>
    <section class="news-events">
        <div class="container">
            <div class="section-header">
                <h2>Latest News & Upcoming Events</h2>
                <p>Stay updated with the latest happenings at Edu World.</p>
            </div>
            <div class="news-grid">
                <div
                    class="news-item"
                    v-for="(item, index) in newsItems"
                    :key="index"
                >
                    <div
                        class="news-image"
                        :style="{ background: item.background }"
                    >
                        <span class="news-icon">{{ item.icon }}</span>
                    </div>
                    <div class="news-content">
                        <span class="news-date">{{ item.date }}</span>
                        <h3>{{ item.title }}</h3>
                        <p>{{ item.description }}</p>
                        <a href="#" class="news-link">Read More</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>

<script>
export default {
    name: "NewsEvents",
    data() {
        return {
            newsItems: [
                {
                    icon: "🏆",
                    title: "Science Fair Winners Announced",
                    date: "March 15, 2025",
                    description:
                        "Our students excel in the regional science competition with innovative projects combining Islamic principles and modern science.",
                    background: "linear-gradient(135deg, #ff7101, #d35b00)",
                },
                {
                    icon: "📖",
                    title: "Ramadan Program Launch",
                    date: "March 20, 2025",
                    description:
                        "Special Ramadan activities including Tarawih prayers, community iftar, and Quran recitation competitions.",
                    background: "linear-gradient(135deg, #20bf6b, #27ae60)",
                },
                {
                    icon: "🎓",
                    title: "Graduation Ceremony 2025",
                    date: "April 5, 2025",
                    description:
                        "Celebrating our graduates as they move on to prestigious universities around the world.",
                    background: "linear-gradient(135deg, #f7b731, #f39c12)",
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
            .querySelectorAll(".section-header, .news-item")
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
.news-events {
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
.news-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
    gap: 30px;
}
.news-item {
    background: #fff;
    border-radius: 15px;
    overflow: hidden;
    box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
    transition: all 0.3s ease;
    opacity: 0;
    transform: translateY(30px);
}
.news-item.animate {
    opacity: 1;
    transform: translateY(0);
    transition: all 0.3s ease;
}
.news-item:hover {
    transform: translateY(-10px);
    box-shadow: 0 15px 30px rgba(0, 0, 0, 0.15);
}
.news-image {
    height: 200px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #fff;
}
.news-icon {
    font-size: 3rem;
}
.news-content {
    padding: 25px;
}
.news-date {
    color: #ff7101;
    font-size: 0.9rem;
    font-weight: 600;
}
.news-content h3 {
    color: #102e4a;
    margin: 10px 0 15px;
    font-size: 1.3rem;
}
.news-content p {
    color: #111;
    line-height: 1.6;
    margin-bottom: 15px;
}
.news-link {
    color: #ff7101;
    text-decoration: none;
    font-weight: 600;
    transition: color 0.3s ease;
}
.news-link:hover {
    color: #d35b00;
}

/* Staggered animation */
.news-item:nth-child(1) {
    --delay: 0.1s;
}
.news-item:nth-child(2) {
    --delay: 0.2s;
}
.news-item:nth-child(3) {
    --delay: 0.3s;
}

@media (max-width: 768px) {
    .news-grid {
        grid-template-columns: 1fr;
    }
}
</style>
