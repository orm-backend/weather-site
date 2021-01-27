## Install

* `mkdir site; cd site`
* `git clone https://github.com/orm-backend/orm-backend/weather-site.git .`
* `composer install`
* `npm i`
* `cp .env.example .env`
* `php artisan key:generate`

## Run

* Edit .env to connect your Json-Rpc service by setting WEATHER_SERVICE_URL variable. Yhe default value is `http://127.0.0.1:8000/jsonrpc`
* Cache the configuration by running `php artisan config:cache`
* Build assets `npm run dev`
* Start your json-rpc service
* Perform testing `php artisan test`
* Start development server `php artisan serve`
