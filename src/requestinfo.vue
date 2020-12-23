<template>
    <div>
        <h1 class="description pagetitle" style='width: 100%'>&#128158; Informacije o zahtjevima za prijateljstvo</h1>
        <table style="margin: 0 auto; vertical-align: top">
            <tr>
                <td>
                    <span class="tabletitle description">Pošaljite zahtjev korisniku s određenim korisničkim imenom</span>
                </td>
            </tr>
            <tr>
                <td>
                    <table class="frame" style="margin: 0 auto; vertical-align: top">
                        <tr>
                            <td style="white-space: nowrap;">
                                <span class="description">Korisničko ime: </span>
                                <input class="register description" style="border: none; outline: none" type="text" v-model="someuser"/> 
                            </td>
                            <td>
                                <button class="submit" v-on:click="sendrequest(someuser)">Pošalji</button>
                            </td>
                        </tr>
                    </table><br>
                </td>
            </tr>
        </table> 
        <div v-if="requestsent.length > 0">
            <table class="description" style='width: 100%; border-collapse: collapse'>
                <tr>
                    <td style="text-align: center" colspan="7">
                        <span class="tabletitle">&#128228; Poslani zahtjevi za prijateljstvo</span>
                        <button v-if="!showsent" class = "xbtn" v-on:click="showsent=!showsent"><span>+</span></button>
                        <button  class = "xbtn" v-on:click="showsent=!showsent" v-else><span>-</span></button>
                    </td>
                </tr>
            </table>
            <table v-if="showsent" class="description" style='width: 100%; border-collapse: collapse'>
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
                    <th class="odd"><span style="white-space: nowrap">Status</span></th>
                    <th class="odd"><span style="white-space: nowrap">Korisničko</span><span style="white-space: nowrap"> ime</span></th>
                    <th class="odd"><span style="white-space: nowrap">&#128197; Datum</span><span style="white-space: nowrap"> slanja</span></th>
                    <th class="odd"><span style="white-space: nowrap">&#128337; Vrijeme</span><span style="white-space: nowrap"> slanja</span></th>
                </tr>
                <tr class = "row" v-for="index in puzzle_array1" :key="index">
                    <td :class="{even: index % 2 === 0, odd: index % 2 !== 0}" title = "Admin" v-if="finduserwithusername(requestsent[page_length1 * page_number1 + index].requested).status === '1'">&#127775;</td>
                    <td :class="{even: index % 2 === 0, odd: index % 2 !== 0}" title = "Korisnik" v-else>&#11088;</td>
                    <td :class="{even: index % 2 === 0, odd: index % 2 !== 0}">{{requestsent[page_length1 * page_number1 + index].requested}}</td>
                    <td :class="{even: index % 2 === 0, odd: index % 2 !== 0}">{{requestsent[page_length1 * page_number1 + index].sent.split(' ')[0]}}</td>
                    <td :class="{even: index % 2 === 0, odd: index % 2 !== 0}">{{requestsent[page_length1 * page_number1 + index].sent.split(' ')[1]}}</td>
                </tr><br>
                <tr>
                    <td colspan="4">
                        <table style='width: 100%; border-collapse: collapse'>
                            <tr>
                                <td class = "description" v-if="puzzle_array1.length > 1">Zahtjevi {{page_length1 * page_number1 + 1}}-{{Math.min(page_length1 * (page_number1 + 1), requestsent.length)}} od {{requestsent.length}}</td>
                                <td class = "description" v-else>Zahtjev {{page_number1 + 1}} od {{requestsent.length}}</td>
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
                <tr><td style="text-align: center" colspan="7"><span class="tabletitle">&#128228; Poslani zahtjevi za prijateljstvo</span></td></tr><br>
                <tr><td style="text-align: center" colspan="7">Nije zabilježeno da ste poslali niti jedan zahtjev.</td></tr>
            </table>
        </p><br>
        <div v-if="requestreceived.length > 0">
            <table class="description" style='width: 100%; border-collapse: collapse'>
                <tr>
                    <td style="text-align: center;" colspan="8">
                        <span class="tabletitle">&#128229; Primljeni zahtjevi za prijateljstvo</span>
                        <button v-if="!showreceived" class = "xbtn" v-on:click="showreceived=!showreceived"><span>+</span></button>
                        <button  class = "xbtn" v-on:click="showreceived=!showreceived" v-else><span>-</span></button>
                    </td>
                </tr>
            </table>
            <table v-if="showreceived" class="description" style='width: 100%; border-collapse: collapse'>
                <tr>
                    <td colspan="8">
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
                    <th class="odd"><span style="white-space: nowrap">Status</span></th>
                    <th class="odd"><span style="white-space: nowrap">Korisničko</span><span style="white-space: nowrap"> ime</span></th>
                    <th class="odd"><span style="white-space: nowrap">&#128197; Datum</span><span style="white-space: nowrap"> slanja</span></th>
                    <th class="odd"><span style="white-space: nowrap">&#128337; Vrijeme</span><span style="white-space: nowrap"> slanja</span></th>
                    <th class="odd"><span style="white-space: nowrap">Odbij</span><span style="white-space: nowrap"> zahtjev</span></th>
                    <th class="odd"><span style="white-space: nowrap">Prihvati</span><span style="white-space: nowrap"> zahtjev</span></th>
                </tr>
                <tr class = "row" v-for="index in puzzle_array2" :key="index">
                    <td :class="{even: index % 2 === 0, odd: index % 2 !== 0}" title = "Admin" v-if="finduserwithusername(requestreceived[page_length2 * page_number2 + index].requester).status === '1'">&#127775;</td>
                    <td :class="{even: index % 2 === 0, odd: index % 2 !== 0}" title = "Korisnik" v-else>&#11088;</td>
                    <td :class="{even: index % 2 === 0, odd: index % 2 !== 0}">{{requestreceived[page_length2 * page_number2 + index].requester}}</td>
                    <td :class="{even: index % 2 === 0, odd: index % 2 !== 0}">{{requestreceived[page_length2 * page_number2 + index].sent.split(' ')[0]}}</td>
                    <td :class="{even: index % 2 === 0, odd: index % 2 !== 0}">{{requestreceived[page_length2 * page_number2 + index].sent.split(' ')[1]}}</td>
                    <td style="cursor: pointer" :class="{even: index % 2 === 0, odd: index % 2 !== 0}" v-on:click="decline(requestreceived[page_length2 * page_number2 + index].requester)">&#128148;</td>
                    <td style="cursor: pointer" :class="{even: index % 2 === 0, odd: index % 2 !== 0}" v-on:click="accept(requestreceived[page_length2 * page_number2 + index].requester)">&#128150;</td>
                </tr>
                <tr>
                    <td colspan="6">
                       <table style='width: 100%; border-collapse: collapse'>
                            <tr>
                                <td class = "description" v-if="puzzle_array2.length > 1">Zahtjevi {{page_length2 * page_number2 + 1}}-{{Math.min(page_length2 * (page_number2 + 1), requestreceived.length)}} od {{requestreceived.length}}</td>
                                <td class = "description" v-else>Zahtjev {{page_number2 + 1}} od {{requestreceived.length}}</td>
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
                <tr><td style="text-align: center;" colspan="8"><span class="tabletitle">&#128229; Primljeni zahtjevi za prijateljstvo</span></td></tr><br>
                <tr><td style="text-align: center;" colspan="8">Nije zabilježeno da ste primili niti jedan zahtjev.</td></tr>
            </table>
        </p><br>
    </div>
</template>

<script>
import { user } from './App.vue';
import axios from 'axios'

export default {
  name: 'RequestInfo',
  created() {
      document.title = "Pananagram - Zahtjevi za prijateljstvo";
      document.body.style.backgroundColor = "white";
      if (!this.user.username) {
        this.$router.push("/puzzle_list");
      }
  },
  data () {
    this.getall();
    return {
        user: user,
        requestreceived: [],
        requestsent: [],
        page_length1: 1,
        page_number1: 0,
        page_length2: 1,
        page_number2: 0,
        showreceived: 0,
        showsent: 0,
        someuser: "",
        users: [],
        friendships: [],
        blocked: [],
        blocker: [],
    }
  },
  methods: {
    getall() {
        axios.post('http://localhost/panagram/src/getfriendrequests.php', {
            request: 2,
            requested: user.username,
        })
        .then((response) => {
            this.requestreceived = response.data;
            this.page_length2 = Math.min(18, this.requestreceived.length);
            axios.post('http://localhost/panagram/src/getfriendrequests.php', {
                request: 3,
                requester: user.username,
            })
            .then((response) => {
                this.requestsent = response.data;
                this.page_length1 = Math.min(18, this.requestsent.length);
                axios.post('http://localhost/panagram/src/getusers.php', {
                        request: 1,
                })
                .then((response) => {
                    this.users = response.data;
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
    accept(b) {
        axios.post('http://localhost/panagram/src/getfriendrequests.php', {
            request: 5,
            requester: b,
            requested: user.username,
        })
        .then(() => {
            axios.post('http://localhost/panagram/src/getfriends.php', {
                request: 3,
                username1: b,
                username2: user.username,
            })
            .then(() => {
                window.alert("Zahtjev je prihvaćen.");
                window.location.reload();
            })
            .catch((error) => {
                console.log(error);
            });
        })
        .catch((error) => {
            console.log(error);
        });
    },
    decline(b) {
        axios.post('http://localhost/panagram/src/getfriendrequests.php', {
            request: 5,
            requester: b,
            requested: user.username,
        })
        .then(() => {
            window.alert("Zahtjev je odbijen.");
            window.location.reload();
        })
        .catch((error) => {
            console.log(error);
        });
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
        if (b === this.user.username) {
            window.alert("Ne možete poslati zahtjev za prijateljstvo sami sebi.");
            return;
        }
        axios.post('http://localhost/panagram/src/getusers.php', {
                request: 2,
                username: b,
        })
        .then((response) => {
            if (response.data.length != 0) {
                axios.post('http://localhost/panagram/src/getfriends.php', {
                    request: 2,
                    username: this.user.username
                })
                .then((response) => {
                    var i = 0;
                    this.friendships = response.data;
                    for (i = 0; i < this.friendships.length; i++){
                        if (this.friendships[i].username1 == this.user.username && this.friendships[i].username2 == b) {
                            window.alert("Već ste prijatelj korisnika " + b + ".");
                            return;
                        }
                        if (this.friendships[i].username2 == this.user.username && this.friendships[i].username1 == b) {
                            window.alert("Već ste prijatelj korisnika " + b + ".");
                            return;
                        }
                    }
                    axios.post('http://localhost/panagram/src/getblocked.php', {
                        request: 3,
                        blocked: user.username,
                    })
                    .then((response) => {
                        this.blocked = response.data;
                        if (this.isblocked(b)) {
                            window.alert("Ne možete poslati zahtjev korisniku koji Vas je blokirao.");
                        } else {
                            axios.post('http://localhost/panagram/src/getblocked.php', {
                                request: 2,
                                blocker: user.username,
                            })
                            .then((response) => {
                                this.blocker = response.data;
                                if (this.amblocked(b)) {
                                    window.alert("Ne možete poslati zahtjev korisniku kojeg ste blokirali.");
                                } else {
                                    axios.post('http://localhost/panagram/src/getfriendrequests.php', {
                                        request: 4,
                                        requester: this.user.username,
                                        requested: b
                                    })
                                    .then(() => {
                                        window.alert("Zahtjev je poslan.");
                                    })
                                    .catch((error) => {
                                        console.log(error);
                                    });
                                }
                            })
                            .catch((error) => {
                                console.log(error);
                            });
                        }
                    })
                    .catch((error) => {
                        console.log(error);
                    });
                })
                .catch((error) => {
                    console.log(error);
                });
            } else {
                window.alert("Nema korisnika s korisničkim imenom " + b + ".");
            }
        })
        .catch((error) => {
            console.log(error);
        });
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
    amblocked(b) {
        var i;
        for (i = 0; i < this.blocker.length; i++) {
            if (this.blocker[i].blocked == b && this.blocker[i].blocker == this.user.username) {
                return true;
            }
        }
        return false;
    },
    finduserwithusername(x) {
        var i = 0;
        for (i = 0; i < this.users.length; i++) {
            if (this.users[i].username.toString() === x.toString()) {
                return this.users[i];
            }
        }
        return user;
    },
  }, 
  computed: {
    max_page_number1: {
        get: function() {
            return Math.ceil(this.requestsent.length / this.page_length1) - 1; 
        },
        set: function() {
            return Math.ceil(this.requestsent.length / this.page_length1) - 1; 
        }
    },
    max_page_number2: {
        get: function() {
            return Math.ceil(this.requestreceived.length / this.page_length2) - 1; 
        },
        set: function() {
            return Math.ceil(this.requestreceived.length / this.page_length2) - 1; 
        }
    },
    puzzle_array1: {
        get: function() {
            var a = [];
            var i = 0;
            var n = Math.min(this.page_length1, Math.min(this.page_length1 * (this.page_number1 + 1), this.requestsent.length) - this.page_length1 * this.page_number1);
            for (i = 0; i < n; i++) {
                a.push(i);
            }
            return a;
        },
        set: function() {
            var a = [];
            var i = 0;
            var n = Math.min(this.page_length1, Math.min(this.page_length1 * (this.page_number1 + 1), this.requestsent.length) - this.page_length1 * this.page_number1);
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
            var n = Math.min(this.page_length2, Math.min(this.page_length2 * (this.page_number2 + 1), this.requestreceived.length) - this.page_length2 * this.page_number2);
            for (i = 0; i < n; i++) {
                a.push(i);
            }
            return a;
        },
        set: function() {
            var a = [];
            var i = 0;
            var n = Math.min(this.page_length2, Math.min(this.page_length2 * (this.page_number2 + 1), this.requestreceived.length) - this.page_length2 * this.page_number2);
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
              this.page_length1 = Math.min(18, this.requestsent.length);
          }
          if (this.page_length1 > this.requestsent.length) {
              this.page_length1 = 1;
          }
          if (this.page_number1 > this.max_page_number1) {
              this.page_number1 = this.max_page_number1;
          }
      },
      page_length2: function() {
          if (this.page_length2 < 1) {
              this.page_length2 = Math.min(18, this.requestreceived.length);
          }
          if (this.page_length2 > this.requestreceived.length) {
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