<template>
    <div class="layout">
        <!-- Menu Superior -->
        <aside class="menu">
            <div class="profile-data">
                <img :src="profilePic" alt="Foto do perfil">
            </div>
            <div class="menu-links">
                <router-link to="/app/dashboard"><i class="bi bi-border-all"></i>Dashboard</router-link>
                <router-link to="/app/pessoas"><i class="bi bi-people"></i>Pessoas</router-link>
                <router-link to="/app/cargos"><i class="bi bi-briefcase-fill"></i>Cargos</router-link>
            </div>
            <button @click="logout">
                <i class="bi bi-box-arrow-left"></i>
                Sair
            </button>
        </aside>

        <!-- Conteúdo Principal -->
        <main class="content">
            <router-view />
        </main>
    </div>
</template>
<script setup>
import profilePic from "@/assets/images/profilePic.png"
import { useRouter } from 'vue-router';

const router = useRouter();

const logout = () => {
            router.push('/');
        };
</script>
<style scoped>
.layout {
    display: flex;
    flex-direction: column;
    height: 100vh;
    position: relative;
}

.menu {
    width: 100%;
    background: #121C3E;
    color: white;
    padding: 16px 24px;
    display: flex;
    flex-direction: row;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 16px;
}

.menu-links {
    display: flex;
    flex-direction: row;
    gap: 16px;
    flex-wrap: wrap;
}

button {
    background-color: #FF0000;
    color: white;
    padding: 12px 20px;
    font-size: 16px;
    border-radius: 14px;
    font-weight: 600;
    display: flex;
    align-items: center;
    justify-content: center;
    column-gap: 8px;
    border: none;
    cursor: pointer;
}

button i {
    font-size: 20px;
}

.menu a {
    color: white;
    text-decoration: none;
    display: flex;
    align-items: center;
    padding: 12px 20px;
    border-radius: 14px;
    font-size: 16px;
    column-gap: 8px;
    font-weight: 700;
}

.menu a i {
    font-size: 24px;
    font-weight: 700;
}


.menu .router-link-active {
    background-color: #FFC20E;
    color: black;
    font-weight: 500;
}

.menu .router-link-active i {
    color: black;
    font-weight: 700;
}

.content {
    flex: 1;
    background-color: #E3EDF9;

    overflow-y: auto;
}

/* Responsividade */
@media (max-width: 768px) {
    .menu {
        flex-direction: column;
        align-items: flex-start;
        padding: 12px;
    }

    .menu-links {
        flex-direction: column;
        width: 100%;
    }

    .menu a {
        width: 100%;
        justify-content: flex-start;
    }

    button {
        width: 100%;
        margin-top: 10px;
    }
}

@media (max-width: 480px) {
    .menu a {
        font-size: 14px;
        padding: 10px 16px;
    }

    .menu a i {
        font-size: 20px;
    }

    button {
        font-size: 14px;
        padding: 10px 16px;
    }

    button i {
        font-size: 18px;
    }
}
</style>