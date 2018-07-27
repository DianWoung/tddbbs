<template>
    <button :class="classes" @click="subscribe">Subscribe</button>
</template>

<script>
    export default {
        props: ['active'],
        data() {
          return {
              activeStatus:this.active,
          }
        },
        computed: {
          classes() {
              return ['btn', this.activeStatus ? 'btn-primary' : 'btn-default'];
          }
        },
        methods: {
            subscribe(){
                axios[
                    (this.active ? 'delete' : 'post')
                    ](location.pathname + '/subscriptions');

                this.activeStatus = !this.activeStatus;
            }
        }
    }
</script>

<style scoped>

</style>