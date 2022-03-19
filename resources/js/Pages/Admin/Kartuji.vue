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
                                <v-spacer></v-spacer>
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
                            <v-sheet v-if="rombel" class="my-5" ref="sheet" id="kartu">
                                <div class="mainRow"
                                    style="display:flex;flex-wrap:wrap;"
                                >
                                    <div class="kartu"
                                        style="width:45%; flex-grow:50%;margin:20px;box-sizing:border-box;" 
                                        v-for="(siswa,i) in rombel[0].siswas"
                                        :key="i"
                                        d-print-flex
                                    >
                                        <v-card style="border: 1px solid black;padding:20px;">
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
                                                <h2 class="text-center my-5">DATA PESERTA</h2>
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
                                                <br>
                                                <v-row>
                                                    <v-col style="margin-left:50%;">
                                                        Kepala Sekolah
                                                        <br>
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
                                    </div>
                                </div>
                            </v-sheet>
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
            let sheet = document.querySelector('#kartu').outerHTML
            let style = "//localhost:8080/css/app.css"
            let html =`
                <!doctype html>
                <html>
                    <head>
                        <title>Ceak Kartu Ujian</title>
                        <style>
                            .mainRow {
                                display: flex;
                                flex-direction:row;
                            }
                            .mainRow > .col-6{
                                flex: 50%;
                            }
                            .text-center {
                                text-align: center;
                            }
                            .logo {
                                top: 20px;
                                left: 20px;
                            }
                            @media print {
                                .mainRow < .col-6 {
                                    display: inline-block;
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
    mounted() {
        this.getRombels()
    }
}
</script>
<style scoped>
    
</style>