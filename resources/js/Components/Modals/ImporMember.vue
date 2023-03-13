<template>
	<div>
		<v-dialog
			v-model="dialog.show"
		>
			<v-card>
				<v-toolbar 
					dense
					color="primary"
					dark
					>
					<v-toolbar-title>Data Calon Anggota Kelas {{ dialog.rombel.label }}</v-toolbar-title>
					<v-spacer></v-spacer>
					<v-btn :disabled="!btnSimpan" rounded color="accent" :loading="menyimpan" @click="simpan">Simpan</v-btn>
					<v-btn  fab small color="error" class="ml-3	" @click="$emit('hide')"><v-icon>mdi-close</v-icon></v-btn>
				</v-toolbar>
				<v-card-text>
					<v-data-table
						:items="newmembers"
						:headers="headers"
						no-data-text="Pastikan minimal ada kolom nisn dan nama"
					>
						
					</v-data-table>
				</v-card-text>
			</v-card>
		</v-dialog>
		<v-snackbar  v-if="snackbar.show" v-model="snackbar.show" :color="snackbar.color">
			{{ snackbar.text }}
		</v-snackbar>
	</div>
</template>

<script>
	import XLSX from 'xlsx'
	export default {
		name: 'ImporMember',
		props: { dialog: Object },
		data: () => ({
			snackbar: {show: false},
			newmembers: [],
			headers: [
				{ text: 'NISN', value: 'nisn' },
				{ text: 'Nama', value: 'nama' },
			],
			btnSimpan: false,
			menyimpan: false

		}),
		methods: {
			simpan() {
				this.menyimpan = true
				axios({
					method: 'post',
					url: '/dashboard/rombel/masukkan?id='+this.dialog.rombel.id +'&mode=impor',
					data: {data:this.newmembers}
				}).then( res => {
					this.menyimpan = false
					this.$emit('hide')
				}).catch( err => {
					console.log(err.response)
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

                    this.newmembers = lists
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
                    if( hdr == 'nama' || hdr == 'nisn') {
	                    headers.push({ text:hdr, value:hdr })
	                }
                }
                var valid = this.checkHeaders(check)
                if ( valid == true ) {
                    this.btnSimpan = true
                    return headers

                } else {
                	var defaultHeaders = [
                		{ text: 'nisn', value: 'nisn'},
                		{ text: 'nama', value: 'nama'},
                	]
                	this.newmembers = []
                	return defaultHeaders
                    this.snackbar = {
                    	show: true,
                    	color: 'error',
                    	text: 'Kolom tidak sesuai. Pastikan file memiliki minmal kolom nisn dan nama'
                    }
                    // this.$emit('hide')
                }
            },
            checkHeaders ( headers ) {
                var v = 0
                const valids = ['nisn', 'nama'];
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
		mounted() {
			this.checkData()
		}
	}
</script>