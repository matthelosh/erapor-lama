<template>
	<Layout :title="page_title">
		<v-container>
			<v-row>
				<v-col cols="12">
					<v-card class="mt-3">
						<v-toolbar dense>
							<v-toolbar-title>Data Guru</v-toolbar-title>
							<v-spacer></v-spacer>
							<v-btn rounded color="primary" @click="baru"><v-icon>mdi-account-plus</v-icon> Tambah</v-btn> &nbsp;
							<v-btn rounded color="info" @click="impor"><v-icon>mdi-microsoft-excel</v-icon> Import</v-btn> &nbsp;
							<v-btn rounded color="success" @click="expor"><v-icon>mdi-microsoft-excel</v-icon> Export</v-btn> &nbsp;
							<v-btn rounded color="warning" @click="cetak"><v-icon>mdi-printer</v-icon> Cetak</v-btn> &nbsp;
							
						</v-toolbar>
						<v-card-text>
							<v-data-table
								:items="gurus"
								:headers="headers"
								:search="search"
								show-select
								dense
								v-model="selectedgurus"
								@toggle-select-all="selectAllToggle"
								>
								<template
									v-slot:top
								>
									<v-container>
										<v-row>
											<v-col cols="4">
												<v-badge
		                                            :content="selectedgurus.length"
		                                            :value="selectedgurus.length"
		                                            color="info"
		                                            overlap
		                                          >
		                                            <v-btn @click.stop="createAccount" dense color="success" :disabled="(selectedgurus.length < 1)" rounded>
		                                                <v-icon>mdi-key</v-icon> 
		                                                Buat Akun
		                                            </v-btn>
		                                        </v-badge>
		                                        <v-badge
		                                            :content="selectedgurus.length"
		                                            :value="selectedgurus.length"
		                                            color="warning"
		                                            overlap
		                                          >
		                                            <v-btn @click.stop="hapusMany" dense color="error" :disabled="(selectedgurus.length < 1)" rounded>
		                                                <v-icon>mdi-account-multiple-remove</v-icon> 
		                                                Hapus Guru
		                                            </v-btn>
		                                        </v-badge>
											</v-col>
											<v-col cols="4">
												<!-- <v-badge
		                                            :content="selectedgurus.length"
		                                            :value="selectedgurus.length"
		                                            color="success"
		                                            overlap
		                                          >
		                                            <v-btn @click.stop="resetMany" dense color="warning" :disabled="(selectedgurus.length < 1)" rounded>
		                                                <v-icon>mdi-lock-reset</v-icon> 
		                                                Reset Password
		                                            </v-btn>
		                                        </v-badge> -->
											</v-col>
											<v-col cols="4">
												<v-text-field
													v-model="search"
													label="Cari"
													append-icon="mdi-magnify"
													outlined
													rounded
													dense
													hide-details
													/>
											</v-col>
											
										</v-row>
									</v-container>
								</template>
								<template v-slot:item.data-table-select="{ item, isSelected, select }">
                                    <v-simple-checkbox
                                        :value="isSelected"
                                        :readonly="item.user.length > 0"
                                        :disabled="item.user.length > 0"
                                        :color="item.user.length > 0 ? 'error' : 'success'"
                                        @input="select($event)"
                                    ></v-simple-checkbox>
                                </template>
								<template
									v-slot:item.foto="{item}"
									>
									<v-avatar size="50" @click="detail(item)" style="cursor:pointer;">
										<img :src="'/storage/img/users/'+item.nip+'.jpg'"
										alt="Foto"
										@error="setDefaultFoto($event,item.jk)"
										/>
									</v-avatar>
								</template>
								<template 
									v-slot:item.opsi="{item}"
								>
									<span>
										<v-btn color="error" small rounded @click="hapus(item)">
											<v-icon>mdi-account-remove</v-icon>
										</v-btn>
										<!-- <v-btn color="warning" small rounded @click="reset">
											<v-icon>mdi-lock-reset</v-icon>
										</v-btn> -->
										
									</span>	
								</template>
							</v-data-table>
						</v-card-text>
					</v-card>
				</v-col>
			</v-row>
		</v-container>
		<modal-guru v-if="modalGuru.show" :dialog="modalGuru" @hide="modalGuru.show = false" @getGurus="getGurus"></modal-guru>
		<v-snackbar v-model="snackbar.show" :color="snackbar.color" right>{{ snackbar.text }}</v-snackbar>
		<confirm-dialog ref="confirm"></confirm-dialog>
	</Layout>

</template>

<script>
	import Download from '../../Plugins/Download'
	import Layout from '../../Layout/Dashboard'
	import ModalGuru from './Modals/ModalGuru'
	import ConfirmDialog from '../../Components/Modals/ConfirmDialog'
	export default {
		name: 'Guru',
		props: {page: String, page_title: String },
		components: { Layout, ModalGuru, ConfirmDialog },
		data: () => ({
			snackbar: { show: false },
			modalGuru: {show: false},
			search: '',
			gurus: [],
			headers: [
				{ text: 'No', value: 'index', sortable: false },
				{ text: 'Foto', value: 'foto',},
				{ text: 'NIP', value: 'nip',},
				{ text: 'Nama', value: 'name',},
				{ text: 'Email', value: 'email',},
				{ text: 'HP', value: 'hp',},
				{ text: 'Opsi', value: 'opsi',},
			],
			dialogImport: false,
			selectedgurus: [],
			disabledCount: 0,
		}),
		methods: {
			cetak() {
				var items = this.gurus
				if (items.length < 1 ) {
					this.snackbar = { show: true, color: 'error', text: 'Tidak ada data guru yang dapat dicetak.'}
					return false
				}
				var win = window.open('', '_blank', 'width=900,height=800,left=100')
	            var tr = ''
	                items.forEach((guru,index) => {
	                    tr += `<tr>
	                            <td>${index+1}</td><td>${guru.nip}</td><td>${guru.name}</td><td>${(guru.user.length > 0 ) ? guru.user[0].username : '-'}</td><td>${guru.email}</td><td>${guru.jk}</td><td>${guru.alamat}</td><td>${guru.hp}</td>
	                        </tr>`
	                })
	                        var html = `
			                    <!doctype html>
			                    <html>
			                        <head>
			                            <title>Cetak Data Pengguna</title>
			                            <style>
			                                * {
			                                    font-family: 'sans-serif';
			                                }
			                                h1 {
			                                    text-align: center;
			                                    text-transform: uppercase;
			                                }
			                                table {
			                                    border-collapse: collapse;
			                                    font-family: 'sans-serif';
			                                }
			                                table tbody tr:nth-child(odd) {
			                                    background: #efefef;
			                                }
			                                th {
			                                    text-align: center;
			                                }
			                                td {
			                                    padding: 5px 20px;
			                                }
			                            </style>
			                        </head>
			                        <body>
			                            <div class="wrapper">
			                                <h1>Data Pengguna</h1>
			                                <table border="1">
			                                    <thead>
			                                        <tr>
			                                            <th>No</th><th>NIP</th><th>Nama</th><th>Username</th><th>Email</th><th>JK</th><th>Alamat</th><th>HP</th>
			                                        </tr>
			                                    </thead>
			                                    <tbody>
			                                        ${tr}
			                                    </tbody>
			                                </table>
			                            </div>
			                        </body>
			                    </html>
			            
			            `
			            win.document.write(html)

			            setTimeout(function(){
			                win.print()
			                win.close()
			            },500)

			},
			async hapusMany() {
				var gurus = this.selectedgurus
				this.$refs.confirm.open("Hapus Data", "Yakin Menghapus data - data guru tersebut ?")
				.then((lanjut) => {
					axios({
						method: 'delete',
						url: '/dashboard/guru/many',
						data: {gurus : gurus}
					}).then( res => {
						this.snackbar = { show: true, color: 'success', text: res.data.msg }
                        // console.log(response)
                        this.getGurus()
					}).catch( err => {
						console.log(err.response)
					})
				}).catch( err => {
                    this.snackbar = { show: true, color: 'error', text: err.response.data.msg }
                })
				
			},
			async hapus(item) {
				this.$refs.confirm.open("Hapus Data", "Yakin menghapus data Guru "+item.name+"?")
                .then((lanjut)=>{
                    if (lanjut) {
                        axios({
                            method: 'delete',
							url: '/dashboard/guru/'+item.id
                        }).then(response => {
                            this.snackbar = { show: true, color: 'success', text: response.data.msg }
                            // console.log(response)
                            this.getGurus()
                        }).catch(err => {
                            this.snackbar = { show: true, color: 'error', text: err.response.data.msg }
                        })
                    }
                }).catch( err => {
                    console.log(err)
                })
			},
			expor() {
				if (this.gurus.length < 1 ) {
					this.snackbar = { show: true, color: 'error', text: 'Tidak ada data guru yang dapat dicetak.'}
					return false
				}
				var items = []
				this.gurus.forEach(item => {
					items.push({no: item.index, nip: item.nip, nama: item.name, email: item.email, hp: item.hp, role: item.role, alamat: item.alamat,jk: item.jk, username: (item.user.length > 0 ? item.user[0].username : null), level: (item.user.length > 0 ? item.user[0].level : null)})
				})
				Download.UnduhExcel(items, 'Data Guru')
			},
			createAccount() {
				axios({
                    method: 'post',
                    url: '/admin/user/grantguru',
                    data: { gurus: this.selectedgurus}
                }).then( res => {
                    this.selectedgurus = []
                    this.disabledCount = 0
                    this.getGurus()
                    this.snackbar = {
                        show: true,
                        color: 'success',
                        text: res.data.msg
                    }
                }).catch( err => {
                    this.snackbar = {
                        show: true,
                        color: 'error',
                        text: err.response.msg
                    }
                })
			},
			selectAllToggle(props) {
                if ( this.selectedgurus.length != props.items.length - this.disabledCount) {
                    this.selectedgurus = []
                    const self = this
                    props.items.forEach( item => {
                        if ( item.user.length < 1 ) {
                            self.selectedgurus.push( item )
                        }
                    })
                } else {
                    this.selectedgurus = []
                    // return false
                }
                // console.log(props)
            },
			baru(){
				this.modalGuru = {
					show: true, mode: 'baru', single: true
				}
			},
			impor(){
				this.modalGuru = {
					show: true, mode: 'impor', impor: true
				}
			},
			detail(item) {
				this.modalGuru = {
					show: true,
					mode: 'edit',
					single: true,
					guru: item
				}
			},
			getGurus() {
				axios({
					method: 'post',
					url: '/admin/guru'
				}).then( res => {
					var gurus = []
					res.data.gurus.forEach((item, index) => {
						item.index = index+1
						gurus.push(item)
						// item.disabled = (item.user.length < 1 ) ? true : false
						if( item.user.length > 0 ) {
							this.disabledCount += 1
						}
						
					})
					this.gurus = gurus
				}).catch( err => {

				})
			},
			setDefaultFoto(event,jk) {
				var foto = (jk == 'l') ? '/storage/img/users/user_l.png' : '/storage/img/users/user_p.png'
				event.target.onerror = null
				event.target.src = foto
			}
		},
		computed: {

		},
		mounted() {
			this.getGurus()
		}
	}
</script>