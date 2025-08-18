<template>
    <HeaderSection />
    <HeroSection />
    <ProgramsSection />
    <AboutSection />
    <FeaturesSection />
</template>

<script>
import HeaderSection from "../components/home/HeaderSection.vue";
import HeroSection from "../components/home/HeroSection.vue";
import ProgramsSection from "../components/home/ProgramsSection.vue";
import AboutSection from "../components/home/AboutSection.vue";
import FeaturesSection from "../components/home/FeaturesSection.vue";

export default {
    name: "Home",
    components: {
        HeaderSection,
        HeroSection,
        ProgramsSection,
        AboutSection,
        FeaturesSection,
    },
    setup() {
        // Any setup logic can go here
    },
};
</script>

<style></style>
