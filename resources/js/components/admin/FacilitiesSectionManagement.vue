<template>
    <div class="facilities-page">
        
        <!-- Admin Panel (only show if user is admin) -->
        <div v-if="isAdmin" class="admin-panel">
            <div class="admin-header">
                <h2>Facilities Section Management</h2>
                <div class="admin-actions">
                    <button @click="showForm = !showForm" class="toggle-btn">
                        {{ showForm ? 'Hide Form' : 'Show Form' }}
                    </button>
                    <button @click="refreshSection" class="refresh-btn">
                        Refresh Section
                    </button>
                </div>
            </div>
            
            <!-- Admin Form -->
            <div v-if="showForm" class="admin-form-container">
                <FacilitiesSectionForm 
                    :sectionId="editingSectionId"
                    @saved="handleSectionSaved"
                />
            </div>
            
            <!-- Sections List -->
            <div class="sections-list">
                <h3>All Facilities Sections</h3>
                <div class="sections-grid">
                    <div 
                        v-for="section in sections" 
                        :key="section.id"
                        class="section-card"
                        :class="{ active: section.is_active }"
                    >
                        <div class="section-info">
                            <h4>{{ section.title }}</h4>
                            <p>{{ section.subtitle.substring(0, 100) }}...</p>
                            <div class="section-meta">
                                <span class="status" :class="{ active: section.is_active }">
                                    {{ section.is_active ? 'Active' : 'Inactive' }}
                                </span>
                                <span class="date">
                                    {{ formatDate(section.created_at) }}
                                </span>
                            </div>
                        </div>
                        
                        <div class="section-actions">
                            <button 
                                @click="editSection(section.id)" 
                                class="edit-btn"
                            >
                                Edit
                            </button>
                            <button 
                                @click="activateSection(section.id)" 
                                class="activate-btn"
                                :disabled="section.is_active"
                            >
                                {{ section.is_active ? 'Active' : 'Activate' }}
                            </button>
                            <button 
                                @click="deleteSection(section.id)" 
                                class="delete-btn"
                                :disabled="section.is_active"
                            >
                                Delete
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import FacilitiesSectionForm from './FacilitiesSectionForm.vue';
import axios from 'axios';

export default {
    name: 'FacilitiesPage',
    components: {
        FacilitiesSectionForm,
    },
    data() {
        return {
            isAdmin: true, // Set this based on user role
            showForm: false,
            editingSectionId: null,
            sections: [],
            sectionKey: 0, // Force refresh of section component
        };
    },
    methods: {
        async fetchSections() {
            try {
                const response = await axios.get('/admin/facilities-sections');
                this.sections = response.data;
            } catch (error) {
                console.error('Error fetching sections:', error);
            }
        },
        
        async activateSection(sectionId) {
            try {
                await axios.patch(`/facilities-sections/${sectionId}/toggle-active`);
                await this.fetchSections();
                this.refreshSection();
                alert('Section activated successfully!');
            } catch (error) {
                console.error('Error activating section:', error);
                alert('Error activating section');
            }
        },
        
        async deleteSection(sectionId) {
            if (!confirm('Are you sure you want to delete this section?')) {
                return;
            }
            
            try {
                await axios.delete(`/facilities-sections/${sectionId}`);
                await this.fetchSections();
                alert('Section deleted successfully!');
            } catch (error) {
                console.error('Error deleting section:', error);
                alert('Error deleting section');
            }
        },
        
        editSection(sectionId) {
            this.editingSectionId = sectionId;
            this.showForm = true;
        },
        
        handleSectionSaved(savedSection) {
            this.showForm = false;
            this.editingSectionId = null;
            this.fetchSections();
            this.refreshSection();
        },
        
        refreshSection() {
            // Force re-render of the facilities section component
            this.sectionKey += 1;
        },
        
        formatDate(dateString) {
            return new Date(dateString).toLocaleDateString();
        },
    },
    async mounted() {
        if (this.isAdmin) {
            await this.fetchSections();
        }
    },
};
</script>

<style scoped>
.facilities-page {
    min-height: 100vh;
}

.admin-panel {
    background: #f8f9fa;
    padding: 40px 20px;
    margin-top: 20px;
}

.admin-header {
    max-width: 1200px;
    margin: 0 auto;
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 30px;
}

.admin-header h2 {
    color: #102e4a;
    margin: 0;
}

.admin-actions {
    display: flex;
    gap: 15px;
}

.toggle-btn,
.refresh-btn {
    padding: 10px 20px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    font-weight: 500;
    transition: all 0.3s ease;
}

.toggle-btn {
    background: #ff7101;
    color: white;
}

.toggle-btn:hover {
    background: #e55a00;
}

.refresh-btn {
    background: #17a2b8;
    color: white;
}

.refresh-btn:hover {
    background: #138496;
}

.admin-form-container {
    margin-bottom: 40px;
}

.sections-list {
    max-width: 1200px;
    margin: 0 auto;
}

.sections-list h3 {
    color: #102e4a;
    margin-bottom: 20px;
}

.sections-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
    gap: 20px;
}

.section-card {
    background: white;
    border-radius: 10px;
    padding: 20px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    border: 2px solid transparent;
}

.section-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 20px rgba(0, 0, 0, 0.15);
}

.section-card.active {
    border-color: #28a745;
}

.section-info h4 {
    color: #102e4a;
    margin: 0 0 10px 0;
    font-size: 1.2rem;
}

.section-info p {
    color: #666;
    margin: 0 0 15px 0;
    line-height: 1.5;
}

.section-meta {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 15px;
}

.status {
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
    background: #6c757d;
    color: white;
}

.status.active {
    background: #28a745;
}

.date {
    color: #999;
    font-size: 12px;
}

.section-actions {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
}

.edit-btn,
.activate-btn,
.delete-btn {
    flex: 1;
    padding: 8px 12px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    font-size: 12px;
    font-weight: 500;
    transition: all 0.3s ease;
    min-width: 70px;
}

.edit-btn {
    background: #007bff;
    color: white;
}

.edit-btn:hover {
    background: #0056b3;
}

.activate-btn {
    background: #28a745;
    color: white;
}

.activate-btn:hover:not(:disabled) {
    background: #218838;
}

.activate-btn:disabled {
    background: #6c757d;
    cursor: not-allowed;
}

.delete-btn {
    background: #dc3545;
    color: white;
}

.delete-btn:hover:not(:disabled) {
    background: #c82333;
}

.delete-btn:disabled {
    background: #6c757d;
    cursor: not-allowed;
}

/* Responsive Design */
@media (max-width: 768px) {
    .admin-header {
        flex-direction: column;
        gap: 20px;
        text-align: center;
    }
    
    .admin-actions {
        justify-content: center;
    }
    
    .sections-grid {
        grid-template-columns: 1fr;
    }
    
    .section-actions {
        justify-content: center;
    }
}
</style>