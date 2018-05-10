<template>
    <div class="flex-row">
        <div class="flex-item" v-for="(value, key) in resource">{{ resourceName[key] }}：<span>{{ value }}</span></div>
    </div>
</template>

<script>
  export default {
    name: "ResourceBar",
    data() {
      return {
        resourceName: {
          'people': '人口',
          'food': '食物',
          'wood': '木材',
          'money': '钱财',
          'stone': '石块',
        }
      }
    },
    props: {
      resource: {
        type: Object
      }
    },
    // mounted: function () {
    //   console.info(this.resource)
    // }
  }
</script>

<style scoped>
    .flex-row {
        display: flex;
        background: rgba(212, 198, 120, 0.12);
    }

    .flex-item {
        justify-content: center;
        min-width: 80px;
        margin: 4px 4px;
    }
</style>