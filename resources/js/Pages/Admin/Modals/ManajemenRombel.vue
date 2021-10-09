<template>
	<div>
		<v-dialog 
			v-model="dialog.show"
	        transition="dialog-bottom-transition"
	        fullscreen
	        hide-overlay
		>
			<v-card>
				<v-toolbar dense>
					<v-toolbar-title>
                    <v-icon>mdi-google-classroom</v-icon>
                    	Manajemen Rombel {{ dialog.rombel.label }}
	                </v-toolbar-title>
	                <v-spacer></v-spacer>
	                <v-btn icon @click="$emit('hide')">
	                    <v-icon>mdi-close</v-icon>
	                </v-btn>
	            </v-toolbar>
	            <v-card-text>
	            	<v-container>
	            		<v-row>
	            			<v-col cols="12" sm="6">
	            				<v-data-table :search="membersearch" show-select :items="members" :headers="headermembers" dense v-model="selectedmembers" item-key="id" class="elevation-3" :loading="loadingmembers" loading-text="Tunggu Sebentar ..." loader-height="5">
	            					<template v-slot:top>
	            						<v-container>
		            						<v-row>
			            						<v-col cols="12" sm="4">
			            							<v-badge
												        :content="selectedmembers.length"
												        :value="selectedmembers.length"
												        color="success"
												        overlap
												      >
				            							<v-btn @click.stop="keluarkan" dense color="error" :disabled="(selectedmembers.length < 1)" rounded>
				            								<v-icon>mdi-close-circle</v-icon> 
				            								Keluarkan
				            							</v-btn>
				            						</v-badge>
				            					</v-col>
				            					<v-col cols="8">
				            						<input type="file" ref="filesiswa" class="d-none" @change="onFileSelected" accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel, *.csv, *.ods">
				            						<v-btn @click.stop="impor" dense color="success"  rounded :loading="imporMembers.loading" >
			            								<v-icon>mdi-microsoft-excel</v-icon> 
			            								{{ imporMembers.label }}
			            							</v-btn>
				            						
			            						</v-col>
			            						<v-col cols="12" sm="3">
			            							<v-badge 
				            							v-if="dialog.rombel.kelas_id == '6'"
												        :content="selectedmembers.length"
												        :value="selectedmembers.length"
												        color="success"
												        overlap
												      >
				            							<v-btn @click.stop="nonaktifkan" dense color="error" :disabled="(selectedmembers.length < 1)" rounded>
				            								<v-icon>mdi-close-circle</v-icon> 
				            								Lulus
				            							</v-btn>
				            						</v-badge>
				            						
			            						</v-col>
			            						<v-col cols="12" sm="6">
				            						<v-text-field label="Cari" dense hide-details rounded outlined append-icon="mdi-magnify" v-model="membersearch" clearable></v-text-field>
				            					</v-col>
				            				</v-row>
				            			</v-container>
	            					</template>
	            				</v-data-table>
	            			</v-col>
	            			<v-col cols="12" sm="6">
	            				
	            				<v-data-table show-select :items="nonmembers" :headers="headernonmembers" v-model="selectednonmembers" item-key="id" dense class="elevation-3" :search="nonmembersearch" :loading="loadingnonmembers" loading-text="Tunggu Sebentar ..." loader-height="5">
	            					<template v-slot:top>
	            						<v-container>
		            						<v-row>
			            						<v-col cols="12" sm="8">
			            							<v-badge
												        :content="selectednonmembers.length"
												        :value="selectednonmembers.length"
												        color="error"
												        overlap
												      >
				            							<v-btn @click.stop="masukkan" dense color="success" :disabled="(selectednonmembers.length < 1)" rounded>
				            								<v-icon>mdi-check-circle</v-icon> Masukkan
				            							</v-btn>
				            							
				            							
				            						</v-badge>
				            						<v-badge
												        :content="selectednonmembers.length"
												        :value="selectednonmembers.length"
												        color="error"
												        overlap
												      >
					            						<v-btn @click.stop="nonaktifkan" dense color="warning" :disabled="(selectednonmembers.length < 1)" rounded>
				            								<v-icon>mdi-check-circle</v-icon> Non-Aktifkan
				            							</v-btn>
			            							</v-badge>
			            						</v-col>
			            						<!-- <v-col cols="12" sm="3">
			            							{{ selectednonmembers.length }} terpilih
			            						</v-col> -->
			            						<v-col cols="12" sm="4">
				            						<v-text-field label="Cari" rounded dense hide-details outlined append-icon="mdi-magnify" v-model="nonmembersearch"></v-text-field>
				            					</v-col>
				            				</v-row>
				            			</v-container>
	            					</template>
	            				</v-data-table>
	            			</v-col>
	            		</v-row>
	            	</v-container>
	            </v-card-text>
			</v-card>
		</v-dialog>
		<confirm-dialog ref="confirm"></confirm-dialog>
		<v-snackbar v-model="snackbar.show" :color="snackbar.color" bottom right multi-line>
			{{ snackbar.text }}
		</v-snackbar>
		<impor-member v-if="imporMembers.show" :dialog="imporMembers" @hide="closeImpor"></impor-member>
	</div>
</template>

<script>
	import ConfirmDialog from '../../../Components/Modals/ConfirmDialog'
	import ImporMember from './ImporMember'
	export default {
		name: 'ManajemenRombel',
		props: {
			dialog: Object
		},
		components: { ConfirmDialog, ImporMember },
		data: () => ({
			imporMembers: {
				label: 'Impor dari file',
				loading: false,
			},
			loadingmembers: false,
			loadingnonmembers: false,
			snackbar: {show: false},
			membersearch:'',
			nonmembersearch:'',
			members:[],
			selectedmembers:[],
			selectednonmembers:[],
			nonmembers:[],
			headermembers:[
				{ text: 'No', value: 'index', sortable: false },
				{ text: 'NISN', value: 'nisn' },
				{ text: 'Nama', value: 'nama' }
			],
			headernonmembers:[
				{ text: 'No', value: 'index', sortable: false },
				{ text: 'NISN', value: 'nisn' },
				{ text: 'Nama', value: 'nama' }
			]
		}),
		methods: {
			closeImpor(){
				this.imporMembers = {
					show: false,
					loading: false,
					label: 'Impor dari file',
					e: null
				}
				this.$refs.filesiswa.value = null
				this.initialize()
			},
			impor() {
				this.imporMembers.loading = true
				this.$refs.filesiswa.click()
			},
			onFileSelected(e) {
				this.imporMembers = {
					loading: true,
					show: true,
					e: e,
					rombel: this.dialog.rombel
				}
				// console.log(e)
			},
			async nonaktifkan() {
                // alert(siswa.nama)
                if ( await this.$refs.confirm.open("Confirm", "Yakin Me-nonaktifkan siswa terpilih?")) {
                    axios({
                        method: 'put',
                        url: '/admin/siswa/nonaktifkan',
                        data: { siswas: this.selectednonmembers }
                    }).then(response => {
                        // alert(response.data.msg)
                        this.snackbar = {show:true, color:'success',text: response.data.msg }
                        this.initialize()
                    }).catch(err=>{
                        this.snackbar = { show: true, color: 'error', text: err.response.data.msg}
                    })
                }
            },
			masukkan(){
				let rombel_id = this.dialog.rombel.id
				axios({
					method: 'post',
					url: '/admin/rombel/masukkan?id='+rombel_id,
					data: {data:this.selectednonmembers}
				}).then(response => {
					// console.log(response)
					// 

					this.initialize()
					this.snackbar = { show: true, color: 'success', text: response.data.msg }
				}).catch( err => {
					this.snackbar = { show: true, color: 'error', text: err.response.data.msg}
				})
			},
			keluarkan() {
				let rombel_id = this.dialog.rombel.id
				axios({
					method: 'post',
					url: '/admin/rombel/keluarkan?id='+rombel_id,
					data: {data:this.selectedmembers}
				}).then( response => {
					this.initialize()
					// this.snackbar = { show: true, color: 'success', text: response.data.msg }
					
				}).catch( err => {
					this.snackbar = { show: true, color: 'error', text: err.response.data.msg }
				})
			},
			initialize() {
				const rombel = this.dialog.rombel
				this.members = []
				this.nonmembers = []
				this.selectedmembers = []
				this.selectednonmembers = []
				this.getMembers(rombel.id)
				this.getNonMembers(rombel.periode_id)
			},
			getMembers(id) {
				this.loadingmembers = true
				axios({
					method: 'post',
					url: '/admin/rombel/'+id
				}).then(response => {
					response.data.members.forEach((siswa,index) => {
						siswa.index = index+1
						this.members.push(siswa)
					})
					this.loadingmembers = false
				})
			},
			getNonMembers(periode) {
				this.loadingnonmembers = true
				axios({
					method: 'post',
					url: '/admin/siswa/nonmembers?periode='+periode
				}).then( response => {
					// console.log(response)
					response.data.nonmembers.forEach((siswa,index) => {
						siswa.index = index+1
						this.nonmembers.push(siswa)
					})
					this.loadingnonmembers = false
				})
			}
		},
		computed: {},
		mounted(){
			this.initialize()
		}
	}
</script>