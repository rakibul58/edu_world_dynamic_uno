<template>
    <section class="facilities-section">
        <div class="container">
            <div class="section-header">
                <h2>World-Class Facilities</h2>
                <p>
                    Our campus is equipped with modern amenities and traditional
                    Islamic architecture to create the perfect learning
                    environment.
                </p>
            </div>
            <div class="facilities-showcase">
                <div class="facility-large">
                    <div
                        class="facility-image"
                        style="
                            background: linear-gradient(
                                135deg,
                                rgba(255, 113, 1, 0.9),
                                rgba(16, 46, 74, 0.9)
                            );
                        "
                    >
                        <div class="facility-overlay">
                            <h3>Modern Classrooms</h3>
                            <p>
                                Smart boards, air conditioning, and ergonomic
                                furniture in every classroom to enhance the
                                learning experience.
                            </p>
                            <a href="#" class="facility-btn">Explore</a>
                        </div>
                    </div>
                </div>
                <div class="facility-grid">
                    <div
                        class="facility-item"
                        v-for="(facility, index) in facilities"
                        :key="index"
                    >
                        <div class="facility-icon">{{ facility.icon }}</div>
                        <h4>{{ facility.title }}</h4>
                        <p>{{ facility.description }}</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>

<script>
export default {
    name: "FacilitiesSection",
    data() {
        return {
            facilities: [
                {
                    icon: "💻",
                    title: "Computer Lab",
                    description:
                        "Latest technology and high-speed internet for digital learning.",
                },
                {
                    icon: "🍽️",
                    title: "Halal Cafeteria",
                    description:
                        "Nutritious, halal meals prepared fresh daily by our kitchen staff.",
                },
                {
                    icon: "🚌",
                    title: "Transport",
                    description:
                        "Safe and reliable transportation covering major areas of the city.",
                },
                {
                    icon: "🏥",
                    title: "Health Center",
                    description:
                        "On-campus medical facility with qualified healthcare professionals.",
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
            .querySelectorAll(
                ".section-header, .facility-large, .facility-item"
            )
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
.facilities-section {
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
.facilities-showcase {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 40px;
    align-items: center;
}
.facility-large {
    border-radius: 20px;
    overflow: hidden;
    height: 400px;
    opacity: 0;
    transform: translateY(30px);
    transition: all 0.6s ease;
}
.facility-large.animate {
    opacity: 1;
    transform: translateY(0);
}
.facility-image {
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    position: relative;
}
.facility-overlay {
    text-align: center;
    color: #fff;
    padding: 30px;
}
.facility-overlay h3 {
    font-size: 2rem;
    margin-bottom: 15px;
}
.facility-overlay p {
    font-size: 1.1rem;
    margin-bottom: 20px;
    opacity: 0.9;
}
.facility-btn {
    background: #fff;
    color: #ff7101;
    padding: 12px 30px;
    border-radius: 25px;
    text-decoration: none;
    font-weight: 600;
    transition: all 0.3s ease;
}
.facility-btn:hover {
    background: #ff7101;
    color: #fff;
    transform: translateY(-2px);
}
.facility-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
}
.facility-item {
    background: #fff;
    padding: 25px;
    border-radius: 15px;
    box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
    text-align: center;
    transition: all 0.3s ease;
    opacity: 0;
    transform: translateY(30px);
}
.facility-item.animate {
    opacity: 1;
    transform: translateY(0);
    transition: all 0.6s ease;
}
.facility-item:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 30px rgba(0, 0, 0, 0.15);
}
.facility-icon {
    font-size: 2.5rem;
    margin-bottom: 15px;
    display: block;
}
.facility-item h4 {
    color: #102e4a;
    margin-bottom: 10px;
    font-size: 1.2rem;
}
.facility-item p {
    color: #111;
    font-size: 0.95rem;
}

/* Staggered animation */
.facility-item:nth-child(1) {
    --delay: 0.1s;
}
.facility-item:nth-child(2) {
    --delay: 0.2s;
}
.facility-item:nth-child(3) {
    --delay: 0.3s;
}
.facility-item:nth-child(4) {
    --delay: 0.4s;
}

@media (max-width: 768px) {
    .facilities-showcase {
        grid-template-columns: 1fr;
        gap: 30px;
    }
    .facility-grid {
        grid-template-columns: 1fr;
    }
}
</style>
