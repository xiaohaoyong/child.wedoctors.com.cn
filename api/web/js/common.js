
// 全局渠道变量

var CHANNEL_WRAP = {
    WECHAT : "WECHAT",
    YOUANBAO : "YOUANBAO",
    YIERWAP: "YIERWAP", // 网页版
    ZJHJY: "ZJHJY" // 浙江和教育
};

var CHANNEL = CHANNEL_WRAP.WECHAT;

var Y_TOOL = {

    /* 正则格式验证
     ** isIdCard: 验证身份证
     ** isPhone:  验证手机号
     ** isEmail:  验证邮箱
     ** isChinese: 验证中文
     */
    Regexp: {
        cityCode: { 11: "北京", 12: "天津", 13: "河北", 14: "山西", 15: "内蒙古", 21: "辽宁", 22: "吉林", 23: "黑龙江 ", 31: "上海", 32: "江苏", 33: "浙江", 34: "安徽", 35: "福建", 36: "江西", 37: "山东", 41: "河南", 42: "湖北 ", 43: "湖南", 44: "广东", 45: "广西", 46: "海南", 50: "重庆", 51: "四川", 52: "贵州", 53: "云南", 54: "西藏 ", 61: "陕西", 62: "甘肃", 63: "青海", 64: "宁夏", 65: "新疆", 71: "台湾", 81: "香港", 82: "澳门", 91: "国外 " },
        isIdCard: function(idCard) {
            if (typeof idCard === "undefined") return false;
            var reg = /^([0-9]\d{5}(19|20)?\d{2}((0\d)|(1[0-2]))(([0|1|2]\d)|3[0-1])\d{3}([0-9]|X|x))$/;
            if (!this.cityCode[idCard.substr(0, 2)] || !reg.test(idCard)) return false;
            return true;
        },
        isPhone: function(phone) {
            if (typeof phone === "undefined") return false;
            var reg = /^(13|14|15|17|18)\d{9}$/;
            if (!reg.test(phone)) return false;
            return true;
        },
        isEmail: function(mail) {
            if (typeof mail === "undefined") return false;
            var reg = /^(\w-*\.*)+@(\w-?)+(\.\w{2,})+$/;
            if (!reg.test(mail)) return false;
            return true;
        },
        isChinese: function(str) {
            if (typeof str === "undefined") return false;
            var reg = /^[\u4e00-\u9fa5]+$/;
            if (!reg.test(str)) return false;
            return true;
        },
        checkMinAge: function(idCode) {
            if (typeof idCode == "undefined") return;
            var nYear = parseInt(idCode.substr(6, 4));
            var nMonth = parseInt(idCode.substr(10, 2));
            var nDay = parseInt(idCode.substr(12, 2));
            var oDate = new Date();
            var nowDateStamp = oDate.getTime();
            var babyDate = new Date();
            babyDate.setFullYear(nYear, nMonth - 1, nDay);
            var babyDateStamp = babyDate.getTime();
            var diffDate = (nowDateStamp - babyDateStamp) / 24 / 60 / 60 / 1000;
            if (diffDate > 26) {
                return true;
            } else {
                return false;
            }
        },
        checkMaxAge: function(idCode) {
            if (typeof idCode == "undefined") return;
            var nYear = parseInt(idCode.substr(6, 4));
            var nMonth = parseInt(idCode.substr(10, 2));
            var nDay = parseInt(idCode.substr(12, 2));
            var oDate = new Date();
            var nNowYear = oDate.getFullYear();
            var nNowMonth = oDate.getMonth() + 1;
            var nNowDate = oDate.getDate();
            if ((nNowYear - nYear > 14) || (nNowYear - nYear == 14 && nNowMonth - nMonth > 0) || (nNowYear - nYear == 14 && nNowMonth - nMonth == 0 && nNowDate - nDay >= 0)) {
                return false;
            } else {
                return true;
            }
        }
    },
    /* 日期相关
     ** getTimeStamp: 获取时间戳
     ** getDate:      获取当前日期，YY-MM-DD
     ** getWeek:      获取当前星期几
     ** getDiffDay:   获取日期差值
     */
    DateFunc: {
        getTimeStamp: function() {
            return new Date().getTime();
        },
        getDate: function(modifyday) {
            var D = new Date();
            if(modifyday) {
                D.setDate(D.getDate() + parseInt(modifyday));
            }
            var Year = D.getFullYear(),
                Month = Y_TOOL.repair0(D.getMonth() + 1),
                Day = Y_TOOL.repair0(D.getDate());
            return (Year + "-" + Month + "-" + Day);
        },
        getWeek: function() {
            return (new Date().getDay());
        },
        getDiffDay: function(minDate, maxDate) {
            var D1 = new Date(minDate.substr(0, 10)),
                D2 = maxDate ? new Date(maxDate.substr(0, 10))
                    : new Date() ;
            D1.setHours(0), D1.setMinutes(0), D1.setSeconds(0);
            maxDate && (D2.setHours(0), D2.setMinutes(0), D2.setSeconds(0));
            var T1 = D1.getTime(),
                T2 = D2.getTime(),
                diff = T2 - T1,
                diffDay = diff / (1000 * 60 * 60 * 24);
            return Math.floor(diffDay);
        }
    },
    /* 检测数据类型 */
    TypeOf: {
        isArray: function(obj) {
            return Object.prototype.toString.call(obj) === "[object Array]";
        },
        isObject: function(obj) {
            return Object.prototype.toString.call(obj) === "[object Object]";
        },
        isFunction: function(fun) {
            return Object.prototype.toString.call(fun) === "[object Function]";
        },
        isString: function(str) {
            return Object.prototype.toString.call(str) === "[object String]";
        },
        isNumber: function(num) {
            return Object.prototype.toString.call(num) === "[object Number]";
        },
        isBool: function(bool) {
            return Object.prototype.toString.call(bool) === "[object Boolean]";
        },
        isNull: function(obj) {
            return Object.prototype.toString.call(obj) === "[object Null]";
        },
        isUndefined: function(obj) {
            return Object.prototype.toString.call(obj) === "[object Undefined]";
        }
    },
    /* 对象数据操作
     ** key2Array : object的key转数组
     ** cloneObj : 复制对象
     ** extendObj : 扩展对象
     */
    ObjectFunc: {
        key2Array: function(obj) {
            var result = [];
            if (!Y_TOOL.TypeOf.isObject(obj)) return result;
            for (var i in obj) {
                result.push(i);
            }
            return result;
        },
        cloneObj: function(obj) {
            var result = {};
            if (!Y_TOOL.TypeOf.isObject(obj)) return result;
            for (var i in obj) {
                if (typeof obj[i] === "object") {
                    result[i] = this.cloneObj(obj[i]);
                } else {
                    result[i] = obj[i];
                }
            }
            return result;
        },
        extendObj: function(obj) {
            if (arguments.length < 2 || obj === null) return obj;
            var result = {};
            var len = arguments.length;
            for (var i = 0; i < len; i++) {
                var o = arguments[i];
                for (var l in o) {
                    o[l] && (result[l] = o[l]);
                }
            }
            return result;
        }
    },
    /* toast  信息提示栏
     ** msg:   提示文本
     ** delay: 提示时间，默认2s
     */
    Toast: {
        msgQueue: [],
        delayQueue: [],
        timer: "",
        _show: function(msg, delay) {

            $("body > #toast_container > .text").html(msg).css({ "font-size": ".28rem", "height": "auto" });
            $("body > #toast_container").fadeIn();
            $("body > #toast_container").css({
                "top": 0,
                "opacity": 1,
                "background-color": "rgba(82,100,120,0.9)",
                "line-height": ".58rem",
                "width": "7.5rem"
            });
            Y_TOOL.Toast.timer = setTimeout("Y_TOOL.Toast._hideOne()", delay);
        },
        _hideOne: function() {
            if (Y_TOOL.Toast.msgQueue.length > 0) {
                Y_TOOL.Toast._show(Y_TOOL.Toast.msgQueue.shift(), Y_TOOL.Toast.delayQueue.shift());
            } else {
                $("body > #toast_container").fadeOut();
                clearTimeout(Y_TOOL.Toast.timer);
            }
        },
        show: function(msg, delay) {
            if ($("body > #toast_container").length == 0) {
                var toastDom = $("<div id='toast_container' style='display:none; position:fixed;z-index:999999;'>" +
                    "<span class='prompt_info' style='display:inline-block;'>" +
                    "" +
                    "</span>" +
                    "<span class='text'></span>" +
                    "<span onclick='Y_TOOL.Toast._hideOne();' class='close' style='display:inline-block;'>" +
                    "</span>" +
                    "</div>");
                $("body").append(toastDom);
            }
            delay = delay || 2000;
            if ($("body > #toast_container").css("display") != 'none') {
                Y_TOOL.Toast.msgQueue.push(msg);
                Y_TOOL.Toast.delayQueue.push(delay);
            } else {
                Y_TOOL.Toast._show(msg, delay);
            }
        }
    },
    GetLocationMsg: {
        addressContainer: null,
        positionInfo: {},
        _getUserInfo: function() {
            var _self = this,
                isHasPositionInfo = false;
            Mafw.service.s("userInfo", {
                beforeSend: function() {},
                success: function(data) {
                    if (data.success && data.data) {
                        if (data.data.positionInfo && data.data.positionInfo.provinceCode) {
                            var obj = data.data.positionInfo;
                            for (var i in obj) {
                                if (obj[i]) {
                                    _self.positionInfo[i] = obj[i];
                                    isHasPositionInfo = true;
                                }
                            }
                            if (isHasPositionInfo) {
                                _self._savePositionInfo2Cookie();
                            } else {
                                _self._getPositionInfoFromCookie()
                            }
                        } else {
                            _self._getPositionInfoFromCookie();
                        }
                    } else {
                        _self._getPositionInfoFromCookie();
                    }
                }
            })
        },
        _getPositionInfoFromCookie: function() {
            var _self = this,
                isEmpty = true,
                result,
                positionKeyList = ["provinceCode", "provinceName", "cityCode", "cityName", "districtCode", "districtName"];
            result = Y_TOOL.getCookie(positionKeyList);
            for (var i in result) {
                if (result["provinceCode"]) {
                    isEmpty = false;
                    break;
                }
            }
            if (!isEmpty) {
                _self.positionInfo = result;
                _self.addressContainer && _self.addressContainer.html(result.districtName ? result.districtName : (result.cityName ? result.cityName : result.provinceName));
                Y_TOOL.savePositionInfo2Service(result);
            } else {
                _self._doGpsLocation();
            }
        },
        // 向服务器获取省市区的code
        _getCityCode: function(prov, city, dist) {
            var _self = this;
            Mafw.service.s("searchRegionInfo", {
                data: {
                    province: prov,
                    city: city,
                    area: dist
                },
                beforeSend: function() {},
                success: function(data) {
                    if (data.success && data.data && data.data.positionInfo) {
                        var msg = data.data.positionInfo;
                        for (var i in msg) {
                            _self.positionInfo[i] = msg[i];
                        }
                        _self._savePositionInfo2Cookie();
                    }
                }
            })
        },
        // 执行gps定位
        _doGpsLocation: function(callBack) {
            var _self = this,
                args = arguments;
            var geolocation = new qq.maps.Geolocation("GDUBZ-BAKR5-VSKI6-QLQQS-V4R47-5MFVW", "益儿健康");
            var showIpPos = function(position) {
                var _province = position.province;
                var _city = position.city;
                var _district = position.district || "";
                _self.positionInfo.lat = position.lat || ""; //纬度
                _self.positionInfo.lng = position.lng || ""; //经度
                _self._getCityCode(_province, _city, _district);
            };
            var showIpErr = function() {
                Y_TOOL.ShowCityList._init(".baby-cityList", ".baby-location");
                if (!_self.positionInfo.provinceCode) {
                    _self.positionInfo = {
                        provinceCode: "110000"
                    }
                    _self._savePositionInfo2Cookie();
                }
            };
            var showPosition = function(position) {
                var _province = position.province,
                    _city = position.city,
                    _district = position.district;
                _self.positionInfo.lat = position.lat; //纬度
                _self.positionInfo.lng = position.lng; //经度
                if (typeof callBack == "function") {
                    _self.callBack = callBack;
                    if (args.length > 1) {
                        _self.args = Array.prototype.slice.call(args, 1);
                    }
                }
                _self._getCityCode(_province, _city, _district);
            };
            var showErr = function() {
                // Y_TOOL.ShowCityList._init(".baby-cityList", ".baby-location");
                geolocation.getIpLocation(showIpPos, showIpErr);
            }
            geolocation.getLocation(showPosition, showErr, { timeout: 5000 });
        },
        // 将位置信息存储到cookie
        _savePositionInfo2Cookie: function() {
            var positionCookieObj = [],
                msg = this.positionInfo;
            for (var i in msg) {
                var obj = {
                    key: i,
                    value: msg[i]
                };
                positionCookieObj.push(obj);
            };
            this.addressContainer && this.addressContainer.html(msg.districtName ? msg.districtName : (msg.cityName ? msg.cityName : msg.provinceName));
            Y_TOOL.setCookie(positionCookieObj);
            // Y_TOOL.savePositionInfo2Service(msg);
            if (this.callBack) {
                this.args.unshift(msg.provinceCode);
                this.callBack.apply(null, this.args);
            }
            if (this.success && (typeof this.success.fn == "function")) {
                this.success.params.unshift(msg.provinceCode);
                this.success.fn.apply(this.success.context, this.success.params);
            }
        },
        _init: function(ele) {
            (typeof ele === "string") ? (this.addressContainer = $(ele)) : (this.success = ele);
            this._getUserInfo();
        }
    },
    ShowCityList: {
        cityObj: null, // 城市数据容器
        cityDom: null, // 城市列表容器
        isExisted: false,
        positionInfo: {},
        level2City: ["710000", "810000", "820000"],
        _getCityObj: function() {
            var _self = this;
            if (!_self.cityObj) {
                $.ajax({
                    url: Mafw.constants.basePath + "/data/provinceInfo",
                    type: "get",
                    dataType: "json",
                    beforeSend: function() {},
                    success: function(data) {
                        _self.cityObj = data;
                        _self._createCityList(data);
                    },
                    error: function(error) {},
                    complete: function() {}
                });
            } else {
                _self._createCityList(_self.cityObj);
            }
        },
        _createCityList: function() {
            var _self = this,
                obj = _self.cityObj,
                cityHtml = "";

            for (var i in obj) {
                var value = obj[i];
                if (value && value.l == 2) {
                    cityHtml += "<li class='cityLi js_level_2' data-code='" + i + "' data-level='" + value.l + "'>" +
                        "<span>" + value.n + "</span>" +
                        "</li>";
                }
            }

            _self.cityContainer.html(cityHtml);
            _self._bind();
            _self._show();
            _self.isExisted = true;
        },
        _bind: function() {
            var _self = this;
            _self.cityDom.on("click", "li", function(e) {
                var that = $(this),
                    name = that.children("span").text(),
                    code = that.attr("data-code"),
                    level = parseInt(that.attr("data-level")),
                    liOffsetTop = that[0].offsetTop,
                    liHeight = that.height(),
                    ulHtml = "";
                that.siblings("li").find("ul").remove();
                // 是否处于展开状态
                if (that.hasClass("js_show")) {
                    // that.css({"height": ".8rem", "overflow": "hidden"}).removeClass("js_show")
                    // 	.children("span").css("color", "#1e1e1e");
                    that.removeClass("js_show").children("span").css("color", "#1e1e1e").siblings("ul").remove();
                } else {
                    // 是否已存在子项目
                    if (that.find("ul").length === 0) {
                        ulHtml = "<ul>";
                        for (var i in _self.cityObj) {
                            var value = _self.cityObj[i];
                            if (value && value.l === (level + 1) && value.c === code) {
                                ulHtml += "<li class='cityLi js_level_" + (level + 1) + "' data-code='" + i + "' data-level='" + value.l + "'>" +
                                    "<span>" + value.n + "</span>" +
                                    "</li>";
                            }
                        }
                        ulHtml += "</ul>";

                    }
                    that.css({ "height": "auto", "overflow": "scroll" }).addClass("js_show")
                        .children("span").css("color", "#f7aa2b")
                        .parents("li")
                        .siblings("li").removeClass("js_show").children("span").css("color", "#1e1e1e");
                    that.append(ulHtml);
                    // 将点击信息滚到到视窗
                    setTimeout(function() {
                        that[0].scrollIntoView(true);
                        $(".i-body").scrollTop(0);
                        // $(".cityList-list").scrollTop(liOffsetTop - liHeight);
                    }, 50);
                }

                // 保存定位信息
                switch (level) {
                    case 2:
                        _self.positionInfo.provinceCode = code,
                            _self.positionInfo.provinceName = name;
                        ulHtml === "<ul></ul>" ? _self._save(name) : "";
                        break;
                    case 3:
                        _self.positionInfo.cityCode = code,
                            _self.positionInfo.cityName = name;
                        ulHtml === "<ul></ul>" ? _self._save(name) : "";
                        break;
                    case 4:
                        _self.positionInfo.districtCode = code,
                            _self.positionInfo.districtName = name;
                        _self._save(name);
                        break;
                    default:
                        break;
                }
            });

            // 绑定关闭城市列表事件
            _self.cityDom.on("click", ".cityList-title-close", function(){
                _self._close();
            })
        },
        _show : function() {
            Y_TOOL.createMark();
            this.cityDom.css("display", "block");
            $(".i-body").css({"overflow": "hidden"});
        },
        _close : function() {
            Y_TOOL.removeMark();
            this.cityDom.css("display", "none");
            $(".i-body").css({"overflow": "auto"});
        },
        _save : function(location) {
            this._close();
            this.addressDom.text(location);
            var obj = this.positionInfo, posObj = [];
            for(var i in obj) {
                posObj.push({
                    "key": i,
                    "value": obj[i]
                });
            };
            Y_TOOL.setCookie(posObj);
            Y_TOOL.savePositionInfo2Service(obj);
        },
        _init : function(listHtml, ele) {
            if($(ele).length < 1) return;
            this.addressDom    = $(ele);
            this.cityDom       = $(listHtml);
            this.cityContainer = this.cityDom.find(".cityList-list-container");
            this.isExisted ? this._show() : this._getCityObj();
        }
    },
    /****加载到底部弧形提示*****
     *
     * scrollWrap 选择器 滚动容器
     * scrollContent 选择器 滚动体
     *
     *****加载到底部弧形提示*****/
    ScrollBotoomTip : function(scrollWrap, scrollContent) {
        var SlideAnimation = function() {
            this.cav = null;      // canvas对象
            this.ctxt = null;
            this.isCanvas = false;
            this.wrap = $(scrollWrap);
            this.content = $(scrollContent);
            this.client = {};     // touch位置集合
            this.cavY = 200;      // 二次贝塞尔曲线控制点Y坐标
            this.cavOpacity = 0.2;
            this.width = this.wrap.width();
            this.height = 200;    // canvas高度
            if(window.devicePixelRatio) {
                this.width = this.width * window.devicePixelRatio;
                this.height = this.height * window.devicePixelRatio;
            }
        };
        SlideAnimation.prototype = {
            constructor: SlideAnimation,
            _setStartPos: function(e) {
                this.cHeight = this.content.height();
                this.client.startX = e.changedTouches[0].clientX;
                this.client.startY = e.changedTouches[0].clientY;
            },
            _createCanvas: function() {
                if(!this.isCanvas) {
                    this.cav = document.createElement("canvas");
                    this.cav.id = "slideAnimation";
                    document.body.appendChild(this.cav);
                    this.cav.setAttribute("class", "slide-animation");
                    this.ctxt = this.cav.getContext("2d");
                    this.cav.width = this.width;
                    this.cav.height = this.height;
                    this.isCanvas = true;
                }
            },
            _slideDir: function() {
                var diffX = this.client.endX - this.client.startX,
                    diffY = this.client.endY - this.client.startY;
                return Math.abs(diffX) > Math.abs(diffY)
                    ? diffX > 0
                        ? 'right'
                        : 'left'
                    : diffY > 0
                        ? 'bottom'
                        : 'top'
            },
            _hasSlideBottom: function() {
                var scrollTop = this.wrap[0].scrollTop,
                    _height = this.wrap.height();
                return (this.cHeight - scrollTop - _height <= 5);
            },
            _reset: function() {
                this.client = {};
                this.cavY = this.cav.height;
                this.cavOpacity = 0.2;
                this.ctxt.clearRect(0, 0, this.width, this.height);
                document.body.removeChild(document.getElementById("slideAnimation"));
                this.cav = null;
                this.isCanvas = false;
            },
            _touchmove: function(e) {
                this.client.endX = e.changedTouches[0].clientX;
                this.client.endY = e.changedTouches[0].clientY;
                if(this._slideDir() === 'top') {
                    if(this._hasSlideBottom()) {
                        this._createCanvas();
                        this.cav && requestAnimationFrame(this._drawAnimation.bind(this));
                        e.preventDefault();
                    }
                } else if(this._slideDir() === "bottom") {
                    this.cav && this._reset();
                }
                this.client.startX = e.changedTouches[0].clientX;
                this.client.startY = e.changedTouches[0].clientY;
            },
            _bindEvent: function() {
                var _self = this;
                this.wrap[0].addEventListener("touchstart", function(event) {
                    _self._setStartPos(event);
                });
                this.wrap[0].addEventListener("touchmove", function(event) {
                    if(event.changedTouches[0].clientY > 0) {
                        _self._touchmove(event);
                    } else {
                        _self.cav && _self._reset();
                    }
                });
                this.wrap[0].addEventListener("touchend", function() {
                    _self.cav && _self._reset();
                });
                this.wrap[0].addEventListener("touchcancel", function() {
                    _self.cav && _self._reset();
                });
            },
            _drawAnimation:function() {
                // if(window.devicePixelRatio) {
                //     this.ctxt.scale(window.devicePixelRatio, window.devicePixelRatio);
                // }
                this.ctxt.clearRect(0, 0, this.width, this.height);
                this.ctxt.beginPath();
                this.ctxt.moveTo(0, this.cav.height);
                this.ctxt.fillStyle = "rgba(0, 0, 0, "+ Math.min(this.cavOpacity, 0.6) +")";
                this.ctxt.quadraticCurveTo(this.cav.width / 2, Math.max(this.cavY, this.cav.height / 2), this.cav.width, this.cav.height);
                this.ctxt.fill();

                this.cavY -= 8;
                this.cavOpacity += 0.01;
            },
            _init: function() {
                window.requestAnimationFrame = window.requestAnimationFrame || function(fn) {
                    return setTimeout(fn, 17);
                };
                this._bindEvent();
            }
        };
        return new SlideAnimation();
    },
    /* 创建遮罩
    ** elem: 添加遮罩的父节点，默认body
    ** isScroll: 是否允许滚动，默认false
    */
    createMark : function(elem, isScroll) {
        var parentNode = elem || document.body;
        var mark = document.createElement("div");
        mark.setAttribute("class", "markDiv");
        mark.setAttribute("id", "markDiv");
        parentNode.appendChild(mark);
        !isScroll && mark.addEventListener("touchmove", function(e) {
            e.preventDefault();
        });
    },
    /* 移除遮罩层 */
    removeMark : function() {
        var mark = document.getElementById("markDiv");
        mark && mark.parentNode.removeChild(mark);
    },
    /* 显示loading
    ** options {object}
    ** text: 显示的文本
    ** isShowMark: 是否显示遮罩，默认：true
     */
    showLoading : function(options) {
        var config = {
            text : "",
            isShowMark : true
        };
        if(typeof options === "object") {
            for(var i in options) {
                config[i] = options[i];
            };
        };
        var loadingWrap = document.querySelector(".loading");
        loadingWrap && (
            (loadingWrap.querySelector("span").innerHTML = config.text),
                loadingWrap.style.display = "block"
        );
    },
    /* 隐藏loading
    ** isHideMark: 是否隐藏遮罩，默认为true
     */
    hideLoading : function() {
        var loadingWrap = document.querySelector(".loading");
        loadingWrap && (
            loadingWrap.style.display = "none"
        );
    },
    inputWarning : function(parentElem, isJq) {
        var parent;
        if(isJq){
            parent = parentElem[0];
        }else{
            parent = document.querySelector(parentElem);
        }
        if(!parent.querySelector(".asterisk")){
            var span = document.createElement("span");
            parent.querySelector("input").style.color = "rgb(244,101,68)";
            parent.querySelector("input").style.right = ".3rem";
            span.className        = "asterisk";
            span.style.display    = "block";
            span.style.height     = "14px";
            span.style.position   = "absolute";
            span.style.right      = ".1rem";
            span.style.top        = "50%";
            span.style.lineHeight = "14px";
            span.style.marginTop  = "-7px";
            span.style.color      = "rgb(244,101,68)";
            span.innerHTML        = "*";
            parent.appendChild(span);
        }
    },
    removeWarning : function(elem, isPos){
        $(elem).css("color", "rgb(102,102,102)");
        $(elem).css("right", isPos ? "0" : ".3rem");
        $(elem).siblings(".asterisk").remove();
    },
    addOrderData : function(obj, code) {
        var orderDatas = JSON.parse(sessionStorage.prodOrderObj);
        if(!orderDatas){
            orderDatas = {};
        }
        orderDatas[code] = obj;
        sessionStorage.prodOrderObj = JSON.stringify(orderDatas);
    },
    getOrderData : function() {
        return JSON.parse(sessionStorage.prodOrderObj);
    },
    addPersonInfo : function(obj) {
        sessionStorage.protectedPersonInfo = JSON.stringify(obj);
    },
    getPersonInfo : function() {
        return sessionStorage.protectedPersonInfo ? JSON.parse(sessionStorage.protectedPersonInfo) : null;
    },
    startCreatOrder : function(productId) {
        var _self = this,
            regionId = Y_TOOL.getCookie("provinceCode");
        var datas = $.extend({},
            {productId: productId, channel : CHANNEL, regionId : regionId, "reverse" : _self.getPersonInfo()},
            this.getOrderData());
        Mafw.service.s("createOrder", {
            data : JSON.stringify(datas),
            success : function(data) {
                if( data.success ) {
                    sessionStorage.protectedPersonName = data.data.reverse;
                    location.href = Mafw.constants.basePath + "/pages/payment.html?orderId="+ data.data.id +"&prodId="+ data.data.productId +"&code=";
                } else {
                    Y_TOOL.Toast.show(data.msg);
                }
            }
        })
    },
    createRenewOrder : function(prodOrderId, prodId) {
        var regionId = Y_TOOL.getCookie("provinceCode");
        Mafw.service.s("renewOrder", {
            data : JSON.stringify({
                regionId : regionId,
                channel : CHANNEL,
                prodOrderId : prodOrderId
            }),
            success : function(data) {
                if(data.success) {
                    //TODO 跳转续费页面
                    location.href = Mafw.constants.basePath + "/pages/payment.html?orderId="+ data.data.id +"&prodId="+ prodId;
                } else {
                    CMCC.Toast.show(data.msg);
                }
            }
        })
    },
    orderPay : function(payType, orderId, productId, couponCode) {
        Mafw.service.s('payOrder', {
            data: JSON.stringify({
                payType : payType,
                orderId : orderId,
                couponCode : couponCode || null
            }),
            success: function(result){
                if( result.success ) {
                    if( result.data && result.data.special ) {
                        location.replace(Mafw.constants.basePath + "/pages/productDetails.html?isGoHome=1&productId=" + productId);
                    } else {
                        // 测试支付
                        if(payType == 0 && Mafw.constants.MODE_TEST) {
                            Mafw.service.s("testPay", {
                                data : { orderId : orderId },
                                dataType : "text",
                                success : function(data) {
                                    if( typeof data == "string" && data == "success" ) {
                                        location.replace(Mafw.constants.basePath + "/pages/productDetails.html?isGoHome=1&productId=" + productId);
                                    } else {
                                        Y_TOOL.Toast.show(data.msg);
                                    }
                                }
                            });
                            return ;
                        }
                        // 判断支付类型
                        // payType : 1-微信，2-支付宝，0-测试支付，5-和宝贝支付渠道
                        if( payType == 2 || payType == 4 ) {
                            $("body").append("<form style='display:none;' method='get' id='alipaysubmit' action='https://mapi.alipay.com/gateway.do?_input_charset=utf-8'></form>");
                            var Form = $("#alipaysubmit");
                            for(var i in result.data) {
                                Form.append("<input type='hidden' name='"+ i +"' value='"+ result.data[i] +"' />");
                            }
                            Form.append("<input id='applySub' type='submit'>");
                            $("#applySub").click();
                        } else if( payType == 1 ) {
                            try{
                                var apply = new Apply();
                                apply.wx_apply(result.data, productId);
                            }catch(error){

                            }

                        } else if( payType == 5 ) {
                            $("body").append("<form style='display:none;' method='get' id='hbbpaysubmit' action='"+ result.data.url +"'></form>");
                            var Form = $("#hbbpaysubmit");
                            for(var i in result.data) {
                                Form.append("<input type='hidden' name='"+ i +"' value='"+ result.data[i] +"' />");
                            }
                            Form.append("<input id='applySub' type='submit'>");
                            $("#applySub").click();
                        }
                    }
                }else{
                    CMCC.Toast.show('购买产品失败，原因：' + result.msg);
                }
            }
        });
    },
    /* url参数转换为对象 */
    getParams : function(href) {
        var paramMaps = {};
        var href = document.location.href;
        var paramSplitTmp = href.split(/[?#]/);
        if(paramSplitTmp.length < 2){
            return paramMaps;
        }
        var paramStrs = paramSplitTmp[1].split('&');
        for(var i = 0; i < paramStrs.length; i++){
            paramExp = paramStrs[i].split('=');
            if(paramExp.length > 1){
                var key = paramExp[0],
                    value = paramExp[1];
                if( paramMaps[key] ) {
                    if( typeof paramMaps[key] === "object" ) {
                        paramMaps[key].push(value);
                    } else {
                        paramMaps[key] = [ paramMaps[key], value ];
                    }
                } else {
                    paramMaps[paramExp[0]] = paramExp[1];
                }
            }else{
                paramMaps[paramExp[0]] = true;
            }
        }
        return paramMaps;
    },
    getProductPrice : function(product) {
        var unitText = '';
        if(product.validTimeUnit == 0){
            unitText += '日';
            if(product.validTimeCount > 1){
                unitText = product.validTimeCount + unitText;
            }
        }else if(product.validTimeUnit == 1){
            unitText += '月';
            if(product.validTimeCount > 1){
                if(product.validTimeCount == 6){
                    unitText = '半年';
                }else{
                    unitText = product.validTimeCount + unitText;
                }
            }
        }else if(product.validTimeUnit == 2){
            unitText += '年';
            if(product.validTimeCount > 1){
                unitText = product.validTimeCount + unitText;
            }
        }
        return {
            price : new Number(product.price / 100),
            unit : unitText
        }
    },
    /* 存储cookie
    ** obj {array} 每一项为object
    ** {"key": key, "value": value, "exdays": exdeys}
    ** exdays: 过期时间，以天为单位，默认为365天
     */
    setCookie : function(obj) {
        if( typeof obj != "object" ) return;
        if( obj.length < 1 ) return;
        for( var i = 0, length = obj.length; i < length; i++ ) {
            var parmar = obj[i],
                d = new Date(),
                exdays = parmar.exdays || 365;
            d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
            var expires = "expires=" + d.toUTCString();
            document.cookie = parmar.key + "=" + encodeURI(parmar.value) + ";path=/; " + expires;
        }
    },
    /* 获取cookie
    ** obj: {String} 单一key值，返回结果字符串
    ** obj: {Array} key值列表 返回Object
    */
    getCookie : function(obj) {
        var result;
        if(typeof obj === "string") {
            var arr,
                reg = new RegExp("(^| )"+ obj +"=([^;]*)(;|$)");
            if(arr = document.cookie.match(reg)) {
                result = decodeURI(arr[2]);
            } else {
                result = null;
            }
        } else {
            result = {};
            for(var i = 0, len = obj.length; i < len; i++) {
                var arr,
                    key = obj[i],
                    reg = new RegExp("(^| )"+ key +"=([^;]*)(;|$)");
                if(arr = document.cookie.match(reg)) {
                    result[key] = decodeURI(arr[2]);
                } else {
                    result[key] = null;
                }
            }
        }
        return result;
    },
    /* 将位置信息保存到服务器 */
    savePositionInfo2Service : function(obj) {
        Mafw.service.s("saveProvinceInfo", {
            data : obj,
            beforeSend : function(){},
            success : function(data) {},
            error : function(){}
        });
    },
    /* 检测当前系统
    ** sys: 系统（android|ios）
    ** version: 型号（android）
    ** broswer: 浏览器（微信浏览器）
     */
    getSysVersion : function(){
        var sysVersion = {};
        var u = navigator.userAgent;
        if( u.indexOf('Android') > -1 || u.indexOf('Adr') > -1 ) {
            sysVersion.sys = "android";
            sysVersion.version = u.substr(u.indexOf('Android') + 8, 3);
        } else if (!!u.match(/\(i[^;]+;( U;)? CPU.+Mac OS X/)) {
            sysVersion.sys = "ios";
        }
        if( u.match(/MicroMessenger/i) ) {
            sysVersion.broswer = "wx";
        }
        return sysVersion;
    },
    /* 检测当前设备横竖屏状态
    ** -1： 横屏
    ** 1：竖屏
     */
    watchOrientationChange : function(){
        var flag = 1;
        if ("orientation" in window) {
            switch (window.orientation) {
                case 90:
                case -90:
                    flag = -1;
                    break;
                case 0:
                case 180:
                    flag = 1;
                    break;
                default:
            }
        } else {
            flag = window.innerHeight > window.innerWidth ? 1 : -1
        }
        return flag
    },
    /* 配置WX_SDK的config
    ** apiList: 需要调用接口的array集合
    ** callBack: 回调
    */
    setWx_Config : function(apiList, callBack) {
        var href = location.href;
        href = encodeURIComponent(href.indexOf("#") != -1 ? href.split("#")[0] : href);
        Mafw.service.s("setWx_Config", {
            data : { "url" : href, reqId : Mafw.constants.project},
            beforeSend : function(){

            },
            success : function(data) {
                if( data.success && data.data ) {
                    var configMsg = data.data;
                    wx.config({
                        debug : false,
                        appId : configMsg.appId,
                        timestamp : configMsg.timestamp,
                        nonceStr : configMsg.nonceStr,
                        signature : configMsg.signature,
                        jsApiList : apiList
                    });
                    // wx.config({
                    // 	debug : true,
                    // 	appId : "wx4476a2179bb0e190",
                    // 	timestamp : "1483023043",
                    // 	nonceStr : "963dc719d69d4e18accc",
                    // 	signature : "4C2F224CB400B48C2A20C3EADC47AF8236B4402F",
                    // 	jsApiList : apiList
                    // });
                    if( typeof callBack == "function" ) {
                        callBack(configMsg.appId, href);
                    }
                } else {
                    Y_TOOL.Toast.show(data.msg);
                }
            }
        })
    },
    /* 设置分享层级 */
    setShareLevel : function(){
        var urlParams = this.getParams();
        if( !urlParams.SHARE_LEVEL ) return '1';
        var level = parseInt(urlParams.SHARE_LEVEL);
        level += 1;
        return level;
    },
    /* 分享事件统计
    ** label: 标签
    ** argus: 参数 {object}
    */
    sharePage : function(label, argus){
        // TODO 创建trackEvent
        Y_TOOL.onEvent("分享"+ document.title, label ? label : "", argus);
    },
    /* 个位数补0 */
    repair0 : function(num) {
        return (num < 10 ? ("0" + num) : num);
    },
    /* 跳转登录
    ** isReplace: 是否删除记录
    */
    goLogin : function(isReplace) {
        var oldPath = location.href,
            newPath = Mafw.constants.basePath + "/pages/login.html?oldPath=" + encodeURIComponent(oldPath);
        isReplace
            ? location.replace(newPath)
            : location.href = newPath;
    },
    /* 跳转链接
    ** isReplace: 是否删除记录
    ** isHasParams: 目标链接是否含有参数
    */
    goUrl : function(url, isReplace, isHasParams) {
        var oldPath   = location.href,
            urlSymbol = (isHasParams ? "&" : "?"),
            newUrl    = url + urlSymbol + "oldPath=" + encodeURIComponent(oldPath);
        isReplace
            ? location.replace(newUrl)
            : location.href = newUrl;
    },
    back2Referer : function() {
        var params = this.getParams();
        if(params && params.oldPath){
            location.replace(decodeURIComponent(params.oldPath));
            return true;
        }
        return false;
    },
    /*
    ** 函数防抖
    ** fn：真正执行的函数
    ** delay：延迟时间
    ** rightNow：是否立即执行
    */
    debounce : function(fn, delay, rightNow) {
        var timer, argus, context, timestamp;
        var later = function() {
            var diff = Y_TOOL.DateFunc.getTimeStamp() - timestamp;
            if(diff < delay && diff >= 0) {
                timer = setTimeout(later, delay - diff);
            } else {
                timer = null;
                fn.apply(context, argus);
            }
        };
        return function() {
            argus = arguments,
                context = this,
                timestamp = Y_TOOL.DateFunc.getTimeStamp();
            var doit = !timer && rightNow;
            if(!timer) {
                timer = setTimeout(later, delay);
            }
            if(doit) {
                fn.apply(context, argus);
            }
        }
    },
    throttle : function(fn, delay, options) {
        var context, args, result;
        var timeout = null;
        var previous = 0;
        if (!options) options = {};
        var later = function() {
            previous = options.leading === false ? 0 : Y_TOOL.DateFunc.getTimeStamp();
            timeout = null;
            result = fn.apply(context, args);
            if (!timeout) context = args = null;
        };
        return function() {
            var now = Y_TOOL.DateFunc.getTimeStamp();
            if (!previous && options.leading === false) previous = now;
            var remaining = delay - (now - previous);
            context = this;
            args = arguments;
            if (remaining <= 0 || remaining > delay) {
                if (timeout) {
                    clearTimeout(timeout);
                    timeout = null;
                }
                previous = now;
                result = fn.apply(context, args);
                if (!timeout) context = args = null;
            } else if (!timeout && options.trailing !== false) {
                timeout = setTimeout(later, remaining);
            }
            return result;
        };
    },
    loadScript : function(url, fn) {
        var scriptFile = document.createElement("script");
        scriptFile.src = url;
        scriptFile.onload = function() {
            fn.apply(null);
        };
        var head = document.head;
        head.appendChild(scriptFile, head.firstChild);
    },
    onEvent : function(EventName, EventId, MapKv){
        if(arguments.length < 2) return ;
        var timer_TD = null,
            timer_GA = null,
            MapKv = MapKv ? Y_TOOL.ObjectFunc.extendObj(MapKv, {"channel": CHANNEL}) : {"channel": CHANNEL};
        if(typeof TDAPP === "undefined") {
            timer_TD = setTimeout(function(){
                TDAPP.onEvent(EventName, EventId, MapKv);
                clearTimeout(timer_TD);
            }, 6000);
        } else {
            TDAPP.onEvent(EventName, EventId, MapKv);
        }
        if(typeof ga === void 0) {
            timer_GA = setTimeout(function(){
                ga("send", {
                    hitType: "event",
                    eventCategory: "点击事件",
                    eventAction: EventName,
                    eventLabel: EventId
                });
                clearTimeout(timer_GA);
            }, 6000);
        } else {
            ga("send", {
                hitType: "event",
                eventCategory: "点击事件",
                eventAction: EventName,
                eventLabel: EventId
            });
        }
    }
};

var ZhengEr = {
    docIconPath: Mafw.constants.basePath,
    default_doctor: "/static/images/zhenger/default_doctor.png",
    getMyTreatCard: function(callBack) {
        Mafw.service.s("myTreatCard", {
            success: function(data) {
                if (data.success && data.data.length > 0) {
                    (typeof callBack == "function") && callBack(data.data);
                    Y_TOOL.hideLoading("mark");
                } else {
                    // TODO 错误处理
                    Y_TOOL.hideLoading("mark");
                }
            }
        });
    },
    // 取消预约
    cancelAppoint: function(hosId, hosCode, serialNumber, callBack) {
        Mafw.service.s("cancelAppoint", {
            type: "post",
            contentType: "application/json",
            data: JSON.stringify({
                hospitalCode: hosCode,
                hospitalId: hosId,
                planId: serialNumber
            }),
            success: function(data) {
                if (data.success) {
                    // TODO 取消预约成功
                    Y_TOOL.hideLoading("mark");
                    (typeof callBack == "function") && callBack();
                }
            }
        })
    },
    // 就诊卡格式转换
    transformCardFormat: function(card) {
        if (card.length < 1) return;
        // var firstHalf = card.slice(0, -4);
        // var replaceReg = new RegExp(firstHalf);
        // card = card.replace(replaceReg, "");
        var spaceTotal = card.length / 4;
        var newCard = "";
        for (var i = 0; i < spaceTotal; i++) {
            newCard += card.substr(i * 4, 4);
            newCard += " ";
        };
        return newCard;
    }
};

var TalkingData = {
    _https: {
        http: "http://sdk.talkingdata.com/app/h5/v1",
        https: "https://jic.talkingdata.com/app/h5/v1"
    },
    _loadTDJsFile: function() {
        var _self = this,
            script = document.createElement("script"),
            currentHttp = ((location.href.indexOf("https") == -1) ? _self._https.http : _self._https.https);
        script.src = currentHttp + "?appid=" + Mafw.constants.TalkingDataFlag;
        script.type = "text/javascript";
        script.onload = function() {
            sessionStorage.__TD_td_channel = CHANNEL;
        };
        document.addEventListener("DOMContentLoaded", function() {
            document.body.appendChild(script);
        });
        return this;
    },
    _loadGAJsFile: function() {
        (function(i, s, o, g, r, a, m) {
            i['GoogleAnalyticsObject'] = r;
            i[r] = i[r] ||
                function() {
                    (i[r].q = i[r].q || []).push(arguments)
                }, i[r].l = 1 * new Date();
            a = s.createElement(o), m = s.getElementsByTagName(o)[0];
            a.async = 1;
            a.src = g;
            m.parentNode.insertBefore(a, m);
            a.onload = function() {
                ga("send", {
                    hitType: "event",
                    eventCategory: "自动事件",
                    eventAction: "进入页面",
                    eventLabel: document.title || document.getElementsByTagName("title")[0].innerHTML
                });
            }
        })(window, document, 'script', 'https://www.google-analytics.com/analytics.js', 'ga');

        ga('create', Mafw.constants.GA_Router, 'auto');
        ga('send', 'pageview');
        return this;
    },
    _init: function() {
        this._loadTDJsFile()._loadGAJsFile();
    }
};

// 微信分享

var Wx_share = function(opition, apiList) {
    apiList = apiList || [];
    option = opition || {};
    LEVEL = Y_TOOL.setShareLevel();
    this.default_config = {
        title: document.title || document.getElementsByTagName("title")[0].innerHTML, // 分享标题
        desc: "全程一站式养护体验，让我们陪宝宝共同成长", // 分享描述
        link: location.href.replace(/SHARE_LEVEL=(\d+)(&|$)/g, "") + (location.href.indexOf("?") === -1 ? "?" : "&") + "SHARE_LEVEL=" + LEVEL, // 分享链接
        imgUrl: Mafw.constants.basePath + "/static/images/icon/YiErLogo.png", // 分享图标
        type: "",   // 分享类型,music、video或link，不填默认为link
        dataUrl: "", // 如果type是music或video，则要提供数据链接，默认为空
        success: function() {
            Y_TOOL.Toast.show("分享成功");
        },
        cancel: function() {
            // 用户取消分享后执行的回调函数
            Y_TOOL.Toast.show("您已取消分享");
        }
    };
    this.apiList = ["onMenuShareTimeline", "onMenuShareAppMessage"].concat(apiList);
    this.config = Y_TOOL.ObjectFunc.extendObj(this.default_config, option);

};
Wx_share.prototype._checkWxSDK = function() {
    var scripts = document.getElementsByTagName("script");

    for(var i = 0; i < scripts.length; i++) {

        // 如果页面内已经存在src为jweixin-1.0.0.js的js文件，则认为是旧配置，不需再执行新配置
        if(new RegExp("jweixin-1.0.0.js").test(scripts[i].src)) {
            return true;
            break ;
        }
    }

    return false;
}
Wx_share.prototype._loadWxSDK = function() {
    var _self         = this,
        _script       = document.createElement("script"),
        _head         = document.head || document.getElementsByTagName("head")[0],
        configFunc    = this._doConfig,
        docReadyState = document.readyState;
    _script.type = "text/javascript";
    _script.async = false;
    _script.defer = false;

    _script.onload = function() {
        configFunc.apply(_self);
    }
    _script.src = "https://res.wx.qq.com/open/js/jweixin-1.0.0.js";

    // 防止二次配置的时候DOMContentLoaded事件已经完成，不会再次调用_doConfig
    if(docReadyState === "complete" || docReadyState === "interactive") {
        if(_self._checkWxSDK()) {
            configFunc.apply(_self);
        } else {
            _head.appendChild(_script);
        }

    } else {
        document.addEventListener("DOMContentLoaded", function() {
            if(!_self._checkWxSDK()) {
                _head.appendChild(_script);
            }
        });
    }
};
Wx_share.prototype._doConfig = function() {
    // apiList和config都需要复制一份，防止wx.ready之后被篡改
    var list = this.apiList,
        api = list.slice(0),
        opt = Y_TOOL.ObjectFunc.extendObj({}, this.config);

    Y_TOOL.setWx_Config(api, function() {
        wx.ready(function() {

            for(var i = 0; i < list.length; i++) {
                wx[list[i]](opt);
            }
        })
    })
};
Wx_share.prototype._init = function() {
    this._loadWxSDK();
};

// 一系列自执行函数
(function() {

    // 初始化微信分享
    var wxShare = new Wx_share();
    wxShare._init();


    // 设置渠道
    var channel = Y_TOOL.getCookie("userChannel") || "WECHAT";
    if(channel === "WECHAT" && !Y_TOOL.getSysVersion().broswer) channel = "YIERWAP";
    if(CHANNEL_WRAP[channel]) {
        CHANNEL = CHANNEL_WRAP[channel];
    }

    // 创建TalkingData文件
    TalkingData._init();

    var refreshSession = function(){
        var timer, fn;
        fn = function(){
            timer = setTimeout(function(){
                Mafw.service.s("userInfo", {
                    beforeSend: function(){},
                    success: function(){},
                    error: function(){},
                    complete: function(){
                        fn();
                    }
                })
            }, 1000 * 60 * 20);
        }
        return fn;
    }()();


    // 幼安宝渠道隐藏产品入口
    var hideProductEntranceWithYouAnBao = function() {
        if(CHANNEL === "YOUANBAO") {
            $(".js_youAnBao_hide").remove();
        }
        setTimeout(function() {
            $(".i-footer").addClass("footer-show");
        }, 0);
    }
    if($("body").length > 0) {
        hideProductEntranceWithYouAnBao();
    } else {
        document.addEventListener("DOMContentLoaded", function(){
            hideProductEntranceWithYouAnBao();
        });
    }
}())

