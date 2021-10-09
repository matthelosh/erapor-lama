<template>
	<div>
        <div v-if="progress" class="progress d-flex justify-center align-center">
			<div>
				<img src="/img/ngopi.gif" class="kopi">
				<h3 class="text-center">Data Nilai sedang diproses. Tunggu sebentar, ya ..</h3>
			</div>
		</div>
		<v-dialog v-model="dialog.show" fullscreen hide-overlay>
            <v-card>
                <v-toolbar dense color="primary" dark>
                    <v-toolbar-title>
                    	<v-icon>mdi-microsoft-excel</v-icon>
                    	Impor Nilai
                    </v-toolbar-title>
                    <v-spacer></v-spacer>
                    <v-btn rounded color="accent" @click="unduhFormat" light><v-icon>mdi-microsoft-excel</v-icon> Unduh Format</v-btn>
                    <v-btn icon small dense @click="close" color="error"><v-icon>mdi-close</v-icon></v-btn>
                </v-toolbar>
                <v-card-text>
                	<v-container>
                		<v-row>
                			<v-col cols="12">
                				<v-card>
                					<v-card-text>
                						<v-row>
                							<v-col cols="1">Pilih File </v-col>
                							<v-col cols="4">
                								<v-file-input 
			                                        dense 
			                                        outlined 
			                                        rounded
                                                    ref="filenilai"
			                                        prepend-icon="" 
			                                        append-icon="mdi-microsoft-excel" 
			                                        label="Pilih File" 
			                                        accept=".csv, application/vnd.ms-excel, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet"
			                                        @change="onFilePicked"
                                                    @click:clear="onFileCleared"
                                                    v-model = "fileNilai"
			                                    ></v-file-input>
                							</v-col>
                							<v-col cols="4">
                								<v-text-field outlined dense hide-details rounded v-model="search" label="Cari" append-icon="mdi-magnify"/>
                							</v-col>
                						</v-row>
                					</v-card-text>
                				</v-card>
                			</v-col>
                		</v-row>
                		<v-row>
                			<v-col cols="12">
                				<v-card>
                					<v-toolbar dense color="accent" dark>
                						<v-toolbar-title>Preview Data</v-toolbar-title>
                						<v-spacer></v-spacer>
                						<v-btn rounded color="primary" :disabled="!checkdatas" @click="simpan"><v-icon>mdi-save</v-icon> Simpan</v-btn>
                					</v-toolbar>
                					<v-card-text>
                						<v-row>
                							<v-col cols="4"></v-col>
                							<v-col cols="4"></v-col>
                							<v-col cols="4">
                								<v-checkbox v-model="checkdatas" label="Data Nilai Siswa Sudah Benar" @change="checkDatas" :disabled="(items.length < 1 ) ? true:false"></v-checkbox>
                							</v-col>
                						</v-row>
                						<v-row>
                							<v-col cols="12">
                								<v-data-table
                								:search="search"
                								:loading="progress"
			                                    :items="items"
			                                    :headers="headers"
			                                    dense
			                                    no-data-text="Data Tidak Ditemukan"
			                                    loading-text="TUnggu Sebentar. Menegecek Kolom ..."
				                                ></v-data-table>
                							</v-col>
                						</v-row>
                					</v-card-text>
                				</v-card>
                			</v-col>
                		</v-row>
                	</v-container>
                </v-card-text>
            </v-card>
        </v-dialog> 
        <v-snackbar v-model="snackbar.show" v-if="snackbar.show == true" timeout="3000" :color="snackbar.color">
        	{{ snackbar.text }}
        </v-snackbar>
	</div>
</template>

<script>
	import XLSX from 'xlsx'
    import saveAs from 'file-saver'
    import Download from '../../Plugins/Download'
	export default {
		name: 'ModalImport',
		props: { dialog: Object},
		data: () => ({
            fileNilai: null,
			search: '',
			items: [],
			headers: [],
			checkdatas: false,
			snackbar: { show: false},
			progress: false,
            progress: false
            // snackbar: { show: false }
		}),
		methods: {
            onFileCleared() {
                // alert('hi')
                this.fileNilai = null
            },
            close() {
                this.items = []
                this.headers = []
                this.$emit('hide')
            },
            unduhFormat() {
                var siswas = []
                var headers = []
                this.dialog.siswas.forEach(siswa => {
                    siswas.push({nisn: siswa.nisn, nama: siswa.nama})
                })
                var title =  'Format Import Nilai '+this.dialog.mapel_id.toUpperCase()+' '+ this.dialog.jenis.toUpperCase()+' '+this.dialog.aspek.toUpperCase()
                    
                Download.UnduhFormat(siswas,this.dialog.headers, title)
            },
			simpan(){
                this.progress = true
                var datas = []
                var i = 0
                this.items.forEach((item, index) => {
                    datas.push({nisn: item.nisn, nama: item.nama})
                    var nilais = []
                    Object.keys(item).forEach(key => {
                        if(key != 'nisn' && key != 'nama') {
                            var nilai = {}
                            this.dialog.kds.forEach(kd => {
                                if(kd.kd_id == key) {
                                    nilai = {kd_id: key, nilai: item[key], ppn: kd.ppn}   
                                }
                            })
                            
                            nilais.push(nilai)
                        }
                    })
                    datas[index].nilais =nilais
                    i++
                })

                // console.log(datas)
				axios({
					method: 'post',
					url: '/dashboard/nilai/import', // Nanti Dinamis sesuai yang diimpor
					data: {
                        siswas : datas,
                        periode_id: this.$page.props.periode,
                        semester: this.$page.props.periode_aktif.semester,
                        rombel_id: this.$page.props.rombel.kode_rombel,
                        mapel_id: this.dialog.mapel_id,
                        jenis: this.dialog.jenis,
                        aspek: this.dialog.aspek,
                    }
				}).then( res => {
					this.snackbar = {
                        show: true,
                        color: 'success',
                        text: res.data.msg
                    }
                    this.progress = false
                    this.$emit('hide')
				}).catch( err => {
					this.snackbar = {
                        show: true,
                        color: 'erro',
                        text: res.data.msg
                    }
                    this.progress = false
				})
			},
			onFilePicked(e) {
				
				if ( e == null ) {
                    this.items = []
                    this.headers = []
                    return false
                } else {
                    this.progress = true
                    var files = this.fileNilai.files;
                    var reader = new FileReader;
                    reader.onload = ev => {
                        var data = ev.target.result
                        var workbook = XLSX.read(data, {type: 'binary'})
                        var wsname = workbook.SheetNames[0]
                        const ws = XLSX.utils.sheet_to_json(workbook.Sheets[wsname])
                        const lists = []

                        for ( var i = 0; i < ws.length; i++) {
                            // lists.push(ws[i])
                            ws[i] = Object.keys(ws[i]).reduce((c,k) => (c[k.toLowerCase().trim()] = ws[i][k], c), {})
                            lists.push(ws[i])
                        }
                        this.items = lists
                        const a = workbook.Sheets[wsname]
                        const headers = this.getHeaders(a)
                        this.headers = headers
                    }
                    reader.readAsBinaryString(this.fileNilai)
                }
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
                        } 
                        else {
                            hdr = '__EMPTY' + i
                        }
                        i++
                    }
                    check.push( hdr.toLowerCase() )
                }
                var checks = this.dialog.headers.filter( value => check.includes(value))
                checks.forEach(item => {
                    headers.push({text: item.toUpperCase(), value: item})
                })
                if (headers.length > 2) {
                    this.progress = false
                    return headers
                    
                } else {
                    this.progress = false
                    this.snackbar = {
                        show: true,
                        color: 'error',
                        text: 'Kolom KD tidak sesuai. Mohon cek Ulang'
                    }
                    
                    return false
                }
            },
            // checkHeaders ( headers ) {
            //     var v = 0
            //     const valids = this.dialog.headers;
                
            //     for ( var i = 0; i < valids.length; i++ ) {
            //         if ( headers.includes( valids[i])) v++
            //     }

            //     if ( v < valids.length ) {
            //     	this.snackbar = {
            //     		show: true,
            //     		text: 'Kolom Tidak Sesuai, Cek kembali.',
            //     		color: 'error'
            //     	}
            //     	this.progress = false
            //         return false
            //     } else {
            //     	this.snackbar = {
            //     		show: true,
            //     		text: 'Kolom Sesuai',
            //     		color: 'success'
            //     	}
            //     	this.progress = false
            //         return true
            //     }
            // },
            checkDatas(){},
		},
		components: {

		}
	}
</script>

<style scoped>
    .progress {
            position: fixed;
            top: 0;
            right: 0;
            bottom: 0;
            left:  0;
            z-index:  9999999;
            background:  rgba(255,255,255,0.8);
        }

</style>
