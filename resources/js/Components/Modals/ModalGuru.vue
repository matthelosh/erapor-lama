<template>
	<div>
		<v-dialog v-if="dialog.single" v-model="dialog.single" max-width="800">
			<v-card>
	   			<v-toolbar dense>
	   				<v-toolbar-title>{{ dialog.mode == 'baru' ? 'Tambah' : 'Edit' }}  {{ dialog.guru ? dialog.guru.name : 'Guru' }}</v-toolbar-title>
	   				<v-spacer></v-spacer>
	   				<v-btn small fab color="error" @click="$emit('hide')"><v-icon>mdi-close</v-icon></v-btn>
	   			</v-toolbar>
	   			<v-card-text>
	   				<v-container>
	   					<v-row>
	   						<v-col cols="3">
	   							<h3 style="text-align: center">Foto Guru</h3>
	   							<v-avatar size="150" class="m-auto">
	   								<img src="/img/users/user_l.png" />
	   							</v-avatar>

	   						</v-col>
	   						<v-col cols="9">
			   					<v-row>
			   						<v-col cols="12" sm="5">
			   							<v-text-field v-model="newguru.nip" outlined rounded label="NIP" dense hide-details />
			   						</v-col>
			   						<v-col cols="12" sm="7">
			   							<v-text-field v-model="newguru.name" outlined rounded label="Nama" dense hide-details />
			   						</v-col>
			   						<v-col cols="12" sm="5">
			   							<v-select v-model="newguru.jk" outlined rounded label="Jenis Kelamin" dense hide-details :items="jks" item-text="text" item-value="value" />
			   						</v-col>
			   						<v-col cols="12" sm="7">
			   							<v-text-field v-model="newguru.email" outlined rounded label="Email" dense hide-details />
			   						</v-col>
			   						<v-col cols="12" sm="5">
			   							<v-select v-model="newguru.role" outlined rounded label="Role" dense hide-details :items="roles" item-text="text" item-value="value" />
			   						</v-col>
			   						<v-col cols="12" sm="7">
			   							<v-text-field v-model="newguru.hp" outlined rounded label="No. HP" dense hide-details />
			   						</v-col>
			   						<v-col cols="12" sm="5">
			   							<v-select v-model="newguru.status" outlined rounded label="Status" dense hide-details :items="status" item-text="text" item-value="value" />
			   						</v-col>
			   						<v-col cols="12" sm="7">
			   							<v-textarea v-model="newguru.alamat" outlined rounded label="Alamat" rows="2" dense hide-details />
			   						</v-col>
			   						<v-col cols="12" class="d-flex justify-center">
			   							<v-btn color="primary" rounded @click="simpan">Simpan</v-btn>
			   						</v-col>
			   					</v-row>
			   				</v-col>
			   			</v-row>
	   				</v-container>
	   			</v-card-text>
			</v-card>
		</v-dialog>

		<v-dialog
			v-if="dialog.impor"
            v-model="dialog.impor"
            transition="dialog-bottom-transition"
            fullscreen
            hide-overlay
        >
            <v-toolbar 
                dense
                color="primary"
                dark
            >
                <v-toolbar-title>
                    <v-icon>mdi-account-multiple-plus</v-icon>
                    Impor Guru
                </v-toolbar-title>
                <v-spacer></v-spacer>
                    <v-btn small color="error" @click="$emit('hide')" fab>
                        <v-icon>mdi-close</v-icon>
                    </v-btn>
            </v-toolbar>
            <v-card>
                <v-card-text>
                    <v-container fluid>
                        <v-row>
                            <v-col cols="12" sm="9">
                                <v-card>
                                    <v-toolbar dense>
                                        <v-card-title>Daftar Calon Guru</v-card-title>
                                        <v-divider vertical></v-divider>
                                        <v-spacer></v-spacer>
                                            <v-text-field
                                                label="Cari Data"
                                                v-model="search"
                                                single-line
                                                hide-details
                                                flat
                                                dense
                                                outlined
                                                rounded
                                                append-icon="mdi-magnify"
                                                clearable
                                                :disabled="(newgurus.length < 1)"
                                            >
                                            </v-text-field>
                                    </v-toolbar>
                                    <v-data-table
                                        :items="newgurus"
                                        :headers="newheaders"
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
                                        <label>Pilih file yang berisi data pengguna yang akan disimpan yang berupa xls, xlsx, csv.</label>
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
                                        <v-checkbox v-model="checknewgurus" label="Data Pengguna Baru Sudah Benar" @change="checkNewGurus" :disabled="(newgurus.length < 1 ) ? true:false"></v-checkbox>
                                        <v-btn :disabled="!checknewgurus" color="primary" :dark="checknewgurus" class="ma-auto" @click.stop="importGurus">Simpan</v-btn>

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
                                    src="/img/format-xls-user.png" alt="Gambar"
                                    title="Klik untuk melihat contoh format"
                                />
                                </a>
                            </v-col>
                        </v-row>
                        <v-row>
                            <v-col cols="12">
                                <div class="d-flex justify-center">
                                    <v-btn color="success" @click.stop="genTemplate">Download Contoh Format</v-btn>
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
	import Download from '../../Plugins/Download'
	export default {
		name: 'ModalGuru',
		props: {dialog: Object},
		data: () => ({
			search: '',
			newguru: {
				// nip: '',
				// name: '',
				// jk: '',
				// email: '',
				// role: '',
				// alamat: '',
				// hp: '',
				// status: ''
			},
			jks: [
				{ text: 'Laki-laki', value: 'l'},
				{ text: 'Perempuan', value: 'p'},
			],
			roles: [
				// {value:'admin',text: 'Admin'},
	            {value: 'ks',text: 'Kepala Sekolah'},
	            {value: 'wali',text: 'Wali Kelas'},
	            {value:'gpai',text:'Guru PAI'}, 
	            {value:'gor', text: 'Guru Olah Raga'}, 
	            {value:'gbig', text: 'Guru Bahasa Inggris'},
	            // {value:'siswa', text: 'Siswa'}
			],
			status: [
				{ text: 'PNS', value: 'pns'},
				{ text: 'GTT', value: 'gtt'},
			],
			newgurus: [],
	        newheaders:[
	            {
	                text: 'nip',
	                value: 'nip'
	            },
	            {
	                text: 'Nama',
	                value: 'nama_siswa'
	            }
	        ],
	        checknewgurus: false,
	        errorDialog: false,
	        dialogImport: false,
		}),
		methods: {
			simpan(e) {
				e.preventDefault()
	            let guru = this.newguru
	            console.log(guru)
	            axios({
	                method: 'post',
	                url: '/dashboard/guru/store',
	                data: guru
	            }).then(response => {
	                this.snackbar = {
	                    show: true,
	                    text: response.data.msg,
	                    color: 'success'
	                }
	                this.$emit('getGurus')
	                this.$emit('hide')
	            }).catch(err => {
	                this.snackbar = {
	                    show: true,
	                    text: err.response.data,
	                    color: 'error'
	                }
	                // conso+le.log(err.response)
	            })
			},
			importGurus() {
	            // let users = this.newgurus
	            axios({
	                method: 'post',
	                url: '/dashboard/guru/import',
	                data: {gurus: this.newgurus}
	            }).then(response => {
	                // console.log(response)
	                this.dialogImport = false
	                this.newgurus = []
	                this.$emit('getGurus')
	                this.$emit('hide')
	                this.snackbar = {
	                    show: true,
	                    color: 'success',
	                    text: response.data.msg
	                }

	            }).catch(err => {
	                console.log(err.response)
	            })
	        },
	        onFilePicked(e) {
	            // console.log(e)
	            if (e == null) {
	                this.newgurus = []
	                this.newheaders = []
	                return false
	            }
	            var files = e.files;
	            var reader = new FileReader
	            reader.onload = ev => {
	                var data = ev.target.result
	                var workbook = XLSX.read(data,{type: 'binary'})

	                var wsname = workbook.SheetNames[0]
	                const ws = XLSX.utils.sheet_to_json(workbook.Sheets[wsname])
	                const lists = []

	                for ( var i = 0; i < ws.length; i++ ) {
	                    lists.push(ws[i])
	                }
	                this.newgurus = lists
	                console.log(lists)
	                // GetHeaders
	                const a = workbook.Sheets[workbook.SheetNames[0]]
	                const headers = this.getHeaders(a)
	                
	                this.newheaders = headers
	                // console.log(valid)
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
	            for (C = range.s.c; C <= range.e.c; ++C) {
	                var cell = sheet[XLSX.utils.encode_cell({c:C, r:R})]

	                var hdr = "UNKNOWN"+C
	                if ( cell && cell.t) hdr = XLSX.utils.format_cell(cell)
	                if( hdr.indexOf('UNKNOWN') > -1 ) {
	                    if(!i) {
	                        hdr = '__EMPTY'
	                    } else {
	                        hdr = '__EMPTY'+i
	                    }
	                    i++
	                }
	                check.push(hdr)
	                headers.push({text:hdr, value:hdr})
	            }
	            var valid = this.checkHeaders(check)
	            if (valid == true) {
	                return headers
	            } else {
	                this.errorDialog = true
	            }
	        },
	        genTemplate() {
	            var wb = XLSX.utils.book_new()
	            wb.Props = {
	                Title: "Template Import Guru",
	                Author: "noname"
	            }

	            wb.SheetNames.push("Sheet 1")

	            var ws_data =[['nip', 'nama', 'email', 'jk', 'alamat', 'hp', 'role', 'status']]

	            var ws = XLSX.utils.aoa_to_sheet(ws_data)
	            wb.Sheets["Sheet 1"] = ws

	            var wbout = XLSX.write(wb, {bookType: 'xlsx', type: 'binary'})

	            var buf = new ArrayBuffer(wbout.length)
	            var view = new Uint8Array(buf)
	            for (var i=0;i< wbout.length;i++) view[i] = wbout.charCodeAt(i) & 0xFF;

	            saveAs(new Blob([buf], {type: "application/octet-stream"}), "Format Guru.xlsx")
	        },

	        checkHeaders(headers) {
	            var v = 0
	            const valids = ['nip', 'nama', 'email', 'jk', 'alamat', 'hp', 'role', 'status']
	            if ( headers.length < valids.length) {
	                return false
	            } else {
	                
	                // for (const v of new Set([...valids, ...headers]))
	                //     if (valids.filter(e => e === v).length !== headers.filter(e => e === v).length)
	                //     return false;
	                // return true;
	                for ( var i=0;i < valids.length; i++) {
	                    if(headers.includes(valids[i])) v++
	                }
	            }
	            if (v < valids.length) {
	                return false
	            } else {
	                return true
	            }
	        },
	        checkNewGurus(e) {
	            this.checknewgurus = e
	        },
		},
		computed: {

		},
		mounted() {
			this.newguru = this.dialog.guru ? this.dialog.guru : {}
		}
	}
</script>