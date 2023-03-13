import '@mdi/font/css/materialdesignicons.css'
import Vue from 'vue';
import Vuetify from 'vuetify';
import { TiptapVuetifyPlugin } from 'tiptap-vuetify'

import 'tiptap-vuetify/dist/main.css'
import 'vuetify/dist/vuetify.min.css'

// import moment from './moment.js'
const vuetify = new Vuetify()
// moment.locale('id')
Vue.use(Vuetify);
Vue.use(TiptapVuetifyPlugin, {
    vuetify, iconsGroup: 'mdi'
})

var d = new Date()
var jam = d.getHours()
var dark =  (jam < 6 || jam > 18)

export default new Vuetify({
    icons: {
        iconfont: 'mdi'
    },
    theme: {
        // dark: dark,
        themes: {
            // light: {
            //     primary: '#102542',
            //     secondary: '#F9F9ED',
            //     accent: '#7D84B2',
            //     error: '#FD151B',
            //     warning: '#FFB30F',
            //     info: '#437F97',
            //     success: '#849324'
            // }
        }
    }
});
