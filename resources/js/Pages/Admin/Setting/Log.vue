<template>
	<Layout :title="page_title">
		<v-container>
			<v-row>
				<v-col cols="12">
					<v-card class="mt-3">
						<v-toolbar dense>
							<v-toolbar-title>Catatan Akses Pengguna</v-toolbar-title>
						</v-toolbar>
						<v-card-text>
							<v-data-table
								:items="logs"
								:headers="headers"
								:search="search"
								dense
							>
								<template v-slot:top>
									<v-container>
										<v-row>
											<v-col cols="4"></v-col>
											<v-col cols="4"></v-col>
											<v-col cols="4">
												<v-text-field v-model="search" label="Cari" append-icon="mdi-magnify" outlined rounded dense hide-details></v-text-field>
											</v-col>
										</v-row>
									</v-container>
								</template>
								<template v-slot:item.access_time="{item}">
									<span>{{ accessOn(item.created_at) }}</span>
								</template>
							</v-data-table>
						</v-card-text>
					</v-card>
				</v-col>
			</v-row>
		</v-container>
	</Layout>
</template>

<script>
	import Layout from '../../../Layout/Dashboard'
	import moment from 'moment'
	export default {
		name: 'AksesLog',
		props: {page_title: String, page: String},
		components: { Layout },
		data: () => ({
			search: '',
			logs: [],
			headers: [
				{ text: 'No', value: 'index' , filterable: false,},
				{ text: 'OS', value: 'os' },
				{ text: 'Browser', value: 'browser' },
				{ text: 'IP', value: 'ip' },
				{ text: 'User', value: 'users.username' },
				{ text: 'Waktu', value: 'access_time' },
			],
		}),
		methods: {
			getLogs() {
				axios({
					method: 'post',
					url: '/admin/pengaturan/logs'
				}).then( res => {
					var logs = []
					res.data.logs.forEach( (item, index) => {
						item.index = index+1
						logs.push(item)
					})
					this.logs = logs
				}).catch( err => {
					console.log( err.response )
				})
			},
			accessOn(time) {
				moment.locale('id')
				return moment(time).format('Do-MM-YYYY H:m:s')

			}
		},
		computed: {

		},
		mounted() {
			this.getLogs()
		}
	}
</script>