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
				<v-row>
					<v-col cols="12">
						<v-card>
							<v-card-text>
								<v-row>
									<v-col cols="10">
										<v-select v-model="selectedPeriode" :items="periodes" item-value="value" item-text="text" outlined rounded label="Periode" dense hide-details @change="onChangedPeriode"></v-select>
									</v-col>
									<v-col cols="2">
										<v-btn fab color="error" small @click="$emit('hide')"><v-icon>mdi-close</v-icon></v-btn>
									</v-col>
								</v-row>
							</v-card-text>
						</v-card>
					</v-col>
					<v-col cols="12">
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
								<v-tabs-items v-model="tab" touchless>
									<v-tab-item>
										<v-card color="basil" flat v-if="tab == 0">
											<v-card-text>
												
												<pts :siswa="dialog.siswa" :rapor="pts"></pts>
											</v-card-text>
										</v-card>
									</v-tab-item>
									<v-tab-item>
										<v-card color="basil" flat v-if="tab ==1">
											<v-card-text>
												<pas :siswa="dialog.siswa" :rapor="pas"></pas>
											</v-card-text>
										</v-card>
									</v-tab-item>
								</v-tabs-items>
								<v-tabs  
									dense
									v-model="tab" background-color="info" dark
									@change="onTabChanged"
									
									style="position: absolute;z-index: 999999999;top: 50%;right:-100px; transform: rotate(-90deg);width: auto;"
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
				</v-row>
					<!-- </v-container> -->
			</v-card>
		</v-dialog>			
	</div>
</template>

<script>
	import Pts from './RaporPts'
	import Pas from './RaporPas'
	export default {
		name: 'ModalRapor',
		props: {
			dialog: Object,
		},
		components: { Pts, Pas},
		data: () => ({
			siswa: '',
			tab: null,
			items: [
				Pts,Pas
			],
			text: 'Sample Text',
			rapor: {},
			pts: {},
			pas: {},
			teks: "Halo",
			progress: false,
			periodes: [],
			selectedPeriode: '',
			selectedRombel: '',
		}),
		methods: {
			onChangedPeriode(e){
				// this.
				var romb = this.$page.props.rombel.kode_rombel.split('-')
				// alert(romb)
				this.selectedRombel = e+'-'+romb[1]
				 switch(this.tab){
				 	case 0:
				 		this.getPTS()
				 		break
				 	case 1:
				 		this.getPAS()
				 		break
				 }
			},
			getPTS(){
				this.progress = true
				axios({
					method: 'post',
					url: '/dashboard/rapor/pts',
					data: {
						rombel: this.rombel,
						periode: this.selectedPeriode,
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
					url: '/dashboard/rapor/pas',
					data: {
						rombel: this.rombel,
						periode: this.selectedPeriode,
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
					case 0:
						this.getPTS()
						break
					case 1:
						this.getPAS()
						break
				}
			},
			setDefaultFoto(event, siswa) {
                var foto = (siswa.jk == 'l') ? 'siswa.png' : 'siswi.png'
                event.target.src = '/storage/img/siswas/'+foto
            }, 
            getPeriodes() {
            	axios({
            		method: 'post',
            		url: '/dashboard/periode'
            	}).then( res => {
            		var items =  []
            		res.data.periode.forEach(item => {
            			items.push({ value: item.kode_periode, text: item.tapel+' Semester '+ item.semester})
            		})
            		this.periodes = items
            	}).catch( err => {
            		console.log(err.response)
            	})
            }
           
		},
		computed: {
			rombel() {
				return this.selectedRombel ? this.selectedRombel : this.$page.props.rombel.kode_rombel
			}
		},
		created() {
			this.siswa = this.dialog.siswa.nisn
			this.selectedPeriode = this.$page.props.periode
			this.getPeriodes()
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