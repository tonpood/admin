/*
 * Javascript Libraly for GCMS (front-end + back-end)
 *
 * @filesource js/common.js
 * @link http://www.kotchasan.com/
 * @copyright 2016 Goragod.com
 * @license http://www.kotchasan.com/license/
 */
var mtooltip,
  modal = null,
  loader = null,
  editor = null,
  G_Lightbox = null;
function mTooltipShow(id, action, method, elem) {
  if (Object.isNull(mtooltip)) {
    mtooltip = new GTooltip({
      className: 'member-tooltip',
      fade: true,
      cache: true
    });
  }
  mtooltip.showAjax(elem, action, method + '&id=' + id, function (xhr) {
    if (loader) {
      loader.init(this.tooltip);
    }
  });
}
function send(target, query, callback, wait, c) {
  var req = new GAjax();
  req.initLoading(wait || 'wait', false, c);
  req.send(target, query, function (xhr) {
    callback.call(this, xhr);
  });
}
var hideModal = function () {
  if (modal != null) {
    modal.hide();
  }
};
function showModal(src, qstr, doClose) {
  send(src, qstr, function (xhr) {
    var ds = xhr.responseText.toJSON();
    var detail = '';
    if (ds) {
      if (ds.alert) {
        alert(ds.alert);
      } else if (ds.detail) {
        detail = decodeURIComponent(ds.detail);
      }
    } else {
      detail = xhr.responseText;
    }
    if (detail != '') {
      modal = new GModal({
        onclose: doClose
      }).show(detail);
      detail.evalScript();
    }
  });
}
function defaultSubmit(ds) {
  var _alert = '',
    _input = false,
    _url = false,
    _location = false,
    t, el,
    remove = /remove([0-9]{0,})/;
  for (var prop in ds) {
    var val = ds[prop];
    if (prop == 'error') {
      _alert = eval(val);
    } else if (prop == 'alert') {
      _alert = val;
    } else if (prop == 'location') {
      if (val == 'close') {
        if (modal) {
          modal.hide();
        }
      } else {
        _location = val;
      }
    } else if (prop == 'url') {
      _url = val;
      _location = val;
    } else if (prop == 'tab') {
      initWriteTab("accordient_menu", val);
    } else if (remove.test(prop)) {
      if ($E(val)) {
        $G(val).remove();
      }
    } else if (prop == 'input') {
      el = $G(val);
      t = el.title ? el.title.strip_tags() : '';
      if (t == '' && el.placeholder) {
        t = el.placeholder.strip_tags();
      }
      if (_input != el) {
        el.invalid(t);
      }
      if (t != '' && _alert == '') {
        _alert = t;
        _input = el;
      }
    } else if ($E(prop)) {
      $G(prop).setValue(decodeURIComponent(val).replace('%', '&#37;'));
    } else if ($E(prop.replace('ret_', ''))) {
      el = $G(prop.replace('ret_', ''));
      if (val == '') {
        el.valid();
      } else {
        if (val == 'this') {
          val = el.title.strip_tags();
          if (val == '' && el.placeholder) {
            val = el.placeholder.strip_tags();
          }
        }
        if (_input != el) {
          el.invalid(val);
        }
        if (_alert == '') {
          _alert = val;
          _input = el;
        }
      }
    }
  }
  if (_alert != '') {
    alert(_alert);
  }
  if (_input) {
    _input.focus();
    var tag = _input.tagName.toLowerCase();
    if (tag != 'select') {
      _input.highlight();
    }
    if (tag == 'input') {
      var type = _input.get('type').toLowerCase();
      if (type == 'text' || type == 'password') {
        _input.select();
      }
    }
  }
  if (_location) {
    if (_location == 'reload') {
      reload();
    } else if (_location == _url) {
      window.location = decodeURIComponent(_location);
    } else if (_location == 'back') {
      if (loader) {
        loader.back();
      } else {
        window.history.go(-1);
      }
    } else {
      window.location = _location.replace(/&amp;/g, '&');
    }
  }
}
function doFormSubmit(xhr) {
  var datas = xhr.responseText.toJSON();
  if (datas) {
    defaultSubmit(datas);
  } else if (xhr.responseText != '') {
    alert(xhr.responseText);
  }
}
function checkUsername() {
  var patt = /[a-zA-Z0-9]+/;
  var value = this.input.value;
  var ids = this.input.id.split('_');
  var id = '&id=' + floatval($E(ids[0] + '_id').value);
  if (value == '') {
    this.invalid(this.input.title);
  } else if (patt.test(value)) {
    return 'value=' + encodeURIComponent(value) + id;
  } else {
    this.invalid(this.input.title);
  }
}
function checkPassword() {
  var ids = this.input.id.split('_');
  var id = '&id=' + floatval($E(ids[0] + '_id').value);
  var Password = $E(ids[0] + '_password');
  var Repassword = $E(ids[0] + '_repassword');
  if (Password.value == '' && Repassword.value == '') {
    if (id == 0) {
      this.input.Validator.invalid(this.input.Validator.title);
    } else {
      this.input.Validator.reset();
    }
    this.input.Validator.reset();
  } else if (Password.value == Repassword.value) {
    Password.Validator.valid();
    Repassword.Validator.valid();
  } else {
    this.input.Validator.invalid(this.input.Validator.title);
  }
}
function checkIdcard() {
  var value = this.input.value;
  var ids = this.input.id.split('_');
  var id = '&id=' + floatval($E(ids[0] + '_id').value);
  var i, sum;
  if (value.length != 13) {
    this.invalid(this.input.title);
  } else {
    for (i = 0, sum = 0; i < 12; i++) {
      sum += parseFloat(value.charAt(i)) * (13 - i);
    }
    if ((11 - sum % 11) % 10 != parseFloat(value.charAt(12))) {
      this.invalid(this.input.title);
    } else {
      return 'value=' + encodeURIComponent(value) + '&id=' + id;
    }
  }
}
function reload() {
  window.location = replaceURL('timestamp', new String(new Date().getTime()), window.location.toString());
}
function getWebUri() {
  var port = floatval(window.location.port);
  var protocol = window.location.protocol;
  if ((protocol == 'http:' && port == 80) || (protocol == 'https:' && port == 443)) {
    port = '';
  } else {
    port = port > 0 ? ':' + port : '';
  }
  return protocol + '//' + window.location.hostname + port + '/';
}
function replaceURL(keys, values, url) {
  var patt = /^(.*)=(.*)$/;
  var ks = keys.toLowerCase().split(',');
  var vs = values.split(',');
  var urls = new Object();
  var u = url || window.location.href;
  var us2 = u.split('#');
  u = us2.length == 2 ? us2[0] : u;
  var us1 = u.split('?');
  u = us1.length == 2 ? us1[0] : u;
  if (us1.length == 2) {
    forEach(us1[1].split('&'), function () {
      hs = patt.exec(this);
      if (!hs || ks.indexOf(hs[1].toLowerCase()) == -1) {
        urls[this] = this;
      }
    });
  }
  if (us2.length == 2) {
    forEach(us2[1].split('&'), function () {
      hs = patt.exec(this);
      if (!hs || ks.indexOf(hs[1].toLowerCase()) == -1) {
        urls[this] = this;
      }
    });
  }
  var us = new Array();
  for (var p in urls) {
    us.push(urls[p]);
  }
  forEach(ks, function (item, index) {
    if (vs[index] && vs[index] != '') {
      us.push(item + '=' + vs[index]);
    }
  });
  u += '?' + us.join('&');
  return u;
}
$G(window).Ready(function () {
  if (navigator.userAgent.indexOf("MSIE") > -1) {
    document.body.addClass("ie");
  }
  var _scrolltop = 0;
  var toTop = $E('toTop') ? $G('toTop').getTop() : 100;
  document.addEvent('scroll', function () {
    var c = this.viewport.getscrollTop() > toTop;
    if (_scrolltop != c) {
      _scrolltop = c;
      if (c) {
        document.body.addClass('toTop');
        document.callEvent('toTopChange');
      } else {
        document.body.removeClass('toTop');
        document.callEvent('toTopChange');
      }
    }
  });
});