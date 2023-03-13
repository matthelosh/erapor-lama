<template>
	<div>
		<v-dialog v-model="dialog.show"  max-width="600">
			<v-card >
				<v-toolbar dense>
					<v-toolbar-title>Form KD {{ title }}</v-toolbar-title>
					<v-spacer></v-spacer>
					<v-btn fab small color="error" @click="$emit('hide')"><v-icon>mdi-close</v-icon></v-btn>
				</v-toolbar>
				<v-card-text>
					<v-container>
						<v-form ref="form">
							<v-row>
								<v-col cols="4">
									<v-text-field label="Kode KD" v-model="kd.kode_kd" rounded outlined dense  hide-details :rules="rules" required ></v-text-field>
								</v-col>
								<v-col cols="8">
									<v-text-field label="Agama" v-model="kd.agama" rounded outlined dense hide-details :rules="rules" required ></v-text-field>
								</v-col>
								<v-col cols="12">
									<v-textarea label="Teks KD" v-model="kd.teks" rounded outlined rows="4" dense hide-details :rules="rules" required ></v-textarea>
								</v-col>
								<v-col cols="12" class="d-flex justify-center">
									<v-btn rounded @click="simpan" color="primary">Simpan</v-btn>
								</v-col>
							</v-row>
						</v-form>
					</v-container>
				</v-card-text>
			</v-card>
		</v-dialog>
		<v-snackbar v-if="snackbar.show" v-model="snackbar.show" :color="snackbar.color" right>{{ snackbar.text }}</v-snackbar>
	</div>
</template>

<script>
	export default {
		name: 'FormKd',
		props: { dialog: Object },
		data: () => ({
			kd: {
			},
			rules: [v => !!v || 'Harus Diisi'],
			// defaultKd: {
			// 	kode_kd: '',
			// 	agama: '',
			// 	teks: '',
			// 	mapel_id: this.dialog.mapel.kode_mapel,
			// 	kelas: this.dialog.kelas
			// },
			snackbar: { show: false }
		}),
		methods: {
			simpan() {
				var valid = this.$refs.form.validate()
				if ( valid ) {
					let data = this.kd
					// data.mapel_id = this.dialog.mapel.kode_mapel
					// data.kelas_id = this.dialog.mapel.kelas
					axios({
						method: 'post',
						url: '/dashboard/kd/store',
						data: {kd: this.kd}
					}).then( res => {
						this.snackbar = { show: true, color: 'success', text: 'Data KD disimpan' }
						this.$emit('hide')
					}).catch( err => {
						this.snackbar = { show: true, color: 'error', text: err.response }
					})
				} else {
					this.snackbar = { show: true, color: 'red', text: 'Pastikan semua terisi.' }
				}
			},
			init() {
				this.kd = this.dialog.kd
			}
		},
		computed: {
			title() {
				return (this.dialog.kd.kode_kd && (this.dialog.kd.mapel_id == 'pabp')) ? (this.dialog.kd.mapel_id + ' Agama ' + this.dialog.kd.agama + ' Kelas ' + this.dialog.kd.kelas_id ) : this.dialog.kd ? (this.dialog.kd.mapel_id + ' Kelas ' + this.dialog.kd.kelas_id ) : 'Baru'
			}
		},
		mounted() {
			this.init()
		}
	}
</script>