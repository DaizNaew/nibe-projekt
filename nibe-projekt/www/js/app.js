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
    };
  },
  // App root methods
  methods: {
    helloWorld: function () {
      app.dialog.alert('Hello World!');
    },
  },
  // App routes
  routes: routes,

  on: {
    pageInit: function(page) {
      console.log(page.route.path);
      console.log(app.data['user']);
      if(app.data['user']) { 
        console.log('init');
      } else {
        console.log('altså nej');
      }
    },
  },
});

// Init/Create views
var homeView = app.views.create('#view-home', {
  url: '/'
});