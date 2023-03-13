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
                        <v-toolbar dense >
                            <v-toolbar-title>NILAI HARIAN {{ ($page.props.user.role == 'wali') ? $page.props.rombel.label : $page.props.mapel.nama_mapel.toUpperCase()}} </v-toolbar-title>
                            <v-spacer/>
                            <v-btn fab small :outlined="dark" color="warning" @click="getNilais" class="mr-3"><v-icon>mdi-reload</v-icon></v-btn>
                            <v-btn rounded :outlined="dark" color="primary" @click=" ph = !ph">{{ (ph == true) ? 'Sembunyikan' : 'Lihat' }}</v-btn>
                        </v-toolbar>
                        <v-expand-transition>
                            <v-card-text v-show="ph">
                                <v-row>
                                    <v-col cols="12" sm="3" v-for="item in items" :key="item.text">
                                        <v-card  max-width="300" class="card" light>
                                            
                                            <v-card-text >

                                                <v-list dense style="background: transparent!important;">
                                                    <v-subheader dark style="background: rgba(0,0,0,0.8);text-align: center!important;font-weight: 800;color: white;justify-content:center;">{{ item.text.toUpperCase() }}</v-subheader>

                                                    <v-list-item-group v-if="item.value=='pabp' || ($page.props.mapel && $page.props.mapel.kode_mapel == 'pabp') " class="my-2 elevation-3" rounded>
                                                        <v-list-item :style="bg((item.nh1 ? item.nh1.prosentase : 0), (item.nh1 ? item.nh1.jml_kd : 0))" @click="showModalNilai(item, 'uh', null, 'k1')" :dark="((item.nh1) && item.nh1.prosentase > 80) ? true:false">
                                                            <v-list-item-content>
                                                                <v-list-item-title>K-1:SPIRITUAL</v-list-item-title>
                                                            </v-list-item-content>
                                                            <v-list-item-icon>
                                                                <v-icon text color="warning">
                                                                    {{ Math.round(item.nh1 ? item.nh1.prosentase: 0) }}%
                                                                </v-icon>
                                                            </v-list-item-icon>
                                                        </v-list-item>
                                                    </v-list-item-group>
                                                    <v-list-item-group v-if="item.value=='ppkn' || ($page.props.mapel && $page.props.mapel.kode_mapel == 'ppkn') " class="my-2 elevation-3">
                                                        <v-list-item :style="bg(item.nh2.prosentase, item.nh2.jml_kd)" @click="showModalNilai(item, 'uh', null, 'k2')">
                                                            <v-list-item-content>
                                                                <v-list-item-title>K-2:SOSIAL</v-list-item-title>
                                                            </v-list-item-content>
                                                            <v-list-item-icon>
                                                                <v-icon text color="warning">
                                                                    {{ Math.round(item.nh2.prosentase) }}%
                                                                </v-icon>
                                                            </v-list-item-icon>
                                                        </v-list-item>
                                                    </v-list-item-group>
                                                    <v-list-item-group v-if="item.nh3" class="my-2 elevation-3">
                                                        <v-list-item :style="bg(item.nh3.prosentase, item.nh3.jml_kd)" @click="showModalNilai(item, 'uh', null,'k3')">
                                                            
                                                            <v-list-item-content>
                                                                <v-list-item-title>K-3:PENGETAHUAN</v-list-item-title>
                                                            </v-list-item-content>
                                                            <v-list-item-icon>
                                                                <v-icon text color="warning">
                                                                    {{ Math.round(item.nh3.prosentase) }}%
                                                                </v-icon>
                                                            </v-list-item-icon>
                                                        </v-list-item>
                                                    </v-list-item-group>
                                                    <v-list-item-group v-if="item.nh4" class="my-2 elevation-3">
                                                        <v-list-item :style="bg(item.nh4.prosentase, item.nh4.jml_kd)" @click="showModalNilai(item, 'uh', null,'k4')">
                                                            <v-list-item-content>
                                                                <v-list-item-title>K-4:KETERAMPILAN</v-list-item-title>
                                                            </v-list-item-content>
                                                            <v-list-item-icon>
                                                                <v-icon text color="warning">
                                                                    {{ Math.round(item.nh4.prosentase) }}%
                                                                </v-icon>
                                                            </v-list-item-icon>
                                                        </v-list-item>
                                                    </v-list-item-group>
                                                    
                                                </v-list>
                                            </v-card-text>
                                        </v-card>
                                    </v-col>
                                </v-row>
                            </v-card-text>
                        </v-expand-transition>
                    </v-card>
                </v-col>
                <v-col cols="12"> 
                    <v-card>
                        <v-toolbar dense >
                            <v-toolbar-title>NILAI UTS {{ ($page.props.user.role == 'wali') ? $page.props.rombel.label : $page.props.mapel.nama_mapel.toUpperCase()}}</v-toolbar-title>
                            <v-spacer/>
                            <v-btn rounded :outlined="dark" color="primary" @click=" pts = !pts">{{ (pts == true) ? 'Sembunyikan' : 'Lihat' }}</v-btn>
                        </v-toolbar>
                        <v-expand-transition>
                            <v-card-text v-show="pts">
                                <v-row>
                                    <v-col cols="12" sm="3" v-for="item in items" :key="item.text">
                                        <!-- <v-card  max-width="300" :style="bg_card(item.value)" class="card"> -->
                                        <v-card  max-width="300" light class="card">
                                            
                                            <v-card-text >

                                                <v-list dense style="background: transparent!important;">
                                                    <v-subheader dark style="background: rgba(0,0,0,0.8);text-align: center!important;font-weight: 800;">{{ item.text.toUpperCase() }}</v-subheader>

                                                    
                                                    <v-list-item-group class="my-2 elevation-3" v-if="item.nts">
                                                        <v-list-item :style="bg(item.nts.prosentase, item.nts.jml_kd)" @click="showModalNilai(item, 'pts', 'ts','k3')" >
                                                            
                                                            <v-list-item-content>
                                                                <v-list-item-title>K-3</v-list-item-title>
                                                            </v-list-item-content>
                                                            <v-list-item-icon>
                                                                <v-icon text color="warning">
                                                                    {{ Math.round(item.nts.prosentase) }}%
                                                                </v-icon>
                                                            </v-list-item-icon>
                                                        </v-list-item>
                                                    </v-list-item-group>
                                                    
                                                </v-list>
                                            </v-card-text>
                                        </v-card>
                                    </v-col>
                                </v-row>
                            </v-card-text>
                        </v-expand-transition>
                    </v-card>
                </v-col>
                <v-col cols="12"> 
                    <v-card>
                        <v-toolbar dense >
                            <v-toolbar-title>NILAI UAS {{ ($page.props.user.role == 'wali') ? $page.props.rombel.label : $page.props.mapel.nama_mapel.toUpperCase()}}</v-toolbar-title>
                            <v-spacer/>
                            <v-btn rounded :outlined="dark" color="primary" @click=" pas = !pas">{{ (pas == true) ? 'Sembunyikan' : 'Lihat' }}</v-btn>
                        </v-toolbar>
                        <v-expand-transition>
                            <v-card-text v-show="pas">
                                <v-row>
                                    <v-col cols="12" sm="3" v-for="item in items" :key="item.text" v-if="item.nas">
                                        <v-card  max-width="300" light class="card">
                                            
                                            <v-card-text >

                                                <v-list dense style="background: transparent!important;">
                                                    <v-subheader dark style="background: rgba(0,0,0,0.8);text-align: center!important;font-weight: 800;">{{ item.text.toUpperCase() }}</v-subheader>

                                                    
                                                    <v-list-item-group class="my-2 elevation-3">
                                                        <v-list-item :style="bg(item.nas.prosentase, item.nas.jml_kd)" @click="showModalNilai(item, 'pas', null,'k3')">
                                                            
                                                            <v-list-item-content>
                                                                <v-list-item-title>K-3</v-list-item-title>
                                                            </v-list-item-content>
                                                            <v-list-item-icon>
                                                                <v-icon text color="warning">
                                                                    {{ Math.round(item.nas.prosentase) }}%
                                                                </v-icon>
                                                            </v-list-item-icon>
                                                        </v-list-item>
                                                    </v-list-item-group>
                                                    
                                                </v-list>
                                            </v-card-text>
                                        </v-card>
                                    </v-col>
                                </v-row>
                            </v-card-text>
                        </v-expand-transition>
                    </v-card>
                </v-col>
                
            </v-row>
        </v-container>
        <v-snackbar v-model="snackbar.show" :color="snackbar.color" bottom right  :timeout="snackbar.timeout">
            <template v-slot:action="{ attrs }">
                <v-btn color="white" icon v-bind="attrs" @click="snackbar.show = false"><v-icon>mdi-close</v-icon></v-btn>
            </template>
            {{snackbar.text}}
            
        </v-snackbar>

        <modal-nilai :dialog="modalNilai" @hide="modalNilai.show = false; init()" v-if="modalNilai.show == true"></modal-nilai>
    </Layout>
</template>
<script>
import Layout from '../../Layout/Dashboard'
import ModalNilai from './Modals/ModalNilai'
// import ModalNilai from '../Components/Modals/ModalNilai.vue';
export default {
    name: 'Penilaian',
    props: { page_title: String, page: String },
    components:{Layout, ModalNilai},
    data: ()=>({
        ph: true,
        pts: false,
        pas: false,
        items:[],
        snackbar: {
            show: false,
            color: 'error',
            text: 'Halo',
            timeout: 500
        },
        modalNilai: {
            show: false
        },
        overlay: false
    }),
    methods: {
        bg_card(mapel) {
            if (this.$page.props.rombel) {
                return 'background-image: url("/img/'+mapel+'.jpg");background-size: cover;background-position: center center;'
            } else {
                var smapel = this.$page.props.mapel.kode_mapel
                return 'background-image: url("/img/'+smapel+'.jpg");background-size: cover;background-position: center center;'
            }

        },
        bg(prosentase, jml_kd) {
            return 'background-image: linear-gradient(75deg, '+ ((prosentase > 80 ? "#849324de" : (prosentase > 20) ? "#FFB30Fde" : "#FD151Bde") +' '+prosentase+'%, #ffffffde '+(jml_kd - prosentase))+'%); border: solid 1px '+((prosentase > 80) ? "#849324de" : (prosentase > 50) ? "#FFB30Fde" : "#FD151Bde")+';color:'+((prosentase > 80) ? '#ffffffde': 'black!important')+';'
            // return 'background-image: linear-gradient(75deg, green 80%, white 20%);'
        },

       init(){
           
            var role = this.$page.props.user.role
            // setTimeout(function(role){
                if ( role == 'wali') {
                   this.getMapels()
                } else {
                    this.getRombels()
                }
            // }, 500)
            this.getNilais(); 
        },

      
        getNilais() {
            this.overlay = true
            if(this.$page.props.mapel == null) {
                axios.post('/wali/nilai?role=wali')
                .then( res => {
                    var datas = res.data.ikhtisar
                    var items = []
                    Object.values(datas).forEach(item => {
                        items.push({
                            text: item.label, 
                            value: item.kode, 
                            nh1: (item.nh1) ? item.nh1 : null,
                            nh2: (item.nh2) ? item.nh2 : null,
                            nh3: item.nh3, 
                            
                            nh4: item.nh4, 
                            
                            nts: item.nts, 
                            nas:item.nas
                            })
                    })
                        this.items = items
                       this.overlay = false
                }).catch( err => {
                    console.log(err)
                })
            } else {
                axios({
                    method: 'post',
                    url: '/mapel/nilai?role='+this.$page.props.user.role
                }).then( res => {
                    var datas = res.data.ikhtisar
                    var items = []
                    Object.values(datas).forEach(item => {
                        items.push({
                            text: item.label, 
                            value: item.kode, 
                            nh1: (item.nh1) ? item.nh1 : null,
                            nh2: (item.nh2) ? item.nh2 : null,
                            nh3: item.nh3, 
                            
                            nh4: item.nh4, 
                            
                            nts: item.nts, 
                            nas:item.nas
                            })
                    })
                    this.items = items
                    this.overlay = false
                }).catch( err => {
                    console.log(err)
                })
            }
        },
        getRombels(){
            axios.post('/mapel/rombel?periode='+this.$page.props.periode_aktif.kode_periode)
                .then( res => {
                    var rombels = []
                    res.data.rombels.forEach(rombel => {
                        rombels.push({text: rombel.label, value: rombel.kode_rombel})
                    })
                    this.items = rombels
                }).catch( err => {
                    console.log( err.response)
                })
        },
        getMapels(){
            axios.post('/mapel/mapel?tingkat='+this.$page.props.rombel.kelas_id)
                .then(res => {
                    var mapels = []
                    res.data.mapels.forEach(mapel => {
                        mapels.push({ text: mapel.label, value: mapel.kode_mapel})
                    })

                    // this.items = mapels
                }).catch(err => {
                    console.log( err.response)
                })
        },
        
        showModalNilai(val, jenis, ppn, aspek) {
            // this.snackbar = {
            //     show: true,
            //     color: 'success',
            //     text: val,
            //     timeout: 2000
            // }
            /// alert('halo')
            // console.log(ppn)
            // let by = this.$page.props.user.role == 'wali' ? {mapel: val} : {rombel: v}
            this.modalNilai = {
                show: true,
                mapel: val, 
                jenis: jenis,
                ppn: ppn,
                aspek: aspek,
            }

        }
    },
    computed: {
        colorCheck(val){
            return val == '100' ? 'success' : 'error'
        },
        dark() {
            return this.$vuetify.theme.dark 
        }
    },
    created() {

        this.init()
    }
}
</script>

<style type="text/css" scoped>
    .aspek {
        /*background: pink;*/
        padding: 10px;
        border-radius: 5px;
        margin: 5px 0;
    }
    .card:before{
        position: absolute;
        width: 100%;
        height: 100%;
        content: '';
        /*background: #000;*/
        opacity: 0.5;

    }
</style>