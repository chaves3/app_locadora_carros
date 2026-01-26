<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <card-component titulo="Busca de Modelos">
                    <template v-slot:conteudo>
                        <div class="row g-3">
                            <div class="col-md-6 mb-3">
                                <input-container-component 
                                    titulo="ID" 
                                    id="inputId"
                                    id-help="inputIdHelp"
                                    texto-ajuda="Opcional. Informe o ID do Modelo"
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

                            <div class="col-md-6 mb-3">
                                <input-container-component 
                                    titulo="Nome do Modelo" 
                                    id="inputNome"
                                    id-help="nomeHelp"
                                    texto-ajuda="Opcional. Informe o Nome do Modelo"
                                >
                                    <input v-model="busca.nome" type="text" class="form-control" id="inputNome" aria-describedby="nomeHelp" placeholder="Nome do Modelo">
                                </input-container-component>
                            </div>
                        </div>
                    </template>

                    <template v-slot:rodape>
                        <button @click="pesquisar" type="submit" class="btn btn-primary btn-sm float-end">Pesquisar</button>
                    </template>
                </card-component>

                <!--inicio do card de listagem de modelos-->
                <card-component titulo="Listagem de Modelos">
                    <template v-slot:conteudo>
                        <div class="card-body">
                            <table-component 
                                :titulos="{
                                    id: {titulo: 'ID', tipo: 'texto'},
                                    nome: {titulo: 'Modelo', tipo: 'texto'},
                                    marca_nome: {titulo: 'Marca', tipo: 'texto'},
                                    numero_portas: {titulo: 'Portas', tipo: 'texto'},
                                    lugares: {titulo: 'Lugares', tipo: 'texto'},
                                    air_bag: {titulo: 'Air Bag', tipo: 'texto'},
                                    abs: {titulo: 'ABS', tipo: 'texto'},
                                    imagem: {titulo: 'Imagem', tipo: 'imagem'},
                                    created_at: {titulo: 'Criado em', tipo: 'data'},
                                }"
                                :visualizar="{
                                    visivel:true,
                                    dataBsToggle: 'modal',
                                    dataBsTarget: '#modalModeloVisualizar',
                                }"
                                :atualizar="{
                                    visivel: true,
                                    dataBsToggle: 'modal',
                                    dataBsTarget: '#modalModeloAtualizar',
                                }"
                                :excluir="{
                                    visivel:true,
                                    dataBsToggle: 'modal',
                                    dataBsTarget: '#modalModeloRemover',
                                }"
                                :dados="modelos.data"
                            >
                            </table-component>
                        </div>
                    </template>
                    <template v-slot:rodape>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-10">
                                    <paginate-component>
                                        <li v-for="l, key in modelos.links" :key="key" :class="l.active ? 'page-item active' : 'page-item'" @click="paginacao(l)">
                                            <a class="page-link" v-html="l.label"></a>
                                        </li>
                                    </paginate-component>
                                </div>
                                <div class="col">
                                    <button type="button" class="btn btn-primary btn-sm float-end" data-bs-toggle="modal" data-bs-target="#modalModelo">Adicionar</button>
                                </div>
                            </div>
                        </div>
                    </template>
                </card-component>
                <!--fim da listagem de modelos -->
            </div>
        </div>

        <!--inicio do modal de inclusão de modelo-->
        <modal-component id="modalModelo" titulo="Adicionar Modelo">
            <template v-slot:alertas>
                <alert-component tipo="success" :detalhes="transacaoDetalhes" titulo="Cadastro Realizado com sucesso" v-if="transacaoStatus == 'Adicionado'"></alert-component>
                <alert-component tipo="danger" :detalhes="transacaoDetalhes" titulo="Erro ao tentar cadastrar o modelo" v-if="transacaoStatus == 'Cancelado'"></alert-component>
            </template>
            <template v-slot:conteudo>
                <div class="row">
                    <!-- Coluna 1 -->
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <input-container-component 
                                titulo="Marca" 
                                id="marcaId"
                                id-help="marcaIdHelp"
                                texto-ajuda="Selecione a marca do veículo"
                            >
                                <select class="form-control" id="marcaId" v-model="novoModelo.marca_id">
                                    <option value="">Selecione uma marca</option>
                                    <option v-for="marca in marcas" :key="marca.id" :value="marca.id">{{ marca.nome }}</option>
                                </select>
                            </input-container-component>
                        </div>

                        <div class="form-group mb-3">
                            <input-container-component 
                                titulo="Nome do Modelo" 
                                id="novoNome"
                                id-help="novonomeHelp"
                                texto-ajuda="Informe o nome do modelo"
                            >
                                <input type="text" class="form-control" id="novoNome" aria-describedby="novonomeHelp" placeholder="Ex: Civic, Corolla, Gol" v-model="novoModelo.nome">
                            </input-container-component>
                        </div>

                        <div class="form-group mb-3">
                            <input-container-component 
                                titulo="Número de Portas" 
                                id="numeroPortas"
                                id-help="numeroPortasHelp"
                                texto-ajuda="Informe o número de portas"
                            >
                                <select class="form-control" id="numeroPortas" v-model="novoModelo.numero_portas">
                                    <option value="">Selecione</option>
                                    <option value="2">2 Portas</option>
                                    <option value="3">3 Portas</option>
                                    <option value="4">4 Portas</option>
                                    <option value="5">5 Portas</option>
                                </select>
                            </input-container-component>
                        </div>

                        <div class="form-group mb-3">
                            <input-container-component 
                                titulo="Número de Lugares" 
                                id="lugares"
                                id-help="lugaresHelp"
                                texto-ajuda="Informe a capacidade de passageiros"
                            >
                                <select class="form-control" id="lugares" v-model="novoModelo.lugares">
                                    <option value="">Selecione</option>
                                    <option value="2">2 Lugares</option>
                                    <option value="4">4 Lugares</option>
                                    <option value="5">5 Lugares</option>
                                    <option value="7">7 Lugares</option>
                                    <option value="8">8 Lugares</option>
                                </select>
                            </input-container-component>
                        </div>
                    </div>

                    <!-- Coluna 2 -->
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <input-container-component 
                                titulo="Air Bag" 
                                id="airBag"
                                id-help="airBagHelp"
                                texto-ajuda="O veículo possui air bag?"
                            >
                                <select class="form-control" id="airBag" v-model="novoModelo.air_bag">
                                    <option value="">Selecione</option>
                                    <option value="1">Sim</option>
                                    <option value="0">Não</option>
                                </select>
                            </input-container-component>
                        </div>

                        <div class="form-group mb-3">
                            <input-container-component 
                                titulo="Freio ABS" 
                                id="abs"
                                id-help="absHelp"
                                texto-ajuda="O veículo possui freio ABS?"
                            >
                                <select class="form-control" id="abs" v-model="novoModelo.abs">
                                    <option value="">Selecione</option>
                                    <option value="1">Sim</option>
                                    <option value="0">Não</option>
                                </select>
                            </input-container-component>
                        </div>

                        <div class="form-group mb-3">
                            <input-container-component 
                                titulo="Imagem" 
                                id="novoImagem"
                                id-help="novoImagemHelp"
                                texto-ajuda="Selecione uma imagem (JPG, PNG, GIF)"
                            >
                                <input type="file" class="form-control-file" id="novoImagem" aria-describedby="novoImagemHelp" @change="carregarImage($event)" accept="image/*">
                            </input-container-component>
                        </div>

                        <!-- Pré-visualização da imagem -->
                        <div class="form-group mb-3" v-if="arquivoImagem[0]">
                            <label>Pré-visualização:</label>
                            <div class="mt-2">
                                <img :src="previewImage" alt="Pré-visualização" class="img-thumbnail" style="max-width: 200px; max-height: 150px;">
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
        <!--final do modal de inclusão de modelo-->

        <!--inicio do modal de visualização de modelo-->
        <modal-component id="modalModeloVisualizar" titulo="Visualizar Modelo">
            <template v-slot:alertas></template>
            <template v-slot:conteudo>
                <div class="row">
                    <div class="col-md-6">
                        <input-container-component titulo="ID">
                            <input type="text" class="form-control" :value="$store.state.item.id" disabled>
                        </input-container-component>

                        <input-container-component titulo="Marca">
                            <input type="text" class="form-control" :value="$store.state.item.marca_nome || 'N/A'" disabled>
                        </input-container-component>

                        <input-container-component titulo="Nome do Modelo">
                            <input type="text" class="form-control" :value="$store.state.item.nome" disabled>
                        </input-container-component>

                        <input-container-component titulo="Número de Portas">
                            <input type="text" class="form-control" :value="$store.state.item.numero_portas + ' portas'" disabled>
                        </input-container-component>

                        <input-container-component titulo="Lugares">
                            <input type="text" class="form-control" :value="$store.state.item.lugares + ' lugares'" disabled>
                        </input-container-component>
                    </div>

                    <div class="col-md-6">
                        <input-container-component titulo="Air Bag">
                            <input type="text" class="form-control" :value="$store.state.item.air_bag == 1 ? 'Sim' : 'Não'" disabled>
                        </input-container-component>

                        <input-container-component titulo="Freio ABS">
                            <input type="text" class="form-control" :value="$store.state.item.abs == 1 ? 'Sim' : 'Não'" disabled>
                        </input-container-component>

                        <input-container-component titulo="Imagem" v-if="$store.state.item.imagem">
                            <img :src="'storage/' + $store.state.item.imagem" alt="Imagem do Modelo" class="img-fluid img-thumbnail">
                        </input-container-component>

                        <input-container-component titulo="Data de Criação">
                            <input type="text" class="form-control" :value="$store.state.item.created_at" disabled>
                        </input-container-component>
                    </div>
                </div>
            </template>
            <template v-slot:rodape>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
            </template>
        </modal-component>
        <!--final do modal de visualização de modelo-->

        <!--inicio do modal de remoção de modelo-->
        <modal-component id="modalModeloRemover" titulo="Remover Modelo">
            <template v-slot:alertas>
                <alert-component tipo="success" titulo="Transação realizada com sucesso" :detalhes="$store.state.transacao" v-if="$store.state.transacao.status == 'sucesso'"></alert-component>
                <alert-component tipo="danger" titulo="Erro na Transação" :detalhes="$store.state.transacao" v-if="$store.state.transacao.status == 'erro'"></alert-component>
            </template>
            <template v-slot:conteudo v-if="$store.state.transacao.status != 'sucesso'">
                <input-container-component titulo="ID">
                    <input type="text" class="form-control" :value="$store.state.item.id" disabled>
                </input-container-component>
                <input-container-component titulo="Nome">
                    <input type="text" class="form-control" :value="$store.state.item.nome" disabled>
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
        <!--fim do modal de remoção de modelo-->

        <!--inicio do modal de atualizar de modelo-->
        <modal-component id="modalModeloAtualizar" titulo="Atualizar Modelo">
            <template v-slot:alertas>
                <alert-component tipo="success" titulo="Transação realizada com sucesso" :detalhes="$store.state.transacao" v-if="$store.state.transacao.status == 'sucesso'"></alert-component>
                <alert-component tipo="danger" titulo="Erro na Transação" :detalhes="$store.state.transacao" v-if="$store.state.transacao.status == 'erro'"></alert-component>
            </template>
            <template v-slot:conteudo v-if="$store.state.transacao.status != 'sucesso'">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <input-container-component 
                                titulo="Marca" 
                                id="atualizarMarcaId"
                                texto-ajuda="Selecione a marca do veículo"
                            >
                                <select class="form-control" id="atualizarMarcaId" v-model="$store.state.item.marca_id">
                                    <option value="">Selecione uma marca</option>
                                    <option v-for="marca in marcas" :key="marca.id" :value="marca.id">{{ marca.nome }}</option>
                                </select>
                            </input-container-component>
                        </div>

                        <div class="form-group mb-3">
                            <input-container-component 
                                titulo="Nome do Modelo" 
                                id="atualizarNome"
                                texto-ajuda="Informe o nome do modelo"
                            >
                                <input type="text" class="form-control" id="atualizarNome" placeholder="Nome do Modelo" v-model="$store.state.item.nome">
                            </input-container-component>
                        </div>

                        <div class="form-group mb-3">
                            <input-container-component 
                                titulo="Número de Portas" 
                                id="atualizarNumeroPortas"
                            >
                                <select class="form-control" id="atualizarNumeroPortas" v-model="$store.state.item.numero_portas">
                                    <option value="">Selecione</option>
                                    <option value="2">2 Portas</option>
                                    <option value="3">3 Portas</option>
                                    <option value="4">4 Portas</option>
                                    <option value="5">5 Portas</option>
                                </select>
                            </input-container-component>
                        </div>

                        <div class="form-group mb-3">
                            <input-container-component 
                                titulo="Número de Lugares" 
                                id="atualizarLugares"
                            >
                                <select class="form-control" id="atualizarLugares" v-model="$store.state.item.lugares">
                                    <option value="">Selecione</option>
                                    <option value="2">2 Lugares</option>
                                    <option value="4">4 Lugares</option>
                                    <option value="5">5 Lugares</option>
                                    <option value="7">7 Lugares</option>
                                    <option value="8">8 Lugares</option>
                                </select>
                            </input-container-component>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <input-container-component 
                                titulo="Air Bag" 
                                id="atualizarAirBag"
                            >
                                <select class="form-control" id="atualizarAirBag" v-model="$store.state.item.air_bag">
                                    <option value="">Selecione</option>
                                    <option value="1">Sim</option>
                                    <option value="0">Não</option>
                                </select>
                            </input-container-component>
                        </div>

                        <div class="form-group mb-3">
                            <input-container-component 
                                titulo="Freio ABS" 
                                id="atualizarAbs"
                            >
                                <select class="form-control" id="atualizarAbs" v-model="$store.state.item.abs">
                                    <option value="">Selecione</option>
                                    <option value="1">Sim</option>
                                    <option value="0">Não</option>
                                </select>
                            </input-container-component>
                        </div>

                        <div class="form-group mb-3">
                            <input-container-component 
                                titulo="Imagem" 
                                id="atualizarImagem"
                                texto-ajuda="Selecione uma nova imagem (opcional)"
                            >
                                <input type="file" class="form-control-file" id="atualizarImagem" @change="carregarImage($event)" accept="image/*">
                            </input-container-component>
                        </div>

                        <div class="form-group mb-3" v-if="$store.state.item.imagem">
                            <label>Imagem Atual:</label>
                            <div class="mt-2">
                                <img :src="'storage/' + $store.state.item.imagem" alt="Imagem Atual" class="img-thumbnail" style="max-width: 200px; max-height: 150px;">
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
        <!--fim do modal de atualizar de modelo-->
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
    name: 'Modelos',
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
            urlBase: 'http://localhost:8000/api/v1/modelo',
            urlMarcas: 'http://localhost:8000/api/v1/marca', 
            urlPaginacao: '',
            urlFiltro: '',
            arquivoImagem: [],
            previewImage: null,
            transacaoStatus: '',
            transacaoDetalhes: {},
            modelos: { data: [] },
            marcas: [], 
            busca: {
                id: '',
                nome: '',
            },
            novoModelo: {
                marca_id: '',
                nome: '',
                numero_portas: '',
                lugares: '',
                air_bag: '',
                abs: '',
            }
        }
    },

    methods: {
        carregarMarcas() {
            axios.get(this.urlMarcas)
                .then(response => {
                    this.marcas = response.data.data;
                })
                .catch(error => {
                    console.error('Erro ao carregar marcas:', error);
                });
        },

        atualizar() {
            let formData = new FormData();
            formData.append('_method', 'patch');
            formData.append('marca_id', this.$store.state.item.marca_id);
            formData.append('nome', this.$store.state.item.nome);
            formData.append('numero_portas', this.$store.state.item.numero_portas);
            formData.append('lugares', this.$store.state.item.lugares);
            formData.append('air_bag', this.$store.state.item.air_bag);
            formData.append('abs', this.$store.state.item.abs);

            if (this.arquivoImagem[0]) {
                formData.append('imagem', this.arquivoImagem[0]);
            }

            let url = this.urlBase + '/' + this.$store.state.item.id;
            let config = {
                headers: {
                    'Content-Type': 'multipart/form-data',
                }
            };

            axios.post(url, formData, config)
                .then(response => {
                    this.$store.state.transacao.status = 'sucesso';
                    this.$store.state.transacao.mensagem = 'Modelo atualizado com sucesso';
                    document.getElementById('atualizarImagem').value = '';
                    this.carregarLista();
                })
                .catch(errors => {
                    this.$store.state.transacao.status = 'erro';
                    this.$store.state.transacao.mensagem = errors.response.data.message;
                    this.$store.state.transacao.dados = errors.response.data.errors;
                });
        },

        remover() {
            let confirmacao = confirm('Tem certeza que deseja remover este modelo?');
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
                    this.$store.state.transacao.mensagem = errors.response.data.erro || 'Erro ao remover modelo';
                });
        },

        pesquisar() {
            let filtro = '';

            for (let chave in this.busca) {
                if (this.busca[chave]) {
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
                    console.log('Dados recebidos:', response.data);
                    
                    let modelosData = [];
                    
                    // SUA API RETORNA UM ARRAY DIRETAMENTE!
                    if (Array.isArray(response.data)) {
                        modelosData = response.data;
                    } 
                    // Se por acaso for paginado
                    else if (response.data && response.data.data) {
                        modelosData = response.data.data;
                    }
                    
                    // TRANSFORMA OS DADOS PARA O FORMATO QUE A TABELA ESPERA
                    const modelosTransformados = modelosData.map(modelo => {
                        // Se tiver marca como objeto, extrai o nome
                        const marcaNome = modelo.marca 
                            ? modelo.marca.nome 
                            : (modelo.marca_nome || `ID: ${modelo.marca_id}`);
                        
                        // Cria um novo objeto com os campos que a tabela espera
                        return {
                            ...modelo,
                            marca_nome: marcaNome,  // ⬅️ ADICIONA O CAMPO QUE A TABELA ESPERA
                            // Garante valores para todos os campos
                            numero_portas: modelo.numero_portas || '-',
                            lugares: modelo.lugares || '-',
                            air_bag: modelo.air_bag != null ? (modelo.air_bag ? 'Sim' : 'Não') : '-',
                            abs: modelo.abs != null ? (modelo.abs ? 'Sim' : 'Não') : '-',
                        };
                    });
                    
                    this.modelos = { 
                        data: modelosTransformados, 
                        links: [] 
                    };
                    
                    console.log('Modelos transformados:', this.modelos.data);
                })
                .catch(errors => {
                    console.error('Erro ao carregar modelos:', errors);
                    this.modelos = { data: [], links: [] };
                });
        },

        carregarImage(e) {
            this.arquivoImagem = e.target.files;
            if (this.arquivoImagem[0]) {
                const reader = new FileReader();
                reader.onload = (e) => {
                    this.previewImage = e.target.result;
                };
                reader.readAsDataURL(this.arquivoImagem[0]);
            }
        },

        salvar() {
            let formData = new FormData();
            formData.append('marca_id', this.novoModelo.marca_id);
            formData.append('nome', this.novoModelo.nome);
            formData.append('numero_portas', this.novoModelo.numero_portas);
            formData.append('lugares', this.novoModelo.lugares);
            formData.append('air_bag', this.novoModelo.air_bag);
            formData.append('abs', this.novoModelo.abs);

            if (this.arquivoImagem[0]) {
                formData.append('imagem', this.arquivoImagem[0]);
            }

            let config = {
                headers: {
                    'Content-Type': 'multipart/form-data'
                }
            };

            axios.post(this.urlBase, formData, config)
                .then(response => {
                    this.transacaoDetalhes = {
                        mensagem: 'ID do registro: ' + response.data.id
                    };
                    this.transacaoStatus = 'Adicionado';
                    
                    // Limpa todos os campos após salvar
                    this.resetForm();
                    
                    // Fecha o modal e atualiza a lista
                    setTimeout(() => {
                        document.getElementById('modalModelo').querySelector('.btn-secondary').click();
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
            this.novoModelo = {
                marca_id: '',
                nome: '',
                numero_portas: '',
                lugares: '',
                air_bag: '',
                abs: '',
            };
            this.arquivoImagem = [];
            this.previewImage = null;
            document.getElementById('novoImagem').value = '';
        }
    },

    mounted() {
        this.carregarLista();
        this.carregarMarcas(); // Carrega as marcas ao iniciar
    },
}
</script>