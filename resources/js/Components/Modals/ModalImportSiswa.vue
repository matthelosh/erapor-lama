<template lang="html">
    <div>
    <v-dialog
        v-model="show"
        transition="dialog-bottom-transition"
        fullscreen
        hide-overlay
    >
        <v-card>
            <v-toolbar dense color="primary" dark>
                <v-toolbar-title>
                    <v-icon>mdi-file-excel</v-icon>
                    Impor Siswa
                </v-toolbar-title>
                <v-spacer></v-spacer>
                <v-btn fab small @click="$emit('hide')" color="error">
                    <v-icon>mdi-close</v-icon>
                </v-btn>
            </v-toolbar>
            <v-card-text> 
                <v-container fluid>
                    <v-row>
                        <v-col cols="12" sm="9">
                            <v-card>
                                <v-toolbar dense class="mb-5">
                                    <v-card-title>Daftar Calon Siswa</v-card-title>
                                    <v-divider vertical></v-divider>
                                    <v-spacer></v-spacer>
                                        <v-text-field
                                            label="Cari Data"
                                            v-model="search"
                                            single-line
                                            hide-details
                                            flat
                                            dense
                                            append-icon="mdi-magnify"
                                            clearable
                                            :disabled="(newsiswas.length < 1) ? true : false"
                                        >
                                        </v-text-field>
                                </v-toolbar>
                                <v-data-table
                                    :items="newsiswas"
                                    :headers="headers"
                                    dense
                                    :search="search"
                                    no-data-text="Data Tidak Ditemukan"
                                >

                                </v-data-table>
                            </v-card>
                        </v-col>
                        <v-col cols="12" sm="3">
                            <v-card>
                                <v-toolbar>
                                    Pilih File Excel
                                </v-toolbar>
                                <v-card-text>
                                    <label>Pilih file yang berisi data siswa yang akan disimpan yang berupa xls, xlsx, csv.</label>
                                    <v-file-input 
                                        dense 
                                        outlined 
                                        prepend-icon="" 
                                        append-icon="mdi-file-excel" 
                                        label="Pilih File" 
                                        accept=".csv, application/vnd.ms-excel, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet"
                                        @change="onFilePicked"
                                    ></v-file-input>
                                    <v-divider></v-divider>
                                    <v-checkbox v-model="checknewsiswas" label="Data Calon Siswa Baru Sudah Benar" @change="checkNewSiswas" :disabled="(newsiswas.length < 1 ) ? true:false"></v-checkbox>
                                    <v-btn :disabled="!checknewsiswas" color="secondary" :dark="checknewsiswas" class="ma-auto" @click.stop="importSiswas">Simpan</v-btn>

                                </v-card-text>
                            </v-card>
                        </v-col>
                    </v-row>
                </v-container>
            </v-card-text>
        </v-card>
    </v-dialog>
    <v-dialog
            v-model="errorDialog"
            max-width="400"
        >
            <v-card 
                
                color="error"
                dark
            >
                <v-card-title>Format Kolom Salah.</v-card-title>
                <v-card-text>
                    <v-container fluid>
                        <v-row>
                            <v-col cols="12">
                                <a href="/img/format-xls-user.png" target="__blank">
                                <v-img
                                    src="/img/format-import-siswa.png" alt="Gambar"
                                    title="Klik untuk melihat contoh format"
                                />
                                </a>
                            </v-col>
                        </v-row>
                        <v-row>
                            <v-col cols="12">
                                <div class="d-flex justify-center">
                                    <v-btn color="secondary" @click.stop="unduhTemplate">Download Contoh Format</v-btn>
                                </div>
                            </v-col>
                        </v-row>
                    </v-container>
                </v-card-text>
            </v-card>
        </v-dialog>
        </div>
</template>
<script>
    import XLSX from 'xlsx'
    import saveAs from 'file-saver'
    export default {
        name: 'ModalImportSiswa',
        props: {
            modal: Boolean
        },
        data: () =>({
            headers:[],
            newsiswas:[],
            checknewsiswas: false,
            errorDialog: false,
            search: ''
        }),
        methods: {
            importSiswas() {
                let siswas = this.newsiswas
                axios({
                    method: 'post',
                    url: '/dashboard/siswa/import',
                    data: { siswas: siswas }
                }).then( res => {
                    this.newsiswas = []
                    this.headers = []
                    this.$emit('hide')
                })
            },
            onFilePicked(e) {
                if ( e == null ) {
                    this.newsiswas = []
                    this.headers = []
                }
                var files = e.files;
                var reader = new FileReader;
                reader.onload = ev => {
                    var data = ev.target.result
                    var workbook = XLSX.read(data, {type: 'binary'})
                    var wsname = workbook.SheetNames[0]
                    const ws = XLSX.utils.sheet_to_json(workbook.Sheets[wsname])
                    const lists = []

                    for ( var i = 0; i < ws.length; i++) {
                        lists.push(ws[i])
                    }

                    this.newsiswas = lists
                    const a = workbook.Sheets[workbook.SheetNames[0]]
                    const headers = this.getHeaders(a)
                    this.headers = headers
                }
                reader.readAsBinaryString(e)
            },
            getHeaders(sheet) {
                const headers = []
                const check = []
                const range = XLSX.utils.decode_range(sheet["!ref"])
                let C;

                const R = range.s.r
                let i = 0
                for ( C = range.s.c; C <= range.e.c; ++C ) {
                    var cell = sheet[ XLSX.utils.encode_cell({c:C, r:R})]

                    var hdr = "UNKNOWN"+C
                    if ( cell && cell.t ) hdr = XLSX.utils.format_cell( cell )
                    if ( hdr.indexOf('UNKNOWN') > -1 ) {
                        if (!i) {
                            hdr = '__EMPTY'
                        } else {
                            hdr = '__EMPTY' + i
                        }
                        i++
                    }
                    check.push( hdr )
                    headers.push({ text:hdr, value:hdr })
                }
                var valid = this.checkHeaders(check)
                if ( valid == true ) {
                    return headers
                } else {
                    this.errorDialog = true
                }
            },
            checkHeaders ( headers ) {
                var v = 0
                const valids = ['nik', 'nisn', 'nis','nama','jk', 'tempat_lahir', 'tanggal_lahir','agama', 'alamat','rt','rw','desa','kec','kab','kode_pos','hp','email','sekolah_asal'];
                for ( var i = 0; i < valids.length; i++ ) {
                    if ( headers.includes( valids[i])) v++
                }

                if ( v < valids.length ) {
                    return false
                } else {
                    return true
                }
            },
            unduhTemplate() {
                saveAs('/files/template_siswa.xlsx')
            },
            checkNewSiswas(e) {
                this.checknewsiswas = e
            }
        },
        computed: {
            show() {
                return this.modal
            }
        }
    }

</script>