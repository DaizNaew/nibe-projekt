routes = [
  {
    path: '/',
    url: './index.html',
  },
  {
    path: '/about/',
    url: './pages/about.html',
  },
  {
    path: '/catalog/',
    componentUrl: './pages/catalog.html',
  },
  {
    path: '/product/:id/',
    componentUrl: './pages/product.html',
  },
  {
    path: '/settings/',
    url: './pages/settings.html',
  },
  {
    name: 'adminlogin',
    path: '/adminlogin/',
    componentUrl: './pages/admin/login.html'
  },
  {
    name: 'udlon',
    path: '/udlon/',
    componentUrl: './pages/udlon.html',
  },
  {
    name: 'navbar',
    path: '/navbar/',
    componentUrl: './pages/navbar.html',
  },
  {
    name: 'toolkat',
    path: '/toolkat/',
    componentUrl: './pages/admin/toolkat.html',
  },
  {
    name: 'aflervering',
    path: '/aflervering/',
    componentUrl: './pages/aflervering.html',
  },
  {
    name: 'historik',
    path: '/historik/',
    componentUrl: './pages/historik.html',
  },
  {
    name: 'reserver',
    path: '/reserver/',
    componentUrl: './pages/reserver.html',
  },
  {
    name: 'reservationer',
    path: '/reservationer/',
    componentUrl: './pages/reservationer.html',
  },
  {
    name: 'tooladdpan',
    path: '/tooladdpan/',
    componentUrl: './pages/admin/tooladdpan.html',
  },
  {
    name: 'createuser',
    path: '/createuser/',
    componentUrl: './pages/admin/createuser.html',
  },
  {
    name: 'certifikat',
    path: '/certifikat/',
    componentUrl: './pages/admin/certifikat.html',
  },
  {
    name: 'adminoversigt',
    path: '/adminoversigt/',
    componentUrl: './pages/admin/adminoversigt.html',
  },
  {
    name: 'log',
    path: '/log/',
    componentUrl: './pages/admin/log.html',
  },
  {
    name: 'oversigt',
    path: '/oversigt/',
    componentUrl: './pages/oversigt.html',
  },
  // Page Loaders & Router
  {
    path: '/page-loader-template7/:user/:userId/:posts/:postId/',
    templateUrl: './pages/page-loader-template7.html',
  },
  {
    path: '/page-loader-component/:user/:userId/:posts/:postId/',
    componentUrl: './pages/page-loader-component.html',
  },
  {
    path: '/request-and-load/user/:userId/',
    async: function (routeTo, routeFrom, resolve, reject) {
      // Router instance
      var router = this;

      // App instance
      var app = router.app;

      // Show Preloader
      app.preloader.show();

      // User ID from request
      var userId = routeTo.params.userId;

      // Simulate Ajax Request
      setTimeout(function () {
        // We got user data from request
        var user = {
          firstName: 'Vladimir',
          lastName: 'Kharlampidi',
          about: 'Hello, i am creator of Framework7! Hope you like it!',
          links: [
            {
              title: 'Framework7 Website',
              url: 'http://framework7.io',
            },
            {
              title: 'Framework7 Forum',
              url: 'http://forum.framework7.io',
            },
          ]
        };
        // Hide Preloader
        app.preloader.hide();

        // Resolve route to load page
        resolve(
          {
            componentUrl: './pages/request-and-load.html',
          },
          {
            context: {
              user: user,
            }
          }
        );
      }, 1000);
    },
  },
  // Default route (404 page). MUST BE THE LAST
  {
    path: '(.*)',
    url: './pages/404.html',
  },
];
