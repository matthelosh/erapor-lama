<template>
	<Layout :title="page_title">
		<v-container>
			<v-row>
				<v-col cols="12">
					<v-card>
						<v-toolbar dense color="secondary">
							<v-toolbar-title>
								<v-icon>mdi-microsoft-word</v-icon>
								Rapor Siswa
							</v-toolbar-title>
							<v-spacer></v-spacer>
							<v-btn rounded color="success" @click="ledger">
                                <v-icon>mdi-table</v-icon>
                                Rekap
                            </v-btn>
							<v-text-field label="Cari" v-model="cari" prepend-inner-icon="mdi-magnify" hide-details rounded outlined dense color="primary" style="width:10px" clearable class="ml-5"></v-text-field>
						</v-toolbar>
						<v-card-text>
							<v-data-table :headers="sheaders" :items="siswas" :search="cari">
                                <template v-slot:item.foto="{item}">
                                    <v-avatar size="46">
                                        <img :src="'/storage/img/siswas/'+item.nisn+'.jpg'" @error="setDefaultFoto($event, item)" class="foto" />
                                    </v-avatar>
                                </template>
                               <!--  <template v-slot:item.rombels="{item}" v-if="$page.props.user.role == 'admin'">
                                    <div>
                                        <span v-if="item.rombels.length > 0">
                                            {{item.rombels[0].label}}
                                        </span>
                                        <span v-else>
                                            Belum Masuk Rombel
                                        </span>
                                    </div>
                                </template> -->
                                <template v-slot:item.jk="{item}">
                                	<span>
                                		{{ (item.jk == 'l') ? 'L' : 'P' }}
                                	</span>
                                </template>
                                <template v-slot:item.options="{item}"> 
                                    <span>
                                    	
	                                    <v-btn rounded color="primary" dark small @click="dataRapor(item)">
	                                        <v-icon>mdi-cogs</v-icon>
	                                        Data
	                                    </v-btn>
	                                    
	                                    <v-btn rounded color="info" dark small @click="cetak(item)">
	                                        <v-icon>mdi-printer</v-icon>
	                                        Cetak
	                                    </v-btn>
	                                    
                                    </span>
                                </template>
                            </v-data-table>
						</v-card-text>
					</v-card>
				</v-col>
			</v-row>
		</v-container>
		<modal-rapor v-if="modalRapor.show" :dialog="modalRapor" @hide="modalRapor.show = false"></modal-rapor>
		<data-rapor v-if="modalDataRapor.show" :dialog="modalDataRapor" @hide="modalDataRapor.show = false"></data-rapor>
		<ledger v-if="modalLedger.show" :dialog="modalLedger" @hide="modalLedger.show = false"></ledger>
	</Layout>
</template>

<script>
import Layout from '../../Layout/Dashboard'
import ModalRapor from './Components/Modals/ModalRapor'
import DataRapor from './Components/Modals/DataRapor'
// import Ledger from '../../Components/Modals/Ledger'
export default {
	name: 'Rapor',
	props: {
		page: String,
		page_title: String
	},
	components: { Layout, ModalRapor, DataRapor, Ledger },
	data: () =>({
		cari: '',
		modalRapor: {
			show: false,
			
		},
		modalDataRapor: {
			show: false
		},
		modalLedger: {
			show: false
		},
		siswas: [],
		sheaders: [
                {value: 'index', text: 'No', sortable: false},
                {value: 'foto', text: 'FOTO'},
                {value: 'nisn', text: 'NISN'},
                {value: 'nis', text: 'NIS'},
                {value: 'nama', text: 'Nama'},
                {value: 'jk', text: 'JK'},
                // {value: 'agama', text: 'Agama'},
                {value: 'options', text: 'Opsi'},
            ],
	}),
	methods:{
		ledger() {
			this.modalLedger = {
				show: true,
				// siswa: item,
				// siswas: this.siswas
			}
		},
		dataRapor(item){
			this.modalDataRapor = {
				show: true,
				siswa: item,
				siswas: this.siswas
			}
		},
		cetak(item){
			this.modalRapor = {
				show: true,
				siswa: item,
				siswas: this.siswas
			}
		},
		getSiswas(){
			let role = this.$page.props.user.role

                
                axios({
                    method: 'post',
                    url: '/siswa/rapor/'+this.$page.props.rombel.kode_rombel
                }).then(res => {
                    let siswas = []
                    res.data.siswas.forEach((siswa,index) => {
                        siswa.index = index+1
                        siswas.push(siswa)
                    })
                    this.siswas = siswas
                    // this.grafik(res.data.siswas)
                }).catch(err => {
                    console.log(err)
                })
		},
		setDefaultFoto(event, siswa) {
                var foto = (siswa.jk == 'l') ? 'siswa.png' : 'siswi.png'
                event.target.src = '/storage/img/siswas/'+foto
            },
	},
	computed: {

	},
	created() {
		this.getSiswas()
	}
}
</script>
	
<style scoped>
	
</style>