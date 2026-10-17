# Webincline website

New website for Webincline, built with plain PHP, CSS and GSAP.

## Run locally

You need PHP 8.1 or newer (`php -v`). Nothing else: no Node.js, no database, no build step.

```sh
git clone https://github.com/gaccesswebi-rgb/Webincline.git
cd Webincline
php -S 127.0.0.1:8080 router.php
```

Then open http://127.0.0.1:8080. Pass `router.php` so clean addresses such as `/services` work; Apache hosting does the same through `.htaccess`.

Installing PHP: on Windows, XAMPP (apachefriends.org) or the zip from windows.php.net; on macOS, `brew install php`; on Ubuntu, `sudo apt install php-cli`.
The contact form saves every lead to `storage/leads.csv`; it only sends email on a server where PHP's `mail()` is set up.

## Where things live

| What | File |
|---|---|
| Contact details, stats, markets, social links, main menu | `includes/config.php` |
| Which pages exist (add a line when a page is built) | `includes/routes.php` |
| The seven services and their sub-services, industries, markets | `includes/services.php` |
| Homepage content: channels, case studies, clients, process, FAQ, team, blog cards | `includes/data.php` |
| Homepage sections | `index.php` |
| Inner pages | `pages/` (`services.php`, `404.php`) |
| Sections shared by several pages (process funnel, FAQ, contact form) | `includes/partials/` |
| Head, navigation (with the services menu), footer | `includes/header.php`, `includes/footer.php` |
| Clean addresses, trailing slashes, 404 | `router.php`, `.htaccess` |
| Contact form handler (emails `LEADS_EMAIL`, keeps a copy in `storage/leads.csv`) | `contact-submit.php` |
| Styles | `assets/css/main.css` |
| Animations (particles, plane flight, scroll reveals, services menu) | `assets/js/main.js` |

Until a page exists, links to it fall back to the matching homepage section (`link_or()` and `anchor()` in `includes/functions.php`), so no link is ever broken.

Placeholders in `[square brackets]` (team names, testimonial names) are waiting for real details.
