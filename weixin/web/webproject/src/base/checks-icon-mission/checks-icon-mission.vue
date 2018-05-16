<template>
  <b class="icon_box bg_gray">
    <x-icon type="ios-circle-outline" class="icon gray" size="100%" v-show="!isChecked"></x-icon>
    <x-icon type="ios-checkmark-empty" class="icon check" size="100%" v-show="isChecked"></x-icon>
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
    margin-right: unit(20rem);
    display: inline-block;
    position: relative;
    width: unit(53rem);
    height: unit(53rem);
    .icon{
      position: absolute;
      left: 0;
      top: 0;
      width: 100%;
      height: 100%;
      &.check{
        right: 0;
        bottom: 0;
        margin: auto;
        width: 92%;
        height: 92%;
        background-color: #f8f8f8;
        border-radius: 50%;
      }
    }
  }
</style>
