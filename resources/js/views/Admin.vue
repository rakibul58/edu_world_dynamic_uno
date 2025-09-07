<template>
    <div class="admin-dashboard">
        <!-- Sidebar -->
        <aside
            class="sidebar"
            :class="{ 'sidebar-collapsed': sidebarCollapsed }"
        >
            <div class="sidebar-header">
                <div class="logo">
                    <div class="logo-icon">
                        <svg
                            width="32"
                            height="32"
                            viewBox="0 0 24 24"
                            fill="none"
                        >
                            <circle cx="12" cy="12" r="10" fill="#ff7101" />
                            <path
                                d="M8 12l2 2 4-4"
                                stroke="white"
                                stroke-width="2"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                            />
                        </svg>
                    </div>
                    <span class="logo-text" v-if="!sidebarCollapsed"
                        >Admin Panel</span
                    >
                </div>
                <button
                    @click="toggleSidebar"
                    class="sidebar-toggle"
                    :class="{ rotate: sidebarCollapsed }"
                >
                    <svg
                        width="20"
                        height="20"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2"
                    >
                        <polyline points="15,18 9,12 15,6" />
                    </svg>
                </button>
            </div>

            <nav class="sidebar-nav">
                <ul class="nav-list">
                    <li
                        v-for="item in navigationItems"
                        :key="item.name"
                        class="nav-item"
                    >
                        <router-link
                            :to="item.path"
                            class="nav-link"
                            active-class="active"
                        >
                            <svg
                                class="nav-icon"
                                width="20"
                                height="20"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="2"
                            >
                                <path
                                    v-if="item.icon === 'dashboard'"
                                    d="M3 3h7v9H3V3zm11 0h7v5h-7V3zm0 9h7v9h-7v-9zM3 16h7v5H3v-5z"
                                />
                                <g v-else-if="item.icon === 'page-sections'">
                                    <!-- Top section -->
                                    <rect
                                        x="3"
                                        y="3"
                                        width="18"
                                        height="4"
                                        rx="1"
                                    />
                                    <line x1="6" y1="5" x2="10" y2="5" />

                                    <!-- Middle section with divider -->
                                    <rect
                                        x="3"
                                        y="9"
                                        width="18"
                                        height="6"
                                        rx="1"
                                    />
                                    <line x1="3" y1="12" x2="21" y2="12" />
                                    <line x1="6" y1="10.5" x2="10" y2="10.5" />
                                    <line x1="6" y1="13.5" x2="14" y2="13.5" />

                                    <!-- Bottom section -->
                                    <rect
                                        x="3"
                                        y="17"
                                        width="18"
                                        height="4"
                                        rx="1"
                                    />
                                    <line x1="6" y1="19" x2="12" y2="19" />

                                    <!-- Management dots -->
                                    <circle cx="18" cy="5" r="1" />
                                    <circle cx="18" cy="19" r="1" />
                                </g>
                                <g v-else-if="item.icon === 'header-section'">
                                    <!-- Outer page rectangle -->
                                    <rect
                                        x="3"
                                        y="4"
                                        width="18"
                                        height="16"
                                        rx="2"
                                        ry="2"
                                    />

                                    <!-- Header bar -->
                                    <rect
                                        x="3"
                                        y="4"
                                        width="18"
                                        height="4"
                                        rx="1"
                                        ry="1"
                                    />

                                    <!-- Header text lines -->
                                    <line x1="6" y1="6" x2="10" y2="6" />
                                    <line x1="12" y1="6" x2="16" y2="6" />
                                </g>
                                <g v-else-if="item.icon === 'hero-section'">
                                    <!-- Outer container -->
                                    <rect
                                        x="2"
                                        y="3"
                                        width="20"
                                        height="18"
                                        rx="2"
                                        ry="2"
                                    />

                                    <!-- Hero section background -->
                                    <rect
                                        x="2"
                                        y="3"
                                        width="20"
                                        height="12"
                                        rx="2"
                                        ry="2"
                                        fill="currentColor"
                                        fill-opacity="0.1"
                                    />

                                    <!-- Main headline -->
                                    <line
                                        x1="5"
                                        y1="7"
                                        x2="14"
                                        y2="7"
                                        stroke-width="2.5"
                                    />

                                    <!-- Subtitle -->
                                    <line x1="5" y1="10" x2="11" y2="10" />

                                    <!-- Call-to-action button -->
                                    <rect
                                        x="5"
                                        y="12"
                                        width="4"
                                        height="2"
                                        rx="1"
                                        fill="currentColor"
                                        fill-opacity="0.2"
                                    />

                                    <!-- Hero image placeholder -->
                                    <rect
                                        x="16"
                                        y="5"
                                        width="4"
                                        height="3"
                                        rx="0.5"
                                    />
                                    <circle
                                        cx="17"
                                        cy="6"
                                        r="0.5"
                                        fill="currentColor"
                                        fill-opacity="0.3"
                                    />
                                    <line
                                        x1="16.5"
                                        y1="7.5"
                                        x2="19.5"
                                        y2="7.5"
                                    />

                                    <!-- Content area below hero -->
                                    <line x1="5" y1="17" x2="10" y2="17" />
                                    <line x1="5" y1="19" x2="13" y2="19" />
                                </g>
                                <g v-else-if="item.icon === 'programs-section'">
                                    <!-- Outer container -->
                                    <rect
                                        x="3"
                                        y="3"
                                        width="18"
                                        height="18"
                                        rx="2"
                                        ry="2"
                                    />

                                    <!-- Program card 1 -->
                                    <rect
                                        x="5"
                                        y="5"
                                        width="14"
                                        height="4"
                                        rx="1"
                                    />
                                    <line x1="7" y1="7" x2="12" y2="7" />
                                    <circle cx="16" cy="7" r="0.8" />

                                    <!-- Program card 2 -->
                                    <rect
                                        x="5"
                                        y="10"
                                        width="14"
                                        height="4"
                                        rx="1"
                                    />
                                    <line x1="7" y1="12" x2="13" y2="12" />
                                    <circle cx="16" cy="12" r="0.8" />

                                    <!-- Program card 3 -->
                                    <rect
                                        x="5"
                                        y="15"
                                        width="14"
                                        height="4"
                                        rx="1"
                                    />
                                    <line x1="7" y1="17" x2="11" y2="17" />
                                    <circle cx="16" cy="17" r="0.8" />
                                </g>
                                <g v-else-if="item.icon === 'about-section'">
                                    <!-- Person silhouette -->
                                    <circle cx="12" cy="8" r="3" />
                                    <path d="M17 21v-2a5 5 0 0 0-10 0v2" />

                                    <!-- Information circle -->
                                    <circle
                                        cx="18"
                                        cy="6"
                                        r="1.5"
                                        fill="currentColor"
                                    />
                                    <line x1="18" y1="8" x2="18" y2="10" />
                                </g>
                                <g v-else-if="item.icon === 'cta-section'">
                                    <!-- Outer container -->
                                    <rect
                                        x="3"
                                        y="3"
                                        width="18"
                                        height="18"
                                        rx="1"
                                    />

                                    <!-- Text content -->
                                    <line x1="7" y1="8" x2="17" y2="8" />
                                    <line x1="7" y1="10" x2="14" y2="10" />

                                    <!-- Primary CTA button -->
                                    <rect
                                        x="7"
                                        y="13"
                                        width="6"
                                        height="3"
                                        rx="1.5"
                                        fill="currentColor"
                                        fill-opacity="0.2"
                                    />
                                    <line
                                        x1="9"
                                        y1="14.5"
                                        x2="11"
                                        y2="14.5"
                                        stroke-width="1.5"
                                    />

                                    <!-- Secondary link -->
                                    <line x1="15" y1="14.5" x2="17" y2="14.5" />
                                </g>
                                <g
                                    v-else-if="
                                        item.icon ===
                                        'admission-process-section'
                                    "
                                >
                                    <!-- Outer document -->
                                    <rect
                                        x="3"
                                        y="3"
                                        width="18"
                                        height="18"
                                        rx="2"
                                        ry="2"
                                    />

                                    <!-- Step 1 -->
                                    <circle
                                        cx="7"
                                        cy="8"
                                        r="1"
                                        fill="currentColor"
                                    />
                                    <line x1="10" y1="8" x2="17" y2="8" />

                                    <!-- Step 2 -->
                                    <circle
                                        cx="7"
                                        cy="12"
                                        r="1"
                                        fill="currentColor"
                                    />
                                    <line x1="10" y1="12" x2="17" y2="12" />

                                    <!-- Step 3 -->
                                    <circle
                                        cx="7"
                                        cy="16"
                                        r="1"
                                        fill="currentColor"
                                    />
                                    <line x1="10" y1="16" x2="17" y2="16" />

                                    <!-- Checkmark for completion -->
                                    <path d="M15 19l2 2l4-4" stroke-width="2" />
                                </g>
                                <g v-else-if="item.icon === 'location-section'">
                                    <!-- Outer container -->
                                    <rect
                                        x="3"
                                        y="3"
                                        width="18"
                                        height="18"
                                        rx="2"
                                        ry="2"
                                    />

                                    <!-- Map pin -->
                                    <path
                                        d="M12 7a3 3 0 0 1 3 3c0 2-3 7-3 7s-3-5-3-7a3 3 0 0 1 3-3z"
                                        fill="currentColor"
                                        fill-opacity="0.2"
                                    />
                                    <circle
                                        cx="12"
                                        cy="10"
                                        r="1"
                                        fill="currentColor"
                                    />

                                    <!-- Optional small roads/lines -->
                                    <line
                                        x1="5"
                                        y1="18"
                                        x2="19"
                                        y2="18"
                                        stroke-width="1"
                                        stroke-opacity="0.5"
                                    />
                                    <line
                                        x1="5"
                                        y1="16"
                                        x2="19"
                                        y2="16"
                                        stroke-width="1"
                                        stroke-opacity="0.3"
                                    />
                                </g>
                                <g v-else-if="item.icon === 'feature-section'">
                                    <!-- Outer container -->
                                    <rect
                                        x="3"
                                        y="3"
                                        width="18"
                                        height="18"
                                        rx="2"
                                        ry="2"
                                    />

                                    <!-- Feature block 1 -->
                                    <rect
                                        x="5"
                                        y="5"
                                        width="5"
                                        height="4"
                                        rx="1"
                                    />
                                    <line x1="5" y1="10" x2="10" y2="10" />

                                    <!-- Feature block 2 -->
                                    <rect
                                        x="14"
                                        y="5"
                                        width="5"
                                        height="4"
                                        rx="1"
                                    />
                                    <line x1="14" y1="10" x2="19" y2="10" />

                                    <!-- Feature block 3 -->
                                    <rect
                                        x="5"
                                        y="12"
                                        width="5"
                                        height="4"
                                        rx="1"
                                    />
                                    <line x1="5" y1="17" x2="10" y2="17" />

                                    <!-- Feature block 4 -->
                                    <rect
                                        x="14"
                                        y="12"
                                        width="5"
                                        height="4"
                                        rx="1"
                                    />
                                    <line x1="14" y1="17" x2="19" y2="17" />
                                </g>
                                <g
                                    v-else-if="
                                        item.icon === 'testimonials-section'
                                    "
                                >
                                    <!-- Quote symbol -->
                                    <path d="M7 8h3v3H7zM14 8h3v3h-3z" />
                                    <path d="M7 13h3v4H7zM14 13h3v4h-3z" />
                                </g>

                                <!-- Gallery -->
                                <g v-else-if="item.icon === 'gallery-section'">
                                    <rect
                                        x="3"
                                        y="3"
                                        width="18"
                                        height="18"
                                        rx="2"
                                        ry="2"
                                    />
                                    <circle cx="9" cy="9" r="2" />
                                    <path d="M3 17l5-5 4 4 5-6 4 7" />
                                </g>

                                <!-- Facilities -->
                                <g
                                    v-else-if="
                                        item.icon === 'facilities-section'
                                    "
                                >
                                    <rect
                                        x="3"
                                        y="4"
                                        width="18"
                                        height="16"
                                        rx="2"
                                    />
                                    <path d="M7 20V4M17 20V4" />
                                    <line x1="3" y1="10" x2="21" y2="10" />
                                    <line x1="3" y1="15" x2="21" y2="15" />
                                </g>

                                <!-- Academic Programs -->
                                <g
                                    v-else-if="
                                        item.icon === 'academic-programs'
                                    "
                                >
                                    <path d="M12 3l9 4-9 4-9-4 9-4z" />
                                    <path d="M12 11v10" />
                                    <path d="M5 20h14" />
                                </g>

                                <!-- News & Events -->
                                <g v-else-if="item.icon === 'news-events'">
                                    <rect
                                        x="3"
                                        y="4"
                                        width="18"
                                        height="16"
                                        rx="2"
                                    />
                                    <line x1="7" y1="8" x2="17" y2="8" />
                                    <line x1="7" y1="12" x2="17" y2="12" />
                                    <line x1="7" y1="16" x2="12" y2="16" />
                                    <circle cx="17" cy="16" r="1.2" />
                                </g>

                                <!-- Footer -->
                                <g v-else-if="item.icon === 'footer-section'">
                                    <rect
                                        x="3"
                                        y="4"
                                        width="18"
                                        height="16"
                                        rx="2"
                                    />
                                    <rect
                                        x="3"
                                        y="16"
                                        width="18"
                                        height="4"
                                        rx="1"
                                    />
                                    <line x1="6" y1="18" x2="10" y2="18" />
                                    <line x1="12" y1="18" x2="16" y2="18" />
                                </g>
                            </svg>
                            <span class="nav-text" v-if="!sidebarCollapsed">{{
                                item.label
                            }}</span>
                        </router-link>
                    </li>
                </ul>
            </nav>

            <div class="sidebar-footer">
                <div class="user-info" v-if="!sidebarCollapsed">
                    <div class="user-avatar">
                        <span>{{ userInitials }}</span>
                    </div>
                    <div class="user-details">
                        <div class="user-name">
                            {{ user?.name || "Admin User" }}
                        </div>
                        <div class="user-role">Administrator</div>
                    </div>
                </div>
                <button
                    @click="handleLogout"
                    class="logout-btn"
                    :title="sidebarCollapsed ? 'Logout' : ''"
                >
                    <svg
                        width="18"
                        height="18"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2"
                    >
                        <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4" />
                        <polyline points="16,17 21,12 16,7" />
                        <line x1="21" y1="12" x2="9" y2="12" />
                    </svg>
                    <span v-if="!sidebarCollapsed">Logout</span>
                </button>
            </div>
        </aside>

        <!-- Main Content -->
        <div
            class="main-content"
            :class="{ 'content-expanded': sidebarCollapsed }"
        >
            <!-- Header -->
            <header class="header">
                <div class="header-left">
                    <h1 class="page-title">{{ currentPageTitle }}</h1>
                    <nav class="breadcrumb">
                        <span
                            v-for="(crumb, index) in breadcrumbs"
                            :key="index"
                            class="breadcrumb-item"
                        >
                            {{ crumb }}
                            <svg
                                v-if="index < breadcrumbs.length - 1"
                                width="16"
                                height="16"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="2"
                            >
                                <polyline points="9,18 15,12 9,6" />
                            </svg>
                        </span>
                    </nav>
                </div>

                <div class="header-right">
                    <div class="header-actions">
                        <button
                            v-for="action in headerActions"
                            :key="action.name"
                            class="action-btn"
                            :title="action.title"
                            @click="action.handler"
                        >
                            <svg
                                width="20"
                                height="20"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="2"
                            >
                                <g v-if="action.icon === 'search'">
                                    <circle cx="11" cy="11" r="8" />
                                    <path d="m21 21-4.35-4.35" />
                                </g>
                                <g v-else-if="action.icon === 'external'">
                                    <path
                                        d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"
                                    />
                                    <polyline points="15,3 21,3 21,9" />
                                    <line x1="10" y1="14" x2="21" y2="3" />
                                </g>
                            </svg>
                            <span
                                v-if="action.badge"
                                class="notification-badge"
                                >{{ action.badge }}</span
                            >
                        </button>
                    </div>
                </div>
            </header>

            <!-- Content Area -->
            <main class="content">
                <div class="content-wrapper">
                    <!-- This is where the page content will be rendered -->
                    <router-view />
                </div>
            </main>
        </div>

        <!-- Search Overlay -->
        <div v-if="showSearch" class="search-overlay" @click="toggleSearch">
            <div class="search-modal" @click.stop>
                <div class="search-header">
                    <svg
                        width="20"
                        height="20"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2"
                    >
                        <circle cx="11" cy="11" r="8" />
                        <path d="m21 21-4.35-4.35" />
                    </svg>
                    <input
                        ref="searchInput"
                        v-model="searchQuery"
                        type="text"
                        placeholder="Search anything..."
                        class="search-input"
                        @keyup.esc="toggleSearch"
                    />
                    <button @click="toggleSearch" class="search-close">
                        <svg
                            width="20"
                            height="20"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="2"
                        >
                            <line x1="18" y1="6" x2="6" y2="18" />
                            <line x1="6" y1="6" x2="18" y2="18" />
                        </svg>
                    </button>
                </div>
                <div class="search-results">
                    <div class="search-placeholder">
                        <p>Start typing to search...</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { ref, computed, nextTick, onMounted } from "vue";
import { useStore } from "vuex";
import { useRouter, useRoute } from "vue-router";

export default {
    name: "Admin",
    setup() {
        const store = useStore();
        const router = useRouter();
        const route = useRoute();

        const sidebarCollapsed = ref(false);
        const showSearch = ref(false);
        const searchQuery = ref("");
        const searchInput = ref(null);

        // Dynamic navigation items - easily customizable
        const navigationItems = ref([
            {
                name: "Dashboard",
                label: "Dashboard",
                path: "/admin/dashboard",
                icon: "dashboard",
            },
            {
                name: "SectionsManagement",
                label: "Sections Management",
                path: "/admin/sections-management",
                icon: "page-sections",
            },
            {
                name: "HeaderSection",
                label: "Header Management",
                path: "/admin/manage-header-section",
                icon: "header-section",
            },
            {
                name: "HeroSection",
                label: "Hero Management",
                path: "/admin/manage-hero-section",
                icon: "hero-section",
            },
            {
                name: "ProgramsSection",
                label: "Programs Management",
                path: "/admin/manage-programs-section",
                icon: "programs-section",
            },
            {
                name: "AboutSection",
                label: "About Management",
                path: "/admin/manage-about-section",
                icon: "about-section",
            },
            {
                name: "FeatureSection",
                label: "Feature Management",
                path: "/admin/manage-feature-section",
                icon: "feature-section",
            },
            {
                name: "TestimonialSection",
                label: "Testimonial Management",
                path: "/admin/manage-testimonial-section",
                icon: "testimonials-section",
            },
            {
                name: "GallerySection",
                label: "Gallery Management",
                path: "/admin/manage-gallery-section",
                icon: "gallery-section",
            },
            {
                name: "FacilitiesSection",
                label: "Facilities Management",
                path: "/admin/manage-facilities-section",
                icon: "facilities-section",
            },
            {
                name: "AcademicPrograms",
                label: "Academic Programs",
                path: "/admin/manage-academic-programs",
                icon: "academic-programs",
            },
            {
                name: "NewsEvents",
                label: "News & Events",
                path: "/admin/manage-news-events-section",
                icon: "news-events",
            },
            {
                name: "LocationSection",
                label: "Location Management",
                path: "/admin/manage-location-section",
                icon: "location-section",
            },
            {
                name: "AdmissionProcessSection",
                label: "Admission Process",
                path: "/admin/manage-admission-process-section",
                icon: "admission-process-section",
            },
            {
                name: "CtaSection",
                label: "CTA Management",
                path: "/admin/manage-cta-section",
                icon: "cta-section",
            },
            {
                name: "FooterSection",
                label: "Footer Management",
                path: "/admin/manage-footer-section",
                icon: "footer-section",
            },
        ]);

        // Dynamic header actions - easily customizable
        const headerActions = ref([
            {
                name: "search",
                title: "Search",
                icon: "search",
                handler: () => toggleSearch(),
            },
            {
                name: "visit-site",
                title: "Visit Site",
                icon: "external",
                handler: () => goToHome(),
            },
        ]);

        const user = computed(() => store.state.auth.user);

        const userInitials = computed(() => {
            const name = user.value?.name || "Admin User";
            return name
                .split(" ")
                .map((n) => n[0])
                .join("")
                .toUpperCase();
        });

        const currentPageTitle = computed(() => {
            const routeName = route.name;
            const item = navigationItems.value.find(
                (nav) =>
                    routeName &&
                    routeName.toLowerCase().includes(nav.name.toLowerCase())
            );
            return item ? item.label : "Dashboard";
        });

        const breadcrumbs = computed(() => {
            const routeName = route.name;
            console.log("Current Route Name:", routeName); // Debugging line
            const item = navigationItems.value.find(
                (nav) =>
                    routeName &&
                    routeName.toLowerCase().includes(nav.name.toLowerCase())
            );
            return item ? ["Admin", item.label] : ["Admin", "Dashboard"];
        });

        const toggleSidebar = () => {
            sidebarCollapsed.value = !sidebarCollapsed.value;
            localStorage.setItem(
                "sidebarCollapsed",
                sidebarCollapsed.value.toString()
            );
        };

        const toggleSearch = async () => {
            showSearch.value = !showSearch.value;
            if (showSearch.value) {
                await nextTick();
                searchInput.value?.focus();
            } else {
                searchQuery.value = "";
            }
        };

        const handleLogout = async () => {
            await store.dispatch("auth/logout");
            router.push("/login");
        };

        const goToHome = () => {
            window.open("/", "_blank");
        };

        onMounted(() => {
            const saved = localStorage.getItem("sidebarCollapsed");
            if (saved !== null) {
                sidebarCollapsed.value = saved === "true";
            }
        });

        return {
            sidebarCollapsed,
            showSearch,
            searchQuery,
            searchInput,
            user,
            userInitials,
            currentPageTitle,
            breadcrumbs,
            navigationItems,
            headerActions,
            toggleSidebar,
            toggleSearch,
            handleLogout,
            goToHome,
        };
    },
};
</script>

<style scoped>
.admin-dashboard {
    display: flex;
    min-height: 100vh;
    background: #f8fbfe;
}

/* Sidebar */
.sidebar {
    width: 280px;
    background: linear-gradient(180deg, #102e4a 0%, #030811 100%);
    color: #ffffff;
    display: flex;
    flex-direction: column;
    position: fixed;
    left: 0;
    top: 0;
    height: 100vh;
    z-index: 1000;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    box-shadow: 4px 0 20px rgba(0, 0, 0, 0.1);
}

.sidebar-collapsed {
    width: 80px;
}

.sidebar-header {
    padding: 24px 20px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    position: relative;
}

.logo {
    display: flex;
    align-items: center;
    gap: 12px;
}

.logo-icon {
    flex-shrink: 0;
}

.logo-text {
    font-size: 1.25rem;
    font-weight: 700;
    white-space: nowrap;
}

.sidebar-toggle {
    background: rgba(255, 255, 255, 0.1);
    border: none;
    color: #ffffff;
    width: 36px;
    height: 36px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.3s ease;
}

.sidebar-toggle:hover {
    background: rgba(255, 255, 255, 0.2);
}

.sidebar-toggle.rotate {
    transform: rotate(180deg);
}

.sidebar-nav {
    flex: 1;
    padding: 20px 0;
    overflow-y: auto;
}

.nav-list {
    list-style: none;
    padding: 0;
    margin: 0;
}

.nav-item {
    margin-bottom: 4px;
}

.nav-link {
    display: flex;
    align-items: center;
    gap: 16px;
    padding: 14px 20px;
    color: rgba(255, 255, 255, 0.8);
    text-decoration: none;
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
}

.nav-link::before {
    content: "";
    position: absolute;
    top: 0;
    left: 0;
    width: 4px;
    height: 100%;
    background: #ff7101;
    transform: scaleY(0);
    transition: transform 0.3s ease;
}

.nav-link:hover {
    background: rgba(255, 255, 255, 0.1);
    color: #ffffff;
}

.nav-link.active {
    background: rgba(255, 113, 1, 0.2);
    color: #ff7101;
}

.nav-link.active::before {
    transform: scaleY(1);
}

.nav-icon {
    flex-shrink: 0;
    width: 20px;
    height: 20px;
}

.nav-text {
    white-space: nowrap;
}

.sidebar-footer {
    padding: 20px;
    border-top: 1px solid rgba(255, 255, 255, 0.1);
}

.user-info {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 16px;
    padding: 12px;
    background: rgba(255, 255, 255, 0.05);
    border-radius: 12px;
}

.user-avatar {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: #ff7101;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    font-size: 0.9rem;
    flex-shrink: 0;
}

.user-details {
    flex: 1;
    min-width: 0;
}

.user-name {
    font-weight: 600;
    font-size: 0.9rem;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.user-role {
    font-size: 0.8rem;
    color: rgba(255, 255, 255, 0.7);
}

.logout-btn {
    width: 100%;
    background: rgba(255, 113, 1, 0.1);
    border: 1px solid rgba(255, 113, 1, 0.3);
    color: #ff7101;
    padding: 12px 16px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    cursor: pointer;
    transition: all 0.3s ease;
    font-weight: 500;
}

.logout-btn:hover {
    background: rgba(255, 113, 1, 0.2);
    border-color: rgba(255, 113, 1, 0.5);
}

/* Main Content */
.main-content {
    flex: 1;
    margin-left: 280px;
    display: flex;
    flex-direction: column;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.content-expanded {
    margin-left: 80px;
}

.header {
    background: #ffffff;
    padding: 20px 32px;
    border-bottom: 1px solid #d1d5db;
    display: flex;
    align-items: center;
    justify-content: space-between;
    position: sticky;
    top: 0;
    z-index: 100;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
}

.header-left {
    flex: 1;
}

.page-title {
    font-size: 1.75rem;
    font-weight: 700;
    color: #102e4a;
    margin: 0 0 4px 0;
}

.breadcrumb {
    display: flex;
    align-items: center;
    gap: 8px;
    color: #d1d5db;
    font-size: 0.9rem;
}

.breadcrumb-item {
    display: flex;
    align-items: center;
    gap: 8px;
}

.header-right {
    display: flex;
    align-items: center;
}

.header-actions {
    display: flex;
    align-items: center;
    gap: 12px;
}

.action-btn {
    width: 44px;
    height: 44px;
    border-radius: 12px;
    border: none;
    background: #f8fbfe;
    color: #102e4a;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.3s ease;
    position: relative;
}

.action-btn:hover {
    background: #ff7101;
    color: #ffffff;
    transform: translateY(-2px);
}

.notification-badge {
    position: absolute;
    top: -4px;
    right: -4px;
    background: #21bf6b;
    color: #ffffff;
    font-size: 0.7rem;
    font-weight: 700;
    width: 18px;
    height: 18px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    border: 2px solid #ffffff;
}

.content {
    flex: 1;
    padding: 32px;
    overflow-y: auto;
}

.content-wrapper {
    max-width: 1200px;
    margin: 0 auto;
}

/* Search Overlay */
.search-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.5);
    display: flex;
    align-items: flex-start;
    justify-content: center;
    padding-top: 15vh;
    z-index: 2000;
    backdrop-filter: blur(4px);
}

.search-modal {
    background: #ffffff;
    border-radius: 16px;
    width: 100%;
    max-width: 600px;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
    overflow: hidden;
    margin: 0 20px;
}

.search-header {
    display: flex;
    align-items: center;
    padding: 20px 24px;
    border-bottom: 1px solid #d1d5db;
    gap: 16px;
}

.search-input {
    flex: 1;
    border: none;
    outline: none;
    font-size: 1.1rem;
    color: #102e4a;
}

.search-close {
    background: none;
    border: none;
    color: #d1d5db;
    cursor: pointer;
    padding: 4px;
    border-radius: 4px;
    transition: color 0.3s ease;
}

.search-close:hover {
    color: #102e4a;
}

.search-results {
    padding: 24px;
    min-height: 200px;
}

.search-placeholder {
    text-align: center;
    color: #d1d5db;
    padding: 40px 0;
}

/* Mobile Responsiveness */
@media (max-width: 768px) {
    .sidebar {
        transform: translateX(-100%);
    }

    .sidebar.sidebar-open {
        transform: translateX(0);
    }

    .main-content {
        margin-left: 0;
    }

    .content-expanded {
        margin-left: 0;
    }

    .header {
        padding: 16px 20px;
    }

    .page-title {
        font-size: 1.5rem;
    }

    .content {
        padding: 20px;
    }
}
</style>
