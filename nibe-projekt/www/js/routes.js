routes = [
  {
    path: '/',
    url: './index.html',
  },
  {
    name: 'adminlogin',
    path: '/adminlogin/',
    componentUrl: './pages/admin/login.html'
  },
  {
    name: 'udlon',
    path: '/udlon/:reservationID?/',
    componentUrl: './pages/udlon.html',
    data: function(){
      return{
        reservationID: routeTo.params.reservationID,
      }
    }
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
    name: 'aflevering',
    path: '/aflevering/',
    componentUrl: './pages/aflevering.html',
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
    name: 'userList',
    path: '/userList/',
    componentUrl: './pages/admin/userList.html',
  },
  {
    name: 'oversigt',
    path: '/oversigt/',
    componentUrl: './pages/oversigt.html',
  },
  // Default route (404 page). MUST BE THE LAST
  {
    path: '(.*)',
    url: './pages/404.html',
  },
];
