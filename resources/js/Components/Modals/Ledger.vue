<template>
	<div>
		<div v-if="progress" class="progress d-flex justify-center align-center">
			<div>
				<img src="/img/ngopi.gif" class="kopi">
				<h3 class="text-center">Data Rapor sedang diambil. Tunggu sebentar, ya ..</h3>
			</div>
		</div>
		<v-dialog v-model="dialog.show" fullscreen transition="dialog-bottom-transition">
			<v-card>
				<v-toolbar dense color="secondary">
					<v-toolbar-title> <v-icon>mdi-table</v-icon> LEGER NILAI SISWA KELAS {{ $page.props.rombel.label }} SEMESTER {{ $page.props.periode_aktif.semester }} - {{ $page.props.periode_aktif.tapel }}</v-toolbar-title>
					<v-spacer></v-spacer>
					
					<v-btn fab color="error" small @click="$emit('hide')" class="ml-3">
						<v-icon>mdi-close</v-icon>
					</v-btn>
				</v-toolbar>
				<v-card-text>
					<v-container>
						<v-row>
							<v-col cols="12" sm="4">
								<h3>DATA NILAI SISWA</h3>
							</v-col>
							<v-col cols="12" sm="4">
							</v-col>
							<v-col cols="12" sm="4" class="text-right">
								<v-btn rounded color="primary" @click="print"><v-icon>mdi-printer</v-icon> Cetak</v-btn>
							</v-col>
						</v-row>
						<v-row>
							<v-col cols="12">
								<div id="print">
								<table width="100%" border="1">
									<thead>
										<tr>
											<th>No</th><th>NISN/NIS</th><th>NAMA</th>
											<th v-for="key in headers">{{ key }}</th>
										</tr>
									</thead>
									<tbody>
										<tr v-for="(item) in nilais" :key="item.index">
											<td>{{ item.index }}</td><td>{{ item.nisn }}</td><td>{{ item.nama }}</td>
											<td v-for="nilai in item.nilais">{{ Math.round(nilai,0) }}</td>
										</tr>
									</tbody>
								</table>
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
	export default {
		name: 'Ledger',
		props: {
			dialog: Object
		},
		data: () => ({
			progress: false,
			siswa: '',
			nilais: [],
			headers: []
		}),
		methods: {
			onSiswaChanged(){

			},
			getNilais() {
				this.progress = true
				axios({
					method: 'post',
					url: '/dashboard/nilai/ledger',
					data: {
						siswa_id: this.siswa,
						rombel_id: this.$page.props.rombel.kode_rombel
					}
				}).then( res => {
					this.progress = false
					var nilais = []
					Object.keys(res.data.nilais[0].nilais).forEach(k=>{
						this.headers.push(k)
					})
					res.data.nilais.forEach((item,index) => {
						item.index = index+1
						nilais.push(item)
					})
					this.nilais = nilais
					// console.log(res.data.nilais)
				}).catch( err => {
					this.progress = false
					console.log(err.response)
				})
			},
			print() {
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
								Rekap Nilai Kelas ${this.$page.props.rombel.label}
							</title>
							${css}
							<style>
								h3 {
									margin: 20px auto!important;
								}
							</style>
						</head>
						<body>
							<h3>Rekap Nilai Siswa Kelas ${this.$page.props.rombel.label}</h3>
							<hr>
							${html}
						</body>
					<html>
				`)

				WinPrint.document.close()
				WinPrint.focus()
				WinPrint.print()
				WinPrint.close()
			},

		},
		created() {
			// this.siswa = this.dialog.siswa.nisn
			this.getNilais()
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
	table {
		border-collapse: collapse;
	}
	table th, table td {
		padding: 5px 10px;
	}
</style>