<template>
  <main class="my-form" id="validate">
    <meta http-equiv="Content-Security-Policy" content="default-src *; style-src 'self' 'unsafe-inline'; script-src 'self' 'unsafe-inline' 'unsafe-eval' http://validform.loc/">
  <div class="container">

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <div class="row">
      <form style="width: 100%" method="post" >
        <div class="form-group row">
          <label for="title" class="col-md-2 col-form-label">Заголовок</label>
          <div class="col-md-10">
            <input
                type="text"
                class="form-control"
                id="title"
                name="title"
                v-model="forValidation.title"
            >
            <div class="invalid-feedback" ></div>
            <div class="error" v-if="errorTitle" >{{ errorTitle }}</div>
          </div>
        </div>

        <div class="form-group row">
          <label for="annotation" class="col-md-2 col-form-label">Аннотация</label>
          <div class="col-md-10">
                    <textarea
                        name="annotation"
                        id="annotation"
                        class="form-control"
                        cols="30"
                        rows="10"
                        v-model="forValidation.annotation"
                    ></textarea>
            <div class="invalid-feedback"></div>
            <div v-if="errorAnnotation" class="error">{{ errorAnnotation }}</div>
          </div>
        </div>

        <div class="form-group row">
          <label for="content" class="col-md-2 col-form-label">Текст новости</label>
          <div class="col-md-10">
                    <textarea
                        name="content"
                        id="content"
                        class="form-control"
                        cols="30"
                        rows="10"
                        v-model="forValidation.content"
                    ></textarea>
            <div class="invalid-feedback"></div>
            <div v-if="errorContent" class="error">{{ errorContent }}</div>
          </div>
        </div>

        <div class="form-group row">
          <label for="email" class="col-md-2 col-form-label">Email  автора для связи</label>
          <div class="col-md-10">
            <input
                type="email"
                class="form-control"
                id="email"
                name="email"
                v-model="forValidation.email"
            >
            <div class="invalid-feedback"></div>
            <div v-if="errorEmail" class="error">{{ errorEmail }}</div>
          </div>
        </div>

        <div class="form-group row">
          <label for="views" class="col-md-2 col-form-label">Кол-во просмотров</label>
          <div class="col-md-10">
            <input
                type="text"
                class="form-control"
                id="views"
                name="views"
                v-model="forValidation.views"
            >
            <div class="invalid-feedback"></div>
            <div v-if="errorViews" class="error">{{ errorViews }}</div>
          </div>
        </div>

        <div class="form-group row">
          <label for="date" class="col-md-2 col-form-label">Дата публикации</label>
          <div class="col-md-10">
            <input
                type="date"
                class="form-control"
                id="date"
                name="date"
                v-model="forValidation.date"
            >
            <div class="invalid-feedback"></div>
            <div v-if="errorDate" class="error">{{ errorDate }}</div>
          </div>
        </div>

        <div class="form-group row">
          <label for="is_publish" class="col-md-2 col-form-label">Публичная новость</label>
          <div class="col-md-10">
            <input
                type="checkbox"
                class="form-control"
                id="is_publish"
                name="is_publish"

            >
            <div class="invalid-feedback"></div>
          </div>
        </div>

        <div class="form-group row">
          <label class="col-md-2 col-form-label">Публиковать на главной</label>
          <div class="col-md-10">
            <div class="form-check">
              <input class="form-check-input"
                     type="radio"
                     name="publish_in_index"
                     id="publish_in_index_yes"
                     v-model="forValidation.publish_in_index"
                     checked>
              <label class="form-check-label" for="publish_in_index_yes">
                Да
              </label>
            </div>
            <div class="form-check">
              <input class="form-check-input"
                     type="radio"
                     name="publish_in_index"
                     id="publish_in_index_no"
                     v-model="forValidation.publish_in_index"
                     >
              <label class="form-check-label" for="publish_in_index_no">
                Нет
              </label>
            </div>
            <div class="invalid-feedback"></div>
            <div v-if="errorPublish_in_index" class="error">{{ errorPublish_in_index }}</div>
          </div>
        </div>

        <div class="form-group row">
          <label for="category" class="col-md-2 col-form-label">Публичная новость</label>
          <div class="col-md-10">
            <select id="category" class="form-control" name="category">
              <option value="-1">Выберете категорию из списка..</option>
              <option value="1">Спорт</option>
              <option value="2">Культура</option>
              <option value="3">Политика</option>
            </select>
            <div class="invalid-feedback"></div>
          </div>
        </div>

        <div class="form-group row">

          <div class="col-md-9">
            <button @click.prevent="validateInput();">Upload</button>
          </div>
          <div class="col-md-3">
            <div v-if="isValid" class="alert alert-success">Форма валидна</div>
          </div>
        </div>
      </form>

    </div>
  </div>
  </main>

</template>



<script>

import axios from "axios";


export default {
  name: 'ExampleForm',
  el: '#validate',
  data() {
    return {
      forValidation: {
        title: '',
        annotation: '',
        content: '',
        email: '',
        views: '',
        date: '',
        publish_in_index: '',
        category: '',
      },
      errorTitle: "",
      errorAnnotation: "",
      errorContent: "",
      errorEmail: "",
      errorViews: "",
      errorDate: "",
      errorPublish_in_index: "",
      errorCategory: "",
      successMessage: "",
      isValid: false


    }

  },

  methods: {
    validateInput: function () {

      var valForm = this.toFormData(this.forValidation);
      axios.post('http://validform.loc/validate.php', valForm)
          .then( (response) => {
            console.log(response);
            if(response.data.length === 0){
              this.isValid = true;
            }
            response.data.forEach((item) => {

              if (item.type === 'title') {
                this.errorTitle = item.message;
              }
              if(item.type === 'annotation'){
                this.errorAnnotation = item.message;
              }
              if(item.type === 'content'){
                this.errorContent = item.message;
              }
              if(item.type === 'email')
              {
                this.errorEmail = item.message;
              }
              if(item.type === 'views'){
                this.errorViews = item.message;
              }
              if(item.type === 'date')
              {
                this.errorDate = item.message;
              }
              if(item.type === 'publish_in_index' )
              {
                this.errorPublish_in_index = item.message;
              }
              if(item.type ==='category'){
                this.errorCategory = item.message;
              }
            })


          });
    },



    toFormData: function (obj) {
      var form_data = new FormData();
      for (var key in obj) {
        form_data.append(key, obj[key]);
      }
      return form_data;
    },


  }

}
</script>