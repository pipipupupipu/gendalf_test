<script setup>
import { ref } from 'vue'
import TableSelector from '@/components/TableSelector.vue'
import RecordsSelector from '@/components/RecordsSelector.vue'
import RecordForm from '@/components/RecordForm.vue'

const selectedTable = ref('')
const recordID = ref('')
</script>

<template>
    <main>
        <table-selector v-model="selectedTable" />
        <records-selector :tableName="selectedTable" v-model="recordID" :key="componentKey" />
        <record-form :tableName="selectedTable" :recordID="recordID" @submit="forceRerender" />
        <button @click="logout">Выход</button>
    </main>
</template>
<script>
import axios from 'axios'

export default {
    data() {
        return {
            componentKey: 0
        }
    },
    methods: {
        forceRerender() {
            this.componentKey += 1
        },
        logout() {
            axios.post('/api/logout').then(response => {
                window.location.href = response.data.redirect
            }).catch(error => {
                alert(error)
            })
        }
    }
}
</script>
<style scoped>

</style>
