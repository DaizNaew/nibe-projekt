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
      serverip: "http://10.11.5.135/cordova/",
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

    addToLog: function(UserID, Handling){
      app.request.post(`${app.data['serverip']}endpoint/logging.php`, { UserID: UserID, Handling: Handling }, function(response){
        //console.log(response);
      }, function(e, e2){
        //console.log(e);
      }, "json");
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
      setTimeout(function(){
        app.data['navbarheight'] = $$('#view-navbar')[0].clientHeight;
        document.getElementById('view-home').style.top = app.data['navbarheight']+"px";
      }, 0);

      let currentpage = app.views.main.router.currentPageEl.dataset.name;
      let navbarheader = $$('#navbarheader').find('li');
      if(currentpage == "udlon"){

      }else{
        var idleTime = 0;
        setInterval(function(){
          idleTime++;
          if(idleTime > 5){
            app.methods.hideAdminNavbar();
            for(let i = 0; i < navbarheader.length; i++) {
              if($$(navbarheader[i]).hasClass('active')){
                $$(navbarheader[i]).removeClass('active');
              }
            }
            $$(`#navbarudlon`).addClass('active');
            app.views.main.router.navigate("/udlon/", {reloadCurrent: true,});
          }
        }, 60000);
        
  
        $$(document).mousemove(function(e){
          idleTime = 0;
        });

        $$(document).keypress(function(e){
          idleTime = 0;
        });
      }
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