// Dom7
var $$ = Dom7;

// Framework7 App main instance
var app  = new Framework7({
  root: '#app', // App root element
  id: 'dk.nibefestival.nibelager', // App bundle ID
  name: 'nibelager', // App name
  theme: 'auto', // Automatic theme detection
  // App root data
  data: function () {
      
    return {
      username: "",
      userID: -1,
      adminConfirmedBool: false,
      navbarheight: 0,
      serverip: "http://10.11.4.136/cordova/",
    };
  },
  // App root methods
  methods: {
    showAdminNavbar: function (){
      if(app.data['adminConfirmedBool'] == true) {
        //document.getElementById('adminnavbarwrapper').style.display = "block";
        $$('#adminnavbarwrapper').show();
      }
    },

    hideAdminNavbar: function (){
      app.data['adminConfirmedBool'] = false;
      //document.getElementById('adminnavbarwrapper').style.display = "none";
      $$('#adminnavbarwrapper').hide();
    },
  },
  // App routes
  routes: routes,

  on: {
    pageInit: function(page){
      let currentpage = app.views.main.router.currentPageEl.dataset.name;
      if(currentpage == "home"){
        app.views.main.router.navigate("/udlon/", {reloadCurrent: true,});
        app.data['navbarheight'] = $$('#view-navbar')[0].clientHeight;
        document.getElementById('view-home').style.top = app.data['navbarheight']+"px";
      }
    },
    pageAfterIn: function(page) {
      app.data['navbarheight'] = $$('#view-navbar')[0].clientHeight;
      document.getElementById('view-home').style.top = app.data['navbarheight']+"px";
    },
  },
});

// Init/Create views
var homeView = app.views.create('#view-home', {
  url: '/'
});
var navbarView = app.views.create('#view-navbar', {
  url: '/navbar/'
});