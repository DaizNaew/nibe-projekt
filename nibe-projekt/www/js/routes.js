// Framework7 Route array med objekter som er alle de routes vi bruger til at redirecte pages i views
routes = [
  // Default route som viser index siden
  {
    path: '/',
    url: './index.html',
  },

  /** 
   * Start på alle routes som hører til det generelt tilgængelige område
  */

  // Route til at vise adminlogin siden
  {
    name: 'adminlogin', // Internt navn på routen
    path: '/adminlogin/',
    componentUrl: './pages/admin/login.html'
  },
  /** Route til at vise udlån siden
   *  
   * @param {int} reservationID ReservationID er en optional param som kan sendes med hvis der skal udlånes en reservation
   *                            Ved at skrive :reservationID? siger vi med : at det er en parameter der bliver sendt med
   *                            og ved at slutte af med et ? fortæller vi framework7 at det er en optional parameter
   */
  {
    name: 'udlon', // Internt navn på routen
    path: '/udlon/:reservationID?/', // Den sti som der skal kaldes når der routes i HTML koden, dvs der skal peges på denne sti i 'href'
    componentUrl: './pages/udlon.html', // Den path hvor i filen som vi prøver at indlæse ligger i og navnet på filen
    // Her sender vi ekstra data med routet
    data: function(){
      return{
        reservationID: routeTo.params.reservationID, // Der laves en ny Variabel som indeholder de parametre som der blev sendt med routet
      }
    }
  },
  // Route til at vise navbaren
  // Denne bruges på den her måde for at vi kan dynamisk populate selve navbar området
  {
    name: 'navbar', // Internt navn på routen
    path: '/navbar/', // Den sti som der skal kaldes når der routes i HTML koden, dvs der skal peges på denne sti i 'href'
    componentUrl: './pages/navbar.html', // Den path hvor i filen som vi prøver at indlæse ligger i og navnet på filen
  },
  // Route til at vise afleverings siden
  {
    name: 'aflevering', // Internt navn på routen
    path: '/aflevering/', // Den sti som der skal kaldes når der routes i HTML koden, dvs der skal peges på denne sti i 'href'
    componentUrl: './pages/aflevering.html', // Den path hvor i filen som vi prøver at indlæse ligger i og navnet på filen
  },
  // Route til at vise reserver siden
  {
    name: 'reserver', // Internt navn på routen
    path: '/reserver/', // Den sti som der skal kaldes når der routes i HTML koden, dvs der skal peges på denne sti i 'href'
    componentUrl: './pages/reserver.html', // Den path hvor i filen som vi prøver at indlæse ligger i og navnet på filen
  },
  // Route til at vise reservationer siden
  {
    name: 'reservationer', // Internt navn på routen
    path: '/reservationer/', // Den sti som der skal kaldes når der routes i HTML koden, dvs der skal peges på denne sti i 'href'
    componentUrl: './pages/reservationer.html', // Den path hvor i filen som vi prøver at indlæse ligger i og navnet på filen
  },
  // Route til at vise historik siden
  {
    name: 'historik', // Internt navn på routen
    path: '/historik/', // Den sti som der skal kaldes når der routes i HTML koden, dvs der skal peges på denne sti i 'href'
    componentUrl: './pages/historik.html', // Den path hvor i filen som vi prøver at indlæse ligger i og navnet på filen
  },
  // Route til at vise oversigt siden
  {
    name: 'oversigt', // Internt navn på routen
    path: '/oversigt/', // Den sti som der skal kaldes når der routes i HTML koden, dvs der skal peges på denne sti i 'href'
    componentUrl: './pages/oversigt.html', // Den path hvor i filen som vi prøver at indlæse ligger i og navnet på filen
  },
  
  /** 
   * Start på alle routes som hører til Admin området
  */

  // Route til at vise den side som håndterer oprettelse af nye brugere og redigering af allerede eksisterende brugere
  {
    name: 'createuser', // Internt navn på routen
    path: '/createuser/', // Den sti som der skal kaldes når der routes i HTML koden, dvs der skal peges på denne sti i 'href'
    componentUrl: './pages/admin/createuser.html', // Den path hvor i filen som vi prøver at indlæse ligger i og navnet på filen
  },
  // Route til at vise den side som håndterer oprettelse af nye værktøj og redigering af allerede eksisterende værktøj
  {
    name: 'tooladdpan', // Internt navn på routen
    path: '/tooladdpan/', // Den sti som der skal kaldes når der routes i HTML koden, dvs der skal peges på denne sti i 'href'
    componentUrl: './pages/admin/tooladdpan.html', // Den path hvor i filen som vi prøver at indlæse ligger i og navnet på filen
  },
  // Route til at vise den side som håndterer oprettelse af værktøjs kategorier
  {
    name: 'toolkat', // Internt navn på routen
    path: '/toolkat/', // Den sti som der skal kaldes når der routes i HTML koden, dvs der skal peges på denne sti i 'href'
    componentUrl: './pages/admin/toolkat.html', // Den path hvor i filen som vi prøver at indlæse ligger i og navnet på filen
  },
  // Route til at vise den side som håndterer oprettelse af nye certifikater og kørekort, og redigering af allerede eksisterende certifikater og kørekort
  {
    name: 'certifikat', // Internt navn på routen
    path: '/certifikat/', // Den sti som der skal kaldes når der routes i HTML koden, dvs der skal peges på denne sti i 'href'
    componentUrl: './pages/admin/certifikat.html', // Den path hvor i filen som vi prøver at indlæse ligger i og navnet på filen
  },
  // Route til at vise admin oversigt siden, denne side har mulighed for at slette ting i oversigten
  {
    name: 'adminoversigt', // Internt navn på routen
    path: '/adminoversigt/', // Den sti som der skal kaldes når der routes i HTML koden, dvs der skal peges på denne sti i 'href'
    componentUrl: './pages/admin/adminoversigt.html', // Den path hvor i filen som vi prøver at indlæse ligger i og navnet på filen
  },
  // Route til at vise log siden
  {
    name: 'log', // Internt navn på routen
    path: '/log/', // Den sti som der skal kaldes når der routes i HTML koden, dvs der skal peges på denne sti i 'href'
    componentUrl: './pages/admin/log.html', // Den path hvor i filen som vi prøver at indlæse ligger i og navnet på filen
  },
  // Route til at vise en oversigt af alle brugere som er oprettet i systemet
  {
    name: 'userList', // Internt navn på routen
    path: '/userList/', // Den sti som der skal kaldes når der routes i HTML koden, dvs der skal peges på denne sti i 'href'
    componentUrl: './pages/admin/userList.html', // Den path hvor i filen som vi prøver at indlæse ligger i og navnet på filen
  },

  // Default route (404 page). MUST BE THE LAST
  {
    path: '(.*)', // Den sti som der skal kaldes når der routes i HTML koden, dvs der skal peges på denne sti i 'href'
    url: './pages/404.html', // Den path hvor i filen som vi prøver at indlæse ligger i og navnet på filen
  },
  
];
