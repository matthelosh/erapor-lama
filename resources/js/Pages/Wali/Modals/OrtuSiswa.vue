<template>
	<div>
		<v-dialog 
			v-model="show"
	        transition="dialog-bottom-transition"
	        fullscreen
	        hide-overlay
		>
			<v-card>
				<v-toolbar dense>
					<v-toolbar-title>
                    <v-icon>mdi-account-child</v-icon>
                    	Orang Tua {{ modal.siswa.nama }}
	                </v-toolbar-title>
	                <v-spacer></v-spacer>
	                <v-btn icon @click="$emit('hide')">
	                    <v-icon>mdi-close</v-icon>
	                </v-btn>
	            </v-toolbar>
	            <v-card-text>
	            	<v-container>
	            		<v-row>
	            			<v-col cols="6">
	            				<v-card>
	            					<v-card-title>Identitas Siswa</v-card-title>
	            					<v-card-text>
	            						<table>
	            							<tr v-for="(value,key) in modal.siswa">
	            								<td>{{ key }}</td>
	            								<td>:{{ value }}</td>
	            							</tr>
	            						</table>
	            					</v-card-text>
	            				</v-card>

	            			</v-col>
	            			<v-col cols="6">
	            				<v-card>
	            					<v-card-title>Identitas Orang Tua</v-card-title>
	            					<v-card-text>
	            						<v-container>
	            							<v-row>
	            								<v-col cols="12" sm="8">
	            									<v-text-field v-model="ortu.nik_ayah" label="NIK Ayah (boleh kosong)" rounded outlined dense hide-details></v-text-field>
	            								</v-col>
	            								<!-- <v-col cols="12">
	            									Data Ayah:
	            								</v-col> -->
	            								<v-col cols="12" sm="6">
	            									<v-text-field v-model="ortu.nama_ayah" label="Nama Ayah" rounded outlined dense hide-details></v-text-field>
	            								</v-col>
	            								<v-col cols="12" sm="6">
	            									<v-text-field v-model="ortu.job_ayah" label="Pekerjaan Ayah" rounded outlined dense hide-details></v-text-field>
	            								</v-col>
	            								<!-- <v-col cols="12">
	            									Data Ibu:
	            								</v-col> -->
	            								<v-col cols="12" sm="6">
	            									<v-text-field v-model="ortu.nama_ibu" label="Nama Ibu" rounded outlined dense hide-details></v-text-field>
	            								</v-col>
	            								<v-col cols="12" sm="6">
	            									<v-text-field v-model="ortu.job_ibu" label="Pekerjaan Ibu" rounded outlined dense hide-details></v-text-field>
	            								</v-col>
	            								<!-- <v-col cols="12">
	            									Data Wali Murid:
	            								</v-col> -->
	            								<v-col cols="12" sm="6">
	            									<v-text-field v-model="ortu.nama_wali" label="Nama Wali (boleh kosong)" rounded outlined dense hide-details></v-text-field>
	            								</v-col>
	            								<v-col cols="12" sm="6">
	            									<v-text-field v-model="ortu.job_wali" label="Pekerjaan Wali (boleh kosong)" rounded outlined dense hide-details></v-text-field>
	            								</v-col>
	            								<v-col cols="12">
	            									<v-textarea v-model="ortu.alamat_wali" label="Alamat Wali (boleh kosong)" rows="2" rounded outlined dense hide-details></v-textarea>
	            								</v-col>
	            								<v-col cols="12" class="d-flex justify-center">
	            									<v-btn color="primary" @click="simpan" rounded>Simpan</v-btn>
	            								</v-col>
	            							</v-row>
	            						</v-container>
	            					</v-card-text>
	            				</v-card>
	            			</v-col>
	            		</v-row>
	            	</v-container>
	            </v-card-text>
			</v-card>
		</v-dialog>
	</div>
</template>

<script>
export default {
	name: 'OrtuSiswa',
	props: {
		modal: Object
	},
	data: () => ({
		ortu: {
			nik_ayah: '',
			nama_ayah: '',
			job_ayah: '',
			nama_ibu: '',
			job_ibu: '',
			nama_wali: '',
			job_wali: '',
			alamat_wali: ''
		},
		ortu_field: {
			nik_ayah: '-',
			nama_ayah: '',
			job_ayah: '',
			nama_ibu: '',
			job_ibu: '',
			nama_wali: '-',
			job_wali: '-',
			alamat_wali: '-'
		}
		
	}),
	methods: {
		simpan() {
			axios({
				method: 'post',
				url: '/admin/ortu/simpan',
				data: {
					siswa: this.modal.siswa.nisn,
					ortu: this.ortu
				}
			}).then( res => {
				this.getOrtu(res.data.ortu_id)
			}).catch( err => {
				console.log( err .response )
			})
		},
		getOrtu(ortu_id = null) {
			axios({
				method: 'post',
				url: '/admin/ortu/'+(ortu_id ? ortu_id : this.modal.siswa.ortu_id)
			}).then( res => {
				this.ortu = res.data.ortu ? res.data.ortu : Object.assign(this.ortu, this.ortu_field)
			}).catch( err => {
				console.log(err.response)
			})
		}
	},
	computed: {
		show: {
			get() {
				return this.modal.show
			},
			set(val) {
				return this.$emit('hide', val)
			}
		},
		detail_siswa() {
			var siswa = this.modal.siswa

			var detil = []

			
			return detil
		}
	},
	created(){
		this.getOrtu()
	}
}
</script>