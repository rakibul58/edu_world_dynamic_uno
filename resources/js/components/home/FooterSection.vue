<template>
    <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-section">
                    <a class="nav-logo" href="#"
                        ><img
                            src="/public/uploads/image/logo.png"
                            alt="Footer Logo"
                    /></a>
                    <p>A Complete ERP System</p>
                    <p>
                        Providing exceptional Islamic Cambridge education that
                        prepares students for success in this world and the
                        hereafter.
                    </p>
                    <div class="footer-social">
                        <a href="#" class="social-link"
                            ><i class="fab fa-facebook-f"></i
                        ></a>
                        <a href="#" class="social-link"
                            ><i class="fab fa-twitter"></i
                        ></a>
                        <a href="#" class="social-link"
                            ><i class="fab fa-instagram"></i
                        ></a>
                        <a href="#" class="social-link"
                            ><i class="fab fa-youtube"></i
                        ></a>
                    </div>
                </div>
                <div class="footer-section">
                    <h3>Quick Links</h3>
                    <ul class="footer-links">
                        <li><a href="#">About Us</a></li>
                        <li><a href="#">Admissions</a></li>
                        <li><a href="#">Programs</a></li>
                        <li><a href="#">Campus Life</a></li>
                        <li><a href="#">News & Events</a></li>
                    </ul>
                </div>
                <div class="footer-section">
                    <h3>Programs</h3>
                    <ul class="footer-links">
                        <li><a href="#">Islamic Studies</a></li>
                        <li><a href="#">Cambridge Curriculum</a></li>
                        <li><a href="#">Hifz Program</a></li>
                        <li><a href="#">Primary Education</a></li>
                        <li><a href="#">Secondary Education</a></li>
                    </ul>
                </div>
                <div class="footer-section">
                    <h3>Contact Info</h3>
                    <p>📍 Chattogram, Bangladesh</p>
                    <p>📞 +880 123 456 789</p>
                    <p>✉️ info@eduworld.edu</p>
                    <p>🕒 Mon-Fri: 8:00 AM - 4:00 PM</p>
                </div>
            </div>
            <div class="footer-bottom">
                <p>
                    &copy; 2025 Edu World. All rights reserved. | Privacy Policy
                    | Terms of Service
                </p>
            </div>
        </div>
    </footer>
</template>

<script>
export default {
    name: "FooterSection",
};
</script>

<style scoped>
.container {
    max-width: 1240px;
    margin: 0 auto;
    padding: 0 20px;
}
.footer {
    background: #030811;
    color: #fff;
    padding: 60px 0 20px;
}
.footer-content {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 40px;
    margin-bottom: 40px;
}
.footer-section h3 {
    color: #ff7101;
    margin-bottom: 20px;
    font-size: 1.3rem;
}
.footer-section p,
.footer-section a {
    color: #ccc;
    text-decoration: none;
    line-height: 1.8;
}
.footer-section a:hover {
    color: #ff7101;
}
.footer-links {
    list-style: none;
}
.footer-links li {
    margin-bottom: 10px;
}
.footer-social {
    display: flex;
    gap: 15px;
    margin-top: 20px;
}
.social-link {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: #ff7101;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #fff;
    text-decoration: none;
    transition: all 0.3s ease;
}
.social-link:hover {
    transform: translateY(-3px);
    background: #d35b00;
}
.footer-bottom {
    border-top: 1px solid #333;
    padding-top: 20px;
    text-align: center;
    color: #999;
}
.nav-logo img {
    width: 180px;
    height: auto;
    object-fit: contain;
    margin-bottom: 15px;
}
</style>
