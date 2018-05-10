<template>
    <div class="flex-row header">
        <div class="occupy3">Logo</div>
        <div class="occupy16 menu">
            <span v-for="item in menuList" :class="menu[item.id].class" @click="choiceMenu(item.id)">
                <a :href="item.to" class="menu-item">{{ item.text }}</a>
            </span>
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
        ],
        menuList: [
          { id: 1, to: '#/outside', text: '首页' },
        ],
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
      if (localStorage.getItem('loginCheck') === 'true') {
        this.menuList = [
          {id: 1, to: '#/home', text: '领地'},
          {id: 2, to: '#/building', text: '建筑'},
        ]
      } else {
        this.menuList = [
          {id: 1, to: '#/outside', text: '首页'},
        ]
      }

      let preg = /#\/(.+)/i;
      let url = window.location.href;
      let result = url.match(preg)
      let selectId = 1
      // console.info(result)
      result = (result === null) ? '' : result[1]

      switch (result) {
        case 'outside':
          selectId = 1
          this.menu[0].last = selectId
          this.menu[1].class = 'menu-active'
          break;
        case 'home':
          selectId = 2
          this.menu[0].last = selectId
          this.menu[2].class = 'menu-active'
          break;
        case 'building':
          selectId = 3
          this.menu[0].last = selectId
          this.menu[3].class = 'menu-active'
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
        font-size: 1.2rem;
        font-weight:500;
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