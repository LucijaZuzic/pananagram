<template>
    <div class='container-fluid'><br>
        <h1 class="display-4" style='width: 100%'>&#128680; Informacije o blokiranim korisnicima</h1>
        <div class="row">
            <div class="col">
                <h4>Blokirajte korisnika s određenim korisničkim imenom</h4>
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
                <b-button variant='success' v-on:click="block(someuser)">Blokiraj</b-button>
            </div>
        </div>
        <div v-if="blockedusers.length > 0">
            <h4 v-on:click="showblocked=!showblocked" style="cursor: pointer" >&#128228; Blokirani korisnici</h4>
            <div v-if="showblocked">
                <br>Prikaži:
                <b-form-select style="margin:5px;display:inline-block; width: 75px"  v-model="page_length1" class="form-select" aria-label="Default select example">
                    <option v-for='(item, index) in blockedusers' v-bind:key = index>{{index + 1}}</option>
                </b-form-select><br><br>
            </div>
            <table v-if="showblocked" class="table table-hover table-striped text-center">
                <thead>
                    <tr style="border-bottom: gray 2px solid;">
                        <th>Status</th>
                        <th>Korisničko ime</th>
                        <th>&#128197; Datum blokiranja</th>
                        <th>&#128337; Vrijeme blokiranja</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="index in puzzle_array1" :key="index">
                        <td   title = "Admin" v-if="finduserwithusername(blockedusers[page_length1 * page_number1 + index].blocked).status === '1'">&#127775;</td>
                        <td   title = "Korisnik" v-else>&#11088;</td>
                        <td  >{{blockedusers[page_length1 * page_number1 + index].blocked}}</td>
                        <td  >{{blockedusers[page_length1 * page_number1 + index].sent.split(' ')[0]}}</td>
                        <td  >{{blockedusers[page_length1 * page_number1 + index].sent.split(' ')[1]}}</td>
                        <td   style="cursor: pointer" title = "Ukloni blokadu" v-on:click="unblock(blockedusers[page_length1 * page_number1 + index].blocked)">&#9989;</td>
                    </tr>
                </tbody>
            </table>
            <table v-if="showblocked" style='width: 100%; border-collapse: collapse'>
                <tr>
                    <td v-if="puzzle_array1.length > 1">Blokiranja {{page_length1 * page_number1 + 1}}-{{Math.min(page_length1 * (page_number1 + 1), blockedusers.length)}} od {{blockedusers.length}}</td>
                    <td v-else>Blokiranje {{page_number1 + 1}} od {{blockedusers.length}}</td>
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
            <h3 >&#128228; Blokirani korisnici</h3>
            <h5 >Nije zabilježeno da ste nekoga blokirali.</h5>
        </div>
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