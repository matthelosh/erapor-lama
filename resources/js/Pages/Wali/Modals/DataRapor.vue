<template>
	<div>
		<!-- <div v-if="progress" class="progress d-flex justify-center align-center">
			<div>
				<img src="/img/ngopi.gif" class="kopi">
				<h3 class="text-center">Data Rapor sedang diambil. Tunggu sebentar, ya ..</h3>
			</div>
		</div> -->
		<v-dialog v-model="dialog.show" fullscreen transition="dialog-bottom-transition">
 			<v-card light color="grey" >
 				<v-toolbar dense style="position: fixed;width:100%;z-index: 999999;">
 					<v-toolbar-title>DATA RAPOR {{ dialog.siswa.nama }}</v-toolbar-title>

 					<v-spacer></v-spacer>
 					TAPEL {{ $page.props.periode_aktif.tapel }} - SEMESTER {{ $page.props.periode_aktif.semester }}
 					<v-btn class="ml-3" @click="$emit('hide')" color="error" small fab><v-icon>mdi-close</v-icon></v-btn>
 				</v-toolbar>
 				<v-card-text class="pt-12">
 					<v-container>
						<v-row>
							<v-col cols="4"></v-col>
							<v-col cols="4"></v-col>
							
							<v-col cols="4">
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
							</v-col>
						</v-row>
 						<v-row>
 							<v-col cols="12" sm="5">
 								<v-card  >
 									<v-card-title>
										Ekstrakurikuler
										<v-spacer></v-spacer>
										<v-btn fab small color="error" @click="showFormEkskul(null,null)"><v-icon>mdi-plus</v-icon></v-btn>
									</v-card-title>
 									<v-card-text class="d-flex justify-center">
 										<v-progress-circular
		 									v-if="ekskul.progress"
										    indeterminate
										    color="error"
										    class="my-auto"
									    ></v-progress-circular>
 										<transition name="slide-fade">
	 										<span v-if="ekskul.show">
	 											<v-data-table
	 												:items="ekskul.dataItems"
	 												:headers="ekskul.headers"
	 												:footer-props="{
	 													itemsPerPageText: '',
	 													itemsPerPageAllText: 'Semua'
	 												}"
	 												>
	 												<template v-slot:item.opsi="{item}">
	 													<span>
	 														<v-btn icon small color="warning" @click="showFormEkskul('edit', item)">
	 															<v-icon small>mdi-pencil</v-icon>
	 														</v-btn>
	 														<v-btn icon small color="error" @click="deleteItem('EkskulSiswa', item)">
	 															<v-icon small>mdi-delete</v-icon>
	 														</v-btn>
 														</span>
	 												</template>
	 											</v-data-table>
 											</span>
	 									</transition>
 									</v-card-text>
 								</v-card>
 							</v-col>
 							<v-col cols="12" sm="4">
 								<v-card>
 									<v-card-title>
										Saran
										<v-spacer></v-spacer>
										<v-btn color="primary" @click="simpanSaran" rounded>Simpan</v-btn>
									</v-card-title>
 									<v-card-text>
 										<v-progress-circular
		 									v-if="saran.progress"
										    indeterminate
										    color="error"
										    class="my-auto"
									    ></v-progress-circular>
 										<!-- <v-textarea v-if="!saran.progress" label="Saran" v-model="saran.teks" outlined rows="4" solo hide-details></v-textarea> -->
										<tiptap-vuetify 
											v-if="!saran.progress"
											v-model="saran.teks"
											:extensions="extensions"
										>

										</tiptap-vuetify>
 									</v-card-text>
 								</v-card>
 							</v-col>
 							<v-col cols="12" sm="3">
 								<v-card>
 									<v-card-title>
 										Fisik Siswa
 										<v-spacer></v-spacer>
 										<v-btn color="primary" rounded @click="simpanFisik">Simpan</v-btn>
 									</v-card-title>
 									<v-card-text>
	 									<div class="d-flex align-center justify-center">
	 										<v-progress-circular
			 									v-if="fisik.progress"
											    indeterminate
											    color="error"
											    class="my-auto"
										    ></v-progress-circular>
										    <v-row v-if="!fisik.progress">
										    	<v-col cols="6">
										    		<v-text-field v-model="fisik.tb" label="Tinggi(cm)" type="number" outlined rounded dense hide-details />
										    	</v-col>
										    	<v-col cols="6">
										    		<v-text-field v-model="fisik.bb" label="Berat(kg)" type="number" outlined rounded dense hide-details />
										    	</v-col>
										    	<v-col cols="12" class="d-flex justify-center">
										    		<v-btn color="primary" rounded @click="simpanFisik">Simpan</v-btn>
										    	</v-col>
										    </v-row>
	 									</div>
 									</v-card-text>
 								</v-card>
 							</v-col>
 							<v-col cols="12" sm="5">
 								<v-card>
 									<v-card-title>
 										Prestasi
 										<v-spacer></v-spacer>
 										<v-btn color="primary" @click="simpanPrestasi" rounded>Simpan</v-btn>
 									</v-card-title>
 									<v-card-text>
 										<div class="d-flex align-center justify-center">
 											<v-progress-circular
			 									v-if="prestasi.progress"
											    indeterminate
											    color="success"
											    class="my-auto"
										    ></v-progress-circular>
										    <v-row v-if="!prestasi.progress">
										    	<v-col cols="3">
										    		Akademik
											    </v-col>
											    <v-col cols="9">
										    		<v-textarea v-model="prestasi.akademik" label="Prestasi Akademik" solo rows="2" dense hide-details></v-textarea>
											    </v-col>
											    <v-col cols="3">
										    		Olahraga
											    </v-col>
											    <v-col cols="9">
										    		<v-textarea v-model="prestasi.olahraga" label="Prestasi Akademik" solo rows="2" dense hide-details></v-textarea>
											    </v-col>
											    <v-col cols="3">
										    		Kesenian
											    </v-col>
											    <v-col cols="9">
										    		<v-textarea v-model="prestasi.kesenian" label="Prestasi Akademik" solo rows="2" dense hide-details></v-textarea>
											    </v-col>
											    
										    </v-row>
 										</div>
 									</v-card-text>
 								</v-card>
 							</v-col>
 							<v-col cols="12" sm="4">
 								<v-card>
 									<v-card-title>
 										Kesehatan
 										<v-spacer></v-spacer>
 										<v-btn color="primary" @click="simpanKesehatan" rounded>Simpan</v-btn>
 									</v-card-title>
 									<v-card-text>
 										<div class="d-flex align-center justify-center">
	 										<v-progress-circular
			 									v-if="kesehatan.progress"
											    indeterminate
											    color="success"
											    class="my-auto"
										    ></v-progress-circular>
										    <v-row v-if="!kesehatan.progress">
										    	<v-col cols="4">
										    		Pendengaran
											    </v-col>
											    <v-col cols="8">
										    		<v-text-field v-model="kesehatan.pendengaran" label="Pendengaran" solo rounded dense hide-details></v-text-field>
											    </v-col>
											    <v-col cols="4">
										    		Penglihatan
											    </v-col>
											    <v-col cols="8">
										    		<v-text-field v-model="kesehatan.penglihatan" label="Penglihatan" solo rounded dense hide-details></v-text-field>
											    </v-col>
											    <v-col cols="4">
										    		Gigi
											    </v-col>
											    <v-col cols="8">
										    		<v-text-field v-model="kesehatan.gigi" label="Gigi" solo rounded dense hide-details></v-text-field>
											    </v-col>
											    <v-col cols="4">
										    		Lainnya
											    </v-col>
											    <v-col cols="8">
										    		<v-text-field v-model="kesehatan.lainnya" label="Lainnya" solo rounded dense hide-details></v-text-field>
											    </v-col>
											    
										    </v-row>
	 									</div>
 									</v-card-text>
 								</v-card>
 							</v-col>
 							
 							<v-col cols="12" sm="3">
 								<v-card>
 									<v-card-title>
 										Absensi
 										<v-spacer></v-spacer>
 										<v-btn color="primary" @click="simpanAbsensi" rounded>Simpan</v-btn>
 									</v-card-title>
 									<v-card-text>
 										<div class="d-flex align-center justify-center">
	 										<v-progress-circular
			 									v-if="absensi.progress"
											    indeterminate
											    color="success"
											    class="my-auto"
										    ></v-progress-circular>
										    <v-row v-if="!absensi.progress">
										    	<v-col cols="4">
										    		Alpa
											    </v-col>
											    <v-col cols="8">
										    		<v-text-field v-model="absensi.a" label="Alpa" solo rounded dense hide-details type="number"></v-text-field>
											    </v-col>
											    <v-col cols="4">
										    		Ijin
											    </v-col>
											    <v-col cols="8">
										    		<v-text-field v-model="absensi.i" label="Ijin" solo rounded dense hide-details type="number"></v-text-field>
											    </v-col>
											    <v-col cols="4">
										    		Sakit
											    </v-col>
											    <v-col cols="8">
										    		<v-text-field v-model="absensi.s" label="Sakit" solo rounded dense hide-details type="number"></v-text-field>
											    </v-col>
											    
										    </v-row>
	 									</div>
 									</v-card-text>
 								</v-card>
 							</v-col>
 							
 						</v-row>
 					</v-container>
 				</v-card-text>
 			</v-card>
		</v-dialog>
		<v-dialog v-model="ekskul.selected.show" max-width="500">
			<v-card >
				<v-toolbar dense>
					<v-toolbar-title>Form Ekskul</v-toolbar-title>
					<v-spacer></v-spacer>
					<v-btn color="error" small @click="ekskul.selected.show = false" fab><v-icon>mdi-close</v-icon></v-btn>
				</v-toolbar>
				<v-card-text>
					<v-container>
						<v-row>
							<v-col cols="12">
								<v-autocomplete label="Pilih Ekskul" :items="ekskul.items" item-value="kode_ekskul" item-text="label" v-model="ekskul.selected.ekskul_id" rounded outlined dense hide-details prepend-inner-icon="mdi-soccer"></v-autocomplete>
							</v-col>
							<v-col cols="12">
								<v-textarea label="Keterangan" v-model="ekskul.selected.ket" auto-grow rows="2" outlined dense rounded hide-details prepend-inner-icon="mdi-note"></v-textarea>
							</v-col>
							<v-col cols="12" class="d-flex justify-center">
								<div>
									<v-btn color="primary" @click="simpanDataEkskul">Simpan</v-btn>
								</div>
							</v-col>
						</v-row>
					</v-container>
				</v-card-text>
			</v-card>
		</v-dialog>
	</div>
</template>

<script>
	import { TiptapVuetify, Heading, Bold, Italic, Strike, Underline, Code, Paragraph, BulletList, OrderedList, ListItem, Link, Blockquote, HardBreak, HorizontalRule, History } from 'tiptap-vuetify'
	export default {
		components: {TiptapVuetify},
		name: 'DataRapor',
		props: {
			dialog: Object
		},
		data: ()=>({
			siswa: '',
			ekskul: {
				progress: false,
				show: false,
				items: [],
				dataItems: [],
				headers: [
					{ text: 'Jenis', value: 'ekskuls.label'},
					{ text: 'Keterangan', value: 'ket'},
					{ text: 'Opsi', value: 'opsi'}
				],
				selected: {}

			},
			extensions: [
				History,
				Blockquote,
				Link,
				Underline,
				Strike,
				Italic,
				ListItem,
				BulletList,
				OrderedList,
				[Heading, {
					options: {
					levels: [1, 2, 3]
					}
				}],
				Bold,
				Code,
				HorizontalRule,
				Paragraph,
				HardBreak
				],
			saran: {
				progress: false,
				show: false,
				teks: ''
			},
			fisik: {
				progress: false,
				show: false,
				items: [],
				headers: []
			},
			kesehatan: {
				progress: false,
				show: false,
				pendengaran: '',
				penglihatan: '',
				gigi: '',
				lainnya: ''
			},
			prestasi: {
				progress: false,
				show: false,
				items: [],
				headers: []
			},
			absensi: {
				progress: false,
				show: false,
				a: '',
				i: '',
				s: ''
			}

		}),
		methods: {
			
			deleteItem(model, item) {
				// alert('hi')
				this.ekskul.progress = true
				axios({
					method: 'delete',
					url: '/wali/datarapor?model='+model,
					data: item
				}).then( res => {
					this.getDataEkskul()
				}).catch( err => {
					console.log( err.response )
				})
			},
			async init() {
				// this.progress = true
				await this.getEkskul()
				await this.getDataEkskul()
				await this.getSaran()
				await this.getFisik()
				await this.getKesehatan()
				await this.getPrestasi() 
				await this.getAbsensi()
				
			},
			getEkskul(){
				axios({
					method: 'post',
					url: '/wali/datarapor/ekskul'
				}).then( res => {
					this.ekskul.items = res.data.ekskuls
				}).catch( err => {
					console.log( err.response)
				})
			},
			getDataEkskul() {
				this.ekskul.progress = true
				axios({
					method: 'post',
					url: '/wali/datarapor/dataekskul?siswa='+this.siswa,
					data: {
						periode: this.$page.props.periode,
						rombel: this.$page.props.rombel.kode_rombel 
					}
				}).then( res => {
					var dataItems = []
					res.data.dataEkskuls.forEach( (item,index) => {
						item.index = index + 1
						dataItems.push(item)
					})
					
					this.ekskul.dataItems = dataItems
					this.ekskul.progress = false
					this.ekskul.show= true
				}).catch( err => {
					console.log( err.response)
					this.ekskul.progress = false
					this.ekskul.show = true
				})


			},
			showFormEkskul(edit,item) {
				if (edit == null) {
					this.ekskul.selected = {
						show: true,
						mode: 'create',
						periode_id: this.$page.props.periode,
						rombel_id: this.$page.props.rombel.kode_rombel,
						ekskul_id: '',
						siswa_id: this.siswa,
						ket: ''
					}
				} else {
					this.ekskul.selected = {
						show: true,
						mode: 'edit',
						periode_id: item.periode_id,
						rombel_id: item.rombel_id,
						ekskul_id: item.ekskul_id,
						siswa_id: item.siswa_id,
						ket: item.ket
					}
				}
			},
			simpanDataEkskul(){
				this.ekskul.progress = true
				axios({
					method: 'post',
					url: '/wali/datarapor/ekskul/create',
					data: this.ekskul.selected
				}).then( res => {
					this.getDataEkskul()
					this.ekskul.progress = false			
				}).catch( err => {
					console.log( err )
				})
			},
			getSaran() {
				this.saran.progress = true
				axios({
					method: 'post',
					url: '/wali/datarapor/saran',
					data: {
						periode_id: this.$page.props.periode,
						rombel_id: this.$page.props.rombel.kode_rombel,
						siswa_id: this.siswa,
					}
				}).then( res => {
					this.saran.progress = false
					this.saran.teks = res.data.saran ? res.data.saran.teks : ''
				}).catch( err => {
					console.log(err.response)
				})
			},
			simpanSaran(){
				this.saran.progress = true
				axios({
					method: 'post',
					url: '/wali/datarapor/saran/create',
					data: {
						periode_id: this.$page.props.periode,
						rombel_id: this.$page.props.rombel.kode_rombel,
						siswa_id: this.siswa,
						jenis_rapor: 'pas',
						teks: this.saran.teks
					}
				}).then( res => {
					this.saran.progress = false
					this.getSaran()
				}).catch( err => {
					console.log(err.response)
				})
			},
			getFisik() {
				this.fisik.progress = true
				axios({
					method: 'post',
					url: '/wali/datarapor/fisik',
					data: {
						periode_id: this.$page.props.periode,
						rombel_id: this.$page.props.rombel.kode_rombel,
						siswa_id: this.siswa,
					}
				}).then( res => {
					this.fisik.progress = false
					this.fisik.tb = res.data.fisik ? res.data.fisik.tb : ''
					this.fisik.bb = res.data.fisik ? res.data.fisik.bb : ''
				}).catch( err => {
					console.log(err.response)
				})
			},
			simpanFisik() {
				this.fisik.progress = true
				axios({
					method: 'post',
					url: '/wali/datarapor/fisik/create',
					data: {
						periode_id: this.$page.props.periode,
						rombel_id: this.$page.props.rombel.kode_rombel,
						siswa_id: this.siswa,
						tb: this.fisik.tb,
						bb: this.fisik.bb,
					}
				}).then( res => {
					this.fisik.progress = false
					this.getFisik()
				}).catch( err => {
					console.log(err.response)
				})
			},
			getKesehatan() {
				this.kesehatan.progress = true
				axios({
					method: 'post',
					url: '/wali/datarapor/kesehatan',
					data: {
						periode_id: this.$page.props.periode,
						rombel_id: this.$page.props.rombel.kode_rombel,
						siswa_id: this.siswa,
					}
				}).then( res => {
					this.kesehatan.progress = false
					this.kesehatan.pendengaran = res.data.kesehatan ? res.data.kesehatan.pendengaran : ''
					this.kesehatan.penglihatan = res.data.kesehatan ? res.data.kesehatan.penglihatan : ''
					this.kesehatan.gigi = res.data.kesehatan ? res.data.kesehatan.gigi  : ''
					this.kesehatan.lainnya = res.data.kesehatan ? res.data.kesehatan.lainnya : ''
				}).catch( err => {
					console.log(err.response)
				})
			},
			simpanKesehatan(){
				this.kesehatan.progress = true
				axios({
					method: 'post',
					url: '/wali/datarapor/kesehatan/create',
					data: {
						periode_id: this.$page.props.periode,
						rombel_id: this.$page.props.rombel.kode_rombel,
						siswa_id: this.siswa,
						pendengaran: this.kesehatan.pendengaran,
						penglihatan: this.kesehatan.penglihatan,
						gigi: this.kesehatan.gigi,
						lainnya: this.kesehatan.lainnya,
					}
				}).then( res => {
					this.kesehatan.progress = false
					this.getFisik()
				}).catch( err => {
					console.log(err.response)
				})
			},
			getPrestasi() {
				this.prestasi.progress = true
				axios({
					method: 'post',
					url: '/wali/datarapor/prestasi',
					data: {
						periode_id: this.$page.props.periode,
						rombel_id: this.$page.props.rombel.kode_rombel,
						siswa_id: this.siswa,
					}
				}).then( res => {
					this.prestasi.progress = false
					this.prestasi.akademik = res.data.prestasi ? res.data.prestasi.akademik : ''
					this.prestasi.olahraga = res.data.prestasi ? res.data.prestasi.olahraga : ''
					this.prestasi.kesenian = res.data.prestasi ? res.data.prestasi.kesenian : ''
				}).catch( err => {
					console.log(err.response)
				})

			},
			simpanPrestasi(){
				this.prestasi.progress = true
				axios({
					method: 'post',
					url: '/wali/datarapor/prestasi/create',
					data: {
						periode_id: this.$page.props.periode,
						rombel_id: this.$page.props.rombel.kode_rombel,
						siswa_id: this.siswa,
						akademik: this.prestasi.akademik,
						olahraga: this.prestasi.olahraga,
						kesenian: this.prestasi.kesenian
					}
				}).then( res => {
					this.prestasi.progress = false
					this.getPrestasi()
				}).catch( err => {
					console.log(err.response)
				})
			},
			getAbsensi() {
				this.absensi.progress = true
				axios({
					method: 'post',
					url: '/wali/datarapor/absensi',
					data: {
						periode_id: this.$page.props.periode,
						rombel_id: this.$page.props.rombel.kode_rombel,
						siswa_id: this.siswa,
					}
				}).then( res => {
					this.absensi.progress = false
					this.absensi.a = res.data.absensi ? res.data.absensi.a : ''
					this.absensi.i = res.data.absensi ? res.data.absensi.i : ''
					this.absensi.s = res.data.absensi ? res.data.absensi.s : ''
				}).catch( err => {
					console.log(err.response)
				})
			},
			simpanAbsensi() {
				this.absensi.progress = true
				axios({
					method: 'post',
					url: '/wali/datarapor/absensi/create',
					data: {
						periode_id: this.$page.props.periode,
						rombel_id: this.$page.props.rombel.kode_rombel,
						siswa_id: this.siswa,
						a: this.absensi.a,
						i: this.absensi.i,
						s: this.absensi.s,
					}
				}).then( res => {
					this.absensi.progress = false
					this.getAbsensi()
				}).catch( err => {
					console.log(err.response)
				})
			},
			setDefaultFoto(event, siswa) {
                var foto = (siswa.jk == 'l') ? 'siswa.png' : 'siswi.png'
                event.target.src = '/storage/img/siswas/'+foto
            },
            onSiswaChanged(e){
            	var _this = this
            	this.siswa = e
            	setTimeout(function() {
            		_this.init()
            	}, 500)
            }
		},
		created() {
			this.siswa = this.dialog.siswa.nisn
			var _this = this
			setTimeout(function(){
				_this.init()
			}, 500)
			
		}
	}
</script>

<style>
	.progress {
		position: fixed;
		top: 0;
		right: 0;
		bottom: 0;
		left:  0;
		z-index:  9999999;
		background:  rgba(255,255,255,0.8);
	}
	/* Enter and leave animations can use different */
	/* durations and timing functions.              */
	.slide-fade-enter-active {
	  transition: all .3s ease;
	}
	.slide-fade-leave-active {
	  transition: all .8s cubic-bezier(1.0, 0.5, 0.8, 1.0);
	}
	.slide-fade-enter, .slide-fade-leave-to
	/* .slide-fade-leave-active below version 2.1.8 */ {
	  transform: translateX(10px);
	  opacity: 0;
	}
