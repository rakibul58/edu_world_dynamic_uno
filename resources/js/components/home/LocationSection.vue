<template>
    <section class="location-section">
        <div class="container">
            <div class="section-header">
                <h2>Visit Our Campus</h2>
                <p>
                    Located in the heart of Chattogram, our campus is easily
                    accessible and provides a serene environment for learning.
                </p>
            </div>
            <div class="location-content">
                <div class="location-info">
                    <div class="contact-cards">
                        <div
                            class="contact-card"
                            v-for="(contact, index) in contacts"
                            :key="index"
                        >
                            <div class="contact-icon">{{ contact.icon }}</div>
                            <h4>{{ contact.title }}</h4>
                            <p v-html="contact.content"></p>
                        </div>
                    </div>
                </div>
                <div class="map-container">
                    <iframe
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3079.6418820516014!2d91.80977059999998!3d22.360993000000004!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x30acd8909da98ea1%3A0x3d9b8053516f015c!2sThe%20world%20IT!5e1!3m2!1sen!2sbd!4v1755059269124!5m2!1sen!2sbd"
                        width="600"
                        height="450"
                        style="border: 0"
                        allowfullscreen=""
                        loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade"
                    ></iframe>
                </div>
            </div>
        </div>
    </section>
</template>

<script>
export default {
    name: "LocationSection",
    data() {
        return {
            contacts: [
                {
                    icon: "📍",
                    title: "Address",
                    content:
                        "Edu World<br />123 Education Street<br />Chattogram 4000, Bangladesh",
                },
                {
                    icon: "📞",
                    title: "Phone",
                    content: "+880 31 123 4567<br />+880 1712 345 678",
                },
                {
                    icon: "✉️",
                    title: "Email",
                    content: "info@eduworld.edu<br />admissions@eduworld.edu",
                },
                {
                    icon: "🕒",
                    title: "Office Hours",
                    content: "Saturday - Thursday<br />8:00 AM - 4:00 PM",
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
            .querySelectorAll(".section-header, .contact-card, .map-container")
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
.location-section {
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
.location-content {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 60px;
    align-items: start;
}
.contact-cards {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 20px;
}
.contact-card {
    background: #fff;
    padding: 25px;
    border-radius: 15px;
    box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
    text-align: center;
    transition: all 0.3s ease;
    opacity: 0;
    transform: translateY(30px);
}
.contact-card.animate {
    opacity: 1;
    transform: translateY(0);
    transition: all 0.6s ease var(--delay, 0s);
}
.contact-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 30px rgba(0, 0, 0, 0.15);
}
.contact-icon {
    font-size: 2.5rem;
    margin-bottom: 15px;
    display: block;
}
.contact-card h4 {
    color: #102e4a;
    margin-bottom: 10px;
    font-size: 1.2rem;
}
.contact-card p {
    color: #111;
    line-height: 1.6;
}
.map-container {
    border-radius: 15px;
    overflow: hidden;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    opacity: 0;
    transform: translateY(30px);
    transition: all 0.6s ease;
}
.map-container.animate {
    opacity: 1;
    transform: translateY(0);
}
.map-container iframe {
    width: 100%;
    height: 400px;
    border: 0;
    border-radius: 15px;
}

/* Staggered animation */
.contact-card:nth-child(1) {
    --delay: 0.1s;
}
.contact-card:nth-child(2) {
    --delay: 0.2s;
}
.contact-card:nth-child(3) {
    --delay: 0.3s;
}
.contact-card:nth-child(4) {
    --delay: 0.4s;
}

@media (max-width: 768px) {
    .location-content {
        grid-template-columns: 1fr;
        gap: 30px;
    }
    .contact-cards {
        grid-template-columns: 1fr;
    }
}
</style>
