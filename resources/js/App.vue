<template>
    <div id="app" :style="cssVariables">
        <router-view />
    </div>
</template>

<script>
import { computed, onMounted } from "vue";
import { useStore } from "vuex";

export default {
    name: "App",
    setup() {
        const store = useStore();

        const settings = computed(() => store.state.site.settings);

        const cssVariables = computed(() => {
            const s = settings.value;
            return {
                "--c0": s.primary_color || "#ff7101",
                "--c1": s.secondary_color || "#102e4a",
                "--c2": s.dark_color || "#030811",
                "--c3": s.secondary_color || "#102e4a",
                "--c4": s.light_color || "#f8fbfe",
                "--c5": "#ffffff",
                "--c6": "#d1d5db",
                "--c7": "#000000",
                "--c8": "#111111",
                "--c9": s.accent_color || "#20bf6b",
                "--c10": s.warning_color || "#f7b731",
                "--c11": s.accent_color || "#20bf6b",
                "--font-family-primary": s.primary_font || "Rubik",
                "--font-size-base": s.font_size_base || "16px",
                "--line-height-base": s.line_height_base || "1.6",
                "--border-radius-base": s.border_radius_base || "15px",
                "--border-radius-small": s.border_radius_small || "5px",
                "--border-radius-large": s.border_radius_large || "25px",
                "--container-max-width": s.container_max_width || "1240px",
            };
        });

        onMounted(() => {
            store.dispatch("site/loadSiteData");
        });

        return {
            cssVariables,
        };
    },
};
</script>

<style>
* {
    box-sizing: border-box;
    margin: 0;
    padding: 0;
    font-family: var(--font-family-primary), sans-serif;
}

body {
    overflow-x: hidden;
    color: #333;
    background: #fff;
    line-height: var(--line-height-base);
    font-size: var(--font-size-base);
}

.container {
    max-width: var(--container-max-width);
    margin: 0 auto;
    padding: 0 20px;
}

/* Animations */
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes slideInLeft {
    from {
        opacity: 0;
        transform: translateX(-30px);
    }
    to {
        opacity: 1;
        transform: translateX(0);
    }
}

.animate-on-scroll {
    opacity: 0;
    transform: translateY(30px);
    transition: all 0.6s ease;
}

.animate-on-scroll.animate {
    opacity: 1;
    transform: translateY(0);
}
</style>
