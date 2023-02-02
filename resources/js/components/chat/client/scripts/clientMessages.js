import Vue from 'vue';

import connection from '../../../../plugins/SocketInstance';

import VueSocketio from 'vue-socket.io';

import Logger from '../../../../plugins/Logger';


import {Config}  from '../../../../config';

export default new Vue();

Vue.use(new VueSocketio({
    debug: true,
    connection: connection,
    vuex: {
        
        actionPrefix: 'SOCKET_',
        mutationPrefix: 'SOCKET_'
    },
    components: {
    	Config : new Config()
    },
    // options: { 
    //     transports: ['websocket', 'polling']
    // } //path: "/private/socketchat/version2/public" [Optional options]
}));

Vue.use(Logger, {loggin: true});

Vue.prototype.$Config = new Config();
