// require('./bootstrap');

window.Vue = require('vue').default;

window.axios = require('axios');

/*Client Chat Components*/

Vue.component('client-chat-component', require('./components/chat/client/MainComponent.vue').default);
Vue.component('client-chat-input-component', require('./components/chat/client/InputComponent.vue').default);
Vue.component('client-chat-message-component', require('./components/chat/client/MessageComponent.vue').default);

Vue.component('admin-chat-component', require('./components/chat/admin/MainComponent.vue').default);
Vue.component('admin-chat-input-component', require('./components/chat/admin/InputComponent.vue').default);
Vue.component('admin-chat-message-component', require('./components/chat/admin/MessageComponent.vue').default);
Vue.component('admin-chat-user-component', require('./components/chat/admin/UserComponent.vue').default);
Vue.component('admin-chat-body-component', require('./components/chat/admin/ChatComponent.vue').default);

import store from './Store';


const app = new Vue({
    el: '#chat-bot-main',
    store: store
});

