<template>
	<Layout :title="page_title">
		<v-container>
			<v-row>
				<v-col cols="12">
					<v-card class="elevation-5">
						<v-toolbar dense>
							<v-toolbar-title>Data Sekolah</v-toolbar-title>
							<v-spacer></v-spacer>
							<v-toolbar-items>
								<v-btn icon @click="buat" ><v-icon>mdi-pencil</v-icon></v-btn>
							</v-toolbar-items>
						</v-toolbar>
						<v-img src="/img/sekolah.jpg">
							<v-container style="background: rgba(255,255,255,0.9);height:100%;" class="d-flex justify-center align-center">
								<v-row style="width:80%;">
									<v-col cols="12" sm="3">
										<v-img src="/img/tutwuri.png" height="150" contain></v-img>
									</v-col>
									<v-col cols="12" sm="5">
										<h3><u>{{ sekolah.nama_sekolah }}</u></h3>
										<h4>NPSN: {{ sekolah.npsn }}</h4>
										<p class="my-0 l-0">Alamat {{ sekolah.alamat }} - {{ sekolah.desa }}</p>
										<p class="my-0 l-0">Kecamatan {{ sekolah.kec }} - Kode Pos {{ sekolah.kode_pos }}</p>
										<p class="my-0 l-0">Kabupaten {{ sekolah.kab }} - {{ sekolah.prov }}</p>
										<p class="my-0 l-0">Telp: {{ sekolah.telp }} / Fax: -</p>
										<p class="my-0 l-0">Email: {{ sekolah.email }}</p>
										<p class="my-0 l-0">Website: {{ sekolah.website }}</p>
										<p class="my-0 l-0">Kepala Sekolah: {{ (sekolah.ks ? sekolah.ks.name : '-')  }}</p>
									</v-col>
									<v-col cols="12" sm="4">
										<!-- <div class="mapouter"><div class="gmap_canvas"><iframe width="300" height="350" id="gmap_canvas" src="https://maps.google.com/maps?q=SD%20Negeri.%201%20Bedalisodo&t=&z=19&ie=UTF8&iwloc=&output=embed" frameborder="0" scrolling="no" marginheight="0" marginwidth="0"></iframe></div></div> -->
										<v-avatar  class="elevation-1" size='200'>
											<img :src="'/storage/img/users/'+(sekolah.ks ? (sekolah.ks.nip+'.jpg') : 'user_l.png')" onerror="this.error=false;this.src='/img/siswi-avatar.png'">
										</v-avatar>
									</v-col>
								</v-row>
							</v-container>
						</v-img>
					</v-card>
				</v-col>
			</v-row>
		</v-container>
		<div v-if="dialog.show">
			<modal-sekolah :dialog="dialog" @hide="dialog.show = false"></modal-sekolah>
		</div>
	</Layout>
</template>

<script>
import Layout from '../../../Layout/Dashboard.vue'
import ModalSekolah from '../Modals/ModalSekolah'
export default {
	name: 'Sekolah',
	props: {
		page_title: String,
		page: String
	},
	components: {Layout, ModalSekolah},
	data: () => ({
		sekolah: {},
		dialog: { show: false },
		default: {
			npsn: '00000',
			'nama_sekolah':'Sekolah',
			'alamat' :'',
			'desa' :'',
			'kec':'',
			'kab' :'',
			'prov':'',
			'kode_pos':'',
			'telp' :'',
			'email' :'',
			'website' :'',
			'ks' :'',
			'operator' :''
		}
	}),
	methods: {
		buat(){
			this.dialog = { show: true, sekolah: this.sekolah }
		},
		init() {
			axios({
				method: 'post',
				url: '/admin/pengaturan/sekolah'
			}).then(response => {
				this.sekolah = response.data.sekolah != null ? response.data.sekolah : Object.assign(this.sekolah, this.default)
			}).catch( err => {
				this.snackbar = { show: true, color: 'error', text: err.response.data.msg }
			})
		}
	},
	computed: {

	},
	created() {
		this.init()
	}
}
</script>

<style>
	.shadowed-text {
		text-shadow: 0 0 2px rgba(0,0,0,0.3);
	}
	.l-0 {
		line-height: 1.2em;
	}
	.gmap_canvas {overflow:hidden;background:none!important;height:350px;width:350;}
	.mapouter{position:relative;text-align:left;height:350px;width:350px;}
</style>