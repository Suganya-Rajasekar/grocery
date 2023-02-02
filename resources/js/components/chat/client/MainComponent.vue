<script>
  import Event from './scripts/clientMessages.js';
  import ClientInputComponent from './InputComponent.vue';

  import { mapState, mapActions } from 'vuex'

  export default {
      created() {
          document.addEventListener('beforeunload', this.closeWindow);

          this.makeAuth(this.isAuth).then(() => {

            this.setUser(this.user);

          });
        },
      data: function (){
         return  {
             clicked: false,
             messages: [],
             is_auth:this.isAuth,
             is_connect: false
         }
       },
       components: {
         ClientInputComponent
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
        }
      },
      sockets: {
        connect: function () {
          Event.$logger('info','socket connected');
        },
      },
       sockets: {
         connect: function () {
           //Event.$logger('info','socket connected '+this.user.socket_room_name);
         },
       },
      methods : {
          expand: function (event) {
              if(this.clicked){
                  this.$set(this.$data, 'clicked', false);
              }
              else{
                  this.$set(this.$data, 'clicked', true);
              }
          },

          openChat: function(){
            var user = {id : this.user.id,name : this.user.name,avatar:this.user.avatar,room:this.user.socket_room_name};
            this.$socket.emit(
              'connectRoom',
              {userObj:user,room:this.user.socket_room_name},
              (err) => {
                console.log(err)
              }
            );
            // this.getMessageList(user);   

            this.$socket.emit(
              'historyMessage',
              {room : user.room},
              (err) => {
                console.log(err)
              }
            );
            this.sockets.subscribe('receiveMessage', function(data) {
              var message = data.message;
              // if (message.user) {          
              //   if (message.user.id == this.user.id) {
              //     message.type = 'sent';
              //   }else{
              //     message.type = 'receive';            
              //   }
                  Event.$emit('added_message', data);
              // }
            });
            this.$set(this.$data, 'is_connect', true);
          },

          currentDate() {
              const current = new Date();
              const date = `${current.getDate()}/${current.getMonth()+1}/${current.getFullYear()} ${current.getHours()}:${current.getMinutes()}:${current.getSeconds()}`;
              return date;
          },

          closeWindow() {
            this.$socket.emit('disconnect');
          },

          ...mapActions([
              'setUser',
              'makeAuth',
              'getMessageList',
              'putMessageList',
              'setMessageList',
              'unsetMessageList',
            ]), 
      },
      computed : {

          ...mapState({

            messageList: state => state.messageList.messageList,

            usersTypeResponse: state => state.messageList.usersTypeResponse,


          })           
      },
      mounted() {
          Event.$logger('info','Client Main component mounted.');

          // axios.get(Event.$Config.getMessage).then((response) => {
          //     this.messages = response.data;
          // });

          Event.$on('send_message', (message,room) => {
             this.$socket.emit('sendMessage',{room:room,message:message},(err) => {console.log(err)});
          });

          Event.$on('added_message', (data) => {
            if (data.history != undefined && data.history == false) {
              this.setMessageList(data.message);
            }else{
              this.putMessageList(data.message);              
            }
          });
          
          var subscribeName = this.channel;
      }
  }
</script>

<template>
    <div id="chat-bot" :class="[ clicked ? 'expanded' : '' ]">
        
        <div class="messenger br10">

            <div class="timestamp">
              {{currentDate()}}
              <span class="chat-close" v-on:click="expand">
                <i class="fas fa-times"></i>
              </span>
            </div>
            <div  v-if="is_auth" :class="[is_connect ? '' : 'h-100']">   
              <div v-if="is_connect">              
                <div class="chatroom" ref="message">
                  <div class="msg msg-left">
                    <div class="bubble arrowaddd">
                      <h6 class="name">Knosh Support</h6>
                      Hi
                    </div>
                  </div>
                  <client-chat-message-component 
                 v-for='(message, index) in messageList' 
                          :key='messageList._id' 
                          :message="message"
                          :user="user"
                          >
                  </client-chat-message-component>
                </div>
                <template v-for="typer in usersTypeResponse">
                  <div class="typeLoader client msg" v-if="user.id != typer.id">
                    <span>{{ typer.user }}</span>
                    <div class="typing typing-1"></div>
                    <div class="typing typing-2"></div>
                    <div class="typing typing-3"></div>
                  </div>
                </template>
                <client-chat-input-component v-bind="$props">
                </client-chat-input-component>
              </div>
              <div v-else class="h-100">
                <div class="starttochat text-center">
                  <i class="far fa-smile"></i>
                  <h4 class="py-3">
                    <button class="btn btn-default" @click="openChat()">Ask your Queries</button>
                  </h4>
                </div>
              </div>         
            </div>
            <div v-else class="h-100">
              <div class="logintochat text-center">
                <i class="fas fa-user-lock"></i>
                <h4 class="py-3">You need to <a href="login">login</a></h4>
              </div>
            </div>
        </div>
        <div class="icon" v-on:click="expand">
            <div class="user">
                <i class="bi bi-person-circle me-2"></i>
                Chat
            </div>
            <i class="fas fa-comments"></i>
        </div>
    </div>
</template>

<style lang="scss">
  @import '../../../../css/clientChat.css';
</style>