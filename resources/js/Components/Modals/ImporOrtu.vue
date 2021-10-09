<template>
	<v-dialog v-model="dialog.show" fullscreen transition="dialog-bottom-transition">
		<v-card>
 			<v-toolbar
	 			dark
  				dense
  				color="primary"
			>
				<v-toolbar-title>Impor Orang Tua Siswa</v-toolbar-title>
				<v-spacer></v-spacer>
				
				<v-btn rounded color="accent" class="mx-3" :disabled="!checked" @click="simpan">Simpan</v-btn>
				<v-btn fab color="error" small @click="$emit('hide')"   class="ml-1"><v-icon>mdi-close</v-icon></v-btn>
			</v-toolbar>
			<v-card-text>
				<v-data-table
					:items="ortus"
					:headers="headers"
					:search="search"
					no-data-text="Kolom tidak sesuai atau data kosong."
					dense
				>
					<template v-slot:top>
						<v-container>
							<v-row>
								<v-col cols="4"><v-checkbox v-model="checked" label="Data Nilai Siswa Sudah Benar"  :disabled="(ortus.length < 1 ) ? true:false"></v-checkbox></v-col>
								<v-col cols="4">
								</v-col>
								<v-col cols="4">
									<v-text-field v-model="search" dense hide-details rounded outlined append-icon="mdi-magnify" label="Cari"></v-text-field>
								</v-col>
							</v-row>
						</v-container>
					</template>
				</v-data-table>
			</v-card-text>
		</v-card>

	</v-dialog>
</template>

<script>
	import XLSX from 'xlsx'
	export default {
		name: 'ImporOrtu',
		props: { dialog: Object },
		data: () => ({
			ortus: [],
			headers: [],
			search: '',
			checked: false
		}),
		methods: {
			simpan() {
				axios({
					method: 'post',
					url: '/dashboard/ortu/impor',
					data: { ortus: this.ortus }
				}).then( res => {
					this.$emit('hide')
				}).catch( err => {
					console.log( err.response.msg )
				})
			},
			checkData() {
                var files = this.dialog.e.files;
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

                    this.ortus = lists
                    const a = workbook.Sheets[workbook.SheetNames[0]]
                    const headers = this.getHeaders(a)
                    this.headers = headers
                }
                reader.readAsBinaryString(this.dialog.e.target.files[0])
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
                    this.btnSimpan = true
                    return headers

                } else {
                	var defaultHeaders = [
                		{ text: 'nisn', value: 'nisn'},
                		{ text: 'nik_ayah', value: 'nik_ayah'},
                		{ text: 'nama_ayah', value: 'nama_ayah'},
                		{ text: 'job_ayah', value: 'job_ayah'},
                		{ text: 'status_ayah', value: 'status_ayah'},
                		{ text: 'nama_ibu', value: 'nama_ibu'},
                		{ text: 'job_ibu', value: 'job_ibu'},
                		{ text: 'status_ibu', value: 'status_ibu'},
                		{ text: 'nama_wali', value: 'nama_wali'},
                		{ text: 'job_wali', value: 'job_wali'},
                		{ text: 'alamat_wali', value: 'alamat_wali'},
                		
                	]
                	this.ortus = []
                	return defaultHeaders
                    this.snackbar = {
                    	show: true,
                    	color: 'error',
                    	text: 'Kolom tidak sesuai.'
                    }
                    // this.$emit('hide')
                }
            },
            checkHeaders ( headers ) {
                var v = 0
                const valids = [ 'nik_ayah','nama_ayah', 'job_ayah','status_ayah', 'nama_ibu', 'job_ibu', 'status_ibu', 'nama_wali', 'alamat_wali', 'job_wali'];
                for ( var i = 0; i < valids.length; i++ ) {
                    if ( headers.includes( valids[i])) v++
                }

                if ( v < valids.length ) {
                    return false
                } else {
                    return true
                }
            },
		},
		computed: {

		},
		mounted() {
			this.checkData()
		}
	}
</script>