const state = {

	authUser : {},

	authState : false

}

const mutations = {

	SET_USER (state,user) {

		state.authUser = user;

	},

	UNSET_USER(state){

		state.authUser = {};
	},


	SET_AUTH (state,cond) {

		state.authState = cond;

	}


}

const actions = {

	getDefaultUser: async ({commit})=>{

		var userObject = {
							name  : '',
							id    : '',
							avatar: '',
							room  : ''
						};

		return userObject;
		
	},

	setUser: async ({dispatch,commit},userDetail) =>{

		if (state.authState) {

			let user = dispatch('getDefaultUser');

			user.id     = userDetail.id;
			user.name   = userDetail.name;
			user.avatar = userDetail.avatar;
			user.room   = userDetail.socket_room_name;

			commit("SET_USER", user);

		}

		// commit();


	},

	unsetUserList: async ({dispatch,commit},userDetail) => {
		
		let user = dispatch('getDefaultUser');

		user.id     = userDetail.id;
		user.name   = userDetail.name;
		user.avatar = userDetail.avatar;
		user.room   = userDetail.socket_room_name;


		commit("UNSET_USER", user);

	},

	makeAuth: async ({dispatch,commit},cond) => {
		
		let user = dispatch('getDefaultSelectedUser');

		var setCond = false;

		if (cond === true) setCond = true;

		commit("SET_AUTH", setCond);

	},
}


const getters =  {

}


export default {

	state, mutations, actions, getters

}