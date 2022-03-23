<template>
    <Layout :title="page_title">
        <v-container>
            Tentang 
            <v-card>
                <v-card-text>
                    <v-alert color="info">
                        <h3>Halo, Saya.</h3>
                    </v-alert>
                    <v-container>
                        <v-row>
                            <v-col v-if="loading" class="d-flex justify-center align-center">
                                <v-progress-circular  color="purple" :value="7" size="80" indeterminate></v-progress-circular>
                            </v-col>
                            <v-col v-else>
                                {{ text }}
                            </v-col>
                        </v-row>
                    </v-container>
                </v-card-text>
            </v-card>
        </v-container>
    </Layout>
</template>

<script>
import Layout from '../../Layout/Dashboard'
export default {
    components: {
        Layout
    },
    props: {
        user: Object,
        page_title: String,
        menus: Array,
        datas: Array
    },
    data: () => ({
        text: null,
        loading: false,
    }),
    methods: {
        tes() {
            this.loading = true
            axios({
                method: 'post',
                url: '/wali/about'
            }).then(res => {
                this.text = res.data.msg
                console.log(res)
                this.loading = false
            })
        },
    },
    created() {
        // console.log(this.$page.props.menus)
        this.tes()
    }
}
</script>