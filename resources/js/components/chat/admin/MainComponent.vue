<script>
    import Event from './scripts/adminMessages.js';

    import { mapState, mapActions } from 'vuex'

    export default {

      created() {

         this.makeAuth(this.isAuth).then(() => {

          this.setUser(this.user);

         });

         // this.setChannelUser(this.channeluser);
         // Event.$logger('info','Admin Main component Created.');

      },

      mounted() {

        Event.$on('send_message', (message,room) => {
          // this.setMessageList(message);
          this.$socket.emit('sendMessage',{room:room,message:message},(err) => {console.log(err)});
        });

        Event.$on('added_message', (data) => {
          if (data.history != undefined && data.history == false) {
            this.setMessageList(data.message);
          }else{
            this.putMessageList(data.message);              
          }
        });

        Event.$on('onlineUser', (data) => {
          console.log(data);
          if (data.userObj != undefined) {  
            var dataUser = data.userObj;
            if (data.status == 'single') {
              var user = {id : dataUser.id,name : dataUser.name,avatar:dataUser.avatar,room:dataUser.room};
              this.setUserList(user);
            }else{
              this.setUserGroupList(dataUser);
            }       
          }
        });

        Event.$on('offlineUser', (data) => {
          if (data.userObj != undefined) {                
            var user = {id : data.userObj.id,name : data.userObj.name,avatar:data.userObj.avatar,room:data.userObj.socket_room_name};
            this.unsetUserList(user);
          }
        });

        this.sockets.subscribe('receiveMessage', function(data) {
          var message = data.message;
          // if (message.user) {          
            // if (message.user.id == this.user.id) {
            //   message.type = 'sent';
            // }else{
            //   message.type = 'receive';            
            // }
              Event.$emit('added_message', data);
          // }
        });

        this.sockets.subscribe('onlineuser', function(data) {
          Event.$emit('onlineUser', data); 
        });

        this.sockets.subscribe('offlineuser', function(data) {
          Event.$emit('offlineUser', data); 
        });
      },

      computed : {

        ...mapState({

          userList : state => state.userList.userList,
          
          authUser : state => state.userConf.authUser,
          
          authState: state => state.userConf.authState

        })           
      },

      props: {
       isAuth: {
         type: Boolean,
         default: false
       },
       user: {
         type: [Object, Array],
         required: false,
       },
       channel: {
         type: String,
         required: false,
         default: 'none'
       },
       channeluser: {
         type: [Object, Array],
         required: false,
       }
      },

      sockets: {
         connect: function () {
           Event.$logger('info','socket connected');
           var user = {id : this.user.id,name : this.user.name,avatar:this.user.avatar,room:this.user.socket_room_name};
           this.$socket.emit('connectRoom',{userObj:user,room:'admin'},(err) => {console.log(err)});
         },
      },

      methods : {
        ...mapActions([
            'setUserGroupList',
            'setUserList',
            'unsetUserList',
            'setChannelUser',
            'setMessageList',
            'putMessageList',
            'setUser',
            'makeAuth'
          ]), 
      },

    }

$(function(){ 
  $(document).on('keyup','input[name="userSearch"]' ,function(event){
    var value = $(this).val().toLowerCase();
    $(".userList").filter(function() {
      $(this).toggle($(this).find(".userListName").text().toLowerCase().indexOf(value) > -1)
    });
  });
});
</script>


<template>
  <div class="container-fluid" id="frame">
      <div class="d-md-flex h-100">
        <div class="user-list-backdrop d-none"></div>
        <div class="user-list ">
          <div>
              <form class="search-user">
                  <input type="text" name="userSearch" placeholder="Search User" class="form-control">
                  <button class="btn btn-default">
                      <i class="fa fa-search"></i>
                  </button>
              </form>
          </div>
          <div class="contact-profile" id="example-1">
            <div class="p-2 text-right user-list-close d-lg-none">
                <i class="fa fa-times-circle"></i>
            </div>
            <admin-chat-user-component 
            v-for='(onlineuser, index) in userList' :key='onlineuser._id' :onlineuser="onlineuser" :activeMenu="onlineuser.selected"
            v-bind="$props">
            </admin-chat-user-component>
          </div>
        </div>
          <admin-chat-body-component v-bind="$props"></admin-chat-body-component>
      </div>
  </div>
</template>

<style lang="scss">
  @import '../../../../css/adminChat.css';
</style>