<template>
  <section class="usertype">
    <my-title>儿童健康管理</my-title>
    <ul class="usertype">
      <li>
        <div class="card" @click="visit_parent">
          <h3>我是家长</h3>
          <p>PARENTS</p>
          <span class="arrow"></span>
          <div class="big_bg parent"></div>
        </div>
      </li>
      <li>
        <div class="card" @click="visit_docter">
          <h3>我是医生</h3>
          <p>DOCTERS</p>
          <span class="arrow"></span>
          <div class="big_bg docter"></div>
        </div>
      </li>
    </ul>
  </section>
</template>

<script type="text/ecmascript-6">
  import myTitle from '../../base/title/title.vue'
  export default {
      data() {
          return {}
      },
      methods: {
        visit_parent(){
          /*this.$router.push({ //开发先跳过用户登录
            path: '/home-parent'
          })*/
          this.$router.push({ //真实跳转
            path: '/sign',
            query: {usertype: 'parent'}
          })
        },
        visit_docter() {
          this.$router.push({ //访问对象为医生
            path: '/accountdocter',
              query: {usertype: 'docter'}
          })
        }
      },
      components: {
        myTitle
      }
  }
</script>

<style lang="scss" rel="stylesheet/scss" scoped>
.usertype{
  display: flex;
  flex-direction: column;
  justify-content: space-between;
  height: 100vh;
  li{
    flex:1;
    display: flex;
    padding: 1rem;
  }
  .card{
    max-height: 45vh;
    position: relative;
    overflow: hidden;
    flex: 1;
    padding-top: unit(80rem);
    padding-left: unit(40rem);
    background-color: #fff;
    border-radius: unit(14rem);
    transition: transform 0.15s;
    &:active{
      transform: scale(0.985);
    }
    h3{
      font-size: unit(48rem);
      color: $color666;
    }
    p{
      margin: unit(20rem) 0;
      color: $colorbbb;
      font-size: unit(24rem);
    }
    .arrow{
      display: block;
      width: unit(38rem);
      height: unit(27rem);
      background: url("./arrow.png") no-repeat center center;
      background-size: contain;
    }
    .big_bg{
      position: absolute;
      bottom: 0;
      right: 0;
      &.parent{
        width: unit(462rem);
        height: unit(389rem);
        background: url("./parents.png") no-repeat center;
        background-size: contain;
      }
      &.docter{
        width: unit(469rem);
        height: unit(402rem);
        background: url("./docter.png") no-repeat center;
        background-size: contain;
      }
    }
  }
}
</style>
