<template id="">
  <div>
    <header>
      <Nav></Nav>
    </header>
    <section class="app_description">
      <div class="container">
        <div class="row">
          <div class="six columns forums-column">
            <template v-if="type !== 'comments'">
              <h5><i class="material-icons">sms</i> Topics</h5>
              <p>The Most Recent Topics For You To Talk About...</p>
              <hr>
            </template>
            <template v-if="type == 'comments'">
              <!-- Add The Post Meta Here!! -->
              <div class="card">
                <div class="card-body">
                <h5 class="card-title"><b>{{ forum.title }}</b></h5>
                <h6 class="card-subtitle mb-2 text-muted"><b>Author: {{ forum.user.name }}, Created {{ forum.timestamp }}</b></h6>
                <hr>
                <p class="card-text" v-html="forum.content"></p>
                <div class="card-footer">
                  <ul>
                    <li class="activity forums" @click.prevent="like(forum.id)"><a href="#" class="card-link"><i class="material-icons">favorite</i> {{ forum.likes }}</a></li>
                    <li class="activity forums" @click.prevent="editForum(forum)"><a href="#" class="card-link"><i class="material-icons">edit</i> Edit</a></li>
                  </ul>
                </div>
              </div>
              </div>
              <h3><i class="material-icons medium">sms</i> Comments {{ forum.comments }}</h3>
              <hr>
              <!-- Add The Comments Here... -->
              <div v-if="comments.length > 0">
                <div class="card" v-for="postComment in comments" :key="postComment.id">
                  <div class="card-body">
                    <p><b>{{ postComment.user.name }} Created {{ postComment.humanTime }}</b></p>
                    <p v-html="postComment.comment"></p>
                  </div>
                </div>
              </div>
            </template>
            <template v-if="forums.length > 0 && type !== 'comments'">
              <div class="forums" v-for="singleForum in forums" :key="singleForum.id">
                <div class="card">
                  <div class="card-body">
                    <h5 class="card-title"><b>{{ singleForum.title }}</b></h5>
                    <h6 class="card-subtitle mb-2 text-muted"><b>Author: {{ singleForum.user.name }}, Created {{ singleForum.humanTime }}</b></h6>
                    <hr>
                    <p class="card-text" v-html="$options.filters.truncate(singleForum.content)"></p>
                    <div class="card-footer">
                      <ul>
                        <li class="activity forums" @click.prevent="like(singleForum.id)"><a href="#" class="card-link"><i class="material-icons">favorite</i> {{ singleForum.likes }}</a></li>
                        <li v-if="parseInt(singleForum.user.id) == user_id" @click.prevent="fetchAndAddComments(singleForum)" class="activity forums"><a href="#" class="card-link"><i class="material-icons">sms</i> {{ singleForum.comments }}</a></li>
                        <li class="activity forums" @click.prevent="editForum(singleForum)"><a href="#" class="card-link"><i class="material-icons">edit</i> Edit</a></li>
                        <li v-if="singleForum.user.id == user_id" class="activity forums" @click.prevent="deleteForum(singleForum.id)"><a href="#" class="card-link"><i class="material-icons">close</i></a></li>
                      </ul>
                    </div>
                  </div>
                </div>
              </div>
            </template>
          </div>
          <div class="six columns">
            <div class="forum-editor">
              <h5 v-if="type === 'create'"><i class="material-icons">sms</i> Create A Topic...</h5>
              <h5 v-if="type === 'edit'"><i class="material-icons">sms</i> <b>Update {{ forum.title }}</b>...</h5>
              <h5 v-if="type === 'comments'"><i class="material-icons">sms</i> <b> Add A Comment.</b></h5>
              <hr>
              <form v-if="type === 'create'" method="post" @submit.prevent="createForum()">
                <div class="form-group">
                  <label for="forum-title" class="col-md-4 col-form-label text-md-right">Forum Title</label>
                  <div class="col-md-6">
                    <input id="forum-title" type="text" class="form-control" name="text" required  autofocus v-model="forum.title">
                  </div>
                </div>
                <div class="form-group">
                  <label for="forum-content" class="col-md-4 col-form-label text-md-right">Forum Content</label>
                  <div class="col-md-6">
                     <ckeditor v-model="forum.content" required placeholder="Start A Topic..." :config="editorConfig"></ckeditor>
                  </div>
                </div>
                <div class="form-group btn-div mb-0">
                  <div class="col-md-8 offset-md-4">
                    <button type="submit" class="btn btn-primary">
                      Create Topic
                    </button>
                  </div>
                </div>
              </form>
              <form v-if="type === 'edit'" method="post" @submit.prevent="updateForum()">
                <div class="form-group">
                  <label for="forum-title" class="col-md-4 col-form-label text-md-right">Forum Title</label>
                  <div class="col-md-6">
                    <input id="forum-title" type="text" class="form-control" name="text" required  autofocus v-model="forum.title">
                  </div>
                </div>
                <div class="form-group">
                  <label for="forum-content" class="col-md-4 col-form-label text-md-right">Forum Content</label>
                  <div class="col-md-6">
                     <ckeditor v-model="forum.content" required placeholder="Start A Topic..." :config="editorConfig"></ckeditor>
                  </div>
                </div>
                <div class="form-group btn-div mb-0">
                  <div class="col-md-8 offset-md-4">
                    <button type="submit" class="btn btn-primary">
                      Update Topic
                    </button>
                  </div>
                </div>
              </form>
              <form v-if="type === 'comments'" @submit.prevent="addComment()" method="post" >
                <div class="form-group">
                  <label for="forum-content" class="col-md-4 col-form-label text-md-right">Your Comment</label>
                  <div class="col-md-6">
                     <ckeditor v-model="comment" required placeholder="Enter A Comment..." :config="editorConfig"></ckeditor>
                  </div>
                </div>
                <div class="form-group btn-div mb-0">
                  <div class="col-md-8 offset-md-4">
                    <button type="submit" class="btn btn-primary">
                      Comment
                    </button>
                  </div>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>
</template>

<script type="text/javascript">
  import axios from 'axios';
  import swal from 'sweetalert';

  import Nav from '../includes/Nav';

  export default {
    data() {
      return {
        editorConfig: {},
        forum: {
          id: null,
          user: {},
          title: '',
          content: '',
          likes: 0,
          comments: 0,
          timestamp: ''
        },
        type: 'create',
        forums: [],
        comment: '',
        user_id: '',
        comments: [],
        accessToken: '',
      }
    },
    components: {
      Nav
    },
    async mounted() {
      await this.fetchForums();
      this.user_id = parseInt(localStorage.getItem('user-id'));
    },
    filters: {
      truncate(value) {
        // Make sure an element and number of items to truncate is provided
        if (!value) return;

        // Get the inner content of the element
        var content = value.trim();

        // Convert the content into an array of words
        // Remove any words above the limit
        if (value.length > 25) {
          content = content.split(' ').slice(0, 25);

          // Convert the array of words back into a string
          content = content.join(' ') + '.....';

          return content;
        }

        return value;
      },
    },
    methods: {
      async createForum() {
        try {
          if (this.forum.content.trim() == '') {
            swal('Forum Content', 'Sorry, This Field Cannot Be Empty. Please, Write A Story To Continue.', 'error');
            return;
          }

          // create a simple forum..........
          let createdForum = await axios.post(this.$baseUrl + 'forum/create', this.forum, {
            headers: {
              Accept: 'application/json',
              'Content-Type': 'application/json',
              'Authorization': 'Bearer ' + this.accessToken,
              'Cache-Control': 'no-cache'
            }
          });

          if (createdForum.status == 201) {
            swal('Topic Created.', 'Your Topic Has Been Created Successfully', 'success');
            this.forums.unshift(createdForum.data.data);
            return;
          }
        } catch (e) {
          let errorData = e.response.data;
          if (errorData.status == 400) {
            for (const error in errorData.errors) {
              swal(
                `${error} Error: `,
                errorData.errors[error][0],
                'error'
              );
            }
            return;
          }

          swal('Operation Failed', errorData.message, 'error');
          return;
        }
      },
      async updateForum() {
        try {
          if (this.forum.content.trim() == '') {
            swal('Forum Content', 'Sorry, This Field Cannot Be Empty. Please, Write A Story To Continue.', 'error');
            return;
          }

          // create a simple forum..........
          let updatedForum = await axios.post(this.$baseUrl + 'forum/update/' + this.forum.id, this.forum, {
            headers: {
              Accept: 'application/json',
              'Content-Type': 'application/json',
              'Authorization': 'Bearer ' + this.accessToken,
              'Cache-Control': 'no-cache'
            }
          });

          if (updatedForum.status == 200) {
            swal('Topic Updated.', 'Your Topic Has Been Updated Successfully', 'success');
            this.forums = this.forums.map((el) => {
              if (parseInt(el.id) == parseInt(this.forum.id)) {
                el.title = this.forum.title;
                el.content = this.forum.content;
              }

              return el;
            });

            this.type = 'create';

            this.forum.id = null;
            this.forum.title = '';
            this.forum.content = '';
            return;
          }
        } catch (e) {
          let errorData = e.response.data;
          if (errorData.status == 400) {
            for (const error in errorData.errors) {
              swal(
                `${error} Error: `,
                errorData.errors[error][0],
                'error'
              );
            }
            return;
          }

          swal('Operation Failed', errorData.message, 'error');
          return;
        }
      },
      async fetchForums() {
        try {
          let forums = await axios.get(this.$baseUrl + 'forum', {
            headers: {
              Accept: 'application/json',
              'Content-Type': 'application/json',
              'Authorization': 'Bearer ' + this.accessToken,
              'Cache-Control': 'no-cache'
            }
          });

          if (forums.status == 200) {
            this.forums = forums.data.data;
            return;
          }
        } catch (e) {
          this.forums = [];
          return;
        }
      },
      async fetchAndAddComments(forumComment) {
        try {
          let forumAndComment = await axios.get(this.$baseUrl + 'forum/comments/' + forumComment.id, {
            headers: {
              Accept: 'application/json',
              'Content-Type': 'application/json',
              'Authorization': 'Bearer ' + this.accessToken,
              'Cache-Control': 'no-cache'
            }
          });

          if (forumAndComment.status == 200) {
            this.type = 'comments';

            this.forum.id = forumAndComment.data.data.id;
            this.forum.title = forumAndComment.data.data.title;
            this.forum.content = forumAndComment.data.data.content;
            this.forum.likes = forumAndComment.data.data.likes;
            this.forum.comments = forumAndComment.data.data.comments;
            this.forum.timestamp = forumAndComment.data.timestamp;
            this.forum.user = forumAndComment.data.data.user;

            await this.fetchComments(forumComment.id);
            return;
          }
        } catch (e) {
          this.type = 'create';

          this.forum.id = '';
          this.forum.title = '';
          this.forum.content = '';
          this.forum.likes = '';
          this.forum.comments = '';
          this.forum.timestamp = '';

          return;
        }
      },
      async fetchComments(forumId) {
        try {
          let comments = await axios.get(this.$baseUrl + 'comments/' + forumId, {
            headers: {
              Accept: 'application/json',
              'Content-Type': 'application/json',
              'Authorization': 'Bearer ' + this.accessToken,
              'Cache-Control': 'no-cache'
            }
          });

          if (comments.status == 200) {
            this.comments = comments.data.comments;
            return;
          }

          this.comments = [];
          return;
        } catch (e) {
          console.log(e);
          this.comments = [];
          return;
        }
      },
      async editForum(forumToUpdate) {
        this.type = 'edit';

        this.forum.id = forumToUpdate.id;
        this.forum.title = forumToUpdate.title;
        this.forum.content = forumToUpdate.content;
      },
      async addComment() {
        try {
          let comment = await axios.post(this.$baseUrl + 'comment/' + this.forum.id, {
            comment: this.comment
          }, {
            headers: {
              Accept: 'application/json',
              'Content-Type': 'application/json',
              'Authorization': 'Bearer ' + this.accessToken,
              'Cache-Control': 'no-cache'
            }
          });

          if (comment.status == 201) {
            swal('Success', 'Your Comment Has Been Saved Successfully.', 'success');
            this.comments.unshift(comment.data.data);

            this.forum.comments += 1;
            return;
          }

          swal('Operation Failed', 'Oops, An Unexpected Error Occurred And Your Comment Could Not Be Saved.', 'error');
          return;
        } catch (e) {
          let errorData = e.response.data;
          if (errorData.status == 400) {
            for (const error in errorData.errors) {
              swal(
                `${error} Error: `,
                errorData.errors[error][0],
                'error'
              );
            }
            return;
          }

          swal('Operation Failed', errorData.message, 'error');
          return;
        }
      },
      async like(forumId) {
        try {
          let liked = await axios.get(this.$baseUrl + 'forum/like/' + forumId, {
            headers: {
              Accept: 'application/json',
              'Content-Type': 'application/json',
              'Authorization': 'Bearer ' + this.accessToken,
              'Cache-Control': 'no-cache'
            }
          });

          if (liked.status == 200) {
            this.forums = this.forums.map((el) => {
              if (parseInt(el.id) == parseInt(forumId)) {
                el.likes += 1;
              }
              return el;
            });

            swal({
              icon: 'success'
            });
            return;
          }
        } catch (e) {
          this.forums = [];
          return;
        }
      },
      async deleteForum(forumId) {
        try {
          let deletedForum = await axios.delete(this.$baseUrl + 'forum/delete/' + forumId, {
            headers: {
              Accept: 'application/json',
              'Content-Type': 'application/json',
              'Authorization': 'Bearer ' + this.accessToken,
              'Cache-Control': 'no-cache'
            }
          });

          if (deletedForum.status == 200) {
            swal('success', 'The Selected Forum Has Been Deleted Successfully!', 'success');
            this.forums = this.forums.filter((el) => {
              if (el.id == forumId) return false;
            })
            return;
          }

          swal('Operation Failed', 'The Selected Forum Could Not Be Deleted Successfully. Please, Try Again Later!', 'error');
          return;
        } catch (e) {
          let errorData = e.response.data;
          if (errorData.status == 400) {
            for (const error in errorData.errors) {
              swal(
                `${error} Error: `,
                errorData.errors[error][0],
                'error'
              );
            }
            return;
          }

          swal('Operation Failed', errorData.message, 'error');
          return;
        }
      }
    },
    async created() {
      try {
        let userSecret = localStorage.getItem('access-token');
        this.accessToken = userSecret;

        // check if the user access token is set.....
        if (userSecret.trim() !== '') {
          let user = await axios.get(this.$baseUrl + 'user',  {
            headers: {
              Accept: 'application/json',
              'Authorization': 'Bearer ' + userSecret,
              'Cache-Control': 'no-cache'
            }
          });

          // check for a status code other than 200.....
          if (user.status != 200) {
            swal('Unauthorized Access', 'Sorry, You Do Not Have Enough Priviledge To Access This Page.', 'error');
            setTimeout(() => {
              this.$router.push({
                name: 'Welcome'
              });
            }, 1000);
            return;
          }

        }

        if (userSecret.trim() == '') {
          swal('Unauthorized Access', 'Sorry, You Do Not Have Enough Priviledge To Access This Page.', 'error');
          setTimeout(() => {
            this.$router.push({
              name: 'Welcome'
            });
          }, 1000);
          return;
        }
      } catch (e) {
        swal('Unauthorized Access', 'Sorry, You Do Not Have Enough Priviledge To Access This Page.', 'error');
        setTimeout(() => {
          this.$router.push({
            name: 'Welcome'
          });
        }, 1000);
        return;
      }
    }
  }
</script>

<style scoped>
.form-control {
  width: 100% !important;
}
.forums-column {
  padding-right: 100px;
  border-right: 1px solid grey;
}
.forum-editor, .forums {
  padding: 10px 10px;
}
.app_description {
  margin-left: -1%;
}
.btn-div {
  margin-top: 15px;
}
.forums {
  border-radius: 4px;
  margin: 20px 0;
}
.card {
  cursor: pointer;
}
ul {
  list-style: none;
  display: inline-flex;
}
ul li {
  margin: 0 5px !important;
}
.activity {
  padding: 5px 5px !important;
}
.material-icons {
  vertical-align: middle;
}
a {
  text-decoration: none;
}
.activity:nth-of-type(1) a,
.activity:nth-of-type(4) a {
  color: red;
  font-weight: 600;
}
</style>
