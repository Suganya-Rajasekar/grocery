import { Config } from '../../config.js';
import $socket from '../../plugins/SocketInstance';

const getDefaultState = () => {

	return {

		messageList: [],

		usersTypeResponse: []

	}
}

const state = getDefaultState()

const mutations = {

	RESET_MESSAGE_LIST_STATE (state) {

	  Object.assign(state, getDefaultState())
	  
	},

	PUT_MESSAGE_LIST (state,messageList) {

		state.messageList = messageList;

	},

	SET_MESSAGE_LIST (state,messageList) {

		state.messageList.push(messageList);

	},

	UNSET_MESSAGE_LIST(state,messageList){

		state.messageList.pop(messageList);
	},

	SET_TYPE_RESPONSE_LIST (state,userTypeResponse) {

		state.usersTypeResponse.push(userTypeResponse);

	},

	UNSET_TYPE_RESPONSE_LIST(state,userTypeResponse){

		state.usersTypeResponse.pop(userTypeResponse);
	}
	

}

const actions = {

	resetMessageListState ({ commit }) {

	   commit('RESET_MESSAGE_LIST_STATE')

	},

	getDefaultObject: async ({commit})=>{

		var messageObject = {
								id: Date.now(),
								body: '',
								selfMessage: true,
								type: 'sent',
								user: {
									name: '',
									id: '',
									avatar: '',
								}
							};

		return messageObject;
	},
	getDefaultTypeResponseObject: async ({commit})=>{

		var typeResponse = {
								'id'   : '',
								'name' : '',
								'status' : false
							};

		return typeResponse;
	},


	getMessageList: async ({commit},user) => {

		// commit("PUT_MESSAGE_LIST", messages);


		// axios.get(Config().getMessage).then((response) => {

		// 	commit("PUT_MESSAGE_LIST", response.data);

		// });

       this._vm.$socket.emit('historyMessage',{room : user.room},(err) => {console.log(err)});     


	},

	putMessageList: async ({commit},messages) => {

			commit("PUT_MESSAGE_LIST", messages);

	},


	setMessageList: async ({commit,dispatch},messageList) =>{

		let messageDefault = dispatch('getDefaultObject');

		messageList.forEach((entry) => {

			let message = messageDefault;

			message.id          =  entry.id;
			message.body        =  entry.body;
			message.selfMessage =  entry.selfMessage;
			message.type        =  entry.type;

			message.selfMessage =  true;
			message.type        =  'sent';

			let user = {};
			user.name    =  entry.user.name;
			user.id      =  entry.user.id;
			user.avatar  =  entry.user.avatar;
			message.user =  user;

			commit("SET_MESSAGE_LIST", message);

		});

	},

	unsetMessageList: async ({commit,dispatch},messageList) => {
		
		let message = dispatch('getDefaultObject');

		message.id          =  messageList.id;
		message.body        =  messageList.body;
		message.selfMessage =  messageList.selfMessage;
		message.type        =  messageList.type;

		let user = {};
		user.name    =  messageList.user;
		user.id      =  messageList.user.id;
		user.avatar  =  messageList.user.avatar;
		message.user =  user;

		commit("UNSET_MESSAGE_LIST", message);
	},

	setTypeResponseList: async ({commit,dispatch},typeReponse) =>{

		let typeReponseDefault = dispatch('getDefaultTypeResponseObject');

		typeReponseDefault.user = typeReponse.name;	

		typeReponseDefault.id = typeReponse.id;	

		typeReponseDefault.status = true;	

		console.log(typeReponseDefault);	

		commit("SET_TYPE_RESPONSE_LIST", typeReponseDefault);

	},

	unsetTypeReponseList: async ({commit,dispatch},typeReponse) => {
		
		let typeReponseDefault = dispatch('getDefaultTypeResponseObject');

		typeReponseDefault.user = typeReponse.name;	

		typeReponseDefault.id = typeReponse.id;	
			
		typeReponseDefault.status = true;

		commit("UNSET_TYPE_RESPONSE_LIST", typeReponseDefault);
	}
}


export default {

	state, mutations, actions

}