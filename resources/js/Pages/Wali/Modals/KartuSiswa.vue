<template>
	<v-dialog 
		v-model="dialog.show" 
		transition="dialog-bottom-transition"
        fullscreen
	>
			<v-card color="grey">
				<v-toolbar dense>
					<v-toolbar-title>Kartu Siswa</v-toolbar-title>
					<v-spacer></v-spacer>
					<v-btn fab @click="$emit('hide')" color="error" small>
						<v-icon>mdi-close</v-icon>
					</v-btn>
				</v-toolbar>
				<v-card-text>
					<v-container>
						<v-row>
							<v-col cols="9">
								<div id="print">
								<v-sheet 
									v-for="(item, index) in items"
									color="white"
									:key="index"
									elevation="1"
									width="794"
									height="auto"
									padding="10"
									class="p-20 text-center my-5 sheet">
									<div
										v-for="siswa in item"
										:key="siswa.nisn"
										max-
										style="background: url('/storage/img/kartu/bg_kartu.png';background-repeat: no-repeat ; position: relative;width: 647px; height: 190px;"
										cover
										class="mx-auto my-5 d-print-block"
										relative
									>
										<v-row>
											<v-col cols="6" style="position: relative; height: 190px;width: 323.5px; ">
												<div style="position: relative;">
												<table style="min-height: 120px;">
													<tr>
														<td></td>
														<td colspan="3" style="padding-bottom: 15px;"><h3 style="margin-bottom: -5px">{{ $page.props.sekolah.nama_sekolah.toUpperCase() }}</h3>
															<span style="font-size: 0.8em;text-transform: capitalize;">{{ $page.props.sekolah.alamat }}, {{ $page.props.sekolah.desa}}, {{$page.props.sekolah.kode_pos}}</span>
														</td>
													</tr>
													<tr>
														<td rowspan="4" style="vertical-align: top;padding-top: 5px;" width="20%">
															<img :src="'/img/siswas/'+siswa.nisn+'.jpg'" alt="Foto" width="50px" @error="setDefaultFoto($event,siswa.jk)">
														</td>
														<td width="25%">NIS / NISN</td><td>:</td><td>{{ siswa.nis }} / <b>{{ siswa.nisn }}</b></td>
													</tr>
													<tr>
														<td>Nama</td><td>:</td><td>{{ siswa.nama }}</td>
													</tr>
													<tr>
														<td>Kelahiran</td><td>:</td><td>{{ siswa.tempat_lahir }}, {{ siswa.tanggal_lahir }}</td>
													</tr>
													<tr>
														<td>Kontak</td><td>:</td><td>{{ siswa.hp }}</td>
													</tr>
												</table>
												
												
												</div>
												<h1 class="tahun" style="position:absolute;left: 40%; bottom: 7px; filter: opacity(40%) ;">2021</h1>
												<img class="ttd" :src="'/img/ttd/'+$page.props.sekolah.ks.nip+'.png'" alt="TTD." width="50px" height="auto" style="position: absolute; left: 35px; bottom: 15px;" />
												<vue-qrious mime="image/png" :value="siswa.nisn" :size="100" width="40" style="position: absolute; bottom:5px;right: 10px;" />
											</v-col>
											<v-col cols="6">
											</v-col>
										</v-row>
									</div>
								</v-sheet>
								</div>
							</v-col>
							<v-col cols="3">
								<v-card height="100">
									<v-card-text class="d-flex justify-center align-center">
										<v-btn color="primary" rounded fixed style="top:90px" @click="cetak">
											<v-icon>mdi-printer</v-icon>
											Cetak
										</v-btn>
									</v-card-text>
								</v-card>
							</v-col>
						</v-row>
					</v-container>
				</v-card-text>
			</v-card>


	</v-dialog>
</template>

<script>
	import VueQrious from 'vue-qrious'
	export default {
		name: 'KartuSiswa',
		props: {dialog:Object},
		components: {VueQrious},
		data: () => ({

		}),
		methods: {
			setDefaultFoto(event,jk){
				var foto = (jk == 'l') ? 'siswa.png' : 'siswi.png'
                event.target.src = '/storage/img/siswas/'+foto
                // event.error = false
			},
			cetak() {
				const html = document.getElementById('print').innerHTML
				let css = ''
				

				const WinPrint = window.open('','', 'left=0,top=0,width=800,height=900, toolbar=0, scrollbars=0,status=0')
				WinPrint.document.write(`<!DOCTYPE html>
					<html>
						<head>
							<title>
								Kartu Siswa
							</title>
							<style>
									.p-20 {
										padding:  20px;
									}
									table {
										margin: 20px auto 10px auto;
										border-collapse: collapse;
										width:  85%;
									}
									table td {
										text-align: left;
										font-size: 0.85em;
										padding: 0;
										vertical-align: top;
										/*background: pink;*/
										line-height: 1.5em;
									}
								.sheet {
									page-break-after: always;
								}
							}
							</style>
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
			items() {
				const datas = this.dialog.siswas
				var per_item = 5
				var group = datas.length / 5
				var items = []
				for ( var i = 0; i < group; i++) {
					items[i] = datas.slice(i, (i + 5))
				}
				return items
			}
		}
	}
</script>
<style scoped>
	.p-20 {
		padding:  20px;
	}
	table {
		margin: 20px auto 10px auto;
		border-collapse: collapse;
		width:  85%;
	}
	table td {
		text-align: left;
		font-size: 0.85em;
		padding: 0;
		vertical-align: top;
		/*background: pink;*/
		line-height: 1.5em;
	}
	/*img.ttd {
		position: absolute;
		left:  20px;
		margin-top:  10px;
	}*/
	/*h1.tahun {
		position: absolute;
		left: 40%;
		bottom:  10px;
		font-weight: 800;
		color:  grey;
		filter: opacity(40%) ;
		mix-blend-mode: exclusion;
	}
	.qr {
		position: absolute;
		bottom: 5px;
		left:  40%;
	}*/
</style>