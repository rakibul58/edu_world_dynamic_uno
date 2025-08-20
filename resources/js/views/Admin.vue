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
                                <g v-else-if="item.icon === 'orders'">
                                    <path
                                        d="M9 11H5a2 2 0 0 0-2 2v7a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7a2 2 0 0 0-2-2h-4"
                                    />
                                    <rect x="9" y="7" width="6" height="8" />
                                </g>
                                <polyline
                                    v-else-if="item.icon === 'analytics'"
                                    points="22,12 18,12 15,21 9,3 6,12 2,12"
                                />
                                <g v-else-if="item.icon === 'settings'">
                                    <circle cx="12" cy="12" r="3" />
                                    <path
                                        d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"
                                    />
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
                name: "dashboard",
                label: "Dashboard",
                path: "/admin/dashboard",
                icon: "dashboard",
            },
            {
                name: "pageSections",
                label: "Sections Management",
                path: "/admin/sections-management",
                icon: "page-sections",
            },
            {
                name: "headerSection",
                label: "Header Management",
                path: "/admin/manage-header-section",
                icon: "header-section",
            },
            {
                name: "orders",
                label: "Orders",
                path: "/admin/orders",
                icon: "orders",
            },
            {
                name: "analytics",
                label: "Analytics",
                path: "/admin/analytics",
                icon: "analytics",
            },
            {
                name: "settings",
                label: "Settings",
                path: "/admin/settings",
                icon: "settings",
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
                (nav) => routeName && routeName.toLowerCase().includes(nav.name)
            );
            return item ? item.label : "Dashboard";
        });

        const breadcrumbs = computed(() => {
            const routeName = route.name;
            const item = navigationItems.value.find(
                (nav) => routeName && routeName.toLowerCase().includes(nav.name)
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
