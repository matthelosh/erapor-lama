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
                        
                            <v-container>
                                <v-row class="pt-5">
                                    <v-col cols="12" sm="3">
                                        <h1 class="mb-2">Foto Siswa</h1>
                                        <v-divider horizontal></v-divider>
                                        <v-row class="mt-2">
                                            <v-col cols="12">
                                                <input type="file" @change="onFotoPicked" ref="foto" class="d-none" accept="image/*" id="fotoInput">
                                            </v-col>
                                            <v-col cols="12" >
                                                <v-avatar size="200" height="auto" @click="pickFoto" cover elevation-10 style="box-shadow: 0 0 5px rgba(0,0,0,0.5);border: 5   px solid white" tile rounded> 
                                                    <v-tooltip bottom>
                                                        <template v-slot:activator="{on, attrs}">
                                                            <img :src="fotoUrl" v-bind="attrs" v-on="on" onerror="this.error=false;this.src='/img/siswi-avatar.png'">
                                                        </template>
                                                        <span>Klik untuk Menambah Foto Siswa</span>
                                                    </v-tooltip>
                                                </v-avatar>
                                            </v-col>
                                            <v-col cols="12" >
                                                <h3 >{{fotoname}}</h3>
                                            </v-col>
                                        </v-row>
                                        
                                    </v-col>
                                    <v-col cols="12" sm="9">
                                        <h1 class="mb-2">Data Siswa</h1>
                                        <v-divider horizontal></v-divider>
                                        <v-form @submit="save">
                                        <v-row class="mt-2">
                                            <v-col cols="12" sm="4">
                                                <v-text-field label="NIK (Boleh Kosong)" v-model="newsiswa.nik" append-icon="mdi-barcode" hide-details dense> </v-text-field>
                                            </v-col>
                                            <v-col cols="12" sm="4">
                                                <v-text-field label="NISN" v-model="newsiswa.nisn" append-icon="mdi-barcode" hide-details dense></v-text-field>
                                            </v-col>
                                            <v-col cols="12" sm="4">
                                                <v-text-field label="NIS" v-model="newsiswa.nis" append-icon="mdi-barcode" hide-details dense></v-text-field>
                                            </v-col>
                                            <v-col cols="12" sm="6">
                                                <v-text-field label="Nama Lengkap" v-model="newsiswa.nama" append-icon="mdi-account-details" hide-details dense></v-text-field>
                                            </v-col>
                                            <v-col cols="12" sm="2">
                                                <v-select :items="[{text: 'Laki-laki', value: 'l'}, {text: 'Perempuan', value: 'p'}]" item-value="value" item-text="text" v-model="newsiswa.jk" dense hide-details label="Jenis Kelamin"></v-select>
                                            </v-col>
                                            <v-col cols="12" sm="4">
                                                <v-text-field label="Tempat Lahir" v-model="newsiswa.tempat_lahir" append-icon="mdi-city" hide-details dense></v-text-field>
                                            </v-col>
                                            <v-col cols="12" sm="4">
                                                <v-text-field label="Tanggal Lahir" v-model="newsiswa.tanggal_lahir" hide-details dense type="date"></v-text-field>
                                            </v-col>
                                            <v-col cols="12" sm="4">
                                                <v-select label="Agama" v-model="newsiswa.agama" 
                                                :items="agama" item-text="text" item-value="value" hide-details dense type="date"></v-select>
                                            </v-col>
                                            <v-col cols="12" sm="4">
                                                <v-textarea label="Alamat" v-model="newsiswa.alamat" hide-details dense  auto-grow rows="1" append-icon="mdi-home-account" row-height="4"></v-textarea>
                                            </v-col>
                                            <v-col cols="6" sm="2">
                                                <v-text-field label="RT" v-model="newsiswa.rt" hide-details dense></v-text-field>
                                            </v-col>
                                            <v-col cols="6" sm="2">
                                                <v-text-field label="RW" v-model="newsiswa.rw" hide-details dense></v-text-field>
                                            </v-col>
                                            <v-col cols="12" sm="4">
                                                <v-text-field label="Desa" v-model="newsiswa.desa" hide-details dense append-icon="mdi-city"></v-text-field>
                                            </v-col>
                                            <v-col cols="12" sm="4">
                                                <v-text-field label="Kecamatan" v-model="newsiswa.kec" hide-details dense append-icon="mdi-city"></v-text-field>
                                            </v-col>
                                            <v-col cols="12" sm="5">
                                                <v-text-field label="Kabupaten/Kota" v-model="newsiswa.kab" hide-details dense append-icon="mdi-city"></v-text-field>
                                            </v-col>
                                            <v-col cols="12" sm="3">
                                                <v-text-field label="Kode Pos" v-model="newsiswa.kode_pos" hide-details dense append-icon="mdi-inbox"></v-text-field>
                                            </v-col>
                                            <v-col cols="12" sm="4">
                                                <v-text-field label="No. HP" v-model="newsiswa.hp" hide-details dense append-icon="mdi-cellphone"></v-text-field>
                                            </v-col>
                                            <v-col cols="12" sm="6">
                                                <v-text-field label="Email" v-model="newsiswa.email" hide-details dense append-icon="mdi-gmail"></v-text-field>
                                            </v-col>
                                            <v-col cols="12" sm="6">
                                                <v-text-field label="Sekolah / TK Asal" v-model="newsiswa.sekolah_asal" hide-details dense append-icon="mdi-office-building"></v-text-field>
                                            </v-col>

                                            <v-col cols="12" class="d-flex justify-center">
                                                <v-btn type="submit" color="primary">Simpan</v-btn>
                                            </v-col>
                                        </v-row>
                                        </v-form>
                                    </v-col>
                                </v-row>
                            </v-container>
                        
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
                url: '/admin/siswa/store',
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
