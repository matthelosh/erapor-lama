<template lang="html">
    <Layout :title="page_title">
        <v-container>
            <v-row>
                <v-col cols="12">
                    <v-card class="mt-5">
                        <v-toolbar dense>
                            <v-toolbar-title>
                                <v-icon>
                                    mdi-dots-vertical
                                </v-icon>
                                <span class="hidden-sm-and-down">Data</span> Pengguna
                            </v-toolbar-title>
                            <v-spacer></v-spacer>
                                <v-btn  color="primary"  ripple rounded @click.stop="print">
                                    <v-icon>mdi-printer</v-icon>
                                    <span class="hidden-sm-and-down">
                                        Cetak
                                    </span>
                                </v-btn>
                                &nbsp;
                                <v-btn  rounded ripple color="success" @click.stop="unduhData">
                                    <v-icon>mdi-file-excel-outline</v-icon>
                                    <span class="hidden-sm-and-down">
                                        Unduh
                                    </span>
                                </v-btn>
                        </v-toolbar>
                        <v-card-text>
                            <v-data-table
                                dense
                                :headers="headers"
                                :items="itemsWithIndex"
                                :search="cari"
                                show-select
                                v-model="selectedusers"
                                :loading="loading"
                                loading-text="Tunggu Sebentar"
                            >
                            <template v-slot:top>
                                <v-row>
                                    <v-col cols="12" sm="8">
                                        <v-badge
                                                    :content="selectedusers.length"
                                                    :value="selectedusers.length"
                                                    color="success"
                                                    overlap
                                                  >
                                                    <v-btn @click.stop="resetMany" dense color="warning" :disabled="(selectedusers.length < 1)" rounded>
                                                        <v-icon>mdi-lock-reset</v-icon> 
                                                        Reset Password
                                                    </v-btn>
                                                </v-badge>
                                   <!--  </v-col>
                                    <v-col cols="12" sm="4"> -->
                                        <v-badge
                                            :content="selectedusers.length"
                                            :value="selectedusers.length"
                                            color="success"
                                            overlap
                                          >
                                            <v-btn @click.stop="deleteMany" dense color="error" :disabled="(selectedusers.length < 1)" rounded>
                                                <v-icon>mdi-close-circle</v-icon> 
                                                Cabut Akses
                                            </v-btn>
                                        </v-badge>
                                    </v-col>
                                    <v-col cols="12" sm="4">
                                        <v-text-field v-model="cari" label="Cari ... " prepend-inner-icon="mdi-magnify" outlined rounded dense hide-details></v-text-field>
                                    </v-col>
                                </v-row>
                            </template>
                            <template v-slot:item.nama="{item}">
                                <span>
                                    {{ item.userable.name ? item.userable.name : item.userable.nama }}
                                </span>
                            </template>
                            <template v-slot:item.options="{item}">
                                <span>
                                    <v-btn color="success" small rounded @click="editItem(item)">
                                        <v-icon>mdi-pencil</v-icon>
                                    </v-btn>
                                    <v-btn color="error" small rounded @click="deleteItem(item)">
                                        <v-icon>mdi-eraser</v-icon>
                                    </v-btn>
                                    <v-btn color="warning" small rounded @click="reset(item)">
                                        <v-icon>mdi-lock-reset</v-icon>
                                    </v-btn>
                                </span>
                                
                            </template>
                            </v-data-table>
                        </v-card-text>
                    </v-card>
                </v-col>
            </v-row>
            <v-row>
                <v-col cols="12">
                    <v-card class="mt-5">
                        <v-toolbar dense >
                            <v-toolbar-title>
                            <v-icon>mdi-dots-vertical</v-icon>
                            Grafik Pengguna</v-toolbar-title>
                        </v-toolbar>
                        <v-card-text>
                            <v-row>
                                <v-col cols="12" sm="4">
                                    <v-card>
                                        <v-card-title>
                                            <v-icon>mdi-gender-male-female</v-icon>
                                            Gender Pengguna
                                        </v-card-title>
                                        <v-card-text>
                                            <div id="chart">
                                                
                                            </div>
                                        </v-card-text>
                                    </v-card>
                                </v-col>
                                <v-col cols="12" sm="4">
                                    <v-card>
                                        <v-card-title>
                                            <v-icon>mdi-account-hard-hat</v-icon>
                                            Role Pengguna
                                        </v-card-title>
                                        <v-card-text>
                                            <div id="chart-role">
                                            </div>
                                        </v-card-text>
                                    </v-card>
                                </v-col>
                                <v-col cols="12" sm="4">
                                    <v-card>
                                        <v-card-title>
                                            <v-icon>mdi-account-tie</v-icon>
                                            Status Pengguna
                                        </v-card-title>
                                        <v-card-text>
                                            <div id="chart-status">
                                            </div>
                                        </v-card-text>
                                    </v-card>
                                </v-col>
                            </v-row>
                        </v-card-text>
                    </v-card>
                </v-col>
                
            </v-row>
        </v-container>
        <v-dialog 
            v-model="dialog" 
            transition="dialog-bottom-transition" 
            max-width="700">
            <v-card light>
                <v-toolbar
                    color="secondary"
                    light
                    dense
                >
                {{ dialogTitle }}
                <v-spacer></v-spacer>
                <v-btn fab small @click="dialog = false" color="error"><v-icon>mdi-close</v-icon></v-btn>
                </v-toolbar>
                <v-card-text>
                    <v-form @submit="simpan">
                        <v-container fluid>
                        <v-row>
                            <v-col cols="12" sm="6">
                                <v-text-field v-model="newuser.userid" :label="(newuser.level == 'siswa') ? 'NISN' :'NIP'" outlined dense hide-details append-icon="mdi-barcode" readonly></v-text-field>
                            </v-col>
                            <v-col cols="12" sm="4">
                                <v-text-field v-model="newuser.username" :label="(newuser.level == 'siswa') ? 'NISN' : 'Username'" outlined dense hide-details append-icon="mdi-qrcode"></v-text-field>
                            </v-col>
                            <v-col cols="12" sm="8">
                                <v-text-field v-model="newuser.email" label="Email" type="email" outlined dense hide-details append-icon="mdi-email"></v-text-field>
                            </v-col>
                            <v-col cols="12" sm="4">
                                <v-text-field v-model="newuser.password" label="Password / Sandi" outlined dense hide-details append-icon="mdi-fingerprint"></v-text-field>
                            </v-col>
                            <v-col cols="12" sm="4">
                                <v-select v-model="newuser.level" :items="levels" item-value="value" item-text="text" outlined dense hide-details append-icon="mdi-account-tie" label="Level"></v-select>
                            </v-col>
                            <v-col cols="12" sm="4">
                                <v-select v-model="newuser.role" :items="roles" item-value="value" item-text="text" outlined dense hide-details append-icon="mdi-account-hard-hat" label="Role"></v-select>
                            </v-col>
                            <v-col cols="12" class="d-flex justify-center">
                                <v-btn class="flat" type="submit" light color="primary" :loading="loading">Simpan</v-btn>
                            </v-col>
                            
                        </v-row>
                    </v-container>
                    </v-form>
                </v-card-text>
            </v-card>
        </v-dialog>

        <v-snackbar v-model="snackbar.show" bottom right multi-line :color="snackbar.color">
            {{ snackbar.text }}
        </v-snackbar>
        <confirm-dialog ref="confirm"></confirm-dialog>
    </Layout>
</template>

<script>
import Layout from '../../Layout/Dashboard'
// import ModalUser from '../Components/Modals/ModalUser.vue'
import ApexCharts from 'apexcharts'
import XLSX from 'xlsx'
import saveAs from 'file-saver'
import Download from '../../Plugins/Download'
import ConfirmDialog from '../../Components/Modals/ConfirmDialog'
export default {
    components: {
        Layout,
        ConfirmDialog
        // ModalUser
    },
    props: {
        user: Object,
        page_title: String
    },
    data: () => ({
        selectedusers: [],
        cari: '',
        status: [
            {value: 'pns', text: 'PNS'},
            {value: 'gtt', text: 'GTT'},
            {value: 'ptt', text: 'PTT'},
        ],
        hapus: {
            open: false,
            user: {}
        },
        search:'',
        newusers: [],
        newheaders:[
            {
                text: 'nip',
                value: 'nip'
            },
            {
                text: 'Nama',
                value: 'nama_siswa'
            }
        ],
        checknewusers: false,
        snackbar: {
            show: false,
            text: 'Halo'
        },
        errorDialog: false,
        dialogImport: false,
        levels: [
            {value:'admin',
            text: 'Admin'},
            {value: 'guru',text: 'Guru'},
            {value: 'siswa',text: 'Siswa'},
            ],
        roles: [
            {value:'admin',text: 'Admin'},
            {value: 'ks',text: 'Kepala Sekolah'},
            {value: 'wali',text: 'Wali Kelas'},
            {value:'gpai',text:'Guru PAI'}, 
            {value:'gor', text: 'Guru Olah Raga'}, 
            {value:'gben', text: 'Guru Bahasa Inggris'},
            {value:'siswa', text: 'Siswa'}
            ],
        dialog: false,
        dialogTitle: 'Tambah Pengguna Baru',
        headers: [
            {
                text: 'No',
                value: 'index',
                align: 'start',
                sortable: false,
            },
            {
                text: 'NIP',
                value: 'userid'
            },
            {
                text: 'Nama',
                value: 'nama'
            },
            {
                text: 'Username',
                value: 'username'
            },
            {
                text: 'Jk',
                value: 'userable.jk'
            },
            {
                text: 'Level',
                value: 'level'
            },
            
            {
                text: 'Opsi',
                value: 'options'
            }
        ],
        items: [],
        newuser: {

        },
        jks: [
            {id: 'l', text: 'Laki-laki'},
            {id: 'p', text: 'Perempuan'},
        ],
        loading: false
    }),
    methods: {
        async reset(item) {
            this.$refs.confirm.open("Reset Password", "Anda akan mereset password "+ (item.level == 'siswa' ? 'Ananda ' : (item.userable.jk == 'l' ? 'Bapak' : 'Ibu')) +" "+(item.userable.name ? item.userable.name : item.userable.nama)+"!<br /> Password default: "+ (item.level == 'guru' ? '12345' : item.userable.tanggal_lahir))
                .then((lanjut) => {
                    if ( lanjut ) {
                        var ttl = (item.userable.tanggal_lahir ? '?ttl='+item.userable.tanggal_lahir:'')
                        axios({
                            method: 'put',
                            url: '/admin/user/'+item.id+ttl
                        }).then( res => {
                             this.snackbar = { show: true, color: 'success', text: res.data.msg }
                             this.getUsers()
                        }).catch( err => {
                            this.snackbar = { show: true, color: 'error', text: err.response.data.msg }
                        })
                    } else {
                        return false
                    }
                }).catch( err => {
                    this.snackbar = { show: true, color: 'error', text: err.response.data.msg }
                })
        },
        async resetMany() {
            // console.log(this.selectedusers)
                 this.$refs.confirm.open("Hapus Data", "Yakin Mengatur ulang password beberapa pengguna tersebut?")
                 .then((lanjut) => {
                    if ( lanjut ) {
                        // var ttl = (item.userable.tanggal_lahir ? '?ttl='+item.userable.tanggal_lahir:'')
                        axios({
                            method: 'put',
                            url: '/admin/user/many',
                            data: { users: this.selectedusers}
                        }).then( res => {
                             this.snackbar = { show: true, color: 'success', text: res.data.msg }
                             this.getUsers()
                        }).catch( err => {
                            this.snackbar = { show: true, color: 'error', text: err.response.data.msg }
                        })
                    } else {
                        return false
                    }
                }).catch( err => {
                    this.snackbar = { show: true, color: 'error', text: err.response.data.msg }
                })
            },
        async deleteMany() {
            var users = this.selectedusers
                this.$refs.confirm.open("Hapus Data", "Yakin Menghapus data - data pengguna tersebut ?")
                .then((lanjut) => {
                    if(lanjut) {
                        axios({
                            method: 'delete',
                            url: '/admin/user/many',
                            data: {users : users}
                        }).then( res => {
                            this.snackbar = { show: true, color: 'success', text: res.data.msg }
                            // console.log(response)
                            this.getUsers()
                        }).catch( err => {
                            console.log(err.response)
                        })
                    } else {
                        return false
                    }
                }).catch( err => {
                    this.snackbar = { show: true, color: 'error', text: err.response.data.msg }
                })
                
        },
        newUser(){
            this.newuser = Object.assign({},{})
            this.dialog = true
            this.dialogTitle = 'Tambah Pengguna Baru'
        },
        importUsers() {
            let users = this.newusers
            axios({
                method: 'post',
                url: '/admin/user/import',
                data: {users: users}
            }).then(response => {
                // console.log(response)
                this.dialogImport = false
                this.newusers = []
                this.getUsers()
                this.snackbar = {
                    show: true,
                    color: 'success',
                    text: response.data.msg
                }

            }).catch(err => {
                console.log(err.response)
            })
        },
        importDialog() {
            this.dialogImport = true
        },
        onFilePicked(e) {
            // console.log(e)
            if (e == null) {
                this.newusers = []
                this.newheaders = []
                return false
            }
            var files = e.files;
            var reader = new FileReader
            reader.onload = ev => {
                var data = ev.target.result
                var workbook = XLSX.read(data,{type: 'binary'})

                var wsname = workbook.SheetNames[0]
                const ws = XLSX.utils.sheet_to_json(workbook.Sheets[wsname])
                const lists = []

                for ( var i = 0; i < ws.length; i++ ) {
                    lists.push(ws[i])
                }
                this.newusers = lists
                // console.log(lists)
                // GetHeaders
                const a = workbook.Sheets[workbook.SheetNames[0]]
                const headers = this.getHeaders(a)
                
                this.newheaders = headers
                // console.log(valid)
            }
            reader.readAsBinaryString(e)
        },
        getHeaders(sheet) {
            const headers = []
            const check = []
            const range = XLSX.utils.decode_range(sheet["!ref"])
            let C;

            const R = range.s.r
            let i = 0
            for (C = range.s.c; C <= range.e.c; ++C) {
                var cell = sheet[XLSX.utils.encode_cell({c:C, r:R})]

                var hdr = "UNKNOWN"+C
                if ( cell && cell.t) hdr = XLSX.utils.format_cell(cell)
                if( hdr.indexOf('UNKNOWN') > -1 ) {
                    if(!i) {
                        hdr = '__EMPTY'
                    } else {
                        hdr = '__EMPTY'+i
                    }
                    i++
                }
                check.push(hdr)
                headers.push({text:hdr, value:hdr})
            }
            var valid = this.checkHeaders(check)
            if (valid == true) {
                return headers
            } else {
                this.errorDialog = true
            }
        },
        genTemplate() {
            var wb = XLSX.utils.book_new()
            wb.Props = {
                Title: "Template Import User",
                Author: "noname"
            }

            wb.SheetNames.push("Sheet 1")

            var ws_data =[['nip', 'nama', 'username', 'email', 'password', 'jk', 'alamat', 'hp', 'level', 'role']]

            var ws = XLSX.utils.aoa_to_sheet(ws_data)
            wb.Sheets["Sheet 1"] = ws

            var wbout = XLSX.write(wb, {bookType: 'xlsx', type: 'binary'})

            var buf = new ArrayBuffer(wbout.length)
            var view = new Uint8Array(buf)
            for (var i=0;i< wbout.length;i++) view[i] = wbout.charCodeAt(i) & 0xFF;

            saveAs(new Blob([buf], {type: "application/octet-stream"}), "Format User.xlsx")
        },

        checkHeaders(headers) {
            var v = 0
            const valids = ['nip', 'nama', 'username', 'email', 'password', 'jk', 'alamat', 'hp', 'level', 'role']
            if ( headers.length < valids.length) {
                return false
            } else {
                
                // for (const v of new Set([...valids, ...headers]))
                //     if (valids.filter(e => e === v).length !== headers.filter(e => e === v).length)
                //     return false;
                // return true;
                for ( var i=0;i < valids.length; i++) {
                    if(headers.includes(valids[i])) v++
                }
            }
            if (v < valids.length) {
                return false
            } else {
                return true
            }
        },
        unduhData() { 
            if(this.items.length < 1) {
                this.snackbar = { show: true, color: 'error', text: 'Tidak ada data pengguna.'}
                return false
            }
            let datas = []
            this.items.forEach((item, index) => {
                item.index = index+1
                datas.push({index: index, userid: item.userid, nama: (item.userable.name ? item.userable.name : item.userable.nama), level: item.level, role: item.role, username: item.username})
            })
            Download.UnduhExcel(datas, 'Data Pengguna')
        },
        print() {
            if(this.items.length < 1) {
                this.snackbar = { show: true, color: 'error', text: 'Tidak ada data pengguna.'}
                return false
            }
            let datas = []
            this.items.forEach((item, index) => {
                item.index = index+1
                datas.push({index: index, userid: item.userid, nama: (item.userable.name ? item.userable.name : item.userable.nama), level: item.level, role: item.role, username: item.username, email: item.email, hp: item.userable.hp})
            })
            console.log(datas)
            var win = window.open('', '_blank', 'width=900,height=800,left=100')
            var tr = ''
                datas.forEach(user => {
                    tr += `<tr>
                            <td>${user.index+1}</td><td>${user.userid}</td><td>${user.nama}</td><td>${user.level}</td><td>${user.role}</td><td>${user.username}</td><td>${user.email}</td><td>${user.hp}</td>
                        </tr>`
                })

            var html = `
                    <!doctype html>
                    <html>
                        <head>
                            <title>Cetak Data Pengguna</title>
                            <style>
                                * {
                                    font-family: 'sans-serif';
                                }
                                h1 {
                                    text-align: center;
                                    text-transform: uppercase;
                                }
                                table {
                                    border-collapse: collapse;
                                    font-family: 'sans-serif';
                                }
                                table tbody tr:nth-child(odd) {
                                    background: #efefef;
                                }
                                th {
                                    text-align: center;
                                }
                                td {
                                    padding: 5px 20px;
                                }
                            </style>
                        </head>
                        <body>
                            <div class="wrapper">
                                <h1>Data Pengguna</h1>
                                <table border="1">
                                    <thead>
                                        <tr>
                                            <th>No</th><th>NIP/NISN</th><th>Nama</th><th>Level</th><th>Role</th><th>Username</th><th>Email</th><th>HP</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        ${tr}
                                    </tbody>
                                </table>
                            </div>
                        </body>
                    </html>
            
            `
            win.document.write(html)

            setTimeout(function(){
                win.print()
                win.close()
            },500)

            
        },
        checkNewUsers(e) {
            this.checknewusers = e
        },
        editItem(item) {
            this.newuser = item,
            this.dialogTitle = 'Perbarui Data '+item.username
            this.dialog = true
        },
        async deleteItem(item) {
            // this.hapus = {open: true, user: item}
            this.$refs.confirm.open("Hapus Data", "Yakin Menghapus pengguna "+item.username+" tersebut ?")
                .then((lanjut) => {
                    if(lanjut) {
                        axios({
                            method: 'delete',
                            url: '/admin/user/'+item.id,
                            // data: {users : users}
                        }).then( res => {
                            this.snackbar = { show: true, color: 'success', text: res.data.msg }
                            // console.log(response)
                            this.getUsers()
                        }).catch( err => {
                            console.log(err.response)
                        })
                    } else {
                        return false
                    }
                }).catch( err => {
                    this.snackbar = { show: true, color: 'error', text: err.response.data.msg }
                })
        },
        getUsers() {
            this.loading = true
            axios({
                method: 'post',
                url: '/admin/user'
            }).then(response => {
                this.items = response.data.users
                this.grafik(response.data.users)
                this.loading = false
            })
        }, 
        simpan(e) {
            e.preventDefault()
            this.loading = true
            let user = this.newuser
            // console.log(user)
            axios({
                method: 'post',
                url: '/admin/user/store',
                data: user
            }).then(response => {
                this.snackbar = {
                    show: true,
                    text: response.data.msg,
                    color: 'success'
                }
                this.loading = false
                this.getUsers()
                this.dialog = false
            }).catch(err => {
                this.snackbar = {
                    show: true,
                    text: err.response.data,
                    color: 'error'
                }
                 this.loading = false
                // conso+le.log(err.response)
            })
        },
        grafik(users){
            // var users = this.items
            
            let byjks = [0,0];
            let byroles = [0,0,0,0,0,0];
            let bystatus = [0,0]
            let labelstatus = ['PNS', 'GTT'];
            let labelsjk = ['Laki-laki', 'Perempuan'];
            let labelsrole = ['Kepala Sekolah','Wali Kelas', 'Guru PAI','Guru Olahraga','Guru Bhs. Inggris', 'Siswa']

            // console.log(users)
            users.forEach(user => {
                // By JK
                if (user.userable.jk == 'l') {
                    byjks[0] += 1
                } else {
                    byjks[1] += 1
                }
                // By Role
                if( user.role == 'ks') {
                    byroles[0] += 1
                } else if ( user.role == 'wali') {
                    byroles[1] += 1
                } else if ( user.role == 'gpai') {
                    byroles[2] += 1
                } else if ( user.role == 'gor') {
                    byroles[3] += 1
                } else if ( user.role == 'gben') {
                    byroles[4] += 1
                } else if ( user.role == 'siswa') {
                    byroles[5] += 1
                }

                // By Status
                if (user.userable.status == 'pns') {
                    bystatus[0] += 1
                } else {
                    bystatus[1] += 1
                }

                    

            })
            // console.log(byroles)
            var chartjk = new ApexCharts(document.querySelector("#chart"), {chart: {
                    type: 'pie'
                },
                series: byjks,
                labels: labelsjk})

            var chartrole = new ApexCharts(document.querySelector('#chart-role'), { 
                chart: { type: 'pie'}, series: byroles, labels: labelsrole
                })

            var chartstatus = new ApexCharts(document.querySelector("#chart-status"), {
                chart: { type: 'pie'}, series: bystatus, labels: labelstatus
            })
            chartjk.render();
            chartrole.render()
            chartstatus.render()
        }
    },
    computed: {
        itemsWithIndex() {
            var nitems = []
            this.items.map((items, index) => {
                items.index = index+1,
                nitems.push(items)
            })

            return nitems
        }
    },
    created() {
        this.getUsers()
        
    }, mounted() {
        // this.grafik()
    }
}
</script>