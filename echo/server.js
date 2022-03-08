require('dotenv').config();

const env = process.env;
console.log(env.REDIS_HOST)
console.log(env.APP_URL)

require('laravel-echo-server').run({
    authHost: env.APP_URL,
    devMode: env.APP_DEBUG,
    database: "redis",
    databaseConfig: {
        redis: {
            host: env.REDIS_HOST,
            port: env.REDIS_PORT,
        }
    }
});