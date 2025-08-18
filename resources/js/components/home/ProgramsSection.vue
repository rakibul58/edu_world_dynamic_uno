<template>
    <section class="programs-background">
        <div class="programs-wrap">
            <div class="row">
                <div
                    class="col"
                    v-for="(program, index) in programs"
                    :key="index"
                >
                    <div
                        class="program-card"
                        :class="program.class"
                        :style="{ borderColor: program.borderColor }"
                    >
                        <h3>{{ program.title }}</h3>
                        <p>{{ program.description }}</p>
                        <a
                            href="#"
                            class="btn-program"
                            :style="{
                                background: program.hover
                                    ? 'transparent'
                                    : program.btnColor,
                                border: `2px solid ${program.btnBorderColor}`,
                                color: program.hover
                                    ? program.btnBorderColor
                                    : '#fff',
                            }"
                            @mouseover="hoverBtn(index)"
                            @mouseleave="leaveBtn(index)"
                        >
                            Learn more
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>

<script>
export default {
    name: "ProgramsSection",
    data() {
        return {
            programs: [
                {
                    title: "Islamic Studies",
                    description:
                        "The Islamic Studies program at Edu World aims to provide students with a comprehensive understanding of Islam, its principles, and their relevance to contemporary issues.",
                    class: "islamic-studies",
                    borderColor: "#ff7101",
                    btnColor: "#ff7101",
                    btnBorderColor: "#ff7101",
                    hover: false,
                },
                {
                    title: "Cambridge Curriculum",
                    description:
                        "We take pride in delivering a top-tier educational experience through the internationally recognized Cambridge Curriculum while incorporating Islamic values.",
                    class: "cambridge",
                    borderColor: "#20bf6b",
                    btnColor: "#20bf6b",
                    btnBorderColor: "#20bf6b",
                    hover: false,
                },
                {
                    title: "Hifz Program",
                    description:
                        "Students are empowered to engage with the Quran through reading, memorization and understanding, along with fundamental Islamic knowledge and culture.",
                    class: "hifz",
                    borderColor: "#f7b731",
                    btnColor: "#f7b731",
                    btnBorderColor: "#f7b731",
                    hover: false,
                },
            ],
        };
    },
    methods: {
        hoverBtn(index) {
            this.programs[index].hover = true;
        },
        leaveBtn(index) {
            this.programs[index].hover = false;
        },
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

        document.querySelectorAll(".program-card").forEach((el) => {
            observer.observe(el);
        });
    },
};
</script>


<style scoped>
.programs-background {
    background: #102e4a;
    padding: 60px 0 80px;
}
.programs-wrap {
    max-width: 1240px;
    margin: 0 auto;
    padding: 0 20px;
    margin-top: -90px;
    z-index: 5;
    position: relative;
}
.row {
    display: flex;
    flex-wrap: wrap;
    gap: 20px;
    align-items: stretch;
}
.col {
    flex: 1 1 calc(33.333% - 20px);
    display: flex;
}
.program-card {
    background: linear-gradient(
        180deg,
        rgba(16, 46, 74, 0.35),
        rgba(3, 8, 17, 0.25)
    );
    border-radius: 15px;
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
    opacity: 0;
    transform: translateY(30px);
}
.program-card.animate {
    opacity: 1;
    transform: translateY(0);
    transition: all 0.6s ease;
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
    border-radius: 5px;
    text-decoration: none;
    font-weight: 500;
    margin-top: 12px;
    transition: all 0.2s ease-in-out;
    text-align: center;
}

@media (max-width: 768px) {
    .col {
        flex: 1 1 100%;
    }
    .programs-wrap {
        margin-top: -60px;
    }
}
</style>
