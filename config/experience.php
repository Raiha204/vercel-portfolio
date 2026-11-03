<?php

/*
|--------------------------------------------------------------------------
| Work experience shown on the portfolio
|--------------------------------------------------------------------------
| Listed in the order shown (put the newest first). Run `php artisan portfolio:export`
| after any change.
|
|   title, company        required
|   employment_type       e.g. 'Full-time', 'Part-time', 'Internship'
|   start, end            'YYYY-MM'. Leave `end` as null for a role you still hold (shown as "Present").
|   location, work_mode   e.g. 'Davao del Sur, Philippines', 'On-site' / 'Hybrid' / 'Remote'
|   description           what you did, in your own words
|   published             set to false to hide one
*/

return [

    [
        'title' => 'Operation Customer Expert',
        'company' => 'Teleperformance',
        'employment_type' => 'Full-time',
        'start' => '2025-06',
        'end' => '2025-11',
        'location' => 'Davao del Sur, Davao Region, Philippines',
        'work_mode' => 'On-site',
        'description' => 'Worked in the healthcare support industry, assisting customers by addressing their concerns related to benefits, claims, and health plans.',
    ],

];
