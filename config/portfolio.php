<?php

/*
|--------------------------------------------------------------------------
| Portfolio settings
|--------------------------------------------------------------------------
| Personal details shown on the site. Change them here or override any value
| from the .env file (PORTFOLIO_NAME, PORTFOLIO_EMAIL, ...).
| Projects and certificates are NOT configured here: edit config/projects.php
| and config/certificates.php, then run `php artisan portfolio:export`.
*/

return [

    // true  = show dashed "to be added" placeholders for missing details
    // false = hide them once every project is fully filled in
    'show_placeholders' => (bool) env('PORTFOLIO_SHOW_PLACEHOLDERS', true),

    'owner' => [
        'name' => env('PORTFOLIO_NAME', 'Carl Adrian Gilbuena'),
        'role' => env('PORTFOLIO_ROLE', 'Web Developer'),
        'intro' => env(
            'PORTFOLIO_INTRO',
            'I build web and mobile applications that help businesses manage members, stock, sales and customers.'
        ),
        // Optional: put your CV in public/ (for example public/cv/Carl-Adrian-Gilbuena-CV.pdf) and set this to
        // 'cv/Carl-Adrian-Gilbuena-CV.pdf'. A "Download CV" button then appears in the header; no file, no button.
        'cv' => env('PORTFOLIO_CV'),
        // The header types "Hi, I'm <your name>", erases it, types your role, erases it, and repeats. Set to false to show the role straight away.
        'typed_intro' => true,
        'photo' => 'images/profile.jpg',
        'photo_alt' => 'Portrait of Carl Adrian Gilbuena in a white collared shirt and black tie against a blue backdrop',

        // Shown in the header only when you add a full https:// link.
        'github' => env('PORTFOLIO_GITHUB'),
        'linkedin' => env('PORTFOLIO_LINKEDIN'),
    ],

    'contact' => [
        'whatsapp' => env('PORTFOLIO_WHATSAPP', '09705476036'),
        'viber' => env('PORTFOLIO_VIBER', '09705476036'),
        'email' => env('PORTFOLIO_EMAIL', 'gilbuenacarladrian12@gmail.com'),

        // Used to turn a local number (09...) into the international form chat links need.
        'country_code' => env('PORTFOLIO_COUNTRY_CODE', '63'),
    ],

];
