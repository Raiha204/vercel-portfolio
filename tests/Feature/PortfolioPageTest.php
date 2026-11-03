<?php

namespace Tests\Feature;

use Tests\TestCase;

class PortfolioPageTest extends TestCase
{
    public function test_home_shows_owner_projects_certificates_and_contact_details(): void
    {
        $this->get('/')
            ->assertOk()
            ->assertSee('Carl Adrian Gilbuena')
            ->assertSee('MAX Gym Integrated Management System')
            ->assertSee('Reading Habit Analytics')
            ->assertSee('Farm Supply Inventory Management System')
            ->assertSee('Grocery Store Point-of-Sale System')
            ->assertSee('Mobile application')
            ->assertSee('Desktop application')
            ->assertSee('HTML and CSS')
            ->assertSee('Databases')
            ->assertSee('yzMp-uTn8')
            ->assertSee('yWU9-uTnH')
            ->assertSee('Introduction to C#')
            ->assertSee('CC-0CCWRQWX')
            ->assertSee('October 24, 2026')
            ->assertSee('Sololearn')
            ->assertSee('May 18, 2026')
            ->assertSee('09705476036')
            ->assertSee('gilbuenacarladrian12@gmail.com')
            ->assertSee('https://wa.me/639705476036', false)
            ->assertSee('mailto:gilbuenacarladrian12@gmail.com', false)
            ->assertSee('images/profile.jpg', false);
    }

    public function test_nothing_is_left_as_a_placeholder_that_was_filled_in(): void
    {
        $html = $this->get('/')
            ->assertDontSee('GitHub link to be added')
            ->assertDontSee('Technologies to be added')
            ->assertDontSee('Live demo')
            ->getContent();

        $this->assertStringNotContainsString('Screenshot placeholder', $html);
    }

    public function test_placeholders_can_be_switched_off(): void
    {
        config(['portfolio.show_placeholders' => false]);

        $this->get('/')
            ->assertOk()
            ->assertDontSee('Technologies to be added')
            ->assertDontSee('GitHub link to be added')
            ->assertDontSee('Technologies to be added')
            ->assertDontSee('Type to be added');
    }

    public function test_valid_links_are_shown_and_unsafe_links_never_become_live_links(): void
    {
        $projects = config('projects');
        $projects[1]['github_url'] = 'https://github.com/example-user/reading-habit';
        $projects[1]['demo_url'] = 'https://reading.example-host.test';
        $projects[3]['github_url'] = 'javascript:alert(1)';
        $projects[3]['demo_url'] = 'not a link';
        config(['projects' => $projects]);

        $this->get('/')
            ->assertOk()
            ->assertSee('href="https://github.com/example-user/reading-habit"', false)
            ->assertSee('href="https://reading.example-host.test"', false)
            ->assertDontSee('javascript:alert', false)
            ->assertDontSee('not a link');
    }

    public function test_screenshots_listed_in_the_config_are_shown(): void
    {
        $projects = config('projects');
        $projects[1]['screenshots'] = [
            ['src' => 'images/projects/reading-home.png', 'alt' => 'Reading home screen', 'caption' => 'Home'],
            ['src' => 'images/projects/reading-stats.png', 'alt' => 'Reading statistics'],
            ['src' => '', 'alt' => 'ignored because it has no file'],
        ];
        config(['projects' => $projects]);

        $this->get('/projects/reading-habit-analytics')
            ->assertOk()
            ->assertSee('images/projects/reading-home.png', false)
            ->assertSee('images/projects/reading-stats.png', false)
            ->assertSee('Reading home screen')
            ->assertDontSee('ignored because it has no file');
    }

    public function test_project_page_shows_the_full_write_up_and_neighbours(): void
    {
        $this->get('/projects/reading-habit-analytics')
            ->assertOk()
            ->assertSee('Reading Habit Analytics')
            ->assertSee('The problem')
            ->assertSee('The solution')
            ->assertSee('Implemented features')
            ->assertSee('Reading streaks')
            ->assertDontSee('My development contribution')
            ->assertDontSee('Describe your role here')
            ->assertSee('Mobile application')
            // previous and next projects wrap around the list
            ->assertSee('MAX Gym Integrated Management System')
            ->assertSee('Farm Supply Inventory Management System');
    }

    public function test_hidden_projects_are_left_out_everywhere(): void
    {
        $projects = config('projects');
        $projects[1]['published'] = false;
        config(['projects' => $projects]);

        $this->get('/')->assertOk()->assertDontSee('Reading Habit Analytics');
        $this->get('/projects/reading-habit-analytics')->assertNotFound();
    }

    public function test_projects_appear_in_the_order_listed_in_the_config(): void
    {
        $projects = config('projects');
        config(['projects' => array_reverse($projects)]);

        $this->get('/')->assertSeeInOrder([
            'Grocery Store Point-of-Sale System',
            'MAX Gym Integrated Management System',
        ]);
    }

    public function test_unknown_project_returns_the_404_page(): void
    {
        $this->get('/projects/does-not-exist')->assertNotFound()->assertSee('That page does not exist.');
    }

    public function test_the_admin_area_and_logins_are_gone(): void
    {
        foreach (['/admin', '/admin/login', '/admin/projects', '/login', '/storage/anything'] as $url) {
            $this->get($url)->assertNotFound();
        }
        $this->post('/admin/login', ['email' => 'a@b.test', 'password' => 'x'])->assertNotFound();
    }

    public function test_the_site_has_no_database_dependency(): void
    {
        // Nothing may touch the database: point it at a connection that cannot exist.
        config(['database.default' => 'missing', 'database.connections.missing' => ['driver' => 'sqlite', 'database' => '/nonexistent/none.sqlite']]);

        $this->get('/')->assertOk();
        $this->get('/projects/grocery-store-point-of-sale-system')->assertOk();
    }

    public function test_every_project_links_to_its_github_repository(): void
    {
        $this->get('/')
            ->assertSee('https://github.com/Raiha204/max_gym_maximum_fitness', false)
            ->assertSee('https://github.com/Raiha204/Reading_Analytic_Habit_App', false)
            ->assertSee('https://github.com/Raiha204/Farm-Supply-Inventory-System', false)
            ->assertSee('https://github.com/Raiha204/POS-GROCERY-SYSTEM', false)
            ->assertSee('https://github.com/Raiha204/nolune-ecommerce', false);

        $this->get('/projects/grocery-store-point-of-sale-system')
            ->assertSee('https://github.com/Raiha204/POS-GROCERY-SYSTEM', false);
    }

    public function test_every_file_named_in_the_content_config_exists(): void
    {
        $paths = [];
        foreach (config('certificates') as $c) {
            $paths[] = $c['preview'] ?? null;
            $paths[] = $c['pdf'] ?? null;
        }
        foreach (config('projects') as $p) {
            foreach ($p['screenshots'] ?? [] as $s) {
                $paths[] = $s['src'] ?? null;
            }
        }
        $paths[] = config('portfolio.owner.photo');

        foreach (array_filter($paths) as $path) {
            $this->assertFileExists(public_path($path), "Missing file: {$path}");
        }
    }

    public function test_phone_screenshots_use_the_portrait_frame_on_the_details_page(): void
    {
        $this->get('/projects/reading-habit-analytics')->assertOk()->assertSee('images/projects/reading-overview.jpg', false);

        $this->assertTrue((new \App\Data\Screenshot('images/projects/reading-home.jpg'))->isPortrait());
        $this->assertFalse((new \App\Data\Screenshot('images/projects/reading-overview.jpg'))->isPortrait());
        $this->assertFalse((new \App\Data\Screenshot('images/projects/does-not-exist.jpg'))->isPortrait());
    }

    public function test_app_links_have_a_copy_fallback_and_whatsapp_opens_in_a_new_tab(): void
    {
        $html = $this->get('/')->getContent();

        $this->assertStringContainsString('href="viber://chat?number=%2B639705476036" data-app-link data-app-name="Viber"', $html);
        $this->assertStringContainsString('href="mailto:gilbuenacarladrian12@gmail.com" data-app-link data-app-name="Email"', $html);
        $this->assertStringContainsString('href="https://wa.me/639705476036" target="_blank"', $html);
        $this->assertSame(2, substr_count($html, 'data-app-hint'));
    }

    public function test_profile_buttons_link_to_github_and_linkedin(): void
    {
        $this->get('/')
            ->assertSee('href="https://github.com/Raiha204"', false)
            ->assertSee('href="https://www.linkedin.com/in/carl-adrian-gilbuena-783b76375/"', false)
            ->assertDontSee('trackingId', false);
    }

    public function test_work_experience_is_listed_and_linked_from_the_menu(): void
    {
        $this->get('/')
            ->assertSee('id="experience"', false)
            ->assertSee('Operation Customer Expert')
            ->assertSee('Full-time')
            ->assertSee('Jun 2025 – Nov 2025')
            ->assertSee('6 mos')
            ->assertSee('Davao del Sur, Davao Region, Philippines · On-site')
            ->assertSee('benefits, claims, and health plans')
            ->assertSee('#experience', false);
    }

    public function test_brand_logos_and_online_profiles_are_shown_and_the_company_is_spelled_out(): void
    {
        $html = $this->get('/')->getContent();

        $this->assertStringContainsString('Teleperformance', $html);
        $this->assertStringNotContainsString('>TP<', $html);
        $this->assertGreaterThanOrEqual(8, substr_count($html, 'brand-icon'));
        $this->assertStringContainsString('contact-icon is-whatsapp', $html);
        $this->assertStringContainsString('contact-icon is-viber', $html);
        $this->assertStringContainsString('contact-icon is-github', $html);
        $this->assertStringContainsString('contact-icon is-linkedin', $html);
        $this->assertStringContainsString('data-copy="https://github.com/Raiha204"', $html);
        $this->assertStringContainsString('github.com/Raiha204', $html);
    }

    public function test_the_projects_section_has_no_intro_paragraph(): void
    {
        $this->get('/')->assertSee('Selected work')->assertDontSee('built around practical business problems');
    }

    public function test_the_header_has_no_profile_buttons_but_the_contact_section_has_both_profiles(): void
    {
        $html = $this->get('/')->getContent();
        $hero = substr($html, 0, strpos($html, 'id="projects"'));

        $this->assertStringContainsString('View projects', $hero);
        $this->assertStringContainsString('Contact me', $hero);
        $this->assertStringNotContainsString('GitHub profile', $html);
        $this->assertStringNotContainsString('github.com', $hero);
        $this->assertStringNotContainsString('linkedin.com', $hero);
        $this->assertStringContainsString('href="https://www.linkedin.com/in/carl-adrian-gilbuena-783b76375/"', $html);
    }

    public function test_the_skills_section_lists_skills_without_project_counts(): void
    {
        $this->get('/')
            ->assertSee('Tech skills')
            ->assertSee('href="'.route('home').'#skills"', false)
            ->assertSee('Laravel')
            ->assertDontSee('Used in 3 projects')
            ->assertDontSee('Certiport certified');
    }

    public function test_a_skill_can_show_a_note_and_hidden_skills_are_skipped(): void
    {
        config(['skills' => [
            ['name' => 'Zig', 'note' => 'Just learning'],
            ['name' => 'Cobol', 'published' => false],
        ]]);

        $this->get('/')
            ->assertSee('Zig')
            ->assertSee('Just learning')
            ->assertDontSee('Cobol');
    }

    public function test_the_skills_section_is_left_out_when_no_skills_are_configured(): void
    {
        config(['skills' => []]);

        $this->get('/')->assertOk()->assertDontSee('Tech skills');
    }

    public function test_skills_and_project_technologies_show_their_logos(): void
    {
        $home = $this->get('/')->getContent();

        // One logo per skill (26 listed) plus the logos on project technology chips.
        $this->assertGreaterThanOrEqual(26, substr_count($home, 'skill-logo'));
        $this->assertStringContainsString('<svg', $home);
        $this->assertSame('php', \App\Support\TechIcons::slug('PHP'));
        $this->assertSame('alpinedotjs', \App\Support\TechIcons::slug('Alpine.js'));
        $this->assertNull(\App\Support\TechIcons::slug('Something unknown'));
        $this->assertTrue(\App\Support\TechIcons::isDark('github'));
        $this->assertFalse(\App\Support\TechIcons::isDark('react'));
    }

    public function test_every_logo_named_in_the_icon_map_exists(): void
    {
        $names = ['HTML', 'CSS', 'Tailwind CSS', 'React', 'TypeScript', 'Alpine.js', 'PHP', 'Laravel', 'Inertia.js',
            'MySQL', 'Vite', 'GitHub', 'Blade', 'Databases', 'Python', 'Flutter', 'Dart', 'Cloud Firestore',
            'Git', 'REST APIs', 'Postman', 'UI/UX Design', 'Deployment & Hosting', 'Firebase', 'C#', 'Java', 'C++'];

        foreach ($names as $name) {
            $slug = \App\Support\TechIcons::slug($name);
            $this->assertNotNull($slug, "No logo for {$name}");
            $this->assertStringContainsString('<svg', \App\Support\TechIcons::svg($slug));
        }
    }

    public function test_skills_are_one_combined_list_without_groups(): void
    {
        $html = $this->get('/')->getContent();

        $this->assertStringNotContainsString('skill-group', $html);
        $this->assertStringNotContainsString('Front end', $html);
        $this->assertSame(1, substr_count($html, 'skill-board'));
        $this->assertSame(26, substr_count($html, 'class="skill"'));
    }

    public function test_the_older_grouped_skills_format_is_still_flattened(): void
    {
        config(['skills' => [
            ['group' => 'A', 'skills' => [['name' => 'PHP'], ['name' => 'Laravel']]],
            ['group' => 'B', 'skills' => [['name' => 'MySQL']]],
        ]]);

        $this->assertSame(['PHP', 'Laravel', 'MySQL'], \App\Support\Portfolio::skills()->pluck('name')->all());
    }

    public function test_the_hero_leads_with_the_role(): void
    {
        $html = $this->get('/')->getContent();
        $hero = substr($html, 0, strpos($html, 'id="projects"'));

        $this->assertStringContainsString('aria-label="Web Developer"', $hero);
        // The role is in the page as plain text (it is what shows without JavaScript); the typed greeting is added by the script.
        $this->assertStringContainsString('<span class="hl" data-tw-line>Web</span>', $hero);
        $this->assertStringContainsString('<span data-tw-line>Developer</span>', $hero);
        $this->assertStringContainsString('data-intro="[&quot;Hi, I’m&quot;,&quot;Carl Adrian&quot;]"', $hero);
        $this->assertStringNotContainsString('class="greet"', $hero);
    }

    public function test_the_cv_button_only_appears_when_the_file_exists(): void
    {
        $this->get('/')->assertDontSee('Download CV');

        config(['portfolio.owner.cv' => 'cv/does-not-exist.pdf']);
        $this->get('/')->assertDontSee('Download CV');

        @mkdir(public_path('cv'));
        file_put_contents(public_path('cv/test-cv.pdf'), '%PDF-1.4');
        try {
            config(['portfolio.owner.cv' => 'cv/test-cv.pdf']);
            $this->get('/')->assertSee('Download CV')->assertSee('cv/test-cv.pdf', false);
        } finally {
            @unlink(public_path('cv/test-cv.pdf'));
            @rmdir(public_path('cv'));
        }
    }

    public function test_project_cards_are_short_and_show_at_most_five_technologies(): void
    {
        $html = $this->get('/')->getContent();

        $this->assertStringContainsString('Problem solved', $html);
        $this->assertStringContainsString('Key features', $html);
        $this->assertStringContainsString('class="feat-line"', $html);
        // NOLUNE lists eight technologies: five are shown and the rest are counted.
        $this->assertStringContainsString('class="chip-more">+3</li>', $html);
        $this->assertStringContainsString('Member management · Membership payments · Partial payments', $html);
    }

    public function test_a_section_menu_follows_the_visitor_and_covers_every_section(): void
    {
        $html = $this->get('/')->getContent();

        $this->assertStringContainsString('data-stickybar', $html);
        foreach (['projects', 'skills', 'certifications', 'experience', 'contact'] as $id) {
            $this->assertStringContainsString('href="#'.$id.'"', $html);
            $this->assertStringContainsString('id="'.$id.'"', $html);
        }
    }

    public function test_certifications_come_before_work_experience(): void
    {
        $html = $this->get('/')->getContent();

        $this->assertLessThan(strpos($html, 'id="experience"'), strpos($html, 'id="certifications"'));
    }

    public function test_email_is_the_featured_contact_and_copy_buttons_have_a_label(): void
    {
        $html = $this->get('/')->getContent();
        $contact = substr($html, strpos($html, 'id="contact"'));

        $this->assertSame(strpos($contact, 'class="contact-card'), strpos($contact, 'class="contact-card reveal is-primary'));
        // email, WhatsApp & Viber (one number, one card), GitHub, LinkedIn
        $this->assertSame(4, substr_count($contact, 'btn-copy'));
        $this->assertSame(4, substr_count($contact, '<span class="visually-hidden">Copy</span>'));
    }

    public function test_the_details_page_has_a_gallery_that_can_be_enlarged(): void
    {
        $js = file_get_contents(public_path('js/portfolio.js'));

        $this->assertStringContainsString('initLightbox', $js);
        $this->get('/projects/max-gym-integrated-management-system')->assertSee('data-gallery-main', false);
    }

    public function test_email_and_viber_links_are_safe_inside_an_embedded_preview(): void
    {
        $js = file_get_contents(public_path('js/portfolio.js'));

        // Inside a frame the script must not let mailto:/viber: replace the frame with an error page.
        $this->assertStringContainsString('window.self !== window.top', $js);
        $this->assertStringContainsString('e.preventDefault()', $js);
        $this->assertStringContainsString('copyInstead(link)', $js);
    }

    public function test_the_header_is_just_the_greeting_headline_intro_buttons_and_photo(): void
    {
        $html = $this->get('/')->getContent();
        $hero = substr($html, 0, strpos($html, 'id="projects"'));

        $this->assertStringContainsString('Web', $hero);
        $this->assertStringContainsString('View projects', $hero);
        $this->assertStringContainsString('Contact me', $hero);
        $this->assertStringContainsString('class="portrait"', $hero);
        // The project/certification counts and the row of tech pills were removed from the header.
        $this->assertStringNotContainsString('hero-meta', $hero);
        $this->assertStringNotContainsString('hero-facts', $hero);
        $this->assertStringNotContainsString('hero-stack', $hero);
        // The caption under the photo repeated the name and role, so it is gone.
        $this->assertStringNotContainsString('p-role', $hero);
    }

    public function test_skills_are_compact_chips_in_a_sensible_order_with_staggered_entrance(): void
    {
        $html = $this->get('/')->getContent();

        $this->assertSame(26, substr_count($html, 'class="skill"'));
        // Chip number (for the staggered entrance) and a brand colour for the hover glow.
        $this->assertStringContainsString('style="--i:0;--brand-hover:#', $html);
        $this->assertStringContainsString('--i:25', $html);

        $names = \App\Support\Portfolio::skills()->pluck('name')->all();
        $this->assertSame(['HTML', 'CSS', 'JavaScript'], array_slice($names, 0, 3));
        $this->assertLessThan(array_search('Laravel', $names), array_search('PHP', $names));
        $this->assertLessThan(array_search('GitHub', $names), array_search('Git', $names));
    }

    public function test_project_cards_let_visitors_move_through_the_screenshots(): void
    {
        $html = $this->get('/')->getContent();
        $projects = \App\Support\Portfolio::projects();
        $multi = $projects->filter(fn ($p) => $p->screenshots->count() > 1);

        // One carousel per project with several screenshots, each with arrows and one dot per picture.
        $this->assertSame($multi->count(), substr_count($html, 'data-card-carousel'));
        $this->assertSame($multi->count(), substr_count($html, 'data-prev'));
        $this->assertSame($multi->count(), substr_count($html, 'data-next'));
        $this->assertSame($multi->sum(fn ($p) => $p->screenshots->count()), substr_count($html, 'data-dot='));
        // Every screenshot is in the card, only the first one starts visible.
        $this->assertSame($projects->sum(fn ($p) => $p->screenshots->count()), substr_count($html, 'data-slide'));
        $this->assertSame($projects->filter(fn ($p) => $p->screenshots->isNotEmpty())->count(), substr_count($html, 'shot-slide is-active'));

        $js = file_get_contents(public_path('js/portfolio.js'));
        $this->assertStringContainsString('initCardCarousels();', $js);
        $this->assertStringContainsString('ArrowRight', $js);
    }

    public function test_a_project_with_one_screenshot_has_no_carousel_controls(): void
    {
        $projects = config('projects');
        $projects[0]['screenshots'] = [array_values($projects[0]['screenshots'])[0]];
        config(['projects' => $projects]);

        $html = $this->get('/')->getContent();
        $first = substr($html, strpos($html, 'id="project-'.$projects[0]['slug'].'"'));
        $first = substr($first, 0, strpos($first, 'card-body'));

        $this->assertStringNotContainsString('data-card-carousel', $first);
        $this->assertStringNotContainsString('shot-nav', $first);
    }

    public function test_card_texts_are_short_enough_to_end_on_a_full_stop_not_an_ellipsis(): void
    {
        foreach (\App\Support\Portfolio::projects() as $project) {
            $this->assertLessThanOrEqual(100, mb_strlen((string) $project->cardSummary()), $project->title.' card summary');
            $this->assertLessThanOrEqual(140, mb_strlen((string) $project->cardProblem()), $project->title.' card problem');
        }

        // The key-features line never lists more than fits on two lines; the rest is "+N more".
        $html = $this->get('/')->getContent();
        preg_match_all('/<p class="feat-line">(.*?)<\/p>/s', $html, $m);
        $this->assertCount(\App\Support\Portfolio::projects()->count(), $m[1]);
        foreach ($m[1] as $line) {
            $this->assertLessThanOrEqual(90, mb_strlen(trim(html_entity_decode(strip_tags(preg_replace('/<span class="more">.*?<\/span>/s', '', $line))))));
        }
    }

    public function test_the_header_types_a_greeting_then_the_role_and_respects_reduced_motion(): void
    {
        $html = $this->get('/')->getContent();
        $hero = substr($html, 0, strpos($html, 'id="projects"'));

        $this->assertStringContainsString('data-typewriter', $hero);
        $this->assertStringContainsString('<span class="line" aria-hidden="true"><span class="hl" data-tw-line>Web</span></span>', $hero);
        $this->assertStringContainsString('<span class="line is-last" aria-hidden="true"><span data-tw-line>Developer</span></span>', $hero);

        $js = file_get_contents(public_path('js/portfolio.js'));
        $this->assertStringContainsString('function initTypewriter()', $js);
        $this->assertStringContainsString('initTypewriter();', $js);
        $this->assertStringContainsString('tw-skip', $js);        // no typing data: the role simply shows
        $this->assertStringContainsString('Click to pause', $js);  // it loops; a click on the headline pauses it
        $this->assertStringContainsString('Pause typing animation', $js); // and keyboard users get a button
        $this->assertStringContainsString('visibilitychange', $js);       // it also waits while the tab is hidden

        $css = file_get_contents(public_path('css/portfolio.css'));
        foreach (['@keyframes tw-blink', '@keyframes soft-in', '@keyframes btn-in', '@keyframes hl-shine', '.tw-caret'] as $needle) {
            $this->assertStringContainsString($needle, $css);
        }
        // With reduced motion the typing still plays, but the sliding, pulsing and blinking are switched off.
        $this->assertMatchesRegularExpression('/prefers-reduced-motion:reduce\)\{[^@]*\.tw-caret,\.hero-actions \.btn-light\{animation:none!important\}/', $css);
    }

    public function test_the_typed_greeting_can_be_switched_off(): void
    {
        config(['portfolio.owner.typed_intro' => false]);

        $hero = $this->get('/')->getContent();
        $hero = substr($hero, 0, strpos($hero, 'id="projects"'));

        $this->assertStringNotContainsString('data-typewriter', $hero);
        $this->assertStringContainsString('<span class="hl" data-tw-line>Web</span>', $hero);
    }
}
