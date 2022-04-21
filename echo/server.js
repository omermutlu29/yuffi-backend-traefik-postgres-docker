require('dotenv').config();

const env = process.env;
console.log(env.REDIS_HOST)
console.log(env.APP_URL)

require('laravel-echo-server').run({
    "authHost": "http://nginx:8000/",
    "authEndpoint": "broadcasting/auth",
    "database": "redis",
    "databaseConfig": {
        "redis": {
            "host": "redis",
            "port": "6379",
            "db": "0"
        }
    },
    "devMode": true,
    "host": null,
    "port": "6001",
    "protocol": "http",
    "socketio": {}
});