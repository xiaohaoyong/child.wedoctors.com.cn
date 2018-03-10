var FormWizard = function () {
    return {
        init: function () {
            if (!jQuery().bootstrapWizard) {
                return;
            }
            var form = $('#fileupload');
            var error = $('.alert-danger', form);
            var success = $('.alert-success', form);
            form.validate({
                doNotHideMessage: true, //this option enables to show the error/success messages on tab switch.
                errorElement: 'span', //default input error message container
                errorClass: 'help-block help-block-error', // default input error message class
                focusInvalid: false, //
                // ignore:false,
                rules: {
                    'Article[title]': {
                        required: true,
                        minlength: 2,
                        maxlength: 30
                    },
                    'Article[catid]': {
                        required: true
                    },
                    'Article[tag]': {
                        required: true
                    },

                    'Article[mediaid]': {
                        required: true
                    },
                    description1: {
                        minlength: 20,
                        maxlength: 300,
                        required: true
                    },  description2: {
                        minlength: 20,
                        maxlength: 300,
                        required: true
                    },  description3: {
                        minlength: 20,
                        maxlength: 300,
                        required: true
                    },  description4: {
                        minlength: 20,
                        maxlength: 300,
                        required: true
                    },  description5: {
                        minlength: 20,
                        maxlength: 300,
                        required: true
                    },  description6: {
                        minlength: 20,
                        maxlength: 300,
                        required: true
                    },  description7: {
                        minlength: 20,
                        maxlength: 300,
                        required: true
                    },  description8: {
                        minlength: 20,
                        maxlength: 300,
                        required: true
                    },  description9: {
                        minlength: 20,
                        maxlength: 300,
                        required: true
                    },  description10: {
                        minlength: 20,
                        maxlength: 300,
                        required: true
                    },  description11: {
                        minlength: 20,
                        maxlength: 300,
                        required: true
                    },  description12: {
                        minlength: 20,
                        maxlength: 300,
                        required: true
                    },  description13: {
                        minlength: 20,
                        maxlength: 300,
                        required: true
                    },  description14: {
                        minlength: 20,
                        maxlength: 300,
                        required: true
                    },  description15: {
                        minlength: 20,
                        maxlength: 300,
                        required: true
                    },  description16: {
                        minlength: 20,
                        maxlength: 300,
                        required: true
                    },  description17: {
                        minlength: 20,
                        maxlength: 300,
                        required: true
                    },  description18: {
                        minlength: 20,
                        maxlength: 300,
                        required: true
                    },  description19: {
                        minlength: 20,
                        maxlength: 300,
                        required: true
                    },  description20: {
                        minlength: 20,
                        maxlength: 300,
                        required: true
                    },  description21: {
                        minlength: 20,
                        maxlength: 300,
                        required: true
                    },  description22: {
                        minlength: 20,
                        maxlength: 300,
                        required: true
                    },  description23: {
                        minlength: 20,
                        maxlength: 300,
                        required: true
                    },  description24: {
                        minlength: 20,
                        maxlength: 300,
                        required: true
                    },  description25: {
                        minlength: 20,
                        maxlength: 300,
                        required: true
                    },  description26: {
                        minlength: 20,
                        maxlength: 300,
                        required: true
                    },  description27: {
                        minlength: 20,
                        maxlength: 300,
                        required: true
                    },  description28: {
                        minlength: 20,
                        maxlength: 300,
                        required: true
                    },  description29: {
                        minlength: 20,
                        maxlength: 300,
                        required: true
                    },  description30: {
                        minlength: 20,
                        maxlength: 300,
                        required: true
                    },  description31: {
                        minlength: 20,
                        maxlength: 300,
                        required: true
                    },  description32: {
                        minlength: 20,
                        maxlength: 300,
                        required: true
                    },  description33: {
                        minlength: 20,
                        maxlength: 300,
                        required: true
                    },  description34: {
                        minlength: 20,
                        maxlength: 300,
                        required: true
                    },  description35: {
                        minlength: 20,
                        maxlength: 300,
                        required: true
                    },  description36: {
                        minlength: 20,
                        maxlength: 300,
                        required: true
                    },  description37: {
                        minlength: 20,
                        maxlength: 300,
                        required: true
                    },  description38: {
                        minlength: 20,
                        maxlength: 300,
                        required: true
                    },  description39: {
                        minlength: 20,
                        maxlength: 300,
                        required: true
                    },  description40: {
                        minlength: 20,
                        maxlength: 300,
                        required: true
                    },  description41: {
                        minlength: 20,
                        maxlength: 300,
                        required: true
                    },  description42: {
                        minlength: 20,
                        maxlength: 300,
                        required: true
                    },  description43: {
                        minlength: 20,
                        maxlength: 300,
                        required: true
                    },  description44: {
                        minlength: 20,
                        maxlength: 300,
                        required: true
                    },  description45: {
                        minlength: 20,
                        maxlength: 300,
                        required: true
                    },  description46: {
                        minlength: 20,
                        maxlength: 300,
                        required: true
                    },  description47: {
                        minlength: 20,
                        maxlength: 300,
                        required: true
                    },  description48: {
                        minlength: 20,
                        maxlength: 300,
                        required: true
                    },  description49: {
                        minlength: 20,
                        maxlength: 300,
                        required: true
                    },  description50: {
                        minlength: 20,
                        maxlength: 300,
                        required: true
                    },
                    files1 :{
                        required: {
                            depends:function(){
                                return ($("#img1").val()=='');
                            }
                        }
                    }, files2 :{
                        required: {
                            depends:function(){
                                return ($("#img2").val()=='');
                            }
                        }
                    }, files3 :{
                        required: {
                            depends:function(){
                                return ($("#img3").val()=='');
                            }
                        }
                    }, files4 :{
                        required: {
                            depends:function(){
                                return ($("#img4").val()=='');
                            }
                        }
                    }, files5 :{
                        required: {
                            depends:function(){
                                return ($("#img5").val()=='');
                            }
                        }
                    }, files6 :{
                        required: {
                            depends:function(){
                                return ($("#img6").val()=='');
                            }
                        }
                    }, files7 :{
                        required: {
                            depends:function(){
                                return ($("#img7").val()=='');
                            }
                        }
                    }, files8 :{
                        required: {
                            depends:function(){
                                return ($("#img8").val()=='');
                            }
                        }
                    }, files9 :{
                        required: {
                            depends:function(){
                                return ($("#img9").val()=='');
                            }
                        }
                    }, files10 :{
                        required: {
                            depends:function(){
                                return ($("#img10").val()=='');
                            }
                        }
                    }, files11 :{
                        required: {
                            depends:function(){
                                return ($("#img11").val()=='');
                            }
                        }
                    }, files12 :{
                        required: {
                            depends:function(){
                                return ($("#img12").val()=='');
                            }
                        }
                    }, files13 :{
                        required: {
                            depends:function(){
                                return ($("#img13").val()=='');
                            }
                        }
                    }, files14 :{
                        required: {
                            depends:function(){
                                return ($("#img14").val()=='');
                            }
                        }
                    }, files15 :{
                        required: {
                            depends:function(){
                                return ($("#img15").val()=='');
                            }
                        }
                    }, files16 :{
                        required: {
                            depends:function(){
                                return ($("#img16").val()=='');
                            }
                        }
                    }, files17 :{
                        required: {
                            depends:function(){
                                return ($("#img17").val()=='');
                            }
                        }
                    }, files18 :{
                        required: {
                            depends:function(){
                                return ($("#img18").val()=='');
                            }
                        }
                    }, files19 :{
                        required: {
                            depends:function(){
                                return ($("#img19").val()=='');
                            }
                        }
                    }, files20 :{
                        required: {
                            depends:function(){
                                return ($("#img20").val()=='');
                            }
                        }
                    }, files21 :{
                        required: {
                            depends:function(){
                                return ($("#img21").val()=='');
                            }
                        }
                    }, files22 :{
                        required: {
                            depends:function(){
                                return ($("#img22").val()=='');
                            }
                        }
                    }, files23 :{
                        required: {
                            depends:function(){
                                return ($("#img23").val()=='');
                            }
                        }
                    }, files24 :{
                        required: {
                            depends:function(){
                                return ($("#img24").val()=='');
                            }
                        }
                    }, files25 :{
                        required: {
                            depends:function(){
                                return ($("#img25").val()=='');
                            }
                        }
                    }, files26 :{
                        required: {
                            depends:function(){
                                return ($("#img26").val()=='');
                            }
                        }
                    }, files27 :{
                        required: {
                            depends:function(){
                                return ($("#img27").val()=='');
                            }
                        }
                    }, files28 :{
                        required: {
                            depends:function(){
                                return ($("#img28").val()=='');
                            }
                        }
                    }, files29 :{
                        required: {
                            depends:function(){
                                return ($("#img29").val()=='');
                            }
                        }
                    }, files30 :{
                        required: {
                            depends:function(){
                                return ($("#img30").val()=='');
                            }
                        }
                    }, files31 :{
                        required: {
                            depends:function(){
                                return ($("#img31").val()=='');
                            }
                        }
                    }, files32 :{
                        required: {
                            depends:function(){
                                return ($("#img32").val()=='');
                            }
                        }
                    }, files33 :{
                        required: {
                            depends:function(){
                                return ($("#img33").val()=='');
                            }
                        }
                    }, files34 :{
                        required: {
                            depends:function(){
                                return ($("#img34").val()=='');
                            }
                        }
                    }, files35 :{
                        required: {
                            depends:function(){
                                return ($("#img35").val()=='');
                            }
                        }
                    }, files36 :{
                        required: {
                            depends:function(){
                                return ($("#img36").val()=='');
                            }
                        }
                    }, files37 :{
                        required: {
                            depends:function(){
                                return ($("#img37").val()=='');
                            }
                        }
                    }, files38 :{
                        required: {
                            depends:function(){
                                return ($("#img38").val()=='');
                            }
                        }
                    }, files39 :{
                        required: {
                            depends:function(){
                                return ($("#img39").val()=='');
                            }
                        }
                    }, files40 :{
                        required: {
                            depends:function(){
                                return ($("#img40").val()=='');
                            }
                        }
                    }, files41 :{
                        required: {
                            depends:function(){
                                return ($("#img41").val()=='');
                            }
                        }
                    }, files42 :{
                        required: {
                            depends:function(){
                                return ($("#img42").val()=='');
                            }
                        }
                    }, files43 :{
                        required: {
                            depends:function(){
                                return ($("#img43").val()=='');
                            }
                        }
                    }, files44 :{
                        required: {
                            depends:function(){
                                return ($("#img44").val()=='');
                            }
                        }
                    }, files45 :{
                        required: {
                            depends:function(){
                                return ($("#img45").val()=='');
                            }
                        }
                    }, files46 :{
                        required: {
                            depends:function(){
                                return ($("#img46").val()=='');
                            }
                        }
                    }, files47 :{
                        required: {
                            depends:function(){
                                return ($("#img47").val()=='');
                            }
                        }
                    }, files48 :{
                        required: {
                            depends:function(){
                                return ($("#img48").val()=='');
                            }
                        }
                    }, files49 :{
                        required: {
                            depends:function(){
                                return ($("#img49").val()=='');
                            }
                        }
                    }, files50 :{
                        required: {
                            depends:function(){
                                return ($("#img50").val()=='');
                            }
                        }
                    }
                },
                messages: {
                    'Article[title]': {
                        required: '必填字段',
                        minlength: jQuery.validator.format("请输入至少{0}个字符"),
                        maxlength: jQuery.validator.format("请输入最多不超过{0}个字符")
                    },
                    'Article[tag]': {
                        required: '文章标签为必填'
                    },
                    'Article[catid]': {
                        required: '文章分类必选'
                    },
                    'Article[mediaid]': {
                        required: '媒体号为必选'
                    },
                    'Media[name]': {
                        required: '必选字段'
                    },
                    tagids: {
                        required: '必选字段'
                    },
                    'description1': {
                        required: '必填字段',
                        minlength: jQuery.validator.format("请输入至少{0}个字符"),
                        maxlength: jQuery.validator.format("请输入最多不超过{0}个字符")
                    }, 'description2': {
                        required: '必填字段',
                        minlength: jQuery.validator.format("请输入至少{0}个字符"),
                        maxlength: jQuery.validator.format("请输入最多不超过{0}个字符")
                    }, 'description3': {
                        required: '必填字段',
                        minlength: jQuery.validator.format("请输入至少{0}个字符"),
                        maxlength: jQuery.validator.format("请输入最多不超过{0}个字符")
                    }, 'description4': {
                        required: '必填字段',
                        minlength: jQuery.validator.format("请输入至少{0}个字符"),
                        maxlength: jQuery.validator.format("请输入最多不超过{0}个字符")
                    }, 'description5': {
                        required: '必填字段',
                        minlength: jQuery.validator.format("请输入至少{0}个字符"),
                        maxlength: jQuery.validator.format("请输入最多不超过{0}个字符")
                    }, 'description6': {
                        required: '必填字段',
                        minlength: jQuery.validator.format("请输入至少{0}个字符"),
                        maxlength: jQuery.validator.format("请输入最多不超过{0}个字符")
                    }, 'description7': {
                        required: '必填字段',
                        minlength: jQuery.validator.format("请输入至少{0}个字符"),
                        maxlength: jQuery.validator.format("请输入最多不超过{0}个字符")
                    }, 'description8': {
                        required: '必填字段',
                        minlength: jQuery.validator.format("请输入至少{0}个字符"),
                        maxlength: jQuery.validator.format("请输入最多不超过{0}个字符")
                    }, 'description9': {
                        required: '必填字段',
                        minlength: jQuery.validator.format("请输入至少{0}个字符"),
                        maxlength: jQuery.validator.format("请输入最多不超过{0}个字符")
                    }, 'description10': {
                        required: '必填字段',
                        minlength: jQuery.validator.format("请输入至少{0}个字符"),
                        maxlength: jQuery.validator.format("请输入最多不超过{0}个字符")
                    }, 'description11': {
                        required: '必填字段',
                        minlength: jQuery.validator.format("请输入至少{0}个字符"),
                        maxlength: jQuery.validator.format("请输入最多不超过{0}个字符")
                    }, 'description12': {
                        required: '必填字段',
                        minlength: jQuery.validator.format("请输入至少{0}个字符"),
                        maxlength: jQuery.validator.format("请输入最多不超过{0}个字符")
                    }, 'description13': {
                        required: '必填字段',
                        minlength: jQuery.validator.format("请输入至少{0}个字符"),
                        maxlength: jQuery.validator.format("请输入最多不超过{0}个字符")
                    }, 'description14': {
                        required: '必填字段',
                        minlength: jQuery.validator.format("请输入至少{0}个字符"),
                        maxlength: jQuery.validator.format("请输入最多不超过{0}个字符")
                    }, 'description15': {
                        required: '必填字段',
                        minlength: jQuery.validator.format("请输入至少{0}个字符"),
                        maxlength: jQuery.validator.format("请输入最多不超过{0}个字符")
                    }, 'description16': {
                        required: '必填字段',
                        minlength: jQuery.validator.format("请输入至少{0}个字符"),
                        maxlength: jQuery.validator.format("请输入最多不超过{0}个字符")
                    }, 'description17': {
                        required: '必填字段',
                        minlength: jQuery.validator.format("请输入至少{0}个字符"),
                        maxlength: jQuery.validator.format("请输入最多不超过{0}个字符")
                    }, 'description18': {
                        required: '必填字段',
                        minlength: jQuery.validator.format("请输入至少{0}个字符"),
                        maxlength: jQuery.validator.format("请输入最多不超过{0}个字符")
                    }, 'description19': {
                        required: '必填字段',
                        minlength: jQuery.validator.format("请输入至少{0}个字符"),
                        maxlength: jQuery.validator.format("请输入最多不超过{0}个字符")
                    }, 'description20': {
                        required: '必填字段',
                        minlength: jQuery.validator.format("请输入至少{0}个字符"),
                        maxlength: jQuery.validator.format("请输入最多不超过{0}个字符")
                    }, 'description21': {
                        required: '必填字段',
                        minlength: jQuery.validator.format("请输入至少{0}个字符"),
                        maxlength: jQuery.validator.format("请输入最多不超过{0}个字符")
                    }, 'description22': {
                        required: '必填字段',
                        minlength: jQuery.validator.format("请输入至少{0}个字符"),
                        maxlength: jQuery.validator.format("请输入最多不超过{0}个字符")
                    }, 'description23': {
                        required: '必填字段',
                        minlength: jQuery.validator.format("请输入至少{0}个字符"),
                        maxlength: jQuery.validator.format("请输入最多不超过{0}个字符")
                    }, 'description24': {
                        required: '必填字段',
                        minlength: jQuery.validator.format("请输入至少{0}个字符"),
                        maxlength: jQuery.validator.format("请输入最多不超过{0}个字符")
                    }, 'description25': {
                        required: '必填字段',
                        minlength: jQuery.validator.format("请输入至少{0}个字符"),
                        maxlength: jQuery.validator.format("请输入最多不超过{0}个字符")
                    }, 'description26': {
                        required: '必填字段',
                        minlength: jQuery.validator.format("请输入至少{0}个字符"),
                        maxlength: jQuery.validator.format("请输入最多不超过{0}个字符")
                    }, 'description27': {
                        required: '必填字段',
                        minlength: jQuery.validator.format("请输入至少{0}个字符"),
                        maxlength: jQuery.validator.format("请输入最多不超过{0}个字符")
                    }, 'description28': {
                        required: '必填字段',
                        minlength: jQuery.validator.format("请输入至少{0}个字符"),
                        maxlength: jQuery.validator.format("请输入最多不超过{0}个字符")
                    }, 'description29': {
                        required: '必填字段',
                        minlength: jQuery.validator.format("请输入至少{0}个字符"),
                        maxlength: jQuery.validator.format("请输入最多不超过{0}个字符")
                    }, 'description30': {
                        required: '必填字段',
                        minlength: jQuery.validator.format("请输入至少{0}个字符"),
                        maxlength: jQuery.validator.format("请输入最多不超过{0}个字符")
                    }, 'description31': {
                        required: '必填字段',
                        minlength: jQuery.validator.format("请输入至少{0}个字符"),
                        maxlength: jQuery.validator.format("请输入最多不超过{0}个字符")
                    }, 'description32': {
                        required: '必填字段',
                        minlength: jQuery.validator.format("请输入至少{0}个字符"),
                        maxlength: jQuery.validator.format("请输入最多不超过{0}个字符")
                    }, 'description33': {
                        required: '必填字段',
                        minlength: jQuery.validator.format("请输入至少{0}个字符"),
                        maxlength: jQuery.validator.format("请输入最多不超过{0}个字符")
                    }, 'description34': {
                        required: '必填字段',
                        minlength: jQuery.validator.format("请输入至少{0}个字符"),
                        maxlength: jQuery.validator.format("请输入最多不超过{0}个字符")
                    }, 'description35': {
                        required: '必填字段',
                        minlength: jQuery.validator.format("请输入至少{0}个字符"),
                        maxlength: jQuery.validator.format("请输入最多不超过{0}个字符")
                    }, 'description36': {
                        required: '必填字段',
                        minlength: jQuery.validator.format("请输入至少{0}个字符"),
                        maxlength: jQuery.validator.format("请输入最多不超过{0}个字符")
                    }, 'description37': {
                        required: '必填字段',
                        minlength: jQuery.validator.format("请输入至少{0}个字符"),
                        maxlength: jQuery.validator.format("请输入最多不超过{0}个字符")
                    }, 'description38': {
                        required: '必填字段',
                        minlength: jQuery.validator.format("请输入至少{0}个字符"),
                        maxlength: jQuery.validator.format("请输入最多不超过{0}个字符")
                    }, 'description39': {
                        required: '必填字段',
                        minlength: jQuery.validator.format("请输入至少{0}个字符"),
                        maxlength: jQuery.validator.format("请输入最多不超过{0}个字符")
                    }, 'description40': {
                        required: '必填字段',
                        minlength: jQuery.validator.format("请输入至少{0}个字符"),
                        maxlength: jQuery.validator.format("请输入最多不超过{0}个字符")
                    }, 'description41': {
                        required: '必填字段',
                        minlength: jQuery.validator.format("请输入至少{0}个字符"),
                        maxlength: jQuery.validator.format("请输入最多不超过{0}个字符")
                    }, 'description42': {
                        required: '必填字段',
                        minlength: jQuery.validator.format("请输入至少{0}个字符"),
                        maxlength: jQuery.validator.format("请输入最多不超过{0}个字符")
                    }, 'description43': {
                        required: '必填字段',
                        minlength: jQuery.validator.format("请输入至少{0}个字符"),
                        maxlength: jQuery.validator.format("请输入最多不超过{0}个字符")
                    }, 'description44': {
                        required: '必填字段',
                        minlength: jQuery.validator.format("请输入至少{0}个字符"),
                        maxlength: jQuery.validator.format("请输入最多不超过{0}个字符")
                    }, 'description45': {
                        required: '必填字段',
                        minlength: jQuery.validator.format("请输入至少{0}个字符"),
                        maxlength: jQuery.validator.format("请输入最多不超过{0}个字符")
                    }, 'description46': {
                        required: '必填字段',
                        minlength: jQuery.validator.format("请输入至少{0}个字符"),
                        maxlength: jQuery.validator.format("请输入最多不超过{0}个字符")
                    }, 'description47': {
                        required: '必填字段',
                        minlength: jQuery.validator.format("请输入至少{0}个字符"),
                        maxlength: jQuery.validator.format("请输入最多不超过{0}个字符")
                    }, 'description48': {
                        required: '必填字段',
                        minlength: jQuery.validator.format("请输入至少{0}个字符"),
                        maxlength: jQuery.validator.format("请输入最多不超过{0}个字符")
                    }, 'description49': {
                        required: '必填字段',
                        minlength: jQuery.validator.format("请输入至少{0}个字符"),
                        maxlength: jQuery.validator.format("请输入最多不超过{0}个字符")
                    }, 'description50': {
                        required: '必填字段',
                        minlength: jQuery.validator.format("请输入至少{0}个字符"),
                        maxlength: jQuery.validator.format("请输入最多不超过{0}个字符")
                    },
                    files1: {
                        required: '图片必选',
                    },  files2: {
                        required: '图片必选',
                    },  files3: {
                        required: '图片必选',
                    },  files4: {
                        required: '图片必选',
                    },  files5: {
                        required: '图片必选',
                    },  files6: {
                        required: '图片必选',
                    },  files7: {
                        required: '图片必选',
                    },  files8: {
                        required: '图片必选',
                    },  files9: {
                        required: '图片必选',
                    },  files10: {
                        required: '图片必选',
                    },  files11: {
                        required: '图片必选',
                    },  files12: {
                        required: '图片必选',
                    },  files13: {
                        required: '图片必选',
                    },  files14: {
                        required: '图片必选',
                    },  files15: {
                        required: '图片必选',
                    },  files16: {
                        required: '图片必选',
                    },  files17: {
                        required: '图片必选',
                    },  files18: {
                        required: '图片必选',
                    },  files19: {
                        required: '图片必选',
                    },  files20: {
                        required: '图片必选',
                    },  files21: {
                        required: '图片必选',
                    },  files22: {
                        required: '图片必选',
                    },  files23: {
                        required: '图片必选',
                    },  files24: {
                        required: '图片必选',
                    },  files25: {
                        required: '图片必选',
                    },  files26: {
                        required: '图片必选',
                    },  files27: {
                        required: '图片必选',
                    },  files28: {
                        required: '图片必选',
                    },  files29: {
                        required: '图片必选',
                    },  files30: {
                        required: '图片必选',
                    },  files31: {
                        required: '图片必选',
                    },  files32: {
                        required: '图片必选',
                    },  files33: {
                        required: '图片必选',
                    },  files34: {
                        required: '图片必选',
                    },  files35: {
                        required: '图片必选',
                    },  files36: {
                        required: '图片必选',
                    },  files37: {
                        required: '图片必选',
                    },  files38: {
                        required: '图片必选',
                    },  files39: {
                        required: '图片必选',
                    },  files40: {
                        required: '图片必选',
                    },  files41: {
                        required: '图片必选',
                    },  files42: {
                        required: '图片必选',
                    },  files43: {
                        required: '图片必选',
                    },  files44: {
                        required: '图片必选',
                    },  files45: {
                        required: '图片必选',
                    },  files46: {
                        required: '图片必选',
                    },  files47: {
                        required: '图片必选',
                    },  files48: {
                        required: '图片必选',
                    },  files49: {
                        required: '图片必选',
                    },  files50: {
                        required: '图片必选',
                    }
                },
                errorPlacement: function (error, element) { // render error placement for each input type
                    if (element.parent(".input-group").size() > 0) {
                        error.insertAfter(element.parent(".input-group"));
                    } else if (element.attr("data-error-container")) {
                        error.appendTo(element.attr("data-error-container"));
                    } else if (element.parents('.radio-list').size() > 0) {
                        error.appendTo(element.parents('.radio-list').attr("data-error-container"));
                    } else if (element.parents('.radio-inline').size() > 0) {
                        error.appendTo(element.parents('.radio-inline').attr("data-error-container"));
                    } else if (element.parents('.checkbox-list').size() > 0) {
                        error.appendTo(element.parents('.checkbox-list').attr("data-error-container"));
                    } else if (element.parents('.checkbox-inline').size() > 0) {
                        error.appendTo(element.parents('.checkbox-inline').attr("data-error-container"));
                    } else {
                        error.insertAfter(element); // for other inputs, just perform default behavior
                    }
                },

                //验证
                invalidHandler: function (event, validator) { //display error alert on form submit
                    success.hide();
                    error.show();
                    Metronic.scrollTo(error, -200);
                },

                highlight: function (element) { // hightlight error inputs
                    $(element)
                        .closest('.form-group').removeClass('has-success').addClass('has-error'); // set error class to the control group
                },

                unhighlight: function (element) { // revert the change done by hightlight
                    $(element)
                        .closest('.form-group').removeClass('has-error'); // set error class to the control group
                },

                success: function (label) {
                    if (label.attr("for") == "gender" || label.attr("for") == "payment[]") { // for checkboxes and radio buttons, no need to show OK icon
                        label
                            .closest('.form-group').removeClass('has-error').addClass('has-success');
                        label.remove(); // remove error label here
                    } else { // display success icon for other inputs
                        label
                            .addClass('valid') // mark the current input as valid and display OK icon
                            .closest('.form-group').removeClass('has-error').addClass('has-success'); // set success class to the control group
                    }
                },

                submitHandler: function (form) {
                    // success.show();
                    error.hide();
                    form.submit();
                    //add here some ajax code to submit your form or just call form.submit() if you want to submit the form without ajax
                }

            });


            var displayConfirm = function () {
                $('.tabs .form-control-static', form).each(function () {
                    var input = $('[name="' + $(this).attr("data-display") + '"]', form);
                    var length=$('#new_imgage tr').length;
                    html='<table >';
                    for (var i = 1; i < length; i++) {
                        img=$('#img_src'+i +'> img').attr('src');
                        des=$('#description'+i).val();
                        html+="<tr>" +
                            "<td class='text-center'><span class='badge badge-danger'>"+i+"</span></td>" +
                            "<td><div style='width:100px;height:80px'><img  style='width:80px;height:60px' src="+img+"></div><td>" +
                            "<td>"+des+"<td><tr>";
                    }
                    html+='</table>';
                    $('#text-Article').html(html);
                    if (input.is(":radio")) {
                        input = $('[name="' + $(this).attr("data-display") + '"]:checked', form);
                    }
                    if (input.is(":text") || input.is("textarea")) {
                        $(this).html(input.val());
                    } else if (input.is("select")) {
                        $(this).html(input.find('option:selected').text());
                    } else if (input.is(":radio") && input.is(":checked")) {
                        $(this).html(input.attr("data-title"));
                    } else if ($(this).attr("data-display") == 'payment[]') {
                        var payment = [];
                        $('[name="payment[]"]:checked', form).each(function () {
                            payment.push($(this).attr('data-title'));
                        });
                        $(this).html(payment.join("<br>"));
                    }
                });
            }


            var handleTitle = function (tab, navigation, index) {
                var total = navigation.find('li').length;
                var current = index + 1;
                $('.step-title', $('#form_wizard_1')).text('Step ' + (index + 1) + ' of ' + total);
                // set wizard title
                // set done steps
                jQuery('li', $('#form_wizard_1')).removeClass("done");
                var li_list = navigation.find('li');
                for (var i = 0; i < index; i++) {
                    jQuery(li_list[i]).addClass("done");
                }
                if (current == 1) {
                    $('#form_wizard_1').find('.button-previous').hide();
                } else {
                    $('#form_wizard_1').find('.button-previous').show();
                }

                if (current >= total) {
                    $('#form_wizard_1').find('.button-next').hide();
                    $('#form_wizard_1').find('#next').hide();
                    $('#form_wizard_1').find('.button-submit').show();
                    displayConfirm();
                } else {
                    $('#form_wizard_1').find('.button-next').show();
                    $('#form_wizard_1').find('#next').show();
                    $('#form_wizard_1').find('.button-submit').hide();
                }
                Metronic.scrollTo($('.page-title'));
            }

            // default form wizard
            $('#form_wizard_1').bootstrapWizard({
                'nextSelector': '.button-next',
                'previousSelector': '.button-previous',
                onTabClick: function (tab, navigation, index, clickedIndex) {
                    return false;
                    success.hide();
                    error.hide();
                    if (form.valid() == false) {
                        return false;
                    }
                    handleTitle(tab, navigation, clickedIndex);
                },
                onNext: function (tab, navigation, index) {
                    success.hide();
                    error.hide();

                    if (form.valid() == false) {
                        return false;
                    }

                    handleTitle(tab, navigation, index);
                },
                onPrevious: function (tab, navigation, index) {
                    success.hide();
                    error.hide();

                    handleTitle(tab, navigation, index);
                },
                onTabShow: function (tab, navigation, index) {
                    if (index == 3) {
                        displayConfirm();
                        handleTitle(tab, navigation, index);
                    }
                    var total = navigation.find('li').length;
                    var current = index + 1;
                    var $percent = (current / total) * 100;
                    $('#form_wizard_1').find('.progress-bar').css({
                        width: $percent + '%'
                    });
                }
            });
            $('#form_wizard_1').find('.button-previous').hide();
            $('#form_wizard_1 .button-submit').hide();
            //apply validation on select2 dropdown value change, this only needed for chosen dropdown integration.
            $('#country_list', form).change(function () {
                form.validate().element($(this)); //revalidate the chosen dropdown value and show error or success message for the input
            });
        }
    };
}();