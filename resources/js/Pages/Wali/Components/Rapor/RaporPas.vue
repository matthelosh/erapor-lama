<template>
	<div id="print" v-if="rapor" class="rapor-pas">
		<v-sheet class="page elevation-2">
			<div class="title section">
				<h2 class="text-center mt-0" >PROFIL DAN RAPOR PESERTA DIDIK</h2>
			</div>
			<div class="biodata section">
				<table width="100%">
					<tr>
						<td>Nama Peserta Didik</td><td>: {{ siswa.nama }}</td><td>Kelas</td><td>: {{rapor.rombel.label}}</td>
					</tr>
					<tr>
						<td>NISN/NIS</td><td>: {{ siswa.nisn }}/{{ siswa.nis }}</td><td>Semester</td><td>: {{ (rapor.periode.substr(-1) == '1') ? 'I (GANJIL)' : 'II (GENAP)' }}</td>
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
				<div class="sikap">
					<h3>A. Sikap</h3>
					<table width="100%" border="1" class="table-nilai">
						<thead>
							<tr>
								<th colspan="3">Deskripsi</th>
							</tr>
						</thead>
						<tbody>
							<tr v-for="(nilai,i) in rapor.pas.sikap" :key="i">
								<td class="p-5 text-center">{{nilai.id}}</td><td class="p-5">{{ nilai.label ? nilai.label : '-' }}</td><td class="p-5"><span v-show="nilai.deskripsi">Ananda {{ siswa.nama }} {{ nilai.deskripsi }}</span></td>
							</tr>
							<!-- <tr>
								<td class="p-5 text-center">2</td><td class="p-5">Sikap Sosial</td><td class="p-5">Ananda {{ siswa.nama }} ...</td>
							</tr> -->
						</tbody>
					</table>
				</div>
				<div class="akademik">
					<h3>B. Pengetahuan dan Keterampilan</h3>
					<table width="100%" border="1" class="table-nilai">
						<thead>
							<tr>
								<th rowspan="2">No</th>
								<th rowspan="2">Muatan Pelajaran</th>
								<th colspan="3">Pengetahuan</th>
								<th colspan="3">Keterampilan</th>
							</tr>
							<tr>
								<th>Nilai</th>
								<th>Predikat</th>
								<th width="30%">Deskripsi</th>
								<th>Nilai</th>
								<th>Predikat</th>
								<th width="30%">Deskripsi</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td colspan="8" class="pl-5"><h3>Muatan Wajib</h3></td>
							</tr>
							<tr v-for="(mapel,i ) in wajibs" :key="mapel.id">
								<td class="text-center">{{ i+1 }}</td>
								<td class="px-5">{{ mapel.label }}</td>
								<td class="text-center" :class="cekkm(mapel.pivot.kkm, mapel.nilai.k3.nilai)">{{Math.round(mapel.nilai.k3.nilai,0) }}</td>
								<td class="text-center" :class="cekkm(mapel.pivot.kkm, mapel.nilai.k3.nilai)">{{ mapel.nilai.k3.predikat }}</td>
								<td class="p-5"><span v-show="mapel.nilai.k3.nilai > 0">Ananda {{ siswa.nama }} {{ mapel.nilai.k3.deskripsi }}</span></td>
								<td class="text-center" :class="cekkm(mapel.pivot.kkm, mapel.nilai.k4.nilai)">{{ Math.round(mapel.nilai.k4.nilai,0) }}</td>
								<td class="text-center" :class="cekkm(mapel.pivot.kkm, mapel.nilai.k4.nilai)">{{ mapel.nilai.k4.predikat }}</td>
								<td class="p-5"><span v-show="mapel.nilai.k4.nilai > 0">Ananda {{ siswa.nama }} {{ mapel.nilai.k4.deskripsi }}</span></td>
								
							</tr>
							<tr>
								<td colspan="8" class="pl-5"><h3>Muatan Lokal</h3></td>
							</tr>
							<tr v-for="(mapel,i ) in muloks" :key="mapel.id">
								<td class="text-center">{{ wajibs.length + 1 +i }}</td>
								<td class="pl-5">{{ mapel.label }}</td>
								<td class="text-center" :class="cekkm(mapel.pivot.kkm, mapel.nilai.k3.nilai)">{{ Math.round(mapel.nilai.k3.nilai,0) }}</td>
								<td class="text-center" :class="cekkm(mapel.pivot.kkm, mapel.nilai.k3.nilai)">{{ mapel.nilai.k3.predikat }}</td>
								<td class="p-5"><span v-show="mapel.nilai.k3.nilai > 0">Ananda {{ siswa.nama }} {{ mapel.nilai.k3.deskripsi }}</span></td>
								<td class="text-center" :class="cekkm(mapel.pivot.kkm, mapel.nilai.k4.nilai)">{{ Math.round(mapel.nilai.k4.nilai,0) }}</td>
								<td class="text-center" :class="cekkm(mapel.pivot.kkm, mapel.nilai.k4.nilai)">{{ mapel.nilai.k4.predikat }}</td>
								<td class="p-5"><span v-show="mapel.nilai.k4.nilai > 0">Ananda {{ siswa.nama }} {{ mapel.nilai.k4.deskripsi }}</span></td>
								
							</tr>
						</tbody>
					</table>
				</div>
			</div>
			<div class="ekskul section">
				<h3>C. Ekstrakurikuler</h3>
				<table width="100%" border="1">
					<thead>
						<tr>
							<th width="3%" style="width:5%!important">No</th><th>Ekstrakurikuler</th><th>Keterangan</th>
						</tr>
					</thead>
					<tbody>
						<tr v-for="(e,i) in rapor.ekskul" :key="i">
							<td class="p-5 text-center" width="3%" style="width:3%!important">{{ e ? i+1 : '' }}</td><td class="p-5">{{ e ? e.ekskuls.label : '' }}</td><td class="p-5">{{ e ? e.ket : '' }}</td>
						</tr>
					</tbody>
				</table>
			</div>
			<div class="saran section">
				<h3>D. Saran</h3>
				<article class="saran-content" v-html="rapor.saran ? rapor.saran.teks : '-'"></article>
			</div>
			<div class="fisik section">
				<h3>E. Tinggi Badan dan Berat badan</h3>
				<table width="100%" border="1">
					<thead>
						<tr>
							<th rowspan="2" width="5%">No</th>
							<th rowspan="2">Aspek</th>
							<th colspan="2">Semester</th>
						</tr>
						<tr>
							<th>1</th>
							<th>2</th>
						</tr>
					</thead>
					<tbody>
						<tr v-for="(f,i) in rapor.fisik" :key="i">
							<td class="text-center">{{ f.id }}</td>
							<td class="p-5">{{ f.label }}</td>
							<td class="text-center">{{ f.sem1 }} </td>
							<td class="text-center">{{ f.sem2 }} </td>
						</tr>
					</tbody>
				</table>
			</div>
			<div class="html2pdf__page-break"></div>
			<div class="kesehatan section">
				<h3>F. Kondisi Kesehatan</h3>
				<table width="100%" border="1">
					<thead>
						<tr>
							<th width="5%">No</th><th>Aspek Fisik</th><th>Keterangan</th>
						</tr>
					</thead>
					<tbody>
						<!-- <span v-if="rapor.hasOwnProperty('kesehatan')"> -->
						<tr>
							<td class="text-center p-5">1</td><td class="p-5">Pendengaran</td><td class="p-5">{{ rapor.kesehatan ? rapor.kesehatan.pendengaran : ''}}</td>
						</tr>
						<tr>
							<td class="text-center p-5">2</td><td class="p-5">Penglihatan</td><td class="p-5">{{ rapor.kesehatan ? rapor.kesehatan.penglihatan : ''}}</td>
						</tr>
						<tr>
							<td class="text-center p-5">3</td><td class="p-5">Gigi</td><td class="p-5">{{ rapor.kesehatan ? rapor.kesehatan.gigi : ''}}</td>
						</tr>
						<tr>
							<td class="text-center p-5">4</td><td class="p-5">Lainnya</td><td class="p-5">{{ rapor.kesehatan ? rapor.kesehatan.lainnya : ''}}</td>
						</tr>
						<!-- </span> -->
					</tbody>
				</table>
			</div>
			<div class="prestasi section">
				<h3>G. Prestasi</h3>
				<table width="100%" border="1">
					<thead>
						<tr>
							<th width="5%">No</th><th>Jenis Prestasi</th><th>Keterangan</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td class="text-center p-5">1</td><td class="p-5">Akademik</td><td class="p-5">{{ rapor.prestasi ? rapor.prestasi.akademik : '-' }}</td>
						</tr>
						<tr>
							<td class="text-center p-5">2</td><td class="p-5">Olahraga</td><td cclass="p-5">{{ rapor.prestasi ? rapor.prestasi.olahraga : '-'}}</td>
						</tr>
						<tr>
							<td class="text-center p-5">3</td><td class="p-5">Kesenian</td><td class="p-5">{{ rapor.prestasi ? rapor.prestasi.kesenian : '-' }}</td>
						</tr>
						<!-- </span> -->
					</tbody>
				</table>

			</div>
			<div class="absensi section">
				<h3>H. Absensi</h3>
				<table border="1" width="40%">
					<!-- <span v-if="rapor.hasOwnProperty('absensi')"> -->
					<tr>
						<td class="p-5">Tanpa Keterangan</td><td class="p-5">: {{ rapor.absensi ? rapor.absensi.a : '-' }} hari</td>
					</tr>
					<tr>
						<td class="p-5">Ijin</td><td class="p-5">: {{ rapor.absensi ? rapor.absensi.i : '-'}} hari</td>
					</tr>
					<tr>
						<td class="p-5">Sakit</td><td class="p-5">: {{ rapor.absensi ? rapor.absensi.s : '-' }} hari</td>
					</tr>
					<!-- </span> -->
				</table>
			</div>
			<div class="keputusan section" v-if="(rapor.periode.substr(-1) == '2')">
				<h3>I. Keputusan {{rapor.periode}}</h3>
				<article>
					<!-- <h4>Keputusan:</h4> -->
					<p>Berdasarkan pada pencapaian kompetensi, peserta didik dinyatakan:</p>
					<p v-if="$page.props.rombel.kelas_id != 6" style="font-weight: 800;"><span :class="naik">Naik</span> / <span :class="tinggal"> Tinggal</span> *) kelas {{ kelasTujuan }}</p>
					<p v-else style="font-weight: 800;"><span :class="naik">Lulus</span> / <span :class="tinggal"> Mengulang</span> *) kelas {{ kelasTujuan }}</p>
					<p>*) Coret yang tidak perlu</p>
				</article>
			</div>
			<div class="ttd section">
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
							<img :src="'/img/ttd/'+rapor.rombel.walis.nip+'.png'" class="ttd-ks" />
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
	</div>
</template>

<script>
	import moment from 'moment'
	export default {
		name: 'RaporPAS',
		label: 'Rapor PAS',
		icon: 'mdi-book-open-blank-variant',
		props: {
			siswa: Object, 
			rapor: Object
		},
		methods: {
			cekkm(kkm, nilai){
				// console.log(kkm,nilai)
				return (nilai < kkm) ? 'tl' : ''
			}
		},
		computed: {
			ttd(nip) {
				// console.log(nip.rapor.sekolah.ks.nip)
				return `/img/ttd/${nip.rapor.sekolah.ks.nip}.png`
			},
			kelasTujuan() {
				if (this.naik == 'stroked') {
					return this.$page.props.rombel.kelas_id
				} else{
					return (parseInt(this.$page.props.rombel.kelas_id,10) + 1)
				}
			},
			naik() {
				var nilai = this.rapor.pas.rerata.reduce((a,b) => a+b,0)
				var rerata = nilai / this.rapor.pas.rerata.length
				return (rerata < 75) ? 'stroked' : ''
			},
			tinggal() {
				var nilai = this.rapor.pas.rerata.reduce((a,b) => a+b,0)
				var rerata = nilai / this.rapor.pas.rerata.length
				return (rerata >= 75) ? 'stroked' : ''
			},
			tanggal() {
				moment.locale('id')
				return moment(this.rapor.tanggal_rapor).format('Do MMMM YYYY')
			},
			wajibs(){
				return _.orderBy(this.rapor.pas.wajib, "id")
			},
			muloks() {
				return _.orderBy(this.rapor.pas.mulok, "id")
			}

		}
	}
</script>

<style scoped>
	.tl {
		color:  red;
		font-weight: 800;
	}
	.stroked {
		text-decoration: line-through;
	}
	.section {
		margin-top:  10px;
		margin-bottom:  10px;
	}
	.keputusan {
		border-top:  5px double black;
		border-bottom:  5px double black;
	}
	.saran p {
		width:  100%;
		padding: 20px;
		border: 1px solid black;
	}
	.mt-20 {
		margin-top: 20px;
	}
	table {
		border-collapse: collapse;
	}
	.table-nilai thead tr {
		background: #efefef;
	}
	.px-5 {
		padding-right:  5px;
		padding-left:  5px;
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
	.saran article {
		box-sizing:border-box;
		padding: 10px;
		border: 1px solid black;
	}
	.ttd-ks {
		position: absolute;
		width: 100px;
		margin-top:  -20px;
		margin-left:  -55px;
	}
</style>