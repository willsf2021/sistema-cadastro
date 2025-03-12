<template>
    <div class="dashboard">
        <div class="row">
            <div class="col-md-6">

                <div class="card-body">
                    <h5 class="card-title" id="title">Dashboard</h5>
                    <div class="row">
                        <div class="col-md-6 ">
                            <div class="card-show cargos">
                                <i class="bi bi-briefcase-fill"></i>
                                <div class="quantity">
                                    <p class="card-text">3</p>
                                    <h5 class="card-title">Cargos</h5>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card-show pessoas">
                                <i class="bi bi-people"></i>
                                <div class="quantity">
                                    <p class="card-text">10</p>
                                    <h5 class="card-title">Pessoas</h5>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <div class="row mt-4">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Pessoas</h5>
                        <ul class="list-group">
                            <li class="list-group-item" v-for="person in people" :key="person.name">
                                {{ person.name }} - Cargo: {{ person.role }}
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Cargos</h5>
                        <ul class="list-group">
                            <li class="list-group-item" v-for="role in roles" :key="role.name">
                                {{ role.name }} - {{ role.count }} pessoas
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            people: [
                { name: 'José', role: 'Auxiliar Administrativo' },
                { name: 'Maria', role: 'Executivo de Vendas' },
                { name: 'Wilson', role: 'Desenvolvedor Fronteind' },
                { name: 'Dorilhar', role: 'Dorilhar' },
                { name: 'Botelha', role: 'Botelha' }
            ],
            roles: [
                { name: 'Auxiliar Administrativo', count: 3 },
                { name: 'Executivo de Vendas', count: 5 },
                { name: 'Desenvolvedor Fronteind', count: 2 }
            ]
        };
    }
};
</script>

<style scoped>
.dashboard {
    padding: 20px;
}
.dashboard h5#title {
    font-size: 25px;
}
.card-show {
    /* min-height: 148px; */
    /* width: 324px; */
    border-radius: 14px;
    /* border: 1px solid black; */
    color: white;
    display: flex;
    align-items: center;
    justify-content: space-around;
    padding: 16px 24px;
    cursor: pointer;
}

.card-show:hover {
    transform: scale(1.007);
    transition: all ease-in 200ms;
}

.card-show i {
    font-size: 80px;
}
.card-show p {
    font-size: 64px;
    margin: 0;
    padding: 0;
    line-height: 4.8rem;
    font-weight: 600;
}
.card-show h5 {
    padding: 0;
    margin: 0;
    font-weight: 400;
}

.quantity {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
}

.card-show.pessoas {
    background-color: #3F861E;
}

.card-show.cargos {
    background-color: #253d90;
}
</style>