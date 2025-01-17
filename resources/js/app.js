require('./bootstrap');

import { App, plugin } from '@inertiajs/inertia-vue'
import { InertiaProgress } from '@inertiajs/progress'
InertiaProgress.init()
import Vue from 'vue'
import vuetify from './Plugins/vuetify.js'
import axios from 'axios'
Vue.prototype.$route = (...args) => route(...args).url()  // Tambahkan Baris Ini 
// Vue.use(InertiaApp)
Vue.use(plugin)
var token = document.head.querySelector('meta[name="csrf-token"]');
window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;

axios.interceptors.response.use(function(response) {
    return response
}, function( error ) {
    if ( error.response.status === 419 || error.response.message === 'CSRF token mismatch.' || error.response.status === 401 || error.response.message === 'Unauthenticated.') {
        // alert('Sesi Habis')
         // window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
        return window.location.href = '/'
        // window.location.reload()
        //
    }

    return Promise.reject(error)
})
// GDrive APi
// const { google } = require('googleapis')
// const scopes = ['https://www.googleapis.com/auth/drive']

// const auth = new google.auth.JWT(
//     credentials.client_email,null,
//     credentials.private_key, scopes
// )

// const gdrive = google.drive({ version: "v3", auth})

// drive.files.list({}, (err, res) => {
//     if ( err ) throw err;
//     const files = res.data.files;
//     if (file.length) {
//         files.map((file) => {
//             console.log(file)
//         })
//     } else {
//         console.log('no Files Found')
//     }
// })

const el = document.getElementById('app')

new Vue({
    vuetify,
    // gdrive,
    render: h => h(App, {
        props: {
            initialPage: JSON.parse(el.dataset.page),
            resolveComponent: name => require(`./Pages/${name}`).default,
        },
    }),
}).$mount(el)
