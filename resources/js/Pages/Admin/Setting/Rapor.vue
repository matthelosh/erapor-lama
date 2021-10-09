<template>
	<Layout :title="page_title">
		<v-container>
			<v-row>
				<v-col cols="12">
					<v-card>
						<v-toolbar dense>
							<v-toolbar-title>
								<v-icon>mdi-cogs</v-icon>
								Pengaturan Rapor
							</v-toolbar-title>
						</v-toolbar>
						<v-card-text>
							<v-col cols="12" sm="6">
								<v-card>
									<v-toolbar dense >
										<v-toolbar-title>
											<v-icon>mdi-calendar</v-icon>
											Tanggal Rapor
										</v-toolbar-title>
										<v-spacer></v-spacer>
										<v-btn fab small color="error" dark @click="addTanggal">
											<v-icon>mdi-plus</v-icon>
										</v-btn>
									</v-toolbar>
									<v-card-text>
										<v-data-table
											:items="itemTanggals"
											:headers="headerTglRapor"
											dense
											>
											<template v-slot:item.options="{item}">
												<span>
													<v-btn small rounded color="warning" @click="editTanggal(item)">
														<v-icon small>mdi-pencil</v-icon>
													</v-btn>
													<v-btn small rounded color="error" @click="deleteTanggal(item)">
														<v-icon small>mdi-delete</v-icon>
													</v-btn>
													
												</span>
											</template>
										</v-data-table>
									</v-card-text>
								</v-card>
							</v-col>
						</v-card-text>
					</v-card>
				</v-col>
			</v-row>
		</v-container>
		<v-dialog max-width="500" v-model="tanggal.show">
			<v-card>
				<v-toolbar dense>
					<v-toolbar-title>
						Form Tanggal rapor
					</v-toolbar-title>
					<v-spacer></v-spacer>
					<v-btn small fab color="error" @click="tanggal.show = false">
						<v-icon>mdi-close</v-icon>
					</v-btn>
				</v-toolbar>
				<v-card-text>
					<v-container>
						<v-row>
							<v-col cols="12">
								<v-select label="Periode" v-model="tanggal.item.periode_id" outlined dense hide-details rounded :items="periodes" item-value="kode_periode" item-text="deskripsi"></v-select>
							</v-col>
							<v-col cols="12" sm="4">
								<v-select label="Jenis Rapor" v-model="tanggal.item.jenis_rapor" outlined dense hide-details rounded :items="jenisRapor" item-text="text" item-value="value"></v-select>
							</v-col>
							<v-col cols="12" sm="8">
								<v-text-field label="Tanggal Rapor" v-model="tanggal.item.tanggal" outlined dense hide-details rounded type="date"></v-text-field>
							</v-col>
							
						</v-row>
					</v-container>
				</v-card-text>
				<v-card-actions class="d-flex justify-center">
					<v-btn color="primary" @click="simpanTanggal" rounded>Simpan</v-btn>
				</v-card-actions>
			</v-card>
		</v-dialog>
		<v-snackbar v-model="snackbar.show" :color="snackbar.color" right>
			{{ snackbar.text }}
		</v-snackbar>
	</Layout>
</template>

<script>
	import Layout from '../../../Layout/Dashboard'
	export default {
		name: 'SettingRapor',
		components: { Layout },
		props: { page: String, page_title: String },
		data: () => ({
			headerTglRapor: [
				{ text: 'Periode', value: 'periode_id' },
				{ text: 'Jenis', value: 'jenis_rapor' },
				{ text: 'Tanggal', value: 'tanggal'},
				{ text: 'Opsi', value: 'options'}
			],
			itemTanggals: [
				{ periode_id: '20211', jenis_rapor: 'PTS', tanggal_rapor: '23 Mei 2021'},
			],
			tanggal: {
				show: false,
				item: {
					periode_id: '',
					jenis_rapor: '',
					tanggal: ''
				}
			},
			jenisRapor: [
				{ value: 'pts', text: 'PTS'},
				{ value: 'pas', text: 'PAS'},
			],
			periodes: [],
			snackbar: {
				show: false,
				color: '',
				text: ''
			}
		}),
		methods: {
			getTanggal() {
				axios({
					url: '/admin/pengaturan/tanggalrapor',
					method: 'post',
				}).then( res => {
					this.itemTanggals = res.data.tanggals
				}).catch( err => {
					console.log( err.response )
				})
			}, 
			getPeriodes() {
				axios({
					method: 'post',
					url: '/admin/pengaturan/periode'
				}).then( res => {
					this.periodes = res.data.periode
				}).catch( err => {
					console.log(err.response)
				})
			},
			addTanggal() {
				this.tanggal.show = true
			},
			editTanggal(item) {
				this.tanggal = {
					show: true,
					item: item
				}
			},
			deleteTanggal(item) {
				axios({
					method: 'delete',
					url: '/admin/pengaturan/tanggalrapor/'+item.id
				}).then( res => {
					this.getTanggal()
					this.snackbar = {
						show: true,
						color: 'success',
						text: res.data.msg
					}
				}).catch( err => {
					this.snackbar =  {
						show: true,
						color: 'error',
						text: err.response.msg
					}
					console.log( err.response )
				})
			},
			simpanTanggal(){
				axios({
					method: 'post',
					url: '/admin/pengaturan/tanggalrapor/simpan',
					data: this.tanggal.item
				}).then( res => {
					this.getTanggal()
					this.snackbar = {
						show: true,
						color: 'success',
						text: res.data.msg
					}
				}).catch( err => {
					this.snackbar =  {
						show: true,
						color: 'error',
						text: err.response.msg
					}
					console.log( err.response )
				})
			}
		},
		created() {
			this.getTanggal()
			this.getPeriodes()
		}
	}
</script>