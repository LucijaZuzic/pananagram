<template>
    <div class='container-fluid'>
        <br>
        <div class = "row">
            <div class="col">
                    <h1 class="display-4" style='width: 100%'>
                        <span v-if="user.status==='1'" title="Admin">&#127775;</span>
                        <span v-else title="Korisnik">&#11088;</span>
                        Moj osobni profil
                    </h1>
            </div>
        </div>
        <div class = "row">
            <div class="col"><br>
                <h4 style="cursor: pointer" v-on:click="showsettings=!showsettings">
                    &#128295; Postavke
                </h4><br>
            </div>
        </div>
        <div v-if="showsettings" class = "row">
            <div class="col">
                <h4  style='width: 100%'>&#128373; Vidljivost profila</h4>
                <table style="width: 450px">
                    <tr>
                        <td>
                            &#128275;<span style="cursor: pointer" v-on:click="show(1)" :class="{ visiblesetting: this.user.visibility.toString() === '1'}"> Javno (profil je vidljiv svim korisnicima)</span>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            &#128274;<span style="cursor: pointer" v-on:click="show(0)" :class="{ visiblesetting: this.user.visibility.toString() === '0'}"> Privatno (profil je vidljiv samo prijateljima)</span>
                        </td>
                    </tr>
                </table> 
            </div>
            <div class="col">
                <h4>&#128273; Promjeni lozinku</h4>
                <div class="d-inline-flex align-items-center p-2">
                    <div class="p-2">
                        <span>Lozinka: </span>
                    </div>
                    <div class="p-2">
                        <span style="white-space: nowrap;">
                            <b-form-input style="display: inline-block;" id="pass" type="password" v-model="pwd"></b-form-input>
                            <span class="eye" style="text-decoration: line-through" id="eye" v-on:click="showpassword()">&#128065;</span>
                        </span>
                    </div>
                    <div class="p-2">
                        <b-button variant="success"  v-on:click="changepwd()">Spremi </b-button>
                    </div>
                </div>
            </div>
            <div class="col">
                <h4>&#129485; Korisničko ime</h4>
                <div class="d-inline-flex align-items-center p-2">
                    <div class="p-2">
                        <span>Korisničko ime: </span>
                    </div>
                    <div class="p-2">
                        <b-form-input style="display:inline-block;width:225px;" type="text" id="uname" v-model="newusername"></b-form-input>&nbsp;
                    </div>
                    <div class="p-2">
                        <b-button variant="success"  v-on:click="changeusername()">Spremi </b-button>
                    </div>
                </div>
            </div>
        </div>
        <div v-if="userrecords().length > 0">
            <h4 v-on:click="showrecords=!showrecords" style="cursor: pointer">&#127942; Moji rekordi</h4>
            <div v-if="showrecords">
                <br>Prikaži:
                <b-form-select style="margin:5px;display:inline-block; width: 75px"  v-model="page_length1" class="form-select" aria-label="Default select example">
                    <option v-for='(item, index) in userrecords()' v-bind:key = index>{{index + 1}}</option>
                </b-form-select><br><br>
            </div>
            <table class="table table-hover table-striped text-center" v-if="showrecords">
                <thead>
                    <tr>
                        <th>Broj zagonetke</th>
                        <th >Broj riječi</th>
                        <th>Duljina riječi</th>
                        <th>Izvor</th>
                        <th>&#128197; Datum</th>
                        <th>&#128337; Vrijeme</th>
                        <th>&#9201; Rezultat</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="index in puzzle_array1" :key="index">
                        <td>{{userrecords()[page_length1 * page_number1 + index].id}}</td>
                        <td>{{findpuzzlewithid(userrecords()[page_length1 * page_number1 + index].id).numwords}}</td>
                        <td>{{findpuzzlewithid(userrecords()[page_length1 * page_number1 + index].id).max_word_len}}</td>
                        <td>{{findpuzzlewithid(userrecords()[page_length1 * page_number1 + index].id).intro[1]}}</td>
                        <td style="white-space: nowrap"  >{{userrecords()[page_length1 * page_number1 + index].scoredate.split(" ")[0]}}</td>
                        <td style="white-space: nowrap"  >{{userrecords()[page_length1 * page_number1 + index].scoredate.split(" ")[1]}}</td>
                        <td style="white-space: nowrap"  >{{format(userrecords()[page_length1 * page_number1 + index].score)}}</td>
                    </tr>
                </tbody>
            </table>
            <table v-if="showrecords" style='width: 100%; border-collapse: collapse'>
                <tr>
                    <td v-if="puzzle_array1.length > 1">Rekordi {{page_length1 * page_number1 + 1}}-{{Math.min(page_length1 * (page_number1 + 1), userrecords().length)}} od {{userrecords().length}}</td>
                    <td v-else>Rekord {{page_number1 + 1}} od {{userrecords().length}}</td>
                    <td>Stranica {{page_number1 + 1}} od {{max_page_number1 + 1}}</td>
                    <td>
                        <div class="btn-group" role="group">
                            <b-button variant="outline-primary btn-sm" style="font-family: Arial, Helvetica, sans-serif;" v-on:click="previous1()">&#9664;</b-button>
                            <b-button :class = "{ns: page_number1 === 0}" variant="outline-primary btn-sm" v-on:click="jumptopage1(0)">1</b-button>
                            <b-button :class = "{ns: page_number1 === 1}" variant="outline-primary btn-sm" v-on:click="jumptopage1(1)" v-if="Math.ceil(max_page_number1) > 2">2</b-button>
                            <b-button variant="outline-primary btn-sm" v-if="Math.ceil(max_page_number1) > 6">...</b-button>
                            <b-button :class = "{ns: page_number1 === Math.ceil(Math.ceil(max_page_number1 + 1) / 2) - 2}" variant="outline-primary btn-sm" v-on:click="jumptopage1(Math.ceil(Math.ceil(max_page_number1 + 1) / 2) - 2)"  v-if="Math.ceil(max_page_number1) > 5 && Math.ceil(max_page_number1) % 2 === 0">{{Math.ceil(Math.ceil(max_page_number1 + 1) / 2) - 1}}</b-button>
                            <b-button :class = "{ns: page_number1 === Math.ceil(Math.ceil(max_page_number1 + 1) / 2) - 1}" variant="outline-primary btn-sm" v-on:click="jumptopage1(Math.ceil(Math.ceil(max_page_number1 + 1) / 2) - 1)"  v-if="Math.ceil(max_page_number1) > 3">{{Math.ceil(Math.ceil(max_page_number1 + 1) / 2)}}</b-button>
                            <b-button :class = "{ns: page_number1 === Math.ceil(Math.ceil(max_page_number1 + 1) / 2)}" variant="outline-primary btn-sm" v-on:click="jumptopage1(Math.ceil(Math.ceil(max_page_number1 + 1) / 2))"  v-if="Math.ceil(max_page_number1) > 4">{{Math.ceil(Math.ceil(max_page_number1 + 1) / 2) + 1}}</b-button>
                            <b-button variant="outline-primary btn-sm" v-if="Math.ceil(max_page_number1) > 6">...</b-button>
                            <b-button :class = "{ns: page_number1 === Math.ceil(max_page_number1) - 1}" variant="outline-primary btn-sm" v-on:click="jumptopage1(Math.ceil(max_page_number1) - 1)" v-if="Math.ceil(max_page_number1) > 1">{{Math.ceil(max_page_number1)}}</b-button>
                            <b-button :class = "{ns: page_number1 === Math.ceil(max_page_number1)}" variant="outline-primary btn-sm" v-on:click="jumptopage1(Math.ceil(max_page_number1))" v-if="Math.ceil(max_page_number1) > 0">{{Math.ceil(max_page_number1) + 1}}</b-button>
                            <b-button variant="outline-primary btn-sm" style="font-family: Arial, Helvetica, sans-serif;" v-on:click="next1()">&#9654;</b-button>
                        </div>
                    </td>
                </tr>
            </table> 
        </div>
        <div v-else>
            <h4>&#127942; Moji rekordi</h4>
            <h5>Nije zabilježeno da ste riješili niti jednu zagonetku.</h5>
        </div><br>
        <div v-if="user.status === '1' && puzzleauthor().length > 0">
            <h4 v-on:click="showauthor=!showauthor" style="cursor: pointer">&#128218; Autorske zagonetke</h4>
            <div v-if="showauthor">
                <br>Prikaži:
                <b-form-select style="margin:5px;display:inline-block; width: 75px"  v-model="page_length2" class="form-select" aria-label="Default select example">
                    <option v-for='(item, index) in puzzleauthor()' v-bind:key = index>{{index + 1}}</option>
                </b-form-select><br><br>
            </div>
            <table v-if="showauthor" class="table table-hover table-striped text-center">
                <thead>
                <tr style="border-bottom: gray 2px solid;">
                    <th>Broj zagonetke</th>
                    <th>Broj riječi</th>
                    <th>Duljina riječi</th>
                    <th>Izvor</th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="index in puzzle_array2" :key="index">
                    <td>{{puzzleauthor()[page_length2 * page_number2 + index].id}}</td>
                    <td>{{findpuzzlewithid(puzzleauthor()[page_length2 * page_number2 + index].id).numwords}}</td>
                    <td>{{findpuzzlewithid(puzzleauthor()[page_length2 * page_number2 + index].id).max_word_len}}</td>
                    <td>{{findpuzzlewithid(puzzleauthor()[page_length2 * page_number2 + index].id).intro[1]}}</td>
                    <td><router-link title ="Ažuriraj zagonetku" v-if="user.status === '1'" v-bind:to="{ name: 'update', params: { id: puzzleauthor()[page_length2 * page_number2 + index].id }}">&#9997;</router-link></td>
                    <td title ="Riješi zagonetku"><router-link v-bind:to="{ name: 'solve', params: { id: puzzleauthor()[page_length2 * page_number2 + index].id }}">&#127918;</router-link></td>
                    <td title ="Pogledaj informacije o zagonetki"><router-link v-bind:to="{ name: 'puzzle_info', params: { id: puzzleauthor()[page_length2 * page_number2 + index].id }}" style="color: black">&#128712;</router-link></td>
                    <td><span title ="Izbriši zagonetku" v-if="user.status === '1'" style="cursor: pointer" v-on:click="delete_puzzle(puzzleauthor()[page_length2 * page_number2 + index].id)">&#10060;</span></td>
                </tr>
                <tr v-if="user.status === '1'">
                    <td colspan="7"></td>
                    <td title ="Stvori zagonetku"><router-link to="/create">&#10133;</router-link></td>		
                </tr>
                </tbody> 
            </table>
            <table v-if="showauthor" style='width: 100%; border-collapse: collapse'>
                <tr>
                    <td v-if="puzzle_array2.length > 1">Pananagramke {{page_length2 * page_number2 + 1}}-{{Math.min(page_length2 * (page_number2 + 1), puzzleauthor().length)}} od {{puzzleauthor().length}}</td>
                    <td v-else>Pananagramka {{page_number2 + 1}} od {{puzzleauthor().length}}</td>
                    <td>Stranica {{page_number2 + 1}} od {{max_page_number2 + 1}}</td>
                    <td>
                        <div class="btn-group" role="group">
                            <b-button variant="outline-primary btn-sm" style="font-family: Arial, Helvetica, sans-serif;" v-on:click="previous2()">&#9664;</b-button>
                            <b-button :class = "{ns: page_number2 === 0}" variant="outline-primary btn-sm" v-on:click="jumptopage2(0)">1</b-button>
                            <b-button :class = "{ns: page_number2 === 1}" variant="outline-primary btn-sm" v-on:click="jumptopage2(1)" v-if="Math.ceil(max_page_number2) > 2">2</b-button>
                            <b-button variant="outline-primary btn-sm" v-if="Math.ceil(max_page_number2) > 6">...</b-button>
                            <b-button :class = "{ns: page_number2 === Math.ceil(Math.ceil(max_page_number2 + 1) / 2) - 2}" variant="outline-primary btn-sm" v-on:click="jumptopage2(Math.ceil(Math.ceil(max_page_number2 + 1) / 2) - 2)"  v-if="Math.ceil(max_page_number2) > 5 && Math.ceil(max_page_number2) % 2 === 0">{{Math.ceil(Math.ceil(max_page_number2 + 1) / 2) - 1}}</b-button>
                            <b-button :class = "{ns: page_number2 === Math.ceil(Math.ceil(max_page_number2 + 1) / 2) - 1}" variant="outline-primary btn-sm" v-on:click="jumptopage2(Math.ceil(Math.ceil(max_page_number2 + 1) / 2) - 1)"  v-if="Math.ceil(max_page_number2) > 3">{{Math.ceil(Math.ceil(max_page_number2 + 1) / 2)}}</b-button>
                            <b-button :class = "{ns: page_number2 === Math.ceil(Math.ceil(max_page_number2 + 1) / 2)}" variant="outline-primary btn-sm" v-on:click="jumptopage2(Math.ceil(Math.ceil(max_page_number2 + 1) / 2))"  v-if="Math.ceil(max_page_number2) > 4">{{Math.ceil(Math.ceil(max_page_number2 + 1) / 2) + 1}}</b-button>
                            <b-button variant="outline-primary btn-sm" v-if="Math.ceil(max_page_number2) > 6">...</b-button>
                            <b-button :class = "{ns: page_number2 === Math.ceil(max_page_number2) - 1}" variant="outline-primary btn-sm" v-on:click="jumptopage2(Math.ceil(max_page_number2) - 1)" v-if="Math.ceil(max_page_number2) > 1">{{Math.ceil(max_page_number2)}}</b-button>
                            <b-button :class = "{ns: page_number2 === Math.ceil(max_page_number2)}" variant="outline-primary btn-sm" v-on:click="jumptopage2(Math.ceil(max_page_number2))" v-if="Math.ceil(max_page_number2) > 0">{{Math.ceil(max_page_number2) + 1}}</b-button>
                            <b-button variant="outline-primary btn-sm" style="font-family: Arial, Helvetica, sans-serif;" v-on:click="next2()">&#9654;</b-button>
                        </div>
                    </td>
                </tr>
            </table>  
        </div>
        <div v-else>
            <h4>&#128218; Autorske zagonetke</h4>
            <h5 v-if="user.status==='1'">Nije zabilježeno da ste stvorili niti jednu zagonetku.</h5>
            <h5 v-else>Niste admin pa ne možete stvarati zagonetke.</h5>
        </div>
    </div>
</template>

<script>
import { user } from './App.vue';
import axios from 'axios'

export default {
  name: 'UserInfo',
  created() {
      document.title = "Pananagram - Moj korisnički profil";
      document.body.style.backgroundColor = "white";
      if (!this.user.username) {
        this.$router.push("/puzzle_list");
      }
  },
  data () {
    this.getall();
    return {
        user: user,
        pwd: "",
        newusername: "",
        records: [],
        users: [],
        puzzles: [],
        page_length1: 1,
        page_number1: 0,
        page_length2: 1,
        page_number2: 0,
        showsettings: 0,
        showrecords: 0,
        showauthor: 0,
    }
  },
  methods: {
    showpassword() {
        var x = document.getElementById("pass");
        if (x.type === "password") {
            x.type = "text";
            document.getElementById("eye").style.textDecoration = "none";
        } else {
            x.type = "password";
            document.getElementById("eye").style.textDecoration = "line-through";
        }
    },
    show(x) {
        this.user.visibility = x;
        sessionStorage.user = JSON.stringify(this.user);
        axios.post('http://localhost/panagram/src/getusers.php', {
            request: 4,
            username: this.user.username,
            password: this.user.password,
            userId: this.user.userId,
            status: this.user.status,
            visibility: this.user.visibility,
        })
        .then(() => {
            window.alert("Vidljivost profila je izmijenjena.");
        })
        .catch((error) => {
            console.log(error);
        });
    },
    changepwd() {
        this.user.password = this.pwd;
        sessionStorage.user = JSON.stringify(this.user);
        axios.post('http://localhost/panagram/src/getusers.php', {
            request: 4,
            username: this.user.username,
            password: this.pwd,
            userId: this.user.userId,
            status: this.user.status,
            visibility: this.user.visibility,
        })
        .then(() => {
            window.alert("Lozinka je izmijenjena.");
        })
        .catch((error) => {
            console.log(error);
        });
    },
    changeusername() {
        axios.post('http://localhost/panagram/src/updateusername.php', {
            username: this.user.username,
            newusername: this.newusername,
            userId: this.user.userId,
        })
        .then(() => {
            this.user.username = this.newusername;
            window.alert("Korisničko ime je izmijenjeno.");
            sessionStorage.user = JSON.stringify(this.user);
            window.location.reload();
        })
        .catch((error) => {
            console.log(error);
        });
    },
    getall() {
      axios.post('http://localhost/panagram/src/getpuzzles.php', {
          request: 1
      })
      .then((response) => {
        this.puzzles = response.data;
        var i;
        for (i = 0; i < response.data.length; i++) {
            this.puzzles[i].id = parseInt(this.puzzles[i].id);
            this.puzzles[i].intro = JSON.parse(this.puzzles[i].intro);
            this.puzzles[i].numwords = parseInt(this.puzzles[i].numwords);
            this.puzzles[i].max_word_len = parseInt(this.puzzles[i].max_word_len);
            this.puzzles[i].authorid = parseInt(this.puzzles[i].authorid);
        }
        axios.post('http://localhost/panagram/src/getrecords.php', {
            request: 1
        })
        .then((response) => {
            this.records = response.data;
            axios.post('http://localhost/panagram/src/getusers.php', {
                request: 2,
                username: user.username,
            })
            .then((response) => {
                this.users = response.data;
                this.page_length1 = Math.min(18, this.userrecords().length);
                this.page_length2 = Math.min(18, this.puzzleauthor().length);
            })
            .catch((error) => {
                console.log(error);
            });
        })
        .catch((error) => {
            console.log(error);
        });
      })
      .catch((error) => {
          console.log(error);
      });
    },
    format(time) {
      var hours = Math.floor(time / 3600);
      var minutes = Math.floor((time - hours * 3600) / 60);
      var seconds = time - hours * 3600 - minutes * 60;
      if (seconds < 10) {
        seconds = "0" + seconds;
      }
      if (minutes < 10) {
        minutes = "0" + minutes;
      }
      if (hours < 10) {
        hours = "0" + hours;
      }
      return hours + ":" + minutes + ":" + seconds;
    },
    userrecords() {
        var r = [];
        var i = 0;
        for (i = 0; i < this.records.length; i++) {
            if (this.records[i].userId.toString() === this.user.userId.toString()) {
                r.push(this.records[i]);
            }
        }
        return r;
    },
    puzzleauthor() {
        var r = [];
        var i = 0;
        for (i = 0; i < this.puzzles.length; i++) {
            if (this.puzzles[i].authorid.toString() === this.user.userId.toString()) {
                r.push(this.puzzles[i]);
            }
        }
        return r;
    },
    findpuzzlewithid(x) {
        var i = 0;
        for (i = 0; i < this.puzzles.length; i++) {
            if (this.puzzles[i].id.toString() === x.toString()) {
                return this.puzzles[i];
            }
        }
    },
    jumptopage1(page) {
        this.page_number1 = page;
    },
    jumptopage2(page) {
        this.page_number2 = page;
    },
    next1() {
        if (this.page_number1 < this.max_page_number1) {
            this.page_number1++;
        }
    },
    previous1() {
        if (this.page_number1 > 0) {
            this.page_number1--;
        }
    },
    next2() {
        if (this.page_number2 < this.max_page_number2) {
            this.page_number2++;
        }
    },
    previous2() {
        if (this.page_number2 > 0) {
            this.page_number2--;
        }
    },
      delete_puzzle (x) {
        axios.post('http://localhost/panagram/src/getpuzzles.php', {
            request: 4,
            id: parseInt(x),
        })
        .then(() => {
            window.alert("Zagonetka " + x + " je izbrisana.");
            window.location.reload();
        })
        .catch((error) => {
            console.log(error);
        });
        this.getall();
        this.$forceUpdate();
      },
  }, 
  computed: {
    max_page_number1: {
        get: function() {
            return Math.ceil(this.userrecords().length / this.page_length1) - 1; 
        },
        set: function() {
            return Math.ceil(this.userrecords().length / this.page_length1) - 1; 
        }
    },
    max_page_number2: {
        get: function() {
            return Math.ceil(this.puzzleauthor().length / this.page_length2) - 1; 
        },
        set: function() {
            return Math.ceil(this.puzzleauthor().length / this.page_length2) - 1; 
        }
    },
    puzzle_array1: {
        get: function() {
            var a = [];
            var i = 0;
            var n = Math.min(this.page_length1, Math.min(this.page_length1 * (this.page_number1 + 1), this.userrecords().length) - this.page_length1 * this.page_number1);
            for (i = 0; i < n; i++) {
                a.push(i);
            }
            return a;
        },
        set: function() {
            var a = [];
            var i = 0;
            var n = Math.min(this.page_length1, Math.min(this.page_length1 * (this.page_number1 + 1), this.userrecords().length) - this.page_length1 * this.page_number1);
            for (i = 0; i < n; i++) {
                a.push(i);
            }
            return a;
        }
    },
    puzzle_array2: {
        get: function() {
            var a = [];
            var i = 0;
            var n = Math.min(this.page_length2, Math.min(this.page_length2 * (this.page_number2 + 1), this.puzzleauthor().length) - this.page_length2 * this.page_number2);
            for (i = 0; i < n; i++) {
                a.push(i);
            }
            return a;
        },
        set: function() {
            var a = [];
            var i = 0;
            var n = Math.min(this.page_length2, Math.min(this.page_length2 * (this.page_number2 + 1), this.puzzleauthor().length) - this.page_length2 * this.page_number2);
            for (i = 0; i < n; i++) {
                a.push(i);
            }
            return a;
        }
    },
  },
  watch: {
      page_length1: function() {
          if (this.page_length1 < 1) {
              this.page_length1 = Math.min(18, this.userrecords().length);
          }
          if (this.page_length1 > this.userrecords().length) {
              this.page_length1 = 1;
          }
          if (this.page_number1 > this.max_page_number1) {
              this.page_number1 = this.max_page_number1;
          }
      },
      page_length2: function() {
          if (this.page_length2 < 1) {
              this.page_length2 = Math.min(18, this.puzzleauthor().length);
          }
          if (this.page_length2 > this.puzzleauthor().length) {
              this.page_length2 = 1;
          }
          if (this.page_number2 > this.max_page_number2) {
              this.page_number2 = this.max_page_number2;
          }
      }
  }
}
</script>
  
<style scoped src="./pananagram.css">
</style>