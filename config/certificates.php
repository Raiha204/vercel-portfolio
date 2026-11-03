<?php

/*
|--------------------------------------------------------------------------
| Certificates shown on the portfolio
|--------------------------------------------------------------------------
| Listed in the order shown. Put the files in public/certificates/ and run
| `php artisan portfolio:export` after any change.
|
|   title, program, issuer, issued_on (YYYY-MM-DD), credential_id, verify_url, expiry_note
|   preview   image shown on the card        e.g. 'certificates/my-cert.jpg'
|   pdf       the certificate PDF            e.g. 'certificates/my-cert.pdf'
|   kind      label on the card, default 'Certification' (use 'Course certificate' for course completions)
|   summary   one line under the title, default is a generic sentence
|   published set to false to hide one
*/

return [

    [
        'title' => 'HTML and CSS',
        'program' => 'Information Technology Specialist',
        'issuer' => 'Certiport, a Pearson VUE business',
        'issued_on' => '2026-05-18',
        'credential_id' => 'yzMp-uTn8',
        // The certificate prints this address for checking it.
        'verify_url' => 'https://verify.certiport.com',
        'expiry_note' => 'Certificate expires 5 years after date of issue',
        'preview' => 'certificates/it-specialist-html-and-css.jpg',
        'pdf' => 'certificates/it-specialist-html-and-css.pdf',
    ],

    [
        'title' => 'Databases',
        'program' => 'Information Technology Specialist',
        'issuer' => 'Certiport, a Pearson VUE business',
        'issued_on' => '2026-05-14',
        'credential_id' => 'yWU9-uTnH',
        'verify_url' => 'https://verify.certiport.com',
        'expiry_note' => 'Certificate expires 5 years after date of issue',
        'preview' => 'certificates/it-specialist-databases.jpg',
        'pdf' => 'certificates/it-specialist-databases.pdf',
    ],

    [
        'title' => 'Introduction to C#',
        'kind' => 'Course certificate',
        'summary' => 'Completed the course by demonstrating theoretical and practical understanding of Introduction to C#.',
        'issuer' => 'Sololearn',
        'issued_on' => '2026-10-24',
        'credential_id' => 'CC-0CCWRQWX',
        'preview' => 'certificates/sololearn-introduction-to-csharp.jpg',
    ],

];
