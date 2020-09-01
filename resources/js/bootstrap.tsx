window._ = require('lodash')

/**
 * Logger
 */
let Logger = require('js-logger')
Logger.useDefaults();


window.API_URL = process.env.MIX_API_URL
window.APP_ENV = window.APP_ENV || process.env.MIX_APP_ENV // Allow to change APP_ENV without rebuild
let logsEnabled = !!(window.LOGS_ENABLED || process.env.MIX_LOGS_ENABLED || window.APP_ENV !== 'local')

if (!logsEnabled) {
    Logger.setLevel(Logger.OFF)
}

try {
    window.Popper = require('popper.js').default
    window.$ = window.jQuery = require('jquery')

    require('bootstrap')
} catch (e) {
}


Logger.info('APP_ENV', window.APP_ENV)

/**
 * We'll load the axios HTTP library which allows us to easily issue requests
 * to our Laravel back-end. This library automatically handles sending the
 * CSRF token as a header based on the value of the "XSRF" token cookie.
 */
let apiToken = $('meta[name="api-token"]').attr('content')

Logger.debug('token', apiToken)

window.axios = require('axios')

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest'

window.axios.defaults.headers.common['Authorization'] = `Bearer ${apiToken}`
window.axios.defaults.withCredentials = true

/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allows your team to easily build robust real-time web applications.
 */

// import Echo from 'laravel-echo';

// window.Pusher = require('pusher-js');

// window.Echo = new Echo({
//     broadcaster: 'pusher',
//     key: process.env.MIX_PUSHER_APP_KEY,
//     cluster: process.env.MIX_PUSHER_APP_CLUSTER,
//     forceTLS: true
// });

console.log('MIX_APP_ENV',process.env.MIX_APP_ENV)
