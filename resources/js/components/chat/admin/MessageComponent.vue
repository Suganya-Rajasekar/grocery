<script>
    export default {
        props: ['message','channelUser']
    }
</script>
<template>
	    <li :class="[ channelUser.id == message.user.id ? 'sent' : 'replies' ]">
	      <img :src="message.user.avatar" alt="" />
	      <p>{{ message.body }}</p>
	    </li>
</template>