<template>
  <div>
    <sql-editor
      v-bind:placeholder="sql"
      @change="setSQL"></sql-editor>
    <div class="mt-3">
      <async-button
        defaultText="Run SQL"
        pendingText="Processing..."
        v-bind:processing="isProcessing"
        @click="executeQuery">
      </async-button>
      <button
        type="button"
        class="btn btn-secondary ml-2"
        @click="share">Share</button>
    </div>
    <div class="mt-4">
      <error v-if="error">
        Looks like you have an error in your SQL.
      </error>
      <data-table
        v-else
        v-bind:headers="headers"
        v-bind:rows="rows"></data-table>
    </div>
  </div>
</template>

<script>
  const { keys, values } = Object;

  export default {
    data() {
      let sql;
      let { search } = window.location;

      if (search.includes('?q=')) {
        sql = window.atob(search.replace('?q=', ''));
      } else {
        sql = 'SELECT * FROM tracks LIMIT 10';
      }

      return {
        isProcessing: false,
        sql,
        error: false,
        headers: null,
        rows: null
      };
    },
    methods: {
      setSQL(sql) {
        this.sql = sql;
      },
      share() {
        let hashedSQL = window.btoa(this.sql);
        window.location = `/?q=${hashedSQL}`;
      },
      executeQuery() {
        this.isProcessing = true;

        window.axios.post('/query', {
          sql: this.sql
        })
        .then(({ data: results }) => {
          this.error = false;

          if (results.length) {
            this.headers = keys(results[0]);
            this.rows = results.map((record) => values(record));
          } else {
            this.headers = [];
            this.rows = [];
          }
        }, (error) => {
          this.error = true;
        })
        .finally(() => {
          this.isProcessing = false;
        });
      }
    }
  }
</script>
