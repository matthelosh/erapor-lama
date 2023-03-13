<template lang="html">
	<v-dialog v-model="open" transition="dialog-top-transition">
		<v-card>
			<v-toolbar
				color="secondary"
				dark
			>
			Modal User
			</v-toolbar>
			<v-card-text>
				<div class="text-h2 pa-12">Form Pengguna</div>
			</v-card-text>
		</v-card>
	</v-dialog>
</template>

<script>
	export default {
		name: 'ModalUser',
		props: {
			open: Boolean,
		}
	}
</script>