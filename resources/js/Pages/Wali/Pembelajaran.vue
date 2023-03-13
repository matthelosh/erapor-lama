<template>
    <Layout :title="page_title">
        <v-overlay :value="overlay">
          <v-progress-circular
            indeterminate
            size="64"
          ></v-progress-circular>
        </v-overlay>
        <v-container>
            <v-row>
                <v-col cols="12">
                    <v-card>
                        <v-toolbar dense>
                            <v-toolbar-title>{{page_title}}</v-toolbar-title>
                            <v-spacer></v-spacer>
                            
                            <v-btn rounded  :outlined="dark" color="info" @click="showModalPembelajaran" class="mr-3"><v-icon>mdi-microsoft-word</v-icon> Buat RPP</v-btn>
                            <v-btn rounded  :outlined="dark" color="primary" @click="cetakJurnal" ><v-icon>mdi-printer</v-icon> Cetak Jurnal</v-btn>
                        </v-toolbar>
                        <v-card-text>
                            <v-alert v-if="items.length < 1" color="error" border="left" prepend-inner-icon="mdi-leaf" :outlined="dark">
                                <h3 class="text-center">Anda belum membuat pembelajaran.</h3>
                            </v-alert>
                            <div v-else>
                                <v-row v-if="$page.props.user.role == 'wali'">
                                    <v-col cols="12" sm="6" v-for="tema in items" :key="tema.kode_tema">
                                        <v-card elevation="5">
                                            <v-card-text>
                                                <v-list>
                                                    <v-list-item color="warning">
                                                        <v-list-item-content>
                                                            <v-list-item-title class="text-h6">TEMA {{ tema.kode_tema.substr(-1) }}. {{ tema.teks.toUpperCase() }}</v-list-item-title>
                                                        </v-list-item-content>
                                                    </v-list-item>
                                                    <v-expansion-panels focusable accordion color="primary" dense>
                                                        <v-expansion-panel
                                                            v-for="st in tema.subtemas" :key="st.kode_subtema"
                                                            dense
                                                        >
                                                            <v-expansion-panel-header>{{ st.kode_subtema.substr(-1) }}. {{ st.teks }}</v-expansion-panel-header>
                                                            <v-expansion-panel-content dense>
                                                                 <v-list dense>
                                                                    <v-list-item v-for="pembelajaran in st.pembelajaran" :key="pembelajaran.kode_pembelajaran">
                                                                        <v-list-item-content>
                                                                            <v-list-item-title>

                                                                                PEMBELAJARAN KE {{ pembelajaran.ke }}
                                                                                
                                                                                <v-tooltip top>
                                                                                    <template v-slot:activator="{ on, attrs }" >     
                                                                                        <v-icon @click="editRpp(pembelajaran)" color="warning" v-bind="attrs" v-on="on">mdi-file-edit-outline</v-icon>
                                                                                    </template>
                                                                                     <span>Edit Pembelajaran</span>
                                                                                </v-tooltip>
                                                                                <v-tooltip top>
                                                                                    <template v-slot:activator="{ on, attrs }" >     
                                                                                        <v-icon @click="showJurnal(item = { pembelajaran: pembelajaran, tema : {kode: tema.kode_tema, label: tema.teks} ,subtema : {kode: st.kode_subtema, label: st.teks}})" color="info" v-bind="attrs" v-on="on">mdi-teach</v-icon>
                                                                                    </template>
                                                                                     <span>Pelaksanaan</span>
                                                                                </v-tooltip>
                                                                                
                                                                                <v-tooltip top>
                                                                                    <template v-slot:activator="{ on, attrs }" > 
                                                                                        <v-icon @click="penilaian(pembelajaran, tema.teks, st.teks)" color="error" v-bind="attrs" v-on="on">mdi-progress-question</v-icon>
                                                                                    </template>
                                                                                    <span>Penilaian</span>
                                                                                </v-tooltip>
                                                                                <v-tooltip top>
                                                                                    <template v-slot:activator="{ on, attrs }" > 
                                                                                        <v-icon @click="showRpp(pembelajaran, tema.teks, st.teks)" color="primary" v-bind="attrs" v-on="on">mdi-printer</v-icon>
                                                                                    </template>
                                                                                    <span>Cetak RPP</span>
                                                                                </v-tooltip>
                                                                                <v-tooltip top>
                                                                                    <template v-slot:activator="{ on, attrs }" > 
                                                                                        <v-icon @click="hapus(pembelajaran.id)" color="error" v-bind="attrs" v-on="on">mdi-delete</v-icon>
                                                                                    </template>
                                                                                    <span>Hapus RPP</span>
                                                                                </v-tooltip>
                                                                                
                                                                            </v-list-item-title>
                                                                        </v-list-item-content>
                                                                    </v-list-item>
                                                            </v-list>
                                                            </v-expansion-panel-content>
                                                        </v-expansion-panel>
                                                    </v-expansion-panels>
                                                </v-list>
                                            </v-card-text>
                                        </v-card>
                                    </v-col>
                                </v-row>
                                <v-row v-else>
                                    <v-col cols="12" sm="6" v-for="rombel in rombels" :key="rombel.kode_rombel">
                                        <v-card elevation="3" light>
                                            <v-card-text>
                                                <v-list>
                                                    <v-list-item color="warning">
                                                        <v-list-item-content>
                                                            <v-list-item-title class="text-h6">KELAS {{ rombel.label }}</v-list-item-title>
                                                        </v-list-item-content>
                                                    </v-list-item>
                                                    <v-expansion-panels focusable accordion color="primary" dense>
                                                        <v-expansion-panel
                                                            v-for="pb in items[rombel.kode_rombel]" :key="pb.id"
                                                            dense
                                                        >
                                                            <v-expansion-panel-header>PEMBELAJARAN KE {{ pb.ke }}</v-expansion-panel-header>
                                                            <v-expansion-panel-content dense>
                                                                 <v-list dense>
                                                                    <v-list-item>
                                                                        <v-list-item-content>
                                                                            <v-list-item-title>

                                                                               
                                                                                
                                                                                <v-tooltip top>
                                                                                    <template v-slot:activator="{ on, attrs }" >     
                                                                                        <v-icon @click="editRpp(pb)" color="warning" v-bind="attrs" v-on="on">mdi-file-edit-outline</v-icon>
                                                                                    </template>
                                                                                     <span>Edit Pembelajaran</span>
                                                                                </v-tooltip>
                                                                                <v-tooltip top>
                                                                                    <template v-slot:activator="{ on, attrs }" >     
                                                                                        <v-icon color="info" v-bind="attrs" v-on="on">mdi-teach</v-icon>
                                                                                    </template>
                                                                                     <span>Pelaksanaan</span>
                                                                                </v-tooltip>
                                                                                
                                                                                <v-tooltip top>
                                                                                    <template v-slot:activator="{ on, attrs }" > 
                                                                                        <v-icon  color="error" v-bind="attrs" v-on="on">mdi-progress-question</v-icon>
                                                                                    </template>
                                                                                    <span>Penilaian</span>
                                                                                </v-tooltip>
                                                                                <v-tooltip top>
                                                                                    <template v-slot:activator="{ on, attrs }" > 
                                                                                        <v-icon @click="showRpp(pb, $page.props.mapel.label, pb.rombel_id)" color="primary" v-bind="attrs" v-on="on">mdi-printer</v-icon>
                                                                                    </template>
                                                                                    <span>Cetak RPP</span>
                                                                                </v-tooltip>
                                                                                <v-tooltip top>
                                                                                    <template v-slot:activator="{ on, attrs }" > 
                                                                                        <v-icon @click="hapus(pb.id)" color="error" v-bind="attrs" v-on="on">mdi-delete</v-icon>
                                                                                    </template>
                                                                                    <span>Hapus RPP</span>
                                                                                </v-tooltip>
                                                                                
                                                                            </v-list-item-title>
                                                                        </v-list-item-content>
                                                                    </v-list-item>
                                                            </v-list>
                                                            </v-expansion-panel-content>
                                                        </v-expansion-panel>
                                                    </v-expansion-panels>
                                                </v-list>
                                            </v-card-text>
                                        </v-card>
                                    </v-col>
                                </v-row>
                            </div>
                        </v-card-text>
                    </v-card>
                </v-col>
            </v-row>
        </v-container>
        <modal-pembelajaran v-if="dialog.show" :dialog="dialog" @hide="hideModalPembelajaran"></modal-pembelajaran>
        <rpp v-if="rpp.show" :dialog="rpp" @hide="hideRpp"></rpp>
        <jurnal v-if="jurnal.show" :dialog="jurnal" @hide="hideJurnal"></jurnal>
        <cetak-jurnal v-if="cetakjurnal.show" :dialog="cetakjurnal" @hide="cetakjurnal.show = false"></cetak-jurnal>
    </Layout>
</template>
 
<script>
import Layout from '../../Layout/Dashboard'
import ModalPembelajaran from '../../Components/Modals/ModalPembelajaran'
import Rpp from '../../Components/Modals/Rpp'
import Jurnal from '../../Components/Modals/Jurnal'
import CetakJurnal from '../../Components/Modals/CetakJurnal'
export default {
    name: 'Pembelajaran',
    components: { Layout, ModalPembelajaran, Rpp, Jurnal, CetakJurnal },
    props: {
        page_title: String,
        page: String
    },
    data: () => ({
        items: [],
        dialog: {show: false},
        rpp: { show: false },
        jurnal: { show: false },
        cetakjurnal: { show: false },
        overlay: false
    }),
    methods: {
        cetakJurnal() {
            this.cetakjurnal = { show: true }
        },
        showJurnal(item) {
            this.jurnal = { show: true, jurnal: item }
        },
        hideJurnal() {
            this.jurnal = { show: false }
        },
        editRpp(item) {
             this.dialog = {show: true , pembelajaran: item }

        },
        showRpp(item, tema, subtema ) {
            this.rpp = { show: true, rpp: item, tema: tema, subtema: subtema }
        },
        hideRpp(){
            this.rpp = { show: false }
        },
        hideModalPembelajaran(){
            this.dialog = { show: false}
            this.getPembelajaran()
        },
        getPembelajaran() {
            if ( this.$page.props.user.role == 'wali' ) {
                this.overlay = true
                axios({
                    method: 'post',
                    url: '/dashboard/pembelajaran?role=wali',
                    data: {
                        periode: this.$page.props.periode,
                        rombel: this.$page.props.rombel
                    }
                }).then( res => {
                    var items = []
                    if( res.data.pembelajarans) {
                        res.data.pembelajarans.forEach((item, index) => {
                            item.no = index+1
                            items.push(item)
                        })
                    }
                    this.items = items
                    this.overlay = false
                })
            } else {
                this.overlay = true
                axios({
                    method: 'post',
                    url: '/dashboard/pembelajaran?role=mapel',
                    data: {
                        periode: this.$page.props.periode,
                        mapel: this.$page.props.mapel.kode_mapel,
                        guru: this.$page.props.user.nip
                        // rombel: this.$page.props.rombel
                    }
                }).then( res => {
                   var items = _.groupBy(res.data.pembelajarans, 'rombel_id')
                    this.items = items
                    this.overlay = false
                })
            }
        },
        showModalPembelajaran(){
            this.dialog = {show: true}
        },
        mupels(text) {
            // let data =  JSON.parse(text)
            let list = ''
            // data.forEach(item => {
                
            //     var sublist = ''
            //     item.kds.forEach(kd => {
            //         sublist += '<li>'+kd.kode_kd+'</li>'
            //     })

            //     list += '<li>'+item.label+' <ul>'+sublist+'</ul></li>'
            // })
            // var mupel = `<ol>${list}</ol>`

            // return mupel
        }
    },
    computed: {
        dark() {
            return this.$vuetify.theme.dark
        },
        rombels() {
            return this.$page.props.rombels
        }
    },
    mounted() {
        this.getPembelajaran()
    }
}
</script>
