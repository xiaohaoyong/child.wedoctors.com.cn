import Vue from 'vue'
import VueRouter from 'vue-router'


// import Hello from '@/components/hello/hello'
// import Home from '@/components/home/home'
// import Homechild from '@/components/home-child/home-child'
import Nofind from '@/components/404/Nofind'



// const Sign = (resolve) => { //注册页面
//   import('@/components/sign/sign').then((module) => {
//     resolve(module)
//   })
// }
import Sign from '@/components/sign/sign'//注册页面

// const Account = (resolve) => { //登陆页面--家长
//   import('@/components/account/account').then((module) => {
//     resolve(module)
//   })
// }
import Account from '@/components/account/account' //登陆页面--家长

// const Accountdocter = (resolve) => { //登陆页面--医生
//   import('@/components/account-docter/account-docter').then((module) => {
//     resolve(module)
//   })
// }
import Accountdocter from '@/components/account-docter/account-docter'//登陆页面--医生

/*const Forget = (resolve) => { //忘记密码
  import('@/components/forgetaccount/forgetaccount').then((module) => {
    resolve(module)
  })
}*/
import Forget from '@/components/forgetaccount/forgetaccount'//忘记密码

const FirstInfo = (resolve) => { //首次登录 信息完善
  import('@/components/info-first-sign/info-first-sign').then((module) => {
    resolve(module)
  })
}

import FirstLoginAdviser from '@/components/parents-doctor-first-login/parents-doctor-first-login'//家长-首次扫码登录签约--儿宝顾问

const Infos = (resolve) => { //完善信息
  import('@/components/info-new/info-new').then((module) => {
    resolve(module)
  })
}
const InfosEdit = (resolve) => { //修改信息   信息
  import('@/components/info-edit/info-edit').then((module) => {
    resolve(module)
  })
}
/*const Usertypes = (resolve) => { //用户类型
  import('@/components/usertype/usertype').then((module) => {
    resolve(module)
  })
}*/
import Usertypes from '@/components/usertype/usertype'//用户类型

const AddDocter = (resolve) => { //医生详情
  import('@/components/adddocter/adddocter').then((module) => {
    resolve(module)
  })
}

const Parenthome = (resolve) => { //家长首页
  import('@/components/home-parent/home-parent').then((module) => {
    resolve(module)
  })
}
const Docterhome = (resolve) => { //医生首页
  import('@/components/home-docter/home-docter').then((module) => {
    resolve(module)
  })
}
const DocterSelfInfo = (resolve) => { //医生自己信息
  import('@/components/docter-myinfo/docter-myinfo').then((module) => {
    resolve(module)
  })
}

/*const HealthRecord = (resolve) => { //健康档案--- 旧版
  import('@/components/health-record/health-record').then((module) => {
    resolve(module)
  })
}*/
const HealthRecord = (resolve) => { //健康档案 --- 2018.01.17改版
  import('@/components/health-record-new/health-record-new').then((module) => {
    resolve(module)
  })
}

const HealthRecordContent = (resolve) => { //健康档案=> 体检详情
  import('@/components/health-record-card-page/health-record-card-page').then((module) => {
    resolve(module)
  })
}


/*const HealthRecordDocter = (resolve) => { //健康档案  医生页面
  import('@/components/health-record-docter/health-record-docter').then((module) => {
    resolve(module)
  })
}*/

const HealthRecordDocter = (resolve) => { //健康档案  医生页面 --- 2018.01.17改版
  import('@/components/health-record-docter-new/health-record-docter-new').then((module) => {
    resolve(module)
  })
}

const HealthRecord_add = (resolve) => { //添加新的健康档案
  import('@/components/health-record-add/health-record-add').then((module) => {
    resolve(module)
  })
}
const ChildDoctorAdvisers = (resolve) => { //家长-儿宝顾问
  import('@/components/parents-doctor/parents-doctor').then((module) => {
    resolve(module)
  })
}

const TalkChildList = (resolve) => { //家长儿宝顾问-儿童列表
  import('@/components/parents-talk-list/parents-talk-list').then((module) => {
    resolve(module)
  })
}

const TalkPage = (resolve) => { //家长聊天页面
  import('@/components/parents-talk-page/parents-talk-page').then((module) => {
    resolve(module)
  })
}
const TalkListDoctor = (resolve) => { //医生答疑儿童列表
  import('@/components/doctor-talk-list/doctor-talk-list').then((module) => {
    resolve(module)
  })
}
const TalkFamilyListDoctor = (resolve) => { //医生答疑儿童家庭列表
  import('@/components/doctor-talk-family-list/doctor-talk-family-list').then((module) => {
    resolve(module)
  })
}

const TalkPageDoctor = (resolve) => { //医生聊天页面
  import('@/components/doctor-talk-page/doctor-talk-page').then((module) => {
    resolve(module)
  })
}

const ClassroomParent = (resolve) => { //健康课堂  父母版本
  import('@/components/parents-classroom/parents-classroom').then((module) => {
    resolve(module)
  })
}
const Article = (resolve) => { //详情新闻
  import('@/components/article-classroom/article-classroom').then((module) => {
    resolve(module)
  })
}
const Setting = (resolve) => { //设置
  import('@/components/setting/setting').then((module) => {
    resolve(module)
  })
}
const SettingDocter = (resolve) => { //设置
  import('@/components/setting-docter/setting-docter').then((module) => {
    resolve(module)
  })
}

const ChangePw = (resolve) => { //修改密码
  import('@/components/setting-password/setting-password').then((module) => {
    resolve(module)
  })
}
const MissionHistory = (resolve) => { //宣教记录
  import('@/components/mission-history/mission-history').then((module) => {
    resolve(module)
  })
}
const MissionTask = (resolve) => { //医生的 宣教任务
  import('@/components/mission-task/mission-task').then((module) => {
    resolve(module)
  })
}
const MissionTaskSend = (resolve) => { //医生的 宣教任务-宣教发送
  import('@/components/mission-task-send/mission-task-send').then((module) => {
    resolve(module)
  })
}

const DocterManagersUser = (resolve) => { //医生的 管辖用户
  import('@/components/docter-managers/docter-managers').then((module) => {
    resolve(module)
  })
}
const WaitSigns = (resolve) => { //等待签约的用户
  import('@/components/wait-sign/wait-sign').then((module) => {
    resolve(module)
  })
}

const SendMission = (resolve) => { //发送宣教
  import('@/components/mission-send/mission-send').then((module) => {
    resolve(module)
  })
}
const GetQRcode = (resolve) => { //获取医生二维码
  import('@/components/docter-QRcode/docter-QRcode').then((module) => {
    resolve(module)
  })
}

const missionRead = (resolve) => { //健康指导文章
  import('@/components/send-read/send-read').then((module) => {
    resolve(module)
  })
}

// component: resolve => require(['@/components/hello/hello'], resolve)//路由页面//按需加载方式2
Vue.use(VueRouter)
const routes = [//设置路由的页面
  {
    path: '/sign',
    name:"sign",
    meta: {
      requireAuth: false,
    },
    component: Sign
  },
  {
    path: '/account',
    meta: {
      requireAuth: false,
    },
    component: Account
  },
  {
    path: '/accountdocter',
    meta: {
      requireAuth: false,
    },
    component: Accountdocter
  },
  {
    path: '/forget',
    meta: {
      requireAuth: false,
    },
    component: Forget
  },
  {
    path: '/first-login-info',
    component: FirstInfo
  },
  {
    path: '/first-login-adviser',
    component: FirstLoginAdviser
  },
  {
    path: '/infos',
    name: 'new_baby',
    component: Infos
  },
  {
    path: '/infos-edit',
    name: 'edit_baby',
    component: InfosEdit
  },
  {
    path: '/usertype',
    meta: {
      requireAuth: false,
    },
    component: Usertypes
  },
  {
    path: '/add-docter',
    name: 'docter-info',
    component: AddDocter
  },
  {
    path: '/docter-self',
    meta: {
      keepAlive: true,
      title: '我的主页'
    },
    name: 'docter-self',
    component: DocterSelfInfo
  },
  {
    path: '/home-parent',
    component: Parenthome
  },
  {
    path: '/home-docter',
    component: Docterhome
  },
  {
    path: '/child-advisers',
    meta: {
      keepAlive: true,
    },
    name: 'erbaoguwen',
    component: ChildDoctorAdvisers
  },
  {
    path: '/talk-select-child',
    name: 'xuanzeertong',
    component: TalkChildList
  },
  {
    path: '/talk-forparent',
    meta: {
      keepAlive: false,
    },
    name: 'jiazhangtalk',
    component: TalkPage
  },
  {
    path: '/doctor-select-talk',
    name: 'yishengxuanze',
    component: TalkListDoctor
  },
  {
    path: '/talk-fordoctor',
    name: 'yishengtalk',
    component: TalkPageDoctor
  },
  {
    path: '/doctor-familylist-talk',
    name: 'jiating',
    component: TalkFamilyListDoctor
  },
  {
    path: '/record',
    meta: {
      title: '健康档案'
    },
    name: 'record-parent',
    component: HealthRecord
  },
  {
    path: '/record-data/:record',
    props: true,
    meta: {
      title: '体检数据'
    },
    component: HealthRecordContent
  },
  {
    path: '/record-fordocter',
    component: HealthRecordDocter
  },
  {
    path: '/mission-history',
    component: MissionHistory
  },
  {
    path: '/record-add',
    component: HealthRecord_add
  },
  {
    path: '/parents-classroom',
    meta: {
      keepAlive: true,
      title: '文章列表'
    },
    component: ClassroomParent
  },
  {
    path: '/article/:id',
    meta: {
      keepAlive: false,
      title: '文章列表'
    },
    component: Article
  },
  {
    path: '/setting',
    meta: {
      keepAlive: true,
      title: '设置'
    },
    component: Setting
  },
  {
    path: '/setting-password',
    meta: {
      keepAlive: true,
      title: '修改密码'
    },
    name: 'change-password',
    component: ChangePw
  },
  {
    path: '/docter-setting',
    meta: {
      keepAlive: true,
      title: '设置'
    },
    component: SettingDocter
  },
  {
    path: '/docter-missiontask',
    component: MissionTask
  },
  {
    path: '/docter-missiontask-send',
    component: MissionTaskSend
  },
  {
    path: '/docters-user',
    component: DocterManagersUser
  },
  {
    path: '/waitsign-user',
    component: WaitSigns
  },
  {
    path: '/send-mission',
    component: SendMission
  },
  {
    path: '/get-qrcode',
    meta: {
      keepAlive: true,
      title: '获取二维码'
    },
    component: GetQRcode
  },
  {
    path: '/mission-read',
    component: missionRead
  },




  {
    path: '*',
    name: '404',
    meta: {
      title: '页面未找到'
    },
    component: Nofind,          //--》 404页面
  },
  {
    path: '/',
    component: Usertypes,          //--》  临时配置  默认页面是按钮
    // component: Sign,          //--》  默认登录页
  }
]


const router = new VueRouter({
  routes: routes,
  // redirect: '/Hello',
  linkActiveClass: 'active'//改变当前选中的路由的class
});


/*this.interceptors.response.use(response => {
  //对响应数据做些事，比如说把loading动画关掉
  // console.log('请求完毕');
  Vue.$vux.loading.hide();
  console.log(response.data.code);
  console.log(Vue.$router);
  return response
}, error => {
  Vue.$vux.loading.hide();
  //请求错误时做些事
  return Promise.reject(error)
})*/

// router.push('/home');// 强制设置默认启动的路由页面
import store from '../vuex/index'

// if (window.localStorage.getItem('token')) {
//   // store.commit(types.LOGIN, window.localStorage.getItem('token'))
// }

/*router.beforeEach((to, from, next) => {
  // if (to.matched.some(r => r.meta.requireAuth)) {
  /!*if (true) {
    console.log(store.state.token);
    if (!store.state.token) {
      next();
    }
    else {
      next({
        path: '/sign',
        query: {redirect: to.fullPath}//用于登录成功之后重定向
      })
    }
  }
  else {
    next();
  }*!/
  setTimeout(()=>{console.log(store.state.token);},500)
  if (store.state.token){//如果axios中没有返回过50000  token永恒为true  可以跳转到下一个路由页面
    next();
  } else {//否则会重定向到登陆页面
    window.location = store.state.redirectUrl;
  }

})*/


export default router
