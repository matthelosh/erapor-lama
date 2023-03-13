<template lang="html">
    <div id="app">
        <div v-if="MainError.show" id="main-error">
            <v-alert style="width:500px" dark bordered-outline border="left" color="error">
                <h1 class="text-center">{{ MainError.text }}</h1>
            </v-alert>
        </div>
        <v-app>
            <v-container class="fill-height bg-body fluid" >
                <v-row>
                    <v-col cols="12" class="d-sm-none d-flex justify-center">
                         <img
                        src="img/tutwuri.png"
                        height="100"
                        contain
                        />
                    </v-col>
                    <v-col cols="12" sm="8" class="d-none d-sm-flex">
                       <v-card width="100%">
                            <v-card-text >
                                <div>
                                    <h1 class="mb-10">SELAMAT DATANG !</h1>
                                    
                                   <v-list>
                                       <v-list-item><v-list-item-title>Terbaru</v-list-item-title></v-list-item>
                                       <v-list-item
                                            v-for="baru in news" 
                                            :key="baru.id"
                                       >
                                            <v-list-item-title>{{baru.title}}</v-list-item-title>
                                       </v-list-item>
                                    </v-list>
                                </div>
                            </v-card-text>
                       </v-card>
                    </v-col>
                    <v-col cols="12" sm="4">
                        <v-card  class="mx-auto ">
                            <v-img 
                                src="img/upacara.jpg"
                                max-height="100"
                                class="white--text align-end"
                                gradient="to bottom left, #78967822, #5667fedd"
                            >
                               
                                <v-card-title>Login</v-card-title>
                                <v-card-subtitle class="white--text">{{ $page.props.sekolah ? $page.props.sekolah.nama_sekolah : 'Data Sekolah Kosong' }}</v-card-subtitle>
                                 <div class="media"></div>
                            </v-img>
                            
                            <v-card-text>
                                <v-form @submit="login">
                                    <v-text-field
                                        solo
                                        label="Username / NISN"
                                        prepend-inner-icon="mdi-qrcode"
                                        v-model="auth.username"
                                        :error="salah"
                                        required
                                    />
                                    <v-text-field 
                                        solo
                                        label="Sandi/ThBlnTgl(20100721)"
                                        :type="password"
                                        prepend-inner-icon="mdi-key"
                                        v-model="auth.password"
                                        :error="salah"
                                        
                                        required
                                    />

                                    <v-checkbox
                                        @change="togglePassword"
                                        label="Lihat Password"
                                        />
                                    <v-row class="d-flex justify-center">
                                        <v-btn
                                            color="primary"
                                            type="submit"
                                            class="mx-auto"
                                            :loading="loading"
                                            :disabled="loading"
                                        >Login</v-btn>
                                    </v-row>
                                </v-form>
                            </v-card-text>
                        </v-card>
                    </v-col>
                </v-row>
            </v-container>
            <v-snackbar
                v-model="snackbar"
                color="error"
                bottom
                vertical
                right
                multi-line
            >
                {{errMsg}}
                <template v-slot:action="{ attrs }">
                    <v-btn
                    color="white"
                    text
                    v-bind="attrs"
                    @click="snackbar = false"
                    >
                    Tutup
                    <v-icon>mdi-close</v-icon>
                    </v-btn>
                </template>
            </v-snackbar>
        </v-app>
    </div>
</template>

<script>
// var token = document.head.querySelector('meta[name="csrf-token"]');
// window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
export default {
    name: 'Login',
    props: {news: Array},
    data: () => ({
        auth: {

        },
        salah: false,
        errMsg: '',
        password: 'password',
        snackbar: false,
        loading: false,
        MainError: { show: false }
    }),
    methods: {
        login(e){
            e.preventDefault()
            // console.log(this.auth)
            this.loading = true
            axios({
                method: 'post',
                url: '/auth/login',
                data: this.auth
            }).then(response => {
                // alert(response.data.role)
                // console.log(response.data)const 
                const roles = ['admin','wali', 'siswa']
                const rute = roles.includes(response.data.role) ? response.data.role : 'mapel'
                window.location.href = '/'+ rute
            }).catch(err => {
                console.log(err)
                if ( err.response.data.status === 403) {
                    let old = err.response.data.old
                    Object.assign(this.auth, old)
                    this.errMsg = err.response.data.msg
                } else if(err.response.status === 500 && (err.response.data.msg.search('Base table or view not found') > -1)) {
                    this.errMsg = 'Ada kesalahan pada basis data. Silahkan hubungi admin.!'
                } else {
                    this.errMsg = err.response.data.msg
                }
                
                this.salah = true
                this.snackbar = true
                
                this.loading = false
            })
        },
        togglePassword(val){
            if ( val === true ) {
                this.password = 'text'
            } else {
                this.password = 'password'
            }
        }
    },
    computed: {
        
    },
    created() {
        document.title = "Login"

       var notable = 0
       Object.values(this.$page.props).forEach((v,k) => {
            if (v == 'No Table') notable += 1
       })
      if (notable > 0) this.MainError = { show: true, text: 'Ada Kesalahan Basis Data. Hubungi Admin' }
    }
}
</script>

<style>
    #app {
        
        background-color: #8EC5FC;
        background-image: linear-gradient( 64.5deg,  rgba(245,116,185,1) 14.7%, rgba(89,97,223,1) 88.7% );

    }
    .media {
        position: absolute;
        width:  100%;
        height: 100%;
        content:  '';
        background: teal;
    }
    #app > div > div.container.fill-height.bg-body.fluid > div.row.d-sm-none > div > div.v-responsive__sizer {
        padding-bottom:  0!important;
        height: 0!important;
        display: none;
    }
    #main-error {
        position: fixed;
        top:  0;
        right:  0;
        bottom:  0;
        left:  0;
        height: 100vh;
        background: #646464ef;
        z-index: 999999;
        display: flex;
        justify-content: center;
        align-items: center;
    }
</style>