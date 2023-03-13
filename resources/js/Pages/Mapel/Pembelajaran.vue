<template>
    <Layout :title="page_title">
        <v-overlay :value="overlay">
          <v-progress-circular
            indeterminate
            size="64"
          ></v-progress-circular>
        </v-overlay>
        <v-container>
            <v-row >
                <v-col cols="12" class="d-flex justify-center">
                    <h3 color="primary">ADMINISTRASI PEMBELAJARAN</h3>
                </v-col>
                
            </v-row>
            <v-row>
                <v-col cols="4">
                    <inertia-link as="v-btn" href="/mapel/pembelajaran/ahe" block large>Analisis Hari Efektif</inertia-link>
                </v-col>
                <v-col cols="4">
                    <v-btn block large>Prota</v-btn>
                </v-col>
                <v-col cols="4">
                    <v-btn block large>Promes</v-btn>
                </v-col>
                <v-col cols="4">
                    <v-btn block large>KKM</v-btn>
                </v-col>
                <v-col cols="4">
                    <v-btn block large>Silabus</v-btn>
                </v-col>
                <v-col cols="4">
                    <v-btn block large>RPP</v-btn>
                </v-col>
                <v-col cols="4">
                    <v-btn block large>Buku Guru & Siswa</v-btn>
                </v-col>
                <v-col cols="4">
                    <v-btn block large>Pemetaan KI - KD</v-btn>
                </v-col>
                <v-col cols="4">
                    <v-btn block large>Jurnal Pembelajaran</v-btn>
                </v-col>
                <v-col cols="4">
                    <v-btn block large>Jurnal Pengembangan Sikap</v-btn>
                </v-col>
                
            </v-row>
        </v-container>
    </Layout>
</template>
 
<script>
import Layout from '../../Layout/Dashboard'
export default {
    name: 'Pembelajaran',
    components: { Layout,},
    props: {
        page_title: String,
        page: String
    },
    data: () => ({
        items: [],
        dialog: {show: false},
        rpp: { show: false },
        jurnal: { show: false },
        cetakjurnal: { show: false },
        overlay: false
    }),
    methods: {
    },
    computed: {
        dark() {
            return this.$vuetify.theme.dark
        },
    },
    mounted() {
    }
}
</script>
