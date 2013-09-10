*Installation*

 * Clone the repo `--recursive`ly

 * Set your webserver root to `www`

 * Run `./bootstrap.sh` (optionally set up daily cron on `./minion fetch`, because all the local data is stored in Kohana_Cache for 30 days)

 * Optionally you can run `./minion popstations`, to fetch the fallback index page stations based on the most popular ones from Yandex.Metrika (you will need the access token)

