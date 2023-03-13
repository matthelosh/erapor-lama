<template>
	<div>
		<v-dialog v-model="dialog.show" fullscreen transition="dialog-bottom-transition">
			<v-card>
				<v-toolbar dense>
					<v-toolbar-title>Absensi Siswa</v-toolbar-title>
					<v-spacer></v-spacer>
					<v-btn fab small color="error" @click="$emit('hide')"><v-icon>mdi-close</v-icon></v-btn>
				</v-toolbar>
			</v-card>
		</v-dialog>
	</div>
</template>

<script>
	export default {
		name: 'Absensi',
		props: { dialog: Object }
	}
</script>