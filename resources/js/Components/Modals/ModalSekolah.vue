<template>
	<div>
		<v-dialog v-model="dialog.show" max-width="800">
			<v-card>
				<v-toolbar dense>
					<v-toolbar-title>Edit Data Sekolah</v-toolbar-title>
				</v-toolbar>
				<v-card-text>
					<v-form @submit.stop="simpan">
						<v-container>
							<v-row>
								<v-col cols="12" sm="8">
										<v-container>
											<v-row>
												<v-col cols="12" sm="5">
													<v-text-field v-model="sekolah.npsn" label="NPSN" outlined dense hide-details></v-text-field>
												</v-col>
												<v-col cols="12" sm="7">
													<v-text-field v-model="sekolah.nama_sekolah" label="Nama Sekolah" outlined dense hide-details></v-text-field>
												</v-col>
												<v-col cols="12" sm="12">
													<v-text-field v-model="sekolah.alamat" label="Alamat" outlined dense hide-details></v-text-field>
												</v-col>
												<v-col cols="12" sm="6">
													<v-text-field v-model="sekolah.desa" label="Desa/Kelurahan" outlined dense hide-details></v-text-field>
												</v-col>
												<v-col cols="12" sm="6">
													<v-text-field v-model="sekolah.kec" label="Kecamatan" outlined dense hide-details></v-text-field>
												</v-col>
												<v-col cols="12" sm="6">
													<v-text-field v-model="sekolah.kab" label="Kabupaten/Kota" outlined dense hide-details></v-text-field>
												</v-col>
												<v-col cols="12" sm="6">
													<v-text-field v-model="sekolah.prov" label="Provinsi" outlined dense hide-details></v-text-field>
												</v-col>
												<v-col cols="12" sm="4">
													<v-text-field v-model="sekolah.kode_pos" label="Kode Pos" outlined dense hide-details></v-text-field>
												</v-col>
												<v-col cols="12" sm="6">
													<v-text-field v-model="sekolah.telp" label="Telpon/HP" outlined dense hide-details></v-text-field>
												</v-col>
												<v-col cols="12" sm="6">
													<v-text-field v-model="sekolah.email" label="Email" type="email" outlined dense hide-details></v-text-field>
												</v-col>
												<v-col cols="12" sm="6">
													<v-text-field v-model="sekolah.website" label="Website" outlined dense hide-details></v-text-field>
												</v-col>
											</v-row>
										</v-container>
								</v-col>
								<v-col cols="12" sm="4">
									<v-container>
										<v-row>
											<v-col cols="12" class="d-flex justify-center">
												<v-avatar size="80" class="elevation-5">
													<img :src="'/storage/img/users/'+sekolah.ks+'.jpg'" onerror="this.error=false;this.src='/img/siswi-avatar.png'">
												</v-avatar>
											</v-col>
											<v-col cols="12">
												<v-select :items="users" item-key="value" item-text="text" v-model="sekolah.ks" label="Kepala Sekolah" dense outlined hide-details></v-select>
											</v-col>
											<v-col cols="12">
												<v-col cols="12" class="d-flex justify-center">
												<v-avatar size="80" class="elevation-5">
													<img :src="'/storage/img/users/'+sekolah.operator+'.jpg'" onerror="this.error=false;this.src='/img/siswi-avatar.png'">
												</v-avatar>
											</v-col>
											</v-col>
											<v-col cols="12">
												<v-select v-model="sekolah.operator" :items="users" item-key="value" item-text="text" label="Operator" dense outlined hide-details></v-select>
											</v-col>
											
										</v-row>
									</v-container>
								</v-col>
								<v-row cols="12" class="d-flex justify-center">
									<v-btn type="submit" color="primary">Simpan</v-btn>
								</v-row>
							</v-row>
						</v-container>
					</v-form>
				</v-card-text>
			</v-card>
		</v-dialog>
		<v-snackbar v-model="snackbar.show" :color="snackbar.color" bottom right multi-line>
			{{ snackbar.text }}
		</v-snackbar>
	</div>
</template>

<script>
	export default {
		name: 'ModalSekolah',
		props: { dialog: Object },
		data: () => ({
			sekolah: {},
			users: [],
			snackbar: { show: false }
		}),
		methods: {
			simpan(e) {
				e.preventDefault()
				let data = this.sekolah
				
				axios({
					method: 'post',
					url: '/dashboard/sekolah/store',
					data: data
				}).then( response => {
					this.snackbar = { show: true, color: 'success', text: response.data.msg }
					// this.$emit('hide')
				}).catch( err => {
					this.snackbar = { show: true, color: 'error', text: err.response.data.msg }
				})
			},
			getUsers() {
				axios({
					method: 'post',
					url: '/dashboard/guru'
				}).then( response => {
					var users = []
					response.data.gurus.forEach(user => {
						users.push({ text: user.name, value: user.nip })
					})
					this.users = users
				})
			}
		},
		computed: {},
		mounted() {
			this.getUsers()
			this.sekolah = this.dialog.sekolah
		}
	}
</script>