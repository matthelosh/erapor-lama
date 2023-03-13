<template lang="html">
    <div>
        <v-dialog v-model="dialog.show" fullscreen transition="dialog-top-transition">
            <v-card color="secondary">
                <v-toolbar dense>
                    <v-icon>mdi-teach</v-icon>
                    Pembelajaran Baru / Edit
                    <v-spacer></v-spacer>
                    <v-btn icon @click.stop="$emit('hide')" color="error"><v-icon>mdi-close</v-icon></v-btn>
                </v-toolbar>
                <v-card-text>
                    <v-row>
                        <v-col cols="8">
                            <v-stepper
                                v-model="step"
                                vertical
                                class="mt-5"
                                non-linear
                                >
                                <v-stepper-step
                                    :complete="step > 1"
                                    step="1"
                                    editable
                                    >
                                    Pilih Tematik
                                    <small>Pilih Tema jika tematik</small>
                                </v-stepper-step>

                                <v-stepper-content step="1">
                                    <v-card
                                        class="mb-12"
                                        
                                    >
                                        <v-card-text>
                                            
                                            <v-radio-group
                                              v-model="pembelajaran.tematik"
                                              row
                                              @change="onTematik"
                                            >
                                              <v-radio
                                                  v-for="i in [{value: 1, label:'Tematik'}, {value: 0, label:'Non Tematik'}]"
                                                  :label="i.label"
                                                  :value="i.value"
                                                  :key="i.value"
                                              ></v-radio>
                                            </v-radio-group>
                                            <v-select outlined rounded dense hide-details v-model="pembelajaran.ke" label="Pembelajaran Ke" :items="nomors"></v-select>
                                            <v-autocomplete
                                            v-if="pembelajaran.tematik == '1'"
                                                :items="temas"
                                                v-model="pembelajaran.tema"
                                                dense
                                                label="Tema"
                                                @change="onSelectTema"
                                                rounded
                                                outlined
                                            >
                                            </v-autocomplete>
                                            <v-autocomplete
                                                v-if="pembelajaran.tema && pembelajaran.tematik"
                                                :items="subtemas"
                                                v-model="pembelajaran.subtema"
                                                dense
                                                label="Subtema"
                                                rounded
                                                outlined
                                                @change="onSelectSubtema"
                                            >
                                            </v-autocomplete>
                                            <v-autocomplete
                                                v-if="pembelajaran.ke && pembelajaran.tematik === 0"
                                                :items="rombels"
                                                v-model="pembelajaran.rombel_id"
                                                dense
                                                label="Rombel"
                                                rounded
                                                outlined
                                                @change="onSelectedRombel"
                                            >
                                            </v-autocomplete>
                                            
                                        </v-card-text>


                                    </v-card>
                                    <v-btn text color="warning">
                                        Batal
                                    </v-btn>
                                    <v-btn @click="step = 2" color="primary" :disabled="($page.props.user.role == 'wali' && !pembelajaran.subtema || $page.props.user.role != 'wali' && !pembelajaran.rombel_id)" rounded>
                                        Lanjut
                                    </v-btn>
                                    
                                </v-stepper-content>
                                <v-stepper-step
                                    :complete="step > 2"
                                    step="2"
                                    editable
                                    >
                                    Pilih Muatan Pelajaran
                                    <small>Pilih Mapel</small>
                                </v-stepper-step>

                                <v-stepper-content step="2">
                                    <v-card
                                        class="mb-5"
                                    >
                                        <v-card-text>
                                            <v-item-group multiple v-model="pembelajaran.mupels">
                                                <v-container>
                                                    <v-row class="d-flex justify-center">
                                                        <v-col
                                                            v-for="(mapel,index) in mapels"
                                                            :key="mapel.kode_mapel"
                                                            cols="12"
                                                            md="2"

                                                        >
                                                            <v-item v-slot="{ active, toggle }" :value="mapel.kode_mapel">
                                                                <v-card
                                                                :color="active ? 'primary' : ''"
                                                                class="d-flex align-center py-5"
                                                                max-height="200"
                                                                @click="toggle"

                                                              >
                                                              <div class="flex-grow-1 ">
                                                                <div
                                                                    class=" text-center text-h5 "
                                                                  >
                                                                <v-scroll-x-transition>
                                                                    <v-icon v-if="active" color="success">mdi-check-circle</v-icon>
                                                                </v-scroll-x-transition>
                                                                
                                                                    {{ mapel.label }}

                                                                </div>
                                                                <div v-if="active" class="text-center">
                                                                    <p v-for="kd in mapel.kds">{{ kd.kode_kd }}</p>
                                                                </div>
                                                            </div>
                                                              </v-card>
                                                            </v-item>
                                                        </v-col>
                                                    </v-row>
                                                </v-container>
                                            </v-item-group>
                                        </v-card-text>
                                    </v-card>   
                                    
                                    <v-btn text color="warning">
                                        Batal
                                    </v-btn>
                                    <v-btn @click="step = 3" color="primary" :disabled="pembelajaran.mupels.length < 1">
                                        Lanjut
                                    </v-btn>
                                </v-stepper-content>
                                <v-stepper-step
                                    :complete="step > 3"
                                    step="3"
                                    editable
                                >Tujuan Pembelajaran
                                <small>Tambahkan tujuan pembelajaran</small></v-stepper-step>
                                <v-stepper-content step="3">
                                    <v-row>
                                        <v-col cols="12">
                                            <tiptap-vuetify v-model="pembelajaran.tujuan" label="Tujuan Pembelajaran" :extensions="extensions" style="box-shadow: 0 0 5px  rgba(0,0,0,0.5);margin-bottom: 10px" :toolbar-attributes=" { color: (dark ? 'dark': 'light')}" color="light">
                                            </tiptap-vuetify>
                                        </v-col>
                                    </v-row>
                                    
                                    <v-btn text color="warning">
                                        Batal
                                    </v-btn>
                                    <v-btn @click="step = 4" color="primary" :disabled="pembelajaran.tujuan == ''">
                                        Lanjut
                                    </v-btn>
                                </v-stepper-content>
                                <v-stepper-step
                                    :complete="step > 4"
                                    step="4"
                                    :editable="pembelajaran.kegiatan != ''"
                                >
                                    Kegiatan Pembelajaran
                                    <small>Isi Kegiatan/Langkan Pembelajaran</small>
                                </v-stepper-step>
                                <v-stepper-content step="4">
                                            <v-row>
                                                <v-col cols="12" sm="4">
                                                    <h4>Apersepsi</h4>
                                                    <tiptap-vuetify v-model="pembelajaran.kegiatan.pembuka.teks" label="Pembukaan" :extensions="extensions"></tiptap-vuetify>
                                                    <p>Durasi: {{ pembelajaran.kegiatan.pembuka.durasi }} Menit</p>
                                                    <v-slider
                                                        v-model="pembelajaran.kegiatan.pembuka.durasi"
                                                        thumb-label
                                                        min="10"
                                                        max="15"
                                                        prepend-icon="mdi-clock"
                                                    ></v-slider>
                                                </v-col>
                                                <v-col cols="12" sm="4">
                                                    <h4>Kegiatan Inti</h4>
                                                    <tiptap-vuetify v-model="pembelajaran.kegiatan.inti.teks" label="Pembukaan" :extensions="extensions"></tiptap-vuetify>
                                                    <p>Durasi: {{ pembelajaran.kegiatan.inti.durasi }} Menit </p>
                                                    <v-slider
                                                        v-model="pembelajaran.kegiatan.inti.durasi"
                                                        thumb-label
                                                        min="60"
                                                        max="150"
                                                        prepend-icon="mdi-clock"
                                                    ></v-slider>
                                                </v-col>
                                                <v-col cols="12" sm="4">
                                                    <h4>Penutup</h4>
                                                    <tiptap-vuetify v-model="pembelajaran.kegiatan.penutup.teks" label="Pembukaan" :extensions="extensions"></tiptap-vuetify>
                                                    <p>Durasi: {{ pembelajaran.kegiatan.penutup.durasi }} Menit</p>
                                                    <v-slider
                                                        v-model="pembelajaran.kegiatan.penutup.durasi"
                                                        thumb-label
                                                        min="10"
                                                        max="20"
                                                        prepend-icon="mdi-clock"
                                                    ></v-slider>
                                                </v-col>
                                                
                                            </v-row>
                                    <v-btn text color="warning">
                                        Batal
                                    </v-btn>
                                    <v-btn @click="step = 5" color="primary" :disabled="pembelajaran.kegiatan.pembuka.teks == '' || pembelajaran.kegiatan.inti.teks == '' || pembelajaran.kegiatan.penutup.teks == ''">
                                        Lanjut
                                    </v-btn>
                                </v-stepper-content>
                                <v-stepper-step
                                     :complete="step == 5"
                                    step="5"
                                    :editable="pembelajaran.penilaian !=''"
                                >Penilaian</v-stepper-step>
                                <v-stepper-content step="5">
                                    <v-row class="mb-5">
                                        <v-col cols="12">
                                            <tiptap-vuetify v-model="pembelajaran.penilaian" :extensions="extensions"></tiptap-vuetify>
                                        </v-col>
                                    </v-row>
                                    <v-btn text color="warning">
                                        Batal
                                    </v-btn>
                                    <v-btn @click="simpan" color="primary" :disabled="pembelajaran.penilaian == '' || pembelajaran.tanggal == ''">
                                        Simpan
                                    </v-btn>
                                </v-stepper-content>
                            </v-stepper>
                        </v-col>
                        <v-col cols="4">
                            <v-card class="mt-5">
                                <v-card-text class="text-center">
                                    <v-card-title class="text-h5 d-flex justify-center">Tanggal Pelaksanaan</v-card-title>
                                    <v-date-picker v-model="pembelajaran.tanggal" elevation="5" locale="id" color="info" year-icon="mdi-calendar"></v-date-picker>
                                </v-card-text>
                            </v-card>
                            
                        </v-col>
                    </v-row>
                </v-card-text>
            </v-card>
        </v-dialog>
    </div>
</template>

<script>
    import { TiptapVuetify, Heading, Bold, Italic, Strike, Underline, Code, Paragraph, BulletList, OrderedList, ListItem, Link, Blockquote, HardBreak, HorizontalRule, History } from 'tiptap-vuetify'
export default {
    name: 'ModalPembelajaran',
    props: { dialog: Object },
    components: { TiptapVuetify },
    data: () => ({
        pembelajaran: {
            ke: '',
            tematik: false,
            tema: null,
            subtema: null,
            mupels: [
            ],
            tujuan: '',
            kegiatan: {
                pembuka: { teks: '', durasi: 10},
                inti: { teks: '', durasi: 120},
                penutup: { teks: '', durasi: 15},
            },
            penilaian: '',
            tanggal: '',
        },
        periodes: [],
        rombels: [],
        fileRpp: null,
        temas: [],
        subtemas: [],
        rawtemas:[],
        mapels: [],
        step: 1,
        nomors: ["1","2","3","4","5","6"],
        extensions: [
              History,
              Blockquote,
              Link,
              Underline,
              Italic,
              ListItem,
              BulletList,
              OrderedList,
              [Heading, {
                options: {
                  levels: [1, 2, 3]
                }
              }],
              Bold,
              
              
              Paragraph,
              HardBreak
            ],
    }),
    methods: {
        simpan(){
            var mupels = []
            this.pembelajaran.mupels.forEach(mu => {
                mupels.push(this.mapels.filter(ma => ma.kode_mapel === mu)[0])
            })
            var data = {
                    ke: this.pembelajaran.ke,
                    periode: this.$page.props.periode,
                    rombel_id: this.pembelajaran.rombel_id ? this.pembelajaran.rombel_id : this.$page.props.rombel.kode_rombel,
                    guru: this.$page.props.user.nip,
                    tematik: this.pembelajaran.tematik,
                    tema: this.pembelajaran.tema,
                    subtema: this.pembelajaran.subtema,
                    mupels: JSON.stringify(mupels),
                    tujuan: this.pembelajaran.tujuan,
                    kegiatan: JSON.stringify(this.pembelajaran.kegiatan),
                    penilaian: this.pembelajaran.penilaian,
                    tanggal: this.pembelajaran.tanggal,
                }
                // console.log(data)
            axios({
                method: 'post',
                url:'/dashboard/pembelajaran/store',
                data: data
            }).then( res => {
                this.$emit('hide')
            }).catch( err => {
                console.log( err.response )
            })

        },
        onSelectedRombel(e){
            // console.log(e)
            axios({
                method: 'post',
                url: '/dashboard/promes?q=rpp',
                data: {
                    rombel: this.pembelajaran.rombel,
                    mapel: this.$page.props.mapel.kode_mapel,
                    tematik: this.pembelajaran.tematik
                }
            }).then( res => {
                var datas = _.groupBy(res.data.promes, 'tema_id')
                var results = []
                Object.entries(datas).forEach(v=> {
                    var kds = []
                    v[1].forEach(item => {
                        kds.push({kode_kd: item.kd.kode_kd, teks: item.kd.teks})
                    })
                    results.push({kode_mapel: v[0], label: v[0], kds: kds})
                })
                this.mapels = results
            })
        },
        onSelectSubtema(e) {
            axios({
                method: 'post',
                url: '/dashboard/pemetaan',
                data: {
                    kelas: this.$page.props.rombel.kelas_id,
                    tema: this.pembelajaran.tema,
                    subtema: this.pembelajaran.subtema
                }
            }).then( res => {
                var mapels = []
                res.data.pemetaans.forEach((item, index) => {
                    item.no = index+1
                    mapels.push({kode_mapel: item.mapel.kode_mapel, label: item.mapel.label, kds: item.kds})
                })
                this.mapels = mapels
                // console.log(res.data.pemetaans)
            }).catch( err => {

            })
        },
        onSelectTema(a){
            // console.log(a)
            // alert(a)
            let subtemas =[]
            this.rawtemas.forEach(item=>{
                if(item.tema_id == a){
                    // subtemas.push({value: item.tema.subtemas.kode_subtema, text: item.tema.subtemas.kode_subtema.substr(2,1)+'. '+item.tema.subtemas.teks})
                    item.tema.subtemas.forEach(subtema => {
                        subtemas.push({ value: subtema.kode_subtema, text: subtema.kode_subtema.substr(2,1) + '. '+subtema.teks})
                    })
                }
            })
            // console.log(subtemas)
            this.subtemas = subtemas
        },
        onTematik(e){
            // alert(e)
           if (e == '1') {
               axios({
                   method: 'post',
                   url: '/dashboard/tema?kelas='+this.$page.props.rombel.kelas_id
               }).then(response => {
                   let temas = []
                   this.rawtemas = response.data.datas
                   response.data.datas.forEach(item => {
                       temas.push({value: item.tema_id, text: item.tema_id.substr(1,1)+'. '+item.tema.teks})
                   })
                   this.temas = temas
               }).catch( err => {
                console.log(err)
               })
           } else {
                this.pembelajaran.tema = '0'
                this.pembelajaran.subtema = '0'
               this.temas = []
               this.subtemas = []
           }
        },
        init(){

         
            // Get Periodes
            this.pembelajaran.periode_id = this.$page.props.periode
            axios({
                url: '/dashboard/periode?mode=select',
                method: 'post'
            }).then(response => {
                response.data.periode.forEach((item,index) => {
                    item.index = index+1
                    var sem = (item.semester == '1') ? 'Ganjil' : 'Genap'
                    this.periodes.push({value: item.kode_periode, text: item.tapel+ ' '+sem})
                })
                // console.log(response.data)
            }).catch(err => {
                console.log(err)
            })

            if ( this.$page.props.user.role != 'wali') {

                // Get Rombels
                axios.post('/dashboard/rombel?mode=select')
                     .then(response => {
                        response.data.rombels.forEach((item, index) => {
                            item.index = index+1
                            this.rombels.push({value: item.kode_rombel, text: item.label})
                        })
                     })
                     .catch(err => {
                         console.log(err.response)
                     })
            } else {
                // Get Mapel
                axios({
                    method: 'post',
                    url: '/dashboard/pemetaan',
                    data: {
                        kelas: this.$page.props.rombel.kelas_id,
                        tema: this.pembelajaran.tema,
                        subtema: this.pembelajaran.subtema
                    }
                }).then( res => {
                    var mapels = []
                    res.data.pemetaans.forEach((item, index) => {
                        item.no = index+1
                        mapels.push({kode_mapel: item.mapel.kode_mapel, label: item.mapel.label, kds: item.kds})
                    })
                    this.mapels = mapels
                    // console.log(res.data.pemetaans)
                }).catch( err => {
                    console.log(err)
                })
            // Get Tema
                axios({
                   method: 'post',
                   url: '/dashboard/tema?kelas='+this.$page.props.rombel.kelas_id
               }).then(response => {
                   let temas = []
                   this.rawtemas = response.data.datas
                   response.data.datas.forEach(item => {
                       temas.push({value: item.tema_id, text: item.tema_id.substr(1,1)+'. '+item.tema.teks})
                   })
                   this.temas = temas
                   if ( this.pembelajaran.subtema != '') {
                        let subtemas =[]
                        this.rawtemas.forEach(item=>{
                            if(item.tema_id == this.pembelajaran.tema){
                                // subtemas.push({value: item.tema.subtemas.kode_subtema, text: item.tema.subtemas.kode_subtema.substr(2,1)+'. '+item.tema.subtemas.teks})
                                item.tema.subtemas.forEach(subtema => {
                                    subtemas.push({ value: subtema.kode_subtema, text: subtema.kode_subtema.substr(2,1) + '. '+subtema.teks})
                                })
                            }
                        })
                        // console.log(subtemas)
                        this.subtemas = subtemas
                   }
               }).catch( err => {
                console.log(err)
               })
                this.pembelajaran.rombel_id = this.$page.props.rombel.kode_rombel
            }
        },
        edit() {
                var data = this.dialog.pembelajaran
                var mupels = JSON.parse(data.mupels)
                data.mupels = JSON.parse(data.mupels)
                data.kegiatan = JSON.parse(data.kegiatan)
                // var mupelkey = []
                // mupels.forEach(m => {
                //     mupelkey.push(m.kode_mapel)
                // })
                // var res = {
                //     ke: data.ke,
                //     tematik: data.tematik,
                //     tema: data.tema_id,
                //     subtema: data.subtema_id,
                //     mupels: mupelkey,
                //     tujuan: data.tujuan,
                //     kegiatan: JSON.parse(data.kegiatan),
                //     penilaian: data.penilaian,
                //     tanggal: data.tanggal,
                // }

                this.pembelajaran = data
        }
    },
    computed: {
        // show: {
        //     get() {
        //         return this.dialog.show
        //     },
        //     set(val) {
        //         this.$emit('hide', val)
        //     }
        // }
        dark() {
            return this.$vuetify.theme.dark
        }
    },
    mounted() {
        if(this.dialog.pembelajaran) {
            this.edit()
        }
        this.init()
        // this.pembelajaran = this.dialog.pembelajaran
        // this.edit()
    }
}
</script>

<style scoped>
    .align-top {
        vertical-align: 0%;
    }
</style>