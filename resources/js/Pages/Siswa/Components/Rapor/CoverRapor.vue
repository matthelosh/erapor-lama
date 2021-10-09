<template>
	<div id="print">
		<v-sheet class="page elevation-2">
			<div class="tutwuri text-center">
				<img src="/img/tutwuri.png" width="200px">
			</div>
			<div class="title text-center">
				<h2>RAPOR <br/> PESERTA DIDIK
				SEKOLAH DASAR (SD)</h2>
			</div>
			<div class="nama">
				<p class="text-center">Nama Peserta Didik</p>
				<div class="box-name text-center">{{ siswa.nama.toUpperCase() }}</div>
			</div>
			<div class="nisn">
				<p class="text-center">NISN / NIS</p>
				<div class="box-nis text-center">
					{{ siswa.nisn }} / {{ siswa.nis }}
				</div>
			</div>
			<div class="footer">
				<h4 class="text-center">
					KEMENTERIAN PENDIDIKAN DAN KEBUDAYAAN
				</h4>
				<h3 class="text-center">REPUBLIK INDONESIA</h3>
			</div>

		</v-sheet>	
	</div>
</template>

<script>
	export default {
		name: 'CoverRapor',
		label: 'Sampul',
		icon: 'mdi-home',
		props: { siswa: Object}
	}

</script>

<style scoped>
	.page {
		/*border:  1px solid #efefef;*/
		background: #fefefe;
		text-align: center;
		padding-top:  200px;
		max-height:  33cm;
		height: 33cm;
		position: relative;
	}
	.nisn {
		margin-top: 20px;
	}
	.nama p,
	.nisn p {
		margin-bottom:  0!important;
		padding:  0;
		font-weight: 400;
	}

	.box-name, .box-nis {
		border:  2px solid black;
		max-width: 300px;
		margin:  auto;
		padding: 10px;
		font-weight: 900;
		font-size: 1.2em;
		box-sizing: border-box;
	}
	.title {
		margin:  3cm ; 
	}
	.nama {
		margin-top:  5cm auto 1cm auto;
	}

	.footer {
		position: absolute;
		/*background: pink;*/
		width: 100%;
		bottom:  110px;
	}
</style>
