<template>
    <div class="facilities-form">
        <form @submit.prevent="submitForm" class="form-container">
            <!-- Section Basic Info -->
            <div class="form-section">
                <h3>Section Information</h3>
                <div class="form-group">
                    <label>Title</label>
                    <input
                        v-model="formData.title"
                        type="text"
                        required
                        class="form-control"
                    />
                </div>

                <div class="form-group">
                    <label>Subtitle</label>
                    <textarea
                        v-model="formData.subtitle"
                        required
                        class="form-control"
                        rows="3"
                    ></textarea>
                </div>

                <div class="form-group">
                    <label>Background Color</label>
                    <div class="color-input-group">
                        <input
                            v-model="formData.background_color"
                            type="color"
                            class="color-picker"
                        />
                        <input
                            v-model="formData.background_color"
                            type="text"
                            class="form-control color-text"
                            placeholder="#ffffff"
                        />
                    </div>
                </div>
            </div>

            <!-- Title Styling -->
            <div class="form-section">
                <h3>Title Styling</h3>
                <div class="form-row">
                    <div class="form-group">
                        <label>Title Color</label>
                        <div class="color-input-group">
                            <input
                                v-model="formData.title_color"
                                type="color"
                                class="color-picker"
                            />
                            <input
                                v-model="formData.title_color"
                                type="text"
                                class="form-control color-text"
                            />
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Font Size</label>
                        <input
                            v-model="formData.title_font_size"
                            type="text"
                            class="form-control"
                            placeholder="2.5rem"
                        />
                    </div>

                    <div class="form-group">
                        <label>Font Weight</label>
                        <select
                            v-model="formData.title_font_weight"
                            class="form-control"
                        >
                            <option value="400">Normal</option>
                            <option value="500">Medium</option>
                            <option value="600">Semi-bold</option>
                            <option value="700">Bold</option>
                            <option value="800">Extra-bold</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Subtitle Styling -->
            <div class="form-section">
                <h3>Subtitle Styling</h3>
                <div class="form-row">
                    <div class="form-group">
                        <label>Subtitle Color</label>
                        <div class="color-input-group">
                            <input
                                v-model="formData.subtitle_color"
                                type="color"
                                class="color-picker"
                            />
                            <input
                                v-model="formData.subtitle_color"
                                type="text"
                                class="form-control color-text"
                            />
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Font Size</label>
                        <input
                            v-model="formData.subtitle_font_size"
                            type="text"
                            class="form-control"
                            placeholder="1.1rem"
                        />
                    </div>

                    <div class="form-group">
                        <label>Font Weight</label>
                        <select
                            v-model="formData.subtitle_font_weight"
                            class="form-control"
                        >
                            <option value="300">Light</option>
                            <option value="400">Normal</option>
                            <option value="500">Medium</option>
                            <option value="600">Semi-bold</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Featured Facility -->
            <div class="form-section">
                <h3>Featured Facility</h3>

                <div class="form-group">
                    <label>Featured Title</label>
                    <input
                        v-model="formData.featured_title"
                        type="text"
                        required
                        class="form-control"
                    />
                </div>

                <div class="form-group">
                    <label>Featured Description</label>
                    <textarea
                        v-model="formData.featured_description"
                        required
                        class="form-control"
                        rows="3"
                    ></textarea>
                </div>

                <div class="form-group">
                    <label>Background Type</label>
                    <div class="radio-group">
                        <label class="radio-label">
                            <input
                                v-model="formData.featured_type"
                                type="radio"
                                value="gradient"
                            />
                            Gradient
                        </label>
                        <label class="radio-label">
                            <input
                                v-model="formData.featured_type"
                                type="radio"
                                value="image"
                            />
                            Image
                        </label>
                    </div>
                </div>

                <!-- Gradient Settings -->
                <div
                    v-if="formData.featured_type === 'gradient'"
                    class="gradient-settings"
                >
                    <div class="form-row">
                        <div class="form-group">
                            <label>Start Color</label>
                            <div class="color-input-group">
                                <input
                                    v-model="formData.featured_gradient_start"
                                    type="color"
                                    class="color-picker"
                                />
                                <input
                                    v-model="formData.featured_gradient_start"
                                    type="text"
                                    class="form-control color-text"
                                />
                            </div>
                        </div>

                        <div class="form-group">
                            <label>End Color</label>
                            <div class="color-input-group">
                                <input
                                    v-model="formData.featured_gradient_end"
                                    type="color"
                                    class="color-picker"
                                />
                                <input
                                    v-model="formData.featured_gradient_end"
                                    type="text"
                                    class="form-control color-text"
                                />
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Direction</label>
                            <input
                                v-model="formData.featured_gradient_direction"
                                type="text"
                                class="form-control"
                                placeholder="135deg"
                            />
                        </div>
                    </div>
                </div>

                <!-- Image Settings -->
                <div
                    v-if="formData.featured_type === 'image'"
                    class="image-settings"
                >
                    <div class="form-group">
                        <label>Upload Image</label>
                        <input
                            @change="handleImageUpload"
                            type="file"
                            accept="image/*"
                            class="form-control"
                        />
                        <div v-if="imagePreview" class="image-preview">
                            <img :src="imagePreview" alt="Preview" />
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label>Tint Color</label>
                            <div class="color-input-group">
                                <input
                                    v-model="formData.featured_tint_color"
                                    type="color"
                                    class="color-picker"
                                />
                                <input
                                    v-model="formData.featured_tint_color"
                                    type="text"
                                    class="form-control color-text"
                                />
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Tint Opacity</label>
                            <input
                                v-model="formData.featured_tint_opacity"
                                type="range"
                                min="0"
                                max="1"
                                step="0.1"
                                class="form-control range-input"
                            />
                            <span class="range-value">{{
                                formData.featured_tint_opacity
                            }}</span>
                        </div>
                    </div>
                </div>

                <!-- Featured Text Styling -->
                <div class="form-row">
                    <div class="form-group">
                        <label>Text Color</label>
                        <div class="color-input-group">
                            <input
                                v-model="formData.featured_text_color"
                                type="color"
                                class="color-picker"
                            />
                            <input
                                v-model="formData.featured_text_color"
                                type="text"
                                class="form-control color-text"
                            />
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Title Font Size</label>
                        <input
                            v-model="formData.featured_title_font_size"
                            type="text"
                            class="form-control"
                            placeholder="2rem"
                        />
                    </div>

                    <div class="form-group">
                        <label>Description Font Size</label>
                        <input
                            v-model="formData.featured_desc_font_size"
                            type="text"
                            class="form-control"
                            placeholder="1.1rem"
                        />
                    </div>
                </div>
            </div>

            <!-- Facility Buttons -->
            <div class="form-section">
                <h3>Facility Buttons</h3>
                <div
                    v-for="(button, index) in formData.facility_buttons"
                    :key="index"
                    class="button-item"
                >
                    <div class="button-header">
                        <h4>Button {{ index + 1 }}</h4>
                        <button
                            type="button"
                            @click="removeButton(index)"
                            class="btn-remove"
                        >
                            Remove
                        </button>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label>Button Text</label>
                            <input
                                v-model="button.text"
                                type="text"
                                required
                                class="form-control"
                            />
                        </div>

                        <div class="form-group">
                            <label>URL</label>
                            <input
                                v-model="button.url"
                                type="text"
                                class="form-control"
                                placeholder="#"
                            />
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label>Background Color</label>
                            <div class="color-input-group">
                                <input
                                    v-model="button.background_color"
                                    type="color"
                                    class="color-picker"
                                />
                                <input
                                    v-model="button.background_color"
                                    type="text"
                                    class="form-control color-text"
                                />
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Text Color</label>
                            <div class="color-input-group">
                                <input
                                    v-model="button.text_color"
                                    type="color"
                                    class="color-picker"
                                />
                                <input
                                    v-model="button.text_color"
                                    type="text"
                                    class="form-control color-text"
                                />
                            </div>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label>Hover Background</label>
                            <div class="color-input-group">
                                <input
                                    v-model="button.hover_background_color"
                                    type="color"
                                    class="color-picker"
                                />
                                <input
                                    v-model="button.hover_background_color"
                                    type="text"
                                    class="form-control color-text"
                                />
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Hover Text Color</label>
                            <div class="color-input-group">
                                <input
                                    v-model="button.hover_text_color"
                                    type="color"
                                    class="color-picker"
                                />
                                <input
                                    v-model="button.hover_text_color"
                                    type="text"
                                    class="form-control color-text"
                                />
                            </div>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label>Border Radius</label>
                            <input
                                v-model="button.border_radius"
                                type="text"
                                class="form-control"
                                placeholder="25px"
                            />
                        </div>

                        <div class="form-group">
                            <label>Font Size</label>
                            <input
                                v-model="button.font_size"
                                type="text"
                                class="form-control"
                                placeholder="1rem"
                            />
                        </div>

                        <div class="form-group">
                            <label>Font Weight</label>
                            <select
                                v-model="button.font_weight"
                                class="form-control"
                            >
                                <option value="400">Normal</option>
                                <option value="500">Medium</option>
                                <option value="600">Semi-bold</option>
                                <option value="700">Bold</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Padding</label>
                            <input
                                v-model="button.padding"
                                type="text"
                                class="form-control"
                                placeholder="12px 30px"
                            />
                        </div>
                    </div>
                </div>

                <button type="button" @click="addButton" class="btn-add">
                    Add Button
                </button>
            </div>

            <!-- Facility Items -->
            <div class="form-section">
                <h3>Facility Items</h3>
                <div
                    v-for="(item, index) in formData.facility_items"
                    :key="index"
                    class="facility-item-form"
                >
                    <div class="item-header">
                        <h4>Item {{ index + 1 }}</h4>
                        <button
                            type="button"
                            @click="removeItem(index)"
                            class="btn-remove"
                            :disabled="formData.facility_items.length <= 1"
                        >
                            Remove
                        </button>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label>Icon</label>
                            <input
                                v-model="item.icon"
                                type="text"
                                required
                                class="form-control"
                                placeholder="💻"
                            />
                        </div>

                        <div class="form-group">
                            <label>Title</label>
                            <input
                                v-model="item.title"
                                type="text"
                                required
                                class="form-control"
                            />
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Description</label>
                        <textarea
                            v-model="item.description"
                            required
                            class="form-control"
                            rows="2"
                        ></textarea>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label>Background Color</label>
                            <div class="color-input-group">
                                <input
                                    v-model="item.background_color"
                                    type="color"
                                    class="color-picker"
                                />
                                <input
                                    v-model="item.background_color"
                                    type="text"
                                    class="form-control color-text"
                                />
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Title Color</label>
                            <div class="color-input-group">
                                <input
                                    v-model="item.title_color"
                                    type="color"
                                    class="color-picker"
                                />
                                <input
                                    v-model="item.title_color"
                                    type="text"
                                    class="form-control color-text"
                                />
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Description Color</label>
                            <div class="color-input-group">
                                <input
                                    v-model="item.description_color"
                                    type="color"
                                    class="color-picker"
                                />
                                <input
                                    v-model="item.description_color"
                                    type="text"
                                    class="form-control color-text"
                                />
                            </div>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label>Title Font Size</label>
                            <input
                                v-model="item.title_font_size"
                                type="text"
                                class="form-control"
                                placeholder="1.2rem"
                            />
                        </div>

                        <div class="form-group">
                            <label>Description Font Size</label>
                            <input
                                v-model="item.description_font_size"
                                type="text"
                                class="form-control"
                                placeholder="0.95rem"
                            />
                        </div>
                    </div>
                </div>

                <button type="button" @click="addItem" class="btn-add">
                    Add Item
                </button>
            </div>

            <!-- Form Actions -->
            <div class="form-actions">
                <button type="submit" class="btn-primary" :disabled="loading">
                    {{
                        loading ? "Saving..." : isEditing ? "Update" : "Create"
                    }}
                </button>
                <button type="button" @click="resetForm" class="btn-secondary">
                    Reset
                </button>
            </div>
        </form>
    </div>
</template>

<script>
import axios from "axios";

export default {
    name: "FacilitiesSectionForm",
    props: {
        sectionId: {
            type: Number,
            default: null,
        },
    },
    data() {
        return {
            loading: false,
            imagePreview: null,
            formData: {
                title: "World-Class Facilities",
                subtitle:
                    "Our campus is equipped with modern amenities and traditional Islamic architecture to create the perfect learning environment.",
                background_color: "#ffffff",
                title_color: "#102e4a",
                title_font_size: "2.5rem",
                title_font_weight: "700",
                subtitle_color: "#111111",
                subtitle_font_size: "1.1rem",
                subtitle_font_weight: "400",
                featured_title: "Modern Classrooms",
                featured_description:
                    "Smart boards, air conditioning, and ergonomic furniture in every classroom to enhance the learning experience.",
                featured_type: "gradient",
                featured_image: null,
                featured_gradient_start: "#ff7101",
                featured_gradient_end: "#102e4a",
                featured_gradient_direction: "135deg",
                featured_tint_color: "#000000",
                featured_tint_opacity: 0.5,
                featured_text_color: "#ffffff",
                featured_title_font_size: "2rem",
                featured_desc_font_size: "1.1rem",
                facility_items: [
                    {
                        title: "Computer Lab",
                        description:
                            "Latest technology and high-speed internet for digital learning.",
                        icon: "💻",
                        background_color: "#ffffff",
                        title_color: "#102e4a",
                        description_color: "#111111",
                        title_font_size: "1.2rem",
                        description_font_size: "0.95rem",
                    },
                ],
                facility_buttons: [
                    {
                        text: "Explore",
                        url: "#",
                        background_color: "#ffffff",
                        text_color: "#ff7101",
                        hover_background_color: "#ff7101",
                        hover_text_color: "#ffffff",
                        border_radius: "25px",
                        font_size: "1rem",
                        font_weight: "600",
                        padding: "12px 30px",
                    },
                ],
            },
        };
    },
    computed: {
        isEditing() {
            return this.sectionId !== null;
        },
    },
    methods: {
        async fetchSectionData() {
            if (!this.sectionId) return;

            try {
                const response = await axios.get(
                    `/facilities-sections/${this.sectionId}`
                );
                this.formData = { ...this.formData, ...response.data };

                if (response.data.featured_image_path) {
                    this.imagePreview = `/storage/${response.data.featured_image_path}`;
                }
            } catch (error) {
                console.error("Error fetching section data:", error);
                alert("Error loading section data");
            }
        },

        handleImageUpload(event) {
            const file = event.target.files[0];
            if (file) {
                this.formData.featured_image = file;

                // Create preview
                const reader = new FileReader();
                reader.onload = (e) => {
                    this.imagePreview = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        },

        addButton() {
            this.formData.facility_buttons.push({
                text: "New Button",
                url: "#",
                background_color: "#ffffff",
                text_color: "#ff7101",
                hover_background_color: "#ff7101",
                hover_text_color: "#ffffff",
                border_radius: "25px",
                font_size: "1rem",
                font_weight: "600",
                padding: "12px 30px",
            });
        },

        removeButton(index) {
            this.formData.facility_buttons.splice(index, 1);
        },

        addItem() {
            this.formData.facility_items.push({
                title: "New Facility",
                description: "Facility description here.",
                icon: "📚",
                background_color: "#ffffff",
                title_color: "#102e4a",
                description_color: "#111111",
                title_font_size: "1.2rem",
                description_font_size: "0.95rem",
            });
        },

        removeItem(index) {
            if (this.formData.facility_items.length > 1) {
                this.formData.facility_items.splice(index, 1);
            }
        },

        async submitForm() {
            this.loading = true;

            try {
                const formData = new FormData();

                // Append all form data
                Object.keys(this.formData).forEach((key) => {
                    if (
                        key === "facility_items" ||
                        key === "facility_buttons"
                    ) {
                        formData.append(
                            key,
                            JSON.stringify(this.formData[key])
                        );
                    } else if (key === "featured_image" && this.formData[key]) {
                        formData.append(key, this.formData[key]);
                    } else if (key !== "featured_image") {
                        formData.append(key, this.formData[key]);
                    }
                });

                let response;
                if (this.isEditing) {
                    response = await axios.post(
                        `/admin/facilities-sections/${this.sectionId}?_method=PUT`,
                        formData,
                        {
                            headers: {
                                "Content-Type": "multipart/form-data",
                            },
                        }
                    );
                } else {
                    response = await axios.post(
                        "/admin/facilities-sections",
                        formData,
                        {
                            headers: {
                                "Content-Type": "multipart/form-data",
                            },
                        }
                    );
                }

                alert("Facilities section saved successfully!");
                this.$emit("saved", response.data);
            } catch (error) {
                console.error("Error saving section:", error);
                alert(
                    "Error saving section: " +
                        (error.response?.data?.message || error.message)
                );
            } finally {
                this.loading = false;
            }
        },

        resetForm() {
            if (confirm("Are you sure you want to reset the form?")) {
                this.formData = {
                    title: "World-Class Facilities",
                    subtitle:
                        "Our campus is equipped with modern amenities and traditional Islamic architecture to create the perfect learning environment.",
                    background_color: "#ffffff",
                    title_color: "#102e4a",
                    title_font_size: "2.5rem",
                    title_font_weight: "700",
                    subtitle_color: "#111111",
                    subtitle_font_size: "1.1rem",
                    subtitle_font_weight: "400",
                    featured_title: "Modern Classrooms",
                    featured_description:
                        "Smart boards, air conditioning, and ergonomic furniture in every classroom to enhance the learning experience.",
                    featured_type: "gradient",
                    featured_image: null,
                    featured_gradient_start: "#ff7101",
                    featured_gradient_end: "#102e4a",
                    featured_gradient_direction: "135deg",
                    featured_tint_color: "#000000",
                    featured_tint_opacity: 0.5,
                    featured_text_color: "#ffffff",
                    featured_title_font_size: "2rem",
                    featured_desc_font_size: "1.1rem",
                    facility_items: [
                        {
                            title: "Computer Lab",
                            description:
                                "Latest technology and high-speed internet for digital learning.",
                            icon: "💻",
                            background_color: "#ffffff",
                            title_color: "#102e4a",
                            description_color: "#111111",
                            title_font_size: "1.2rem",
                            description_font_size: "0.95rem",
                        },
                    ],
                    facility_buttons: [
                        {
                            text: "Explore",
                            url: "#",
                            background_color: "#ffffff",
                            text_color: "#ff7101",
                            hover_background_color: "#ff7101",
                            hover_text_color: "#ffffff",
                            border_radius: "25px",
                            font_size: "1rem",
                            font_weight: "600",
                            padding: "12px 30px",
                        },
                    ],
                };
                this.imagePreview = null;
            }
        },
    },
    async mounted() {
        if (this.isEditing) {
            await this.fetchSectionData();
        }
    },
};
</script>

<style scoped>
.facilities-form {
    max-width: 1200px;
    margin: 0 auto;
    padding: 20px;
}

.form-container {
    background: #fff;
    padding: 30px;
    border-radius: 10px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
}

.form-section {
    margin-bottom: 40px;
    padding-bottom: 30px;
    border-bottom: 1px solid #eee;
}

.form-section:last-of-type {
    border-bottom: none;
}

.form-section h3 {
    color: #102e4a;
    margin-bottom: 20px;
    font-size: 1.5rem;
    font-weight: 600;
}

.form-group {
    margin-bottom: 20px;
}

.form-group label {
    display: block;
    margin-bottom: 5px;
    font-weight: 500;
    color: #333;
}

.form-control {
    width: 100%;
    padding: 10px 15px;
    border: 1px solid #ddd;
    border-radius: 5px;
    font-size: 14px;
    transition: border-color 0.3s ease;
}

.form-control:focus {
    outline: none;
    border-color: #ff7101;
    box-shadow: 0 0 0 2px rgba(255, 113, 1, 0.1);
}

.form-row {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 20px;
}

.color-input-group {
    display: flex;
    gap: 10px;
    align-items: center;
}

.color-picker {
    width: 50px;
    height: 40px;
    border: 1px solid #ddd;
    border-radius: 5px;
    cursor: pointer;
}

.color-text {
    flex: 1;
}

.radio-group {
    display: flex;
    gap: 20px;
}

.radio-label {
    display: flex;
    align-items: center;
    gap: 8px;
    cursor: pointer;
}

.radio-label input[type="radio"] {
    margin: 0;
}

.range-input {
    flex: 1;
}

.range-value {
    min-width: 40px;
    text-align: center;
    font-weight: 500;
    color: #666;
}

.gradient-settings,
.image-settings {
    background: #f8f9fa;
    padding: 20px;
    border-radius: 8px;
    margin-top: 15px;
}

.image-preview {
    margin-top: 15px;
}

.image-preview img {
    max-width: 200px;
    max-height: 150px;
    object-fit: cover;
    border-radius: 8px;
    border: 1px solid #ddd;
}

.button-item,
.facility-item-form {
    background: #f8f9fa;
    padding: 25px;
    border-radius: 10px;
    margin-bottom: 20px;
    border: 1px solid #e9ecef;
}

.button-header,
.item-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
    padding-bottom: 15px;
    border-bottom: 1px solid #dee2e6;
}

.button-header h4,
.item-header h4 {
    margin: 0;
    color: #495057;
    font-size: 1.1rem;
}

.btn-remove {
    background: #dc3545;
    color: white;
    border: none;
    padding: 8px 16px;
    border-radius: 5px;
    cursor: pointer;
    font-size: 12px;
    transition: background-color 0.3s ease;
}

.btn-remove:hover:not(:disabled) {
    background: #c82333;
}

.btn-remove:disabled {
    background: #6c757d;
    cursor: not-allowed;
}

.btn-add {
    background: #28a745;
    color: white;
    border: none;
    padding: 12px 24px;
    border-radius: 5px;
    cursor: pointer;
    font-size: 14px;
    font-weight: 500;
    transition: background-color 0.3s ease;
    margin-top: 15px;
}

.btn-add:hover {
    background: #218838;
}

.form-actions {
    display: flex;
    gap: 15px;
    justify-content: flex-end;
    padding-top: 30px;
    border-top: 1px solid #eee;
}

.btn-primary {
    background: #ff7101;
    color: white;
    border: none;
    padding: 12px 30px;
    border-radius: 5px;
    cursor: pointer;
    font-size: 16px;
    font-weight: 600;
    transition: background-color 0.3s ease;
}

.btn-primary:hover:not(:disabled) {
    background: #e55a00;
}

.btn-primary:disabled {
    background: #6c757d;
    cursor: not-allowed;
}

.btn-secondary {
    background: #6c757d;
    color: white;
    border: none;
    padding: 12px 30px;
    border-radius: 5px;
    cursor: pointer;
    font-size: 16px;
    font-weight: 500;
    transition: background-color 0.3s ease;
}

.btn-secondary:hover {
    background: #5a6268;
}

/* Responsive Design */
@media (max-width: 768px) {
    .facilities-form {
        padding: 10px;
    }

    .form-container {
        padding: 20px;
    }

    .form-row {
        grid-template-columns: 1fr;
    }

    .color-input-group {
        flex-direction: column;
        align-items: stretch;
    }

    .radio-group {
        flex-direction: column;
        gap: 10px;
    }

    .form-actions {
        flex-direction: column;
    }

    .btn-primary,
    .btn-secondary {
        width: 100%;
    }
}

@media (max-width: 480px) {
    .button-header,
    .item-header {
        flex-direction: column;
        align-items: stretch;
        gap: 10px;
    }

    .btn-remove {
        align-self: flex-end;
        width: fit-content;
    }
}
</style>
