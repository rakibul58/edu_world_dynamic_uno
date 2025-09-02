<template>
    <div
        class="program-card"
        :class="[program.class_name, { animate: isAnimated }]"
        :style="cardStyles"
        ref="card"
    >
        <h3>{{ program.title }}</h3>
        <p>{{ program.description }}</p>
        <a
            href="#"
            class="btn-program"
            :style="btnStyles"
            @mouseover="setHover(true)"
            @mouseleave="setHover(false)"
        >
            Learn more
        </a>
    </div>
</template>

<script>
export default {
    name: "ProgramCard",
    props: {
        program: {
            type: Object,
            required: true,
        },
        globalSettings: {
            type: Object,
            required: true,
        },
    },
    data() {
        return {
            isAnimated: false,
            isHovered: false,
        };
    },
    computed: {
        cardStyles() {
            return {
                background: this.globalSettings.cardBackground,
                borderRadius: this.globalSettings.borderRadius,
                border: `${this.globalSettings.borderWidth} solid ${this.program.border_color}`,
                backdropFilter: this.globalSettings.backdropFilter,
                minHeight: this.globalSettings.minHeight,
            };
        },
        btnStyles() {
            // Apply the original hover effect logic
            return {
                background: this.isHovered
                    ? "transparent"
                    : this.program.btn_color,
                border: `2px solid ${this.program.btn_border_color}`,
                color: this.isHovered ? this.program.btn_border_color : "#fff",
            };
        },
    },
    methods: {
        setHover(hoverState) {
            this.isHovered = hoverState;
            // Emit events to parent component if needed
            if (hoverState) {
                this.$emit("hover");
            } else {
                this.$emit("leave");
            }
        },
    },
    mounted() {
        const observer = new IntersectionObserver(
            (entries) => {
                entries.forEach((entry) => {
                    if (entry.isIntersecting) {
                        this.isAnimated = true;
                    }
                });
            },
            { threshold: 0.1 }
        );

        observer.observe(this.$refs.card);
    },
};
</script>

<style scoped>
.program-card {
    border-radius: 15px;
    padding: 22px;
    color: #fff;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    transition: all 0.3s ease;
    width: 100%;
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

/* Original hover effect from the initial design */
.btn-program:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
}
</style>
