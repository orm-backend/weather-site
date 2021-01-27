<template>
  <div class="card card-default">
    <div class="card-header">
      <span>Форма поиска</span>
    </div>
    <div class="card-body">
      <div class="alert alert-danger" v-if="message">
        <p class="mb-0">{{ message }}</p>
      </div>
      <form role="form" v-on:submit.prevent="search">
        <div class="form-group row">
          <label class="col-md-3 col-form-label">Дата</label>
          <div class="col-md-9">
            <input
              type="text"
              name="date"
              id="date-input"
              class="form-control"
              v-bind:class="{ 'is-invalid': errors && errors.date }"
              v-model="date"
            />
            <small
              class="form-text text-muted"
            >Поддерживается любой формат даты в английской локали (1/25/2021, 2021-01-25, 25.01.2021, 25 January 2021 и т.п.)</small>
            <div class="invalid-feedback" v-if="errors && errors.date">
              <span v-for="error in errors.date" :key="error">{{ error }}</span>
            </div>
          </div>
        </div>
        <div class="float-right">
          <button type="button" class="btn btn-primary" @click="search">Найти</button>
        </div>
      </form>
    </div>
    <div class="card-footer" v-if="temp !== null">
      <span>Температура {{ temp }} ℃</span>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      temp: null,
      date: "",
      message: "",
      errors: {}
    };
  },
  mounted() {
    $("#date-input").focus();
  },
  methods: {
    search() {
      this.temp = null;
      this.message = "";
      this.errors = {};

      axios
        .post("/api/weather", { date: this.date })
        .then(response => {
          if (response.data.temp) {
            this.temp = response.data.temp;
          } else {
            this.message = "Нет результатов за указанную дату.";
          }
        })
        .catch(error => {
          if (typeof error.response.data === "object") {
            this.message = error.response.data.message;
            this.errors = error.response.data.errors;
          } else {
            this.message =
              "Что-то пошло не так. Пожалуйста, попробуйте еще раз.";
          }
        });
    }
  }
};
</script>
