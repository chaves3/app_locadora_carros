<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <!-- Card de Busca -->
                <card-component titulo="Busca de Clientes">
                    <template v-slot:conteudo>
                        <div class="row g-3">
                            <div class="col-md-4 mb-3">
                                <input-container-component 
                                    titulo="ID" 
                                    id="inputId"
                                    id-help="inputIdHelp"
                                    texto-ajuda="Opcional. Informe o ID do Cliente"
                                >
                                    <input 
                                        type="number" 
                                        class="form-control" 
                                        id="inputId" 
                                        aria-describedby="inputIdHelp" 
                                        placeholder="ID"
                                        v-model="busca.id"
                                    >
                                </input-container-component>
                            </div>

                            <div class="col-md-8 mb-3">
                                <input-container-component 
                                    titulo="Nome" 
                                    id="inputNome"
                                    id-help="nomeHelp"
                                    texto-ajuda="Buscar por nome ou parte do nome"
                                >
                                    <input 
                                        type="text" 
                                        class="form-control" 
                                        id="inputNome" 
                                        placeholder="Digite o nome"
                                        v-model="busca.nome"
                                    >
                                </input-container-component>
                            </div>
                        </div>
                    </template>

                    <template v-slot:rodape>
                        <button @click="pesquisar" type="submit" class="btn btn-primary btn-sm float-end">Pesquisar</button>
                        <button @click="limparFiltros" type="button" class="btn btn-secondary btn-sm float-end me-2">Limpar Filtros</button>
                    </template>
                </card-component>

                <!-- Card de Listagem -->
                <card-component titulo="Listagem de Clientes">
                    <template v-slot:conteudo>
                        <div class="card-body">
                            <table-component 
                                :titulos="{
                                    id: {titulo: 'ID', tipo: 'texto'},
                                    nome: {titulo: 'Nome', tipo: 'texto'},
                                    created_at: {titulo: 'Cadastrado em', tipo: 'data'},
                                }"
                                :visualizar="{
                                    visivel: true,
                                    dataBsToggle: 'modal',
                                    dataBsTarget: '#modalClienteVisualizar',
                                }"
                                :atualizar="{
                                    visivel: true,
                                    dataBsToggle: 'modal',
                                    dataBsTarget: '#modalClienteAtualizar',
                                }"
                                :excluir="{
                                    visivel: true,
                                    dataBsToggle: 'modal',
                                    dataBsTarget: '#modalClienteRemover',
                                }"
                                :dados="clientes.data"
                            >
                            </table-component>
                        </div>
                    </template>
                    <template v-slot:rodape>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-10">
                                    <paginate-component>
                                        <li v-for="l, key in clientes.links" :key="key" :class="l.active ? 'page-item active' : 'page-item'" @click="paginacao(l)">
                                            <a class="page-link" v-html="l.label"></a>
                                        </li>
                                    </paginate-component>
                                </div>
                                <div class="col">
                                    <button type="button" class="btn btn-primary btn-sm float-end" data-bs-toggle="modal" data-bs-target="#modalCliente">
                                        Novo Cliente
                                    </button>
                                </div>
                            </div>
                        </div>
                    </template>
                </card-component>
            </div>
        </div>

        <!-- Modal de Inclusão -->
        <modal-component id="modalCliente" titulo="Novo Cliente">
            <template v-slot:alertas>
                <alert-component 
                    tipo="success" 
                    :detalhes="transacaoDetalhes" 
                    titulo="Cliente Cadastrado com sucesso" 
                    v-if="transacaoStatus == 'Adicionado'">
                </alert-component>
                <alert-component 
                    tipo="danger" 
                    :detalhes="transacaoDetalhes" 
                    titulo="Erro ao cadastrar cliente" 
                    v-if="transacaoStatus == 'Cancelado'">
                </alert-component>
            </template>
            <template v-slot:conteudo>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group mb-3">
                            <input-container-component 
                                titulo="Nome Completo*" 
                                id="novoNome"
                                id-help="novoNomeHelp"
                                texto-ajuda="Informe o nome completo do cliente"
                            >
                                <input 
                                    type="text" 
                                    class="form-control" 
                                    id="novoNome" 
                                    placeholder="João da Silva"
                                    v-model="novoCliente.nome"
                                    required
                                >
                            </input-container-component>
                        </div>
                    </div>
                </div>
            </template>
            <template v-slot:rodape>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                <button 
                    type="button" 
                    class="btn btn-primary" 
                    @click="salvar()" 
                    :disabled="!podeSalvar"
                >
                    Cadastrar Cliente
                </button>
            </template>
        </modal-component>

        <!-- Modal de Visualização -->
        <modal-component id="modalClienteVisualizar" titulo="Detalhes do Cliente">
            <template v-slot:alertas></template>
            <template v-slot:conteudo>
                <div class="row">
                    <div class="col-md-12">
                        <input-container-component titulo="ID">
                            <input type="text" class="form-control" :value="$store.state.item.id" disabled>
                        </input-container-component>

                        <input-container-component titulo="Nome">
                            <input type="text" class="form-control" :value="$store.state.item.nome || 'N/A'" disabled>
                        </input-container-component>

                        <input-container-component titulo="Cadastrado em" v-if="$store.state.item.created_at">
                            <input type="text" class="form-control" :value="formatarData($store.state.item.created_at)" disabled>
                        </input-container-component>
                    </div>
                </div>
            </template>
            <template v-slot:rodape>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
            </template>
        </modal-component>

        <!-- Modal de Remoção -->
        <modal-component id="modalClienteRemover" titulo="Remover Cliente">
            <template v-slot:alertas>
                <alert-component 
                    tipo="success" 
                    titulo="Transação realizada com sucesso" 
                    :detalhes="$store.state.transacao" 
                    v-if="$store.state.transacao.status == 'sucesso'">
                </alert-component>
                <alert-component 
                    tipo="danger" 
                    titulo="Erro na Transação" 
                    :detalhes="$store.state.transacao" 
                    v-if="$store.state.transacao.status == 'erro'">
                </alert-component>
            </template>
            <template v-slot:conteudo v-if="$store.state.transacao.status != 'sucesso'">
                <input-container-component titulo="ID">
                    <input type="text" class="form-control" :value="$store.state.item.id" disabled>
                </input-container-component>
                <input-container-component titulo="Nome">
                    <input type="text" class="form-control" :value="$store.state.item.nome || 'N/A'" disabled>
                </input-container-component>
                <div class="alert alert-warning mt-3">
                    <i class="fas fa-exclamation-triangle"></i> 
                    <strong>Atenção!</strong> Esta ação removerá permanentemente o cliente. Deseja continuar?
                </div>
            </template>
            <template v-slot:rodape>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                <button 
                    v-if="$store.state.transacao.status != 'sucesso'" 
                    type="button" 
                    class="btn btn-danger" 
                    @click="remover()"
                >
                    Remover Cliente
                </button>
            </template>
        </modal-component>

        <!-- Modal de Atualização -->
        <modal-component id="modalClienteAtualizar" titulo="Atualizar Cliente">
            <template v-slot:alertas>
                <alert-component 
                    tipo="success" 
                    titulo="Transação realizada com sucesso" 
                    :detalhes="$store.state.transacao" 
                    v-if="$store.state.transacao.status == 'sucesso'">
                </alert-component>
                <alert-component 
                    tipo="danger" 
                    titulo="Erro na Transação" 
                    :detalhes="$store.state.transacao" 
                    v-if="$store.state.transacao.status == 'erro'">
                </alert-component>
            </template>
            <template v-slot:conteudo v-if="$store.state.transacao.status != 'sucesso'">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group mb-3">
                            <input-container-component 
                                titulo="Nome*" 
                                id="atualizarNome"
                                texto-ajuda="Informe o novo nome"
                            >
                                <input 
                                    type="text" 
                                    class="form-control" 
                                    id="atualizarNome" 
                                    v-model="$store.state.item.nome"
                                    required
                                >
                            </input-container-component>
                        </div>
                    </div>
                </div>
            </template>
            <template v-slot:rodape>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                <button 
                    v-if="$store.state.transacao.status != 'sucesso'" 
                    type="button" 
                    class="btn btn-primary" 
                    @click="atualizar()"
                >
                    Atualizar
                </button>
            </template>
        </modal-component>
    </div>
</template>

<script>
import InputContainer from './InputContainer.vue';
import Table from './Table.vue';
import Card from './Card.vue';
import Modal from './Modal.vue';
import Alert from './Alert.vue';
import Paginate from './Paginate.vue';

export default {
    name: 'Clientes',
    components: {
        'input-container-component': InputContainer,
        'table-component': Table,
        'card-component': Card,
        'modal-component': Modal,
        'alert-component': Alert,
        'paginate-component': Paginate,
    },

    data() {
        return {
            urlBase: 'http://localhost:8000/api/v1/cliente',
            urlPaginacao: '',
            urlFiltro: '',
            transacaoStatus: '',
            transacaoDetalhes: {},
            clientes: { data: [] },
            busca: {
                id: '',
                nome: ''
            },
            novoCliente: {
                nome: ''
            }
        }
    },

    computed: {
        dataHoje() {
            return new Date().toISOString().split('T')[0];
        },

        podeSalvar() {
            return this.novoCliente.nome && this.novoCliente.nome.trim() !== '';
        }
    },

    methods: {
        formatarData(data) {
            if (!data) return 'N/A';
            return new Date(data).toLocaleDateString('pt-BR');
        },

        pesquisar() {
            let filtro = '';

            for (let chave in this.busca) {
                if (this.busca[chave] !== '') {
                    if (filtro !== '') {
                        filtro += ';'
                    }
                    filtro += chave + ':like:' + this.busca[chave];
                }
            }

            this.urlPaginacao = 'page=1';
            this.urlFiltro = filtro ? '&filtro=' + filtro : '';
            this.carregarLista();
        },

        limparFiltros() {
            this.busca = {
                id: '',
                nome: ''
            };
            this.urlPaginacao = '';
            this.urlFiltro = '';
            this.carregarLista();
        },

        paginacao(l) {
            if (l.url) {
                this.urlPaginacao = l.url.split('?')[1];
                this.carregarLista();
            }
        },

        carregarLista() {
            let url = this.urlBase + '?' + this.urlPaginacao + this.urlFiltro;
            
            console.log('📡 Buscando clientes:', url);
            
            axios.get(url)
                .then(response => {
                    console.log('✅ Clientes recebidos:', response.data);
                    
                    let clientesData = [];
                    
                    if (Array.isArray(response.data)) {
                        clientesData = response.data;
                    } else if (response.data && response.data.data) {
                        clientesData = response.data.data;
                    }
                    
                    // Processa dados dos clientes
                    const clientesTransformados = clientesData.map(cliente => {
                        return {
                            id: cliente.id || '-',
                            nome: cliente.nome || 'N/A',
                            created_at: cliente.created_at || new Date().toISOString(),
                            updated_at: cliente.updated_at || null
                        };
                    });
                    
                    this.clientes = { 
                        data: clientesTransformados, 
                        links: [] 
                    };
                    
                    console.log(`🎯 ${this.clientes.data.length} clientes carregados`);
                })
                .catch(errors => {
                    console.error('❌ Erro ao carregar clientes:', errors);
                    console.error('❌ Resposta:', errors.response);
                    this.clientes = { data: [], links: [] };
                });
        },

        salvar() {
            // Validação
            if (!this.podeSalvar) {
                alert('Preencha o campo nome!');
                return;
            }

            // Prepara dados
            const dados = {
                nome: this.novoCliente.nome.trim()
            };

            console.log('📤 Enviando cliente:', dados);

            axios.post(this.urlBase, dados)
                .then(response => {
                    console.log('✅ Cliente criado:', response.data);
                    
                    this.transacaoDetalhes = {
                        mensagem: 'Cliente ID: ' + response.data.id + ' cadastrado com sucesso!'
                    };
                    this.transacaoStatus = 'Adicionado';
                    
                    // Limpa formulário
                    this.resetForm();
                    
                    // Fecha modal e atualiza lista
                    setTimeout(() => {
                        const modal = bootstrap.Modal.getInstance(document.getElementById('modalCliente'));
                        if (modal) modal.hide();
                        this.carregarLista();
                    }, 1500);
                })
                .catch(errors => {
                    console.error('❌ Erro ao salvar cliente:', errors.response?.data);
                    
                    this.transacaoStatus = 'Cancelado';
                    this.transacaoDetalhes = {
                        mensagem: errors.response?.data?.message || 'Erro ao cadastrar cliente',
                        dados: errors.response?.data?.errors || {}
                    };
                });
        },

        atualizar() {
            const dados = {
                nome: this.$store.state.item.nome.trim()
            };

            let url = this.urlBase + '/' + this.$store.state.item.id;
            let config = {
                headers: {
                    'Content-Type': 'application/json',
                }
            };

            axios.put(url, dados, config)
                .then(response => {
                    this.$store.state.transacao.status = 'sucesso';
                    this.$store.state.transacao.mensagem = 'Cliente atualizado com sucesso';
                    this.carregarLista();
                })
                .catch(errors => {
                    this.$store.state.transacao.status = 'erro';
                    this.$store.state.transacao.mensagem = errors.response.data.message;
                    this.$store.state.transacao.dados = errors.response.data.errors;
                });
        },

        remover() {
            let confirmacao = confirm('Tem certeza que deseja remover este cliente?');
            if (!confirmacao) {
                return false;
            }

            let url = this.urlBase + '/' + this.$store.state.item.id;
            let formData = new FormData();
            formData.append('_method', 'delete');

            axios.post(url, formData)
                .then(response => {
                    this.$store.state.transacao.status = 'sucesso';
                    this.$store.state.transacao.mensagem = response.data.msg || 'Cliente removido com sucesso';
                    this.carregarLista();
                })
                .catch(errors => {
                    this.$store.state.transacao.status = 'erro';
                    this.$store.state.transacao.mensagem = errors.response.data.erro || 'Erro ao remover cliente';
                });
        },

        resetForm() {
            this.novoCliente = {
                nome: ''
            };
        }
    },

    mounted() {
        // Carrega clientes
        this.carregarLista();
    }
}
</script>