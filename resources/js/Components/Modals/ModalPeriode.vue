<template lang="html">
    <v-dialog max-width="500" v-model="show" transition="dialog-top-transition">
        <v-card>
            <v-toolbar dense>
                <v-toolbar-title>Tambah Periode</v-toolbar-title>
            </v-toolbar>
            <v-card-text>
                <v-form @submit="simpan">
                    <v-container>
                        <v-row>
                            <v-col cols="12" sm="6">
                                <v-select :items="tapels" v-model="periode.tapel" label="Tahun Pelajaran" outlined dense hide-details></v-select>
                            </v-col>
                            <v-col cols="12" sm="6">
                                <v-select :items="semester" item-key="value" item-text="text" v-model="periode.semester" label="Semester" outlined dense hide-details></v-select>
                            </v-col>
                            <v-col cols="12">
                                <v-textarea v-model="periode.deskripsi" label="Deskripsi" auto-grow rows="1" outlined dense hide-details ></v-textarea>
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
    name: 'ModalPeriode',
    props: { dialog: Object },
    data: () => ({
        periode: {},
        kelas: [],
        semester:[
            {value: 1, text: "Ganjil"},
            {value: 2, text: "Genap"}
        ]
    }),
    methods: {
        simpan(e) {
            e.preventDefault()
            var tapel = this.periode.tapel.split('/')
            var t = tapel[0].substr(2,2)+tapel[1].substr(2,2)
            let data = {
                kode_periode: t+this.periode.semester,
                tapel: this.periode.tapel,
                semester: this.periode.semester,
                deskripsi: this.periode.deskripsi
            }
            axios({
                method: 'post',
                url: '/dashboard/periode/store',
                data: data
            }).then(response => {
                this.periode = Object.assign({},{})
                this.$emit('refresh', response.data.msg)
            }).catch(err => {
                console.log(err.response)
                let snackbar = { show: true, color: 'error', text: err.response.data.errors}
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
        tapels() {
            // 2020/2021
            // 2021/2022
            // ...
            var tapels = []
            var date = new Date()
            var thisYear = date.getFullYear()
            for ( var i = thisYear; i < thisYear + 5; i++ )
            {
                tapels.push((i-1)+'/'+(i))
            }

            return tapels
        }
    },
    created() {
    }
}
</script>