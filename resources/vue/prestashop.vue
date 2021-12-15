<template>
<div>
    <p>Translation key <code>SCHEDULER_EXAMPLE</code> from <code>scheduler/resources/lang/**/js.php</code>: {{$lang.SCHEDULER_EXAMPLE}}</p>
    <button class="form-builder__send btn" @click="testDebug">Test console log for debug</button>
    <p>From config JS file: {{this.example_data}}</p>
    <p>Example function: {{this.exampleFromFunction}}</p>
    <p>
        <button class="form-builder__send btn" @click="testLoading">Test loading</button>
        <span v-if="isLoading">is loading...</span>
    </p>
</div>
</template>

<script>
import schedulerMixin from '../js/mixins/scheduler'
import {consoleDebug} from '../js/modules/helpers'

let _uniqSectionId = 0;

export default {

    name: 'scheduler',

    mixins: [ schedulerMixin ],

    props: {
        name: {
            type: String,
            default() {
                return `scheduler-${ _uniqSectionId++ }`
            }
        },

        default: Object,

        storeData: String,
    },


    computed: {
        scheduler() {
            return this.$store.state.scheduler[this.name]
        },

        isLoading() {
            return this.scheduler && this.scheduler.isLoading
        },
    },

    created() {

        let data = this.storeData ? this.$store.state[this.storeData] : (this.default || {})

        this.$store.commit('scheduler/create', {
            name: this.name,
            data
        })
    },

    mounted() {

    },

    methods: {
        testDebug(){
            consoleDebug('message', ['data1'], ['data2'])
        },

        testLoading(){
            if ( this.isLoading) return;

            AWEMA.emit(`scheduler::${this.name}:before-test-loading`)

            this.$store.dispatch('scheduler/testLoading', {
                name: this.name
            }).then( data => {
                consoleDebug('data', data);
                this.$emit('success', data.data)
                this.$store.$set(this.name, this.$get(data, 'data', {}))
            })
        }
    },


    beforeDestroy() {

    }
}
</script>
