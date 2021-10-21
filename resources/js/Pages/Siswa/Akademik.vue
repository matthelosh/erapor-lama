<template>
    <Layout :title="page_title">
        <div class="container">
            
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