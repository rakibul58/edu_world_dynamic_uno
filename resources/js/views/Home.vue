<template>
    <HeaderSection />
    <HeroSection />
</template>

<script>
import HeaderSection from "../components/home/HeaderSection.vue";
import HeroSection from "../components/home/HeroSection.vue";
export default {
    name: "Home",
    components: {
        HeaderSection,
        HeroSection,
    },
    setup() {
        // Any setup logic can go here
    },
};
</script>

<style></style>
