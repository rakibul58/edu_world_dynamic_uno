<template>
    <nav class="navbar-top">
        <div class="container nav-container">
            <a class="nav-logo" href="#"
                ><img src="/public/uploads/image/logo.png" alt="Site Logo"
            /></a>
            <div class="top-actions">
                <a href="#" class="nav-btn nav-btn-primary">Enroll Now</a>
                <a href="#" class="nav-btn nav-btn-secondary">Contact us</a>
            </div>
        </div>
    </nav>
</template>

<script>
export default {
    name: "HeaderSection",
};
</script>

<style scoped>
.navbar-top {
    background: #102e4a;
    width: 100%;
    padding: 0 10px;
}
.nav-container {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 10px 0;
}
.nav-logo img {
    width: 180px;
    height: auto;
    object-fit: contain;
}
.top-actions {
    display: flex;
    gap: 10px;
    align-items: center;
}
.nav-btn {
    color: #fff;
    text-decoration: none;
    border: 2px solid;
    border-radius: 5px;
    padding: 8px 30px;
    font-weight: 500;
    font-size: 0.95rem;
    transition: all 0.18s;
    text-align: center;
}
.nav-btn-primary {
    background: #20bf6b;
    border-color: #20bf6b;
}
.nav-btn-primary:hover {
    background: transparent;
    color: #20bf6b;
}
.nav-btn-secondary {
    background: #ff7101;
    border-color: #ff7101;
}
.nav-btn-secondary:hover {
    background: transparent;
    color: #ff7101;
}

@media (max-width: 991px) {
    .navbar-top {
        display: none;
    }
}
</style>
