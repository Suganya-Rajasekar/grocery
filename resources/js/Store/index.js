import Vue from 'vue';
import Vuex from 'vuex';

Vue.use(Vuex);

import userList from './Modules/userList';

import messageList from './Modules/messageList';

import userConf from './Modules/userConf';

const store = new Vuex.Store({
				// state: {

				// },
				// mutation: {

				// },
				// actions: {

				// },
				modules : {
					userList,
					messageList,
					userConf	
				},
				strict : false
			});

export default store;