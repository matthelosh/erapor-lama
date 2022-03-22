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
				console.log(res);
			})
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