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
                                <v-row v-if="!$page.props.user.role">
                                    <v-col  cols="6" v-for=" (item,index) in siswasItem" class="d-flex justify-center align-center" :key="index">
                                        <v-btn class="text-center" rounded :color="item.color" @click="show(item.modal)">
                                            <v-icon>{{ item.icon }}</v-icon>
                                            <h4 class="text-center">{{ item.label }}</h4>
                                        </v-btn>
                                    </v-col>
                                </v-row>
                                <v-row v-else>
                                    <h3>Selamat Datang {{ ($page.props.user.jk == 'l') ? 'Bapak' : 'Ibu' }} {{ $page.props.user.name }}</h3>
                                    <p>{{$page.props.serverIp}}</p>
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
import Rapor from '../../Components/Modals/Siswa/ModalRapor'
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
        }
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
        }
    }
}
</script>

<style scoped>
    .text-center {
        text-align: center;
    }
</style>