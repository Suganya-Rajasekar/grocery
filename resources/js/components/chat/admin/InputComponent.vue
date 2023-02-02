<script>
	import Event from './scripts/adminMessages.js';

    import { mapState, mapActions } from 'vuex'

    export default {
        mounted() {
            Event.$logger('info','Admin Chat input component mounted.');

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
        computed : {

          ...mapState({

            activeUser: state => state.userList.activeUser,
            channelUser: state => state.userList.channelUser,

          })           
        },
        methods: {
            typing(e) {
                if(e.keyCode === 13 && !e.shiftKey) {
                    e.preventDefault();
                    this.sendMessage();
                }else{
                    this.$socket.emit('typing',{room:this.channelUser.room,user:this.authUser,status : false},(err) => {console.log(err)});
                }        
            },
            focus(status){

                this.$socket.emit('typing',{room : this.channelUser.room,user : this.user,status : status},(err) => {console.log(err)});

            },
            sendMessage() {

                if(!this.body || this.body.trim() === '') {
                    return
                }

                let messageObj = this.buildMessage();

                Event.$emit('send_message', messageObj, this.activeUser.room);
                
                axios.post(Event.$Config.putMessage, {
                    body: this.body.trim(),
                    channel : this.channel
                }).catch(() => {
                    Event.$logger('error','failed');
                });

                this.body = null;
            },
            buildMessage() {
                return {
                    id: Date.now(),
                    body: this.body,
                    selfMessage: true,
                    type: 'sent',
                    user: {
                        name  : this.user.name,
                        id    : this.user.id,
                        avatar: this.user.avatar,
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
    <div class="message-input">
        <div class="wrap">
            <input type="text" placeholder="Type and enter to send..."" @keydown="typing" @focusin="focus(true)" @focusout="focus(false)" v-model="body"/>
            <!-- <i class="fa fa-paperclip attachment" aria-hidden="true"></i> -->
            <button class="submit" @click="sendMessage()"><i class="fa fa-paper-plane" aria-hidden="true"></i></button>
        </div>
    </div>
</template>