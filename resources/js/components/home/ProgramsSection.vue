<template>
  <section class="programs-background" :style="sectionStyles">
    <div class="programs-wrap">
      <div class="row">
        <div 
          class="col" 
          v-for="(program, index) in programs" 
          :key="program.id"
          :style="{ flex: `1 1 calc(${100 / programsPerRow}% - 20px)` }"
        >
          <program-card 
            :program="program" 
            :globalSettings="globalSettings"
            @hover="hoverBtn(index)"
            @leave="leaveBtn(index)"
          />
        </div>
      </div>
    </div>
  </section>
</template>

<script>
import axios from 'axios';
import ProgramCard from './ProgramCard.vue'

export default {
  name: "ProgramsSection",
  components: {
    ProgramCard
  },
  data() {
    return {
      programs: [],
      programsPerRow: 3,
      globalSettings: {
        background: '#102e4a',
        paddingTop: '60px',
        paddingBottom: '80px',
        cardBackground: 'linear-gradient(180deg, rgba(16, 46, 74, 0.35), rgba(3, 8, 17, 0.25))',
        borderRadius: '15px',
        borderWidth: '10px',
        backdropFilter: 'blur(8px)',
        minHeight: '280px'
      }
    };
  },
  computed: {
    sectionStyles() {
      return {
        background: this.globalSettings.background,
        padding: `${this.globalSettings.paddingTop} 0 ${this.globalSettings.paddingBottom}`
      };
    }
  },
  methods: {
    hoverBtn(index) {
      this.$set(this.programs[index], 'hover', true);
    },
    leaveBtn(index) {
      this.$set(this.programs[index], 'hover', false);
    },
    fetchPrograms() {
      axios.get('/programs')
        .then(response => {
          this.programs = response.data;
        })
        .catch(error => {
          console.error('Error fetching programs:', error);
        });
    },
    fetchSettings() {
      axios.get('/programs/settings')
        .then(response => {
          this.globalSettings = { ...this.globalSettings, ...response.data };
          this.programsPerRow = parseInt(response.data.programs_per_row) || 3;
        })
        .catch(error => {
          console.error('Error fetching settings:', error);
        });
    }
  },
  mounted() {
    this.fetchPrograms();
    this.fetchSettings();
    
    const observer = new IntersectionObserver(
      (entries) => {
        entries.forEach((entry) => {
          if (entry.isIntersecting) {
            entry.target.classList.add("animate");
          }
        });
      },
      { threshold: 0.1 }
    );
    
    this.$nextTick(() => {
      document.querySelectorAll(".program-card").forEach((el) => {
        observer.observe(el);
      });
    });
  }
};
</script>

<style scoped>
.programs-background {
  transition: all 0.3s ease;
}

.programs-wrap {
  max-width: 1240px;
  margin: 0 auto;
  padding: 0 20px;
  margin-top: -90px;
  z-index: 5;
  position: relative;
}

.row {
  display: flex;
  flex-wrap: wrap;
  gap: 20px;
  align-items: stretch;
}

.col {
  display: flex;
}

@media (max-width: 768px) {
  .col {
    flex: 1 1 100%;
  }
  
  .programs-wrap {
    margin-top: -60px;
  }
}
</style>