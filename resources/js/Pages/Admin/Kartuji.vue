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
                                <v-select dense hide-details label="Rombel" outlined rounded
                                    :items="rombels"
                                    item-text="label"
                                    item-value="kode_rombel"
                                    @change="selectRombel"
                                ></v-select>
                                <v-btn rounded class="mx-3" color="primary" @click="cetak"><v-icon>mdi-printer</v-icon></v-btn>
                            </v-toolbar>
                        </v-card-title>
                        <v-card-text>
                            <!-- <v-sheet v-if="rombel" class="my-5" ref="sheet" id="kartu">
                                <div class="mainRow"
                                    style="display:flex;flex-wrap:wrap;"
                                >
                                    <span class="kartu"
                                        style="width:47%; flex-grow:50%;margin: 5px 10px;box-sizing:border-box;" 
                                        v-for="(siswa,i) in rombel[0].siswas"
                                        :key="i"
                                        d-print-flex
                                    >
                                        <v-card style="border: 1px solid black;padding:10px;">
                                            <v-card-title class="text-center">
                                                <v-row style="position:relative;display:flex; justify-content:start;">
                                                    <img src="/img/malangkab.png" width="50" style="position:absolute;" class="logo" />
                                                    <div class="text-center" style="margin-left: 20px;flex-grow:1;">
                                                        <h4 class="my-0 pa-0" style="line-height:1em;">
                                                            KARTU PESERTA UJIAN <br>
                                                            SD NEGERI 1 BEDALISODO <br>
                                                            TAHUN PELAJARAN {{ $page.props.periode_aktif.tapel}}</h4>
                                                    </div>
                                                </v-row>
                                            </v-card-title>
                                            <v-card-text>
                                                <v-divider></v-divider>
                                                <h4 class="text-center my-2">DATA PESERTA</h4>
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
                                                        <td class="pl-2">{{ rombel[0].label }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Sekolah Asal</td>
                                                        <td class="mx-2">:</td>
                                                        <td class="pl-2">{{ $page.props.sekolah.nama_sekolah }}</td>
                                                    </tr>
                                                </table>
                                                <br>
                                                <br>
                                                <br>
                                                <v-row>
                                                    <v-col style="margin-left:50%; font-size:1em;">
                                                        Kepala Sekolah
                                                        <br>
                                                        <br>
                                                        <br>
                                                        <br>
                                                        <b><u>{{$page.props.sekolah.ks.name}}</u></b><br>
                                                        NIP. {{$page.props.sekolah.ks.nip}}
                                                    </v-col>
                                                </v-row>
                                            </v-card-text>
                                        </v-card>
                                        <div v-if="(i+1)%6 == 0" style="page-break-after:always;" class="pageBreak">
                                            ===================================================================
                                        </div>
                                    </span>
                                    
                                </div>
                            </v-sheet> -->
                            <span v-if="pages.length > 0" id="print">
                               <v-sheet v-for="(page,i) in pages" :key="i" class="my-2 page" color="#efefef" :style="(i+1) == pages.length ? 'page-break-after:avoid;' : 'page-break-after:always;'">
                                   <div v-for="(siswa,s) in page" :key="s" style="width:46%;display:inline-block;" class="kartu">
                                       <div class="header" style="position:relative;border-bottom: 5px double black;padding: 10px;">
                                           <img src="/img/malangkab.png" alt="Logo" width="50" style="position:absolute;" class="logo">
                                           <h4 class="my-0 pa-0" style="line-height:1.3em;text-align:center;">
                                                    KARTU PESERTA UJIAN <br>
                                                    SD NEGERI 1 BEDALISODO <br>
                                                    TAHUN PELAJARAN {{ $page.props.periode_aktif.tapel}}</h4>
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
        rombel: null
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
            axios({
                method: 'post',
                url: '/admin/rombel?periode='+this.$page.props.periode_aktif.kode_periode
            }).then(res => {
                this.rombels = res.data.rombels
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