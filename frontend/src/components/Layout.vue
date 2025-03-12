<template>
    <div class="layout">
        <aside class="menu">
            <div class="profile-data">
                <img src="" alt="">
            </div>
            <router-link to="/app/dashboard"><i class="bi bi-border-all"></i>Dashboard</router-link>
            <router-link to="/app/pessoas"><i class="bi bi-people"></i>Pessoas</router-link>
            <router-link to="/app/cargos"><i class="bi bi-briefcase-fill"></i>Cargos</router-link>
            <button>
                <i class="bi bi-box-arrow-left"></i>
                Sair
            </button>
        </aside>
        <main class="content">
            <router-view />
        </main>
    </div>
</template>

<style scoped>
.layout {
    display: flex;
    height: 100vh;
}

.menu {
    width: 360px;
    background: #121C3E;
    color: white;
    padding: 0px 24px;
    display: flex;
    flex-direction: column;
}

button {
    background-color: #FF0000;
    color: white;
    padding: 16px 28px;
    font-size: 18px;
    border-radius: 14px;
    font-weight: 600;
    display: flex;
    align-items: center;
    justify-content: center;
    column-gap: 12px;
}

button i {
    font-size: 24px;
}

.menu a {
    color: white;
    text-decoration: none;
    display: flex;
    align-items: center;
    padding: 16px 28px;
    border-radius: 14px;
    font-size: 18px;
    display: flex;
    column-gap: 13px;
    font-weight: 700;
}

.menu a i {
    font-size: 28px;
    font-weight: 700;
}

/* Estilo para o link ativo */
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
}
</style>