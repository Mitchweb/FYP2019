<?php

/**
 * @package BeeQuiz
 */

class Quiz_Database_Schema
{
    const TYPES = array(
        'd' => 'integer',
        's' => 'string'
    );

    const ATTRIBUTE_DESCRIPTION_SCHEMA = array(
        array(
            'category' => 'University Status',
            'fields' => 6,
            'description' => 'Do you help students or graduates? If the former, do they have to study at a certain university?'
        ),
        array(
            'category' => 'Industry',
            'fields' => 11,
            'description' => 'Which industries do you offer business support for?'
        ),
        array(
            'category' => 'Business Stage',
            'fields' => 3,
            'description' => 'Do you support people with no business idea, those with an idea, or those with a business?'
        ),
        array(
            'category' => 'Time Trading',
            'fields' => 3,
            'description' => 'How long do businesses need to have been trading to receive your support?'
        ),
        array(
            'category' => 'Business Size',
            'fields' => 3,
            'description' => 'Which business sizes do you support?'
        ),
        array(
            'category' => 'Financial Support',
            'fields' => 3,
            'description' => 'What financial support, if any, does your organisation provide?'
        ),
        array(
            'category' => 'Areas Covered',
            'fields' => 4,
            'description' => 'What business areas, if any, does your organisation cover?'
        ),
        array(
            'category' => 'Skills Training',
            'fields' => 4,
            'description' => 'What skills training, if any, does your organisation provide?'
        ),
        array(
            'category' => 'Support Types',
            'fields' => 6,
            'description' => 'What types of support does your organisation provide?'
        )
    );

    const ATTRIBUTE_SCHEMA = array(
        'university_of_birmingham' => 'University of Birmingham',
        'birmingham_city_university' => 'Birmingham City University',
        'university_college_birmingham' => 'University College Birmingham',
        'aston_university' => 'Aston University',
        'newman_university' => 'Newman University',
        'graduate' => 'Graduate',

        'manufacturing_or_engineering' => 'Manufacturing or Engineering',
        'tourism_or_leisure' => 'Tourism or Leisure',
        'aerospace' => 'Aerospace',
        'financial_services' => 'Financial Services',
        'automotive' => 'Automotive',
        'digital_or_creative' => 'Digital or Creative',
        'medical_technologies' => 'Medical Technologies',
        'construction' => 'Construction',
        'environmental_technologies' => 'Environmental Technologies',
        'food_and_drink' => 'Food and Drink',
        'energy' => 'Energy',

        'no_business_idea' => 'No Business Idea',
        'business_idea' => 'Business Idea',
        'business' => 'Business',

        'under_6_months' => 'Under 6 months',
        'over_6_months_under_3_years' => 'Between 6 months and 3 years',
        'over_3_years' => 'Over 3 years',

        'people_1' => '1 person',
        'people_2_to_5' => '2 to 5 people',
        'people_over_5' => 'Over 5 people',

        'grants' => 'Grants',
        'cash_loan' => 'Cash Loan',
        'equity_loan' => 'Equity Loan',
        
        'accounting' => 'Accounting',
        'legal' => 'Legal',
        'marketing' => 'Marketing',
        'sales' => 'Sales',

        'digital_skills' => 'Digital Skills',
        'networking_skills' => 'Networking Skills',
        'presentation_skills' => 'Presentation Skills',
        'writing_skills' => 'Writing Skills',

        'accelerators' => 'Accelerators',
        'coworking_space' => 'Co-working Space',
        'guest_talks' => 'Guest Talks',
        'incubators' => 'Incubators',
        'mentoring' => 'Mentoring',
        'workshops' => 'Workshops'
    );

    public static function check_attribute( $key ) {
        return array_key_exists( $key, self::ATTRIBUTE_SCHEMA );
    }
}