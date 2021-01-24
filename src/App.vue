<template>
  <div>
    <b-navbar style='height:100%;text-decoration:none' toggleable="lg" type="dark" variant="info">
      <b-collapse id="nav-collapse" is-nav>
        <b-navbar-nav>
          <b-nav-item><router-link v-if="user.status === '1'" to="/create">Stvori pananagramku</router-link></b-nav-item>
          <b-nav-item><router-link to="/puzzle_list">Popis zagonetki</router-link></b-nav-item>
          <b-nav-item><router-link v-if="user.username" to="/search_user">Popis korisnika</router-link></b-nav-item>
        </b-navbar-nav>
        <!-- Right aligned nav items -->
        <b-navbar-nav class="ml-auto">
          <b-nav-item>
            <span v-if="user.username" style="white-space: nowrap;">
            <router-link to="/user_info">Dobrodošli <span style="font-style: italic" v-if="user.status === '1'">admin </span>
            <span v-else>korisniče </span>
            <span >{{user.username}} </span>
            </router-link>
            </span>
            <router-link v-if="!user.username" to="/log_in">Prijava</router-link>
          </b-nav-item>
          <b-nav-item-dropdown v-if="user.username" text="Opcije" right>
            <b-dropdown-item v-if="user.username"><span v-on:click="logout()" style="cursor: pointer">Odjava</span></b-dropdown-item>
            <b-dropdown-item v-if="user.username"><router-link to="/user_info">Profil</router-link></b-dropdown-item>
            <b-dropdown-item v-if="user.username"><router-link to="/friend_list">Prijatelji</router-link></b-dropdown-item>
            <b-dropdown-item v-if="user.username"><router-link to="/request_info">Zahtjevi</router-link></b-dropdown-item>
            <b-dropdown-item v-if="user.username"><router-link to="/blocked_info">Blokirani</router-link></b-dropdown-item>
          </b-nav-item-dropdown>
        </b-navbar-nav>
      </b-collapse>
    </b-navbar>
    <br>
    <div>
      <router-view></router-view>
    </div><br>
    <div class = "container-fluid">
      <div class = "row">
        <div class = "col" style="display:inline-block;vertical-align:bottom">
          <b-button variant='info' v-on:click="help()">?</b-button>
          <br><br>
          <b-alert show variant='dark' v-if="showhelp">
            <p>{{helptext[where]}}</p>
          </b-alert>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
    var link = document.querySelector("link[rel*='icon']") || document.createElement('link');
    link.type = 'image/x-icon';
    link.rel = 'shortcut icon';
    link.href = 'assets/titleicon.png';
    document.getElementsByTagName('head')[0].appendChild(link);
    document.title = "Pananagram";

const alphabet = [ "A", "B", "C", "Č", "Ć", 
                  "D", "Ǆ", "Đ", "E", "F",
                  "G", "H", "I", "J", "K", 
                  "L", "Ǉ", "M", "N", "Ǌ",
                  "O", "P", "R", "S", "Š",
                  "T", "U", "V", "Z", "Ž", "-"];

var user = {
          userId: "",
          username: "",
          password: "",
          status: "",
          visibility: 0
      }

var helptext =  { 
  'solve': 'Da biste riješili pananagramku, upišite riječi koje odgovaraju opisima u stupce lijeve ispunjaljke. Stiskom na strelice uz broj riječi možete ih sve pregledati. Kao pomoć su zadani svi slogovi okomitih riječi. Slogove odaberite iz popisa lijevim klikom i postavite ih na odgovarajuće mjesto u prvom liku. Svaki slog se koristi točno jednom, ali ima više primjeraka istih slogova. Slog možete očistiti ako drugi slog postavite tako da se preklapaju ili ako stisnete desnim klikom na slog. Riješite li lijevu ispunjaljku u posebno označenom retku dobiti ćete istaknuti pojam. Prenesete li slova iz prvog u drugi lik po načelu "isti broj-isto slovo" dobit ćete dodatne pojmove koji su smisleno povezani s istaknutim pojmom. Kada mislite da ste zagonetku ispravno riješili, provjerite rješenje stiskom na odgovaraući gumb, a ukoliko ste prijavljeni korisnik, Vaš će se rezultat spremiti. U gornjem desnom kutu ekrana vidite koliko je vremena proteklo od početka rješavanja zagonetke.',
  'create': 'Da biste stvorili pananagramku, odaberite broj riječi, duljinu riječi te broj retka s istaknutim pojmom u lijevoj ispunjaljci pomoću kližućeg izbornika. Onda odredite broj slogova, slova u slogovima te opis svake okomite riječi u lijevoj ispunjaljci. Stiskom na strelice uz broj riječi možete ih sve pregledati. Slova upisujete u slogove tako da iz abecede na ekranu odaberete slovo pa klikom na slog ubacite slovo na kraj sloga. Odabirom znaka - koji se nalazi uz slova možete dodati prazna polja na početku ili kraju riječi. Slog možete isprazniti desnim klikom. Znakovima plus i minus koji se nalaze na kraju riječi možete prilagoditi broj slogova u svakoj pojedinoj riječi, a klikom na simbol x očistit ćete cijelu riječ. Upišite opis zagonetke. Dodajte izvor zagonetke ako niste autor originalne pananagramke. U lijevoj ispunjaljci dodajte broj svakome slovu, pa će se ono automatski prebaciti u desnu ispunjaljku. Odabirom posebnih znakova razgraničenja za lijevu ili desnu ispunjaljku te stiskom na polja odgovarajuće ispunjaljke možete razgraničiti pojmove i riječi unutar pojmova. Ukoliko nisu ispravno zadani svi obavezni parametri, u crvenom okviru na dnu ekrana prikazati će se koje značajke zagonetke nedostaju.',
  'update': 'Da biste ažurirali pananagramku, izmjenite broj riječi, duljinu riječi te broj retka s istaknutim pojmom u lijevoj ispunjaljci pomoću kližućeg izbornika. Onda izmijenite broj slogova, slova u slogovima te opis svake okomite riječi u lijevoj ispunjaljci. Stiskom na strelice uz broj riječi možete ih sve pregledati. Slova upisujete u slogove tako da iz abecede na ekranu odaberete slovo pa klikom na slog ubacite slovo na kraj sloga. Odabirom znaka - koji se nalazi uz slova možete dodati prazna polja na početku ili kraju riječi. Slog možete isprazniti desnim klikom. Znakovima plus i minus koji se nalaze na kraju riječi možete prilagoditi broj slogova u svakoj pojedinoj riječi, a klikom na simbol x očistit ćete cijelu riječ. Izmijenite opis zagonetke. Dodajte izvor zagonetke ako niste autor originalne pananagramke. U lijevoj ispunjaljci izmijenite broj svakome slovu, pa će se ono automatski prebaciti u desnu ispunjaljku. Odabirom posebnih znakova razgraničenja za lijevu ili desnu ispunjaljku te stiskom na polja odgovarajuće ispunjaljke možete razgraničiti pojmove i riječi unutar pojmova. Ukoliko nisu ispravno zadani svi obavezni parametri, u crvenom okviru na dnu ekrana prikazati će se koje značajke zagonetke nedostaju.',
  'user_info': 'Izmijenite svoju lozinku ili vidljivost profila te pregledajte svoje rekorde i zagonetke koje ste stvorili.',
  'victory': 'Čestitke, ispravno ste riješili pananagramku!',
  'puzzle_info': 'Pregledajte detalje o pananagramki, svoje rekorde ukoliko ste ju riješili te rekorde ostalih korisnika.',
  'puzzle_list': 'Pregledajte sve zagonetke, sortirajte ih uzlazno ili silazno po rednom broju, broju riječi, duljini riječi ili izvoru, a moguće je i dodati više kriterija za pretragu po navedenim poljima.'  ,
  'log_in': 'Prijavite se da biste mogli spremiti svoje rezultate te kontaktirati s drugim korisnicima čiji profil nije javan.',
  'register': 'Izradite račun na stranici da biste mogli spremiti svoje rezultate te kontaktirati s drugim korisnicima čiji profil nije javan.',
  'search_user': 'Pretražite korisnike čiji je profil javan ili koji su Vaši prijatelji prema njihovom korisničkom imenu.',
  'friend_list': 'Pretražite korisnike koji su Vaši prijatelji prema njihovom korisničkom imenu.',
  'friend_info': 'Pregledajte rekorde korisnika i zagonetke koje je stvorio, te uredite svoje veze s navedenim korisnikom.',
  'request_info': 'Pregledajte zahtjeve za prijateljstvo koje ste poslali te zahtjeve za prijateljstvo koje su drugi poslali Vama. Možete i poslati zahtjev korisniku s određenim korisničkim imenom.',
  'blocked_info': 'Pregledajte korisnike koje ste blokirali, te im je Vaš profil nevidljiv. Možete i blokirati korisnika s određenim korisničkim imenom, ili ukloniti blokadu korisnika.',
};

if (sessionStorage.user) {
  user = JSON.parse(sessionStorage.user);
}

export default {
  name: 'App',
  beforeUpdate() {
    if (sessionStorage.user) {
      this.where = this.$route.name;
      this.user = JSON.parse(sessionStorage.user);
    }
  },
  data() {
    return {
      user: user,
      showhelp: false,
      helptext: helptext,
      where: "puzzle_list",
    }
  },
  methods: {
    logout() {
      user = {
          userId: "",
          username: "",
          password: "",
          status: ""
      };
      this.user = user;
      sessionStorage.clear();
      window.location.reload();
    },
    help() {
      this.showhelp = !this.showhelp;
    }    
  },
  watch: {
    '$route': function() {
      this.where = this.$route.name;
    }
  }
}

export { user }
export { alphabet }
</script>

<style scoped src="./pananagram.css">
</style>

