<script setup>
import { ref, watch, defineProps, defineEmits } from 'vue'
import axios from 'axios'

const props = defineProps({
    tableName: String,
    modelValue: String
})

const emit = defineEmits(['update:modelValue'])

const records = ref([])

watch(() => props.tableName, (newValue) => {
    if (newValue) {
        axios.get(`/api/admin/${newValue}`)
            .then(response => {
                records.value = response.data[newValue]
            })
            .catch(error => {
                console.error('Ошибка при запросе данных:', error)
            })
    }
}, { immediate: true })

const updateValue = (event) => {
    emit('update:modelValue', event.target.value)
}
</script>

<template>
    <select :value="modelValue" @change="updateValue">
        <option v-for="(record, index) in records" :key="index" :value="record.id">
            {{ record.id }}
        </option>
    </select>
</template>

<style scoped>
select {
    width: 1000px;
}
</style>
