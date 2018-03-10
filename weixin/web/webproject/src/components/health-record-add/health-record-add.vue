<template>
  <transition name="slide-left">
    <div class="add_new_record">
      <group>
        <x-textarea :max="200" :rows="6" :autosize="true" v-model.trim="new_record" placeholder="填写宝宝健康档案（不超过200字）" @on-focus="onEvent('focus')" @on-blur="onEvent('blur')"></x-textarea>
      </group>
      <div class="control">
        <x-button type="primary" class="btn" @click.native="add_new">提交记录</x-button>
      </div>
    </div>
  </transition>
</template>

<script type="text/ecmascript-6">
  import myTitle from '../../base/title/title.vue'
  import {httpRecordadd} from 'my_api/post';
  import { XTextarea,XButton,Group } from 'vux'
  const ERR_OK = 200;
    export default {
        data() {
            return {
              new_record: ''
            }
        },
        methods: {
          add_new() {
            let self = this;
            if (!self.new_record.length){
              self.$vux.toast.text('记录内容不能为空!');
              return;
            }

            httpRecordadd(self.$route.query.id,self.new_record).then((response) => {
              if (response.code == ERR_OK){
                self.$vux.toast.text(response.msg);
                setTimeout(()=>{
                  self.$router.go(-1);
                },200);
              }else{
                self.$vux.toast.text(response.msg);
              }
            });
            /*self.$axios.post('/doctor/add-health',{
              "id": self.$route.query.id,
              "content": self.new_record
            }).then(function (response) {
              let data = response.data;
//              console.log(data);
              if (data.code == ERR_OK){
                self.$vux.toast.text(data.msg);
                setTimeout(()=>{
                  self.$router.go(-1);
                },200);
              }else{
                self.$vux.toast.text(data.msg);
              }
            }).catch(function (error) {
              console.warn(error);
              self.$vux.toast.text('网络错误');
            });*/
            console.log(this.new_record);
          },
          onEvent (event) {
            console.log('on', event)
          }
        },
        components: {myTitle,XTextarea,Group,XButton}
    }
</script>

<style lang="scss" rel="stylesheet/scss">
  .add_new_record{
    .weui-cells{
      margin-top: 0;
      textarea{
        font-size: unit(30rem);
      }
    }
    .control{
      padding: unit(90rem) unit(50rem) 0;
      text-align: center;
      .btn{
        border-radius: unit(90rem);
        height: unit(90rem);
      }
    }
  }
</style>
