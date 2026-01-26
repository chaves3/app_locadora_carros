<template>
    <div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <card-component titulo="Busca de Marcas">
                <template v-slot:conteudo>
                         <div class="row g-3">
                        <div class="col mb-3">
                        <input-container-component 
                            titulo="ID" 
                            id="inputId"
                            id-help="inputIdHelp"
                            texto-ajuda="Opcional. Informe o ID da Marca"
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

                        <div class="col mb-3">
                        <input-container-component 
                        titulo="Nome da Marca" 
                        id="inputNome"
                        id-help="nomeHelp"
                        texto-ajuda="Opcional. Informe o Nome da Marca"
                        >
                        <input  v-model="busca.nome" type="text" class="form-control" id="inputNome" aria-describedby="nomeHelp" placeholder="Nome da Marca">
                       </input-container-component>
                        </div>
                    </div>
                </template>

                <template v-slot:rodape>
                    <button @click="pesquisar" type="submit" class="btn btn-primary btn-sm float-end">Pesquisar</button>
                </template>

            </card-component>

            <!--inicio do card de listagem de marcas-->
            <card-component titulo="Listagem de Marcas">
                <template v-slot:conteudo>
                     <div class="card-body">
                        <table-component 
                        :titulos="{
                            id: {titulo: 'ID', tipo: 'texto'},
                            nome: {titulo: 'Nome', tipo: 'texto'},
                            imagem: {titulo: 'Imagem', tipo: 'imagem'},
                            created_at: {titulo: 'Data de Criação', tipo: 'data'},
                        }"
                        :visualizar="{
                            visivel:true,
                            dataBsToggle: 'modal',
                            dataBsTarget: '#modalMarcaVisualizar',
                        }"
                        :atualizar="{
                            visivel: true,
                            dataBsToggle: 'modal',
                            dataBsTarget: '#modalMarcaAtualizar',
                        }"
                        :excluir="{
                            visivel:true,
                            dataBsToggle: 'modal',
                            dataBsTarget: '#modalMarcaRemover',
                        }"
                        :dados="marcas.data">
                    </table-component>
                    </div>
                </template>
                 <template v-slot:rodape>
                     <div class="card-body">
                        <div class="row">
                            <div class="col-10">
                        <paginate-component>
                               <li v-for="l, key in marcas.links" :key="key" :class="l.active ? 'page-item active' : 'page-item'" @click="paginacao(l)">
                                <a class="page-link" v-html="l.label"></a>
                            </li>
                        </paginate-component>
                        </div>
                        <div class="col">
                        <button type="button" class="btn btn-primary btn-sm float-end" data-bs-toggle="modal" data-bs-target="#modalMarca">Adicionar</button>
                        </div>
                    </div>
                    </div>
                </template>
            </card-component>
            <!--fim da listagem de marxas -->
        </div>
    </div>
    <!--inicio do modal de incluse de marca-->
    <modal-component id="modalMarca" titulo="Adicionar Marca">
        <template v-slot:alertas>
            <alert-component tipo="success" :detalhes="transacaoDetalhes" titulo="Cadastro Realizado com sucesso" v-if="transacaoStatus == 'Adicionado'"></alert-component>
            <alert-component tipo="danger" :detalhes="transacaoDetalhes" titulo="Erro ao tentar cadastrar a marca" v-if="transacaoStatus == 'Cancelado'"></alert-component>
        </template>
        <template v-slot:conteudo>
        <div class="form-group mb-3">
         <input-container-component 
          titulo="Nome da Marca" 
          id="novoNome"
          id-help="novonomeHelp"
          texto-ajuda="Informe o Nome da Marca"
          >
         <input type="text" class="form-control" id="novoNome" aria-describedby="novonomeHelp" placeholder="Nome da Marca" v-model="nomeMarca">
         </input-container-component>
        </div>
        <div class="form-group">
         <input-container-component 
          titulo="Imagem" 
          id="novoImagem"
          id-help="novoImagemHelp"
          texto-ajuda="Selecione uma imagem no formato PNG"
          >
         <input type="file" class="form-control-file" id="novoImagem" aria-describedby="novoImagemHelp" placeholder="Imagem da Marca" @change="carregarImage($event)">
         </input-container-component>
        </div>
        </template>
        <template v-slot:rodape>
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
            <button type="button" class="btn btn-primary" @click="salvar()">Salvar</button>
        </template>
    </modal-component>
    <!--final do modal de incluse de marca-->

    <!--inicio do modal de visualização de marca-->
        <modal-component id="modalMarcaVisualizar" titulo="Visualizar Marca">
             <template v-slot:alertas>
             </template>
              <template v-slot:conteudo>
                <input-container-component titulo="ID">
                 <input type="text" class="form-control" :value=" $store.state.item.id" disabled>
                </input-container-component>
                <input-container-component titulo="Nome">
                 <input type="text" class="form-control" :value=" $store.state.item.nome" disabled>
                </input-container-component>
                <input-container-component titulo="Imagem">
                 <img :src="'storage/'+$store.state.item.imagem" alt="" v-if="$store.state.item.imagem">
                </input-container-component>
                 <input-container-component titulo="Data de Criação">
                  <input type="text" class="form-control" :value=" $store.state.item.created_at" disabled>
                </input-container-component> 
              </template>
            <template v-slot:rodape>
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
           </template>
        </modal-component>
    <!--final do modal de visualização de marca-->

    <!--inicio do modal de remoção de marca-->
        <modal-component id="modalMarcaRemover" titulo="Remover Marca">
             <template v-slot:alertas>
                <alert-component tipo="success" titulo="Transação realizada com sucesso" :detalhes="$store.state.transacao" v-if="$store.state.transacao.status == 'sucesso'"></alert-component>
                <alert-component tipo="danger" titulo="Erro na Transação" :detalhes="$store.state.transacao"  v-if="$store.state.transacao.status == 'erro'"></alert-component>
             </template>
              <template v-slot:conteudo v-if="$store.state.transacao.status != 'sucesso'">
                <input-container-component titulo="ID">
                 <input type="text" class="form-control" :value=" $store.state.item.id" disabled>
                </input-container-component>
                <input-container-component titulo="Nome">
                 <input type="text" class="form-control" :value=" $store.state.item.nome" disabled>
                </input-container-component>
              </template>
            <template v-slot:rodape>
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
            <button v-if="$store.state.transacao.status != 'sucesso'" type="button" class="btn btn-danger" @click="remover()">Remover</button>
           </template>
        </modal-component>
    <!--fim do modal de remoção de marca-->

    <!--inicio do modal de atualizar de marca-->
        <modal-component id="modalMarcaAtualizar" titulo="Atualizar Marca">
             <template v-slot:alertas>
                <alert-component tipo="success" titulo="Transação realizada com sucesso" :detalhes="$store.state.transacao" v-if="$store.state.transacao.status == 'sucesso'"></alert-component>
                <alert-component tipo="danger" titulo="Erro na Transação" :detalhes="$store.state.transacao"  v-if="$store.state.transacao.status == 'erro'"></alert-component>
             </template>
              <template v-slot:conteudo v-if="$store.state.transacao.status != 'sucesso'">
               <div class="form-group mb-3">
                    <input-container-component 
                    titulo="Atualizar nome da Marca" 
                    id="AtualizarNome"
                    id-help="AtualizarnomeHelp"
                    texto-ajuda="Informe o Nome da Marca"
                    >
                    <input type="text" class="form-control" id="AtualizarNome" aria-describedby="AtualizarnomeHelp" placeholder="Atualizar Nome da Marca" v-model="$store.state.item.nome">
                    </input-container-component>
                    </div>
                    <div class="form-group">
                    <input-container-component 
                    titulo="AtualizarImagem" 
                    id="AtualizarImagem"
                    id-help="AtualizarImagemHelp"
                    texto-ajuda="Selecione uma imagem no formato PNG"
                    >
                    <input type="file" class="form-control-file" id="AtualizarImagem" aria-describedby="novoImagemHelp" placeholder="Imagem da Marca" @change="carregarImage($event)">
                    </input-container-component>
        </div>
              </template>
            <template v-slot:rodape>
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
            <button v-if="$store.state.transacao.status != 'sucesso'" type="button" class="btn btn-primary" @click="atualizar()">Atualizar</button>
           </template>
        </modal-component>
    <!--fim do modal de atualizar de marca-->

 
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
    name: 'Marcas',
    components: {
        'input-container-component': InputContainer,
        'table-component': Table,
        'card-component': Card,
        'modal-component': Modal,
        'alert-component': Alert,
        'paginate-component': Paginate,
    },

    data(){
        return{
            urlBase: 'http://localhost:8000/api/v1/marca',
            urlPaginacao: '',
            urlFiltro: '',
            nomeMarca: '',
            arquivoImagem: [],
            transacaoStatus: '',
            transacaoDetalhes: {},
            marcas: {data: []},
            busca: {
                id: '',
                nome: '',
            },
        }
    },
    methods: {
        atualizar(){
            let formData = new FormData();
            formData.append('_method', 'patch');
            formData.append('nome', this.$store.state.item.nome);

            if(this.arquivoImagem[0]){
            formData.append('imagem', this.arquivoImagem[0]);
             }
             let url = this.urlBase+'/'+this.$store.state.item.id;
             let config = {
                headers: {
                    'Content-Type': 'multipart/form-data',
                   

                }
             }

            axios.post(url, formData, config).then(response => {
                this.$store.state.transacao.status = 'sucesso';
                this.$store.state.transacao.mensagem = 'Atualizado com sucesso'
                AtualizarImagem.value = '';
                this.carregarLista();
            })
            .catch(errors => {
                this.$store.state.transacao.status = 'erro';
                this.$store.state.transacao.mensagem = errors.response.data.message;
                this.$store.state.transacao.dados = errors.response.data.errors;
            });
        },
        remover(){
            let confirmacao = confirm('Tem certerza que deseja remover esse regiostro?');
            if(!confirmacao){
                return false;
            } 
       
            let url = this.urlBase + '/' + this.$store.state.item.id
            let formData = new FormData();
            formData.append('_method', 'delete');
          
            axios.post(url, formData).then(response=> {
                this.$store.state.transacao.status = 'sucesso';
                this.$store.state.transacao.mensagem = response.data.msg;
                this.carregarLista();
            }).catch(errors =>{
                 console.log(errors.response);
                 this.$store.state.transacao.status = 'erro';
                 this.$store.state.transacao.mensagem = errors.response.data.erro;
            });
        },
        pesquisar(){
            let filtro = '';

            for(let chave in this.busca){
                if(this.busca[chave]){
                    if(filtro !== ''){
                        filtro += ';'
                    }
                    filtro += chave + ':like:' + this.busca[chave]
                }
            }

            this.urlPaginacao = 'page=1';
            this.urlFiltro = filtro ? '&filtro=' + filtro : '';
            this.carregarLista();
        },
        paginacao(l){
            if(l.url){
             //this.urlBase = l.url;
             this.urlPaginacao = l.url.split('?')[1];
             this.carregarLista();
            }
         
        },
        carregarLista(){
            let url = this.urlBase+ '?' + this.urlPaginacao + this.urlFiltro;
            axios.get(url)
            .then(response => {
               this.marcas= response.data
            }).catch(errors => {
                console.log(errors)
            });
        },
        carregarImage(e){
            this.arquivoImagem = e.target.files
        },
        salvar(){
            let formData = new FormData();
            formData.append('nome', this.nomeMarca);
            formData.append('imagem', this.arquivoImagem[0]);

            let config = {
                headers: {
                    'Content-Type': 'multipart/form-data'
                }
            }
            axios.post(this.urlBase, formData, config)
            .then(response => {
               this.transacaoDetalhes = {
                 mensagem:'ID do registro ' + response.data.id
               }
               this.transacaoStatus = 'Adicionado';
            })
            .catch(errors => {
                 this.transacaoStatus = 'Cancelado';
                 this.transacaoDetalhes = {
                    mensagem: errors.response.data.message,
                    dados: errors.response.data.errors
                 }
                 
            })
        }
    },

    mounted(){
        this.carregarLista();
    },
}
</script>