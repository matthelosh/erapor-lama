<template>
    <div>
        <v-dialog v-model="show">
            <v-card>
                <v-toolbar dense>
                    <v-toolbar-title>Tema Kelas {{ dialog.kelas.label }}</v-toolbar-title>
                    <v-spacer></v-spacer>
                    <v-btn icon @click="show = false"><v-icon>mdi-close</v-icon></v-btn>
                </v-toolbar>
                <v-card-text>
                    <v-container>
                        <v-row>
                            <v-col cols="12" sm="6">
                                <v-data-table :items="temas" :headers="headertema" dense>
                                    <template v-slot:top>
                                        <v-container>
                                            <v-row>
                                                <v-col cols="12" sm="6">Daftar Tematik Kelas {{ dialog.kelas.label }}</v-col>
                                                <v-col cols="12" sm="6">
                                                    
                                                </v-col>
                                            </v-row>
                                        </v-container>
                                    </template>
                                    <template v-slot:[`item.options`]="{item}">
                                        <span>
                                            <v-btn icon @click="viewSubtema(item)">
                                                <v-icon>mdi-magnify</v-icon>
                                            </v-btn>
                                            
                                        </span>
                                    </template>
                                </v-data-table>
                            </v-col>
                            <v-col cols="12" sm="6">
                                <v-data-table :items="subtemas" :headers="headersubtema" dense no-data-text="Klik icon kaca pembesar untuk melihat Sub Tema">
                                    <template v-slot:top>
                                        <v-container>
                                            <v-row>
                                                <v-col cols="12" >Daftar Subtema: <b>{{ tema }}</b></v-col>
                                                
                                            </v-row>
                                        </v-container>
                                    </template>
                                    
                                </v-data-table>
                            </v-col>
                        </v-row>
                    </v-container>
                </v-card-text>
            </v-card>
        </v-dialog>
    </div>
</template>

<script>
export default {
    name: 'KelasTema',
    props: {
        dialog: Object
    },
    data: () => ({
        temas: [],
        tema: '',
        headertema: [
            { text: 'No.', value: 'index', sortable: false },
            { text: 'Kode Tema', value: 'tema.kode_tema'},
            { text: 'Teks Tema', value: 'tema.teks' },
            { text: 'Opsi', value: 'options' }
        ],
        subtemas: [],
        headersubtema: [
            { text: 'No.', value: 'index', sortable: false },
            { text: 'Kode Subtema', value: 'kode_subtema'},
            { text: 'Teks Subtema', value: 'teks' },
            
        ]
    }),
    methods: {
        init() {
            const kelas = this.dialog.kelas
            axios({
                method: 'post',
                url: '/admin/tema?kelas='+kelas.kode_kelas
            }).then( response => {
                var temas = []
                response.data.datas.forEach((item,index) => {
                    item.index = index+1
                    temas.push(item)
                })
                this.temas = temas
            }).catch( err => {
                console.log( err.response )
            })
        },
        viewSubtema(item) {
            // console.log(tema)
            var subtemas = []
            item.tema.subtemas.forEach((subtema,index) => {
                subtema.index = index+1
                subtemas.push(subtema)
            })
            this.subtemas = subtemas
        }
    },
    computed: {
        show: {
            get() {
                return this.dialog.show
            },
            set(val) {
                this.$emit('hide', val)
            }
        }
    },
    mounted() {
        this.init()
    }
}
</script>