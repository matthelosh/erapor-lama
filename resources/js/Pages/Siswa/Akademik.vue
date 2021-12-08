<template>
    <Layout :title="page_title">
        <div class="container">
            <div class="row">
                <div class="col" cols="12">
                    <v-card>
                        <v-toolbar dense> <h3 class="v-card-header">DATA AKADEMIK {{$page.props.user.nama }}</h3></v-toolbar>
                        <v-card-content>
                            <p>Lorem ipsum dolor sit amet consectetur adipisicing, elit. Nam totam ex at explicabo sequi atque perspiciatis veritatis eum, vero deleniti autem. Quasi non corporis sunt. Facilis saepe eum unde rerum.</p>
                        </v-card-content>
                    </v-card>
                </div>
            </div>
        </div>
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

    }),
    methods: {

    },
    created() {
        // console.log(this.$page.props.menus)
    }
}
</script>

<style scoped>
    .container {
        background: #fff;
    }
</style>