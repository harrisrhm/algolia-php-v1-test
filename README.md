# Algolia PHP v1.23.1 Quick Search Tester (Docker)

Minimal setup to test Algolia searches using:
- PHP via Docker (no local PHP required)
- Algolia PHP API Client pinned to **1.23.1**

## Prerequisites
- Docker

## Setup

1) Create an env file:

```bash
cp .env.algolia.example .env.algolia

Edit `.env.algolia` and set:
- `ALGOLIA_APP_ID`
- `ALGOLIA_API_KEY` (recommended: search-only key)
- `ALGOLIA_INDEX_NAME`

Install dependencies (creates `vendor/` locally):

```bash
docker run --rm -it -v "$PWD:/app" -w /app composer:2 install
Run a test search:

```bash
docker run --rm -it -v "$PWD:/app" -w /app --env-file "$PWD/.env.algolia" \
  php:7.4-cli php search.php "apple"

Output includes:
PHP version
Algolia client version
index, query, nbHits