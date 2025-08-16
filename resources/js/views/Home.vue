<template>
    <div class="home">
        <!-- Top Navigation -->
        <nav class="navbar-top">
            <div class="container nav-container">
                <a class="nav-logo" href="#">
                    <img :src="settings.logo" :alt="settings.site_name" />
                </a>
                <div class="top-actions">
                    <a href="#" class="nav-btn nav-btn-primary">Enroll Now</a>
                    <a href="#" class="nav-btn nav-btn-secondary">Contact us</a>
                </div>
            </div>
        </nav>

        <!-- Hero Section -->
        <section class="hero-section">
            <!-- Background slides -->
            <div
                v-for="(slide, index) in heroSlides"
                :key="slide.id"
                class="hero-background"
                :class="{ active: index === activeSlide }"
                :style="{
                    background: slide.background_image
                        ? `url(${slide.background_image})`
                        : `linear-gradient(135deg, ${slide.background_color}, var(--c3))`,
                }"
            ></div>

            <div class="hero-overlay"></div>

            <!-- Hero Navigation -->
            <div class="hero-nav">
                <div class="hero-nav-inner">
                    <a class="brand" href="#">
                        <img :src="settings.logo" :alt="settings.site_name" />
                    </a>

                    <div
                        class="menu-toggle"
                        @click="toggleMenu"
                        :class="{ active: mobileMenuOpen }"
                    >
                        <span></span><span></span><span></span>
                    </div>

                    <nav class="nav-links" :class="{ active: mobileMenuOpen }">
                        <a href="#" @click="closeMenu">Home</a>
                        <div class="dropdown">
                            <a href="#" class="drop-toggle">About Us ▾</a>
                            <div class="dropdown-content">
                                <a href="#" @click="closeMenu">Our Story</a>
                                <a href="#" @click="closeMenu"
                                    >Mission & Vision</a
                                >
                            </div>
                        </div>
                        <div class="dropdown">
                            <a href="#" class="drop-toggle">Admission ▾</a>
                            <div class="dropdown-content">
                                <a href="#" @click="closeMenu">Apply Now</a>
                                <a href="#" @click="closeMenu">Requirements</a>
                            </div>
                        </div>
                        <div class="dropdown">
                            <a href="#" class="drop-toggle">Campus ▾</a>
                            <div class="dropdown-content">
                                <a href="#" @click="closeMenu">Facilities</a>
                                <a href="#" @click="closeMenu">Virtual Tour</a>
                            </div>
                        </div>
                        <div class="dropdown">
                            <a href="#" class="drop-toggle">IA Life ▾</a>
                            <div class="dropdown-content">
                                <a href="#" @click="closeMenu">Student Life</a>
                                <a href="#" @click="closeMenu">Events</a>
                            </div>
                        </div>
                        <div class="dropdown">
                            <a href="#" class="drop-toggle">Academics ▾</a>
                            <div class="dropdown-content">
                                <a href="#" @click="closeMenu">Curriculum</a>
                                <a href="#" @click="closeMenu">Programs</a>
                            </div>
                        </div>
                    </nav>
                </div>
            </div>

            <!-- Hero Content -->
            <div class="hero-content container" v-if="currentSlide">
                <div class="hero-text">
                    <h2 class="hero-title">
                        {{ currentSlide.title }}
                        <span
                            style="color: var(--c0)"
                            v-if="
                                settings.site_name &&
                                currentSlide.title.includes('Edu World')
                            "
                        >
                            {{ settings.site_name }}
                        </span>
                    </h2>
                    <h3 class="hero-subtitle">
                        {{ currentSlide.subtitle || settings.site_tagline }}
                    </h3>
                    <h1 class="hero-tagline">{{ currentSlide.description }}</h1>
                    <div class="hero-ctas">
                        <a
                            class="btn-apply"
                            :href="currentSlide.cta_primary_url"
                        >
                            {{ currentSlide.cta_primary_text }}
                        </a>
                        <a
                            class="btn-learn"
                            :href="currentSlide.cta_secondary_url"
                        >
                            {{ currentSlide.cta_secondary_text }}
                        </a>
                    </div>
                </div>
            </div>
        </section>

        <!-- Programs Section -->
        <section class="programs-background">
            <div class="programs-wrap">
                <div class="row">
                    <div
                        v-for="program in programs"
                        :key="program.id"
                        class="col"
                    >
                        <div
                            class="program-card animate-on-scroll"
                            :style="{ borderColor: program.background_color }"
                        >
                            <h3>{{ program.title }}</h3>
                            <p>{{ program.description }}</p>
                            <a
                                :href="program.button_url"
                                class="btn-program"
                                :style="{
                                    background: program.background_color,
                                    borderColor: program.background_color,
                                }"
                            >
                                {{ program.button_text }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- About Section -->
        <section class="about-section">
            <div class="container">
                <div class="about-content animate-on-scroll">
                    <div class="about-text">
                        <h2>Excellence in Islamic Education</h2>
                        <p>
                            At {{ settings.site_name }}, we bridge the gap
                            between traditional Islamic values and modern
                            academic excellence. Our unique approach combines
                            the globally recognized Cambridge curriculum with
                            comprehensive Islamic studies, creating well-rounded
                            individuals prepared for both this world and the
                            hereafter.
                        </p>
                        <p>
                            Founded on the principles of knowledge, wisdom, and
                            character development, we nurture students to become
                            confident, compassionate leaders who contribute
                            positively to society while maintaining their
                            Islamic identity.
                        </p>
                        <div class="about-stats">
                            <div class="stat-item">
                                <span class="stat-number">500+</span>
                                <span class="stat-label">Students</span>
                            </div>
                            <div class="stat-item">
                                <span class="stat-number">50+</span>
                                <span class="stat-label">Teachers</span>
                            </div>
                            <div class="stat-item">
                                <span class="stat-number">95%</span>
                                <span class="stat-label">Success Rate</span>
                            </div>
                            <div class="stat-item">
                                <span class="stat-number">15+</span>
                                <span class="stat-label">Years Experience</span>
                            </div>
                        </div>
                    </div>
                    <div class="about-image animate-on-scroll">📚</div>
                </div>
            </div>
        </section>

        <!-- Features Section -->
        <section class="features-section">
            <div class="container">
                <div class="section-header animate-on-scroll">
                    <h2>Why Choose {{ settings.site_name }}?</h2>
                    <p>
                        Discover what makes our Islamic Cambridge education
                        exceptional and how we prepare students for success in
                        this life and the next.
                    </p>
                </div>
                <div class="features-grid">
                    <div
                        v-for="(feature, index) in features"
                        :key="feature.id"
                        class="feature-card animate-on-scroll"
                        :style="{ borderTopColor: getFeatureColor(index) }"
                    >
                        <div
                            class="feature-icon"
                            :style="{ background: getFeatureColor(index) }"
                        >
                            {{ feature.icon }}
                        </div>
                        <h3>{{ feature.title }}</h3>
                        <p>{{ feature.description }}</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Testimonials Section -->
        <section class="testimonials-section">
            <div class="container">
                <div class="section-header animate-on-scroll">
                    <h2 style="color: white">What Our Community Says</h2>
                    <p style="color: white">
                        Hear from parents, students, and graduates about their
                        transformative experience at {{ settings.site_name }}.
                    </p>
                </div>
                <div class="testimonials-grid">
                    <div
                        v-for="testimonial in testimonials"
                        :key="testimonial.id"
                        class="testimonial-card animate-on-scroll"
                    >
                        <div class="testimonial-text">
                            "{{ testimonial.content }}"
                        </div>
                        <div class="testimonial-author">
                            <div
                                class="author-avatar"
                                :style="{ background: 'var(--c0)' }"
                            >
                                {{ testimonial.author_initials }}
                            </div>
                            <div class="author-info">
                                <h4>{{ testimonial.author_name }}</h4>
                                <p>{{ testimonial.author_title }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Campus Gallery Section -->
        <section class="gallery-section">
            <div class="container">
                <div class="section-header animate-on-scroll">
                    <h2>Explore Our Beautiful Campus</h2>
                    <p>
                        Take a virtual tour of our state-of-the-art facilities
                        designed to inspire learning and spiritual growth.
                    </p>
                </div>
                <div class="gallery-grid">
                    <div
                        v-for="item in gallery"
                        :key="item.id"
                        class="gallery-item animate-on-scroll"
                    >
                        <div
                            class="gallery-image"
                            :style="{
                                background: `linear-gradient(135deg, ${item.background_gradient_start}, ${item.background_gradient_end})`,
                            }"
                        >
                            <div class="gallery-content">
                                <span class="gallery-icon">{{
                                    item.icon
                                }}</span>
                                <h3>{{ item.title }}</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Footer -->
        <footer class="footer">
            <div class="container">
                <div class="footer-content">
                    <div class="footer-section">
                        <a class="nav-logo" href="#">
                            <img
                                :src="settings.footer_logo || settings.logo"
                                :alt="settings.site_name"
                            />
                        </a>
                        <p>{{ settings.site_tagline }}</p>
                        <p>{{ settings.site_description }}</p>
                        <div class="footer-social">
                            <a href="#" class="social-link">f</a>
                            <a href="#" class="social-link">t</a>
                            <a href="#" class="social-link">i</a>
                            <a href="#" class="social-link">y</a>
                        </div>
                    </div>
                    <div class="footer-section">
                        <h3>Quick Links</h3>
                        <ul class="footer-links">
                            <li><a href="#">About Us</a></li>
                            <li><a href="#">Admissions</a></li>
                            <li><a href="#">Programs</a></li>
                            <li><a href="#">Campus Life</a></li>
                            <li><a href="#">News & Events</a></li>
                        </ul>
                    </div>
                    <div class="footer-section">
                        <h3>Programs</h3>
                        <ul class="footer-links">
                            <li
                                v-for="program in programs.slice(0, 5)"
                                :key="program.id"
                            >
                                <a :href="program.button_url">{{
                                    program.title
                                }}</a>
                            </li>
                        </ul>
                    </div>
                    <div class="footer-section">
                        <h3>Contact Info</h3>
                        <div
                            v-for="contact in contact"
                            :key="contact.id"
                            class="contact-item"
                        >
                            <p>{{ contact.icon }} {{ contact.content }}</p>
                        </div>
                    </div>
                </div>
                <div class="footer-bottom">
                    <p>
                        &copy; 2025 {{ settings.site_name }}. All rights
                        reserved. | Privacy Policy | Terms of Service
                    </p>
                </div>
            </div>
        </footer>
    </div>
</template>

<script>
import { computed, onMounted, onUnmounted, ref } from "vue";
import { useStore } from "vuex";

export default {
    name: "Home",
    setup() {
        const store = useStore();
        const activeSlide = ref(0);
        const mobileMenuOpen = ref(false);
        let slideInterval = null;

        const settings = computed(() => store.state.site.settings);
        const heroSlides = computed(() => store.state.site.heroSlides);
        const programs = computed(() => store.state.site.programs);
        const features = computed(() => store.state.site.features);
        const testimonials = computed(() => store.state.site.testimonials);
        const gallery = computed(() => store.state.site.gallery);
        const contact = computed(() => store.state.site.contact);

        const currentSlide = computed(() => {
            return heroSlides.value[activeSlide.value];
        });

        const getFeatureColor = (index) => {
            const colors = ["var(--c0)", "var(--c9)", "var(--c10)"];
            return colors[index % colors.length];
        };

        const startSlideshow = () => {
            if (heroSlides.value.length > 1) {
                slideInterval = setInterval(() => {
                    activeSlide.value =
                        (activeSlide.value + 1) % heroSlides.value.length;
                }, 3500);
            }
        };

        const stopSlideshow = () => {
            if (slideInterval) {
                clearInterval(slideInterval);
                slideInterval = null;
            }
        };

        const toggleMenu = () => {
            mobileMenuOpen.value = !mobileMenuOpen.value;
        };

        const closeMenu = () => {
            mobileMenuOpen.value = false;
        };

        // Intersection Observer for animations
        const observeElements = () => {
            const observer = new IntersectionObserver(
                (entries) => {
                    entries.forEach((entry) => {
                        if (entry.isIntersecting) {
                            entry.target.classList.add("animate");
                        }
                    });
                },
                {
                    threshold: 0.1,
                    rootMargin: "0px 0px -50px 0px",
                }
            );

            document.querySelectorAll(".animate-on-scroll").forEach((el) => {
                observer.observe(el);
            });
        };

        onMounted(() => {
            startSlideshow();
            setTimeout(observeElements, 100); // Small delay to ensure elements are rendered
        });

        onUnmounted(() => {
            stopSlideshow();
        });

        return {
            settings,
            heroSlides,
            programs,
            features,
            testimonials,
            gallery,
            contact,
            currentSlide,
            activeSlide,
            mobileMenuOpen,
            getFeatureColor,
            toggleMenu,
            closeMenu,
        };
    },
};
</script>

<style scoped>
/* Component styles will inherit from global CSS variables */
/* Additional component-specific styles can be added here */

.nav-container {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 10px 0;
}

.nav-logo img {
    width: 180px;
    height: auto;
    object-fit: contain;
}

.hero-section {
    position: relative;
    min-height: 80vh;
    display: block;
    overflow: hidden;
}

.hero-background {
    position: absolute;
    inset: 0;
    z-index: 0;
    opacity: 0;
    transition: opacity 1s ease-in-out;
    background-size: cover;
    background-position: center;
}

.hero-background.active {
    opacity: 1;
}

.hero-overlay {
    position: absolute;
    inset: 0;
    background: linear-gradient(
        135deg,
        rgba(6, 6, 7, 0.8),
        rgba(0, 30, 60, 0.7)
    );
    z-index: 1;
}

.hero-nav {
    position: absolute;
    top: 20px;
    left: 0;
    right: 0;
    z-index: 950;
    padding: 0 20px;
}

.hero-nav-inner {
    max-width: var(--container-max-width);
    margin: 0 auto;
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.hero-nav .brand {
    display: none;
}

.hero-nav .brand img {
    width: 150px;
}

.nav-links {
    display: flex;
    gap: 16px;
    align-items: center;
    transition: all 0.5s ease-in-out;
}

.nav-links a {
    color: #fff;
    text-decoration: none;
    font-weight: 400;
    padding: 6px;
    border-radius: var(--border-radius-small);
    transition: color 0.2s ease-in-out;
}

.nav-links a:hover {
    color: var(--c0);
}

.dropdown {
    position: relative;
}

.dropdown-content {
    position: absolute;
    top: calc(100% + 6px);
    left: 0;
    min-width: 210px;
    background: #fff;
    color: #000;
    border-radius: var(--border-radius-small);
    display: none;
    box-shadow: 0 6px 18px rgba(2, 10, 30, 0.25);
    border-top: 3px solid var(--c0);
}

.dropdown-content a {
    display: block;
    padding: 10px 16px;
    color: #111;
    border-bottom: 1px solid #eee;
}

.dropdown:hover .dropdown-content {
    display: block;
}

.menu-toggle {
    display: none;
    flex-direction: column;
    gap: 5px;
    cursor: pointer;
}

.menu-toggle span {
    display: block;
    height: 3px;
    width: 26px;
    background: var(--c0);
    border-radius: 3px;
    transition: all 0.3s ease;
}

.menu-toggle.active span:nth-child(1) {
    transform: rotate(45deg) translate(6px, 6px);
}

.menu-toggle.active span:nth-child(2) {
    opacity: 0;
}

.menu-toggle.active span:nth-child(3) {
    transform: rotate(-45deg) translate(6px, -6px);
}

.hero-content {
    position: relative;
    z-index: 920;
    min-height: 80vh;
    display: flex;
    align-items: center;
    padding: 110px 20px 80px;
}

.hero-text {
    max-width: 780px;
    color: #fff;
}

.hero-title {
    font-size: 1.9rem;
    font-weight: 700;
    margin-bottom: 8px;
}

.hero-subtitle {
    color: var(--c11);
    font-size: 1.5rem;
    font-weight: 600;
    margin-bottom: 12px;
}

.hero-tagline {
    font-size: 2.2rem;
    color: var(--c10);
    font-weight: 800;
    line-height: 1.05;
    margin-bottom: 18px;
}

.hero-ctas {
    display: flex;
    gap: 12px;
}

.btn-apply,
.btn-learn {
    padding: 10px 28px;
    border-radius: var(--border-radius-small);
    text-decoration: none;
    font-weight: 500;
    text-align: center;
    transition: all 0.3s ease;
    border: 2px solid;
}

.btn-apply {
    background: var(--c9);
    border-color: var(--c9);
    color: #fff;
}

.btn-apply:hover {
    background: transparent;
    color: var(--c9);
    transform: translateY(-2px);
}

.btn-learn {
    background: transparent;
    border-color: var(--c0);
    color: var(--c0);
}

.btn-learn:hover {
    background: var(--c0);
    color: #fff;
    transform: translateY(-2px);
}

.navbar-top {
    background: var(--c3);
    width: 100%;
    padding: 0 10px;
}

.top-actions {
    display: flex;
    gap: 10px;
    align-items: center;
}

.nav-btn {
    color: var(--c5);
    text-decoration: none;
    border: 2px solid;
    border-radius: var(--border-radius-small);
    padding: 8px 30px;
    font-weight: 500;
    font-size: 0.95rem;
    transition: all 0.18s;
    text-align: center;
}

.nav-btn-primary {
    background: var(--c9);
    border-color: var(--c9);
}

.nav-btn-primary:hover {
    background: transparent;
    color: var(--c9);
}

.nav-btn-secondary {
    background: var(--c0);
    border-color: var(--c0);
}

.nav-btn-secondary:hover {
    background: transparent;
    color: var(--c0);
}

/* Programs Section */
.programs-background {
    background: var(--c3);
    padding: 60px 0 80px;
}

.programs-wrap {
    max-width: var(--container-max-width);
    margin: 0 auto;
    padding: 0 20px;
    margin-top: -90px;
    z-index: 5;
    position: relative;
}

.programs-wrap .row {
    display: flex;
    flex-wrap: wrap;
    gap: 20px;
    align-items: stretch;
}

.programs-wrap .col {
    flex: 1 1 calc(33.333% - 20px);
    display: flex;
}

.program-card {
    background: linear-gradient(
        180deg,
        rgba(16, 46, 74, 0.35),
        rgba(3, 8, 17, 0.25)
    );
    border-radius: var(--border-radius-base);
    padding: 22px;
    color: #fff;
    border: 10px solid;
    backdrop-filter: blur(8px);
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    transition: all 0.3s ease;
    width: 100%;
    min-height: 280px;
}

.program-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 40px rgba(0, 0, 0, 0.2);
}

.program-card h3 {
    font-size: 1.6rem;
    margin-bottom: 10px;
}

.program-card p {
    line-height: 1.6;
    opacity: 0.95;
    flex-grow: 1;
    margin-bottom: 15px;
}

.btn-program {
    display: inline-block;
    padding: 10px 30px;
    border-radius: var(--border-radius-small);
    text-decoration: none;
    font-weight: 500;
    margin-top: 12px;
    transition: all 0.2s ease-in-out;
    text-align: center;
    border: 2px solid;
    color: #fff;
}

.btn-program:hover {
    background: transparent !important;
    color: inherit;
}

/* About Section */
.about-section {
    padding: 80px 0;
    background: var(--c4);
}

.about-content {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 60px;
    align-items: center;
}

.about-text h2 {
    font-size: 2.5rem;
    color: var(--c3);
    margin-bottom: 20px;
    font-weight: 700;
}

.about-text p {
    font-size: 1.1rem;
    margin-bottom: 20px;
    color: var(--c8);
}

.about-stats {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 20px;
    margin-top: 30px;
}

.stat-item {
    text-align: center;
    padding: 20px;
    background: #fff;
    border-radius: var(--border-radius-base);
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    transition: transform 0.3s ease;
}

.stat-item:hover {
    transform: translateY(-5px);
}

.stat-number {
    font-size: 2rem;
    font-weight: 700;
    color: var(--c0);
    display: block;
}

.stat-label {
    color: var(--c8);
    font-size: 0.9rem;
    margin-top: 5px;
}

.about-image {
    position: relative;
    border-radius: var(--border-radius-base);
    overflow: hidden;
    height: 400px;
    background: linear-gradient(135deg, var(--c0), var(--c3));
    display: flex;
    align-items: center;
    justify-content: center;
    color: #fff;
    font-size: 3rem;
}

/* Features Section */
.features-section {
    padding: 80px 0;
    background: #fff;
}

.section-header {
    text-align: center;
    margin-bottom: 60px;
}

.section-header h2 {
    font-size: 2.5rem;
    color: var(--c3);
    margin-bottom: 15px;
    font-weight: 700;
}

.section-header p {
    font-size: 1.1rem;
    color: var(--c8);
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
    border-radius: var(--border-radius-base);
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    text-align: center;
    transition: all 0.3s ease;
    border-top: 4px solid;
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
    color: var(--c3);
    margin-bottom: 15px;
}

.feature-card p {
    color: var(--c8);
    line-height: 1.6;
}

/* Testimonials Section */
.testimonials-section {
    padding: 80px 0;
    background: var(--c3);
    color: #fff;
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
    border-radius: var(--border-radius-base);
    border: 1px solid rgba(255, 255, 255, 0.2);
    transition: all 0.3s ease;
}

.testimonial-card:hover {
    transform: translateY(-5px);
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

/* Gallery Section */
.gallery-section {
    padding: 80px 0;
    background: var(--c4);
}

.gallery-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
    gap: 20px;
}

.gallery-item {
    border-radius: var(--border-radius-base);
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

/* Footer */
.footer {
    background: var(--c2);
    color: #fff;
    padding: 60px 0 20px;
}

.footer-content {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 40px;
    margin-bottom: 40px;
}

.footer-section h3 {
    color: var(--c0);
    margin-bottom: 20px;
    font-size: 1.3rem;
}

.footer-section p,
.footer-section a {
    color: #ccc;
    text-decoration: none;
    line-height: 1.8;
}

.footer-section a:hover {
    color: var(--c0);
}

.footer-links {
    list-style: none;
}

.footer-links li {
    margin-bottom: 10px;
}

.footer-social {
    display: flex;
    gap: 15px;
    margin-top: 20px;
}

.social-link {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: var(--c0);
    display: flex;
    align-items: center;
    justify-content: center;
    color: #fff;
    text-decoration: none;
    transition: all 0.3s ease;
}

.social-link:hover {
    transform: translateY(-3px);
    background: var(--c1);
}

.footer-bottom {
    border-top: 1px solid #333;
    padding-top: 20px;
    text-align: center;
    color: #999;
}

.contact-item {
    margin-bottom: 8px;
}

/* Responsive Design */
@media (max-width: 991px) {
    .navbar-top {
        display: none;
    }

    .hero-nav .brand {
        display: block;
    }

    .menu-toggle {
        display: flex;
    }

    .nav-links {
        position: fixed;
        top: 72px;
        right: 18px;
        display: none;
        flex-direction: column;
        background: rgba(0, 30, 60, 0.95);
        padding: 16px;
        border-radius: var(--border-radius-base);
        gap: 6px;
        min-width: 200px;
        width: fit-content;
        z-index: 960;
    }

    .nav-links.active {
        display: flex;
    }

    .nav-links a {
        color: #fff;
        padding: 8px 10px;
    }
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

    .hero-tagline {
        font-size: 1.8rem;
    }

    .hero-subtitle {
        font-size: 1.15rem;
    }

    .hero-content {
        padding: 80px 16px 50px;
    }

    .hero-ctas {
        flex-direction: column;
    }

    .btn-apply,
    .btn-learn {
        width: 100%;
    }

    .programs-wrap .col {
        flex: 1 1 100%;
    }
}
</style>
