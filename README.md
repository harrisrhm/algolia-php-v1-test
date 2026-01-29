# Algolia PHP v1.23.1 Quick Search Tester (Docker)

Minimal setup to test Algolia searches using:
- PHP via Docker (no local PHP required)
- Algolia PHP API Client pinned to **1.23.1**

## Prerequisites
- Docker

## Setup

1) Create an env file:

```sh
cp .env.algolia.example .env.algolia
```

2) Edit `.env.algolia` and set:

```sh
`ALGOLIA_APP_ID`
`ALGOLIA_API_KEY` (recommended: search-only key)
`ALGOLIA_INDEX_NAME`
```

3) Install dependencies (creates `vendor/` locally):

```sh
docker run --rm -it -v "$PWD:/app" -w /app composer:2 install
```

4) Run a test search:

```sh
docker run --rm -it -v "$PWD:/app" -w /app --env-file "$PWD/.env.algolia" \
  php:7.4-cli php search.php "apple"
```

Output includes:
- PHP version
- Algolia PHP API Client version
- index name
- query
- nbHits