<template>
  <h1 v-if="false"><slot>我是标题</slot></h1>
</template>

<script type="text/ecmascript-6">
export default {
  created () {
    this.updateTitle()
  },
  beforeUpdate () {
    this.updateTitle()
  },
  methods: {
    updateTitle () {
      let slots = this.$slots.default
      if (typeof slots === 'undefined' ||
        slots.length < 1 ||
        typeof slots[0].text !== 'string') return
      let {text} = slots[0]
      let {title} = document
      if (text !== title) document.title = text
    }
  }
}
</script>

<style scoped lang="scss" rel="stylesheet/scss">
  h1{
    display: none;
  }
</style>
