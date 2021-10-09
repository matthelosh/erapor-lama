<template>
    <Layout :title="page_title">
        <v-container>
            <v-row>
                <v-col cols="12">
                    <v-card>
                        <v-toolbar dense class="mt-5">
                            <v-toolbar-title>Kelas</v-toolbar-title>
                        </v-toolbar>
                        <v-card-text>
                            <v-data-table :items="kelas" :headers="headers" dense>
                                <template v-slot:[`item.mapel`]="{item}">
                                    <span>
                                        {{ item.mapels.length }}
                                    </span>
                                </template>
                                <template v-slot:[`item.tema`]="{item}">
                                    <span>
                                        {{ item.temas.length }}
                                    </span>
                                </template>
                                <template v-slot:[`item.options`]="{item}">
                                    <v-btn icon @click.stop="aturMapel(item)" color="primary" :title="'Atur Mapel Kelas '+item.label">
                                        <v-icon>mdi-bookshelf</v-icon>
                                    </v-btn>
                                    <v-btn icon @click.stop="aturTema(item)" color="info" :title="'Atur Tema Kelas '+item.label">
                                        <v-icon>mdi-ballot</v-icon>
                                        
                                    </v-btn>
                                </template>
                            </v-data-table>
                        </v-card-text>
                    </v-card>
                </v-col>
            </v-row>
        </v-container>
        <div v-if="dialogmapel.show">
            <kelas-mapel :dialog="dialogmapel" @hide="hide"></kelas-mapel>
        </div>
        <kelas-tema v-if="dialogtema.show" :dialog="dialogtema" @hide="dialogtema.show=false"></kelas-tema>
    </Layout>
</template>

<script>
import Layout from '../../Layout/Dashboard'
import KelasMapel from './Modals/KelasMapel'
import KelasTema from './Modals/KelasTema'
export default {
    name: 'Kelas',
    props: {page:String, page_title:String},
    components: {Layout, KelasMapel, KelasTema},
    data: () => ({
        dialogmapel: { show: false },
        rawkelas: [],
        headers:[
            { text: 'No.', value: 'index', sortable: false},
            { text: 'Kode Kelas', value: 'kode_kelas' },
            { text: 'Nama Kelas', value: 'label' },
            { text: 'Tingkat', value: 'tingkat' },
            { text: 'Tema', value: 'tema' },
            { text: 'Mapel', value: 'mapel' },
            { text: 'Opsi', value: 'options'}
        ], 
        dialogtema: { show: false }
    }),
    methods: {
        getKelas() {
            axios({
                method: 'post',
                url: '/admin/kelas'
            }).then(res => {
                this.rawkelas = res.data.kelas
            })
        },
        aturMapel(kelas) {
            this.dialogmapel = { show: true, kelas: kelas }
        },
        aturTema(kelas) {
            this.dialogtema = { show: true, kelas: kelas }
        },
        hide(){
            this.dialogmapel = { show: false }
            this.getKelas()
        }
    },
    computed: {
        kelas() {
            let datas = []
            this.rawkelas.map((item, index) => {
                item.index = index+1
                datas.push(item)
            })

            return datas
        }
    },
    created() {
        this.getKelas()
    }
}
</script>