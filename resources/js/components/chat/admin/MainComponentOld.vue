<template>
    <div class="col-md-12" id="frame">
      <div class="content">
        <div class="contact-profile">
          <img :src="channeluser.avatar" alt="" />
          <p>{{ channeluser.name }}</p>
        </div>
        <div class="messages">
          <ul>
            <admin-chat-message-component 
            v-for="message in messages" 
            :key="message.id" 
            :message="message">
                
            </admin-chat-message-component>
          </ul>
        </div>
        <admin-chat-input-component v-bind="$props"></admin-chat-input-component>
      </div>
    </div>
</template>

<script>
    import Event from './scripts/adminMessages.js';
    import AdminInputComponent from './InputComponent.vue';

    export default {
        data: () =>({
               clicked: false,
               messages: [],
         }),
         components: {
           AdminInputComponent
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
             this.sockets.emit("connectRoom", this.user,this.channel)
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
            currentDate() {
                const current = new Date();
                const date = `${current.getDate()}/${current.getMonth()+1}/${current.getFullYear()} ${current.getHours()}:${current.getMinutes()}:${current.getSeconds()}`;
                return date;
            }
        },
        mounted() {
            console.log(this);
            Event.$logger('info','Admin Main component mounted.');

            axios.get(Event.$Config.getMessage).then((response) => {
                this.messages = response.data;
            });

            Event.$on('send_message', (message) => {
                this.messages.push(message);
            });
            
            Event.$on('added_message', (message) => {
                this.messages.push(message);
            });

            var subscribeName = this.channel;
            // this.sockets.subscribe(''+subscribeName+'', function(data) {
            this.sockets.subscribe('messages', function(data) {
             var message = JSON.parse(data);
             if (message.user.id == this.user.id) {
              message.type = 'sent';
              Event.$emit('added_message', message);
             }else{
              message.type = 'receive';
              Event.$emit('send_message', message);              
             }
             Event.$logger('warn','Message:',message);
            });

        }
    }
</script>
<style type="text/css">
  
  #frame {
    width: 100%;
    /*min-width: 360px;
    max-width: 1000px;*/
    height: 92vh;
    min-height: 300px;
    max-height: 720px;
    background: #E6EAEA;
  }
  @media screen and (max-width: 360px) {
    #frame {
      width: 100%;
      height: 100vh;
    }
  }
  #frame .content {
    float: right;
    width: 100%;
    height: 100%;
    overflow: hidden;
    position: relative;
  }
  #frame .content .contact-profile {
    width: 100%;
    height: 60px;
    line-height: 60px;
    background: #f5f5f5;
  }
  #frame .content .contact-profile img {
    width: 40px;
    border-radius: 50%;
    float: left;
    margin: 9px 12px 0 9px;
  }
  #frame .content .contact-profile p {
    float: left;
  }
  #frame .content .contact-profile .social-media {
    float: right;
  }
  #frame .content .contact-profile .social-media i {
    margin-left: 14px;
    cursor: pointer;
  }
  #frame .content .contact-profile .social-media i:nth-last-child(1) {
    margin-right: 20px;
  }
  #frame .content .contact-profile .social-media i:hover {
    color: #435f7a;
  }
  #frame .content .messages {
    height: auto;
    min-height: calc(100% - 93px);
    max-height: calc(100% - 93px);
    overflow-y: scroll;
    overflow-x: hidden;
  }
  @media screen and (max-width: 735px) {
    #frame .content .messages {
      max-height: calc(100% - 105px);
    }
  }
  #frame .content .messages::-webkit-scrollbar {
    width: 8px;
    background: transparent;
  }
  #frame .content .messages::-webkit-scrollbar-thumb {
    background-color: rgba(0, 0, 0, 0.3);
  }
  #frame .content .messages ul li {
    display: inline-block;
    clear: both;
    float: left;
    margin: 15px 15px 5px 15px;
    width: calc(100% - 25px);
    font-size: 0.9em;
  }
  #frame .content .messages ul li:nth-last-child(1) {
    margin-bottom: 20px;
  }
  #frame .content .messages ul li.sent img {
    margin: 6px 8px 0 0;
  }
  #frame .content .messages ul li.sent p {
    background: #435f7a;
    color: #f5f5f5;
  }
  #frame .content .messages ul li.replies img {
    float: right;
    margin: 6px 0 0 8px;
  }
  #frame .content .messages ul li.replies p {
    background: #f5f5f5;
    float: right;
  }
  #frame .content .messages ul li img {
    width: 22px;
    border-radius: 50%;
    float: left;
  }
  #frame .content .messages ul li p {
    display: inline-block;
    padding: 10px 15px;
    border-radius: 20px;
    max-width: 205px;
    line-height: 130%;
  }
  @media screen and (min-width: 735px) {
    #frame .content .messages ul li p {
      max-width: 300px;
      min-width: 50px;
    }
  }
  #frame .content .message-input {
    bottom: 0;
    width: 100%;
    z-index: 99;
  }
  #frame .content .message-input .wrap {
    position: relative;
  }
  #frame .content .message-input .wrap input {
    font-family: "proxima-nova",  "Source Sans Pro", sans-serif;
    float: left;
    border: none;
    width: calc(100% - 90px);
    padding: 11px 32px 10px 8px;
    font-size: 0.8em;
    color: #32465a;
  }
  @media screen and (max-width: 735px) {
    #frame .content .message-input .wrap input {
      padding: 15px 32px 16px 8px;
    }
  }
  #frame .content .message-input .wrap input:focus {
    outline: none;
  }
  #frame .content .message-input .wrap .attachment {
    position: absolute;
    right: 60px;
    z-index: 4;
    margin-top: 10px;
    font-size: 1.1em;
    color: #435f7a;
    opacity: .5;
    cursor: pointer;
  }
  @media screen and (max-width: 735px) {
    #frame .content .message-input .wrap .attachment {
      margin-top: 17px;
      right: 65px;
    }
  }
  #frame .content .message-input .wrap .attachment:hover {
    opacity: 1;
  }
  #frame .content .message-input .wrap button {
    float: right;
    border: none;
    width: 50px;
    padding: 12px 0;
    cursor: pointer;
    background: #32465a;
    color: #f5f5f5;
  }
  @media screen and (max-width: 735px) {
    #frame .content .message-input .wrap button {
      padding: 16px 0;
    }
  }
  #frame .content .message-input .wrap button:hover {
    background: #435f7a;
  }
  #frame .content .message-input .wrap button:focus {
    outline: none;
  }
</style>