<template>
    <div class="dashboard-welcome">
        <!-- Welcome Header -->
        <div class="welcome-header">
            <div class="welcome-content">
                <div class="greeting">
                    <h1 class="welcome-title">
                        Welcome back,
                        <span class="user-highlight">{{ userName }}</span
                        >! 👋
                    </h1>
                    <p class="welcome-subtitle">
                        You're successfully logged in to your admin dashboard.
                        Here's what you can do today.
                    </p>
                </div>
                <div class="welcome-stats">
                    <div class="current-time">
                        <div class="time-display">{{ currentTime }}</div>
                        <div class="date-display">{{ currentDate }}</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="quick-actions-section">
            <h2 class="section-title">Quick Actions</h2>
            <div class="actions-grid">
                <div
                    v-for="action in quickActions"
                    :key="action.name"
                    class="action-card"
                    @click="handleActionClick(action)"
                    :class="{ 'action-disabled': action.disabled }"
                >
                    <div
                        class="action-icon"
                        :style="{ backgroundColor: action.color }"
                    >
                        <svg
                            width="24"
                            height="24"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="2"
                        >
                            <!-- Dashboard Icon -->
                            <g v-if="action.icon === 'dashboard'">
                                <rect x="3" y="3" width="7" height="9" />
                                <rect x="14" y="3" width="7" height="5" />
                                <rect x="14" y="12" width="7" height="9" />
                                <rect x="3" y="16" width="7" height="5" />
                            </g>
                            <g v-else-if="action.icon === 'page-sections'">
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
                            <g v-else-if="action.icon === 'header-section'">
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
                            <g v-else-if="action.icon === 'hero-section'">
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
                                <line x1="16.5" y1="7.5" x2="19.5" y2="7.5" />

                                <!-- Content area below hero -->
                                <line x1="5" y1="17" x2="10" y2="17" />
                                <line x1="5" y1="19" x2="13" y2="19" />
                            </g>
                            <g v-else-if="action.icon === 'programs-section'">
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

                            <!-- Analytics Icon -->
                            <g v-else-if="action.icon === 'trending-up'">
                                <polyline
                                    points="22,12 18,12 15,21 9,3 6,12 2,12"
                                />
                            </g>
                            <!-- Settings Icon -->
                            <g v-else-if="action.icon === 'settings'">
                                <circle cx="12" cy="12" r="3" />
                                <path
                                    d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"
                                />
                            </g>
                            <!-- Report Icon -->
                            <g v-else-if="action.icon === 'file-text'">
                                <path
                                    d="M14,2H6a2,2 0 0,0 -2,2V20a2,2 0 0,0 2,2H18a2,2 0 0,0 2,-2V8z"
                                />
                                <polyline points="14,2 14,8 20,8" />
                                <line x1="16" y1="13" x2="8" y2="13" />
                                <line x1="16" y1="17" x2="8" y2="17" />
                                <polyline points="10,9 9,9 8,9" />
                            </g>
                            <!-- Help Icon -->
                            <g v-else-if="action.icon === 'help-circle'">
                                <circle cx="12" cy="12" r="10" />
                                <path
                                    d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"
                                />
                                <point x="12" y="17" />
                            </g>
                        </svg>
                    </div>
                    <div class="action-content">
                        <h3 class="action-title">{{ action.title }}</h3>
                        <p class="action-description">
                            {{ action.description }}
                        </p>
                    </div>
                    <div class="action-arrow">
                        <svg
                            width="16"
                            height="16"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="2"
                        >
                            <polyline points="9,18 15,12 9,6" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Navigation Links -->
        <div class="navigation-section">
            <h2 class="section-title">Navigate to</h2>
            <div class="nav-links-grid">
                <router-link
                    v-for="link in navigationLinks"
                    :key="link.name"
                    :to="link.path"
                    class="nav-card"
                    :class="{ 'nav-disabled': link.disabled }"
                >
                    <div
                        class="nav-icon"
                        :style="{ backgroundColor: link.color }"
                    >
                        <svg
                            width="20"
                            height="20"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="2"
                        >
                            <!-- Users Icon -->
                            <g v-if="link.icon === 'users'">
                                <path
                                    d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"
                                />
                                <circle cx="12" cy="7" r="4" />
                            </g>
                            <!-- Products Icon -->
                            <g v-else-if="link.icon === 'package'">
                                <path
                                    d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"
                                />
                                <line x1="3" y1="6" x2="21" y2="6" />
                                <path d="M16 10a4 4 0 0 1-8 0" />
                            </g>
                            <!-- Orders Icon -->
                            <g v-else-if="link.icon === 'shopping-bag'">
                                <path
                                    d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"
                                />
                                <line x1="3" y1="6" x2="21" y2="6" />
                                <path d="M16 10a4 4 0 0 1-8 0" />
                            </g>
                            <!-- Analytics Icon -->
                            <polyline
                                v-else-if="link.icon === 'bar-chart'"
                                points="18,20 18,10 14,10 14,20 10,20 10,4 6,4 6,20"
                            />
                            <!-- Settings Icon -->
                            <g v-else-if="link.icon === 'cog'">
                                <circle cx="12" cy="12" r="3" />
                                <path
                                    d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"
                                />
                            </g>
                        </svg>
                    </div>
                    <div class="nav-content">
                        <h4 class="nav-title">{{ link.title }}</h4>
                        <p class="nav-description">{{ link.description }}</p>
                    </div>
                </router-link>
            </div>
        </div>

        <!-- Status Section -->
        <div class="status-section">
            <div class="status-card">
                <div class="status-icon">
                    <svg
                        width="24"
                        height="24"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="#21bf6b"
                        stroke-width="2"
                    >
                        <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14" />
                        <polyline points="22,4 12,14.01 9,11.01" />
                    </svg>
                </div>
                <div class="status-content">
                    <h3 class="status-title">System Status</h3>
                    <p class="status-description">
                        All systems are running normally
                    </p>
                    <div class="status-indicator">
                        <span class="status-dot online"></span>
                        <span class="status-text">Online</span>
                    </div>
                </div>
            </div>

            <div class="welcome-note">
                <div class="note-icon">
                    <svg
                        width="20"
                        height="20"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="#ff7101"
                        stroke-width="2"
                    >
                        <circle cx="12" cy="12" r="10" />
                        <path d="M12 6v6l4 2" />
                    </svg>
                </div>
                <div class="note-content">
                    <p><strong>Last login:</strong> {{ lastLoginTime }}</p>
                    <p class="note-sub">You have full administrative access</p>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { ref, computed, onMounted, onUnmounted } from "vue";
import { useStore } from "vuex";
import { useRouter } from "vue-router";

export default {
    name: "Dashboard",
    setup() {
        const store = useStore();
        const router = useRouter();

        const currentTime = ref("");
        const currentDate = ref("");
        const timeInterval = ref(null);

        const user = computed(() => store.state.auth.user);

        const userName = computed(() => {
            return user.value?.name || "Admin";
        });

        const lastLoginTime = computed(() => {
            // You can get this from your user data or store
            return "Today at 9:30 AM";
        });

        // Dynamic Quick Actions - Easy to modify
        const quickActions = ref([
            {
                name: "sections-management",
                title: "Manage Sections",
                description: "Reorganize your dashboard sections",
                icon: "page-sections",
                color: "#21bf6b",
                action: "navigate",
                target: "/admin/sections-management",
                disabled: false,
            },
            {
                name: "headerSection",
                title: "Manage Header",
                description: "Add new product to catalog",
                icon: "header-section",
                color: "#ff7101",
                action: "navigate",
                target: "/admin/manage-header-section",
                disabled: false,
            },
            {
                name: "heroSection",
                title: "Manage Hero",
                description: "Update hero section content",
                icon: "hero-section",
                color: "#f7b731",
                action: "navigate",
                target: "/admin/manage-hero-section",
                disabled: false,
            },
               {
                name: "programsSection",
                title: "Manage Programs",
                description: "Update Programs section content",
                icon: "programs-section",
                color: "#a5c711",
                action: "navigate",
                target: "/admin/manage-programs-section",
                disabled: false,
            }
        ]);

        // Dynamic Navigation Links - Easy to modify
        const navigationLinks = ref([
            {
                name: "users",
                title: "User Management",
                description: "Manage user accounts and permissions",
                icon: "users",
                color: "#21bf6b",
                path: "/admin/users",
                disabled: true,
            },
            {
                name: "products",
                title: "Product Catalog",
                description: "Manage your product inventory",
                icon: "package",
                color: "#ff7101",
                path: "/admin/products",
                disabled: true,
            },

            {
                name: "orders",
                title: "Order Management",
                description: "Process and track customer orders",
                icon: "shopping-bag",
                color: "#f7b731",
                path: "/admin/orders",
                disabled: true,
            },
            {
                name: "analytics",
                title: "Analytics Dashboard",
                description: "View detailed performance metrics",
                icon: "bar-chart",
                color: "#3742fa",
                path: "/admin/analytics",
                disabled: true,
            },
            {
                name: "settings",
                title: "System Settings",
                description: "Configure application preferences",
                icon: "cog",
                color: "#102e4a",
                path: "/admin/settings",
                disabled: true,
            },
        ]);

        const updateTime = () => {
            const now = new Date();
            currentTime.value = now.toLocaleTimeString("en-US", {
                hour: "2-digit",
                minute: "2-digit",
                second: "2-digit",
                hour12: true,
            });
            currentDate.value = now.toLocaleDateString("en-US", {
                weekday: "long",
                year: "numeric",
                month: "long",
                day: "numeric",
            });
        };

        const handleActionClick = (action) => {
            if (action.disabled) return;

            if (action.action === "navigate") {
                router.push(action.target);
            } else if (action.action === "custom") {
                // Handle custom actions
                if (action.handler === "generateReport") {
                    generateReport();
                }
            }
        };

        const generateReport = () => {
            // Custom report generation logic
            console.log("Generating report...");
            // You can add modal, API call, etc.
            alert("Report generation feature coming soon!");
        };

        onMounted(() => {
            updateTime();
            timeInterval.value = setInterval(updateTime, 1000);
        });

        onUnmounted(() => {
            if (timeInterval.value) {
                clearInterval(timeInterval.value);
            }
        });

        return {
            userName,
            currentTime,
            currentDate,
            lastLoginTime,
            quickActions,
            navigationLinks,
            handleActionClick,
        };
    },
};
</script>

<style scoped>
.dashboard-welcome {
    padding: 0;
}

/* Welcome Header */
.welcome-header {
    background: linear-gradient(135deg, #ff7101 0%, #d35b00 100%);
    border-radius: 20px;
    padding: 40px;
    margin-bottom: 32px;
    color: #ffffff;
    position: relative;
    overflow: hidden;
}

.welcome-header::before {
    content: "";
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: radial-gradient(
            circle at 20% 30%,
            rgba(255, 255, 255, 0.1) 0%,
            transparent 50%
        ),
        radial-gradient(
            circle at 80% 70%,
            rgba(255, 255, 255, 0.08) 0%,
            transparent 50%
        );
}

.welcome-content {
    position: relative;
    z-index: 1;
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 40px;
}

.greeting {
    flex: 1;
}

.welcome-title {
    font-size: 2.5rem;
    font-weight: 700;
    margin: 0 0 16px 0;
    text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.user-highlight {
    color: #f8fbfe;
    text-decoration: underline;
    text-decoration-color: rgba(255, 255, 255, 0.3);
}

.welcome-subtitle {
    font-size: 1.1rem;
    margin: 0;
    opacity: 0.9;
    line-height: 1.6;
}

.welcome-stats {
    text-align: right;
}

.current-time {
    background: rgba(255, 255, 255, 0.15);
    backdrop-filter: blur(10px);
    border-radius: 16px;
    padding: 20px 24px;
    border: 1px solid rgba(255, 255, 255, 0.2);
}

.time-display {
    font-size: 2rem;
    font-weight: 700;
    margin-bottom: 4px;
    font-family: "Monaco", "Menlo", monospace;
}

.date-display {
    font-size: 0.9rem;
    opacity: 0.8;
}

/* Quick Actions Section */
.quick-actions-section {
    margin-bottom: 40px;
}

.section-title {
    font-size: 1.5rem;
    font-weight: 700;
    color: #102e4a;
    margin: 0 0 24px 0;
}

.actions-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 20px;
}

.action-card {
    background: #ffffff;
    border-radius: 16px;
    padding: 24px;
    display: flex;
    align-items: center;
    gap: 16px;
    cursor: pointer;
    transition: all 0.3s ease;
    border: 1px solid #f8fbfe;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
}

.action-card:hover:not(.action-disabled) {
    transform: translateY(-4px);
    box-shadow: 0 12px 30px rgba(0, 0, 0, 0.15);
    border-color: #d1d5db;
}

.action-disabled {
    opacity: 0.5;
    cursor: not-allowed;
}

.action-icon {
    width: 48px;
    height: 48px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #ffffff;
    flex-shrink: 0;
}

.action-content {
    flex: 1;
}

.action-title {
    font-size: 1.1rem;
    font-weight: 600;
    color: #102e4a;
    margin: 0 0 4px 0;
}

.action-description {
    font-size: 0.9rem;
    color: #d1d5db;
    margin: 0;
}

.action-arrow {
    color: #d1d5db;
    transition: all 0.3s ease;
}

.action-card:hover:not(.action-disabled) .action-arrow {
    color: #ff7101;
    transform: translateX(4px);
}

/* Navigation Section */
.navigation-section {
    margin-bottom: 40px;
}

.nav-links-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 16px;
}

.nav-card {
    background: #ffffff;
    border-radius: 12px;
    padding: 20px;
    display: flex;
    align-items: center;
    gap: 16px;
    text-decoration: none;
    color: inherit;
    transition: all 0.3s ease;
    border: 1px solid #f8fbfe;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
}

.nav-card:hover:not(.nav-disabled) {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
    border-color: #d1d5db;
}

.nav-disabled {
    opacity: 0.5;
    pointer-events: none;
}

.nav-icon {
    width: 40px;
    height: 40px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #ffffff;
    flex-shrink: 0;
}

.nav-content {
    flex: 1;
}

.nav-title {
    font-size: 1rem;
    font-weight: 600;
    color: #102e4a;
    margin: 0 0 4px 0;
}

.nav-description {
    font-size: 0.85rem;
    color: #d1d5db;
    margin: 0;
}

/* Status Section */
.status-section {
    display: grid;
    grid-template-columns: 2fr 1fr;
    gap: 24px;
    margin-bottom: 20px;
}

.status-card {
    background: linear-gradient(135deg, #f8fbfe 0%, #ffffff 100%);
    border-radius: 16px;
    padding: 24px;
    display: flex;
    align-items: center;
    gap: 16px;
    border: 1px solid #d1d5db;
}

.status-icon {
    width: 48px;
    height: 48px;
    background: rgba(33, 191, 107, 0.1);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.status-content {
    flex: 1;
}

.status-title {
    font-size: 1.1rem;
    font-weight: 600;
    color: #102e4a;
    margin: 0 0 4px 0;
}

.status-description {
    font-size: 0.9rem;
    color: #d1d5db;
    margin: 0 0 8px 0;
}

.status-indicator {
    display: flex;
    align-items: center;
    gap: 8px;
}

.status-dot {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    flex-shrink: 0;
}

.status-dot.online {
    background: #21bf6b;
}

.status-text {
    font-size: 0.85rem;
    font-weight: 500;
    color: #21bf6b;
}

.welcome-note {
    background: #ffffff;
    border-radius: 16px;
    padding: 20px;
    display: flex;
    align-items: flex-start;
    gap: 12px;
    border: 1px solid #f8fbfe;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
}

.note-icon {
    width: 36px;
    height: 36px;
    background: rgba(255, 113, 1, 0.1);
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.note-content p {
    font-size: 0.9rem;
    color: #102e4a;
    margin: 0 0 4px 0;
}

.note-sub {
    color: #d1d5db !important;
    font-size: 0.8rem !important;
}

/* Mobile Responsiveness */
@media (max-width: 768px) {
    .welcome-header {
        padding: 24px;
    }

    .welcome-content {
        flex-direction: column;
        gap: 24px;
    }

    .welcome-title {
        font-size: 2rem;
    }

    .welcome-stats {
        text-align: center;
    }

    .actions-grid {
        grid-template-columns: 1fr;
    }

    .nav-links-grid {
        grid-template-columns: 1fr;
    }

    .status-section {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 480px) {
    .welcome-title {
        font-size: 1.75rem;
    }

    .action-card {
        padding: 16px;
    }

    .nav-card {
        padding: 16px;
    }
}
</style>
