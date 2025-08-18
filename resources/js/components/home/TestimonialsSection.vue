<template>
    <section class="testimonials-section">
        <div class="container">
            <div class="section-header">
                <h2>What Our Community Says</h2>
                <p>
                    Hear from parents, students, and graduates about their
                    transformative experience at Edu World.
                </p>
            </div>
            <div class="testimonials-grid">
                <div
                    class="testimonial-card"
                    v-for="(testimonial, index) in testimonials"
                    :key="index"
                >
                    <div class="testimonial-text">"{{ testimonial.text }}"</div>
                    <div class="testimonial-author">
                        <div class="author-avatar">
                            {{ testimonial.initials }}
                        </div>
                        <div class="author-info">
                            <h4>{{ testimonial.name }}</h4>
                            <p>{{ testimonial.role }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>

<script>
export default {
    name: "TestimonialsSection",
    data() {
        return {
            testimonials: [
                {
                    text: "Edu World has been a blessing for our family. The way they seamlessly integrate Islamic values with academic excellence is truly remarkable. My daughter has grown not just academically but spiritually as well.",
                    initials: "SA",
                    name: "Sarah Ahmed",
                    role: "Parent of Year 10 Student",
                },
                {
                    text: "The teachers at Edu World truly care about each student's success. The Islamic Studies program helped me understand my faith better while the Cambridge curriculum prepared me excellently for university.",
                    initials: "MH",
                    name: "Muhammad Hassan",
                    role: "Alumni - Cambridge University",
                },
                {
                    text: "What sets Edu World apart is their holistic approach to education. They don't just teach subjects; they shape character and nurture the whole person. I'm proud to be part of this community.",
                    initials: "FK",
                    name: "Fatima Khan",
                    role: "Current Student - Year 12",
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
            .querySelectorAll(".section-header, .testimonial-card")
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
.testimonials-section {
    padding: 80px 0;
    background: #102e4a;
    color: #fff;
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
    color: #fff;
    font-size: 2.5rem;
    margin-bottom: 15px;
    font-weight: 700;
}
.section-header p {
    color: #fff;
    font-size: 1.1rem;
    max-width: 600px;
    margin: 0 auto;
    opacity: 0.9;
}
.testimonials-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
    gap: 30px;
}
.testimonial-card {
    background: rgba(255, 255, 255, 0.1);
    backdrop-filter: blur(10px);
    padding: 30px;
    border-radius: 15px;
    border: 1px solid rgba(255, 255, 255, 0.2);
    transition: all 0.3s ease;
}
.testimonial-card:hover {
    transform: translateY(-10px);
    background: rgba(255, 255, 255, 0.15);
}
.testimonial-text {
    font-style: italic;
    font-size: 1.1rem;
    margin-bottom: 20px;
    line-height: 1.6;
}
.testimonial-author {
    display: flex;
    align-items: center;
    gap: 15px;
}
.author-avatar {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    background: #ff7101;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #fff;
    font-weight: 600;
    font-size: 1.2rem;
}
.author-info h4 {
    font-weight: 600;
    margin-bottom: 5px;
}
.author-info p {
    opacity: 0.8;
    font-size: 0.9rem;
}

/* Staggered animation */
.testimonial-card:nth-child(1) {
    --delay: 0.1s;
}
.testimonial-card:nth-child(2) {
    --delay: 0.2s;
}
.testimonial-card:nth-child(3) {
    --delay: 0.3s;
}
</style>
