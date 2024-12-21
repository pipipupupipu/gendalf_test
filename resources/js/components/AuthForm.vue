<template>
    <main>
        <form @submit.prevent="submitForm">
            <input type="text" placeholder="логин" v-model="name">
            <input type="password" placeholder="пароль" v-model="password">
            <button type="submit">Войти</button>
        </form>
    </main>
</template>

<script>
import axios from 'axios'

export default {
    data() {
        return {
            name: '',
            password: ''
        }
    },
    methods: {
        submitForm() {
            const postData = {
                name: this.name,
                password: this.password
            }
            axios.post('/api/login', postData).then(response => {
                window.location.href = response.data.redirect
            }).catch(error => {
                alert(error)
            })
        }
    }

}
</script>

<style scoped>
main {
    width: 100%;
    height: 100%;
    display: flex;
    justify-content: center;
    align-items: center;
}

form {
    display: flex;
    flex-direction: column;
    gap: 5px;
    justify-items: center;
    align-items: center;
}

button,
input {
    width: 200px;
    height: 20px;
}
</style>
