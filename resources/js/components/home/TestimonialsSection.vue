<template>
    <section 
        class="testimonials-section"
        :style="sectionStyle"
        ref="testimonialsSection"
    >
        <div class="container">
            <div class="section-header" ref="sectionHeader">
                <h2 :style="titleStyle">{{ sectionSettings.title }}</h2>
                <p :style="subtitleStyle">{{ sectionSettings.subtitle }}</p>
            </div>
            <div 
                class="testimonials-grid" 
                :style="gridStyle"
                ref="testimonialsGrid"
            >
                <div
                    v-for="(testimonial, index) in testimonials"
                    :key="testimonial.id"
                    class="testimonial-card"
                    :style="getCardStyle(testimonial)"
                    :data-animation-delay="index * 100"
                    ref="testimonialCards"
                >
                    <div 
                        class="testimonial-text"
                        :style="getTextStyle(testimonial)"
                    >
                        "{{ testimonial.text }}"
                    </div>
                    <div class="testimonial-author">
                        <div 
                            class="author-avatar"
                            :style="getAvatarStyle(testimonial)"
                        >
                            <img 
                                v-if="testimonial.avatar_type === 'image' && testimonial.avatar_url"
                                :src="testimonial.avatar_url"
                                :alt="testimonial.name"
                                class="avatar-image"
                            />
                            <span v-else class="avatar-initials">
                                {{ testimonial.initials }}
                            </span>
                        </div>
                        <div class="author-info">
                            <h4 :style="getNameStyle(testimonial)">
                                {{ testimonial.name }}
                            </h4>
                            <p :style="getRoleStyle(testimonial)">
                                {{ testimonial.role }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>

<script>
import axios from 'axios';

export default {
    name: 'TestimonialsSection',
    data() {
        return {
            testimonials: [],
            sectionSettings: {
                title: 'What Our Community Says',
                subtitle: 'Hear from parents, students, and graduates about their transformative experience at Edu World.',
                background_color: '#102e4a',
                title_color: '#ffffff',
                text_color: '#ffffff',
                card_background: 'rgba(255, 255, 255, 0.1)',
                card_border_color: 'rgba(255, 255, 255, 0.2)',
                avatar_background: '#ff7101',
                animation_style: 'fadeInUp',
                animation_duration: 600,
                columns: 'auto'
            },
            isLoading: false,
            observer: null
        };
    },
    computed: {
        sectionStyle() {
            return {
                background: this.sectionSettings.background_color,
                padding: '80px 0',
                color: this.sectionSettings.text_color
            };
        },
        titleStyle() {
            return {
                color: this.sectionSettings.title_color,
                fontSize: '2.5rem',
                marginBottom: '15px',
                fontWeight: '700'
            };
        },
        subtitleStyle() {
            return {
                color: this.sectionSettings.text_color,
                fontSize: '1.1rem',
                maxWidth: '600px',
                margin: '0 auto',
                opacity: '0.9'
            };
        },
        gridStyle() {
            let gridColumns;
            switch (this.sectionSettings.columns) {
                case '1':
                    gridColumns = '1fr';
                    break;
                case '2':
                    gridColumns = 'repeat(2, 1fr)';
                    break;
                case '3':
                    gridColumns = 'repeat(3, 1fr)';
                    break;
                default:
                    gridColumns = 'repeat(auto-fit, minmax(350px, 1fr))';
            }
            return {
                display: 'grid',
                gridTemplateColumns: gridColumns,
                gap: '30px'
            };
        }
    },
    methods: {
        async fetchTestimonials() {
            this.isLoading = true;
            try {
                const response = await axios.get('/testimonials/get');
                this.testimonials = response.data.testimonials || [];
                if (response.data.section_settings) {
                    this.sectionSettings = { ...this.sectionSettings, ...response.data.section_settings };
                }
            } catch (error) {
                console.error('Error fetching testimonials:', error);
            } finally {
                this.isLoading = false;
            }
        },

        getCardStyle(testimonial) {
            return {
                background: testimonial.card_background,
                backdropFilter: 'blur(10px)',
                padding: '30px',
                borderRadius: testimonial.card_border_radius,
                border: `1px solid ${testimonial.card_border_color}`,
                transition: `all 0.3s ease, opacity ${this.sectionSettings.animation_duration}ms ease, transform ${this.sectionSettings.animation_duration}ms ease`,
                opacity: '0',
                transform: this.getInitialTransform()
            };
        },

        getTextStyle(testimonial) {
            return {
                fontStyle: 'italic',
                fontSize: testimonial.text_font_size,
                fontWeight: testimonial.text_font_weight,
                color: testimonial.text_color,
                marginBottom: '20px',
                lineHeight: '1.6'
            };
        },

        getAvatarStyle(testimonial) {
            return {
                width: '50px',
                height: '50px',
                borderRadius: '50%',
                background: testimonial.avatar_bg_color,
                color: testimonial.avatar_text_color,
                display: 'flex',
                alignItems: 'center',
                justifyContent: 'center',
                fontWeight: '600',
                fontSize: '1.2rem',
                overflow: 'hidden'
            };
        },

        getNameStyle(testimonial) {
            return {
                fontSize: testimonial.name_font_size,
                fontWeight: testimonial.name_font_weight,
                color: testimonial.name_color,
                margin: '0 0 5px 0'
            };
        },

        getRoleStyle(testimonial) {
            return {
                fontSize: testimonial.role_font_size,
                fontWeight: testimonial.role_font_weight,
                color: testimonial.role_color,
                margin: '0',
                opacity: '0.8'
            };
        },

        getInitialTransform() {
            switch (this.sectionSettings.animation_style) {
                case 'slideInLeft':
                    return 'translateX(-50px)';
                case 'slideInRight':
                    return 'translateX(50px)';
                case 'slideInUp':
                case 'fadeInUp':
                    return 'translateY(30px)';
                case 'zoomIn':
                    return 'scale(0.8)';
                case 'bounceIn':
                    return 'scale(0.3)';
                default:
                    return 'translateY(30px)';
            }
        },

        getFinalTransform() {
            switch (this.sectionSettings.animation_style) {
                case 'bounceIn':
                    return 'scale(1.05)';
                default:
                    return 'translateY(0) translateX(0) scale(1)';
            }
        },

        animateElements() {
            if (!this.$refs.testimonialCards) return;

            const cards = Array.isArray(this.$refs.testimonialCards) 
                ? this.$refs.testimonialCards 
                : [this.$refs.testimonialCards];

            cards.forEach((card, index) => {
                if (card) {
                    const delay = parseInt(card.dataset.animationDelay) || 0;
                    
                    setTimeout(() => {
                        card.style.opacity = '1';
                        card.style.transform = this.getFinalTransform();
                        
                        // Bounce effect for bounceIn animation
                        if (this.sectionSettings.animation_style === 'bounceIn') {
                            setTimeout(() => {
                                card.style.transform = 'scale(1)';
                            }, this.sectionSettings.animation_duration * 0.3);
                        }
                        
                        // Add hover effects
                        card.addEventListener('mouseenter', () => {
                            card.style.transform = 'translateY(-10px) scale(1.02)';
                            card.style.background = this.adjustOpacity(this.testimonials[index]?.card_background, 0.2);
                        });
                        
                        card.addEventListener('mouseleave', () => {
                            card.style.transform = 'translateY(0) scale(1)';
                            card.style.background = this.testimonials[index]?.card_background;
                        });
                    }, delay);
                }
            });

            // Animate header
            if (this.$refs.sectionHeader) {
                setTimeout(() => {
                    this.$refs.sectionHeader.style.opacity = '1';
                    this.$refs.sectionHeader.style.transform = 'translateY(0)';
                }, 100);
            }
        },

        adjustOpacity(color, increase) {
            if (color.includes('rgba')) {
                // Extract rgba values
                const rgba = color.match(/rgba?\(([^)]+)\)/)[1].split(',');
                const r = rgba[0].trim();
                const g = rgba[1].trim();
                const b = rgba[2].trim();
                let a = parseFloat(rgba[3].trim()) + increase;
                a = Math.min(1, Math.max(0, a)); // Clamp between 0 and 1
                return `rgba(${r}, ${g}, ${b}, ${a})`;
            }
            return color;
        },

        setupIntersectionObserver() {
            const options = {
                root: null,
                rootMargin: '0px',
                threshold: 0.1
            };

            this.observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting && !entry.target.classList.contains('animated')) {
                        entry.target.classList.add('animated');
                        this.animateElements();
                    }
                });
            }, options);

            if (this.$refs.testimonialsSection) {
                this.observer.observe(this.$refs.testimonialsSection);
            }
        },

        // CSS classes for different animation styles
        getAnimationClass() {
            return `animate-${this.sectionSettings.animation_style}`;
        }
    },

    async mounted() {
        await this.fetchTestimonials();
        
        this.$nextTick(() => {
            // Set initial styles for header
            if (this.$refs.sectionHeader) {
                this.$refs.sectionHeader.style.opacity = '0';
                this.$refs.sectionHeader.style.transform = 'translateY(30px)';
                this.$refs.sectionHeader.style.transition = `all ${this.sectionSettings.animation_duration}ms ease`;
            }

            this.setupIntersectionObserver();
        });
    },

    beforeUnmount() {
        if (this.observer) {
            this.observer.disconnect();
        }
    }
};
</script>

<style scoped>
.container {
    max-width: 1240px;
    margin: 0 auto;
    padding: 0 20px;
}

.testimonials-section {
    position: relative;
    overflow: hidden;
}

.section-header {
    text-align: center;
    margin-bottom: 60px;
}

.testimonials-grid {
    position: relative;
}

.testimonial-card {
    position: relative;
    will-change: transform, opacity;
}

.testimonial-card:hover {
    z-index: 1;
}

.testimonial-text {
    position: relative;
    z-index: 1;
}

.testimonial-author {
    display: flex;
    align-items: center;
    gap: 15px;
    position: relative;
    z-index: 1;
}

.author-avatar {
    flex-shrink: 0;
}

.avatar-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
    border-radius: 50%;
}

.avatar-initials {
    user-select: none;
}

.author-info h4 {
    margin: 0 0 5px 0;
    line-height: 1.2;
}

.author-info p {
    margin: 0;
    line-height: 1.3;
}

/* Animation keyframes */
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes slideInLeft {
    from {
        opacity: 0;
        transform: translateX(-50px);
    }
    to {
        opacity: 1;
        transform: translateX(0);
    }
}

@keyframes slideInRight {
    from {
        opacity: 0;
        transform: translateX(50px);
    }
    to {
        opacity: 1;
        transform: translateX(0);
    }
}

@keyframes zoomIn {
    from {
        opacity: 0;
        transform: scale(0.8);
    }
    to {
        opacity: 1;
        transform: scale(1);
    }
}

@keyframes bounceIn {
    0% {
        opacity: 0;
        transform: scale(0.3);
    }
    50% {
        opacity: 1;
        transform: scale(1.05);
    }
    70% {
        transform: scale(0.9);
    }
    100% {
        opacity: 1;
        transform: scale(1);
    }
}

/* Responsive design */
@media (max-width: 1024px) {
    .testimonials-grid {
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)) !important;
    }
}

@media (max-width: 768px) {
    .testimonials-section {
        padding: 60px 0 !important;
    }
    
    .section-header {
        margin-bottom: 40px;
    }
    
    .section-header h2 {
        font-size: 2rem !important;
    }
    
    .section-header p {
        font-size: 1rem !important;
    }
    
    .testimonials-grid {
        grid-template-columns: 1fr !important;
        gap: 20px !important;
    }
    
    .testimonial-card {
        padding: 20px !important;
    }
}

@media (max-width: 480px) {
    .container {
        padding: 0 15px;
    }
    
    .testimonials-section {
        padding: 40px 0 !important;
    }
    
    .section-header h2 {
        font-size: 1.8rem !important;
        margin-bottom: 10px !important;
    }
    
    .testimonial-author {
        gap: 10px !important;
    }
    
    .author-avatar {
        width: 40px !important;
        height: 40px !important;
        font-size: 1rem !important;
    }
}

/* Loading state */
.testimonials-loading {
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 200px;
    color: inherit;
}

.loading-spinner {
    width: 40px;
    height: 40px;
    border: 3px solid rgba(255, 255, 255, 0.3);
    border-top: 3px solid currentColor;
    border-radius: 50%;
    animation: spin 1s linear infinite;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

/* Error state */
.testimonials-error {
    text-align: center;
    padding: 40px 20px;
    color: inherit;
    opacity: 0.8;
}

.testimonials-error h3 {
    margin: 0 0 10px 0;
    font-size: 1.2rem;
}

.testimonials-error p {
    margin: 0;
    font-size: 0.9rem;
}

/* Enhanced hover effects */
.testimonial-card {
    cursor: default;
    transform-origin: center;
}

.testimonial-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    border-radius: inherit;
    background: linear-gradient(135deg, rgba(255, 255, 255, 0.1), rgba(255, 255, 255, 0.05));
    opacity: 0;
    transition: opacity 0.3s ease;
    pointer-events: none;
    z-index: 0;
}

.testimonial-card:hover::before {
    opacity: 1;
}

/* Accessibility improvements */
.testimonial-card:focus-within {
    outline: 2px solid rgba(255, 113, 1, 0.5);
    outline-offset: 2px;
}

.author-avatar:focus {
    outline: 2px solid rgba(255, 113, 1, 0.5);
    outline-offset: 2px;
}

/* Print styles */
@media print {
    .testimonials-section {
        background: white !important;
        color: black !important;
        padding: 20px 0 !important;
    }
    
    .testimonial-card {
        background: white !important;
        border: 1px solid #ccc !important;
        break-inside: avoid;
        margin-bottom: 20px;
    }
    
    .testimonial-text,
    .author-info h4,
    .author-info p {
        color: black !important;
    }
}
</style>