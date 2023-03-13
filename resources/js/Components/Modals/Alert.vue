<template>
	<v-dialog v-model="dialog.show" max-width="600">
		<v-card >
			<v-toolbar>
				<v-toolbar-title>{{ dialog.title }}</v-toolbar-title>
				<v-spacer></v-spacer>
				<v-btn fab color="error"  @click="$emit('close')" small><v-icon>mdi-close</v-icon></v-btn>
			</v-toolbar>
			<v-card-text>
				<p class="text-h5">{{ dialog.text }}</p>
			</v-card-text>
		</v-card>
	</v-dialog>
</template>

<script>
	export default {
		name: 'Alert',
		props: { dialog: Object },
		data: () => ({
			title: '',
			text: ''
		})
	}
</script>