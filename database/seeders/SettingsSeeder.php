<?php
// database/seeders/SettingsSeeder.php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $settings = [
            // General Settings
            [
                'key' => 'site_title',
                'value' => 'Misri Khan Dental Clinic',
                'group' => 'general'
            ],
            [
                'key' => 'site_description',
                'value' => 'Your Smile, Our Priority - Providing Quality Dental Care Services',
                'group' => 'general'
            ],
            [
                'key' => 'logo',
                'value' => null,
                'group' => 'general'
            ],
            [
                'key' => 'favicon',
                'value' => null,
                'group' => 'general'
            ],
            [
                'key' => 'footer_text',
                'value' => '© ' . date('Y') . ' Misri Khan Dental Clinic. All rights reserved.',
                'group' => 'general'
            ],

            // Contact Settings
            [
                'key' => 'contact_email',
                'value' => 'info@misrikhandental.com',
                'group' => 'contact'
            ],
            [
                'key' => 'contact_phone',
                'value' => '+1234567890',
                'group' => 'contact'
            ],
            [
                'key' => 'contact_address',
                'value' => '123 Dental Street, Medical City, Country',
                'group' => 'contact'
            ],

            // Social Media Settings
            [
                'key' => 'facebook',
                'value' => 'https://facebook.com',
                'group' => 'social'
            ],
            [
                'key' => 'twitter',
                'value' => 'https://twitter.com',
                'group' => 'social'
            ],
            [
                'key' => 'instagram',
                'value' => 'https://instagram.com',
                'group' => 'social'
            ],
            [
                'key' => 'linkedin',
                'value' => 'https://linkedin.com',
                'group' => 'social'
            ],

            // SEO Settings
            [
                'key' => 'meta_keywords',
                'value' => 'dental clinic, dentist, teeth, dental care, oral health, dental services',
                'group' => 'seo'
            ],
            [
                'key' => 'meta_description',
                'value' => 'Misri Khan Dental Clinic - Your trusted dental care provider. We offer a wide range of dental services with multiple branches for your convenience.',
                'group' => 'seo'
            ],
            [
                'key' => 'google_analytics',
                'value' => '',
                'group' => 'seo'
            ],

            // Appointment Settings
            [
                'key' => 'enable_appointment',
                'value' => '1',
                'group' => 'appointment'
            ],
            [
                'key' => 'appointment_email_notification',
                'value' => '1',
                'group' => 'appointment'
            ],
            [
                'key' => 'appointment_confirmation_message',
                'value' => 'Thank you for booking your appointment with Misri Khan Dental Clinic. We will confirm your appointment shortly.',
                'group' => 'appointment'
            ]
        ];

        // Insert or update settings
        foreach ($settings as $setting) {
            Setting::updateOrCreate(
                ['key' => $setting['key']],
                ['value' => $setting['value'], 'group' => $setting['group']]
            );
        }
    }
}