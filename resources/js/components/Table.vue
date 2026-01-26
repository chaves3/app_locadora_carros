<template>
    <div>
        <table class="table table-hover">
            <thead>
                <tr>
                    <th scope="col" v-for="t, key in titulos" :key="key">{{ t.titulo }}</th>
                    <th v-if="visualizar.visivel || atualizar.visivel || excluir.visivel">Ações</th>
                </tr>
            </thead>
            <tbody>
                <tr v-if="!dados || dados.length === 0">
                    <td :colspan="Object.keys(titulos).length + 1" class="text-center">
                        Nenhum registro encontrado
                    </td>
                </tr>
                
                <tr v-for="obj, chave in dadosFiltrados" 
                    :key="chave" 
                    :class="rowClassFunction ? rowClassFunction(obj) : ''"
                    v-else
                >
                    <td v-for="valor, chaveValor in obj" :key="chaveValor">
                        <span v-if="titulos[chaveValor].tipo == 'texto'">{{ valor || '-' }}</span>
                        <span v-if="titulos[chaveValor].tipo == 'data'">{{ valor | formatadaDataTempoGlobal }}</span>
                        <span v-if="titulos[chaveValor].tipo == 'dataSQL'">{{ valor | formatarDataSQL }}</span>
                        <span v-if="titulos[chaveValor].tipo == 'imagem'">
                            <img :src="'/storage/'+valor" width="30" height="30" alt="imagem" v-if="valor">
                            <span v-else>-</span>
                        </span>
                    </td>
                    <td v-if="visualizar.visivel || atualizar.visivel || excluir.visivel">
                        <button v-if="visualizar.visivel" class="btn btn-outline-primary btn-sm mt-1" :data-bs-toggle="visualizar.dataBsToggle" :data-bs-target="visualizar.dataBsTarget" @click="setStore(obj)">Visualizar</button>
                        <button v-if="atualizar.visivel" class="btn btn-outline-success btn-sm mt-1" :data-bs-toggle="atualizar.dataBsToggle" :data-bs-target="atualizar.dataBsTarget" @click="setStore(obj)">Atualizar</button>
                        <button v-if="excluir.visivel" class="btn btn-outline-danger btn-sm mt-1" :data-bs-toggle="excluir.dataBsToggle" :data-bs-target="excluir.dataBsTarget" @click="setStore(obj)">Excluir</button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>

<script>
export default {
    name: 'Table',
    props: {
        dados: {
            type: Array,
            default: () => []
        },
        titulos: Object,
        visualizar: Object,
        atualizar: Object,
        excluir: Object,
        rowClassFunction: {
            type: Function,
            default: null
        }
    },
    methods: {
        setStore(obj){
            this.$store.state.transacao.status = '';
            this.$store.state.transacao.mensagem = '';
            this.$store.state.transacao.dados = '';
            this.$store.state.item = obj;
        },  
    },
    computed: {
        dadosFiltrados(){
            if (!this.dados || !Array.isArray(this.dados)) {
                return [];
            }
            
            let campos = Object.keys(this.titulos);
            let dadosFiltrados = [];
            
            this.dados.forEach((item, chave) => {
                let itemFiltrado = {};
                campos.forEach(campo => {
                    itemFiltrado[campo] = item[campo] || '-';
                });
                dadosFiltrados.push(itemFiltrado);
            });
            
            return dadosFiltrados;
        },
    },
}
</script>

<style scoped>
/* Estilos para destaque das linhas */
.table-danger {
    background-color: #f8d7da !important;
    color: #721c24 !important;
    border-left: 4px solid #dc3545 !important;
}

.table-warning {
    background-color: #fff3cd !important;
    color: #856404 !important;
    border-left: 4px solid #ffc107 !important;
}

.table-success {
    background-color: #d4edda !important;
    color: #155724 !important;
    border-left: 4px solid #28a745 !important;
}

.table-info {
    background-color: #d1ecf1 !important;
    color: #0c5460 !important;
    border-left: 4px solid #17a2b8 !important;
}

/* Efeito hover */
.table-danger:hover {
    background-color: #f5c6cb !important;
}

.table-warning:hover {
    background-color: #ffeaa7 !important;
}

.table-success:hover {
    background-color: #c3e6cb !important;
}

.table-info:hover {
    background-color: #bee5eb !important;
}

/* Destaque para células */
.table-danger td {
    font-weight: 600;
}

.table-danger td:first-child {
    position: relative;
}

.table-danger td:first-child::before {
    content: "⚠️";
    position: absolute;
    left: -25px;
    top: 50%;
    transform: translateY(-50%);
}
</style>