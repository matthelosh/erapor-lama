<template lang="html">
    <div id="app">
        <v-app>
            <div v-if="MainError.show" id="main-error">
                <v-alert style="width:500px" dark bordered-outline border="left" color="error">
                    <h1 class="text-center">{{ MainError.text }}</h1>
                </v-alert>
            </div>
            <v-app-bar app dense >
                <v-btn icon @click.stop="mini = !mini">
                    <v-icon>mdi-chevron-double-{{  (mini == false) ? 'left': 'right'}}</v-icon>
                </v-btn>
                <v-app-bar-title>
                    {{ title }}
                    |
                    {{ semester }}
                </v-app-bar-title>
                <v-spacer></v-spacer>
                <v-btn icon>
                    <v-icon>mdi-bell</v-icon>
                </v-btn>
                <v-menu
                    bottom
                    offset-y
                >
                    <template v-slot:activator="{ on, attrs }">
                        <v-btn 
                            v-bind="attrs"
                            v-on="on"
                            icon>
                            <v-avatar size="38">
                                <img
                                    :src="'/storage/img/'+(user.nip ? 'users' : 'siswas')+'/'+(user.nip ? user.nip : user.nisn)+'.jpg'"
                                    alt="John"
                                    @error="setDefaultAvatar"
                                />
                            </v-avatar>
                        </v-btn>
                    </template>
                    <v-list flat>
                        <v-list-item-group
                            color="primary"
                            dense
                        >
                            <v-list-item>
                                <v-list-item-title>{{user.name ? user.name : user.nama}}</v-list-item-title>
                            </v-list-item>
                            <inertia-link href="/dashboard/profile" class="inertia-link">
                                <v-list-item dense>
                                    
                                        <v-icon>mdi-badge-account-horizontal</v-icon>
                                        <v-list-item-title> Profil</v-list-item-title>
                                    
                                </v-list-item>
                            </inertia-link>
                            <v-list-item dense href="/logout">

                                    <v-icon color="red">mdi-logout</v-icon>
                                    <v-list-item-title class="red--text"> Keluar</v-list-item-title>
                            </v-list-item>
                        </v-list-item-group>
                        </v-list>
                </v-menu>
                <v-app-bar-nav-icon class="hidden-md-and-up" @click.stop="drawer= true"></v-app-bar-nav-icon>
            </v-app-bar>
            <sidebar :menus="menus" :open="drawer" @tutup="toggleDrawer" :mini="mini" @maxi="mini = !mini"></sidebar>
            <v-main style="background: #ccc;" color="light">
                <slot />
                
            </v-main>
            <v-snackbar v-model="$page.props.flash.message" multi-line top right color="error">
                {{ $page.props.flash.message }}
                <template v-slot:action="{attrs}">
                    <v-btn color="white" text v-bind="attrs" @click="$page.props.flash.message = false">
                        <v-icon>mdi-close</v-icon>
                    </v-btn>
                </template>
            </v-snackbar>
            <v-dialog v-model="dialog" max-width="400">
                <v-card>
                    <v-card-text>
                        <v-container>
                            <h3>Anda Belum mengunggah Foto Profil</h3>
                            Kunjungi Laman <inertia-link href="/dashboard/profile">Profil</inertia-link></v-container>
                    </v-card-text>
                </v-card>
            </v-dialog>
        </v-app>
    </div>
</template>

<script>
import Sidebar from './Sidebar'
export default {
    name: 'Layout',
    components: {Sidebar},
    props: {
        title: String,
        page: String
    },
    data: () =>({
        dialog: false,
        mini: false,
        menus:[],
        drawer: false,
        rute: route().current(),
        flash: {
            show: false,
            color: 'error',
            text: ''
        },
        MainError: { show: false, text: '' }
    }),
    methods: {
        toggleDrawer(state) {
           this.drawer = state
        },
        setDefaultAvatar(e) {
            let dir = ''
            if ((this.$page.props.user.role)) {
                dir = (this.$page.props.user.jk == 'l') ? '/storage/img/users/user_l.png' : '/storage/img/users/user_p.png'
               
            } else {
                 dir = (this.$page.props.user.jk == 'l') ? '/storage/img/siswas/siswa.png' : '/storage/img/siswas/siswa.png'
            }


            // var foto = (this.$page.props.user.jk == 'l') ? 'user_l.png' : 'user_p.png'
            // e.target.src = '/storage/img/users/'+foto
            e.target.src = dir
        }
    },
    computed: {
        user(){
            return this.$page.props.user
        },
        semester() {
            var periode = this.$page.props.periode_aktif
            switch (periode.semester) {
                case '1':
                    return periode.tapel + ' Semester Ganjil'
                    break;
                default:
                    return periode.tapel + ' Semester Genap'
                    break;
            }
        }
    },
    watch: {
        title: {
            immediate: true,
            handler(title) {
                document.title = title
            }
        },
    },
    created(){
        this.menus = this.$page.props.menus
        if((this.$page.props.user.role == 'wali' && this.$page.props.rombel == null) || (this.$page.props.user.role != 'wali' && this.$page.props.level == 'guru' && this.$page.props.mapel == null)) {
            this.MainError = { show: true, text: 'Data Rombel dan Mapel Semester ini belum disiapkan admin.' }
        }
    },
    mounted() {
    }
}
</script>

<style>
    
    .inertia-link {
        /* color: red; */
        text-decoration: none;
    }
    /* .active {
        background: pink;
    } */
    .pink {
        background: coral!important;
    }

    #main-error {
        position: fixed;
        top:  0;
        right:  0;
        bottom:  0;
        left:  0;
        background: #636363ef;
        color: white;
        z-index:  999999999999;
        display: flex;
        justify-content: center;
        align-items: center;
    }
</style>