<template>
    <div>
        <h1 class="description pagetitle" style='width: 100%'>
            <span v-if="user.status==='1'" title="Admin">&#127775;</span>
            <span v-else title="Korisnik">&#11088;</span>
            Moj osobni profil
        </h1>
        <table class="description" style='width: 100%; border-collapse: collapse'>
            <tr>
                <td colspan="2" style="text-align:center">
                    <span class="description tabletitle">&#128295; Postavke</span>
                    <button v-if="!showsettings" class = "xbtn" v-on:click="showsettings=!showsettings"><span>+</span></button>
                    <button  class = "xbtn" v-on:click="showsettings=!showsettings" v-else><span>-</span></button>
                </td>
            </tr>
        </table>
        <table v-if="showsettings" style="margin: 0 auto;">
            <tr style="vertical-align: top">
                <td>
                    <h2 class="description tabletitle" style='width: 100%'>&#128373; Vidljivost profila</h2>
                    <table style="width: 450px">
                        <tr>
                            <td>
                                &#128275;<span style="cursor: pointer" v-on:click="show(1)" :class="{description: true, visiblesetting: this.user.visibility.toString() === '1'}"> Javno (profil je vidljiv svim korisnicima)</span>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                &#128274;<span style="cursor: pointer" v-on:click="show(0)" :class="{description: true, visiblesetting: this.user.visibility.toString() === '0'}"> Privatno (profil je vidljiv samo prijateljima)</span>
                            </td>
                        </tr>
                    </table> 
                </td>
                <td>
                    <h2 class="description tabletitle" style='width: 100%'>&#128273; Promjeni lozinku</h2>
                    <table style="margin: 0 auto; vertical-align: top">
                        <tr>
                            <td>
                                <table class="frame">
                                    <tr>
                                        <td style="white-space: nowrap;">
                                            <span class="description">Lozinka: </span>
                                            <input class="register description" style="border: none; outline: none" id="pass" type="password" v-model="pwd" />
                                            <span class="eye description" style="text-decoration: line-through" id="eye" v-on:click="showpassword()">&#128065;</span>
                                        </td>
                                        <td>
                                            <button class="submit" v-on:click="changepwd()">Spremi </button>
                                        </td>
                                    </tr>
                                </table><br>
                            </td>
                        </tr>
                    </table> 
                </td>
                <td>
                    <h2 class="description tabletitle" style='width: 100%'>&#129485; Promjeni korisničko ime</h2>
                    <table style="margin: 0 auto; vertical-align: top">
                        <tr>
                            <td>
                                <table class="frame">
                                    <tr>
                                        <td style="white-space: nowrap;">
                                            <span class="description">Korisničko ime: </span>
                                            <input class="register description" style="border: none; outline: none" id="uname" type="text" v-model="newusername" />
                                        </td>
                                        <td>
                                            <button class="submit" v-on:click="changeusername()">Spremi </button>
                                        </td>
                                    </tr>
                                </table><br>
                            </td>
                        </tr>
                    </table> 
                </td>
            </tr>
        </table><br>
        <div v-if="userrecords().length > 0">
            <table class="description" style='width: 100%; border-collapse: collapse'>
                <tr>
                    <td style="text-align: center" colspan="7">
                        <span class="tabletitle">&#127942; Moji rekordi</span>
                        <button v-if="!showrecords" class = "xbtn" v-on:click="showrecords=!showrecords"><span>+</span></button>
                        <button  class = "xbtn" v-on:click="showrecords=!showrecords" v-else><span>-</span></button>
                    </td>
                </tr>
            </table>
            <table v-if="showrecords" class="description" style='width: 100%; border-collapse: collapse'>
                <tr>
                    <td colspan="7">
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
                    <th class="odd"><span style="white-space: nowrap">Broj</span><span style="white-space: nowrap"> zagonetke</span></th>
                    <th class="odd"><span style="white-space: nowrap">Broj</span><span style="white-space: nowrap"> riječi</span></th>
                    <th class="odd"><span style="white-space: nowrap">Duljina</span><span  style="white-space: nowrap"> riječi</span></th>
                    <th style="white-space: nowrap" class="odd">Izvor</th>
                    <th style="white-space: nowrap" class="odd">&#128197; Datum</th>
                    <th style="white-space: nowrap" class="odd">&#128337; Vrijeme</th>
                    <th style="white-space: nowrap" class="odd">&#9201; Rezultat</th>
                </tr>
                <tr class = "row" v-for="index in puzzle_array1" :key="index">
                    <td :class="{even: index % 2 === 0, odd: index % 2 !== 0}">{{userrecords()[page_length1 * page_number1 + index].id}}</td>
                    <td :class="{even: index % 2 === 0, odd: index % 2 !== 0}">{{findpuzzlewithid(userrecords()[page_length1 * page_number1 + index].id).numwords}}</td>
                    <td :class="{even: index % 2 === 0, odd: index % 2 !== 0}">{{findpuzzlewithid(userrecords()[page_length1 * page_number1 + index].id).max_word_len}}</td>
                    <td :class="{even: index % 2 === 0, odd: index % 2 !== 0}">{{findpuzzlewithid(userrecords()[page_length1 * page_number1 + index].id).intro[1]}}</td>
                    <td style="white-space: nowrap" :class="{even: index % 2 === 0, odd: index % 2 !== 0}">{{userrecords()[page_length1 * page_number1 + index].scoredate.split(" ")[0]}}</td>
                    <td style="white-space: nowrap" :class="{even: index % 2 === 0, odd: index % 2 !== 0}">{{userrecords()[page_length1 * page_number1 + index].scoredate.split(" ")[1]}}</td>
                    <td style="white-space: nowrap" :class="{even: index % 2 === 0, odd: index % 2 !== 0}">{{format(userrecords()[page_length1 * page_number1 + index].score)}}</td>
                </tr><br>
                <tr>
                    <td colspan="7">
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
            <table class="description" style='width: 100%; border-collapse: collapse'>
                <tr><td style="text-align: center" colspan="7"><span class="tabletitle">&#127942; Moji rekordi</span></td></tr><br>
                <tr><td style="text-align: center" colspan="7">Nije zabilježeno da ste riješili niti jednu zagonetku.</td></tr>
            </table>
        </p><br>
        <div v-if="user.status === '1' && puzzleauthor().length > 0">
            <table class="description" style='width: 100%; border-collapse: collapse'>
                <tr>
                    <td style="text-align: center;" colspan="8">
                        <span class="tabletitle">&#128218; Autorske zagonetke</span>
                        <button v-if="!showauthor" class = "xbtn" v-on:click="showauthor=!showauthor"><span>+</span></button>
                        <button  class = "xbtn" v-on:click="showauthor=!showauthor" v-else><span>-</span></button>
                    </td>
                </tr>
            </table>
            <table v-if="showauthor" class="description" style='width: 100%; border-collapse: collapse'>
                <tr>
                    <td colspan="8">
                        Prikaži:
                        <!--<div class="dropdown">
                            <button class="dropbtn2">{{page_length2}}</button>
                            <div class="dropdown-content2">
                                <label v-for="size in Array(Math.min(puzzleauthor().length, 18)).keys()" :key="size" class="updown">
                                    <input  type="radio" name="page_size" v-on:change="page_length2=size + 1" :value="size"> <span class="checkmark">{{size + 1}}</span> 
                                </label>
                            </div>
                        </div>-->
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
                    <th class="odd"><span style="white-space: nowrap">Broj</span><span style="white-space: nowrap"> zagonetke</span></th>
                    <th class="odd"><span style="white-space: nowrap">Broj</span><span style="white-space: nowrap"> riječi</span></th>
                    <th class="odd"><span style="white-space: nowrap">Duljina</span><span  style="white-space: nowrap"> riječi</span></th>
                    <th style="white-space: nowrap" class="odd">Izvor</th>
                    <th class="odd"></th>
                    <th class="odd"></th>
                    <th class="odd"></th>
                    <th class="odd"></th>
                </tr>
                <tr class = "row" v-for="index in puzzle_array2" :key="index">
                    <td :class="{even: index % 2 === 0, odd: index % 2 !== 0}">{{puzzleauthor()[page_length2 * page_number2 + index].id}}</td>
                    <td :class="{even: index % 2 === 0, odd: index % 2 !== 0}">{{findpuzzlewithid(puzzleauthor()[page_length2 * page_number2 + index].id).numwords}}</td>
                    <td :class="{even: index % 2 === 0, odd: index % 2 !== 0}">{{findpuzzlewithid(puzzleauthor()[page_length2 * page_number2 + index].id).max_word_len}}</td>
                    <td :class="{even: index % 2 === 0, odd: index % 2 !== 0}">{{findpuzzlewithid(puzzleauthor()[page_length2 * page_number2 + index].id).intro[1]}}</td>
                    <td :class="{even: index % 2 === 0, odd: index % 2 !== 0}"><router-link title ="Ažuriraj zagonetku" v-if="user.status === '1'" v-bind:to="{ name: 'update', params: { id: puzzleauthor()[page_length2 * page_number2 + index].id }}">&#9997;</router-link></td>
                    <td :class="{even: index % 2 === 0, odd: index % 2 !== 0}" title ="Riješi zagonetku"><router-link v-bind:to="{ name: 'solve', params: { id: puzzleauthor()[page_length2 * page_number2 + index].id }}">&#127918;</router-link></td>
                    <td :class="{even: index % 2 === 0, odd: index % 2 !== 0}" title ="Pogledaj informacije o zagonetki"><router-link v-bind:to="{ name: 'puzzle_info', params: { id: puzzleauthor()[page_length2 * page_number2 + index].id }}" style="color: black">&#128712;</router-link></td>
                    <td :class="{even: index % 2 === 0, odd: index % 2 !== 0}"><span title ="Izbriši zagonetku" v-if="user.status === '1'" style="cursor: pointer" v-on:click="delete_puzzle(puzzleauthor()[page_length2 * page_number2 + index].id)">&#10060;</span></td>
                </tr>
                <tr v-if="user.status === '1'">
                    <td :class="{even: page_length2 % 2 === 0, odd: page_length2 % 2 !== 0}" colspan="7"></td>
                    <td :class="{even: page_length2 % 2 === 0, odd: page_length2 % 2 !== 0}" title ="Stvori zagonetku"><router-link to="/create">&#10133;</router-link></td>		
                </tr><br> 
                <tr>
                    <td colspan="8">
                       <table style='width: 100%; border-collapse: collapse'>
                            <tr>
                                <td class = "description" v-if="puzzle_array2.length > 1">Pananagramke {{page_length2 * page_number2 + 1}}-{{Math.min(page_length2 * (page_number2 + 1), puzzleauthor().length)}} od {{puzzleauthor().length}}</td>
                                <td class = "description" v-else>Pananagramka {{page_number2 + 1}} od {{puzzleauthor().length}}</td>
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
            <table class="description" style='width: 100%; border-collapse: collapse'>
                <tr><td style="text-align: center;" colspan="8"><span class="tabletitle">&#128218; Autorske zagonetke</span></td></tr><br>
                <tr>
                    <td style="text-align: center;" colspan="8">
                        <span v-if="user.status==='1'">Nije zabilježeno da ste stvorili niti jednu zagonetku.</span>
                        <span v-else>Niste admin pa ne možete stvarati zagonetke.</span>
                    </td>
                </tr>
            </table>
        </p><br>
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