<template>
	<Layout :title="page_title">
		<v-container>
			<v-row>
				<v-col cols="12">
					<v-card>
						<v-toolbar dense>
							<v-icon>mdi-calendar-check</v-icon>
							<v-toolbar-title>Jadwal Mengajar</v-toolbar-title>
							<v-spacer></v-spacer>
							<v-btn rounded color="primary" :outlined="dark" class="mr-3"><v-icon>mdi-plus</v-icon> Tambah</v-btn>
							<v-btn rounded color="info" :outlined="dark"><v-icon>mdi-printer</v-icon> Cetak</v-btn>
						</v-toolbar>
						<v-card-text>
							<v-data-table :items="jadwals" :headers="headers">

							</v-data-table>
						</v-card-text>
					</v-card>
				</v-col>
			</v-row>
		</v-container>
	</Layout>
</template>

<script>
	import Layout from '../../Layout/Dashboard'
	export default {
		name: 'Jadwal',
		props: { page: String, page_title: String },
		components: { Layout },
		data: () => ({
			jadwals: [],
			headers: [
				{ text: 'Periode', value: 'periode_id' },
				{ text: 'Rombel', value: 'rombel.label' },
				{ text: 'Tematik', value: 'tematik' },
				{ text: 'Mapel', value: 'mapel_id' },
				{ text: 'Hari', value: 'hari' },
				{ text: 'Jam Ke', value: 'jamke' }
			],
		}),
		computed: {
			dark() {
				return this.$vuetify.theme.dark
			}
		},
		methods: {
			getJadwals() {
				axios({
					method: 'post',
					url: '/wali/jadwal',
					data: {
						periode: this.$page.props.periode,
						guru: this.$page.props.user.nip,
					}
				}).then( res => {
					this.jadwals = res.data.jadwals
				})
			}
		},
		mounted() {
			this.getJadwals()
		}
	}
</script>