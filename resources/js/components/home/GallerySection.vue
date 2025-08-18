<template>
    <section class="gallery-section">
        <div class="container">
            <div class="section-header">
                <h2>Explore Our Beautiful Campus</h2>
                <p>
                    Take a virtual tour of our state-of-the-art facilities
                    designed to inspire learning and spiritual growth.
                </p>
            </div>
            <div class="gallery-grid">
                <div
                    class="gallery-item"
                    v-for="(item, index) in galleryItems"
                    :key="index"
                >
                    <div
                        class="gallery-image"
                        :style="{ background: item.background }"
                    >
                        <div class="gallery-content">
                            <span class="gallery-icon">{{ item.icon }}</span>
                            <h3>{{ item.title }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>

<script>
export default {
    name: "GallerySection",
    data() {
        return {
            galleryItems: [
                {
                    icon: "🏛️",
                    title: "Main Building",
                    background: "linear-gradient(135deg, #ff7101, #102e4a)",
                },
                {
                    icon: "📚",
                    title: "Library",
                    background: "linear-gradient(135deg, #20bf6b, #102e4a)",
                },
                {
                    icon: "🔬",
                    title: "Science Labs",
                    background: "linear-gradient(135deg, #f7b731, #102e4a)",
                },
                {
                    icon: "🕌",
                    title: "Prayer Hall",
                    background: "linear-gradient(135deg, #8e44ad, #102e4a)",
                },
                {
                    icon: "⚽",
                    title: "Sports Complex",
                    background: "linear-gradient(135deg, #e74c3c, #102e4a)",
                },
                {
                    icon: "🎭",
                    title: "Arts Center",
                    background: "linear-gradient(135deg, #2ecc71, #102e4a)",
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
            .querySelectorAll(".section-header, .gallery-item")
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
.gallery-section {
    padding: 80px 0;
    background: #f8fbfe;
}
.section-header {
    text-align: center;
    margin-bottom: 60px;
    opacity: 0;
    transform: translateY(30px);
    transition: all 0.3s ease;
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
.gallery-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
    gap: 20px;
}
.gallery-item {
    border-radius: 15px;
    overflow: hidden;
    height: 250px;
    transition: all 0.3s ease;
}
.gallery-item:hover {
    transform: translateY(-5px) scale(1.02);
    box-shadow: 0 15px 40px rgba(0, 0, 0, 0.2);
}
.gallery-image {
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    position: relative;
    color: #fff;
}
.gallery-content {
    text-align: center;
}
.gallery-icon {
    font-size: 3rem;
    display: block;
    margin-bottom: 10px;
}
.gallery-content h3 {
    font-size: 1.5rem;
    font-weight: 600;
}

/* Staggered animation */
.gallery-item:nth-child(1) {
    --delay: 0.1s;
}
.gallery-item:nth-child(2) {
    --delay: 0.2s;
}
.gallery-item:nth-child(3) {
    --delay: 0.3s;
}
.gallery-item:nth-child(4) {
    --delay: 0.4s;
}
.gallery-item:nth-child(5) {
    --delay: 0.5s;
}
.gallery-item:nth-child(6) {
    --delay: 0.6s;
}

@media (max-width: 768px) {
    .gallery-grid {
        grid-template-columns: 1fr;
    }
}
</style>
