## Installation Instructions

#### Install dependencies

```
composer install
```

##### Environment file

```
cp .env.example .env
```

#### Start docker
```
./vendor/bin/sail up
```

#### Generate application key
```
./vendor/bin/sail artisan key:generate
```

#### Migrate and seed database
```
./vendor/bin/sail artisan migrate --seed
```


### Get all bookings
```
curl --location 'http://localhost:88/api/v1/bookings'
```

### Create a booking
```
curl --location 'http://localhost:88/api/v1/bookings' \
--header 'Accept: application/json' \
--header 'Content-Type: application/json' \
--data '{
    "client_id": 1,
    "product_id": 1,
    "booked_on": "2023-05-02"
}'
```
