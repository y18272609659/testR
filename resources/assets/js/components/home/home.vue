<template>
    <div>
        <div class="flex-row">
            <div class="occupy2"></div>
            <div class="occupy13"></div>
            <div class="occupy3">
                <button class="button-blue" @click="init">重置</button>
            </div>
        </div>
        <div class="flex-row">
            <div class="occupy2">{{ user.lord }}</div>
            <div class="occupy12">

            </div>
        </div>
        <div class="flex-row resource-bar">
            <ResourceBay :resource="resource"/>
        </div>
    </div>
</template>

<script>
  import Swal from 'sweetalert2'
  import ResourceBay from '../sub-resourceBar/resourceBar'

  export default {
    name: "v-home",
    data() {
      return {
        resource: {},
        user: {},
        time: {},
      }
    },
    components: {
      ResourceBay
    },
    props: {
      userData: {
        type: Object
      }
    },
    methods: {
      init() {
        Swal.mixin({
          input: 'text',
          confirmButtonText: 'Next &rarr;',
          showCancelButton: true,
          progressSteps: ['1', '2', '3']
        }).queue([
          { title: '你的名字', },
          { title: '王朝名称', },
          { title: '登基年份', },
          { title: '登基月份', },
        ]).then((result) => {
          result = result.value
          if (result) {
            result[3] = Math.floor(Math.random() * 100 + 900)

            this.$root.initSaved(result)

            Swal({
              title: '设置完成！',
              html:
              `在 ${result[2]} 年的 ${result[3]} 月份，受人尊重的 ${result[0]} 阁下，创立了 ${result[1]} 王朝。自此，历史大潮的车辙缓慢而坚定的向前滚动不息。`,
              confirmButtonText: '开始',
            })
          }
        })
      },
    },
    created() {
      Object.assign(this.$data, this.userData)
      // console.info(this.$data)
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