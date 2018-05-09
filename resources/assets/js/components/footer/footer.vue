<template>
    <el-row type="flex" justify="center">
        <el-col :span="6">
            <div class="footer">
                「自由小岛」© {{ site.start }} - {{ site.now }}
                <br>{{ site.record }}
            </div>
        </el-col>
    </el-row>
</template>

<script>
  export default {
    name: "vFooter",
    props: {
      site: {
        type: Object
      }
    }
  }
</script>

<style scoped>
</style>