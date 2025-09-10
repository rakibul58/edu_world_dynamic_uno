// Import components
import Home from "../views/Home.vue";
import Admin from "../views/Admin.vue";
import Login from "../views/Login.vue";

const routes = [
    { path: "/", name: "Home", component: Home },
    {
        path: "/admin",
        name: "Admin",
        component: Admin,
        meta: { requiresAuth: true },
        children: [
            {
                path: "dashboard",
                name: "Dashboard",
                component: () => import("../components/admin/Dashboard.vue"),
            },
            {
                path: "sections-management",
                name: "SectionsManagement",
                component: () =>
                    import("../components/admin/SectionManager.vue"),
            },
            {
                path: "manage-header-section",
                name: "HeaderSection",
                component: () =>
                    import("../components/admin/HeaderSectionManagement.vue"),
            },
            {
                path: "manage-hero-section",
                name: "HeroSection",
                component: () =>
                    import("../components/admin/HeroSectionManagement.vue"),
            },
            {
                path: "manage-programs-section",
                name: "ProgramsSection",
                component: () =>
                    import("../components/admin/ProgramsSectionManagement.vue"),
            },
            {
                path: "manage-about-section",
                name: "AboutSection",
                component: () =>
                    import("../components/admin/AboutSectionManagement.vue"),
            },
            {
                path: "manage-cta-section",
                name: "CtaSection",
                component: () =>
                    import("../components/admin/CtaSectionManagement.vue"),
            },
            {
                path: "manage-admission-process-section",
                name: "AdmissionProcessSection",
                component: () =>
                    import(
                        "../components/admin/AdmissionProcessSectionManagement.vue"
                    ),
            },
            {
                path: "manage-location-section",
                name: "LocationSection",
                component: () =>
                    import("../components/admin/LocationSectionManagement.vue"),
            },
            {
                path: "manage-feature-section",
                name: "FeatureSection",
                component: () =>
                    import("../components/admin/FeatureSectionManagement.vue"),
            },
            {
                path: "manage-footer-section",
                name: "FooterSection",
                component: () =>
                    import("../components/admin/FooterSectionManagement.vue"),
            },
            {
                path: "manage-news-events-section",
                name: "NewsEventsSection",
                component: () => import("../components/admin/NewsEventsSectionManagement.vue"),
            },
            {
                path: "manage-academic-programs",
                name: "AcademicPrograms",
                component: () => import("../components/admin/AcademicProgramsManagement.vue"),
            },
            {
                path: "manage-testimonial-section",
                name: "TestimonialSection",
                component: () => import("../components/admin/TestimonialSectionManagement.vue"),
            },
            {
                path: "manage-facilities-section",
                name: "FacilitiesSection",
                component: () => import("../components/admin/FacilitiesSectionManagement.vue"),
            }
        ],
    },
    { path: "/login", name: "Login", component: Login },
];

export default routes;
