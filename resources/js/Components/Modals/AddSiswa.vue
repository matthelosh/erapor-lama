<template lang="html">
    <div>
        <v-dialog
                v-model="show"
                transition="dialog-bottom-transition"
                fullscreen
                hide-overlay
            >
            <v-card>
                <v-toolbar 
                    dense
                    color="primary"
                    dark
                >
                    <v-toolbar-title>
                        <v-icon>mdi-account-plus</v-icon>
                        Tambah Siswa Baru
                    </v-toolbar-title>
                    <v-spacer></v-spacer>
                    <v-btn fab small @click="$emit('hide')" color="error">
                        <v-icon>mdi-close</v-icon>
                    </v-btn>
                </v-toolbar>
                
                <v-card-text>
                        
                
                        
                </v-card-text>
            </v-card>
        </v-dialog>
        <v-snackbar v-model="snackbar.show" :color="snackbar.color" bottom right>{{ snackbar.text }}</v-snackbar>
    </div>
</template>

<script>
export default {
    name: 'ModalSiswa',
    props: {
        modal: Object,
        siswa: Object,
        
    },
    data: () => ({
        snackbar: { show: false },
        newsiswa: {},
        fotoUrl: '/img/siswi-avatar.png',
        fotoname: 'Klik gambar untuk ambil file foto',
        agama: [
            {
                text: 'Islam',
                value: 'Islam'
            },
            {
                text: 'Protestan',
                value: 'Protestan'
            },
            {
                text: 'Katolik',
                value: 'Katolik'
            },
            {
                text: 'Hindu',
                value: 'Hindu'
            },
            {
                text: 'Budha',
                value: 'Budha'
            },
            {
                text: 'Konghuchu',
                value: 'Konghuchu'
            },
        ]
    }),
    methods: {
        save(e) {
            e.preventDefault()
            let data = JSON.stringify(this.newsiswa)
            let foto = document.querySelector('#fotoInput')  
            // console.log(foto)
            var formData = new FormData()
            formData.append("foto", foto.files[0])
            formData.append('siswa', data)
            console.log(formData)
            axios({
                method: 'post',
                url: '/dashboard/siswa/store',
                data: formData,
                headers: {
                    'Content-Type': 'multipart/form-data'
                },
            }).then(response => {
                this.snackbar = { show: true, color: 'success', text: response.data.msg }
                this.newsiswa = {}
                this.fotoUrl = '/img/siswi-avatar.png'
                this.fotoname = 'Klik gambar untuk ambil file foto'
            }).catch(err => {
                this.snackbar = { show: true, color: 'error', text: err.response.data.msg }
            })
        },
        pickFoto(){
            this.$refs.foto.click()
        },
        onFotoPicked(e){
            const files = e.target.files
            this.fotoname = files[0].name
            const fileReader = new FileReader()
            fileReader.addEventListener('load', () => {
                this.fotoUrl = fileReader.result
            })
            fileReader.readAsDataURL(files[0])
            // this.newsiswa.foto = files[0]

        },
        setSiswa(siswa) {
            this.newsiswa = siswa
        }
    },
    computed: {
        show: {
            get() {
                if (this.modal.mode == 'edit') {
                    this.newsiswa=this.siswa
                    this.fotoUrl = '/storage/img/siswas/'+this.siswa.nisn+'.jpg'
                }
                
                return this.modal.show
            },
            set(val) {
                return this.$emit('hide', val)
                this.newsiswa = {}
            }
        },
        
    },
    created() {
        this.$parent.$on('siswa', function(){
            console.log(siswa)
        })
    }
}
</script>
