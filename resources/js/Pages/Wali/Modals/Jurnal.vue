<template>
	<div>
		<v-dialog v-model="dialog.show" fullscreen transition="dialog-bottom-transition" hide-overlay>
			<v-card>
				<v-toolbar dense>
					<v-toolbar-title>JURNAL P<span class="d-none d-sm-inline">EMBELAJARAN</span> {{ dialog.jurnal.pembelajaran.ke }}</v-toolbar-title>
					<v-spacer></v-spacer>
					<v-btn @click="showFoto" color="info" small fab class="mr-2"><v-icon>mdi-camera</v-icon></v-btn>
					<v-btn @click="showAbsen" color="info" small fab class="mr-2"><v-icon>mdi-clipboard-list-outline</v-icon></v-btn>
					<v-btn @click="$emit('hide')" color="error" fab small><v-icon>mdi-close</v-icon></v-btn>
				</v-toolbar>
				<v-card-text class="pb-15">
						<v-container>
							<p >Tema: {{ dialog.jurnal.tema.kode.substr(-1) }}. {{ dialog.jurnal.tema.label }}</p>
							<p >Sub Tema: {{ dialog.jurnal.subtema.kode.substr(-1) }}. {{ dialog.jurnal.subtema.label }}</p>
							<v-row class="mt-2">
								<v-col  v-for="mupel in mupels" cols="12" sm="6" :key="mupel.kode_mapel">
									<div class="d-flex justify-between">
									Materi {{ mupel.label }} KD: [<span v-for="kd in mupel.kds" :key="kd.kode_kd">{{ kd.kode_kd }},</span>]
									<v-spacer></v-spacer>
									<!-- {{ mupels }} -->
									</div>
									<tiptap-vuetify v-if="Object.keys(jurnal.materi).length > 0" v-model="jurnal.materi[mupel.kode_mapel].teks" :extensions="extensions"></tiptap-vuetify>

								</v-col>
								<v-col cols="12">
									<v-select v-model="jurnal.metode" label="Metode" :items="metodes" rounded dense hide-details outlined></v-select>
								</v-col>
								<v-col cols="12">
									<v-text-field v-model="jurnal.media" label="Media Pembelajaran" rounded dense hide-details outlined></v-text-field>
								</v-col>
								<v-col cols="12">
									Kegiatan Siswa
									<tiptap-vuetify v-model="jurnal.kegiatan" :extensions="extensions"></tiptap-vuetify>
								</v-col>
								<v-col cols="12">
									Tagihan
									<tiptap-vuetify v-model="jurnal.tagihan" :extensions="extensions"></tiptap-vuetify>
								</v-col>
								<v-col cols="12">
									Keterangan
									<tiptap-vuetify v-model="jurnal.keterangan" :extensions="extensions"></tiptap-vuetify>
								</v-col>
								<v-col cols="12" class="d-flex justify-center">
									<v-btn color="primary" rounded @click.stop="simpan" :disabled="loading" :loading="loading">Simpan</v-btn>
								</v-col>
							</v-row>

						</v-container>
					
				</v-card-text>
			</v-card>
		</v-dialog>
		<absensi v-if="absen.show" :dialog="absen" @hide="hideAbseni"></absensi>
		</div>
</template>

<script>
	import moment from 'moment'
	import Absensi from './Absensi'
	import { TiptapVuetify, Heading, Bold, Italic, Strike, Underline, Code, Paragraph, BulletList, OrderedList, ListItem, Link, Blockquote, HardBreak, HorizontalRule, History } from 'tiptap-vuetify'
	moment.locale('id')
	export default {
		name: 'Jurnal',
		props: { dialog: Object },
		components: { TiptapVuetify, Absensi },
		data: () => ({
			loading: false,
			metodes: [
				 'Project Based Learning',
				'Daring',
				'Luring',
				'Guru Kunjung',
				'Terpadu',
				'Blended',
				'Lainya..',
			],
			jurnal: {
				materi: {}
			},
			extensions : [  History,
              Blockquote,
              Link,
              Underline,
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
              Paragraph,
              HardBreak
            ],
            absen: { show: false },
            foto: { show: false },
            value: 1
		}),
		methods: {
			simpan() {
				this.loading = true
				let data = this.jurnal
				data.materi  = JSON.stringify(this.jurnal.materi)
				axios({
					method: 'post',
					url: '/dashboard/jurnal/create',
					data: this.jurnal
				}).then( res => {
					console.log(res)
					this.loading = false
					this.$emit('hide')
				}).catch( err => {
					console.log( err )
				})
			},
			showFoto() {
				this.foto = { show: true }
			},
			
			showAbsen() {
				this.absen = { show: true }
			},
			hideAbseni() {
				this.absen = { show: false }
			},
			initJurnal() {
				// Get Jurnal if exists
				axios({
					method: 'post',
					url: '/dashboard/jurnal/show',
					data: { pembelajaran_id: this.dialog.jurnal.pembelajaran.kode_pembelajaran }
				}).then( res => {
					if (res.data.jurnal) {
						var jurnal = res.data.jurnal
						jurnal.materi = JSON.parse(jurnal.materi)
						this.jurnal = jurnal
					} else {
						var mupels = JSON.parse(this.dialog.jurnal.pembelajaran.mupels)
						var mapels = {}
						mupels.forEach(mapel => {
							mapels[mapel.kode_mapel] = { teks: '', label: mapel.label}
						})
						// this.jurnal.materi = mapels
							this.jurnal = {
								rombel_id: this.dialog.jurnal.pembelajaran.rombel.kode_rombel,
								tema_id: this.dialog.jurnal.tema.kode,
								subtema_id: this.dialog.jurnal.subtema.kode,
								pembelajaran_id: this.dialog.jurnal.pembelajaran.kode_pembelajaran,
								tanggal: moment(new Date()).format('YYYY-MM-DD'),
								metode: '',
								materi : mapels,
								media: '',
								kegiatan: '',
								tagihan: '',
								keterangan: ''
							}
					}

				}).catch( err => {
					console.log( err )
				})
			}
		},
		computed: {
			mupels() {
					var data = JSON.parse(this.dialog.jurnal.pembelajaran.mupels)
					return data
			},
			color () {
		        switch (this.value) {
		          case 0: return 'blue-grey'
		          case 1: return 'teal'
		          case 2: return 'brown'
		          case 3: return 'indigo'
		          default: return 'blue-grey'
		        }
		      }, 
		},
		mounted() {
			this.initJurnal()
		}
	}
</script>

<style>
	.v-application p {
		margin-bottom:  0!important;
	}
</style>