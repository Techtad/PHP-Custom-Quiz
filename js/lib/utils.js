var Utils = {
  getCookie: function(name) {
    let cookies = document.cookie.split("; ");
    for (let c of cookies) {
      let pair = c.split("=");
      if (pair[0] == name) return pair[1];
    }
    return "";
  },

  setCookie: function(name, value, expiresMiliSecs) {
    let date = new Date();
    date.setDate(date.getTime() + expiresMiliSecs);
    document.cookie = `${name}=${value};expires=${date.toUTCString()};path=/`;
  },

  getURLParam: function(name) {
    let pairs = window.location.search.substr(1).split("&");
    let urlParams = [];
    for (let pair of pairs) {
      let values = pair.split("=");
      urlParams[values[0]] = values[1];
    }

    return urlParams[name];
  }
};
