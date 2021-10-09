<template>
    <Layout :title="page_title">
        <v-container grid-list-sm>
            <v-row>
                <v-col cols="12">
                    <v-card class="mt-3">
                        <v-toolbar dense>
                            <v-toolbar-title><v-icon>mdi-google-classroom</v-icon> Periode</v-toolbar-title>
                            <v-spacer></v-spacer>
                            <v-btn ripple   text @click.stop="newPeriode">
                                <v-icon>mdi-plus</v-icon>
                                <span class="hidden-sm-and-down">Tambah</span>
                            </v-btn>
                            <v-btn ripple text @click.stop="importPeriode = true">
                                <v-icon>mdi-import</v-icon>
                                <span class="hidden-sm-and-down">Impor</span>
                            </v-btn>
                            <span class="hidden-sm-and-down">
                            <v-btn   ripple  text @click.stop="print">
                                <v-icon>mdi-printer</v-icon>
                                    Cetak
                            </v-btn>
                            </span>
                            <v-btn   ripple text @click.stop="unduhPeriode">
                                <v-icon>mdi-file-excel-outline</v-icon>
                                <span class="hidden-sm-and-down">
                                    Unduh
                                </span>
                            </v-btn>
                        </v-toolbar>
                        <v-card-text>
                            <v-container>
                                <v-row>
                                    <v-col cols="3"></v-col>
                                    <v-col cols="3"></v-col>
                                    <v-col cols="3"></v-col>
                                    <v-col cols="3">
                                         <v-text-field v-model="search" prepend-inner-icon="mdi-magnify" label="Cari .." outlined dense hide-details max-width="100" clearable></v-text-field>
                                    </v-col>
                                </v-row>
                                <v-row>
                                    <v-col cols="12">
                                        <v-data-table :items="periodes" :headers="headers" :search="search" dense class="elevation-2">
                                            <template v-slot:item.options="{item}">
                                                <span>
                                                    <v-icon :color="item.active ? 'success' : 'error'" style="cursor:pointer" @click.stop="toggleActive(item)" :title="`${(item.active == true)?'Nonaktifkan '+item.deskripsi:'Aktifkan '+item.deskripsi}?`">{{ item.active ? 'mdi-check-circle' : 'mdi-close-circle'}}</v-icon>
                                                </span>
                                            </template>
                                        </v-data-table>
                                    </v-col>
                                </v-row>
                            </v-container>
                        </v-card-text>
                    </v-card>
                </v-col>
            </v-row>
            <v-row>
                <v-col cols="12">
                    <v-card class="mt-5">
                        <v-toolbar dense>
                            <v-toolbar-title><v-icon>mdi-chart-bar</v-icon>
                             Grafik Periode</v-toolbar-title>
                        </v-toolbar>
                        <v-card-text></v-card-text>
                    </v-card>
                </v-col>
            </v-row>
        </v-container>
        <modal-periode :dialog="modalPeriode" @hide="hideModalPeriode" @err="modalErr" @refresh="refresh"></modal-periode>
        <v-snackbar v-model="snackbar.show" :color="snackbar.color" multi-line top right>
            {{ snackbar.text }}
            <template v-slot:action="{ attrs }">
                <v-btn
                text
                dark
                v-bind="attrs"
                @click="snackbar = false"
                >
                <v-icon>mdi-close</v-icon>
                </v-btn>
            </template>
        </v-snackbar>
    </Layout>
</template>

<script>
import Layout from '../../../Layout/Dashboard'
import ModalPeriode from '../Modals/ModalPeriode'
export default {
    name: 'Periode',
    components: {Layout, ModalPeriode},
    props: { page: String, page_title: String },
    data: () => ({
        snackbar: {},
        headers: [
            {value: 'index', text: 'No', sortable: false},
            {value: 'kode_periode', text: 'Kode Periode'},
            {value: 'tapel', text: 'Tahun Pelajaran'},
            {value: 'semester', text: 'Semester'},
            {value: 'deskripsi', text: 'Deskripsi'},
            {value: 'options', text: 'Opsi'},
        ],
        search: '',
        modalPeriode: {
            show: false,
            mode: 'create'
        },
        rawperiodes: []
    }),
    methods: {
        refresh(msg) {
            this.snackbar = {show: true, color: 'success', text: msg}
            this.getPeriodes()
        },
        modalErr(val) {
            this.snackbar = val
        },
        newPeriode() {
            this.modalPeriode = {show: true}
        },
        hideModalPeriode(val) {
            this.modalPeriode = {show: false, mode: 'create'}
        },
        getPeriodes() {
            axios({
                method: 'post',
                url: '/admin/pengaturan/periode'
            }).then(response => {
                // console.log(response)
                this.rawperiodes = response.data.periode
            }).catch(err => {
                console.log(err.response.msg)
            })
            // this.rawperiodes = [
            //     {index: 1, kode_periode: '20211',tapel:'2020/2021', semester: 1, deskripsi: 'tes', active: false}
            // ]
        },
        toggleActive(item) {
            axios({
                method: 'post',
                url: '/admin/pengaturan/periode/'+item.id,
                data: {
                    _method: 'put',
                    active: !item.active
                }
            }).then(response => {
                this.getPeriodes()
                this.snackbar = { show: true, color: 'success', text: response.data.msg}
            }).catch(err => {
                // console.log(err.response)
                this.snackbar = { show: true, color: 'error', text: err.response.data.msg}
            })
        },
        importPeriode() {},
        print() {},
        unduhPeriode() {}
    },
    computed: {
        periodes() {
            let periodes = []
            this.rawperiodes.map((items, index) => {
                items.index = index+1
                periodes.push(items)
            })
            return periodes
        }
    },
    created() {
        this.getPeriodes()
    }
}
</script>
    