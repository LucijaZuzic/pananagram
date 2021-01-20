<template>
    <div class='container-fluid'><br>
        <h1 class="display-4"  style='width: 100%'>&#128158; Informacije o zahtjevima za prijateljstvo</h1><br>
        <div class="row">
        <div class="col">
            <h4>Pošaljite zahtjev korisniku s određenim korisničkim imenom</h4>
        </div>
        </div>
        <div class="d-inline-flex align-items-center p-2">
            <div class="p-2">
                <span>Korisničko ime: </span>
            </div>
            <div class="p-2">
                <b-form-input style="display:inline-block;width:225px;" type="text" v-model="someuser"></b-form-input>&nbsp;
            </div>
            <div class="p-2">
                <b-button variant='success' v-on:click="sendrequest(someuser)">Pošalji zahtjev</b-button>
            </div>
        </div>
        <div v-if="requestsent.length > 0">
            <h4 style="cursor: pointer" v-on:click="showsent=!showsent">&#128228; Poslani zahtjevi za prijateljstvo</h4>
            <div v-if="showsent">
                <br>Prikaži:
                <b-form-select style="margin:5px;display:inline-block; width: 75px"  v-model="page_length1" class="form-select" aria-label="Default select example">
                    <option v-for='(item, index) in requestsent' v-bind:key = index>{{index + 1}}</option>
                </b-form-select><br><br>
            </div>
            <table v-if="showsent" class="table table-hover table-striped text-center">
                <thead>
                    <tr style="border-bottom: gray 2px solid;">
                        <th>Status</th>
                        <th>Korisničko ime</th>
                        <th>&#128197; Datum slanja</th>
                        <th>&#128337; Vrijeme slanja</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="index in puzzle_array1" :key="index">
                        <td title = "Admin" v-if="finduserwithusername(requestsent[page_length1 * page_number1 + index].requested).status === '1'">&#127775;</td>
                        <td title = "Korisnik" v-else>&#11088;</td>
                        <td>{{requestsent[page_length1 * page_number1 + index].requested}}</td>
                        <td>{{requestsent[page_length1 * page_number1 + index].sent.split(' ')[0]}}</td>
                        <td>{{requestsent[page_length1 * page_number1 + index].sent.split(' ')[1]}}</td>
                    </tr>
                </tbody>
            </table>
            <table v-if="showsent" style='width: 100%; border-collapse: collapse'>
                <tr>
                    <td v-if="puzzle_array1.length > 1">Zahtjevi {{page_length1 * page_number1 + 1}}-{{Math.min(page_length1 * (page_number1 + 1), requestsent.length)}} od {{requestsent.length}}</td>
                    <td v-else>Zahtjev {{page_number1 + 1}} od {{requestsent.length}}</td>
                    <td >Stranica {{page_number1 + 1}} od {{max_page_number1 + 1}}</td>
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
            <h4 >&#128228; Poslani zahtjevi za prijateljstvo</h4> 
            <h5 >Nije zabilježeno da ste poslali niti jedan zahtjev.</h5>
        </div><br>
        <div v-if="requestreceived.length > 0">
            <h4 style="cursor: pointer" v-on:click="showreceived=!showreceived" >&#128229; Primljeni zahtjevi za prijateljstvo</h4>
            <div v-if="showreceived">
                <br>Prikaži:
                <b-form-select style="margin:5px;display:inline-block; width: 75px"  v-model="page_length2" class="form-select" aria-label="Default select example">
                    <option v-for='(item, index) in requestreceived' v-bind:key = index>{{index + 1}}</option>
                </b-form-select><br><br>
            </div>
            <table v-if="showreceived"  class="table table-hover table-striped text-center">
                <thead>
                    <tr style="border-bottom: gray 2px solid;">
                        <th>Status</th>
                        <th>Korisničko ime</th>
                        <th>&#128197; Datum slanja</th>
                        <th>&#128337; Vrijeme slanja</th>
                        <th>Odbij zahtjev</th>
                        <th>Prihvati zahtjev</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="index in puzzle_array2" :key="index">
                        <td title = "Admin" v-if="finduserwithusername(requestreceived[page_length2 * page_number2 + index].requester).status === '1'">&#127775;</td>
                        <td title = "Korisnik" v-else>&#11088;</td>
                        <td>{{requestreceived[page_length2 * page_number2 + index].requester}}</td>
                        <td>{{requestreceived[page_length2 * page_number2 + index].sent.split(' ')[0]}}</td>
                        <td  >{{requestreceived[page_length2 * page_number2 + index].sent.split(' ')[1]}}</td>
                        <td style="cursor: pointer"   v-on:click="decline(requestreceived[page_length2 * page_number2 + index].requester)">&#128148;</td>
                        <td style="cursor: pointer"   v-on:click="accept(requestreceived[page_length2 * page_number2 + index].requester)">&#128150;</td>
                    </tr>
                </tbody>
            </table>
            <table v-if="showreceived" style='width: 100%; border-collapse: collapse'>
                <tr>
                    <td v-if="puzzle_array2.length > 1">Zahtjevi {{page_length2 * page_number2 + 1}}-{{Math.min(page_length2 * (page_number2 + 1), requestreceived.length)}} od {{requestreceived.length}}</td>
                    <td v-else>Zahtjev {{page_number2 + 1}} od {{requestreceived.length}}</td>
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
            <h4 >&#128229; Primljeni zahtjevi za prijateljstvo</h4>
            <h5 >Nije zabilježeno da ste primili niti jedan zahtjev.</h5>
        </div>
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
                                        window.location.reload();
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