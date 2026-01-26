import Vue from 'vue/dist/vue.esm.js';
import './bootstrap'; // importa axios, lodash e bootstrap JS
//importando o vuex 

import Vuex from 'vuex'


Vue.use(Vuex);

const store = new Vuex.Store({
    state: {
        item: {},
        transacao:{
            status: '', mensagem: '',
            dados: '',
        }
    }
})

import Teste from './components/Teste.vue';
import Example from './components/ExampleComponent.vue';
import Home from './components/Home.vue';
import Marcas from './components/Marcas.vue';
import Modelos from './components/Modelos.vue';
import Carros from './components/Carros.vue';
import Locacoes from './components/Locacoes.vue';
import Clientes from './components/Clientes.vue';
import InputContainer from './components/InputContainer.vue';
import Table from './components/Table.vue';
import Card from './components/Card.vue';
import Modal from './components/Modal.vue';
import Alert from './components/Alert.vue';
import Paginate from './components/Paginate.vue';


Vue.filter('formatadaDataTempoGlobal', function(d){
    if(!d || d === '-') return '-';
    
    console.log('📅 Data recebida:', d); // Para debug
    
    try {
        // CASO 1: Data já formatada (DD/MM/YYYY)
        if (typeof d === 'string' && d.match(/^\d{2}\/\d{2}\/\d{4}$/)) {
            return d;
        }
        
        // CASO 2: Formato SQL datetime 'YYYY-MM-DD HH:mm:ss' - SEU CASO!
        if (typeof d === 'string' && d.match(/^\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}$/)) {
            // Extrai apenas a parte da data
            const dataPart = d.split(' ')[0]; // '2025-12-26'
            const partes = dataPart.split('-');
            return `${partes[2]}/${partes[1]}/${partes[0]}`; // '26/12/2025'
        }
        
        // CASO 3: Formato ISO (YYYY-MM-DD)
        if (typeof d === 'string' && d.match(/^\d{4}-\d{2}-\d{2}$/)) {
            const partes = d.split('-');
            return `${partes[2]}/${partes[1]}/${partes[0]}`;
        }
        
        // CASO 4: Formato ISO com tempo (YYYY-MM-DDTHH:mm:ss)
        if (typeof d === 'string' && d.includes('T')) {
            const partes = d.split('T');
            let data = partes[0];
            
            data = data.split('-');
            return `${data[2]}/${data[1]}/${data[0]}`;
        }
        
        // CASO 5: Tenta converter para Date
        const dataObj = new Date(d);
        if (!isNaN(dataObj.getTime())) {
            const dia = String(dataObj.getDate()).padStart(2, '0');
            const mes = String(dataObj.getMonth() + 1).padStart(2, '0');
            const ano = dataObj.getFullYear();
            return `${dia}/${mes}/${ano}`;
        }
        
        // CASO 6: Retorna o valor original
        console.warn('⚠️ Formato de data não reconhecido:', d);
        return d;
        
    } catch (error) {
        console.error('❌ Erro ao formatar data:', d, error);
        return d;
    }
});

Vue.filter('formatarDataSQL', function(d){
    if(!d || d === '-') return '-';
    
    // Se já está no formato correto
    if (typeof d === 'string' && d.match(/^\d{2}\/\d{2}\/\d{4}$/)) {
        return d;
    }
    
    // Para formato SQL datetime 'YYYY-MM-DD HH:mm:ss'
    if (typeof d === 'string' && d.match(/^\d{4}-\d{2}-\d{2}/)) {
        // Pega apenas a parte da data (antes do espaço)
        const dataPart = d.split(' ')[0];
        const [ano, mes, dia] = dataPart.split('-');
        return `${dia}/${mes}/${ano}`;
    }
    
    // Tenta converter qualquer outra coisa
    try {
        const data = new Date(d);
        if (!isNaN(data.getTime())) {
            const dia = String(data.getDate()).padStart(2, '0');
            const mes = String(data.getMonth() + 1).padStart(2, '0');
            const ano = data.getFullYear();
            return `${dia}/${mes}/${ano}`;
        }
    } catch (e) {
        // Ignora erro
    }
    
    return d;
});

new Vue({
    el: '#app',
    store,
    components: {
        TesteComponent: Teste,
        ExampleComponent: Example,
        HomeComponent: Home,
        MarcasComponent: Marcas,
        InputContainerComponent: InputContainer,
        TableComponent: Table,
        CardComponent: Card,
        ModalComponent: Modal,
        AlertComponent: Alert,
        PaginateComponent: Paginate,
        ModelosComponent: Modelos,
        CarrosComponent: Carros,
        LocacoesComponent: Locacoes,
        ClientesComponent: Clientes,
    }
});
