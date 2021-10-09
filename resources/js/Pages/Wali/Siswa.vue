<template lang="html">
    <Layout :title="page_title">
        <v-container>
            <v-row>
                <v-col cols="12">
                    <v-card class="mt-5">
                        <v-toolbar dense>
                            <v-icon>mdi-dots-vertical</v-icon>
                            <v-toolbar-title><span class="hidden-sm-and-down">Data</span> Siswa</v-toolbar-title>
                            <v-spacer></v-spacer>
                            <v-btn ripple  rounded color="primary" @click.stop="newSiswa">
                                <v-icon>mdi-account-plus</v-icon>
                                <span class="hidden-sm-and-down">Tambah</span>
                            </v-btn>
                            &nbsp;
                            <v-btn ripple rounded color="info" @click.stop="importSiswa = true">
                                <v-icon>mdi-account-multiple-plus</v-icon>
                                <span class="hidden-sm-and-down">Impor</span>
                            </v-btn>
                            &nbsp;
                            <span class="hidden-sm-and-down">
                            <v-btn   ripple  rounded color="warning" @click.stop="print">
                                <v-icon>mdi-printer</v-icon>
                                    Cetak
                            </v-btn>
                            </span>
                            &nbsp;
                            <v-btn   ripple rounded color="success" @click.stop="unduhSiswa">
                                <v-icon>mdi-file-excel-outline</v-icon>
                                <span class="hidden-sm-and-down">
                                    Unduh
                                </span>
                            </v-btn>
                        </v-toolbar>
                        <v-card-text>
                            <v-container>
                                <v-row>
                                    <v-col cols="5">
                                        <span v-if="$page.props.user.role == 'admin'" >{{ disabledCount }} siswa punya akun</span>
                                        <v-badge
                                            v-if="$page.props.user.role == 'admin'"
                                            :content="selectedsiswas.length"
                                            :value="selectedsiswas.length"
                                            color="success"
                                            overlap
                                          >
                                            <v-btn @click.stop="grantAccess" dense color="error" :disabled="(selectedsiswas.length < 1)" rounded>
                                                <v-icon>mdi-key</v-icon> 
                                                Beri Akses Login
                                            </v-btn>
                                        </v-badge>
                                    </v-col>
                                    <v-col cols="3">
                                        <div v-if="$page.props.user.role == 'admin'">
                                            <input type="file" ref="fileortu" class="d-none" accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel, *.csv, *.ods" @change="onFileOrtuPicked" />
                                            <v-btn color="success" @click="imporOrtu" rounded dense :loading="dialogImporOrtu.loading">
                                                <v-icon>mdi-microsoft-excel</v-icon>
                                                Impor Orang Tua
                                            </v-btn>
                                        </div>
                                        <v-btn v-if="$page.props.user.role == 'wali'" color="primary" rounded @click="cetakKartu">Cetak Kartu Siswa</v-btn>
                                    </v-col>
                                    <v-col cols="4">
                                         <v-text-field v-model="search" prepend-inner-icon="mdi-magnify" label="Cari .." outlined dense hide-details max-width="100" rounded></v-text-field>
                                    </v-col>
                                </v-row>
                            </v-container>
                            <v-data-table :headers="sheaders" :items="siswas" :search="search" :show-select="$page.props.user.role=='admin'" v-model="selectedsiswas" @toggle-select-all="selectAllToggle"> 
                                <template v-slot:item.data-table-select="{ item, isSelected, select }">
                                    <v-simple-checkbox
                                        :value="isSelected"
                                        :readonly="item.user.length > 0"
                                        :disabled="item.user.length > 0"
                                        :color="item.user.length > 0 ? 'error' : 'success'"
                                        @input="select($event)"
                                    ></v-simple-checkbox>
                                </template>
                                <template v-slot:item.foto="{item}">
                                    <v-tooltip right>
                                        <template v-slot:activator="{ on, attrs }" >       
                                            <v-avatar size="46" @click.stop="editItem(item)" v-bind="attrs" v-on="on">
                                                <img :src="'/storage/img/siswas/'+item.nisn+'.jpg'" @error="setDefaultFoto($event, item)" class="foto" />
                                            </v-avatar>
                                        </template>
                                        <span>Klik untuk melihat detil {{item.nama}}.</span>
                                    </v-tooltip>
                                </template>
                                <template v-slot:item.rombels="{item}" v-if="$page.props.user.role == 'admin'">
                                    <div>
                                        <span v-if="item.rombels.length > 0">
                                            {{item.rombels[0].label}}
                                        </span>
                                        <span v-else>
                                            Belum Masuk Rombel
                                        </span>
                                    </div>
                                </template>
                                <template v-slot:item.options="{item}"> 
                                    <div>
                                        <v-btn color="info" small @click.stop="ortu(item)" rounded>
                                            <v-icon >mdi-account-child-circle</v-icon>
                                        </v-btn>
                                        <v-btn color="error" small  @click.stop="deleteItem(item)" rounded v-if="$page.props.user.role == 'admin'">
                                            <v-icon >mdi-eraser</v-icon>
                                        </v-btn>
                                    </div>
                                </template>
                            </v-data-table>
                        </v-card-text>
                        <v-card-actions>
                           
                        </v-card-actions>
                    </v-card>
                </v-col>
            </v-row>
            <v-row>
                <v-col cols="12">
                    <v-card>
                        <v-toolbar dense>
                            <v-icon>mdi-dots-vertical</v-icon>
                            <v-toolbar-title>Grafik Siswa</v-toolbar-title>
                        </v-toolbar>
                        <v-card-text>
                            <v-row>
                                <v-col cols="12" sm="4">
                                    <v-card>
                                        <v-card-title>
                                            <v-icon>mdi-gender-male-female</v-icon>
                                            Gender Siswa
                                        </v-card-title>
                                        <v-card-text>
                                            <div id="chart-gender"></div>
                                        </v-card-text>
                                    </v-card>
                                </v-col>
                                <v-col cols="12" sm="4">
                                    <v-card>
                                        <v-card-title>
                                            <v-icon>mdi-islam</v-icon>
                                            Agama Siswa
                                        </v-card-title>
                                        <v-card-text>
                                            <div id="chart-agama"></div>
                                        </v-card-text>
                                    </v-card>
                                </v-col>
                                <v-col cols="12" sm="4">
                                    <v-card>
                                        <v-card-title>
                                            <v-icon>mdi-cube</v-icon>
                                            Rombel Siswa
                                        </v-card-title>
                                        <v-card-text>
                                            <div id="chart-rombel"></div>
                                        </v-card-text>
                                    </v-card>
                                </v-col>
                            </v-row>
                        </v-card-text>
                    </v-card>
                </v-col>
            </v-row>
        </v-container>
        <modal-siswa v-if="dialogSiswa.show" :modal="dialogSiswa" @hide="closeDialogSiswa" :siswa="siswa"></modal-siswa>
        <modal-import-siswa v-if="importSiswa" :modal="importSiswa" @hide="importSiswa = false; getSiswas()"></modal-import-siswa>
        <confirm-dialog ref="confirm"></confirm-dialog>
        <ortu-siswa v-if="dialogortu.show" :modal="dialogortu" @hide="dialogortu.show = false"></ortu-siswa>
        <kartu-siswa v-if="dialogKartu.show" :dialog="dialogKartu" @hide="dialogKartu.show = false"></kartu-siswa>
        <v-snackbar v-model="snackbar.show" :color="snackbar.color" top right multi-line>
            {{snackbar.text}}
            <template v-slot:action="{ attrs }">
                <v-btn
                  text
                  dark
                  v-bind="attrs"
                  @click="snackbar = false"
                >
                  Tutup
                </v-btn>
            </template>
        </v-snackbar>
        <impor-ortu v-if="dialogImporOrtu.show" :dialog="dialogImporOrtu" @hide="closeImporOrtu"></impor-ortu>
    </Layout>
</template>

<script>
    import Layout from '../../Layout/Dashboard';
    import ModalSiswa from './Modals/ModalSiswa';
    import ModalImportSiswa from './Modals/ModalImportSiswa';
    import ApexCharts from 'apexcharts'
    import Cetak from '../../Plugins/print'
    import Download from '../../Plugins/Download'
    import ConfirmDialog from '../../Components/Modals/ConfirmDialog'
    import OrtuSiswa from './Modals/OrtuSiswa'
    import KartuSiswa from './Modals/KartuSiswa'
    import ImporOrtu from './Modals/ImporOrtu'
// import CetakTable from '../Plugins/print';
    export default {
        name: 'Siswa',
        components: { Layout, ModalSiswa, ModalImportSiswa, ConfirmDialog, OrtuSiswa, KartuSiswa, ImporOrtu },
        props: {page: String, page_title: String},
        data: () => ({
            dialogImporOrtu: {
                show: false,
            }, 
            dialogKartu: {show: false},
            dialogortu: { show: false, siswa: {} },
            dialogSiswa: {
                show: false, mode: 'create'
            },
            disabledCount: 0,
            snackbar:{show:false,color:'',text:''},
            siswa: {},
            importSiswa: false,
            search: '',
            sheaders: [
                
            ],
            siswas: [
                
            ],
            selectedsiswas: []
        }),
        methods: {
            closeImporOrtu() {
                this.dialogImporOrtu = {
                    show: false,
                    e: null
                }
                this.$refs.fileortu.value = null
            },
            imporOrtu() {
                this.dialogImporOrtu.loading = true
                this.$refs.fileortu.click()
            },
            onFileOrtuPicked(e){
                this.dialogImporOrtu = {
                    show: true,
                    loading: true,
                    e: e
                }
            },
            cetakKartu() {
                // alert('hi')
                this.dialogKartu = { show: true, siswas: this.siswas }
            },
            selectAllToggle(props) {
                // alert('hi')
                if ( this.selectedsiswas.length != props.items.length - this.disabledCount) {
                    this.selectedsiswas = []
                    const self = this
                    props.items.forEach( item => {
                        if ( item.user.length < 1) {
                            self.selectedsiswas.push( item )
                        }
                    })
                } else {
                    this.selectedsiswas = []
                }
                // console.log(props)
            },
            grantAccess(){
                axios({
                    method: 'post',
                    url: '/dashboard/admin/user/grantsiswa',
                    data: { siswas: this.selectedsiswas}
                }).then( res => {
                    this.selectedsiswas = []
                    this.disabledCount = 0
                    this.getSiswas()
                    this.snackbar = {
                        show: true,
                        color: 'success',
                        text: res.data.msg
                    }
                }).catch( err => {
                    this.snackbar = {
                        show: true,
                        color: 'error',
                        text: err.response.msg
                    }
                })
            },
            newSiswa() {
                this.dialogSiswa = {show: true}
            },
            closeDialogSiswa(val) {
                this.dialogSiswa = {show: val}
                this.getSiswas()
            },
            print() {
                let datas = this.siswas
                Cetak.CetakTable(datas)
            },
            unduhSiswa() {
                let datas = this.siswas, title = 'Data Siswa'
                // let headers = ['NIK', 'NISN', 'NIS', ]
                Download.UnduhExcel(datas, title)
            },
            getSiswas() {
                let role = this.$page.props.user.role
                var sheaders = [
                    {value: 'index', text: 'No', sortable: false},
                    {value: 'foto', text: 'Foto'},
                    {value: 'nisn', text: 'NISN'},
                    {value: 'nis', text: 'NIS'},
                    {value: 'nama', text: 'Nama'},
                    {value: 'agama', text: 'Agama'},
                    
                    {value: 'ortu.nama_ibu', text: 'Nama Ibu'},
                    {value: 'options', text: 'Opsi'},
                ]

                if (this.$page.props.user.role == 'admin') {
                    sheaders.push({value: 'rombel[0].label', text: 'Kelas'},)
                }
                this.sheaders = sheaders
                axios({
                    method: 'post',
                    url: '/wali/siswa?role='+role
                }).then(res => {
                    let siswas = []
                    res.data.siswas.forEach((siswa,index) => {
                        siswa.index = index+1
                        siswas.push(siswa)
                        if(role == 'admin') {
                            if(siswa.user.length > 0) {
                                this.disabledCount += 1
                            }
                        }
                    })
                    this.siswas = siswas
                    this.grafik(res.data.siswas)
                }).catch(err => {
                    console.log(err)
                })
            },
            setDefaultFoto(event, siswa) {
                var foto = (siswa.jk == 'l') ? 'siswa.png' : 'siswi.png'
                event.target.src = '/storage/img/siswas/'+foto
            },
            // test() {
            //     alert('hi')
            // },
            ortu(siswa) {
                this.dialogortu = { show: true, siswa: siswa }
            },
            async deleteItem(siswa) {
                // alert(siswa.nama)
                if ( await this.$refs.confirm.open("Confirm", "Yakin menghapus data "+siswa.nama+"?")) {
                    axios({
                        method: 'post',
                        url: '/dashboard/siswa/'+siswa.id,
                        data: { _method: 'delete'}
                    }).then(response => {
                        // alert(response.data.msg)
                        this.snackbar = {show:true,color:'success', text: response }
                        this.getSiswas()
                    }).catch(err=>{
                        this.snackbar = {show:true,color:'error', text: err.response.data.msg }
                        if ( err.response.data.code == 401 || err.response.data.msg == 'Sesi Berakhir') window.location.href = '/'
                    })
                }
            },
            editItem(siswa) {
                this.siswa = siswa
                this.dialogSiswa = {show: true, mode:'edit'}
                // this.$refs.ModalSiswa.setSiswa(siswa)
                this.$emit('siswa', siswa)
            },
            grafik (siswas) {
                let jks = [0,0], labeljk = ['Laki-laki', 'Perempuan'];
                let agamas = [0,0,0,0,0,0], labelagama = ['Islam','Protestan','Katolik','Hindu','Budha','Konghuchu'];
                let rombels = [], labelrombel = []
                siswas.forEach(siswa => {
                    ( siswa.jk == 'l' ) ? jks[0] += 1 : jks[1] += 1
                    // Agama
                    var agama = siswa.agama
                    if ( agama == 'Islam' ) {
                        agamas[0] += 1
                    } else if ( agama == 'Protestan' ) {
                        agamas[1] += 1
                    } else if ( agama == 'Katolik' ) {
                        agamas[2] += 1
                    } else if ( agama == 'Hindu' ) {
                        agamas[3] += 1
                    } else if ( agama == 'Budha' ) {
                        agamas[4] += 1
                    } else {
                        agamas[5] += 1
                    }

                })

                var chartjk = new ApexCharts(document.querySelector('#chart-gender'), { 
                    chart: {
                        type: 'pie'
                    },
                    series: jks, labels: labeljk
                })

                var chartagama = new ApexCharts(document.querySelector('#chart-agama'), {
                    chart: {
                        type: 'pie'
                    },
                    series: agamas, labels: labelagama
                })

                chartjk.render()
                chartagama.render()
            }


        },
        computed: {
            sitems() {
                let items = []
                this.siswas.map((siswas, index) => {
                siswas.index = index+1,
                items.push(siswas)
            })

            return items
            }
        },
        created() {
            this.getSiswas()
        }
    }
</script>

<style scoped>
    img.foto {
        cursor: pointer;
    }
</style>