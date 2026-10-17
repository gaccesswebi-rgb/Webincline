<?php
/** /sitemap.xml: every built page from includes/routes.php, for search engines. */

declare(strict_types=1);

require_once __DIR__ . '/../includes/bootstrap.php';

header('Content-Type: application/xml; charset=utf-8');
echo '<?xml version="1.0" encoding="UTF-8"?>', "\n";
?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
<?php foreach (array_keys(ROUTES) as $path): if ($path === '/sitemap.xml') continue; ?>
  <url><loc><?= e(SITE_URL . $path) ?></loc></url>
<?php endforeach; ?>
</urlset>
