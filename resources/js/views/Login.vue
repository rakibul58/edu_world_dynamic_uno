<template>
    <div class="login-page">
        <div class="login-container">
            <div class="login-card">
                <div class="login-header">
                    <div class="header-actions">
                        <button @click="goToHome" class="home-btn">
                            <svg
                                width="20"
                                height="20"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="2"
                            >
                                <path
                                    d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"
                                />
                                <polyline points="9,22 9,12 15,12 15,22" />
                            </svg>
                            Home
                        </button>
                    </div>
                    <div class="logo-section">
                        <div class="logo-icon">
                            <svg
                                width="48"
                                height="48"
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
                        <h2>Admin Login</h2>
                        <p>
                            Access the
                            {{ settings?.site_name || "Dashboard" }} admin panel
                        </p>
                    </div>
                </div>

                <form @submit.prevent="handleLogin" class="login-form">
                    <div class="form-group">
                        <label for="email">Email Address</label>
                        <div class="input-wrapper">
                            <svg
                                class="input-icon"
                                width="18"
                                height="18"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="2"
                            >
                                <path
                                    d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"
                                />
                                <polyline points="22,6 12,13 2,6" />
                            </svg>
                            <input
                                id="email"
                                type="email"
                                v-model="form.email"
                                required
                                :disabled="loading"
                                placeholder="Enter your email address"
                            />
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="password">Password</label>
                        <div class="input-wrapper">
                            <svg
                                class="input-icon"
                                width="18"
                                height="18"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="2"
                            >
                                <rect
                                    x="3"
                                    y="11"
                                    width="18"
                                    height="11"
                                    rx="2"
                                    ry="2"
                                />
                                <circle cx="12" cy="16" r="1" />
                                <path d="M7 11V7a5 5 0 0 1 10 0v4" />
                            </svg>
                            <input
                                id="password"
                                :type="showPassword ? 'text' : 'password'"
                                v-model="form.password"
                                required
                                :disabled="loading"
                                placeholder="Enter your password"
                            />
                            <button
                                type="button"
                                @click="togglePassword"
                                class="password-toggle"
                                :disabled="loading"
                            >
                                <svg
                                    v-if="showPassword"
                                    width="18"
                                    height="18"
                                    viewBox="0 0 24 24"
                                    fill="none"
                                    stroke="currentColor"
                                    stroke-width="2"
                                >
                                    <path
                                        d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"
                                    />
                                    <line x1="1" y1="1" x2="23" y2="23" />
                                </svg>
                                <svg
                                    v-else
                                    width="18"
                                    height="18"
                                    viewBox="0 0 24 24"
                                    fill="none"
                                    stroke="currentColor"
                                    stroke-width="2"
                                >
                                    <path
                                        d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"
                                    />
                                    <circle cx="12" cy="12" r="3" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    <div v-if="error" class="error-message">
                        <svg
                            width="16"
                            height="16"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="2"
                        >
                            <circle cx="12" cy="12" r="10" />
                            <line x1="15" y1="9" x2="9" y2="15" />
                            <line x1="9" y1="9" x2="15" y2="15" />
                        </svg>
                        {{ error }}
                    </div>

                    <button type="submit" class="login-btn" :disabled="loading">
                        <span v-if="loading" class="loading-spinner"></span>
                        <svg
                            v-if="!loading"
                            width="18"
                            height="18"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="2"
                        >
                            <path
                                d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"
                            />
                            <polyline points="10,17 15,12 10,7" />
                            <line x1="15" y1="12" x2="3" y2="12" />
                        </svg>
                        {{ loading ? "Signing in..." : "Sign In" }}
                    </button>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
import { ref, computed, onMounted } from "vue";
import { useStore } from "vuex";
import { useRouter } from "vue-router";

export default {
    name: "Login",
    setup() {
        const store = useStore();
        const router = useRouter();

        const form = ref({
            email: "",
            password: "",
        });

        const error = ref("");
        const showPassword = ref(false);

        const loading = computed(() => store.state.auth.loading);
        // const settings = computed(() => store.state.site.settings);
        const isAuthenticated = computed(
            () => store.getters["auth/isAuthenticated"]
        );

        const handleLogin = async () => {
            error.value = "";

            const result = await store.dispatch("auth/login", form.value);

            if (result.success) {
                router.push("/admin/dashboard");
            } else {
                error.value = result.message;
            }
        };

        const togglePassword = () => {
            showPassword.value = !showPassword.value;
        };

        const goToHome = () => {
            router.push("/");
        };

        onMounted(() => {
            // Redirect if already authenticated
            if (isAuthenticated.value) {
                router.push("/admin/dashboard");
            }
        });

        return {
            form,
            error,
            loading,
            showPassword,
            handleLogin,
            togglePassword,
            goToHome,
        };
    },
};
</script>

<style scoped>
.login-page {
    min-height: 100vh;
    background: linear-gradient(135deg, #ff7101 0%, #d35b00 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 20px;
    position: relative;
}

.login-page::before {
    content: "";
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: radial-gradient(
            circle at 30% 20%,
            rgba(255, 255, 255, 0.1) 0%,
            transparent 50%
        ),
        radial-gradient(
            circle at 70% 80%,
            rgba(255, 255, 255, 0.08) 0%,
            transparent 50%
        );
}

.login-container {
    width: 100%;
    max-width: 420px;
    position: relative;
    z-index: 1;
}

.login-card {
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(20px);
    border-radius: 20px;
    padding: 0;
    box-shadow: 0 25px 50px rgba(0, 0, 0, 0.15),
        0 0 0 1px rgba(255, 255, 255, 0.1);
    overflow: hidden;
}

.login-header {
    background: linear-gradient(135deg, #102e4a 0%, #030811 100%);
    padding: 30px 40px;
    position: relative;
}

.header-actions {
    position: absolute;
    top: 20px;
    right: 20px;
}

.home-btn {
    background: rgba(255, 255, 255, 0.1);
    border: 1px solid rgba(255, 255, 255, 0.2);
    color: #ffffff;
    padding: 8px 12px;
    border-radius: 8px;
    font-size: 0.85rem;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    gap: 6px;
}

.home-btn:hover {
    background: rgba(255, 255, 255, 0.2);
    transform: translateY(-1px);
}

.logo-section {
    text-align: center;
    margin-top: 10px;
}

.logo-icon {
    margin-bottom: 20px;
}

.login-header h2 {
    color: #ffffff;
    margin-bottom: 8px;
    font-size: 2rem;
    font-weight: 700;
    text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.login-header p {
    color: rgba(255, 255, 255, 0.8);
    font-size: 0.95rem;
    margin: 0;
}

.login-form {
    padding: 40px;
    display: flex;
    flex-direction: column;
    gap: 24px;
}

.form-group {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.form-group label {
    color: #102e4a;
    font-weight: 600;
    font-size: 0.9rem;
    margin-bottom: 4px;
}

.input-wrapper {
    position: relative;
    display: flex;
    align-items: center;
}

.input-icon {
    position: absolute;
    left: 16px;
    color: #d1d5db;
    pointer-events: none;
    z-index: 1;
}

.input-wrapper input {
    width: 100%;
    padding: 16px 16px 16px 48px;
    border: 2px solid #f8fbfe;
    border-radius: 12px;
    font-size: 1rem;
    background: #f8fbfe;
    color: #102e4a;
    transition: all 0.3s ease;
    box-sizing: border-box;
}

.input-wrapper input:focus {
    outline: none;
    border-color: #ff7101;
    background: #ffffff;
    box-shadow: 0 0 0 3px rgba(255, 113, 1, 0.1);
}

.input-wrapper input:disabled {
    background: #f8fbfe;
    opacity: 0.6;
}

.password-toggle {
    position: absolute;
    right: 16px;
    background: none;
    border: none;
    color: #d1d5db;
    cursor: pointer;
    padding: 4px;
    border-radius: 6px;
    transition: all 0.2s ease;
    display: flex;
    align-items: center;
}

.password-toggle:hover:not(:disabled) {
    color: #ff7101;
    background: rgba(255, 113, 1, 0.1);
}

.password-toggle:disabled {
    opacity: 0.5;
    cursor: not-allowed;
}

.error-message {
    color: #e74c3c;
    background: linear-gradient(135deg, #ffeaea 0%, #ffe0e0 100%);
    padding: 14px 16px;
    border-radius: 10px;
    font-size: 0.9rem;
    display: flex;
    align-items: center;
    gap: 8px;
    border: 1px solid #f8d7da;
}

.login-btn {
    background: linear-gradient(135deg, #ff7101 0%, #d35b00 100%);
    color: #ffffff;
    border: none;
    padding: 16px 24px;
    border-radius: 12px;
    font-size: 1.05rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    position: relative;
    overflow: hidden;
}

.login-btn::before {
    content: "";
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(
        90deg,
        transparent,
        rgba(255, 255, 255, 0.2),
        transparent
    );
    transition: left 0.5s ease;
}

.login-btn:hover:not(:disabled)::before {
    left: 100%;
}

.login-btn:hover:not(:disabled) {
    background: linear-gradient(135deg, #e6640d 0%, #b84d00 100%);
    transform: translateY(-2px);
    box-shadow: 0 10px 25px rgba(255, 113, 1, 0.3);
}

.login-btn:active:not(:disabled) {
    transform: translateY(0);
}

.login-btn:disabled {
    opacity: 0.7;
    cursor: not-allowed;
    transform: none;
}

.loading-spinner {
    width: 18px;
    height: 18px;
    border: 2px solid rgba(255, 255, 255, 0.3);
    border-radius: 50%;
    border-top-color: #ffffff;
    animation: spin 0.8s ease-in-out infinite;
}

@keyframes spin {
    to {
        transform: rotate(360deg);
    }
}

.login-footer {
    text-align: center;
    margin-top: 8px;
}

.forgot-password {
    color: #ff7101;
    text-decoration: none;
    font-size: 0.9rem;
    font-weight: 500;
    transition: all 0.3s ease;
}

.forgot-password:hover {
    color: #d35b00;
    text-decoration: underline;
}

/* Mobile responsiveness */
@media (max-width: 480px) {
    .login-page {
        padding: 10px;
    }

    .login-card {
        border-radius: 16px;
    }

    .login-header {
        padding: 25px 30px;
    }

    .login-form {
        padding: 30px;
        gap: 20px;
    }

    .login-header h2 {
        font-size: 1.7rem;
    }

    .home-btn {
        padding: 6px 10px;
        font-size: 0.8rem;
    }
}
</style>
