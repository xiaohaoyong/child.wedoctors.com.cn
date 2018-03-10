<template>
  <b class="icon_box">
    <x-icon type="ios-circle-outline" class="icon gray" size="100%" v-show="!isChecked"></x-icon>
    <x-icon type="ios-checkmark" class="icon" size="100%" v-show="isChecked"></x-icon>
  </b>
</template>

<script type="text/ecmascript-6">
    export default {
        data() {
            return {}
        },
      props: {
          isChecked: {
            type: Boolean
          }
      },
        methods: {},
        components: {}
    }
</script>

<style lang="scss" rel="stylesheet/scss" scoped="">
  .vux-x-icon {
    fill: #f47961;
  }
  .gray{
    fill: #bbbbbb;
  }
  .icon_box{
    position: relative;
    margin-right: unit(10rem);
    vertical-align: middle;
    display: inline-block;
    width: unit(32rem);
    height: unit(32rem);
    .icon{
      position: absolute;
      left: unit(2rem);
      top: 0;
      bottom: 0;
      margin: auto;
    }
  }
</style>
