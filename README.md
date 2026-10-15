# Webincline website

New website for Webincline, built with plain PHP, CSS and GSAP.

## Run locally

```sh
php -S 127.0.0.1:8080
```

Then open http://127.0.0.1:8080.

## Where things live

| What | File |
|---|---|
| Contact details, stats, markets, social links | `includes/config.php` |
| Services, case studies, clients, process, FAQ, team, blog cards | `includes/data.php` |
| Homepage sections | `index.php` |
| Head, navigation, footer | `includes/header.php`, `includes/footer.php` |
| Contact form handler (emails `LEADS_EMAIL`, keeps a copy in `storage/leads.csv`) | `contact-submit.php` |
| Styles | `assets/css/main.css` |
| Animations (particles, plane flight, scroll reveals) | `assets/js/main.js` |

Placeholders in `[square brackets]` (team names, testimonial names) are waiting for real details.
