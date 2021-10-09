<template lang="html">
    <v-dialog max-width="500" v-model="show" transition="dialog-top-transition">
        <v-card>
            <v-toolbar dense>
                <v-toolbar-title>Tambah Mapel</v-toolbar-title>
            </v-toolbar>
            <v-card-text>
                <v-form @submit="simpan">
                    <v-container>
                        <v-row>
                            <v-col cols="12" sm="6">
                                <v-text-field v-model="mapel.kode_mapel" label="Kode Mapel" outlined dense hide-details></v-text-field>
                            </v-col>
                            <v-col cols="12" sm="6">
                               <v-text-field v-model="mapel.nama_mapel" label="Nama Mapel" outlined dense hide-details />
                            </v-col>
                            <v-col cols="12" sm="6">
                               <v-text-field v-model="mapel.label" label="label" outlined dense hide-details />
                            </v-col>
                            <v-col cols="12">
                                <v-textarea v-model="mapel.deskripsi" label="Deskripsi" auto-grow rows="1" outlined dense hide-details ></v-textarea>
                            </v-col>
                            <v-col cols="12" class="d-flex justify-center">
                                <v-btn type="submit" color="primary">Simpan</v-btn>
                            </v-col>

                        </v-row>
                    </v-container>
                </v-form>
            </v-card-text>
        </v-card>
    </v-dialog>
</template>
<script>

export default {
    name: 'ModalMapel',
    props: { dialog: Object },
    data: () => ({
        mapel: {},
        
    }),
    methods: {
        simpan(e) {
            e.preventDefault()
            
            axios({
                method: 'post',
                url: '/dashboard/mapel/store',
                data: this.mapel
            }).then(response => {
                this.mapel = Object.assign({},{})
                this.$emit('refresh', response.data.msg)
            }).catch(err => {
                console.log(err.response)
                this.snackbar = { show: true, color: 'error', text: err.response.data.errors}
                this.$emit('err', snackbar)
            })
        },
        
    },
    computed: {
        show: {
            get() {
                return this.dialog.show
            },
            set (val) {
                return this.$emit('hide', val)
            }
        },
        
    },
    created() {
    }
}
</script>