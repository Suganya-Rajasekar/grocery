<script>
    import { mapState, mapActions } from 'vuex'

    export default {
        props: ['onlineuser'],
        methods: {
            openChat(user) {
              
              this.$emit("open-chat", user);

              this.resetChannelUser(user);

              this.makeActiveUser(user);

              this.resetMessageListState().then(() => {

                // this.getMessageList(user); 



              }); 


              if (user && user.room) {
                
                this.$socket.emit('connectRoom',{userObj:this.authUser,room:user.room},(err) => {console.log(err)}); 
                this.$socket.emit('historyMessage',{room : user.room},(err) => {console.log(err)});     


              }
            },
            ...mapActions([
                'resetChannelUser',
                'makeActiveUser',
                'getMessageList',
                'resetMessageListState'                
            ])
        },
        computed : {

          ...mapState({

            activeUser: state => state.userList.activeUser,
            
            authUser  : state => state.userConf.authUser,
            
            authState : state => state.userConf.authState

          })           
        },
    }
</script>

<template>
    <div v-if="onlineuser !== undefined && onlineuser.avatar !== undefined" class="userList" @click="openChat(onlineuser)">        
        <div class="d-flex py-2 user px-2 align-items-center" :class="(activeUser != undefined  && activeUser.name != undefined && activeUser.name == onlineuser.name) ? 'selected' : 'unselected'">
          <div class="userlistmode position-relative">
            <span class="usermode active"></span>
           <img :src="onlineuser.avatar" alt="" />
          </div>
            <p class="m-0 pl-1 userListName">{{ onlineuser.name }}</p> 
        </div>
    </div>
</template>