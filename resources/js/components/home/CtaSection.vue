<template>
    <section class="cta-section">
        <div class="container">
            <div class="cta-content">
                <h2>Ready to Begin Your Journey?</h2>
                <p>
                    Join our community of learners where Islamic values meet
                    academic excellence. Apply today and give your child the
                    education they deserve.
                </p>
                <div class="cta-buttons">
                    <a href="#" class="cta-btn cta-btn-primary">Apply Now</a>
                    <a href="#" class="cta-btn cta-btn-secondary"
                        >Schedule a Visit</a
                    >
                </div>
            </div>
        </div>
    </section>
</template>

<script>
export default {
    name: "CtaSection",
    mounted() {
        const observer = new IntersectionObserver(
            (entries) => {
                entries.forEach((entry) => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add("animate");
                    }
                });
            },
            { threshold: 0.5 }
        );

        const el = document.querySelector(".cta-content");
        if (el) observer.observe(el);
    },
};
</script>

<style scoped>
.container {
    max-width: 1240px;
    margin: 0 auto;
    padding: 0 20px;
}
.cta-section {
    padding: 80px 0;
    background: linear-gradient(135deg, #ff7101, #d35b00);
    color: #fff;
    text-align: center;
}
.cta-content {
    opacity: 0;
    color: white;
    transform: translateY(30px);
    transition: all 0.6s ease;
}
.cta-content.animate {
    opacity: 1;
    transform: translateY(0);
}
.cta-content h2 {
    font-size: 2.5rem;
    margin-bottom: 20px;
    font-weight: 700;
}
.cta-content p {
    font-size: 1.2rem;
    margin-bottom: 30px;
    opacity: 0.9;
}
.cta-buttons {
    display: flex;
    gap: 20px;
    justify-content: center;
    flex-wrap: wrap;
}
.cta-btn {
    padding: 15px 35px;
    border-radius: 8px;
    text-decoration: none;
    font-weight: 600;
    font-size: 1.1rem;
    transition: all 0.5s ease;
    border: 2px solid;
}
.cta-btn-primary {
    background: #fff;
    color: #ff7101;
}
.cta-btn-primary:hover {
    background: transparent;
    color: #fff;
    border: 2px solid #fff;
}
.cta-btn-secondary {
    background: transparent;
    color: #fff;
    border: 2px solid #fff;
}
.cta-btn-secondary:hover {
    background: #fff;
    color: #ff7101;
}

@media (max-width: 576px) {
    .cta-buttons {
        flex-direction: column;
        align-items: center;
    }
    .cta-btn {
        width: 100%;
        max-width: 300px;
    }
}
</style>
