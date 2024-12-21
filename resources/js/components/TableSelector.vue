<script setup>

defineProps({
    modelValue: String
})


const emit = defineEmits(['update:modelValue'])


const tableOptions = [
    { label: 'Товары', value: 'items' },
    { label: 'Категории', value: 'categories' },
    { label: 'Пользователи', value: 'users' },
    { label: 'Права', value: 'partner_rights' }
]


const updateValue = (event) => {
    emit('update:modelValue', event.target.value)
}
</script>

<template>
    <select :value="modelValue" @change="updateValue">
        <option v-for="(table, index) in tableOptions" :key="index" :value="table.value">
            {{ table.label }}
        </option>
    </select>
</template>

<style scoped>

</style>
