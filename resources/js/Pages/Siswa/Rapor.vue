<template>
	<Layout :title="page_title">
		<v-container>
			<v-row>
				<v-col cols="12">
					<v-card>
						<v-toolbar dense >
							<v-toolbar-title>
								<v-icon>mdi-microsoft-word</v-icon>
								Rapor {{$page.props.user.nama}}
							</v-toolbar-title>
							<v-spacer></v-spacer>
						</v-toolbar>
						<v-card-text>
							<v-data-table
								:items="arsips"
								:headers="headers"
							>
								<template v-slot:[`item.file`]="{item}">
									<a :href="'/storage/arsip/'+item.rombel_id.substr(0,5)+'/'+item.rombel_id+'/'+item.filename" target="_blank">{{item.filename}}</a>
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
import Layout from '../../Layout/Dashboard'
export default {
	name: 'Rapor',
	props: {
		page: String,
		page_title: String
	},
	components: { Layout },
	data: () =>({
		arsips: [],
		headers: [
			{ text: 'Rombel', value: 'rombel_id' },
			{ text: 'Jenis', value: 'jenis' },
			{ text: 'Lihat', value: 'file' },
		],
	}),
	methods:{
		setDefaultFoto(event, siswa) {
			var foto = (siswa.jk == 'l') ? 'siswa.png' : 'siswi.png'
			event.target.src = '/storage/img/siswas/'+foto
		},
		getArsip() {
			axios({
				method: 'post',
				url: '/siswa/rapor/arsip'
			}).then( res => {
				// console.log(res);
				this.arsips = res.data.arsips
			});
		}
	},
	computed: {

	},
	created() {
		this.getArsip()
	}
}
</script>
	
<style scoped>
	
</style>