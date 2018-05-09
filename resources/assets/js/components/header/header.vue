<template>
    <div class="flex-row header">
        <div class="occupy3">Logo</div>
        <div class="occupy16 menu">
            <span :class="menu[1].class" @click="choiceMenu(1)">
                <router-link to="/home" class="menu-item">首页</router-link>
            </span>

            <!--<span :class="menu[2].class" @click="choiceMenu(2)">-->
                <!--<router-link to="/building" class="menu-item">工作清单</router-link>-->
            <!--</span>-->

            <!--<span :class="menu[3].class" @click="choiceMenu(3)">-->
                <!--<router-link to="/tomato-time" class="menu-item">番茄时间</router-link>-->
            <!--</span>-->

            <!--<span :class="menu[4].class" @click="choiceMenu(4)">-->
                <!--<router-link to="/data-stats" class="menu-item">数据统计</router-link>-->
            <!--</span>-->
        </div>
        <div class="avatar"></div>
    </div>
</template>

<script>
  export default {
    name: "Header",
    data() {
      return {
        menu: [
          { last: 0 },
          { class: 'menu-unactive' },
          { class: 'menu-unactive' },
          { class: 'menu-unactive' },
          { class: 'menu-unactive' },
        ]
      }
    },
    props: {
      user: {
        type: Object
      }
    },
    methods: {
      choiceMenu(selectId) {
        this.menu[this.menu[0].last].class = 'menu-unactive';
        this.menu[0].last = selectId;
        this.menu[selectId].class = 'menu-active';
      }
    },
    created() {
      let preg = /#\/(.+)/i;
      let url = window.location.href;
      let result = url.match(preg)
      let selectId = 1
      result = (result === null) ? '' : result[1]

      switch (result) {
        case 'home':
          selectId = 1
          this.menu[0].last = selectId
          this.menu[1].class = 'menu-active'
          break;
        case 'work-list':
          selectId = 2
          this.menu[0].last = selectId
          this.menu[2].class = 'menu-active'
          break;
        case 'tomato-time':
          selectId = 3
          this.menu[0].last = selectId
          this.menu[3].class = 'menu-active'
          break;
        case 'data-stats':
          selectId = 4
          this.menu[0].last = selectId
          this.menu[selectId].class = 'menu-active'
          break;
        default:
          this.menu[0].last = selectId
          this.menu[selectId].class = 'menu-active'
          break;
      }
    }
  }
</script>

<style type="text/scss" scoped>
    .menu {
        font-size: 1.1rem;
        line-height: 70px;
        text-align: left;
    }
    .menu-active {
        padding: 30px 15px 30px 15px;
        background: #ffffff;
    }
    .menu-active > a {
        color: #21c3e4;
    }
    .menu-unactive {
        padding: 30px 15px 30px 15px;
    }
    .menu-item {
        line-height: 40px;
        height: 80px;
        padding: 30px 15px 30px 15px; /* TRBL */
    }

    .avatar {
        margin-top: 7px;
        width:50px;
        height:50px;
        border-radius:100px;
        background-image: url("http://wx3.sinaimg.cn/mw690/0060lm7Tly1fr0t1qi5kxj301f01fmwy.jpg");
        background-repeat:no-repeat;
        background-position:center;
        box-shadow:0 3px 8px #7c7c7c;
    }
</style>