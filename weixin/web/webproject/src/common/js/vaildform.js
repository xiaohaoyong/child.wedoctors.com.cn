export function isTel(value) {//电话
  value= value.replace(/\D/g,'')
  if (value.length !== 11) {
    this.$vux.toast.text('请输入完整的手机号');
    return false;
  }
  let reg = /^1[3|4|5|7|8][0-9]{9}$/;
  if (reg.test(value)){
    return true
  }else {
    this.$vux.toast.text('请输入中国大陆手机号');
    return false;
  }
};

export function isPw(value,tiptext) {//密码
  value = value.replace(/^\s+|\s+$/gm,'');
  if (value.length >= 8 && value.length <= 18) {
    return true
  }
  let tips = tiptext || '请输入8~18位的密码';
  this.$vux.toast.text(tips);
  return false;
};

export function isVerification(value) {//验证码
  value = value.replace(/\D/g,'');
  if (value.length === 4) {
    return true
  }
  this.$vux.toast.text('请输入完整的验证码');
  return false;
};

export function isObjEmpty(obj) {//对象是否有空值
  for (let key in obj) {
    if (obj[key].length<1)
      return false
  }
  return true;
};

export function formatTel(value) {//格式化手机号
  if (!value) return '';
  let _arr = (''+value).split('');
  _arr.splice(3,0,' ');
  _arr.splice(8,0,' ');

  return _arr.join('');
};

export function formatText(value) {//格式化文本
  if (!value) return '';
  if (value.length > 15){
    value = value.slice(0,15)+'...';
  }
  return value;
};

export function formatArticleTime(value) {//格式化日期
  if (!value) return '';
  let _arr = value.split('/');
  if (_arr.length > 2){
    _arr.shift();
    value =_arr.join('/');
  }
  return value;
};

export function getVersion(needVersion) {//获取ios系统版本
  let agent = navigator.userAgent.toLowerCase();
  let version;
  if(agent.indexOf("like mac os x") > 0){
      let regStr_saf = /os [\d._]*/gi ;
      let verinfo = agent.match(regStr_saf) ;
      version = (verinfo+"").replace(/[^0-9|_.]/ig,"").replace(/_/ig,".");
  }
  return (parseFloat(version)>=needVersion);
};