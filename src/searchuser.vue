<template>
   <div>
        <table class="description" width="100%" style='border-collapse: collapse'>
            <tr>
                <h1 class="description pagetitle">&#128106; Popis korisnika</h1>
            <tr>
            <tr>
                <td>
                    <span class="description">Prikaži: </span>
                    <span class="filter">{{page_length}}</span>
                    <table style="height: 20px; lineheight: 10px; font-size: 10px; display: inline-block; border-collapse: collapse">
                        <tr>
                            <td>
                                <span class="goup" v-on:click="page_length += 1">
                                    &#9650;
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span class="godown" v-on:click="page_length -= 1">
                                    &#9660;
                                </span>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr><br>
            <tr>
                <td style="white-space:nowrap;">
                    &#128270;
                    <input autocomplete="off" class="filter register" id="regex" placeholder="Unesite kriterij za pretragu" type="text">
                    <button class = "router" v-on:click="addfilter()">Pretraži</button>
                </td>
            </tr><br>
            <tr>
                <td>
                    <div v-if="filteredusers.length">
                        <table style='width: 100%; border-collapse: collapse'>
                            <tr style="border-bottom: gray 2px solid;">
                                <th class="odd"><span style="white-space: nowrap">Status</span></th>
                                <th class="odd"><span style="white-space: nowrap">Korisničko</span><span style="white-space: nowrap"> ime</span></th>
                                <th class="odd"></th>
                                <th class="odd"></th>
                                <th class="odd"></th>
                                <th class="odd"></th>
                                <th class="odd"></th>
                            </tr>
                            <tr class = "row" v-for='index in user_array' v-bind:key = index>
                                <td :class="{even: index % 2 === 0, odd: index % 2 !== 0}" title = "Admin" v-if="filteredusers[page_length * page_number + index].status === '1'">&#127775;</td>
                                <td :class="{even: index % 2 === 0, odd: index % 2 !== 0}" title = "Korisnik" v-else>&#11088;</td>
                                <td :class="{even: index % 2 === 0, odd: index % 2 !== 0}">{{filteredusers[page_length * page_number + index].username}}</td>
                                <td :class="{even: index % 2 === 0, odd: index % 2 !== 0}">
                                    <router-link title = "Informacije o korisniku" v-bind:to="{ name: 'friend_info', params: { username: filteredusers[page_length * page_number + index].username }}" style="color: black">
                                        &#128712;
                                    </router-link>
                                </td>
                                <td title = "Prijatelji ste" v-if="arefriends(filteredusers[page_length * page_number + index].username)" :class="{even: index % 2 === 0, odd: index % 2 !== 0}">&#128145;</td>
                                <td title = "Niste prijatelji" v-else :class="{even: index % 2 === 0, odd: index % 2 !== 0}">&#128589;</td>
                                <td title = "Prekinite prijateljstvo" style="cursor: pointer" v-if="arefriends(filteredusers[page_length * page_number + index].username)" :class="{even: index % 2 === 0, odd: index % 2 !== 0}" v-on:click="unfriend(filteredusers[page_length * page_number + index].username)">&#128148;</td>
                                <td v-if="!arefriends(filteredusers[page_length * page_number + index].username) && !amblocked(filteredusers[page_length * page_number + index].username)" title = "Pošaljite zahtjev za prijateljstvo" style="cursor: pointer" :class="{even: index % 2 === 0, odd: index % 2 !== 0}" v-on:click="sendrequest(filteredusers[page_length * page_number + index].username)">&#128140;</td>
                                <td v-if="!arefriends(filteredusers[page_length * page_number + index].username) && amblocked(filteredusers[page_length * page_number + index].username)" :class="{even: index % 2 === 0, odd: index % 2 !== 0}"></td>
                                <td title = "Blokirajte korisnika" style="cursor: pointer" :class="{even: index % 2 === 0, odd: index % 2 !== 0}" v-if="!amblocked(filteredusers[page_length * page_number + index].username)" v-on:click="block(filteredusers[page_length * page_number + index].username)">&#128683;</td>
                                <td title = "Ukloni blokadu" style="cursor: pointer" :class="{even: index % 2 === 0, odd: index % 2 !== 0}" v-else v-on:click="unblock(filteredusers[page_length * page_number + index].username)">&#9989;</td>
                                <td title = "Promoviraj u admina" style="cursor: pointer" :class="{even: index % 2 === 0, odd: index % 2 !== 0}" v-if="canupgrade(filteredusers[page_length * page_number + index])" v-on:click="upgrade(filteredusers[page_length * page_number + index])">&#128081;</td>
                                <td v-else :class="{even: index % 2 === 0, odd: index % 2 !== 0}"></td>
                            </tr>
                        </table> 
                        <br>
                        <table style='width: 100%; border-collapse: collapse'>
                            <tr>
                                <td class = "description" v-if="user_array.length > 1">Korisnici {{page_length * page_number + 1}}-{{Math.min(page_length * (page_number + 1), filteredusers.length)}} od {{filteredusers.length}}</td>
                                <td class = "description" v-else>Korisnik {{page_length * page_number + 1}} od {{filteredusers.length}}</td>
                                <td class = "description">Stranica {{page_number + 1}} od {{max_page_number + 1}}</td>
                                <!--<td class = "numforward" v-on:click="begin()">&#9664;&#9664;</td>-->
                                <td class = "numforward" v-on:click="previous()">&#9664;</td>
                                <td :class = "{numforward: true, selected: page_number === 0}" v-on:click="jumptopage(0)">1</td>
                                <td :class = "{numforward: true, selected: page_number === 1}" v-on:click="jumptopage(1)" v-if="Math.ceil(max_page_number) > 2">2</td>
                                <td class = "numforward" v-if="Math.ceil(max_page_number) > 6">...</td>
                                <td :class = "{numforward: true, selected: page_number === Math.ceil(Math.ceil(max_page_number + 1) / 2) - 2}" v-on:click="jumptopage(Math.ceil(Math.ceil(max_page_number + 1) / 2) - 2)"  v-if="Math.ceil(max_page_number) > 5 && Math.ceil(max_page_number) % 2 === 0">{{Math.ceil(Math.ceil(max_page_number + 1) / 2) - 1}}</td>
                                <td :class = "{numforward: true, selected: page_number === Math.ceil(Math.ceil(max_page_number + 1) / 2) - 1}" v-on:click="jumptopage(Math.ceil(Math.ceil(max_page_number + 1) / 2) - 1)"  v-if="Math.ceil(max_page_number) > 3">{{Math.ceil(Math.ceil(max_page_number + 1) / 2)}}</td>
                                <td :class = "{numforward: true, selected: page_number === Math.ceil(Math.ceil(max_page_number + 1) / 2)}" v-on:click="jumptopage(Math.ceil(Math.ceil(max_page_number + 1) / 2))"  v-if="Math.ceil(max_page_number) > 4">{{Math.ceil(Math.ceil(max_page_number + 1) / 2) + 1}}</td>
                                <td class = "numforward" v-if="Math.ceil(max_page_number) > 6">...</td>
                                <td :class = "{numforward: true, selected: page_number === Math.ceil(max_page_number) - 1}" v-on:click="jumptopage(Math.ceil(max_page_number) - 1)" v-if="Math.ceil(max_page_number) > 1">{{Math.ceil(max_page_number)}}</td>
                                <td :class = "{numforward: true, selected: page_number === Math.ceil(max_page_number)}" v-on:click="jumptopage(Math.ceil(max_page_number))" v-if="Math.ceil(max_page_number) > 0">{{Math.ceil(max_page_number) + 1}}</td>
                                <td class = "numforward" v-on:click="next()">&#9654;</td>
                                <!--<td class = "numforward" v-on:click="end()">&#9654;&#9654;</td>-->
                            </tr>
                        </table> 
                    </div>
                    <div v-else>
                        <p class= "description">Pretraga pomoću zadanog kriterija nije dala rezultate. Izmjenite kriterij i pokušajte ponovno.</p>
                    </div>
                </td>
            </tr>
        </table>
    </div>
</template>

<script>
import { user } from './App.vue';
import axios from 'axios'

export default {
  name: 'SearchUser',
  created() {
    this.getall();
    document.body.style.backgroundColor = "white";    
    document.title = "Pananagram - Lista korisnika";
    if (!this.user.username) {
        this.$router.push("/puzzle_list");
    }
  },
  data() {
    return {
        user: user,
        users: [],
        filteredusers: [],
        page_length: 0,
        page_number: 0,
        filtervalue: "",
        friendships: [],
        blocked: []
    }
  },
  methods: {
      getall() {
        axios.post('http://localhost/panagram/src/getusers.php', {
            request: 1
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
                    axios.post('http://localhost/panagram/src/getblocked.php', {
                        request: 2,
                        blocker: user.username,
                    })
                    .then((response) => {
                        this.blocker = response.data;
                        this.filteredusers = this.users;
                        this.page_length = Math.min(18, this.filteredusers.length);
                        this.filter();
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
      next() {
          if (this.page_number < this.max_page_number) {
            this.page_number++;
          }
      },
      previous() {
          if (this.page_number > 0) {
            this.page_number--;
          }
      },
        addfilter() {
            var b = document.getElementById("regex").value;
            this.filtervalue = b;
            this.filter();
        },
        filter() {
            this.filteredusers = [];
            var i;
            for (i = 0; i < this.users.length; i++) {
                if (!this.isblocked(this.users[i].username) && this.users[i].username.toLowerCase().match(this.filtervalue.toLowerCase()) && this.users[i].username != this.user.username && (this.users[i].visibility.toString() === "1" || this.arefriends(this.users[i].username))) {
                    this.filteredusers.push(this.users[i]);
                }
            }
            this.page_length = Math.min(18, this.filteredusers.length);
        },
      jumptopage(page) {
          this.page_number = page;
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
      sendrequest(b) {
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
      },
      unfriend(b) {
        axios.post('http://localhost/panagram/src/getfriends.php', {
            request: 4,
            username1: this.user.username,
            username2: b
        })
        .then(() => {
            window.alert("Prekinuli ste prijateljstvo.");
            window.location.reload();
        })
        .catch((error) => {
            console.log(error);
        });
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
      },
      canupgrade(b) {
          if (this.user.status === '1' && b.status !== '1') {
              return true;
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
    }
  },
  computed: {
    max_page_number: {
        get: function() {
            return Math.ceil(this.filteredusers.length / this.page_length) - 1; 
        },
        set: function() {
            return Math.ceil(this.filteredusers.length / this.page_length) - 1; 
        }
    },
    user_array: {
        get: function() {
            var a = [];
            var i = 0;
            var n = Math.min(this.page_length, Math.min(this.page_length * (this.page_number + 1), this.filteredusers.length) - this.page_length * this.page_number);
            for (i = 0; i < n; i++) {
                a.push(i);
            }
            return a;
        },
        set: function() {
            var a = [];
            var i = 0;
            var n = Math.min(this.page_length, Math.min(this.page_length * (this.page_number + 1), this.filteredusers.length) - this.page_length * this.page_number);
            for (i = 0; i < n; i++) {
                a.push(i);
            }
            return a;
        }
    },
  },
  watch: {
      page_length: function() {
          if (this.filteredusers.length == 0) {
              this.page_length = 0;
              return;
          }
          if (this.page_length < 1) {
              this.page_length = Math.min(18, this.filteredusers.length);
          }
          if (this.page_length > this.filteredusers.length ) {
              this.page_length = 1;
          }
          if (this.page_number > this.max_page_number) {
              this.page_number = this.max_page_number;
          }
      }
  }
}

</script>


<style scoped src="./pananagram.css">
</style>

 