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
      user:false,
      username: "",
      usergruppe: "",
      userID: -1,
      rigtignavn: "",
    };
  },
  // App root methods
  methods: {
    LoginCheck: function (page) {
      if(app.data['user'] == true) {
        console.log('logged in');
      } else {
        //console.log('altså nej');
        if(page.route.path == "/login/"){
          return
        }else{
          app.views.main.router.navigate("/login/", {reloadCurrent: true,});
        }
      }
    },
    refreshNavbar: function (){
      if(app.data['user'] == true) {
        let getNavView = app.views.get("#view-navbar");
        getNavView.router.refreshPage();
        document.getElementById('navbarwrapper').style.display = "block";
      }else{
        document.getElementById('navbarwrapper').style.display = "none";
      }
    },
    populateNavbar: function (){

      if(app.data['usergruppe'] == 4){ //admin
        
        app.views.main.router.navigate("/udlon/", {reloadCurrent: true,});
      }
      else if(app.data['usergruppe'] == 3){ //udlåner

        app.views.main.router.navigate("/udlon/", {reloadCurrent: true,});
      }else{ //guest
        app.views.main.router.navigate("/udlon/", {reloadCurrent: true,});
      }
    },

  },
  // App routes
  routes: routes,

  on: {
    pageInit: function(page){
      app.methods.LoginCheck(page);
    },
    pageBeforeIn: function(page) {
      app.methods.LoginCheck(page);

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