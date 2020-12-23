<template>
    <div>
        <h1 class="description pagetitle" style='width: 100%'>&#128680; Informacije o blokiranim korisnicima</h1>
        <table style="margin: 0 auto; vertical-align: top">
            <tr>
                <td>
                    <span class="tabletitle description">Blokirajte korisnika s određenim korisničkim imenom</span>
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
                                <button class="submit" v-on:click="block(someuser)">Blokiraj</button>
                            </td>
                        </tr>
                    </table><br>
                </td>
            </tr>
        </table> 
        <div v-if="blockedusers.length > 0">
            <table class="description" style='width: 100%; border-collapse: collapse'>
                <tr>
                    <td style="text-align: center" colspan="7">
                        <span class="tabletitle">&#128228; Blokirani korisnici</span>
                        <button v-if="!showblocked" class = "xbtn" v-on:click="showblocked=!showblocked"><span>+</span></button>
                        <button  class = "xbtn" v-on:click="showblocked=!showblocked" v-else><span>-</span></button>
                    </td>
                </tr>
            </table>
            <table v-if="showblocked" class="description" style='width: 100%; border-collapse: collapse'>
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
                    <th class="odd"><span style="white-space: nowrap">&#128197; Datum</span><span style="white-space: nowrap"> blokiranja</span></th>
                    <th class="odd"><span style="white-space: nowrap">&#128337; Vrijeme</span><span style="white-space: nowrap"> blokiranja</span></th>
                    <th class="odd"></th>
                </tr>
                <tr class = "row" v-for="index in puzzle_array1" :key="index">
                    <td :class="{even: index % 2 === 0, odd: index % 2 !== 0}" title = "Admin" v-if="finduserwithusername(blockedusers[page_length1 * page_number1 + index].blocked).status === '1'">&#127775;</td>
                    <td :class="{even: index % 2 === 0, odd: index % 2 !== 0}" title = "Korisnik" v-else>&#11088;</td>
                    <td :class="{even: index % 2 === 0, odd: index % 2 !== 0}">{{blockedusers[page_length1 * page_number1 + index].blocked}}</td>
                    <td :class="{even: index % 2 === 0, odd: index % 2 !== 0}">{{blockedusers[page_length1 * page_number1 + index].sent.split(' ')[0]}}</td>
                    <td :class="{even: index % 2 === 0, odd: index % 2 !== 0}">{{blockedusers[page_length1 * page_number1 + index].sent.split(' ')[1]}}</td>
                    <td :class="{even: index % 2 === 0, odd: index % 2 !== 0}" style="cursor: pointer" title = "Ukloni blokadu" v-on:click="unblock(blockedusers[page_length1 * page_number1 + index].blocked)">&#9989;</td>
                </tr><br>
                <tr>
                    <td colspan="5">
                        <table style='width: 100%; border-collapse: collapse'>
                            <tr>
                                <td class = "description" v-if="puzzle_array1.length > 1">Blokiranja {{page_length1 * page_number1 + 1}}-{{Math.min(page_length1 * (page_number1 + 1), blockedusers.length)}} od {{blockedusers.length}}</td>
                                <td class = "description" v-else>Blokiranje {{page_number1 + 1}} od {{blockedusers.length}}</td>
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
                <tr><td style="text-align: center" colspan="7"><span class="tabletitle">&#128228; Blokirani korisnici</span></td></tr><br>
                <tr><td style="text-align: center" colspan="7">Nije zabilježeno da ste nekoga blokirali.</td></tr>
            </table>
        </p><br>
        <!--
        <div v-if="blockedme.length > 0">
            <table class="description" style='width: 100%; border-collapse: collapse'>
                <tr>
                    <td style="text-align: center;" colspan="8">
                        <span class="tabletitle">&#128229; Korisnici koji su me blokirali</span>
                        <button v-if="!showblockedme" class = "xbtn" v-on:click="showblockedme=!showblockedme"><span>+</span></button>
                        <button  class = "xbtn" v-on:click="showblockedme=!showblockedme" v-else><span>-</span></button>
                    </td>
                </tr>
            </table>
            <table v-if="showblockedme" class="description" style='width: 100%; border-collapse: collapse'>
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
                    <th class="odd"><span style="white-space: nowrap">&#128197; Datum</span><span style="white-space: nowrap"> blokiranja</span></th>
                    <th class="odd"><span style="white-space: nowrap">&#128337; Vrijeme</span><span style="white-space: nowrap"> blokiranja</span></th>
                </tr>
                <tr class = "row" v-for="index in puzzle_array2" :key="index">
                    <td :class="{even: index % 2 === 0, odd: index % 2 !== 0}" title = "Admin" v-if="finduserwithusername(blockedme[page_length2 * page_number2 + index].blocker).status === '1'">&#127775;</td>
                    <td :class="{even: index % 2 === 0, odd: index % 2 !== 0}" title = "Korisnik" v-else>&#11088;</td>
                    <td :class="{even: index % 2 === 0, odd: index % 2 !== 0}">{{blockedme[page_length2 * page_number2 + index].blocker}}</td>
                    <td :class="{even: index % 2 === 0, odd: index % 2 !== 0}">{{blockedme[page_length2 * page_number2 + index].sent.split(' ')[0]}}</td>
                    <td :class="{even: index % 2 === 0, odd: index % 2 !== 0}">{{blockedme[page_length2 * page_number2 + index].sent.split(' ')[1]}}</td>
                </tr>
                <tr>
                    <td colspan="4">
                       <table style='width: 100%; border-collapse: collapse'>
                            <tr>
                                <td class = "description" v-if="puzzle_array2.length > 1">Blokiranja {{page_length2 * page_number2 + 1}}-{{Math.min(page_length2 * (page_number2 + 1), blockedme.length)}} od {{blockedme.length}}</td>
                                <td class = "description" v-else>Blokiranje {{page_number2 + 1}} od {{blockedme.length}}</td>
                                <td class = "description">Stranica {{page_number2 + 1}} od {{max_page_number2 + 1}}</td>
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
                            </tr>
                        </table>  
                    </td>
                </tr>
            </table>
        </div>
        <p class="description" v-else>
            <table class="description" style='width: 100%; border-collapse: collapse'>
                <tr><td style="text-align: center;" colspan="8"><span class="tabletitle">&#128229; Korisnici koji su me blokirali</span></td></tr><br>
                <tr><td style="text-align: center;" colspan="8">Nije zabilježeno da Vas je netko blokirao.</td></tr>
            </table>
        </p><br>
        -->
    </div>
</template>

<script>
import { user } from './App.vue';
import axios from 'axios'

export default {
  name: 'BlockedInfo',
  created() {
      document.title = "Pananagram - Blokirani korisnici";
      document.body.style.backgroundColor = "white";
      if (!this.user.username) {
        this.$router.push("/puzzle_list");
      }
  },
  data () {
    this.getall();
    return {
        user: user,
        blockedme: [],
        blockedusers: [],
        page_length1: 1,
        page_number1: 0,
        page_length2: 1,
        page_number2: 0,
        showblockedme: 0,
        showblocked: 0,
        someuser: "",
        users: [],
    }
  },
  methods: {
    getall() {
        axios.post('http://localhost/panagram/src/getblocked.php', {
            request: 3,
            blocked: user.username,
        })
        .then((response) => {
            this.blockedme = response.data;
            this.page_length2 = Math.min(18, this.blockedme.length);
            axios.post('http://localhost/panagram/src/getblocked.php', {
                request: 2,
                blocker: user.username,
            })
            .then((response) => {
                this.blockedusers = response.data;
                this.page_length1 = Math.min(18, this.blockedusers.length);
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
    finduserwithusername(x) {
        var i = 0;
        for (i = 0; i < this.users.length; i++) {
            if (this.users[i].username.toString() === x.toString()) {
                return this.users[i];
            }
        }
        return user;
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
      }
  }, 
  computed: {
    max_page_number1: {
        get: function() {
            return Math.ceil(this.blockedusers.length / this.page_length1) - 1; 
        },
        set: function() {
            return Math.ceil(this.blockedusers.length / this.page_length1) - 1; 
        }
    },
    max_page_number2: {
        get: function() {
            return Math.ceil(this.blockedme.length / this.page_length2) - 1; 
        },
        set: function() {
            return Math.ceil(this.blockedme.length / this.page_length2) - 1; 
        }
    },
    puzzle_array1: {
        get: function() {
            var a = [];
            var i = 0;
            var n = Math.min(this.page_length1, Math.min(this.page_length1 * (this.page_number1 + 1), this.blockedusers.length) - this.page_length1 * this.page_number1);
            for (i = 0; i < n; i++) {
                a.push(i);
            }
            return a;
        },
        set: function() {
            var a = [];
            var i = 0;
            var n = Math.min(this.page_length1, Math.min(this.page_length1 * (this.page_number1 + 1), this.blockedusers.length) - this.page_length1 * this.page_number1);
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
            var n = Math.min(this.page_length2, Math.min(this.page_length2 * (this.page_number2 + 1), this.blockedme.length) - this.page_length2 * this.page_number2);
            for (i = 0; i < n; i++) {
                a.push(i);
            }
            return a;
        },
        set: function() {
            var a = [];
            var i = 0;
            var n = Math.min(this.page_length2, Math.min(this.page_length2 * (this.page_number2 + 1), this.blockedme.length) - this.page_length2 * this.page_number2);
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
              this.page_length1 = Math.min(18, this.blockedusers.length);
          }
          if (this.page_length1 > this.blockedusers.length) {
              this.page_length1 = 1;
          }
          if (this.page_number1 > this.max_page_number1) {
              this.page_number1 = this.max_page_number1;
          }
      },
      page_length2: function() {
          if (this.page_length2 < 1) {
              this.page_length2 = Math.min(18, this.blockedme.length);
          }
          if (this.page_length2 > this.blockedme.length) {
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