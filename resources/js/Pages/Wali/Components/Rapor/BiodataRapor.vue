<template>
	<div id="print">
		<v-sheet class="page elevation-5 py-10">
			<h1 style="margin: 20px auto!important; text-align: center;">IDENTITAS PESERTA DIDIK</h1>
			<hr>

			<table class="bio mt-10" style="margin-left: 150px;margin-top: 50px;">
				<tbody>
					<tr>
						<td>Nama Peserta Didik</td>
						<td>:</td>
						<td>{{ siswa.nama }}</td>
					</tr>
					<tr>
						<td>NISN/NIS</td>
						<td>:</td>
						<td>{{ siswa.nisn }} / {{ siswa.nis }}</td>
					</tr>
					<tr>
						<td>Jenis Kelamin</td>
						<td>:</td>
						<td>{{ (siswa.jk == 'l') ? 'Laki-laki' : 'Perempuan' }}</td>
					</tr>
					<tr>
						<td>Tempat, Tanggal Lahir:</td>
						<td>:</td>
						<td>{{ siswa.tempat_lahir }}, {{ siswa.tanggal_lahir }}</td>
					</tr>
					
					<tr>
						<td>Agama</td>
						<td>:</td>
						<td>{{ siswa.agama }}</td>
					</tr>
					<tr>
						<td>Pendidikan Sebelumnya</td>
						<td>:</td>
						<td>{{ siswa.sekolah_asal }}</td>
					</tr>
					<tr>
						<td>Alamat Peserta Didik</td>
						<td>:</td>
						<td>{{ siswa.alamat }} RT.{{ siswa.rt }} RW.{{ siswa.rw }} {{ siswa.desa }} Kec. {{ siswa.kec }} Kab. {{ siswa.kab }}</td>
					</tr>
					<tr>
						<td>Telp / HP</td>
						<td>:</td>
						<td>{{ siswa.hp }}</td>
					</tr>
					
					<tr>
						<td colspan="3">Nama Orang Tua:</td>
						
					</tr>
					<tr>
						<td class="pl-10">Ayah</td>
						<td>:</td>
						<td>{{ siswa.ortu ? siswa.ortu.nama_ayah: '-' }}</td>
					</tr>
					<tr>
						<td class="pl-10">Ibu</td>
						<td>:</td>
						<td>{{ siswa.ortu ?  siswa.ortu.nama_ibu : '-'}}</td>
					</tr>
					<tr>
						<td>Pekerjaan Orang Tua</td>
						<td>:</td>
						<td></td>
					</tr>
					<tr>
						<td class="pl-10">Ayah</td>
						<td>:</td>
						<td>{{ siswa.ortu ? siswa.ortu.job_ayah : '-' }}</td>
					</tr>
					<tr>
						<td class="pl-10">Ibu</td>
						<td>:</td>
						<td>{{ siswa.ortu ? siswa.ortu.job_ibu : '-' }}</td>
					</tr>
					<tr>
						<td>Alamat Orang tua</td>
						<td>:</td>
						<td></td>
					</tr>
					<tr>
						<td class="pl-10">Jalan / Dusun</td>
						<td>:</td>
						<td>{{ siswa.alamat }}</td>
					</tr>
					<tr>
						<td class="pl-10">Kelurahan / Desa</td>
						<td>:</td>
						<td>{{ siswa.desa }}</td>
					</tr>
					<tr>
						<td class="pl-10">Kecamatan</td>
						<td>:</td>
						<td>{{ siswa.kec }}</td>
					</tr>
					<tr>
						<td class="pl-10">Kabupaten</td>
						<td>:</td>
						<td>{{ siswa.kab }}</td>
					</tr>
					<tr>
						<td class="pl-10">Provinsi</td>
						<td>:</td>
						<td>{{ siswa.prop }}</td>
					</tr>
					<tr>
						<td>Wali Peserta Didik</td>
						<td>:</td>
						<td>{{ siswa.ortu ? siswa.ortu.nama_wali : '-' }}</td>
					</tr>
					<tr>
						<td>Pekerjaan</td>
						<td>:</td>
						<td>{{ siswa.ortu ? siswa.ortu.job_wali : '-' }}</td>
					</tr>
					<tr>
						<td>Alamat</td>
						<td>:</td>
						<td>{{ siswa.ortu ? siswa.ortu.alamat_wali : '-'}}</td>
					</tr>
					
				</tbody>
			</table>
			<v-row>
				<table class="ttd"  width="90%">
					<tr>
						<td width="45%" style="text-align:right">
							<div class="foto-box" style="text-align: center;" :style="fotoSiswa">
								3x4cm
							</div>
						</td>
						<td width="10%"></td>
						<td width="33%" style="text-align: center;">
							{{ $page.props.sekolah.kab }}, {{ tanggal }} <br>
							Kepala Sekolah
							<br>
							<br>
							<img :src="'/img/ttd/'+$page.props.sekolah.ks.nip+'.png'" width="100px">
							<br>
							<br>
							<b><u>{{ $page.props.sekolah.ks.name }}</u></b><br>
							NIP. {{ $page.props.sekolah.ks.nip }}
						</td>
					</tr>
				</table>
			</v-row>
		</v-sheet>
	</div>
</template>

<script>
	export default {
		name: 'BiodataRapor',
		label: 'Biodata',
		icon: 'mdi-card-account-details-outline',
		props: { siswa: Object },
		computed: {
			nipks(){
				let nip = this.$page.props.sekolah.ks.nip
				var text = nip.split("")
				var formatted = text.splice(8,0," ")

				return text
			},
			fotoSiswa(){
				let nisn = this.siswa.nisn
				var style = `
						background-image: url("/storage/img/siswas/${nisn}.jpg");background-size: contain;background-position:center center;
				`
				return style
			},
			tanggal() {
				const periode = this.$page.props.periode_aktif.tapel
				const tapel = periode.split('/')
				return '12 Juli '+ tapel[0]
			}
		}
	}
</script>

<style scoped>
	.foto-box {
		width:  3cm;
		height: 3cm;
		border:  1px solid black;
		margin-left: 70%;
	}
	table.bio {
		width:  70%;
		margin:  auto;
		margin-bottom:  50px;
	}
	table.ttd {
		/*width:  100%;*/
		/*background: pink;*/
		margin:  20px auto;
	}
	.page {
		min-height:  33cm;
		height: auto;
		width:  21.56cm;
		/*border:  10px solid black;*/
	}
	hr {
		width:  50%;
		margin:  auto;
	}
	.my-10 {
		/*margin:  20px auto 5px auto;*/
	}
	.p-5 {
		padding:  5px;
	}
</style>