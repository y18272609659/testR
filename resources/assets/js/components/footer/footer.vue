<template>
    <div class="flex-row main">
        <div class="occupy5"></div>
        <div class="occupy10">
            <div class="footer">
                「自由小岛」© {{ site.start }} - {{ site.now }}
                <br>{{ site.record }}
            </div>
        </div>
    </div>
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