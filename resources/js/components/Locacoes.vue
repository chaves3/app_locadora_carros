<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <card-component titulo="Busca de Locações">
                    <template v-slot:conteudo>
                        <div class="row g-3">
                            <div class="col-md-4 mb-3">
                                <input-container-component 
                                    titulo="ID" 
                                    id="inputId"
                                    id-help="inputIdHelp"
                                    texto-ajuda="Opcional. Informe o ID da Locação"
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
                                    titulo="Cliente" 
                                    id="inputCliente"
                                    id-help="clienteHelp"
                                    texto-ajuda="Filtrar por cliente"
                                >
                                    <select class="form-control" id="inputCliente" v-model="busca.cliente_id">
                                        <option value="">Todos os clientes</option>
                                        <option v-for="cliente in clientes" :key="cliente.id" :value="cliente.id">
                                            {{ cliente.nome }}
                                        </option>
                                    </select>
                                </input-container-component>
                            </div>

                            <div class="col-md-4 mb-3">
                                <input-container-component 
                                    titulo="Carro" 
                                    id="inputCarro"
                                    id-help="carroHelp"
                                    texto-ajuda="Filtrar por carro"
                                >
                                    <select class="form-control" id="inputCarro" v-model="busca.carro_id">
                                        <option value="">Todos os carros</option>
                                        <option v-for="carro in carros" :key="carro.id" :value="carro.id">
                                            {{ carro.placa }} - {{ carro.modelo_nome }}
                                        </option>
                                    </select>
                                </input-container-component>
                            </div>

                            <div class="col-md-6 mb-3">
                                <input-container-component 
                                    titulo="Data Início" 
                                    id="inputDataInicio"
                                    id-help="dataInicioHelp"
                                    texto-ajuda="Filtrar a partir desta data"
                                >
                                    <input type="date" class="form-control" id="inputDataInicio" 
                                        v-model="busca.data_inicio_periodo">
                                </input-container-component>
                            </div>

                            <div class="col-md-6 mb-3">
                                <input-container-component 
                                    titulo="Status" 
                                    id="inputStatus"
                                    id-help="statusHelp"
                                    texto-ajuda="Filtrar por status"
                                >
                                    <select class="form-control" id="inputStatus" v-model="busca.status">
                                        <option value="">Todos</option>
                                        <option value="ativa">Ativa</option>
                                        <option value="finalizada">Finalizada</option>
                                        <option value="pendente">Pendente</option>
                                        <option value="cancelada">Cancelada</option>
                                    </select>
                                </input-container-component>
                            </div>
                        </div>
                    </template>

                    <template v-slot:rodape>
                        <button @click="pesquisar" type="submit" class="btn btn-primary btn-sm float-end">Pesquisar</button>
                        <button @click="limparFiltros" type="button" class="btn btn-secondary btn-sm float-end me-2">Limpar Filtros</button>
                    </template>
                </card-component>

                <!--inicio do card de listagem de locações-->
                <card-component titulo="Listagem de Locações">
                    <template v-slot:conteudo>
                        <div class="card-body">
                            <table-component 
                                :titulos="{
                                    id: {titulo: 'ID', tipo: 'texto'},
                                    cliente_nome: {titulo: 'Cliente', tipo: 'texto'},
                                    carro_info: {titulo: 'Carro', tipo: 'texto'},
                                    data_inicio_periodo: {titulo: 'Início', tipo: 'dataSQL'}, 
                                    data_final_previsto_periodo: {titulo: 'Previsão', tipo: 'dataSQL'}, 
                                    data_final_realizado_periodo: {titulo: 'Realizado', tipo: 'dataSQL'}, 
                                    valor_diaria: {titulo: 'Diária', tipo: 'texto'},
                                    valor_total: {titulo: 'Total', tipo: 'texto'},
                                    status: {titulo: 'Status', tipo: 'texto'},
                                    created_at: {titulo: 'Criado em', tipo: 'data'}, 
                                }"
                                :visualizar="{
                                    visivel:true,
                                    dataBsToggle: 'modal',
                                    dataBsTarget: '#modalLocacaoVisualizar',
                                }"
                                :atualizar="{
                                    visivel: true,
                                    dataBsToggle: 'modal',
                                    dataBsTarget: '#modalLocacaoAtualizar',
                                }"
                                :excluir="{
                                    visivel:true,
                                    dataBsToggle: 'modal',
                                    dataBsTarget: '#modalLocacaoRemover',
                                }"
                                :dados="locacoes.data"
                                :row-class-function="getRowClass"
                            >
                            </table-component>
                        </div>
                    </template>
                    <template v-slot:rodape>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-10">
                                    <paginate-component>
                                        <li v-for="l, key in locacoes.links" :key="key" :class="l.active ? 'page-item active' : 'page-item'" @click="paginacao(l)">
                                            <a class="page-link" v-html="l.label"></a>
                                        </li>
                                    </paginate-component>
                                </div>
                                <div class="col">
                                    <button type="button" class="btn btn-primary btn-sm float-end" data-bs-toggle="modal" data-bs-target="#modalLocacao">Nova Locação</button>
                                </div>
                            </div>
                        </div>
                    </template>
                </card-component>
                <!--fim da listagem de locações -->
            </div>
        </div>

        <!--inicio do modal de inclusão de locação-->
        <modal-component id="modalLocacao" titulo="Nova Locação">
            <template v-slot:alertas>
                <alert-component tipo="success" :detalhes="transacaoDetalhes" titulo="Locação Realizada com sucesso" v-if="transacaoStatus == 'Adicionado'"></alert-component>
                <alert-component tipo="danger" :detalhes="transacaoDetalhes" titulo="Erro ao tentar realizar locação" v-if="transacaoStatus == 'Cancelado'"></alert-component>
            </template>
            <template v-slot:conteudo>
                <div class="row">
                    <!-- Coluna 1 -->
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <input-container-component 
                                titulo="Cliente" 
                                id="clienteId"
                                id-help="clienteIdHelp"
                                texto-ajuda="Selecione o cliente"
                            >
                                <select class="form-control" id="clienteId" v-model="novaLocacao.cliente_id" required>
                                    <option value="">Selecione um cliente</option>
                                    <option v-for="cliente in clientes" :key="cliente.id" :value="cliente.id">
                                        {{ cliente.nome }} - {{ cliente.cpf || cliente.email }}
                                    </option>
                                </select>
                            </input-container-component>
                        </div>

                        <div class="form-group mb-3">
                            <input-container-component 
                                titulo="Carro" 
                                id="carroId"
                                id-help="carroIdHelp"
                                texto-ajuda="Selecione o carro para locação"
                            >
                                <select class="form-control" id="carroId" v-model="novaLocacao.carro_id" required @change="selecionarCarro">
                                    <option value="">Selecione um carro</option>
                                    <option v-for="carro in carrosDisponiveis" :key="carro.id" :value="carro.id">
                                        {{ carro.placa }} - {{ carro.modelo_nome }} ({{ carro.marca_nome }})
                                    </option>
                                </select>
                            </input-container-component>
                        </div>

                        <div class="form-group mb-3">
                            <input-container-component 
                                titulo="Data de Início" 
                                id="dataInicio"
                                id-help="dataInicioHelp"
                                texto-ajuda="Data de início da locação"
                            >
                                <input type="date" class="form-control" id="dataInicio" 
                                    v-model="novaLocacao.data_inicio_periodo" required
                                    :min="dataHoje">
                            </input-container-component>
                        </div>

                        <div class="form-group mb-3">
                            <input-container-component 
                                titulo="Data Previsão de Término" 
                                id="dataPrevista"
                                id-help="dataPrevistaHelp"
                                texto-ajuda="Data prevista para devolução"
                            >
                                <input type="date" class="form-control" id="dataPrevista" 
                                    v-model="novaLocacao.data_final_previsto_periodo" required
                                    :min="novaLocacao.data_inicio_periodo">
                            </input-container-component>
                        </div>
                    </div>

                    <!-- Coluna 2 -->
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <input-container-component 
                                titulo="Valor Diária (R$)" 
                                id="valorDiaria"
                                id-help="valorDiariaHelp"
                                texto-ajuda="Valor da diária do carro"
                            >
                                <input type="number" class="form-control" id="valorDiaria" 
                                    placeholder="Ex: 150.00" v-model="novaLocacao.valor_diaria" 
                                    step="0.01" min="0" required>
                            </input-container-component>
                        </div>

                        <div class="form-group mb-3">
                            <input-container-component 
                                titulo="KM Inicial" 
                                id="kmInicial"
                                id-help="kmInicialHelp"
                                texto-ajuda="Quilometragem atual do carro"
                            >
                                <input type="number" class="form-control" id="kmInicial" 
                                    placeholder="Quilometragem atual" v-model="novaLocacao.km_inicial" 
                                    min="0" required>
                            </input-container-component>
                        </div>

                        <!-- Resumo da locação -->
                        <div class="card mb-3 bg-light">
                            <div class="card-header">
                                <h6 class="mb-0">Resumo da Locação</h6>
                            </div>
                            <div class="card-body p-3">
                                <div class="row small">
                                    <div class="col-6">
                                        <strong>Dias de Locação:</strong><br>
                                        {{ calcularDiasLocacao }} dias
                                    </div>
                                    <div class="col-6">
                                        <strong>Valor Total:</strong><br>
                                        {{ formatarMoeda(valorTotal) }}
                                    </div>
                                    <div class="col-12 mt-2" v-if="carroSelecionado">
                                        <strong>Carro Selecionado:</strong><br>
                                        {{ carroSelecionado.placa }} - {{ carroSelecionado.modelo_nome }}<br>
                                        <small class="text-muted">KM Atual: {{ carroSelecionado.km }}</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </template>
            <template v-slot:rodape>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                <button type="button" class="btn btn-primary" @click="salvar()" :disabled="!podeSalvar">Confirmar Locação</button>
            </template>
        </modal-component>
        <!--final do modal de inclusão de locação-->

        <!--inicio do modal de visualização de locação-->
        <modal-component id="modalLocacaoVisualizar" titulo="Detalhes da Locação">
            <template v-slot:alertas></template>
            <template v-slot:conteudo>
                <div class="row">
                    <div class="col-md-6">
                        <input-container-component titulo="ID">
                            <input type="text" class="form-control" :value="$store.state.item.id" disabled>
                        </input-container-component>

                        <input-container-component titulo="Cliente">
                            <input type="text" class="form-control" :value="$store.state.item.cliente_nome || 'N/A'" disabled>
                        </input-container-component>

                        <input-container-component titulo="Carro">
                            <input type="text" class="form-control" :value="$store.state.item.carro_info || 'N/A'" disabled>
                        </input-container-component>

                        <input-container-component titulo="Data Início">
                            <input type="text" class="form-control" :value="formatarData($store.state.item.data_inicio_periodo)" disabled>
                        </input-container-component>

                        <input-container-component titulo="Data Previsão">
                            <input type="text" class="form-control" :value="formatarData($store.state.item.data_final_previsto_periodo)" disabled>
                        </input-container-component>
                    </div>

                    <div class="col-md-6">
                        <input-container-component titulo="Data Realizada" v-if="$store.state.item.data_final_realizado_periodo">
                            <input type="text" class="form-control" :value="formatarData($store.state.item.data_final_realizado_periodo)" disabled>
                        </input-container-component>

                        <input-container-component titulo="Valor Diária">
                            <input type="text" class="form-control" :value="formatarMoeda($store.state.item.valor_diaria)" disabled>
                        </input-container-component>

                        <input-container-component titulo="KM Inicial">
                            <input type="text" class="form-control" :value="formatarKM($store.state.item.km_inicial)" disabled>
                        </input-container-component>

                        <input-container-component titulo="KM Final" v-if="$store.state.item.km_final">
                            <input type="text" class="form-control" :value="formatarKM($store.state.item.km_final)" disabled>
                        </input-container-component>

                        <input-container-component titulo="Status">
                            <input type="text" class="form-control" :value="formatarStatus($store.state.item.status)" disabled>
                        </input-container-component>

                        <input-container-component titulo="Valor Total Estimado">
                            <input type="text" class="form-control" :value="formatarMoeda($store.state.item.valor_total)" disabled>
                        </input-container-component>
                    </div>
                </div>
            </template>
            <template v-slot:rodape>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
            </template>
        </modal-component>
        <!--final do modal de visualização de locação-->

        <!--inicio do modal de remoção de locação-->
        <modal-component id="modalLocacaoRemover" titulo="Cancelar Locação">
            <template v-slot:alertas>
                <alert-component tipo="success" titulo="Transação realizada com sucesso" :detalhes="$store.state.transacao" v-if="$store.state.transacao.status == 'sucesso'"></alert-component>
                <alert-component tipo="danger" titulo="Erro na Transação" :detalhes="$store.state.transacao" v-if="$store.state.transacao.status == 'erro'"></alert-component>
            </template>
            <template v-slot:conteudo v-if="$store.state.transacao.status != 'sucesso'">
                <input-container-component titulo="ID">
                    <input type="text" class="form-control" :value="$store.state.item.id" disabled>
                </input-container-component>
                <input-container-component titulo="Cliente">
                    <input type="text" class="form-control" :value="$store.state.item.cliente_nome || 'N/A'" disabled>
                </input-container-component>
                <input-container-component titulo="Carro">
                    <input type="text" class="form-control" :value="$store.state.item.carro_info || 'N/A'" disabled>
                </input-container-component>
                <input-container-component titulo="Status">
                    <input type="text" class="form-control" :value="formatarStatus($store.state.item.status)" disabled>
                </input-container-component>
                <div class="alert alert-warning mt-3">
                    <i class="fas fa-exclamation-triangle"></i> 
                    <strong>Atenção!</strong> Esta ação cancelará a locação. Deseja continuar?
                </div>
            </template>
            <template v-slot:rodape>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                <button v-if="$store.state.transacao.status != 'sucesso'" type="button" class="btn btn-danger" @click="remover()">Cancelar Locação</button>
            </template>
        </modal-component>
        <!--fim do modal de remoção de locação-->

        <!--inicio do modal de atualizar de locação-->
        <modal-component id="modalLocacaoAtualizar" titulo="Atualizar Locação">
            <template v-slot:alertas>
                <alert-component tipo="success" titulo="Transação realizada com sucesso" :detalhes="$store.state.transacao" v-if="$store.state.transacao.status == 'sucesso'"></alert-component>
                <alert-component tipo="danger" titulo="Erro na Transação" :detalhes="$store.state.transacao" v-if="$store.state.transacao.status == 'erro'"></alert-component>
            </template>
            <template v-slot:conteudo v-if="$store.state.transacao.status != 'sucesso'">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <input-container-component 
                                titulo="Data Final Realizada" 
                                id="atualizarDataFinal"
                                texto-ajuda="Data real da devolução do carro"
                            >
                                <input type="date" class="form-control" id="atualizarDataFinal" 
                                    v-model="$store.state.item.data_final_realizado_periodo"
                                    :min="$store.state.item.data_inicio_periodo">
                            </input-container-component>
                        </div>

                        <div class="form-group mb-3">
                            <input-container-component 
                                titulo="KM Final" 
                                id="atualizarKmFinal"
                                texto-ajuda="Quilometragem na devolução"
                            >
                                <input type="number" class="form-control" id="atualizarKmFinal" 
                                    v-model="$store.state.item.km_final" :min="$store.state.item.km_inicial">
                            </input-container-component>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <input-container-component 
                                titulo="Valor Diária (R$)" 
                                id="atualizarValorDiaria"
                                texto-ajuda="Valor da diária (caso tenha mudado)"
                            >
                                <input type="number" class="form-control" id="atualizarValorDiaria" 
                                    v-model="$store.state.item.valor_diaria" step="0.01" min="0">
                            </input-container-component>
                        </div>

                        <div class="form-group mb-3">
                            <input-container-component 
                                titulo="Status" 
                                id="atualizarStatus"
                                texto-ajuda="Status da locação"
                            >
                                <select class="form-control" id="atualizarStatus" v-model="$store.state.item.status">
                                    <option value="ativa">Ativa</option>
                                    <option value="finalizada">Finalizada</option>
                                    <option value="pendente">Pendente</option>
                                    <option value="cancelada">Cancelada</option>
                                </select>
                            </input-container-component>
                        </div>

                        <!-- Resumo da atualização -->
                        <div class="card bg-light">
                            <div class="card-body p-2">
                                <small>
                                    <strong>KM Inicial:</strong> {{ formatarKM($store.state.item.km_inicial) }}<br>
                                    <strong>Dias Locados:</strong> {{ calcularDias($store.state.item) }} dias<br>
                                    <strong>Total:</strong> {{ formatarMoeda(calcularTotal($store.state.item)) }}
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
            </template>
            <template v-slot:rodape>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                <button v-if="$store.state.transacao.status != 'sucesso'" type="button" class="btn btn-primary" @click="atualizar()">Atualizar</button>
            </template>
        </modal-component>
        <!--fim do modal de atualizar de locação-->
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
    name: 'Locacoes',
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
            urlBase: 'http://localhost:8000/api/v1/locacao',
            urlClientes: 'http://localhost:8000/api/v1/cliente',
            urlCarros: 'http://localhost:8000/api/v1/carro',
            urlPaginacao: '',
            urlFiltro: '',
            transacaoStatus: '',
            transacaoDetalhes: {},
            locacoes: { data: [] },
            clientes: [],
            carros: [],
            busca: {
                id: '',
                cliente_id: '',
                carro_id: '',
                data_inicio_periodo: '',
                status: ''
            },
            novaLocacao: {
                cliente_id: '',
                carro_id: '',
                data_inicio_periodo: '',
                data_final_previsto_periodo: '',
                data_final_realizado_periodo: '',
                valor_diaria: '',
                km_inicial: '',
                km_final: ''
            }
        }
    },

    computed: {
        dataHoje() {
            return new Date().toISOString().split('T')[0];
        },

        carrosDisponiveis() {
            return this.carros.filter(carro => carro.disponivel === 'Sim' || carro.disponivel === true);
        },

        carroSelecionado() {
            if (!this.novaLocacao.carro_id) return null;
            return this.carros.find(carro => carro.id == this.novaLocacao.carro_id);
        },

        calcularDiasLocacao() {
            if (!this.novaLocacao.data_inicio_periodo || !this.novaLocacao.data_final_previsto_periodo) {
                return 0;
            }
            const inicio = new Date(this.novaLocacao.data_inicio_periodo);
            const fim = new Date(this.novaLocacao.data_final_previsto_periodo);
            const diff = fim.getTime() - inicio.getTime();
            return Math.ceil(diff / (1000 * 3600 * 24));
        },

        valorTotal() {
            const dias = this.calcularDiasLocacao;
            const diaria = parseFloat(this.novaLocacao.valor_diaria) || 0;
            return dias * diaria;
        },

        podeSalvar() {
            return this.novaLocacao.cliente_id &&
                   this.novaLocacao.carro_id &&
                   this.novaLocacao.data_inicio_periodo &&
                   this.novaLocacao.data_final_previsto_periodo &&
                   this.novaLocacao.valor_diaria &&
                   this.novaLocacao.km_inicial !== '';
        }
    },

    methods: {
        // FUNÇÃO PRINCIPAL PARA DESTACAR LINHAS
        getRowClass(locacao) {
            // Verifica se a locação está atrasada
            if (this.isLocacaoAtrasada(locacao)) {
                return 'table-danger'; // Vermelho para atrasadas
            }
            
            // Cores por status
            switch(locacao.status) {
                case 'finalizada':
                    return 'table-success'; // Verde para finalizadas
                case 'cancelada':
                    return 'table-secondary'; // Cinza para canceladas
                case 'pendente':
                    return 'table-warning'; // Amarelo para pendentes
                case 'ativa':
                    return 'table-info'; // Azul para ativas
                default:
                    return '';
            }
        },
        
        // VERIFICA SE LOCAÇÃO ESTÁ ATRASADA
        isLocacaoAtrasada(locacao) {
            // Se já foi finalizada ou cancelada, não está atrasada
            if (locacao.status === 'finalizada' || locacao.status === 'cancelada') {
                return false;
            }
            
            // Verifica se tem data de previsão
            if (!locacao.data_final_previsto_periodo) {
                return false;
            }
            
            try {
                // Converte a data de previsão para objeto Date
                const dataPrevista = this.parseDateSQL(locacao.data_final_previsto_periodo);
                
                if (!dataPrevista || isNaN(dataPrevista.getTime())) {
                    return false;
                }
                
                const hoje = new Date();
                
                // Remove horas para comparar apenas datas
                hoje.setHours(0, 0, 0, 0);
                dataPrevista.setHours(0, 0, 0, 0);
                
                // Retorna true se hoje > data prevista
                return hoje > dataPrevista;
                
            } catch (error) {
                console.error('Erro ao verificar se locação está atrasada:', error);
                return false;
            }
        },
        
        // FUNÇÃO AUXILIAR PARA CONVERTER DATAS SQL
        parseDateSQL(dateString) {
            if (!dateString) return null;
            
            if (typeof dateString === 'string') {
                // Formato SQL: '2025-12-26 00:00:00'
                if (dateString.includes(' ')) {
                    const [datePart] = dateString.split(' ');
                    return new Date(datePart + 'T00:00:00');
                }
                // Formato ISO: '2025-12-26T00:00:00.000Z'
                else if (dateString.includes('T')) {
                    return new Date(dateString);
                }
                // Formato apenas data: '2025-12-26'
                else {
                    return new Date(dateString + 'T00:00:00');
                }
            }
            
            return new Date(dateString);
        },

        // MÉTODOS EXISTENTES
        formatarData(data) {
            if (!data) return 'N/A';
            return new Date(data).toLocaleDateString('pt-BR');
        },

        formatarMoeda(valor) {
            if (!valor) return 'R$ 0,00';
            return new Intl.NumberFormat('pt-BR', {
                style: 'currency',
                currency: 'BRL'
            }).format(valor);
        },

        formatarKM(km) {
            if (!km && km !== 0) return 'N/A';
            return new Intl.NumberFormat('pt-BR').format(km) + ' KM';
        },

        formatarStatus(status) {
            const statusMap = {
                'ativa': 'Ativa',
                'finalizada': 'Finalizada',
                'pendente': 'Pendente',
                'cancelada': 'Cancelada'
            };
            return statusMap[status] || status || 'N/A';
        },

        calcularDias(locacao) {
            if (!locacao.data_inicio_periodo) return 0;
            const inicio = new Date(locacao.data_inicio_periodo);
            const fim = locacao.data_final_realizado_periodo 
                ? new Date(locacao.data_final_realizado_periodo)
                : new Date(locacao.data_final_previsto_periodo);
            const diff = fim.getTime() - inicio.getTime();
            return Math.max(1, Math.ceil(diff / (1000 * 3600 * 24)));
        },

        calcularTotal(locacao) {
            const dias = this.calcularDias(locacao);
            const diaria = parseFloat(locacao.valor_diaria) || 0;
            return dias * diaria;
        },

        selecionarCarro() {
            if (this.carroSelecionado) {
                this.novaLocacao.km_inicial = this.carroSelecionado.km.replace(' KM', '').replace(/\./g, '') || '';
                this.novaLocacao.valor_diaria = 150;
            }
        },

        carregarClientes() {
            axios.get(this.urlClientes)
                .then(response => {
                    console.log('Clientes carregados:', response.data);
                    let clientesData = [];
                    
                    if (Array.isArray(response.data)) {
                        clientesData = response.data;
                    } else if (response.data && response.data.data) {
                        clientesData = response.data.data;
                    }
                    
                    this.clientes = clientesData;
                    console.log(`${this.clientes.length} clientes carregados`);
                })
                .catch(error => {
                    console.error('Erro ao carregar clientes:', error);
                    this.clientes = [];
                });
        },

        carregarCarros() {
            axios.get(this.urlCarros)
                .then(response => {
                    console.log('Carros carregados:', response.data);
                    let carrosData = [];
                    
                    if (Array.isArray(response.data)) {
                        carrosData = response.data;
                    } else if (response.data && response.data.data) {
                        carrosData = response.data.data;
                    }
                    
                    this.carros = carrosData.map(carro => {
                        return {
                            ...carro,
                            disponivel: carro.disponivel === 'Sim' || carro.disponivel === true || carro.disponivel === 1
                        };
                    });
                    
                    console.log(`${this.carros.length} carros carregados`);
                })
                .catch(error => {
                    console.error('Erro ao carregar carros:', error);
                    this.carros = [];
                });
        },

        // MÉTODO ATUALIZAR CORRIGIDO - ENVIA TODOS OS CAMPOS OBRIGATÓRIOS
        atualizar() {
            // Verificar se todos os campos obrigatórios estão presentes no store
            console.log('📋 Dados atuais no store:', this.$store.state.item);
            
            // PREPARA TODOS OS CAMPOS OBRIGATÓRIOS + OS CAMPOS DE ATUALIZAÇÃO
            const dados = {
                // Campos de atualização (que o usuário pode modificar)
                data_final_realizado_periodo: this.$store.state.item.data_final_realizado_periodo || null,
                km_final: this.$store.state.item.km_final ? parseInt(this.$store.state.item.km_final) : null,
                valor_diaria: this.$store.state.item.valor_diaria ? parseFloat(this.$store.state.item.valor_diaria) : 0,
                status: this.$store.state.item.status || 'ativa',
                
                // CAMPOS OBRIGATÓRIOS QUE PRECISAM SER REENVIADOS
                carro_id: this.$store.state.item.carro_id || this.$store.state.item.carro,
                cliente_id: this.$store.state.item.cliente_id || this.$store.state.item.cliente,
                data_inicio_periodo: this.$store.state.item.data_inicio_periodo || '',
                data_final_previsto_periodo: this.$store.state.item.data_final_previsto_periodo || '',
                km_inicial: this.$store.state.item.km_inicial ? parseInt(this.$store.state.item.km_inicial) : 0,
                valor_diaria: this.$store.state.item.valor_diaria ? parseFloat(this.$store.state.item.valor_diaria) : 0
            };

            // Remove campos nulos ou undefined
            Object.keys(dados).forEach(key => {
                if (dados[key] === null || dados[key] === undefined || dados[key] === '') {
                    delete dados[key];
                }
            });

            console.log('📤 Dados COMPLETOS para atualização:', dados);
            console.log('🔗 URL da requisição:', this.urlBase + '/' + this.$store.state.item.id);

            let url = this.urlBase + '/' + this.$store.state.item.id;
            
            // Usando PUT com todos os campos obrigatórios
            axios.patch(url, dados)
                .then(response => {
                    console.log('✅ Locação atualizada com sucesso:', response.data);
                    
                    this.$store.state.transacao.status = 'sucesso';
                    this.$store.state.transacao.mensagem = 'Locação atualizada com sucesso';
                    
                    // Atualiza a lista de locações
                    this.carregarLista();
                    
                    // Fecha o modal após 1 segundo
                    setTimeout(() => {
                        const modal = bootstrap.Modal.getInstance(document.getElementById('modalLocacaoAtualizar'));
                        if (modal) modal.hide();
                    }, 1000);
                })
                .catch(errors => {
                    console.error('❌ Erro completo da resposta:', errors);
                    console.error('📄 Resposta do servidor (data):', errors.response?.data);
                    console.error('⚠️ Erros de validação:', errors.response?.data?.errors);

                    this.$store.state.transacao.status = 'erro';
                    this.$store.state.transacao.mensagem = errors.response?.data?.message || 'Erro ao atualizar locação';
                    this.$store.state.transacao.dados = errors.response?.data?.errors || {};
                });
        },

        remover() {
            let confirmacao = confirm('Tem certeza que deseja cancelar esta locação?');
            if (!confirmacao) {
                return false;
            }

            let url = this.urlBase + '/' + this.$store.state.item.id;
            let formData = new FormData();
            formData.append('_method', 'delete');

            axios.post(url, formData)
                .then(response => {
                    this.$store.state.transacao.status = 'sucesso';
                    this.$store.state.transacao.mensagem = response.data.msg || 'Locação cancelada com sucesso';
                    this.carregarLista();
                })
                .catch(errors => {
                    this.$store.state.transacao.status = 'erro';
                    this.$store.state.transacao.mensagem = errors.response.data.erro || 'Erro ao cancelar locação';
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

        limparFiltros() {
            this.busca = {
                id: '',
                cliente_id: '',
                carro_id: '',
                data_inicio_periodo: '',
                status: ''
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
            
            console.log('📡 Buscando locações:', url);
            
            axios.get(url)
                .then(response => {
                    console.log('✅ Locações recebidas:', response.data);
                    
                    let locacoesData = [];
                    
                    if (Array.isArray(response.data)) {
                        locacoesData = response.data;
                    } else if (response.data && response.data.data) {
                        locacoesData = response.data.data;
                    }
                    
                    const locacoesTransformadas = locacoesData.map(locacao => {
                        const cliente = this.clientes.find(c => c.id == locacao.cliente_id);
                        const carro = this.carros.find(c => c.id == locacao.carro_id);
                        
                        const clienteNome = cliente ? cliente.nome : `ID: ${locacao.cliente_id}`;
                        const carroInfo = carro ? `${carro.placa}` : 'N/A';
                        
                        // Determina status baseado nas datas
                        let status = locacao.status || 'ativa';
                        if (locacao.data_final_realizado_periodo) {
                            status = 'finalizada';
                        } else if (locacao.data_final_previsto_periodo) {
                            const dataPrevista = this.parseDateSQL(locacao.data_final_previsto_periodo);
                            const hoje = new Date();
                            hoje.setHours(0, 0, 0, 0);
                            if (dataPrevista && dataPrevista < hoje) {
                                status = 'pendente';
                            }
                        }
                        
                        // Calcula valor total
                        let dias = 0;
                        let total = 0;
                        
                        if (locacao.data_inicio_periodo) {
                            const inicio = this.parseDateSQL(locacao.data_inicio_periodo);
                            const fim = locacao.data_final_realizado_periodo 
                                ? this.parseDateSQL(locacao.data_final_realizado_periodo)
                                : (locacao.data_final_previsto_periodo 
                                    ? this.parseDateSQL(locacao.data_final_previsto_periodo) 
                                    : new Date());
                            
                            if (inicio && fim && !isNaN(inicio.getTime()) && !isNaN(fim.getTime())) {
                                const diff = fim.getTime() - inicio.getTime();
                                dias = Math.max(1, Math.ceil(diff / (1000 * 3600 * 24)));
                            }
                        }
                        
                        const diaria = parseFloat(locacao.valor_diaria) || 0;
                        total = dias * diaria;
                        
                        // RETORNA OBJETO COMPLETO COM TODOS OS CAMPOS NECESSÁRIOS
                        return {
                            id: locacao.id || '-',
                            cliente_id: locacao.cliente_id || '-',
                            carro_id: locacao.carro_id || '-',
                            cliente: locacao.cliente_id || '-', // Campo alternativo
                            carro: locacao.carro_id || '-', // Campo alternativo
                            cliente_nome: clienteNome,
                            carro_info: carroInfo,
                            data_inicio_periodo: locacao.data_inicio_periodo || '',
                            data_final_previsto_periodo: locacao.data_final_previsto_periodo || '',
                            data_final_realizado_periodo: locacao.data_final_realizado_periodo || '',
                            valor_diaria: locacao.valor_diaria || 0,
                            km_inicial: locacao.km_inicial || 0,
                            km_final: locacao.km_final || null,
                            valor_total: total,
                            status: status,
                            created_at: locacao.created_at || new Date().toISOString(),
                            updated_at: locacao.updated_at || null
                        };
                    });
                    
                    this.locacoes = { 
                        data: locacoesTransformadas, 
                        links: [] 
                    };
                    
                    console.log(`🎯 ${this.locacoes.data.length} locações processadas`);
                })
                .catch(errors => {
                    console.error('❌ Erro ao carregar locações:', errors);
                    this.locacoes = { data: [], links: [] };
                });
        },

        salvar() {
            if (!this.podeSalvar) {
                alert('Preencha todos os campos obrigatórios!');
                return;
            }

            const dados = {
                cliente_id: this.novaLocacao.cliente_id,
                carro_id: this.novaLocacao.carro_id,
                data_inicio_periodo: this.novaLocacao.data_inicio_periodo,
                data_final_previsto_periodo: this.novaLocacao.data_final_previsto_periodo,
                valor_diaria: parseFloat(this.novaLocacao.valor_diaria),
                km_inicial: parseInt(this.novaLocacao.km_inicial)
            };

            console.log('📤 Enviando nova locação:', dados);

            axios.post(this.urlBase, dados)
                .then(response => {
                    console.log('✅ Locação criada:', response.data);
                    
                    this.transacaoDetalhes = {
                        mensagem: 'Locação ID: ' + response.data.id + ' realizada com sucesso!'
                    };
                    this.transacaoStatus = 'Adicionado';
                    
                    this.resetForm();
                    
                    setTimeout(() => {
                        const modal = bootstrap.Modal.getInstance(document.getElementById('modalLocacao'));
                        if (modal) modal.hide();
                        this.carregarLista();
                    }, 1500);
                })
                .catch(errors => {
                    console.error('❌ Erro ao salvar locação:', errors.response?.data);
                    
                    this.transacaoStatus = 'Cancelado';
                    this.transacaoDetalhes = {
                        mensagem: errors.response?.data?.message || 'Erro ao realizar locação',
                        dados: errors.response?.data?.errors || {}
                    };
                });
        },

        resetForm() {
            this.novaLocacao = {
                cliente_id: '',
                carro_id: '',
                data_inicio_periodo: '',
                data_final_previsto_periodo: '',
                data_final_realizado_periodo: '',
                valor_diaria: '',
                km_inicial: '',
                km_final: ''
            };
        }
    },

    mounted() {
        this.carregarClientes();
        this.carregarCarros();
        
        setTimeout(() => {
            this.carregarLista();
        }, 500);
    },
}
</script>

<style scoped>
.card-header {
    background-color: #f8f9fa;
    border-bottom: 1px solid #dee2e6;
}

.btn-sm {
    font-size: 0.875rem;
    padding: 0.25rem 0.5rem;
}

.table-danger .btn-outline-primary {
    border-color: #721c24;
    color: #721c24;
}

.table-danger .btn-outline-primary:hover {
    background-color: #721c24;
    color: white;
}

/* Estilos para os modais */
.modal-body {
    padding: 1.5rem;
}

.form-group {
    margin-bottom: 1rem;
}

/* Melhorias na responsividade */
@media (max-width: 768px) {
    .col-md-6 {
        margin-bottom: 1rem;
    }
    
    .btn-sm {
        font-size: 0.8rem;
        padding: 0.2rem 0.4rem;
    }
}
</style>