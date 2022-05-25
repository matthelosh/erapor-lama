<template>
    <div>
        <v-dialog
            v-model="dialog.show"
            transition="dialog-bottom-transition"
            fullscreen
            hide-overlay
        >
            <v-card>
                <v-toolbar dense>
                    Daftar Nilai {{dialog.jenis.toUpperCase()}} {{ dialog.mapel.text }}
                    <v-spacer></v-spacer>
                    <v-btn rounded color="primary" @click.stop="showImportDialog">
                        <v-icon>mdi-microsoft-excel</v-icon>
                         Impor Nilai
                    </v-btn>
                    <v-btn icon @click="$emit('hide')">
                        <v-icon>mdi-close</v-icon>
                    </v-btn>
                </v-toolbar>
                <v-card-text>
                    <v-data-table
                        dense
                        :headers="headers"
                        :items="siswas"
                        :search="search"
                    >
                    <template v-slot:top>
                        <v-container>
                            <v-row>
                                <v-col cols="6" sm="4">
                                    <h3>Data Nilai</h3>
                                </v-col>
                                <v-col cols="6" sm="4"></v-col>
                                <v-col cols="6" sm="4">
                                    <v-text-field v-model="search" label="Cari" outlined dense append-icon="mdi-magnify" clearable/>
                                </v-col>
                            </v-row>
                        </v-container>
                    </template>
                    
                    <template
                        v-for="(header,index) in headers"
                        v-slot:[`header.${header.value}`]="{header}"
                    >
                        <span :key="header.text">{{header.text}}</span><br />
                        <v-btn v-if="index > 1 && index < (headers.length -1)" small color="warning" dense
                            @click.stop="editNilaiKD(header.text, header.index, header.ppn)"
                            :key="header.value" rounded>
                            <v-icon small>mdi-pencil</v-icon>
                        </v-btn>
                        <v-btn v-if="index > 1 && index < (headers.length -1)" small color="error" rounded dense :key="index" @click.stop="deleteNilai(header.text, header.index, header.ppn)">
                            <v-icon small>mdi-delete</v-icon>
                        </v-btn>
                    </template>

                    <template v-slot:body="{items}">
                        <tbody>
                            <tr v-for="(item,i) in items" :key="item.id">
                                <td>{{ item.nisn}}</td>
                                <td>{{ item.nama}}</td>
                                <td v-for="nilai in item.nilais" style="cursor:pointer;" v-bind:key="nilai.value">
                                    {{ nilai.nilai }}
                                </td>
                                <td>{{ Math.round(item.nilais.reduce((a,b) => a + b.nilai ,0)/item.nilais.length) }}</td>
                            </tr>
                        </tbody>
                    </template>
                    </v-data-table>
                </v-card-text>
            </v-card>

        </v-dialog>
        <v-dialog v-model="dialogEdit" max-width="600">
            <v-card >
                <v-toolbar dense>
                    <v-toolbar-title>Entri Nilai</v-toolbar-title>
                    <v-spacer></v-spacer>
                    <v-btn fab small dense  @click.stop="dialogEdit = false" color="error"><v-icon>mdi-close</v-icon></v-btn>
                </v-toolbar>
                <v-card-text>
                    <v-data-table
                        dense
                        :items.sync="nilais"
                        :headers="nilaiHeaders"
                        hide-default-footer
                        disable-pagination
                    >
                        <template v-slot:body="{items}">
                            <tbody>
                                <tr v-for="(item,i) in items" :key="i">
                                    <td>{{ item.nisn }}</td>
                                    <td>{{ item.nama }}</td>
                                    <td><v-text-field v-model="nilais[i].nilai" dense  hide-details /></td>
                                </tr>
                            </tbody>
                        </template>

                    </v-data-table>
                </v-card-text>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn color="primary" rounded  @click.stop="save">Simpan</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
        <modal-import v-if="dialogImport.show" :dialog="dialogImport" @hide="hideModalImport"></modal-import>
        <v-snackbar v-if="snackbar.show" bottom right :color="snackbar.color">
            <template v-slot:action="{ attrs }">
                <v-btn color="white" icon v-bind="attrs" @click="snackbar.show = false"><v-icon>mdi-close</v-icon></v-btn>
            </template>
            {{snackbar.text}}
        </v-snackbar>
        <confirm-dialog ref="confirm"></confirm-dialog>
    </div>
</template>

<script>
    import ModalImport from './ModalImportNilai'
    import ConfirmDialog from '../../../Components/Modals/ConfirmDialog'
    export default {
        name: 'ModalNilai',
        props: {
            dialog: Object
        },
        components: { ModalImport,ConfirmDialog },
        data: () => ({
            itemsPerPage: 10,
            page: 1,
            dialogEdit: false,
            search: '',
            siswas: [],
            headers: [
                {text: 'NISN', value: 'nisn'},
                {text: 'Nama', value: 'nama'},
            ],
            nilaiHeaders: [
                {text: 'NISN', value: 'nisn', sortable: false},
                           {text: 'Nama', value: 'nama'},
                           {text: 'NILAI', value: 'nilai', width: '100px'},
                           
            ],
            nilais: [],
            selectedKd: '',
            ppn: '',
            dialogImport: {
                show: false
            },
            snackbar: {show: false},
            kds: []
        }),
        methods: {
            hideModalImport() {
                this.dialogImport.show = false
                this.snackbar = {
                    show: true,
                    color: 'success',
                    text: 'Data Nilai Berhasil Diimpor'
                }
                this.getNilais()
            },
            showImportDialog(){
                var headers = ['nisn', 'nama']
                var kds = []
                this.kds.forEach(item => {
                    kds.push(item.kd_id)
                })
                this.dialogImport = {
                    show: true,
                    headers: headers.concat(kds),
                    kds: this.kds,
                    mapel_id: this.$page.props.mapel.kode_mapel,
                    rombel_id: this.dialog.mapel.value,
                    aspek: this.dialog.aspek,
                    jenis:this.dialog.jenis,
                    siswas: this.siswas
                }

            },
            save() {
                var data = {
                    periode_id: this.$page.props.periode,
                    semester: this.$page.props.periode_aktif.semester,
                    rombel_id: (this.$page.props.user.role == 'wali') ? this.$page.props.rombel.kode_rombel : this.dialog.mapel.value,
                    aspek: this.dialog.aspek,
                    jenis:this.dialog.jenis,
                    mapel_id: (this.$page.props.user.role == 'wali') ? this.dialog.mapel.value : this.$page.props.mapel.kode_mapel,
                    kd_id: this.selectedKd,
                    ppn: this.ppn,
                    nilais: this.nilais
                }
                axios.post('/mapel/nilai/save', {
                    data
                }).then( res => {
                    this.dialogEdit = false
                    this.snackbar = {
                        show: true,
                        color: 'success',
                        text: res.data.msg
                    }
                    this.getNilais() 

                }).catch( err => {
                    console.log(err.response.msg)
                })
            },
            async deleteNilai(kd, index, ppn) {
                // alert(ppn)
                var k = kd.split('[')
                if ( await this.$refs.confirm.open("HAPUS DATA", "Yakin menghapus data nilai "+k[0]+" Rombel "+this.dialog.mapel.value+"?")) {
                    axios.delete('/mapel/nilai/'+k[0],{
                        data: {
                            _method: 'delete',
                            periode: this.$page.props.periode,
                            mapel: this.$page.props.mapel.kode_mapel,
                            semester: this.$page.props.periode_aktif.semester,
                            jenis: this.dialog.jenis,
                            ppn: ppn,
                            rombel: this.dialog.mapel.value
                            }
                    }).then( res => {
                        this.snackbar = {
                            show: true,
                            color: 'success',
                            text: res.data.msg
                        }
                        this.getNilais() 
                    }).catch( err => {
                        console.log( err.response.data.msg)
                    })
                }
            },
           getNilais() {
                if(this.$page.props.user.role == 'wali') {
                   axios.post('/mapel/nilai?rombel='+this.$page.props.rombel.kode_rombel+'&mapel='+ this.dialog.mapel.value+'&jenis='+this.dialog.jenis+'&ppn='+this.dialog.ppn+'&aspek='+this.dialog.aspek)
                        .then( res => {
                           
                            var nilais = res.data.nilais

                            nilais.forEach((item,index) => {
                                item.index = index+1 
                            })
                             this.siswas = nilais
                            nilais[0].nilais.forEach((item,index) => {
                                var header = {text: item.kd_id+'['+item.ppn+']', value: 'nilais['+index+'].nilai', sortable: false, index: index, witdh: '50px', ppn: item.ppn}
                                this.addHeaders(item,header)
                                this.kds.push({kd_id: item.kd_id, ppn: item.ppn})
                                
                            })
                            var rerata = { text: 'Rerata', sortable: false}
                            this.addHeaders(null,rerata)

                        }).catch( err => {
                            console.log(err.response.msg)
                    })
                } else {
                    axios({
                        method: 'post',
                        url: '/mapel/nilai?rombel='+this.dialog.mapel.value+'&jenis='+this.dialog.jenis+'&ppn='+this.dialog.ppn+'&aspek='+this.dialog.aspek+'&mapel='+this.$page.props.mapel.kode_mapel
                    }).then( res => {
                        var nilais = res.data.nilais

                            nilais.forEach((item,index) => {
                                item.index = index+1 
                            })
                             this.siswas = nilais
                            nilais[0].nilais.forEach((item,index) => {
                                var header = {text: item.kd_id+'['+item.ppn+']', value: 'nilais['+index+'].nilai', sortable: false, index: index, witdh: '50px', ppn: item.ppn}
                                this.addHeaders(item,header)
                                this.kds.push({kd_id: item.kd_id, ppn: item.ppn})
                                
                            })
                            var rerata = { text: 'Rerata', sortable: false}
                            this.addHeaders(null,rerata)
                    }).catch( err => {
                        console.log( err.response )
                    })       
                }
           },
           addHeaders(item,header) {

                var id = this.headers.findIndex(x => {
                    if (x.text == header.text && x.ppn == header.ppn) {
                        return true
                    } else {
                        return false
                    }
                })
                    if(id === -1) {
                        this.headers.push(header)
                    }
           },
           editNilaiKD(kd,index, ppn) {
                // alert(ppn)
               var nilais = []
               var k = kd.split('[')
               this.selectedKd = k[0]
               this.ppn = ppn
               this.siswas.forEach(siswa=>{
                   if(siswa.nilais[index].kd_id == k[0]) {
                       var $nilai = (siswa.nilais[index].nilai > 0 ? siswa.nilais[index].nilai : ( this.dialog.aspek == 'k1' || this.dialog.aspek == 'k2') ? 80 : 0)
                       nilais.push({
                           nisn: siswa.nisn,
                           nama: siswa.nama,
                           kd_id: kd,
                           nilai: $nilai
                       })
                   }
                      
               })
               this.nilais = nilais
            let data = {
                rombel: (this.$page.props.user.role == 'wali') ? this.$page.props.rombel.kode_rombel : this.dialog.mapel.value,
                periode: this.$page.props.periode_aktif.kode_periode,
                semester: this.$page.props.periode_aktif.semester,
                ppn: this.dialog.ppn,
                mapel: (this.$page.props.user.role == 'wali') ? this.dialog.mapel.value : this.$page.props.mapel.kode_mapel,
                jenis: this.dialog.jenis,
                kd: kd,
                nilais: nilais
            }
            this.dialogEdit = true
            // console.log(data)

           }
        },
        computed: {
            setNilai(nilais, kd){
                console.log(nilais, kd)
            }
        },
        created() {
            this.getNilais()
        }
    }
</script>