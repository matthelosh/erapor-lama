<template>
    <Layout :title="page_title">
        <v-container>
            <v-row>
                <v-col>
                    <v-card>
                        <v-card-title class="pa-0">
                            <v-toolbar dense>
                                <v-icon>mdi-card</v-icon>
                                Kartu Ujian
                                <v-spacer class="my-1"></v-spacer>
                                <span  v-if="rombel != null"> 
                                    <v-btn rounded class="mx-3" color="info" @click="rombel = null"><v-icon>mdi-format-list-text</v-icon> Pilih Rombel</v-btn>
                                    <v-btn rounded class="mx-3" color="info" @click="cetak" ><v-icon>mdi-printer</v-icon> Cetak</v-btn>
                                </span>
                                <span v-else>
                                    <v-select dense hide-details :items="['PENILAIAN HARIAN', 'PENILAIAN TENGAH SEMESTER', 'PENILAIAN AKHIR SEMESTER', 'UJIAN SEKOLAH', 'UJIAN PRAKTEK', 'TRY OUT']" v-model="ujian" label="Jenis Penilaian" solo></v-select>
                                </span>
                            </v-toolbar>
                        </v-card-title>
                        <v-card-text>
                            <span v-if="rombel != null" id="print">
                               <v-sheet v-for="(page,i) in pages" :key="i" class="my-2 page" color="#efefef" :style="(i+1) == pages.length ? 'page-break-after:avoid;' : 'page-break-after:always;'">
                                   <div v-for="(siswa,s) in page" :key="s" style="width:46%;display:inline-block;" class="kartu">
                                       <div class="header" style="position:relative;border-bottom: 5px double black;padding: 10px;">
                                           <img src="/img/malangkab.png" alt="Logo" width="50" style="position:absolute;" class="logo">
                                           <h4 class="my-0 pa-0" style="line-height:1.3em;text-align:center;">
                                                    KARTU PESERTA {{ujian}} <br>
                                                    SD NEGERI 1 BEDALISODO <br>
                                                    TAHUN PELAJARAN {{ $page.props.periode_aktif.tapel}} SEM {{$page.props.periode_aktif.semester == '1' ? 'GANJIL' : 'GENAP'}}</h4>
                                       </div>
                                       <div class="data-siswa" style="padding: 0 10px;">
                                            <h4 class="text-center" style="margin: 10px auto;">DATA PESERTA</h4>
                                                <table width="100%">
                                                    <tr>
                                                        <td style="width: 30%">No. Peserta</td>
                                                        <td class="mx-2">:</td>
                                                        <td class="pl-2">{{ siswa.nisn }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Nama</td>
                                                        <td class="mx-2">:</td>
                                                        <td class="pl-2" style="vertical-align:top;">{{ siswa.nama }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Tempat, Tgl Lahir</td>
                                                        <td class="mx-2">:</td>
                                                        <td class="pl-2">{{ siswa.tempat_lahir }}, {{siswa.tanggal_lahir}}</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Kelas</td>
                                                        <td class="mx-2">:</td>
                                                        <td class="pl-2" v-if="rombel">{{ rombel[0].label }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Sekolah Asal</td>
                                                        <td class="mx-2">:</td>
                                                        <td class="pl-2">{{ $page.props.sekolah.nama_sekolah }}</td>
                                                    </tr>
                                                </table>
                                       </div>
                                       <br>
                                       <div class="ttd">
                                            <v-col style="margin-left:50%;font-size: 0.9em;position:relative;">
                                                Kepala Sekolah
                                                <img :src="'/img/ttd/'+$page.props.sekolah.ks.nip+'.png'" alt="ttd" style="position:absolute;bottom: 30px;left: 0;" width="150">
                                                <br>
                                                <br>
                                                <br>
                                                <b><u>{{$page.props.sekolah.ks.name}}</u></b><br>
                                                NIP. {{$page.props.sekolah.ks.nip}}
                                            </v-col>
                                       </div>
                                   </div>
                               </v-sheet>
                            </span>
                            <span v-else>
                                <v-container>
                                    <v-row>
                                        <v-col>
                                            <h2>Pilih Rombel</h2>
                                        </v-col>
                                    </v-row>
                                    <v-row  v-if="loading">
                                        <v-col cols="12" class="d-flex justify-center">
                                            <span class="text-center">
                                                <v-progress-circular color="purple" indeterminate :size="50" :width="7"></v-progress-circular>
                                                <h3 class="text-center">Data Rombel sedang Diambil...</h3>
                                            </span>
                                        </v-col>
                                    </v-row>
                                    <v-row v-else>
                                        <v-col cols="2" v-for="rombel in rombels" :key="rombel.id">
                                            <v-btn block color="primary" @click="selectRombel(rombel.kode_rombel)">{{rombel.label}}</v-btn>
                                        </v-col>
                                    </v-row>
                                </v-container>
                            </span>
                        </v-card-text>
                    </v-card>
                </v-col>
            </v-row>
        </v-container>
    </Layout>
</template>

<script>
import Layout from '../../Layout/Dashboard'
export default {
    name: 'Kartuji',
    components: {
        Layout
    },
    props: {
        user: Object,
        page_title: String,
        menus: Array,
        datas: Array
    },
    data: () => ({
        rombels: [],
        rombel: null,
        loading: false,
        ujian: '',
    }),
    methods: {
        cetak(){
            let sheet = document.querySelector('#print').outerHTML
            let style = "//localhost:8080/css/app.css"
            let html =`
                <!doctype html>
                <html>
                    <head>
                        <title>Ceak Kartu Ujian</title>
                        <style>
                            .kartu {
                                margin: 10px;
                                border: 2px solid black;
                            }
                            table td {
                                font-size: 0.9em;
                            }
                            .text-center {
                                text-align: center;
                            }
                            @media print {
                                @page {
                                    size: 8.5in 13in;
                                }
                                h4 {
                                    font-size: .9em;
                                    margin-left: 10px;
                                    margin-top: 0;
                                    margin-bottom: 0;
                                }
                            }
                        </style>
                    </head>
                    <body>
                        ${sheet}
                    </body>
                </html>
            `
            let win = window.open('','','_blank')
            win.document.write(html)
            win.focus()
            win.print()
        },
        getRombels() {
            this.loading = true
            axios({
                method: 'post',
                url: '/admin/rombel?periode='+this.$page.props.periode_aktif.kode_periode
            }).then(res => {
                this.rombels = res.data.rombels
                this.loading = false
            })
        },
        selectRombel(e){
            this.rombel = this.rombels.filter(item => {
                return item.kode_rombel = e
            })
        }
    },
    computed: {
        pages() {
            if(this.rombel) {
                let pages = _.chunk(this.rombel[0].siswas, 6)
                return pages
            } else {
                return 'Rombel Belum dipilih'
            }
            
        }
    },
    mounted() {
        this.getRombels()
    }
}
</script>
<style scoped>
    .kartu {
        border: 1px solid black;
        margin: 10px;
        padding: 10px;
        box-sizing: border-box;
    }
</style>