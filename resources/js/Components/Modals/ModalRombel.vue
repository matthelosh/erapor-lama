<template lang="html">
    <div>
        <v-dialog max-width="600" v-model="show" transition="dialog-top-transition">
            <v-card>
                <v-toolbar dense>
                    <v-toolbar-title>Buat Rombel {{ kode_rombel }}</v-toolbar-title>
                </v-toolbar>
                <v-card-text>
                    <v-form @submit.stop="simpan">
                        <v-row class="mt-5">
                            <v-col cols="12" sm="6">
                                <v-select v-model="rombel.periode_id" :items="periodes" item-key="value" item-text="text" label="Periode" dense hide-details outlined></v-select>
                            </v-col>
                            <v-col cols="12" sm="3">
                                <v-select v-model="rombel.kelas_id" :items="kelas" item-key="value" item-text="text" label="Kelas" dense hide-details outlined @change="onChange"></v-select>
                            </v-col>
                            <v-col cols="12" sm="3">
                                <v-select v-model="rombel.abjad" :items="[{text:'0', value:''},{text: 'A', value: 'a'},{text: 'B', value: 'b'}]" item-key="value" item-text="text" label="Pararel" dense hide-details outlined></v-select>
                            </v-col>
                            <v-col cols="12" sm="4">
                                <v-text-field label="Nama Rombel" v-model="rombel.label" dense hide-details outlined></v-text-field>
                            </v-col>
                            <v-col cols="12" sm="8">
                                <v-select v-model="rombel.guru_id" :items="walis" item-key="value" item-text="text" label="Wali Kelas" dense hide-details outlined></v-select>
                            </v-col>
                            <v-col cols="12" class="d-flex justify-center">
                                <v-btn type="submit" color="primary">Simpan</v-btn>
                            </v-col>
                        </v-row>
                    </v-form>
                </v-card-text>
            </v-card>
        </v-dialog>
        <v-snackbar v-model="snackbar.show" :color="snackbar.color" multi-line top right>
            {{ snackbar.text }}
        </v-snackbar>
    </div>
</template>
<script>

    export default {
        name: 'ModalRombel',
        props: { dialog: Object },
        data: () => ({
            rombel: {},
            periodes: [],
            kelas: [],
            walis:[],
            snackbar: {}
        }),
        methods: {
            // onChange(e) {
            //     console.log(e)
            // },
            // kode_rombel','label','kelas_id','guru_id','periode_id'
            simpan(e) {
                e.preventDefault()
                let data = Object.assign({}, this.rombel)
                if (this.dialog.mode == 'edit' ) data._method = 'put'
                let url = (this.dialog.mode == 'create') ? '/dashboard/rombel/store' : '/dashboard/rombel/'+this.rombel.id
                data.kode_rombel = this.rombel.periode_id+'-'+this.rombel.kelas_id+this.rombel.abjad
                console.log(data)
                axios({
                    method: 'post',
                    url: url,
                    data: data
                }).then( response => {
                    // alert('ok')
                    
                    // if ( response.success == true ) {
                        // this.snackbar = { show: true, color: 'success', text: res.data.msg }
                        this.$emit('submitted')
                        this.snackbar = { show:true, color: 'success', text: 'Rombel baru disimpan' }
                    // } else {

                    // }
                }).catch( err => {
                    // console.log(err.response.data.errors.kode_rombel[0])
                    // console.log(err)
                    // alert('ko')
                    // if ( err ) {
                        let errors = err.response.data.errors
                        let msg = ''
                        
                            Object.entries(errors).forEach((k,v) => {
                                // console.log(k[1][0])
                                msg += k[1]+', '
                            })
                        this.snackbar = { show: true, color: 'error', text: 'Cek kembali: ' + msg } 
                    // }
                })
            },
            initData() {
                axios({
                    method: 'post',
                    url: '/dashboard/periode',

                }).then(res => {
                    var periodes = res.data.periode;
                    periodes.forEach(item => {
                        this.periodes.push({text: (item.tapel + '-Semester ' + item.semester), value: item.kode_periode})
                    })
                })

                axios({
                    method: 'post',
                    url: '/dashboard/kelas'
                }).then(res => {
                    res.data.kelas.forEach(item => {
                        this.kelas.push({text: item.label, value: item.kode_kelas})
                    })
                })

                axios({
                    method: 'post',
                    url: '/dashboard/guru'
                }).then( res => {
                    res.data.gurus.forEach( item => {
                        this.walis.push( { text: item.name, value: item.nip } )
                    })
                }).catch( err => {
                    console.log( err.response.msg )
                })
            }
        },
        computed: {
            show: {
                get() {
                    return this.dialog.show
                },
                set (val) {
                    return this.$emit('hide', val)
                    this.rombel = Object.assign({},{})
                }
            },
            selected: {
                get() {
                    return this.dialog.rombel
                    this.rombel = this.dialog.rombel
                }
            },
            kode_rombel(){
                return (( this.rombel.periode_id != undefined ) ? this.rombel.periode_id: '') +'-'+((this.rombel.kelas_id != undefined) ? (this.rombel.kelas_id+this.rombel.abjad ): '')
            }
        },
        mounted() {
            // alert(this.dialog.show)
            if ( this.dialog.mode == 'edit' ) {
                let rombel = this.dialog.rombel
                var abj = rombel.kode_rombel.slice(-1)
                rombel.abjad = (!isNaN(parseFloat(abj)) && isFinite(abj)) ? '': abj
                this.rombel = this.dialog.rombel
            }
            this.rombel.periode_id = this.$page.props.periode
            this.initData()
        }
    }
</script>