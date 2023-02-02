<script>

  import { mapState, mapActions } from 'vuex'


  export default {
      data: () =>({
             clicked: false,
       }),

      props: {
          isAuth: {
              type: Boolean,
              default: false
          },
          user: {
              type: [Object, Array],
              required: false,
          }

      },

      created() {
         // this.setChannelUser(this.channeluser);
      },

       computed : {

           ...mapState({
             
             channelUser: state => state.userList.channelUser,

             messageList: state => state.messageList.messageList,

             usersTypeResponse: state => state.messageList.usersTypeResponse,


           })           
       },
       methods : {
          ...mapActions([
              'setMessageList',
              'unsetMessageList',
              'setChannelUser',
              'setTypeResponseList',
              'unsetTypeReponseList'
          ]), 
       }
     }

</script>

<template>
  <div class="w-100 pl-lg-3 message-space" v-if="channelUser.name">
    <div class="chat-content ">
      <div class="d-flex align-items-center current-user-tab">
        <div class="user-list-menu d-lg-none">
          <i class="fa fa-bars"></i>
        </div>
        <div class="d-flex current-user pl-lg-0 pl-3 align-items-center">
          <img :src="channelUser.avatar" alt="" />
          <p class="m-0 pl-2">{{ channelUser.name }}</p>
        </div>
      </div>
      <div class="messages">
        <ul class="m-0">
          <admin-chat-message-component 
          v-for='(message, index) in messageList' 
          :key='messageList._id' 
          :message="message"
          :channelUser="channelUser"
          >
        </admin-chat-message-component>
      </ul>
      <ul class="m-0">    
        <template v-for="typer in usersTypeResponse">
          <li class="typeLoader msg" v-if="user.id != typer.id">
            <span>{{ typer.user }}</span>
            <div class="typing typing-1"></div>
            <div class="typing typing-2"></div>
            <div class="typing typing-3"></div>
          </li>
        </template>
      </ul>
    </div>
    <admin-chat-input-component v-bind="$props"></admin-chat-input-component>
  </div>
  </div>
  <div class="w-100 pl-lg-3 message-space" v-else>
    <div class="chat-content ">
      <div class="logintochat text-center">
        <i class="fa fa-comment"></i>
        <h4 class="py-3">Communicate to our Users !</h4>
      </div>
    </div>    
  </div>
</template>