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
        
    },
    computed: {
        
        
    },
    created() {
        this.$parent.$on('siswa', function(){
            console.log(siswa)
        })
    }
}
</script>
