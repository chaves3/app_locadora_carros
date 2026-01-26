<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <card-component titulo="Busca de Carros">
                    <template v-slot:conteudo>
                        <div class="row g-3">
                            <div class="col-md-4 mb-3">
                                <input-container-component 
                                    titulo="ID" 
                                    id="inputId"
                                    id-help="inputIdHelp"
                                    texto-ajuda="Opcional. Informe o ID do Carro"
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

                            <div class="col-md-4 mb-3">
                                <input-container-component 
                                    titulo="Placa" 
                                    id="inputPlaca"
                                    id-help="placaHelp"
                                    texto-ajuda="Opcional. Informe a Placa do Carro"
                                >
                                    <input v-model="busca.placa" type="text" class="form-control" id="inputPlaca" aria-describedby="placaHelp" placeholder="Ex: ABC-1234">
                                </input-container-component>
                            </div>

                            <div class="col-md-4 mb-3">
                                <input-container-component 
                                    titulo="Disponível" 
                                    id="inputDisponivel"
                                    id-help="disponivelHelp"
                                    texto-ajuda="Filtrar por disponibilidade"
                                >
                                    <select class="form-control" id="inputDisponivel" v-model="busca.disponivel">
                                        <option value="">Todos</option>
                                        <option value="1">Disponível</option>
                                        <option value="0">Indisponível</option>
                                    </select>
                                </input-container-component>
                            </div>
                        </div>
                    </template>

                    <template v-slot:rodape>
                        <button @click="pesquisar" type="submit" class="btn btn-primary btn-sm float-end">Pesquisar</button>
                    </template>
                </card-component>

                <!--inicio do card de listagem de carros-->
                <card-component titulo="Listagem de Carros">
                    <template v-slot:conteudo>
                        <div class="card-body">
                            <table-component 
                                :titulos="{
                                    id: {titulo: 'ID', tipo: 'texto'},
                                    placa: {titulo: 'Placa', tipo: 'texto'},
                                    modelo_nome: {titulo: 'Modelo', tipo: 'texto'},
                                    marca_nome: {titulo: 'Marca', tipo: 'texto'},
                                    disponivel: {titulo: 'Disponível', tipo: 'texto'},
                                    km: {titulo: 'KM', tipo: 'texto'},
                                    created_at: {titulo: 'Cadastrado em', tipo: 'data'},
                                }"
                                :visualizar="{
                                    visivel:true,
                                    dataBsToggle: 'modal',
                                    dataBsTarget: '#modalCarroVisualizar',
                                }"
                                :atualizar="{
                                    visivel: true,
                                    dataBsToggle: 'modal',
                                    dataBsTarget: '#modalCarroAtualizar',
                                }"
                                :excluir="{
                                    visivel:true,
                                    dataBsToggle: 'modal',
                                    dataBsTarget: '#modalCarroRemover',
                                }"
                                :dados="carros.data"
                            >
                            </table-component>
                        </div>
                    </template>
                    <template v-slot:rodape>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-10">
                                    <paginate-component>
                                        <li v-for="l, key in carros.links" :key="key" :class="l.active ? 'page-item active' : 'page-item'" @click="paginacao(l)">
                                            <a class="page-link" v-html="l.label"></a>
                                        </li>
                                    </paginate-component>
                                </div>
                                <div class="col">
                                    <button type="button" class="btn btn-primary btn-sm float-end" data-bs-toggle="modal" data-bs-target="#modalCarro">Adicionar</button>
                                </div>
                            </div>
                        </div>
                    </template>
                </card-component>
                <!--fim da listagem de carros -->
            </div>
        </div>

        <!--inicio do modal de inclusão de carro-->
        <modal-component id="modalCarro" titulo="Adicionar Carro">
            <template v-slot:alertas>
                <alert-component tipo="success" :detalhes="transacaoDetalhes" titulo="Cadastro Realizado com sucesso" v-if="transacaoStatus == 'Adicionado'"></alert-component>
                <alert-component tipo="danger" :detalhes="transacaoDetalhes" titulo="Erro ao tentar cadastrar o carro" v-if="transacaoStatus == 'Cancelado'"></alert-component>
            </template>
            <template v-slot:conteudo>
                <div class="row">
                    <!-- Coluna 1 -->
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <input-container-component 
                                titulo="Modelo" 
                                id="modeloId"
                                id-help="modeloIdHelp"
                                texto-ajuda="Selecione o modelo do veículo"
                            >
                                <select class="form-control" id="modeloId" v-model="novoCarro.modelo_id">
                                    <option value="">Selecione um modelo</option>
                                    <option v-for="modelo in modelos" :key="modelo.id" :value="modelo.id">
                                        {{ modelo.marca?.nome || '' }} - {{ modelo.nome }}
                                    </option>
                                </select>
                            </input-container-component>
                        </div>

                        <div class="form-group mb-3">
                            <input-container-component 
                                titulo="Placa" 
                                id="novaPlaca"
                                id-help="novaPlacaHelp"
                                texto-ajuda="Informe a placa do veículo"
                            >
                                <input type="text" class="form-control" id="novaPlaca" aria-describedby="novaPlacaHelp" 
                                    placeholder="Ex: ABC-1234" v-model="novoCarro.placa"
                                    @input="formatarPlaca">
                            </input-container-component>
                        </div>

                        <div class="form-group mb-3">
                            <input-container-component 
                                titulo="Quilometragem (KM)" 
                                id="km"
                                id-help="kmHelp"
                                texto-ajuda="Informe a quilometragem atual"
                            >
                                <input type="number" class="form-control" id="km" aria-describedby="kmHelp" 
                                    placeholder="Ex: 50000" v-model="novoCarro.km" min="0">
                            </input-container-component>
                        </div>
                    </div>

                    <!-- Coluna 2 -->
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <input-container-component 
                                titulo="Disponibilidade" 
                                id="disponivel"
                                id-help="disponivelHelp"
                                texto-ajuda="O veículo está disponível para locação?"
                            >
                                <select class="form-control" id="disponivel" v-model="novoCarro.disponivel">
                                    <option value="">Selecione</option>
                                    <option value="1">Disponível</option>
                                    <option value="0">Indisponível</option>
                                </select>
                            </input-container-component>
                        </div>

                        <!-- Informações do modelo selecionado -->
                        <div class="card mb-3" v-if="modeloSelecionado">
                            <div class="card-header bg-light">
                                <h6 class="mb-0">Informações do Modelo</h6>
                            </div>
                            <div class="card-body p-3">
                                <div class="row small">
                                    <div class="col-6">
                                        <strong>Marca:</strong><br>
                                        {{ modeloSelecionado.marca?.nome || 'N/A' }}
                                    </div>
                                    <div class="col-6">
                                        <strong>Modelo:</strong><br>
                                        {{ modeloSelecionado.nome || 'N/A' }}
                                    </div>
                                    <div class="col-6 mt-2">
                                        <strong>Portas:</strong><br>
                                        {{ modeloSelecionado.numero_portas || 'N/A' }}
                                    </div>
                                    <div class="col-6 mt-2">
                                        <strong>Lugares:</strong><br>
                                        {{ modeloSelecionado.lugares || 'N/A' }}
                                    </div>
                                    <div class="col-6 mt-2">
                                        <strong>Air Bag:</strong><br>
                                        {{ modeloSelecionado.air_bag == 1 ? 'Sim' : 'Não' }}
                                    </div>
                                    <div class="col-6 mt-2">
                                        <strong>ABS:</strong><br>
                                        {{ modeloSelecionado.abs == 1 ? 'Sim' : 'Não' }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </template>
            <template v-slot:rodape>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                <button type="button" class="btn btn-primary" @click="salvar()">Salvar</button>
            </template>
        </modal-component>
        <!--final do modal de inclusão de carro-->

        <!--inicio do modal de visualização de carro-->
        <modal-component id="modalCarroVisualizar" titulo="Visualizar Carro">
            <template v-slot:alertas></template>
            <template v-slot:conteudo>
                <div class="row">
                    <div class="col-md-6">
                        <input-container-component titulo="ID">
                            <input type="text" class="form-control" :value="$store.state.item.id" disabled>
                        </input-container-component>

                        <input-container-component titulo="Placa">
                            <input type="text" class="form-control" :value="$store.state.item.placa" disabled>
                        </input-container-component>

                        <input-container-component titulo="Modelo">
                            <input type="text" class="form-control" :value="$store.state.item.modelo_nome || 'N/A'" disabled>
                        </input-container-component>

                        <input-container-component titulo="Marca">
                            <input type="text" class="form-control" :value="$store.state.item.marca_nome || 'N/A'" disabled>
                        </input-container-component>
                    </div>

                    <div class="col-md-6">
                        <input-container-component titulo="Disponível">
                            <input type="text" class="form-control" 
                                :value="$store.state.item.disponivel == 1 ? 'Sim' : 'Não'" 
                                disabled>
                        </input-container-component>

                        <input-container-component titulo="Quilometragem (KM)">
                            <input type="text" class="form-control" 
                                :value="formatarKM($store.state.item.km)" 
                                disabled>
                        </input-container-component>

                        <input-container-component titulo="Data de Cadastro">
                            <input type="text" class="form-control" :value="$store.state.item.created_at" disabled>
                        </input-container-component>

                        <!-- Imagem do modelo se existir -->
                        <input-container-component titulo="Imagem do Modelo" v-if="$store.state.item.imagem">
                            <img :src="'storage/' + $store.state.item.imagem" alt="Imagem do Modelo" class="img-fluid img-thumbnail">
                        </input-container-component>
                    </div>
                </div>
            </template>
            <template v-slot:rodape>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
            </template>
        </modal-component>
        <!--final do modal de visualização de carro-->

        <!--inicio do modal de remoção de carro-->
        <modal-component id="modalCarroRemover" titulo="Remover Carro">
            <template v-slot:alertas>
                <alert-component tipo="success" titulo="Transação realizada com sucesso" :detalhes="$store.state.transacao" v-if="$store.state.transacao.status == 'sucesso'"></alert-component>
                <alert-component tipo="danger" titulo="Erro na Transação" :detalhes="$store.state.transacao" v-if="$store.state.transacao.status == 'erro'"></alert-component>
            </template>
            <template v-slot:conteudo v-if="$store.state.transacao.status != 'sucesso'">
                <input-container-component titulo="ID">
                    <input type="text" class="form-control" :value="$store.state.item.id" disabled>
                </input-container-component>
                <input-container-component titulo="Placa">
                    <input type="text" class="form-control" :value="$store.state.item.placa" disabled>
                </input-container-component>
                <input-container-component titulo="Modelo">
                    <input type="text" class="form-control" :value="$store.state.item.modelo_nome || 'N/A'" disabled>
                </input-container-component>
                <input-container-component titulo="Marca">
                    <input type="text" class="form-control" :value="$store.state.item.marca_nome || 'N/A'" disabled>
                </input-container-component>
            </template>
            <template v-slot:rodape>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                <button v-if="$store.state.transacao.status != 'sucesso'" type="button" class="btn btn-danger" @click="remover()">Remover</button>
            </template>
        </modal-component>
        <!--fim do modal de remoção de carro-->

        <!--inicio do modal de atualizar de carro-->
        <modal-component id="modalCarroAtualizar" titulo="Atualizar Carro">
            <template v-slot:alertas>
                <alert-component tipo="success" titulo="Transação realizada com sucesso" :detalhes="$store.state.transacao" v-if="$store.state.transacao.status == 'sucesso'"></alert-component>
                <alert-component tipo="danger" titulo="Erro na Transação" :detalhes="$store.state.transacao" v-if="$store.state.transacao.status == 'erro'"></alert-component>
            </template>
            <template v-slot:conteudo v-if="$store.state.transacao.status != 'sucesso'">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <input-container-component 
                                titulo="Modelo" 
                                id="atualizarModeloId"
                                texto-ajuda="Selecione o modelo do veículo"
                            >
                                <select class="form-control" id="atualizarModeloId" v-model="$store.state.item.modelo_id">
                                    <option value="">Selecione um modelo</option>
                                    <option v-for="modelo in modelos" :key="modelo.id" :value="modelo.id">
                                        {{ modelo.marca?.nome || '' }} - {{ modelo.nome }}
                                    </option>
                                </select>
                            </input-container-component>
                        </div>

                        <div class="form-group mb-3">
                            <input-container-component 
                                titulo="Placa" 
                                id="atualizarPlaca"
                                texto-ajuda="Informe a placa do veículo"
                            >
                                <input type="text" class="form-control" id="atualizarPlaca" 
                                    placeholder="Ex: ABC-1234" v-model="$store.state.item.placa"
                                    @input="formatarPlacaUpdate">
                            </input-container-component>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <input-container-component 
                                titulo="Quilometragem (KM)" 
                                id="atualizarKm"
                                texto-ajuda="Informe a quilometragem atual"
                            >
                                <input type="number" class="form-control" id="atualizarKm" 
                                    placeholder="Ex: 50000" v-model="$store.state.item.km" min="0">
                            </input-container-component>
                        </div>

                        <div class="form-group mb-3">
                            <input-container-component 
                                titulo="Disponibilidade" 
                                id="atualizarDisponivel"
                                texto-ajuda="O veículo está disponível para locação?"
                            >
                                <select class="form-control" id="atualizarDisponivel" v-model="$store.state.item.disponivel">
                                    <option value="">Selecione</option>
                                    <option value="1">Disponível</option>
                                    <option value="0">Indisponível</option>
                                </select>
                            </input-container-component>
                        </div>
                    </div>
                </div>
            </template>
            <template v-slot:rodape>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                <button v-if="$store.state.transacao.status != 'sucesso'" type="button" class="btn btn-primary" @click="atualizar()">Atualizar</button>
            </template>
        </modal-component>
        <!--fim do modal de atualizar de carro-->
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
    name: 'Carros',
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
            urlBase: 'http://localhost:8000/api/v1/carro',
            urlModelos: 'http://localhost:8000/api/v1/modelo', 
            urlPaginacao: '',
            urlFiltro: '',
            transacaoStatus: '',
            transacaoDetalhes: {},
            carros: { data: [] },
            modelos: [], 
            busca: {
                id: '',
                placa: '',
                disponivel: '',
            },
            novoCarro: {
                modelo_id: '',
                placa: '',
                disponivel: '',
                km: '',
            }
        }
    },

    computed: {
        modeloSelecionado() {
            if (!this.novoCarro.modelo_id) return null;
            return this.modelos.find(modelo => modelo.id == this.novoCarro.modelo_id);
        }
    },

    methods: {
        formatarPlaca(event) {
            let placa = event.target.value.toUpperCase().replace(/[^A-Z0-9]/g, '');
            
            if (placa.length > 3) {
                placa = placa.substring(0, 3) + '-' + placa.substring(3, 7);
            }
            
            this.novoCarro.placa = placa;
        },

        formatarPlacaUpdate(event) {
            let placa = event.target.value.toUpperCase().replace(/[^A-Z0-9]/g, '');
            
            if (placa.length > 3) {
                placa = placa.substring(0, 3) + '-' + placa.substring(3, 7);
            }
            
            this.$store.state.item.placa = placa;
        },

        formatarKM(km) {
            if (!km) return '0 KM';
            return new Intl.NumberFormat('pt-BR').format(km) + ' KM';
        },

        carregarModelos() {
            axios.get(this.urlModelos)
                .then(response => {
                    console.log('Modelos carregados:', response.data);
                    
                    let modelosData = [];
                    
                    if (Array.isArray(response.data)) {
                        modelosData = response.data;
                    } else if (response.data && response.data.data) {
                        modelosData = response.data.data;
                    }
                    
                    // Processa modelos para ter marca_nome
                    this.modelos = modelosData.map(modelo => {
                        const marcaNome = modelo.marca 
                            ? modelo.marca.nome 
                            : (modelo.marca_nome || `ID: ${modelo.marca_id}`);
                        
                        return {
                            ...modelo,
                            marca_nome: marcaNome
                        };
                    });
                    
                    console.log(`${this.modelos.length} modelos processados`);
                })
                .catch(error => {
                    console.error('Erro ao carregar modelos:', error);
                });
        },

        atualizar() {
            let formData = new FormData();
            formData.append('_method', 'patch');
            formData.append('modelo_id', this.$store.state.item.modelo_id);
            formData.append('placa', this.$store.state.item.placa);
            formData.append('disponivel', this.$store.state.item.disponivel);
            formData.append('km', this.$store.state.item.km);

            let url = this.urlBase + '/' + this.$store.state.item.id;
            let config = {
                headers: {
                    'Content-Type': 'application/json',
                }
            };

            // Usando PUT ou PATCH normalmente
            axios.put(url, {
                modelo_id: this.$store.state.item.modelo_id,
                placa: this.$store.state.item.placa,
                disponivel: this.$store.state.item.disponivel,
                km: this.$store.state.item.km
            }, config)
                .then(response => {
                    this.$store.state.transacao.status = 'sucesso';
                    this.$store.state.transacao.mensagem = 'Carro atualizado com sucesso';
                    this.carregarLista();
                })
                .catch(errors => {
                    this.$store.state.transacao.status = 'erro';
                    this.$store.state.transacao.mensagem = errors.response.data.message;
                    this.$store.state.transacao.dados = errors.response.data.errors;
                });
        },

        remover() {
            let confirmacao = confirm('Tem certeza que deseja remover este carro?');
            if (!confirmacao) {
                return false;
            }

            let url = this.urlBase + '/' + this.$store.state.item.id;
            let formData = new FormData();
            formData.append('_method', 'delete');

            axios.post(url, formData)
                .then(response => {
                    this.$store.state.transacao.status = 'sucesso';
                    this.$store.state.transacao.mensagem = response.data.msg;
                    this.carregarLista();
                })
                .catch(errors => {
                    this.$store.state.transacao.status = 'erro';
                    this.$store.state.transacao.mensagem = errors.response.data.erro || 'Erro ao remover carro';
                });
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

        paginacao(l) {
            if (l.url) {
                this.urlPaginacao = l.url.split('?')[1];
                this.carregarLista();
            }
        },

        carregarLista() {
            let url = this.urlBase + '?' + this.urlPaginacao + this.urlFiltro;
            
            axios.get(url)
                .then(response => {
                    console.log('Dados de carros recebidos:', response.data);
                    
                    let carrosData = [];
                    
                    if (Array.isArray(response.data)) {
                        carrosData = response.data;
                    } else if (response.data && response.data.data) {
                        carrosData = response.data.data;
                    }
                    
                    // ENRIQUECE OS DADOS COM NOMES DE MODELO E MARCA
                    const carrosTransformados = carrosData.map(carro => {
                        // Encontra o modelo correspondente
                        const modelo = this.modelos.find(m => m.id == carro.modelo_id);
                        
                        const modeloNome = modelo ? modelo.nome : `ID: ${carro.modelo_id}`;
                        const marcaNome = modelo ? modelo.marca_nome : 'N/A';
                        const imagem = modelo ? modelo.imagem : null;
                        
                        return {
                            ...carro,
                            modelo_nome: modeloNome,
                            marca_nome: marcaNome,
                            imagem: imagem,
                            disponivel: carro.disponivel != null ? (carro.disponivel ? 'Sim' : 'Não') : '-',
                            km: carro.km ? new Intl.NumberFormat('pt-BR').format(carro.km) + ' KM' : '0 KM'
                        };
                    });
                    
                    this.carros = { 
                        data: carrosTransformados, 
                        links: [] 
                    };
                    
                    console.log('Carros transformados:', this.carros.data);
                })
                .catch(errors => {
                    console.error('Erro ao carregar carros:', errors);
                    this.carros = { data: [], links: [] };
                });
        },

        salvar() {
            // Validação básica
            if (!this.novoCarro.modelo_id) {
                alert('Selecione um modelo!');
                return;
            }
            
            if (!this.novoCarro.placa || this.novoCarro.placa.length < 7) {
                alert('Informe uma placa válida!');
                return;
            }

            axios.post(this.urlBase, {
                modelo_id: this.novoCarro.modelo_id,
                placa: this.novoCarro.placa,
                disponivel: this.novoCarro.disponivel || 1,
                km: this.novoCarro.km || 0
            })
                .then(response => {
                    this.transacaoDetalhes = {
                        mensagem: 'ID do registro: ' + response.data.id
                    };
                    this.transacaoStatus = 'Adicionado';
                    
                    // Limpa todos os campos após salvar
                    this.resetForm();
                    
                    // Fecha o modal e atualiza a lista
                    setTimeout(() => {
                        document.getElementById('modalCarro').querySelector('.btn-secondary').click();
                        this.carregarLista();
                    }, 1500);
                })
                .catch(errors => {
                    this.transacaoStatus = 'Cancelado';
                    this.transacaoDetalhes = {
                        mensagem: errors.response.data.message,
                        dados: errors.response.data.errors
                    };
                });
        },

        resetForm() {
            this.novoCarro = {
                modelo_id: '',
                placa: '',
                disponivel: '',
                km: '',
            };
        }
    },

    mounted() {
        // Carrega modelos primeiro, depois carros
         this.carregarModelos();
        this.carregarLista()
    },
}
</script>