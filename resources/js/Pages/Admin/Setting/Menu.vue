<template>
	<Layout :title="page_title">
		<v-container>
			<v-row>
				<v-col cols="12">
					<v-card>
						<v-toolbar dense>
							<v-toolbar-title>Pengaturan Menu</v-toolbar-title>
							<v-spacer></v-spacer>
							<v-btn rounded color="primary" @click="tambah">
								<v-icon>mdi-plus</v-icon>
								Tambah
							</v-btn>
						</v-toolbar>
						<v-card-text>
							<v-data-table
 								:items="menus"
 								:headers="headers"
 								dense
 								:loading="loading"
 								:search="search"
							>
								<template v-slot:top>
									<v-container>
										<v-row>
											<v-col cols="4">
												
											</v-col>
											<v-col cols="4">
												
											</v-col>
											<v-col cols="4">
												<v-text-field v-model="search" outlined rounded dense hide-details label="Cari"></v-text-field>	
											</v-col>
										</v-row>
									</v-container>
								</template>
								<template v-slot:item.no="{item}"><span>{{ item.no }}</span></template>
								<template v-slot:item.parent="{item}"><span>{{ item.parent ? item.parent.label : 'Induk' }}</span></template>
								<template v-slot:item.label="{item}">
									<v-btn text color="info" small @click="edit(item)">{{ item.label }}</v-btn>
								</template>
								<template v-slot:item.icon="{item}">
									<v-icon>mdi-{{ item.icon }}</v-icon>
									<span>{{ item.icon }}</span>
								</template>
								<template v-slot:item.roles="{item}">
									<span v-for="role in item.roles">
										{{role.label}}, 
									</span>
								</template>
								<template v-slot:item.opsi="{item}">
									<v-btn rounded small color="error" @click="hapus(item)"><v-icon>mdi-delete</v-icon></v-btn>
								</template>
								
							</v-data-table>
						</v-card-text>
					</v-card>
				</v-col>
			</v-row>
		</v-container>
		<v-dialog v-if="dialog.show" v-model="dialog.show" max-width="600">
			<v-card>
				<v-toolbar dense>
					<v-toolbar-title>Form Menu</v-toolbar-title>
					<v-spacer></v-spacer>
					<v-btn fab color="error" @click="closeDialog" small><v-icon>mdi-close</v-icon></v-btn>
				</v-toolbar>
				<v-card-text>
					<v-container>
					<v-row>
						<v-col cols="12" sm="6">
							<v-select v-model="selectedmenu.parent_id" :items="parents" item-text="label" item-value="id" dense rounded outlined label="Induk" hide-details></v-select>
						</v-col>
						<v-col cols="12" sm="6">
							<v-text-field v-model="selectedmenu.label" dense rounded outlined label="Label Menu" hide-details></v-text-field>
						</v-col>
						
						<v-col cols="12" sm="6">
							<v-text-field v-model="selectedmenu.url" dense rounded outlined label="URL Menu" hide-details></v-text-field>
						</v-col>
						<v-col cols="12" sm="6">
							<v-text-field v-model="selectedmenu.icon" dense rounded outlined label="Ikon Menu" hide-details :prepend-inner-icon="'mdi-'+selectedmenu.icon"></v-text-field>
						</v-col>
						<v-col cols="12" >
							<v-select v-model="selectedmenu.roles" return-object :items="roles" multiple item-value="id" item-text="label" dense rounded outlined label="Role" hide-details ></v-select>
						</v-col>
						<v-col cols="12" class="d-flex justify-center">
							<v-btn rounded color="primary" @click="simpan" :disabled="loading" :loading="loading">Simpan</v-btn>
						</v-col>
					</v-row>
				</v-container>
				</v-card-text>
			</v-card>
		</v-dialog>
		<confirm-dialog ref="confirm"></confirm-dialog>
	</Layout>
</template>

<script>
	import Layout from '../../../Layout/Dashboard'
	import ConfirmDialog from '../../../Components/Modals/ConfirmDialog'
	export default {
		name: 'Menu',
		components: { Layout, ConfirmDialog },
		props: { page_title: String, title: String},
		data: () => ({
			search: '',
			loading: false,
			menus: [],
			headers: [
				{ text: 'No', value: 'no' , filterable: false},
				{ text: 'Induk', value: 'parent' , filterable: false},
				{ text: 'Label', value: 'label' , filterable: false},
				{ text: 'URL', value: 'url' , filterable: false},
				{ text: 'Ikon', value: 'icon' , filterable: false},
				{ text: 'Role', value: 'roles' },
				{ text: 'Opsi', value: 'opsi' , filterable: false},
			],
			selectedmenu: {},
			roles: [],
			dialog: { show: false},
			loadin: false,
		}),
		methods: {
			getMenus() {
				this.loading = true
				axios({
					method: 'post',
					url: '/dashboard/admin/pengaturan/menu'
				}).then( res => {
					var menus = []
					res.data.menus.forEach((item,index) => {
						item.no = index+1
						menus.push(item)
					})
					this.menus = menus
					this.roles = res.data.roles
					this.loading = false
				}).catch( err => {
					console.log(err)
				})
			},
			tambah() {
				this.dialog.show = true
			},
			edit(item) {
				this.dialog.show = true
				this.selectedmenu = item

			},
			async hapus(item) {
            // this.hapus = {open: true, user: item}
            this.$refs.confirm.open("Hapus Data", "Yakin Menghapus Menu "+item.label+"?")
                .then((lanjut) => {
                    if(lanjut) {
                        axios({
                            method: 'delete',
                            url: '/dashboard/admin/pengaturan/menu/'+item.id,
                            // data: {users : users}
                        }).then( res => {
                            // this.snackbar = { show: true, color: 'success', text: res.data.msg }
                            // console.log(response)
                            this.getMenus()
                        }).catch( err => {
                            console.log(err.response)
                        })
                    } else {
                        return false
                    }
                }).catch( err => {
                    this.snackbar = { show: true, color: 'error', text: err.response.data.msg }
                })
        },
			closeDialog() {
				this.dialog.show = false
				this.selectedmenu = {}
			},
			simpan(){
				this.loading = true
				var roles = this.selectedmenu.roles
				var clean = roles.filter((roles, index, self) => index === self.findIndex((t) => (t.id === roles.id)))
				console.log(clean)
				axios({
					method: 'post',
					url: '/dashboard/admin/pengaturan/menu/store',
					data: this.selectedmenu
				}).then( res => {
					this.getMenus()
					this.loading=false
				})
			}
		},
		computed: {
			parents() {
				return this.menus.filter(menu => {
					return menu.parent_id == '0'
				})
			}
		},
		mounted() {
			this.getMenus()
		}
	}
</script>