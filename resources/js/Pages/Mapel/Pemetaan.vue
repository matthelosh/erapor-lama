<template>
	<Layout :title="page_title">
		<v-container>
			<v-card class="mt-3">
				<v-toolbar dense>
					<v-toolbar-title>Pemetaan KD</v-toolbar-title>
				</v-toolbar>
				<v-card-text>
					<v-container>
						<v-row>
							<v-col cols="12">
								<v-card>
									<v-toolbar dense>
										<v-toolbar-title>Tematik</v-toolbar-title>
									</v-toolbar>
									<v-card-text>
										<v-container>
											<table border="1" width="100%" style="border-collapse: collapse;" class="table-tematik">
												<thead>
													<tr>
														<th>No</th>
														<th>Tema</th>
														<th>Subtema</th>
														<!-- <th>Mupel</th>
														<th>KD</th> -->
													</tr>
												</thead>
												<tbody>
													<tr v-for="(tema,index) in pemetaans">
														<td>{{ index+1 }}</td>
														<td>{{ tema.kode_tema.substr(-1) }}. {{ tema.teks }}</td>
														<td>
															<tr v-for="subtema in tema.subtemas">
																<td>{{ subtema.kode_subtema.substr(-1) }}. {{ subtema.teks }}</td>
																<td>
																	<tr v-for="(mapel,index) in subtema.pemetaans">
																		<td>{{ mapel.mapel.label }}</td>
																		<td>{{ mapel.kd_id }}</td>
																		<td>{{ mapel.kd ? mapel.kd.teks : '-' }}</td>
																	</tr>
																</td>
															</tr>
														</td>
														
													</tr>
												</tbody>
											</table>
										</v-container>
									</v-card-text>
								</v-card>
							</v-col>
							<!-- <v-col cols="12">
								<v-card>
									<v-toolbar dense>
										<v-toolbar-title>Non Tematik</v-toolbar-title>
									</v-toolbar>
									<v-card-text>
										
									</v-card-text>
								</v-card>
							</v-col> -->
							
						</v-row>
					</v-container>
				</v-card-text>
			</v-card>
		</v-container>
	</Layout>
</template>

<script>
	import Layout from '../../Layout/Dashboard'
	export default {
		name: 'Pemetaan',
		props: { page: String, page_title: String },
		components: { Layout },
		data: () => ({
			pemetaans: []
		}),
		methods: {
			getPemetaans(){
				axios({
					method: 'post',
					url: '/dashboard/pemetaan?q=all',
					data: {
						kelas: this.$page.props.rombel.kelas_id,
						semester: this.$page.props.periode_aktif.semester
					}
				}).then( res => {
					this.pemetaans = res.data.pemetaans
				}).catch( err => {
					console.log(err.response)
				})
			}
		},
		computed: {

		},
		mounted() {
			this.getPemetaans()
		}
	}
</script>
<style scoped>
	.table-tematik th,
	.table-tematik td,
	{
		padding: 5px;
	}
</style>