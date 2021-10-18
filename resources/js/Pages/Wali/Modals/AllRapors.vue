<template>
	<v-dialog 
		v-model="dialog.show" 
		transition="dialog-bottom-transition"
        fullscreen
	>
		<v-card color="grey">
			<v-toolbar dense>
				<v-toolbar-title>Semua Rapor</v-toolbar-title>
				<v-spacer></v-spacer>
				<v-btn fab @click="$emit('hide')" color="error" small>
					<v-icon>mdi-close</v-icon>
				</v-btn>
			</v-toolbar>
			<v-card-text>
				<v-container>
					<v-row>
						<v-col cols="8">
							<v-card>
								<v-card-text>
									Data Rapor
								</v-card-text>
							</v-card>
						</v-col>
						<v-col cols="4">
							<v-card>
								<v-card-text>
									Toolbox
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
	export default {
		name: 'AllRapors',
		props: { dialog: Object },
		data: () => ({

		}),
		methods: {

		},
		mounted() {

		}
	}
</script>