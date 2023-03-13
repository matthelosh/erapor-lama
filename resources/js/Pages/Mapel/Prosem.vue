    <template>
    <Layout :title="page_title">
        <v-overlay :value="overlay">
          <v-progress-circular
            indeterminate
            size="64"
          ></v-progress-circular>
        </v-overlay>
        <v-container>
            <v-row>
                <v-col cols="12">
                    <v-card>
                        <v-toolbar dense>
                            <v-toolbar-title>Program Semester</v-toolbar-title>
                            <v-spacer></v-spacer>
                            <v-btn color="primary" rounded @click="tambah" :outlined="dark" class="mr-3">Tambah</v-btn>
                            <v-btn color="info" rounded @click="cetak" :outlined="dark"><v-icon>mdi-printer</v-icon> Cetak</v-btn>
                        </v-toolbar>
                        <v-card-text>
                            <v-row>
                               <v-col cols="4">
                                    <v-select rounded dense :items="rombels" item-text="text" item-value="value" hide-details outlined label="Pilih Rombel"  v-model="selectedrombel" @change="onSelectedRombel"></v-select>
                                </v-col>
                                <v-col cols="4">
                                    <v-select rounded dense v-model="selectedhari" :items="haris" item-text="text" item-value="value" hide-details outlined label="Pilih Hari" @change="onSelectedHari" ></v-select>
                                </v-col>
                                <v-col cols="4"></v-col>
                            </v-row>
                            <v-sheet id="print" style="background: white; color: black; margin-top: 20px;padding: 10px 5px; border-radius: 3px;">
                                <v-row>
                                    <v-col cols="12" class="text-center">
                                        <h3>PROGRAM SEMESTER</h3>
                                        <H3>TAHUN PELAJARAN {{ $page.props.periode_aktif.tapel }}</H3>
                                    </v-col>
                                    <v-col cols="2">
                                        <h5>Nama Sekolah</h5>
                                        <h5>Kelas / Semester</h5>
                                    </v-col>
                                    <v-col cols="4">
                                        <h5>: {{ $page.props.sekolah.nama_sekolah }}</h5>
                                        <h5>: {{ rombel ? rombel.text : '-' }} / {{ $page.props.periode_aktif.semester }}</h5>
                                    </v-col>
                                </v-row>
                                <v-data-table
                                    :items="promes"
                                    :headers="headers"
                                    dense
                                    hide-default-header
                                    disable-pagination
                                    hide-default-footer
                                    light
                                    no-data-text="Data tidak ditemukan."
                                >
                                    <template #header="{  }">
                                        <thead class="v-data-table-header">
                                            <tr>
                                                <th
                                                  v-for="header in headers"
                                                  :key="header.value"
                                                  class="text-uppercase text-center"
                                                  scope="col"
                                                  :rowspan="(header.minggus ? '1' : '2')"
                                                  :colspan="(header.minggus ? header.minggus.length : '0')"
                                                >
                                                  {{ header.text }}
                                                </th>
                                            </tr>
                                            <tr>
                                                <th v-for="(h1, i1) in getSubHeader(headers)" class="text-center child-header td-border-style"  >{{ h1.text }}</th>
                                            </tr>   
                                        </thead>
                                    </template>
                                    <template v-slot:body="{items}">
                                        <v-alert v-if="items.length < 1"> Data tidak ada</v-alert>
                                        <tr v-for="item in items" :ley="item.tema">
                                            <td>Bab {{ item.tema.substr(2, (item.tema.length-2)) }}</td>
                                            <td></td>
                                            <td><p v-for="(d, index) in item.children" :key="index">{{ d.kd.kode_kd }}</p></td>
                                            <td v-for="(m, i) in item.minggus" :key="i" style="position: relative;">
                                                <span v-for="t in item.children">
                                                    <span v-if="t.bulan == m.b && t.minggu == m.m" style="background: #44ff5533; display: flex; position: absolute;height: 100%;top:0;width: 100%; justify-content: center;"><v-icon small>mdi-check</v-icon></span>
                                                </span>
                                            </td>
                                        </tr>
                                    </template>
                                </v-data-table>
                            </v-sheet>
                        </v-card-text>
                    </v-card>
                </v-col>
            </v-row>
        </v-container>
        <modal-prosem v-if="modalprosem.show" :dialog="modalprosem" @hide="closeModalProsem" @saved="getPromes(selectedrombel)"></modal-prosem>
        <alert v-if="alert.show" :dialog="alert" @close="alert.show = false"></alert>
    </Layout>
</template>

<script>
    import moment from 'moment'
    import Layout from '../../Layout/Dashboard'
    import ModalProsem from '../../Components/Modals/ModalProsem'
    import Alert from '../../Components/Modals/Alert'
    moment.locale('id')
    import _ from 'lodash'
    export default {
        name: 'Prosem',
        props: {
            page: String, page_title: String
        },
        components: { Alert, Layout, ModalProsem },
        data: () =>({
            headers: [
                // { text: 'Tema', value: 'tema'},
                // { text: 'Subtema', value: 'subtema'},
                // { text: 'KD', value: 'kd'},
                // { text: 'Bulan', value: 'bulan'},
                // { text: 'Minggu', value: 'minggu'},
            ],
            alert: { show: false },
            promes: [],
            rombels: [],
            temas: [],
            bulans: [],
            modalprosem: { show: false },
            overlay: false,
            rombels: [],
            selectedrombel: '',
            haris: [
                { text: 'Senin', value: 'senin' },
                { text: 'Selasa', value: 'selasa' },
                { text: 'Rabu', value: 'rabu' },
                { text: 'Kamis', value: 'kamis' },
                { text: 'Jumat', value: 'jumat' },
                { text: 'Sabtu', value: 'sabtu' },
            ],
            selectedhari: '',
        }),
        methods: {
            getSubHeader(headers){
                let results = []
                headers.filter(i => i.minggus).forEach( v => { 
                    v.minggus.forEach(m => {
                        results.push({text: m, value: v.value+m})
                    })
                })
                return results

            },
            cetak(){
                const html = document.getElementById('print').innerHTML
                let css = ''
                for ( const node of [...document.querySelectorAll('link[rel="stylesheet"], style')]) {
                    css += node.outerHTML
                }

                const WinPrint = window.open('','', 'left=0,top=0,width=1024,height=900, toolbar=0, scrollbars=0,status=0')
                WinPrint.document.write(`<!DOCTYPE html>
                    <html>
                        <head>
                            <title>
                                Program Semester
                            </title>
                            ${css}
                        </head>
                        <body>
                            ${html}
                        </body>
                    <html>
                `)

                WinPrint.document.close()
                WinPrint.focus()
                setTimeout(function(){
                    WinPrint.print()
                // WinPrint.close()
                }, 500)
                
            },

            closeModalProsem() {
                this.modalprosem.show = false
            },
            tambah() {
                if ( this.$page.props.user.role != 'wali') {
                    if  (!this.selectedrombel) {
                        this.alert = { show: true, title: 'Error', text: 'Pilih dulu Rombel'}
                    } else {
                        this.modalprosem = { show: true, rombel: this.selectedrombel, bulans: this.bulans }
                    }
                } else {
                    this.modalprosem = { show: true, bulans: this.bulans }
                }
            },
            setWeeks(minggus) {
                let ganjil = [7,8,9,10,11,12]
                let genap = [1,2,3,4,5,6]
                let tahun = (this.$page.props.periode.substr(-1) == '1' ) ? '20'+this.$page.props.periode.substr(0,2) : '20'+this.$page.props.periode.substr(2,2)
                var bulans = (this.$page.props.periode.substr(-1) == '1' ) ? ganjil : genap
                var datas = []

                // bulans.forEach(bulan => {
                //    var firstOfMonth = new Date(tahun, bulan, 1)
                //     var day = firstOfMonth.getDay() || 6
                //     day = day === 1 ? 0 : day
                //     if ( day ) { day-- }
                //     var diff = 7 - day 
                //     var lastOfMonth = new Date( tahun, bulan,0)
                //     var lastDate = lastOfMonth.getDate()
                //     if ( lastOfMonth.getDay() === 1 ) {
                //         diff--
                //     }
                //     var result = Math.ceil((lastDate - diff) / 7)+1;
                //     var minggus = []
                //     for ( var i = 1; i < result; i++) { minggus.push(i) }
                //     datas.push({bulan: bulan, minggus: minggus, jml: result})
                // })

                
                    var pekans = _.groupBy(minggus, 'bulan')
                    Object.entries(pekans).forEach(value => {
                        
                        var minggu = []
                        for ( var i = 0; i < value[1][0].jumlah; i++) {
                            minggu.push(i+1)
                        }
                        datas.push({bulan: value[0], minggus: minggu, jml: value[1][0].jumlah})
                    })
                    // console.log(pekans)
                this.bulans = datas
            },
            onSelectedRombel() {
                this.getPromes()
            },
            getPromes() {
                this.overlay = true
                axios({
                    method: 'post',
                    url: '/dashboard/promes?q=byhari',
                    data: {
                        periode: this.$page.props.periode,
                        semester: this.$page.props.periode_aktif.semester,
                        kelas: this.selectedrombel,
                        mapel: this.$page.props.mapel.kode_mapel,
                        guru: this.$page.props.user.nip
                    }
                }).then( res => {
                    var promes = []
                    var minggus = []
                    this.setWeeks(res.data.pekans)
                    this.setHeaders()
                    this.bulans.forEach(b => {
                        b.minggus.forEach(m => {
                            minggus.push({b: b.bulan, m: m})
                        })
                    })
                    var datas = _.groupBy(res.data.promes, 'tema_id')
                    Object.entries(datas).forEach( value => {
                        promes.push({ tema: value[0], children: value[1], minggus: minggus})
                    })


                    // console.log(promes)zz
                    
                    
                    this.promes = promes
                    
                    this.overlay = false
                })
            },
            setHeaders(){
                var headers = [
                    { text: 'Tema', value: 'tema'},
                    { text: 'Subtema', value: 'subtema'},
                    { text: 'KD', value: 'kd'},
                ]
                var bulans = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember']
                this.bulans.forEach(item => {
                    headers.push({ text: bulans[item.bulan-1], value: item.bulan, minggus: item.minggus})
                    
                })
                this.headers = headers
            },
            onSelectedHari(e){
                // this.selectedrombel = e
                this.getPromes(e)
            },
            init() {
                if(this.$page.props.user.role != 'wali') {
                        axios({
                            method: 'post',
                            url: '/dashboard/rombel?mode=select'
                        }).then( res => {
                            if(res.data.rombels.length > 0) {
                                res.data.rombels.forEach(item=>{
                                    this.rombels.push({ text: item.label, value: item.kode_rombel})
                                })
                            } else {
                                this.snackbar = { show: true, text: 'Rombel belum ada. Hubungi Admin'}
                            }
                        }).catch( err => {
                            console.log(err)
                        })
                    } else {
                        this.rombels = [ {text: this.$page.props.rombel.label, value: this.$page.props.rombel.kode_rombel}]
                    }
            }
            
        },
        
        computed: {
            dark() {
                return this.$vuetify.theme.dark
            },
            rombel() {
                var rombel = this.rombels.filter( item => {
                    return item.value == this.selectedrombel
                })

                return rombel[0]
            }
        },
        created() {
            this.init()
            this.setWeeks()
            // this.getPromes()

        }
    }

</script>

<style scoped lang="sass">
    .v-data-table
        td
            font-size: 1em
            border-color: rgba(0,0,0,0.6)
            border-style: solid
            border-left-width: 1px
            border-right-width: 1px
            border-top-width: 1px
            border-bottom-width: 1px
        th
            border-color: rgba(0,0,0,0.5)
            border-style: solid
            border-left-width: 1px
            border-right-width: 1px
            border-top-width: 1px
            border-bottom-width: 1px
    .v-data-table table
        border: solid 1px #363636
        border-radius: 5px
    .v-data-table table thead tr th
        font-weight: bold
        font-size: 1em
        padding: 0px 8px

    .v-data-table table tbody tr td .v-btn
        margin-right: 0px !important

    .v-data-table .v-data-table-header__sort-badge
        font-size: 10px


    .theme--light
        .v-data-table table thead tr th
            color: black
    .v-data-table table thead tr td
        color: black

    .v-data-table>.v-data-table__wrapper>table>tbody>tr>td, .v-data-table>.v-data-table__wrapper>table>tbody>tr>th, .v-data-table>.v-data-table__wrapper>table>tfoot>tr>td,
    .v-data-table>.v-data-table__wrapper>table>tfoot>tr>th, .v-data-table>.v-data-table__wrapper>table>thead>tr>td, .v-data-table>.v-data-table__wrapper>table>thead>tr>th
        padding: 0

    @media print
        @page
            size: landscape
</style>