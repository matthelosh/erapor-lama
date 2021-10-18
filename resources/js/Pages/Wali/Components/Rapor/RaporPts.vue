<template>
	<div id="print">
		<v-sheet class="page elevation-2">
			
			<div class="kop section"> 
				<img src="/img/malangkab.png" alt="" class="logo">
				<h3 class="text-center">PEMERINTAH KABUPATEN {{ rapor.sekolah.kab.toUpperCase() }}</h3>
				<h3 class="text-center">DINAS PENDIDIKAN</h3>
				<h4 class="text-center">KOORDINATOR WILAYAH DINAS PENDIDIKAN KECAMATAN {{ rapor.sekolah.kec.toUpperCase() }}</h4>
				<h2 class="text-center">{{ rapor.sekolah.nama_sekolah.toUpperCase() }}</h2>
				<p class="text-center  alamat">{{ rapor.sekolah.alamat }} {{ rapor.sekolah.desa }} KECAMATAN {{ rapor.sekolah.kec }} KABUPATEN {{ rapor.sekolah.kab }} - {{ rapor.sekolah.kode_pos }}</p>
				<p class="text-center ">Email: {{ rapor.sekolah.email }}, Website: {{ rapor.sekolah.website }}</p>
			</div>
			<div class="title section mt-3">
				<h3 class="text-center">LAPORAN HASIL PENILAIAN </h3>
				<h3 class="text-center">TENGAH SEMESTER {{ ($page.props.periode_aktif.semester == '1') ? 'I (GANJIL)' : 'II (GENAP)' }}</h3>
			</div>
			<div class="biodata section">
				<table width="100%">
					<tr>
						<td>Nama Peserta Didik</td><td>: {{ siswa.nama }}</td><td>Kelas</td><td>: {{ rapor.rombel.label }}</td>
					</tr>
					<tr>
						<td>NISN/NIS</td><td>: {{ siswa.nisn }}/{{ siswa.nis }}</td><td>Semester</td><td>: {{ ($page.props.periode_aktif.semester == '1') ? 'I (GANJIL)' : 'II (GENAP)' }}</td>
					</tr>
					<tr>
						<td>Nama Sekolah</td><td>: {{ rapor.sekolah.nama_sekolah }}</td><td>Tahun Pelajaran</td><td>: {{ $page.props.periode_aktif.tapel }}</td>
					</tr>
					<tr>
						<td >Alamat Sekolah</td><td colspan="3">: {{ rapor.sekolah.alamat }} {{ rapor.sekolah.desa }} {{ rapor.sekolah.kec }} {{ rapor.sekolah.kab }} {{ rapor.sekolah.prov }}</td>
					</tr>
				</table>
			</div>
			
			<div class="nilai section">
				<h3>A. Nilai Pengetahuan</h3>
				<table border="1" width="100%" class="table-nilai">
					<thead>
						<tr>
							<th>No</th>
							<th width="20%">Muatan Pelajaran</th>
							<th>Nilai</th>
							<th>Predikat</th>
							<th>Deskripsi</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td colspan="5" style="padding-left:20px;"><b>Muatan Wajib</b></td>
						</tr>
						<tr v-for="(mapel,i) in rapor.pts.wajib">
							<td class="text-center">{{ mapel.id }}</td>
							<td class="pl-5">{{ mapel.label }}</td>
							<td class="text-center">{{ Math.round(mapel.nilai.nilai,0) }}</td>
							<td class="text-center">{{ mapel.nilai.predikat }}</td>
							<td class="p-5">{{ (mapel.nilai.nilai > 0 ) ? mapel.nilai.deskripsi : '-' }}</td>
						</tr>
						<tr>
							<td colspan="5" style="padding-left:20px;"><b>Muatan Lokal</b></td>
						</tr>
						<tr v-for="(mapel,i) in rapor.pts.mulok">
							<td class="text-center">{{ mapel.id }}</td>
							<td class="pl-5">{{ mapel.label }}</td>
							<td class="text-center">{{ Math.round(mapel.nilai.nilai,0) }}</td>
							<td class="text-center">{{ mapel.nilai.predikat }}</td>
							<td class="p-5">{{ (mapel.nilai.nilai > 0 ) ? mapel.nilai.deskripsi : '-' }}</td>
						</tr>
					</tbody>
				</table>
			</div>
			
			<div class="saran">
				<h3>B. Saran - saran</h3>
				<div class="box-saran" @dblclick="editsaran">
					<p>{{ saran ? saran.teks : '' }}</p>
				</div>
			</div>
			<div class="ttds section mt-20">
				<table width="100%">
					<tr>
						<td class="text-center" width="40%">
							<br>
							Mengetahui,
							<br>Orang Tua / Wali
							<br>
							<br>
							<br>
							<br>
							...........................
						</td>
						<td width="auto">&nbsp;</td>
						<td class="text-center" width="40%">
							<span style="text-transform: capitalize;">{{ rapor.sekolah.kab }}, {{ tanggal }}</span><br>
							Guru Kelas,<br>
							<br>
							<br>
							<br>
							<br>
							<b><u>{{ rapor.rombel.walis.name }}</u></b><br>
							NIP. {{ rapor.rombel.walis.nip }}
						</td>
					</tr>
					<tr>
						<td class="text-center" colspan="3">
							Mengetahui,<br>
							Kepala Sekolah
							<br>
							<br>
							<img :src="ttd" class="ttd-ks" />
							<br>
							<br>
							<b><u>{{ rapor.sekolah.ks.name }}</u></b><br>
							NIP. {{  rapor.sekolah.ks.nip }}
						</td>
					</tr>						
				</table>
			</div>
		</v-sheet>
		<v-dialog v-model="saran.show" max-width="500">
			<!-- <v-btn fab color="orange" absolute style="margin-top: -10px;margin-left:35%;" small dark @click="simpan">
				<v-icon>mdi-harddisk</v-icon>
			</v-btn> -->
			<v-card>

				<v-card-title>
					Edit Saran {{ siswa.nama }}
					<v-spacer></v-spacer>

				</v-card-title>
				<v-card-text>
					<v-textarea label="Edit Saran PTS" v-model="saran.teks" outlined></v-textarea>
				</v-card-text>
				<v-card-actions class="d-flex justify-end">
					<v-btn rounded color="orange"  dark @click="simpan">
						<v-icon>mdi-harddisk</v-icon> Simpan
					</v-btn>
				</v-card-actions>
			</v-card>
		</v-dialog>
	</div>
</template>

<script>
	import moment from 'moment'
	export default {
		name: 'RaporPts',
		label: 'Rapor PTS',
		icon: 'mdi-book-open-blank-variant',
		props: {
			siswa: Object,
			rapor: Object,
			
		},
		data: () =>({
			saran: {
				show: false,
				teks: ''
			}
		}),
		methods: {
			editsaran() {
				// alert('ho')
				this.saran.show = true
			},
			simpan() {
				axios({
					method: 'post',
					url: '/dashboard/datarapor/saran/create',
					data: {
						periode_id: this.$page.props.periode,
						rombel_id: this.$page.props.rombel.kode_rombel,
						siswa_id: this.siswa.nisn,
						jenis_rapor: 'pts',
						teks: this.saran.teks
					}
				}).then( res => {
					this.saran.show = false
				}).catch( err => {
					console.log(err.response)
				})
			}
		},
		computed: {
			ttd(nip) {
				// console.log(nip.rapor.sekolah.ks.nip)
				return `/img/ttd/${nip.rapor.sekolah.ks.nip}.png`
			},
			tanggal() {
				moment.locale('id')
				return moment(this.rapor.tanggal_rapor).format('Do MMMM YYYY')
			}
		},
		mounted() {
			this.saran.teks = this.rapor.saran ? this.rapor.saran.teks : ''
		}
	}
</script>

<style lang="css" scoped>
	.mt-20 {
		margin-top: 20px;
	}
	.table-nilai {
		border-collapse: collapse;
	}
	.table-nilai thead tr {
		background: #efefef;
	}
	.pl-5 {
		padding-left: 5px;
	}
	.p-5 {
		padding:  5px;
	}
	.page {
		padding: 20px;
		position: relative;
		font-size: 1em;
		font-family: Arial;
	}
	.table-nilai th {
		padding: 5px 5px;
	}
	.text-center{
		text-align: center;
	}
	.kop {
		border-bottom:  5px double black;
	}
	.kop p {
		font-size: .7em;
		margin-top: 0;
		margin-bottom: 0;
	}
	img.logo {
		position: absolute;
		width:  75px;
		margin-top: 20px;
		margin-left:  20px;
	}
	.title {
		margin-top:  20px;
	}
	.box-saran {
		border:  1px solid black;
		padding: 20px;
		box-sizing: border-box;
	}
	.box-saran p {
		text-align: center;
		font-weight:  800;
		font-size: 14pt;
	}
	.ttd-ks {
		position: absolute;
		width: 170px;
		margin-top:  -10px;
		margin-left:  -85px;
	}
</style>