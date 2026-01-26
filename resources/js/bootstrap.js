import _ from 'lodash';
window._ = _;

import axios from 'axios';
window.axios = axios;
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

// Bootstrap JS
import * as bootstrap from 'bootstrap';

window.bootstrap = bootstrap;

//intercepetar os request da aplicação
axios.interceptors.request.use(
    config => {

        config.headers['Accept'] = 'application/json';

        let tokenCookie = document.cookie
            .split(';')
            .find(indice => indice.trim().startsWith('token='));

        if (tokenCookie) {
            let token = tokenCookie.split('=')[1];
            config.headers.Authorization = token;
        }

        return config;
    },
    error => {
        return Promise.reject(error);
    }
);


//intercepetar os reponses da aplicação

axios.interceptors.response.use(
    response =>{
        console.log('interceptando o resposta antes da aplicação ', response);
        return response
    },

    error => {
        console.log("Erro na resposta", error);
        if(error.response.status == 401 && error.response.data.message == 'Token has expired'){
            axios.post('http://localhost:8000/api/refresh')
            .then(response =>{
                console.log('Refresh com sucesso', response);
                document.cookie = 'token='+response.data.token;
                window.location.reload(); 

            });
        }
         return Promise.reject(error.response)
    }
)