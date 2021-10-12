<template lang="html">
    <Layout :title="page_title">
        <v-container grid-list-sm>
            <v-row>
                <v-col cols="12">
                    <v-card class="mt-5">
                        <v-toolbar dense>
                            <v-toolbar-title><v-icon>mdi-google-classroom</v-icon> Rombel</v-toolbar-title>
                            <v-spacer></v-spacer>
                            <v-btn ripple   rounded color="primary" class="mx-1" @click.stop="newRombel" rounded>
                                <v-icon>mdi-plus</v-icon>
                                <span class="hidden-sm-and-down">Tambah</span>
                            </v-btn>
                            <!-- <v-btn ripple rounded color="info" class="mx-1" @click.stop="importRombel = true"  rounded>
                                <v-icon>mdi-import</v-icon>
                                <span class="hidden-sm-and-down">Impor</span>
                            </v-btn>
                            <span class="hidden-sm-and-down"> -->
                            <v-btn   ripple  rounded color="warning" class="mx-1" @click.stop="print"  rounded>
                                <v-icon>mdi-printer</v-icon>
                                    Cetak
                            </v-btn>
                            </span>
                            <!-- <v-btn   ripple rounded color="success" class="mx-1" @click.stop="unduhRombel"  rounded>
                                <v-icon>mdi-file-excel-outline</v-icon>
                                <span class="hidden-sm-and-down">
                                    Unduh
                                </span>
                            </v-btn> -->
                        </v-toolbar>
                        <v-card-text>
                            <v-container>
                                <v-row>
                                    <v-col cols="4">
                                        <!-- <v-btn @click="fromPrevTapel" color="info" rounded>Data dari Tahun sebelumnya</v-btn> -->
                                    </v-col>
                                   <!--  <v-col cols="4" v-else>
                                        <v-btn @click="fromPrevSemester" color="info" rounded>Data dari Semester sebelumnya</v-btn>
                                    </v-col> -->
                                    
                                    <v-col cols="5">
                                        <v-select v-model="periode" :items="periodes" rounded item-key="value" item-text="text" outlined label="Periode" dense hide-details @change="initialize"></v-select>
                                    </v-col>
                                    <v-col cols="3">
                                         <v-text-field v-model="search" rounded prepend-inner-icon="mdi-magnify" label="Cari .." outlined dense hide-details max-width="100" clearable></v-text-field>
                                    </v-col>
                                </v-row>
                                <v-row>
                                    <v-col cols="12">
                                        <v-data-table :items="rombels" :headers="headers" :search="search" dense class="elevation-5">
                                            <template v-slot:item.jmlSiswa="{item}">
                                                {{ lk(item) }}, {{pr(item)}}, {{ item.siswas.length }}
                                            </template>
                                            <template v-slot:item.options="{item}"> 
                                                <span>
                                                    <v-icon small class="mr-2 info--text" @click.stop="manage(item)" :title="`Atur Rombel ${item.label}`">mdi-equalizer</v-icon>
                                                    <v-icon small class="mr-2 warning--text" :title="`Edit Rombel ${item.label}`" @click.stop="editRombel(item)">mdi-pencil</v-icon>
                                                    <v-icon :title="`Hapus Rombel ${item.label}`" small class="mr-2 error--text" @click.stop="deleteRombel(item)">mdi-eraser</v-icon>
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
                    <v-card class="mt-3">
                        <v-toolbar dense>
                            <v-toolbar-title><v-icon>mdi-chart-bar</v-icon>
                             Grafik Rombel</v-toolbar-title>
                        </v-toolbar>
                        <v-card-text>
                            <v-container>
                                <v-row v-if="rawrombels.length > 0">
                                    <v-col cols="4" v-for="(item, index) in rawrombels" :key="index" class="d-flex justify-center">
                                        <div>
                                            <h4>Kelas {{item.label}}</h4>
                                            <div :id="'chart'+index" :key="index">{{index}}</div>
                                        </div>
                                    </v-col>
                                </v-row>
                            </v-container>
                            
                        </v-card-text>
                    </v-card>
                </v-col>
            </v-row>
        </v-container>
        <div v-if="modalRombel.show">
            <modal-rombel :dialog="modalRombel" @hide="hideModalRombel" @submitted="submitted"></modal-rombel>
        </div>
        <div v-if="manajemenrombel.show">
            <manajemen-rombel :dialog="manajemenrombel" @hide="manajemenrombel.show = false; initialize()" ></manajemen-rombel>
        </div>
        <v-snackbar v-model="snackbar.show" :color="snackbar.color" top right multi-line>{{ snackbar.text }}</v-snackbar>
        <confirm-dialog ref="confirm"></confirm-dialog>
    </Layout>
</template>

<script>
import Layout from '../../Layout/Dashboard'
import ModalRombel from './Modals/ModalRombel'
import ManajemenRombel from './Modals/ManajemenRombel.vue'
import ConfirmDialog from '../../Components/Modals/ConfirmDialog'
import ApexCharts from 'apexcharts'
import _ from 'lodash'
export default {
    name: 'Rombel',
    components: {Layout, ModalRombel, ManajemenRombel, ConfirmDialog},
    props: { page: String, page_title: String },
    data: () => ({
        snackbar: {},
        headers: [
            {value: 'index', text: 'No', sortable: false},
            {value: 'kode_rombel', text: 'Kode Rombel'},
            {value: 'label', text: 'Label'},
            {value: 'jmlSiswa', text: 'Jml Siswa'},
            {value: 'walis.name', text: 'Wali Kelas' },
            {value: 'options', text: 'Opsi'}
        ],
        search: '',
        modalRombel: {
            show: false,
            mode: 'create'
        },
        manajemenrombel: {show:false},
        rawrombels: [],
        periodes: [], 
        periode: null
        
    }),
    methods: {
        async fromPrevSemester(){
            this.$refs.confirm.open("Konfirmasi", "Yakin ingin menyalin data siswa per rombel dari semester sebelumnya?")
                .then(lanjut => {
                    if ( lanjut ) {
                        axios({
                            method: 'post',
                            url: '/admin/rombel/duplikat?periode='+this.periode,
                            data: { rombels: this.rawrombels }
                        }).then(response => {
                            this.snackbar = { show: true, color: 'success', text: response.data.msg}
                        }).catch(err=>{
                            this.snackbar = { show: true, color: 'error', text: err.response.data.msg }
                        })
                    }
                }).catch( err => {
                    console.log(err)
                })
            
        },
        lk(item) {
            let lk = item.siswas.filter(l => l.jk == 'l')
            return lk.length
        },
        pr(item) {
            let pr = item.siswas.filter(p => p.jk == 'p')
            return pr.length
        },
        submitted( ) {
            // alert('hi')
            this.initialize()
        },
        newRombel() {
            this.modalRombel = {show: true, mode: 'create'}
        },
        hideModalRombel(val) {
            this.modalRombel = {show: false, mode: 'create'}
        },
        editRombel(item) {
            this.modalRombel = { show: true, mode: 'edit', rombel: item}
        },
        async deleteRombel(rombel) {
            
            this.$refs.confirm.open("Hapus Data", "Yakin menghapus data Rombel "+rombel.label+"?")
                .then((lanjut)=>{
                    if (lanjut) {
                        axios({
                            method: 'delete',
                            url: '/admin/rombel/'+rombel.id,
                        }).then(response => {
                            this.snackbar = { show: true, color: 'success', text: response.data.msg }
                            console.log(response)
                            this.initialize()
                        }).catch(err => {
                            this.snackbar = { show: true, color: 'error', text: err.response.data.msg }
                        })
                    }
                }).catch( err => {
                    console.log(err)
                })
        },
        manage(item) {
            // alert('hi')
            this.manajemenrombel = { show:true, rombel: item }
        },
        importRombel() {},
        print() {},
        unduhRombel() {},
        initialize() {
            axios({
                method: 'post',
                url: '/admin/rombel?periode='+ this.periode
            }).then( response => {
                var rombels = [] 
                response.data.rombels.forEach((item, index) => {
                    item.index = index
                    rombels.push( item )
                })
                this.rawrombels = rombels
                // this.grafik(rombels)
                let c = document.querySelector(`#chart2`)
                console.log(c.innerHTML)
            }).catch( err => {
                console.log( err )
            })

            axios({
                method: 'post',
                url: '/admin/pengaturan/periode'
            }).then(res => {
                res.data.periode.forEach(item => {
                    this.periodes.push( {value:item.kode_periode, text: item.deskripsi} )
                })

            }).catch ( err => {
                console.log(err.response)
            })
            
        },
        grafik() {
            
            const rombels = this.rawrombels
            // console.log(rombels)
            var i = 0
            rombels.forEach((rombel,index) => {
                let jks = [0,0]
                let labeljk = ['Laki-laki', 'Perempuan']
                rombel.siswas.forEach( siswa => {
                    if ( siswa.jk == 'l' ) {
                        jks[0] += 1 
                    } else {
                        jks[1] += 1
                    }
                })
                let c = document.querySelector(`#chart${i}`)
                // console.log(c.innerHTML)
                
                // var chart = new ApexCharts(document.getElementById("chart"+i), {
                    
                //         chart: {
                //             type: 'pie'
                //         },
                //         series: jks,
                //         labels: labeljk
                //     })
                // chart.render()
                i++
            })
            // console.log(rombels)
            // var options = {
            //   chart: {
            //     type: 'line'
            //   },
            //   series: [{
            //     name: 'sales',
            //     data: [30,40,35,50,49,60,70,91,125]
            //   }],
            //   xaxis: {
            //     categories: [1991,1992,1993,1994,1995,1996,1997, 1998,1999]
            //   }
            // }

             //var chart = new ApexCharts(document.getElementById('chartrombel'), options);

             //chart.render();
        }
    },
    computed: {
        rombels() {
            var rombels = []
            this.rawrombels.forEach( (item, index) => {
                item.index = index+1
                rombels.push( item )
            })

            return rombels
        },
        
    },
    created() {
        //alert('hi')
        // 
        this.periode = this.$page.props.periode
        
        
    },
    mounted() {
        this.initialize()
        this.$nextTick( function () {
            // console.log('loaded')
            this.grafik()
        });
    }
}
</script>
    