<template>
    <div class="login-page">
        <div class="login-container">
            <div class="login-card">
                <div class="login-header">
                    <!-- <img
                        :src="settings.logo"
                        :alt="settings.site_name"
                        class="login-logo"
                    /> -->
                    <h2>Admin Login</h2>
                    <p>Access the {{ settings?.site_name || "" }} admin panel</p>
                </div>

                <form @submit.prevent="handleLogin" class="login-form">
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input
                            id="email"
                            type="email"
                            v-model="form.email"
                            required
                            :disabled="loading"
                            placeholder="Enter your email"
                        />
                    </div>

                    <div class="form-group">
                        <label for="password">Password</label>
                        <input
                            id="password"
                            type="password"
                            v-model="form.password"
                            required
                            :disabled="loading"
                            placeholder="Enter your password"
                        />
                    </div>

                    <div v-if="error" class="error-message">
                        {{ error }}
                    </div>

                    <button type="submit" class="login-btn" :disabled="loading">
                        {{ loading ? "Logging in..." : "Login" }}
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

        const loading = computed(() => store.state.auth.loading);
        // const settings = computed(() => store.state.site.settings);
        const isAuthenticated = computed(
            () => store.getters["auth/isAuthenticated"]
        );

        const handleLogin = async () => {
            error.value = "";

            const result = await store.dispatch("auth/login", form.value);

            if (result.success) {
                router.push("/admin");
            } else {
                error.value = result.message;
            }
        };

        onMounted(() => {
            // Redirect if already authenticated
            if (isAuthenticated.value) {
                router.push("/admin");
            }
        });

        return {
            form,
            error,
            loading,
            handleLogin,
        };
    },
};
</script>

<style scoped>
.login-page {
    min-height: 100vh;
    background: linear-gradient(135deg, var(--c0), var(--c3));
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 20px;
}

.login-container {
    width: 100%;
    max-width: 400px;
}

.login-card {
    background: #fff;
    border-radius: var(--border-radius-base);
    padding: 40px;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.1);
}

.login-header {
    text-align: center;
    margin-bottom: 30px;
}

.login-logo {
    width: 120px;
    height: auto;
    margin-bottom: 20px;
}

.login-header h2 {
    color: var(--c3);
    margin-bottom: 10px;
    font-size: 1.8rem;
}

.login-header p {
    color: var(--c8);
    opacity: 0.8;
}

.login-form {
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.form-group {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.form-group label {
    color: var(--c3);
    font-weight: 600;
    font-size: 0.9rem;
}

.form-group input {
    padding: 12px 15px;
    border: 2px solid #e0e0e0;
    border-radius: var(--border-radius-small);
    font-size: 1rem;
    transition: border-color 0.3s ease;
}

.form-group input:focus {
    outline: none;
    border-color: var(--c0);
}

.form-group input:disabled {
    background: #f5f5f5;
    opacity: 0.7;
}

.error-message {
    color: #e74c3c;
    background: #ffeaea;
    padding: 10px 15px;
    border-radius: var(--border-radius-small);
    font-size: 0.9rem;
    text-align: center;
}

.login-btn {
    background: var(--c0);
    color: #fff;
    border: none;
    padding: 15px;
    border-radius: var(--border-radius-small);
    font-size: 1.1rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
}

.login-btn:hover:not(:disabled) {
    background: var(--c1);
    transform: translateY(-2px);
}

.login-btn:disabled {
    opacity: 0.7;
    cursor: not-allowed;
}
</style>
