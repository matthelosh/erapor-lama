<template>
	<v-dialog v-model="dialog.show" fullscreen transition="dialog-bottom-transition" hide-overlay>
		<v-card>
			<v-toolbar dense>
				<v-toolbar-title>
					Cetak Jurnal
				</v-toolbar-title>
				<v-spacer></v-spacer>
					<v-btn rounded color="info" @click="cetak" class="mr-3"><v-icon>mdi-printer</v-icon> Cetak</v-btn>
					<v-btn fab small color="error" @click="$emit('hide')"><v-icon>mdi-close</v-icon></v-btn>
			</v-toolbar>
			<v-card-text>
				<v-container>
					<v-sheet elevation="3" id="print" style="padding: 20px;" v-if="jurnals.length > 0">
						<h3 style="text-align: center;margin-bottom: 20px;">JURNAL KEGIATAN BELAJAR MENGAJAR ( KBM )</h3>
						<v-row>
							<v-col cols="4">
								<tr>
									<td>Satuan Pendidikan</td><td>:</td><td>Sekolah</td>
								</tr>
								<tr>
									<td>Kelas / Semester</td><td>:</td><td>IV (EMPAT) / 2 ( Dua )</td>
								</tr>
								
							</v-col>
							<v-col cols="4"></v-col>
							<v-col cols="4">
								<tr>
									<td>Hari/Tanggal</td><td>:</td><td>07 Juli 2021</td><br>
								</tr>
								<tr>
									<td>Tahun Pelajaran</td><td>:</td><td>2021/2022</td>
								</tr>
							</v-col>
							<v-col cols="12">
								<table class="table-jurnal" width="100%" border="1">
									<thead>
										<tr>
											<th>No</th><th>Jam Ke</th><th>Tema, Subtema, Mupel</th><th>Materi</th><th>Metode</th><th>Media</th><th>Aktivitas Siswa</th><th>Tagihan</th><th>Keterangan</th>
										</tr>
									</thead>
									<tbody>
										<tr v-for="jurnal in jurnals">
											<td style="text-align: center;">{{ jurnal.no }}</td>
											<td style="text-align: center;">-</td>
											<td>
												<span v-if="jurnal.pembelajaran.tematik">
													{{ 'Tema ' + jurnal.pembelajaran.tema.kode_tema.substr(-1) + '. ' + jurnal.pembelajaran.tema.teks }},
													{{ 'Subtema ' + jurnal.pembelajaran.subtema.kode_subtema.substr(-1) + '. ' + jurnal.pembelajaran.subtema.teks }},
												</span>
												<span v-else></span>
											</td>
											<td>
												<span v-if="jurnal.pembelajaran.tematik">
													<div v-for="materi in materis(jurnal.materi)">
														<div style="margin-bottom: 10px;">
															{{ materi.label }}:
															<p v-html="materi.teks"></p>
														</div>
													</div>
												</span>
												<span v-else></span>
											</td>
											<td>{{ jurnal.metode }}</td>
											<td>{{ jurnal.media }}</td>
											<td v-html="jurnal.kegiatan"></td>
											<td v-html="jurnal.tagihan"></td>
											<td v-html="jurnal.keterangan"></td>
										</tr>
									</tbody>
								</table>
							</v-col>
							<v-col cols="12" style="position: relative; display: flex;justify-content: space-around;">
								<div class="tanggal" style="text-align: center;">
									Mengetahui<br>
									Kepala Sekolah,
									<br>
									<br>
									<br>
									<br>
									<b><u>{{ this.$page.props.sekolah.ks.name }}</u></b><br>
									NIP. {{ this.$page.props.sekolah.ks.nip }}
								</div>
								<div class="tanggal" style="text-align: center;">
									{{ this.$page.props.sekolah.kec }}, {{ tanggal(jurnals[0].tanggal) }}<br>
									Guru,
									<br>
									<br>
									<br>
									<br>
									<b><u>{{ this.$page.props.user.name }}</u></b><br>
									NIP. {{ this.$page.props.user.nip }}
								</div>
								
							</v-col>
						</v-row>
					</v-sheet>
					<v-alert v-else color="error" colored-border border="left" elevation="3" class="mt-10" icon="mdi-alert" prominent>
						<v-container>
							Anda belum membuat Jurnal.
						</v-container>
					</v-alert>
				</v-container>
			</v-card-text>
		</v-card>

	</v-dialog>
</template>

<script>
	import moment from 'moment'
	moment.locale('id')
	export default {
		name: 'CetakJurnal',
		props: { dialog: Object },
		data: () => ({
			jurnals: []
		}),
		methods: {
			cetak() {
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
								Jurnal KBM
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
			getJurnals() {
				axios({
					method: 'post',
					url: '/dashboard/jurnal',
					data: {
						rombel: this.$page.props.rombel.kode_rombel,
					}
				}).then( res => {
					let jurnals = []
					res.data.jurnals.forEach((jurnal, index) => {
						jurnal.no = index+1
						jurnals.push(jurnal)
					})
					this.jurnals = jurnals
				}).catch( err => {
					console.log( err )
				})
			},
			materis(text) {
				let materis = JSON.parse(text)
				var data = []
				Object.entries(materis).forEach((v, k) => {
					data.push({mapel: v[0], teks: v[1].teks, label: v[1].label})
					// console.log(v)
				})
				return data
			},
			tanggal(tanggal) {
				return moment(tanggal).format('DD MMMM YYYY')
			}
		},
		computed: {

		},
		mounted() {
			this.getJurnals()
		}
	}
</script>

<style scoped lang="scss">
	table.table-jurnal {
		border-collapse: collapse;
		td {
			padding: 0 5px;
		}
	}
</style>
