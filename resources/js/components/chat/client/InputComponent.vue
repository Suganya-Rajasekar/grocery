<script>
    import Event from './scripts/clientMessages.js';

  import { mapState, mapActions } from 'vuex'


    export default {
        created() {
        },
        mounted() {
            Event.$logger('info','Client Chat input component mounted.');

            this.sockets.subscribe('displayTyping', function(data) {

                if (data.status != undefined) {
                    if (data.status) {
                        data.user.status = data.status;
                        this.setTypeResponseList(data.user);
                    }else{
                        data.user.status = data.status;
                        this.unsetTypeReponseList(data.user);
                    }
                }
            });

        },
        data() {
            return {
                body: null,
            }
        },
        computed : {

          ...mapState({
            
            authUser : state => state.userConf.authUser,
            
            authState: state => state.userConf.authState

          })           
        },
        methods: {
            typing(e) {
                if(e.keyCode === 13 && !e.shiftKey) {
                    e.preventDefault();
                    this.sendMessage();
                }else{
                    this.$socket.emit('typing',{room:this.authUser.room,user:this.authUser},(err) => {console.log(err)});
                }        
            },
            focus(status){

                this.$socket.emit('typing',{room : this.authUser.room,user : this.authUser,status : status},(err) => {console.log(err)});

            },
            sendMessage() {

                if(!this.body || this.body.trim() === '') {
                    return
                }

                let messageObj = this.buildMessage();

                // Event.$emit('send_message', messageObj);

                this.$socket.emit('sendMessage',{room:this.authUser.room,message:messageObj},(err) => {console.log(err)});
                
                axios.post(Event.$Config.putMessage, {
                    body: this.body.trim(),
                    channel : this.channel
                }).catch(() => {
                    Event.$logger('error','failed');
                }).then(() => {
                    this.body = null;
                });

            },
            buildMessage() {
                return {
                    id: Date.now(),
                    body: this.body,
                    selfMessage: true,
                    type: 'sent',
                    user: {
                        name: this.authUser.username,
                        id: this.authUser.id,
                        avatar: this.authUser.avatar
                    }
                }
            },
            ...mapActions([
                'setTypeResponseList',
                'unsetTypeReponseList'
            ]), 
        }
    }
</script>
<template>
	<div class="type-area">
		<input type="text" class="typing client" placeholder="Type and enter to send..." @keydown="typing" @focusin="focus(true)" @focusout="focus(false)" v-model="body">
		<span class="send">
			<i class="bi bi-arrow-return-left"></i>
		</span>
	</div>
</template>

