<template>
	<Layout :title="page_title">
		<v-container>
			<v-row>
				<v-col cols="12">
					<v-card>
						<v-toolbar dense>
							<v-toolbar-title><v-icon>mdi-calendar</v-icon> Pekan Efektif</v-toolbar-title>
							<v-spacer></v-spacer>
							<v-btn rounded :outlined="dark" color="primary" @click="baru"><v-icon>mdi-plus</v-icon> Tambah</v-btn>
						</v-toolbar>
						<v-card-text>
							<v-data-table
								:items="pekans"
								:headers="headers"
								dense
								@click:row="edit"
							>
								<template v-slot:item.bulan="{ item }">
									<span>{{ textBulans[item.bulan-1] }}</span>
								</template>
							</v-data-table>
						</v-card-text>
					</v-card>
				</v-col>
			</v-row>
		</v-container>
		<v-dialog v-if="dialog.show" v-model="dialog.show" max-width="600">
			<v-overlay v-model="loading">
				<v-progress-circular
		            indeterminate
		            size="64"
		        ></v-progress-circular>
			</v-overlay>
			<v-card>
				<v-toolbar dense>
					<v-toolbar-title>Form Pekan Efektif</v-toolbar-title>
					<v-spacer></v-spacer>
					<v-btn small fab color="error" @click="dialog.show=false"><v-icon>mdi-close</v-icon></v-btn>
				</v-toolbar>
				<v-card-text>
					<v-container>
						<v-row>
							<v-col cols="6">
								<v-select v-model="pekan.periode_id" dense outlined rounded hide-details label="Periode" :items="periodes" item-value="value" item-text="text" ></v-select>
							</v-col>
							<v-col cols="6">
								<v-select v-model="pekan.bulan" dense outlined rounded hide-details label="Bulan" :items="bulans" item-value="value" item-text="text" :disabled="bulans.length < 1"></v-select>
							</v-col>
							<v-col cols="6">
								<v-select v-model="pekan.hari" dense outlined rounded hide-details label="Hari" :items="haris" item-value="value" item-text="text"  :disabled="!pekan.bulan"></v-select>
							</v-col>
							<v-col cols="6">
								<v-text-field v-model="pekan.jumlah" dense outlined rounded hide-details label="Jumlah Hari" :disabled="!pekan.bulan" type="number"></v-text-field>
							</v-col>
							<v-col cols="12" class="text-center">
								<v-btn rounded :outlined="dark" color="primary" @click="simpan">Simpan</v-btn>
							</v-col>
						</v-row>
					</v-container>
				</v-card-text>
			</v-card>
		</v-dialog>
	</Layout>
</template>

<script>
	import Layout from '../../Layout/Dashboard'
	export default {
		name: 'Pekan',
		props: { page: String, page_title: String },
		components: { Layout },
		data: () => ({
			dialog: { show: false },
			pekan: {},
			periodes: [],
			bulans: [],
			textBulans: ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'],
			haris: [
				{ text: 'Senin', value: 'senin' },
				{ text: 'Selasa', value: 'selasa' },
				{ text: 'Rabu', value: 'rabu' },
				{ text: 'Kamis', value: 'kamis' },
				{ text: 'Jumat', value: 'jumat' },
				{ text: 'Sabtu', value: 'sabtu' },
			],
			loading: false,
			pekans: [],
			headers: [
				{ text: 'No', value: 'no' },
				{ text: 'Periode', value: 'periode_id' },
				{ text: 'Bulan', value: 'bulan' },
				{ text: 'Hari', value: 'hari' },
				{ text: 'Jumlah', value: 'jumlah' },
			]
		}),
		methods: {
			simpan() {
				this.loading = true
				axios({
					method: 'post',
					url: '/dashboard/pe/store',
					data: this.pekan
				}).then( res => {
					this.getPe()
					this.loading = false
				}).catch( err => {

				})
			},
			edit(item) {

				// this.setBulans()
				this.pekan = item
				this.dialog.show = true
			},
			baru() {
				this.dialog.show = true
			},
			getPe() {
				axios({
					method: 'post',
					url: '/dashboard/pe',
					data: { periode: this.$page.props.periode}
				}).then( res => {
					var pekans = []
					res.data.pekans.forEach( (item,index) => {
						item.no = index+1
						pekans.push(item)
					})
					this.pekans = pekans
				}).catch( err => {

				})
			},
			setBulans() {
				var semester = this.pekan.periode_id ? this.pekan.periode_id.substr(-1) : this.$page.props.periode.substr(-1)
				var datas = []
				var i = semester == '1' ? 6 : 0
				for (i; i < this.textBulans.length; i++) {
					datas.push({ text: this.textBulans[i], value: i+1})
				}
				this.bulans = datas
			},
			getPeriodes() {
				axios({
					method: 'post',
					url: '/dashboard/periode'
				}).then( res => {
					var periodes = []
					res.data.periode.forEach( item => {
						periodes.push({ text: item.deskripsi, value: item.kode_periode})
					})
					this.periodes = periodes
				})
			}
		},
		computed: {
			dark() {
				return this.$vuetify.theme.dark
			}
		},
		mounted() {
			this.getPeriodes()
			this.setBulans()
			this.getPe()
		}
	}
</script>