function bindQuarter() {
  $(".level1 > a, .level2 > a").on('click', function () {
    //console.log("aa");
    var subId = $(this).data("id");
    $(subId).slideToggle("normal");

    $(this).find(".weui-cell__ft").toggleClass("weui-cell__ft2");
  });

  $(".level1 :checkbox").on('click', function () {
    //alert($)
    // var checkboxs = $(".level1:checked");
    //console.log($(this).prop("checked"));
    var level1Code = "";
    var level2Code = "";
    var level3Code = "";
    $(".level1 :checked").each(function () {
      //console.log($(this).attr("id"));
      var tmp1 = $(this).data("level1");

      //console.log("level1Code:" + level1Code);
      //console.log("tmp1:" + tmp1);
      if (level1Code != "" && level1Code != tmp1) {
        // console.log("level1Code failed");
        $.alert("只能选一个大类及二级分类");
        return false;
      } else {
        level1Code = tmp1;
      }

      var tmp2 = $(this).data("level2");

      //console.log("level2Code:" + level2Code);
      //console.log("tmp2:" + tmp1);

      if (level2Code != "" && level2Code != tmp2) {
        // console.log("level2Code failed");
        $.alert("只能选一个大类及二级分类");
        return false;
      } else {
        level2Code = tmp2;
      }
      //level3Code = $(this).attr("id");
    });
  });

  var quarters = new Array();
  var quarters2 = new Array();
  $("#btnQuarters").on("click", function () {
    $(".level1 :checked").each(function () {
      var tmp1 = $(this).data("level1");
      var tmp2 = $(this).data("level2");
      var tmp3 = $(this).prop("id");

      //var title1 = $("#" + tmp1).prev("a").find("p").text();
      //var title2 = $("#" + tmp2).prev("a").find("p").text();
      var title3 = $(this).parents("label").find("p").text();

      //quarters.push(title1 + " " + title2 + " " + title3);
      quarters.push(title3);
      quarters2.push(tmp1 + "_" + tmp2 + "_" + tmp3);

      if (title3 == "全部") {
        return false;
      }
    });

    $.closePopup();

    $("#basic_quarters").val(quarters);
    $("#basic_quarters").attr("data-value", quarters2);
    //console.log(quarters);
  });
}



function commonInit() {
  if (typeof (resume) != "undefined" && resume != "") {
    jQuery.each(resume, function (i, val) {
      //text = text + " #Index:" + i + ":" + val;  
      //console.log(i + ":" + val);
      $("#basic_" + i).val(val);
    });
  }

  $(".commonTable").each(function () {
    var type = $(this).data("type").toString();
    var title = $(this).parents(".weui-cell").find('label').text();
    //console.log(title);
    $(this).select({
      'title': "选择" + title,
      'items': commonTable[type]
    });
  });
}

function initLogin() {
  $('#btnLoginValidate').on('click', function () {
    //console.log("aa");
    var phone = $.trim($("#phone").val());
    if (phone == "") {
      $.toptip("请填写您的行动电话", 'error');
      return false;
    }

    var tel1 = /^[0-9]+[-]{0,1}[0-9]+$/;
    if (tel1.test(phone) == false) {
      $.toptip("请正确填写您的行动电话", 'error');
      return false;
    }

    //var id = $(this).data('id');
    // window.pageManager.go(id);
    $.ajax({
      url: handelUrl,
      type: "POST",
      dataType: 'json',
      crossDomain: true,
      data: {
        'func': "checkPhoneExist",
        'phone': phone
      }
    }).done(function (data) {
      //验证失败
      if (data.type == 2) {
        // window.pageManager.go(id);
        $.toptip(buildMessage(data.message), 'error');
      }
      //已注册
      else if (data.type == 3) {
        //window.pageManager.go(id);
        window.pageManager.go("login");

        window.pageManager.setPageAppend(function (obj) {
          //console.log($(obj));
          obj.find("#login_Phone").text(phone);
        });

      }
      //未注册
      else if (data.type == 4) {
        window.pageManager.setPageAppend(function (obj) {
          //console.log($(obj));
          obj.find("#lblPhone").text(phone);
        });

        window.pageManager.go("register");

        //console.log(phone);
        // console.log($("#lblPhone").text());
        //$("#lblPhone").text(phone);
        // console.log($("#lblPhone").text());
      }

    }).fail(function (data) {
      $.toptip('操作失败', 'error');
    });
  });
}

function initRegister() {
  $('#btnRegister').on('click', function () {

    var pwd = $('#password').val();
    var pwdcon = $('#password2').val();
    var phone = $("#lblPhone").text();

    if (pwd == "" || pwdcon == "") {
      $.toptip("密码不能为空", 'error');
      return false;
    } else if (pwd != pwdcon) {
      $.toptip("密码不一致", 'error');
      return false;
    } else if (pwd.length < 6) {
      $.toptip("密码不能少于6位", 'error');
      return false;
    }


    //console.log("aa");
    var id = $(this).data('id');
    // window.pageManager.go(id);
    $.ajax({
      url: handelUrl,
      type: "POST",
      dataType: 'json',
      crossDomain: true,
      data: {
        'func': "registerResume",
        'phone': phone,
        'password': pwd,
        'password2': pwdcon

      }
    }).done(function (data) {
      //验证失败

      if (data.type == 2) {
        // window.pageManager.go(id);
        $.toptip(buildMessage(data.message), 'error');


      }
      //已注册
      else if (data.type == 3) {
        $.toptip(buildMessage(data.message), 'error');
        //window.pageManager.go(id);
      }
      //注册成功
      else if (data.type == 1) {
        // window.pageManager.go(id);

        window.pageManager.setPageAppend(function (obj) {
          //console.log($(obj));
          obj.find("#login_Phone").text(phone);
        });
        window.pageManager.go("login");
        $.toptip("注册成功", 'success');
      }


    }).fail(function (data) {
      $.toptip('操作失败', 'error');;
    });
  });
}

function initLogin() {
  $('#btnLogin').on('click', function () {

    var pwd = $('#login_password').val();

    if (pwd == "") {
      $.toptip("密码不能为空", 'error');
      return false;
    }
    // else if(pwd.length < 6) {
    //     $.toptip("密码不能少于6位", 'error');
    //     return false;
    // }


    //console.log("aa");
    //var id = $(this).data('id');
    // window.pageManager.go(id);
    $.ajax({
      url: handelUrl,
      type: "POST",
      dataType: 'json',
      crossDomain: true,
      data: {
        'func': "loginResume",
        'phone': $("#login_Phone").text(),
        'password': pwd
      }
    }).done(function (data) {
      //验证失败

      if (data.type == 2) {
        // window.pageManager.go(id);
        $.toptip(buildMessage(data.message), 'error');


      }
      //已注册
      else if (data.type == 3) {
        $.toptip(buildMessage(data.message), 'error');
        //window.pageManager.go(id);
      }
      //注册成功
      else if (data.type == 1) {
        //window.pageManager.go(id);

        // window.pageManager.setPageAppend(function(obj) {
        //         //console.log($(obj));
        //     obj.find("#login_Phone").text(phone);
        // });
        resume = data.resultValue[0];
        window.pageManager.go("basic");

      }


    }).fail(function (data) {
      $.toptip('操作失败', 'error');;
    });
  });
}

function initBasic1() {


  commonInit();



  $("#basic_birthday").calendar({
    'dateFormat': 'yyyy-mm-dd'
  });


  $("#basic_house").cityPicker({
    title: "请选择现居地址"
  });

  $('#btnBasic1').on('click', function () {

    var pwd = $('#login_password').val();

    if (pwd == "") {
      $.toptip("密码不能为空", 'error');
      return false;
    }


    //console.log("aa");
    //var id = $(this).data('id');
    // window.pageManager.go(id);
    $.ajax({
      url: handelUrl,
      type: "POST",
      dataType: 'json',
      crossDomain: true,
      data: {
        'func': "submitBasic1",
        'cardno': $("#basic_cardno").val(),
        'name': $("#basic_name").val(),
        'sex': $("#basic_sex").val(),
        'birthday': $("#basic_birthday").val(),
        'nation': $("#basic_nation").val(),
        'educational': $("#basic_educational").val(),
        'political': $("#basic_political").val(),
        'house': $("#basic_house").val(),
        'contractName': $("#basic_contractName").val(),
        'contractPhone': $("#basic_contractPhone").val()
      }
    }).done(function (data) {
      //验证失败

      if (data.type == 2) {
        // window.pageManager.go(id);
        $.toptip(buildMessage(data.message), 'error');
      }
      //已注册
      else if (data.type == 3) {
        $.toptip(buildMessage(data.message), 'error');
        //window.pageManager.go(id);
      }
      //注册成功
      else if (data.type == 1) {
        // window.pageManager.go(id);
        window.pageManager.go("basic2");
      }


    }).fail(function (data) {
      $.toptip('操作失败', 'error');;
    });
  });
}

function initBasic2() {
  commonInit();

  $("#basic_quarters").on("click", function () {
    $("#quartersPopup").popup();
  });

  $("#basic_workingAddress").cityPicker({
    title: "请选择现居地址"
  });

  $('#btnBasic2').on('click', function () {
    $.ajax({
      url: handelUrl,
      type: "POST",
      dataType: 'json',
      crossDomain: true,
      data: {
        'func': "submitBasic2",
        'workingYear': $("#basic_workingYear").val(),
        'workingStatus': $("#basic_workingStatus").val(),
        'joinTime': $("#basic_joinTime").val(),
        'workType': $("#basic_workType").val(),
        'industry': $("#basic_industry").val(),
        'quarters': $("#basic_quarters").data("value"),
        'salary': $("#basic_salary").val(),
        'workingAddress': $("#basic_workingAddress").val()
      }
    }).done(function (data) {
      //验证失败
      if (data.type == 2) {
        // window.pageManager.go(id);
        $.toptip(buildMessage(data.message), 'error');
      }
      //已注册
      else if (data.type == 3) {
        $.toptip(buildMessage(data.message), 'error');
        //window.pageManager.go(id);
      }
      //注册成功
      else if (data.type == 1) {
        // window.pageManager.go(id);
        window.pageManager.go("basic3");
      }


    }).fail(function (data) {
      $.toptip('操作失败', 'error');;
    });
  });
}

function isSkillEmpty(skill) {
  return (skill == "" || skill == "无");
}

function isPhotoUpload(li1, li2) {
  return !(li1 == "none" && li2 == "none");
}
function initBasic3() {
  commonInit();
  initPhotoUpload();
  $("#btnBasic3").on('click', function () {
    
    var current_li = $("weui-uploader__file").find("li[data-upload='1']");
    if(current_li.length > 0) {
      $.toptip("有图片未上传玩，请等待", 'error');
      return;
    }

    var skill1 = $("#basic_skill").val();
    var li1 = $("#li_1").css("background-image");
    var li2 = $("#li_2").css("background-image");
    if(!isSkillEmpty(skill1) && !isPhotoUpload(li1,li2)) {
      $.toptip("技能证书照片未上传", 'error');
      return;
    }

    var skill2 = $("#basic_skill2").val();
    var li3 = $("#li_3").css("background-image");
    var li4 = $("#li_4").css("background-image");
    if(!isSkillEmpty(skill2) && !isPhotoUpload(li3,li4)) {
      $.toptip("技能证书照片未上传", 'error');
      return;
    }

    var skill3 = $("#basic_skill3").val();
    var li5 = $("#li_5").css("background-image");
    var li6 = $("#li_6").css("background-image");
    if(!isSkillEmpty(skill3) && !isPhotoUpload(li5,li6)) {
      $.toptip("技能证书照片未上传", 'error');
      return;
    }

    //if(skill1 == skill2 || skill1 == skill3 || skill2 == skill3) {
    if(!isSkillEmpty(skill1) && !isSkillEmpty(skill2) && skill1 == skill2){
      $.toptip("技能选择重复", 'error');
      return;
    }

    if(!isSkillEmpty(skill1) && !isSkillEmpty(skill3) && skill1 == skill3) {
      $.toptip("技能选择重复", 'error');
      return;
    }

    if(!isSkillEmpty(skill2) && !isSkillEmpty(skill3) && skill2 == skill3){
      $.toptip("技能选择重复", 'error');
      return;
    }

    if(isSkillEmpty(skill1) && isPhotoUpload(li1,li2)) {
      $.toptip("请选择对应技能", 'error');
      return;
    }

    if(isSkillEmpty(skill2) && isPhotoUpload(li3,li4)) {
      $.toptip("请选择对应技能", 'error');
      return;
    }

    if(isSkillEmpty(skill3) && isPhotoUpload(li5,li6)) {
      $.toptip("请选择对应技能", 'error');
      return;
    }

    $.ajax({
      url: handelUrl,
      type: "POST",
      dataType: 'json',
      crossDomain: true,
      data: {
        'func': "submitBasic3",
        'skill': skill1,
        'skill2': skill2,
        'skill3': skill3,
        'li1': li1,
        'li2': li2,
        'li3': li3,
        'li4': li4,
        'li5': li5,
        'li6': li6,
        'hobby': $("#basic_hobby").val()
      }
    }).done(function (data) {
      //验证失败
      if (data.type == 2) {
        // window.pageManager.go(id);
        $.toptip(buildMessage(data.message), 'error');
      }
      //已注册
      else if (data.type == 3) {
        $.toptip(buildMessage(data.message), 'error');
        //window.pageManager.go(id);
      }
      //注册成功
      else if (data.type == 1) {
        // window.pageManager.go(id);
        window.pageManager.go("basic3");
      }


    }).fail(function (data) {
      $.toptip('操作失败', 'error');;
    });
  });
  
}

function initPhotoUpload() {
  var maxsize = 100 * 1024;
  //    用于压缩图片的canvas
  var canvas = document.createElement("canvas");
  var ctx = canvas.getContext('2d');
  //    瓦片canvas
  var tCanvas = document.createElement("canvas");
  var tctx = tCanvas.getContext("2d");

  $(".weui-uploader__file").on("click", function () {
    if ($(this).data("upload") == "2") {
      $("#gallery").show();
      $("#gallery span").css("background-image", $(this).css("background-image"));

      $("#gallery").attr("data-id", "#" + $(this).prop("id"));
    }
  });

  $("#backButton").on("click", function () {
    $("#gallery").hide();
  });


  $("#deleteButton").on("click", function () {
   
    var souceId = $(this).parents(".weui-gallery").attr("data-id");
    $(souceId).attr("data-upload", "0");
    $(souceId).css("background-image","");
    $("#gallery").hide();
  });

  $("#uploaderInput,#uploaderInput2,#uploaderInput3").on('change', function () {
    var reader = new FileReader();
    var _this = this;

    var current_li = $(this).parents(".weui-uploader__bd").find("li[data-upload='0']")[0];
    var file = this.files[0];
    reader.onload = function (e) {
      var result = this.result;
      var img = new Image();
      img.src = result;
      //e.srcElement.value = ""

      //$(current_li).css("background-image", "url(" + result + ")");
      $(current_li).attr("data-upload", "1");
      //如果图片大小小于100kb，则直接上传
      if (result.length <= maxsize) {
        img = null;
        upload(result, file.type, current_li);
        return;
      }
      //      图片加载完毕之后进行压缩，然后上传
      if (img.complete) {
        callback();
      } else {
        img.onload = callback;
      }

      function callback() {
        var data = compress(img);
        uploadPic(data, file.type, current_li);
        img = null;
      }
    };

    reader.readAsDataURL(file);
    $(this).val("");
    //console.log(this.files[0]);
  });


  var uploadPic = function (basestr, type, current_li) {
    var text = window.atob(basestr.split(",")[1]);
    var buffer = new Uint8Array(text.length);
    var pecent = 0,
      loop = null;
    for (var i = 0; i < text.length; i++) {
      buffer[i] = text.charCodeAt(i);
    }
    var blob = getBlob([buffer], type);

    var formdata = getFormData();
    formdata.append('image', blob);
    formdata.append('func', 'uploadPhoto');
    $.ajax({
      type: "post",
      url: handelUrl,
      data: formdata,
      cache: false,
      processData: false,
      contentType: false,
      beforeSend: function (XMLHttpRequest) {
        // $('.swal2-confirm').css({
        //   'background-color': '#c1c1c1',
        //   'border-left-color': '#c1c1c1',
        //   'border-right-color': '#c1c1c1'
        // })
        $(current_li).children("weui-uploader__file-content").html("0%");
      },
      success: function (data) {
        //alert(data)
        $(current_li).attr("data-upload", "2");
        $(current_li).children("weui-uploader__file-content").html("");
        $(current_li).css("background-image", "url(" + data + ")");
        $(current_li).removeClass("weui-uploader__file_status");


        // $info = $(current_li).parents(".weui-uploader").find(".weui-uploader__info");

        // if ($info.html() == "0/2") {
        //   $info.html("1/2");
        // } else if ($info.html() == "1/2") {
        //   $info.html("2/2");
        // }


        //weui-uploader__file-content
      },
      error: function (data) {
        $(current_li).attr("data-upload", "0");
        $(current_li).children("weui-uploader__file-content").html("<i class='weui-icon-warn'></i>");
        //alert(JSON.stringify(data));
      }
    });
  }



  //    使用canvas对大图片进行压缩
  function compress(img) {
    var initSize = img.src.length;
    var width = img.width;
    var height = img.height;
    //如果图片大于四百万像素，计算压缩比并将大小压至400万以下
    var ratio;
    if ((ratio = width * height / 4000000) > 1) {
      ratio = Math.sqrt(ratio);
      width /= ratio;
      height /= ratio;
    } else {
      ratio = 1;
    }
    canvas.width = width;
    canvas.height = height;
    //        铺底色
    ctx.fillStyle = "#fff";
    ctx.fillRect(0, 0, canvas.width, canvas.height);
    //如果图片像素大于100万则使用瓦片绘制
    var count;
    if ((count = width * height / 1000000) > 1) {
      count = ~~(Math.sqrt(count) + 1); //计算要分成多少块瓦片
      //            计算每块瓦片的宽和高
      var nw = ~~(width / count);
      var nh = ~~(height / count);
      tCanvas.width = nw;
      tCanvas.height = nh;
      for (var i = 0; i < count; i++) {
        for (var j = 0; j < count; j++) {
          tctx.drawImage(img, i * nw * ratio, j * nh * ratio, nw * ratio, nh * ratio, 0, 0, nw, nh);
          ctx.drawImage(tCanvas, i * nw, j * nh, nw, nh);
        }
      }
    } else {
      ctx.drawImage(img, 0, 0, width, height);
    }
    //进行最小压缩
    var ndata = canvas.toDataURL('image/jpeg', 0.1);
    console.log('压缩前：' + initSize);
    console.log('压缩后：' + ndata.length);
    console.log('压缩率：' + ~~(100 * (initSize - ndata.length) / initSize) + "%");
    tCanvas.width = tCanvas.height = canvas.width = canvas.height = 0;
    return ndata;
  }

  function getBlob(buffer, format) {
    try {
      return new Blob(buffer, {
        type: format
      });
    } catch (e) {
      var bb = new(window.BlobBuilder || window.WebKitBlobBuilder || window.MSBlobBuilder);
      buffer.forEach(function (buf) {
        bb.append(buf);
      });
      return bb.getBlob(format);
    }
  }

  /**
   * 获取formdata
   */
  function getFormData() {
    var isNeedShim = ~navigator.userAgent.indexOf('Android') &&
      ~navigator.vendor.indexOf('Google') &&
      !~navigator.userAgent.indexOf('Chrome') &&
      navigator.userAgent.match(/AppleWebKit\/(\d+)/).pop() <= 534;
    return isNeedShim ? new FormDataShim() : new FormData()
  }
  /**
   * formdata 补丁, 给不支持formdata上传blob的android机打补丁
   * @constructor
   */
  function FormDataShim() {
    console.warn('using formdata shim');
    var o = this,
      parts = [],
      boundary = Array(21).join('-') + (+new Date() * (1e16 * Math.random())).toString(36),
      oldSend = XMLHttpRequest.prototype.send;
    this.append = function (name, value, filename) {
      parts.push('--' + boundary + '\r\nContent-Disposition: form-data; name="' + name + '"');
      if (value instanceof Blob) {
        parts.push('; filename="' + (filename || 'blob') + '"\r\nContent-Type: ' + value.type + '\r\n\r\n');
        parts.push(value);
      } else {
        parts.push('\r\n\r\n' + value);
      }
      parts.push('\r\n');
    };
    // Override XHR send()
    XMLHttpRequest.prototype.send = function (val) {
      var fr,
        data,
        oXHR = this;
      if (val === o) {
        // Append the final boundary string
        parts.push('--' + boundary + '--\r\n');
        // Create the blob
        data = getBlob(parts);
        // Set up and read the blob into an array to be sent
        fr = new FileReader();
        fr.onload = function () {
          oldSend.call(oXHR, fr.result);
        };
        fr.onerror = function (err) {
          throw err;
        };
        fr.readAsArrayBuffer(data);
        // Set the multipart content type and boudary
        this.setRequestHeader('Content-Type', 'multipart/form-data; boundary=' + boundary);
        XMLHttpRequest.prototype.send = oldSend;
      } else {
        oldSend.call(this, val);
      }
    };
  }
}