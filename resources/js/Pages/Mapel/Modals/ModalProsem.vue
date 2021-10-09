<template>
	<v-dialog v-model="dialog.show" max-width="700">
		<v-overlay :value="overlay">
        <v-progress-circular
          indeterminate
          size="64"
        ></v-progress-circular>
      </v-overlay>
		<v-card>
			<v-toolbar dense>
				<v-toolbar-title>Prosem</v-toolbar-title>
				<v-spacer></v-spacer>
				<v-btn fab small color="error" @click="$emit('hide')"><v-icon>mdi-close</v-icon></v-btn>
			</v-toolbar>
			<v-card-text>
				<v-container>
					<v-row>
						<v-col cols="6">
							<v-radio-group
                              v-model="promes.tematik"
                              row
                              @change="onTematik"
                            >
                              <v-radio
                                  v-for="i in [{value: 1, label:'Tematik'}, {value: 0, label:'Non Tematik'}]"
                                  :label="i.label"
                                  :value="i.value"
                                  :key="i.value"
                                  dense
                                  hide-details
                              ></v-radio>
                            </v-radio-group>
						</v-col>
						<v-col cols="6">
							<v-select v-if="rombels.length > 0" v-model="promes.rombel" rounded dense :items="rombels" item-text="text" item-value="value" hide-details outlined label="Pilih Rombel" @change="onSelectedRombel"></v-select>
						</v-col>
						<v-col cols="6" v-if="temas.length > 0">
							<v-select  v-model="promes.tema" rounded dense :items="temas" item-text="text" item-value="value" hide-details outlined label="Pilih Tema" @change="onSelectedTema"></v-select>
						</v-col>
						<v-col cols="6" v-if="subtemas.length > 0">
							<v-select  v-model="promes.subtema" rounded dense :items="subtemas" item-text="text" item-value="value" hide-details outlined label="Pilih Subtema"></v-select>
						</v-col>
						<v-col cols="6" >
							<v-select :disabled="itemkds.length < 1" v-model="promes.kd_id" rounded dense :items="itemkds" item-text="text" item-value="value" hide-details outlined label="Pilih KD"></v-select>
						</v-col>
						<v-col cols="6">
							<v-select :disabled="promes.tematik == null" v-model="promes.bulan" rounded dense :items="bulans" item-text="text" item-value="value" hide-details outlined label="Pilih Bulan" @change="onSelectedBulan"></v-select>
						</v-col>
						<v-col cols="4">
							<v-select :disabled="weeks.length < 1" v-model="promes.minggu" rounded dense :items="weeks" hide-details outlined label="Pilih Minggu"></v-select>
						</v-col>
						<v-col cols="12" class="d-flex justify-center">
							<v-btn rounded color="primary" @click="simpan" :outlined="dark">Simpan</v-btn>
						</v-col>
					</v-row>
				</v-container>
			</v-card-text>
		</v-card>
		<v-snackbar v-if="snackbar.show" v-model="snackbar.show" color="error" right> {{ snackbar.text  }} </v-snackbar>
	</v-dialog>
</template>

<script>
	import moment from 'moment'
	export default {
		name: 'ModalProsem',
		props: { dialog: Object },
		data: () => ({
			promes: {
				tematik: null,
			},
			rombels: [],
			temas: [],
			subtemas: [],
			mapels: [],
			namabulans: [
			'Januari',
				'Februari',
				'Maret',
				'April',
				'Mei',
				'Juni',
				'Juli',
				'Agustus',
				'September',
				'Oktober', 
				'November', 
				'Desember', 
				
			],
			weeks: [],
			kds: [],
			overlay: false,
			snackbar: { show: false },
			itemkds : [],
			rawtemas: []
		}),
		methods: {
			simpan() {
				this.overlay = true
				let data =  this.promes
				data.semester = this.$page.props.periode_aktif.semester
				data.mapel = this.$page.props.mapel.kode_mapel
				axios({
					method: 'post',
					url: '/dashboard/promes/store',
					data: data
				}).then( res => {
					console.log( res )
					this.overlay = false
					this.$emit('saved')
				}).catch( err => {
					console.log(err)
				})				
			},
			onTematik(e) {
				if ( e == 1 ) {
					if( this.$page.props.user.role == 'wali')  {
						 axios({
                   method: 'post',
                   url: '/dashboard/tema?kelas='+this.$page.props.rombel.kelas_id
               }).then(response => {
                   let temas = []
                   this.rawtemas = response.data.datas
                   response.data.datas.forEach(item => {
                       temas.push({value: item.tema_id, text: item.tema_id.substr(1,1)+'. '+item.tema.teks})
                   })
                   this.temas = temas
               }).catch( err => {
                console.log(err)
               })
					}
				} else {
					// get Rombel
					if(this.$page.props.user.role != 'wali') {
						axios({
							method: 'post',
							url: '/dashboard/rombel?mode=select'
						}).then( res => {
							if(res.data.rombels.length > 0) {
								res.data.rombels.forEach(item=>{
									this.rombels.push({ text: item.label, value: item.kode_rombel})
								})
							} else {
								this.snackbar = { show: true, text: 'Rombel belum ada. Hubungi Admin'}
							}
						}).catch( err => {
							console.log(err)
						})
					} else {
						this.rombels = [ {text: this.$page.props.rombel.label, value: this.$page.props.rombel.kode_rombel}]
					}
				}
			},
			getPemetaan(rombel=null) {
				axios({
					method: 'post',
					url: '/dashboard/pemetaan?q=nontema',
					data: {
						tematik: this.promes.tematik,
						rombel: rombel,
						mapel: this.$page.props.mapel.kode_mapel,
						semester: this.$page.props.periode_aktif.semester
					}
				}).then( res => {
					var kds = []
					var temas = []
					res.data.pemetaans.forEach(item => {
						kds.push({text: item.kd_id+'. '+item.kd.teks, value: item.kd_id, tema: item.tema_id, subtema: item.subtema_id})
						temas.push({text: 'Bab ' +item.tema_id.substr(2, (item.tema_id.length-2)), value: item.tema_id})
					})
					this.kds = kds
					this.temas = temas
					// console.log(res.data.pemetaans)
				}).catch( err => {
					console.log( err )
				})
			},
			onSelectedRombel(e){
				// get Kd
				axios({
					method: 'post',
					url: '/dashboard/pemetaan?q=nontema',
					data: {
						tematik: this.promes.tematik,
						rombel: e,
						mapel: this.$page.props.mapel.kode_mapel,
						semester: this.$page.props.periode_aktif.semester
					}
				}).then( res => {
					var kds = []
					var temas = []
					res.data.pemetaans.forEach(item => {
						kds.push({text: item.kd_id+'. '+item.kd.teks, value: item.kd_id, tema: item.tema_id, subtema: item.subtema_id})
						temas.push({text: 'Bab ' +item.tema_id.substr(2, (item.tema_id.length-2)), value: item.tema_id})
					})
					this.kds = kds
					this.temas = temas
					// console.log(res.data.pemetaans)
				}).catch( err => {
					console.log( err )
				})
			},
			onSelectedTema(e) {
				// alert(e)
				if (this.promes.tematik) {
					var tema = this.rawtemas.filter( item => {
						return item.tema_id == e
					})
					var subtemas = []
					tema[0].tema.subtemas.forEach(item => {
						subtemas.push({text: item.kode_subtema.substr(-1)+', '+item.teks, value: item.kode_subtema})
					})
					this.subtemas = subtemas
				} else {
					var kds = this.kds.filter( item => {
						return item.tema === e
					})
					this.itemkds = kds
				}
			},
			onSelectedBulan(e) {
				var bulan = this.dialog.bulans.filter( item => {
					return item.bulan == e
				})
				this.weeks = bulan[0].minggus
			},
			getWeeks(tahun, bulan) {
				var firstOfMonth = new Date(tahun, bulan, 1)
                    var day = firstOfMonth.getDay() || 6
                    day = day === 1 ? 0 : day
                    if ( day ) { day-- }
                    var diff = 7 - day 
                    var lastOfMonth = new Date( tahun, bulan,0)
                    var lastDate = lastOfMonth.getDate()
                    if ( lastOfMonth.getDay() === 1 ) {
                        diff--
                    }
                    var result = Math.ceil((lastDate - diff) / 7);
                    var jumlah =  result+1
                    var weeks = []
                    for(var i =0;i<jumlah;i++) {weeks.push(i+1)}
                    return weeks
			}
		},
		computed: {
			bulans() {
				// return (this.$page.props.periode_aktif.semester == '1') ? this.ganjil : this.genap
				var bulans = []
				this.dialog.bulans.forEach(b => {
					bulans.push({text: this.namabulans[b.bulan-1], value: b.bulan})
				})
				return bulans
			},
			dark() {
				return this.$vuetify.theme.dark
			}
		},
		mounted() {
			this.promes.rombel = this.dialog.rombel
			this.getPemetaan(this.dialog.rombel)
		}
	}
</script>

// 2   kelas   varchar(255)    utf8mb4 utf8mb4_unicode_ci  NO  NULL            
// 3   semester    varchar(255)    utf8mb4 utf8mb4_unicode_ci  NO  NULL            
// 4   tematik tinyint(1)  NULL    NULL    NO  NULL            
// 5   tema_id varchar(255)    utf8mb4 utf8mb4_unicode_ci  YES NULL            
// 6   subtema_id  varchar(255)    utf8mb4 utf8mb4_unicode_ci  YES NULL            
// 7   mapel_id    varchar(255)    utf8mb4 utf8mb4_unicode_ci  NO  NULL            
// 8   kd_id   varchar(255)    utf8mb4 utf8mb4_unicode_ci  NO  NULL            
// 9   bulan   varchar(255)    utf8mb4 utf8mb4_unicode_ci  NO  NULL            
// 10  minggu  varchar(255)    utf8mb4 utf8mb4_unicode_ci  NO  NULL            
