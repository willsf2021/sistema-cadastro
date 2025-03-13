<template>
    <div class="d-flex justify-content-center align-items-center vh-100  position-relative">

        <div class="position-absolute top-0 start-0 w-100 h-100"
            :style="{ background: 'url(' + imageUrl + ') no-repeat center/cover' }">
            <div class="background-overlay"></div>
        </div>

        <div class="form">
            <h2 class="text-center fw-bold text-white">Login</h2>
            <p class="text-center text-white ">Entre na sua conta</p>

            <form @submit.prevent="login">
                <div class="mb-3">
                    <label class="form-label text-white">Nome de Usuário</label>
                    <input type="text" class="form-control" v-model="username" required />
                </div>

                <div class="mb-3">
                    <label class="form-label text-white">Senha</label>
                    <input type="password" class="form-control" v-model="password" required />
                </div>

                <button @click="login" type="submit" class="btn w-100 fw-bold">Entrar</button>
            </form>
        </div>
    </div>
</template>

<script>
import { ref } from 'vue';
import { useRouter } from 'vue-router';
import imageUrl from '@/assets/images/backgroundLoginView.jpg';  // Importando a imagem corretamente

export default {
    setup() {
        const router = useRouter();
        const username = ref('');
        const password = ref('');

        const login = () => {
            router.push('/app/dashboard');
        };

        return { imageUrl, username, password, login };
    }
};
</script>

<style scoped>
.background-overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(37, 61, 144, 0.85);
    z-index: 1;
}

.text-white {
    color: white;
}

.form {
    width: 400px;
    z-index: 1;
    font-family: 'Raleway', sans-serif;
}

input {
    height: 48px;
}

button {
    height: 48px;
    margin-top: 16px;
    background-color: #FFC20E;
}

button:hover {
    background-color: #ffc30ea4;
}
</style>
