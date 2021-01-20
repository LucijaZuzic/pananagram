<template>
   <div class='container-fluid'>
        <br>
        <h1 class="display-4" >&#128221; Popis zagonetki</h1>
        <div class='row'>
            <div class="d-inline-flex align-items-center p-2">
                <div class = "p-2">
                    <span >Prikaži: </span>
                    <b-form-select style="margin:5px;display:inline-block; width: 75px"  v-model="page_length" class="form-select" aria-label="Default select example">
                        <option v-for='(item, index) in puzzles' v-bind:key = index>{{index + 1}}</option>
                    </b-form-select>
                </div>
                <div class="d-inline-flex align-items-center p-2">
                    <div class = "p-2">
                        <span>Sortiraj po: </span>
                    </div>
                    <div class="d-inline-flex align-items-center p-2">
                        <div class = "p-2">
                            <b-form-select name="kriterij" v-model="sortcrit" :options="options" style="width: 200px"></b-form-select>
                        </div>
                        <div class = "p-2">
                            <div class="btn-group" role="group">
                                <b-button variant="outline-primary btn-sm" v-on:click="direction='up';sort()">&#9660;</b-button>
                                <b-button variant="outline-primary btn-sm" v-on:click="direction='down';sort()">&#9650;</b-button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="d-inline-flex align-items-center p-2">
                    <div class = "p-2">
                        <span>Pretraži po: </span>
                    </div>
                    <div class = "p-2">
                        <b-form-select name="filter" v-model="filtercrit" :options="options"  style="width: 200px"></b-form-select><br>
                    </div>
                    <div class = "p-2">
                        <div class="d-inline-flex align-items-center p-2">
                            <div class="p-2"> 
                                &#128270;
                                <b-form-input autocomplete="off" id="regex" style="margin:5px;display:inline-block; width:225px" placeholder="Unesite kriterij za pretragu" type="text"></b-form-input>
                            </div>
                            <div class="p-2">
                                <b-button variant='outline-primary' v-on:click="addfilter()">Dodaj</b-button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class = "col">
                <div class="d-inline-flex align-items-center p-2" v-if="filters.length">
                    <div class="p-2">
                        <span>Kriteriji:</span>
                    </div>
                    <div class="p-2">
                        <b-button variant='light' style="cursor: context-menu; margin:5px" v-for='(filter, index) in filters' :key = index><span style="cursor: pointer" v-on:click = "remove_filter(index)">&#10062;</span> {{filter}}</b-button>
                        <br>
                   </div>
                </div>
                <div class="d-inline-flex align-items-center p-2" v-if="filters.length">
                    <div class="p-2">
                        <b-button variant='outline-danger' style="margin:5px" v-on:click="filters = []; filtertype = []; filtervalue = []; filter()">Poništi sve</b-button>
                    </div>
                </div>
                <div v-else>
                    Nisu uneseni kriteriji za pretragu.
                </div>
            </div>
       </div><br>
       <div class = "row" v-if="puzzles.length">
            <div class = "col">
                <table class="table table-hover table-striped text-center">
                    <thead>
                        <tr>
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
                        <tr v-for='index in puzzle_array' v-bind:key = index>
                            <td >{{puzzles[page_length * page_number + index].id}}</td>
                            <td >{{puzzles[page_length * page_number + index].numwords}}</td>
                            <td >{{puzzles[page_length * page_number + index].max_word_len}}</td>
                            <td class = 'text-left'>{{puzzles[page_length * page_number + index].intro[1]}}</td>
                            <td><router-link title ="Ažuriraj zagonetku" v-if="user.status === '1'" v-bind:to="{ name: 'update', params: { id: puzzles[page_length * page_number + index].id }}">&#9997;</router-link></td>
                            <td  title ="Riješi zagonetku"><router-link v-bind:to="{ name: 'solve', params: { id: puzzles[page_length * page_number + index].id }}">&#127918;</router-link></td>
                            <td  title ="Pogledaj informacije o zagonetki"><router-link v-bind:to="{ name: 'puzzle_info', params: { id: puzzles[page_length * page_number + index].id }}" style="color: black">&#128712;</router-link></td>
                            <td ><span title ="Izbriši zagonetku" v-if="user.status === '1'" style="cursor: pointer" v-on:click="delete_puzzle(puzzles[page_length * page_number + index].id)">&#10060;</span></td>
                        </tr>
                        <tr v-if="user.status === '1'">
                            <td colspan="7"></td>
                            <td title ="Stvori zagonetku"><router-link to="/create">&#10133;</router-link></td>		
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
       <div class = "row" v-if="puzzles.length">
            <div class = "col">
                <table style="width:100%">
                    <tr>
                        <td v-if="puzzle_array.length > 1">Pananagramke {{page_length * page_number + 1}}-{{Math.min(page_length * (page_number + 1), puzzles.length)}} od {{puzzles.length}}</td>
                        <td v-else>Pananagramka {{page_length * page_number + 1}} od {{puzzles.length}}</td>
                        <td>Stranica {{page_number + 1}} od {{max_page_number + 1}}</td>
                        <td>
                            <div class="btn-group" role="group">
                                <b-button variant="outline-primary btn-sm" style="font-family: Arial, Helvetica, sans-serif;" v-on:click="previous()">&#9664;</b-button>
                                <b-button :class = "{ns: page_number === 0}" variant="outline-primary btn-sm" v-on:click="jumptopage(0)">1</b-button>
                                <b-button :class = "{ns: page_number === 1}" variant="outline-primary btn-sm" v-on:click="jumptopage(1)" v-if="Math.ceil(max_page_number) > 2">2</b-button>
                                <b-button variant="outline-primary btn-sm" v-if="Math.ceil(max_page_number) > 6">...</b-button>
                                <b-button :class = "{ns: page_number === Math.ceil(Math.ceil(max_page_number + 1) / 2) - 2}" variant="outline-primary btn-sm" v-on:click="jumptopage(Math.ceil(Math.ceil(max_page_number + 1) / 2) - 2)"  v-if="Math.ceil(max_page_number) > 5 && Math.ceil(max_page_number) % 2 === 0">{{Math.ceil(Math.ceil(max_page_number + 1) / 2) - 1}}</b-button>
                                <b-button :class = "{ns: page_number === Math.ceil(Math.ceil(max_page_number + 1) / 2) - 1}" variant="outline-primary btn-sm" v-on:click="jumptopage(Math.ceil(Math.ceil(max_page_number + 1) / 2) - 1)"  v-if="Math.ceil(max_page_number) > 3">{{Math.ceil(Math.ceil(max_page_number + 1) / 2)}}</b-button>
                                <b-button :class = "{ns: page_number === Math.ceil(Math.ceil(max_page_number + 1) / 2)}" variant="outline-primary btn-sm" v-on:click="jumptopage(Math.ceil(Math.ceil(max_page_number + 1) / 2))"  v-if="Math.ceil(max_page_number) > 4">{{Math.ceil(Math.ceil(max_page_number + 1) / 2) + 1}}</b-button>
                                <b-button variant="outline-primary btn-sm" v-if="Math.ceil(max_page_number) > 6">...</b-button>
                                <b-button :class = "{ns: page_number === Math.ceil(max_page_number) - 1}" variant="outline-primary btn-sm" v-on:click="jumptopage(Math.ceil(max_page_number) - 1)" v-if="Math.ceil(max_page_number) > 1">{{Math.ceil(max_page_number)}}</b-button>
                                <b-button :class = "{ns: page_number === Math.ceil(max_page_number)}" variant="outline-primary btn-sm" v-on:click="jumptopage(Math.ceil(max_page_number))" v-if="Math.ceil(max_page_number) > 0">{{Math.ceil(max_page_number) + 1}}</b-button>
                                <b-button variant="outline-primary btn-sm" style="font-family: Arial, Helvetica, sans-serif;" v-on:click="next()">&#9654;</b-button>
                            </div>
                        </td>
                    </tr>
                </table> 
            </div>
        </div>
        <div class = "row" v-else>
            <div class="col">
                Pretraga pomoću zadanih kriterija nije dala rezultate. Uklonite neke kriterije i pokušajte ponovno.
            </div>
        </div>
    </div>
</template>

<script>
import { user } from './App.vue';
import axios from 'axios'

export default {
  name: 'PuzzleList',
  created() {
    this.getall();
    document.body.style.backgroundColor = "white";    
    document.title = "Pananagram - Lista zagonetki";
  },
  data() {
    return {
        user: user,
        direction: "up",
        options: [
                        {
                            "value": "broju zagonetke",
                            "text": "broju zagonetke"
                        },
                        {
                            "value": "broju riječi",
                            "text": "broju riječi"
                        },
                        {
                            "value": "duljini riječi",
                            "text": "duljini riječi"
                        },
                        {
                            "value": "izvoru",
                            "text": "izvoru"
                        }
                    ],
        puzzles: [],
        page_length: 0,
        page_number: 0,
        filters: [],
        filtertype: [],
        filtervalue: [],
        sortcrit: "broju zagonetke",
        filtercrit: "broju zagonetke",
    }
  },
  methods: {
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
      getall() {
        axios.post('http://localhost/panagram/src/getpuzzles.php', {
            request: 1
        })
        .then((response) => {
            this.puzzles = response.data;
            var i;
            for (i = 0; i < response.data.length; i++) {
                this.puzzles[i].id = parseInt(this.puzzles[i].id);
                this.puzzles[i].numwords = parseInt(this.puzzles[i].numwords);
                this.puzzles[i].max_word_len = parseInt(this.puzzles[i].max_word_len);
                this.puzzles[i].row_with_hint = parseInt(this.puzzles[i].row_with_hint);
                this.puzzles[i].num_order = JSON.parse(this.puzzles[i].num_order);
                this.puzzles[i].solution = JSON.parse(this.puzzles[i].solution);
                this.puzzles[i].word = JSON.parse(this.puzzles[i].word);
                this.puzzles[i].descriptions = JSON.parse(this.puzzles[i].descriptions);
                this.puzzles[i].borders = JSON.parse(this.puzzles[i].borders);
                this.puzzles[i].barriers = JSON.parse(this.puzzles[i].barriers);
                this.puzzles[i].intro = JSON.parse(this.puzzles[i].intro);
            }
            this.page_length = Math.min(18, this.puzzles.length);
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
        compare(a, b) {
            if (this.direction === "up") {
                if (this.sortcrit === "broju zagonetke") {
                    if (a.id > b.id) {
                        return 1;
                    } 
                    if (a.id < b.id) {
                        return 0;
                    } 
                    if (a.numwords > b.numwords) {
                        return 1;
                    } 
                    if (a.numwords < b.numwords) {
                        return 0;
                    } 
                    if (a.max_word_len > b.max_word_len) {
                        return 1;
                    }
                    if (a.max_word_len < b.max_word_len) {
                        return 0;
                    }
                    if (a.intro[1] > b.intro[1]) {
                        return 1;
                    }
                    return 0;
                }
                if (this.sortcrit === "broju riječi") {
                    if (a.numwords > b.numwords) {
                        return 1;
                    } 
                    if (a.numwords < b.numwords) {
                        return 0;
                    } 
                    if (a.id > b.id) {
                        return 1;
                    } 
                    if (a.id < b.id) {
                        return 0;
                    } 
                    if (a.max_word_len > b.max_word_len) {
                        return 1;
                    }
                    if (a.max_word_len < b.max_word_len) {
                        return 0;
                    }
                    if (a.intro[1] > b.intro[1]) {
                        return 1;
                    }
                    return 0;
                }
                if (this.sortcrit === "duljini riječi") {
                    if (a.max_word_len > b.max_word_len) {
                        return 1;
                    } 
                    if (a.max_word_len < b.max_word_len) {
                        return 0;
                    }
                    if (a.id > b.id) {
                        return 1;
                    }  
                    if (a.id < b.id) {
                        return 0;
                    } 
                    if (a.numwords > b.numwords) {
                        return 1;
                    } 
                    if (a.numwords < b.numwords) {
                        return 0;
                    } 
                    if (a.intro[1] > b.intro[1]) {
                        return 1;
                    }
                    return 0;
                }
                if (this.sortcrit === "izvoru") {
                    if (a.intro[1] > b.intro[1]) {
                        return 1;
                    } 
                    if (a.intro[1] < b.intro[1]) {
                        return 0;
                    }
                    if (a.id > b.id) {
                        return 1;
                    }  
                    if (a.id < b.id) {
                        return 0;
                    } 
                    if (a.numwords > b.numwords) {
                        return 1;
                    } 
                    if (a.numwords < b.numwords) {
                        return 0;
                    } 
                    if (a.max_word_len > b.max_word_len) {
                        return 1;
                    }
                    return 0;
                }
            } else {
                if (this.sortcrit === "broju zagonetke") {
                    if (a.id < b.id) {
                        return 1;
                    }  
                    if (a.id > b.id) {
                        return 0;
                    } 
                    if (a.numwords < b.numwords) {
                        return 1;
                    } 
                    if (a.numwords > b.numwords) {
                        return 0;
                    } 
                    if (a.max_word_len < b.max_word_len) {
                        return 1;
                    } 
                    if (a.max_word_len > b.max_word_len) {
                        return 0;
                    } 
                    if (a.intro[1] < b.intro[1]) {
                        return 1;
                    } 
                    return 0;
                }
                if (this.sortcrit === "broju riječi") {
                    if (a.numwords < b.numwords) {
                        return 1;
                    } 
                    if (a.numwords > b.numwords) {
                        return 0;
                    } 
                    if (a.id < b.id) {
                        return 1;
                    }
                    if (a.id > b.id) {
                        return 0;
                    } 
                    if (a.max_word_len < b.max_word_len) {
                        return 1;
                    } 
                    if (a.max_word_len > b.max_word_len) {
                        return 0;
                    } 
                    if (a.intro[1] < b.intro[1]) {
                        return 1;
                    } 
                    return 0;
                }
                if (this.sortcrit === "duljini riječi") {
                    if (a.max_word_len < b.max_word_len) {
                        return 1;
                    } 
                    if (a.max_word_len > b.max_word_len) {
                        return 0;
                    }
                    if (a.id < b.id) {
                        return 1;
                    }
                    if (a.id > b.id) {
                        return 0;
                    } 
                    if (a.numwords < b.numwords) {
                        return 1;
                    } 
                    if (a.numwords > b.numwords) {
                        return 0;
                    } 
                    if (a.intro[1] < b.intro[1]) {
                        return 1;
                    } 
                    return 0;
                }
                if (this.sortcrit === "izvoru") {
                    if (a.intro[1] < b.intro[1]) {
                        return 1;
                    } 
                    if (a.intro[1] > b.intro[1]) {
                        return 0;
                    } 
                    if (a.id < b.id) {
                        return 1;
                    }
                    if (a.id > b.id) {
                        return 0;
                    } 
                    if (a.numwords < b.numwords) {
                        return 1;
                    } 
                    if (a.numwords > b.numwords) {
                        return 0;
                    } 
                    if (a.max_word_len < b.max_word_len) {
                        return 1;
                    } 
                    return 0;
                }
            }
        },
        addfilter() {
            var b = document.getElementById("regex").value;
            if (this.filtercrit === "broju zagonetke") {
                this.filters.push("Broj zagonetke: " + b);
                this.filtertype.push(0);
                this.filtervalue.push(b);
            }
            if (this.filtercrit === "broju riječi") {
                this.filters.push("Broj riječi: " + b);
                this.filtertype.push(1);
                this.filtervalue.push(b);
            }
            if (this.filtercrit === "duljini riječi") {
                this.filters.push("Duljina riječi: " + b);
                this.filtertype.push(2);
                this.filtervalue.push(b);
            }
            if (this.filtercrit === "izvoru") {
                this.filters.push("Izvor: " + b);
                this.filtertype.push(3);
                this.filtervalue.push(b);
            }
            this.filter();
        },
        remove_filter(index) {
            var nf = [];
            var nft  = [];
            var nfv = [];
            var i;
            for (i = 0; i < this.filters.length; i++) {
                if (i != index) {
                    nf.push(this.filters[i]);
                    nft.push(this.filtertype[i]);
                    nfv.push(this.filtervalue[i]);
                }
            }
            this.filters = nf;
            this.filtertype = nft;
            this.filtervalue = nfv;
            this.filter();
        },
        filter() {
            axios.post('http://localhost/panagram/src/getpuzzles.php', {
                request: 1
            })
            .then((response) => {
                this.puzzles = response.data;
                var i;
                for (i = 0; i < response.data.length; i++) {
                    this.puzzles[i].id = parseInt(this.puzzles[i].id);
                    this.puzzles[i].numwords = parseInt(this.puzzles[i].numwords);
                    this.puzzles[i].max_word_len = parseInt(this.puzzles[i].max_word_len);
                    this.puzzles[i].row_with_hint = parseInt(this.puzzles[i].row_with_hint);
                    this.puzzles[i].num_order = JSON.parse(this.puzzles[i].num_order);
                    this.puzzles[i].solution = JSON.parse(this.puzzles[i].solution);
                    this.puzzles[i].word = JSON.parse(this.puzzles[i].word);
                    this.puzzles[i].descriptions = JSON.parse(this.puzzles[i].descriptions);
                    this.puzzles[i].borders = JSON.parse(this.puzzles[i].borders);
                    this.puzzles[i].barriers = JSON.parse(this.puzzles[i].barriers);
                    this.puzzles[i].intro = JSON.parse(this.puzzles[i].intro);
                }
                var j = 0;
                for (j = 0; j < this.filters.length; j++) {
                    var b = this.filtervalue[j];
                    var x = this.filtertype[j];
                    var backup = this.puzzles;
                    this.puzzles = [];
                    for (i = 0; i < backup.length; i++) {
                        var a = backup[i];
                        if (x === 0 && a.id === parseInt(b)) {
                            this.puzzles.push(backup[i]);
                            continue;
                        }
                        if (x === 1 && a.numwords === parseInt(b)) {
                            this.puzzles.push(backup[i]);
                            continue;
                        }
                        if (x === 2 && a.max_word_len === parseInt(b)) {
                            this.puzzles.push(backup[i]);
                            continue;
                        }
                        if (x === 3 && a.intro[1].toLowerCase().match(b.toLowerCase())) {
                            this.puzzles.push(backup[i]);
                            continue;
                        }
                    }
                    if (this.puzzles.length === 0) {
                        break;
                    }
                }
                this.page_length = Math.min(18, this.puzzles.length);
                this.sort();
            })
            .catch((error) => {
                console.log(error);
            });
        },
        sort() {
            var i = 0;
            var j;
            while (i < this.puzzles.length) {
                for (j = 0; j < this.puzzles.length - i - 1; j++) {                    
                    if (this.compare(this.puzzles[j], this.puzzles[j + 1]) == 1) {              
                        var tmp = this.puzzles[j];
                        this.puzzles[j] = this.puzzles[j + 1];
                        this.puzzles[j + 1] = tmp;
                    }
                }
                i++;
            }
            this.$forceUpdate();
        },
      jumptopage(page) {
          this.page_number = page;
      }
  }, 
  computed: {
    max_page_number: {
        get: function() {
            return Math.ceil(this.puzzles.length / this.page_length) - 1; 
        },
        set: function() {
            return Math.ceil(this.puzzles.length / this.page_length) - 1; 
        }
    },
    puzzle_array: {
        get: function() {
            var a = [];
            var i = 0;
            var n = Math.min(this.page_length, Math.min(this.page_length * (this.page_number + 1), this.puzzles.length) - this.page_length * this.page_number);
            for (i = 0; i < n; i++) {
                a.push(i);
            }
            return a;
        },
        set: function() {
            var a = [];
            var i = 0;
            var n = Math.min(this.page_length, Math.min(this.page_length * (this.page_number + 1), this.puzzles.length) - this.page_length * this.page_number);
            for (i = 0; i < n; i++) {
                a.push(i);
            }
            return a;
        }
    },
  },
  watch: {
      page_length: function() {
          if (this.puzzles.length == 0) {
              this.page_length = 0;
              return;
          }
          if (this.page_length < 1) {
              this.page_length = Math.min(18, this.puzzles.length);
          }
          if (this.page_length > this.puzzles.length ) {
              this.page_length = 1;
          }
          if (this.page_number > this.max_page_number) {
              this.page_number = this.max_page_number;
          }
      },
      sortcrit: function() {
          this.sort();
      }
  }
}

</script>


<style scoped src="./pananagram.css">
</style>

 