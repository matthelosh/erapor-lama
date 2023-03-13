<template>
	<div>
		<v-dialog 
			v-model="modal" 
			transition="dialog-bottom-transition"
	        fullscreen
	        hide-overlay>
			<v-card>
				<v-toolbar dense>
					<v-toolbar-title>Data Mapel Kelas {{ kelas }}</v-toolbar-title>
					<v-spacer></v-spacer>
	                <v-btn icon @click="$emit('hide')">
	                    <v-icon>mdi-close</v-icon>
	                </v-btn>
				</v-toolbar>
				<v-card-text >
					<v-container>
						<v-row class="mt-2">
							<v-col cols="12" sm="6">
								<v-card>
									<v-toolbar dense>
										Sudah Masuk
										<v-spacer></v-spacer>
										<v-btn dense color="warning" @click.stop="keluarkan" :disabled="selected.length <1">Keluarkan</Keluarkan></v-btn>
									</v-toolbar>
									<v-card-text>
										<v-list >
											<v-list-item-group v-model="selected" multiple>
												<template v-for="item in includes" >
													<v-list-item
														dense
														:key="item.kode_mapel"
														:value="item"
														active-class="deep-purple--text text--accent-4"
													>
														<template v-slot:default="{ active }">
															<v-list-item-content dense>
																<v-list-item-title v-text="item.label"></v-list-item-title>
															</v-list-item-content>
															<v-list-item-action>
																<v-checkbox
																	:input-value="active"
																	color="deep-purple accent-4"
																></v-checkbox>
															</v-list-item-action>
															<v-list-item-action>
																<v-btn text color="info" @click="kd(item)">KD</v-btn>
															</v-list-item-action>
														</template>
													</v-list-item>
												</template>
											</v-list-item-group>
										</v-list>
									</v-card-text>
								</v-card>
							</v-col>
							<v-col cols="12" sm="6">
								<v-card>
									<v-toolbar dense>
										Belum ada di Kelas {{ kelas }}
										<v-spacer></v-spacer>
										<v-btn dense color="primary" @click.stop="masukkan" :disabled="selectedX.length <1">Masukkan</v-btn>
									</v-toolbar>
									<v-card-text>
										<v-list>
											<v-list-item-group v-model="selectedX" multiple>
												<template v-for="item in excludes">
													<v-list-item
														:key="item.id"
														:value="item.id"
														active-class="deep-purple--text text--accent-4"
													>
														<template v-slot:default="{ active }">
															<v-list-item-content>
																<v-list-item-title v-text="item.label"></v-list-item-title>
															</v-list-item-content>
															<v-list-item-action>
																<v-checkbox
																	:input-value="active"
																	color="deep-purple accent-4"
																></v-checkbox>
															</v-list-item-action>
														</template>
													</v-list-item>
												</template>
											</v-list-item-group>
										</v-list>
									</v-card-text>
								</v-card>
							</v-col>
						</v-row>
					</v-container>
				</v-card-text>
			</v-card>
		</v-dialog>
		<v-dialog v-model="dialogkd" fullscreen transition="dialog-bottom-transition">
			<v-card>
				<v-toolbar dense>
					Kompetensi Dasar kelas {{dialog.kelas.label}}, Mapel {{ selectedmapel.label }} 
					<v-spacer></v-spacer>
					<v-btn icon @click="dialogkd = false"><v-icon>mdi-close</v-icon></v-btn>
				</v-toolbar>
				<v-card-text>
					<v-data-table :items="kds" :headers="kdheaders" :search="searchkd" outlined dense show-select v-model="selectedkd" item-key="id">
						<template v-slot:top >
							<v-row class="mt-3">
								<v-col cols="12" sm="2"></v-col>
								<v-col cols="12" sm="6" class="d-flex justify-end">
									<v-badge
	                                    :content="selectedkd.length"
	                                    :value="selectedkd.length"
	                                    color="success"
	                                    overlap
	                                    class="mr-3"
                                    >
                                        <v-btn @click.stop="hapusMany" dense color="error" :disabled="(selectedkd.length < 1)" rounded>
                                            <v-icon>mdi-trash-can</v-icon> 
                                            Hapus KD Terpilih
                                        </v-btn>
                                    </v-badge>
									<v-btn
										color="info"
										rounded
										class="mr-2"
										@click="formKd = { show:true, kd: {mapel_id: selectedmapel.kode_mapel, kelas_id: dialog.kelas.kode_kelas} }"
									><v-icon>mdi-plus</v-icon> Tambah KD</v-btn>
									<input type="file" ref="filekd" class="d-none" @change="onFileKdPicked">
									<v-btn
										color="primary"
										rounded
										@click="$refs.filekd.click()"
									><v-icon>mdi-microsoft-excel</v-icon> Impor KD</v-btn>
								</v-col>
								<v-col cols="12" sm="4">
									<v-text-field v-model="searchkd" prepend-inner-icon="mdi-magnify" label="Cari ..." clearable outlined dense rounded></v-text-field>
								</v-col>
							</v-row>
						</template>
						<template v-slot:item.kode="{item}">
							<span>
								<v-btn icon rounded color="info" class="text-left" @click="edit(item)">{{ item.kode_kd }}</v-btn>
							</span>
						</template>
					</v-data-table>
				</v-card-text>
			</v-card>
		</v-dialog>
		<impor-kd v-if="dialogImporKd.show" :dialog="dialogImporKd" @hide="closeImpor" @successImpor="onSuccessImpor"></impor-kd>
		<form-kd v-if="formKd.show" :dialog="formKd" @hide="hideFormKd"></form-kd>
		<confirm-dialog ref="confirm"></confirm-dialog>
		<v-snackbar v-model="snackbar.show" :color="snackbar.color" bottom right>{{ snackbar.text }}</v-snackbar>
	</div>
</template>

<script>
	import ConfirmDialog from './ConfirmDialog'
	import FormKd from './FormKd'
	import ImporKd from './ImporKd'
	export default {
		name: 'KelasMapel',
		components: { ImporKd, FormKd, ConfirmDialog },
		props: { dialog:Object },
		data: ()=>({
			searchkd: '',
			dialogkd: false,
			selectedmapel: '',
			kds: [],
			kdheaders: [
				{ value: 'index', text: 'No.'},
				{ value: 'kode', text: 'Kode KD'},
				{ value: 'agama', text: 'Agama'},
				{ value: 'kode_kd', text: 'Kode KD'},
				{ value: 'kelas_id', text: 'Kelas'},
				{ value: 'teks', text: 'Teks KD' },
			],
			header: [

			],
			mapels: [],
			includes:[
				
			],
			dialogImporKd: { show: false },
			excludes:[],
			selected:[],
			selectedX:[],
			snackbar: { show: false },
			fileKd: null,
			selectedkd: [],
			formKd: {
				show: false
			}
		}),
		methods: {
			hideFormKd(){
				this.kd(this.selectedmapel)
				this.formKd ={ show: false }
			},
			async hapusMany() {
				this.$refs.confirm.open("Hapus Data", "Yakin Menghapus KD tersebut ?")
                .then((lanjut) => {
                	if ( lanjut ) {
						var ids = []
						this.selectedkd.forEach(kd => {
							ids.push(kd.id)
						})
						axios({
							method: 'delete',
							url: '/dashboard/kd',
							data: {ids: ids}
						}).then( res => {
							this.snackbar = { show: true, color: 'success', text: 'Data KD dihapus' }
							this.selectedkd = []
							this.kd(this.selectedmapel)
						}).err( err => {
							this.snackbar = { show: true, color: 'error', text: err.response }
						})
					} else {
                        return false
                    }
				}).catch( err => {
                    this.snackbar = { show: true, color: 'error', text: err.response.data.msg }
                })
			},
			edit(item) {
				this.formKd = { show: true, kd: item}
			},
			onSuccessImpor() {
				this.kd(this.selectedmapel.kode_mapel)
				this.closeImpor()
			},
			closeImpor(){
				this.dialogImporKd = { show: false, file: null }
				this.$refs.filekd.value = null
			},
			onFileKdPicked(e){
				this.dialogImporKd = { show: true, mapel: this.selectedmapel.kode_mapel, kelas: this.dialog.kelas.kode_kelas, file: e.target.files[0]}
			},
			kd(mapel){
				// alert(this.dialog.kelas.kode_kelas+', '+ mapel.kode_mapel)
				// alert(mapel.kode_mapel)
				this.selectedmapel = mapel
				this.dialogkd = true
				var kelas = this.dialog.kelas.kode_kelas, mapel = mapel.kode_mapel
				axios({
					method: 'post',
					url: '/dashboard/kd?kelas='+kelas+'&mapel='+mapel
				}).then( response => {
					let kds = response.data.kds
					let datas = []
					kds.forEach((kd,index) => {
						kd.index = index+1
						datas.push(kd)
					})
					this.kds = datas
				}).catch( err => {
					console.log( err )
				})
			},
			init(kelas) {
				// Ambil data mapel kelas
				axios({
					method: 'post',
					url: '/dashboard/kelas/'+kelas.id
				}).then( response => {
					let mapels = response.data.mapels
					// let ins = []
					// if (mapels.length >  0 ) {
					// 	mapels.forEach(mapel => {
					// 		ins.push(mapel)
					// 	})
					// }
					this.includes = mapels
				}).catch( err => {
					this.snackbar = { show: true, color: 'error', text: err.response.data.msg }
				})

				// Ambil data semua mapel
				axios({
					method: 'post',
					url: '/dashboard/mapel/tanpakelas?kelas_id='+kelas.id
				}).then( response => {
					let mapels = response.data.mapels
					// mapels.forEach( mapel => {
					// 	this.excludes.push(mapel)
					// })
					this.excludes = mapels
				}).catch( err => {
					this.snackbar = { show: true, color: 'error', text: err.response.data.msg }
				})
			},
			masukkan() {
				let mapels = this.selectedX
				// console.log(mapels)
				axios({
					method: 'post',
					url: '/dashboard/kelas/'+this.dialog.kelas.id+'/attach',
					data: {mapels: this.selectedX}
				}).then( response => {
					this.init(this.dialog.kelas)
					this.snackbar = { show: true, color: 'info', text: response.data.msg }
				}).catch( err => {
					this.snackbar = { show: true, color: 'error', text: err.response.data.msg }
					// console.log(err)
				})
			},
			keluarkan() {

			}
		},
		computed: {
			modal: {
				get() {
					return this.dialog.show
				},
				set(val) {
					this.$emit('hide', val)
				}
			},
			kelas() {
				return this.dialog.kelas ? this.dialog.kelas.tingkat: ''
			}
		},
		// watch: {
		// 	dialog(modal){
		// 		if (modal.show) {
		// 			this.init(modal.kelas)
		// 		}
		// 	}
		// }
		mounted() {
			this.init(this.dialog.kelas)
			// console.log(this.dialog)
		}
	}
</script>