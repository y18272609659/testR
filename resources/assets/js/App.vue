<template>
    <div id="app" class="container">
        <Header :user="user"/>
        <router-view></router-view>
        <!--<Footer :site="site"/>-->
    </div>
</template>

<script>
  import Header from './components/header/header'
  import Footer from './components/footer/footer'

  export default {
    name: 'App',

    components: {
      Header,
      Footer
    },

    data() {
      return {
        user: {
          avatar: 'http://wx3.sinaimg.cn/mw690/0060lm7Tly1fr0t1qi5kxj301f01fmwy.jpg',
          nickname: '没拿到数据啊朋友'
        },
        site: {
          start: 2017,
          now: new Date().getYear(),
          record: '邬ICP备 66666666号-1',
        }
      }
    },

    created() {
      // this.$http.get('/api/seller').then(response => {
      //   response = response.body
      //   console.info(response)
      //   if (response.errno === ERROR.succeed) {
      //     this.seller = response.data
      //   }
      // }, response => {
      //   // error callback
      // })
    }
  }
</script>

<style type="text/scss" scoped>
</style>
