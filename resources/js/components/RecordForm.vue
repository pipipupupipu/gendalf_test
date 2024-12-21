<script setup>
import { defineProps, defineEmits, ref, watch } from 'vue'
import axios from 'axios'

const props = defineProps({
    tableName: String,
    recordID: String
})

const emit = defineEmits(['submit'])

const record = ref({})
const isSelected = ref(false)

watch(() => props.recordID, (newValue) => {
    if (newValue) {
        isSelected.value = true
        axios.get(`/api/admin/${props.tableName}/${newValue}`).then(response => {
            record.value = response.data[props.tableName][0]
        }).catch(error => {
            console.error('Ошибка при запросе данных:', error)
        })
    }
})

const submitForm = () => {
    axios.put(`/api/admin/${props.tableName}/${props.recordID}`, record.value).then(response => {
        console.log(response.data)
    })
    emit('submit')
}

const createRecord = () => {
    axios.post(`/api/admin/${props.tableName}`, record.value).then(response => {
        emit('submit')
        console.log(response.data)
    }).catch(error => {
        console.error('Ошибка при создании записи:', error)
    })
    resetSelection()
}

const deleteRecord = () => {
    if (confirm('Вы уверены, что хотите удалить эту запись?')) {
        axios.delete(`/api/admin/${props.tableName}/${props.recordID}`).then(response => {
            console.log(response.data)
            emit('submit')
        }).catch(error => {
            console.error('Ошибка при удалении записи:', error)
        })
        record.value = null
    }
}
const resetSelection = () => {
    isSelected.value = false
    delete record.value.id
    Object.keys(record.value).forEach(key => {
        record.value[key] = null
    })
}
</script>

<template>
    <main>
        <h1>{{ record }}</h1>
        <form @submit.prevent="submitForm">
            <div v-for="(value, key) in record" :key="key">
                <input
                    v-if="key !== 'id'"
                    :placeholder="key"
                    v-model="record[key]"
                />
                <input
                    v-else
                    :placeholder="key"
                    v-model="record[key]"
                    readonly
                />
            </div>
            <button v-if="isSelected" type="submit">🔃 Обновить</button>
            <button v-if="!isSelected && props.recordID" type="button" @click="createRecord">👍🏻 Добавить</button>
            <button v-if="isSelected" type="button" @click="deleteRecord">❌ Удалить</button>
            <button v-if="isSelected" type="button" @click="resetSelection">⬅️ Очистить</button>
        </form>
    </main>
</template>

<style scoped>
form {
    margin-top: 50px;
    display: flex;
    align-self: center;
    flex-direction: column;
    gap: 5px;
}

div, input, button {
    align-self: center;
    width: 400px;
    height: 25px;
}
</style>
