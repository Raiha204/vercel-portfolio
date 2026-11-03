# Carl Adrian Gilbuena · Projects Portfolio

A Laravel portfolio site with no admin, no database and no login. Your projects and certificates live in two
plain files, and one command turns the whole site into static HTML in `dist/`, which Vercel serves.

**What is on the site**

- Home page: hero with photo, project cards, work experience, certificates, contact (WhatsApp, Viber, email with copy buttons).
- One details page per project (`/projects/{slug}`): problem, solution, implemented features, technologies,
  your contribution, screenshots, GitHub and live demo links, previous/next project.
- Dashed placeholders for anything not filled in yet. A link only appears when it is a valid `http(s)` address.
- Animation: staged hero entrance, a scanner beam over the portrait, self-drawing placeholder wireframes,
  scroll reveal and tilt on cards. It all switches off for visitors who prefer reduced motion.
- Light and dark themes follow the visitor's system setting. Works from phone to desktop.

## Deploy on Vercel

The repository already contains the built site in `dist/` and a `vercel.json` that tells Vercel to serve it,
so Vercel does not need PHP.

1. Put this folder in a GitHub repository (include `dist/`).
2. On vercel.com choose **Add New → Project** and import the repository.
3. Leave everything as it is. `vercel.json` already sets *Framework: Other*, no install command, no build command,
   and *Output Directory: dist*. Click **Deploy**.

## Change something, then redeploy

1. Edit the content (see below).
2. Rebuild the static site:

   ```sh
   php artisan portfolio:export
   ```

3. Commit and push, including the changed `dist/` folder. Vercel redeploys automatically.

You need PHP 8.3+ and Composer on your own computer for step 2 (see "Run it locally").

## Where to edit things

| What | Where |
| --- | --- |
| Tech skills (one combined list, in the order you write them) | `config/skills.php` |
| Projects: text, type (web/mobile/desktop), technologies, contribution, screenshots, GitHub and demo links | `config/projects.php` |
| Work experience (add a job, or set `end` to null for a current one) | `config/experience.php` |
| Certificates | `config/certificates.php` |
| Name, role, intro, WhatsApp, Viber, email | `.env` or the defaults in `config/portfolio.php` |
| Hide the dashed placeholders | `PORTFOLIO_SHOW_PLACEHOLDERS=false` in `.env` |
| The typed header ("Hi, I'm Carl Adrian" and "Web Developer" are typed and erased in a loop; click it to pause) | `typed_intro` in `config/portfolio.php`, set it to `false` to show the role straight away |
| Your photo | `public/images/profile.jpg` |
| Project screenshots | put the files in `public/images/projects/` and list them under `screenshots` |
| Certificate files | `public/certificates/` |

`config/projects.php` explains every field at the top. To add a screenshot to a project:

```php
'screenshots' => [
    ['src' => 'images/projects/max-gym-dashboard.png', 'alt' => 'Member dashboard', 'caption' => 'Dashboard'],
],
```

On the home page every project card with more than one screenshot becomes a small slider: arrows, dots, a click on the picture,
a swipe on a phone, or the left/right arrow keys move to the next or previous picture. A project with one screenshot shows it without controls.

### Still to fill in

- Optional: a library screenshot for Reading Habit Analytics (re-take it with a book you are free to show).
- Live demo links (none of the five is deployed as far as this site knows, so none is shown).

Project types, technologies and the CRUD feature of Farm Supply were read from your source code, not guessed. The NOLUNE details come from its README, routes, composer.json and package.json (payments there are simulated, and the entry says so).
Check them in `config/projects.php` and edit anything that is off. The NOLUNE screenshots (home, catalog, registration) are your own captures of the running store. The screenshots of MAX Gym, Farm Supply and the Grocery POS
were taken from your apps running on invented sample data and are captioned "(sample data)".
The Reading Habit Analytics screenshots are your own phone captures (status bar cropped, e-mail address blurred).

## Run it locally

Requirements: PHP 8.3 or newer (extensions `mbstring`, `fileinfo`, `openssl`, `tokenizer`, `xml`, `ctype`) and [Composer](https://getcomposer.org).

```sh
composer install
cp .env.example .env
php artisan key:generate
php artisan serve          # live Laravel site at http://localhost:8000
```

`composer setup` does the first three steps and also builds `dist/`.

To check the exact files Vercel will serve, run `php artisan portfolio:export` and open `dist/index.html`
through any static file server.

## Project layout

```
config/projects.php            your projects
config/skills.php              your tech skills (one list)
config/experience.php           your work experience
config/certificates.php        your certificates
config/portfolio.php           name, contact details, placeholder switch
app/Data/                      Project, Certificate, Screenshot (clean and validate the content)
app/Support/Portfolio.php      reads the two content files
app/Http/Controllers/          PortfolioController (home and project pages)
app/Console/Commands/          portfolio:export (writes dist/)
resources/views/               Blade pages and components
public/css, public/js          styling and animation
dist/                          the built site that Vercel serves
vercel.json                    tells Vercel to serve dist/
tests/                         53 tests
```

## Tests

```sh
php artisan test
```

They cover the pages, placeholders, link safety, screenshots, hidden projects, the export (every page written,
links work from every page) and that the old admin addresses no longer exist.

## Why there is no admin

Vercel has no persistent disk and no database, so an admin area that saves projects and uploads could not keep
anything between requests. The content is in files you edit and commit instead.

## Download CV button (optional)

Put your CV in `public/cv/` (for example `public/cv/Carl-Adrian-Gilbuena-CV.pdf`) and set `PORTFOLIO_CV=cv/Carl-Adrian-Gilbuena-CV.pdf`
in `.env` (or edit `config/portfolio.php`). A "Download CV" button then appears in the header. With no file there is no button.

## Technology logos

The logos on the Tech skills and on project technology chips come from [Simple Icons](https://simpleicons.org) (CC0), plus the C# and Java logos from [Devicon](https://devicon.dev) (MIT).
They are stored in `resources/svg/tech/`. The logos are trademarks of their owners and are used only to name the technology.
A skill whose name is known (see `app/Support/TechIcons.php`) gets its logo automatically. To add a logo for another technology,
put its SVG in `resources/svg/tech/` and add its name and colour in `TechIcons.php`.
