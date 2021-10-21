<template lang="html">
    <v-navigation-drawer app :permanent="$vuetify.breakpoint.mdAndUp" v-model="drawer" class="side" :mini-variant="isMini"  color="tertiary">
        <v-list-item primary class="side-brand" >
            <v-list-item-content>
                <v-list-item-title  class="title ">{{ $page.props.appName }}</v-list-item-title>
            </v-list-item-content>
        </v-list-item>
        <v-divider></v-divider>
        <v-list three-line>
            <v-list-item>
                <v-list-item-avatar>
                    <img :src="fotoAvatar"
                                    alt="User"
                                    @error="setDefaultAvatar">
                </v-list-item-avatar>
                <v-list-item-content>
                    <v-list-item-title class="text-wrap">
                        {{ $page.props.user.name ? $page.props.user.name : $page.props.user.nama }}
                    </v-list-item-title>
                    <v-list-item-subtitle>
                        {{ $page.props.user.role ? $page.props.user.role : 'siswa' }}
                    </v-list-item-subtitle>
                </v-list-item-content>
            </v-list-item>
            <v-divider horizontal></v-divider>
        </v-list>
        <v-list dense nav >
            
            <v-list-item-group  v-for="(menu,i) in menus" :key="i">
                <div v-if="child(menu)" class="inertia-link" >
                    <v-list-group  :prepend-icon="'mdi-'+menu.icon" :value="checkChild(menu)" active-class="aktif">  
                        <!-- v-show="(menu.role.includes($page.props.user.role) || menu.role.includes('all'))"> -->
                        <template v-slot:activator class="buka"> 
                            <v-list-item-title >{{menu.label}}</v-list-item-title>
                        </template>
                        <div v-for="(child, s) in menu.children" :key="s" >
                            <inertia-link :href="rute(child.url)" class="inertia-link" >
                                <v-list-item
                                    :class="(rute(child.url) == path ) ? 'active elevation-3' : ''"
                                    
                                    >
                                    <!-- v-show="(child.role.includes($page.props.user.role) || child.role.includes('all'))"
                                > -->
                                    <v-list-item-icon>
                                        <v-icon>mdi-{{child.icon}}</v-icon>
                                    </v-list-item-icon>
                                    <v-list-item-title>{{child.label}}</v-list-item-title>
                                </v-list-item>
                            </inertia-link>
                        </div>
                    </v-list-group>
                </div>
                <div v-else>
                <inertia-link :href="rute(menu.url)" class="inertia-link" >
                    <v-list-item
                        :class="(rute(menu.url) == path )? 'active elevation-3': ''"
                        
                        >
                        <!-- v-show="(menu.role.includes($page.props.user.role) || menu.role.includes('all'))"
                    > -->
                        <v-list-item-icon>
                            <v-icon>mdi-{{menu.icon}}</v-icon>
                        </v-list-item-icon>
                        <v-list-item-content>
                            <v-list-item-title>{{menu.label}}</v-list-item-title>
                        </v-list-item-content>
                    </v-list-item>
                    
                </inertia-link>
                </div>
            </v-list-item-group>
        </v-list>
        <v-list style="position: absolute; bottom: 0;">
            <v-list-item-group>
                <v-list-item class="text-center">
                    <v-list-item-title>Versi 2.0</v-list-item-title>
                </v-list-item>
            </v-list-item-group>
        </v-list>
    </v-navigation-drawer>
</template>

<script>
    export default {
        name: 'SideBar',
        props: {
            menus: Array,
            open: Boolean,
            mini: Boolean
        },
        data: () => ({
            // path: window.location.pathname,
            sidemenus: [],
            active: false,
            locpath: false
            // mini: false,
            // child: true
        }),
        methods: {
            child(menu) {
                // let c = Object.keys(menu).includes('children')
                let c = (menu.children.length > 0)
                return (c) ? true : false
            },
            checkChild(menu) {
                let children = menu.children
                let benar = false
                children.forEach(ch => {
                    if (this.path == this.rute(ch.url)) benar = true
                })

                return benar
            },
            menu() {
                let items = this.menus
                var role = this.$page.props.role
                items.forEach(item => {
                    // if (item.role.includes(role)) {
                    //     console.log('ok')
                    // } else {
                    //     console.log('ko')
                    // }
                })
            },
            tes(){
                var text = 'wali,gpai,gor,gbig'
                // alert(text.includes('ks'))
            },
            setDefaultAvatar(e) {
                var foto = (this.$page.props.user.jk == 'l') ? 'user_l.png' : 'user_p.png'
                e.target.src = '/storage/img/users/'+foto
                if(this.$page.props.page_title == 'Dashboard') {
                }
            },
            rute(url) {
                const roles = ['admin','wali','siswa']
                var rute = (roles.includes(this.$page.props.role)) ? '/'+this.$page.props.role+url : '/mapel'+url
                return rute
            },
            checkSubmenu(item) {

            }
        },
        computed: {
            drawer: {
                get() {
                    return this.open
                },
                set(state){
                    this.$emit('tutup', state)
                },

            }, 
            fotoAvatar() {
                if (this.$page.props.role == 'siswa') {
                    return '/storage/img/siswas/'+this.$page.props.user.nisn+'.jpg'
                } else {
                    return '/storage/img/users/'+this.$page.props.user.nip+'.jpg'
                }
                
            },
            isMini: {
                get() {
                    return this.mini
                },
                set(val) {
                    this.$emit('maxi', val)
                }
            },
            showMenus() {
                let items = this.menus
                var role = this.$page.props.role
                items.forEach(item => {
                    return item
                    // console.log(item)
                })
            },
            path() {
                return window.location.pathname
            },
            // rute() {
            //     const role = this.$page.props.user.role
            //     return (role == 'admin') ? 'admin' : (role =='wali') ? 'wali' : ( role == 'siswa' ) ? 'siswa' : 'mapel' 
            // }
        },
        created() {
            this.tes()
        }
    }
</script>

<style scoped>

    .active {
        color: red!important;
        /* background: rgba(255, 127, 80, 0.849)!important; */
        background: #fefefe!important;
    }
    .active .v-list-item__icon i,
    .active .v-list-item__title {
        color: #333!important;
    }
    .buka {
        background: pink;
    }

    .side-brand {
        /* background: #4c828a67; */
        box-shadow: 0 3px 5px rgba(0,0,0,0.5);
    }
    .side {
        /* background-image: url('/img/side.jpg'); */
        background-size: cover;
        border: none!important;
    }
    .v-list-item-group .v-list-item--active{
        background: black!important;
        color: red!important;
    }
</style>