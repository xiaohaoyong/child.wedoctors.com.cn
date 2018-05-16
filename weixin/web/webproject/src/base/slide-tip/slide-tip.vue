<template>
  <aside class="tip_wrapper" @touchmove.prevent>
    <transition name="scale-in">
      <slot name="child" class="box"></slot>
    </transition>
  </aside>
</template>

<script type="text/ecmascript-6">
export default {
  created () {

  },
  beforeUpdate () {

  },
  methods: {

  }
}
</script>

<style scoped lang="scss" rel="stylesheet/scss">
  .scale-in-enter-active {
    /*显示的开始加的状态  结束之后会被移除*/
    transition: all 1s ease;
    transform: scale(1);
  }
  .scale-in-leave-active {
    /*隐藏的开始加的状态   结束之后会被移除*/
    transform: scale(0.4);
  }
  .scale-in-enter, .scale-in-leave-active {
    /*过渡的状态*/
    transform: scale(0.4);
  }

  .tip_wrapper{
    position: fixed;
    display: flex;
    flex-direction: column;
    justify-content: flex-end;
    left: 0;
    top: 0;
    z-index: 2;
    width: 100%;
    height: 100%;
    background-color: rgba(#000,0.3);
  }
</style>
