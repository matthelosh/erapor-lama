<template>
    <Layout :title="page_title">
        <v-container>
            <v-row>
                <v-col cols="12">
                    <v-card>
                        <v-toolbar dense>
                            <v-icon>mdi-dots-vertical</v-icon>
                            <v-toolbar-title>{{ $page.props.periode_aktif.tapel+' Semester '+$page.props.periode_aktif.semester }}</v-toolbar-title>
                        </v-toolbar>
                        <v-card-text>
                            <v-container>
                                <v-row>
                                    <v-col cols="12">
                                        <h3 class="text-center">Selamat Datang Ananda <br>{{ $page.props.user.nama }}</h3>
                                    </v-col>
                                    <v-col cols="12">
                                        <v-btn block color="info" href="/siswa/profil"><v-icon>mdi-card-account-details-outline</v-icon> Profil</v-btn>
                                    </v-col>
                                    <v-col cols="12">
                                        <v-btn block color="primary" href="/siswa/fisik"><v-icon>mdi-human</v-icon> Fisik</v-btn>
                                    </v-col>
                                    <v-col cols="12">
                                        <v-btn block color="success" href="/siswa/ortu"><v-icon>mdi-human-male-female</v-icon> Ortu</v-btn>
                                    </v-col>
                                    <v-col cols="12">
                                        <v-btn block color="warning" href="/siswa/akademik"><v-icon>mdi-school</v-icon> Akademik</v-btn>
                                    </v-col>
                                    
                                </v-row>
                                
                            </v-container>
                        </v-card-text>
                    </v-card>
                </v-col>
            </v-row>
        </v-container>
        <rapor v-if="raporku.show" :dialog="raporku" @hide="raporku.show = false"></rapor>
    </Layout>
</template>

<script>
import Layout from '../../Layout/Dashboard'
import Rapor from './Components/Modals/ModalRapor'
export default {
    components: {
        Layout, Rapor
    },
    props: {
        page_title: String
    },
    data : () => ({
        siswasItem : [
            { label: 'Nilai', icon: 'mdi-format-columns', color: 'primary', modal: 'nilai' },
            { label: 'Rapor', icon: 'mdi-file-pdf', color: 'info', modal: 'rapor'}
        ],
        raporku: {
            show: false
        },
        periodes: [],
        periode: null
    }),
    computed: {
        user() {
            return this.$page.props.user
        }
    },
    methods: {
        show(modal) {
            switch(modal) {
                case "rapor":
                    this.raporku = { show: true, siswa: this.$page.props.user }
                    break;
                case "nilai":
                    break;
            }
        },
        initialize() {
            axios({
                method: 'post',
                url: '/siswa/periode'
            }).then(res => {
                res.data.periode.forEach(item => {
                    this.periodes.push( {value:item.kode_periode, text: item.deskripsi} )
                })
            }).catch ( err => {
                console.log(err.response)
            })
        },
    },
    mounted() {
        this.periode = this.$page.props.periode
        this.initialize()
    }
}
</script>

<style scoped>
    .text-center {
        text-align: center;
    }
</style>