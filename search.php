<?php
require __DIR__ . '/vendor/autoload.php';

function envv(string $key): ?string {
  $v = getenv($key);
  if ($v !== false && $v !== '') return $v;
  if (isset($_ENV[$key]) && $_ENV[$key] !== '') return $_ENV[$key];
  return null;
}

function composerPackageVersion(string $name): ?string {
  $path = __DIR__ . '/vendor/composer/installed.json';
  if (!is_readable($path)) return null;

  $j = json_decode(file_get_contents($path), true);
  if (!is_array($j)) return null;

  // Composer v2 can store packages in ["packages"], or as a flat array depending on version/config
  $pkgs = $j['packages'] ?? $j;

  foreach ($pkgs as $p) {
    if (($p['name'] ?? '') === $name) return $p['version'] ?? null;
  }
  return null;
}

$appId     = envv('ALGOLIA_APP_ID');
$apiKey    = envv('ALGOLIA_API_KEY');
$indexName = envv('ALGOLIA_INDEX_NAME');

if (!$appId || !$apiKey || !$indexName) {
  fwrite(STDERR, "Missing env vars. Set ALGOLIA_APP_ID, ALGOLIA_API_KEY, ALGOLIA_INDEX_NAME\n");
  exit(1);
}

$phpVersion = PHP_VERSION;
$algoliaClientVersion = composerPackageVersion('algolia/algoliasearch-client-php') ?? '(unknown)';

$client = new \AlgoliaSearch\Client($appId, $apiKey);
$index  = $client->initIndex($indexName);

$query = $argv[1] ?? "apple";
$params = [
  'hitsPerPage' => 5,
];

$res = $index->search($query, $params);

echo "PHP: {$phpVersion}\n";
echo "Algolia PHP client: {$algoliaClientVersion}\n";
echo "index: {$indexName}\n";
echo "query: {$query}\n";
echo "nbHits: " . ($res['nbHits'] ?? 0) . "\n\n";

foreach (($res['hits'] ?? []) as $i => $hit) {
  $objectID = $hit['objectID'] ?? '(no objectID)';
  $label = $hit['name'] ?? $hit['title'] ?? $objectID;
  echo ($i + 1) . ". {$label} ({$objectID})\n";
}
