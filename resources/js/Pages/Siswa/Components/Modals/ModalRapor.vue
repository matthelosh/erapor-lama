<template>
	<div>
		<div v-if="progress" class="progress d-flex justify-center align-center">
			<div>
				<img src="/img/ngopi.gif" class="kopi">
				<h3 class="text-center">Data Rapor sedang diambil. Tunggu sebentar, ya ..</h3>
			</div>
		</div>
		<v-dialog v-model="dialog.show" fullscreen transition="dialog-bottom-transition" hide-overlay id="main-dialog" ref="mainDialog" >
			<v-card color="grey lighten-2">
				<v-card-title class="text-center justify-center py-6">
					<h3 class="font-weight-bold">RAPOR {{ selectedSiswa.nama.toUpperCase() }}</h3>
						<v-btn absolute color="error" @click="$emit('hide')" fab right small style="margin-top:-30px;">
							<v-icon>mdi-close</v-icon>
						</v-btn>
					
				</v-card-title>
					<!-- <v-container> -->
				<v-row>
					<v-col cols="9">
						<v-card >
							<v-card-text>
								<v-tabs 
									v-model="tab" grow background-color="accent" dark
									@change="onTabChanged"
								>
									<v-tab 
										v-for="(item,index) in items"
										:key="index"
										>
										<v-icon>{{ item.icon }}</v-icon>
										&nbsp;
										{{ item.label }}
									</v-tab>

								</v-tabs>
								<v-tabs-items v-model="tab">
									<v-tab-item>
										<v-card color="basil" flat v-if="tab == 0">
											<v-card-text>
												<cover :siswa="selectedSiswa" ></cover>
											</v-card-text>
										</v-card>
									</v-tab-item>
									<v-tab-item>
										<v-card color="basil" flat v-if="tab == 1">
											<v-card-text>
												<biodata :siswa="selectedSiswa"></biodata>
											</v-card-text>
										</v-card>
									</v-tab-item>
									<v-tab-item>
										<v-card color="basil" flat v-if="tab == 2">
											<v-card-text>
												
												<pts :siswa="selectedSiswa" :rapor="pts"></pts>
											</v-card-text>
										</v-card>
									</v-tab-item>
									<v-tab-item>
										<v-card color="basil" flat v-if="tab ==3">
											<v-card-text>
												<pas :siswa="selectedSiswa" :rapor="pas"></pas>
											</v-card-text>
										</v-card>
									</v-tab-item>
								</v-tabs-items>
								<v-tabs  
									dense
									v-model="tab" background-color="info" dark
									@change="onTabChanged"
									
									style="position: absolute;z-index: 999999999;top: 50%;right:-246px; transform: rotate(-90deg);width: auto;"
								>
									<v-tab 
										
										v-for="(item,index) in items"
										:key="index"
										>
										<span >{{ item.label }}</span>
									</v-tab>

								</v-tabs>
							</v-card-text>
						</v-card>
					</v-col>
					<v-col cols="3">
						<v-card>
							<v-card-text>
								<v-autocomplete 
									hide-details 
									v-model="siswa"
									dense 
									label="Pilih Siswa" 
									solo 
									rounded
									@change="onSiswaChanged"
									item-text="nama"
									item-value="nisn" 
									:items="dialog.siswas">
									<template v-slot:item="data">
										<template v-if="typeof data.item !== 'object'">
											<v-list-item-content v-text="data.item"></v-list-item-content>
										</template>
										<template v-else>
											<v-list-item-avatar>
												<img :src="'/storage/img/siswas/'+data.item.nisn+'.jpg'" @error="setDefaultFoto($event, data.item)" class="foto" >
											</v-list-item-avatar>
											<v-list-item-content>
												<v-list-item-title v-html="data.item.nama"></v-list-item-title>
												<v-list-item-subtitle v-html="data.item.nisn"></v-list-item-subtitle>
											</v-list-item-content>
										</template>
									</template>
								</v-autocomplete>
							</v-card-text>
							<v-col cols="12" >
								<v-btn color="primary" block rounded dark @click="print"><v-icon small>mdi-printer</v-icon> Cetak</v-btn>
							</v-col>
						</v-card>
					</v-col>
				</v-row>
					<!-- </v-container> -->
			</v-card>
		</v-dialog>			
	</div>
</template>

<script>
	import Cover from '../Rapor/CoverRapor'
	import Biodata from '../Rapor/BiodataRapor'
	import Pts from '../Rapor/RaporPts'
	import Pas from '../Rapor/RaporPas'
	export default {
		name: 'ModalRapor',
		props: {
			dialog: Object,
		},
		components: { Cover, Biodata, Pts, Pas},
		data: () => ({
			siswa: '',
			tab: null,
			items: [
				Cover,Biodata,Pts,Pas
			],
			text: 'Sample Text',
			rapor: {},
			pts: {},
			pas: {},
			teks: "Halo",
			progress: false
		}),
		methods: {
			print() {
				const html = document.getElementById('print').innerHTML
				let css = ''
				for ( const node of [...document.querySelectorAll('link[rel="stylesheet"], style')]) {
					css += node.outerHTML
				}

				const WinPrint = window.open('','', 'left=0,top=0,width=800,height=900, toolbar=0, scrollbars=0,status=0')
				WinPrint.document.write(`<!DOCTYPE html>
					<html>
						<head>
							<title>
								Rapor ${this.selectedSiswa.nama}
							</title>
							${css}
						</head>
						<body>
							${html}
						</body>
					<html>
				`)

				WinPrint.document.close()
				WinPrint.focus()
				WinPrint.print()
				WinPrint.close()
			},
			onSiswaChanged(nisn){
				 switch(this.tab){
				 	case 2:
				 		this.getPTS()
				 		break
				 	case 3:
				 		this.getPAS()
				 		break
				 }
			},
			getRapor(){
				// // console.log(this.dialog.siswa.nisn)
				// axios({
				// 	method: 'post',
				// 	url: '/dashboard/rapor/cetak',
				// 	data: {
				// 		rombel: this.$page.props.rombel.kode_rombel,
				// 		periode: this.$page.props.periode,
				// 		siswa_id: this.siswa
				// 	}
				// }).then( res => {
				// 	// console.log( res.data )
				// 	this.rapor = res.data.rapor
				// }).catch( err => {
				// 	console.log( err.response)
				// })
			},
			getPTS(){
				// console.log(this.$page.props.rombel)
				this.progress = true
				axios({
					method: 'post',
					url: '/wali/rapor/pts',
					data: {
						rombel: this.$page.props.rombel.kode_rombel,
						periode: this.$page.props.periode,
						siswa_id: this.siswa
					}
				}).then( res => {
					// console.log( res.data )
					this.progress = false
					this.pts = res.data.rapor
				}).catch( err => {
					console.log( err.response)
				})
			},
			getPAS(){
				this.progress = true
				axios({
					method: 'post',
					url: '/wali/rapor/pas',
					data: {
						rombel: this.$page.props.rombel.kode_rombel,
						periode: this.$page.props.periode,
						siswa_id: this.siswa
					}
				}).then( res => {
					this.progress = false
					// console.log( res.data )
					this.pas = res.data.rapor
				}).catch( err => {
					console.log( err.response)
				})
			},
			onTabChanged(e) {
				switch(e){
					case 2:
						this.getPTS()
						break
					case 3:
						this.getPAS()
						break
				}
			},
			setDefaultFoto(event, siswa) {
                var foto = (siswa.jk == 'l') ? 'siswa.png' : 'siswi.png'
                event.target.src = '/storage/img/siswas/'+foto
            }, 
           
		},
		computed: {
			selectedSiswa() {
				let siswas =  this.dialog.siswas.filter( item => (item.nisn == this.siswa))
				return siswas[0]
			}
		},
		created() {
			this.siswa = this.dialog.siswa.nisn
			
		}
	}
</script>

<style scoped>
	.progress {
		position: fixed;
		top: 0;
		right: 0;
		bottom: 0;
		left:  0;
		z-index:  9999999;
		background:  rgba(255,255,255,0.8);
	}

</style>