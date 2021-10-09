<template>
    <Layout :title="page_title">
        <v-container>
            Tentang 
            <v-card>
                <v-card-text>
                    <v-alert color="info">
                        <h3>Halo, Saya adalah..</h3>
                    </v-alert>
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
    created() {
        // console.log(this.$page.props.menus)
    }
}
</script>