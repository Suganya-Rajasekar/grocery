import Vue from 'vue';

// import store from '../../../../Store';

import connection from '../../../../plugins/SocketInstance';

import VueSocketio from 'vue-socket.io';

import Logger from '../../../../plugins/Logger';

import {Config}  from '../../../../config';


export default new Vue();

// const store = createStore({
//     modules : {
//         userStore
//     },
//     strict : false
// });

const vueSocket =  new VueSocketio({
                    debug: false,
                    connection: connection,
                    vuex: {

                        actionPrefix: 'SOCKET_',
                        mutationPrefix: 'SOCKET_'
                    },
                    components: {
                        Config : new Config()
                    },
                   // options: { 
                   //      transports: ['websocket', 'polling']
                   //  } //path: "/private/socketchat/version2/public" [Optional options]

                });

Vue.use(vueSocket);

// Vue.use(store);

Vue.use(Logger, {loggin: true});

Vue.prototype.$Config = new Config();


