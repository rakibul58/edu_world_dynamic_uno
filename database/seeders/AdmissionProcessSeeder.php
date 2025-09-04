<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AdmissionProcess;
use App\Models\AdmissionStep;

class AdmissionProcessSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create default admission process
        $process = AdmissionProcess::create([
            'title' => 'Simple Admission Process',
            'description' => 'Getting started at Edu World is easy. Follow these simple steps to secure your child\'s future.',
            'background_color' => '#102e4a',
            'text_color' => '#ffffff',
            'padding_top' => '80px',
            'padding_bottom' => '80px',
            'text_align' => 'center',
            'title_color' => '#ffffff',
            'title_font_size' => '2.5rem',
            'title_font_weight' => '700',
            'description_color' => '#ffffff',
            'description_font_size' => '1.1rem',
            'description_opacity' => '0.9',
            'step_number_bg_color' => '#ff7101',
            'step_number_text_color' => '#ffffff',
            'step_title_color' => '#ffffff',
            'step_title_font_size' => '1.3rem',
            'step_description_color' => '#ffffff',
            'step_description_opacity' => '0.9',
            'grid_min_width' => '250px',
            'step_gap' => '30px',
            'is_active' => true,
            'sort_order' => 1,
        ]);

        // Create default steps
        $steps = [
            [
                'title' => 'Submit Application',
                'description' => 'Complete our online application form with required documents and photographs.',
                'step_number' => 1,
                'sort_order' => 0,
            ],
            [
                'title' => 'Assessment & Interview',
                'description' => 'Attend the entrance assessment and family interview with our admissions team.',
                'step_number' => 2,
                'sort_order' => 1,
            ],
            [
                'title' => 'Acceptance & Enrollment',
                'description' => 'Receive your acceptance letter and complete the enrollment process with fee payment.',
                'step_number' => 3,
                'sort_order' => 2,
            ],
            [
                'title' => 'Welcome Orientation',
                'description' => 'Join our new family orientation program and begin your journey with us.',
                'step_number' => 4,
                'sort_order' => 3,
            ],
        ];

        foreach ($steps as $stepData) {
            $process->steps()->create($stepData);
        }

        // Create additional sample processes for demonstration
        $process2 = AdmissionProcess::create([
            'title' => 'Premium Admission Process',
            'description' => 'Experience our comprehensive admission process designed for excellence.',
            'background_color' => '#1a365d',
            'text_color' => '#ffffff',
            'padding_top' => '100px',
            'padding_bottom' => '100px',
            'text_align' => 'center',
            'title_color' => '#ffffff',
            'title_font_size' => '3rem',
            'title_font_weight' => '800',
            'description_color' => '#cbd5e0',
            'description_font_size' => '1.25rem',
            'description_opacity' => '0.95',
            'step_number_bg_color' => '#e53e3e',
            'step_number_text_color' => '#ffffff',
            'step_title_color' => '#ffffff',
            'step_title_font_size' => '1.4rem',
            'step_description_color' => '#e2e8f0',
            'step_description_opacity' => '0.9',
            'grid_min_width' => '300px',
            'step_gap' => '40px',
            'is_active' => false,
            'sort_order' => 2,
        ]);

        $premiumSteps = [
            [
                'title' => 'Initial Consultation',
                'description' => 'Schedule a personalized consultation with our academic advisors to discuss your child\'s needs.',
                'step_number' => 1,
                'sort_order' => 0,
            ],
            [
                'title' => 'Campus Tour & Assessment',
                'description' => 'Experience our facilities firsthand and complete comprehensive academic assessments.',
                'step_number' => 2,
                'sort_order' => 1,
            ],
            [
                'title' => 'Parent-Student Interview',
                'description' => 'Meet with our leadership team for an in-depth discussion about educational goals.',
                'step_number' => 3,
                'sort_order' => 2,
            ],
            [
                'title' => 'Enrollment Confirmation',
                'description' => 'Complete enrollment paperwork and secure your child\'s place in our community.',
                'step_number' => 4,
                'sort_order' => 3,
            ],
            [
                'title' => 'Welcome & Integration',
                'description' => 'Join our welcome program and begin the journey toward academic excellence.',
                'step_number' => 5,
                'sort_order' => 4,
            ],
        ];

        foreach ($premiumSteps as $stepData) {
            $process2->steps()->create($stepData);
        }
    }
}
