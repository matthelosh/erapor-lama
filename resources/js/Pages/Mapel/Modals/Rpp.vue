<template>
	<v-dialog v-model="dialog.show" fullscreen transition="dialog-top-transition">
		<v-card color="grey">
			<v-toolbar dense>
				<v-toolbar-title>
					RPP {{ dialog.rpp.kode_pembelajaran }}
				</v-toolbar-title>
				<v-spacer></v-spacer>
				<v-btn fab color="error" @click="$emit('hide')" small><v-icon>mdi-close</v-icon></v-btn>
			</v-toolbar>
			<v-card-text>
				<v-container>
					<v-row>
						<v-col cols="12" sm="9">
							<v-sheet elevation="3" class="my-5" style="padding: 50px 100px!important;" id="print" light>
								<v-container>
									<v-row>
										<v-col cols="12" class="text-center" style="border-bottom: 5px double black;">
											<h2 class="title" style="text-align: center; margin-bottom: 10px;">RENCANA PELAKSANAAN PEMBELAJARAN ( RPP )</h2>
										</v-col>
									</v-row>
									<v-row>
										<v-col cols="12">
											<table class="table-meta" style="margin-top: 20px;">
												<tr>
													<td>Satuan Pendidikan</td><td>:</td><td>{{ $page.props.sekolah.nama_sekolah }}</td>
												</tr>
												<tr>
													<td>Kelas / Semester</td><td>:</td><td>{{ dialog.rpp.rombel_id.substr(6, (dialog.rpp.rombel_id.length - 6) ) }} / {{ dialog.rpp.periode_id.substr(-1) }} {{ (dialog.rpp.periode_id.substr(-1) % 2 == 0) ? '(Genap)' : '(Ganjil)' }}</td>
												</tr>
												<tr>
													<td>Tema</td><td>:</td><td>{{ dialog.tema }} (Tema {{ dialog.rpp.tema_id.substr(-1) }} )</td>
												</tr>
												<tr>
													<td>Sub Tema</td><td>:</td><td>{{ dialog.subtema }} (Sub Tema {{ dialog.rpp.subtema_id.substr(-1) }} )</td>
												</tr>
												<tr>
													<td>Muatan Terpadu</td><td>:</td><td>{{ mupels(dialog.rpp.mupels) }} </td>
												</tr>
												<tr>
													<td>Pembelajaran Ke</td><td>:</td><td>{{ dialog.rpp.ke }}</td>
												</tr>
												<tr>
													<td>Alokasi Waktu</td><td>:</td><td>1 hari </td>
												</tr>
											</table>
										</v-col>
										<v-col cols="12">
											<h3>A. TUJUAN PEMBELAJARAN</h3>
											<div v-html="dialog.rpp.tujuan" class="text-p" style="margin-left: 20px;"><span ></span></div>
										</v-col>
										<v-col cols="12">
											<h3>B. KEGIATAN PEMBELAJARAN</h3>
											<table class="table-kegiatan" border="1" width="100%" style="border-collapse: collapse;">
												<thead>
													<tr>
														<th >Kegiatan</th>
														<th>Deskripsi</th>
														<th width="10%">Alokasi Waktu</th>
													</tr>
												</thead>
												<tbody>
													<tr>
														<td>Apersepsi / Pembukaan</td><td v-html="kegiatan.pembuka.teks"></td><td>{{ kegiatan.pembuka.durasi }} Menit</td>
													</tr>
													<tr>
														<td>Inti</td><td v-html="kegiatan.inti.teks"></td><td>{{ kegiatan.inti.durasi }} Menit</td>
													</tr>
													<tr>
														<td>Penutup</td><td v-html="kegiatan.penutup.teks"></td><td>{{ kegiatan.penutup.durasi }} Menit</td>
													</tr>
												</tbody>
											</table>
										</v-col>
										<v-col cols="12">
											<h3>C. PENILAIAN</h3>
											<div v-html="dialog.rpp.penilaian" class="text-p"></div>
										</v-col>
										<v-col cols="12">
											<table width="100%" class="ttd">
												<tr>
													<td style="text-align: center">
														Mengetahui,<br>
														Kepala Sekolah
														<br><br><br><br>
														<b><u>{{ $page.props.sekolah.ks.name }}</u></b><br>
														NIP. {{ $page.props.sekolah.ks.nip }}
													</td>
													<td style="text-align: center">
														{{ $page.props.sekolah.kec }}, {{ tanggal }} <br>
														<span v-if="$page.props.user.role == 'wali'">
															Guru Kelas {{ dialog.rpp.rombel.label }}
														</span>
														<span v-else>
															Guru Mapel
														</span>
														<br><br><br><br>
														<b><u>{{ $page.props.user.name }}</u></b><br>
														NIP. {{ $page.props.user.nip }}
													</td>
												</tr>
											</table>
										</v-col>
									</v-row>
								</v-container>
							</v-sheet>
						</v-col>
						<v-col cols="3" class="d-flex justify-center">
							<v-btn color="error darken-2" @click="cetak" style="position: fixed;top: 80px;"><v-icon>mdi-printer</v-icon> cetak</v-btn>
						</v-col>
					</v-row>
				</v-container>
			</v-card-text>
		</v-card>
		
	</v-dialog>
</template>

<script>
	import moment from 'moment'
	moment.locale('id')
	export default {
		name: 'Rpp',
		props: { dialog: Object },
		data: () =>({

		}),
		methods: {
			mupels(mupels){
				let data = ''
				mupels = JSON.parse(mupels)
				mupels.forEach(item => {
					data += item.label +', '
				})
				// console.log(mupels)
				return data
			},
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
								RPP 
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
				// WinPrint.close()
			}
		},
		computed: {
			kegiatan() {
				return JSON.parse(this.dialog.rpp.kegiatan)
			},
			tanggal() {
				return moment(this.dialog.rpp.created_at).format('Do MMMM YYYY')
			}
		},
		mounted() {

		}
	}
</script>

<style>
	ol p {
		margin:  0!important;
	}
	table.table-kegiatan th,
	table.table-kegiatan td {
		padding:  5px;
	}
	/*.table-kegiatan td ol p {
		margin:  0!important;
	}*/
	ol {
		margin-left:  20px!important;
	}
	.text-p p {
		/*background: pink;*/
		margin-bottom:  0!important;
	}

	@media print {
		@page {
			size:  portrait;
		}
		body {
			margin: 0.5cm 1cm 0.5cm 1.5cm;
		}
	}
</style>