<template>
    <div class='container-fluid'>
        <br>
        <h1 class="display-4">&#128240; Zagonetka broj {{id}}</h1><br><b>Opis: </b>{{intro[0]}}
        <br><br>
        <table class="table table-striped text-center">
            <thead>
                <tr>
                    <th>Broj riječi</th>
                    <th>Duljina riječi</th>
                    <th>Izvor</th>
                    <th>Autor</th>
                    <th>Stvorena</th>
                    <th>Ažurirana</th>
                    <th></th>
                    <th></th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>{{numwords}}</td>
                    <td>{{max_word_len}} </td>
                    <td>{{intro[1]}}</td>
                    <td v-if="!isblocked(finduserwithid(authorid).username) && (finduserwithid(authorid).visibility.toString() === '1' || this.arefriends(finduserwithid(authorid).username))">{{finduserwithid(authorid).username}}</td>
                    <td v-else>Izostavljeno</td>
                    <td>{{created}}</td>
                    <td>{{updated}}</td>
                    <td><router-link title ="Ažuriraj zagonetku" v-if="user.status === '1'" v-bind:to="{ name: 'update', params: { id: id }}">&#9997;</router-link></td>
                    <td title ="Riješi zagonetku"><router-link v-bind:to="{ name: 'solve', params: { id: id }}">&#127918;</router-link></td>
                    <td><span title ="Izbriši zagonetku" v-if="user.status === '1'" style="cursor: pointer" v-on:click="delete_puzzle(id)">&#10060;</span></td>
                </tr>
            </tbody>
        </table>
        <div v-if="userrecords().length > 0">
            <h4 style="cursor: pointer" v-on:click="showmyrecords=!showmyrecords">&#127942; Moji osobni rekordi za ovu zagonetku</h4>
            <div v-if="showmyrecords">
                <br>Prikaži:
                <b-form-select style="margin:5px;display:inline-block; width: 75px"  v-model="page_length1" class="form-select" aria-label="Default select example">
                    <option v-for='(item, index) in userrecords()' v-bind:key = index>{{index + 1}}</option>
                </b-form-select><br><br>
            </div>
            <table class="table table-hover table-striped text-center" v-if="showmyrecords">
                <thead>
                    <tr style="border-bottom: gray 2px solid;">
                        <th>&#128197; Datum</th>
                        <th>&#128337; Vrijeme</th>
                        <th>&#9201; Rezultat</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="index in puzzle_array1" :key="index">
                        <td>{{userrecords()[page_length1 * page_number1 + index].scoredate.split(" ")[0]}}</td>
                        <td>{{userrecords()[page_length1 * page_number1 + index].scoredate.split(" ")[1]}}</td>
                        <td>{{format(userrecords()[page_length1 * page_number1 + index].score)}}</td>
                    </tr>
                </tbody>
            </table>
            <table style='width: 100%; border-collapse: collapse' v-if="showmyrecords">
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
            <h4>&#127942; Moji osobni rekordi za ovu zagonetku</h4>
            <h5 v-if="user.username">Nije zabilježeno da ste riješili ovu zagonetku.</h5>
            <h5 v-else>Niste prijavljeni pa ne možete pratiti svoje rekorde.</h5>  
        </div><br>
        <div v-if="puzzlerecords().length > 0">
            <h4 style="cursor: pointer" v-on:click="showallrecords=!showallrecords">&#127941; Svi rekordi za ovu zagonetku</h4>
            <div v-if="showallrecords">
                <br>Prikaži:
                <b-form-select style="margin:5px;display:inline-block; width: 75px"  v-model="page_length2" class="form-select" aria-label="Default select example">
                    <option v-for='(item, index) in puzzlerecords()' v-bind:key = index>{{index + 1}}</option>
                </b-form-select><br><br>
            </div>
            <table class="table table-hover table-striped text-center" v-if="showallrecords">
                <thead>
                    <tr style="border-bottom: gray 2px solid;">
                        <th>Rang</th>
                        <th>Status </th>
                        <th>Korisničko ime</th>
                        <th>&#128197; Datum</th>
                        <th>&#128337; Vrijeme</th>
                        <th>&#9201; Rezultat</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="index in puzzle_array2" :key="index">
                        <td>
                            <span v-if="(page_length2 * page_number2 + index)===0">&#129351;</span>
                            <span v-if="(page_length2 * page_number2 + index)===1">&#129352;</span>
                            <span v-if="(page_length2 * page_number2 + index)===2">&#129353;</span>
                            <span v-if="(page_length2 * page_number2 + index)>2">{{page_length2 * page_number2 + index + 1}}</span>
                        </td>
                        <td title = "Admin" v-if="finduserwithid(puzzlerecords()[page_length2 * page_number2 + index].userId).status === '1'">&#127775;</td>
                        <td title = "Korisnik" v-else>&#11088;</td>
                        <td>{{finduserwithid(puzzlerecords()[page_length2 * page_number2 + index].userId).username}}</td>
                        <td>{{puzzlerecords()[page_length2 * page_number2 + index].scoredate.split(" ")[0]}}</td>
                        <td>{{puzzlerecords()[page_length2 * page_number2 + index].scoredate.split(" ")[1]}}</td>
                        <td>{{format(puzzlerecords()[page_length2 * page_number2 + index].score)}}</td>
                    </tr>
                </tbody>
            </table>
            <table v-if="showallrecords" style='width: 100%; border-collapse: collapse'>
                <tr>
                    <td v-if="puzzle_array2.length > 1">Rekordi {{page_length2 * page_number2 + 1}}-{{Math.min(page_length2 * (page_number2 + 1), puzzlerecords().length)}} od {{puzzlerecords().length}}</td>
                    <td v-else>Rekord {{page_number2 + 1}} od {{puzzlerecords().length}}</td>
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
                <h4>&#127941; Svi rekordi za ovu zagonetku</h4>
                <h5>Nitko još nije riješio ovu zagonetku.</h5>
        </div>
    </div>
</template>

<script>
import { user } from './App.vue';
import axios from 'axios'

export default {
  name: 'PuzzleInfo',
  created() {
    document.body.style.backgroundColor = "white";
    document.title = "Pananagram - Informacije o zagonetki " + this.$route.params.id;
  },
  data () {
    this.getall();
    return {
        id: 0,
        intro: [],
        numwords: 0,
        max_word_len: 0,
        created: "",
        updated: "",
        records: [],
        users: [],
        puzzles: [],
        authorid: 0,
        user: user,
        friendships: [],
        page_length1: 1,
        page_number1: 0,
        page_length2: 1,
        page_number2: 0,
        showmyrecords: 0,
        showallrecords: 0,
        blocked: [],
    }
  },
  methods: {
    getall() {
      axios.post('http://localhost/panagram/src/getpuzzles.php', {
          request: 5,
          id: this.$route.params.id.toString(),
      })
      .then((response) => {
        this.puzzles = response.data;
        var i;
        this.found = false;
        for (i = 0; i < response.data.length; i++) {
            this.puzzles[i].id = parseInt(this.puzzles[i].id);
            this.puzzles[i].intro = JSON.parse(this.puzzles[i].intro);
            this.puzzles[i].numwords = parseInt(this.puzzles[i].numwords);
            this.puzzles[i].max_word_len = parseInt(this.puzzles[i].max_word_len);
            this.puzzles[i].authorid = parseInt(this.puzzles[i].authorid);
            if (response.data[i].id.toString() === this.$route.params.id.toString()) {
                this.id = this.puzzles[i].id;
                this.intro = this.puzzles[i].intro;
                this.numwords = this.puzzles[i].numwords;
                this.max_word_len = this.puzzles[i].max_word_len;
                this.authorid = this.puzzles[i].authorid;
                this.created = this.puzzles[i].created;
                this.updated = this.puzzles[i].updated;
                this.found = true;
            }
        }
        if (!this.found) {
          window.alert("Ne postoji zagonetka s tim brojem.");
          this.$router.push("/puzzle_list");
        }
        axios.post('http://localhost/panagram/src/getrecords.php', {
            request: 1
        })
        .then((response) => {
            this.records = response.data;
            axios.post('http://localhost/panagram/src/getusers.php', {
                request: 1,
                username: user.username,
            })
            .then((response) => {
                this.users = response.data;
                axios.post('http://localhost/panagram/src/getfriends.php', {
                    request: 2,
                    username: user.username
                })
                .then((response) => {
                    this.friendships = response.data;
                    axios.post('http://localhost/panagram/src/getblocked.php', {
                        request: 3,
                        blocked: user.username,
                    })
                    .then((response) => {
                        this.blocked = response.data;
                        this.page_length1 = Math.min(18, this.userrecords().length);
                        this.page_length2 = Math.min(18, this.puzzlerecords().length);
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
            if (this.records[i].userId.toString() === user.userId.toString() && this.records[i].id.toString() === this.$route.params.id.toString()) {
                r.push(this.records[i]);
            }
        }
        return r;
    },
    arefriends(b) {
        var i;
        for (i = 0; i < this.friendships.length; i++) {
            if (this.friendships[i].username1 == this.user.username && this.friendships[i].username2 == b) {
                return true;
            }
            if (this.friendships[i].username2 == this.user.username && this.friendships[i].username1 == b) {
                return true;
            }
        }
        return false;
    },
    isblocked(b) {
        var i;
        for (i = 0; i < this.blocked.length; i++) {
            if (this.blocked[i].blocked == this.user.username && this.blocked[i].blocker == b) {
                return true;
            }
        }
        return false;
    },
    puzzlerecords() {
        var r = [];
        var i = 0;
        for (i = 0; i < this.records.length; i++) {
            if (this.records[i].id.toString() === this.id.toString() ) {
                var curruser = this.finduserwithid(this.records[i].userId.toString());
                if (!this.isblocked(curruser.username) && (curruser.visibility.toString() === "1" || this.arefriends(curruser.username) || curruser.username === this.user.username)) {
                    r.push(this.records[i]);
                }
            }
        }
        return r;
    },
    finduserwithid(x) {
        var i = 0;
        for (i = 0; i < this.users.length; i++) {
            if (this.users[i].userId.toString() === x.toString()) {
                return this.users[i];
            }
        }
        return user;
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
            return Math.ceil(this.puzzlerecords().length / this.page_length2) - 1; 
        },
        set: function() {
            return Math.ceil(this.puzzlerecords().length / this.page_length2) - 1; 
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
            var n = Math.min(this.page_length2, Math.min(this.page_length2 * (this.page_number2 + 1), this.puzzlerecords().length) - this.page_length2 * this.page_number2);
            for (i = 0; i < n; i++) {
                a.push(i);
            }
            return a;
        },
        set: function() {
            var a = [];
            var i = 0;
            var n = Math.min(this.page_length2, Math.min(this.page_length2 * (this.page_number2 + 1), this.puzzlerecords().length) - this.page_length2 * this.page_number2);
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
              this.page_length2 = Math.min(18, this.puzzlerecords().length);
          }
          if (this.page_length2 > this.puzzlerecords().length) {
              this.page_length2 = 1;
          }
          if (this.page_number2 > this.max_page_number2) {
              this.page_number2 = this.max_page_number2;
          }
      },
      '$route.params.id': function () {window.location.reload();}
  },
}
</script>


<style scoped src="./pananagram.css">
</style>