<template>
  <div class="container-fluid">    
    <br>
    <div class="row">
      <div class="col">
          <span>Istaknuti pojam: </span>
          <b-form-input type="range" min="1" :max="max_word_len" v-model="row_with_hint" v-on:change="change_row_hint()"><span class="description"> {{row_with_hint}}</span></b-form-input>
     </div>
      <div class="col">
          <span>Broj riječi: </span>
          <b-form-input type="range" min="11" max="15" v-model="numwords" v-on:change="change_num_words()"><span class="description"> {{numwords}}</span></b-form-input>
      </div>
      <div class="col">
          <span>Duljina riječi: </span>
          <b-form-input type="range" min="8" max="15" v-model="max_word_len" v-on:change="change_len()"><span class="description"> {{max_word_len}}</span></b-form-input>
      </div>
    </div>   
    <div class="row">
      <div class="col">
          <button :class="{ choice : true, selected: letter === a }" v-for="a in alphabet" :key = "a" v-on:click="select_letter(a)">{{a}}</button>
       </div>
    </div>  
    <div class="row">
      <div class="col">
        <b-form-select style="margin:5px;display:inline-block; width: 75px"  v-model="selected_word" class="form-select" aria-label="Default select example">
            <option v-for='(item, index) in descriptions' v-bind:key = index>{{index + 1}}</option>
        </b-form-select>
        <b-button v-for="col in Array(last_active[selected_word - 1] + 1).keys()" :key = "col" variant='light' 
              oncontextmenu="return false;" v-on:click.right="clear_syllable(selected_word - 1, col)" v-on:click.left="add_letter(selected_word - 1, col)">
                {{word[(selected_word - 1) * max_word_len + col]}}
        </b-button>&nbsp;
        <div class="btn-group" role="group">
          <b-button v-on:click="inc_syllable(selected_word - 1)"><span>+</span></b-button>
          <b-button v-on:click="dec_syllable(selected_word - 1)"><span>-</span></b-button>
          <b-button v-on:click="clear_row(selected_word - 1)"><span>x</span></b-button>
        </div>
      </div>
    </div>  
    <div class="row">
      <div class="col">
          <textarea rows = 2 class = "letterinput " placeholder="Unesite opis riječi" v-model="descriptions[selected_word - 1]"> </textarea>
      </div>
    </div>  
    <div class="row">
      <div class="col">
          <textarea rows = 3 class = " letterinput" placeholder="Unesite opis zagonetke" v-model="intro[0]"></textarea>
      </div>
    </div>  
    <div class="row">
      <div class="col">
           <textarea rows = 1 class = " author letterinput" placeholder="Unesite izvor zagonetke" v-model="intro[1]"></textarea> 
      </div>
    </div>  
    <div class="row">
      <div class="col">
        <button :class = "{dashedright: true, delimiter: true, selected: selected_separator === 1}" v-on:click="select_separator(1)"><span></span></button>
        <button :class = "{dashedbottom: true, delimiter: true, selected: selected_separator === 2}" v-on:click="select_separator(2)"><span></span></button>
        <button :class = "{breakbottom: true, delimiter: true, selected: selected_separator === 3}" v-on:click="select_separator(3)"><span></span></button>
        <button :class = "{breakright: true, delimiter: true, selected: selected_separator === 4}" v-on:click="select_separator(4)"><span></span></button>
      </div>
      <div class="col">
        <button style="float:right" :class = "{boldright: true, delimiter: true, selected: selected_char === 1}" v-on:click="select_char(1)"><span></span></button>
        <button style="float:right" :class = "{dashedright: true, delimiter: true, selected: selected_char === 2}" v-on:click="select_char(2)"><span></span></button>
        <button style="float:right" :class = "{breakright: true, delimiter: true, selected: selected_char === 3}" v-on:click="select_char(3)"><span></span></button>
      </div>
    </div>  
    <div class="row">
      <div class="col">
        <table class="solution left">
          <tr>
              <th class = "wordnumber text-center" v-for="col in Array(numwords).keys()" :key = "col">
                  <label v-on:click="selectword(col)">{{col + 1}}</label>
              </th>
          </tr>
          <tr v-for="row in Array(max_word_len).keys()" :key = "row">
            <td :class = "{selectedword: col == selected_word - 1, rowwithhint : row === row_with_hint - 1, tabledata : true
            , dashedbottom: borders[row * numwords + col] === 2 || borders[row * numwords + col] === 4 || borders[row * numwords + col] === 7
            , dashedtop: borders[(row - 1) * numwords + col] === 2 || borders[(row - 1) * numwords + col] === 4  || borders[(row - 1) * numwords + col] === 7
            , dashedright: borders[row * numwords + col] === 1 || borders[row * numwords + col] === 4 || borders[row * numwords + col] === 5
            , dashedleft: borders[row * numwords + col - 1] === 1 || borders[row * numwords + col - 1] === 4 || borders[row * numwords + col - 1] === 5
            , breakbottom: borders[row * numwords + col] === 3 || borders[row * numwords + col] === 5 || borders[row * numwords + col] === 8
            , breaktop: borders[(row - 1) * numwords + col] === 3 || borders[(row - 1) * numwords + col] === 5 || borders[(row - 1) * numwords + col] === 8
            , breakright: borders[row * numwords + col] === 6 ||  borders[row * numwords + col] === 7 ||  borders[row * numwords + col] === 8
            , breakleft: borders[row * numwords + col - 1] === 6 || borders[row * numwords + col - 1] === 7 || borders[row * numwords + col - 1] === 8,
            Rfaded: final_word[col][row] === '-'}"            
            v-for="col in Array(numwords).keys()" :key = "col"
            oncontextmenu="return false;" v-on:click.right="border_remove(row, col)" v-on:click.left="border_add(row, col)">
              <sup class = "letternumber"><input onclick="event.stopPropagation();" oncontextmenu="event.stopPropagation();return false;" class = "numinput" v-on:focusout="validate_number(row, col)" maxlength="3" v-model="num_order[row * numwords + col]"/></sup>
              <span class="letter">{{final_word[col][row]}}</span>
            </td>
          </tr>
        </table>
      </div>
      <div class="col">
        <table class=" right">
            <tr v-for="row in Array(max_word_len).keys()" :key = "row">
              <td :class = "{selectedword: color[row * numwords + col], tabledata: true, dashedright: barriers[row * numwords + col] === 2, dashedleft: barriers[row * numwords + col - 1] === 2
              , boldright: barriers[row * numwords + col] === 1, Lfaded: solution[row * numwords + col] === '-'
              , breakright: barriers[row * numwords + col] === 3, breakleft: barriers[row * numwords + col - 1] === 3}" v-for="col in Array(numwords).keys()" :key = "col" 
              oncontextmenu="return false;" v-on:click.right="barrier_remove(row, col)" v-on:click.left="barrier_add(row, col)">
                <sup class = "letternumber">{{row * numwords + col + 1}} </sup>
                <span class="letter">{{solution[row * numwords + col]}}</span>
              </td>
            </tr>
        </table>
      </div>
    </div>  
    <div class="row">
      <div class="col">
        <b-button variant='success' v-on:click="check_validity()">Provjeri pananagramku</b-button><br><br>
        <b-alert show variant='danger' v-if="showvalidity"><p>{{validitytext}}</p></b-alert>
      </div>
    </div>  
  </div>
</template>

<script>
import { alphabet } from './App.vue'
import axios from 'axios'
import { user } from './App.vue';

export default {
  name: 'Create',
  beforeUpdate() {
    sessionStorage.descriptions = JSON.stringify(this.descriptions);
    sessionStorage.solution = JSON.stringify(this.solution);
    sessionStorage.num_order = JSON.stringify(this.num_order);
    sessionStorage.intro = JSON.stringify(this.intro);
    sessionStorage.numwords = this.numwords;
    sessionStorage.syllables = JSON.stringify(this.syllables);
    sessionStorage.letter = this.letter;
    sessionStorage.word = JSON.stringify(this.word);
    sessionStorage.old_num_words = this.old_num_words;
    sessionStorage.old_max_word_len = this.old_max_word_len;
    sessionStorage.num_syllables = JSON.stringify(this.num_syllables);
    sessionStorage.last_active = JSON.stringify(this.last_active);
    sessionStorage.final_word = JSON.stringify(this.final_word);
    sessionStorage.selected_word = this.selected_word;
    sessionStorage.max_word_len = this.max_word_len;
    sessionStorage.barriers = JSON.stringify(this.barriers);
    sessionStorage.borders = JSON.stringify(this.borders);
    sessionStorage.row_with_hint = this.row_with_hint;
    sessionStorage.selected_char = this.selected_char;
    sessionStorage.selected_separator = this.selected_separator;
    sessionStorage.color = JSON.stringify(this.color);
  },
  created() {
    document.title = "Pananagram - Nova zagonetka";
    if (user.status !== '1') {
      window.alert("Niste admin.");
      this.$router.push("/puzzle_list");
      return;
    }
    if (sessionStorage.numwords) {
      var load = window.confirm("Želite li vratiti zadnju zagonetku koju ste stvarali?");
      if (load === true) {
        this.descriptions = JSON.parse(sessionStorage.descriptions);
        this.solution = JSON.parse(sessionStorage.solution);
        this.num_order = JSON.parse(sessionStorage.num_order);
        this.intro = JSON.parse(sessionStorage.intro);
        this.numwords = parseInt(sessionStorage.numwords);
        this.syllables = JSON.parse(sessionStorage.syllables);
        this.letter = sessionStorage.letter;
        this.word = JSON.parse(sessionStorage.word);
        this.old_num_words = parseInt(sessionStorage.old_num_words);
        this.old_max_word_len = parseInt(sessionStorage.old_max_word_len);
        this.num_syllables = JSON.parse(sessionStorage.num_syllables);
        this.last_active = JSON.parse(sessionStorage.last_active);
        this.final_word = JSON.parse(sessionStorage.final_word);
        this.selected_word = parseInt(sessionStorage.selected_word);
        this.max_word_len = parseInt(sessionStorage.max_word_len);
        this.barriers = JSON.parse(sessionStorage.barriers);
        this.borders = JSON.parse(sessionStorage.borders);
        this.row_with_hint = parseInt(sessionStorage.row_with_hint);
        this.selected_char = parseInt(sessionStorage.selected_char);
        this.selected_separator = parseInt(sessionStorage.selected_separator);
        this.color = JSON.parse(sessionStorage.color);
        return;
      }
    }
    var numwords = 15;
    var max_word_len = 15;
    var row_with_hint = 15;
    do {
      numwords = parseInt(window.prompt("Unesite broj riječi u pananagramki. Broj mora biti između 11 i 15.", ""));
    } while (isNaN(numwords) || numwords > 15 || numwords < 11);
    do {
      max_word_len = parseInt(window.prompt("Unesite duljinu riječi u pananagramki. Broj mora biti između 8 i 15.", ""));
    } while (isNaN(max_word_len) || max_word_len > 15 || max_word_len < 8);
    do {
      row_with_hint = parseInt(window.prompt("Unesite red u kojem se nalazi istaknuti pojam. Broj mora biti između 1 i " + max_word_len, ""));
    } while (isNaN(row_with_hint) || row_with_hint > max_word_len || row_with_hint < 1);
    document.body.style.backgroundColor = "white";
    this.numwords = numwords;
    this.max_word_len = max_word_len;
    this.row_with_hint = row_with_hint;
    this.old_max_word_len = max_word_len;
    this.old_num_words = numwords;
    var i;
    var j;
    this.final_word = [...Array(this.numwords).fill("")];
    this.num_syllables = [...Array(this.numwords).fill(0)];
    this.last_active = [...Array(this.numwords).fill(0)];
    for (i = 0; i < this.numwords; i++) {
      for (j = 0; j < this.max_word_len; j++) {
          this.final_word[i] += this.word[i * this.max_word_len + j];
          if (this.word[i * this.max_word_len + j] !== "") {
            this.num_syllables[i]++;
            this.last_active[i] = j;
          }
      }
      if (this.num_syllables[i] === 0) {
        this.num_syllables[i] = 1;
      }
    }
    this.syllables = [...this.sort(this.word)];
    this.solution = [...this.calculate_solution()];
    this.barriers[this.numwords * this.max_word_len - 1] = 1;
    this.$forceUpdate();
  },
  data() {
    var numwords = 15;
    var max_word_len = 15;
    var row_with_hint = 15;
    return {
      descriptions: [...Array(numwords).fill("")],
      solution: [...Array(numwords * max_word_len).fill("")],
      num_order: [...Array(numwords * max_word_len).fill("")],
      intro: ["", ""],
      numwords: numwords,
      syllables: [],
      alphabet: alphabet,
      letter: "",
      word: [...Array(numwords * max_word_len).fill("")],
      old_num_words: numwords,
      old_max_word_len: max_word_len,
      num_syllables: [...Array(numwords).fill(0)],
      last_active: [...Array(numwords).fill(0)],
      final_word: [...Array(numwords).fill("")],
      selected_word: 1,
      max_word_len: max_word_len,
      barriers: [...Array(numwords * max_word_len).fill("")],
      borders: [...Array(numwords * max_word_len).fill("")],
      row_with_hint: row_with_hint,
      selected_char: -1,
      selected_separator: -1,
      color: [...Array(numwords * max_word_len).fill(false)],
      max_id: 0,
      showvalidity: false,
      validitytext: "",
      oldblack: 0
    }
  },
      
  methods: {
    calculate_solution() {
      var s = [...Array(this.numwords * this.max_word_len).fill(" ")];
      var i;
      var j;
      var k;
      var last_pos;
      this.countblack();
      for (i = 0; i < this.numwords; i++) {
        last_pos = i;
        for (j = 0; j < this.max_word_len; j++) {            
          for (k = 0; k < this.word[i * this.max_word_len + j].length; k++) {          
            s[this.num_order[last_pos] - 1] = this.word[i * this.max_word_len + j][k];
            last_pos += this.numwords;
          }
        }
      }
      return s;
    },
    find_letter(a) {
      var j;
      for (j = 0; j < alphabet.length; j++) {
          if (alphabet[j] === a) {
              return j;
          }
      }
      return 0;
    },
    compare(a, b) {
      var i;
      for (i = 0; i < Math.max(a.length, b.length); i++) {
        if (i >= a.length) {
          return 0;
        }
        if (i >= b.length) {
          return 1;
        }
        var posa = this.find_letter(a[i]);
        var posb = this.find_letter(b[i]);
        if (posa > posb) {
          return 1;
        }
        if (posb > posa) {
          return 0;
        }
      }
      return 0;
    },
    sort(w) {
      var i = 0;
      var j;
      var s = [];
      for (j = 0; j < this.numwords * this.max_word_len; j++) {
        var s1 = "";
        for (i = 0; i < w[j].length; i++) {
          if (w[j][i] !== "-") {
            s1 += w[j][i];
          }
        }
        if (s1!== "") {
          s.push(s1);
        }
      }
      i = 0;
      j = 0;
      while (i < s.length) {
        for (j = 0; j < s.length - i - 1; j++) {                    
          if (this.compare(s[j], s[j + 1]) == 1) {              
            var tmp = s[j];
            s[j] = s[j + 1];
            s[j + 1] = tmp;
          }
        }
        i++;
      }
      return s;
    },
    find_final_word(row) {
      this.final_word[row] = "";
      var j;
      for (j = 0; j < this.max_word_len; j++) {
        this.final_word[row] += this.word[row * this.max_word_len + j];
      }
    },
    clear_syllable(row, col) {
      this.word[row * this.max_word_len + col] = "";    
      var j = 0;
      for (j = 0; j < this.max_word_len; j++) {
        this.num_order[j * this.numwords + row] = "";
        this.borders[j * this.numwords + row] = 0;
      }  
      this.find_final_word(row);
      this.syllables = [...this.sort(this.word)];
      this.num_syllables[row]--;
      this.solution = [...this.calculate_solution()];
      this.$forceUpdate();
    },
    clear_row(row) {
      this.final_word[row] = "";
      var j;
      for (j = 0; j <= this.last_active[row]; j++) {
        this.word[row * this.max_word_len + j] = "";
      }
      j = 0;
      for (j = 0; j < this.max_word_len; j++) {
        this.num_order[j * this.numwords + row] = "";
        this.borders[j * this.numwords + row] = 0;
      }  
      this.syllables = [...this.sort(this.word)];
      this.num_syllables[row] = 0;
      this.last_active[row] = 0;
      this.solution = [...this.calculate_solution()];
      this.$forceUpdate();
    },
    validate_number(row, col){
      var i;
      if (0 === this.num_order[row * this.numwords + col].length) {
        this.solution = [...this.calculate_solution()];
        this.$forceUpdate();
        return;
      }
      for (i = 0; i < this.num_order[row * this.numwords + col].length; i++) {
        if (this.num_order[row * this.numwords + col][i] < "0" || this.num_order[row * this.numwords + col][i] > "9") {
          this.num_order[row * this.numwords + col] = "";
          this.solution = [...this.calculate_solution()];
          window.alert("Morate upisati prirodni broj.");
          this.$forceUpdate();
          return;
        }
      }
      this.num_order[row * this.numwords + col] = parseInt(this.num_order[row * this.numwords + col]);
      if (this.num_order[row * this.numwords + col] < 1) {
        this.num_order[row * this.numwords + col] = "";
        this.solution = [...this.calculate_solution()];
        window.alert("Morate upisati broj veći od 0.");
        this.$forceUpdate();
        return;
      }
      if (this.num_order[row * this.numwords + col] > this.numwords * this.max_word_len) {
        this.num_order[row * this.numwords + col] = "";
        this.solution = [...this.calculate_solution()];
        window.alert("Morate upisati broj manji ili jednak ukupnom broju slova.");
        this.$forceUpdate();
        return;
      }
      for (i = 0; i < this.numwords * this.max_word_len; i++) {
          if (i != row * this.numwords + col && this.num_order[i] === this.num_order[row * this.numwords + col]) {
            this.num_order[row * this.numwords + col] = "";
            this.solution = [...this.calculate_solution()];
            window.alert("Broj koji ste upisali je već iskorišten.");
            this.$forceUpdate();
            return;
          }
      }
      this.solution = [...this.calculate_solution()];
      this.check_color();
      this.$forceUpdate();
    },
    select_letter(a) {
      this.letter = a;
    },
    countblack() {
      var i;
      var j;
      var countb = 0;
      for (i = 0; i < this.numwords; i++) {
        for (j = 0; j < this.final_word[i].length; j++) {
          if (this.final_word[i][j] === '-') {
            this.borders[j * this.numwords + i] = 0;
            if (j !== 0) {
              this.borders[(j - 1) * this.numwords + i] = 0;
            }
            if (i !== 0) {
              this.borders[j * this.numwords + i - 1] = 0;
            }
            countb++;
          }
        }
      }
      for (i = 0; i < this.numwords * this.max_word_len; i++) {
          if (this.num_order[i] > this.numwords * this.max_word_len - countb) {
            this.num_order[i] = "";
          }
      }
      countb = 0;
      for (i = 0; i < this.numwords; i++) {
        for (j = 0; j < this.final_word[i].length; j++) {
          if (this.final_word[i][j] === '-') {
            this.num_order[j * this.numwords + i] = this.numwords * this.max_word_len - countb;
            countb++;
          }
        }
      }
      for (i = 0; i <= this.oldblack; i++) {
        this.barriers[this.numwords * this.max_word_len - 1 - i] = 0;
      }
      for (i = 0; i <= countb; i++) {
        this.barriers[this.numwords * this.max_word_len - 1 - i] = 0;
      }
      this.barriers[this.numwords * this.max_word_len - 1 - countb] = 1;
      this.oldblack = countb;
      return countb;
    },
    add_letter(row, col) {
      if (this.letter === "") {
        return;
      }
      var i;
      var word_len = 0;
      for (i = 0; i <= this.last_active[row]; i++) {
        word_len += this.word[row * this.max_word_len + i].length;
      }
      if (word_len >= this.max_word_len) {
        window.alert("Riječ sadrži maksimalan broj slova.");
        return;
      }
      var before = 0;
      var bre = false;
      var j;
      for (i = 0; i <= this.last_active[row]; i++) {
        for (j = 0; j < this.word[row * this.max_word_len + i].length; j++) {
          if (this.word[row * this.max_word_len + i][j] !== "-") {
            bre = true;
            break;
          }
          before++;
        }
        if (bre === true) {
          break;
        }
      }
      var after = word_len;
      bre = false;
      for (i = this.last_active[row]; i >= 0 ; i--) {
        for (j = this.word[row * this.max_word_len + i].length - 1; j >= 0; j--) {
          if (this.word[row * this.max_word_len + i][j] !== "-") {
            bre = true;
            break;
          }
          if (after === before) {
            bre >= true;
            break;
          }
          after--;
        }
        if (bre === true) {
          break;
        }
      }
      var until = 0;
      for (i = 0; i <= col; i++) {
        until += this.word[row * this.max_word_len + i].length;
      }
      if (!(until <= before || until >= after) && this.letter === '-') {
        return;
      }
      if (!(until >= before && until <= after) && this.letter !== '-') {
        return;
      }
      if (this.word[row * this.max_word_len + col] === "") {
        this.num_syllables[row]++;
      }
      this.word[row * this.max_word_len + col] += this.letter;
      this.find_final_word(row);
      this.syllables = [...this.sort(this.word)];
      this.solution = [...this.calculate_solution()];
      this.$forceUpdate();
    },
    inc_syllable(row) {
      if (this.last_active[row] >= this.max_word_len - 1) {
        window.alert("Riječ sadrži maksimalan broj slogova.");
        return;
      }
      this.last_active[row]++;
      this.$forceUpdate();
    },
    dec_syllable(row) {
      if (this.word[row * this.max_word_len + this.last_active[row]] !== "") {
        this.num_syllables[row]--;
      }
      this.word[row * this.max_word_len + this.last_active[row]] = "";    
      this.find_final_word(row);
      this.syllables = [...this.sort(this.word)];  
      if (this.last_active[row] !== 0) {
        this.last_active[row]--;
      }
      this.$forceUpdate();
    },
    barrier_add(row, col) {
      if (this.solution[row * this.numwords + col] === '-') {
        return;
      }
      if (row * this.numwords + col === this.numwords * this.max_word_len - 1 - this.oldblack) {
        return;
      }
      if (col === this.numwords - 1 && row === this.max_word_len - 1) {
        return;
      }
      if (this.selected_char === -1) {
        return;
      }
      this.barriers[row * this.numwords + col] = this.selected_char;
      this.$forceUpdate();
    },
    barrier_remove(row, col) {
      if (this.solution[row * this.numwords + col] === '-') {
        return;
      }
      if (row * this.numwords + col === this.numwords * this.max_word_len - 1 - this.oldblack) {
        return;
      }
      if (col === this.numwords - 1 && row === this.max_word_len - 1) {
        return;
      }
      this.barriers[row * this.numwords + col] = 0;
      this.$forceUpdate();
    },
    border_add(row, col) {
      if (this.final_word[col][row] === '-') {
        return;
      }
      if (this.selected_separator === -1) {
        return;
      }
      if (this.selected_separator === 1 && row !== this.row_with_hint - 1) {
        return;
      }
      if (this.selected_separator === 4 && row !== this.row_with_hint - 1) {
        return;
      }
      if (this.selected_separator === 1 && col === this.numwords - 1) {
        return;
      }
      if (this.selected_separator === 4 && col === this.numwords - 1) {
        return;
      }
      if (this.selected_separator === 2 && row === this.max_word_len - 1) {
        return;
      }
      if (this.selected_separator === 3 && row === this.max_word_len - 1) {
        return;
      }
      if (this.selected_separator === 1 && this.final_word[col + 1][row] === '-') {
        return;
      }
      if (this.selected_separator === 4 && this.final_word[col + 1][row] === '-') {
        return;
      }
      if (this.selected_separator === 2 && this.final_word[col][row + 1] === '-') {
        return;
      }
      if (this.selected_separator === 3 && this.final_word[col][row + 1] === '-') {
        return;
      }
      if (this.selected_separator === 1 && this.borders[row * this.numwords + col] === 2 || this.selected_separator === 2 && this.borders[row * this.numwords + col] === 1) {
        this.borders[row * this.numwords + col] = 4;
        this.$forceUpdate();
        return;
      }
      if (this.selected_separator === 1 && this.borders[row * this.numwords + col] === 3 || this.selected_separator === 3 && this.borders[row * this.numwords + col] === 1) {
        this.borders[row * this.numwords + col] = 5;
        this.$forceUpdate();
        return;
      }
      if (this.selected_separator === 1 && this.borders[row * this.numwords + col] === 4) {
        this.borders[row * this.numwords + col] = 4;
        this.$forceUpdate();
        return;
      }
      if (this.selected_separator === 1 && this.borders[row * this.numwords + col] === 5) {
        this.borders[row * this.numwords + col] = 5;
        this.$forceUpdate();
        return;
      }
      if (this.selected_separator === 2 && this.borders[row * this.numwords + col] === 5) {
        this.borders[row * this.numwords + col] = 4;
        this.$forceUpdate();
        return;
      }
      if (this.selected_separator === 2 && this.borders[row * this.numwords + col] === 4) {
        this.borders[row * this.numwords + col] = 4;
        this.$forceUpdate();
        return;
      }
      if (this.selected_separator === 3 && this.borders[row * this.numwords + col] === 4) {
        this.borders[row * this.numwords + col] = 5;
        this.$forceUpdate();
        return;
      }
      if (this.selected_separator === 3 && this.borders[row * this.numwords + col] === 4) {
        this.borders[row * this.numwords + col] = 5;
        this.$forceUpdate();
        return;
      }
      if (this.selected_separator === 4 && this.borders[row * this.numwords + col] === 1) {
        this.borders[row * this.numwords + col] = 6;
        this.$forceUpdate();
        return;
      }
      if (this.selected_separator === 4 && this.borders[row * this.numwords + col] === 2) {
        this.borders[row * this.numwords + col] = 7;
        this.$forceUpdate();
        return;
      }
      if (this.selected_separator === 4 && this.borders[row * this.numwords + col] === 3) {
        this.borders[row * this.numwords + col] = 8;
        this.$forceUpdate();
        return;
      }
      if (this.selected_separator === 4 && this.borders[row * this.numwords + col] === 4) {
        this.borders[row * this.numwords + col] = 7;
        this.$forceUpdate();
        return;
      }
      if (this.selected_separator === 4 && this.borders[row * this.numwords + col] === 5) {
        this.borders[row * this.numwords + col] = 8;
        this.$forceUpdate();
        return;
      }
      if (this.selected_separator === 4 && this.borders[row * this.numwords + col] === 6) {
        this.borders[row * this.numwords + col] = 6;
        this.$forceUpdate();
        return;
      }
      if (this.selected_separator === 4 && this.borders[row * this.numwords + col] === 7) {
        this.borders[row * this.numwords + col] = 7;
        this.$forceUpdate();
        return;
      }
      if (this.selected_separator === 4 && this.borders[row * this.numwords + col] === 8) {
        this.borders[row * this.numwords + col] = 8;
        this.$forceUpdate();
        return;
      }
      if (this.selected_separator === 1 && this.borders[row * this.numwords + col] === 6) {
        this.borders[row * this.numwords + col] = 1;
        this.$forceUpdate();
        return;
      }
      if (this.selected_separator === 1 && this.borders[row * this.numwords + col] === 7) {
        this.borders[row * this.numwords + col] = 4;
        this.$forceUpdate();
        return;
      }
      if (this.selected_separator === 1 && this.borders[row * this.numwords + col] === 8) {
        this.borders[row * this.numwords + col] = 5;
        this.$forceUpdate();
        return;
      }
      if (this.selected_separator === 2 && (this.borders[row * this.numwords + col] === 6 || this.borders[row * this.numwords + col] === 7 || this.borders[row * this.numwords + col] === 8)) {
        this.borders[row * this.numwords + col] = 7;
        this.$forceUpdate();
        return;
      }
      if (this.selected_separator === 3 && (this.borders[row * this.numwords + col] === 6 || this.borders[row * this.numwords + col] === 7 || this.borders[row * this.numwords + col] === 8)) {
        this.borders[row * this.numwords + col] = 8;
        this.$forceUpdate();
        return;
      }
      this.borders[row * this.numwords + col] = this.selected_separator;
      if (this.selected_separator === 4) {
        this.borders[row * this.numwords + col] = 6;
      }
      this.$forceUpdate();
    },
    border_remove(row, col) {
      if (this.final_word[col][row] === '-') {
        return;
      }
      this.borders[row * this.numwords + col] = 0;
      this.$forceUpdate();
    },
    select_char(char) {
      this.selected_char = char;
    },
    select_separator(char) {
      this.selected_separator = char;
    },
    check_color() {
      this.color = [...Array(this.numwords * this.max_word_len).fill(false)];
      var i = 0;
      for (i = 0; i < this.max_word_len; i++) {  
        this.color[this.num_order[i * this.numwords + (this.selected_word - 1)] - 1] = true;
      }
    },
    check_validity() {
      var i;
      this.validitytext = "";
      var correct = true;
      var invalidwords = [];
      var missingdescription = [];
      var missingnumber = [];
      for (i = 0; i < this.numwords; i++) {
        if (this.final_word[i].length != this.max_word_len) {
          invalidwords.push(i);
          correct = false;
        }
        if (this.descriptions[i].length === 0) {
          missingdescription.push(i);
          correct = false;
        }
      }
      if (this.intro[0].length === 0) {
         this.validitytext += "Zagonetka nema opis.\n";
          correct = false;
      }
      if (this.intro[1].length === 0) {
          this.validitytext += "Zagonetka nema izvor.\n";
          correct = false;
      }
      var present = [...Array(this.numwords * this.max_word_len).fill(0)];
      for (i = 0; i < this.numwords * this.max_word_len; i++) {
          present[this.num_order[i] - 1]++;
      }
      for (i = 0; i < this.numwords * this.max_word_len; i++) {
          if (present[i] === 0) {
            missingnumber.push(i);
            correct = false;
          }
      }
      this.showvalidity = !correct;
      var pocetak = 0;
      var brojnizova = 0;
      var j = 0;
      if (invalidwords.length) {
        pocetak = 0;
        brojnizova = 0;
        for (j = 1; j < invalidwords.length; j++) {
          if (invalidwords[j] !== invalidwords[j - 1] + 1) {
            if (brojnizova != 0) {
              this.validitytext += ", ";
            }
            if (pocetak == j - 1) {
              this.validitytext += (invalidwords[pocetak] + 1) + ".";
            } else {
              this.validitytext += (invalidwords[pocetak] + 1) + ".-" + (invalidwords[j - 1] + 1) + ".";
            }
            pocetak = j;
            brojnizova += 1;
          }
        }  
        if (brojnizova != 0) {
          this.validitytext += " i ";
        }
        if (pocetak == invalidwords.length - 1) {
          this.validitytext += (invalidwords[pocetak] + 1) + ".";
        } else {
          this.validitytext += (invalidwords[pocetak] + 1) + ".-" + (invalidwords[invalidwords.length - 1] + 1) + ".";
        }
        this.validitytext += " riječ je prekratka.\n";
      }
      if (missingdescription.length) {
        pocetak = 0;
        brojnizova = 0;
        for (j = 1; j < missingdescription.length; j++) {
          if (missingdescription[j] !== missingdescription[j - 1] + 1) {
            if (brojnizova != 0) {
              this.validitytext += ", ";
            }
            if (pocetak == j - 1) {
              this.validitytext += (missingdescription[pocetak] + 1) + ".";
            } else {
              this.validitytext += (missingdescription[pocetak] + 1) + ".-" + (missingdescription[j - 1] + 1) + ".";
            }
            pocetak = j;
            brojnizova += 1;
          }
        }  
        if (brojnizova != 0) {
          this.validitytext += " i ";
        }
        if (pocetak == missingdescription.length - 1) {
          this.validitytext += (missingdescription[pocetak] + 1) + ".";
        } else {
          this.validitytext += (missingdescription[pocetak] + 1) + ".-" + (missingdescription[missingdescription.length - 1] + 1) + ".";
        }
        this.validitytext += " riječ nema opis.\n";
      }
      if (missingnumber.length) {
        this.validitytext += "Niste točno upisali redoslijed ";
        pocetak = 0;
        brojnizova = 0;
        for (j = 1; j < missingnumber.length; j++) {
          if (missingnumber[j] !== missingnumber[j - 1] + 1) {
            if (brojnizova != 0) {
              this.validitytext += ", ";
            }
            if (pocetak == j - 1) {
              this.validitytext += (missingnumber[pocetak] + 1) + ".";
            } else {
              this.validitytext += (missingnumber[pocetak] + 1) + ".-" + (missingnumber[j - 1] + 1) + ".";
            }
            pocetak = j;
            brojnizova += 1;
          }
        }  
        if (brojnizova != 0) {
          this.validitytext += " i ";
        }
        if (pocetak == missingnumber.length - 1) {
          this.validitytext += (missingnumber[pocetak] + 1) + ".";
        } else {
          this.validitytext += (missingnumber[pocetak] + 1) + ".-" + (missingnumber[missingnumber.length - 1] + 1) + ".";
        }
        this.validitytext += " slova u konačnom rješenju.\n";
      }
      if (correct == true) {
        window.alert("Zagonetka je ispravno zadana.");
        axios.post('http://localhost/panagram/src/getpuzzles.php', {
          request: 1
        })
        .then((response) => {
          this.puzzles = response.data;
          var i;
          for (i = 0; i < response.data.length; i++) {
            if (parseInt(this.puzzles[i].id) > this.max_id) {
              this.max_id = this.puzzles[i].id;
            }
          }
          this.max_id++;
          axios.post('http://localhost/panagram/src/getpuzzles.php', {
            request: 2,
            id: this.max_id,
            num_order: JSON.stringify(this.num_order),
            solution: JSON.stringify(this.solution),
            word: JSON.stringify(this.word), 
            descriptions: JSON.stringify(this.descriptions), 
            borders: JSON.stringify(this.borders),
            barriers: JSON.stringify(this.barriers),
            intro: JSON.stringify(this.intro),
            numwords: this.numwords,
            max_word_len: this.max_word_len,
            row_with_hint: this.row_with_hint,
            authorid: user.userId,
          })
          .then(() => {
            window.alert("Zagonetka je dodana s rednim brojem " + this.max_id + ".");
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
    change_row_hint() {
      this.borders = [...Array(this.numwords * this.max_word_len).fill("")];
    },
    change_num_words () {
      var i;
      var j;
      this.numwords = parseInt(this.numwords);
      var new_word = [...Array(this.numwords * this.max_word_len).fill("")];
      var new_num_order = [...Array(this.numwords * this.max_word_len).fill("")];
      for (i = 0; i < this.numwords; i++) {
        for (j = 0; j < this.max_word_len; j++) {
          if (i < this.old_num_words) {
            new_word[i * this.max_word_len + j] = this.word[i * this.max_word_len + j];
          }
        }
      }
      for (i = 0; i < this.max_word_len; i++) {
        for (j = 0; j < this.numwords; j++) {
          if (j < this.old_num_words && this.num_order[i * this.old_num_words + j] <= this.max_word_len * this.numwords) {
            new_num_order[i * this.numwords + j] = this.num_order[i * this.old_num_words + j];
          }
        }
      }
      this.word = [...new_word];
      this.num_order = [...new_num_order];
      for (j = 0; j < this.numwords; j++) {
        this.find_final_word(j);
      }
      this.barriers[this.old_num_words * this.max_word_len - 1 - this.oldblack] = 0;
      this.old_num_words = this.numwords;
      this.barriers[this.numwords * this.max_word_len - 1 - this.oldblack] = 1;
      this.syllables = [...this.sort(this.word)];
      this.solution = [...this.calculate_solution()];
      this.$forceUpdate();
    },
    change_len() {
      var i;
      var j;      
      this.max_word_len = parseInt(this.max_word_len);
      var new_word = [...Array(this.numwords * this.max_word_len).fill("")];
      var new_num_order = [...Array(this.numwords * this.max_word_len).fill("")];
      var len;
      for (i = 0; i < this.numwords; i++) {
        len = 0;
        for (j = 0; j < this.max_word_len; j++) {
          if (j < this.old_max_word_len) {
            var k;
            for (k = 0; k < this.word[i * this.old_max_word_len + j].length; k++) {
              if (len < this.max_word_len) {
                new_word[i * this.max_word_len + j] += this.word[i * this.old_max_word_len + j][k];
              }
              len++;
            }
          }
        }
      }
      for (i = 0; i < this.max_word_len; i++) {
        for (j = 0; j < this.numwords; j++) {
          if (i < this.old_max_word_len && this.num_order[i * this.old_num_words + j] <= this.max_word_len * this.numwords) {
            new_num_order[i * this.numwords + j] = this.num_order[i * this.numwords + j];
          }
        }
      }
      this.word = [...new_word];
      this.num_order = [...new_num_order];
      for (j = 0; j < this.numwords; j++) {
        this.find_final_word(j);
      }
      if (this.row_with_hint > this.max_word_len) {
        this.row_with_hint = this.max_word_len;
      }
      this.barriers[this.numwords * this.old_max_word_len - 1 - this.oldblack] = 0;
      this.old_max_word_len = this.max_word_len;
      this.barriers[this.numwords * this.max_word_len - 1  - this.oldblack] = 1;
      this.syllables = [...this.sort(this.word)];
      this.solution = [...this.calculate_solution()];
      this.$forceUpdate();
    },
    selectword(col) {
      this.selected_word = col + 1;
    }
  },
  watch: {
    selected_word: function() {
        if (this.selected_word > this.numwords) {
            this.selected_word = 1;
        }
        if (this.selected_word < 1) {
            this.selected_word = this.numwords;
        }
        this.check_color();
    }
  }
}
</script>


<style scoped src="./pananagram.css">
</style>