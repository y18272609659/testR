<template>
    <div class="flex-row">
        <div class="occupy2"></div> <!-- 2 -->
        <div class="occupy2">
            <ul class="none-li">
                <li>人口：<span>{{ resource.people }}</span></li>
                <li>食物：<span>{{ resource.food }}</span></li>
                <li>木材：<span>{{ resource.wood }}</span></li>
                <li>钱财：<span>{{ resource.money }}</span></li>
            </ul>
        </div>
        <div class="occupy14"></div>
        <div class="occupy2">
            <button class="button-blue" @click="init">重置</button>
        </div>
    </div>
</template>

<script>
  import Swal from 'sweetalert2'

  export default {
    name: "Home",
    data() {
      return {
        // loginForm: {
        //   user: '',
        //   password: ''
        // },
        resource: {
          people: 10,
          food: 0,
          wood: 0,
          money: 0,
        },
        name: {
          lord: 'R',
          kingdom: '',
        },
        time: {
          year: Math.floor(Math.random() * 100 + 900),
          month: 1,
          day: 13,
        }
      }
    },
    methods: {
      signIn() {
        // this.$http.get('/api/seller').then(response => {
        //   response = response.body
        //   console.info(response)
        //   if (response.errno === ERROR.succeed) {
        //     this.seller = response.data
        //   }
        // }, response => {
        //   // error callback
        // })
      },
      init() {
        Swal.mixin({
          input: 'text',
          confirmButtonText: 'Next &rarr;',
          showCancelButton: true,
          progressSteps: ['1', '2', '3']
        }).queue([
          {
            title: '你的名字',
            text: '你将成为君主，理应具备一个伟大的名字。'
          },
          {
            title: '王朝名称',
            text: `尊崇的阁下，王朝将在你的带领下，将会愈发强横雄奇。`
          },
          {
            title: '登基月份',
            text: `王朝的新王，你在何时正式踏上你的漫漫人生路？`
          },
        ]).then((result) => {
          result = result.value
          if (result) {
            this.name.lord = result[0]
            this.name.kingdom = result[1]
            this.time.month = result[2]
            Swal({
              title: '设置完成！',
              html:
              `在 ${this.time.year} 年的 ${result[2]} 月份，受人尊重的 ${result[0]} 阁下，创立了 ${result[1]} 王朝。自此，历史大潮的车辙缓慢而坚定的向前滚动不息。`,
              confirmButtonText: '开始',
            })
          }
        })
      },
    },
  }
</script>

<style type="text/scss" scoped>
    p {
        font-size: 0.85rem;
        color: #404040;
    }

    .description {
        text-indent: 2rem;
    }

    .text-center {
        text-align: center;
    }
</style>