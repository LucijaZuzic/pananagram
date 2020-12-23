<template>
    <div>
        <table class="description" width="100%" style='border-collapse: collapse'>
            <tr><th style="text-align: center;" colspan="9"><h1 class="pagetitle">&#128240; Zagonetka broj {{id}}</h1></th></tr><br>
            <tr><td class="odd" style="text-align: left; font-size: 20px" colspan="9"><b>Opis: </b>{{intro[0]}}</td></tr><br>
            <tr style="border-bottom: gray 2px solid;">
                <th class="odd"><span style="white-space: nowrap">Broj</span><span style="white-space: nowrap"> riječi</span></th>
                <th class="odd"><span style="white-space: nowrap">Duljina</span><span  style="white-space: nowrap"> riječi</span></th>
                <th style="white-space: nowrap" class="odd">Izvor</th>
                <th style="white-space: nowrap" class="odd">Autor</th>
                <th class="odd">Stvorena</th>
                <th class="odd">Ažurirana</th>
                <th class="odd"></th>
                <th class="odd"></th>
                <th class="odd"></th>
            </tr>
            <tr class = "row">
                <td class="even">{{numwords}}</td>
                <td class="even">{{max_word_len}} </td>
                <td class="even">{{intro[1]}}</td>
                <td class="even" v-if="!isblocked(finduserwithid(authorid).username) && (finduserwithid(authorid).visibility.toString() === '1' || this.arefriends(finduserwithid(authorid).username))">{{finduserwithid(authorid).username}}</td>
                <td class="even" v-else>Izostavljeno</td>
                <td class="even">{{created}}</td>
                <td class="even">{{updated}}</td>
                <td class="even"><router-link title ="Ažuriraj zagonetku" v-if="user.status === '1'" v-bind:to="{ name: 'update', params: { id: id }}">&#9997;</router-link></td>
                <td class="even" title ="Riješi zagonetku"><router-link v-bind:to="{ name: 'solve', params: { id: id }}">&#127918;</router-link></td>
                <td class="even"><span title ="Izbriši zagonetku" v-if="user.status === '1'" style="cursor: pointer" v-on:click="delete_puzzle(id)">&#10060;</span></td>
            </tr>
        </table><br>
        <div v-if="userrecords().length > 0">
            <table class="description" width="100%" style='border-collapse: collapse'>
                <tr>
                    <td style="text-align: center;" colspan="3">
                        <span class="tabletitle">&#127942; Moji osobni rekordi za ovu zagonetku</span>
                        <button v-if="!showmyrecords" class = "xbtn" v-on:click="showmyrecords=!showmyrecords"><span>+</span></button>
                        <button  class = "xbtn" v-on:click="showmyrecords=!showmyrecords" v-else><span>-</span></button>
                    </td>
                </tr>
            </table>
            <table v-if="showmyrecords" class="description" width="100%" style='border-collapse: collapse'>
                <tr>
                    <td colspan="3">
                        Prikaži:
                        <span class="filter">{{page_length1}}</span>
                        <table style="height: 20px; lineheight: 10px; font-size: 10px; display: inline-block; border-collapse: collapse">
                            <tr>
                                <td>
                                    <span class="goup" v-on:click="page_length1 += 1">
                                        &#9650;
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <span class="godown" v-on:click="page_length1 -= 1">
                                        &#9660;
                                    </span>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr><br>
                <tr style="border-bottom: gray 2px solid;">
                    <th style="white-space: nowrap" class="odd">&#128197; Datum</th>
                    <th style="white-space: nowrap" class="odd">&#128337; Vrijeme</th>
                    <th style="white-space: nowrap" class="odd">&#9201; Rezultat</th>
                </tr>
                <tr class = "row" v-for="index in puzzle_array1" :key="index">
                    <td style="white-space: nowrap" :class="{even: index % 2 === 0, odd: index % 2 !== 0}">{{userrecords()[page_length1 * page_number1 + index].scoredate.split(" ")[0]}}</td>
                    <td style="white-space: nowrap" :class="{even: index % 2 === 0, odd: index % 2 !== 0}">{{userrecords()[page_length1 * page_number1 + index].scoredate.split(" ")[1]}}</td>
                    <td style="white-space: nowrap" :class="{even: index % 2 === 0, odd: index % 2 !== 0}">{{format(userrecords()[page_length1 * page_number1 + index].score)}}</td>
                </tr><br>
                <tr>
                    <td colspan="3"> 
                        <table style='width: 100%; border-collapse: collapse'>
                            <tr>
                                <td class = "description" v-if="puzzle_array1.length > 1">Rekordi {{page_length1 * page_number1 + 1}}-{{Math.min(page_length1 * (page_number1 + 1), userrecords().length)}} od {{userrecords().length}}</td>
                                <td class = "description" v-else>Rekord {{page_number1 + 1}} od {{userrecords().length}}</td>
                                <td class = "description">Stranica {{page_number1 + 1}} od {{max_page_number1 + 1}}</td>
                                <!--<td class = "numforward" v-on:click="begin()">&#9664;&#9664;</td>-->
                                <td class = "numforward" v-on:click="previous1()">&#9664;</td>
                                <td :class = "{numforward: true, selected: page_number1 === 0}" v-on:click="jumptopage1(0)">1</td>
                                <td :class = "{numforward: true, selected: page_number1 === 1}" v-on:click="jumptopage1(1)" v-if="Math.ceil(max_page_number1) > 2">2</td>
                                <td class = "numforward" v-if="Math.ceil(max_page_number1) > 6">...</td>
                                <td :class = "{numforward: true, selected: page_number1 === Math.ceil(Math.ceil(max_page_number1 + 1) / 2) - 2}" v-on:click="jumptopage1(Math.ceil(Math.ceil(max_page_number1 + 1) / 2) - 2)"  v-if="Math.ceil(max_page_number1) > 5 && Math.ceil(max_page_number1) % 2 === 0">{{Math.ceil(Math.ceil(max_page_number1 + 1) / 2) - 1}}</td>
                                <td :class = "{numforward: true, selected: page_number1 === Math.ceil(Math.ceil(max_page_number1 + 1) / 2) - 1}" v-on:click="jumptopage1(Math.ceil(Math.ceil(max_page_number1 + 1) / 2) - 1)"  v-if="Math.ceil(max_page_number1) > 3">{{Math.ceil(Math.ceil(max_page_number1 + 1) / 2)}}</td>
                                <td :class = "{numforward: true, selected: page_number1 === Math.ceil(Math.ceil(max_page_number1 + 1) / 2)}" v-on:click="jumptopage1(Math.ceil(Math.ceil(max_page_number1 + 1) / 2))"  v-if="Math.ceil(max_page_number1) > 4">{{Math.ceil(Math.ceil(max_page_number1 + 1) / 2) + 1}}</td>
                                <td class = "numforward" v-if="Math.ceil(max_page_number1) > 6">...</td>
                                <td :class = "{numforward: true, selected: page_number1 === Math.ceil(max_page_number1) - 1}" v-on:click="jumptopage1(Math.ceil(max_page_number1) - 1)" v-if="Math.ceil(max_page_number1) > 1">{{Math.ceil(max_page_number1)}}</td>
                                <td :class = "{numforward: true, selected: page_number1 === Math.ceil(max_page_number1)}" v-on:click="jumptopage1(Math.ceil(max_page_number1))" v-if="Math.ceil(max_page_number1) > 0">{{Math.ceil(max_page_number1) + 1}}</td>
                                <td class = "numforward" v-on:click="next1()">&#9654;</td>
                                <!--<td class = "numforward" v-on:click="end()">&#9654;&#9654;</td>-->
                            </tr>
                        </table> 
                    </td>
                </tr>
            </table>
        </div>
        <p class="description" v-else>
            <table class="description" width="100%" style='border-collapse: collapse'>
                <tr><td style="text-align: center;" colspan="3"><span class="tabletitle">&#127942; Moji osobni rekordi za ovu zagonetku</span></td></tr><br>
                <tr>
                    <td style="text-align: center;" colspan="3">
                        <span v-if="user.username">Nije zabilježeno da ste riješili ovu zagonetku.</span>
                        <span v-else>Niste prijavljeni pa ne možete pratiti svoje rekorde.</span>  
                    </td>
                </tr>
            </table>
        </p><br>
        <div v-if="puzzlerecords().length > 0">
            <table class="description" width="100%" style='border-collapse: collapse'>
                <tr>
                    <td style="text-align: center;" colspan="5">
                        <span class="tabletitle">&#127941; Svi rekordi za ovu zagonetku</span>
                        <button v-if="!showallrecords" class = "xbtn" v-on:click="showallrecords=!showallrecords"><span>+</span></button>
                        <button  class = "xbtn" v-on:click="showallrecords=!showallrecords" v-else><span>-</span></button>
                    </td>
                </tr>
            </table>
            <table v-if="showallrecords" class="description" width="100%" style='border-collapse: collapse'>
                <tr>
                    <td colspan="5">
                        Prikaži:
                        <span class="filter">{{page_length2}}</span>
                        <table style="height: 20px; lineheight: 10px; font-size: 10px; display: inline-block; border-collapse: collapse">
                            <tr>
                                <td>
                                    <span class="goup" v-on:click="page_length2 += 1">
                                        &#9650;
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <span class="godown" v-on:click="page_length2 -= 1">
                                        &#9660;
                                    </span>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr><br> 
                <tr style="border-bottom: gray 2px solid;">
                    <th style="white-space: nowrap" class="odd">Rang</th>
                    <th class="odd"><span style="white-space: nowrap">Status</span></th>
                    <th class="odd"><span style="white-space: nowrap">Korisničko</span><span style="white-space: nowrap"> ime</span></th>
                    <th style="white-space: nowrap" class="odd">&#128197; Datum</th>
                    <th style="white-space: nowrap" class="odd">&#128337; Vrijeme</th>
                    <th style="white-space: nowrap" class="odd">&#9201; Rezultat</th>
                </tr>
                <tr class = "row" v-for="index in puzzle_array2" :key="index">
                    <td :class="{even: index % 2 === 0, odd: index % 2 !== 0}">
                        <span v-if="(page_length2 * page_number2 + index)===0">&#129351;</span>
                        <span v-if="(page_length2 * page_number2 + index)===1">&#129352;</span>
                        <span v-if="(page_length2 * page_number2 + index)===2">&#129353;</span>
                        <span v-if="(page_length2 * page_number2 + index)>2">{{page_length2 * page_number2 + index + 1}}</span>
                    </td>
                    <td :class="{even: index % 2 === 0, odd: index % 2 !== 0}" title = "Admin" v-if="finduserwithid(puzzlerecords()[page_length2 * page_number2 + index].userId).status === '1'">&#127775;</td>
                    <td :class="{even: index % 2 === 0, odd: index % 2 !== 0}" title = "Korisnik" v-else>&#11088;</td>
                    <td :class="{even: index % 2 === 0, odd: index % 2 !== 0}">{{finduserwithid(puzzlerecords()[page_length2 * page_number2 + index].userId).username}}</td>
                    <td style="white-space: nowrap" :class="{even: index % 2 === 0, odd: index % 2 !== 0}">{{puzzlerecords()[page_length2 * page_number2 + index].scoredate.split(" ")[0]}}</td>
                    <td style="white-space: nowrap" :class="{even: index % 2 === 0, odd: index % 2 !== 0}">{{puzzlerecords()[page_length2 * page_number2 + index].scoredate.split(" ")[1]}}</td>
                    <td style="white-space: nowrap" :class="{even: index % 2 === 0, odd: index % 2 !== 0}">{{format(puzzlerecords()[page_length2 * page_number2 + index].score)}}</td>
                </tr><br>
                <tr>
                    <td colspan="6">
                        <table style='width: 100%; border-collapse: collapse'>
                            <tr>
                                <td class = "description" v-if="puzzle_array2.length > 1">Rekordi {{page_length2 * page_number2 + 1}}-{{Math.min(page_length2 * (page_number2 + 1), puzzlerecords().length)}} od {{puzzlerecords().length}}</td>
                                <td class = "description" v-else>Rekord {{page_number2 + 1}} od {{puzzlerecords().length}}</td>
                                <td class = "description">Stranica {{page_number2 + 1}} od {{max_page_number2 + 1}}</td>
                                <!--<td class = "numforward" v-on:click="begin()">&#9664;&#9664;</td>-->
                                <td class = "numforward" v-on:click="previous2()">&#9664;</td>
                                <td :class = "{numforward: true, selected: page_number2 === 0}" v-on:click="jumptopage2(0)">1</td>
                                <td :class = "{numforward: true, selected: page_number2 === 1}" v-on:click="jumptopage2(1)" v-if="Math.ceil(max_page_number2) > 2">2</td>
                                <td class = "numforward" v-if="Math.ceil(max_page_number2) > 6">...</td>
                                <td :class = "{numforward: true, selected: page_number2 === Math.ceil(Math.ceil(max_page_number2 + 1) / 2) - 2}" v-on:click="jumptopage2(Math.ceil(Math.ceil(max_page_number2 + 1) / 2) - 2)"  v-if="Math.ceil(max_page_number2) > 5 && Math.ceil(max_page_number2) % 2 === 0">{{Math.ceil(Math.ceil(max_page_number2 + 1) / 2) - 1}}</td>
                                <td :class = "{numforward: true, selected: page_number2 === Math.ceil(Math.ceil(max_page_number2 + 1) / 2) - 1}" v-on:click="jumptopage2(Math.ceil(Math.ceil(max_page_number2 + 1) / 2) - 1)"  v-if="Math.ceil(max_page_number2) > 3">{{Math.ceil(Math.ceil(max_page_number2 + 1) / 2)}}</td>
                                <td :class = "{numforward: true, selected: page_number2 === Math.ceil(Math.ceil(max_page_number2 + 1) / 2)}" v-on:click="jumptopage2(Math.ceil(Math.ceil(max_page_number2 + 1) / 2))"  v-if="Math.ceil(max_page_number2) > 4">{{Math.ceil(Math.ceil(max_page_number2 + 1) / 2) + 1}}</td>
                                <td class = "numforward" v-if="Math.ceil(max_page_number2) > 6">...</td>
                                <td :class = "{numforward: true, selected: page_number2 === Math.ceil(max_page_number2) - 1}" v-on:click="jumptopage2(Math.ceil(max_page_number2) - 1)" v-if="Math.ceil(max_page_number2) > 1">{{Math.ceil(max_page_number2)}}</td>
                                <td :class = "{numforward: true, selected: page_number2 === Math.ceil(max_page_number2)}" v-on:click="jumptopage2(Math.ceil(max_page_number2))" v-if="Math.ceil(max_page_number2) > 0">{{Math.ceil(max_page_number2) + 1}}</td>
                                <td class = "numforward" v-on:click="next2()">&#9654;</td>
                                <!--<td class = "numforward" v-on:click="end()">&#9654;&#9654;</td>-->
                            </tr>
                        </table> 
                    </td>
                </tr>
            </table>
        </div>
        <p class="description" v-else>
            <table class="description" width="100%" style='border-collapse: collapse'>
                <tr><td style="text-align: center;" colspan="5"><span class="tabletitle">&#127941; Svi rekordi za ovu zagonetku</span></td></tr><br>
                <tr><td style="text-align: center;" colspan="5">Nitko još nije riješio ovu zagonetku.</td></tr>
            </table>
        </p><br>
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