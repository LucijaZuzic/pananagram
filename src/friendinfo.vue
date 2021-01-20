<template>
    <div class='container-fluid'>
        <h1 class="display-4">
            <span v-if="friend.status==='1'" title="Admin">&#127775; Informacije o <i>adminu</i> <span style="color: #2196F3">{{this.friend.username}}</span></span>
            <span v-else title="Korisnik">&#11088; Informacije o <i>korisniku</i> <span style="color: #2196F3">{{this.friend.username}}</span></span>
        </h1><br>
        <div class="row">
            <div clas="col">
                <h4  v-if="arefriends(friend.username)">
                    <p>&#128145; Prijatelji ste od {{friendssince(friend.username)}}</p>
                    <p style="cursor: pointer" v-on:click="unfriend(friend.username)">&#128148; Prekini prijateljstvo</p>
                </h4>
                <h4  v-else>
                    <p>&#128589; Niste prijatelji</p>
                    <p v-if="!amblocked(friend.username)" style="cursor: pointer" v-on:click="sendrequest(friend.username)">&#128140; Pošalji zahtjev za prijateljstvo</p>
                </h4>
            </div>
            <div clas="col">
                <h4 >
                    <p v-if="amblocked(friend.username)" style="cursor: pointer" v-on:click="unblock(friend.username)">&#9989; Ukloni blokadu</p>
                    <p v-else style="cursor: pointer" v-on:click="block(friend.username)">&#128683; Blokiraj korisnika</p>
                </h4>
            </div>
            <div clas="col">
                <h4  v-if="user.status === '1' && friend.status !== '1'">
                    <p style="cursor: pointer" v-on:click="upgrade(friend)">&#128081; Promoviraj u admina</p>
                </h4>
            </div>
        </div><br>
        <div class = "row" v-if="userrecords().length > 0">
            <div class="col">
                <h4 v-on:click="showrecords=!showrecords" style="cursor: pointer" v-if="friend.status==='1'">&#127942; Osobni rekordi <i>admina</i> <span style="color: #2196F3">{{this.friend.username}}</span></h4>
                <h4 v-on:click="showrecords=!showrecords" style="cursor: pointer" v-else>&#127942; Osobni rekordi <i>korisnika</i> <span style="color: #2196F3">{{this.friend.username}}</span></h4>
            </div>
        </div>
        <div class = "row" v-if="userrecords().length > 0">
            <div class="col" v-if="showrecords">
                <br>Prikaži:
                <b-form-select style="margin:5px;display:inline-block; width: 75px"  v-model="page_length1" class="form-select" aria-label="Default select example">
                    <option v-for='(item, index) in userrecords()' v-bind:key = index>{{index + 1}}</option>
                </b-form-select><br><br>
            </div>
            <table v-if="showrecords" class="table table-hover table-striped text-center" style='width: 100%; border-collapse: collapse'>
                <thead>
                <tr style="border-bottom: gray 2px solid;">
                    <th>Broj zagonetke</th>
                    <th>Broj riječi</th>
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
                    <td>{{userrecords()[page_length1 * page_number1 + index].scoredate.split(" ")[0]}}</td>
                    <td>{{userrecords()[page_length1 * page_number1 + index].scoredate.split(" ")[1]}}</td>
                    <td>{{format(userrecords()[page_length1 * page_number1 + index].score)}}</td>
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
            <h4  v-if="friend.status==='1'">&#127942; Osobni rekordi <i>admina</i> <span style="color: #2196F3">{{this.friend.username}}</span></h4>
            <h4  v-else>&#127942; Osobni rekordi <i>korisnika</i> <span style="color: #2196F3">{{this.friend.username}}</span></h4>
            <h5 >Nije zabilježeno da je <span style="color: #2196F3">{{this.friend.username}}</span> riješio niti jednu zagonetku.</h5>
        </div><br>
        <div v-if="friend.status === '1' && puzzleauthor().length > 0">
            <h4 style="cursor: pointer" v-on:click="showauthor=!showauthor" v-if="friend.status==='1'">&#128218; Autorske zagonetke <i>admina</i> <span style="color: #2196F3">{{this.friend.username}}</span></h4>
            <h4 style="cursor: pointer" v-on:click="showauthor=!showauthor" v-else>&#128218; Autorske zagonetke <i>korisnika</i> <span style="color: #2196F3">{{this.friend.username}}</span></h4>
            <div v-if="showauthor">
                <br>Prikaži:
                <b-form-select style="margin:5px;display:inline-block; width: 75px"  v-model="page_length2" class="form-select" aria-label="Default select example">
                    <option v-for='(item, index) in puzzleauthor()' v-bind:key = index>{{index + 1}}</option>
                </b-form-select><br><br>
            </div>
            <table v-if="showauthor" class="table table-hover table-striped text-center" style='width: 100%; border-collapse: collapse'>
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
                        <td  >{{puzzleauthor()[page_length2 * page_number2 + index].id}}</td>
                        <td  >{{findpuzzlewithid(puzzleauthor()[page_length2 * page_number2 + index].id).numwords}}</td>
                        <td  >{{findpuzzlewithid(puzzleauthor()[page_length2 * page_number2 + index].id).max_word_len}}</td>
                        <td  >{{findpuzzlewithid(puzzleauthor()[page_length2 * page_number2 + index].id).intro[1]}}</td>
                        <td  ><router-link v-if="user.status === '1'" v-bind:to="{ name: 'update', params: { id: puzzleauthor()[page_length2 * page_number2 + index].id }}">&#127918;</router-link></td>
                        <td  ><router-link title ="Riješi zagonetku" v-bind:to="{ name: 'solve', params: { id: puzzleauthor()[page_length2 * page_number2 + index].id }}">&#127918;</router-link></td>
                        <td  ><router-link title ="Pogledaj informacije o zagonetki" v-bind:to="{ name: 'puzzle_info', params: { id: puzzleauthor()[page_length2 * page_number2 + index].id }}" style="color: black">&#128712;</router-link></td>
                        <td  ><span v-if="user.status === '1'" style="cursor: pointer" v-on:click="delete_puzzle(puzzleauthor()[page_length2 * page_number2 + index].id)">&#10060;</span></td>
                    </tr>
                    <tr v-if="user.status === '1'">
                        <td colspan="7"></td>
                        <td title ="Stvori zagonetku"><router-link to="/create">&#10133;</router-link></td>		
                    </tr>
                </tbody>
                </table>
                <table  v-if="showauthor" style='width: 100%; border-collapse: collapse'>
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
            <h4  v-if="friend.status==='1'">&#128218; Autorske zagonetke <i>admina</i> <span style="color: #2196F3">{{this.friend.username}}</span></h4>
            <h4  v-else>&#128218; Autorske zagonetke <i>korisnika</i> <span style="color: #2196F3">{{this.friend.username}}</span></h4>
            <h5  v-if="friend.status==='1'">Nije zabilježeno da je admin <span style="color: #2196F3">{{this.friend.username}}</span> stvorio niti jednu zagonetku.</h5>
            <h5  v-else>Korisnik <span style="color: #2196F3">{{this.friend.username}}</span> nije admin pa ne može stvarati zagonetke.</h5>
        </div><br>
    </div>
</template>

<script>
import { user } from './App.vue';
import axios from 'axios'

export default {
  name: 'FriendInfo',
  created() {
        document.title = "Pananagram - Korisnički profil " + this.$route.params.username;
        document.body.style.backgroundColor = "white";
        if (!this.user.username) {
            this.$router.push("/puzzle_list");
        }
  },
  data () {
    this.getall();
    return {
        user: user,
        friend: {},
        records: [],
        puzzles: [],
        page_length1: 1,
        page_number1: 0,
        page_length2: 1,
        page_number2: 0,
        friendships: [],
        showrecords: 0,
        showauthor: 0,
        blocker: [],
        blocked: [],
    }
  },
  methods: {
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
                username: this.$route.params.username,
            })
            .then((response) => {
                this.users = response.data;
                this.friend = response.data[0];
                if (this.friend.username === this.user.username) {
                    this.$router.push("/user_info");
                    return;
                }
                this.page_length1 = Math.min(18, this.userrecords().length);
                this.page_length2 = Math.min(18, this.puzzleauthor().length);
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
                        axios.post('http://localhost/panagram/src/getblocked.php', {
                            request: 2,
                            blocker: user.username,
                        })
                        .then((response) => {
                            this.blocker = response.data;
                            if (this.isblocked(this.friend.username) || !this.friend.username || !(this.friend.visibility.toString() === '1' || this.arefriends(this.friend.username))) {
                                this.$router.push("/puzzle_list");
                            }
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
      })
      .catch((error) => {
          console.log(error);
      });
    },
    amblocked(b) {
        var i;
        for (i = 0; i < this.blocker.length; i++) {
            if (this.blocker[i].blocked == b && this.blocker[i].blocker == this.user.username) {
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
    unblock(b) {
        axios.post('http://localhost/panagram/src/getblocked.php', {
            request: 5,
            blocker: this.user.username,
            blocked: b
        })
        .then(() => {
            window.alert("Uklonili ste blokadu.");
            window.location.reload();
        })
        .catch((error) => {
            console.log(error);
        });
      },
    block(b) {
        axios.post('http://localhost/panagram/src/getblocked.php', {
            request: 4,
            blocker: this.user.username,
            blocked: b
        })
        .then(() => {
            axios.post('http://localhost/panagram/src/getfriends.php', {
                request: 4,
                username1: this.user.username,
                username2: b
            })
            .then(() => {
                axios.post('http://localhost/panagram/src/getfriendrequests.php', {
                    request: 5,
                    requester: this.user.username,
                    requested: b
                }) 
                .then(() => {
                    axios.post('http://localhost/panagram/src/getfriendrequests.php', {
                        request: 5,
                        requested: this.user.username,
                        requester: b
                    }) 
                    .then(() => {
                        window.alert("Korisnik je blokiran.");
                        window.location.reload();
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
    friendssince(b) {
            var i;
            for (i = 0; i < this.friendships.length; i++) {
                if (this.friendships[i].username1 == this.user.username && this.friendships[i].username2 == b) {
                    return this.friendships[i].since;
                }
                if (this.friendships[i].username2 == this.user.username && this.friendships[i].username1 == b) {
                    return this.friendships[i].since;
                }
            }
            return 0;
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
        if (this.records.length === 0 || this.friend.userId === undefined) {
            return r;
        }
        var i = 0;
        for (i = 0; i < this.records.length; i++) {
            if (this.records[i].userId.toString() === this.friend.userId.toString()) {
                r.push(this.records[i]);
            }
        }
        return r;
    },
    puzzleauthor() {
        var r = [];
        if (this.records.length === 0 || this.friend.userId === undefined) {
            return r;
        }
        var i = 0;
        for (i = 0; i < this.puzzles.length; i++) {
            if (this.puzzles[i].authorid.toString() === this.friend.userId.toString()) {
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
      sendrequest(b) {
        axios.post('http://localhost/panagram/src/getfriendrequests.php', {
            request: 4,
            requester: this.user.username,
            requested: b
        })
        .then(() => {
            window.alert("Zahtjev poslan");
        })
        .catch((error) => {
            console.log(error);
        });
      },
      unfriend(b) {
        axios.post('http://localhost/panagram/src/getfriends.php', {
            request: 4,
            username1: this.user.username,
            username2: b
        })
        .then(() => {
            window.alert("Prekinuli ste prijateljstvo");
            window.location.reload();
        })
        .catch((error) => {
            console.log(error);
        });
      },
      delete_puzzle (x) {
        axios.post('http://localhost/panagram/src/getpuzzles.php', {
            request: 4,
            id: parseInt(x),
        })
        .then(() => {
            window.alert("Zagonetka ", x, " je izbrisana.");
            window.location.reload();
        })
        .catch((error) => {
            console.log(error);
        });
        this.getall();
        this.$forceUpdate();
      },
      upgrade(b) {
          axios.post('http://localhost/panagram/src/getusers.php', {
                request: 4,
                userId: b.userId,
                username: b.username,
                password: b.password,
                status: 1,
                visibility: b.visibility,
            })
            .then(() => {
                window.location.reload();
            })
            .catch((error) => {
                console.log(error);
            });
      }
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
      },
      '$route.params.username': function () {window.location.reload();}
  }
}
</script>
  
<style scoped src="./pananagram.css">
</style>