<template>
    <v-dialog v-model="dialog" max-width="500" @keydown.esc="cancel" @keydown.enter="agree">
        <v-card color="secondary" dark>
            <v-toolbar dense flat color="warning" dark>
                <v-toolbar-title>{{ title }}</v-toolbar-title>
            </v-toolbar>
            <v-card-text v-show="!!message" class="pa-4 d-flex justify-center">
                <h3 class="text-center" v-html="message"></h3>
            </v-card-text>
            <v-card-actions class="pt-3">
                <v-spacer></v-spacer>
                <v-btn
                    v-if="!options.noconfirm"
                    color="success"
                    text
                    class="body-2 font-weight-bold"
                    @click.native="cancel"
                    >Cancel</v-btn
                >
                <v-btn
                    rounded
                    color="error"
                    class="body-2 font-weight-bold"
                    
                    @click.native="agree"
                    >OK</v-btn
                >
            </v-card-actions>
        </v-card>
    </v-dialog>
</template>

<script>
    export default {
        name: "ConfirmDialog",
        data: () =>({
            dialog: false,
            resolve: null,
            reject: null,
            message: null,
            title: null,
            options: {
                color: 'grey lighten-3',
                width: 400,
                zIndex: 200,
                noconfirm: false
            }
        }),
        methods: {
            open(title, message, options) {
                this.dialog = true
                this.title = title
                this.message = message,
                this.options = Object.assign(this.options, options)

                return new Promise((resolve, reject) => {
                    this.resolve = resolve
                    this.reject = reject
                })
            },
            agree() {
                this.resolve(true)
                this.dialog = false
            },
            cancel() {
                this.resolve(false)
                this.dialog = false
            }
        }
    }
</script>