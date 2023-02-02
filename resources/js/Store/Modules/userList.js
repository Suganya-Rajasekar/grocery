const getDefaultState = () => {

	return {

		userList : [],

		activeUser : {},

		channelUser : {}

	}
}

const state = getDefaultState()

const mutations = {

	RESET_USER_LIST_STATE (state) {

	  Object.assign(state, getDefaultState())
	  
	},

	RESET_USER_LIST (state) {

	  Object.assign(state.userList, [])
	  
	},

	SET_USER_GROUP_LIST (state,userGroup){

		// Object.assign(state.userList,userGroup);
		state.userList = userGroup;

	},

	SET_USER_LIST (state,userList) {

		state.userList.push(userList);

	},

	UNSET_USER_LIST(state,userList){

		state.userList.pop(userList);
	},

	MAKE_ACTIVE_USER(state,user){

		state.activeUser = user;

		// const item = state.userList.find(check => check.name === user.name);

		// Object.assign(item, user);
	},


	SET_CHANNEL_USER (state,channelUser) {

		state.channelUser = channelUser;

	},

	UNSET_CHANNEL_USER(state){

		state.channelUser = {};
	},

	RESET_CHANNEL_USER(state,channelUser){

		state.channelUser = {};

		state.channelUser = channelUser;
	},


}

const actions = {

	getDefaultChannelUser: async ({commit})=>{

		var userObject = {
							name  : '',
							id    : '',
							avatar: '',
							room  : ''
						};

		return userObject;

	},

	getDefaultUser: async ({commit})=>{

		var userObject = {
							name  : '',
							id    : '',
							avatar: '',
							room  : ''
						};

		return userObject;
		
	},

	getDefaultSelectedUser: async ({commit})=>{

		var userObject = {
							name  : '',
							id    : '',
							avatar: '',
							room  : ''
						};

		return userObject;
		
	},

	resetUserListState ({ commit }) {

	   commit('RESET_USER_LIST_STATE')

	},

	resetUserList ({ commit }) {

	   commit('RESET_USER_LIST')

	},

	getCheckPresend: async ({commit},user) => {

		let liveUser = state.userList;

		let addUserBool = liveUser.some(el => el.id === userList.id);

		console.log(addUserBool);

		return 'addUserBool';

	},

	setUserGroupList: async ({dispatch,commit},userGroup) => {

		commit("SET_USER_GROUP_LIST", userGroup);

	},

	setUserList: async ({dispatch,commit},userList) =>{

		let user = dispatch('getDefaultUser');

		// let checkUserBool = dispatch('getCheckPresend',userList);

		let liveUser = state.userList;

		let checkUserBool = liveUser.some(el => el.id === userList.id);

		if (!checkUserBool) {

			user.id     = userList.id;
			user.name   = userList.name;
			user.avatar = userList.avatar;
			user.room   = userList.room;

			commit("SET_USER_LIST", user);

		}


	},

	unsetUserList: async ({dispatch,commit},userList) => {
		
		let user = dispatch('getDefaultUser');

		// let checkUserBool = dispatch('getCheckPresend',userList);

		let liveUser = state.userList;

		let checkUserBool = liveUser.some(el => el.id === userList.id);


		if (checkUserBool) {

			user.id     = userList.id;
			user.name   = userList.name;
			user.avatar = userList.avatar;
			user.room   = userList.room;

			commit("UNSET_USER_LIST", user);
		}
	},

	makeActiveUser: async ({dispatch,commit},userList) => {
		
		let user = dispatch('getDefaultSelectedUser');

		// let checkUserBool = dispatch('getCheckPresend',userList);

		let liveUser = state.userList;

		let checkUserBool = liveUser.some(el => el.id === userList.id);


		if (checkUserBool) {

			user.id     = userList.id;
			user.name   = userList.name;
			user.avatar = userList.avatar;
			user.room   = userList.room;

			commit("MAKE_ACTIVE_USER", user);
		}
	},

	setChannelUser: async ({dispatch,commit},channelUser) =>{

		let user = dispatch('getDefaultChannelUser');

		user.id     = channelUser.id;
		user.name   = channelUser.name;
		user.avatar = channelUser.avatar;
		user.room   = channelUser.room;


		commit("SET_CHANNEL_USER", user);

	},

	unsetChannelUser: async ({dispatch,commit},channelUser) => {
		
		let user = dispatch('getDefaultChannelUser');

		user.id     = channelUser.id;
		user.name   = channelUser.name;
		user.avatar = channelUser.avatar;
		user.room   = channelUser.room;


		commit("UNSET_CHANNEL_USER", user);

	},

	resetChannelUser: async ({dispatch,commit},channelUser) => {
		
		let user = dispatch('getDefaultChannelUser');

		user.id     = channelUser.id;
		user.name   = channelUser.name;
		user.avatar = channelUser.avatar;
		user.room   = channelUser.room;

		commit("RESET_CHANNEL_USER", user);

	}
}


const getters =  {

}


export default {

	state, mutations, actions, getters

}