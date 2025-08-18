<template>
    <HeaderSection />
    <HeroSection />
    <ProgramsSection />
    <AboutSection />
    <FeaturesSection />
    <TestimonialsSection />
    <GallerySection />
    <FacilitiesSection />
    <AcademicPrograms />
    <NewsEvents />
    <LocationSection />
    <AdmissionProcess />
    <CtaSection />
    <FooterSection />
</template>

<script>
import HeaderSection from "../components/home/HeaderSection.vue";
import HeroSection from "../components/home/HeroSection.vue";
import ProgramsSection from "../components/home/ProgramsSection.vue";
import AboutSection from "../components/home/AboutSection.vue";
import FeaturesSection from "../components/home/FeaturesSection.vue";
import TestimonialsSection from "../components/home/TestimonialsSection.vue";
import GallerySection from "../components/home/GallerySection.vue";
import FacilitiesSection from "../components/home/FacilitiesSection.vue";
import AcademicPrograms from '../components/home/AcademicPrograms.vue';
import NewsEvents from "../components/home/NewsEvents.vue";
import LocationSection from "../components/home/LocationSection.vue";
import AdmissionProcess from "../components/home/AdmissionProcess.vue";
import CtaSection from "../components/home/CtaSection.vue";
import FooterSection from "../components/home/FooterSection.vue";

export default {
    name: "Home",
    components: {
        HeaderSection,
        HeroSection,
        ProgramsSection,
        AboutSection,
        FeaturesSection,
        TestimonialsSection,
        GallerySection,
        FacilitiesSection,
        AcademicPrograms,
        NewsEvents,
        LocationSection,
        AdmissionProcess,
        CtaSection,
        FooterSection,
    },
    setup() {
        // Any setup logic can go here
    },
};
</script>

<style></style>
