<template>
	<div>
		<v-dialog
			v-model="dialog.show"
	  		fullscreen
	  		transition="dialog-bottom-transition"
	  		
		>
			<v-card>
				<v-toolbar
					dense
					color="primary"
					dark
				>
					<v-toolbar-title>Impor KD Mapel {{ dialog.mapel }} Kelas {{ dialog.kelas }}</v-toolbar-title>
					<v-spacer></v-spacer>
					<v-btn color="accent" @click="simpan" rounded :disabled="!checked">Simpan</v-btn>
					<v-btn fab small class="ml-3" color="error" @click="close"><v-icon>mdi-close</v-icon></v-btn>
				</v-toolbar>
				<v-card-text>
					<v-container>
						<v-row>
							<v-col cols="12">
								<v-data-table
								:items="kds"
								:headers="headers"
								class="mt-5 elevation-2"
								item-key="kode_kd"
								show-select
								v-model="selectedkd"
								>
									<template v-slot:top>
										<v-container>
											<v-row>
												<v-col cols="6"></v-col>
												<v-col cols="6" class="d-flex justify-end">
													<v-badge
		                                            :content="selectedkd.length"
		                                            :value="selectedkd.length"
		                                            color="success"
		                                            overlap
		                                            class="mr-3"
			                                        >
			                                            <v-btn @click.stop="buang" dense color="error" :disabled="(selectedkd.length < 1)" rounded>
			                                                <v-icon>mdi-trash-can</v-icon> 
			                                                Buang
			                                            </v-btn>
			                                        </v-badge>
													<v-checkbox v-model="checked" label="Data KD Sudah Benar" dense hide-details :disabled="kds.length < 1" class="align-center justify-center"></v-checkbox>
												</v-col>
											</v-row>
										</v-container>
									</template>
								</v-data-table>
								<v-alert v-if="alert.show" :value="alert.show" color="error lighten-2" dark border="left" colored-border elevation="2">{{ alert.text }}</v-alert>
							</v-col>
						</v-row>
					</v-container>
					
					
				</v-card-text>
			</v-card>
		</v-dialog>
		<v-snackbar
			v-if="snackbar.show"
			v-model="snackbar.show"
			:color="snackbar.color"
			timeout="1000"
		>{{ snackbar.text }}</v-snackbar>
	</div>
</template>

<script>
	import XLSX from 'xlsx'
	export default {
		name: 'ImporKd',
		props: { dialog: Object},
		data: () => ({
			kds: [],
			headers: [],
			snackbar: { show: false },
			alert: { show: false },
			selectedkd: [],
			checked: false,
		}),
		methods: {
			buang(){
				var kodes = []
				this.selectedkd.forEach((item,index,cur) => {
					kodes.push(item.kode_kd)
					this.kds.splice(this.kds.findIndex(obj => obj['kode_kd'] === item.kode_kd), 1) 
					// this.selectedkd.splice(this.selectedkd.findIndex(cur, 1))
				})

				kodes.forEach((item,index) => {
					this.selectedkd.splice(this.selectedkd.findIndex(kd => kd['kode_kd'] === item), 1) 
				})
				

			},
			simpan() {
				axios({
					method: 'post',
					url: '/dashboard/kd/impor',
					data: {
						kelas: this.dialog.kelas,
						mapel: this.dialog.mapel,
						kds: this.kds
					}
				}).then( res => {
					this.snackbar = { show: true, color: 'success', 'msg': 'Data KD diimpor'}
					this.$emit('successImpor')
				}).catch( err => {
					this.snackbar = { show: true, color: 'error', 'msg' : err.response.msg}
				})
			},
			close() {
				this.$emit('hide')
			},
			checkData() {
                var files = this.dialog.file;
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

                    // this.kds = lists
                    this.kds = lists.reduce((acu,cur) => {
                    	if ( !acu.find(obj => obj['kode_kd'] === cur['kode_kd'])) {
                    		acu.push(cur)
                    	}
                    	return acu
                    }, [])
                    const a = workbook.Sheets[workbook.SheetNames[0]]
                    const headers = this.getHeaders(a)
                    this.headers = headers
                }
                reader.readAsBinaryString(this.dialog.file)
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
                		{ text: 'Kode KD', value: 'kode_kd'},
                		{ text: 'Teks KD', value: 'teks'},
                		{ text: 'Agama', value: 'agama'},
                	]
                	this.kds = []
                	
                    this.snackbar = {
                    	show: true,
                    	color: 'error',
                    	text: 'Kolom tidak sesuai. Pastikan ada kolom kode_kd, teks dan agama jika PABP'
                    }

                    this.alert = { show: true, text: 'Pastikan kolom seperti pada tabel di atas.' }

                    return defaultHeaders
                    // this.$emit('hide')
                }
            },
            checkHeaders ( headers ) {
                var v = 0
                const valids = ['kode_kd', 'teks', 'agama'];
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
			// console.log(this.dialog)
			this.checkData()
		}
	}
</script>

<style scoped>
	
	.v-input--selection-controls {
		margin:  0!important;
		padding:  0!important;
	}
</style>