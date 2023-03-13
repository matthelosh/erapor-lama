<template lang="html">
    <Layout :title="page_title">
       <v-container>
           <v-row>
               <v-col cols="12">
                   <v-card class="mt-5">
                       <v-img src="/img/dalam.jpg">
                       <v-toolbar dense>
                           <v-icon>mdi-dots-vertical</v-icon>
                           <v-toolbar-title>
                               Profil Saya
                           </v-toolbar-title>
                       </v-toolbar>
                       <v-card-text>
                           <!-- <v-img src="/img/dalam.jpg"> -->
                           <v-container>
                               <v-row>
                                   <!-- <v-img src="/img/depan.jpg"> -->
                                   <v-col cols="12" sm="4" class="d-flex justify-center">
                                       <v-avatar max-size="200" size="200" style="border: 10px solid #636363;" elevation-10>
                                           <img :src="'/storage/img/users/'+me.nip+'.jpg'" />
                                       </v-avatar>
                                   </v-col>
                                   <v-col cols="12" sm="8" class="d-flex align-center justify-start">
                                       <div class="text-center">
                                           <h1>{{me.name}}</h1>
                                            <a :href="'mailto:'+me.email" class="teal--text">{{me.email}}</a> - {{me.level}}
                                       </div>
                                   </v-col>
                                   <!-- </v-img> -->
                                </v-row>
                                <v-row>
                                   <v-col cols="12">
                                       <h2>Akun</h2>
                                       <table>
                                           <tr>
                                               <td>Username</td>
                                               <td>: {{me.username}}</td>
                                           </tr>
                                           <tr>
                                               <td>Email</td>
                                               <td>: {{me.email}}</td>
                                           </tr>
                                           <tr>
                                               <td>Alamat</td>
                                               <td>: {{me.alamat}}</td>
                                           </tr>
                                           <tr>
                                               <td>No. HP</td>
                                               <td>: {{me.hp}}</td>
                                           </tr>
                                       </table>
                                   </v-col>
                               </v-row>
                               
                           </v-container>
                           <!-- </v-img> -->
                       </v-card-text>
                       </v-img>
                   </v-card>
               </v-col>
           </v-row>
       </v-container>
    </Layout>
    
</template>

<script>
    import Layout from '../../Layout/Dashboard'
    export default {
        name: 'Profile',
        props: {page: String, page_title: String},
        components: {Layout},
        data: () => ({
            
        }),
        computed: {
            me() {
                return this.$page.props.user
            }
        }
    }
</script>

<style scoped>
    .v-card__text {
        height: 100%;
        background: rgba(254, 251, 251, 0.95);
    }
</style>