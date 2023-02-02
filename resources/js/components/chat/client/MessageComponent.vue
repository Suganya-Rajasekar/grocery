<script>
    export default {
        props: ['message','user']
    }
</script>
<template>
	<div class="msg" :class="[ user.id == message.user.id ? 'msg-right' : 'msg-left' ]">
	    <div class="bubble">
	        <h6 v-if="message.user.role == 1" class="name">Knosh Support</h6>
	        <h6 v-else class="name">{{ message.user.name }}</h6>
	        {{ message.body }}
	    </div>
	</div>
</template>
