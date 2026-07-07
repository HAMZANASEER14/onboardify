<?php

namespace App\Repositories;

use App\Models\Waiver;
use App\Repositories\Contracts\TemplateRepositoryInterface;
use Illuminate\Support\Str;

class TemplateRepository implements TemplateRepositoryInterface
{
    public function all(): array
    {
        return $this->getTemplates();
    }

    public function find(string $templateId): ?array
    {
        return collect($this->getTemplates())->firstWhere('id', $templateId);
    }

    public function createWaiverFromTemplate(int $userId, array $template): Waiver
    {
        return Waiver::create([
            'user_id'           => $userId,
            'title'             => $template['title'],
            'fields'            => $template['fields'],
            'require_signature' => true,
            'slug'              => Str::slug($template['title']) . '-' . uniqid(),
        ]);
    }

    // ── Template Library ──────────────────────────────────────────
    private function getTemplates(): array
    {
        return [
            [
                'id'          => 'liability-waiver',
                'title'       => 'General Liability Waiver',
                'description' => 'Standard liability release form for activities, events or services.',
                'category'    => 'Legal',
                'color'       => 'blue',
                'fields'      => [
                    ['id'=>'f1', 'type'=>'fullname',  'label'=>'Full Legal Name',        'required'=>true,  'placeholder'=>'Enter your full name',     'options'=>[]],
                    ['id'=>'f2', 'type'=>'dob',       'label'=>'Date of Birth',          'required'=>true,  'placeholder'=>'',                         'options'=>[]],
                    ['id'=>'f3', 'type'=>'email',     'label'=>'Email Address',          'required'=>true,  'placeholder'=>'your@email.com',           'options'=>[]],
                    ['id'=>'f4', 'type'=>'phone',     'label'=>'Phone Number',           'required'=>true,  'placeholder'=>'+1 (555) 000-0000',        'options'=>[]],
                    ['id'=>'f5', 'type'=>'address',   'label'=>'Home Address',           'required'=>false, 'placeholder'=>'Street, City, State, ZIP', 'options'=>[]],
                    ['id'=>'f6', 'type'=>'textarea',  'label'=>'Activity Description',   'required'=>true,  'placeholder'=>'Describe the activity',    'options'=>[]],
                    ['id'=>'f7', 'type'=>'yesno',     'label'=>'I acknowledge the risks','required'=>true,  'placeholder'=>'',                         'options'=>['Yes','No']],
                    ['id'=>'f8', 'type'=>'yesno',     'label'=>'I agree to release all liability','required'=>true,'placeholder'=>'',                  'options'=>['Yes','No']],
                    ['id'=>'f9', 'type'=>'date',      'label'=>'Date of Agreement',      'required'=>true,  'placeholder'=>'',                         'options'=>[]],
                    ['id'=>'f10','type'=>'signature', 'label'=>'Signature',              'required'=>true,  'placeholder'=>'',                         'options'=>[]],
                ],
            ],
            [
                'id'          => 'nda',
                'title'       => 'Non-Disclosure Agreement',
                'description' => 'Protect confidential business information with a standard NDA.',
                'category'    => 'Legal',
                'color'       => 'purple',
                'fields'      => [
                    ['id'=>'f1','type'=>'fullname', 'label'=>'Full Name',              'required'=>true,  'placeholder'=>'', 'options'=>[]],
                    ['id'=>'f2','type'=>'email',    'label'=>'Email Address',          'required'=>true,  'placeholder'=>'', 'options'=>[]],
                    ['id'=>'f3','type'=>'text',     'label'=>'Company Name',           'required'=>false, 'placeholder'=>'', 'options'=>[]],
                    ['id'=>'f4','type'=>'text',     'label'=>'Job Title',              'required'=>false, 'placeholder'=>'', 'options'=>[]],
                    ['id'=>'f5','type'=>'textarea', 'label'=>'Nature of Confidential Information','required'=>true,'placeholder'=>'Describe the confidential information','options'=>[]],
                    ['id'=>'f6','type'=>'date',     'label'=>'Agreement Start Date',   'required'=>true,  'placeholder'=>'', 'options'=>[]],
                    ['id'=>'f7','type'=>'date',     'label'=>'Agreement End Date',     'required'=>false, 'placeholder'=>'', 'options'=>[]],
                    ['id'=>'f8','type'=>'yesno',    'label'=>'I agree to keep all information confidential','required'=>true,'placeholder'=>'','options'=>['Yes','No']],
                    ['id'=>'f9','type'=>'signature','label'=>'Signature',              'required'=>true,  'placeholder'=>'', 'options'=>[]],
                ],
            ],
            [
                'id'          => 'gym-membership',
                'title'       => 'Gym Membership Agreement',
                'description' => 'Complete membership form with health disclosure and liability waiver.',
                'category'    => 'Fitness',
                'color'       => 'orange',
                'fields'      => [
                    ['id'=>'f1', 'type'=>'fullname', 'label'=>'Full Name',            'required'=>true,  'placeholder'=>'', 'options'=>[]],
                    ['id'=>'f2', 'type'=>'dob',      'label'=>'Date of Birth',        'required'=>true,  'placeholder'=>'', 'options'=>[]],
                    ['id'=>'f3', 'type'=>'email',    'label'=>'Email Address',        'required'=>true,  'placeholder'=>'', 'options'=>[]],
                    ['id'=>'f4', 'type'=>'phone',    'label'=>'Phone Number',         'required'=>true,  'placeholder'=>'', 'options'=>[]],
                    ['id'=>'f5', 'type'=>'select',   'label'=>'Membership Type',      'required'=>true,  'placeholder'=>'', 'options'=>['Monthly','Quarterly','Annual']],
                    ['id'=>'f6', 'type'=>'yesno',    'label'=>'Any medical conditions?','required'=>true,'placeholder'=>'','options'=>['Yes','No']],
                    ['id'=>'f7', 'type'=>'textarea', 'label'=>'If yes, describe',     'required'=>false, 'placeholder'=>'List any medical conditions','options'=>[]],
                    ['id'=>'f8', 'type'=>'text',     'label'=>'Emergency Contact Name','required'=>true, 'placeholder'=>'', 'options'=>[]],
                    ['id'=>'f9', 'type'=>'phone',    'label'=>'Emergency Contact Phone','required'=>true,'placeholder'=>'', 'options'=>[]],
                    ['id'=>'f10','type'=>'yesno',    'label'=>'I agree to gym rules and waiver','required'=>true,'placeholder'=>'','options'=>['Yes','No']],
                    ['id'=>'f11','type'=>'signature','label'=>'Signature',            'required'=>true,  'placeholder'=>'', 'options'=>[]],
                ],
            ],
            [
                'id'          => 'personal-training',
                'title'       => 'Personal Training Agreement',
                'description' => 'Agreement for personal training sessions including health history.',
                'category'    => 'Fitness',
                'color'       => 'green',
                'fields'      => [
                    ['id'=>'f1','type'=>'fullname', 'label'=>'Client Full Name',      'required'=>true,  'placeholder'=>'', 'options'=>[]],
                    ['id'=>'f2','type'=>'email',    'label'=>'Email',                 'required'=>true,  'placeholder'=>'', 'options'=>[]],
                    ['id'=>'f3','type'=>'phone',    'label'=>'Phone',                 'required'=>true,  'placeholder'=>'', 'options'=>[]],
                    ['id'=>'f4','type'=>'select',   'label'=>'Fitness Goal',          'required'=>true,  'placeholder'=>'', 'options'=>['Weight Loss','Muscle Gain','Endurance','General Fitness']],
                    ['id'=>'f5','type'=>'select',   'label'=>'Sessions per Week',     'required'=>true,  'placeholder'=>'', 'options'=>['1','2','3','4','5+']],
                    ['id'=>'f6','type'=>'textarea', 'label'=>'Fitness History',       'required'=>false, 'placeholder'=>'Describe your current fitness level','options'=>[]],
                    ['id'=>'f7','type'=>'yesno',    'label'=>'Any injuries or limitations?','required'=>true,'placeholder'=>'','options'=>['Yes','No']],
                    ['id'=>'f8','type'=>'yesno',    'label'=>'I agree to the training terms','required'=>true,'placeholder'=>'','options'=>['Yes','No']],
                    ['id'=>'f9','type'=>'signature','label'=>'Signature',             'required'=>true,  'placeholder'=>'', 'options'=>[]],
                ],
            ],
            [
                'id'          => 'medical-consent',
                'title'       => 'Medical Consent Form',
                'description' => 'Patient consent form for medical procedures and treatments.',
                'category'    => 'Medical',
                'color'       => 'teal',
                'fields'      => [
                    ['id'=>'f1', 'type'=>'fullname', 'label'=>'Patient Full Name',    'required'=>true,  'placeholder'=>'', 'options'=>[]],
                    ['id'=>'f2', 'type'=>'dob',      'label'=>'Date of Birth',        'required'=>true,  'placeholder'=>'', 'options'=>[]],
                    ['id'=>'f3', 'type'=>'phone',    'label'=>'Phone Number',         'required'=>true,  'placeholder'=>'', 'options'=>[]],
                    ['id'=>'f4', 'type'=>'text',     'label'=>'Insurance Provider',   'required'=>false, 'placeholder'=>'', 'options'=>[]],
                    ['id'=>'f5', 'type'=>'textarea', 'label'=>'Current Medications',  'required'=>false, 'placeholder'=>'List all medications','options'=>[]],
                    ['id'=>'f6', 'type'=>'textarea', 'label'=>'Known Allergies',      'required'=>false, 'placeholder'=>'List any allergies','options'=>[]],
                    ['id'=>'f7', 'type'=>'text',     'label'=>'Emergency Contact',    'required'=>true,  'placeholder'=>'', 'options'=>[]],
                    ['id'=>'f8', 'type'=>'phone',    'label'=>'Emergency Contact Phone','required'=>true,'placeholder'=>'', 'options'=>[]],
                    ['id'=>'f9', 'type'=>'yesno',    'label'=>'I consent to treatment','required'=>true, 'placeholder'=>'', 'options'=>['Yes','No']],
                    ['id'=>'f10','type'=>'signature','label'=>'Patient Signature',    'required'=>true,  'placeholder'=>'', 'options'=>[]],
                ],
            ],
            [
                'id'          => 'event-registration',
                'title'       => 'Event Registration Form',
                'description' => 'Collect attendee information and preferences for your event.',
                'category'    => 'Events',
                'color'       => 'yellow',
                'fields'      => [
                    ['id'=>'f1','type'=>'fullname', 'label'=>'Full Name',             'required'=>true,  'placeholder'=>'', 'options'=>[]],
                    ['id'=>'f2','type'=>'email',    'label'=>'Email Address',         'required'=>true,  'placeholder'=>'', 'options'=>[]],
                    ['id'=>'f3','type'=>'phone',    'label'=>'Phone Number',          'required'=>false, 'placeholder'=>'', 'options'=>[]],
                    ['id'=>'f4','type'=>'text',     'label'=>'Company / Organization','required'=>false, 'placeholder'=>'', 'options'=>[]],
                    ['id'=>'f5','type'=>'select',   'label'=>'Ticket Type',           'required'=>true,  'placeholder'=>'', 'options'=>['General Admission','VIP','Student','Early Bird']],
                    ['id'=>'f6','type'=>'number',   'label'=>'Number of Attendees',   'required'=>true,  'placeholder'=>'1','options'=>[]],
                    ['id'=>'f7','type'=>'textarea', 'label'=>'Dietary Requirements',  'required'=>false, 'placeholder'=>'Any dietary restrictions','options'=>[]],
                    ['id'=>'f8','type'=>'yesno',    'label'=>'I agree to event terms','required'=>true,  'placeholder'=>'', 'options'=>['Yes','No']],
                ],
            ],
            [
                'id'          => 'photo-release',
                'title'       => 'Photography / Video Release',
                'description' => 'Permission form for using photos and videos of participants.',
                'category'    => 'Events',
                'color'       => 'purple',
                'fields'      => [
                    ['id'=>'f1','type'=>'fullname', 'label'=>'Full Name',             'required'=>true,  'placeholder'=>'', 'options'=>[]],
                    ['id'=>'f2','type'=>'email',    'label'=>'Email Address',         'required'=>true,  'placeholder'=>'', 'options'=>[]],
                    ['id'=>'f3','type'=>'date',     'label'=>'Event Date',            'required'=>true,  'placeholder'=>'', 'options'=>[]],
                    ['id'=>'f4','type'=>'text',     'label'=>'Event Name',            'required'=>true,  'placeholder'=>'', 'options'=>[]],
                    ['id'=>'f5','type'=>'checkbox', 'label'=>'Permitted Uses',        'required'=>true,  'placeholder'=>'', 'options'=>['Website','Social Media','Print Materials','Video','Press Releases']],
                    ['id'=>'f6','type'=>'yesno',    'label'=>'I grant permission to use my image','required'=>true,'placeholder'=>'','options'=>['Yes','No']],
                    ['id'=>'f7','type'=>'signature','label'=>'Signature',             'required'=>true,  'placeholder'=>'', 'options'=>[]],
                ],
            ],
            [
                'id'          => 'school-trip',
                'title'       => 'School Trip Permission Slip',
                'description' => 'Parent/guardian permission form for school field trips.',
                'category'    => 'Education',
                'color'       => 'green',
                'fields'      => [
                    ['id'=>'f1', 'type'=>'fullname', 'label'=>'Student Full Name',    'required'=>true,  'placeholder'=>'', 'options'=>[]],
                    ['id'=>'f2', 'type'=>'dob',      'label'=>'Date of Birth',        'required'=>true,  'placeholder'=>'', 'options'=>[]],
                    ['id'=>'f3', 'type'=>'text',     'label'=>'Grade / Class',        'required'=>true,  'placeholder'=>'', 'options'=>[]],
                    ['id'=>'f4', 'type'=>'fullname', 'label'=>'Parent/Guardian Name', 'required'=>true,  'placeholder'=>'', 'options'=>[]],
                    ['id'=>'f5', 'type'=>'phone',    'label'=>'Parent/Guardian Phone','required'=>true,  'placeholder'=>'', 'options'=>[]],
                    ['id'=>'f6', 'type'=>'email',    'label'=>'Parent/Guardian Email','required'=>true,  'placeholder'=>'', 'options'=>[]],
                    ['id'=>'f7', 'type'=>'textarea', 'label'=>'Medical Conditions / Allergies','required'=>false,'placeholder'=>'','options'=>[]],
                    ['id'=>'f8', 'type'=>'yesno',    'label'=>'I give permission for my child to attend','required'=>true,'placeholder'=>'','options'=>['Yes','No']],
                    ['id'=>'f9', 'type'=>'signature','label'=>'Parent/Guardian Signature','required'=>true,'placeholder'=>'','options'=>[]],
                ],
            ],
            [
                'id'          => 'service-agreement',
                'title'       => 'Service Agreement',
                'description' => 'Professional service contract between provider and client.',
                'category'    => 'Business',
                'color'       => 'blue',
                'fields'      => [
                    ['id'=>'f1', 'type'=>'fullname', 'label'=>'Client Full Name',     'required'=>true,  'placeholder'=>'', 'options'=>[]],
                    ['id'=>'f2', 'type'=>'text',     'label'=>'Company Name',         'required'=>false, 'placeholder'=>'', 'options'=>[]],
                    ['id'=>'f3', 'type'=>'email',    'label'=>'Email Address',        'required'=>true,  'placeholder'=>'', 'options'=>[]],
                    ['id'=>'f4', 'type'=>'phone',    'label'=>'Phone Number',         'required'=>true,  'placeholder'=>'', 'options'=>[]],
                    ['id'=>'f5', 'type'=>'textarea', 'label'=>'Services Requested',   'required'=>true,  'placeholder'=>'Describe the services','options'=>[]],
                    ['id'=>'f6', 'type'=>'date',     'label'=>'Project Start Date',   'required'=>true,  'placeholder'=>'', 'options'=>[]],
                    ['id'=>'f7', 'type'=>'select',   'label'=>'Payment Terms',        'required'=>true,  'placeholder'=>'', 'options'=>['Net 15','Net 30','Net 60','Upon Completion','50% Upfront']],
                    ['id'=>'f8', 'type'=>'yesno',    'label'=>'I agree to the service terms','required'=>true,'placeholder'=>'','options'=>['Yes','No']],
                    ['id'=>'f9', 'type'=>'signature','label'=>'Client Signature',     'required'=>true,  'placeholder'=>'', 'options'=>[]],
                ],
            ],
            [
                'id'          => 'client-intake',
                'title'       => 'Client Intake Form',
                'description' => 'Collect essential information from new clients during onboarding.',
                'category'    => 'Business',
                'color'       => 'teal',
                'fields'      => [
                    ['id'=>'f1','type'=>'fullname', 'label'=>'Full Name',             'required'=>true,  'placeholder'=>'', 'options'=>[]],
                    ['id'=>'f2','type'=>'email',    'label'=>'Email Address',         'required'=>true,  'placeholder'=>'', 'options'=>[]],
                    ['id'=>'f3','type'=>'phone',    'label'=>'Phone Number',          'required'=>true,  'placeholder'=>'', 'options'=>[]],
                    ['id'=>'f4','type'=>'text',     'label'=>'Company Name',          'required'=>false, 'placeholder'=>'', 'options'=>[]],
                    ['id'=>'f5','type'=>'url',      'label'=>'Website',               'required'=>false, 'placeholder'=>'https://','options'=>[]],
                    ['id'=>'f6','type'=>'select',   'label'=>'How did you hear about us?','required'=>false,'placeholder'=>'','options'=>['Google','Social Media','Referral','Advertisement','Other']],
                    ['id'=>'f7','type'=>'textarea', 'label'=>'Project Goals',         'required'=>true,  'placeholder'=>'What are you hoping to achieve?','options'=>[]],
                    ['id'=>'f8','type'=>'select',   'label'=>'Budget Range',          'required'=>false, 'placeholder'=>'', 'options'=>['Under $1,000','$1,000–$5,000','$5,000–$10,000','$10,000+']],
                    ['id'=>'f9','type'=>'textarea', 'label'=>'Additional Notes',      'required'=>false, 'placeholder'=>'Anything else we should know','options'=>[]],
                ],
            ],
            [
                'id'          => 'volunteer-form',
                'title'       => 'Volunteer Application',
                'description' => 'Register volunteers and collect availability and skills.',
                'category'    => 'Nonprofit',
                'color'       => 'teal',
                'fields'      => [
                    ['id'=>'f1','type'=>'fullname', 'label'=>'Full Name',             'required'=>true,  'placeholder'=>'', 'options'=>[]],
                    ['id'=>'f2','type'=>'email',    'label'=>'Email Address',         'required'=>true,  'placeholder'=>'', 'options'=>[]],
                    ['id'=>'f3','type'=>'phone',    'label'=>'Phone Number',          'required'=>true,  'placeholder'=>'', 'options'=>[]],
                    ['id'=>'f4','type'=>'checkbox', 'label'=>'Availability',          'required'=>true,  'placeholder'=>'', 'options'=>['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday']],
                    ['id'=>'f5','type'=>'select',   'label'=>'Hours per Week',        'required'=>true,  'placeholder'=>'', 'options'=>['1-5 hours','5-10 hours','10-20 hours','20+ hours']],
                    ['id'=>'f6','type'=>'textarea', 'label'=>'Skills & Experience',   'required'=>false, 'placeholder'=>'Describe relevant skills','options'=>[]],
                    ['id'=>'f7','type'=>'yesno',    'label'=>'I agree to volunteer terms','required'=>true,'placeholder'=>'','options'=>['Yes','No']],
                ],
            ],
            [
                'id'          => 'salon-consent',
                'title'       => 'Salon / Spa Consent Form',
                'description' => 'Client intake and consent form for beauty and spa services.',
                'category'    => 'Beauty',
                'color'       => 'purple',
                'fields'      => [
                    ['id'=>'f1','type'=>'fullname', 'label'=>'Full Name',             'required'=>true,  'placeholder'=>'', 'options'=>[]],
                    ['id'=>'f2','type'=>'dob',      'label'=>'Date of Birth',         'required'=>true,  'placeholder'=>'', 'options'=>[]],
                    ['id'=>'f3','type'=>'email',    'label'=>'Email Address',         'required'=>true,  'placeholder'=>'', 'options'=>[]],
                    ['id'=>'f4','type'=>'phone',    'label'=>'Phone Number',          'required'=>true,  'placeholder'=>'', 'options'=>[]],
                    ['id'=>'f5','type'=>'select',   'label'=>'Service Requested',     'required'=>true,  'placeholder'=>'', 'options'=>['Haircut','Color','Facial','Massage','Manicure','Pedicure','Other']],
                    ['id'=>'f6','type'=>'textarea', 'label'=>'Known Allergies',       'required'=>false, 'placeholder'=>'','options'=>[]],
                    ['id'=>'f7','type'=>'yesno',    'label'=>'Any skin conditions?',  'required'=>true,  'placeholder'=>'', 'options'=>['Yes','No']],
                    ['id'=>'f8','type'=>'yesno',    'label'=>'I consent to the selected service','required'=>true,'placeholder'=>'','options'=>['Yes','No']],
                    ['id'=>'f9','type'=>'signature','label'=>'Signature',             'required'=>true,  'placeholder'=>'', 'options'=>[]],
                ],
            ],
            [
                'id'          => 'sports-registration',
                'title'       => 'Sports Team Registration',
                'description' => 'Player registration form for sports leagues and teams.',
                'category'    => 'Sports',
                'color'       => 'green',
                'fields'      => [
                    ['id'=>'f1', 'type'=>'fullname', 'label'=>'Player Full Name',     'required'=>true,  'placeholder'=>'', 'options'=>[]],
                    ['id'=>'f2', 'type'=>'dob',      'label'=>'Date of Birth',        'required'=>true,  'placeholder'=>'', 'options'=>[]],
                    ['id'=>'f3', 'type'=>'email',    'label'=>'Email Address',        'required'=>true,  'placeholder'=>'', 'options'=>[]],
                    ['id'=>'f4', 'type'=>'phone',    'label'=>'Phone Number',         'required'=>true,  'placeholder'=>'', 'options'=>[]],
                    ['id'=>'f5', 'type'=>'select',   'label'=>'Experience Level',     'required'=>true,  'placeholder'=>'', 'options'=>['Beginner','Intermediate','Advanced','Professional']],
                    ['id'=>'f6', 'type'=>'text',     'label'=>'Emergency Contact',    'required'=>true,  'placeholder'=>'', 'options'=>[]],
                    ['id'=>'f7', 'type'=>'phone',    'label'=>'Emergency Contact Phone','required'=>true,'placeholder'=>'', 'options'=>[]],
                    ['id'=>'f8', 'type'=>'yesno',    'label'=>'Any medical conditions?','required'=>true,'placeholder'=>'','options'=>['Yes','No']],
                    ['id'=>'f9', 'type'=>'yesno',    'label'=>'I agree to team rules','required'=>true,  'placeholder'=>'', 'options'=>['Yes','No']],
                    ['id'=>'f10','type'=>'signature','label'=>'Signature',            'required'=>true,  'placeholder'=>'', 'options'=>[]],
                ],
            ],
        ];
    }
}