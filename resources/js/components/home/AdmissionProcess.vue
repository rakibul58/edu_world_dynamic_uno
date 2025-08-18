<template>
    <section class="admission-process">
        <div class="container">
            <div class="section-header">
                <h2>Simple Admission Process</h2>
                <p>
                    Getting started at Edu World is easy. Follow these simple
                    steps to secure your child's future.
                </p>
            </div>
            <div class="process-steps">
                <div
                    class="step-item"
                    v-for="(step, index) in steps"
                    :key="index"
                >
                    <div class="step-number">{{ index + 1 }}</div>
                    <div class="step-content">
                        <h3>{{ step.title }}</h3>
                        <p>{{ step.description }}</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>

<script>
export default {
    name: "AdmissionProcess",
    data() {
        return {
            steps: [
                {
                    title: "Submit Application",
                    description:
                        "Complete our online application form with required documents and photographs.",
                },
                {
                    title: "Assessment & Interview",
                    description:
                        "Attend the entrance assessment and family interview with our admissions team.",
                },
                {
                    title: "Acceptance & Enrollment",
                    description:
                        "Receive your acceptance letter and complete the enrollment process with fee payment.",
                },
                {
                    title: "Welcome Orientation",
                    description:
                        "Join our new family orientation program and begin your journey with us.",
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
            .querySelectorAll(".section-header, .step-item")
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
.admission-process {
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
.process-steps {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 30px;
}
.step-item {
    text-align: center;
    position: relative;
    opacity: 0;
    transform: translateY(30px);
}
.step-item.animate {
    opacity: 1;
    transform: translateY(0);
    transition: all 0.6s ease var(--delay, 0s);
}
.step-number {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    background: #ff7101;
    color: #fff;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2rem;
    font-weight: 700;
    margin: 0 auto 20px;
    position: relative;
    z-index: 2;
}
.step-content h3 {
    margin-bottom: 15px;
    font-size: 1.3rem;
}
.step-content p {
    opacity: 0.9;
    line-height: 1.6;
}

/* Staggered animation */
.step-item:nth-child(1) {
    --delay: 0.1s;
}
.step-item:nth-child(2) {
    --delay: 0.2s;
}
.step-item:nth-child(3) {
    --delay: 0.3s;
}
.step-item:nth-child(4) {
    --delay: 0.4s;
}

@media (max-width: 768px) {
    .process-steps {
        grid-template-columns: 1fr;
    }
}
</style>
