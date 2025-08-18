<template>
    <section class="hero-section">
        <!-- backgrounds -->
        <div
            class="hero-background active"
            :style="{ backgroundImage: 'url(/uploads/slides/1.jpg)' }"
        ></div>
        <div
            class="hero-background"
            :style="{ backgroundImage: 'url(/uploads/slides/2.jpg)' }"
        ></div>
        <div
            class="hero-background"
            :style="{ backgroundImage: 'url(/uploads/slides/3.jpg)' }"
        ></div>
        <div
            class="hero-background"
            :style="{ backgroundImage: 'url(/uploads/slides/4.jpg)' }"
        ></div>
        <div
            class="hero-background"
            :style="{ backgroundImage: 'url(/uploads/slides/5.jpg)' }"
        ></div>

        <div class="hero-overlay"></div>

        <!-- overlay nav -->
        <div class="hero-nav" :class="{ scrolled: isScrolled }">
            <div class="hero-nav-inner">
                <a class="brand" href="#"
                    ><img src="/public/uploads/image/logo.png" alt="Logo"
                /></a>

                <div
                    class="menu-toggle"
                    aria-label="Toggle menu"
                    title="Open menu"
                    @click="toggleMenu"
                >
                    <span></span><span></span><span></span>
                </div>

                <nav
                    class="nav-links"
                    :class="{ active: menuActive }"
                    aria-label="Primary"
                >
                    <a href="#">Home</a>

                    <div
                        class="dropdown"
                        v-for="(item, index) in menuItems"
                        :key="index"
                    >
                        <a
                            href="#"
                            class="drop-toggle"
                            @click.prevent="toggleDropdown(index)"
                            >{{ item.title }} ▾</a
                        >
                        <div
                            class="dropdown-content"
                            v-if="item.showDropdown || !isMobile"
                        >
                            <a
                                v-for="(subItem, subIndex) in item.subItems"
                                :key="subIndex"
                                href="#"
                                >{{ subItem }}</a
                            >
                        </div>
                    </div>
                </nav>
            </div>
        </div>

        <!-- hero content -->
        <div class="hero-content container">
            <div class="hero-text">
                <h2 class="hero-title">
                    Welcome To <span style="color: #ff7101">Edu World</span>
                </h2>
                <h3 class="hero-subtitle">A Complete ERP System</h3>
                <h1 class="hero-tagline">
                    A complete system for educational institution management.
                </h1>
                <div class="hero-ctas">
                    <a class="btn-apply" href="#">Apply now</a>
                    <a class="btn-learn" href="#">Learn more</a>
                </div>
            </div>
        </div>
    </section>
</template>

<script>
export default {
    name: "HeroSection",
    data() {
        return {
            isScrolled: false,
            menuActive: false,
            isMobile: false,
            currentSlide: 0,
            menuItems: [
                {
                    title: "About Us",
                    subItems: ["Our Story", "Mission & Vision"],
                    showDropdown: false,
                },
                {
                    title: "Admission",
                    subItems: ["Apply Now", "Requirements"],
                    showDropdown: false,
                },
                {
                    title: "Campus",
                    subItems: ["Facilities", "Virtual Tour"],
                    showDropdown: false,
                },
                {
                    title: "IA Life",
                    subItems: ["Student Life", "Events"],
                    showDropdown: false,
                },
                {
                    title: "Academics",
                    subItems: ["Curriculum", "Programs"],
                    showDropdown: false,
                },
                {
                    title: "Information",
                    subItems: ["News", "Resources"],
                    showDropdown: false,
                },
                {
                    title: "More",
                    subItems: ["Contact", "Gallery"],
                    showDropdown: false,
                },
            ],
        };
    },
    mounted() {
        window.addEventListener("scroll", this.handleScroll);
        window.addEventListener("resize", this.checkMobile);
        this.checkMobile();
        this.startSlider();
    },
    beforeDestroy() {
        window.removeEventListener("scroll", this.handleScroll);
        window.removeEventListener("resize", this.checkMobile);
        clearInterval(this.sliderInterval);
    },
    methods: {
        handleScroll() {
            this.isScrolled = window.scrollY > 50;
        },
        checkMobile() {
            this.isMobile = window.innerWidth < 992;
        },
        toggleMenu() {
            this.menuActive = !this.menuActive;
        },
        toggleDropdown(index) {
            if (this.isMobile) {
                this.menuItems.forEach((item, i) => {
                    if (i !== index) {
                        item.showDropdown = false;
                    }
                });
                this.menuItems[index].showDropdown =
                    !this.menuItems[index].showDropdown;
            }
        },
        startSlider() {
            const slides = document.querySelectorAll(".hero-background");
            let idx = 0;

            this.sliderInterval = setInterval(() => {
                slides[idx].classList.remove("active");
                idx = (idx + 1) % slides.length;
                slides[idx].classList.add("active");
            }, 3500);
        },
    },
};
</script>

<style scoped>
.container {
    max-width: 1240px;
    margin: 0 auto;
    padding: 0 20px;
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
    background-size: cover;
    background-position: center;
    z-index: 0;
    opacity: 0;
    transition: opacity 1s ease-in-out;
    transform-origin: center center;
}
.hero-background.active {
    opacity: 1;
}
.hero-background:nth-child(1) {
    background: linear-gradient(135deg, #ff7101, #102e4a);
}
.hero-background:nth-child(2) {
    background: linear-gradient(135deg, #20bf6b, #102e4a);
}
.hero-background:nth-child(3) {
    background: linear-gradient(135deg, #f7b731, #102e4a);
}
.hero-background:nth-child(4) {
    background: linear-gradient(135deg, #102e4a, #030811);
}
.hero-background:nth-child(5) {
    background: linear-gradient(135deg, #d35b00, #102e4a);
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
@keyframes zoomOut {
    0% {
        transform: scale(1.08);
    }
    100% {
        transform: scale(1);
    }
}
.hero-background {
    animation: zoomOut 8s ease-out infinite;
}

/* Hero nav */
.hero-nav {
    position: absolute;
    top: 20px;
    left: 0;
    right: 0;
    z-index: 950;
    padding: 0 20px;
    pointer-events: auto;
    background: transparent;
    transition: background 0.3s ease;
}
.hero-nav.scrolled {
    background: rgba(0, 30, 60, 0.95);
}
.hero-nav-inner {
    max-width: 1240px;
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
.nav-links a,
.dropdown > a {
    color: #fff;
    text-decoration: none;
    font-weight: 400;
    padding: 6px 6px;
    border-radius: 2px;
    transition: color 0.2s ease-in-out;
}
.nav-links a:hover,
.dropdown > a:hover {
    color: #d35b00;
}
/* dropdown (desktop hover) */
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
    border-radius: 2px;
    display: none;
    box-shadow: 0 6px 18px rgba(2, 10, 30, 0.25);
    border-top: 3px solid #ff7101;
}
.dropdown-content a {
    display: block;
    padding: 10px 16px;
    color: #111;
    text-decoration: none;
    border-bottom: 1px solid #eee;
}
.dropdown:hover .dropdown-content {
    display: block;
}

/* hamburger */
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
    background: #ff7101;
    border-radius: 3px;
}

/* Hero content */
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
    color: #21bf6b;
    font-size: 1.5rem;
    font-weight: 600;
    margin-bottom: 12px;
}
.hero-tagline {
    font-size: 2.2rem;
    color: #f7b731;
    font-weight: 800;
    line-height: 1.05;
    margin-bottom: 18px;
}
.hero-ctas {
    display: flex;
    gap: 12px;
}
.btn-apply {
    background: #20bf6b;
    border: 2px solid #20bf6b;
    color: #fff;
    padding: 10px 28px;
    border-radius: 6px;
    text-decoration: none;
    font-weight: 500;
    text-align: center;
    transition: all 0.3s ease;
}
.btn-apply:hover {
    background: transparent;
    color: #20bf6b;
    transform: translateY(-2px);
}
.btn-learn {
    background: transparent;
    border: 2px solid #ff7101;
    color: #ff7101;
    padding: 10px 28px;
    border-radius: 6px;
    text-decoration: none;
    font-weight: 500;
    text-align: center;
    transition: all 0.3s ease;
}
.btn-learn:hover {
    background: #ff7101;
    color: #fff;
    transform: translateY(-2px);
}

@media (max-width: 991px) {
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
        border-radius: 10px;
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
    .dropdown-content {
        position: static;
        display: none;
        margin-top: 5px;
        background: rgba(0, 0, 0, 0.2);
        border-top: none;
    }
    .dropdown-content a {
        color: #fff;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    }
}

@media (max-width: 768px) {
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
}
</style>
