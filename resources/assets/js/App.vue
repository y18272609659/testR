<template>
    <div id="app" class="container">
        <Header :user="user"/>
        <router-view></router-view>
        <Footer :site="site"/>
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
          lord: '',
          kingdom: '',
          avatar: '',
        },
        resource: {
          people: 10,
          food: 0,
          wood: 0,
          money: 0,
        },
        time: {
          year: 1,
          month: 1,
          day: 1,
        },
        // footer
        site: {
          start: 2017,
          now: new Date().getFullYear(),
          record: '邬ICP备 66666666号-1',
        }
      }
    },

    props: {
      userData: {
        type: Object
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
